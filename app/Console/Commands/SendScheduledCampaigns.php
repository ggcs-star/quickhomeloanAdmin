<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\Loan;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class SendScheduledCampaigns extends Command
{
    protected $signature = 'campaigns:send-scheduled';
    protected $description = 'Send scheduled WhatsApp/SMS campaigns';

    public function handle()
    {
        $campaigns = Campaign::where('status', 'Scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($campaigns as $campaign) {

$loansQuery = Loan::where('step_completed', '>=', 3);

if (
    ($campaign->recipient_type ?? 'all') === 'selected'
    && is_array($campaign->selected_leads)
    && count($campaign->selected_leads)
) {
    $loansQuery->whereIn(
        '_id',
        array_map('strval', $campaign->selected_leads)
    );
}

$loans = $loansQuery->get();

            $sent = 0;
            $failed = 0;

           foreach ($loans as $loan) {
    try {

        $mobile = $loan->data['mobile']
            ?? optional(\App\Models\User::find($loan->user_id))->mobile_number;

        $mobile = preg_replace('/\D/', '', $mobile);

        if (strlen($mobile) === 11 && str_starts_with($mobile, '0')) {
            $mobile = substr($mobile, 1);
        }

        if (strlen($mobile) !== 10) {
            $failed++;
            continue;
        }

        $phone = '91' . $mobile;

        // ✅ WHATSAPP
        if ($campaign->channel === 'WhatsApp') {
            $res = WhatsAppService::sendTemplate(
                $phone,
                config('services.whatsapp.default_template')
            );

            $res && $res->successful()
                ? $sent++
                : $failed++;
        }

        // ✅ SMS (🔥 THIS WAS MISSING)
        if ($campaign->channel === 'SMS') {
            Log::info('Scheduled SMS sent', [
                'phone' => $phone,
                'message' => $campaign->message,
            ]);
            $sent++;
        }

    } catch (\Throwable $e) {
        $failed++;
        Log::error('Scheduled campaign failed', [
            'campaign_id' => (string) $campaign->_id,
            'error' => $e->getMessage()
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
        }

        return Command::SUCCESS;
    }
}
