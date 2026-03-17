<x-admin_layout>

    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">

        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Active Protocols</h1>
                <p class="text-slate-500 mt-2 text-sm">Monitoring Initial Reviews and Revisions.</p>
            </div>
            <div class="flex gap-4 mt-4 md:mt-0 w-full md:w-auto">
                <form action="{{ route('admin.applications') }}" method="GET" class="relative flex w-full gap-4" id="activeProtocolsForm">
                    <!-- Search Input -->
                    <div class="relative flex-1 md:w-64">
                        <input type="text" name="search" id="search_input" value="{{ request('search') }}" placeholder="Search protocols..."
                            class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm bg-white">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>

                    <!-- Filter Dropdown -->
                    <div class="relative" x-data="{ expanded: sessionStorage.getItem('activeProtocolsFilterExpanded') === 'true' }" x-init="$watch('expanded', value => sessionStorage.setItem('activeProtocolsFilterExpanded', value))">
                        <button type="button" @click="expanded = !expanded" @click.outside="expanded = false"
                            class="flex items-center gap-2 px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm transition-colors w-[150px] justify-between">
                            <span><i class="fas fa-filter mr-1 text-slate-400"></i> Filter</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Advanced Dropdown Menu -->
                        <div x-show="expanded" x-cloak x-transition.opacity.duration.200ms @click.stop
                            class="absolute right-0 mt-2 w-72 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden max-h-[80vh] overflow-y-auto">
                            
                            <!-- Sort Section -->
                            <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Sort By</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="sort_by" value="created_at" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('sort_by', 'created_at') == 'created_at' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Submission Date</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="sort_by" value="Title" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('sort_by') == 'Title' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Title</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Review Type Section -->
                            <div class="p-3 border-b border-slate-100">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Review Type</label>
                                <div class="space-y-2">
                                    @php $selectedTypes = request('review_types', []); @endphp
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="review_types[]" value="Exempt Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Exempt Review', $selectedTypes) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Exempt</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="review_types[]" value="Expedited Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Expedited Review', $selectedTypes) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Expedited</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="review_types[]" value="Full Board Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Full Board Review', $selectedTypes) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Full Board</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Status Section -->
                            <div class="p-3 border-b border-slate-100">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Current Status</label>
                                <div class="space-y-2">
                                    @php $selectedStatuses = request('statuses', []); @endphp
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="statuses[]" value="For Initial Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('For Initial Review', $selectedStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors text-balance leading-snug">For Initial Review (Includes Hardcopy Receiving)</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="statuses[]" value="Under Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Under Review', $selectedStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Under Review</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Assignment Section -->
                            <div class="p-3 bg-slate-50/50">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Reviewer Assignment</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="assignment" value="All" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('assignment', 'All') == 'All' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Show All Protocols</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="assignment" value="Unassigned" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('assignment') == 'Unassigned' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Unassigned Only</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="assignment" value="Assigned" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('assignment') == 'Assigned' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Assigned Only</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="active-protocols-wrapper" class="flex-1 flex flex-col min-h-[400px]">
            @include('admin.partials.active_protocols_list')
        </div>
    </div>



    <!-- Include Status Update Modal -->
    @include('admin.partials.status_modal')

    <!-- Assign Reviewer Modal -->
    <div x-data="{ open: false, protocolId: '', protocolTitle: '', assigned: [] }" 
         @open-assign-modal.window="open = true; protocolId = $event.detail.id; protocolTitle = $event.detail.title; assigned = Array.isArray($event.detail.assigned) ? $event.detail.assigned : [];" 
         class="relative z-[9999]"
         aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;" x-show="open">

        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" x-show="open"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md"
                    x-show="open" @click.away="open = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <form :action="'{{ url('admin/applications') }}/' + protocolId + '/assign-reviewers'" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-users-cog text-blue-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Assign Reviewers</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500 mb-4" x-text="'Select reviewers for: ' + protocolTitle"></p>
                                        
                                        <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                                            @foreach($reviewers as $reviewer)
                                                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-colors">
                                                    <input type="checkbox" name="reviewers[]" value="{{ $reviewer->id }}" 
                                                           ::checked="assigned.map(String).includes('{{ $reviewer->id }}')"
                                                           class="rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] w-5 h-5">
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-800">{{ $reviewer->first_name }} {{ $reviewer->last_name }}</p>
                                                        <p class="text-xs text-slate-500">{{ $reviewer->college ?? ucfirst($reviewer->role) }}</p>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-xl bg-[#8B0000] px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-900 sm:ml-3 sm:w-auto transition-colors">
                                Save Assignments
                            </button>
                            <button type="button" @click="open = false"
                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmFinalize(id, title) {
            Swal.fire({
                title: 'Finalize Review?',
                text: "Are you sure you want to proceed with revision for \"" + title + "\"? This will notify the researcher.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Proceed',
                cancelButtonText: 'Cancel',
                scrollbarPadding: false,
                backdrop: `rgba(15, 23, 42, 0.75)`,
                buttonsStyling: false,
                showClass: {
                    popup: 'animate-[fadeInUp_0.3s_ease-out]'
                },
                hideClass: {
                    popup: 'animate-[fadeOutDown_0.3s_ease-in]'
                },
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                    title: 'font-heading text-xl text-slate-800 font-bold pt-4',
                    htmlContainer: 'text-slate-600 text-sm mt-2',
                    confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-900 hover:shadow-xl hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition-all outline-none focus:ring-0 mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show Loading State
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Please wait while we finalize the review.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        scrollbarPadding: false,
                        backdrop: `rgba(15, 23, 42, 0.75)`,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                            title: 'font-heading text-xl text-slate-800 font-bold',
                            htmlContainer: 'text-slate-600 text-sm'
                        },
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    document.getElementById('finalizeForm-' + id).submit();
                }
            });
        }

        function confirmUndoComplete(id, title) {
            Swal.fire({
                title: 'Undo "Complete"?',
                text: "Revert \"" + title + "\" to Pending? This will cancel the hardcopy appointment.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#475569',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, Undo',
                cancelButtonText: 'Cancel',
                scrollbarPadding: false,
                backdrop: `rgba(15, 23, 42, 0.75)`,
                customClass: {
                    popup: 'rounded-2xl shadow-xl',
                    confirmButton: 'rounded-xl px-4 py-2',
                    cancelButton: 'rounded-xl px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Reverting...',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('undoCompleteForm-' + id).submit();
                }
            });
        }

        // Auto-Submit Logic for Advanced Filters via AJAX
        document.addEventListener('DOMContentLoaded', function() {
            let debounceTimer;
            const form = document.getElementById('activeProtocolsForm');
            
            const fetchProtocols = (params) => {
                const url = `{{ route('admin.applications') }}?${params.toString()}`;
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('active-protocols-wrapper');
                    if (container && data.html) {
                        container.innerHTML = data.html;
                    }
                    window.history.pushState({}, '', url);
                })
                .catch(error => console.error('Error fetching protocols:', error));
            };

            const triggerFetch = () => {
                const params = new URLSearchParams(new FormData(form));
                fetchProtocols(params);
            };

            // Text search input with debounce
            const searchInput = document.getElementById('search_input');
            if(searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        triggerFetch();
                    }, 500); 
                });
            }

            // Checkboxes and radio buttons trigger immediate fetch
            const autoSubmitInputs = document.querySelectorAll('.auto-submit-input');
            autoSubmitInputs.forEach(input => {
                input.addEventListener('change', function() {
                    triggerFetch();
                });
            });

            // Prevent default form submission from reloading the page
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                triggerFetch();
            });

            // Pagination delegation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('.filter-pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    fetchProtocols(new URLSearchParams(url.search));
                }
            });
        });
    </script>
</x-admin_layout>