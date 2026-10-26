<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $acTypes=[
            [
                'name'=>"Balbachat",
                "interest_rate"=>8.00,
                "interest_payout_frequency"=>"SEMI_ANNUAL",
                'status'=>"active"
            ],[
                'name'=>"Regular",
                "interest_rate"=>6.00,
                "interest_payout_frequency"=>"SEMI_ANNUAL",
                'status'=>"active"
            ],
        ];

        foreach ($acTypes as $acType) {
            AccountType::create($acType);
        }
    }
}
