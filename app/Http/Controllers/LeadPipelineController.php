<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class LeadPipelineController extends Controller
{
    /* ================= PIPELINE FLOW ================= */
    private function nextStageMap()
    {
        return [
            'New Lead' => ['Contacted'],
            'Contacted' => ['Document Collected'],
            'Document Collected' => ['Verification'],
            'Verification' => ['Loan Offer Shared', 'Lost / Rejected'],
            'Loan Offer Shared' => ['Negotiation / Follow-up', 'Lost / Rejected'],
            'Negotiation / Follow-up' => ['Approved / Sanctioned', 'Lost / Rejected'],
            'Approved / Sanctioned' => ['Disbursed / Closed'],
        ];
    }

    /* ================= FILTER ================= */
    private function matchFilters($lead, $filters)
    {
        if ($filters['search']) {
            $text = strtolower(
                ($lead->data['full_name'] ?? '') . ' ' .
                ($lead->email ?? '') . ' ' .
                ($lead->mobile ?? '') . ' ' .
                ($lead->data['city'] ?? '')
            );
            if (!str_contains($text, strtolower($filters['search']))) {
                return false;
            }
        }

        if ($filters['stage'] && $lead->stage !== $filters['stage']) {
            return false;
        }

        if ($filters['amount']) {
            $amt = (int) ($lead->data['loan_amount'] ?? 0);
            if ($filters['amount'] === '<25' && $amt >= 2500000) return false;
            if ($filters['amount'] === '25-50' && ($amt < 2500000 || $amt > 5000000)) return false;
            if ($filters['amount'] === '50-100' && ($amt < 5000000 || $amt > 10000000)) return false;
            if ($filters['amount'] === '>100' && $amt <= 10000000) return false;
        }
        // 🔹 SOURCE FILTER
if ($filters['source']) {

    $source = strtolower($lead->data['source'] ?? '');

    if ($source !== strtolower($filters['source'])) {
        return false;
    }
}
// 🔹 CIBIL FILTER
if ($filters['cibil']) {

    $cibil = (int) ($lead->data['cibil'] ?? 0);

    if ($filters['cibil'] === '750+' && $cibil < 750) return false;
    if ($filters['cibil'] === '700-749' && ($cibil < 700 || $cibil > 749)) return false;
    if ($filters['cibil'] === '650-699' && ($cibil < 650 || $cibil > 699)) return false;
    if ($filters['cibil'] === '<650' && $cibil >= 650) return false;
}


        return true;
    }

    /* ================= INDEX ================= */
    public function index(Request $request)
    {
        $view = $request->get('view', 'board');
$filters = [
    'search' => trim($request->get('search')),
    'stage'  => $request->get('stage'),
    'amount' => $request->get('amount'),
    'cibil'  => $request->get('cibil'),
    'source' => $request->get('source'),
];

        $leadsByStage = collect([
            'New Lead' => collect(),
            'Contacted' => collect(),
            'Document Collected' => collect(),
            'Verification' => collect(),
            'Loan Offer Shared' => collect(),
            'Negotiation / Follow-up' => collect(),
            'Approved / Sanctioned' => collect(),
            'Disbursed / Closed' => collect(),
            'Lost / Rejected' => collect(),
        ]);

        $loans = Loan::orderBy('created_at', 'desc')->get();
$stage = null;
        foreach ($loans as $loan) {

            if (($loan->step_completed ?? 0) < 3) continue;

            $stage = match ((int) $loan->pipeline_step) {
                1 => 'Contacted',
                2 => 'Document Collected',
                3 => 'Verification',
                4 => 'Loan Offer Shared',
                5 => 'Negotiation / Follow-up',
                6 => 'Approved / Sanctioned',
                7 => 'Disbursed / Closed',
                9 => 'Lost / Rejected',
                default => 'New Lead',
            };

          $loan->lead_id = (string) $loan->_id;
$loan->stage   = $stage;

if ($loan->user_id && $loan->user_id !== auth()->id()) {
    // 🔹 User applied lead
    $user = User::find($loan->user_id);
    $loan->email  = $user->email ?? '-';
    $loan->mobile = $user->mobile_number ?? '-';
} else {
    // 🔹 Admin added lead
    $loan->email  = $loan->data['email'] ?? '-';
    $loan->mobile = $loan->data['mobile'] ?? '-';
}

            if ($this->matchFilters($loan, $filters)) {
                $leadsByStage[$stage]->push($loan);
            }
        }

        return view('leads.index', [
            'leadsByStage' => $leadsByStage,
            'leads'        => $leadsByStage->flatten(1),
            'view'         => $view,
            'nextActions'  => $this->nextStageMap(),
            'stage'       => $stage,
        ]);
    }

    /* ================= STORE (ADD LEAD) ================= */
  public function store(Request $request)
{
    try {
        $request->validate([
            'full_name'   => 'required',
            'loan_amount' => 'required|numeric',
        ]);

        Loan::create([
            'user_id' => auth()->id(),
            'step_completed' => 3,
            'pipeline_step' => 0,
'data' => [
    'full_name'        => $request->full_name,
    'city'             => $request->city,
    'email'            => $request->email,   // ✅ ADD THIS
    'mobile'           => $request->mobile,  // ✅ ADD THIS
    'pan'              => $request->pan,
    'dob'              => $request->dob,
    'employment_type'  => $request->employment_type,
    'income'           => (int) $request->income,
    'existing_emi'     => (int) $request->existing_emi,
    'loan_amount'      => (int) $request->loan_amount,
]

        ]);

        // 🔥 VERY IMPORTANT FOR JS FETCH
        return response()->json(['ok' => true]);

    } catch (Throwable $e) {
        Log::error('Lead store error', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Save failed'], 500);
    }
}


    /* ================= SHOW ================= */
    public function show($id)
    {
        $loan = Loan::findOrFail($id);
        $user = User::find($loan->user_id);

        return response()->json([
            'loan' => $loan,
            'email' => $user->email ?? null,
            'mobile' => $user->mobile_number ?? null,
        ]);
    }

    /* ================= EDIT ================= */
    public function edit($id)
    {
        return Loan::findOrFail($id);
    }

    /* ================= UPDATE ================= */
   public function update(Request $request, $id)
{
    try {
        $loan = Loan::findOrFail($id);

        $allowed = [
            'full_name',
            'city',
            'pan',
            'dob',
            'employment_type',
            'income',
            'existing_emi',
            'loan_amount',
        ];

        $data = $loan->data ?? [];

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                $data[$key] = $request->$key;
            }
        }

        $loan->data = $data;
        $loan->save();

        return response()->json(['ok' => true]);

    } catch (Throwable $e) {
        Log::error('Lead update error', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Update failed'], 500);
    }
}

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        Loan::findOrFail($id)->delete();
        return response()->json(['deleted' => true]);
    }

    /* ================= PIPELINE MOVE ================= */
   public function updateStage(Request $request, $id)
{
    $request->validate([
        'stage' => 'required|integer'
    ]);

    $loan = Loan::findOrFail($id);

    $loan->pipeline_step = $request->stage;
    $loan->save();

    return response()->json([
        'ok' => true,
        'stage' => $loan->pipeline_step
    ]);
}


public function search(Request $request)
{
    $q = strtolower(trim($request->q));

    return Loan::where('step_completed', '>=', 3)
        ->get()
        ->filter(function ($loan) use ($q) {

            $name   = strtolower($loan->data['full_name'] ?? '');
            $email  = strtolower($loan->data['email'] ?? '');
            $mobile = strtolower($loan->data['mobile'] ?? '');

            return str_contains($name, $q)
                || str_contains($email, $q)
                || str_contains($mobile, $q);
        })
        ->take(10)
        ->map(function ($loan) {
            return [
                'id'     => (string) $loan->_id,
                'name'   => $loan->data['full_name'] ?? 'Unknown',
                'mobile' => $loan->data['mobile'] ?? '',
                'email'  => $loan->data['email'] ?? '',
            ];
        })
        ->values();
}

public function list()
{
    return Loan::where('step_completed', '>=', 3)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($loan) {
            return [
                'id'     => (string) $loan->_id,
                'name'   => $loan->data['full_name'] ?? 'Unknown',
                'mobile' => $loan->data['mobile'] ?? '',
                'email'  => $loan->data['email'] ?? '',
                'pan'    => $loan->data['pan'] ?? '',
            ];
        });
}



}
