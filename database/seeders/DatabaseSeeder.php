<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Information;
use App\Models\Merchant;
use App\Models\Pimage;
use App\Models\Product;
use App\Models\ProductInformation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data1 = [
            'az' => ['name'=>'Komputerlər və notbuklar'],
            'en' => ['name'=>'Laptops and other computer technologies'],
            'ru' => ['name'=>'Ноутбуки и другие компьютерные технологии'],
            'slug' => 'laptops-and-other-computer-technologies',
            'image' => 'computerparent.jpg',
        ];
        $computersparent = Category::create($data1);
        $data2 = [
            'category_id' => $computersparent->id,
            'az' => ['name'=>'Komputerlər'],
            'en' => ['name'=>'Computers'],
            'ru' => ['name'=>'Компьютеры'],
            'slug' => 'computers',
            'image' => 'computers.jpg'
        ];
        Category::create($data2);
        $data3 = [
            'category_id' => $computersparent->id,
            'az' => ['name'=>'Notbuklar'],
            'en' => ['name'=>'Laptops'],
            'ru' => ['name'=>'Ноутбуки'],
            'slug' => 'laptops',
            'image' => 'laptops.jpg'
        ];
        Category::create($data3);
        $data4 = [
            'category_id' => $computersparent->id,
            'az' => ['name'=>'Monitorlar'],
            'en' => ['name'=>'Monitors'],
            'ru' => ['name'=>'Мониторы'],
            'slug' => 'monitors',
            'image' => 'monitors.jpg'
        ];
        Category::create($data4);
        $data5 = [
            'category_id' => $computersparent->id,
            'az' => ['name'=>'Siçan və klavişlər'],
            'en' => ['name'=>'Mouse and keyboards'],
            'ru' => ['name'=>'Мышь и клавиатуры'],
            'slug' => 'mouse-and-keyboards',
            'image' => 'mouseandkeyboards.jpg'
        ];
        $mouseandkeyboards = Category::create($data5);
        $data6 = [
            'category_id' => $mouseandkeyboards->id,
            'az' => ['name'=>'Klavişlər'],
            'en' => ['name'=>'Keyboards'],
            'ru' => ['name'=>'Клавиатуры'],
            'slug' => 'keyboards',
            'image' => 'keyboards.jpg'
        ];
        $keyboard = Category::create($data6);
        $data7 = [
            'category_id' => $mouseandkeyboards->id,
            'az' => ['name'=>'Siçanlar'],
            'en' => ['name'=>'Mouses'],
            'ru' => ['name'=>'Мыши'],
            'slug' => 'mouses',
            'image' => 'mouses.jpg'
        ];
        $mouse = Category::create($data7);
        $infoIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $mouse->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət '.$i],
                'en' => ['title' => 'Spesification in English '.$i],
                'ru' => ['title' => 'Спецификация на русском языке '.$i],
            ]);
            array_push($infoIds,$info->id);
        }
        $data8 = [
            'category_id' => $mouseandkeyboards->id,
            'az' => ['name'=>'Mouse altlıqları'],
            'en' => ['name'=>'Mousepads'],
            'ru' => ['name'=>'Коврики для мыши'],
            'slug' => 'mousepads',
            'image' => 'mousepads.jpg'
        ];
        $mousepad = Category::create($data8);
        $data9 = [
            'az' => ['name'=>'Məişət əşyaları'],
            'en' => ['name'=>'Household appliances'],
            'ru' => ['name'=>'Бытовая техника'],
            'slug' => 'household-appliances',
            'image' => 'households.jpg'
        ];
        $housestuff = Category::create($data9);
        $data10 = [
            'category_id' => $housestuff->id,
            'az' => ['name'=>'Soyuducular'],
            'en' => ['name'=>'Refrigerators'],
            'ru' => ['name'=>'Холодильники'],
            'slug' => 'refrigerators',
            'image' => 'refrigerators.jpg',
        ];
        Category::create($data10);
        $data11 = [
            'category_id' => $housestuff->id,
            'az' => ['name'=>'Paltaryuyan maşınlar'],
            'en' => ['name'=>'Washing machines'],
            'ru' => ['name'=>'Стиральные машины'],
            'slug' => 'washing-machines',
            'image' => 'washingmachines.jpg'
        ];
        $houseprid = Category::create($data11);
        $data12 = [
            'category_id' => $housestuff->id,
            'az' => ['name'=>'Qurutma maşınları'],
            'en' => ['name'=>'Drying machines'],
            'ru' => ['name'=>'Сушильные машины'],
            'slug' => 'drying-machines',
            'image' => 'dryingmachines.jpg',
        ];
        Category::create($data12);
        $data13 = [
            'category_id' => $housestuff->id,
            'az' => ['name'=>'Qabyuyan maşınlar'],
            'en' => ['name'=>'Dishwashers'],
            'ru' => ['name'=>'Посудомоечные машины'],
            'slug' => 'dishwashers',
            'image' => 'dishwashers.jpg',
        ];
        Category::create($data13);
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
        for($i=0;$i <= 5;$i++){
            $rand = array_rand($randomize);
            $randItem = $randomize[$rand];
            $product = Product::create([
                'merchant_id' => $randItem['mrid'],
                'category_id' => $randItem['id'],
                'brand_id' => $randItem['brid'],
                'az' => ['name'=> 'Məhsulun Azərbaycan dilində adı '.$i],
                'en' => ['name'=> 'Product name in English '.$i],
                'ru' => ['name'=> 'Название продукта на русском языке '.$i],
                'slug' => 'product-name-in-english-'.$i,
                'price' => rand(600, 3000),
                'quantity' => rand(5, 20)
            ]);
            Pimage::create([
                'image' => $randItem['image'],
                'product_id' => $product->id,
            ]);
            for($n=0;$n <= 7;$n++){
                $randa = array_rand($randomize);
                $randItema = $randomize[$randa];
                Pimage::create([
                    'image' => $randItema['image'],
                    'product_id' => $product->id,
                ]);
            }
        }
        $exaproduct = Product::create([
            'merchant_id' => $randomize[rand(0,4)]['mrid'],
            'category_id' => $mouse->id,
            'brand_id' => $br1->id,
            'az' => ['name'=> 'Nümunə'],
            'en' => ['name'=> 'Sample'],
            'ru' => ['name'=> 'Образец'],
            'slug' => 'sample',
            'price' => rand(600, 3000),
            'quantity' => rand(5, 20)
        ]);
        $da = 0;
        foreach($infoIds as $infoId){
            ProductInformation::create([
                'product_id' => $exaproduct->id,
                'information_id' => $infoId,
                'az' => ['body'=>'Örnək xüsusiyyət '.$da],
                'en' => ['body'=>'Example specification '.$da],
                'ru' => ['body'=>'Пример спецификации '.$da],
            ]);
            $da++;
        }
        for($t=0;$t <= 7;$t++){
            $randa = array_rand($randomize);
            $randItema = $randomize[$randa];
            Pimage::create([
                'image' => $randItema['image'],
                'product_id' => $exaproduct->id,
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
