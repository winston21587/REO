<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Certifications</h1>
                <p class="text-slate-500 mt-2 text-sm">View approved protocols and manage clearance certificates.</p>
            </div>
            <div class="flex gap-4" x-data="{ expanded: sessionStorage.getItem('certFilterExpanded') === 'true' }" x-init="$watch('expanded', value => sessionStorage.setItem('certFilterExpanded', value))">
                <div class="relative flex-1">
                    <input type="text" name="search" id="cert_search_input" value="{{ request('search') }}"
                        placeholder="Search approved protocols..."
                        class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-64 shadow-sm bg-white">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                
                <div class="relative">
                    <button type="button" @click="expanded = !expanded" @click.outside="expanded = false"
                        class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm transition-colors justify-between w-[120px]">
                        <span><i class="fas fa-filter mr-1 text-slate-400"></i> Filter</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Advanced Dropdown -->
                    <div x-show="expanded" x-cloak x-transition.opacity.duration.200ms @click.stop
                        class="absolute right-0 mt-2 w-64 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                        
                        <!-- Sort Section -->
                        <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Sort By</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="cert_sort" value="updated_at" class="cert-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('sort_by', 'updated_at') == 'updated_at' ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Last Updated</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="cert_sort" value="Title" class="cert-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('sort_by') == 'Title' ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Title</span>
                                </label>
                            </div>
                        </div>

                        <!-- Review Type Section -->
                        <div class="p-3">
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Review Type</label>
                            <div class="space-y-2">
                                @php $selectedTypes = request('review_types', []); @endphp
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="cert_review_types[]" value="Exempt Review" class="cert-filter-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Exempt Review', $selectedTypes) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Exempt</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="cert_review_types[]" value="Expedited Review" class="cert-filter-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Expedited Review', $selectedTypes) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Expedited</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="cert_review_types[]" value="Full Board Review" class="cert-filter-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Full Board Review', $selectedTypes) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Full Board</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
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
    <div id="certToast" class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-medium animate-[fadeInUp_0.4s_ease-out]">
        <i class="fas fa-check-circle text-emerald-200"></i>
        {{ session('success') }}
        <button onclick="document.getElementById('certToast').remove()" class="ml-2 text-emerald-200 hover:text-white">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div id="certErrorToast" class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 bg-red-600 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-medium animate-[fadeInUp_0.4s_ease-out]">
        <i class="fas fa-exclamation-circle text-red-200"></i>
        {{ session('error') }}
        <button onclick="document.getElementById('certErrorToast').remove()" class="ml-2 text-red-200 hover:text-white">&times;</button>
    </div>
    @endif

    <script>
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
