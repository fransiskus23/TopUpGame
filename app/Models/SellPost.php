<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPost extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'sell_posts';
    protected $casts = [
        'image' => 'object',
        'credential' => 'object',
        'post_specification_form' => 'object'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(SellPostCategory::class, 'category_id', 'id');
    }

    public function activites()
    {
        return $this->hasMany(ActivityLog::class, 'sell_post_id');
    }

    public function getStatusMessageAttribute()
    {
        if ($this->status == 1) {
            return '<span class="badge badge-light"><i class="fa fa-circle text-success   font-12" ></i> ' . trans('Approved') . '</span>';
        } elseif ($this->status == 0) {
            return '<span class="badge badge-light"><i class="fa fa-circle text-warning pending font-12" ></i> ' . @trans('Pending') . '</span>';
        } elseif ($this->status == 2) {
            return '<span class="badge badge-light"><i class="fa fa-circle text-warning pending font-12" ></i> ' . @trans('Re Submission') . '</span>';
        } elseif ($this->status == 3) {
            return '<span class="badge badge-light"><i class="fa fa-circle text-warning pending font-12" ></i> ' . @trans('Hold') . '</span>';
        } elseif ($this->status == 4) {
            return '<span class="badge badge-light"><i class="fa fa-circle text-danger font-12" ></i> ' . @trans('Soft Rejected') . '</span>';
        } elseif ($this->status == 5) {
            return '<span class="badge badge-light"><i class="fa fa-circle text-danger font-12" ></i> ' . @trans('Hard Rejected') . '</span>';
        }
    }


    public function getActivityTitleAttribute()
    {
        $oldActivity = $this->activites->count();
        if ($this->status == 0) {
            return "New Post Submission";
        } elseif (0 < $oldActivity && $this->status == 1) {
            return "Resubmission Trusted Approval";
        } elseif ($this->status == 1) {
            return "Trusted Approval";
        } elseif ($this->status == 2) {
            return "Resubmission";
        } elseif ($this->status == 3) {
            return "Post Hold";
        } elseif (0 < $oldActivity && $this->status == 4) {
            return "Resubmission Soft Rejected";
        } elseif ($this->status == 4) {
            return "Soft Rejected";
        } elseif ($this->status == 5) {
            return 'Hard Rejected';
        }

        return 'Unknown';
    }

    public function sellPostOffer()
    {
        return $this->hasMany(SellPostOffer::class, 'sell_post_id');
    }

    public function sellPostPayment()
    {
        return $this->hasOne(SellPostPayment::class, 'sell_post_id');
    }

    public function scopeStatus($query, $value)
    {
        return $query->where('status', $value);
    }

}
