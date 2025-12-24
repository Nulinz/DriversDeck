<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Corporate;
use App\Models\User_active;

class Cook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('corporate')->check()) {
            $cookie = $request->cookie('corp_auth_token');

            if ($cookie) {
                try {
                    $userId = decrypt($cookie);
                    $user = Corporate::find($userId);

                    if ($user) {
                        Auth::guard('corporate')->login($user);
                    }
                } catch (\Exception $e) {
                    // Cookie was tampered or invalid
                    Cookie::queue(Cookie::forget('cook_auth_corp'));
                }
            }
        }

        if (!Auth::guard('web')->check()) {
            $userCookie = $request->cookie('cook_auth_user');

            if ($userCookie) {
                try {
                    $userId = decrypt($userCookie);
                    $user = User_active::find($userId); // Or your user model for this guard

                    if ($user) {
                        Auth::guard('web')->login($user);
                    }
                } catch (\Exception $e) {
                    Cookie::queue(Cookie::forget('cook_auth_user'));
                }
            }
        }

        return $next($request);
    }
}
