<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPostChat extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function chatable()
    {
        return $this->morphTo();
    }



    protected $appends = ['formatted_date'];


    public function getFormattedDateAttribute(){
        return $this->created_at->format('M d, Y h:i A');
    }

    public function sellPost()
    {
        return $this->belongsTo(SellPost::class,'sell_post_id');
    }
    public function sellPostOffer()
    {
        return $this->belongsTo(SellPostOffer::class,'offer_id');
    }



}
