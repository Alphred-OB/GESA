<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreCourseRegistrationRequest;
use App\Models\CourseRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentCourseRegistrationController extends Controller
{
    public function show(Request $request): RedirectResponse
    {
        return redirect()
            ->route('student.dashboard')
            ->with('status', __('Course registration is currently disabled.'));
    }

    public function store(StoreCourseRegistrationRequest $request): RedirectResponse
    {
        return redirect()
            ->route('student.dashboard')
            ->with('status', __('Course registration is currently disabled.'));
    }

    public function destroyDocument(Request $request, CourseRegistration $registration, string $encodedPath): RedirectResponse
    {
        return redirect()
            ->route('student.dashboard')
            ->with('status', __('Course registration is currently disabled.'));
    }
}
