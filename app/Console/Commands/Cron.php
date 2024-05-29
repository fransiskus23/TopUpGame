<?php

namespace App\Console\Commands;

use App\Http\Traits\Notify;
use App\Models\Fund;
use App\Models\Investment;
use App\Models\SellPost;
use App\Models\SellPostPayment;
use Carbon\Traits\Creator;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Facades\App\Services\BasicService;

class Cron extends Command
{
    use Notify;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron for investment Status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

         Fund::where('status',0)->whereDate('created_at','<=',(new Carbon)->subDays(1))->orderBy('created_at','desc')->delete();

        $now = Carbon::now();
        $basic = (object)config('basic');

        SellPost::where('status', 1)->where('payment_lock', 1)->whereNotNull('lock_at')->where('payment_status', 0)->get()->map(function ($item) use ($now, $basic) {
            if (Carbon::parse($item->lock_at)->addMinutes($basic->payment_expired) < $now) {
                $item->lock_at = null;
                $item->lock_for = null;
                $item->payment_lock = 0;
                $item->save();
                return $item;
            };
        });


        SellPostPayment::where('payment_status', 1)->where('payment_release', 0)->with(['sellPost', 'sellPost.user'])->get()->map(function ($item) use ($now, $basic) {
            if (Carbon::parse($item->created_at)->addDays($basic->payment_released) < $now) {
                $item->released_at = $now;
                $item->payment_release = 1;
                $item->save();

                $user = $item->sellPost->user;
                $user->balance += $item->seller_amount;
                $user->save();

                BasicService::makeTransaction($user, $item->seller_amount, $item->admin_amount, '+', $item->transaction, 'Payment Release on ' . $item->sellPost->title);
                $this->sendMailSms($user, 'SELL_POST_PAYMENT_RELEASED', [
                    'link' => route('sellPost.details', [slug($item->sellPost->title), $item->sell_post_id]),
                    'amount' => getAmount($item->seller_amount),
                    'charge' => getAmount($item->admin_amount),
                    'currency' => $basic->currency,
                    'post' => $item->sellPost->title
                ]);

            }
        });

        $this->info('status');
    }

}
