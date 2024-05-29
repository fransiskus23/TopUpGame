<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherService extends Model
{
    use HasFactory;


    protected $guarded = ['id'];

    protected $appends = ['statusMessage','editRoute','serviceInfoRoute'];

    public function voucher(){
        return $this->belongsTo(GameVoucher::class, 'game_vouchers_id');
    }
    public function voucherCodes(){
        return $this->hasMany(VoucherCode::class, 'voucher_service_id','id');
    }
    public function voucherActiveCodes(){
        return $this->hasMany(VoucherCode::class, 'voucher_service_id')->where('status',1);
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
        return route('admin.voucherServiceUpdate',$this->id);
    }

    public function getServiceInfoRouteAttribute()
    {
        return route('admin.gameVoucher.serviceCode',[$this->id]);
    }



}
