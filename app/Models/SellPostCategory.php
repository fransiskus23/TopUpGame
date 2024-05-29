<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellPostCategory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts=[
        'form_field' => 'object',
        'post_specification_form' => 'object'
    ];


    public function language()
    {
        return $this->hasMany(Language::class, 'language_id', 'id');
    }

    public function details(){
        return $this->hasOne(SellPostCategoryDetail::class);
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

    public function post()
    {
        return $this->hasMany(SellPost::class,'category_id');
    }
    public function activePost()
    {
        return $this->hasMany(SellPost::class,'category_id')->where('payment_status','!=','1')->where('status',1);
    }
}
