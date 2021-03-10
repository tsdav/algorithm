<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['phone','pc','headphones','mouse','touchpads'];
        for ($i =0 ; $i < count($categories);$i++) {
            DB::table('categories')->insert([
                'category_name' => $categories[$i]
            ]);
        }
    }
}
