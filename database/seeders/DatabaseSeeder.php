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
use App\Models\Role;
use App\Models\Slider;
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
        $admin = Role::create([
            'name' => 'admin'
        ]);
        Role::create([
            'name' => 'moderator'
        ]);
        SLider::factory(4)->create([
            'az_image' => 'az_default.jpg',
            'en_image' => 'en_default.jpg',
            'ru_image' => 'ru_default.jpg'
        ]);
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
        $computer = Category::create($data2);
        $computerIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $computer->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Komputerlər'.$i],
                'en' => ['title' => 'Specification in English - Computers'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Компьютеры'.$i],
            ]);
            array_push($computerIds,$info->id);
        }
        $data3 = [
            'category_id' => $computersparent->id,
            'az' => ['name'=>'Notbuklar'],
            'en' => ['name'=>'Laptops'],
            'ru' => ['name'=>'Ноутбуки'],
            'slug' => 'laptops',
            'image' => 'laptops.jpg'
        ];
        $laptop = Category::create($data3);
        $laptopIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $laptop->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Notbuklar'.$i],
                'en' => ['title' => 'Specification in English - Laptops'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Ноутбуки'.$i],
            ]);
            array_push($laptopIds,$info->id);
        }
        $data4 = [
            'category_id' => $computersparent->id,
            'az' => ['name'=>'Monitorlar'],
            'en' => ['name'=>'Monitors'],
            'ru' => ['name'=>'Мониторы'],
            'slug' => 'monitors',
            'image' => 'monitors.jpg'
        ];
        $monitor = Category::create($data4);
        $monitorIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $monitor->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Monitorlar'.$i],
                'en' => ['title' => 'Specification in English - Monitors'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Мониторы'.$i],
            ]);
            array_push($monitorIds,$info->id);
        }
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
        $keyboardIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $keyboard->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Klavişlər'.$i],
                'en' => ['title' => 'Specification in English - Keyboards'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Клавиатуры'.$i],
            ]);
            array_push($keyboardIds,$info->id);
        }
        $data7 = [
            'category_id' => $mouseandkeyboards->id,
            'az' => ['name'=>'Siçanlar'],
            'en' => ['name'=>'Mouses'],
            'ru' => ['name'=>'Мыши'],
            'slug' => 'mouses',
            'image' => 'mouses.jpg'
        ];
        $mouse = Category::create($data7);
        $mouseIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $mouse->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Siçanlar'.$i],
                'en' => ['title' => 'Specification in English - Mouses'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Мыши'.$i],
            ]);
            array_push($mouseIds,$info->id);
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
        $mousepadIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $mousepad->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Mouse altlıqları'.$i],
                'en' => ['title' => 'Specification in English - Mousepads'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Коврики для мыши'.$i],
            ]);
            array_push($mousepadIds,$info->id);
        }
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
        $refrigerator = Category::create($data10);
        $refrigeratorIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $refrigerator->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Soyuducular'.$i],
                'en' => ['title' => 'Specification in English - Refrigerators'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Холодильники'.$i],
            ]);
            array_push($refrigeratorIds,$info->id);
        }
        $data11 = [
            'category_id' => $housestuff->id,
            'az' => ['name'=>'Paltaryuyan maşınlar'],
            'en' => ['name'=>'Washing machines'],
            'ru' => ['name'=>'Стиральные машины'],
            'slug' => 'washing-machines',
            'image' => 'washingmachines.jpg'
        ];
        $washingmachine = Category::create($data11);
        $washingmachineIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $washingmachine->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Paltaryuyan maşınlar'.$i],
                'en' => ['title' => 'Specification in English - Washing machines'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Стиральные машины'.$i],
            ]);
            array_push($washingmachineIds,$info->id);
        }
        $data12 = [
            'category_id' => $housestuff->id,
            'az' => ['name'=>'Qurutma maşınları'],
            'en' => ['name'=>'Drying machines'],
            'ru' => ['name'=>'Сушильные машины'],
            'slug' => 'drying-machines',
            'image' => 'dryingmachines.jpg',
        ];
        $dryingmachine = Category::create($data12);
        $dryingmachineIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $dryingmachine->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Qurutma maşınları'.$i],
                'en' => ['title' => 'Specification in English - Drying machines'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Сушильные машины'.$i],
            ]);
            array_push($dryingmachineIds,$info->id);
        }
        $data13 = [
            'category_id' => $housestuff->id,
            'az' => ['name'=>'Qabyuyan maşınlar'],
            'en' => ['name'=>'Dishwashers'],
            'ru' => ['name'=>'Посудомоечные машины'],
            'slug' => 'dishwashers',
            'image' => 'dishwashers.jpg',
        ];
        $dishwasher =  Category::create($data13);
        $dishwasherIds = [];
        for($i=0;$i <= 15;$i++){
            $info = Information::create([
                'category_id' => $dishwasher->id,
                'az' => ['title' => 'Azərbaycanca xüsusiyyət - Qabyuyan maşınlar'.$i],
                'en' => ['title' => 'Specification in English - Dishwashers'.$i],
                'ru' => ['title' => 'Спецификация на русском языке - Посудомоечные машины'.$i],
            ]);
            array_push($dishwasherIds,$info->id);
        }
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);
        $user->roles()->attach($admin->id);
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
                'mrid'=>$mr1->id,
                'ids'=>$keyboardIds
            ],
            [
                'image'=>'mouse.jpg',
                'id'=>$mouse->id,
                'brid'=>$br2->id,
                'mrid'=>$mr2->id,
                'ids'=>$mouseIds
            ],
            [
                'image'=>'mousepad.jpg',
                'id'=>$mousepad->id,
                'brid'=>$br3->id,
                'mrid'=>$mr3->id,
                'ids'=>$mousepadIds
            ],
            [
                'image'=>'laptop.jpg',
                'id'=>$laptop->id,
                'brid'=>$br5->id,
                'mrid'=>$mr5->id,
                'ids'=>$laptopIds
            ],
            [
                'image'=>'monitor.jpg',
                'id'=>$monitor->id,
                'brid'=>$br5->id,
                'mrid'=>$mr5->id,
                'ids'=>$monitorIds
            ],
            [
                'image'=>'computer.jpg',
                'id'=>$computer->id,
                'brid'=>$br5->id,
                'mrid'=>$mr5->id,
                'ids'=>$computerIds
            ],
            [
                'image'=>'washingmachine.jpg',
                'id'=>$washingmachine->id,
                'brid'=>$br4->id,
                'mrid'=>$mr4->id,
                'ids'=>$washingmachineIds
            ],
            [
                'image'=>'dryingmachine.jpg',
                'id'=>$dryingmachine->id,
                'brid'=>$br4->id,
                'mrid'=>$mr4->id,
                'ids'=>$dryingmachineIds
            ],
            [
                'image'=>'refrigerator.jpg',
                'id'=>$refrigerator->id,
                'brid'=>$br4->id,
                'mrid'=>$mr4->id,
                'ids'=>$refrigeratorIds
            ],
            [
                'image'=>'dishwasher.jpg',
                'id'=>$dishwasher->id,
                'brid'=>$br4->id,
                'mrid'=>$mr4->id,
                'ids'=>$dishwasherIds
            ],
        ];
        for($i=0;$i <= 10000;$i++){
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
            foreach($randItem['ids'] as $infoId){
                ProductInformation::create([
                    'product_id' => $product->id,
                    'information_id' => $infoId,
                    'az' => ['body'=>'Örnək xüsusiyyət '.$i],
                    'en' => ['body'=>'Example specification '.$i],
                    'ru' => ['body'=>'Пример спецификации '.$i],
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
        foreach($mouseIds as $infoId){
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
