<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyticsController extends Controller
{
    public function index()
    {
        try {
            Log::info('Analytics page accessed');

            return view('analytics.index');
        } catch (Throwable $e) {
            Log::error('Analytics page error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            abort(500);
        }
    }
}
