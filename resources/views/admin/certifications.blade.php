<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">


        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200"
            x-data="{ filterDrawerOpen: sessionStorage.getItem('certFilterExpanded') === 'true' }">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Certifications</h1>
                <p class="text-slate-500 mt-2 text-sm">View approved protocols and manage clearance certificates.</p>
            </div>

            <div class="flex gap-4 mt-4 md:mt-0 w-full md:w-auto">
                <div class="relative flex w-full gap-4">
                    <!-- Search Input -->
                    <div class="relative flex-1 md:w-64">
                        <input type="text" id="cert_search_input" value="{{ request('search') }}"
                            placeholder="Search certifications..."
                            class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm bg-white">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>

                    <button type="button" @click="filterDrawerOpen = true"
                        class="flex items-center gap-2 px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm transition-colors w-[150px] justify-between flex-shrink-0">
                        <span><i class="fas fa-filter mr-1 text-slate-400"></i> Filter</span>
                        <i class="fas fa-bars text-xs text-slate-400 transition-transform"
                            :class="filterDrawerOpen ? 'rotate-90' : ''"></i>
                    </button>
                </div>

                <!-- Dedicated Filters Slide-Over Drawer -->
                <template x-teleport="body">
                    <div x-show="filterDrawerOpen" class="fixed inset-0 z-[105] overflow-hidden"
                        aria-labelledby="slide-over-title" role="dialog" aria-modal="true" style="display: none;">
                        <div class="absolute inset-0 overflow-hidden">
                            <!-- Backdrop -->
                            <div x-show="filterDrawerOpen" x-transition.opacity
                                class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                                @click="filterDrawerOpen = false"></div>

                            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-md w-full pl-10">
                                <div x-show="filterDrawerOpen"
                                    x-transition:enter="transform transition ease-out duration-300"
                                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                                    x-transition:leave="transform transition ease-in duration-300"
                                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                                    class="pointer-events-auto w-screen max-w-md">
                                    <div class="flex h-full flex-col overflow-y-scroll bg-slate-50 shadow-2xl">

                                        <div
                                            class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 flex-none text-left">
                                            <h3
                                                class="font-heading font-extrabold text-lg text-slate-800 leading-tight">
                                                Apply Filters</h3>
                                            <button type="button" @click="filterDrawerOpen = false"
                                                class="text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-colors w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-full mt-0.5">
                                                <i class="fas fa-times text-lg"></i>
                                            </button>
                                        </div>

                                        <div class="flex-1 overflow-y-auto w-full pb-10">
                                            <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                                <label
                                                    class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Sort
                                                    Results By</label>
                                                <div class="space-y-2">
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input type="radio" name="cert_sort" value="updated_at"
                                                            class="cert-filter-input text-[#8B0000] focus:ring-[#8B0000]"
                                                            {{ request('sort_by', 'updated_at') == 'updated_at' ? 'checked' : '' }}>
                                                        <span
                                                            class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Last
                                                            Updated</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input type="radio" name="cert_sort" value="Title"
                                                            class="cert-filter-input text-[#8B0000] focus:ring-[#8B0000]"
                                                            {{ request('sort_by') == 'Title' ? 'checked' : '' }}>
                                                        <span
                                                            class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Alphabetical
                                                            (Title)</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="p-3 border-b border-slate-100">
                                                <label
                                                    class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Review
                                                    Type</label>
                                                <div class="space-y-2">
                                                    @php $selectedTypes = request('review_types', []); @endphp
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input type="checkbox" name="cert_review_types[]"
                                                            value="Exempt Review"
                                                            class="cert-filter-input rounded text-[#8B0000] focus:ring-[#8B0000]"
                                                            {{ in_array('Exempt Review', $selectedTypes) ? 'checked' : '' }}>
                                                        <span
                                                            class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Exempt</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input type="checkbox" name="cert_review_types[]"
                                                            value="Expedited Review"
                                                            class="cert-filter-input rounded text-[#8B0000] focus:ring-[#8B0000]"
                                                            {{ in_array('Expedited Review', $selectedTypes) ? 'checked' : '' }}>
                                                        <span
                                                            class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Expedited</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input type="checkbox" name="cert_review_types[]"
                                                            value="Full Board Review"
                                                            class="cert-filter-input rounded text-[#8B0000] focus:ring-[#8B0000]"
                                                            {{ in_array('Full Board Review', $selectedTypes) ? 'checked' : '' }}>
                                                        <span
                                                            class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Full
                                                            Board</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 flex flex-col min-h-[400px]">
            <div id="certifications-wrapper" class="overflow-x-auto flex-grow flex flex-col">
                @include('admin.partials.active_certifications_list')
            </div>
        </div>
    </div>

    @include('admin.partials.upload_certificate_modal')
    @include('admin.partials.view_certificates_modal')

    @if(session('success'))
        <div id="certToast"
            class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-medium animate-[fadeInUp_0.4s_ease-out]">
            <i class="fas fa-check-circle text-emerald-200"></i>
            {{ session('success') }}
            <button onclick="document.getElementById('certToast').remove()"
                class="ml-2 text-emerald-200 hover:text-white">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div id="certErrorToast"
            class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 bg-red-600 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-medium animate-[fadeInUp_0.4s_ease-out]">
            <i class="fas fa-exclamation-circle text-red-200"></i>
            {{ session('error') }}
            <button onclick="document.getElementById('certErrorToast').remove()"
                class="ml-2 text-red-200 hover:text-white">&times;</button>
        </div>
    @endif

    <script>
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
            const searchInput = document.getElementById('cert_search_input');
            let debounceTimer;

            const fetchCertifications = (params) => {
                const url = `{{ route('admin.certifications') }}?${params.toString()}`;

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        const wrapper = document.getElementById('certifications-wrapper');
                        if (wrapper && data.html) {
                            wrapper.innerHTML = data.html;
                        }
                        window.history.pushState({}, '', url);
                    })
                    .catch(error => console.error('Error fetching certifications:', error));
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
                    const sortNode = document.querySelector('input[name="cert_sort"]:checked');
                    if (sortNode) params.set('sort_by', sortNode.value);

                    // Review Type
                    params.delete('review_types[]');
                    document.querySelectorAll('input[name="cert_review_types[]"]:checked').forEach(cb => {
                        params.append('review_types[]', cb.value);
                    });

                    if (resetPage) params.delete('page');

                    fetchCertifications(params);
                }, 300);
            };

            if (searchInput) {
                searchInput.addEventListener('input', () => triggerFetch(true));
            }

            document.querySelectorAll('.cert-filter-input').forEach(input => {
                input.addEventListener('change', () => triggerFetch(true));
            });

            // Pagination delegation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('.filter-pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    fetchCertifications(new URLSearchParams(url.search));
                }
            });
        });
    </script>
</x-admin_layout>