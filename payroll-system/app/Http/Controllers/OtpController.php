<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OtpController extends Controller
{
    public function show()
    {
        return Inertia::render('User/OTP');
    }

    public function send(Request $request)
    {
        $user = Auth::user();

        if (!Session::has('otp_sent') || Session::get('otp_expires_at') < now()) {
            $this->generateAndSendOtp($user);
            Session::put('otp_sent', true);
            Session::put('otp_expires_at', now()->addMinutes(5));
            return response()->json([
                'message' => 'An OTP has been sent to your email address.'
            ]);
        } else {
            return response()->json([
                'message' => 'An OTP has already been sent to your email address.'
            ]);
        }
    }

    public function verify(Request $request)
    {
        $otp = is_array($request->otp) ? implode('', $request->otp) : $request->otp;

        $request->merge(['otp' => $otp]);

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if (empty($otp)) {
            return back()->withErrors([
                'otp' => 'Please enter the OTP.'
            ]);
        }

        $user = Auth::user();

        if ($this->verifyOtpLogic($user, $otp)) {
            $user->otp_verified_at = now();
            $user->save();

            Session::forget('otp_sent');
            Session::forget('otp_expires_at');

            return redirect()->route('user.dashboard')->with('message', 'OTP verified successfully');
        } else {
            return back()->withErrors([
                'otp' => 'Invalid OTP. Please try again.'
            ]);
        }
    }
    public function resend()
    {
        $user = Auth::user();

        if ($this->hasRecentOtp($user)) {
            return response()->json([
                'success' => false,
                'message' => 'An OTP was already sent recently. Please wait before requesting a new one.'
            ], 429);
        }

        try {
            $this->generateAndSendOtp($user);
            Session::put('otp_sent', true);
            Session::put('otp_expires_at', now()->addMinutes(5));

            return response()->json([
                'success' => true,
                'message' => 'OTP has been resent to your email.'
            ]);
        } catch (\Exception $e) {
            Log::error('OTP resend failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again later.'
            ], 500);
        }
    }

    private function generateAndSendOtp(User $user)
    {
        $otp = sprintf('%06d', mt_rand(0, 999999));
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));
    }

    private function verifyOtpLogic(User $user, $otp)
    {
        return $user->otp === $otp && $user->otp_expires_at > now();
    }

    private function hasRecentOtp(User $user)
    {
        return Session::has('otp_expires_at') && Session::get('otp_expires_at') > now();
    }
}
