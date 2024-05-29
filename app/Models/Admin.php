<?php

namespace App\Models;

use App\Http\Traits\Notify;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class Admin extends Authenticatable
{
    use Notifiable, Notify;
    protected $guarded = ['id'];
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'admin_access' => 'object'
    ];
    protected $appends = ['imgPath','lastSeen'];

    public function siteNotificational()
    {
        return $this->morphOne(SiteNotification::class, 'siteNotificational', 'site_notificational_type', 'site_notificational_id');
    }

    public function chatable()
    {
        return $this->morphOne(SellPostChat::class, 'chatable');
    }

    public function getLastSeenAttribute()
    {
        if(Cache::has('admin-is-online-' . $this->id)){
            return true;
        }else{
            return false;
        }
    }


    public function sendPasswordResetNotification($token)
    {
        $this->mail($this, 'PASSWORD_RESET', $params = [
            'message' => '<a href="'.url('admin/password/reset',$token).'?email='.$this->email.'" target="_blank">Click To Reset Password</a>'
        ]);
    }

    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'activityable');
    }

    public function getImgPathAttribute()
    {
        return getFile(config('location.admin.path').$this->image);
    }

}
