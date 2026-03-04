<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SchoolStaff;

class EnsureCanUploadStudents
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

        // Principals can always upload
        if ($user->isPrincipal()) {
            return $next($request);
        }

        // School Staff must have permission
        if ($user->isSchoolStaff()) {
             // Find the staff record for this user
             $staffRecord = SchoolStaff::where('user_id', $user->id)->first();
             
             if ($staffRecord && $staffRecord->can_upload_students) {
                 return $next($request);
             }
        }

        abort(403, 'You do not have permission to manage students.');
    }
}
