<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('units')->insert([
            [
                'unit_id' => 1,
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'conversion_rate' => 1000,
                'unit_category_id' => 1,
                'status' => "active",
                'is_fixed'=>true
            ],
            [
                'unit_id' => 2,
                'name' => 'Gram',
                'symbol' => 'g',
                'conversion_rate' => 1,
                'unit_category_id' => 1,
                'status' => "active",
                'is_fixed'=>true
            ],
            [
                'unit_id' => 4,
                'name' => 'Liter',
                'symbol' => 'L',
                'conversion_rate' => 1000,
                'unit_category_id' => 2,
                'status' => "active",
                'is_fixed'=>true
            ],
            [
                'unit_id' => 3,
                'name' => 'Milliliter',
                'symbol' => 'ml',
                'conversion_rate' => 1,
                'unit_category_id' => 2,
                'status' => "active",
                'is_fixed'=>true
            ],
            [
                'unit_id' => 5,
                'name' => 'Piece',
                'symbol' => 'pcs',
                'conversion_rate' => 1,
                'unit_category_id' => 3,
                'status' => "active",
                'is_fixed'=>true
            ],
            [
                'unit_id' => 6,
                'name' => 'Dozen',
                'symbol' => 'doz',
                'conversion_rate' => 12,
                'unit_category_id' => 3,
                'status' => "active",
                'is_fixed'=>true
            ],
        ]);
    }
}
