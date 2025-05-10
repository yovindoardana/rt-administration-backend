<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\House;
use Illuminate\Database\Seeder;
use Database\Seeders\HouseSeeder;
use Database\Seeders\ExpenseSeeder;
use Database\Seeders\PaymentSeeder;
use Database\Seeders\ResidentSeeder;
use Database\Seeders\ResidentHouseHistorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            HouseSeeder::class,
            ResidentSeeder::class,
            ResidentHouseHistorySeeder::class,
            PaymentSeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
