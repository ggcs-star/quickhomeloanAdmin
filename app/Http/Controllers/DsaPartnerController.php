<?php

namespace App\Http\Controllers;

use App\Models\DsaPartner;
use App\Models\Loan;
use Illuminate\Http\Request;

class DsaPartnerController extends Controller
{
    /* ================= LIST ================= */
    public function index()
    {
        $partners = DsaPartner::where('status','active')->get()->map(function ($partner) {

            $leads = Loan::where('dsa_id', (string) $partner->_id)->get();

            $referrals = $leads->count();
            $disbursedLeads = $leads->where('stage','Disbursed / Closed');

            $disbursedAmount = $disbursedLeads->sum(fn ($l) =>
                $l->data['loan_amount'] ?? 0
            );

            $conversion = $referrals > 0
                ? round(($disbursedLeads->count() / $referrals) * 100)
                : 0;

            $commission = ($disbursedAmount * ($partner->commission_rate ?? 1)) / 100;

            return compact(
                'partner',
                'referrals',
                'conversion',
                'disbursedAmount',
                'commission'
            );
        });

        return view('partners.index', compact('partners'));
    }

    /* ================= CREATE ================= */
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'email'  => 'required|email',
            'mobile' => 'required',
        ]);

        DsaPartner::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'commission_rate' => $request->commission_rate ?? 1,
            'status' => 'active',
        ]);

        return redirect()->route('partners.index')
            ->with('success','Partner added successfully');
    }

    /* ================= EDIT ================= */
    public function edit($id)
    {
        $partner = DsaPartner::findOrFail($id);
        return view('partners.edit', compact('partner'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, $id)
    {
        $partner = DsaPartner::findOrFail($id);

        $request->validate([
            'name'   => 'required',
            'email'  => 'required|email',
            'mobile' => 'required',
        ]);

        $partner->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'commission_rate' => $request->commission_rate,
            'status' => $request->status,
        ]);

        return redirect()->route('partners.index')
            ->with('success','Partner updated successfully');
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        DsaPartner::findOrFail($id)->delete();

        return redirect()->route('partners.index')
            ->with('success','Partner deleted');
    }
}
