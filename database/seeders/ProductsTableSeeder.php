<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ( $i = 0; $i < 8; $i++ ) {
            $category = random_int(1, 4);
            Product::factory()->create()->categories()->attach($category);
        }

        Product::find(1)->categories()->attach(5);
    }
}
