<?php

namespace App\Models;

use App\Http\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPostCategoryDetail extends Model
{
    use HasFactory, Translatable;
    protected $guarded = ['id'];


    public function sellPostCategory(){
        return $this->belongsTo(SellPostCategory::class, 'sell_post_category_id');
    }
}
