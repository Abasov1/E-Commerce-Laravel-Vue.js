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
        $keyboard = Category::create([
            'category_id' => $mouseandkeyboards->id,
            'name' => 'Keyboards',
            'slug' => 'keyboards',
            'image' => 'keyboards.jpg',
        ]);
        $mouse = Category::create([
            'category_id' => $mouseandkeyboards->id,
            'name' => 'Mouses',
            'slug' => 'mouses',
            'image' => 'mouses.jpg',
        ]);
        $mousepad = Category::create([
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
        $houseprid = Category::create([
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
        $mr1 = Merchant::create([
            'name' => 'Bazar store',
            'slug' => 'bazar-store',
            'image' => 'default.jpg',
        ]);
        $mr2 = Merchant::create([
            'name' => 'Irshad Telecom',
            'slug' => 'irshad-telecom',
            'image' => 'default.jpg',
        ]);
        $mr3 = Merchant::create([
            'name' => 'Kontakt Home',
            'slug' => 'kontakt-home',
            'image' => 'default.jpg',
        ]);
        $mr4 = Merchant::create([
            'name' => 'Merchant 4',
            'slug' => 'merchant-4',
            'image' => 'default.jpg',
        ]);
        $mr5 = Merchant::create([
            'name' => 'Merchant 5',
            'slug' => 'merchant-5',
            'image' => 'default.jpg',
        ]);
        $br1 = Brand::create([
            'name' => 'Samsung',
            'slug' => 'samsung',
            'image' => 'default.jpg',
        ]);
        $br2 = Brand::create([
            'name' => 'NVIDIA',
            'slug' => 'nvidia',
            'image' => 'default.jpg',
        ]);
        $br3 = Brand::create([
            'name' => 'RYZEN',
            'slug' => 'ryzen',
            'image' => 'default.jpg',
        ]);
        $br4 = Brand::create([
            'name' => 'BOSCH',
            'slug' => 'bosch',
            'image' => 'default.jpg',
        ]);
        $br5 = Brand::create([
            'name' => 'Xiaomi',
            'slug' => 'ryzen',
            'image' => 'default.jpg',
        ]);
        $randomize = [
            [
                'image'=>'keyboard.jpg',
                'id'=>$keyboard->id,
                'brid'=>$br1->id,
                'mrid'=>$mr1->id
            ],
            [
                'image'=>'mouse.jpg',
                'id'=>$mouse->id,
                'brid'=>$br2->id,
                'mrid'=>$mr2->id
            ],
            [
                'image'=>'mousepad.jpg',
                'id'=>$mousepad->id,
                'brid'=>$br3->id,
                'mrid'=>$mr3->id
            ],
            [
                'image'=>'default.jpg',
                'id'=>$keyboard->id,
                'brid'=>$br5->id,
                'mrid'=>$mr5->id
            ],
            [
                'image'=>'washingmachine.jpg',
                'id'=>$houseprid->id,
                'brid'=>$br4->id,
                'mrid'=>$mr4->id
            ],
        ];
        for($i=0;$i <= 50;$i++){
            $rand = array_rand($randomize);
            $randItem = $randomize[$rand];
            $product = Product::create([
                'merchant_id' => $randItem['mrid'],
                'category_id' => $randItem['id'],
                'brand_id' => $randItem['brid'],
                'name' => 'example product',
                'slug' => 'example-product',
                'price' => rand(600, 3000)
            ]);
            Pimage::create([
                'image' => $randItem['image'],
                'product_id' => $product->id,
            ]);
        }
        $housepr = Product::factory(20)->create([
            'merchant_id' => $randomize[rand(0,4)]['mrid'],
            'category_id' => $houseprid->id,
            'brand_id' => $br1->id,
            'name' => 'example product',
            'slug' => 'example-product',
            'price' => rand(600, 3000)
        ]);
        foreach($housepr as $pr){
            Pimage::create([
                'image' => 'washingmachine.jpg',
                'product_id' => $pr->id,
            ]);
        }
        Brand::factory(10)->create([
            'name' => 'example brand',
            'slug' => 'example-brand',
            'image' => 'default.jpg',
        ]);
        Merchant::factory(10)->create([
            'name' => 'example merchant',
            'slug' => 'example-merchant',
            'image' => 'default.jpg',
        ]);
    }
}
