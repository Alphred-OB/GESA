@php($title = $title ?? 'Academic timeline')

<x-layouts.admin :title="$title">
	@include('components.dashboard.skeleton-styles')

	<div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">
		<div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
			<header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 shadow-lg shadow-[#16136a]/5">
				<div class="space-y-2">
					<div class="skeleton inline-flex h-7 w-48 items-center rounded-full bg-[#16136a]/10"></div>
					<div class="skeleton h-8 w-80 rounded-2xl bg-slate-200"></div>
					<div class="skeleton h-4 w-64 rounded-2xl bg-slate-100"></div>
				</div>
				<div class="flex flex-wrap items-center justify-center gap-3 md:justify-end">
					<div class="skeleton h-10 w-32 rounded-2xl bg-[#16136a]/10"></div>
				</div>
			</header>

			<section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
				<div class="flex flex-col gap-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
					<div class="space-y-2">
						<div class="skeleton h-4 w-44 rounded-full bg-slate-200"></div>
						<div class="skeleton h-3 w-56 rounded-full bg-slate-100"></div>
					</div>
					<div class="skeleton h-10 w-40 rounded-2xl bg-slate-100"></div>
				</div>

				<div class="overflow-hidden rounded-2xl border border-slate-200/70">
					<div class="hidden md:block">
						<div class="skeleton h-10 w-full bg-slate-50/80"></div>
						@for ($i = 0; $i < 4; $i++)
							<div class="skeleton h-12 w-full bg-white"></div>
						@endfor
					</div>
					<div class="grid gap-4 p-4 md:hidden">
						@for ($i = 0; $i < 3; $i++)
							<div class="skeleton h-24 w-full rounded-2xl bg-white"></div>
						@endfor
					</div>
				</div>

				<div class="mt-4 rounded-2xl border border-slate-200/70 bg-slate-50/50 px-4 py-3">
					<div class="skeleton h-4 w-40 rounded-full bg-slate-200"></div>
				</div>
			</section>
		</div>

		<div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
			<header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 text-center shadow-lg shadow-[#16136a]/5 sm:text-left md:flex-row md:items-center md:justify-between">
				<div class="space-y-2">
					<p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
						<i class="ri-time-line text-base" aria-hidden="true"></i>
						Academic timeline
					</p>
					<h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Orchestrate key academic milestones</h1>
					<p class="text-sm text-slate-600">Publish semester checkpoints so students can stay ahead of registrations, exams, and breaks.</p>
				</div>
				<div class="flex flex-wrap items-center justify-center gap-3 md:justify-end">
					<a href="{{ route('admin.timeline.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl" aria-label="Create timeline entry">
						<i class="ri-add-line text-base" aria-hidden="true"></i>
						New entry
					</a>
				</div>
			</header>

			@if (session('status'))
				<div class="rounded-3xl border border-emerald-200/60 bg-emerald-50 px-5 py-4 text-sm text-emerald-800 shadow-inner">
					<div class="flex items-start gap-3">
						<span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
							<i class="ri-check-line text-lg" aria-hidden="true"></i>
						</span>
						<p>{{ session('status') }}</p>
					</div>
				</div>
			@endif

			<section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
				<div class="flex flex-col gap-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
					<div>
						<h2 class="text-lg font-semibold text-[#16136a]">Published milestones</h2>
						<p class="text-sm text-slate-500">Showing {{ $entries->firstItem() ?? 0 }}-{{ $entries->lastItem() ?? 0 }} of {{ $entries->total() }} entries.</p>
					</div>
					<form method="GET" class="flex flex-col items-center gap-2 sm:flex-row" x-data>
						@foreach (request()->except(['per_page', 'page']) as $key => $value)
							<input type="hidden" name="{{ $key }}" value="{{ $value }}">
						@endforeach
						<label for="per_page" class="text-sm font-medium text-slate-600">Rows per page</label>
						<select id="per_page" name="per_page" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-[#16136a] focus:ring-[#16136a] sm:w-auto" x-on:change="$el.form.submit()">
							@foreach ($perPageOptions as $option)
								<option value="{{ $option }}" @selected($option === $currentPerPage)>{{ $option }}</option>
							@endforeach
						</select>
					</form>
				</div>

				<div class="overflow-hidden rounded-2xl border border-slate-200/70">
					<div class="hidden md:block">
						<table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600">
							<thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
								<tr>
									<th scope="col" class="px-6 py-3">Milestone</th>
									<th scope="col" class="px-6 py-3">Window</th>
									<th scope="col" class="px-6 py-3">Academic year</th>
									<th scope="col" class="px-6 py-3">Status</th>
									<th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100">
								@forelse ($entries as $entry)
									<tr class="transition hover:bg-slate-50/70">
										<td class="px-6 py-4 align-top">
											<div class="space-y-1">
												<p class="flex items-center gap-2 text-sm font-semibold text-[#16136a]">
													<i class="ri-flag-2-line text-base" aria-hidden="true"></i>
													{{ $entry->title }}
												</p>
											</div>
										</td>
										<td class="px-6 py-4 align-top text-sm text-slate-500">
											<p><i class="ri-calendar-line mr-1 text-[#16136a]" aria-hidden="true"></i>{{ optional($entry->starts_at)->format('M j, Y') }}</p>
										</td>
										<td class="px-6 py-4 align-top text-sm text-slate-500">{{ $entry->academic_year ?? '—' }}</td>
										<td class="px-6 py-4 align-top">
											@if ($entry->is_published)
												<span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
													<span class="h-2 w-2 rounded-full bg-emerald-500"></span>
													Live
												</span>
											@else
												<span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
													<span class="h-2 w-2 rounded-full bg-slate-400"></span>
													Hidden
												</span>
											@endif
										</td>
										<td class="px-6 py-4 text-right text-sm">
											<div class="flex items-center justify-end gap-3">
												<a href="{{ route('admin.timeline.edit', $entry) }}" class="inline-flex items-center gap-2 rounded-xl border border-[#16136a]/20 px-3 py-1.5 text-xs font-semibold text-[#16136a] transition hover:border-[#16136a] hover:text-[#16136a]"><i class="ri-edit-line text-sm" aria-hidden="true"></i>Edit</a>
												<form method="POST" action="{{ route('admin.timeline.destroy', $entry) }}" onsubmit="return confirm('Delete this timeline entry?');">
													@csrf
													@method('DELETE')
													<button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:border-rose-400 hover:bg-rose-100">
														<i class="ri-delete-bin-6-line text-sm" aria-hidden="true"></i>
														Delete
													</button>
												</form>
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
											<div class="mx-auto flex w-full max-w-md flex-col items-center gap-4">
												<span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#16136a]/10 text-[#16136a]">
													<i class="ri-time-line text-2xl" aria-hidden="true"></i>
												</span>
												<p>No timeline events yet. Create your first milestone to guide students through the semester.</p>
												<a href="{{ route('admin.timeline.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl">
													<i class="ri-add-line text-base" aria-hidden="true"></i>
													Create milestone
												</a>
											</div>
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<div class="divide-y divide-slate-100 md:hidden">
						@foreach ($entries as $entry)
							<article class="space-y-4 p-5">
								<header class="space-y-1">
									<p class="flex items-center gap-2 text-sm font-semibold text-[#16136a]"><i class="ri-flag-2-line text-base" aria-hidden="true"></i>{{ $entry->title }}</p>
								</header>
								<dl class="space-y-1 text-xs text-slate-500">
									<div class="flex items-start gap-2">
										<dt class="font-semibold text-[#16136a]">Date</dt>
										<dd>{{ optional($entry->starts_at)->format('M j, Y') }}</dd>
									</div>
									<div class="flex items-start gap-2">
										<dt class="font-semibold text-[#16136a]">Year</dt>
										<dd>{{ $entry->academic_year ?? '—' }}</dd>
									</div>
									<div class="flex items-start gap-2">
										<dt class="font-semibold text-[#16136a]">Status</dt>
										<dd>
											@if ($entry->is_published)
												<span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-600">
													<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
													Live
												</span>
											@else
												<span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-500">
													<span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
													Hidden
												</span>
											@endif
										</dd>
									</div>
								</dl>
								<div class="flex flex-wrap gap-3">
									<a href="{{ route('admin.timeline.edit', $entry) }}" class="inline-flex items-center gap-2 rounded-xl border border-[#16136a]/20 px-3 py-1.5 text-xs font-semibold text-[#16136a] transition hover:border-[#16136a] hover:text-[#16136a]"><i class="ri-edit-line text-sm" aria-hidden="true"></i>Edit</a>
									<form method="POST" action="{{ route('admin.timeline.destroy', $entry) }}" class="inline" onsubmit="return confirm('Delete this timeline entry?');">
										@csrf
										@method('DELETE')
										<button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:border-rose-400 hover:bg-rose-100">
											<i class="ri-delete-bin-6-line text-sm" aria-hidden="true"></i>
											Delete
										</button>
									</form>
								</div>
							</article>
						@endforeach
					</div>
				</div>

				<div class="rounded-2xl border border-slate-200/70 bg-slate-50/50 px-4 py-3">
					{{ $entries->links('vendor.pagination.data-limit') }}
				</div>
			</section>
		</div>
	</div>
</x-layouts.admin>
