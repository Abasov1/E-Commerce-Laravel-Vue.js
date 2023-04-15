<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
    public function loadpr($slug){
        $product = Product::with(['merchant','brand','category','infbody','images','reviews.user'])->where('slug',$slug)->first();
        $titles = $product->inftitle();
        $product->revcount = Review::where('product_id',$product->id)->get()->count();
        $product->fivestar = Review::where(['product_id'=>$product->id,'star'=>5])->count();
        if($product->fivestar != 0){
            $product->fivepercent = $product->fivestar * 100 / $product->revcount;
        }else{
            $product->fivepercent = 0;
        }
        $product->fourstar = Review::where(['product_id'=>$product->id,'star'=>4])->count();
        if($product->fourstar != 0){
            $product->fourpercent = $product->fourstar * 100 / $product->revcount;
        }else{
            $product->fourpercent = 0;
        }
        $product->threestar = Review::where(['product_id'=>$product->id,'star'=>3])->count();
        if($product->threestar != 0){
            $product->threepercent = $product->threestar * 100 / $product->revcount;
        }else{
            $product->threepercent = 0;
        }
        $product->twostar = Review::where(['product_id'=>$product->id,'star'=>2])->count();
        if($product->twostar != 0){
            $product->twopercent = $product->twostar * 100 / $product->revcount;
        }else{
            $product->twopercent = 0;
        }
        $product->joestar = Review::where(['product_id'=>$product->id,'star'=>1])->count();
        if($product->joestar != 0){
            $product->joepercent = $product->joestar * 100 / $product->revcount;
        }else{
            $product->joepercent = 0;
        }
        foreach($product->reviews as $rew){
            $rew->date = Carbon::parse($rew->created_at)->format('j F Y');
        }
        return response([
            'product' => [$product,$titles],
        ]);
    }
    public function loadpras(Request $request){
        $category = Category::where('id',$request->catid)->first();
        if($request->orderBy === '1'){
            if($request->brands && $request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->brands){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->latest()->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->latest()->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->latest()->paginate(20,['*'],'page',$request->page);
                }
            }else{
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->latest()->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->latest()->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->latest()->paginate(20,['*'],'page',$request->page);
                }
            }
        }elseif($request->orderBy === '2'){
            if($request->brands && $request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->brands){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
                }
            }else{
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->paginate(20,['*'],'page',$request->page);
                }
            }
        }elseif($request->orderBy === '3'){
            if($request->brands && $request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->brands){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                }
            }else{
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                }
            }
        }elseif($request->orderBy === '4'){
            if($request->brands && $request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->brands){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                }
            }else{
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                }
            }
        }elseif($request->orderBy === '5'){
            if($request->brands && $request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->brands){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                }
            }else{
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                }
            }
        }elseif($request->orderBy === '6'){
            if($request->brands && $request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->merchants){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                }
            }elseif($request->brands){
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->whereIn('brand_id',$request->brands)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->whereIn('brand_id',$request->brands)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                }
            }else{
                if(Category::where('category_id',$category->id)->exists()){
                    $subcategory = Category::where('category_id',$category->id)->first();
                    if(Category::where('category_id',$subcategory->id)->exists()){
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                        $products = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    $products = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                }
            }
        }
        return response([
            'products'=>$products,
            'count'=>$products->lastPage()
        ]);
    }
    public function loadmrpras(Request $request){
        $merchant = Merchant::where('id',$request->merid)->first();
        if($request->orderBy === '1'){
            if($request->brands && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->latest()->paginate(20,['*'],'page',$request->page);
            }elseif($request->brands){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->whereIn('brand_id',$request->brands)->latest()->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->latest()->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->latest()->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '2'){
            if($request->brands && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
            }elseif($request->brands){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '3'){
            if($request->brands && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->brands){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->whereIn('brand_id',$request->brands)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '4'){
            if($request->brands && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->brands){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->whereIn('brand_id',$request->brands)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '5'){
            if($request->brands && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->brands){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->whereIn('brand_id',$request->brands)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '6'){
            if($request->brands && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->brands){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->whereIn('brand_id',$request->brands)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->where('category_id',$request->category)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('merchant_id',$merchant->id)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
            }
        }
        return response([
            'products'=>$products,
            'count'=>$products->lastPage()
        ]);
    }
    public function loadbrpras(Request $request){
        $brand = Brand::where('id',$request->brid)->first();
        if($request->orderBy === '1'){
            if($request->merchants && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
            }elseif($request->merchants){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->latest()->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->latest()->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '2'){
            if($request->merchants && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
            }elseif($request->merchants){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '3'){
            if($request->merchants && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->merchants){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '4'){
            if($request->merchants && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->merchants){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '5'){
            if($request->merchants && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->merchants){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
            }
        }elseif($request->orderBy === '6'){
            if($request->merchants && $request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->merchants){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
            }elseif($request->category){
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->where('category_id',$request->category)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
            }else{
                $products = Product::with(['merchant','brand','category','images'])->where('brand_id',$brand->id)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
            }
        }
        return response([
            'products'=>$products,
            'count'=>$products->lastPage()
        ]);
    }
    public function loadbrand($slug){
        $brand = Brand::where('slug',$slug)->first();
        $catids = Product::where('brand_id',$brand->id)->pluck('category_id')->toArray();
        $mrids = Product::where('brand_id',$brand->id)->pluck('merchant_id')->toArray();
        $categories = Category::whereIn('id',$catids)->get();
        $parents = [];
        foreach($categories as $cr){
            if(Category::where('id',$cr->category_id)->exists()){
                $cr->parent = Category::with('subcategories.subcategories')->where('id',$cr->category_id)->first();
                if(!in_array($cr->parent,$parents)){
                    array_push($parents,$cr->parent);
                }
                if(Category::where('id',$cr->parent->category_id)->exists()){
                    $cr->parent2 = Category::with('subcategories.subcategories')->where('id',$cr->parent->category_id)->first();
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
            'merchants'=>$merchants,
            'parents'=>[$parents,$catids]
        ]);
    }
    public function loadbras($id){
        $category = Category::where('id',$id)->first();
        if(Category::where('category_id',$category->id)->exists()){
            $subcategory = Category::where('category_id',$category->id)->first();
            if(Category::where('category_id',$subcategory->id)->exists()){
                $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                $productids = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->pluck('brand_id')->toArray();
            }else{
                $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                $productids = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->pluck('brand_id')->toArray();
            }
        }else{
            $productids = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->pluck('brand_id')->toArray();
        }
        $brands = Brand::whereIn('id',$productids)->get();
        return response([
            'brands' => $brands
        ]);
    }
    public function loadmerchant($slug){
        $merchant = Merchant::where('slug',$slug)->first();
        $catids = Product::where('merchant_id',$merchant->id)->pluck('category_id')->toArray();
        $brids = Product::where('merchant_id',$merchant->id)->pluck('brand_id')->toArray();
        $categories = Category::whereIn('id',$catids)->get();
        $parents = [];
        foreach($categories as $cr){
            if(Category::where('id',$cr->category_id)->exists()){
                $cr->parent = Category::with('subcategories.subcategories')->where('id',$cr->category_id)->first();
                if(!in_array($cr->parent,$parents)){
                    array_push($parents,$cr->parent);
                }
                if(Category::where('id',$cr->parent->category_id)->exists()){
                    $cr->parent2 = Category::with('subcategories.subcategories')->where('id',$cr->parent->category_id)->first();
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
            'categories'=>[$parents,$catids],
            'brands'=>$brands,
        ]);
    }
    public function loadmras($id){
        $category = Category::where('id',$id)->first();
        if(Category::where('category_id',$category->id)->exists()){
            $subcategory = Category::where('category_id',$category->id)->first();
            if(Category::where('category_id',$subcategory->id)->exists()){
                $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                $productids = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->pluck('merchant_id')->toArray();
            }else{
                $ids  = Category::where('category_id',$category->id)->pluck('id')->toArray();
                $productids = Product::with(['merchant','brand','category','images'])->whereIn('category_id',$ids)->pluck('merchant_id')->toArray();
            }
        }else{
            $productids = Product::with(['merchant','brand','category','images'])->where('category_id',$category->id)->pluck('merchant_id')->toArray();
        }
        $merchants = Merchant::whereIn('id',$productids)->get();
        return response([
            'merchants' => $merchants
        ]);
    }
    public function addwish($id){
        $user = Auth::user();
        $product = Product::where('id',$id)->first();
        if($user->wproducts->contains('id',$id)){
            $user->wproducts()->detach($product->id);
        }else{
            $user->wproducts()->attach($product->id);
        }
        $user->wishlist = $user->wproducts()->get();
        $user->cart = $user->cproducts()->get();
        return response([
            'user' => $user,
        ]);
    }
    public function addcart($id){
        $user = Auth::user();
        $product = Product::where('id',$id)->first();
        if($user->cproducts->contains('id',$id)){
            $user->cproducts()->detach($product->id);
        }else{
            $user->cproducts()->attach($product->id,['quantity'=>1]);
        }
        $user->wishlist = $user->wproducts()->get();
        $user->cart = $user->cproducts()->get();
        return response([
            'user' => $user,
        ]);
    }
    public function changequantity(Request $request){
        $user = Auth::user();
        if($request->increase){
            $oror = $request->quantity + 1;
        }elseif($request->decrase){
            $oror = $request->quantity - 1;
        }
        $user->cproducts()->updateExistingPivot($request->id, ['quantity' => $oror]);
        $user->wishlist = $user->wproducts()->get();
        $user->cart = $user->cproducts()->get();
        return response([
            'user' => $user,
            'oror' => $oror,
        ]);
    }
    public function recentlyviewed(){
        $recentlyviewed = Product::with(['merchant','brand','category','images'])->paginate(25);
        return response([
            'rec' => $recentlyviewed,
        ]);
    }
    public function brandcarousel(){
        $brands = Brand::paginate(25);
        return response([
            'brands' => $brands,
        ]);
    }
    public function merchantcarousel(){
        $merchants = Merchant::paginate(25);
        return response([
            'merchants' => $merchants,
        ]);
    }
    public function search(Request $request){
        $products = Product::with('images')->whereTranslationLike('name','%' . $request->index . '%')->paginate(5);
        $brands = Brand::where('name','LIKE','%' . $request->index . '%')->paginate(5);
        $merchants = Merchant::where('name','LIKE','%' . $request->index . '%')->paginate(5);
        return response([
            'data'=>[$products,$brands,$merchants]
        ]);
    }
    public function searchAll(Request $request){
        if($request->index != '' && $request->index != null){
            $basepr = Product::with(['merchant','brand','category','images'])->whereTranslationLike('name','%' . $request->index . '%');
            $catIds = $basepr->pluck('category_id')->toArray();
            $brIds = $basepr->pluck('brand_id')->toArray();
            $mrIds = $basepr->pluck('merchant_id')->toArray();
            $categories = Category::whereIn('id',$catIds)->get();
            $parents = [];
            foreach($categories as $cr){
                if(Category::where('id',$cr->category_id)->exists()){
                    $cr->parent = Category::with('subcategories.subcategories')->where('id',$cr->category_id)->first();
                    if(!in_array($cr->parent,$parents)){
                        array_push($parents,$cr->parent);
                    }
                    if(Category::where('id',$cr->parent->category_id)->exists()){
                        $cr->parent2 = Category::with('subcategories.subcategories')->where('id',$cr->parent->category_id)->first();
                        $cr->type = 3;
                    }else{
                        $cr->type = 2;
                    }
                }else{
                    $cr->type = 1;
                }
            }
            $brands = Brand::whereIn('id',$brIds)->get();
            $merchants = Merchant::whereIn('id',$mrIds)->get();
            if($request->orderBy === '1'){
                if($request->category){
                    if($request->brands && $request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->latest()->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->where('category_id',$request->category)->latest()->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    if($request->brands && $request->merchants){
                        $products = $basepr->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->whereIn('brand_id',$request->brands)->latest()->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->whereIn('merchant_id',$request->merchants)->latest()->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->latest()->paginate(20,['*'],'page',$request->page);
                    }
                }
            }elseif($request->orderBy === '2'){
                if($request->category){
                    if($request->brands && $request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->where('category_id',$request->category)->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    if($request->brands && $request->merchants){
                        $products = $basepr->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->whereIn('brand_id',$request->brands)->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->whereIn('merchant_id',$request->merchants)->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->paginate(20,['*'],'page',$request->page);
                    }
                }
            }elseif($request->orderBy === '3'){
                if($request->category){
                    if($request->brands && $request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->where('category_id',$request->category)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    if($request->brands && $request->merchants){
                        $products = $basepr->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->whereIn('brand_id',$request->brands)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->whereIn('merchant_id',$request->merchants)->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->orderBy('price','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }
            }elseif($request->orderBy === '4'){
                if($request->category){
                    if($request->brands && $request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->where('category_id',$request->category)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    if($request->brands && $request->merchants){
                        $products = $basepr->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->whereIn('brand_id',$request->brands)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->whereIn('merchant_id',$request->merchants)->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->orderBy('price','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }
            }elseif($request->orderBy === '5'){
                if($request->category){
                    if($request->brands && $request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->where('category_id',$request->category)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    if($request->brands && $request->merchants){
                        $products = $basepr->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->whereIn('brand_id',$request->brands)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->whereIn('merchant_id',$request->merchants)->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->orderBy('name','asc')->paginate(20,['*'],'page',$request->page);
                    }
                }
            }elseif($request->orderBy === '6'){
                if($request->category){
                    if($request->brands && $request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->where('category_id',$request->category)->whereIn('brand_id',$request->brands)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->where('category_id',$request->category)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->where('category_id',$request->category)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }else{
                    if($request->brands && $request->merchants){
                        $products = $basepr->whereIn('brand_id',$request->brands)->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->brands){
                        $products = $basepr->whereIn('brand_id',$request->brands)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }elseif($request->merchants){
                        $products = $basepr->whereIn('merchant_id',$request->merchants)->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }else{
                        $products = $basepr->orderBy('name','desc')->paginate(20,['*'],'page',$request->page);
                    }
                }
            }
            return response([
                'data' => [[$products,$products->lastPage()],$brands,$merchants,[$parents,$catIds]]
            ]);
        }
    }
    public function addreview(Request $request){
        $user = Auth::user();
        if($request->deleting){
            Review::where(['user_id'=>$user->id,'product_id'=>$request->product_id])->first()->delete();
        }elseif(!$request->deleting && $request->updating){
            if($request->body === null || $request->body === ''){
                return;
            }
            Review::where(['user_id'=>$user->id,'product_id'=>$request->product_id])->first()->update([
                'star'=>$request->star,
                'body'=>$request->body,
                'isBought'=>$user->isBought($request->product_id)
            ]);
        }else{
            if($request->body === null || $request->body === ''){
                return;
            }
            Review::create([
                'user_id'=>$user->id,
                'product_id'=>$request->product_id,
                'star'=>$request->star,
                'body'=>$request->body,
                'isBought'=>$user->isBought($request->product_id)
            ]);
        }
        $product = Product::with(['merchant','brand','category','infbody','images','reviews.user'])->whereId($request->product_id)->first();
        $titles = $product->inftitle();
        $product->revcount = Review::where('product_id',$product->id)->get()->count();
        $product->fivestar = Review::where(['product_id'=>$product->id,'star'=>5])->count();
        if($product->fivestar != 0){
            $product->fivepercent = $product->fivestar * 100 / $product->revcount;
        }else{
            $product->fivepercent = 0;
        }
        $product->fourstar = Review::where(['product_id'=>$product->id,'star'=>4])->count();
        if($product->fourstar != 0){
            $product->fourpercent = $product->fourstar * 100 / $product->revcount;
        }else{
            $product->fourpercent = 0;
        }
        $product->threestar = Review::where(['product_id'=>$product->id,'star'=>3])->count();
        if($product->threestar != 0){
            $product->threepercent = $product->threestar * 100 / $product->revcount;
        }else{
            $product->threepercent = 0;
        }
        $product->twostar = Review::where(['product_id'=>$product->id,'star'=>2])->count();
        if($product->twostar != 0){
            $product->twopercent = $product->twostar * 100 / $product->revcount;
        }else{
            $product->twopercent = 0;
        }
        $product->joestar = Review::where(['product_id'=>$product->id,'star'=>1])->count();
        if($product->joestar != 0){
            $product->joepercent = $product->joestar * 100 / $product->revcount;
        }else{
            $product->joepercent = 0;
        }
        foreach($product->reviews as $rew){
            $rew->date = Carbon::parse($rew->created_at)->format('j F Y');
        }
        return response([
            'product' => [$product,$titles],
        ]);

    }
    public function loadreview(Request $request){
        $user = Auth::user();
        if($user->reviews()->where('product_id',$request->id)->exists()){
            $review = $user->reviews()->where('product_id',$request->id)->first();
            return response([
                'review'=> $review
            ]);
        }else{
            return response([
                'review'=>false
            ]);
        }
    }

    public function purchase(Request $request){
        $user = auth()->user();
        $user->update([
            'address'=>$request->address,
            'city'=>$request->city,
            'state'=>$request->state,
            'zip_code'=>$request->zip_code,
        ]);
        try{
            $user->createOrGetStripeCustomer();
            $payment = $user->charge(
                $request->amount,
                $request->payment_method_id
            );
            $payment = $payment->asStripePaymentIntent();
            $order = $user->orders()->create([
                'transaction_id'=>$payment->id,
                'total'=>$payment->amount
            ]);
            foreach(json_decode($request->cart,true) as $item){
                $order->products()->attach($item['id'],['quantity'=>$item['pivot']['quantity']]);
                $user->cproducts()->detach($item['id']);
                $pr = Product::where('id',$item['id'])->first();
                $pr->update([
                    'quantity' => $pr->quantity - $item['pivot']['quantity']
                ]);
            }
            return response([
                'message'=>'success'
            ]);
        }catch(\Exception $e){
            return response([
                'message'=>$e->getMessage()
            ],422);
        }
    }

    public function soldPrs(Request $request){
        $user = auth()->user();
        $orders = $user->orders()->with('products.brand','products.merchant','products.category','products.images')->paginate(5,['*'],'page',$request->page);
        foreach($orders as $or){
            $or->date = Carbon::parse($or->created_at)->format('j F Y H:i');
        }
        return response([
            'data' => [$orders,$orders->lastPage()]
        ]);
    }
}
