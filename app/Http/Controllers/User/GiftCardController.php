<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\GiftCardSell;
use App\Models\GiftCardService;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Facades\App\Services\BasicService;

class GiftCardController extends Controller
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

    public function giftCardOrder()
    {
        $data['giftCardOrders'] = $this->user->giftCard()->wherePayment_status(1)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.gift_card.index', $data);
    }

    public function giftCardSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $giftCardOrders = GiftCardSell::where('user_id', $this->user->id)->with('user')
            ->when(@$search['transaction_id'], function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $giftCardOrders = $giftCardOrders->appends($search);


        return view($this->theme . 'user.gift_card.index', compact('giftCardOrders'));

    }

    public function giftCardPayment(Request $request)
    {

        $this->validate($request, [
            'gateway' => ['required', 'numeric'],
            'service' => ['required', 'numeric']
        ], [
            'gateway.required' => 'Please select a payment method',
            'service.required' => 'Please select a recharge option'
        ]);

        $service = GiftCardService::with('giftCard')->whereStatus(1)
            ->whereHas('giftCard', function ($query) {
                $query->where('status', 1);
            })->findOrFail($request->service);

        $serviceGiftCard = $service->giftCard;

        $gate = Gateway::where('status', 1)->findOrFail($request->gateway);
        $discount = 0;
        if ($serviceGiftCard->discount_status == 1) {
            if ($serviceGiftCard->discount_type == 0) {
                $discount = $serviceGiftCard->discount_amount; // fixed Discount
            } else {
                $discount = ($service->price * $serviceGiftCard->discount_amount) / 100; // percent Discount
            }
        }

        $user = $this->user;

        $reqAmount = $service->price - $discount;
        $charge = getAmount($gate->fixed_charge + ($reqAmount * $gate->percentage_charge / 100));
        $payable = getAmount($reqAmount + $charge);
        $final_amo = getAmount($payable * $gate->convention_rate);

        $giftCardSell = new GiftCardSell();
        $giftCardSell->user_id = $user->id;
        $giftCardSell->gift_card_service_id = $service->id;
        $giftCardSell->gift_card_id = $serviceGiftCard->id;
        $giftCardSell->price = $reqAmount;
        $giftCardSell->discount = $discount;
        $giftCardSell->transaction = strRandom();
        $collection = collect($request);

        $giftCardSell->save();

        $fund = PaymentController::newFund($request, $user, $gate, $charge, $final_amo, $reqAmount);
        $giftCardSell->fundable()->save($fund);
        session()->put('track', $fund['transaction']);


        return redirect()->route('user.addFund.confirm');

    }

}
