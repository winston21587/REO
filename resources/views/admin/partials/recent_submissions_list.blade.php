<div id="recent-submissions-container" class="flex flex-col flex-1 h-full justify-between min-h-[460px]">
    <div class="space-y-2.5 flex flex-col flex-1">
        @forelse($pendingSubmissions as $sub)
            <div class="bg-white hover:border-slate-400 border border-slate-300/90 rounded-xl p-3 sm:p-3.5 transition-all group shadow-xs hover:shadow-md">
                <!-- Top Row: Protocol Title + Status Pill -->
                <div class="flex items-start justify-between gap-2.5">
                    <h3 class="font-bold text-slate-900 text-sm sm:text-[15px] leading-snug group-hover:text-[#8B0000] transition-colors line-clamp-1 break-words flex-1 min-w-0 tracking-tight"
                        title="{{ $sub->Study_Protocol_title }}">{{ $sub->Study_Protocol_title }}</h3>

                    @if($sub->Status === 'Revision Submitted')
                        <span
                            class="inline-flex items-center text-xs font-bold text-purple-950 bg-purple-100 border border-purple-300/90 px-2.5 py-0.5 rounded-md shrink-0 shadow-2xs">
                            Resubmitted
                        </span>
                    @else
                        <span
                            class="inline-flex items-center text-xs font-bold text-amber-950 bg-amber-100 border border-amber-300/90 px-2.5 py-0.5 rounded-md shrink-0 shadow-2xs">
                            Pending
                        </span>
                    @endif
                </div>

                @if($sub->Status === 'Revision Submitted' && $sub->revisionLogs->first())
                    <div class="mt-2 px-2.5 py-1.5 bg-blue-50/90 rounded-lg border border-blue-200 text-xs text-blue-950 flex items-center gap-1.5 truncate shadow-2xs">
                        <i class="fas fa-comment-dots text-blue-600 text-[11px] shrink-0" aria-hidden="true"></i>
                        <span class="font-bold text-blue-950 shrink-0">Note:</span>
                        <span class="italic truncate text-blue-900 font-medium">"{{ $sub->revisionLogs->first()->message }}"</span>
                    </div>
                @endif

                <!-- Bottom Row: Metadata (Left) + Actions (Right) -->
                <div class="flex items-center justify-between gap-2 mt-2 pt-2 border-t border-slate-200/80">
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-600 min-w-0">
                        <span class="inline-flex items-center gap-1 shrink-0 text-slate-700 font-semibold">
                            <i class="far fa-calendar-alt text-slate-500 text-[11px]" aria-hidden="true"></i>
                            <time datetime="{{ $sub->created_at->toIso8601String() }}">{{ $sub->created_at->format('M d, Y') }}</time>
                        </span>
                        @if($sub->researcher && $sub->researcher->user)
                            <span class="text-slate-400 shrink-0" aria-hidden="true">•</span>
                            <span class="inline-flex items-center gap-1 truncate text-slate-800 font-bold">
                                <i class="far fa-user text-slate-500 text-[11px]" aria-hidden="true"></i>
                                <span class="truncate">{{ $sub->researcher->user->first_name }} {{ $sub->researcher->user->last_name }}</span>
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('admin.view_files', $sub->id) }}"
                            aria-label="View details for {{ $sub->Study_Protocol_title }}"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-800 hover:text-slate-950 bg-white hover:bg-slate-100 border border-slate-300 shadow-2xs transition-colors inline-flex items-center gap-1 focus:outline-none focus-visible:ring-1 focus-visible:ring-slate-400">
                            <i class="far fa-folder-open text-slate-500 text-[11px]" aria-hidden="true"></i> View
                        </a>

                        <button type="button"
                            class="triage-trigger-btn px-3 py-1 rounded-lg text-xs font-bold text-white bg-[#8B0000] hover:bg-[#6e0000] active:scale-[0.98] transition-colors inline-flex items-center gap-1 shadow-2xs focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1"
                            data-id="{{ $sub->id }}"
                            data-title="{{ $sub->Study_Protocol_title }}"
                            data-or-number=""
                            data-or-path="{{ $sub->or_file_path ? asset($sub->or_file_path) : '' }}"
                            data-or-verified="{{ $sub->is_or_verified ? 'true' : 'false' }}"
                            data-cv-status="{{ $sub->cv_verification_status ?? '' }}"
                            data-has-project-type="{{ ($sub->project_type || $sub->research_type || $sub->Research_Category) ? 'true' : 'false' }}"
                            data-researcher-name="{{ trim(($sub->researcher->user->first_name ?? '') . ' ' . ($sub->researcher->user->last_name ?? '')) }}"
                            data-researcher-email="{{ $sub->researcher->user->email ?? '' }}"
                            aria-label="Screen protocol completeness for: {{ $sub->Study_Protocol_title }}">
                            <i class="fas fa-clipboard-check text-white text-[11px]" aria-hidden="true"></i> Triage
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-dashed border-slate-300 p-6 text-center flex flex-col items-center justify-center min-h-[220px] flex-1">
                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center mb-2">
                    <i class="fas fa-inbox text-sm" aria-hidden="true"></i>
                </div>
                @if(request('recent_search'))
                    <h3 class="font-bold text-slate-800 text-sm mb-0.5">No matching submissions</h3>
                    <p class="text-slate-500 text-xs font-medium max-w-xs leading-normal">No submissions match "{{ request('recent_search') }}".</p>
                @else
                    <h3 class="font-bold text-slate-800 text-sm mb-0.5">Queue is Clear</h3>
                    <p class="text-slate-500 text-xs font-medium max-w-xs leading-normal">No submissions are currently awaiting intake screening.</p>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-auto pt-3 shrink-0 flex flex-row justify-between items-center gap-2 text-xs font-medium text-slate-600">
        <div>
            Showing <span class="tabular-nums font-bold text-slate-900">{{ $pendingSubmissions->firstItem() ?? 0 }}–{{ $pendingSubmissions->lastItem() ?? 0 }}</span> of <span class="font-extrabold text-slate-950 tabular-nums">{{ $pendingSubmissions->total() }}</span>
        </div>
        <nav class="flex items-center gap-1" aria-label="Recent submissions pagination">
            <!-- Previous Page Link -->
            @if ($pendingSubmissions->onFirstPage())
                <span class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-300 cursor-not-allowed opacity-40 text-xs" aria-disabled="true" aria-label="Previous page">
                    <i class="fas fa-chevron-left text-[9px]" aria-hidden="true"></i>
                </span>
            @else
                <a href="{{ $pendingSubmissions->appends(['incomplete_page' => $incompleteSubmissions ? $incompleteSubmissions->currentPage() : request('incomplete_page', 1), 'recent_search' => request('recent_search'), 'recent_sort' => request('recent_sort')])->previousPageUrl() }}"
                    class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-300 bg-white text-slate-800 hover:text-[#8B0000] hover:border-[#8B0000]/50 hover:bg-slate-50 active:scale-95 transition-all pagination-link shadow-2xs text-xs font-bold"
                    data-target="recent"
                    aria-label="Go to previous page of recent submissions">
                    <i class="fas fa-chevron-left text-[9px]" aria-hidden="true"></i>
                </a>
            @endif

            <!-- Next Page Link -->
            @if ($pendingSubmissions->hasMorePages())
                <a href="{{ $pendingSubmissions->appends(['incomplete_page' => $incompleteSubmissions ? $incompleteSubmissions->currentPage() : request('incomplete_page', 1), 'recent_search' => request('recent_search'), 'recent_sort' => request('recent_sort')])->nextPageUrl() }}"
                    class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-300 bg-white text-slate-800 hover:text-[#8B0000] hover:border-[#8B0000]/50 hover:bg-slate-50 active:scale-95 transition-all pagination-link shadow-2xs text-xs font-bold"
                    data-target="recent"
                    aria-label="Go to next page of recent submissions">
                    <i class="fas fa-chevron-right text-[9px]" aria-hidden="true"></i>
                </a>
            @else
                <span class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-300 cursor-not-allowed opacity-40 text-xs" aria-disabled="true" aria-label="Next page">
                    <i class="fas fa-chevron-right text-[9px]" aria-hidden="true"></i>
                </span>
            @endif
        </nav>
    </div>
</div>