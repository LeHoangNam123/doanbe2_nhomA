<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function changePassword(Request $request){
        $validatedData = $request->validate([
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
        ]);
        // kiểm tra coi match password với mk của user đó không
        if(!Hash::check($request->old_password, auth()->user()->password)){
            return back()->with("error", "Không khẩu cũ không chính xác!");
        }
        if ($request->new_password === $request->confirm_password) {
            $password = Hash::make($request->new_password);
            User::whereId(auth()->user()->id)->update([
                'password' => $password //đã hash rồi nhen
            ]);
        } else {
            return back()->with("error_confirm_password", "Mật khẩu mới và xác nhận mật khẩu không khớp!");
        }
        return response()->json(['success' => 'success'], 200);
    }
    
}
