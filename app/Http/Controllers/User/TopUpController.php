<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\CategoryService;
use App\Models\Gateway;
use App\Models\TopUpSell;
use Illuminate\Http\Request;
use Facades\App\Services\BasicService;

class TopUpController extends Controller
{
    use Upload, Notify;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function topUpOrder()
    {
        $data['topUpOrders'] = $this->user->topUp()->wherePayment_status(1)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.top_up.index', $data);
    }

    public function topUpSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $topUpOrders = TopUpSell::where('user_id', $this->user->id)->with('user')
            ->when(@$search['transaction_id'], function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $topUpOrders = $topUpOrders->appends($search);


        return view($this->theme . 'user.top_up.index', compact('topUpOrders'));

    }

    public function topUpPayment(Request $request)
    {
        $this->validate($request, [
            'gateway' => ['required', 'numeric'],
            'service' => ['required', 'numeric']
        ], [
            'gateway.required' => 'Please select a payment method',
            'service.required' => 'Please select a recharge option'
        ]);

        $service = CategoryService::with('category')->whereStatus(1)
            ->whereHas('category', function ($query) {
                $query->where('status', 1);
            })->findOrFail($request->service);

        $serviceCategory = $service->category;

        $rules = [];
        $inputField = [];
        if ($serviceCategory->form_field != null) {
            foreach ($serviceCategory->form_field as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }
        $this->validate($request, $rules);

        $gate = Gateway::where('status', 1)->findOrFail($request->gateway);
        $serviceCategory = $service->category;
        $discount = 0;
        if ($serviceCategory->discount_status == 1) {
            if ($serviceCategory->discount_type == 0) {
                $discount = $serviceCategory->discount_amount; // fixed Discount
            } else {
                $discount = ($service->price * $serviceCategory->discount_amount) / 100; // percent Discount
            }
        }

        $user = $this->user;

        $reqAmount = $service->price - $discount;
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge);
        $final_amo = getAmount($payable * $gate->convention_rate);

        $topUpSell = new TopUpSell();
        $topUpSell->user_id = $user->id;
        $topUpSell->category_service_id = $service->id;
        $topUpSell->category_id = $serviceCategory->id;
        $topUpSell->price = $reqAmount;
        $topUpSell->discount = $discount;
        $topUpSell->transaction = strRandom();
        $collection = collect($request);
        $reqField = [];
        if ($serviceCategory->form_field != null) {
            foreach ($collection as $k => $v) {
                foreach ($serviceCategory->form_field as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {

                                try {
                                    $image = $request->file($inKey);
                                    $location = config('location.toUpSellLog.path');
                                    $filename = $this->uploadImage($image, $location);;
                                    $reqField[$inKey] = [
                                        'field_name' => $filename,
                                        'type' => $inVal->type,
                                    ];

                                } catch (\Exception $exp) {
                                    return back()->with('error', 'Image could not be uploaded.')->withInput();
                                }

                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $topUpSell['information'] = $reqField;
        } else {
            $topUpSell['information'] = null;
        }
        $topUpSell->save();

        $fund = PaymentController::newFund($request, $user, $gate, $charge, $final_amo, $reqAmount);
        $topUpSell->fundable()->save($fund);
        session()->put('track', $fund['transaction']);

        return redirect()->route('user.addFund.confirm');
    }
}
