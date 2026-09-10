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
                    <span class="inline-block font-mono text-[11px] font-bold text-slate-600 bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded-md tabular-nums mb-1">
                        #{{ str_pad($log->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <div class="text-xs text-slate-500 font-medium tabular-nums">
                        {{ $log->created_at ? $log->created_at->format('M d, Y • h:i A') : 'N/A' }}
                    </div>
                </div>

                <!-- Verification Status Indicator -->
                <div>
                    @if(empty($log->Official_Receipt_Number) && empty($log->or_file_path))
                        <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 inline-flex items-center gap-1">
                            <i class="fas fa-clock text-[10px]" aria-hidden="true"></i>
                            <span>PENDING PAYMENT</span>
                        </span>
                    @elseif(!$log->is_or_verified)
                        <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 inline-flex items-center gap-1">
                            <i class="fas fa-hourglass-half text-[10px]" aria-hidden="true"></i>
                            <span>PENDING VERIFICATION</span>
                        </span>
                    @else
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 inline-flex items-center gap-1">
                            <i class="fas fa-check-circle text-[10px]" aria-hidden="true"></i>
                            <span>VERIFIED</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Protocol Title -->
            <h3 class="font-bold text-slate-900 text-sm leading-snug">
                {{ $log->Study_Protocol_title }}
            </h3>

            <!-- Researcher & Category Metadata -->
            <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400">Investigator:</span>
                    <span class="font-bold text-slate-700 ml-1">
                        {{ trim(($log->researcher->user->first_name ?? $log->user->first_name ?? '') . ' ' . ($log->researcher->user->last_name ?? $log->user->last_name ?? '')) ?: ($log->Created_by ?? 'Researcher') }}
                    </span>
                </div>
                <div>
                    <span class="inline-block text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                        {{ $log->Research_Category ?? 'Standard' }}
                    </span>
                </div>
            </div>

            <!-- Tariff & Action Footer -->
            <div class="flex items-center justify-between gap-3 pt-3 border-t border-slate-100 bg-slate-50/50 -mx-5 -mb-5 p-4 rounded-b-2xl">
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Assessed Fee</div>
                    <div class="font-heading text-base font-extrabold {{ $log->is_or_verified ? 'text-emerald-700' : 'text-slate-500' }} tabular-nums whitespace-nowrap">
                        {{ $log->is_or_verified ? '+' : '' }} ₱ {{ number_format($log->category_fee_at_submission, 2) }}
                    </div>
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
        <div class="flex items-center gap-2 filter-pagination">
            {{ $submissions->links() }}
        </div>
    </div>
@endif
