<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\SellPost;
use App\Models\GiftCardSell;
use App\Models\SellPostCategory;
use App\Models\SellPostPayment;
use App\Models\Transaction;
use App\Models\TopUpSell;
use App\Models\VoucherSell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    use Notify;
    public function transaction()
    {
        $transaction = Transaction::with('user')->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view('admin.transaction.index', compact('transaction'));
    }

    public function transactionSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $transaction = Transaction::with('user')->orderBy('id', 'DESC')
            ->when(isset($search['transaction_id']), function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(isset($search['user_name']), function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['user_name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['user_name']}%");
                });
            })
            ->when(isset($search['remark']), function ($query) use ($search) {
                return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $transaction =  $transaction->appends($search);
        return view('admin.transaction.index', compact('transaction'));
    }

    public function topUpSellTran($userId=null)
    {
        if($userId != null){
            $data['topUpSell'] = TopUpSell::with(['user','category','service'])
                ->whereHas('category')
                ->whereHas('service')
                ->whereHas('user')
                ->wherePayment_status(1)->where('user_id',$userId)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
            return view('admin.sell_summary.topUpSell',$data);
        } else{
            $data['topUpSell'] = TopUpSell::with(['user','category','service'])
                ->whereHas('category')
                ->whereHas('service')
                ->whereHas('user')
                ->wherePayment_status(1)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        }
        return view('admin.sell_summary.topUpSell',$data);
    }

    public function topUpSellSearch(Request $request)
    {

        if($request->top_status==0)
        {
            $top_status=[0,1];
        }
        elseif ($request->top_status==1)
        {
            $top_status=[1];
        }
        elseif ($request->top_status==2)
        {
            $top_status=[0];
        }


        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $topUpSell = TopUpSell::with(['user','category','service'])
            ->whereHas('category')
            ->whereHas('service')
            ->whereHas('user')
            ->orderBy('id', 'DESC')
            ->when(isset($search['transaction_id']), function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(isset($search['user_name']), function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['user_name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['user_name']}%");
                });
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })->whereIn('status',$top_status)->wherePayment_status(1)
            ->paginate(config('basic.paginate'));

        $topUpSell =  $topUpSell->appends($search);
        return view('admin.sell_summary.topUpSell', compact('topUpSell'));
    }

    public function topUpSellAction(Request $request,$id)
    {
        $topUpSell = TopUpSell::with(['user','service','category'])->where(['status'=>0, 'payment_status'=>1])->findOrFail($id);
        $topUpSell->status = 1;
        $topUpSell->save();

        $user= $topUpSell->user;
        $msg = [
            'service' => optional($topUpSell->service)->name,
            'transaction' => $topUpSell->transaction
        ];
        $action = [
            "link" => '#',
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $this->userPushNotification('TOP_UP_COMPLETE', $msg, $action);

        $this->sendMailSms($user, 'TOP_UP_COMPLETE', [
            'transaction' => $topUpSell->transaction,
            'name' => (optional($topUpSell->category)->details->name) ?? 'game top up',
            'service' => optional($topUpSell->service)->name,
        ]);

        session()->flash('success','Update Successfully');
        return back();
    }


    public function voucherSellTran($userId=null)
    {
        if($userId != null){
            $data['voucherSell'] = VoucherSell::with('user')->wherePayment_status(1)->where('user_id',$userId)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
            return view('admin.sell_summary.voucherSell',$data);
        } else{
            $data['voucherSell'] = VoucherSell::with('user')->wherePayment_status(1)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        }
        return view('admin.sell_summary.voucherSell',$data);
    }

    public function voucherSellSearch(Request $request)
    {
        if($request->voucher_status==0)
        {
            $voucher_status=[0,1];
        }
        elseif ($request->voucher_status==1)
        {
            $voucher_status=[1];
        }
        elseif ($request->voucher_status==2)
        {
            $voucher_status=[0];
        }

        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $voucherSell = VoucherSell::with('user')->orderBy('id', 'DESC')
            ->when(isset($search['transaction_id']), function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(isset($search['user_name']), function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['user_name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['user_name']}%");
                });
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })->whereIn('status',$voucher_status)->wherePayment_status(1)
            ->paginate(config('basic.paginate'));
        $voucherSell =  $voucherSell->appends($search);
        return view('admin.sell_summary.voucherSell', compact('voucherSell'));
    }

    public function voucherSellAction(Request $request,$id)
    {
        $voucherSell = VoucherSell::with(['user','service','voucher'])->where(['status'=>0, 'payment_status'=>1])->findOrFail($id);
        $voucherSell->status = 1;
        $voucherSell->save();

        $user= $voucherSell->user;
        $msg = [
            'service' => optional($voucherSell->service)->name,
            'transaction' => $voucherSell->transaction
        ];
        $action = [
            "link" => '#',
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $this->userPushNotification($user,'VOUCHER_COMPLETE', $msg, $action);

        $this->sendMailSms($user, 'VOUCHER_COMPLETE', [
            'transaction' => $voucherSell->transaction,
            'name' => (optional($voucherSell->voucher)->details->name) ?? 'voucher',
            'service' => optional($voucherSell->service)->name,
        ]);

        session()->flash('success','Update Successfully');
        return back();
    }

    public function giftCardSellTran($userId=null)
    {
        if($userId != null){
            $data['giftCardSell'] = GiftCardSell::with('user')->wherePayment_status(1)->where('user_id',$userId)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        } else{
            $data['giftCardSell'] = GiftCardSell::with('user')->wherePayment_status(1)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        }
        return view('admin.sell_summary.giftCardSell',$data);
    }

    public function giftCardSellSearch(Request $request)
    {
        if($request->giftCard_status==0)
        {
            $giftCard_status=[0,1];
        }
        elseif ($request->giftCard_status==1)
        {
            $giftCard_status=[1];
        }
        elseif ($request->giftCard_status==2)
        {
            $giftCard_status=[0];
        }

        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $giftCardSell = GiftCardSell::with('user')->orderBy('id', 'DESC')
            ->when(isset($search['transaction_id']), function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(isset($search['user_name']), function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['user_name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['user_name']}%");
                });
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })->whereIn('status',$giftCard_status)->wherePayment_status(1)
            ->paginate(config('basic.paginate'));
        $giftCardSell =  $giftCardSell->appends($search);
        return view('admin.sell_summary.giftCardSell', compact('giftCardSell'));
    }

    public function giftCardSellAction(Request $request,$id,$value)
    {
        $giftCardSell = GiftCardSell::with(['user','service','giftCard'])->where(['status'=>0, 'payment_status'=>1])->findOrFail($id);
        $giftCardSell->status = 1;
        $giftCardSell->save();

        $user= $giftCardSell->user;
        $msg = [
            'service' => optional($giftCardSell->service)->name,
            'transaction' => $giftCardSell->transaction
        ];
        $action = [
            "link" => '#',
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $this->userPushNotification($user,'VOUCHER_COMPLETE', $msg, $action);

        $this->sendMailSms($user, 'VOUCHER_COMPLETE', [
            'transaction' => $giftCardSell->transaction,
            'name' => (optional($giftCardSell->giftCard)->details->name) ?? 'gift-card',
            'service' => optional($giftCardSell->service)->name,
        ]);

        session()->flash('success','Update Successfully');
        return back();
    }

    public function postSellTran()
    {
        $data['title'] = 'Sold Posts';
        $data['postSell'] = SellPostPayment::with(['user','sellPost','sellPost.user'])->wherePayment_status(1)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view('admin.sell_summary.postSell',$data);
    }

    public function postSellSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $postSell = SellPostPayment::with(['user'])
            ->whereHas('user')
            ->orderBy('id', 'DESC')
            ->when(isset($search['transaction_id']), function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(isset($search['user_name']), function ($query) use ($search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['user_name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['user_name']}%");
                });
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })->wherePayment_status(1)
            ->paginate(config('basic.paginate'));
        $postSell =  $postSell->appends($search);

        $data['title'] = 'Sold Post';
        $data['postSell'] = $postSell;
        return view('admin.sell_summary.postSell', $data);
    }

    public function paymentRelease()
    {
        $data['title'] = 'Release Payment';
        $data['postSell'] = SellPostPayment::with(['user','sellPost'])->where('payment_status', 1)->where('payment_release', 1)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view('admin.sell_summary.postSell',$data);
    }
    public function paymentUpcoming()
    {
        $data['title'] = 'Upcoming Payment';
        $data['postSell'] = SellPostPayment::with(['user','sellPost','sellPost.user'])->where('payment_status', 1)->where('payment_release', 0)->orderBy('id', 'DESC')->paginate(config('basic.paginate'));
        return view('admin.sell_summary.postSell',$data);
    }

    public function paymentHold(Request $request){
        $hold=SellPostPayment::findOrFail($request->id);
        $hold->payment_release=2;
        $hold->save();

        session()->flash('success','Update Successfully');
        return back();
    }

    public function paymentUnhold(Request $request)
    {
        $hold=SellPostPayment::findOrFail($request->id);
        $hold->payment_release=0;
        $hold->save();

        session()->flash('success','Update Successfully');
        return back();
    }

    public function holdMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You did not select any row.');
            return response()->json(['error' => 1]);
        } else {
            SellPostPayment::whereIn('id', $request->strIds)->where('payment_release', '!=',1)->update([
                'payment_release' => 2,
            ]);
            session()->flash('success', 'Successfully Updated');
            return response()->json(['success' => 1]);
        }
    }

    public function releaseMultiple(Request $request){

        if ($request->strIds == null) {
            session()->flash('error', 'You do not select any row.');
            return response()->json(['error' => 1]);
        } else {
            SellPostPayment::whereIn('id', $request->strIds)->where('payment_release',2)->update([
                'payment_release' => 0,
            ]);
            session()->flash('success', 'Successfully Updated');
            return response()->json(['success' => 1]);
        }
    }
}
