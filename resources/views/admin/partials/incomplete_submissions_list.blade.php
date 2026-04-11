<div id="incomplete-submissions-container" class="space-y-4 flex-1 h-full flex flex-col">
    <div class="space-y-4 flex-1">
        @forelse($incompleteSubmissions as $sub)
            @php
                // Check if any researcher file was uploaded AFTER this title was last updated (i.e., after status was set to Incomplete)
                $latestFileUpload = $sub->files->max('pivot.created_at') ?? $sub->files->max('created_at');
                $hasNewFilesUploaded = $latestFileUpload && \Carbon\Carbon::parse($latestFileUpload)->gt($sub->updated_at);
            @endphp
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-800 text-lg leading-tight group-hover:text-[#8B0000] transition-colors line-clamp-1"
                            title="{{ $sub->Study_Protocol_title }}">{{ $sub->Study_Protocol_title }}</h3>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Submitted at:
                            {{ $sub->created_at->format('Y-m-d') }}
                        </p>
                    </div>
                    @if($hasNewFilesUploaded)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200 shadow-sm flex-shrink-0 ml-2 animate-pulse" title="Researcher has uploaded updated files. Please review.">
                            <i class="fas fa-file-upload"></i> Files Updated
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-3 mt-4">
                    <button onclick="undoIncomplete('{{ $sub->id }}', '{{ addslashes($sub->Study_Protocol_title) }}')"
                        class="bg-slate-50 hover:bg-slate-100 text-slate-500 border border-slate-200 px-3 py-2 rounded-lg text-xs font-bold transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                        title="Undo Incomplete Status">
                        <i class="fas fa-undo"></i>
                    </button>
                    
                    <a href="{{ route('admin.view_files', $sub->id) }}"
                        class="bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-slate-300">
                        View Details
                    </a>

                    <button onclick="openTriageModal('{{ $sub->id }}', '{{ addslashes($sub->Study_Protocol_title) }}')"
                        class="bg-[#8B0000] hover:bg-[#6d0000] text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-1">
                        Re-Check
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-circle text-3xl text-slate-300"></i>
                </div>
                <p class="text-slate-500 font-medium">No incomplete submissions found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-auto pt-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500">
        <div>
            Showing <span class="font-bold text-slate-700">{{ $incompleteSubmissions->firstItem() ?? 0 }}</span> - <span
                class="font-bold text-slate-700">{{ $incompleteSubmissions->lastItem() ?? 0 }}</span> of <span
                class="font-bold text-slate-700">{{ $incompleteSubmissions->total() }}</span>
        </div>
        <div class="flex gap-2">
            <!-- Previous Page Link -->
            @if ($incompleteSubmissions->onFirstPage())
                <span class="opacity-50 cursor-not-allowed text-slate-400"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $incompleteSubmissions->appends(['pending_page' => $pendingSubmissions->currentPage(), 'incomplete_search' => request('incomplete_search'), 'incomplete_sort' => request('incomplete_sort')])->previousPageUrl() }}"
                    class="text-slate-600 hover:text-[#8B0000] transition-colors pagination-link"
                    data-target="incomplete"><i class="fas fa-chevron-left"></i></a>
            @endif

            <!-- Next Page Link -->
            @if ($incompleteSubmissions->hasMorePages())
                <a href="{{ $incompleteSubmissions->appends(['pending_page' => $pendingSubmissions->currentPage(), 'incomplete_search' => request('incomplete_search'), 'incomplete_sort' => request('incomplete_sort')])->nextPageUrl() }}"
                    class="text-slate-600 hover:text-[#8B0000] transition-colors pagination-link"
                    data-target="incomplete"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="opacity-50 cursor-not-allowed text-slate-400"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
</div>

<script>
    function undoIncomplete(id, title) {
        Swal.fire({
            title: 'Undo "Incomplete"?',
            text: `Revert "${title}" to Pending status?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Undo',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#475569',
            cancelButtonColor: '#94a3b8',
            customClass: {
                popup: 'rounded-2xl shadow-xl',
                confirmButton: 'rounded-xl px-4 py-2',
                cancelButton: 'rounded-xl px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Reverting...',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX Request
                fetch(`{{ route('admin.updateStatus', ':id') }}`.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        classification: 'Undo'
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Submission reverted to Pending.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Reload to refresh lists (since we need to move item from Incomplete -> Recent)
                            window.location.reload();
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    });
            }
        });
    }
</script>