<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Loan;
use App\Models\CampaignClick;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        return view('campaigns.index', compact('campaigns'));
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
    'name'        => 'required|string|max:255',
    'channel'     => 'required|in:SMS,WhatsApp',
    'message'     => 'required|string',
    'short_link'  => 'nullable|url',
    'scheduled_at' => 'nullable|date|after:now',

    // 👇 ADD THESE
    'recipient_type'  => 'nullable|in:all,selected',
    'selected_leads'  => 'nullable|array',
    'selected_leads.*'=> 'string',
]);

$scheduledAt = null;

if (!empty($validated['scheduled_at'])) {
    $scheduledAt = Carbon::parse(
        $validated['scheduled_at'],
        'Asia/Kolkata'
    )->utc();
}


        // 🔹 Qualified leads
    $loansQuery = Loan::where('step_completed', '>=', 3);

if (
    ($request->recipient_type ?? 'all') === 'selected'
    && is_array($request->selected_leads)
    && count($request->selected_leads)
) {
    $loansQuery->whereIn(
        '_id',
        array_map('strval', $request->selected_leads)
    );
}

$loans = $loansQuery->get();


      $validLoans = $loans->map(function ($loan) {

    $mobile = $loan->data['mobile']
        ?? optional(\App\Models\User::find($loan->user_id))->mobile_number;

    if (!$mobile) {
        return null;
    }

    // 👇 ek baar resolve karke loan me hi store kar do
    $loan->resolved_mobile = $mobile;

    return $loan;

})->filter();


        // 🔹 CHECK: scheduled or instant
        $isScheduled = !empty($validated['scheduled_at']);

$campaign = Campaign::create([
    'name'           => $validated['name'],
    'channel'        => $validated['channel'],
    'message'        => $validated['message'],
    'short_link'     => $validated['short_link'] ?? null,

    'recipient_type' => $request->recipient_type ?? 'all',
    'selected_leads' => $request->selected_leads ?? [],

    'recipients'     => $validLoans->count(),
    'sent_count'     => 0,
    'failed_count'   => 0,
    'status'         => $scheduledAt ? 'Scheduled' : 'Processing',
    'scheduled_at'   => $scheduledAt,
    'clicks'         => 0,
]);


        // 🔒 VERY IMPORTANT: if scheduled → STOP HERE
        if ($isScheduled) {
            return response()->json($campaign);
        }

        // =====================
        // 🔥 EXISTING SEND FLOW (UNCHANGED)
        // =====================

        $sent   = 0;
        $failed = 0;

        $template = config('services.whatsapp.default_template', 'hello_world');

        foreach ($validLoans as $loan) {
            try {
               $mobile = $loan->resolved_mobile;

                $mobile = preg_replace('/\D/', '', $mobile);

                if (strlen($mobile) === 11 && str_starts_with($mobile, '0')) {
                    $mobile = substr($mobile, 1);
                }

                if (strlen($mobile) !== 10) {
                    $failed++;
                    continue;
                }

                $phone = '91' . $mobile;

                if ($validated['channel'] === 'WhatsApp') {
                    $response = WhatsAppService::sendTemplate($phone, $template);

                    $response && $response->successful()
                        ? $sent++
                        : $failed++;
                }

                if ($validated['channel'] === 'SMS') {
                    $this->sendSMS($phone, $validated['message']);
                    $sent++;
                }

            } catch (\Throwable $e) {
                $failed++;
                Log::error('Campaign send exception', [
                    'error' => $e->getMessage(),
                    'loan_id' => (string) $loan->_id,
                ]);
            }
        }

        $campaign->update([
            'sent_count'   => $sent,
            'failed_count' => $failed,
            'status'       => $sent === 0
                                ? 'Failed'
                                : ($failed > 0 ? 'Partial' : 'Sent'),
            'sent_at'      => now(),
        ]);

        return response()->json(
            Campaign::find($campaign->_id)
        );
    }

    private function sendSMS(string $phone, string $message): void
    {
        Log::info('SMS SENT', [
            'phone' => $phone,
            'message' => $message,
        ]);
    }

    public function redirect($id)
    {
        $campaign = Campaign::findOrFail($id);

        CampaignClick::create([
            'campaign_id' => (string) $campaign->_id,
            'clicked_at'  => now(),
        ]);

        $campaign->increment('clicks');

        return redirect()->to($campaign->short_link);
    }
}
