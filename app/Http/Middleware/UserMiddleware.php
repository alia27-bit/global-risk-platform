<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->peran !== 'user') {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        return $next($request);
    }
}
