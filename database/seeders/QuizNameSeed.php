<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizNameSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quiz_names')->delete();

        DB::table('quiz_names')->insert([
            'name' => 'Oral'
        ]);

        DB::table('quiz_names')->insert([
            'name' => 'quiz'
        ]);

    }
}
