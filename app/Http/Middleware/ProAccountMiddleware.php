<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProAccountMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $peternak = $user->peternak;

        if (! $peternak) {
            abort(403, 'Peternak profile not found.');
        }

        // Check if peternak has pro account and it's still valid
        if (! $peternak->isPro()) {
            abort(403, 'Pro account required. Please upgrade your account.');
        }

        return $next($request);
    }
}
