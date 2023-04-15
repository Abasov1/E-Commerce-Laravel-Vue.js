<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class ProductInformation extends Model
{
    use HasFactory;
    use Translatable;
    public $translatedAttributes = ['body'];
    protected $fillable = [
        'information_id',
        'product_id'
    ];
    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
