<?php

namespace App\Models;

use App\Http\Traits\Notify;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Notify;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen' => 'datetime',
    ];

    public  $allusers = [];

    protected $appends = ['fullname', 'mobile','imgPath','lastSeen'];


    protected $dates = ['sent_at'];



    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getLastSeenAttribute()
    {
        if(Cache::has('user-is-online-' . $this->id)){
            return true;
        }else{
            return false;
        }
    }
    public function getMobileAttribute()
    {
        return  $this->phone;
    }

    public function getImgPathAttribute()
    {
        return getFile(config('location.user.path').$this->image);
    }

    public function funds()
    {
        return $this->hasMany(Fund::class)->latest()->where('status', '!=', 0);
    }


    public function transaction()
    {
        return $this->hasMany(Transaction::class,'user_id')->latest();
    }

    public function topUp()
    {
        return $this->hasMany(TopUpSell::class,'user_id')->latest();
    }

    public function voucher()
    {
        return $this->hasMany(VoucherSell::class,'user_id')->latest();
    }

    public function giftCard()
    {
        return $this->hasMany(GiftCardSell::class,'user_id')->latest();
    }

    public function sellPost()
    {
        return $this->hasMany(SellPostPayment::class,'user_id')->latest();
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class ,'user_id');
    }

    public function payout()
    {
        return $this->hasMany(PayoutLog::class,'user_id');
    }



    public function referral()
    {
        return $this->belongsTo(User::class,'referral_id');
    }

    public function siteNotificational()
    {
        return $this->morphOne(SiteNotification::class, 'siteNotificational', 'site_notificational_type', 'site_notificational_id');
    }

    public function chatable()
    {
        return $this->morphOne(SellPostChat::class, 'chatable');
    }


    public function sendPasswordResetNotification($token)
    {
        $this->mail($this, 'PASSWORD_RESET', $params = [
            'message' => '<a href="' . url('password/reset', $token) . '?email=' . $this->email . '" target="_blank">Click To Reset Password</a>'
        ]);
    }


    public function getReferralLinkAttribute()
    {
        return $this->referral_link = route('register', ['ref' => $this->username]);
    }
    public function scopeLevel()
    {
        $count = 0;
        $user_id = $this->id;
        while ($user_id != null) {
            $user = User::where('referral_id',$user_id)->first();
            if (!$user) {
                break;
            }else{
                $user_id = $user->id;
                $count++;
            }
        }
        return $count;
    }

    public function referralUsers($id, $currentLevel = 1)
    {
        $users = $this->getUsers($id);
        if($users['status']) {
            $this->allusers[$currentLevel] = $users['user'];
            $currentLevel++;
            $this->referralUsers($users['ids'], $currentLevel);
        }
        return $this->allusers;
    }

    public function getUsers($id)
    {
        if (isset($id)) {
            $data['user'] = User::whereIn('referral_id', $id)->get(['id', 'firstname','lastname', 'username','email','phone_code','phone', 'referral_id','created_at']);
            if(count($data['user']) > 0){
                $data['status'] = true;
                $data['ids'] = $data['user']->pluck('id');
                return $data;
            }
        }
        $data['status'] = false;
        return $data;
    }

    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'activityable');
    }

    public function sellChats()
    {
        return $this->morphMany(SellPostChat::class, 'chatable');
    }
}
