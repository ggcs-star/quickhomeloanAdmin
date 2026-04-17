<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at','desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'full_name'     => $request->full_name,
            'channel_name'  => $request->channel_name,
            'channel_url'   => $request->channel_url,
            'email'         => $request->email,
            'mobile_number' => $request->mobile_number,
            'address'       => $request->address,
            'password'      => Hash::make($request->password),
            'role'          => $request->role ?? 'user',
            'status'        => 'active',
        ]);

        return redirect('/admin/users');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->only([
            'full_name','channel_name','channel_url',
            'email','mobile_number','address',
            'role','status'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect('/admin/users');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        return back();
    }
}
