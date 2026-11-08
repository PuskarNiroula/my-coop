<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('unit_categories')->insert([
            [
                'unit_category_id' => 1,
                'name' => 'Weight',
                'status' => 'active',
                'is_fixed'=>true
            ],
            [
                'unit_category_id' => 2,
                'name' => 'Liquid',
                'status' => 'active',
                'is_fixed'=>true
            ],
            [
                'unit_category_id' => 3,
                'name' => 'Item',
                'status' => 'active',
                'is_fixed'=>true
            ],
        ]);
    }
}
