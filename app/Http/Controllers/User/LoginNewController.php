<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LoginNewController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function login(){
        return view('users.login');
    }

    public function loginOnPage(Request $request)
    {
        // Validate dữ liệu form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Cố gắng đăng nhập người dùng
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Đăng nhập thành công, chuyển hướng đến trang mong muốn
             $user = Auth::user();
            //$allowedDays = json_decode($user->allowed_days, true); // Lấy danh sách các ngày mà user được phép đăng nhập
            //$currentDay = Carbon::now()->dayOfWeek; // Lấy ngày trong tuần (0: Chủ Nhật, 1: Thứ Hai, 2: Thứ Ba, ...)

        if (!$user->super) {//!in_array($currentDay, $allowedDays)
            auth()->guard("web")->logout();
            return back()->withErrors([
                'error' => "You don't have permission to log in or you entered the wrong password.",
            ]);
        }
            return redirect()->route('users.index');
        }

        // Đăng nhập không thành công, quay lại với lỗi
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');        
    }
}
