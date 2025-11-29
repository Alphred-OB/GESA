# UI Components

<!-- Existing content assumed here -->

## Suggestion Box Page

- **Location**: `resources/views/dashboards/student/suggestions/index.blade.php`
- **Purpose**: Allows students to submit feedback, ideas, or issues directly to the ACSES team and review their past submissions.
- **Key Elements**:
  - Gradient hero card describing the response window and purpose.
  - Submission form with the following controls:
    - Category select (`general`, `academic`, `facilities`, `technology`, `wellness`, `other`).
    - Subject text input (160 character max).
    - Message textarea with detailed guidance.
    - Optional file attachment (PNG/JPG/PDF/DOC/DOCX, max 4 MB) stored under `storage/app/public/suggestions`.
  - Sidebar with best-practice tips for writing suggestions and contact options for urgent issues.
  - Submissions table listing subject, category, relative submission time, status badge, and attachment download links.
- **Interactive States**:
  - Success alert on submission (status text pulled from session flash data).
  - Validation error summary with bullet list (FormRequest handles validation).
  - Pagination for suggestion history via Tailwind-styled Laravel pagination views.

## Suggestion Box Navigation

- Header and mobile menus link to `route('student.suggestions.index')`.
- Footer “Suggestion box” link remains as a placeholder (`#`) unless a public-facing link is introduced.

## Admin · Student Accounts Dashboard

- **Location**: `resources/views/dashboards/admin/students/index.blade.php`
- **Purpose**: Gives administrators a single hub to review class/year distribution, manage student records, and export data.
- **Key Elements (2025-11-12 update)**:
  - Stat grid with a hero “Total students” card plus per-class cards, each showing Year 1–4 counts drawn from `StudentAccountService::stats()`.
  - Filters for search, class, and year with right-aligned Apply CTA matching ACSES styling.
  - Paginated table (desktop and mobile layouts) using custom pagination view `vendor/pagination/data-limit.blade.php`.
- **Data Source**: `app/Services/Admin/StudentAccountService.php` aggregates totals and year breakdown per class.

## Admin · Suggestion Centre

- **Location**: `resources/views/dashboards/admin/suggestions/index.blade.php`
- **Purpose**: Allows administrators to triage student feedback with filters, metrics, and detailed views.
- **Key Elements (2025-11-12 add)**:
  - Metrics cards for total suggestions, pending review, and resolved this week (from `AdminSuggestionService::metrics()`).
  - Filter bar with search, category, status selectors, right-aligned Apply button, and rows-per-page selector.
  - Responsive suggestion list: desktop table plus mobile cards, each linking to detailed view.
  - Pagination uses `vendor/pagination/data-limit.blade.php` for consistency.
- **Detail View**: `resources/views/dashboards/admin/suggestions/show.blade.php` displays subject, student info, formatted message, and attachment download link.
- **Data Source**: `app/Services/Admin/AdminSuggestionService.php` provides filtered paginator, categories, statuses, and metrics.

## Admin · Announcements Broadcast

- **Location**: `resources/views/dashboards/admin/announcements/index.blade.php` & `create.blade.php`
- **Purpose**: Empower administrators to compose announcements and monitor delivery to all students or targeted groups.
- **Key Elements (2025-11-12 add)**:
  - Filterable listing (search, type, priority, audience) with delivery counts and responsive mobile cards.
  - Compose form with rich text area, type/priority selectors, and audience toggles (all, specific class, year group, or selected students) backed by Alpine-powered conditional fields.
  - Tailwind-only styling consistent with admin dashboard, using CTA buttons and descriptive helper copy.
- **Backend Source**: `app/Services/Admin/AdminAnnouncementService.php` aggregates options, persists announcements, and dispatches notifications.
- **Notifications**: `App\Notifications\StudentAnnouncementPublishedNotification` sends email using `resources/views/emails/announcements/student-announcement.blade.php`.
