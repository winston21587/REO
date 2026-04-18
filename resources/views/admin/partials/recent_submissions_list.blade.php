<div id="recent-submissions-container" class="space-y-4 flex-1 h-full flex flex-col">
    <div class="space-y-4 flex-1">
        @forelse($pendingSubmissions as $sub)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg leading-tight group-hover:text-[#8B0000] transition-colors line-clamp-1"
                            title="{{ $sub->Study_Protocol_title }}">{{ $sub->Study_Protocol_title }}</h3>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Submitted at:
                            {{ $sub->created_at->format('Y-m-d') }}
                        </p>
                    </div>
                    @if($sub->Status === 'Revision Submitted')
                        <span
                            class="inline-flex items-center justify-center min-w-[70px] uppercase font-bold text-[10px] tracking-wider px-2 py-1 rounded bg-violet-50 text-violet-600 border border-violet-100 shrink-0">
                            Resubmitted
                        </span>
                    @else
                        <span
                            class="inline-flex items-center justify-center min-w-[70px] uppercase font-bold text-[10px] tracking-wider px-2 py-1 rounded bg-orange-50 text-orange-600 border border-orange-100 shrink-0">
                            Pending
                        </span>
                    @endif
                </div>

                @if($sub->Status === 'Revision Submitted' && $sub->revisionLogs->first())
                    <div class="mt-3 bg-blue-50 p-3 rounded-lg border border-blue-100">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-blue-800 mb-1">
                            <i class="fas fa-comment-alt mr-1"></i> Researcher's Note
                        </p>
                        <p class="text-xs text-blue-900 leading-relaxed italic">
                            "{{ $sub->revisionLogs->first()->message }}"
                        </p>
                    </div>
                @endif

                <div class="flex items-center justify-end gap-3 mt-4">
                    <a href="{{ route('admin.view_files', $sub->id) }}"
                        class="bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-slate-300">
                        View Details
                    </a>

                    <button
                        onclick="openTriageModal('{{ $sub->id }}', '{{ addslashes($sub->Study_Protocol_title) }}', '{{ $sub->Official_Receipt_Number ?? '' }}', '{{ $sub->or_file_path ? asset($sub->or_file_path) : '' }}', {{ $sub->is_or_verified ? 'true' : 'false' }})"
                        class="bg-[#8B0000] hover:bg-[#6d0000] text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-1">
                        Action
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-3xl text-slate-300"></i>
                </div>
                <p class="text-slate-500 font-medium">No recent submissions found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-auto pt-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500">
        <div>
            Showing <span class="font-bold text-slate-700">{{ $pendingSubmissions->firstItem() ?? 0 }}</span> - <span
                class="font-bold text-slate-700">{{ $pendingSubmissions->lastItem() ?? 0 }}</span> of <span
                class="font-bold text-slate-700">{{ $pendingSubmissions->total() }}</span>
        </div>
        <div class="flex gap-2">
            <!-- Previous Page Link -->
            @if ($pendingSubmissions->onFirstPage())
                <span class="opacity-50 cursor-not-allowed text-slate-400"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $pendingSubmissions->appends(['incomplete_page' => $incompleteSubmissions->currentPage(), 'recent_search' => request('recent_search'), 'recent_sort' => request('recent_sort')])->previousPageUrl() }}"
                    class="text-slate-600 hover:text-[#8B0000] transition-colors pagination-link" data-target="recent"><i
                        class="fas fa-chevron-left"></i></a>
            @endif

            <!-- Next Page Link -->
            @if ($pendingSubmissions->hasMorePages())
                <a href="{{ $pendingSubmissions->appends(['incomplete_page' => $incompleteSubmissions->currentPage(), 'recent_search' => request('recent_search'), 'recent_sort' => request('recent_sort')])->nextPageUrl() }}"
                    class="text-slate-600 hover:text-[#8B0000] transition-colors pagination-link" data-target="recent"><i
                        class="fas fa-chevron-right"></i></a>
            @else
                <span class="opacity-50 cursor-not-allowed text-slate-400"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
</div>