<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Information;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Http\Request;

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
        Brand::create([
            'name' => $request->name
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
}
