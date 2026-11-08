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
                'unit_type' => 'weight',
                'unit_category_id' => 1,
                'status' => "active",
            ],
            [
                'unit_id' => 2,
                'name' => 'Gram',
                'symbol' => 'g',
                'unit_type' => 'weight',
                'unit_category_id' => 1,
                'status' => "active",
            ],
            [
                'unit_id' => 3,
                'name' => 'Milligram',
                'symbol' => 'mg',
                'unit_type' => 'weight',
                'unit_category_id' => 1,
                'status' => "active",
            ],
            [
                'unit_id' => 4,
                'name' => 'Liter',
                'symbol' => 'L',
                'unit_type' => 'liquid',
                'unit_category_id' => 2,
                'status' => "active",
            ],
            [
                'unit_id' => 5,
                'name' => 'Milliliter',
                'symbol' => 'ml',
                'unit_type' => 'liquid',
                'unit_category_id' => 2,
                'status' => "active",
            ],
            [
                'unit_id' => 6,
                'name' => 'Piece',
                'symbol' => 'pcs',
                'unit_type' => 'item',
                'unit_category_id' => 3,
                'status' => "active",
            ],
            [
                'unit_id' => 7,
                'name' => 'Dozen',
                'symbol' => 'doz',
                'unit_type' => 'item',
                'unit_category_id' => 3,
                'status' => "active",
            ],
            [
                'unit_id' => 8,
                'name' => 'Box',
                'symbol' => 'box',
                'unit_type' => 'item',
                'unit_category_id' => 3,
                'status' => "active",
            ],
            [
                'unit_id' => 9,
                'name' => 'Packet',
                'symbol' => 'pkt',
                'unit_type' => 'item',
                'unit_category_id' => 3,
                'status' => "active",
            ],
        ]);
    }
}
