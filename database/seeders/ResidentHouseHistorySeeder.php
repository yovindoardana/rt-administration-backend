<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\House;
use App\Models\Resident;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\ResidentHouseHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ResidentHouseHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker    = Faker::create();
        $houseIds = House::pluck('id')->toArray();

        Resident::all()->each(function ($resident) use ($faker, $houseIds) {
            $periods = rand(1, 3);
            $cursor  = Carbon::now()->subYears(3);

            for ($i = 0; $i < $periods; $i++) {
                $startDate = $cursor->copy()->addMonths(rand(1, 12));
                $isCurrent = $i === $periods - 1;
                $endDate   = $isCurrent ? null : $startDate->copy()->addMonths(rand(1, 12));

                ResidentHouseHistory::create([
                    'resident_id' => $resident->id,
                    'house_id'    => $faker->randomElement($houseIds),
                    'start_date'  => $startDate->toDateString(),
                    'end_date'    => $endDate ? $endDate->toDateString() : null,
                    'is_current'  => $isCurrent,
                ]);

                $cursor = $endDate ?: $startDate;
            }
        });
    }
}
