<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AccountDeletionRequest;

class LegalController extends Controller
{
    public function deleteAccount()
    {
        return view('legal.delete-account');
    }

    public function storeDeletionRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reason' => 'nullable|string|max:1000',
        ]);

        AccountDeletionRequest::create($request->only('email', 'reason'));

        return back()->with('success', [
            'gu' => 'તમારી એકાઉન્ટ ડિલીટ કરવાની વિનંતી સબમિટ કરવામાં આવી છે. અમે ૭ કાર્યકારી દિવસોમાં તેની પ્રક્રિયા કરીશું.',
            'en' => 'Your account deletion request has been submitted. We will process it within 7 working days.'
        ]);
    }

    public function adminDeletionRequests()
    {
        $requests = AccountDeletionRequest::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.deletion-requests', compact('requests'));
    }

    public function updateDeletionStatus(Request $request, $id)
    {
        $deletionRequest = AccountDeletionRequest::findOrFail($id);
        $deletionRequest->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully.');
    }
}
