<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterNewController extends Controller
{
    public function registerShow(){
        return view('users.register');
    }

    public function register(Request $request)
    {
        // Validate form
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|min:4|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            //'allowed_days' => ['required', 'array'], // Validate rằng user đã chọn các ngày
        ]);

        //$allowedDays = json_encode($request->allowed_days);
        // Create user
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'status' => $request->status,
            'password' => Hash::make($request->password),
            //'allowed_days' => $allowedDays, // Lưu danh sách ngày được chọn
        ]);

        if(Auth::user()->super){
            return redirect()->route('users.index')->with('success', 'Create successful.');
        }else
        return redirect()->route('users.login')->with('success', 'Registration successful! You can now log in.');
        // Redirect or login the user
        
    }
}
