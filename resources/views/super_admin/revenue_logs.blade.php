<x-dynamic-component :component="auth()->user()?->role === 'super_admin' ? 'super_admin_layout' : 'admin_layout'" title="Revenue Logs">
    <div id="revenue-logs-ledger"
         x-data="{
        showReceiptModal: false,
        previewReceiptUrl: '',
        previewReceiptTitle: '',
        previewReceiptNumber: '',

        openReceiptModal(url, title, orNum) {
            this.previewReceiptUrl = url || '';
            this.previewReceiptTitle = title || 'Official Receipt';
            this.previewReceiptNumber = orNum || 'N/A';
            this.showReceiptModal = true;
        },

        closeReceiptModal() {
            this.showReceiptModal = false;
        },

        isPdf(url) {
            return url && url.toLowerCase().includes('.pdf');
        }
    }" 
    @keydown.escape.window="closeReceiptModal()"
    @open-receipt.window="openReceiptModal($event.detail.url, $event.detail.title, $event.detail.orNum)"
    class="w-full max-w-7xl mx-auto space-y-8 min-w-0 pb-12">

        <!-- Executive Header & Quick Navigation -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pb-6 border-b border-slate-200">
            <div class="min-w-0">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">Revenue Logs</h1>
                <p class="text-slate-500 mt-1 text-sm max-w-2xl">
                    Institutional tariff collection audit, official receipt tracking, and research fee accumulation registry.
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('super_admin.manage_fees') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors shadow-xs min-h-[44px]">
                    <i class="fas fa-tags text-xs text-slate-500" aria-hidden="true"></i>
                    <span>Manage Fee Schedule</span>
                </a>
            </div>
        </div>

        <!-- Feedback Alerts -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-start gap-3 text-emerald-800 text-sm">
                <i class="fas fa-check-circle text-emerald-600 mt-0.5" aria-hidden="true"></i>
                <div class="flex-1 font-medium">{{ session('success') }}</div>
            </div>
        @endif

        <!-- Executive Metrics Ribbon (4 Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Realized Revenue -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Realized Revenue</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <i class="fas fa-coins text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span id="metric-total-revenue" class="font-heading text-xl sm:text-2xl 2xl:text-3xl font-extrabold text-slate-900 tabular-nums tracking-tight whitespace-nowrap block truncate">₱ {{ number_format($totalRevenue, 2) }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Verified official receipts</p>
                </div>
            </div>

            <!-- Card 2: Pending Clearance -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pending Pipeline</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                        <i class="fas fa-clock text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span id="metric-pending-revenue" class="font-heading text-xl sm:text-2xl 2xl:text-3xl font-extrabold text-slate-900 tabular-nums tracking-tight whitespace-nowrap block truncate">₱ {{ number_format($pendingRevenue ?? 0, 2) }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Awaiting receipt verification</p>
                </div>
            </div>

            <!-- Card 3: Assessed Protocols -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Assessed Protocols</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class="fas fa-file-invoice-dollar text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span id="metric-total-count" class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 tabular-nums tracking-tight whitespace-nowrap block">{{ number_format($totalCount ?? $submissions->total()) }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Fee-assessed submissions</p>
                </div>
            </div>

            <!-- Card 4: Verification Clearance Rate -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Clearance Rate</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 flex-shrink-0">
                        <i class="fas fa-check-double text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    @php
                        $tot = $totalCount ?? $submissions->total();
                        $ver = $verifiedCount ?? 0;
                        $rate = $tot > 0 ? number_format(($ver / $tot) * 100, 1) : '0.0';
                    @endphp
                    <span id="metric-clearance-rate" class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 tabular-nums tracking-tight whitespace-nowrap block">{{ $rate }}%</span>
                    <p id="metric-clearance-sub" class="text-xs text-slate-500 mt-1 font-medium">{{ $ver }} of {{ $tot }} verified</p>
                </div>
            </div>
        </div>

        <!-- Date & Audit Filter Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
            <form id="revenue-logs-filter-form" method="GET" action="{{ route('super_admin.revenue_logs') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                
                <!-- Left: Filter Controls -->
                <div class="flex flex-wrap items-center gap-3 flex-1 min-w-0">
                    
                    <!-- Month Filter -->
                    <div class="flex items-center gap-2">
                        <label for="filter_month" class="text-xs font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Month:</label>
                        <select id="filter_month" 
                                name="month" 
                                class="revenue-filter-input bg-slate-50/80 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 py-2.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] cursor-pointer">
                            <option value="">All Months</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div class="flex items-center gap-2">
                        <label for="filter_year" class="text-xs font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Year:</label>
                        <select id="filter_year" 
                                name="year" 
                                class="revenue-filter-input bg-slate-50/80 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 py-2.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] cursor-pointer">
                            <option value="">All Years</option>
                            @foreach(range(date('Y') - 5, date('Y')) as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Exact Date Picker -->
                    <div class="flex items-center gap-2">
                        <label for="filter_exact_date" class="text-xs font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Date:</label>
                        <input type="date" 
                               id="filter_exact_date" 
                               name="exact_date" 
                               value="{{ request('exact_date') }}" 
                               class="revenue-filter-input bg-slate-50/80 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] cursor-pointer">
                    </div>

                </div>

                <!-- Right: Action Buttons -->
                <div class="flex items-center gap-2 self-start lg:self-center flex-shrink-0">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#8B0000] hover:bg-[#6d0000] text-white text-xs font-bold uppercase tracking-wider transition-all shadow-xs min-h-[44px] cursor-pointer"
                            aria-label="Apply filters">
                        <i class="fas fa-filter text-[10px]" aria-hidden="true"></i>
                        <span>Apply Filter</span>
                    </button>
                    
                    <a id="revenue-filter-reset" 
                       href="{{ route('super_admin.revenue_logs') }}" 
                       class="{{ (request()->filled('month') || request()->filled('year') || request()->filled('exact_date')) ? '' : 'hidden' }} inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider transition-colors min-h-[44px]"
                       aria-label="Reset date filters">
                        <i class="fas fa-undo text-[10px]" aria-hidden="true"></i>
                        <span>Reset</span>
                    </a>
                </div>

            </form>
        </div>

        <!-- Revenue Logs Table Ledger Wrapper -->
        <div id="revenue-logs-table-wrapper" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden transition-opacity duration-150">
            @include('super_admin.partials.revenue_logs_table')
        </div>

        <!-- ============================================== -->
        <!-- MODAL: Official Receipt Image Preview Modal    -->
        <!-- ============================================== -->
        <div id="receipt-preview-modal"
             x-show="showReceiptModal" 
             style="display: none"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="receipt-modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Frosted Scrim Backdrop -->
            <div x-show="showReceiptModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="closeReceiptModal()"></div>

            <!-- Modal Panel Centering -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div x-show="showReceiptModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-2xl border border-slate-200 flex flex-col max-h-[90vh] overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/50 flex-shrink-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-receipt text-sm" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg font-bold text-slate-900 font-heading truncate" id="receipt-modal-title">
                                    Official Receipt: <span class="font-mono" x-text="previewReceiptNumber ? '#' + previewReceiptNumber : 'Audit File'"></span>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5 truncate" x-text="previewReceiptTitle"></p>
                            </div>
                        </div>
                        <button type="button" 
                                @click="closeReceiptModal()" 
                                class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer flex-shrink-0"
                                aria-label="Close receipt dialog">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Modal Body: Document / Scan Preview Canvas -->
                    <div class="p-6 bg-slate-100/70 flex-1 overflow-y-auto flex items-center justify-center min-h-[360px]">
                        <template x-if="isPdf(previewReceiptUrl)">
                            <div class="w-full h-[60vh] rounded-xl overflow-hidden border border-slate-200 bg-white shadow-xs">
                                <iframe :src="previewReceiptUrl" class="w-full h-full" frameborder="0"></iframe>
                            </div>
                        </template>
                        <template x-if="!isPdf(previewReceiptUrl)">
                            <img :src="previewReceiptUrl" 
                                 alt="Official Receipt Document Scan" 
                                 class="max-w-full max-h-[60vh] object-contain rounded-xl shadow-md border border-slate-200 bg-white"
                                 x-on:error="$event.target.src = '{{ asset('images/reoc-nobg.png') }}'">
                        </template>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-between items-center gap-3 flex-shrink-0">
                        <span class="text-xs text-slate-500 font-medium">Verified Official Receipt Audit Record</span>
                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <button type="button" 
                                    @click="closeReceiptModal()" 
                                    class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 font-semibold text-xs hover:bg-slate-50 transition-colors min-h-[44px] cursor-pointer">
                                Close Preview
                            </button>
                            <a :href="previewReceiptUrl" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-[#8B0000] hover:bg-[#6d0000] text-white font-semibold text-xs shadow-sm transition-all min-h-[44px]">
                                <i class="fas fa-external-link-alt text-[10px]" aria-hidden="true"></i>
                                <span>Open Full Resolution</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Asynchronous Pagination & Filtering Delegation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('revenue-logs-filter-form');
            const wrapper = document.getElementById('revenue-logs-table-wrapper');

            const fetchRevenueLogs = function(urlOrParams) {
                let url;
                if (typeof urlOrParams === 'string') {
                    url = urlOrParams;
                } else {
                    url = `{{ route('super_admin.revenue_logs') }}?${urlOrParams.toString()}`;
                }

                if (wrapper) wrapper.style.opacity = '0.5';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (wrapper && data.html) {
                        wrapper.innerHTML = data.html;
                    }

                    // Update Executive Metric Cards
                    if (data.totalRevenue) {
                        const el = document.getElementById('metric-total-revenue');
                        if (el) el.textContent = '₱ ' + data.totalRevenue;
                    }
                    if (data.pendingRevenue) {
                        const el = document.getElementById('metric-pending-revenue');
                        if (el) el.textContent = '₱ ' + data.pendingRevenue;
                    }
                    if (data.totalCount !== undefined) {
                        const el = document.getElementById('metric-total-count');
                        if (el) el.textContent = data.totalCount;
                    }
                    if (data.clearanceRate !== undefined) {
                        const el = document.getElementById('metric-clearance-rate');
                        if (el) el.textContent = data.clearanceRate + '%';
                        const sub = document.getElementById('metric-clearance-sub');
                        if (sub && data.verifiedCount !== undefined) {
                            sub.textContent = `${data.verifiedCount} of ${data.totalCount} verified`;
                        }
                    }

                    // Toggle Reset Button
                    updateResetButton(url);

                    // Update browser history
                    window.history.pushState({}, '', url);
                })
                .catch(function(err) {
                    console.error('Error fetching revenue logs:', err);
                })
                .finally(function() {
                    if (wrapper) wrapper.style.opacity = '1';
                });
            };

            function updateResetButton(urlStr) {
                try {
                    const parsed = new URL(urlStr, window.location.origin);
                    const hasFilters = parsed.searchParams.get('month') || 
                                       parsed.searchParams.get('year') || 
                                       parsed.searchParams.get('exact_date');
                    const resetBtn = document.getElementById('revenue-filter-reset');
                    if (resetBtn) {
                        if (hasFilters) {
                            resetBtn.classList.remove('hidden');
                        } else {
                            resetBtn.classList.add('hidden');
                        }
                    }
                } catch(e) {}
            }

            // Form Submit Interception
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    fetchRevenueLogs(new URLSearchParams(formData));
                });
            }

            // Filter Inputs Auto-Submit on Change
            document.querySelectorAll('.revenue-filter-input').forEach(function(input) {
                input.addEventListener('change', function() {
                    if (form) {
                        const formData = new FormData(form);
                        fetchRevenueLogs(new URLSearchParams(formData));
                    }
                });
            });

            // Reset Button Delegation
            const resetBtn = document.getElementById('revenue-filter-reset');
            if (resetBtn) {
                resetBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (form) form.reset();
                    fetchRevenueLogs('{{ route('super_admin.revenue_logs') }}');
                });
            }

            // Pagination Link Delegation
            document.addEventListener('click', function(e) {
                const link = e.target.closest('#revenue-logs-table-wrapper nav a') || e.target.closest('.filter-pagination a');
                if (link && link.href) {
                    e.preventDefault();
                    fetchRevenueLogs(link.href);
                    return;
                }

                // Official Receipt Preview Button Delegation
                const receiptBtn = e.target.closest('.receipt-preview-btn');
                if (receiptBtn && receiptBtn.dataset.url) {
                    window.dispatchEvent(new CustomEvent('open-receipt', {
                        detail: {
                            url: receiptBtn.dataset.url,
                            title: receiptBtn.dataset.title,
                            orNum: receiptBtn.dataset.or
                        }
                    }));
                }
            });

            // Browser Back/Forward navigation
            window.addEventListener('popstate', function() {
                fetchRevenueLogs(window.location.href);
            });
        });
    </script>
</x-dynamic-component>