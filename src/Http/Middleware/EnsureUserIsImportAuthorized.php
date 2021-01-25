<?php

namespace Bigmom\Excel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Bigmom\Auth\Facades\Permission;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsImportAuthorized
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
        if (Permission::allows(Auth::guard('bigmom')->user(), 'excel-admin')
            || Permission::allows(Auth::guard('bigmom')->user(), 'excel-import')) {
            return $next($request);
        } else {
            abort(403, "User is not authorized to access this link. Are you sure you are accessing the correct link?");
        }
    }
}
