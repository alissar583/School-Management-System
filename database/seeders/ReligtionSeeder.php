<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Religtion;
class ReligtionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Religtions')->delete();
        $Religions=['Muslim','Christian'];
        foreach($Religions as $Religion){
            Religtion::create(['name'=>$Religion]);
        }
    }
}
