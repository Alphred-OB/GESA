<?php

namespace App\Services\Admin;

use App\Models\CourseRegistration;
use App\Models\Due;
use App\Models\Event;
use App\Models\PendingRegistration;
use App\Models\Suggestion;
use App\Models\SupportResource;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminDashboardService
{
    public function overview(?User $admin): array
    {
        $adminName = $admin?->fullname ?? $admin?->username ?? 'Administrator';
        $adminRole = $admin?->role;
        $greeting = $this->greetingForNow();
        $now = Carbon::now();

        $studentCount = User::query()
            ->where('role', 'student')
            ->count();

        // Count upcoming events
        $upcomingEventsCount = Event::query()
            ->upcoming()
            ->count();
        $eventsThisMonth = Event::query()
            ->upcoming()
            ->where('start_at', '>=', $now->copy()->startOfMonth())
            ->where('start_at', '<=', $now->copy()->endOfMonth())
            ->count();

        // Changed from CourseRegistration to PendingRegistration (student user registrations)
        $registrationQuery = PendingRegistration::query();
        $pendingRegistrations = (clone $registrationQuery)
            ->where('status', 'pending')
            ->count();
        $submittedToday = (clone $registrationQuery)
            ->whereDate('created_at', $now->toDateString())
            ->count();
        $approvedThisWeek = (clone $registrationQuery)
            ->where('status', 'approved')
            ->where('reviewed_at', '>=', $now->copy()->startOfWeek())
            ->count();

        $suggestionsPending = Suggestion::query()
            ->where('status', 'pending')
            ->count();
        $suggestionsResolvedWeek = Suggestion::query()
            ->where('status', 'resolved')
            ->where('handled_at', '>=', $now->copy()->startOfWeek())
            ->count();

        $overviewCards = [
            [
                'label' => 'Students onboarded',
                'value' => number_format($studentCount),
                'description' => 'Active student accounts on the platform.',
                'icon' => 'ri-user-star-fill',
                'link' => route('admin.students.index'),
                'cta' => 'Manage students',
            ],
            [
                'label' => 'Active events',
                'value' => number_format($upcomingEventsCount),
                'description' => $eventsThisMonth . ' scheduled this month.',
                'icon' => 'ri-calendar-event-fill',
                'link' => route('admin.events.index'),
                'cta' => 'View events',
            ],
            [
                'label' => 'Pending registrations',
                'value' => number_format($pendingRegistrations),
                'description' => $approvedThisWeek . ' approved this week.',
                'icon' => 'ri-user-add-fill',
                'link' => route('admin.pending-registrations.index'),
                'cta' => 'See registrations',
            ],
            [
                'label' => 'Suggestions awaiting review',
                'value' => number_format($suggestionsPending),
                'description' => $suggestionsResolvedWeek . ' resolved this week.',
                'icon' => 'ri-chat-smile-3-fill',
                'link' => route('admin.suggestions.index'),
                'cta' => 'Open suggestions',
            ],
        ];

        $upcomingEvents = Event::query()
            ->upcoming()
            ->limit(3)
            ->get()
            ->map(function (Event $event): array {
                return [
                    'title' => $event->title,
                    'schedule' => optional($event->start_at)->isoFormat('MMM D · h:mm A'),
                    'category' => Str::headline($event->category ?? 'General'),
                    'location' => $event->location,
                ];
            });

        $recentSuggestions = Suggestion::query()
            ->with(['user:user_id,fullname,username,email'])
            ->latest()
            ->limit(3)
            ->get()
            ->map(function (Suggestion $suggestion): array {
                return [
                    'subject' => $suggestion->subject,
                    'category' => Str::headline($suggestion->category ?? 'General'),
                    'status' => Str::headline($suggestion->status ?? 'Pending'),
                    'submitted_at' => optional($suggestion->created_at)->diffForHumans(),
                    'owner' => $suggestion->user?->fullname
                        ?? $suggestion->user?->username
                        ?? 'Student',
                ];
            });

        return [
            'adminName' => $adminName,
            'hero' => [
                'greeting' => $greeting,
                'message' => 'Monitor student activity, approvals, and support trends from a single view.',
                'lastUpdated' => $now->isoFormat('MMMM D, YYYY [at] h:mm A'),
            ],
            'overviewCards' => $overviewCards,
            'registrationSummary' => [
                'pending' => $pendingRegistrations,
                'submittedToday' => $submittedToday,
                'approvedThisWeek' => $approvedThisWeek,
            ],
            'resourcesTotal' => SupportResource::query()->count(),
            'upcomingEvents' => $upcomingEvents,
            'recentSuggestions' => $recentSuggestions,
        ];
    }

    private function greetingForNow(): string
    {
        $now = Carbon::now();
        $hour = (int) $now->format('H');

        return match (true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default => 'Good evening',
        };
    }
}
