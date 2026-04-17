<?php

namespace App\Http\Controllers;

use App\Models\Lender;
use Illuminate\Http\Request;

class InterestRateController extends Controller
{
    public function index(Request $request)
    {
        // 🔹 Default = All
        $type = $request->get('type', 'All');

        $query = Lender::query();

        // 🔹 Sirf tab filter jab All nahi ho
        if ($type !== 'All') {
            $query->where('type', $type);
        }

        $lenders = $query->orderBy('name')->get();

        return view('rates.index', compact('lenders', 'type'));
    }
}
