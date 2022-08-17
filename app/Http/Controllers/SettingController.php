<?php

namespace App\Http\Controllers;

use App\Traits\basicFunctionsTrait;
use App\Traits\generalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\setting;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    use generalTrait, basicFunctionsTrait;

    public function update( Request $request)
    {
        $setting = setting::query()->first();
        $address = $this->addAddress($request);
        $time = Carbon::now();
        if (isset($request->picture)) {
            if (Storage::exists($setting->logo)) {
                Storage::delete($setting->logo);
                Storage::deleteDirectory($time->format('Y') . '/images/settings/logo');
            }
            $byte_array = $request->picture;
            $image = base64_decode($byte_array);
            Storage::put($time->format('Y').'/images/settings/logo/logo.jpg', $image);
            $picture = '/'. $time->format('Y').'/images/settings/logo/logo.jpg';
            $setting->update(['logo' => $picture]);
        }


        $setting->update([
            'phone' =>  $request->phone,
//            'logo' => $picture,
            'address_id' => $address->id,
            'name' => $request->name,
            'color' => $request->color
        ]);
       $setting->admin()->update([
           'email' => $request->email,
       ]);

       if ($request->old_password !== null && $request->new_password !== null){
           if (Hash::check($request->old_password, $setting->admin->password)) {
                $setting->admin()->update([
                    'password' => Hash::make($request->new_password)
                ]);
           }else {
               return $this->returnErrorMessage('the old password does not match', 403);
           }
       }
       return $this->returnData('settings', $setting->load('admin', 'address'), 'success');

    }

    public function show() {
        $setting = setting::query()->first();
        return $setting->load('admin', 'address');
    }

}
