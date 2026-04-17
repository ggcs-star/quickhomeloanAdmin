<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Loan;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks.index');
    }

    // 🔹 List all tasks (WITH LEAD DATA)
    public function list()
    {
        return Task::orderBy('created_at','desc')
            ->get()
            ->map(function ($task) {

                $lead = null;

                if ($task->lead_id) {
                    $lead = Loan::find($task->lead_id);
                }

                return [
                    '_id'         => (string) $task->_id,
                    'title'       => $task->title,
                    'description' => $task->description,
                    'priority'    => $task->priority,
                    'status'      => $task->status,
                    'due_date'    => $task->due_date,

                    // ✅ REAL LEAD DATA
                    'lead_name'   => $lead->data['full_name'] ?? '-',
                    'lead_mobile' => $lead->data['mobile'] ?? '-',
                    'lead_email'  => $lead->data['email'] ?? '-',

                    'assigned_to' => $task->assigned_to ?? '-',
                ];
            });
    }

    // 🔹 Create task
    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required',
            'priority' => 'required',
        ]);

        return Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'due_date'    => $request->due_date,

            // ✅ ONLY lead_id store karo
            'lead_id'     => $request->lead_id,

            'assigned_to' => $request->assigned_to,
            'status'      => 'Pending',
        ]);
    }

    // 🔹 Mark completed
    public function complete($id)
    {
        $task = Task::findOrFail($id);
        $task->status = 'Completed';
        $task->save();

        return response()->json(['ok' => true]);
    }
}
