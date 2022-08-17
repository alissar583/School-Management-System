<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lessons')->delete();

        Lesson::query()->create([
           'name' => 'first lesson'
        ]);

        Lesson::query()->create([
           'name' => 'second lesson '
        ]);

        Lesson::query()->create([
           'name' => 'third lesson '
        ]);

        Lesson::query()->create([
           'name' => 'fourth lesson '
        ]);

        Lesson::query()->create([
           'name' => 'fifth lesson '
        ]);

        Lesson::query()->create([
           'name' => 'sixth lesson '
        ]);

        Lesson::query()->create([
           'name' => 'seventh lesson'
        ]);


    }
}
