<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventBackHistoryMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        optional($response)->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');

        return $response;
    }
}
