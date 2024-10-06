<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Room;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = User::find($id);
        $rooms = $user->rooms()->get();

        return view('users.rooms', compact(['rooms','user']));
    }

    public function create($id){
        $user = User::find($id);
        return view('users.room_add', compact('user'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms',
        ]);
        $user = User::find($id);
        $allowedDays = json_encode($request->allowed_days);
        $room = $user->rooms()->create(
            [
                'name' => $request->name,
                'allowed_days' => $allowedDays
            ]
        );

        return redirect()->route('user.rooms', ['id' => $id])->with('success', 'Create successful.');
    }

    /**
     * Display the specified resource.
     */
    public function edit($id)
    {
        $room = Room::find($id);
        $allowed_days = json_decode($room->allowed_days, true); // Lấy danh sách các ngày mà user được phép đăng nhập
        return view('users.room_edit', compact(['room','allowed_days']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $room = Room::find($id);

        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25|unique:rooms,name,' . $room->id,
            'allowed_days' => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cập nhật thông tin người dùng
        $room->name = $request->name;
        $room->allowed_days = json_encode($request->allowed_days);
        $room->save();

        // Redirect với thông báo thành công
        return redirect()->route('user.rooms', $room->user->id)->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $room = Room::find($id);
        $room->delete();
        return redirect()->route('user.rooms', $room->user->id)
                        ->with('success','Room deleted successfully');
    }
}
