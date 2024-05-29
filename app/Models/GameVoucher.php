<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameVoucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';

    protected $guarded = ['id'];

    protected $appends = ['detailsRoute','imgPath'];
    public function getDetailsRouteAttribute()
    {
        return route('voucher.details',[slug(optional($this->details)->name),$this->id]);
    }
    public function getImgPathAttribute()
    {
        return getFile(config('location.voucher.path').$this->thumb);
    }



    public function language()
    {
        return $this->hasMany(Language::class, 'language_id', 'id');
    }

    public function details(){
        return $this->hasOne(GameVoucherDetail::class,'game_vouchers_id', 'id');
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
        return $this->hasMany(VoucherService::class,'game_vouchers_id');
    }

    public function activeServices()
    {
        return $this->hasMany(VoucherService::class,'game_vouchers_id','id')->where('status',1);
    }

    public function code(){
        return $this->hasMany(VoucherCode::class,'voucher_id', 'id');
    }

    public function activeCodes()
    {
        return $this->hasMany(VoucherCode::class,'voucher_id','id')->where('status',1);
    }

    public function totalSold()
    {
        return $this->hasMany(VoucherSell::class,'voucher_id')->where('payment_status',1);
    }
}
