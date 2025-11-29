<?php

namespace App\Http\Middleware;

use App\Models\Due;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentHasNoOutstandingDues
{
    /**
     * If the authenticated student has outstanding dues, restrict access to
     * certain routes by redirecting them to the dues page with a notice.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $student = $request->user('student');

        if (! $student) {
            return $next($request);
        }

        $route = $request->route();
        $routeName = $route?->getName();

        // Routes that should be blocked when dues are outstanding.
        $blockedRoutes = [
            'student.dashboard',
            'student.suggestions.index',
            'student.suggestions.store',
            'student.announcements.index',
            'student.announcements.show',
            'student.events.index',
            'student.events.ics',
            'student.resources.index',
        ];

        if (! $routeName || ! in_array($routeName, $blockedRoutes, true)) {
            return $next($request);
        }

        $hasOutstanding = Due::outstanding()
            ->where('student_id', $student->getAuthIdentifier())
            ->exists();

        if (! $hasOutstanding) {
            return $next($request);
        }

        return redirect()
            ->route('student.dues.index')
            ->with('student_portal_limited', true)
            ->with('student_portal_blocked_route', $routeName);
    }
}
