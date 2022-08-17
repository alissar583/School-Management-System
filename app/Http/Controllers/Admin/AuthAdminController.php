<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use App\Traits\generalTrait;

class AuthAdminController extends Controller
{
    use generalTrait;


    public function login(Request $request)
    {
        $admin = Admin::query()->where('email', $request->email)->first();
        if(!isset($admin)) {
            return $this->returnErrorMessage('Admin Not Found', 404);
        }
        if (!Hash::check(request('password'), $admin->password)) {
            return $this->returnErrorMessage('Incorrect password', 403);
        }

        $token= $admin->createToken('admin', ['admin']);
        $data['admin'] = $admin;
        $data['Bearer'] ='Bearer';
        $data['token'] = $token->accessToken;

        return $this->returnData('data', $data,'logged in successfully');
    }
}
