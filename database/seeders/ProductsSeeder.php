<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'category_id'=>'1',
            'name'=>'iPhone 5s',
            'price'=>'120'
        ]);
        DB::table('products')->insert([
            'category_id'=>'2',
            'name'=>'Lenovo',
            'price'=>'250'
        ]);

        DB::table('products')->insert([
            'category_id'=>'3',
            'name'=>'iPhone',
            'price'=>'120'
        ]);

        DB::table('products')->insert([
            'category_id'=>'4',
            'name'=>'Genius',
            'price'=>'50'
        ]);

        DB::table('products')->insert([
            'category_id'=>'5',
            'name'=>'Touch',
            'price'=>'20'
        ]);
    }
}
