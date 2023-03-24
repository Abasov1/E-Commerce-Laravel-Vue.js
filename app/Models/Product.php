<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'merchant_id',
        'category_id',
        'brand_id',
        'name',
        'image',
        'price'
    ];
    public function merchant(){
        return $this->hasOne(Merchant::class,'id','merchant_id');
    }
    public function brand(){
        return $this->hasOne(Brand::class,'id','brand_id');
    }
    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }
    public function informations(){
        return $this->belongsToMany(Information::class,'product_information');
    }
}
