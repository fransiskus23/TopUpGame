<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\VoucherService;
use App\Models\VoucherSell;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Facades\App\Services\BasicService;

class VoucherController extends Controller
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

    public function voucherOrder()
    {
        $data['voucherOrders'] = $this->user->voucher()->wherePayment_status(1)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.voucher.index', $data);
    }

    public function voucherSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $voucherOrders = VoucherSell::where('user_id', $this->user->id)->with('user')
            ->when(@$search['transaction_id'], function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $voucherOrders = $voucherOrders->appends($search);


        return view($this->theme . 'user.voucher.index', compact('voucherOrders'));

    }

    public function voucherPayment(Request $request)
    {

        $this->validate($request, [
            'gateway' => ['required', 'numeric'],
            'service' => ['required', 'numeric']
        ], [
            'gateway.required' => 'Please select a payment method',
            'service.required' => 'Please select a recharge option'
        ]);

        $service = VoucherService::with('voucher')->whereStatus(1)
            ->whereHas('voucher', function ($query) {
                $query->where('status', 1);
            })->findOrFail($request->service);

        $serviceVoucher = $service->voucher;

        $gate = Gateway::where('status', 1)->findOrFail($request->gateway);
        $discount = 0;
        if ($serviceVoucher->discount_status == 1) {
            if ($serviceVoucher->discount_type == 0) {
                $discount = $serviceVoucher->discount_amount; // fixed Discount
            } else {
                $discount = ($service->price * $serviceVoucher->discount_amount) / 100; // percent Discount
            }
        }

        $user = $this->user;

        $reqAmount = $service->price - $discount;
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge);
        $final_amo = getAmount($payable * $gate->convention_rate);

        $voucherSell = new VoucherSell();
        $voucherSell->user_id = $user->id;
        $voucherSell->voucher_service_id = $service->id;
        $voucherSell->voucher_id = $serviceVoucher->id;
        $voucherSell->price = $reqAmount;
        $voucherSell->discount = $discount;
        $voucherSell->transaction = strRandom();
        $collection = collect($request);

        $voucherSell->save();

        $fund = PaymentController::newFund($request, $user, $gate, $charge, $final_amo, $reqAmount);
        $voucherSell->fundable()->save($fund);
        session()->put('track', $fund['transaction']);


        return redirect()->route('user.addFund.confirm');

    }
}
