<div id="incomplete-submissions-container" class="flex flex-col flex-1 h-full justify-between min-h-[380px]">
    <div class="space-y-2.5 flex flex-col flex-1">
        @forelse($incompleteSubmissions as $sub)
            @php
                $hasNewFilesUploaded = $sub->Status === 'Incomplete Resubmitted';
            @endphp
            <div class="bg-white hover:border-slate-400 border border-slate-300/90 rounded-xl p-3 sm:p-3.5 transition-all group shadow-xs hover:shadow-md">
                <!-- Top Row: Protocol Title + Status Pill -->
                <div class="flex items-start justify-between gap-2.5">
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base leading-snug group-hover:text-[#8B0000] transition-colors line-clamp-1 break-words flex-1 min-w-0 tracking-tight"
                        title="{{ $sub->Study_Protocol_title }}">{{ $sub->Study_Protocol_title }}</h3>

                    @if($hasNewFilesUploaded)
                        <span class="inline-flex items-center text-xs font-semibold text-amber-800 whitespace-nowrap shrink-0"
                            title="Researcher has uploaded revised files for review.">
                            Files Updated
                        </span>
                    @endif
                </div>

                <!-- Bottom Row: Metadata (Left) + Actions (Right) -->
                <div class="flex items-center justify-between gap-2 mt-2 pt-2 border-t border-slate-200/80">
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-600 min-w-0">
                        <span class="inline-flex items-center gap-1 shrink-0 text-slate-700 font-semibold">
                            <i class="far fa-calendar-alt text-slate-500 text-xs" aria-hidden="true"></i>
                            <time datetime="{{ $sub->created_at->toIso8601String() }}" class="tabular-nums">{{ $sub->created_at->format('M d, Y') }}</time>
                        </span>
                        @if($sub->researcher && $sub->researcher->user)
                            <span class="text-slate-400 shrink-0" aria-hidden="true">•</span>
                            <span class="inline-flex items-center gap-1 truncate text-slate-800 font-bold">
                                <i class="far fa-user text-slate-500 text-xs" aria-hidden="true"></i>
                                <span class="truncate">{{ $sub->researcher->user->first_name }} {{ $sub->researcher->user->last_name }}</span>
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button"
                            class="undo-incomplete-btn inline-flex items-center justify-center gap-1.5 px-3 py-1.5 min-h-[38px] rounded-xl text-xs font-bold text-amber-950 hover:text-black bg-amber-50 hover:bg-amber-100 border border-amber-300 shadow-2xs transition-all cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 active:scale-[0.98]"
                            data-id="{{ $sub->id }}"
                            data-title="{{ $sub->Study_Protocol_title }}"
                            title="Revert to Recent Submissions"
                            aria-label="Revert incomplete status for {{ $sub->Study_Protocol_title }}">
                            <i class="fas fa-undo text-amber-800 text-xs" aria-hidden="true"></i> Revert
                        </button>

                        <a href="{{ route('admin.view_files', $sub->id) }}"
                            aria-label="View details for {{ $sub->Study_Protocol_title }}"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 min-h-[38px] rounded-xl text-xs font-bold text-slate-800 hover:text-slate-950 bg-white hover:bg-slate-50 border border-slate-300 shadow-2xs transition-all cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 active:scale-[0.98]">
                            <i class="far fa-folder-open text-slate-500 text-xs" aria-hidden="true"></i> View
                        </a>

                        <button type="button"
                            class="triage-trigger-btn inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 min-h-[38px] rounded-xl text-xs font-bold text-white bg-[#8B0000] hover:bg-[#6e0000] active:scale-[0.98] transition-all shadow-2xs cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-2"
                            data-id="{{ $sub->id }}"
                            data-title="{{ $sub->Study_Protocol_title }}"
                            data-or-number=""
                            data-or-path="{{ $sub->or_file_path ? asset($sub->or_file_path) : '' }}"
                            data-or-verified="{{ $sub->is_or_verified ? 'true' : 'false' }}"
                            data-cv-status="{{ $sub->cv_verification_status ?? '' }}"
                            data-has-project-type="{{ ($sub->project_type || $sub->research_type || $sub->Research_Category) ? 'true' : 'false' }}"
                            data-researcher-name="{{ trim(($sub->researcher->user->first_name ?? '') . ' ' . ($sub->researcher->user->last_name ?? '')) }}"
                            data-researcher-email="{{ $sub->researcher->user->email ?? '' }}"
                            aria-label="Re-screen completeness for: {{ $sub->Study_Protocol_title }}">
                            <i class="fas fa-clipboard-check text-white text-xs" aria-hidden="true"></i> Re-Screen
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-dashed border-slate-300 p-6 text-center flex flex-col items-center justify-center min-h-[220px] flex-1">
                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center mb-2">
                    <i class="far fa-check-circle text-base" aria-hidden="true"></i>
                </div>
                @if(request('incomplete_search'))
                    <h3 class="font-bold text-slate-800 text-sm mb-0.5">No matching incomplete submissions</h3>
                    <p class="text-slate-500 text-xs font-medium max-w-xs leading-normal">No incomplete submissions match "{{ request('incomplete_search') }}".</p>
                @else
                    <h3 class="font-bold text-slate-800 text-sm mb-0.5">No Incomplete Submissions</h3>
                    <p class="text-slate-500 text-xs font-medium max-w-xs leading-normal">All submissions currently meet documentation requirements.</p>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-auto pt-4 shrink-0 flex flex-row justify-between items-center gap-2 text-xs font-medium text-slate-600 border-t border-slate-100">
        <div>
            Showing <span class="tabular-nums font-bold text-slate-900">{{ $incompleteSubmissions->firstItem() ?? 0 }} to {{ $incompleteSubmissions->lastItem() ?? 0 }}</span> of <span class="font-extrabold text-slate-950 tabular-nums">{{ $incompleteSubmissions->total() }}</span>
        </div>
        <nav class="flex items-center gap-1.5" aria-label="Incomplete submissions pagination">
            <!-- Previous Page Link -->
            @if ($incompleteSubmissions->onFirstPage())
                <span class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl border border-slate-200 text-slate-300 cursor-not-allowed opacity-40 text-xs" aria-disabled="true" aria-label="Previous page">
                    <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                </span>
            @else
                <a href="{{ $incompleteSubmissions->appends(['pending_page' => $pendingSubmissions ? $pendingSubmissions->currentPage() : request('pending_page', 1), 'incomplete_search' => request('incomplete_search'), 'incomplete_sort' => request('incomplete_sort')])->previousPageUrl() }}"
                    class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:text-[#8B0000] hover:border-[#8B0000] hover:bg-slate-50 active:scale-95 transition-all pagination-link shadow-2xs text-xs font-bold cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                    data-target="incomplete"
                    aria-label="Go to previous page of incomplete submissions">
                    <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                </a>
            @endif

            <!-- Next Page Link -->
            @if ($incompleteSubmissions->hasMorePages())
                <a href="{{ $incompleteSubmissions->appends(['pending_page' => $pendingSubmissions ? $pendingSubmissions->currentPage() : request('pending_page', 1), 'incomplete_search' => request('incomplete_search'), 'incomplete_sort' => request('incomplete_sort')])->nextPageUrl() }}"
                    class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:text-[#8B0000] hover:border-[#8B0000] hover:bg-slate-50 active:scale-95 transition-all pagination-link shadow-2xs text-xs font-bold cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                    data-target="incomplete"
                    aria-label="Go to next page of incomplete submissions">
                    <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                </a>
            @else
                <span class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl border border-slate-200 text-slate-300 cursor-not-allowed opacity-40 text-xs" aria-disabled="true" aria-label="Next page">
                    <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                </span>
            @endif
        </nav>
    </div>
</div>