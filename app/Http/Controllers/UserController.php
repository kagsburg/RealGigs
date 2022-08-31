<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //
    public function create(){
        return view ('users.create');
    }
    public function store(Request $request){
        $users = $request->validate([
            'name'=>['required', 'min:3'],
            'email'=>['required','email',Rule::unique('users','email')],
            'password'=>['required','min:8','confirmed'],
        ]);
        //hash password
        $users['password']=bcrypt($users['password']);
        //create user
        $user= User::create($users);
        //login user
        auth()->login($user);
        return redirect('/')->with('success',"User Created Successfully");

    }
    //logout user function
    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success',"User Logged Out Successfully");
    }
    //login route view
    public function login(){
        return view ('users.login');
    }
    //authenicate users 
    public function authenicate(Request $request){
        $credentials = $request->only('email','password');
        if(!auth()->attempt($credentials)){
            return back()->withErrors('email','Invalid Credentials')->onlyInput('email');
        }
        $request->session()->regenerateToken();
        return redirect('/')->with('success',"User Logged In Successfully");
    }

}
