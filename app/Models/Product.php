<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Product extends Model
{
    use HasFactory;
    use Translatable;
    public $translatedAttributes = ['name'];
    protected $fillable = [
        'merchant_id',
        'category_id',
        'brand_id',
        'slug',
        'price',
        'quantity'
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
    public function images(){
        return $this->hasMany(Pimage::class);
    }
    public function inftitle(){
        $ids = ProductInformation::where('product_id',$this->id)->pluck('information_id')->toArray();
        $idz = Information::whereIn('id',$ids)->pluck('category_id')->toArray();
        return Information::whereIn('category_id',$idz)->get();
    }
    public function infbody(){
        return $this->hasMany(ProductInformation::class);
    }
    public function wusers(){
        return $this->belongsToMany(User::class,'wishlist','user_id','product_id');
    }
    public function cusers(){
        return $this->belongsToMany(User::class,'cart','user_id','product_id')->withPivot('quantity');
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }
    public function orders(){
        return $this->belongsToMany(Product::class);
    }
}
