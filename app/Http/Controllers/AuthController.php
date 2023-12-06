<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Mail\ForgotPasswordMail;
use Mail;
use Str;
use Hash;

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

    public function logout(){
        Auth::logout();
        return redirect(url(''));
    }
    public function forgotpassword(){
        return view('auth.forgot');
    }
    public function Postforgotpassword(Request $request){
        $user = User::getEmailSingle($request->email);
        if($user){
            $user->remember_token = Str::random(30);
            $user->save();
            Mail::to($user->email)->send(new ForgotPasswordMail($user));
            return redirect()->back()->with('success', "Please check your email and restet password");	

        }else{
            return redirect()->back()->with('error', "User not found");
        }
    }
    public function reset($remember_token){
        $user = User::getTokenSingle($remember_token);
        if($user){
            $data['user'] = $user;
            return view('auth.reset',$data);
        }
        else{
            abort(404);
        }
    }
    public function PostReset($remember_token, Request $request){
        
        if($request->password == $request->cpassword){
            $user = User::getTokenSingle($remember_token);
            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(30);
            $user->save();
            return redirect(url(''))->with('success', "Password reset successfully"); 
        }
        else{
            return redirect()->back()->with('error', "Password and Confirm password should be same"); 
        }
    }
}
