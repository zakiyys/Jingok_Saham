<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['ticker' => 'ANTM', 'name' => 'Aneka Tambang'],
            ['ticker' => 'BREN', 'name' => 'Barito Renewables Energy'],
            ['ticker' => 'BRMS', 'name' => 'Bumi Resources Minerals'],
            ['ticker' => 'BUMI', 'name' => 'Bumi Resources'],
            ['ticker' => 'DEWA', 'name' => 'Darma Henwa'],
            ['ticker' => 'ELIT', 'name' => 'Sentul City'],
            ['ticker' => 'INET', 'name' => 'Indo Internet'],
        ];

        foreach ($companies as $company) {
            Company::updateOrCreate(
                ['ticker' => $company['ticker']],
                [
                    'name' => $company['name'],
                    'exchange' => 'IDX',
                    'is_active' => true,
                ]
            );
        }
    }
}
