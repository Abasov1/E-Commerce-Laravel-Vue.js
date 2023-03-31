<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function loadcategory($slug){
        $category = Category::where('slug',$slug)->first();
        $categories = Category::with('subcategories.subcategories')->where('category_id',$category->id)->get();
        if(Category::where('id',$category->category_id)->exists()){
            $category->parent = Category::where('id',$category->category_id)->first();
            if(Category::where('id',$category->parent->category_id)->exists()){
                    $category->parent2 = Category::where('id',$category->parent->category_id)->first();
                    $category->parent = Category::where('slug',$slug)->first();
                    return response([
                        'categories' => [$category]
                    ]);
            }elseif(!Category::where('category_id',$category->id)->exists()){
                $category->parent = Category::where('slug',$slug)->first();
                return response([
                    'categories' => [$category],
                    'type' => 1,
                ]);
            }
        }
        foreach($categories as $cr){
            $cr->parent = Category::where('id',$cr->category_id)->first();
            if(Category::where('id',$cr->parent->category_id)->exists()){
                $cr->parent2 = Category::where('id',$cr->parent->category_id)->first();
            }
        }
            return response([
                'categories' =>$categories,
                'type' => 2
            ]);
    }
    public function loadcategories(){
        $categories = Category::with('subcategories.subcategories')->whereNull('category_id')->get();
        return response([
            'categories' => $categories
        ]);
    }
    public function loadallcategories(){
        $categories = Category::get();
        foreach($categories as $cr){
            if(Category::where('id',$cr->category_id)->exists()){
                $cr->parent = Category::where('id',$cr->category_id)->first();
                if(Category::where('id',$cr->parent->category_id)->exists()){
                    $cr->parent2 = Category::where('id',$cr->parent->category_id)->first();
                    $cr->type = 3;
                }else{
                    $cr->type = 2;
                }
            }else{
                $cr->type = 1;
            }
        }
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
    public function loadpras(Request $request){
        $category = Category::where('id',$request->catid)->first();
        if($request->brands){
            if(Category::where('category_id',$category->id)->exists()){
                $subcategory = Category::where('category_id',$category->id)->first();
                if(Category::where('category_id',$subcategory->id)->exists()){
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $products = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
                }else{
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $products = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
                }
            }else{
                $products = Product::with(['merchant','brand','category','informations','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
            }
        }else{
            if(Category::where('category_id',$category->id)->exists()){
                $subcategory = Category::where('category_id',$category->id)->first();
                if(Category::where('category_id',$subcategory->id)->exists()){
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $products = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->paginate(20,['*'],'page',$request->page);
                }else{
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $products = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->paginate(20,['*'],'page',$request->page);
                }
            }else{
                $products = Product::with(['merchant','brand','category','informations','images'])->where('category_id',$category->id)->paginate(20,['*'],'page',$request->page);
            }
        }
        return response([
            'products'=>$products,
            'count'=>$products->lastPage()
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
    public function loadbras(){
        $brands = Brand::get();
        return response([
            'brands' => $brands
        ]);
    }
}
