<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardService extends Model
{
    use HasFactory;


    protected $guarded = ['id'];

    protected $appends = ['statusMessage','editRoute','serviceInfoRoute'];

    public function giftCard(){
        return $this->belongsTo(GiftCard::class, 'gift_cards_id');
    }
    public function giftcardCodes(){
        return $this->hasMany(GiftCardCode::class, 'gift_card_service_id','id');
    }
    public function giftCardActiveCodes(){
        return $this->hasMany(GiftCardCode::class, 'gift_card_service_id','id')->where('status',1);
    }

    public function getStatusMessageAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-light">
            <i class="fa fa-circle text-danger danger font-12"></i> '. trans('Deactive') . '</span>';
        }
        return '<span class="badge badge-light">
            <i class="fa fa-circle text-success success font-12"></i> '. trans('Active') . '</span>';
    }

    public function getEditRouteAttribute()
    {
        return route('admin.giftCardServiceUpdate',$this->id);
    }

    public function getServiceInfoRouteAttribute()
    {
        return route('admin.giftCard.serviceCode',[$this->id]);
    }

}
