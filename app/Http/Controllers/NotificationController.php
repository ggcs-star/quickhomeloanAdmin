<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function create()
    {
        $users = User::all();
        return view('notifications.create', compact('users'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:500',
            'send_to' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageUrl = null;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('notifications', 'public');
            $imageUrl = Storage::url($path);
            $imageUrl = url($imageUrl);
        }

        $sendTo = $request->send_to ?? 'all';
        $userIds = [];
        $userNames = [];

        if ($sendTo === 'specific' && $request->has('user_ids')) {
            $userIds = $request->user_ids;
            $users = User::whereIn('_id', $userIds)->get();
            $userNames = $users->pluck('full_name')->toArray();
        }

        // Backend API Call
        $apiUrl = rtrim(env('BACKEND_API_URL'), '/') . '/api/fcm/notify-all';
        
        try {
            $response = Http::timeout(60)->post($apiUrl, [
                'title' => $request->title,
                'body' => $request->body,
                'image_url' => $imageUrl,
                'send_to' => $sendTo,
                'user_ids' => $userIds,
                'user_names' => $userNames,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return redirect()->route('notifications.create')
                    ->with('success', '✅ Notification sent! ' . ($result['summary']['successfully_sent'] ?? 0) . ' delivered');
            } else {
                return redirect()->route('notifications.create')
                    ->with('error', 'API Error: ' . ($response->json()['message'] ?? 'Unknown error'));
            }
            
        } catch (\Exception $e) {
            Log::error('Notification send error: ' . $e->getMessage());
            return redirect()->route('notifications.create')
                ->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    public function history()
    {
        // Direct database query (or API call)
        $histories = NotificationHistory::orderBy('sent_at', 'desc')->limit(100)->get();
        
        // Format for display
        foreach ($histories as $history) {
            $history->send_to_label = $this->getSendToLabel($history);
        }
        
        return view('notifications.history', compact('histories'));
    }
    
    private function getSendToLabel($history)
    {
        if ($history->send_to === 'all') {
            return '📱 All Users';
        } elseif ($history->send_to === 'specific' && !empty($history->user_names)) {
            $names = array_slice($history->user_names, 0, 2);
            $label = '👥 ' . implode(', ', $names);
            if (count($history->user_names) > 2) {
                $label .= ' +' . (count($history->user_names) - 2) . ' more';
            }
            return $label;
        }
        return '👥 ' . ($history->total_receivers ?? 0) . ' users';
    }
}