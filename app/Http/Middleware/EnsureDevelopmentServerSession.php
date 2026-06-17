<?php

namespace App\Http\Middleware;

use App\Support\DevelopmentSessionManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDevelopmentServerSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->environment('local')) {
            return $next($request);
        }

        $currentToken = DevelopmentSessionManager::currentServerToken();

        if (! $currentToken) {
            return $next($request);
        }

        $sessionToken = $request->session()->get('_dev_server_token');

        if ($sessionToken !== $currentToken) {
            Auth::logout();
            $request->session()->flush();
            $request->session()->regenerate();
        }

        $request->session()->put('_dev_server_token', $currentToken);

        return $next($request);
    }
}
