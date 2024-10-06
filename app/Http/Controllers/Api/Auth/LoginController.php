<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('username', $request->input('username'))->where('status', true)->first();

        if($user){
            $allowedDays = [];
            $allowed_days = $user->rooms()->where('name', $request->input('room'))->first();
            if($allowed_days)
            $allowedDays = json_decode($allowed_days->allowed_days, true); // Lấy danh sách các ngày mà user được phép đăng nhập
            $currentDay = Carbon::now()->dayOfWeek; 
            if (!in_array($currentDay, $allowedDays)) {
                throw ValidationException::withMessages([
                    "The credentials you entered are incorrect"
                ]);
            }
        }
        
        // if (!$user || !Hash::check($request->password, $user->password)) {
        //     throw ValidationException::withMessages([
        //         "The credentials you entered are incorrect"
        //     ]);
        // }
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('laraval_api_token')->plainTextToken
        ]);
    }
}
