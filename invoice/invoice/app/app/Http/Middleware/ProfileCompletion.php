<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;


class ProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->routeIs('auth.logout') && !$request->routeIs('app.user.details')) {
            $user = Auth::user();
            if ($user->role != "manager" || $user->role != "admin" || $user->role != "super") {
                $isProfileUpdated = Cache::get("user_details_{$user->id}");
                if (!$isProfileUpdated || $isProfileUpdated == "false") {
                    return redirect(route('app.user.details'))->with('warning', "Please fill the profile details before you can continue");
                }
            }
        }
        return $next($request);
    }
}
