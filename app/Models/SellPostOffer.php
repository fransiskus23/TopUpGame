<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPostOffer extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function sellPost()
    {
        return $this->belongsTo(SellPost::class, 'sell_post_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(SellPostChat::class, 'offer_id')->latest();
    }
    public function messages()
    {
        return $this->hasMany(SellPostChat::class, 'offer_id')->latest();
    }



}
