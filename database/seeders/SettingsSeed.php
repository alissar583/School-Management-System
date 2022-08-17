<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Traits\basicFunctionsTrait;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeed extends Seeder
{
    use basicFunctionsTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();

        $address = Address::query()->create([
            'city' => 'Damascus',
            'town' => 'almaza',
            'street' => 'aya'
        ]);
        $logo = '/'. Carbon::now()->format('Y'). '/images/settings/logo/logo.jpg';
        DB::table('settings')->insert([
            'phone' =>  '0991232992',
            'logo' => $logo,
            'admin_id' => 1,
            'address_id' =>$address->id,
            'name' => 'test',
            'color' => 'test'
        ]);
    }
}
