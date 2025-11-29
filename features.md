# ACSES Portal Features

> Mark completed items with `[x]`.

## Authentication & Access Control
- [x] Account segmentation
  - [x] Configure student guard and authentication provider
  - [x] Configure admin guard and authentication provider
- [x] Login flow
  - [x] Build login page UI for students and admins
  - [x] Validate credentials, enforce email verification, and start role-specific sessions with OTP 2FA
  - [x] Redirect users to their dashboards after successful OTP verification
- [x] Sign-up flow
  - [x] Collect student registration details with validations
  - [x] Create student profile records on successful sign-up
  - [x] Send branded OTP-based verification email upon registration completion
- [x] Password recovery
  - [x] Provide forgot-password request form
  - [x] Generate and email secure password reset links
  - [x] Validate reset tokens and allow password update submission
- [ ] Access enforcement
  - [x] Protect admin routes with middleware checks
  - [x] Restrict student routes to student-facing resources
  - [ ] Audit access attempts and denial events

## Student Portal
- [ ] Student dashboard
  - [ ] Display dues balances and upcoming deadlines
  - [ ] Surface latest announcements with priority tags
  - [ ] Highlight upcoming events and quick actions
- [ ] Course registration
  - [ ] Upload required registration documents
  - [ ] Download templates and approval letters
  - [ ] Track document approval status per semester
- [ ] Dues & payments
  - [ ] Submit online payment via integrated gateway
  - [ ] Register manual payments with reference evidence
  - [ ] View payment history and current status
- [ ] Announcements
  - [ ] Browse announcement list with filters
  - [ ] Open announcement detail pages and attachments
- [ ] Events
  - [ ] View events calendar and detailed descriptions
  - [ ] Download event tickets or RSVP confirmations
- [ ] Suggestion box
  - [ ] Submit new ideas with categories or tags
  - [ ] Track suggestion progress and admin responses
- [ ] Profile & support
  - [ ] Update personal contact and academic details
  - [ ] Access help center articles and support channels

## Admin Portal
- [ ] Admin dashboard
  - [ ] Present key metrics on student engagement
  - [ ] Visualize dues collection and outstanding balances
- [ ] Student management
  - [ ] Search students by name, ID, or program
  - [ ] Filter students by class, year, or status
  - [ ] Edit student profiles and enrollment details
- [ ] Course registration approvals
  - [ ] Review pending registration submissions
  - [ ] Approve or reject with feedback notes
  - [ ] Export registration summaries (CSV/PDF)
- [ ] Dues configuration
  - [ ] Define dues types and default amounts
  - [ ] Assign dues to cohorts or individual students
  - [ ] Reconcile submitted payments against dues
- [ ] Content publishing
  - [ ] Publish announcements with attachments and priority
  - [ ] Schedule events and upload related resources
  - [ ] Manage downloadable assets for students
- [ ] Operations oversight
  - [ ] Monitor payment gateway and notification settings
  - [ ] Track SMS balance for transactional messaging
  - [ ] Manage suggestion box follow-up workflow
- [ ] Reporting
  - [ ] Generate dues collection reports
  - [ ] Generate payment reconciliation reports
  - [ ] Generate event ticket holder reports

## Shared & Public Features
- [ ] Suggestion collaboration
  - [ ] Provide suggestion submission access to all roles
  - [ ] Display unified suggestion status timeline
- [ ] File storage & access
  - [ ] Organize announcement, event, and registration file libraries
  - [ ] Enforce role-based access on downloads
- [ ] Platform services
  - [ ] Maintain centralized database connections
  - [ ] Configure transactional email delivery
  - [ ] Monitor job queues and background tasks
