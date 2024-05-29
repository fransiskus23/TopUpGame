<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopUpSell extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table="top_up_sells";

    protected $casts = [
        'information' => 'object'
    ];

    public function fundable()
    {
        return $this->morphMany(Fund::class, 'fundable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function service()
    {
        return $this->belongsTo(CategoryService::class, 'category_service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }



}
