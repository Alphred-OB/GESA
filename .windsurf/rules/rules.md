---
trigger: always_on
---

# ACSES – Project Rules



Primary brand color: `#0b3019` on a clean white theme, per product requirements.

---


```
Rules:
1. Document any structural changes in `docs/structure.md`.
2. Temporary/test files belong under `storage/tmp/` and must be deleted before commit.
3. Maintain PSR-4 namespaces aligned to folder layout.

---

---

## 4. Frontend Standards
1. **Tailwind Only** – no standalone CSS files or inline styles (unless generated via JavaScript). Configure in `tailwind.config.js`; build with `npm run dev/build`.
2. **Responsive First** – mobile-first layouts using Tailwind breakpoints (`sm`, `md`, `lg`, `xl`, `2xl`). No horizontal scrolling; clickable targets ≥ 44×44px on touch screens.
3. **Componentization** – Shared layouts under `resources/views/layouts/`. Role-specific includes live within `resources/views/dashboards/{role}/includes/`. Blade components under `resources/views/components` for cards, buttons, icons, tables, etc.
4. **Iconography** – standardized on font awesome Default sizing `w-5 h-5`. Icons must enhance clarity.
5. **Motion & Feedback** –
   - Buttons: hover color shift, `transform scale-105`, shadow transition (150–250 ms ease-out).
   - Cards: `hover:-translate-y-1 hover:shadow-lg`.
   - Links: color transition + underline on hover.
   - Tables: row hover background highlight.
   - Page load fade-in and modal slide/scale transitions.
6. **Loading Overlays** – Login/logout actions trigger full-screen blurred overlay with spinner and status text, blocking duplicate submissions.
7. **Images** – Compressed to WebP when possible, stored in `public/assets/images/`, always use `loading="lazy"` and Intersection Observer placeholders.
8. **Email Templates** – Reside under `resources/views/emails/`. Use inline CSS for compatibility, branded headers, responsive layout, clear CTA buttons.

---

## 5. Data Display Requirements
1. All tables must implement pagination: numbered pages like `1 2 3 … 67 68`, prev/next buttons, highlight active page, preserve filters.
2. Include a "Rows per page" selector (10/25/50/100) near each table with instant reload and sensible page reset.
3. Document pagination behavior and Blade components in `docs/ui-components.md`.

---

## 6. Backend & Coding Standards
1. Controllers remain thin; business logic goes into service classes under `app/Services`.
2. Use Form Requests for validation; API endpoints return standardized JSON errors.
3. Migrations mandatory for schema changes; seeders for bootstrap data. Optimize indexes, avoid N+1 queries via eager loading.
4. Sessions stored in database by default; enable Redis cache when available.
5. Security (OWASP Top 10):
   - Use prepared statements/Eloquent only.
   - Regenerate session IDs on auth events; rate limit login attempts; enforce HTTPS in production.
   - Guard against sensitive data exposure; never log secrets; set secure cookies.
   - Disable unsafe XML features; sanitize file uploads; keep dependencies patched with `composer audit`.
6. Secrets live in `.env`; `.env.example` carries safe placeholders. Never hardcode credentials or API keys.
7. Testing: add PHPUnit/Pest coverage for key flows (auth, dashboards, CRUD). Run `php artisan test` before merge.
8. Logging: use structured channels. Do not log PII or credentials.

---

## 7. JavaScript Guidelines
- Use Vite entrypoints under `resources/js/`. Modules live in `resources/js/modules/` and are imported as needed.
- Prefer Alpine.js for lightweight interactivity; heavier frameworks require architectural approval.
- Page-specific scripts may be placed in Blade sections when minimal; otherwise keep logic in dedicated modules.
- External libraries only via npm or trusted CDN and must be documented in `docs/tech-stack.md`.
- No jQuery unless integrating legacy code with prior approval.

---

## 8. Performance & Accessibility
1. Targets: Lighthouse ≥ 85 (mobile/desktop), perceived load < 2 seconds, JavaScript bundle minimized.
2. Caching: use config/route caching post-stabilization; enable HTTP compression on deployment.
3. Defer non-critical JS, prefetch key routes, use code splitting when necessary.
4. Accessibility: aim for WCAG 2.1 AA. Semantic HTML, labeled controls, >4.5:1 contrast, keyboard navigable modals, descriptive alt text.

---

## 9. Documentation & Workflow
1. Maintain comprehensive documentation in `docs/`:
   - `README.md` (setup, env vars, deployment, troubleshooting)
   - `architecture.md`, `database.md`, `api.md`, `ui-components.md`, `email-styleguide.md`
2. Code comments explain **why**, not **what**. Provide PHPDoc for classes and public methods.
3. Git workflow: branches `main` (stable), `develop` (integration), and feature branches (`feature/<ticket>`). Use Conventional Commits.
4. CI/CD pipeline (future):
   - `composer install --no-dev`
   - `npm ci`
   - `npm run build`
   - `php artisan test`
   - `composer audit`
   - `php artisan migrate --pretend`
5. Release tagging format: `vYYYY.MM.DD`, update `CHANGELOG.md` using Keep a Changelog style.

---

## 10. Environment Setup Checklist
1. Copy `.env.example` → `.env`; run `php artisan key:generate`.
2. Configure database credentials; run `php artisan migrate --seed` as needed.
3. Install dependencies: `composer install`, `npm install`.
4. Start services: `php artisan serve`, `npm run dev`.
5. Ensure storage symlink for uploads: `php artisan storage:link`.

---

Keep this document updated as conventions evolve. Record updates in `docs/changelog-rules.md` and circulate changes with the team.