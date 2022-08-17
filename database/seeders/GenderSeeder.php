<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Gender;
class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genders')->delete();
         $genders=['Male','Female'];
         foreach($genders as $gender){
             Gender::query()->create(['type'=>$gender]);
         }
    }
}
