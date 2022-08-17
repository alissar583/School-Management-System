<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('days')->delete();

        Day::query()->create([
            'name' => 'Saturday'
        ]);

        Day::query()->create([
            'name' => 'Sunday'
        ]);

        Day::query()->create([
            'name' => 'Monday'
        ]);

        Day::query()->create([
            'name' => 'Tuesday'
        ]);

        Day::query()->create([
            'name' => 'Wednesday'
        ]);

        Day::query()->create([
            'name' => 'Thursday'
        ]);

        Day::query()->create([
            'name' => 'Friday'
        ]);

        DB::table('lesson_day')->delete();

        for ($j=1 ; $j<=7 ; $j++){
            for ($i =1 ; $i<= 7; $i++) {
                DB::table('lesson_day')->insert([
                    'day_id' => $j ,
                    'lesson_id' => $i
                ]);
            }}



    }
}
