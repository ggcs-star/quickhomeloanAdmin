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
        ]);

        Calculator::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'category' => $request->category,
            'description' => $request->description,
            'is_active' => true,
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
}