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
                    <span class="font-heading text-xl sm:text-2xl 2xl:text-3xl font-extrabold text-slate-900 tabular-nums tracking-tight whitespace-nowrap block truncate">₱ {{ number_format($totalRevenue, 2) }}</span>
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
                    <span class="font-heading text-xl sm:text-2xl 2xl:text-3xl font-extrabold text-slate-900 tabular-nums tracking-tight whitespace-nowrap block truncate">₱ {{ number_format($pendingRevenue ?? 0, 2) }}</span>
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
                    <span class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 tabular-nums tracking-tight whitespace-nowrap block">{{ number_format($totalCount ?? $submissions->total()) }}</span>
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
                    <span class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 tabular-nums tracking-tight whitespace-nowrap block">{{ $rate }}%</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">{{ $ver }} of {{ $tot }} verified</p>
                </div>
            </div>
        </div>

        <!-- Date & Audit Filter Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs">
            <form method="GET" action="{{ route('super_admin.revenue_logs') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                
                <!-- Left: Filter Controls -->
                <div class="flex flex-wrap items-center gap-3 flex-1 min-w-0">
                    
                    <!-- Month Filter -->
                    <div class="flex items-center gap-2">
                        <label for="filter_month" class="text-xs font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Month:</label>
                        <select id="filter_month" 
                                name="month" 
                                class="bg-slate-50/80 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 py-2.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] cursor-pointer">
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
                                class="bg-slate-50/80 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 py-2.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] cursor-pointer">
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
                               class="bg-slate-50/80 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] cursor-pointer">
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
                    
                    @if(request()->filled('month') || request()->filled('year') || request()->filled('exact_date'))
                        <a href="{{ route('super_admin.revenue_logs') }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider transition-colors min-h-[44px]"
                           aria-label="Reset date filters">
                            <i class="fas fa-undo text-[10px]" aria-hidden="true"></i>
                            <span>Reset</span>
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Revenue Logs Table Ledger -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            
            <!-- Desktop Tabular View (visible on lg: 1024px and wider) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">
                            <th scope="col" class="py-3.5 px-3 xl:px-4 font-bold whitespace-nowrap">Timestamp</th>
                            <th scope="col" class="py-3.5 px-3 xl:px-4 font-bold min-w-[170px]">Protocol & Title</th>
                            <th scope="col" class="py-3.5 px-3 xl:px-4 font-bold">Investigator</th>
                            <th scope="col" class="py-3.5 px-3 xl:px-4 font-bold">Category</th>
                            <th scope="col" class="py-3.5 px-3 xl:px-4 font-bold min-w-[170px]">Receipt & Audit</th>
                            <th scope="col" class="py-3.5 px-3 xl:px-4 font-bold text-right min-w-[120px]">Fee Logged</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-normal">
                        @forelse($submissions as $log)
                            <tr class="hover:bg-slate-50/60 transition-colors group">
                                
                                <!-- Timestamp -->
                                <td class="py-3.5 px-3 xl:px-4 align-top whitespace-nowrap">
                                    <div class="font-bold text-slate-900 text-sm tabular-nums">
                                        {{ $log->created_at ? $log->created_at->format('M d, Y') : 'N/A' }}
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5 tabular-nums">
                                        {{ $log->created_at ? $log->created_at->format('h:i A') : '' }}
                                    </div>
                                </td>

                                <!-- Protocol ID & Title -->
                                <td class="py-3.5 px-3 xl:px-4 align-top min-w-[170px]">
                                    <span class="inline-block font-mono text-[11px] font-bold text-slate-600 bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded-md mb-1.5 tabular-nums shadow-2xs">
                                        #{{ str_pad($log->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <p class="font-bold text-slate-900 text-sm leading-snug group-hover:text-[#8B0000] transition-colors line-clamp-2" 
                                       title="{{ $log->Study_Protocol_title }}">
                                        {{ $log->Study_Protocol_title }}
                                    </p>
                                </td>

                                <!-- Researcher -->
                                <td class="py-3.5 px-3 xl:px-4 align-top">
                                    @php
                                        $firstName = $log->researcher->user->first_name ?? $log->user->first_name ?? $log->Created_by ?? 'Researcher';
                                        $lastName = $log->researcher->user->last_name ?? $log->user->last_name ?? '';
                                        $fullName = trim($firstName . ' ' . $lastName);
                                        $initial = strtoupper(substr($firstName, 0, 1));
                                    @endphp
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-2xs">
                                            {{ $initial }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate" title="{{ $fullName }}">
                                                {{ $fullName }}
                                            </p>
                                            <p class="text-[11px] text-slate-400">Submitter</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Research Category -->
                                <td class="py-3.5 px-3 xl:px-4 align-top">
                                    <span class="inline-block text-[11px] font-semibold text-slate-700 bg-slate-50 border border-slate-200/80 px-2 py-0.5 rounded-md leading-tight">
                                        {{ $log->Research_Category ?? 'Standard' }}
                                    </span>
                                </td>

                                <!-- Official Receipt Verification Status (Zero Pill Slop) -->
                                <td class="py-3.5 px-3 xl:px-4 align-top min-w-[170px]">
                                    @if(empty($log->Official_Receipt_Number) && empty($log->or_file_path))
                                        <!-- Pending Payment -->
                                        <div class="space-y-1">
                                            <span class="text-xs font-bold uppercase tracking-wider text-amber-600 inline-flex items-center gap-1.5 whitespace-nowrap">
                                                <i class="fas fa-clock text-[10px]" aria-hidden="true"></i>
                                                <span>PENDING PAYMENT</span>
                                            </span>
                                            <div class="text-xs text-slate-400 font-medium whitespace-nowrap">Unpaid • No OR</div>
                                        </div>
                                    @elseif(!$log->is_or_verified)
                                        <!-- Pending Verification -->
                                        <div class="space-y-1">
                                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 inline-flex items-center gap-1.5 whitespace-nowrap">
                                                <i class="fas fa-hourglass-half text-[10px]" aria-hidden="true"></i>
                                                <span>PENDING VERIFICATION</span>
                                            </span>
                                            <div class="flex items-center justify-between gap-2 text-xs">
                                                <span class="font-mono text-slate-700 font-semibold tabular-nums truncate" title="{{ $log->Official_Receipt_Number ? 'OR: #' . $log->Official_Receipt_Number : 'OR: N/A' }}">
                                                    OR: <span class="font-bold text-slate-900">{{ $log->Official_Receipt_Number ? '#' . $log->Official_Receipt_Number : 'N/A' }}</span>
                                                </span>
                                                @if($log->or_file_path)
                                                    <button type="button" 
                                                            data-url="{{ asset($log->or_file_path) }}" 
                                                            data-title="{{ e($log->Study_Protocol_title) }}" 
                                                            data-or="{{ $log->Official_Receipt_Number }}"
                                                            @click="openReceiptModal($el.dataset.url, $el.dataset.title, $el.dataset.or)" 
                                                            class="receipt-preview-btn inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors cursor-pointer flex-shrink-0 whitespace-nowrap shadow-2xs"
                                                            title="Inspect Official Receipt"
                                                            aria-label="Inspect Official Receipt">
                                                        <i class="fas fa-search-plus text-[10px]" aria-hidden="true"></i>
                                                        <span>Preview</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <!-- Verified -->
                                        <div class="space-y-1">
                                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 inline-flex items-center gap-1.5 whitespace-nowrap">
                                                <i class="fas fa-check-circle text-[10px]" aria-hidden="true"></i>
                                                <span>VERIFIED</span>
                                            </span>
                                            <div class="flex items-center justify-between gap-2 text-xs">
                                                <span class="font-mono text-slate-700 font-semibold tabular-nums truncate" title="{{ $log->Official_Receipt_Number ? 'OR: #' . $log->Official_Receipt_Number : 'OR: N/A' }}">
                                                    OR: <span class="font-bold text-slate-900">{{ $log->Official_Receipt_Number ? '#' . $log->Official_Receipt_Number : 'N/A' }}</span>
                                                </span>
                                                @if($log->or_file_path)
                                                    <button type="button" 
                                                            data-url="{{ asset($log->or_file_path) }}" 
                                                            data-title="{{ e($log->Study_Protocol_title) }}" 
                                                            data-or="{{ $log->Official_Receipt_Number }}"
                                                            @click="openReceiptModal($el.dataset.url, $el.dataset.title, $el.dataset.or)" 
                                                            class="receipt-preview-btn inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-colors cursor-pointer flex-shrink-0 whitespace-nowrap shadow-2xs"
                                                            title="Inspect Official Receipt"
                                                            aria-label="Inspect Official Receipt">
                                                        <i class="fas fa-receipt text-[10px]" aria-hidden="true"></i>
                                                        <span>View OR</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <!-- Fee Logged Amount -->
                                <td class="py-3.5 px-3 xl:px-4 align-top text-right min-w-[120px] whitespace-nowrap">
                                    @if($log->is_or_verified)
                                        <div class="flex flex-col items-end whitespace-nowrap">
                                            <div class="font-heading text-base font-extrabold text-emerald-700 tabular-nums whitespace-nowrap leading-tight tracking-tight">
                                                + ₱ {{ number_format($log->category_fee_at_submission, 2) }}
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 mt-1 whitespace-nowrap block">
                                                COLLECTED
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-end whitespace-nowrap">
                                            <div class="font-heading text-sm font-semibold text-slate-400 tabular-nums line-through decoration-slate-300 whitespace-nowrap leading-tight">
                                                ₱ {{ number_format($log->category_fee_at_submission, 2) }}
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1 whitespace-nowrap block">
                                                AWAITING OR
                                            </span>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 px-6 text-center text-slate-400">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-file-invoice-dollar text-2xl" aria-hidden="true"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-800 font-heading">No revenue records found</h3>
                                    <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">
                                        No tariff-assessed submissions match the selected date filter range.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile & Tablet Adaptive Card View (visible below lg: 1024px) -->
            <div class="lg:hidden divide-y divide-slate-100">
                @forelse($submissions as $log)
                    <div class="p-5 space-y-3.5 hover:bg-slate-50/50 transition-colors">
                        
                        <!-- Card Header: Date & Status -->
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded tabular-nums">
                                    #{{ str_pad($log->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-xs text-slate-400 ml-2 tabular-nums">
                                    {{ $log->created_at ? $log->created_at->format('M d, Y • h:i A') : 'N/A' }}
                                </span>
                            </div>
                            <div>
                                @if(empty($log->Official_Receipt_Number) && empty($log->or_file_path))
                                    <span class="text-xs font-bold uppercase tracking-wider text-amber-600 inline-flex items-center gap-1">
                                        <i class="fas fa-clock text-[10px]" aria-hidden="true"></i>
                                        <span>UNPAID</span>
                                    </span>
                                @elseif(!$log->is_or_verified)
                                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 inline-flex items-center gap-1">
                                        <i class="fas fa-hourglass-half text-[10px]" aria-hidden="true"></i>
                                        <span>PENDING</span>
                                    </span>
                                @else
                                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 inline-flex items-center gap-1">
                                        <i class="fas fa-check-circle text-[10px]" aria-hidden="true"></i>
                                        <span>VERIFIED</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Protocol Title -->
                        <div>
                            <h2 class="font-bold text-slate-900 text-sm leading-snug" title="{{ $log->Study_Protocol_title }}">
                                {{ $log->Study_Protocol_title }}
                            </h2>
                            @php
                                $rName = trim(($log->researcher->user->first_name ?? $log->user->first_name ?? $log->Created_by ?? 'Researcher') . ' ' . ($log->researcher->user->last_name ?? $log->user->last_name ?? ''));
                            @endphp
                            <p class="text-xs text-slate-500 mt-1">
                                Submitter: <span class="font-semibold text-slate-700">{{ $rName }}</span>
                            </p>
                        </div>

                        <!-- Category & Details Grid -->
                        <div class="py-2.5 px-3 bg-slate-50/80 rounded-xl border border-slate-100 flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Category</span>
                                <span class="font-semibold text-slate-700 truncate block">{{ $log->Research_Category ?? 'Standard Protocol' }}</span>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Assessed Tariff</span>
                                @if($log->is_or_verified)
                                    <span class="font-heading text-sm font-extrabold text-emerald-700 tabular-nums">
                                        ₱ {{ number_format($log->category_fee_at_submission, 2) }}
                                    </span>
                                @else
                                    <span class="font-heading text-sm font-semibold text-slate-400 tabular-nums line-through">
                                        ₱ {{ number_format($log->category_fee_at_submission, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Receipt & Inspection Actions -->
                        <div class="flex items-center justify-between gap-2 pt-1">
                            <div class="text-xs text-slate-600 font-mono tabular-nums">
                                @if($log->Official_Receipt_Number)
                                    <span class="font-bold text-slate-700">OR: #{{ $log->Official_Receipt_Number }}</span>
                                @else
                                    <span class="text-slate-400 italic">No receipt number</span>
                                @endif
                            </div>

                            @if($log->or_file_path)
                                <button type="button" 
                                        data-url="{{ asset($log->or_file_path) }}" 
                                        data-title="{{ e($log->Study_Protocol_title) }}" 
                                        data-or="{{ $log->Official_Receipt_Number }}"
                                        @click="openReceiptModal($el.dataset.url, $el.dataset.title, $el.dataset.or)" 
                                        class="receipt-preview-btn inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors min-h-[44px] cursor-pointer"
                                        aria-label="Preview official receipt image">
                                    <i class="fas fa-receipt text-xs text-slate-500" aria-hidden="true"></i>
                                    <span>Preview Receipt</span>
                                </button>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="py-12 px-6 text-center text-slate-400">
                        <i class="fas fa-file-invoice-dollar text-3xl mb-2 text-slate-300"></i>
                        <p class="text-sm font-bold text-slate-700">No records found</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Footer -->
            @if($submissions->total() > 0)
                <div class="p-5 sm:p-6 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                    <div class="tabular-nums">
                        Showing <span class="font-bold text-slate-800">{{ $submissions->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-800">{{ $submissions->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800">{{ $submissions->total() }}</span> log records
                    </div>
                    <div class="flex items-center gap-2">
                        {{ $submissions->links() }}
                    </div>
                </div>
            @endif

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
</x-dynamic-component>