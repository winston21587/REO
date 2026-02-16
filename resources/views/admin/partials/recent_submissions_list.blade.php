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
                    <span
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-yellow-50 text-yellow-700 text-[10px] font-bold border border-yellow-200 uppercase tracking-wider shadow-sm cursor-default flex-shrink-0 ml-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                        Pending
                    </span>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a href="{{ route('admin.view_files', $sub->id) }}"
                        class="bg-[#dc2626] hover:bg-[#b91c1c] text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                        View Details
                    </a>

                    <div class="flex items-center gap-2">
                        <button onclick="openTriageModal('{{ $sub->id }}', '{{ addslashes($sub->Study_Protocol_title) }}')"
                            class="bg-[#fecaca] hover:bg-[#fca5a5] text-[#991b1b] px-4 py-2 rounded-lg text-xs font-bold transition-all">
                            Action
                        </button>
                    </div>
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