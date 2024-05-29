<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    use HasFactory;

    protected $table = 'gift_cards';

    protected $fillable = ['image', 'status','discount_type','discount_amount','discount_status','featured'];


    protected $appends = ['detailsRoute','imgPath'];
    public function getDetailsRouteAttribute()
    {
        return route('giftCard.details',[slug(optional($this->details)->name),$this->id]);
    }
    public function getImgPathAttribute()
    {
        return getFile(config('location.giftCard.path').$this->thumb);
    }


    public function language()
    {
        return $this->hasMany(Language::class, 'language_id', 'id');
    }

    public function details(){

       return $this->hasOne(GiftCardDetail::class,'gift_cards_id', 'id');
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

    public function services(){
        return $this->hasMany(GiftCardService::class,'gift_cards_id');
    }

    public function activeServices()
    {
        return $this->hasMany(GiftCardService::class,'gift_cards_id','id')->where('status',1);
    }

    public function code(){
        return $this->hasMany(GiftCardCode::class,'gift_card_id', 'id');
    }

    public function activeCodes()
    {
        return $this->hasMany(GiftCardCode::class,'gift_card_id','id')->where('status',1);
    }

    public function totalSold()
    {
        return $this->hasMany(GiftCardSell::class,'gift_card_id','id')->where('payment_status',1);
    }
}
