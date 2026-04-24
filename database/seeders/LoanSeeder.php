<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Loan;
use Illuminate\Support\Str;

class LoanSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {

            Loan::create([
                'user_id' => $i, 
                'step_completed' => 3,
                'data' => [
                    'pan' => strtoupper(Str::random(5)) . rand(1000,9999) . strtoupper(Str::random(1)),
                    'full_name' => 'User ' . $i,
                    'dob' => now()->subYears(rand(22, 50))->format('Y-m-d'),
                    'city' => 'Ahmedabad',
                    'employment_type' => collect(['salaried','self-employed'])->random(),
                    'income' => rand(20000, 100000),
                    'existing_emi' => rand(0, 20000),
                    'loan_amount' => rand(100000, 1000000),
                    'property_stage' => collect(['under_construction','ready_to_move'])->random(),
                ]
            ]);
        }
    }
}