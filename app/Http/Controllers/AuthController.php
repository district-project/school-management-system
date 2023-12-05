<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function login()
    {
        // Uncomment or remove if necessary
        // dd(Hash::make(123456));

        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function AuthLogin(Request $request)
    {
        $remember = $request->has('remember');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            if(Auth::user()->user_type == 1){
                return redirect()->route('admin.dashboard');
            }else if(Auth::user()->user_type == 2){
                return redirect()->route('teacher.dashboard');
            }else if(Auth::user()->user_type == 3){
                return redirect()->route('student.dashboard');                
            }else if(Auth::user()->user_type == 4){
                return redirect()->route('parent.dashboard');                
            }
        } else {
            return redirect()->back()->with('error', 'Please enter correct email and password');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url(''));
    }
}
