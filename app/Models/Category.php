<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Category extends Model
{
    use HasFactory;
    use Translatable;
    public $translatedAttributes = ['name'];
    protected $fillable = [
        'category_id',
        'slug',
        'image'
    ];
    public function subcategories(){
        return $this->hasMany(Category::class);
    }
    public function informations(){
        return $this->hasMany(Information::class);
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
    public function blocophobia(){
        return $this->hasOne(Category::class,'id','category_id');
    }
}
