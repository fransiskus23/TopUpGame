<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload;
use App\Models\Category;
use App\Models\Fund;
use App\Models\GameVoucher;
use App\Models\Gateway;
use App\Models\GiftCard;
use App\Models\GiftCardSell;
use App\Models\PayoutLog;
use App\Models\SellPost;
use App\Models\SellPostPayment;
use App\Models\Ticket;
use App\Models\TopUpSell;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VoucherSell;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;
use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    use Upload;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function forbidden()
    {
        return view('admin.errors.403');
    }


    public function dashboard()
    {

        $data['funding'] = collect(Fund::selectRaw('SUM(CASE WHEN status = 1 THEN amount END) AS totalAmountReceived')
            ->selectRaw('SUM(CASE WHEN status = 1 THEN charge END) AS totalChargeReceived')
            ->selectRaw('SUM((CASE WHEN created_at >= CURDATE() AND status = 1 THEN amount END)) AS todayDeposit')
            ->get()->toArray())->collapse();

        $data['userRecord'] = collect(User::selectRaw('COUNT(id) AS totalUser')
            ->selectRaw('count(CASE WHEN status = 1  THEN id END) AS activeUser')
            ->selectRaw('SUM(balance) AS totalUserBalance')
            ->selectRaw('COUNT((CASE WHEN created_at >= CURDATE()  THEN id END)) AS todayJoin')
            ->get()->makeHidden(['fullname', 'mobile'])->toArray())->collapse();


        $data['tickets'] = collect(Ticket::where('created_at', '>', Carbon::now()->subDays(30))
            ->selectRaw('count(CASE WHEN status = 3  THEN status END) AS closed')
            ->selectRaw('count(CASE WHEN status = 2  THEN status END) AS replied')
            ->selectRaw('count(CASE WHEN status = 1  THEN status END) AS answered')
            ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS pending')
            ->get()->toArray())->collapse();

        if(config('basic.top_up')){
            $data['topupInfo'] = collect(Category::selectRaw('count(id) AS total')
                ->selectRaw('count(CASE WHEN status = 1  THEN status END) AS active')
                ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS deActive')
                ->get()->makeHidden(['detailsRoute','imgPath'])->toArray())->collapse();
            $data['topupSold'] =  TopUpSell::where('payment_status', 1)->sum('price');
        }

        if(config('basic.voucher')){
            $data['voucherInfo'] = collect(GameVoucher::selectRaw('count(id) AS total')
                ->selectRaw('count(CASE WHEN status = 1  THEN status END) AS active')
                ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS deActive')
                ->get()->makeHidden(['detailsRoute','imgPath'])->toArray())->collapse();
            $data['voucherSold'] =  VoucherSell::where('payment_status', 1)->sum('price');
        }



        if(config('basic.gift_card')){

            $data['giftCardInfo'] = collect(GiftCard::selectRaw('count(id) AS total')
                ->selectRaw('count(CASE WHEN status = 1  THEN status END) AS active')
                ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS deActive')
                ->get()->makeHidden(['detailsRoute','imgPath'])->toArray())->collapse();
            $data['giftCardSold'] =  GiftCardSell::where('payment_status', 1)->sum('price');

        }



        if(config('basic.sell_post')){
            $data['sellPosts'] = collect(SellPost::selectRaw('count(id) AS total')
                ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS pending')
                ->selectRaw('count(CASE WHEN status = 2  THEN status END) AS ReSubmission')
                ->selectRaw('count(CASE WHEN status = 3  THEN status END) AS Hold')
                ->selectRaw('count(CASE WHEN status = 1 AND payment_status = 0 THEN status END) AS running')
                ->selectRaw('count(CASE WHEN status = 1 AND payment_status = 1 THEN status END) AS soldOut')
                ->selectRaw('count(CASE WHEN status = 1 AND payment_status = 3 THEN status END) AS paymentProcessing')
                ->selectRaw('count(CASE WHEN status = 4 OR status = 5 THEN status END) AS rejected')
                ->get()->toArray())->collapse();
        }


        /*
         * Pie Chart Data
         */

        $gateway = Gateway::count('id');
        $data['gateway'] = $gateway;
        $pieLog = collect();
        Fund::where('status',1)->with('gateway:id,name')
            ->get()->groupBy('gateway.name')->map(function ($items, $key) use ($gateway, $pieLog) {
                $pieLog->push(['level' => $key, 'value' => round((0 < $gateway) ? (count($items) / $gateway * 100) : 0, 2)]);
                return $items;
            });

        $dailyTopUp = $this->dayList();
        TopUpSell::whereMonth('created_at', Carbon::now()->month)->where('payment_status', 1)
            ->select(
                DB::raw('sum(price) as totalAmount'),
                DB::raw('DATE_FORMAT(created_at,"Day %d") as date')
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()->map(function ($item) use ($dailyTopUp) {
                $dailyTopUp->put($item['date'], round($item['totalAmount'], 2));
            });

        $statistics['topUpSell'] = $dailyTopUp;


        $dailyVoucher = $this->dayList();
        VoucherSell::whereMonth('created_at', Carbon::now()->month)->where('payment_status', 1)
            ->select(
                DB::raw('sum(price) as totalAmount'),
                DB::raw('DATE_FORMAT(created_at,"Day %d") as date')
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()->map(function ($item) use ($dailyVoucher) {
                $dailyVoucher->put($item['date'], round($item['totalAmount'], 2));
            });

        $statistics['voucher'] = $dailyVoucher;

        $dailyGiftCard = $this->dayList();
        GiftCardSell::whereMonth('created_at', Carbon::now()->month)->where('payment_status', 1)
            ->select(
                DB::raw('sum(price) as totalAmount'),
                DB::raw('DATE_FORMAT(created_at,"Day %d") as date')
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()->map(function ($item) use ($dailyGiftCard) {
                $dailyGiftCard->put($item['date'], round($item['totalAmount'], 2));
            });

        $statistics['giftCard'] = $dailyGiftCard;

        $dailySellPost = $this->dayList();
        SellPostPayment::whereMonth('created_at', Carbon::now()->month)->where('payment_status', 1)
            ->select(
                DB::raw('sum(price) as totalAmount'),
                DB::raw('DATE_FORMAT(created_at,"Day %d") as date')
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()->map(function ($item) use ($dailySellPost) {
                $dailySellPost->put($item['date'], round($item['totalAmount'], 2));
            });

        $statistics['sellPost'] = $dailySellPost;
        $statistics['schedule'] = $this->dayList();

        $data['payout'] = collect(PayoutLog::selectRaw('COUNT(CASE WHEN status = 1  THEN id END) AS pending')
            ->selectRaw('SUM((CASE WHEN status = 2 AND created_at >= CURDATE()  THEN amount END)) AS todayPayoutAmount')
            ->selectRaw('SUM((CASE WHEN status = 2 AND created_at >=  DATE_SUB(CURRENT_DATE() , INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY) THEN amount END)) AS monthlyPayoutAmount')
            ->selectRaw('SUM((CASE WHEN status = 2 AND created_at >=  DATE_SUB(CURRENT_DATE() , INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY) THEN charge END)) AS monthlyPayoutCharge')
            ->get()->toArray())->collapse();


        $data['latestUser'] = User::tobase()->latest()->limit(5)->get();

        return view('admin.dashboard', $data, compact('statistics', 'pieLog', 'statistics'));
    }

    public function dayList()
    {
        $totalDays = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $daysByMonth = [];
        for ($i = 1; $i <= $totalDays; $i++) {
            array_push($daysByMonth, ['Day ' . sprintf("%02d", $i) => 0]);
        }

        return collect($daysByMonth)->collapse();
    }

    public function profile()
    {
        $admin = $this->user;
        return view('admin.profile', compact('admin'));
    }


    public function profileUpdate(Request $request)
    {
        $req = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'name' => 'sometimes|required',
            'username' => 'sometimes|required|unique:admins,username,' . $this->user->id,
            'email' => 'sometimes|required|email|unique:admins,email,' . $this->user->id,
            'phone' => 'sometimes|required',
            'address' => 'sometimes|required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = $this->user;
        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = $this->uploadImage($request->image, config('location.admin.path'), config('location.admin.size'), $old);
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }
        $user->name = $req['name'];
        $user->username = $req['username'];
        $user->email = $req['email'];
        $user->phone = $req['phone'];
        $user->address = $req['address'];
        $user->save();

        return back()->with('success', 'Updated Successfully.');
    }


    public function password()
    {
        return view('admin.password');
    }

    public function passwordUpdate(Request $request)
    {
        $req = Purify::clean($request->all());

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $request = (object)$req;
        $user = $this->user;
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', "Password didn't match");
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        return back()->with('success', 'Password has been Changed');
    }
}
