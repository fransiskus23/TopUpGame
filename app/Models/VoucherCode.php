<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherCode extends Model
{
    use HasFactory;

    protected $fillable = ['voucher_id', 'voucher_service_id','code','status'];


    public function getStatusMessageAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-light">
            <i class="fa fa-circle text-danger danger font-12"></i> '. trans('Deactive') . '</span>';
        }
        return '<span class="badge badge-light">
            <i class="fa fa-circle text-success success font-12"></i> '. trans('Active') . '</span>';
    }

    public function gameVoucher(){
        return $this->belongsTo(GameVoucherDetail::class, 'voucher_id','game_vouchers_id');
    }

    public function voucherService(){
        return $this->belongsTo(VoucherService::class, 'voucher_service_id','id');
    }

}
