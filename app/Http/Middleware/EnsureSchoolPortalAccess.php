<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EnsureSchoolPortalAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Must be Principal or School Staff
        if (!$user->isPrincipal() && !$user->isSchoolStaff()) {
            abort(403, 'Unauthorized access to School Portal.');
        }
        
        // Must belong to a school
        if (!$user->school_id) {
            abort(403, 'No school assigned to your account.');
        }

        return $next($request);
    }
}
