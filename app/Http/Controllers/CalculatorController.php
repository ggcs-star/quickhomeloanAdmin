<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calculator;

class CalculatorController extends Controller
{
    public function index()
    {
        $calculators = Calculator::all();
        return view('calculators.index', compact('calculators'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:calculators,slug',
            'category' => 'required',
            'user_type' => 'required|in:first_time,existing,all',
        ]);

        Calculator::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'category' => $request->category,
            'description' => $request->description,
            'is_active' => true,
            'user_type' => $request->user_type,
        ]);

        return back()->with('success', 'Calculator Added');
    }

    // 📌 Toggle ON/OFF
    public function toggle($id)
    {
        $calc = Calculator::findOrFail($id);
        $calc->is_active = !$calc->is_active;
        $calc->save();

        return back();
    }

    public function updateAccess(Request $request, $id)
    {
        $calc = Calculator::findOrFail($id);

        $calc->access_type = $request->access_type;
        $calc->save();

        return back()->with('success', 'Access updated');
    }

    public function updateUserType(Request $request, $id)
    {
        $calc = Calculator::findOrFail($id);

        $calc->user_type = $request->user_type;
        $calc->save();

        return back()->with('success', 'User type updated');
    }
}