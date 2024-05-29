<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardSell extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'code' =>'object'
    ];

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class, 'gift_card_id');
    }
    public function service()
    {
        return $this->belongsTo(GiftCardService::class, 'gift_card_service_id');
    }


    public function fundable()
    {
        return $this->morphMany(Fund::class, 'fundable');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
