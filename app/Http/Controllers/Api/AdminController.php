<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\Information;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Validation\ValidationRuleParser;

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
        if($request->id){
            Brand::where('id',$request->id)->first()->update([
                'name' => $request->name,
            ]);
            return response([
                'message' => 'brand updated successfully'
            ]);
        }else{
            Brand::create([
                'name' => $request->name,
            ]);
            return response([
                'message' => 'brand created successfully'
            ]);
        }
    }
    public function addmerchant(Request $request){
        $request->validate([
            'name' => 'required|unique:merchants,name'
        ],[
            'name.unique' => 'This merchant is already exists'
        ]);
        if($request->id){
            Merchant::where('id',$request->id)->first()->update([
                'name' => $request->name,
            ]);
            return response([
                'message' => 'Merchant updated successfully'
            ]);
        }else{
            Merchant::create([
                'name' => $request->name,
            ]);
            return response([
                'message' => 'Merchant created successfully'
            ]);
        }
    }
    public function addcategory(Request $request){
        if($request->id){
            $category = Category::where('id',$request->id)->first();
            $request->validate([
                'name' => [
                    'required',
                    ValidationRule::unique('categories')->ignore($category->id)
                ]
            ],[
                'name.unique' => 'This category is already exists'
            ]);
        }else{
            $request->validate([
                'name' => 'required|unique:categories,name'
            ],[
                'name.unique' => 'This category is already exists'
            ]);
        }
        if($request->id){
            if($request->category_id){
                $category->update([
                    'category_id' => $request->category_id,
                    'name' => $request->name,
                ]);
            }else{
                $category->update([
                    'name' => $request->name,
                ]);
            }
            return response([
                'message' => 'Category updated successfully'
            ]);
        }else{
            if($request->category_id){
                Category::create([
                    'category_id' => $request->category_id,
                    'name' => $request->name,
                ]);
            }else{
                Category::create([
                    'name' => $request->name,
                ]);
            }
            return response([
                'message' => 'Category created successfully'
            ]);
        }
    }
    public function loadbrands(Request $request){
        if($request->search){
            $brandos = Brand::where('name','LIKE','%' . $request->search . '%');
            if($request->order){
                if($request->orderBy){
                    $brands = $brandos->orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $brands = $brandos->orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $brands = $brandos->latest()->paginate($request->perPage);
            }
        }else{
            if($request->order){
                if($request->orderBy){
                    $brands = Brand::orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $brands = Brand::orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $brands = Brand::latest()->paginate($request->perPage,['*'],'page',$request->page);
            }
        }
        foreach($brands as $br){
            $br->date = Carbon::parse($br->created_at)->format('m/d/Y');
        }
        $lastPage = $brands->lastPage();
        $count = count(Brand::all());
        return response([
            'brands' =>$brands,
            'last' => $lastPage,
            'count' => $count
        ]);
    }
    public function loadmerchants(Request $request){
        if($request->search){
            $brandos = Merchant::where('name','LIKE','%' . $request->search . '%');
            if($request->order){
                if($request->orderBy){
                    $merchants = $brandos->orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $merchants = $brandos->orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $merchants = $brandos->latest()->paginate($request->perPage);
            }
        }else{
            if($request->order){
                if($request->orderBy){
                    $merchants = Merchant::orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $merchants = Merchant::orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $merchants = Merchant::latest()->paginate($request->perPage,['*'],'page',$request->page);
            }
        }
        foreach($merchants as $br){
            $br->date = Carbon::parse($br->created_at)->format('m/d/Y');
        }
        $lastPage = $merchants->lastPage();
        $count = count(Merchant::all());
        return response([
            'merchants' =>$merchants,
            'last' => $lastPage,
            'count' => $count
        ]);
    }
    public function loadcategories(Request $request){
        if($request->search){
            $brandos = Category::where('name','LIKE','%' . $request->search . '%');
            if($request->order){
                if($request->orderBy){
                    $categories = $brandos->orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $categories = $brandos->orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $categories = $brandos->latest()->paginate($request->perPage);
            }
        }else{
            if($request->order){
                if($request->orderBy){
                    $categories = Category::orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $categories = Category::orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $categories = Category::latest()->paginate($request->perPage,['*'],'page',$request->page);
            }
        }
        foreach($categories as $br){
            $br->date = Carbon::parse($br->created_at)->format('m/d/Y');
            $br->parent = Category::where('id',$br->category_id)->first();
        }
        $lastPage = $categories->lastPage();
        $count = count(Category::all());
        return response([
            'categories' =>$categories,
            'last' => $lastPage,
            'count' => $count
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
    public function deletemerchant($id){
        $merchant = Merchant::where('id',$id)->first();
        if($merchant->exists()){
            $merchant->delete();
            return response([
                'message' => 'Merchant is deleted'
            ]);
        }else{
            return response([
                'message' => 'This merchant does not exists'
            ]);
        }
    }
    public function deletecategory($id){
        $category = Category::where('id',$id)->first();
        if($category->exists()){
            $category->delete();
            return response([
                'message' => 'Category is deleted'
            ]);
        }else{
            return response([
                'message' => 'This category does not exists'
            ]);
        }
    }
}
