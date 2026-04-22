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

    public function requestDeletionOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students,email',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        $otp = rand(100000, 999999);

        \App\Models\OtpVerification::updateOrCreate(
            ['email' => $request->email, 'purpose' => 'reset'],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
                'verified_at' => null,
                'attempts' => 0
            ]
        );

        \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\DeletionOtpMail($otp));

        return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
    }

    public function storeDeletionRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students,email',
            'otp' => 'required|string|size:6',
            'reason' => 'nullable|string|max:1000',
        ]);

        $verification = \App\Models\OtpVerification::where('email', $request->email)
            ->where('purpose', 'reset') // Reusing reset purpose for convenience
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->whereNull('verified_at')
            ->first();

        if (!$verification) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.'])->withInput();
        }

        // Mark OTP as verified
        $verification->update(['verified_at' => now()]);

        AccountDeletionRequest::create($request->only('email', 'reason'));

        return back()->with('success', [
            'gu' => 'તમારી એકાઉન્ટ ડિલીટ કરવાની વિનંતી સફળતાપૂર્વક ચકાસવામાં આવી છે અને સબમિટ કરવામાં આવી છે.',
            'en' => 'Your account deletion request has been verified and submitted successfully.'
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
