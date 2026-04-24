<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10); 

        return view('users.index', compact('users'));
    }
public function toggleStatus(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'status' => 'required|in:active,inactive,ban'
    ]);

    $user->status = $request->status;
    $user->save();

    return back()->with('success', 'User status updated');
}
}