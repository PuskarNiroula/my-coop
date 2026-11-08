<?php

namespace Database\Seeders;

use App\Models\UnitCategory;
use Illuminate\Database\Seeder;

class BaseUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitCategory::find(1)->update([
            "base_unit_id" => 1,
        ]);
        UnitCategory::find(2)->update([
            "base_unit_id" => 3,
        ]);
        UnitCategory::find(3)->update([
            "base_unit_id" => 5,
        ]);

    }
}
