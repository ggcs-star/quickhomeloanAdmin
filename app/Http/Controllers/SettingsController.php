<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show Settings Page
     */
    public function index(Request $request)
    {
        // Default data (first time)
        $settings = session()->get('settings', [
            'profile' => [
                'firstName' => 'Admin',
                'lastName'  => 'User',
                'email'     => 'admin@example.com',
                'phone'     => '+91 99999 00000',
            ],
            'notifications' => [
                'email'           => true,
                'sms'             => false,
                'leadAssignment'  => true,
                'documentUpload'  => true,
            ],
            'organization' => [
                'name'    => 'FinTech Solutions Pvt Ltd',
                'email'   => 'contact@fintech.com',
                'address' => 'Mumbai, India',
            ]
        ]);

        return view('settings.index', compact('settings'));
    }

    /**
     * Save Profile Settings (Session)
     */
    public function saveProfile(Request $request)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName'  => 'required',
            'email'     => 'required|email',
            'phone'     => 'required',
        ]);

        $settings = session()->get('settings', []);
        $settings['profile'] = $request->only([
            'firstName','lastName','email','phone'
        ]);

        session()->put('settings', $settings);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Save Notification Settings (Session)
     */
    public function saveNotifications(Request $request)
    {
        $settings = session()->get('settings', []);

        $settings['notifications'] = [
            'email'          => (bool) $request->email,
            'sms'            => (bool) $request->sms,
            'leadAssignment' => (bool) $request->leadAssignment,
            'documentUpload' => (bool) $request->documentUpload,
        ];

        session()->put('settings', $settings);

        return response()->json([
            'success' => true,
            'message' => 'Notification settings saved'
        ]);
    }

    /**
     * Change Password (Demo Only)
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current' => 'required',
            'new'     => 'required|min:6',
            'confirm' => 'required|same:new',
        ]);

        // ❗ Demo only – no real password change
        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully (demo)'
        ]);
    }

    /**
     * Save Organization Settings (Session)
     */
    public function saveOrganization(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'address' => 'required',
        ]);

        $settings = session()->get('settings', []);
        $settings['organization'] = $request->only([
            'name','email','address'
        ]);

        session()->put('settings', $settings);

        return response()->json([
            'success' => true,
            'message' => 'Organization details saved'
        ]);
    }
}
