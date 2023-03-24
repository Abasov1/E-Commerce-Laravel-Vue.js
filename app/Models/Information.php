<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'title',
        'body'
    ];
    public function products(){
        return $this->belongsToMany(Product::class,'product_information');
    }
}
