<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPostPayment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function fundable()
    {
        return $this->morphMany(Fund::class, 'fundable');
    }

    // payment By
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function sellPost()
    {
        return $this->belongsTo(SellPost::class, 'sell_post_id');
    }

    public function sellPostUser()
    {
        return $this->belongsToT(SellPost::class, 'sell_post_id');
    }

}
