<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Calculator;

class CalculatorSeeder extends Seeder
{
    public function run(): void
    {
        $calculators = [
            [
                'name' => 'EMI Calculator',
                'slug' => 'emi_calculator',
                'category' => 'Loan',
                'description' => 'Calculate EMI based on loan amount, interest rate, and tenure.',
                'access_type' => 'free',
                'is_active' => true,
            ],
            [
                'name' => 'Housing EMI Calculator',
                'slug' => 'housing_emi_calculator',
                'category' => 'Housing',
                'description' => 'Calculate EMI for housing loans with tenure and interest.',
                'access_type' => 'premium',
                'is_active' => true,
            ],
        ];

        foreach ($calculators as $calc) {
            Calculator::updateOrCreate(
                ['slug' => $calc['slug']], 
                $calc
            );
        }
    }
}