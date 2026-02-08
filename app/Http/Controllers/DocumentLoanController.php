<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\DocumentLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentLoanController extends Controller
{
    public function myTokens()
    {
        $tokens = DocumentLoan::with('document')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('loans.my-tokens', compact('tokens'));
    }

    public function manageRequests()
    {
        $user = Auth::user();

        $query = DocumentLoan::with(['document', 'user']);

        if (! $user->hasRole('Admin')) {
            $query->whereHas('document', function ($q) use ($user) {
                $q->where('division_id', $user->division_id)
                    ->orWhereHas('category', function ($c) {
                        $c->where('code', 'CERTIFICATION');
                    });
            });
        }

        $requests = $query->latest()->paginate(10);

        return view('loans.manage-requests', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'duration' => 'required',
            'note' => 'required|string',
        ]);

        DocumentLoan::create([
            'user_id' => Auth::id(),
            'document_id' => $request->document_id,
            'duration' => $request->duration,
            'note' => $request->note,
            'request_date' => now(),
            'status' => 'Pending',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Request Loan',
            'target' => 'Document ID: '.$request->document_id,
            'target_type' => 'Loan',
        ]);

        return redirect()->route('loans.my-tokens')->with('success', 'Request submitted!');
    }

    public function updateStatus(Request $request, $id)
    {
        if (! Auth::user()->hasAnyRole(['Admin', 'Manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $loan = DocumentLoan::with('document')->findOrFail($id);

        if (! $loan->document) {
            $loan->update([
                'status' => 'Canceled',
                'admin_note' => 'System: Document has been deleted.',
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Auto Cancel Loan',
                'target' => 'Loan #'.$loan->id.' (Doc Deleted)',
                'target_type' => 'Loan',
            ]);

            return redirect()->back()->with('error', 'Document not found. Request automatically canceled.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:255',
        ]);

        $action = $request->action;

        if ($action === 'approve') {
            $loan->update([
                'status' => 'Accepted',
                'approved_at' => now(),
                'token' => strtoupper(Str::random(6)),
                'admin_note' => $request->admin_note,
            ]);
        } elseif ($action === 'reject') {
            $loan->update([
                'status' => 'Rejected',
                'admin_note' => $request->admin_note,
            ]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => ucfirst($action).' Loan Request',
            'target' => 'Loan #'.$loan->id.' ('.$loan->document->title.')',
            'target_type' => 'Loan',
        ]);

        return redirect()->back()->with('success', 'Loan request has been '.$action.'d.');
    }
}
