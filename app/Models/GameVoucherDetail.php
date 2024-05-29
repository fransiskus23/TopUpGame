<?php

namespace App\Models;

use App\Http\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameVoucherDetail extends Model
{
    use HasFactory,Translatable;

    protected $table = 'voucher_details';
    protected $guarded = ['id'];

    public function gameVoucher(){
        return $this->belongsTo(GameVoucher::class, 'game_vouchers_id');
    }


}
