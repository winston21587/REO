<x-admin_layout :title="'Revisions'">
    <div class="max-w-7xl mx-auto w-full min-h-[calc(100vh-5rem)] flex flex-col justify-between space-y-8 selection:bg-[#8B0000] selection:text-white pt-3 sm:pt-4">
        <div class="space-y-8 flex-1 flex flex-col">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-slate-200 w-full min-w-0">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Revisions</h1>
                <p class="text-slate-500 mt-1 sm:mt-1.5 text-xs sm:text-sm">Manage protocols requiring or submitting revisions.</p>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto min-w-0">
                <div class="relative flex-1 sm:flex-initial min-w-0">
                    <input type="text" name="search" id="revisions_search_input" value="{{ request('search') }}"
                        placeholder="Search revisions..."
                        aria-label="Search revisions"
                        class="h-10 sm:h-11 min-h-[38px] pl-10 pr-4 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-full sm:w-64 md:w-72 shadow-2xs bg-white transition-all">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs sm:text-sm"></i>
                </div>
                
                <!-- Filter Drawer Toggle -->
                <div class="relative shrink-0" x-data="{ expanded: sessionStorage.getItem('revisionsFilterExpanded') === 'true' }" x-init="$watch('expanded', value => sessionStorage.setItem('revisionsFilterExpanded', value))" @keydown.escape.window="expanded = false">
                    <button type="button" @click="expanded = true"
                        aria-label="Open filter options"
                        class="h-10 sm:h-11 min-h-[38px] flex items-center gap-2 px-3 sm:px-4 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs transition-all justify-between w-auto sm:w-[120px] cursor-pointer">
                        <span><i class="fas fa-filter mr-1.5 text-slate-400 text-xs"></i> Filter</span>
                        <i class="fas fa-bars text-xs text-slate-400 transition-transform" :class="expanded ? 'rotate-90' : ''"></i>
                    </button>

                    <!-- Advanced Filter Drawer -->
                    <div x-show="expanded" style="display: none;" class="relative z-[100]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
                        <!-- Background backdrop -->
                        <div x-show="expanded" 
                             x-transition:enter="ease-in-out duration-300" 
                             x-transition:enter-start="opacity-0" 
                             x-transition:enter-end="opacity-100" 
                             x-transition:leave="ease-in-out duration-300" 
                             x-transition:leave-start="opacity-100" 
                             x-transition:leave-end="opacity-0" 
                             class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" 
                             @click="expanded = false"></div>

                        <div class="fixed inset-0 overflow-hidden pointer-events-none">
                            <div class="absolute inset-0 overflow-hidden">
                                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10"
                                     x-show="expanded"
                                     x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                                     x-transition:enter-start="translate-x-full"
                                     x-transition:enter-end="translate-x-0"
                                     x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                                     x-transition:leave-start="translate-x-0"
                                     x-transition:leave-end="translate-x-full">
                                    
                                    <div class="pointer-events-auto w-screen max-w-xs flex flex-col h-full bg-white shadow-2xl">
                                        <!-- Drawer Header -->
                                        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/80 flex-none text-left">
                                            <div>
                                                <h3 class="font-heading font-bold text-base text-slate-900">Apply Filters</h3>
                                                <p class="text-xs text-slate-500 mt-0.5">Filter revisions by status & review type</p>
                                            </div>
                                            <button type="button" @click="expanded = false" class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer" aria-label="Close filters">
                                                <i class="fas fa-times text-base"></i>
                                            </button>
                                        </div>

                                        <!-- Drawer Filters List -->
                                        <div class="flex-1 overflow-y-auto w-full pb-10">
                                            <!-- Sort Section -->
                                            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Sort By</label>
                                                <div class="space-y-2">
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="radio" name="revisions_sort" value="updated_at" class="revisions-filter-input text-[#8B0000] focus:ring-[#8B0000] cursor-pointer" {{ request('sort_by', 'updated_at') == 'updated_at' ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Last Updated</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="radio" name="revisions_sort" value="Title" class="revisions-filter-input text-[#8B0000] focus:ring-[#8B0000] cursor-pointer" {{ request('sort_by') == 'Title' ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Title</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Status Section -->
                                            <div class="p-4 border-b border-slate-100">
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Protocol Status</label>
                                                <div class="space-y-2">
                                                    @php $selectedStatuses = request('statuses', ['Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions', 'Reviewed', 'Panel Deliberation']); @endphp
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_status[]" value="Waiting for Revision" class="revisions-filter-input rounded text-amber-600 focus:ring-amber-500 cursor-pointer" {{ in_array('Waiting for Revision', $selectedStatuses) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-amber-700 transition-colors">Waiting for Revision</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_status[]" value="Revision Submitted" class="revisions-filter-input rounded text-purple-600 focus:ring-purple-500 cursor-pointer" {{ in_array('Revision Submitted', $selectedStatuses) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-purple-700 transition-colors">Revision Submitted</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_status[]" value="Reviewing Revisions" class="revisions-filter-input rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer" {{ in_array('Reviewing Revisions', $selectedStatuses) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-700 transition-colors">Reviewing Revisions</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_status[]" value="Reviewed" class="revisions-filter-input rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer" {{ in_array('Reviewed', $selectedStatuses) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-emerald-700 transition-colors">Reviewed</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_status[]" value="Panel Deliberation" class="revisions-filter-input rounded text-pink-600 focus:ring-pink-500 cursor-pointer" {{ in_array('Panel Deliberation', $selectedStatuses) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-pink-700 transition-colors">Panel Deliberation</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Review Type Section -->
                                            <div class="p-4 border-b border-slate-100">
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Review Type</label>
                                                <div class="space-y-2">
                                                    @php $selectedTypes = request('review_types', []); @endphp
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_review_types[]" value="Exempt Review" class="revisions-filter-input rounded text-[#8B0000] focus:ring-[#8B0000] cursor-pointer" {{ in_array('Exempt Review', $selectedTypes) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Exempt</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_review_types[]" value="Expedited Review" class="revisions-filter-input rounded text-[#8B0000] focus:ring-[#8B0000] cursor-pointer" {{ in_array('Expedited Review', $selectedTypes) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Expedited</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_review_types[]" value="Full Board Review" class="revisions-filter-input rounded text-[#8B0000] focus:ring-[#8B0000] cursor-pointer" {{ in_array('Full Board Review', $selectedTypes) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Full Board</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Reviewer Decision Section -->
                                            <div class="p-4">
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Reviewer Decision</label>
                                                <div class="space-y-2">
                                                    @php $selectedDecisions = request('reviewer_decisions', []); @endphp
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_reviewer_decisions[]" value="Approved" class="revisions-filter-input rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer" {{ in_array('Approved', $selectedDecisions) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-emerald-700 transition-colors">Approved</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_reviewer_decisions[]" value="Minor revision/s required" class="revisions-filter-input rounded text-amber-600 focus:ring-amber-500 cursor-pointer" {{ in_array('Minor revision/s required', $selectedDecisions) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-amber-700 transition-colors">Minor revision/s</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_reviewer_decisions[]" value="Major revision/s required" class="revisions-filter-input rounded text-orange-600 focus:ring-orange-500 cursor-pointer" {{ in_array('Major revision/s required', $selectedDecisions) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-orange-700 transition-colors">Major revision/s</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_reviewer_decisions[]" value="Disapproved" class="revisions-filter-input rounded text-red-600 focus:ring-red-500 cursor-pointer" {{ in_array('Disapproved', $selectedDecisions) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-red-700 transition-colors">Disapproved</span>
                                                    </label>
                                                    <label class="flex items-center gap-2.5 cursor-pointer group py-0.5">
                                                        <input type="checkbox" name="revisions_reviewer_decisions[]" value="Panel Deliberation" class="revisions-filter-input rounded text-pink-600 focus:ring-pink-500 cursor-pointer" {{ in_array('Panel Deliberation', $selectedDecisions) ? 'checked' : '' }}>
                                                        <span class="text-sm font-medium text-slate-700 group-hover:text-pink-700 transition-colors">Panel Deliberation</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="revisions-wrapper" class="flex-1 flex flex-col min-h-[400px]">
            @include('admin.partials.active_revisions_list')
        </div>
        </div>
    </div>

    @include('admin.partials.revision_status_modal')
    @include('admin.partials.revision_action_drawer')
    
    <!-- Revision Logs Modal -->
    <div id="revisionLogsModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" onclick="document.getElementById('revisionLogsModal').classList.add('hidden')"></div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200/80">
                    <div class="bg-white px-5 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-history text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-heading" id="modal-title">Revision History</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Audit log of revision submissions and status updates</p>
                            </div>
                        </div>
                        <button type="button" onclick="document.getElementById('revisionLogsModal').classList.add('hidden')"
                            class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer" aria-label="Close modal">
                            <i class="fas fa-times text-base"></i>
                        </button>
                    </div>
                    <div class="px-5 py-5 sm:p-6 bg-slate-50/50">
                        <div class="max-h-[60vh] overflow-y-auto space-y-4 pr-1" id="logsContainer">
                            <!-- Logs will be injected here -->
                        </div>
                    </div>
                    <div class="bg-white px-5 py-3.5 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                        <button type="button" onclick="document.getElementById('revisionLogsModal').classList.add('hidden')"
                            class="h-10 min-h-[38px] inline-flex w-full justify-center items-center rounded-xl bg-white px-4 text-sm font-semibold text-slate-700 shadow-2xs border border-slate-200 hover:bg-slate-50 hover:text-slate-900 sm:w-auto transition-colors cursor-pointer">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openRevisionLogsModal(logs) {
            const container = document.getElementById('logsContainer');
            const modal = document.getElementById('revisionLogsModal');
            
            container.innerHTML = ''; // Clear previous logs
            
            if (logs.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-slate-500">
                        <i class="fas fa-info-circle text-2xl mb-2 text-slate-300"></i>
                        <p class="text-sm font-medium">No revision logs found for this protocol.</p>
                    </div>
                `;
            } else {
                logs.forEach(log => {
                    const date = new Date(log.created_at).toLocaleString();
                    const message = log.message || '<em class="text-slate-400 font-normal">No message provided</em>';
                    const userName = log.user ? `${log.user.first_name} ${log.user.last_name}` : 'Unknown User';
                    
                    const logItem = `
                        <div class="relative pl-6 border-l-2 border-slate-200 pb-3 last:pb-0">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-slate-300 ring-4 ring-white"></div>
                            <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
                                <div class="flex justify-between items-start mb-1.5 gap-2">
                                    <span class="text-xs font-bold text-slate-800">${userName}</span>
                                    <span class="text-xs text-slate-500 font-mono">${date}</span>
                                </div>
                                <p class="text-sm text-slate-600 whitespace-pre-wrap leading-relaxed">${message}</p>
                            </div>
                        </div>
                    `;
                    container.innerHTML += logItem;
                });
            }
            
            modal.classList.remove('hidden');
        }

        function confirmRevertPhase(id, title, currentStatus) {
            let revertText = "";
            let targetPhase = "";

            switch (currentStatus) {
                case 'Reviewed':
                    revertText = "Reopen the evaluation window? Submissions will step backward.";
                    targetPhase = "Under Review";
                    break;
                case 'Under Review':
                    revertText = "Are you sure? This will step backward to assignment phase.";
                    targetPhase = "Reviewer Assigned";
                    break;
                case 'Reviewer Assigned':
                    revertText = "This will unassign all reviewers and clear their progress!";
                    targetPhase = "Hardcopy Received";
                    break;
                case 'Hardcopy Received':
                case 'Incomplete Hardcopy':
                    revertText = "Step backward to incomplete status?";
                    targetPhase = "Incomplete - Awaiting Hardcopy";
                    break;
                case 'Incomplete - Awaiting Hardcopy':
                default:
                    revertText = "This will cancel the appointment and toss it back to New Submissions.";
                    targetPhase = "Pending (Initial Intake)";
                    break;
            }

            Swal.fire({
                title: 'Step Backward?',
                html: `Are you sure you want to revert "<span class="font-bold">${title}</span>"?<br><br>` + 
                      `<span class="text-red-600 font-bold">${revertText}</span><br>` +
                      `<span class="text-xs text-slate-500">Target Phase: ${targetPhase}</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, Step Backward',
                cancelButtonText: 'Cancel',
                scrollbarPadding: false,
                backdrop: `rgba(15, 23, 42, 0.75)`,
                customClass: {
                    popup: 'rounded-2xl shadow-xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold shadow-lg shadow-red-900/20',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold'
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
                    document.getElementById('revertPhaseForm-' + id).submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('revisions_search_input');
            let debounceTimer;

            const fetchRevisions = (params) => {
                const url = `{{ route('admin.revisions') }}?${params.toString()}`;
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const wrapper = document.getElementById('revisions-wrapper');
                    if (wrapper && data.html) {
                        wrapper.innerHTML = data.html;
                    }
                    window.history.pushState({}, '', url);
                })
                .catch(error => console.error('Error fetching revisions:', error));
            };

            const triggerFetch = (resetPage = false) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const params = new URLSearchParams(window.location.search);
                    
                    if (searchInput) {
                        if (searchInput.value) params.set('search', searchInput.value);
                        else params.delete('search');
                    }

                    // Sort By
                    const sortNode = document.querySelector('input[name="revisions_sort"]:checked');
                    if (sortNode) params.set('sort_by', sortNode.value);

                    // Status
                    params.delete('statuses[]');
                    document.querySelectorAll('input[name="revisions_status[]"]:checked').forEach(cb => {
                        params.append('statuses[]', cb.value);
                    });

                    // Review Type
                    params.delete('review_types[]');
                    document.querySelectorAll('input[name="revisions_review_types[]"]:checked').forEach(cb => {
                        params.append('review_types[]', cb.value);
                    });

                    // Reviewer Decision
                    params.delete('reviewer_decisions[]');
                    document.querySelectorAll('input[name="revisions_reviewer_decisions[]"]:checked').forEach(cb => {
                        params.append('reviewer_decisions[]', cb.value);
                    });

                    if (resetPage) params.delete('page');

                    fetchRevisions(params);
                }, 300);
            };

            if (searchInput) {
                searchInput.addEventListener('input', () => triggerFetch(true));
            }

            document.querySelectorAll('.revisions-filter-input').forEach(input => {
                input.addEventListener('change', () => triggerFetch(true));
            });

            // Pagination delegation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('.filter-pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    fetchRevisions(new URLSearchParams(url.search));
                }
            });
        });
    </script>
</x-admin_layout>