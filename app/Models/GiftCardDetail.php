<?php

namespace App\Models;

use App\Http\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardDetail extends Model
{
    use HasFactory, Translatable;

    protected $table = 'gift_card_details';
    protected $guarded = ['id'];

    public function giftCard(){
        return $this->belongsTo(GiftCard::class, 'gift_cards_id');
    }
}
