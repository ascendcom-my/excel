<?php

namespace Bigmom\Excel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnsureUserIsExportAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('excel.restrict-usage')) {
            $allowed = app()->environment('local')
                || Gate::forUser(Auth::guard('excel')->user())->allows('excel-admin')
                || Gate::forUser(Auth::guard('excel')->user())->allows('excel-export');

            abort_unless($allowed, 403);
        }

        return $next($request);
    }
}
