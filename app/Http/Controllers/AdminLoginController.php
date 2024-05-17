<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class AdminloginController extends Controller
{
    public function showloginform()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = request(['phone_number', 'password']);
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            if ($user->role == 'admin') {
                return redirect('business');
            }
            else{
                return response()->json('you are not an admin');
            }
        }
        return redirect('login_form');
    }

    public function logout()
    {
        Session::flush();

        return redirect('login_form');
    }

    public function guard()
    {
        return Auth::guard('web');
    }
}