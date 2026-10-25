<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthSeeder extends Seeder
{
    public function run(): void
    {
        $months = [
            'Baisakh',
            'Jestha',
            'Ashadh',
            'Shrawan',
            'Bhadra',
            'Ashwin',
            'Kartik',
            'Mangsir',
            'Poush',
            'Magh',
            'Falgun',
            'Chaitra',
        ];

        foreach ($months as $month) {
            DB::table('months')->updateOrInsert(
                ['name' => $month],
                ['name' => $month]
            );
        }
    }
}
