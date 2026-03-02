<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PeternakMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {

        if (! Auth::check()) {

            return redirect()->route('login');

        }

        if (! Auth::user()->isPeternak()) {

            abort(403, 'Akses ditolak. Hanya peternak.');

        }

        return $next($request);
    }
}