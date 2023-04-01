<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Merchant;
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
        if($request->brands && $request->merchants){
            if(Category::where('category_id',$category->id)->exists()){
                $subcategory = Category::where('category_id',$category->id)->first();
                if(Category::where('category_id',$subcategory->id)->exists()){
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $products = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                }else{
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $products = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                }
            }else{
                $products = Product::with(['merchant','brand','category','informations','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->merchants){
            if(Category::where('category_id',$category->id)->exists()){
                $subcategory = Category::where('category_id',$category->id)->first();
                if(Category::where('category_id',$subcategory->id)->exists()){
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $products = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                }else{
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $products = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                }
            }else{
                $products = Product::with(['merchant','brand','category','informations','images'])->where('category_id',$category->id)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->brands){
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
    public function loadmrpras(Request $request){
        $merchant = Merchant::where('id',$request->merid)->first();
        if($request->brands && $request->category){
            $products = Product::with(['merchant','brand','category','informations','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
        }elseif($request->brands){
            $products = Product::with(['merchant','brand','category','informations','images'])->where('merchant_id',$merchant->id)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
        }elseif($request->category){
            $products = Product::with(['merchant','brand','category','informations','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->paginate(20,['*'],'page',$request->page);
        }else{
            $products = Product::with(['merchant','brand','category','informations','images'])->where('merchant_id',$merchant->id)->paginate(20,['*'],'page',$request->page);
        }
        return response([
            'products'=>$products,
            'count'=>$products->lastPage()
        ]);
    }
    public function loadbrpras(Request $request){
        $brand = Brand::where('id',$request->brid)->first();
        if($request->merchants && $request->category){
            $products = Product::with(['merchant','brand','category','informations','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
        }elseif($request->merchants){
            $products = Product::with(['merchant','brand','category','informations','images'])->where('brand_id',$brand->id)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
        }elseif($request->category){
            $products = Product::with(['merchant','brand','category','informations','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->paginate(20,['*'],'page',$request->page);
        }else{
            $products = Product::with(['merchant','brand','category','informations','images'])->where('brand_id',$brand->id)->paginate(20,['*'],'page',$request->page);
        }
        return response([
            'products'=>$products,
            'count'=>$products->lastPage()
        ]);
    }
    public function loadbrands($id){
        if($id != 'false'){
            $category = Category::where('id',$id)->first();
            if(Category::where('category_id',$category->id)->exists()){
                $subcategory = Category::where('category_id',$category->id)->first();
                if(Category::where('category_id',$subcategory->id)->exists()){
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $productids = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->pluck('brand_id')->toArray();
                }else{
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $productids = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->pluck('brand_id')->toArray();
                }
            }else{
                $productids = Product::with(['merchant','brand','category','informations','images'])->where('category_id',$category->id)->pluck('brand_id')->toArray();
            }
            $br1 = Brand::whereIn('id',$productids)->paginate(4,['*'],'page',1);
            $br2 = Brand::whereIn('id',$productids)->paginate(4,['*'],'page',2);
            $br3 = Brand::whereIn('id',$productids)->paginate(4,['*'],'page',3);
        }else{
            $br1 = Brand::paginate(4,['*'],'page',1);
            $br2 = Brand::paginate(4,['*'],'page',2);
            $br3 = Brand::paginate(4,['*'],'page',3);
        }
        return response([
            'br1' => $br1,
            'br2' => $br2,
            'br3' => $br3
        ]);
    }
    public function loadbrand($slug){
        $brand = Brand::where('slug',$slug)->first();
        $catids = Product::where('brand_id',$brand->id)->pluck('category_id')->toArray();
        $mrids = Product::where('brand_id',$brand->id)->pluck('merchant_id')->toArray();
        $categories = Category::whereIn('id',$catids)->get();
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
        $merchants = Merchant::whereIn('id',$mrids)->get();
        return response([
            'brand'=>$brand,
            'categories'=>$categories,
            'merchants'=>$merchants,
        ]);
    }
    public function loadbras($id){
        $category = Category::where('id',$id)->first();
        if(Category::where('category_id',$category->id)->exists()){
            $subcategory = Category::where('category_id',$category->id)->first();
            if(Category::where('category_id',$subcategory->id)->exists()){
                $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                $productids = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->pluck('brand_id')->toArray();
            }else{
                $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                $productids = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->pluck('brand_id')->toArray();
            }
        }else{
            $productids = Product::with(['merchant','brand','category','informations','images'])->where('category_id',$category->id)->pluck('brand_id')->toArray();
        }
        $brands = Brand::whereIn('id',$productids)->get();
        return response([
            'brands' => $brands
        ]);
    }
    public function loadmerchants($id){
        if($id != 'false'){
            $category = Category::where('id',$id)->first();
            if(Category::where('category_id',$category->id)->exists()){
                $subcategory = Category::where('category_id',$category->id)->first();
                if(Category::where('category_id',$subcategory->id)->exists()){
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $productids = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->pluck('merchant_id')->toArray();
                }else{
                    $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                    $productids = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->pluck('merchant_id')->toArray();
                }
            }else{
                $productids = Product::with(['merchant','brand','category','informations','images'])->where('category_id',$category->id)->pluck('merchant_id')->toArray();
            }
            $mr1 = Merchant::whereIn('id',$productids)->paginate(4,['*'],'page',1);
            $mr2 = Merchant::whereIn('id',$productids)->paginate(4,['*'],'page',2);
            $mr3 = Merchant::whereIn('id',$productids)->paginate(4,['*'],'page',3);
        }else{
            $mr1 = Merchant::paginate(4,['*'],'page',1);
            $mr2 = Merchant::paginate(4,['*'],'page',2);
            $mr3 = Merchant::paginate(4,['*'],'page',3);
        }
        return response([
            'mr1' => $mr1,
            'mr2' => $mr2,
            'mr3' => $mr3,
        ]);
    }
    public function loadmerchant($slug){
        $merchant = Merchant::where('slug',$slug)->first();
        $catids = Product::where('merchant_id',$merchant->id)->pluck('category_id')->toArray();
        $brids = Product::where('merchant_id',$merchant->id)->pluck('brand_id')->toArray();
        $categories = Category::whereIn('id',$catids)->get();
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
        $brands = Brand::whereIn('id',$brids)->get();
        return response([
            'merchant'=>$merchant,
            'categories'=>$categories,
            'brands'=>$brands,
        ]);
    }
    public function loadmras($id){
        $category = Category::where('id',$id)->first();
        if(Category::where('category_id',$category->id)->exists()){
            $subcategory = Category::where('category_id',$category->id)->first();
            if(Category::where('category_id',$subcategory->id)->exists()){
                $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                $productids = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->pluck('merchant_id')->toArray();
            }else{
                $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                $productids = Product::with(['merchant','brand','category','informations','images'])->whereIn('category_id',$ids)->pluck('merchant_id')->toArray();
            }
        }else{
            $productids = Product::with(['merchant','brand','category','informations','images'])->where('category_id',$category->id)->pluck('merchant_id')->toArray();
        }
        $merchants = Merchant::whereIn('id',$productids)->get();
        return response([
            'merchants' => $merchants
        ]);
    }
}
