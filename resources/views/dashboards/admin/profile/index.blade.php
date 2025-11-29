@php($title = 'Admin profile')

<x-layouts.admin :title="$title">
    <div class="mx-auto w-full max-w-6xl space-y-10 px-5 py-10 sm:px-6 lg:px-8">
        <header class="space-y-4 rounded-[28px] border border-[#16136a]/15 bg-gradient-to-br from-[#16136a] via-[#1f2a8a] to-[#16136a] p-8 text-white shadow-[0_24px_60px_-30px_rgba(22,19,106,0.45)]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-slate-100/90">
                        <i class="ri-user-settings-line text-base" aria-hidden="true"></i>
                        Admin profile
                    </p>
                    <h1 class="text-3xl font-semibold md:text-4xl">Manage your administrator workspace</h1>
                    <p class="max-w-2xl text-sm text-slate-100/85">Update personal details, invite additional administrators, and capture point-in-time system insights for audits.</p>
                </div>
                <div class="rounded-3xl border border-white/20 bg-white/10 px-6 py-4 text-sm text-slate-100/85 shadow-inner">
                    <p class="font-semibold uppercase tracking-[0.25em] text-slate-100/90">Signed in as</p>
                    <p class="mt-2 text-base font-semibold">{{ $admin->fullname ?? $admin->username }}</p>
                    <p class="text-xs text-slate-100/80">{{ $admin->email }}</p>
                </div>
            </div>
        </header>

        @if (session('status'))
            <div class="flex items-start gap-3 rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 shadow-sm">
                <span class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-[#16136a]">
                    <i class="ri-check-double-line text-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm">
                <p class="flex items-center gap-2 font-semibold uppercase tracking-[0.2em]">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white text-rose-500">
                        <i class="ri-error-warning-line text-base" aria-hidden="true"></i>
                    </span>
                    Please review the highlighted fields:
                </p>
                <ul class="mt-2 space-y-1 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-[28px] border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <header class="space-y-2">
                    <h2 class="flex items-center gap-3 text-xl font-semibold text-[#16136a]">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                            <i class="ri-user-settings-line text-lg" aria-hidden="true"></i>
                        </span>
                        <span>Profile settings</span>
                    </h2>
                    <p class="text-sm text-slate-500">Refresh your contact details and update your password to keep your account secure.</p>
                </header>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="fullname" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Full name</label>
                            <input id="fullname" name="fullname" type="text" value="{{ old('fullname', $admin->fullname) }}" autocomplete="name" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="username" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Username</label>
                            <input id="username" name="username" type="text" value="{{ old('username', $admin->username) }}" required autocomplete="username" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="email" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $admin->email) }}" required autocomplete="email" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="phone_number" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Phone number</label>
                            <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number', $admin->phone_number) }}" autocomplete="tel" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="password" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">New password</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                            <p class="text-xs text-slate-400">Leave blank to keep your current password.</p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="password_confirmation" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2.5 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/25 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                            <i class="ri-save-3-line text-base"></i>
                            Save changes
                        </button>
                    </div>
                </form>
            </article>

            <article class="rounded-[28px] border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
                <header class="space-y-2">
                    <h2 class="flex items-center gap-3 text-xl font-semibold text-[#16136a]">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                            <i class="ri-user-add-line text-lg" aria-hidden="true"></i>
                        </span>
                        <span>Invite administrator</span>
                    </h2>
                    <p class="text-sm text-slate-500">Provision a new admin account with immediate console access.</p>
                </header>

                <form method="POST" action="{{ route('admin.profile.admins.store') }}" class="mt-6 space-y-5">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="invite_fullname" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Full name</label>
                            <input id="invite_fullname" name="fullname" type="text" value="{{ old('fullname') }}" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="invite_username" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Username</label>
                            <input id="invite_username" name="username" type="text" value="{{ old('username') }}" required class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="invite_email" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Email address</label>
                            <input id="invite_email" name="email" type="email" value="{{ old('email') }}" required class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="invite_phone" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Phone number</label>
                            <input id="invite_phone" name="phone_number" type="text" value="{{ old('phone_number') }}" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="invite_admin_role" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Position</label>
                            <select id="invite_admin_role" name="admin_role" required class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                                <option value="" disabled {{ old('admin_role') ? '' : 'selected' }}>Select position</option>
                                <option value="president" @selected(old('admin_role') === 'president')>President</option>
                                <option value="financial_secretary" @selected(old('admin_role') === 'financial_secretary')>Financial Secretary</option>
                                <option value="general_secretary" @selected(old('admin_role') === 'general_secretary')>General Secretary</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="invite_password" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Temporary password</label>
                            <input id="invite_password" name="password" type="password" required class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="invite_password_confirmation" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Confirm password</label>
                            <input id="invite_password_confirmation" name="password_confirmation" type="password" required class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2.5 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/25 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                            <i class="ri-user-add-line text-base"></i>
                            Add administrator
                        </button>
                    </div>
                </form>

                @if ($others->isNotEmpty())
                    <section class="mt-8 space-y-3">
                        <header class="flex items-center justify-between">
                            <h3 class="flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#16136a]/10 text-[#16136a]">
                                    <i class="ri-team-line text-sm" aria-hidden="true"></i>
                                </span>
                                Existing admins
                            </h3>
                            <span class="rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold text-[#16136a]">{{ $others->count() }}</span>
                        </header>
                        <ul class="space-y-3 text-sm text-slate-600">
                            @foreach ($others as $other)
                                <li class="rounded-2xl border border-slate-200/70 bg-slate-50/70 px-4 py-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $other->fullname ?? $other->username }}</p>
                                            <p class="text-xs text-slate-500">{{ $other->email }}</p>
                                        </div>
                                        <p class="text-xs text-slate-400">Joined {{ optional($other->created_at)->diffForHumans() }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </article>
        </section>

        <section class="rounded-[28px] border border-[#16136a]/15 bg-white p-6 shadow-lg shadow-[#16136a]/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="flex items-center gap-3 text-xl font-semibold text-[#16136a]">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#16136a]/10 text-[#16136a]">
                            <i class="ri-database-2-line text-lg" aria-hidden="true"></i>
                        </span>
                        <span>System snapshots</span>
                    </h2>
                    <p class="text-sm text-slate-500">Capture configuration or database fingerprints to support backups and audits.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profile.snapshots.store') }}" class="mt-6 grid gap-4 md:grid-cols-[1fr_200px_160px]">
                @csrf

                <div class="flex flex-col gap-2">
                    <label for="snapshot_notes" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Snapshot notes</label>
                    <input id="snapshot_notes" name="notes" type="text" value="{{ old('notes') }}" placeholder="Optional context (e.g. before deployment)" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="snapshot_type" class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Snapshot type</label>
                    <select id="snapshot_type" name="type" class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm transition focus:border-[#16136a] focus:outline-none focus:ring-2 focus:ring-[#16136a]/30">
                        <option value="system" @selected(old('type') === 'system')>System overview</option>
                        <option value="database" @selected(old('type') === 'database')>Database structure</option>
                    </select>
                </div>

                <div class="flex items-end justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2.5 text-sm font-semibold uppercase tracking-[0.2em] text-white shadow-lg shadow-[#16136a]/25 transition hover:-translate-y-0.5 hover:bg-[#16136a]/90">
                        <i class="ri-database-2-line text-base"></i>
                        Generate
                    </button>
                </div>
            </form>

            <div class="mt-8 rounded-2xl border border-slate-200/70 bg-slate-50/70 p-5">
                <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Recent snapshots</p>
                        <p class="text-xs text-slate-500">Stored locally under <code class="font-mono text-slate-600">storage/app/snapshots</code>.</p>
                    </div>
                </header>

                @if (empty($snapshots))
                    <p class="mt-4 rounded-2xl border border-dashed border-slate-300 bg-white/70 px-4 py-6 text-center text-sm text-slate-500">No snapshots captured yet. Use the form above to generate your first snapshot.</p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-600">
                            <thead class="bg-white/70 text-xs uppercase tracking-[0.2em] text-slate-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="ri-file-text-line text-base" aria-hidden="true"></i>
                                            File
                                        </span>
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="ri-price-tag-3-line text-base" aria-hidden="true"></i>
                                            Type
                                        </span>
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="ri-time-line text-base" aria-hidden="true"></i>
                                            Generated
                                        </span>
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="ri-sticky-note-line text-base" aria-hidden="true"></i>
                                            Notes
                                        </span>
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="ri-hard-drive-3-line text-base" aria-hidden="true"></i>
                                            Size
                                        </span>
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-right">
                                        <span class="inline-flex items-center gap-2 justify-end">
                                            <i class="ri-settings-4-line text-base" aria-hidden="true"></i>
                                            Actions
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200/70 bg-white/70">
                                @foreach ($snapshots as $snapshot)
                                    <tr class="hover:bg-emerald-50/40">
                                        @php($type = $snapshot['content']['type'] ?? 'system')
                                        @php($isDatabase = $type === 'database')
                                        @php($tables = $snapshot['content']['snapshot']['tables'] ?? [])
                                        @php($tableCount = is_array($tables) ? count($tables) : 0)
                                        @php($extension = strtoupper(pathinfo($snapshot['filename'], PATHINFO_EXTENSION)))
                                        @php($bytes = $snapshot['size'] ?? 0)
                                        @php($sizeLabel = $bytes >= 1048576 ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 2) . ' KB')
                                        <td class="px-4 py-3 font-medium text-slate-800">{{ $snapshot['filename'] }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center gap-1 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a]">
                                                <i class="{{ $isDatabase ? 'ri-database-line' : 'ri-shield-check-line' }} text-base"></i>
                                                {{ $isDatabase ? 'Database (SQL)' : 'System (JSON)' }}
                                            </span>
                                            <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">{{ $extension }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-500">{{ optional($snapshot['last_modified'])->diffForHumans() }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-500">
                                            <p>{{ $snapshot['content']['notes'] ?? ($isDatabase ? 'Full database export' : 'System overview') }}</p>
                                            @if ($isDatabase)
                                                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">{{ $tableCount }} tables</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-500">{{ $sizeLabel }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('admin.profile.snapshots.download', base64_encode($snapshot['path'])) }}" class="inline-flex items-center gap-2 rounded-full border border-[#16136a]/20 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#16136a] transition hover:-translate-y-0.5 hover:bg-white/90" download>
                                                <i class="ri-download-2-line text-base"></i>
                                                Download {{ $extension }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-layouts.admin>
