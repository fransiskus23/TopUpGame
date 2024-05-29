<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
      'form_field' =>'object'
    ];
    protected $appends = ['detailsRoute','imgPath'];

    public function getDetailsRouteAttribute()
    {
        return route('topUp.details',[slug(optional($this->details)->name),$this->id]);
    }

    public function getImgPathAttribute()
    {
        return getFile(config('location.category.path').$this->thumb);
    }


    public function language()
    {
        return $this->hasMany(Language::class, 'language_id', 'id');
    }

    public function details(){
        return $this->hasOne(CategoryDetails::class);
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

    public function categoryService(){
        return $this->hasMany(CategoryService::class);
    }

    public function activeServices()
    {
        return $this->hasMany(CategoryService::class)->where('status',1);
    }

    public function totalSold()
    {
        return $this->hasMany(TopUpSell::class,'category_id')->where('payment_status',1);
    }


    public function topUpSells(){
        return $this->hasMany(TopUpSell::class, 'category_id');
    }



}
