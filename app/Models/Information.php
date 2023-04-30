<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Information extends Model
{
    use HasFactory;
    use Translatable;
    public $translatedAttributes = ['title'];
    protected $fillable = [
        'category_id',
    ];
    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }
    public function bodies(){
        return $this->hasMany(ProductInformation::class);
    }
}
