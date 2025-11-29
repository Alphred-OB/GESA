<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        /** @var User|null $admin */
        $admin = $request->user('admin');

        if (! $admin || $admin->role !== 'admin') {
            abort(403);
        }

        $adminRole = $admin->admin_role ?? 'president';

        if (empty($roles) || $adminRole === 'president' || in_array($adminRole, $roles, true)) {
            return $next($request);
        }

        $homeRoute = match ($adminRole) {
            'financial_secretary' => 'admin.dues.index',
            'general_secretary' => 'admin.events.index',
            default => 'admin.dashboard',
        };

        if (! Route::has($homeRoute)) {
            abort(403);
        }

        $message = match ($adminRole) {
            'financial_secretary' => __('Because of your role, you only have access to the dues and financial pages. You have been redirected to the dues section.'),
            'general_secretary' => __('Because of your role, you only have access to events, announcements, and resources. You have been redirected to your workspace.'),
            default => __('You do not have permission to access that section.'),
        };

        return redirect()->route($homeRoute)->with('role_status', $message);
    }
}
