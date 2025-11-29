<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkCourseRegistrationActionRequest;
use App\Http\Requests\Admin\UpdateCourseRegistrationRequest;
use App\Models\CourseRegistration;
use App\Models\User;
use App\Services\CourseRegistration\CourseRegistrationNotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AdminCourseRegistrationController extends Controller
{
    private const STATUSES = ['in_progress', 'submitted', 'approved', 'rejected'];

    public function __construct(private readonly CourseRegistrationNotificationService $notificationService)
    {
    }

    public function index(Request $request): RedirectResponse
    {
        return redirect()
            ->route('admin.dashboard')
            ->with('status', __('Course registration admin panel is currently disabled.'));
    }

    public function bulk(BulkCourseRegistrationActionRequest $request): Response
    {
        return redirect()
            ->route('admin.dashboard')
            ->with('status', __('Course registration admin panel is currently disabled.'));
    }

    public function show(Request $request, CourseRegistration $registration)
    {
        return redirect()
            ->route('admin.dashboard')
            ->with('status', __('Course registration admin panel is currently disabled.'));
    }

    public function update(UpdateCourseRegistrationRequest $request, CourseRegistration $registration): RedirectResponse
    {
        return redirect()
            ->route('admin.dashboard')
            ->with('status', __('Course registration admin panel is currently disabled.'));
    }
}
