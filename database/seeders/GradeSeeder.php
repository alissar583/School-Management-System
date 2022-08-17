<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Grade;
class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->delete();
            Grade::query()->create([
                'name' => 'Primary School'
            ]);

            Grade::query()->create([
                'name' => 'Middle School'
            ]);

            Grade::query()->create([
                'name' => 'High School'
            ]);

    }
}
