<?php

namespace Database\Seeders;

use App\Models\Expense;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Buat 50 pengeluaran acak
        foreach (range(1, 50) as $index) {
            Expense::create([
                'description' => $faker->sentence(6),
                'amount'      => $faker->numberBetween(50_000, 1_000_000),
                'type'        => $faker->randomElement(['recurring', 'one-time']),
                'category'    => $faker->randomElement(['maintenance', 'utilities', 'repairs', 'misc']),
                'date'        => $faker->date(),
            ]);
        }
    }
}
