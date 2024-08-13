<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOtpIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()->otp_verified_at) {
            return redirect()->route('otp.show');
        }

        return $next($request);
    }
}
