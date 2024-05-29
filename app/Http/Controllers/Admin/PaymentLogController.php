<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Models\Fund;
use App\Models\SellPost;
use App\Models\SellPostOffer;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;

class PaymentLogController extends Controller
{
    use Notify;

    public function index()
    {
        $page_title = "Payment Logs";
        $funds = Fund::where('status', '!=', 0)->orderBy('id', 'DESC')->with('user', 'gateway')->paginate(config('basic.paginate'));
        return view('admin.payment.logs', compact('funds', 'page_title'));
    }

    public function pending()
    {
        $page_title = "Payment Pending";
        $funds = Fund::where('status', 2)->where('gateway_id', '>', 999)->orderBy('id', 'DESC')->with('user', 'gateway')->paginate(config('basic.paginate'));
        return view('admin.payment.logs', compact('funds', 'page_title'));
    }


    public function search(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $funds = Fund::when(isset($search['name']), function ($query) use ($search) {
            return $query->where('transaction', 'LIKE', $search['name'])
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['name']}%");
                });
        })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when($search['status'] != -1, function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->where('status', '!=', 0)
            ->with('user', 'gateway')
            ->paginate(config('basic.paginate'));
        $funds->appends($search);
        $page_title = "Search Payment Logs";
        return view('admin.payment.logs', compact('funds', 'page_title'));
    }


    public function action(Request $request, $id)
    {

        $this->validate($request, [
            'id' => 'required',
            'status' => ['required', Rule::in(['1', '3'])],
        ]);
        $data = Fund::where('id', $request->id)->whereIn('status', [2])->with('user', 'gateway')->firstOrFail();
        $basic = (object)config('basic');

        $sellPost=SellPost::whereId($data->fundable->sell_post_id)->firstOrFail();

        $authorUser=$sellPost->user;
        $user = $data->user;
        $fundable = $data->fundable;
        $gateway = $data->gateway;


        $req = Purify::clean($request->all());

        if ($request->status == '1') {
            $data->status = 1;
            $data->feedback = @$req['feedback'];
            $data->update();


            if ($data->fundable_type == 'App\Models\TopUpSell') {
                $fundable->payment_status = 1;
                $fundable->transaction = $data->transaction;
                $fundable->save();


                $msg = [
                    'amount' => getAmount($data->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => '#',
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user,'TOP_UP_PAYMENT_COMPLETE', $msg, $action);


                $this->sendMailSms($user, 'TOP_UP_PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($data->amount),
                    'currency' => $basic->currency,
                    'transaction' => $data->transaction,
                    'game_name' => (optional($data->fundable)->category->details->name) ?? 'game top up',
                    'game_service' => optional($data->fundable)->service->name,
                ]);

            }

            if ($data->fundable_type == 'App\Models\VoucherSell') {

                $lackingCode = 0;
                $qty = $data->fundable->qty;
                $vCodes = $data->fundable->service->voucherActiveCodes->take($qty);
                if ($qty > count($vCodes)) {
                    $lackingCode = $qty - count($vCodes);
                }
                $orderCode = [];
                foreach ($vCodes as $vCode) {
                    array_push($orderCode, $vCode->code);
                    $vCode->status = 2;
                    $vCode->save();
                }


                $fundable->payment_status = 1;
                $fundable->transaction = $data->transaction;
                $fundable->stock_short = $lackingCode;
                $fundable->code = $orderCode;
                $fundable->save();



                $msg = [
                    'amount' => getAmount($data->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => '#',
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user,'VOUCHER_PAYMENT_COMPLETE', $msg, $action);



                $this->sendMailSms($user, 'VOUCHER_PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($data->amount),
                    'currency' => $basic->currency,
                    'transaction' => $data->transaction,
                    'voucher_name' => @optional($data->fundable)->voucher->details->name ?? 'Voucher',
                    'voucher_service' => optional($data->fundable)->service->name,
                    'voucher_code' => @implode(',',$orderCode),
                ]);

            }

            if ($data->fundable_type == 'App\Models\GiftCardSell') {

                $lackingCode = 0;
                $qty = $data->fundable->qty;
                $vCodes = $data->fundable->service->giftCardActiveCodes->take($qty);
                if ($qty > count($vCodes)) {
                    $lackingCode = $qty - count($vCodes);
                }
                $orderCode = [];
                foreach ($vCodes as $vCode) {
                    array_push($orderCode, $vCode->code);
                    $vCode->status = 2;
                    $vCode->save();
                }


                $fundable->payment_status = 1;
                $fundable->transaction = $data->transaction;
                $fundable->stock_short = $lackingCode;
                $fundable->code = $orderCode;
                $fundable->save();



                $msg = [
                    'amount' => getAmount($data->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => '#',
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user,'GIFT_CARD_PAYMENT_COMPLETE', $msg, $action);



                $this->sendMailSms($user, 'GIFT_CARD_PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($data->amount),
                    'currency' => $basic->currency,
                    'transaction' => $data->transaction,
                    'voucher_name' => @optional($data->fundable)->voucher->details->name ?? 'Voucher',
                    'voucher_service' => optional($data->fundable)->service->name,
                    'voucher_code' => @implode(',',$orderCode),
                ]);

            }
            if ($data->fundable_type == 'App\Models\SellPostPayment') {

                $fundable = $data->fundable;
                $user = $data->user;

                $fundable->payment_status = 1;
                $fundable->transaction = $data->transaction;
                $fundable->save();


                $sellPost = $fundable->sellPost;
                $sellPost->payment_status = 1;
                $sellPost->lock_for = $data->user_id;
                $sellPost->save();


                $checkMyProposal = SellPostOffer::where([
                    'user_id' => $data->user_id,
                    'sell_post_id' => $sellPost->id,
                    'status' => 1,
                    'payment_status' => 0,
                ])->first();
                if ($checkMyProposal) {
                    $checkMyProposal->payment_status = 1;
                    $checkMyProposal->save();
                }


                SellPostOffer::where('user_id', '!=', $data->user_id)->where('sell_post_id', $sellPost->id)->get()->map(function ($item) {
                    $item->uuid = null;
                    $item->save();
                });

                $msg = [
                    'amount' => getAmount($data->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => '#',
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user,'SELL_POST_PAYMENT_COMPLETE', $msg, $action);
                $this->userPushNotification($authorUser,'SELL_POST_PAYMENT_COMPLETE_AUTHOR', $msg, $action);

                $this->sendMailSms($user, 'SELL_POST_PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($data->amount),
                    'currency' => $basic->currency,
                    'transaction' => $data->transaction,
                    'sell_post_title' => (optional($data->fundable)->sellPost->title) ?? 'sell post',
                ]);

                $this->sendMailSms($authorUser, 'SELL_POST_PAYMENT_COMPLETE_AUTHOR', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($data->amount),
                    'currency' => $basic->currency,
                    'transaction' => $data->transaction,
                    'sell_post_title' => (optional($data->fundable)->sellPost->title) ?? 'sell post',
                ]);
            }

            session()->flash('success', 'Approve Successfully');
            return back();

        } elseif ($request->status == '3') {

            $data->status = 3;
            $data->feedback = $request->feedback;
            $data->update();

            if ($data->fundable_type == 'App\Models\TopUpSell') {
                $fundable->payment_status = 2;
                $fundable->transaction = $data->transaction;
                $fundable->save();
            }

            if ($data->fundable_type == 'App\Models\VoucherSell') {
                $fundable->payment_status = 2;
                $fundable->transaction = $data->transaction;
                $fundable->save();
            }

            if ($data->fundable_type == 'App\Models\GiftCardSell') {
                $fundable->payment_status = 2;
                $fundable->transaction = $data->transaction;
                $fundable->save();
            }

            if ($data->fundable_type == 'App\Models\SellPostPayment') {
                $sellPost->payment_status=2;
                $sellPost->lock_for=null;
                $sellPost->lock_at=null;
                $sellPost->payment_lock=0;
                $sellPost->save();

                $fundable->payment_status = 2;
                $fundable->transaction = $data->transaction;
                $fundable->save();
            }


            $this->sendMailSms($user, $type = 'PAYMENT_REJECTED', [
                'amount' => getAmount($data->amount),
                'currency' => $basic->currency,
                'method' => optional($data->gateway)->name,
                'transaction' => $data->transaction,
                'feedback' => $data->feedback
            ]);

            $msg = [
                'amount' => getAmount($data->amount),
                'currency' => $basic->currency,
                'feedback' => $data->feedback,
            ];
            $action = [
                "link" => '#',
                "icon" => "fas fa-money-bill-alt text-white"
            ];
            $this->userPushNotification($user, 'PAYMENT_REJECTED', $msg, $action);

            session()->flash('success', 'Reject Successfully');
            return back();
        }
    }
}
