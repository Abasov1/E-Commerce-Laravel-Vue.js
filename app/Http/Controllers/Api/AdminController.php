<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Pimage;
use App\Models\Review;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Role;
use App\Models\Slider;
use App\Models\Information;
use App\Models\ProductInformation;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Validation\ValidationRuleParser;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;



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
        $category = Category::with(['blocophobia.blocophobia','subcategories','informations'])->whereTranslationLike('name','%' . $request->category .'%')->first();
        if($category && !$request->parent){
            if($category->subcategories->count()){
                $category = null;
            }
        }
        if($category && $request->parent){
            if($category->blocophobia){
                if($category->blocophobia->blocophobia){
                    $category = null;
                }
            }
        }
        return response([
            'category' => $category,
            'request' => $request->category
        ]);
    }
    public function addproduct(PostRequest $request){
        if($request->id){
            $request->validate([
                'category_id' => 'required',
                'merchant_id' => 'required',
                'brand_id' => 'required',
                'az_name' => 'required',
                'en_name' => 'required',
                'ru_name' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                'az_inf' => 'required',
                'en_inf' => 'required',
                'ru_inf' => 'required'
            ]);
        }else{
            $request->validate($request->rules());
        }
        if(Product::whereTranslation('name',$request->az_name)->orWhereTranslation('name',$request->en_name)->orWhereTranslation('name',$request->ru_name)->count()){
            return response([
                'message' => 'This product already exists'
            ]);
        }
        $az_infos = json_decode($request->input('az_inf'));
        $en_infos = json_decode($request->input('en_inf'));
        $ru_infos = json_decode($request->input('ru_inf'));
        foreach($az_infos as $az){
            $az->az_body = $az->body;
            $az->en_body = '';
            $az->ru_body = '';
            foreach($en_infos as $niye){
                if($az->id === $niye->id){
                    $az->en_body = $niye->body;
                }
            }
            foreach($ru_infos as $neucun){
                if($az->id === $neucun->id){
                    $az->ru_body = $neucun->body;
                }
            }
        }
        if($request->id){
            Product::where('id',$request->id)->first()->update([
                'category_id' => $request->category_id,
                'merchant_id' => $request->merchant_id,
                'brand_id' => $request->brand_id,
                'az' => ['name'=>$request->az_name],
                'en' => ['name'=>$request->en_name],
                'ru' => ['name'=>$request->ru_name],
                'slug' => Str::slug($request->en_name),
                'price' => $request->price,
                'quantity' => $request->quantity
            ]);
            $product = Product::with('infbody')->where('id',$request->id)->first();
            $product->infbody()->delete();
            foreach($az_infos as $info){
                if($info->selected && $info->body){
                    ProductInformation::create([
                        'product_id' => $product->id,
                        'information_id' => $info->id,
                        'az' => ['body'=>$info->az_body],
                        'en' => ['body'=>$info->en_body],
                        'ru' => ['body'=>$info->ru_body],
                    ]);
                }
            }
        }else{
            $product = Product::create([
                'category_id' => $request->category_id,
                'merchant_id' => $request->merchant_id,
                'brand_id' => $request->brand_id,
                'az' => ['name'=>$request->az_name],
                'en' => ['name'=>$request->en_name],
                'ru' => ['name'=>$request->ru_name],
                'slug' => Str::slug($request->en_name),
                'price' => $request->price,
                'quantity' => $request->quantity
            ]);
            foreach($az_infos as $info){
                if($info->selected && $info->body){
                    ProductInformation::create([
                        'product_id' => $product->id,
                        'information_id' => $info->id,
                        'az' => ['body'=>$info->az_body],
                        'en' => ['body'=>$info->en_body],
                        'ru' => ['body'=>$info->ru_body],
                    ]);
                }
            }
        }
        if($request->hasFile('images')){
            foreach($request->file('images') as $imaga){
                $image = Image::make($imaga);
                $image->fit(600, 600);
                $ex = $imaga->getClientOriginalExtension();
                $file = uniqid() .'.'. $ex;
                $image->save(public_path('storage/products/'.$file));
                Pimage::create([
                    'image' => $file,
                    'product_id' => $product->id
                ]);
            }
        }else{
            $file = 'default.png';
        }


        if($request->id){
            return response([
                'message' => 'product updated successfully'
            ]);
        }else{
            return response([
                'message' => 'product created successfully'
            ]);
        }
    }
    public function addbrand(Request $request){
        if($request->hasFile('image')){
            $image = Image::make($request->file('image'));
            $image->fit(600, 600);
            $ex = $request->file('image')->getClientOriginalExtension();
            $file = uniqid() .'.'. $ex;
            $image->save(public_path('storage/brands/'.$file));
        }else{
            $file = 'default.png';
        }
        $request->validate([
            'name' => 'required|unique:brands,name'
        ],[
            'name.unique' => 'This brand is already exists'
        ]);
        if($request->id){
            Brand::where('id',$request->id)->first()->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            return response([
                'message' => 'brand updated successfully'
            ]);
        }else{
            Brand::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $file
            ]);
            return response([
                'message' => 'brand created successfully'
            ]);
        }
    }
    public function addmerchant(Request $request){
        if($request->hasFile('image')){
            $image = Image::make($request->file('image'));
            $image->fit(600, 600);
            $ex = $request->file('image')->getClientOriginalExtension();
            $file = uniqid() .'.'. $ex;
            $image->save(public_path('storage/merchants/'.$file));
        }else{
            $file = 'default.png';
        }
        $request->validate([
            'name' => 'required|unique:merchants,name'
        ],[
            'name.unique' => 'This merchant is already exists'
        ]);
        if($request->id){
            Merchant::where('id',$request->id)->first()->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $file
            ]);
            return response([
                'message' => 'Merchant updated successfully'
            ]);
        }else{
            Merchant::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $file
            ]);
            return response([
                'message' => 'Merchant created successfully'
            ]);
        }
    }
    public function addcategory(Request $request){
        if($request->hasFile('image')){
            $image = Image::make($request->file('image'));
            $image->fit(600, 600);
            $ex = $request->file('image')->getClientOriginalExtension();
            $file = uniqid() .'.'. $ex;
            $image->save(public_path('storage/categories/'.$file));
        }else{
            $file = 'default.png';
        }
        $az_infos = json_decode($request->input('az_inf'));
        $en_infos = json_decode($request->input('en_inf'));
        $ru_infos = json_decode($request->input('ru_inf'));
        $thosewhofacedagainsgod = json_decode($request->input('deleted'));
        foreach($az_infos as $az){
            $az->az_title = $az->title;
            $az->en_title = '';
            $az->ru_title = '';
            foreach($en_infos as $niye){
                if($az->id === $niye->id){
                    $az->en_title = $niye->title;
                }
            }
            foreach($ru_infos as $neucun){
                if($az->id === $neucun->id){
                    $az->ru_title = $neucun->title;
                }
            }
        }
        if($request->id){
            $category = Category::with('informations')->where('id',$request->id)->first();
            $request->validate([
                'az_name' => 'required',
                'en_name' => 'required',
                'ru_name' => 'required',
            ]);
        }else{
            $request->validate([
                'az_name' => 'required',
                'en_name' => 'required',
                'ru_name' => 'required',
                'image' => 'required',
            ]);
        }
        if($request->id){
            if($request->category_id){
                $category->update([
                    'category_id' => $request->category_id,
                    'az' => ['name' => $request->az_name],
                    'en' => ['name' => $request->en_name],
                    'ru' => ['name' => $request->ru_name],
                    'slug' => Str::slug($request->en_name),
                ]);
            }else{
                $category->update([
                    'az' => ['name' => $request->az_name],
                    'en' => ['name' => $request->en_name],
                    'ru' => ['name' => $request->ru_name],
                    'slug' => Str::slug($request->en_name),
                ]);
            }
                foreach($az_infos as $inf){
                    if($inf->base){
                        Information::where('id',$inf->id)->first()->update([
                            'category_id' => $category->id,
                            'az' => ['title' => $inf->az_title],
                            'en' => ['title' => $inf->en_title],
                            'ru' => ['title' => $inf->ru_title]
                        ]);
                    }else{
                        Information::create([
                            'category_id' => $category->id,
                            'az' => ['title' => $inf->az_title],
                            'en' => ['title' => $inf->en_title],
                            'ru' => ['title' => $inf->ru_title]
                        ]);
                    }
                }
                if(count($thosewhofacedagainsgod) > 0){
                    foreach($thosewhofacedagainsgod as $thosewillbepunished){
                        Information::where('id',$thosewillbepunished->id)->first()->delete();
                    }
                }
                $category = Category::with('informations')->where('id',$request->id)->first();
                if($category->category_id){
                    $category->parent = Category::where('id',$category->category_id)->first();
                }
                return response([
                    'message' => 'Category updated successfully',
                    'category' => $category
                ]);
        }else{
            if($request->category_id){
                $category = Category::create([
                    'category_id' => $request->category_id,
                    'az' => ['name' => $request->az_name],
                    'en' => ['name' => $request->en_name],
                    'ru' => ['name' => $request->ru_name],
                    'slug' => Str::slug($request->en_name),
                    'image' => $file
                ]);
            }else{
                $category = Category::create([
                    'az' => ['name' => $request->az_name],
                    'en' => ['name' => $request->en_name],
                    'ru' => ['name' => $request->ru_name],
                    'slug' => Str::slug($request->en_name),
                    'image' => $file
                ]);
            }
            foreach($az_infos as $inf){
                Information::create([
                    'category_id' => $category->id,
                    'az' => ['title' => $inf->az_title],
                    'en' => ['title' => $inf->en_title],
                    'ru' => ['title' => $inf->ru_title]
                ]);
            }
            return response([
                'message' => 'Category created successfully'
            ]);
        }
    }
    public function adduser(Request $request){
        $auth = auth()->user();
        if($auth->role() != 'admin'){
            return response([
                'message' => 'This action is unauthorizated'
            ],403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($request->id){
            $user = User::whereId($request->id)->first();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if($request->is_moderator){
                $role = Role::where('name','moderator')->first();
                $user->roles()->attach($role->id);
            }else{
                $role = Role::where('name','moderator')->first();
                $user->roles()->detach($role->id);
            }
            return response([
                'message' => 'User updated successfully',
            ]);
        }else{
            if(User::where('email',$request->email)->exists()){
                return response([
                    'message' => 'This email is already taken'
                ],403);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if($request->is_moderator){
                $role = Role::where('name','moderator')->first();
                $user->roles()->attach($role->id);
            }
            return response([
                'message' => 'User created successfully',
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
            $br->prcount = $br->products->count();
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
            $br->prcount = $br->products->count();

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
            $brandos = Category::with('informations')->where('name','LIKE','%' . $request->search . '%');
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
                    $categories = Category::with('informations')->orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $categories = Category::with('informations')->orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $categories = Category::with('informations')->latest()->paginate($request->perPage,['*'],'page',$request->page);
            }
        }
        foreach($categories as $br){
            $br->date = Carbon::parse($br->created_at)->format('m/d/Y');
            $br->parent = Category::where('id',$br->category_id)->first();
            $br->prcount = $br->products->count();
        }
        $lastPage = $categories->lastPage();
        $count = count(Category::all());
        return response([
            'categories' =>$categories,
            'last' => $lastPage,
            'count' => $count
        ]);
    }
    public function loadproducts(Request $request){
        if($request->search){
            $brandos = Product::with(['merchant','brand','category.informations','infbody.title','images'])->whereTranslationLike('name','%' . $request->search . '%');
            if($request->order){
                if($request->orderBy){
                    $products = $brandos->orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $products = $brandos->orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $products = $brandos->latest()->paginate($request->perPage);
            }
        }else{
            if($request->order){
                if($request->orderBy){
                    $products = Product::with(['merchant','brand','category.informations','infbody.title','images'])->orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $products = Product::with(['merchant','brand','category.informations','infbody.title','images'])->orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $products = Product::with(['merchant','brand','category.informations','infbody.title','images'])->latest()->paginate($request->perPage,['*'],'page',$request->page);
            }
        }
        foreach($products as $br){
            $br->date = Carbon::parse($br->created_at)->format('m/d/Y');
        }
        $lastPage = $products->lastPage();
        $count = count(Product::all());
        foreach($products as $pr){
            $pr->titles = $pr->inftitle();
        }
        return response([
            'products' =>$products,
            'last' => $lastPage,
            'count' => $count
        ]);
    }
    public function loadusers(Request $request){
        if($request->search){
            $brandos = User::where('name','LIKE','%' . $request->search . '%')->orWhere('email','LIKE','%' . $request->search . '%');
            if($request->order){
                if($request->orderBy){
                    $users = $brandos->orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $users = $brandos->orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $users = $brandos->latest()->paginate($request->perPage);
            }
        }else{
            if($request->order){
                if($request->orderBy){
                    $users = User::orderBy('name','asc')->paginate($request->perPage);
                }else{
                    $users = User::orderBy('name','desc')->paginate($request->perPage);
                }
            }else{
                $users = User::latest()->paginate($request->perPage,['*'],'page',$request->page);
            }
        }
        foreach($users as $br){
            $br->role = $br->role();
            $br->date = Carbon::parse($br->created_at)->format('m/d/Y');
        }
        $lastPage = $users->lastPage();
        $count = count(User::all());
        return response([
            'users' =>$users,
            'last' => $lastPage,
            'count' => $count
        ]);
    }
    public function loadorders(Request $request){
        if($request->search){
            $brandos = Order::with(['user','products'])->where('transaction_id','LIKE','%' . $request->search . '%');
            $orders = $brandos->latest()->paginate($request->perPage);
        }else{
            $orders = Order::with(['user','products'])->latest()->paginate($request->perPage,['*'],'page',$request->page);
        }
        foreach($orders as $br){
            $br->transaction_id = $br->makeVisible('transaction_id')->transaction_id;
            $br->date = Carbon::parse($br->created_at)->format('m/d/Y');
        }
        $lastPage = $orders->lastPage();
        $count = count(Order::all());
        return response([
            'orders' =>$orders,
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
    public function deleteproduct($id){
        $products = Product::where('id',$id)->first();
        if($products->exists()){
            $products->delete();
            return response([
                'message' => 'Product is deleted'
            ]);
        }else{
            return response([
                'message' => 'This product does not exists'
            ]);
        }
    }
    public function deleteuser($id){
        if(auth()->user()->role() != 'admin'){
            return response([
                'message' => 'This action is unauthorizated'
            ],403);
        }
        $user = User::where('id',$id)->first();
        if($user->exists()){
            $user->delete();
            return response([
                'message' => 'User is deleted'
            ]);
        }else{
            return response([
                'message' => 'This user does not exists'
            ]);
        }
    }
    public function deletereview($id){
        if(!auth()->user()->isAdmin() && !auth()->user()->isModerator()){
            return response([
                'message' => 'This action is unauthorizated'
            ],403);
        }
        Review::where('id',$id)->first()->delete();
        return response([
            'message' => 'Review deleted successfully'
        ]);
    }
    public function changeimage(Request $request){
        if($request->hasFile('image')){
            $image = Image::make($request->file('image'));
            $image->fit(600, 600);
            $ex = $request->file('image')->getClientOriginalExtension();
            $file = uniqid() .'.'. $ex;
            $image->save(public_path('storage/products/'.$file));
        }else{
            $file = 'default.png';
        }
        if($request->delete === 'true'){
            Pimage::where(['id'=>$request->id,'product_id'=>$request->product_id])->first()->delete();
        }elseif($request->add === 'true'){
            Pimage::create([
                'product_id'=>$request->product_id,
                'image'=>$file
            ]);
        }else{
            Pimage::where(['id'=>$request->id,'product_id'=>$request->product_id])->first()->update([
                'image' => $file
            ]);
        }
        return response([
            'product' => Product::with('images')->where('id',$request->product_id)->first()
        ]);
    }
    public function changemerchantimage(Request $request){
        if($request->hasFile('image')){
            $image = Image::make($request->file('image'));
            $image->fit(600, 600);
            $ex = $request->file('image')->getClientOriginalExtension();
            $file = uniqid() .'.'. $ex;
            $image->save(public_path('storage/merchants/'.$file));
        }else{
            $file = 'default.png';
        }
        $merchant = Merchant::where('id',$request->merchant_id)->first();
        $merchant->update([
            'image' => $file
        ]);
        return response([
            'merchant' => $merchant
        ]);
    }
    public function changebrandimage(Request $request){
        if($request->hasFile('image')){
            $image = Image::make($request->file('image'));
            $image->fit(600, 600);
            $ex = $request->file('image')->getClientOriginalExtension();
            $file = uniqid() .'.'. $ex;
            $image->save(public_path('storage/brands/'.$file));
        }else{
            $file = 'default.png';
        }
        $brand = Brand::where('id',$request->brand_id)->first();
        $brand->update([
            'image' => $file
        ]);
        return response([
            'brand' => $brand
        ]);
    }
    public function changecategoryimage(Request $request){
        if($request->hasFile('image')){
            $image = Image::make($request->file('image'));
            $image->fit(600, 600);
            $ex = $request->file('image')->getClientOriginalExtension();
            $file = uniqid() .'.'. $ex;
            $image->save(public_path('storage/categories/'.$file));
        }else{
            $file = 'default.png';
        }
        $category = Category::where('id',$request->category_id)->first();
        $category->update([
            'image' => $file
        ]);
        return response([
            'category' => $category
        ]);
    }
    public function changeslider(Request $request){
        if($request->hasFile('image')){
            $image = Image::make($request->file('image'));
            $image->fit(1000, 450);
            $ex = $request->file('image')->getClientOriginalExtension();
            $file = uniqid() .'.'. $ex;
            $image->save(public_path('storage/sliders/'.$file));
        }else{
            $file = 'default.jpg';
        }
        if($request->lang == "1"){
            Slider::where('id',$request->id)->first()->update([
                'az_image' => $file
            ]);
        }elseif($request->lang == '2'){
           Slider::where('id',$request->id)->first()->update([
                'en_image' => $file
            ]);
        }elseif($request->lang == '3'){
           Slider::where('id',$request->id)->first()->update([
                'ru_image' => $file
            ]);
        }
        
        return Slider::all();
    }
    public function changelang($lang,Request $request){
        if(!auth()->user()->isAdmin() && !auth()->user()->isModerator()){
            return response([
                'message' => 'This action is unauthorizated'
            ],403);
        }
        // Read the contents of the JSON file
        $json = file_get_contents(resource_path("lang/{$lang}.json"));

        // Decode the JSON data
        $data = json_decode($json, true);

        // Update the JSON data with the new values from the request
        $data = $request->json;
        // Encode the modified JSON data
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Write the updated contents back to the file
        file_put_contents(resource_path("lang/{$lang}.json"), $json);

        $json = file_get_contents(resource_path("lang/{$lang}.json"));
        // Return a success response
        return response($json);
    }
}
