<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherSell extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'code' =>'object'
    ];

    public function voucher()
    {
        return $this->belongsTo(GameVoucher::class, 'voucher_id');
    }
    public function service()
    {
        return $this->belongsTo(VoucherService::class, 'voucher_service_id');
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
