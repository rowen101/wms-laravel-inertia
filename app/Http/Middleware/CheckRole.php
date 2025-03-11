<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role->name === $role) {
            return $next($request);
        }
        abort(403);
    }
}
