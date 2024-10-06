<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserNewController extends Controller
{
    public function index()
    {
        // Phân trang với mỗi trang có 10 người dùng
        $users = User::paginate(10);

        return view('users.index', compact('users'));
    }
    // Hiển thị form cập nhật
    public function edit($id)
    {
        $user = User::find($id);
        //$allowed_days = json_decode($user->allowed_days, true); // Lấy danh sách các ngày mà user được phép đăng nhập
        return view('users.edit', compact('user'));
    }

    // Xử lý việc cập nhật thông tin
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|max:25|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            //'allowed_days' => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cập nhật thông tin người dùng
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->status = $request->status;
        //$user->allowed_days = json_encode($request->allowed_days);
        $user->save();

        // Redirect với thông báo thành công
        return redirect()->route('users.index')->with('success', 'Profile updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}

