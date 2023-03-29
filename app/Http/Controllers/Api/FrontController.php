<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function loadcategories(){
        $categories = Category::with('subcategories.subcategories')->whereNull('category_id')->get();
        return response([
            'categories' => $categories
        ]);
    }
    public function loadallcategories(){
        $categories = Category::get();
        return response([
            'categories' => $categories
        ]);
    }
    public function loadprs(){
        //bestseller
        $bestpr1 = Product::with(['merchant','brand','category','informations','images'])->paginate(6,['*'],'page',1);
        $bestpr2 = Product::with(['merchant','brand','category','informations','images'])->paginate(6,['*'],'page',2);
        $bestpr3 = Product::with(['merchant','brand','category','informations','images'])->paginate(6,['*'],'page',3);
        //recently viewed
        $recpr1 = Product::with(['merchant','brand','category','informations','images'])->paginate(3,['*'],'page',1);
        $recpr2 = Product::with(['merchant','brand','category','informations','images'])->paginate(3,['*'],'page',2);
        $recpr3 = Product::with(['merchant','brand','category','informations','images'])->paginate(3,['*'],'page',3);
        return response([
            'bestpr1' => $bestpr1,
            'bestpr2' => $bestpr2,
            'bestpr3' => $bestpr3,
            'recpr1' => $recpr1,
            'recpr2' => $recpr2,
            'recpr3' => $recpr3,
        ]);
    }
    public function loadbrands(){
        $br1 = Brand::paginate(4,['*'],'page',1);
        $br2 = Brand::paginate(4,['*'],'page',2);
        $br3 = Brand::paginate(4,['*'],'page',3);
        return response([
            'br1' => $br1,
            'br2' => $br2,
            'br3' => $br3
        ]);
    }
}
