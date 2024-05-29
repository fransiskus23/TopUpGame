<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardCode extends Model
{
    use HasFactory;

    protected $fillable = ['gift_card_id', 'gift_card_service_id','code','status'];


    public function getStatusMessageAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-light">
            <i class="fa fa-circle text-danger danger font-12"></i> '. trans('Deactive') . '</span>';
        }
        return '<span class="badge badge-light">
            <i class="fa fa-circle text-success success font-12"></i> '. trans('Active') . '</span>';
    }

    public function giftCard(){
        return $this->belongsTo(GiftCardDetail::class, 'gift_card_id','gift_cards_id');
    }

    public function giftCardService(){
        return $this->belongsTo(GiftCardService::class, 'gift_card_service_id','id');
    }
}
