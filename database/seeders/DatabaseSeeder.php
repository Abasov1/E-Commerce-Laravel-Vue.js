<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Pimage;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $computersparent = Category::create([
            'name' => 'Laptops and other computer technologies',
            'slug' => 'laptops-and-other-computer-technologies',
            'image' => 'computerparent.jpg',
        ]);
        Category::create([
            'category_id' => $computersparent->id,
            'name' => 'Computers',
            'slug' => 'computers',
            'image' => 'computers.jpg',
        ]);
        Category::create([
            'category_id' => $computersparent->id,
            'name' => 'Laptops',
            'slug' => 'laptops',
            'image' => 'laptops.jpg',
        ]);
        Category::create([
            'category_id' => $computersparent->id,
            'name' => 'Monitors',
            'slug' => 'monitors',
            'image' => 'monitors.jpg',
        ]);
        $mouseandkeyboards = Category::create([
            'category_id' => $computersparent->id,
            'name' => 'Mouse and keyboards',
            'slug' => 'mouse-and-keyboards',
            'image' => 'mouseandkeyboards.jpg',
        ]);
        $prcat = Category::create([
            'category_id' => $mouseandkeyboards->id,
            'name' => 'Keyboards',
            'slug' => 'keyboards',
            'image' => 'keyboards.jpg',
        ]);
        Category::create([
            'category_id' => $mouseandkeyboards->id,
            'name' => 'Mouses',
            'slug' => 'mouses',
            'image' => 'mouses.jpg',
        ]);
        Category::create([
            'category_id' => $mouseandkeyboards->id,
            'name' => 'Mousepads',
            'slug' => 'mousepads',
            'image' => 'mousepads.jpg',
        ]);
        $housestuff = Category::create([
            'name' => 'Household appliances',
            'slug' => 'household-appliances',
            'image' => 'households.jpg',
        ]);
        Category::create([
            'category_id' => $housestuff->id,
            'name' => 'Refrigerators',
            'slug' => 'refrigerators',
            'image' => 'refrigerators.jpg',
        ]);
        Category::create([
            'category_id' => $housestuff->id,
            'name' => 'Washing machines',
            'slug' => 'washing-machines',
            'image' => 'washingmachines.jpg',
        ]);
        Category::create([
            'category_id' => $housestuff->id,
            'name' => 'Drying machines',
            'slug' => 'drying-machines',
            'image' => 'dryingmachines.jpg',
        ]);
        Category::create([
            'category_id' => $housestuff->id,
            'name' => 'Dishwashers',
            'slug' => 'dishwashers',
            'image' => 'dishwashers.jpg',
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);
        $mr = Merchant::create([
            'name' => 'example brand',
            'slug' => 'example-merchant',
            'image' => 'default.png',
        ]);
        $br = Brand::create([
            'name' => 'example brand',
            'slug' => 'example-brand',
            'image' => 'default.jpg',
        ]);
        $product = Product::factory(30)->create([
            'merchant_id' => $mr->id,
            'category_id' => $prcat->id,
            'brand_id' => $br->id,
            'name' => 'example product',
            'slug' => 'example-product',
            'price' => '12313',
        ]);
        foreach($product as $pr){
            Pimage::create([
                'image' => 'default.jpg',
                'product_id' => $pr->id,
            ]);
        }
        Brand::factory(10)->create([
            'name' => 'example brand',
            'slug' => 'example-brand',
            'image' => 'default.jpg',
        ]);
    }
}
