<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit');
    }

    public function update(UpdateUserRequest $request)
    {
        $user = Auth::user();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path("uploads/admins"), $fileName);

            if ($user->image && file_exists(public_path("uploads/admins/" . $user->image))) {
                unlink(public_path("uploads/admins/" . $user->image));
            }

            $user->image = $fileName;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->has('status')) {
            $user->status = $request->status;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}
