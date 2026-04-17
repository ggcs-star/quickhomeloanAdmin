<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    /**
     * 📌 Projects Page
     * URL: /projects
     * View: projects/index.blade.php
     */
    public function index(Request $request)
    {
        // 🔹 STATIC PROJECT LIST
        $projects = [
            [
                'name'     => 'Godrej Panorama',
                'builder'  => 'Godrej Properties',
                'city'     => 'Pune',
                'approved' => 1,
                'pending'  => 0,
            ],
            [
                'name'     => 'Lodha Palava',
                'builder'  => 'Lodha Group',
                'city'     => 'Mumbai',
                'approved' => 2,
                'pending'  => 0,
            ],
            [
                'name'     => 'Prestige Falcon City',
                'builder'  => 'Prestige Group',
                'city'     => 'Bangalore',
                'approved' => 0,
                'pending'  => 1,
            ],
            [
                'name'     => 'DLF The Crest',
                'builder'  => 'DLF Ltd.',
                'city'     => 'Gurgaon',
                'approved' => 0,
                'pending'  => 0,
            ],
        ];

        // 🔍 SEARCH
        if ($request->search) {
            $search = strtolower($request->search);

            $projects = array_filter($projects, function ($project) use ($search) {
                return str_contains(strtolower($project['name']), $search)
                    || str_contains(strtolower($project['builder']), $search)
                    || str_contains(strtolower($project['city']), $search);
            });
        }

        // 🔹 BANKS + AFP STATUS (DETAIL VIEW ke liye)
        $banks = [
            'HDFC Bank',
            'ICICI Bank',
            'SBI',
            'Axis Bank',
            'Kotak Mahindra Bank',
            'Yes Bank',
            'PNB Housing',
            'LIC Housing',
        ];

        $afpStatuses = [
            [
                'bank'   => 'HDFC Bank',
                'status' => 'Approved',
                'notes'  => 'Pre-approved for all configurations.',
            ],
        ];

        return view('projects.index', compact('projects','banks','afpStatuses'));
    }
}
