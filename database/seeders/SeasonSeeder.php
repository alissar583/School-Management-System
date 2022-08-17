<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Season;
class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('seasons')->delete();
        $names=['first season','second season'];
        foreach($names as $name){
           Season::create(['name'=>$name]);
        }
    }
}
