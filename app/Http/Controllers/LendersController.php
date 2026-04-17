<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lender;
use Illuminate\Support\Facades\Storage;

class LendersController extends Controller
{
    public function index()
    {
        $lenders = Lender::orderBy('name')->get();
        return view('lenders.index', compact('lenders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'type'         => 'required|in:Nationalized,Private,NBFC',
            'logo'         => 'nullable|image|mimes:png,jpg,jpeg,svg',
            'product_name' => 'required|array|min:1',
        ]);

        $logoPath = null;
        $logoUrl  = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('lenders', 'public');
            $logoUrl  = asset('storage/' . $logoPath);
        }

        $products = [];
        foreach ($request->product_name as $i => $name) {
            $products[] = [
                'product_name' => $name,
                'rate'   => $request->rate[$i] ?? null,
                'emi'    => $request->emi[$i] ?? null,
                'loan'   => $request->loan[$i] ?? null,
                'tenure' => $request->tenure[$i] ?? null,
            ];
        }

        Lender::create([
            'name'     => $request->name,
            'type'     => $request->type,
            'logo'     => $logoPath,
            'logo_url' => $logoUrl,
            'products' => $products,
        ]);

        return back()->with('success', 'Lender added');
    }

    public function update(Request $request, $id)
    {
        $lender = Lender::findOrFail($id);

        $logoPath = $lender->logo;
        $logoUrl  = $lender->logo_url;

        if ($request->hasFile('logo')) {
            if ($lender->logo) {
                Storage::disk('public')->delete($lender->logo);
            }

            $logoPath = $request->file('logo')->store('lenders', 'public');
            $logoUrl  = asset('storage/' . $logoPath);
        }

        $products = [];
        foreach ($request->product_name as $i => $name) {
            $products[] = [
                'product_name' => $name,
                'rate'   => $request->rate[$i] ?? null,
                'emi'    => $request->emi[$i] ?? null,
                'loan'   => $request->loan[$i] ?? null,
                'tenure' => $request->tenure[$i] ?? null,
            ];
        }

        $lender->update([
            'name'     => $request->name,
            'type'     => $request->type,
            'logo'     => $logoPath,
            'logo_url' => $logoUrl,
            'products' => $products,
        ]);

        return back()->with('success', 'Updated');
    }

    public function destroy($id)
    {
        $lender = Lender::findOrFail($id);

        if ($lender->logo) {
            Storage::disk('public')->delete($lender->logo);
        }

        $lender->delete();

        return back()->with('success', 'Deleted');
    }
}
