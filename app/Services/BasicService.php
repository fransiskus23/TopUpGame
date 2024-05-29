<?php

namespace App\Services;

use App\Http\Traits\Notify;
use App\Models\SellPost;
use App\Models\SellPostOffer;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Image;

class BasicService
{
    use Notify;

    public function validateImage(object $getImage, string $path)
    {
        if ($getImage->getClientOriginalExtension() == 'jpg' or $getImage->getClientOriginalName() == 'jpeg' or $getImage->getClientOriginalName() == 'png') {
            $image = uniqid() . '.' . $getImage->getClientOriginalExtension();
        } else {
            $image = uniqid() . '.jpg';
        }
        Image::make($getImage->getRealPath())->resize(300, 250)->save($path . $image);
        return $image;
    }

    public function validateDate(string $date)
    {
        if (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}$/", $date)) {
            return true;
        } else {
            return false;
        }
    }

    public function validateKeyword(string $search, string $keyword)
    {
        return preg_match('~' . preg_quote($search, '~') . '~i', $keyword);
    }

    public function cryptoQR($wallet, $amount, $crypto = null)
    {

        $varb = $wallet . "?amount=" . $amount;
        return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8";
    }

    public function preparePaymentUpgradation($order)
    {
        $basic = (object)config('basic');
        $gateway = $order->gateway;

        $sellPost = SellPost::whereId($order->fundable->sell_post_id)->firstOrFail();
        $authorUser = $sellPost->user;

        if ($order->status == 0) {
            $order['status'] = 1;
            $order->update();
            $fundable = $order->fundable;
            $user = $order->user;


            $this->makeTransaction($user, getAmount($order->amount), getAmount($order->charge), '+', $order->transaction, 'Payment Via ' . $gateway->name);

            if ($order->fundable_type == 'App\Models\TopUpSell') {
                $fundable->payment_status = 1;
                $fundable->transaction = $order->transaction;
                $fundable->save();
                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => route('admin.user.fundLog', $user->id),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->adminPushNotification('TOP_UP_PAYMENT_COMPLETE_ADMIN', $msg, $action);

                $msg = [
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => '#',
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user, 'TOP_UP_PAYMENT_COMPLETE', $msg, $action);


                $this->sendMailSms($user, 'TOP_UP_PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'transaction' => $order->transaction,
                    'game_name' => (optional($order->fundable)->category->details->name) ?? 'game top up',
                    'game_service' => optional($order->fundable)->service->name,
                ]);

            }

            if ($order->fundable_type == 'App\Models\VoucherSell') {
                $lackingCode = 0;
                $qty = $order->fundable->qty;
                $vCodes = $order->fundable->service->voucherActiveCodes->take($qty);
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
                $fundable->transaction = $order->transaction;
                $fundable->stock_short = $lackingCode;
                $fundable->code = $orderCode;
                $fundable->save();


                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => route('admin.user.fundLog', $user->id),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->adminPushNotification('VOUCHER_PAYMENT_COMPLETE_ADMIN', $msg, $action);

                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => '#',
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user, 'VOUCHER_PAYMENT_COMPLETE', $msg, $action);


                $this->sendMailSms($user, 'VOUCHER_PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'transaction' => $order->transaction,
                    'voucher_name' => @optional($order->fundable)->voucher->details->name ?? 'Voucher',
                    'voucher_service' => optional($order->fundable)->service->name,
                    'voucher_code' => @implode(',', $orderCode),
                ]);
            }

            if ($order->fundable_type == 'App\Models\GiftCardSell') {
                $lackingCode = 0;
                $qty = $order->fundable->qty;
                $vCodes = $order->fundable->service->giftCardActiveCodes->take($qty);
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
                $fundable->transaction = $order->transaction;
                $fundable->stock_short = $lackingCode;
                $fundable->code = $orderCode;
                $fundable->save();


                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => route('admin.user.fundLog', $user->id),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->adminPushNotification('GIFT_CARD_PAYMENT_COMPLETE_ADMIN', $msg, $action);

                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => '#',
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user, 'GIFT_CARD_PAYMENT_COMPLETE', $msg, $action);


                $this->sendMailSms($user, 'GIFT_CARD_PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'transaction' => $order->transaction,
                    'card_name' => @optional($order->fundable)->voucher->details->name ?? 'Voucher',
                    'card_service' => optional($order->fundable)->service->name,
                    'card_code' => @implode(',', $orderCode),
                ]);
            }


            if ($order->fundable_type == 'App\Models\SellPostPayment') {
                $fundable->payment_status = 1;
                $fundable->transaction = $order->transaction;
                $fundable->save();

                $sellPost = $fundable->sellPost;
                $sellPost->payment_status = 1;
                $sellPost->lock_for = $order->user_id;
                $sellPost->save();


                $checkMyProposal = SellPostOffer::where([
                    'user_id' => $order->user_id,
                    'sell_post_id' => $sellPost->id,
                    'status' => 1,
                    'payment_status' => 0,
                ])->first();
                if ($checkMyProposal) {
                    $checkMyProposal->payment_status = 1;
                    $checkMyProposal->save();
                }

                SellPostOffer::where('user_id', '!=', $order->user_id)->where('sell_post_id', $sellPost->id)->get()->map(function ($item) {
                    $item->uuid = null;
                    $item->save();
                });

                $msg = [
                    'username' => $user->username,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => route('admin.user.fundLog', $user->id),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->adminPushNotification('SELL_POST_PAYMENT_COMPLETE_ADMIN', $msg, $action);

                $msg = [
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'gateway' => $gateway->name
                ];
                $action = [
                    "link" => '#',
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userPushNotification($user, 'SELL_POST_PAYMENT_COMPLETE', $msg, $action);
                $this->userPushNotification($authorUser, 'SELL_POST_PAYMENT_COMPLETE_AUTHOR', $msg, $action);

                $this->sendMailSms($user, 'SELL_POST_PAYMENT_COMPLETE', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'transaction' => $order->transaction,
                    'sell_post_title' => (optional($order->fundable)->sellPost->title) ?? 'sell post',
                ]);

                $this->sendMailSms($authorUser, 'SELL_POST_PAYMENT_COMPLETE_AUTHOR', [
                    'gateway_name' => $gateway->name,
                    'amount' => getAmount($order->amount),
                    'currency' => $basic->currency,
                    'transaction' => $order->transaction,
                    'sell_post_title' => (optional($order->fundable)->sellPost->title) ?? 'sell post',
                ]);

            }


        }
        session()->forget('amount');

    }


    public function setBonus($user, $amount, $commissionType = '')
    {


    }


    /**
     * @param $user
     * @param $amount
     * @param $charge
     * @param $trx_type
     * @param $balance_type
     * @param $trx_id
     * @param $remarks
     */
    public function makeTransaction($user, $amount, $charge, $trx_type = null, $trx_id, $remarks = null): void
    {
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = getAmount($amount);
        $transaction->charge = $charge;
        $transaction->trx_type = $trx_type;
        $transaction->trx_id = $trx_id;
        $transaction->remarks = $remarks;
        $transaction->save();
    }


}
