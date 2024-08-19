<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
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
        $user = $this->getAuthenticatedUser();

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

        $user = $this->getAuthenticatedUser();

        if ($this->verifyOtpLogic($user, $otp)) {
            $user->otp_verified_at = now();
            $user->save();

            Session::forget('otp_sent');
            Session::forget('otp_expires_at');

            return $this->redirectToDashboard($user);
        } else {
            return back()->withErrors([
                'otp' => 'Invalid OTP. Please try again.'
            ]);
        }
    }

    public function resend()
    {
        $user = $this->getAuthenticatedUser();

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

    private function generateAndSendOtp($user)
    {
        $otp = sprintf('%06d', mt_rand(0, 999999));
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));
    }

    private function verifyOtpLogic($user, $otp)
    {
        return $user->otp === $otp && $user->otp_expires_at > now();
    }

    private function hasRecentOtp($user)
    {
        return Session::has('otp_expires_at') && Session::get('otp_expires_at') > now();
    }

    private function getAuthenticatedUser()
    {
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        } elseif (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        }

        throw new \Exception('No authenticated user found');
    }

    private function redirectToDashboard($user)
    {
        if ($user instanceof User) {
            if ($user->role_number == 1) {
                return redirect()->route('user.dashboard')->with('message', 'OTP verified successfully');
            }
            if ($user->role_number == 2) {
                return redirect()->route('admin.dashboard')->with('message', 'OTP verified successfully');
            }
        }

        // If we reach here, it means we couldn't determine the correct dashboard
        Log::error('Unable to determine dashboard for user: ' . $user->id);
        return redirect()->route('login')->with('error', 'Unable to access dashboard. Please contact support.');
    }
}


/*

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
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
        $user = $this->getAuthenticatedUser();

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

        $user = $this->getAuthenticatedUser();

        if ($this->verifyOtpLogic($user, $otp)) {
            $user->otp_verified_at = now();
            $user->save();

            Session::forget('otp_sent');
            Session::forget('otp_expires_at');

            return $this->redirectToDashboard($user);
        } else {
            return back()->withErrors([
                'otp' => 'Invalid OTP. Please try again.'
            ]);
        }
    }

    public function resend()
    {
        $user = $this->getAuthenticatedUser();

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

    private function generateAndSendOtp($user)
    {
        $otp = sprintf('%06d', mt_rand(0, 999999));
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));
    }

    private function verifyOtpLogic($user, $otp)
    {
        return $user->otp === $otp && $user->otp_expires_at > now();
    }

    private function hasRecentOtp($user)
    {
        return Session::has('otp_expires_at') && Session::get('otp_expires_at') > now();
    }

    private function getAuthenticatedUser()
    {
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        } elseif (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        }

        throw new \Exception('No authenticated user found');
    }

    private function redirectToDashboard($user)
    {
        if ($user instanceof User) {
            return redirect()->route('user.dashboard')->with('message', 'OTP verified successfully');
        } elseif ($user instanceof Admin) {
            return redirect()->route('admin.dashboard')->with('message', 'OTP verified successfully');
        }

        throw new \Exception('Invalid user type');
    }
}
*/
