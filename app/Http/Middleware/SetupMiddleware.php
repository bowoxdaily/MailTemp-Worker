<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetupMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->routeIs('setup.*') && ! User::where('is_admin', true)->exists()) {
            return redirect()->route('setup.index');
        }

        return $next($request);
    }
}
