<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\Information;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function merchantS(Request $request){
        $merchant = Merchant::where('name','LIKE','%' . $request->merchant .'%')->first();
        return response([
            'merchant' => $merchant,
            'request' => $request->merchant
        ]);
    }
    public function brandS(Request $request){
        $brand = Brand::where('name','LIKE','%' . $request->brand .'%')->first();
        return response([
            'brand' => $brand,
            'request' => $request->brand
        ]);
    }
    public function categoryS(Request $request){
        $category = Category::where('name','LIKE','%' . $request->category .'%')->first();
        return response([
            'category' => $category,
            'request' => $request->category
        ]);
    }
    public function addproduct(PostRequest $request){
        $request->validate($request->rules());
        $product = Product::create([
            'category_id' => $request->category_id,
            'merchant_id' => $request->merchant_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'image' => 'default.png',
            'price' => $request->price
        ]);
        foreach($request->inf as $inf){
            $information = Information::create([
                'product_id' => $product['id'],
                'title' => $inf['title'],
                'body' => $inf['body']
            ]);
            $product->informations()->attach($information->id);
        }
        return response([
            'message' => 'product created successfully'
        ]);
    }
    public function addbrand(Request $request){
        $request->validate([
            'name' => 'required|unique:brands,name'
        ],[
            'name.unique' => 'This brand is already exists'
        ]);
        Brand::create([
            'name' => $request->name,
        ]);
        return response([
            'message' => 'brand created successfully'
        ]);
    }
    public function addmerchant(Request $request){
        $request->validate([
            'name' => 'required|unique:merchants,name'
        ],[
            'name.unique' => 'This merchant is already exists'
        ]);
        Merchant::create([
            'name' => $request->name,
        ]);
        return response([
            'message' => 'merchant created successfully'
        ]);
    }
    public function loadbrands(Request $request){
        if($request->search){
            $brands = Brand::where('name','LIKE','%' . $request->search . '%')->latest()->paginate($request->perPage,['*'],'page',$request->page);
        }else{
            $brands = Brand::latest()->paginate($request->perPage,['*'],'page',$request->page);
        }
        return response([
            'brands' =>$brands,
            'request' => $request
        ]);
    }
    public function deletebrand($id){
        $brand = Brand::where('id',$id)->first();
        if($brand->exists()){
            $brand->delete();
            return response([
                'message' => 'Brand is deleted'
            ]);
        }else{
            return response([
                'message' => 'This brand does not exists'
            ]);
        }
    }
}
