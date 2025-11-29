# Project Structure Updates

## 2025-11-10
- Removed forum-related triggers from `database/migrations/0001_01_01_000000_create_users_table.php` since forum integration is no longer required.
- Added migration `2025_11_10_143500_drop_forum_triggers.php` to clean up legacy triggers and `2025_11_10_144000_add_email_verified_at_to_users.php` to support email verification timestamps.
- Introduced `app/Services/Auth/EmailVerificationService.php` and `app/Http/Controllers/Auth/EmailVerificationController.php` to handle verification workflows.
- Added verification notice view at `resources/views/auth/verify-notice.blade.php` and updated registration email template for verification CTA.
- Added password reset support: migration `2025_11_10_160000_create_password_reset_tokens_table.php`, controllers `PasswordResetLinkController` & `NewPasswordController`, form requests, notifications, Blade views (`auth/forgot-password`, `auth/reset-password`, email template), and password reset routes.
- Added migration `2025_11_10_161000_add_remember_token_to_users_table.php` to ensure `remember_token` column exists for authentication features.
- Introduced student dashboard UI: new layout `resources/views/components/layouts/dashboard.blade.php` plus shared header/footer components under `resources/views/components/dashboard/`, and overhauled `resources/views/dashboards/student/index.blade.php` for the full experience.

## 2025-11-11
- Added dynamic student dashboard data pipeline: new models (`Due`, `Announcement`, `Event`, `CourseRegistration`, `SupportResource`) with respective migrations under `database/migrations/2025_11_11_*`.
- Created service `app/Services/Student/StudentDashboardService.php` to aggregate personalized dashboard data.
- Registered invokable controller `app/Http/Controllers/Student/StudentDashboardController.php` and updated `routes/web.php` to resolve `student.dashboard` via the service instead of static view.
- Updated `resources/views/dashboards/student/index.blade.php` to consume controller-provided data (hero, quick actions, tips, events, timeline, calendar, support resources).
- Replaced weekly dashboard calendar with branded monthly matrix highlighting upcoming events fed by the dashboard service.
- Added admin events management layout: updated `app/Http/Controllers/Admin/AdminEventController.php` for configurable pagination and created `resources/views/dashboards/admin/events/index.blade.php` to render the events table with controls.
- Built admin event CRUD views: shared form partial at `resources/views/dashboards/admin/events/partials/form.blade.php` plus `create.blade.php` and `edit.blade.php` using Remix Icons and admin layout.
- Added responsive admin event list partials for desktop and mobile experiences: `resources/views/dashboards/admin/events/partials/table-rows.blade.php` and `.../mobile-list.blade.php`.
- Removed legacy timeline UI/fields from admin & student dashboards: stripped timeline controls from event forms/views, simplified student dashboard calendar, and updated validation/models.
- Added student email notification pipeline for new events: `app/Notifications/StudentEventCreatedNotification.php`, mailing integration in `AdminEventController`, and template `resources/views/emails/events/student-event-created.blade.php`.
- Added event banner support: migration `2025_11_11_201500_add_banner_fields_to_events_table.php`, updated `AdminEventController` for storage/removal, extended form partial for preview uploads, and exposed banner data to student listings.

## 2025-11-12
- Introduced custom pagination template at `resources/views/vendor/pagination/data-limit.blade.php` to render ACSES-styled numbered pagination for admin tables, and wired it into student accounts and course registration index views.
- Added admin suggestions management: new service `app/Services/Admin/AdminSuggestionService.php`, controller `app/Http/Controllers/Admin/AdminSuggestionController.php`, routes for `admin/suggestions`, and Blade views under `resources/views/dashboards/admin/suggestions/` (`index.blade.php`, `show.blade.php`).
- Enabled admin announcements broadcast: service `app/Services/Admin/AdminAnnouncementService.php`, controller `app/Http/Controllers/Admin/AdminAnnouncementController.php`, form request `app/Http/Requests/Admin/StoreAnnouncementRequest.php`, notification `app/Notifications/StudentAnnouncementPublishedNotification.php`, email view `resources/views/emails/announcements/student-announcement.blade.php`, admin Blade screens under `resources/views/dashboards/admin/announcements/`, and added targeting columns migration `2025_11_12_103000_create_announcements_table.php`.
- Launched academic timeline management: migration `2025_11_12_171000_create_academic_timeline_entries_table.php`, model `app/Models/AcademicTimelineEntry.php`, shared service `app/Services/AcademicTimelineService.php`, admin requests/controller/views under `resources/views/dashboards/admin/timeline/`, student dashboard integration, and routes via `AdminAcademicTimelineController` for CRUD.
- Extended admin announcements with update/delete support: new `app/Http/Requests/Admin/UpdateAnnouncementRequest.php`, controller edit/update/destroy actions, shared form partial at `resources/views/dashboards/admin/announcements/partials/form.blade.php`, and dedicated edit view `resources/views/dashboards/admin/announcements/edit.blade.php`.
- Enabled admin dues maintenance: controller edit/update/destroy actions, validation via `app/Http/Requests/Admin/UpdateDueRequest.php`, service helpers in `app/Services/Admin/AdminDueService.php`, Blade actions in `resources/views/dashboards/admin/dues/index.blade.php`, and new edit form at `resources/views/dashboards/admin/dues/edit.blade.php`.

## 2025-11-14
- Added `is_graduated` boolean column to `users` via migration `2025_11_14_230000_add_is_graduated_to_users_table.php` to track when students have completed year 4.
- Updated `App\Models\User` to mark `is_graduated` as mass assignable and cast it to boolean.
- Updated `App\Services\Admin\StudentAccountService` so:
  - Student stats, filter options, and listing queries only include non-graduated students (`where('is_graduated', false)`).
  - `promoteAllStudents()` now promotes years 1–3 as before, but when a student in year 4 is promoted they are instead marked as graduated (`is_graduated = true`) and excluded from future promotions and stats.

## 2025-11-15
- Added reusable marketing hero Blade component at `resources/views/components/hero-section.blade.php` to power the public landing hero. It uses Tailwind-only styling with the primary brand color `#0b3019`, responsive two-column layout, and CTA buttons for login and a product tour.

## 2025-11-17
- Removed the "GESA Store" navigation item from the student dashboard header dropdown menu in `resources/views/components/dashboard/header.blade.php` since the external store is no longer part of the primary navigation.
