<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        /** @var User $user */
        $user = Auth::user();

        if(!($user && $user->hasAnyRole($roles))) {
            return redirect(route('login'));
        }

        return $next($request);
    }
}
