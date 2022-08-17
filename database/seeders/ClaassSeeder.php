<?php

namespace Database\Seeders;

use App\Models\Claass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClaassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Claasses')->delete();
        DB::table('classrooms')->delete();
        DB::table('classrooms')->insert([
            'name' => 1,
            'max_number' => 20
        ]);


            DB::table('Claasses')->insert([
                [
                    'name' => ' first class',
                    'grade_id' => '1'
                ],
                [
                    'name' => "second class",
                    'grade_id' => '1'
                ],
                [
                    'name' => 'third class',
                    'grade_id' => '1'
                ], [
                    'name' => 'fourth class',
                    'grade_id' => '1'
                ], [
                    'name' => 'fifth class',
                    'grade_id' => '1'
                ], [
                    'name' => 'sixth class',
                    'grade_id' => '1'
                ],
                [
                    'name' => 'seventh class',
                    'grade_id' => '2'
                ], [
                    'name' => 'eighth class',
                    'grade_id' => '2'
                ], [
                    'name' => 'ninth class',
                    'grade_id' => '2'
                ],
                [
                    'name' => 'tenth class',
                    'grade_id' => '3'
                ], [
                    'name' => 'eleventh class',
                    'grade_id' => '3'
                ], [
                    'name' => 'twelfth class',
                    'grade_id' => '3'
                ],

            ]);

            for ($i =1 ; $i<= 12; $i++) {
                DB::table('claass_classrooms')->insert([
                    'class_id' => $i,
                    'classroom_id' => 1
                ]);
            }

    }
}
