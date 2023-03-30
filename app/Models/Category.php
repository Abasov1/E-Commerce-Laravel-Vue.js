<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image'
    ];
    public function subcategories(){
        return $this->hasMany(Category::class);
    }
    public function products(){
        if ($this->parent_id) {
            $child_categories = $this->subcategories()->pluck('id')->toArray();
            return Product::whereIn('category_id', $child_categories)->get();
        }
        return $this->hasMany(Product::class);
    }
}
