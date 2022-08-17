<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceStatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attendance_statuses')->delete();

        DB::table('attendance_statuses')->insert([
            'status' => 'P'
        ]);

        DB::table('attendance_statuses')->insert([
            'status' => 'A'
        ]);

        DB::table('attendance_statuses')->insert([
            'status' => 'L'
        ]);
    }
}
