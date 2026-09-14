<x-admin_layout :title="'Certifications'">
    <div class="max-w-7xl mx-auto w-full min-h-[calc(100vh-5rem)] flex flex-col justify-between space-y-8 selection:bg-[#8B0000] selection:text-white pt-3 sm:pt-4">
        <div class="space-y-8 flex-1 flex flex-col">

        {{-- Page Header with Search & Filter Controls --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-slate-200 w-full min-w-0"
             x-data="{ filterDrawerOpen: sessionStorage.getItem('certFilterExpanded') === 'true' }"
             x-init="$watch('filterDrawerOpen', value => sessionStorage.setItem('certFilterExpanded', value))"
             @keydown.escape.window="filterDrawerOpen = false">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Certifications</h1>
                <p class="text-slate-500 mt-1 sm:mt-1.5 text-xs sm:text-sm">View approved submissions and issue ethics clearance certificates.</p>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto min-w-0">
                <!-- Search Input -->
                <div class="relative flex-1 sm:flex-initial min-w-0">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search text-xs sm:text-sm" aria-hidden="true"></i>
                    </div>
                    <input type="text" id="cert_search_input" value="{{ request('search') }}"
                           placeholder="Search certifications..."
                           aria-label="Search certifications"
                           class="h-10 sm:h-11 min-h-[38px] pl-10 pr-4 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-full sm:w-64 md:w-72 shadow-2xs bg-white transition-all">
                </div>

                <!-- Filter Drawer Trigger Button -->
                <button type="button" @click="filterDrawerOpen = true"
                        aria-label="Open filter options"
                        class="h-10 sm:h-11 min-h-[38px] flex items-center gap-2 px-3 sm:px-4 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs transition-all justify-between w-auto sm:w-[120px] cursor-pointer shrink-0">
                    <span><i class="fas fa-filter mr-1.5 text-slate-400 text-xs"></i> Filter</span>
                    <i class="fas fa-bars text-xs text-slate-400 transition-transform"
                       :class="filterDrawerOpen ? 'rotate-90' : ''"></i>
                </button>

                <!-- Dedicated Filters Slide-Over Drawer -->
                <div x-show="filterDrawerOpen" 
                     style="display: none;" 
                     class="relative z-[105]" 
                     aria-labelledby="cert-filter-title" 
                     role="dialog" 
                     aria-modal="true">
                    <!-- Backdrop -->
                    <div x-show="filterDrawerOpen" 
                         x-transition:enter="ease-in-out duration-300" 
                         x-transition:enter-start="opacity-0" 
                         x-transition:enter-end="opacity-100" 
                         x-transition:leave="ease-in-out duration-300" 
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0" 
                         class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" 
                         @click="filterDrawerOpen = false"></div>

                    <div class="fixed inset-0 overflow-hidden pointer-events-none z-10">
                        <div class="absolute inset-0 overflow-hidden">
                            <!-- Mobile: Bottom sheet | Desktop (sm+): Right slide-over -->
                            <div class="pointer-events-none fixed inset-x-0 bottom-0 top-auto sm:top-0 sm:bottom-0 sm:left-auto sm:right-0 flex max-w-full sm:pl-10 justify-end"
                                 x-show="filterDrawerOpen"
                                 x-transition:enter="transform transition ease-out duration-300 sm:duration-400"
                                 x-transition:enter-start="translate-y-full sm:translate-y-0 sm:translate-x-full"
                                 x-transition:enter-end="translate-y-0 sm:translate-x-0"
                                 x-transition:leave="transform transition ease-in duration-250 sm:duration-300"
                                 x-transition:leave-start="translate-y-0 sm:translate-x-0"
                                 x-transition:leave-end="translate-y-full sm:translate-y-0 sm:translate-x-full">
                                
                                <div class="pointer-events-auto w-full sm:w-screen sm:max-w-xs md:max-w-sm flex flex-col max-h-[88vh] sm:max-h-full sm:h-full bg-white shadow-2xl rounded-t-3xl sm:rounded-none overflow-hidden">
                                    
                                    <!-- Mobile Drag Handle -->
                                    <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mt-2.5 sm:hidden shrink-0"></div>

                                    <!-- Drawer Header -->
                                    <div class="px-5 sm:px-6 pt-2 pb-4 sm:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/80 flex-none text-left">
                                        <div>
                                            <h3 id="cert-filter-title" class="font-heading font-bold text-base sm:text-lg text-slate-900">Apply Filters</h3>
                                            <p class="text-xs text-slate-500 mt-0.5">Filter certifications by type & order</p>
                                        </div>
                                        <button type="button" @click="filterDrawerOpen = false" 
                                                class="w-10 h-10 min-w-[44px] min-h-[44px] flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition-colors cursor-pointer" 
                                                aria-label="Close filters">
                                            <i class="fas fa-times text-base"></i>
                                        </button>
                                    </div>

                                    <!-- Drawer Filters Body -->
                                    <div class="flex-1 overflow-y-auto w-full p-4 sm:p-5 space-y-5">
                                        <!-- Sort Section -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Sort Results By</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <label class="group relative flex items-center justify-center gap-2 p-3 min-h-[44px] rounded-xl border border-slate-200/80 bg-white hover:bg-slate-50 cursor-pointer transition-all has-checked:border-[#8B0000] has-checked:bg-red-50/50 has-checked:shadow-2xs">
                                                    <input type="radio" name="cert_sort" value="updated_at"
                                                           class="cert-filter-input sr-only"
                                                           {{ request('sort_by', 'updated_at') == 'updated_at' ? 'checked' : '' }}>
                                                    <i class="far fa-clock text-xs text-slate-400 group-has-checked:text-[#8B0000]"></i>
                                                    <span class="text-xs font-bold text-slate-700 group-has-checked:text-[#8B0000]">Last Updated</span>
                                                </label>
                                                <label class="group relative flex items-center justify-center gap-2 p-3 min-h-[44px] rounded-xl border border-slate-200/80 bg-white hover:bg-slate-50 cursor-pointer transition-all has-checked:border-[#8B0000] has-checked:bg-red-50/50 has-checked:shadow-2xs">
                                                    <input type="radio" name="cert_sort" value="Title"
                                                           class="cert-filter-input sr-only"
                                                           {{ request('sort_by') == 'Title' ? 'checked' : '' }}>
                                                    <i class="fas fa-font text-xs text-slate-400 group-has-checked:text-[#8B0000]"></i>
                                                    <span class="text-xs font-bold text-slate-700 group-has-checked:text-[#8B0000]">Title</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Review Type Section -->
                                        <div class="pt-4 border-t border-slate-100">
                                            <div class="flex items-center justify-between mb-2.5">
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Review Type</label>
                                                <span class="text-[11px] text-slate-400 font-medium">Select track</span>
                                            </div>
                                            <div class="grid grid-cols-3 gap-2">
                                                @php
                                                    $selectedTypes = request('review_types', []);
                                                    $certTypeList = [
                                                        'Exempt Review' => 'Exempt',
                                                        'Expedited Review' => 'Expedited',
                                                        'Full Board Review' => 'Full Board',
                                                    ];
                                                @endphp
                                                @foreach($certTypeList as $typeVal => $typeLabel)
                                                    <label class="group relative flex flex-col items-center justify-center p-2.5 min-h-[48px] rounded-xl border border-slate-200/80 bg-white hover:bg-slate-50 cursor-pointer transition-all text-center has-checked:border-[#8B0000] has-checked:bg-red-50/40 has-checked:shadow-2xs">
                                                        <input type="checkbox" name="cert_review_types[]" value="{{ $typeVal }}"
                                                               class="cert-filter-input sr-only"
                                                               {{ in_array($typeVal, $selectedTypes) ? 'checked' : '' }}>
                                                        <span class="text-xs font-bold text-slate-700 group-has-checked:text-[#8B0000] truncate w-full">{{ $typeLabel }}</span>
                                                        <span class="text-[10px] text-slate-400 font-medium group-has-checked:text-[#8B0000]/70">Review</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Sticky Bottom Actions -->
                                    <div class="p-4 sm:p-5 border-t border-slate-200/80 bg-slate-50/80 flex items-center gap-2.5 flex-none mt-auto sticky bottom-0 z-10">
                                        <button type="button" 
                                                id="reset_cert_filters_btn"
                                                class="flex-1 h-11 min-h-[44px] px-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 uppercase tracking-wider bg-white hover:bg-slate-100 transition-all cursor-pointer flex items-center justify-center gap-1.5 active:scale-[0.98]">
                                            <i class="fas fa-undo-alt text-xs text-slate-400"></i>
                                            <span>Reset All</span>
                                        </button>
                                        <button type="button" @click="filterDrawerOpen = false" 
                                                class="flex-1 h-11 min-h-[44px] px-3 rounded-xl bg-[#8B0000] hover:bg-[#6d0000] text-xs font-bold text-white uppercase tracking-wider transition-all cursor-pointer shadow-xs flex items-center justify-center gap-1.5 active:scale-[0.98]">
                                            <i class="fas fa-check text-xs"></i>
                                            <span>Done</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Navigation Tabs (Option A) --}}
        <div class="flex items-center gap-1 sm:gap-2 border-b border-slate-200">
            <button type="button"
                    data-tab-value="awaiting"
                    class="cert-tab-btn pb-3 px-3 text-xs sm:text-sm font-bold border-b-2 transition-all flex items-center gap-2 cursor-pointer {{ ($tab ?? 'awaiting') === 'awaiting' ? 'border-[#8B0000] text-[#8B0000]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                <span>Awaiting Certification</span>
                <span id="tab-badge-awaiting"
                      class="px-2 py-0.5 rounded-full text-xs font-bold tabular-nums {{ ($tab ?? 'awaiting') === 'awaiting' ? 'bg-[#8B0000]/10 text-[#8B0000]' : 'bg-slate-100 text-slate-600' }}">
                    {{ $awaitingCount ?? 0 }}
                </span>
            </button>
            <button type="button"
                    data-tab-value="certified"
                    class="cert-tab-btn pb-3 px-3 text-xs sm:text-sm font-bold border-b-2 transition-all flex items-center gap-2 cursor-pointer {{ ($tab ?? 'awaiting') === 'certified' ? 'border-[#8B0000] text-[#8B0000]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                <span>Certified Archive</span>
                <span id="tab-badge-certified"
                      class="px-2 py-0.5 rounded-full text-xs font-bold tabular-nums {{ ($tab ?? 'awaiting') === 'certified' ? 'bg-[#8B0000]/10 text-[#8B0000]' : 'bg-slate-100 text-slate-600' }}">
                    {{ $certifiedCount ?? 0 }}
                </span>
            </button>
        </div>

        {{-- Certifications Ledger Card Wrapper --}}
        <div id="certifications-wrapper" class="flex-1 flex flex-col min-h-[400px]">
            @include('admin.partials.active_certifications_list')
        </div>
        </div>
    </div>

    {{-- Modals & Drawer Partial Inclusions --}}
    @include('admin.partials.certification_action_drawer')
    @include('admin.partials.upload_certificate_modal')
    @include('admin.partials.view_certificates_modal')

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div id="certToast"
             class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 bg-emerald-700 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-semibold animate-[fadeInUp_0.4s_ease-out]">
            <i class="fas fa-check-circle text-emerald-200 text-base"></i>
            <span>{{ session('success') }}</span>
            <button type="button" onclick="document.getElementById('certToast').remove()"
                    class="ml-2 text-emerald-200 hover:text-white cursor-pointer" aria-label="Dismiss">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div id="certErrorToast"
             class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 bg-rose-700 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-semibold animate-[fadeInUp_0.4s_ease-out]">
            <i class="fas fa-exclamation-circle text-rose-200 text-base"></i>
            <span>{{ session('error') }}</span>
            <button type="button" onclick="document.getElementById('certErrorToast').remove()"
                    class="ml-2 text-rose-200 hover:text-white cursor-pointer" aria-label="Dismiss">&times;</button>
        </div>
    @endif

    {{-- Script for Step Backward & AJAX Filtering --}}
    <script>
        function confirmRevertPhase(id, title, currentStatus) {
            let revertText = "";
            let targetPhase = "";

            switch (currentStatus) {
                case 'Approved':
                    revertText = "Revert approval status? The protocol will return to Reviewed for deliberation.";
                    targetPhase = "Reviewed";
                    break;
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
                      `<span class="text-rose-600 font-bold">${revertText}</span><br>` +
                      `<span class="text-xs text-slate-500">Target Phase: ${targetPhase}</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Step Backward',
                cancelButtonText: 'Cancel',
                scrollbarPadding: false,
                backdrop: `rgba(15, 23, 42, 0.65)`,
                customClass: {
                    popup: 'rounded-2xl shadow-xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold shadow-lg shadow-red-900/20 cursor-pointer',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold cursor-pointer'
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

                    // Build and submit hidden dynamic form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/update-status') }}/${id}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    const classInput = document.createElement('input');
                    classInput.type = 'hidden';
                    classInput.name = 'classification';
                    classInput.value = 'Revert Phase';
                    form.appendChild(classInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('cert_search_input');
            let debounceTimer;
            let currentTab = new URLSearchParams(window.location.search).get('tab') || '{{ $tab ?? "awaiting" }}';

            const updateTabStyles = (active) => {
                document.querySelectorAll('.cert-tab-btn').forEach(btn => {
                    const val = btn.getAttribute('data-tab-value');
                    const badge = btn.querySelector('span:last-child');
                    if (val === active) {
                        btn.className = 'cert-tab-btn pb-3 px-3 text-xs sm:text-sm font-bold border-b-2 transition-all flex items-center gap-2 cursor-pointer border-[#8B0000] text-[#8B0000]';
                        if (badge) badge.className = 'px-2 py-0.5 rounded-full text-xs font-bold tabular-nums bg-[#8B0000]/10 text-[#8B0000]';
                    } else {
                        btn.className = 'cert-tab-btn pb-3 px-3 text-xs sm:text-sm font-bold border-b-2 transition-all flex items-center gap-2 cursor-pointer border-transparent text-slate-500 hover:text-slate-800';
                        if (badge) badge.className = 'px-2 py-0.5 rounded-full text-xs font-bold tabular-nums bg-slate-100 text-slate-600';
                    }
                });
            };

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
                        if (data.awaitingCount !== undefined) {
                            const badge = document.getElementById('tab-badge-awaiting');
                            if (badge) badge.textContent = data.awaitingCount;
                        }
                        if (data.certifiedCount !== undefined) {
                            const badge = document.getElementById('tab-badge-certified');
                            if (badge) badge.textContent = data.certifiedCount;
                        }
                        if (data.tab) {
                            currentTab = data.tab;
                            updateTabStyles(currentTab);
                        }
                        window.history.pushState({}, '', url);
                    })
                    .catch(error => console.error('Error fetching certifications:', error));
            };

            const triggerFetch = (resetPage = false) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const params = new URLSearchParams(window.location.search);

                    params.set('tab', currentTab);

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

            // Tab button clicks
            document.querySelectorAll('.cert-tab-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const selected = btn.getAttribute('data-tab-value');
                    if (selected === currentTab) return;
                    currentTab = selected;
                    updateTabStyles(currentTab);
                    triggerFetch(true);
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', () => triggerFetch(true));
            }

            document.querySelectorAll('.cert-filter-input').forEach(input => {
                input.addEventListener('change', () => triggerFetch(true));
            });

            const resetCertBtn = document.getElementById('reset_cert_filters_btn');
            if (resetCertBtn) {
                resetCertBtn.addEventListener('click', () => {
                    const defaultSort = document.querySelector('input[name="cert_sort"][value="updated_at"]');
                    if (defaultSort) defaultSort.checked = true;

                    document.querySelectorAll('input[name="cert_review_types[]"]').forEach(cb => {
                        cb.checked = false;
                    });

                    triggerFetch(true);
                });
            }

            // Pagination delegation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('.filter-pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const params = new URLSearchParams(url.search);
                    params.set('tab', currentTab);
                    fetchCertifications(params);
                }
            });

            // Browser back/forward navigation
            window.addEventListener('popstate', () => {
                const params = new URLSearchParams(window.location.search);
                currentTab = params.get('tab') || 'awaiting';
                updateTabStyles(currentTab);
                fetchCertifications(params);
            });
        });
    </script>
</x-admin_layout>