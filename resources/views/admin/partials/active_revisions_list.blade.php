<div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 flex flex-col flex-1 h-full min-h-[400px]">
    @if($datas->count() > 0)
        {{-- MOBILE & TABLET ADAPTIVE CARDS VIEW (Visible below lg: 1024px) --}}
        <div class="block lg:hidden divide-y divide-slate-100 flex-grow">
            @foreach($datas as $data)
                @php
                    $displayStatus = $data->Status === 'Panel Deliberation' ? 'Reviewed' : $data->Status;
                    $statusDotColors = [
                        'Waiting for Revision' => 'bg-amber-500',
                        'Revision Submitted' => 'bg-purple-500',
                        'Reviewing Revisions' => 'bg-indigo-500',
                        'Reviewed' => 'bg-emerald-500',
                    ];
                    $statusBadgeStyles = [
                        'Waiting for Revision' => 'text-amber-800',
                        'Revision Submitted' => 'text-purple-700',
                        'Reviewing Revisions' => 'text-indigo-700',
                        'Reviewed' => 'text-emerald-700',
                    ];
                    $dotColor = $statusDotColors[$displayStatus] ?? 'bg-slate-400';
                    $badgeStyle = $statusBadgeStyles[$displayStatus] ?? 'text-slate-600';

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

                    $reviewers = [];
                    if (!empty($data->assigned_reviewers)) {
                        $reviewers = \App\Models\User::whereIn('id', $data->assigned_reviewers)->get();
                    }

                    $allLetters = $data->files->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review'])
                        ->merge($data->adminFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review']))
                        ->sortByDesc('created_at');
                    $currentLetter = $allLetters->filter(fn($l) => in_array($l->filetype, ['Result of Review (Admin Generated)', 'recommendation letter']))->first();
                    $previousLetters = $allLetters->filter(fn($l) => $l->filetype === 'Archived Result of Review');

                    $researcherName = trim(($data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'Unknown') . ' ' . ($data->researcher->user->last_name ?? $data->user->last_name ?? ''));
                    $researcherEmail = $data->researcher->user->email ?? $data->user->email ?? '';
                @endphp
                <div class="p-4 sm:p-5 space-y-3 hover:bg-slate-50/60 transition-colors">
                    <!-- Card Header: Code + Date + Title + Action Trigger -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                <span class="text-[11px] font-mono font-semibold tabular-nums text-slate-600 bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded-md">
                                    #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-xs text-slate-500 font-medium tabular-nums flex items-center gap-1.5">
                                    <i class="far fa-calendar-alt text-slate-400 text-[11px]" aria-hidden="true"></i>Updated {{ $data->updated_at->format('M d, Y') }}
                                </span>
                            </div>
                            <a href="{{ route('admin.view_files', $data->id) }}"
                               class="font-bold text-slate-900 text-sm sm:text-base leading-snug line-clamp-2 hover:text-[#8B0000] transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000] rounded-sm block"
                               title="{{ $data->Study_Protocol_title }}">
                                {{ $data->Study_Protocol_title }}
                            </a>
                        </div>
                        <button type="button"
                            @click="$dispatch('open-revision-drawer', {
                                id: '{{ $data->id }}',
                                title: {{ json_encode($data->Study_Protocol_title) }},
                                researcher_name: {{ json_encode($researcherName) }},
                                created_at: '{{ $data->created_at->format('M d, Y') }}',
                                code: '#{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}',
                                status: {{ json_encode($data->Status) }},
                                review_type: {{ json_encode($data->Review_Type) }},
                                view_files_url: '{{ route('admin.view_files', $data->id) }}',
                                logs: {{ json_encode($data->revisionLogs ?? []) }},
                                current_letter_url: {{ $currentLetter ? json_encode(route('admin.recommendation.view_file', $currentLetter->id)) : 'null' }},
                                previous_letters: [
                                    @foreach($previousLetters as $letter)
                                        {
                                            url: '{{ route('admin.recommendation.view_file', $letter->id) }}',
                                            date: '{{ $letter->created_at->format('M d, Y') }}'
                                        },
                                    @endforeach
                                ]
                            })"
                            class="p-2.5 -mr-1 text-slate-500 hover:text-[#8B0000] hover:bg-slate-100 rounded-xl transition-all shrink-0 min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000] touch-manipulation"
                            title="Revision Actions"
                            aria-label="Actions for {{ $data->Study_Protocol_title }}">
                            <i class="fas fa-ellipsis-v text-sm" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Badges Row -->
                    <div class="flex items-center gap-2.5 flex-wrap">
                        @if(isset($typeBadgeConfig[$data->Review_Type]))
                            @php $tConf = $typeBadgeConfig[$data->Review_Type]; @endphp
                            <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap {{ $tConf['text'] }}">
                                {{ $tConf['label'] }}
                            </span>
                        @elseif($data->Review_Type === 'N/A')
                            <span class="inline-flex items-center text-xs font-semibold text-slate-500 italic">N/A</span>
                        @else
                            <span class="inline-flex items-center text-xs font-semibold text-slate-400 italic">Unassigned</span>
                        @endif

                        <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap {{ $badgeStyle }}">
                            {{ $displayStatus }}
                        </span>

                        @if($data->Status === 'Waiting for Revision')
                            <span class="inline-flex items-center text-[11px] font-bold uppercase tracking-wider text-red-600 whitespace-nowrap">Modifications Required</span>
                        @elseif($data->Status === 'Panel Deliberation')
                            <span class="inline-flex items-center text-[11px] font-bold uppercase tracking-wider text-pink-700 whitespace-nowrap">Committee Review</span>
                        @endif
                    </div>

                    <!-- Metadata Grid: Researcher & Reviewer -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-full bg-slate-100 border border-slate-200/80 flex items-center justify-center text-xs font-bold text-slate-700 uppercase shrink-0">
                                {{ substr($researcherName ?: 'U', 0, 1) }}
                            </div>
                            <div class="min-w-0 truncate">
                                <span class="font-semibold text-slate-800 truncate block">{{ $researcherName }}</span>
                                @if($researcherEmail)
                                    <span class="text-slate-400 text-[11px] truncate block">{{ $researcherEmail }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-slate-400 font-medium">Reviewer:</span>
                            @if(count($reviewers) > 0)
                                <span class="font-semibold text-slate-700 truncate">
                                    {{ $reviewers->map(fn($r) => $r->first_name . ' ' . $r->last_name)->join(', ') }}
                                </span>
                            @else
                                <span class="text-slate-400 italic">Unassigned</span>
                            @endif
                        </div>
                    </div>

                    @if($data->reviewer_decision)
                        <div class="flex items-center gap-1.5 text-xs text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200/80">
                            <i class="fas fa-level-up-alt text-slate-400 rotate-90 text-xs" aria-hidden="true"></i>
                            <span>Recommendation: <strong class="text-slate-800">{{ $data->reviewer_decision }}</strong></span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- DESKTOP DATA TABLE (Visible on Desktop lg+: 1024px and up) --}}
        <div class="hidden lg:block overflow-x-auto flex-grow overflow-y-visible">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-4 py-3.5 min-w-[200px]">Research Title</th>
                        <th class="px-3 py-3.5 min-w-[140px]">Researcher</th>
                        <th class="px-3 py-3.5 min-w-[120px]">Reviewer</th>
                        <th class="px-3 py-3.5 min-w-[105px] whitespace-nowrap">Last Updated</th>
                        <th class="px-3 py-3.5 min-w-[115px] whitespace-nowrap">Review Type</th>
                        <th class="px-3 py-3.5 min-w-[120px] whitespace-nowrap">Status</th>
                        <th class="px-3 py-3.5 min-w-[200px] whitespace-nowrap">Action Taken</th>
                        <th class="px-3 py-3.5 w-12 text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($datas as $data)
                        @php
                            $displayStatus = $data->Status === 'Panel Deliberation' ? 'Reviewed' : $data->Status;
                            $statusDotColors = [
                                'Waiting for Revision' => 'bg-amber-500',
                                'Revision Submitted' => 'bg-purple-500',
                                'Reviewing Revisions' => 'bg-indigo-500',
                                'Reviewed' => 'bg-emerald-500',
                            ];
                            $statusBadgeStyles = [
                                'Waiting for Revision' => 'text-amber-800',
                                'Revision Submitted' => 'text-purple-700',
                                'Reviewing Revisions' => 'text-indigo-700',
                                'Reviewed' => 'text-emerald-700',
                            ];
                            $dotColor = $statusDotColors[$displayStatus] ?? 'bg-slate-400';
                            $badgeStyle = $statusBadgeStyles[$displayStatus] ?? 'text-slate-600';

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

                            $reviewers = [];
                            if (!empty($data->assigned_reviewers)) {
                                $reviewers = \App\Models\User::whereIn('id', $data->assigned_reviewers)->get();
                            }

                            $allLetters = $data->files->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review'])
                                ->merge($data->adminFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review']))
                                ->sortByDesc('created_at');
                            $currentLetter = $allLetters->filter(fn($l) => in_array($l->filetype, ['Result of Review (Admin Generated)', 'recommendation letter']))->first();
                            $previousLetters = $allLetters->filter(fn($l) => $l->filetype === 'Archived Result of Review');

                            $researcherName = trim(($data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'Unknown') . ' ' . ($data->researcher->user->last_name ?? $data->user->last_name ?? ''));
                            $researcherEmail = $data->researcher->user->email ?? $data->user->email ?? '';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            {{-- Research Title --}}
                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="text-[11px] font-mono font-semibold tabular-nums text-slate-600 bg-slate-100 border border-slate-200/80 px-2 py-0.5 rounded-md">
                                        #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                <a href="{{ route('admin.view_files', $data->id) }}"
                                   class="font-semibold text-slate-900 text-sm line-clamp-2 leading-snug group-hover:text-[#8B0000] transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000] rounded-sm block"
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
                                        @if($researcherEmail)
                                            <p class="text-[11px] text-slate-500 font-medium truncate">{{ $researcherEmail }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Reviewer --}}
                            <td class="px-3 py-3 align-middle whitespace-nowrap">
                                @if(count($reviewers) > 0)
                                    <div class="flex flex-col gap-1">
                                        @foreach($reviewers as $rev)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/80 font-medium text-[11px] truncate max-w-[140px]" title="{{ $rev->first_name }} {{ $rev->last_name }}">
                                                <i class="fas fa-user-check text-slate-400 text-[11px] shrink-0" aria-hidden="true"></i>
                                                <span class="truncate">{{ $rev->first_name }} {{ $rev->last_name }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>

                            {{-- Last Updated --}}
                            <td class="px-3 py-3 align-middle whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600 tabular-nums">
                                    <i class="far fa-calendar-alt text-slate-400 text-[11px]" aria-hidden="true"></i>
                                    <span>{{ $data->updated_at->format('M d, Y') }}</span>
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

                            {{-- Status --}}
                            <td class="px-3 py-3 align-middle whitespace-nowrap">
                                <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap {{ $badgeStyle }}">
                                    {{ $displayStatus }}
                                </span>
                            </td>

                            {{-- Action Taken --}}
                            <td class="px-3 py-3 align-middle whitespace-nowrap">
                                <div class="flex flex-col gap-1 items-start">
                                    @if($data->Status === 'Waiting for Revision')
                                        <span class="inline-flex items-center text-[11px] font-bold uppercase tracking-wide text-red-600 whitespace-nowrap">Modifications Required</span>
                                    @elseif($data->Status === 'Panel Deliberation')
                                        <span class="inline-flex items-center text-[11px] font-bold uppercase tracking-wide text-pink-700 whitespace-nowrap">Committee Review</span>
                                    @else
                                        <span class="inline-flex items-center text-[11px] font-medium text-slate-400 italic whitespace-nowrap">Not Assigned</span>
                                    @endif
                                    @if($data->reviewer_decision)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-500 whitespace-nowrap" title="Suggested: {{ $data->reviewer_decision }}">
                                            <i class="fas fa-level-up-alt text-[10px] text-slate-400 rotate-90 shrink-0" aria-hidden="true"></i>
                                            <span>Suggested: {{ $data->reviewer_decision }}</span>
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-3 py-3 text-right align-middle pr-4 relative">
                                <button type="button"
                                    @click="$dispatch('open-revision-drawer', {
                                        id: '{{ $data->id }}',
                                        title: {{ json_encode($data->Study_Protocol_title) }},
                                        researcher_name: {{ json_encode($researcherName) }},
                                        created_at: '{{ $data->created_at->format('M d, Y') }}',
                                        code: '#{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}',
                                        status: {{ json_encode($data->Status) }},
                                        review_type: {{ json_encode($data->Review_Type) }},
                                        view_files_url: '{{ route('admin.view_files', $data->id) }}',
                                        logs: {{ json_encode($data->revisionLogs ?? []) }},
                                        current_letter_url: {{ $currentLetter ? json_encode(route('admin.recommendation.view_file', $currentLetter->id)) : 'null' }},
                                        previous_letters: [
                                            @foreach($previousLetters as $letter)
                                                {
                                                    url: '{{ route('admin.recommendation.view_file', $letter->id) }}',
                                                    date: '{{ $letter->created_at->format('M d, Y') }}'
                                                },
                                            @endforeach
                                        ]
                                    })"
                                    class="w-9 h-9 min-w-[38px] min-h-[38px] flex items-center justify-center text-slate-500 hover:text-[#8B0000] hover:bg-slate-100 rounded-xl transition-all ml-auto touch-manipulation cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                                    title="Revision Actions"
                                    aria-label="Actions for {{ $data->Study_Protocol_title }}">
                                    <i class="fas fa-ellipsis-v text-xs" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-12 text-center text-slate-500 flex-grow flex flex-col items-center justify-center">
            @if(request()->anyFilled(['search', 'review_types', 'status', 'reviewer_decisions', 'sort_by']))
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                    <i class="fas fa-search text-xl" aria-hidden="true"></i>
                </div>
                <h4 class="text-sm font-semibold text-slate-900 mb-1">No matching revisions found</h4>
                <p class="text-xs text-slate-500 max-w-sm mb-4 leading-relaxed">No revisions match your current search or filter criteria. Try adjusting your parameters or clear all filters.</p>
                <a href="{{ route('admin.revisions') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200/80 transition-colors shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                    <i class="fas fa-times-circle text-slate-400" aria-hidden="true"></i> Clear All Filters
                </a>
            @else
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                    <i class="fas fa-folder-open text-xl" aria-hidden="true"></i>
                </div>
                <h4 class="text-sm font-semibold text-slate-900 mb-1">No active revisions</h4>
                <p class="text-xs text-slate-500 max-w-sm leading-relaxed">There are currently no submissions requiring or submitting revisions.</p>
            @endif
        </div>
    @endif

    @if($datas->total() > 0)
        <div class="p-4 sm:p-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500 mt-auto shrink-0">
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
                        aria-label="Previous page"><i
                            class="fas fa-chevron-left text-xs" aria-hidden="true"></i></a>
                @endif

                <!-- Next Page Link -->
                @if ($datas->hasMorePages())
                    <a href="{{ $datas->appends(request()->except('page'))->nextPageUrl() }}"
                        class="text-slate-600 hover:text-[#8B0000] hover:bg-slate-50 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer"
                        aria-label="Next page"><i
                            class="fas fa-chevron-right text-xs" aria-hidden="true"></i></a>
                @else
                    <span class="opacity-50 cursor-not-allowed text-slate-400 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs"><i class="fas fa-chevron-right text-xs" aria-hidden="true"></i></span>
                @endif
            </div>
        </div>
    @endif
</div>