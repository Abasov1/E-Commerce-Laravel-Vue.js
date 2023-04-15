<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Information;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class DemoController extends Controller
{
    public function index(){
        $products = Product::get();
        $merchants = Merchant::get();
        $brands = Brand::get();
        $informations = Information::get();
        $allcategories = Category::get();
        $categories = Category::with('subcategories.subcategories')->whereNull('category_id')->get();
        return view('demo',get_defined_vars());
    }
    public function createcat(Request $request){
        Category::create([
            'category_id' => $request->category,
            'name' => $request->name
        ]);
        return back();
    }
    public function createmer(Request $request){
        Merchant::create([
            'name' => $request->name
        ]);
        return back();
    }
    public function createbr(Request $request){
        if($request->hasFile('image')){
            $image = Image::make($request->file('image'));
            $image->fit(300, 300);
            $ex = $request->file('image')->getClientOriginalExtension();
            $file = uniqid() .'.'. $ex;
            $image->save(public_path('storage/'.$file));
        }
        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $file
        ]);
        return back();
    }
    public function createpr(Request $request){
        Product::create([
            'category_id' => $request->category,
            'merchant_id' => $request->merchant,
            'brand_id' => $request->brand,
            'name' => $request->name,
            'image' => 'default.png'
        ]);
        return back();
    }
    public function createinf(Request $request){
        $information = Information::create([
            'product_id' => $request->product,
            'title' => $request->title,
            'body' => $request->body
        ]);
        $product = Product::find($request->product);
        $product->informations()->attach($information->id);
        return back();
    }
    public function showbrand($slug){
        $brand = Brand::where('slug',$slug)->first();
        return view('brand',get_defined_vars());
    }
    public function why(){
        $products = Product::with('images')->whereTranslationLike('name','%a%')->first();
        return $products->translate('en');
    }
}
