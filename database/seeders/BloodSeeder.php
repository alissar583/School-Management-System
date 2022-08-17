<?php

namespace Database\Seeders;

use App\Models\Blood;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class BloodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bloods')->delete();

        Blood::query()->create([
            'type' => 'A+'
        ]);
        Blood::query()->create([
            'type' => 'A-'
        ]);
        Blood::query()->create([
            'type' => 'B+'
        ]);
        Blood::query()->create([
            'type' => 'B-'
        ]);
        Blood::query()->create([
            'type' => 'O+'
        ]);
        Blood::query()->create([
            'type' => 'O-'
        ]);
        Blood::query()->create([
            'type' => 'AB+'
        ]);
        Blood::query()->create([
            'type' => 'AB-'
        ]);

//         $bloods=['A+','A-','B+','B-','O+','O-','AB+','AB-'];
//         foreach($bloods as $blood){
//             Blood::create(['type'=>$blood]);
//         }


    }
}
