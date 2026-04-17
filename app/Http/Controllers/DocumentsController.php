<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Loan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; 

class DocumentsController extends Controller
{
   
    public function index()
    {
        $documents = Document::latest()->get();

        $loans = Loan::all()->keyBy(function ($loan) {
            return (string) $loan->_id;
        });

        return view('documents.index', compact('documents', 'loans'));
    }

    
  public function store(Request $request)
{
    Log::info('Document upload request', $request->all());

    $request->validate([
        'loan_id' => 'required',
        'file'    => 'required|file|max:5120',
    ]);

    // ✅ File save (relative path)
    $storedPath = $request->file('file')->store('documents', 'public');

    // ✅ ONLY THIS CHANGE
    Document::create([
        'loan_id'    => (string) $request->loan_id,
        'file_path' => $storedPath,   // 👈 documents/xyz.jpg
        'status'    => 'pending',
    ]);

    Loan::where('_id', $request->loan_id)
        ->update(['pipeline_step' => 2]);

    return back()->with('success', 'Document uploaded successfully');
}

    public function verify($id)
    {
        $doc = Document::where('_id', $id)->firstOrFail();

        $doc->update(['status' => 'verified']);

        Loan::where('_id', $doc->loan_id)
            ->update(['pipeline_step' => 3]);

        return back()->with('success', 'Document verified');
    }
}
