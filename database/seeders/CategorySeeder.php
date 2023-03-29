<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
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
        Category::create([
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
    }
}
