<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send OTP email to the student.
     *
     * @param string $email
     * @param string $otp
     * @param string $purpose
     * @return bool
     */
    public function sendOtpEmail($email, $otp, $purpose)
    {
        $subject = $purpose === 'signup' ? 'Account Verification OTP' : 'Login OTP';
        $messageText = $purpose === 'signup' 
            ? "Your signup OTP is {$otp}\n\nThis OTP will expire in 5 minutes."
            : "Your login OTP is {$otp}\n\nThis OTP expires in 5 minutes.";

        try {
            Mail::raw($messageText, function ($message) use ($email, $subject) {
                $message->to($email)
                        ->subject($subject);
            });
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email to {$email}: " . $e->getMessage());
            return false;
        }
    }
}
