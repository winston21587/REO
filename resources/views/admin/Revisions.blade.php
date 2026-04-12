<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Revisions</h1>
                <p class="text-slate-500 mt-2 text-sm">Manage protocols requiring or submitting revisions.</p>
            </div>
            <div class="flex gap-4" x-data="{ expanded: sessionStorage.getItem('revisionsFilterExpanded') === 'true' }" x-init="$watch('expanded', value => sessionStorage.setItem('revisionsFilterExpanded', value))">
                <div class="relative flex-1">
                    <input type="text" name="search" id="revisions_search_input" value="{{ request('search') }}"
                        placeholder="Search revisions..."
                        class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-64 shadow-sm bg-white">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                
                    <!-- Filter Drawer Toggle -->
                    <div class="relative" x-data="{ expanded: sessionStorage.getItem('revisionsFilterExpanded') === 'true' }" x-init="$watch('expanded', value => sessionStorage.setItem('revisionsFilterExpanded', value))">
                        <button type="button" @click="expanded = true"
                            class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm transition-colors justify-between w-[120px]">
                            <span><i class="fas fa-filter mr-1 text-slate-400"></i> Filter</span>
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
                                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
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
                                            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 flex-none text-left">
                                                <h3 class="font-heading font-extrabold text-lg text-slate-800 leading-tight">Apply Filters</h3>
                                                <button type="button" @click="expanded = false" class="text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-colors w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-full mt-0.5">
                                                    <i class="fas fa-times text-lg"></i>
                                                </button>
                                            </div>

                                            <!-- Drawer Filters List -->
                                            <div class="flex-1 overflow-y-auto w-full pb-10">
                                                <!-- Sort Section -->
                                                <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Sort By</label>
                                                    <div class="space-y-2">
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="radio" name="revisions_sort" value="updated_at" class="revisions-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('sort_by', 'updated_at') == 'updated_at' ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Last Updated</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="radio" name="revisions_sort" value="Title" class="revisions-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('sort_by') == 'Title' ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Title</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Status Section -->
                                                <div class="p-3 border-b border-slate-100">
                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Protocol Status</label>
                                                    <div class="space-y-2">
                                                        @php $selectedStatuses = request('statuses', ['Waiting for Revision', 'Revision Submitted', 'Corrections Submitted', 'Checking of Revisions', 'Panel Deliberation', 'Reviewed']); @endphp
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_status[]" value="Waiting for Revision" class="revisions-filter-input rounded text-orange-600 focus:ring-orange-500" {{ in_array('Waiting for Revision', $selectedStatuses) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-orange-600 transition-colors">Waiting for Revision</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_status[]" value="Revision Submitted" class="revisions-filter-input rounded text-purple-600 focus:ring-purple-500" {{ in_array('Revision Submitted', $selectedStatuses) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-purple-600 transition-colors">Revision Submitted</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_status[]" value="Corrections Submitted" class="revisions-filter-input rounded text-violet-600 focus:ring-violet-500" {{ in_array('Corrections Submitted', $selectedStatuses) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-violet-600 transition-colors">Corrections Submitted</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_status[]" value="Checking of Revisions" class="revisions-filter-input rounded text-indigo-600 focus:ring-indigo-500" {{ in_array('Checking of Revisions', $selectedStatuses) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-600 transition-colors">Checking of Revisions</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_status[]" value="Panel Deliberation" class="revisions-filter-input rounded text-pink-600 focus:ring-pink-500" {{ in_array('Panel Deliberation', $selectedStatuses) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-pink-600 transition-colors">Panel Deliberation</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_status[]" value="Reviewed" class="revisions-filter-input rounded text-emerald-600 focus:ring-emerald-500" {{ in_array('Reviewed', $selectedStatuses) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-emerald-600 transition-colors">Reviewed</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Review Type Section -->
                                                <div class="p-3 border-b border-slate-100">
                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Review Type</label>
                                                    <div class="space-y-2">
                                                        @php $selectedTypes = request('review_types', []); @endphp
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_review_types[]" value="Exempt Review" class="revisions-filter-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Exempt Review', $selectedTypes) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Exempt</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_review_types[]" value="Expedited Review" class="revisions-filter-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Expedited Review', $selectedTypes) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Expedited</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_review_types[]" value="Full Board Review" class="revisions-filter-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Full Board Review', $selectedTypes) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Full Board</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Reviewer Decision Section -->
                                                <div class="p-3">
                                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Reviewer Decision</label>
                                                    <div class="space-y-2">
                                                        @php $selectedDecisions = request('reviewer_decisions', []); @endphp
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_reviewer_decisions[]" value="Approved" class="revisions-filter-input rounded text-green-600 focus:ring-green-500" {{ in_array('Approved', $selectedDecisions) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-green-600 transition-colors">Approved</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_reviewer_decisions[]" value="Minor revision/s required" class="revisions-filter-input rounded text-yellow-600 focus:ring-yellow-500" {{ in_array('Minor revision/s required', $selectedDecisions) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-yellow-600 transition-colors">Minor revision/s</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_reviewer_decisions[]" value="Major revision/s required" class="revisions-filter-input rounded text-orange-600 focus:ring-orange-500" {{ in_array('Major revision/s required', $selectedDecisions) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-orange-600 transition-colors">Major revision/s</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_reviewer_decisions[]" value="Disapproved" class="revisions-filter-input rounded text-red-600 focus:ring-red-500" {{ in_array('Disapproved', $selectedDecisions) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-red-600 transition-colors">Disapproved</span>
                                                        </label>
                                                        <label class="flex items-center gap-2 cursor-pointer group">
                                                            <input type="checkbox" name="revisions_reviewer_decisions[]" value="Panel Deliberation" class="revisions-filter-input rounded text-pink-600 focus:ring-pink-500" {{ in_array('Panel Deliberation', $selectedDecisions) ? 'checked' : '' }}>
                                                            <span class="text-sm font-medium text-slate-700 group-hover:text-pink-600 transition-colors">Panel Deliberation</span>
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

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 flex flex-col min-h-[400px]">
            <div id="revisions-wrapper" class="overflow-x-auto flex-grow flex flex-col">
                @include('admin.partials.active_revisions_list')
            </div>
        </div>
    </div>

    @include('admin.partials.revision_status_modal')
    
    <!-- Revision Logs Modal -->
    <div id="revisionLogsModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('revisionLogsModal').classList.add('hidden')"></div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-history text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl font-semibold leading-6 text-slate-900" id="modal-title">Revision History</h3>
                                <div class="mt-4 max-h-[60vh] overflow-y-auto space-y-4 pr-2" id="logsContainer">
                                    <!-- Logs will be injected here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick="document.getElementById('revisionLogsModal').classList.add('hidden')"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
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
                        <p>No revision logs found for this protocol.</p>
                    </div>
                `;
            } else {
                logs.forEach(log => {
                    const date = new Date(log.created_at).toLocaleString();
                    const message = log.message || '<em class="text-slate-400">No message provided</em>';
                    const userName = log.user ? `${log.user.first_name} ${log.user.last_name}` : 'Unknown User';
                    
                    const logItem = `
                        <div class="relative pl-6 border-l-2 border-slate-200 pb-2 last:pb-0">
                            <div class="absolute -left-[5px] top-1 w-2.5 h-2.5 rounded-full bg-slate-300 ring-4 ring-white"></div>
                            <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-xs font-bold text-slate-700">${userName}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">${date}</span>
                                </div>
                                <p class="text-sm text-slate-600 whitespace-pre-wrap">${message}</p>
                            </div>
                        </div>
                    `;
                    container.innerHTML += logItem;
                });
            }
            
            modal.classList.remove('hidden');
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