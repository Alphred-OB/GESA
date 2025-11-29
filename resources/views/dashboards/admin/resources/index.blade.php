@php($title = $title ?? 'Manage academic resources')

<x-layouts.admin :title="$title">
	@include('components.dashboard.skeleton-styles')

	<div x-data="{ loading: true }" x-init="setTimeout(() => { loading = false }, 600)" class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">
		<div x-show="loading" x-transition.opacity.duration.200ms class="space-y-8" role="status" aria-live="polite">
			<header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 text-center shadow-lg shadow-[#16136a]/5 sm:text-left md:flex-row md:items-center md:justify-between">
				<div class="space-y-2">
					<div class="skeleton inline-flex h-7 w-40 items-center rounded-full bg-[#16136a]/10"></div>
					<div class="skeleton h-8 w-64 rounded-2xl bg-slate-200"></div>
					<div class="skeleton h-4 w-80 rounded-2xl bg-slate-100"></div>
				</div>
				<div class="flex flex-wrap items-center justify-center gap-3 md:justify-end">
					<div class="skeleton h-10 w-40 rounded-2xl bg-[#16136a]/15"></div>
				</div>
			</header>

			<section class="space-y-6 rounded-3xl border border-[#16136a]/10 bg-white p-6 shadow-lg shadow-[#16136a]/10">
				<div class="flex flex-col gap-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
					<div class="space-y-2">
						<div class="skeleton h-4 w-40 rounded-full bg-slate-200"></div>
						<div class="skeleton h-3 w-64 rounded-full bg-slate-100"></div>
					</div>
					<div class="flex flex-col items-center gap-2 sm:flex-row">
						<div class="skeleton h-3 w-32 rounded-full bg-slate-200"></div>
						<div class="skeleton h-9 w-28 rounded-xl bg-slate-100"></div>
					</div>
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
							<div class="skeleton h-28 w-full rounded-2xl bg-white"></div>
						@endfor
					</div>
				</div>

				<div class="flex flex-col gap-3 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
					<div class="skeleton h-3 w-40 rounded-full bg-slate-200"></div>
					<div class="skeleton h-8 w-32 rounded-2xl bg-slate-100 sm:ml-auto"></div>
				</div>
			</section>
		</div>

		<div x-show="!loading" x-transition.opacity.duration.200ms x-cloak class="space-y-10">
			<header class="flex flex-col gap-4 rounded-3xl border border-[#16136a]/15 bg-white/80 p-6 text-center shadow-lg shadow-[#16136a]/5 sm:text-left md:flex-row md:items-center md:justify-between">
				<div class="space-y-2">
					<p class="inline-flex items-center gap-2 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-[#16136a]">
						<i class="ri-book-3-line text-base" aria-hidden="true"></i>
						Academic resources
					</p>
					<h1 class="text-2xl font-semibold text-[#16136a] md:text-3xl">Curate resources for every cohort</h1>
					<p class="text-sm text-slate-600">Upload lecture materials, past questions, and helpful links with clear targeting by class and year.</p>
				</div>
				<div class="flex flex-wrap items-center justify-center gap-3 md:justify-end">
					<a href="{{ route('admin.resources.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-[#16136a] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#16136a]/20 transition hover:-translate-y-0.5 hover:shadow-xl" aria-label="Create new academic resource">
						<i class="ri-add-line text-base" aria-hidden="true"></i>
						New resource
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
						<h2 class="text-lg font-semibold text-[#16136a]">Resource library</h2>
						<p class="text-sm text-slate-500">Showing {{ $resources->firstItem() ?? 0 }}-{{ $resources->lastItem() ?? 0 }} of {{ $resources->total() }} resources.</p>
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
									<th scope="col" class="px-6 py-3">Resource</th>
									<th scope="col" class="px-6 py-3">Type</th>
									<th scope="col" class="px-6 py-3">Audience</th>
									<th scope="col" class="px-6 py-3">Visibility</th>
									<th scope="col" class="px-6 py-3 text-right">Actions</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-200 bg-white">
								@if ($resources->count())
									@foreach ($resources as $resource)
									<tr>
										<td class="px-6 py-4">
											<div class="space-y-1">
												<p class="font-semibold text-slate-900">{{ $resource->title }}</p>
												<p class="text-xs text-slate-500">{{ Str::limit($resource->description, 90) }}</p>
												<div class="flex flex-wrap gap-2 pt-2">
													@if ($resource->category)
														<span class="inline-flex items-center gap-1 rounded-full bg-[#16136a]/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-[#16136a]/80">
															<i class="fa-solid {{ $resource->icon ?? 'fa-book-open-reader' }} text-[#16136a]"></i>
															{{ Str::headline($resource->category) }}
														</span>
													@endif
													<span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-600">
														{{ Str::headline($resource->content_type) }}
													</span>
												</div>
											</div>
										</td>
										<td class="px-6 py-4">
											<div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
												<i class="ri-{{ $resource->resource_type === 'file' ? 'file-line' : 'external-link-line' }} text-sm"></i>
												{{ ucfirst($resource->resource_type) }}
											</div>
										</td>
										<td class="px-6 py-4">
											<div class="flex flex-wrap items-center gap-2">
												@php
													$classes = $resource->target_classes ?? [];
													$years = $resource->target_years ?? [];
												@endphp
												@if (empty($classes) && empty($years))
													<span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
														<i class="ri-user-shared-line text-sm"></i>
														All students
													</span>
												@endif
												@foreach ($classes as $class)
													<span class="inline-flex items-center gap-1 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold text-[#16136a]">
														<i class="ri-stack-line text-sm"></i>
														{{ $class }}
													</span>
												@endforeach
												@foreach ($years as $year)
													<span class="inline-flex items-center gap-1 rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
														<i class="ri-graduation-cap-line text-sm"></i>
														Year {{ $year }}
													</span>
												@endforeach
											</div>
										</td>
										<td class="px-6 py-4">
											<span class="inline-flex items-center gap-2 rounded-full {{ $resource->visibility === 'student' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-semibold">
												<i class="ri-eye-{{ $resource->visibility === 'student' ? '2-line' : 'close-line' }} text-sm"></i>
												{{ $resource->visibility === 'student' ? 'Visible' : 'Hidden' }}
											</span>
										</td>
										<td class="px-6 py-4">
											<div class="flex items-center justify-end gap-2">
												<a href="{{ route('admin.resources.edit', $resource) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
													<i class="ri-edit-line text-sm"></i>
													Edit
												</a>
												<form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" class="inline-flex" onsubmit="return confirm('Delete this resource?');">
													@csrf
													@method('DELETE')
													<button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
														<i class="ri-delete-bin-line text-sm"></i>
														Delete
													</button>
												</form>
											</div>
										</td>
									</tr>
									@endforeach
								@else
									<tr>
										<td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
											<div class="flex flex-col items-center gap-3">
												<i class="ri-book-mark-line text-3xl text-slate-300"></i>
												<p class="font-semibold text-slate-600">No resources yet.</p>
												<p>Upload your first resource to make materials available to students.</p>
											</div>
										</td>
									</tr>
								@endif
							</tbody>
						</table>
					</div>

					<div class="grid gap-4 md:hidden">
						@if ($resources->count())
							@foreach ($resources as $resource)
							<article class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
								<header class="space-y-2">
									<h3 class="text-lg font-semibold text-slate-900">{{ $resource->title }}</h3>
									<p class="text-sm text-slate-600">{{ Str::limit($resource->description, 120) }}</p>
									<div class="flex flex-wrap gap-2">
										<span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
											<i class="ri-{{ $resource->resource_type === 'file' ? 'file-line' : 'external-link-line' }} text-sm"></i>
											{{ ucfirst($resource->resource_type) }}
										</span>
										<span class="inline-flex items-center gap-1 rounded-full bg-[#16136a]/10 px-3 py-1 text-xs font-semibold text-[#16136a]">
											{{ Str::headline($resource->content_type) }}
										</span>
									</div>
								</header>
								<div class="flex flex-wrap gap-2 text-xs text-slate-600">
									@php
										$classes = $resource->target_classes ?? [];
										$years = $resource->target_years ?? [];
									@endphp
									@if (empty($classes) && empty($years))
										<span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">
											<i class="ri-user-shared-line text-sm"></i>
											All students
										</span>
									@endif
									@foreach ($classes as $class)
										<span class="inline-flex items-center gap-1 rounded-full bg-[#16136a]/10 px-3 py-1 font-semibold text-[#16136a]">
											<i class="ri-stack-line text-sm"></i>
											{{ $class }}
										</span>
									@endforeach
									@foreach ($years as $year)
										<span class="inline-flex items-center gap-1 rounded-full bg-slate-200 px-3 py-1 font-semibold text-slate-700">
											<i class="ri-graduation-cap-line text-sm"></i>
											Year {{ $year }}
										</span>
									@endforeach
								</div>
								<footer class="flex items-center justify-between border-t border-slate-200 pt-4">
									<span class="inline-flex items-center gap-2 rounded-full {{ $resource->visibility === 'student' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }} px-3 py-1 text-xs font-semibold">
										<i class="ri-eye-{{ $resource->visibility === 'student' ? '2-line' : 'close-line' }} text-sm"></i>
										{{ $resource->visibility === 'student' ? 'Visible' : 'Hidden' }}
									</span>
									<div class="flex items-center gap-2">
										<a href="{{ route('admin.resources.edit', $resource) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-[#16136a]/40 hover:text-[#16136a]">
											<i class="ri-edit-line text-sm"></i>
											Edit
										</a>
										<form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" class="inline-flex" onsubmit="return confirm('Delete this resource?');">
											@csrf
											@method('DELETE')
											<button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
												<i class="ri-delete-bin-line text-sm"></i>
												Delete
											</button>
										</form>
									</div>
								</footer>
							</article>
							@endforeach
						@else
							<div class="rounded-2xl border border-dashed border-slate-300 bg-white/70 p-10 text-center text-sm text-slate-500">
								<i class="ri-book-mark-line text-3xl text-slate-300"></i>
								<p class="mt-3 font-semibold text-slate-600">No resources yet.</p>
								<p>Upload your first resource to make materials available to students.</p>
							</div>
						@endif
					</div>
				</div>

				<div class="flex flex-col gap-4 border-t border-slate-200/70 pt-4 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
					<p class="text-xs text-slate-500">Page {{ $resources->currentPage() }} of {{ $resources->lastPage() }}</p>
					<div class="sm:ml-auto">
						{{ $resources->appends(['per_page' => $currentPerPage])->onEachSide(1)->links() }}
					</div>
				</div>
			</section>
		</div>
	</div>
</x-layouts.admin>
