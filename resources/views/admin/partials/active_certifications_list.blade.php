<div class="flex flex-col flex-1 h-full min-h-[400px] lg:bg-white lg:rounded-2xl lg:shadow-xs lg:border lg:border-slate-200/80">
    @if($datas->count() > 0)
        {{-- =========================================================
             MOBILE & TABLET VIEWPORT: Adaptive Cards (block lg:hidden)
             ========================================================= --}}
        <div class="block lg:hidden space-y-3.5 flex-grow">
            @foreach($datas as $data)
                @php
                    $certificate = $data->adminFiles->firstWhere('filetype', 'certificate');
                    $approvalLetter = $data->adminFiles->firstWhere('filetype', 'Approval Letter');
                    $hasCerts = ($certificate && $approvalLetter);
                    $letterUrl = $approvalLetter ? route('admin.serve_file', $approvalLetter->id) : '';
                    $certUrl = $certificate ? route('admin.serve_file', $certificate->id) : '';
                    $researcherName = trim(($data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'Unknown') . ' ' . ($data->researcher->user->last_name ?? $data->user->last_name ?? ''));
                    $researcherEmail = $data->researcher->user->email ?? $data->user->email ?? '';
                    $approvalDateFormatted = $data->updated_at ? $data->updated_at->format('M d, Y') : 'Not Provided';

                    $typeBadgeConfig = [
                        'Exempt Review' => [
                            'text' => 'text-emerald-700',
                            'icon' => 'fa-check-circle text-emerald-600',
                            'label' => 'Exempt Review'
                        ],
                        'Expedited Review' => [
                            'text' => 'text-blue-700',
                            'icon' => 'fa-bolt text-blue-600',
                            'label' => 'Expedited Review'
                        ],
                        'Full Board Review' => [
                            'text' => 'text-amber-800',
                            'icon' => 'fa-users text-amber-700',
                            'label' => 'Full Board Review'
                        ]
                    ];
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 sm:p-5 space-y-3 hover:border-slate-300 hover:shadow-sm transition-all">
                    <!-- Top Metadata: REOC Code + Classification + Date -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 flex-wrap min-w-0">
                            <span class="text-[11px] font-mono font-semibold tabular-nums text-slate-600 bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded-md shrink-0">
                                {{ $data->reoc_code ?: ('#' . str_pad($data->id, 5, '0', STR_PAD_LEFT)) }}
                            </span>
                            @if(isset($typeBadgeConfig[$data->Review_Type]))
                                <span class="text-xs font-bold uppercase tracking-wider {{ $typeBadgeConfig[$data->Review_Type]['text'] }} truncate">
                                    {{ $data->Review_Type }}
                                </span>
                            @elseif($data->Review_Type === 'N/A')
                                <span class="text-xs font-semibold text-slate-400 italic truncate">N/A</span>
                            @else
                                <span class="text-xs font-semibold text-slate-400 italic truncate">Unassigned</span>
                            @endif
                        </div>
                        <span class="text-xs text-slate-400 font-medium tabular-nums flex items-center gap-1.5 shrink-0">
                            <i class="far fa-calendar-alt text-slate-400 text-[11px]" aria-hidden="true"></i>{{ $approvalDateFormatted }}
                        </span>
                    </div>

                    <!-- Title -->
                    <a href="{{ route('admin.view_files', $data->id) }}"
                       class="font-bold text-slate-900 text-sm sm:text-base leading-snug line-clamp-2 hover:text-[#8B0000] transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000] rounded-xs block"
                       title="{{ $data->Study_Protocol_title }}">
                        {{ $data->Study_Protocol_title }}
                    </a>

                    <!-- Researcher Info -->
                    <div class="flex items-center gap-2 text-xs text-slate-600 min-w-0">
                        <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200/80 flex items-center justify-center text-[10px] font-bold text-slate-700 uppercase shrink-0">
                            {{ substr($researcherName ?: 'U', 0, 1) }}
                        </div>
                        <span class="font-semibold text-slate-800 truncate">
                            {{ $researcherName }}
                        </span>
                        @if($researcherEmail)
                            <span class="text-slate-300 shrink-0">•</span>
                            <span class="text-slate-500 truncate text-[11px] font-medium">{{ $researcherEmail }}</span>
                        @endif
                    </div>

                    <!-- Unified Operational Details: Status & Approval -->
                    <div class="pt-3 border-t border-slate-100 space-y-2.5">
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="min-w-0">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Status</span>
                                @if($hasCerts)
                                    <button type="button"
                                            onclick="openViewCertificatesModal('{{ $letterUrl }}', '{{ $certUrl }}', '{{ $data->reoc_code ?: ('#' . str_pad($data->id, 5, '0', STR_PAD_LEFT)) }}', {{ json_encode($data->Study_Protocol_title) }}, {{ json_encode($researcherName) }})"
                                            class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700 hover:text-emerald-800 transition-colors cursor-pointer group"
                                            title="Click to view certified documents">
                                        <span class="truncate">Certified</span>
                                        <i class="fas fa-external-link-alt text-[10px] text-emerald-600 shrink-0 group-hover:translate-x-0.5 transition-transform" aria-hidden="true"></i>
                                    </button>
                                @else
                                    <span class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-amber-800 truncate">
                                        Ready to Issue
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Approval Date</span>
                                <div class="text-xs font-semibold text-slate-700 truncate">
                                    {{ $approvalDateFormatted }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tactile Mobile Action Bar (Thumb-friendly 44px buttons) -->
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-2.5">
                        <a href="{{ route('admin.view_files', $data->id) }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 h-11 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-[#8B0000] text-xs font-bold border border-slate-200/80 transition-all active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                           title="View Protocol Documents"
                           aria-label="View documents for {{ $data->Study_Protocol_title }}">
                            <i class="fas fa-folder-open text-xs text-slate-400" aria-hidden="true"></i>
                            <span>View Files</span>
                        </a>
                        <button type="button" 
                                @click="$dispatch('open-cert-drawer', {
                                    id: '{{ $data->id }}',
                                    code: '{{ $data->reoc_code ?: ('#' . str_pad($data->id, 5, '0', STR_PAD_LEFT)) }}',
                                    title: {{ json_encode($data->Study_Protocol_title) }},
                                    researcher_name: {{ json_encode($researcherName) }},
                                    approval_date: '{{ $approvalDateFormatted }}',
                                    status: '{{ $data->Status }}',
                                    has_certificates: {{ $hasCerts ? 'true' : 'false' }},
                                    approval_letter_url: '{{ $letterUrl }}',
                                    certificate_url: '{{ $certUrl }}',
                                    view_files_url: '{{ route('admin.view_files', $data->id) }}',
                                    generate_page_url: '{{ route('admin.certificate.generate_page', $data->id) }}'
                                })"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 h-11 px-3 rounded-xl bg-[#8B0000] hover:bg-[#6d0000] text-white text-xs font-bold shadow-xs active:scale-[0.98] transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                                title="Open Certification Actions Drawer"
                                aria-label="Open action drawer for {{ $data->Study_Protocol_title }}">
                            <i class="fas fa-sliders-h text-xs" aria-hidden="true"></i>
                            <span>Actions</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- =========================================================
             DESKTOP VIEWPORT: Academic Ledger Table (lg:block, >= 1024px)
             ========================================================= --}}
        <div class="hidden lg:block overflow-x-auto flex-grow overflow-y-visible">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-4 py-3.5 min-w-[280px]">Research Title</th>
                        <th class="px-3 py-3.5 min-w-[180px] w-56">Researcher</th>
                        <th class="px-3 py-3.5 min-w-[130px] w-36 whitespace-nowrap">Approval Date</th>
                        <th class="px-3 py-3.5 min-w-[140px] w-40 whitespace-nowrap">Review Type</th>
                        <th class="px-3 py-3.5 min-w-[140px] w-44 whitespace-nowrap">Status</th>
                        <th class="px-3 py-3.5 w-12 text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($datas as $data)
                        @php
                            $certificate = $data->adminFiles->firstWhere('filetype', 'certificate');
                            $approvalLetter = $data->adminFiles->firstWhere('filetype', 'Approval Letter');
                            $hasCerts = ($certificate && $approvalLetter);
                            $letterUrl = $approvalLetter ? route('admin.serve_file', $approvalLetter->id) : '';
                            $certUrl = $certificate ? route('admin.serve_file', $certificate->id) : '';
                            $researcherName = trim(($data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'Unknown') . ' ' . ($data->researcher->user->last_name ?? $data->user->last_name ?? ''));
                            $researcherEmail = $data->researcher->user->email ?? $data->user->email ?? '';
                            $approvalDateFormatted = $data->updated_at ? $data->updated_at->format('M d, Y') : 'Not Provided';

                            $typeBadgeConfig = [
                                'Exempt Review' => [
                                    'text' => 'text-emerald-700',
                                    'icon' => 'fa-check-circle text-emerald-600',
                                    'label' => 'Exempt Review'
                                ],
                                'Expedited Review' => [
                                    'text' => 'text-blue-700',
                                    'icon' => 'fa-bolt text-blue-600',
                                    'label' => 'Expedited Review'
                                ],
                                'Full Board Review' => [
                                    'text' => 'text-amber-800',
                                    'icon' => 'fa-users text-amber-700',
                                    'label' => 'Full Board Review'
                                ]
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            {{-- Research Title --}}
                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="text-[11px] font-mono font-semibold tabular-nums text-slate-600 bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded-md">
                                        {{ $data->reoc_code ?: ('#' . str_pad($data->id, 5, '0', STR_PAD_LEFT)) }}
                                    </span>
                                </div>
                                <a href="{{ route('admin.view_files', $data->id) }}"
                                   class="font-semibold text-slate-900 text-sm line-clamp-2 leading-snug group-hover:text-[#8B0000] transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000] rounded-sm"
                                   title="{{ $data->Study_Protocol_title }}">
                                    {{ $data->Study_Protocol_title }}
                                </a>
                            </td>

                            {{-- Researcher --}}
                            <td class="px-3 py-3 align-middle">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200/80 flex items-center justify-center text-xs font-bold text-slate-700 uppercase shrink-0">
                                        {{ substr($researcherName ?: 'U', 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-800 truncate">
                                            {{ $researcherName }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 font-medium truncate">
                                            {{ $researcherEmail ?: 'Not Provided' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Approval Date --}}
                            <td class="px-3 py-3 align-middle whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600 tabular-nums">
                                    <i class="far fa-calendar-alt text-slate-400 text-[11px]" aria-hidden="true"></i>
                                    <span>{{ $approvalDateFormatted }}</span>
                                </div>
                            </td>

                            {{-- Review Type --}}
                            <td class="px-3 py-3 align-middle whitespace-nowrap">
                                @if(isset($typeBadgeConfig[$data->Review_Type]))
                                    @php $tConf = $typeBadgeConfig[$data->Review_Type]; @endphp
                                    <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap {{ $tConf['text'] }}" title="Official Review Type">
                                        {{ $tConf['label'] }}
                                    </span>
                                @elseif($data->Review_Type === 'N/A')
                                    <span class="inline-flex items-center text-xs font-semibold text-slate-500 italic">N/A</span>
                                @else
                                    <span class="inline-flex items-center text-xs font-semibold text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>

                            {{-- Status with Dual-Coding --}}
                            <td class="px-3 py-3 align-middle whitespace-nowrap">
                                @if($hasCerts)
                                    <button type="button"
                                            onclick="openViewCertificatesModal('{{ $letterUrl }}', '{{ $certUrl }}', '{{ $data->reoc_code ?: ('#' . str_pad($data->id, 5, '0', STR_PAD_LEFT)) }}', {{ json_encode($data->Study_Protocol_title) }}, {{ json_encode($researcherName) }})"
                                            class="inline-flex items-center gap-1 text-xs font-semibold whitespace-nowrap text-emerald-700 hover:text-emerald-800 transition-colors cursor-pointer group"
                                            title="Click to view certified documents">
                                        <span>Certified</span>
                                        <i class="fas fa-external-link-alt text-[10px] text-emerald-600 ml-0.5 group-hover:translate-x-0.5 transition-transform" aria-hidden="true"></i>
                                    </button>
                                @else
                                    <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap text-amber-800">
                                        <span>Ready to Issue</span>
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-3 py-3 text-right align-middle pr-4 relative">
                                <button type="button" 
                                        @click="$dispatch('open-cert-drawer', {
                                            id: '{{ $data->id }}',
                                            code: '{{ $data->reoc_code ?: ('#' . str_pad($data->id, 5, '0', STR_PAD_LEFT)) }}',
                                            title: {{ json_encode($data->Study_Protocol_title) }},
                                            researcher_name: {{ json_encode($researcherName) }},
                                            approval_date: '{{ $approvalDateFormatted }}',
                                            status: '{{ $data->Status }}',
                                            has_certificates: {{ $hasCerts ? 'true' : 'false' }},
                                            approval_letter_url: '{{ $letterUrl }}',
                                            certificate_url: '{{ $certUrl }}',
                                            view_files_url: '{{ route('admin.view_files', $data->id) }}',
                                            generate_page_url: '{{ route('admin.certificate.generate_page', $data->id) }}'
                                        })"
                                        class="w-9 h-9 min-w-[38px] min-h-[38px] flex items-center justify-center text-slate-500 hover:text-[#8B0000] hover:bg-slate-100 rounded-xl transition-all ml-auto touch-manipulation cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                                        title="Certification Actions"
                                        aria-label="Open action drawer for {{ $data->Study_Protocol_title }}">
                                    <i class="fas fa-ellipsis-v text-xs" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs lg:border-0 lg:shadow-none p-12 text-center text-slate-500 flex-grow flex flex-col items-center justify-center">
            @if(request()->anyFilled(['search', 'review_types', 'status', 'date_from', 'date_to', 'sort_by']))
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                    <i class="fas fa-search text-xl" aria-hidden="true"></i>
                </div>
                <h4 class="text-sm font-semibold text-slate-900 mb-1">No matching submissions found</h4>
                <p class="text-xs text-slate-500 max-w-sm mb-4 leading-relaxed">No approved submissions match your current search or filter criteria. Try adjusting your parameters or clear all filters.</p>
                <a href="{{ route('admin.certifications') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200/80 transition-colors shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                    <i class="fas fa-times-circle text-slate-400" aria-hidden="true"></i> Clear All Filters
                </a>
            @else
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                    <i class="{{ ($tab ?? 'awaiting') === 'certified' ? 'fas fa-certificate' : 'fas fa-folder-open' }} text-xl" aria-hidden="true"></i>
                </div>
                <h4 class="text-sm font-semibold text-slate-900 mb-1">
                    {{ ($tab ?? 'awaiting') === 'certified' ? 'No Certified Records Found' : 'No Submissions Awaiting Certification' }}
                </h4>
                <p class="text-xs text-slate-500 max-w-sm leading-relaxed">
                    {{ ($tab ?? 'awaiting') === 'certified' ? 'There are currently no completed certificates in the archive.' : 'There are currently no approved submissions awaiting certificate issuance.' }}
                </p>
            @endif
        </div>
    @endif

    {{-- =========================================================
         PAGINATION FOOTER (Matching Active Protocols)
         ========================================================= --}}
    @if($datas->total() > 0)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs lg:bg-transparent lg:border-0 lg:border-t lg:border-slate-100 lg:shadow-none lg:rounded-none p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500 mt-3.5 lg:mt-auto shrink-0">
            <div class="text-center sm:text-left">
                Showing <span class="font-semibold text-slate-800 tabular-nums">{{ $datas->firstItem() ?? 0 }}</span> to <span
                    class="font-semibold text-slate-800 tabular-nums">{{ $datas->lastItem() ?? 0 }}</span> of <span
                    class="font-semibold text-slate-800 tabular-nums">{{ $datas->total() }}</span>
            </div>
            <div class="flex gap-2 filter-pagination">
                <!-- Previous Page Link -->
                @if ($datas->onFirstPage())
                    <span class="opacity-50 cursor-not-allowed text-slate-400 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs"><i class="fas fa-chevron-left text-xs" aria-hidden="true"></i></span>
                @else
                    <a href="{{ $datas->appends(request()->except('page'))->previousPageUrl() }}"
                       class="text-slate-600 hover:text-[#8B0000] hover:bg-slate-50 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer"
                       aria-label="Previous page">
                        <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                    </a>
                @endif

                <!-- Next Page Link -->
                @if ($datas->hasMorePages())
                    <a href="{{ $datas->appends(request()->except('page'))->nextPageUrl() }}"
                       class="text-slate-600 hover:text-[#8B0000] hover:bg-slate-50 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer"
                       aria-label="Next page">
                        <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                    </a>
                @else
                    <span class="opacity-50 cursor-not-allowed text-slate-400 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs"><i class="fas fa-chevron-right text-xs" aria-hidden="true"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>