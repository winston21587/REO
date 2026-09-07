        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 flex flex-col flex-1 h-full min-h-[400px]">
            @if(count($datas) > 0)
                <!-- MOBILE & TABLET ADAPTIVE CARDS VIEW (Visible below lg: 1024px) -->
                <div class="block lg:hidden divide-y divide-slate-100 flex-grow">
                    @foreach($datas as $data)
                        @php
                            $isAdvanced = in_array($data->Status, ['Reviewer Assigned', 'Under Review', 'Reviewed']);
                            $docStatus = $isAdvanced ? 'Hardcopy Received' : $data->Status;
                            $docBadgeConfig = [
                                'Hardcopy Received' => [
                                    'badge' => 'bg-teal-50/70 text-teal-800 border-teal-200/70',
                                    'dot' => 'bg-teal-500',
                                    'label' => 'Hardcopy Received'
                                ],
                                'Incomplete - Awaiting Hardcopy' => [
                                    'badge' => 'bg-rose-50/70 text-rose-800 border-rose-200/70',
                                    'dot' => 'bg-rose-500',
                                    'label' => 'Incomplete - Awaiting Hardcopy'
                                ],
                                'Incomplete Hardcopy' => [
                                    'badge' => 'bg-rose-50/70 text-rose-800 border-rose-200/70',
                                    'dot' => 'bg-rose-500',
                                    'label' => 'Incomplete Hardcopy'
                                ],
                                'For Initial Review' => [
                                    'badge' => 'bg-sky-50/70 text-sky-800 border-sky-200/70',
                                    'dot' => 'bg-sky-500',
                                    'label' => 'For Initial Review'
                                ],
                            ];
                            $docConfig = $docBadgeConfig[$docStatus] ?? [
                                'badge' => 'bg-slate-100 text-slate-700 border-slate-200/80',
                                'dot' => 'bg-slate-400',
                                'label' => $docStatus
                            ];

                            $revStatus = $isAdvanced ? $data->Status : 'Pending Assignment';
                            $revBadgeConfig = [
                                'Pending Assignment' => [
                                    'badge' => 'bg-slate-100 text-slate-600 border-slate-200/80 italic',
                                    'dot' => 'bg-slate-400',
                                    'label' => 'Pending Assignment'
                                ],
                                'Reviewer Assigned' => [
                                    'badge' => 'bg-blue-50/70 text-blue-800 border-blue-200/70',
                                    'dot' => 'bg-blue-500',
                                    'label' => 'Reviewer Assigned'
                                ],
                                'Under Review' => [
                                    'badge' => 'bg-indigo-50/70 text-indigo-800 border-indigo-200/70',
                                    'dot' => 'bg-indigo-500',
                                    'label' => 'Under Review'
                                ],
                                'Reviewed' => [
                                    'badge' => 'bg-emerald-50/70 text-emerald-800 border-emerald-200/70',
                                    'dot' => 'bg-emerald-500',
                                    'label' => 'Reviewed'
                                ],
                            ];
                            $revConfig = $revBadgeConfig[$revStatus] ?? [
                                'badge' => 'bg-slate-100 text-slate-700 border-slate-200/80',
                                'dot' => 'bg-slate-400',
                                'label' => $revStatus
                            ];

                            $reviewerSuggestedType = $data->adminFiles->whereNotNull('suggested_review_type')->first()?->suggested_review_type;
                            $typeBadgeConfig = [
                                'Exempt Review' => [
                                    'badge' => 'bg-emerald-50/70 text-emerald-800 border-emerald-200/70',
                                    'icon' => 'fa-check-circle text-emerald-600',
                                    'label' => 'Exempt Review'
                                ],
                                'Expedited Review' => [
                                    'badge' => 'bg-blue-50/70 text-blue-800 border-blue-200/70',
                                    'icon' => 'fa-bolt text-blue-600',
                                    'label' => 'Expedited Review'
                                ],
                                'Full Board Review' => [
                                    'badge' => 'bg-amber-50/70 text-amber-900 border-amber-200/70',
                                    'icon' => 'fa-users text-amber-700',
                                    'label' => 'Full Board Review'
                                ]
                            ];

                            $recLetter = $data->files->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->first()
                                ?? $data->adminFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->first();
                            $defMessage = $latestDeficiencies->get($data->id)?->message ?? '';
                            $alreadyNotified = isset($recentReminders[$data->id]);
                            $hasReviewers = !empty($data->assigned_reviewers) && count($data->assigned_reviewers) > 0;
                            $hasValidType = !empty($data->Review_Type) && !in_array($data->Review_Type, ['Unassigned', 'N/A']);
                        @endphp
                        <div class="p-4 sm:p-5 space-y-3 hover:bg-slate-50/60 transition-colors">
                            <!-- Header: Code + Date + Title + Action Trigger -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                        <span class="text-[10px] font-mono font-medium text-slate-600 bg-slate-100 border border-slate-200/70 px-1.5 py-0.5 rounded">
                                            #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="text-xs text-slate-500 font-medium">
                                            <i class="far fa-calendar-alt text-slate-400 mr-1"></i>{{ $data->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('admin.view_files', $data->id) }}"
                                       class="font-semibold text-slate-900 text-sm sm:text-base leading-snug line-clamp-2 hover:text-[#8B0000] transition-colors"
                                       title="{{ $data->Study_Protocol_title }}">
                                        {{ $data->Study_Protocol_title }}
                                    </a>
                                </div>
                                <button type="button"
                                    @click="$dispatch('open-protocol-drawer', {
                                        id: '{{ $data->id }}',
                                        title: {{ json_encode($data->Study_Protocol_title) }},
                                        researcher_name: {{ json_encode(trim(($data->researcher->user->first_name ?? '') . ' ' . ($data->researcher->user->last_name ?? 'Unknown'))) }},
                                        created_at: '{{ $data->created_at->format('M d, Y') }}',
                                        code: '#{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}',
                                        status: {{ json_encode($data->Status) }},
                                        review_type: {{ json_encode($data->Review_Type) }},
                                        ai_suggested_type: {{ json_encode($data->ai_suggested_review_type) }},
                                        view_url: '{{ route('admin.view_files', $data->id) }}',
                                        has_deficiency: {{ in_array($data->Status, ['Incomplete - Awaiting Hardcopy', 'Incomplete Hardcopy']) ? 'true' : 'false' }},
                                        deficiency_message: {{ json_encode($defMessage) }},
                                        can_ai_predict: {{ (empty($data->Review_Type) || in_array($data->Review_Type, ['Unassigned', 'N/A'])) ? 'true' : 'false' }},
                                        rec_letter_url: {{ $recLetter ? json_encode(route('admin.recommendation.view_saved', $data->id)) : 'null' }},
                                        is_reviewed: {{ $data->Status === 'Reviewed' ? 'true' : 'false' }},
                                        is_or_verified: {{ (bool)$data->is_or_verified ? 'true' : 'false' }},
                                        rec_form_url: '{{ route('admin.recommendation.form', $data->id) }}',
                                        already_notified: {{ $alreadyNotified ? 'true' : 'false' }},
                                        can_assign: {{ in_array($data->Status, ['Hardcopy Received', 'Reviewer Assigned', 'Under Review']) ? 'true' : 'false' }},
                                        has_valid_review_type: {{ $hasValidType ? 'true' : 'false' }},
                                        has_reviewers_assigned: {{ $hasReviewers ? 'true' : 'false' }},
                                        assigned_reviewers: {{ json_encode($data->assigned_reviewers ?? []) }},
                                        update_status_url: '{{ route('admin.updateStatus', $data->id) }}'
                                    })"
                                    class="p-2.5 -mr-1 text-slate-400 hover:text-[#8B0000] hover:bg-slate-100 rounded-xl transition-all shrink-0 min-w-[44px] min-h-[44px] flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]"
                                    title="Protocol Actions"
                                    aria-label="Actions for {{ $data->Study_Protocol_title }}">
                                    <i class="fas fa-ellipsis-v text-sm"></i>
                                </button>
                            </div>

                            <!-- Researcher Row -->
                            <div class="flex items-center gap-2.5 pt-2 border-t border-slate-100 text-xs text-slate-600">
                                <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-700 uppercase shrink-0">
                                    {{ substr($data->researcher->user->first_name ?? 'U', 0, 1) }}
                                </div>
                                <span class="font-semibold text-slate-800 truncate">
                                    {{ $data->researcher->user->first_name ?? '' }} {{ $data->researcher->user->last_name ?? 'Unknown' }}
                                </span>
                                @if($data->researcher->user->email ?? false)
                                    <span class="text-slate-300">•</span>
                                    <span class="text-slate-400 truncate text-[11px]">{{ $data->researcher->user->email }}</span>
                                @endif
                            </div>

                            <!-- Status Badges Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-1">
                                <div class="bg-slate-50/80 border border-slate-200/60 p-2 rounded-lg">
                                    <span class="block text-[9px] font-semibold uppercase tracking-wider text-slate-400">Document Status</span>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $docConfig['dot'] }}"></span>
                                        <span class="text-xs font-semibold truncate leading-tight">
                                            {{ $docConfig['label'] }}
                                        </span>
                                        @if($data->Status === 'Incomplete - Awaiting Hardcopy' && $latestDeficiencies->get($data->id))
                                            <span class="text-rose-600 shrink-0" title="Reason: {{ $latestDeficiencies->get($data->id)->message }}">
                                                <i class="fas fa-exclamation-circle text-xs"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-slate-50/80 border border-slate-200/60 p-2 rounded-lg">
                                    <span class="block text-[9px] font-semibold uppercase tracking-wider text-slate-400">Reviewer Status</span>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $revConfig['dot'] }}"></span>
                                        <span class="text-xs font-semibold truncate leading-tight">
                                            {{ $revConfig['label'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="bg-slate-50/80 border border-slate-200/60 p-2 rounded-lg col-span-2 sm:col-span-1">
                                    <span class="block text-[9px] font-semibold uppercase tracking-wider text-slate-400">Classification</span>
                                    <div class="flex flex-col gap-0.5 mt-1">
                                        <div class="flex items-center gap-1">
                                            @if(isset($typeBadgeConfig[$data->Review_Type]))
                                                <i class="fas {{ $typeBadgeConfig[$data->Review_Type]['icon'] }} text-[9px]"></i>
                                                <span class="text-xs font-semibold truncate leading-tight">
                                                    {{ $data->Review_Type }}
                                                </span>
                                            @else
                                                <span class="text-xs font-medium text-slate-400 italic truncate leading-tight">
                                                    {{ $data->Review_Type ?: 'Unassigned' }}
                                                </span>
                                            @endif
                                        </div>
                                        @if(!empty($reviewerSuggestedType))
                                            <span class="text-[10px] text-slate-500 font-medium truncate">
                                                Suggested: {{ str_replace(' Review', '', $reviewerSuggestedType) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Assigned Reviewers -->
                            @if($data->assigned_reviewers && count($data->assigned_reviewers) > 0)
                                <div class="flex flex-wrap items-center gap-1.5 pt-1 text-xs">
                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mr-0.5">Reviewers:</span>
                                    @foreach($data->assigned_reviewers as $reviewerId)
                                        @php $reviewerUser = $reviewersById->get($reviewerId); @endphp
                                        @if($reviewerUser)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200/80 font-medium text-[11px]">
                                                <i class="fas fa-user-check text-slate-400 text-[9px]"></i>
                                                {{ $reviewerUser->first_name }} {{ $reviewerUser->last_name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- DESKTOP DATA TABLE (Visible on Desktop lg+: 1024px and up) -->
                <div class="hidden lg:block overflow-x-auto flex-grow overflow-y-visible">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-500 font-semibold">
                                <th class="px-4 py-3.5 min-w-[200px]">Research Title</th>
                                <th class="px-3 py-3.5 min-w-[140px]">Researcher</th>
                                <th class="px-3 py-3.5 min-w-[120px]">Reviewers</th>
                                <th class="px-3 py-3.5 min-w-[100px] whitespace-nowrap">Date</th>
                                <th class="px-3 py-3.5 min-w-[120px] whitespace-nowrap">Doc Status</th>
                                <th class="px-3 py-3.5 min-w-[110px] whitespace-nowrap">Review Status</th>
                                <th class="px-3 py-3.5 min-w-[115px] whitespace-nowrap">Review Type</th>
                                <th class="px-3 py-3.5 w-12 text-right pr-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($datas as $data)
                                @php
                                    $isAdvanced = in_array($data->Status, ['Reviewer Assigned', 'Under Review', 'Reviewed']);
                                    $docStatus = $isAdvanced ? 'Hardcopy Received' : $data->Status;
                                    $docBadgeConfig = [
                                        'Hardcopy Received' => [
                                            'badge' => 'bg-teal-50/70 text-teal-800 border-teal-200/70',
                                            'dot' => 'bg-teal-500',
                                            'label' => 'Hardcopy Received'
                                        ],
                                        'Incomplete - Awaiting Hardcopy' => [
                                            'badge' => 'bg-rose-50/70 text-rose-800 border-rose-200/70',
                                            'dot' => 'bg-rose-500',
                                            'label' => 'Incomplete - Awaiting Hardcopy'
                                        ],
                                        'Incomplete Hardcopy' => [
                                            'badge' => 'bg-rose-50/70 text-rose-800 border-rose-200/70',
                                            'dot' => 'bg-rose-500',
                                            'label' => 'Incomplete Hardcopy'
                                        ],
                                        'For Initial Review' => [
                                            'badge' => 'bg-sky-50/70 text-sky-800 border-sky-200/70',
                                            'dot' => 'bg-sky-500',
                                            'label' => 'For Initial Review'
                                        ],
                                    ];
                                    $docConfig = $docBadgeConfig[$docStatus] ?? [
                                        'badge' => 'bg-slate-100 text-slate-700 border-slate-200/80',
                                        'dot' => 'bg-slate-400',
                                        'label' => $docStatus
                                    ];

                                    $revStatus = $isAdvanced ? $data->Status : 'Pending Assignment';
                                    $revBadgeConfig = [
                                        'Pending Assignment' => [
                                            'badge' => 'bg-slate-100 text-slate-600 border-slate-200/80 italic',
                                            'dot' => 'bg-slate-400',
                                            'label' => 'Pending Assignment'
                                        ],
                                        'Reviewer Assigned' => [
                                            'badge' => 'bg-blue-50/70 text-blue-800 border-blue-200/70',
                                            'dot' => 'bg-blue-500',
                                            'label' => 'Reviewer Assigned'
                                        ],
                                        'Under Review' => [
                                            'badge' => 'bg-indigo-50/70 text-indigo-800 border-indigo-200/70',
                                            'dot' => 'bg-indigo-500',
                                            'label' => 'Under Review'
                                        ],
                                        'Reviewed' => [
                                            'badge' => 'bg-emerald-50/70 text-emerald-800 border-emerald-200/70',
                                            'dot' => 'bg-emerald-500',
                                            'label' => 'Reviewed'
                                        ],
                                    ];
                                    $revConfig = $revBadgeConfig[$revStatus] ?? [
                                        'badge' => 'bg-slate-100 text-slate-700 border-slate-200/80',
                                        'dot' => 'bg-slate-400',
                                        'label' => $revStatus
                                    ];

                                    $reviewerSuggestedType = $data->adminFiles->whereNotNull('suggested_review_type')->first()?->suggested_review_type;
                                    $hasTop = !empty($data->Review_Type) && $data->Review_Type !== 'Unassigned' && $data->Review_Type !== 'N/A';
                                    $isNA = $data->Review_Type === 'N/A';
                                    $hasMid = !empty($reviewerSuggestedType);
                                    $typeBadgeConfig = [
                                        'Exempt Review' => [
                                            'badge' => 'bg-emerald-50/70 text-emerald-800 border-emerald-200/70',
                                            'icon' => 'fa-check-circle text-emerald-600',
                                            'label' => 'Exempt Review'
                                        ],
                                        'Expedited Review' => [
                                            'badge' => 'bg-blue-50/70 text-blue-800 border-blue-200/70',
                                            'icon' => 'fa-bolt text-blue-600',
                                            'label' => 'Expedited Review'
                                        ],
                                        'Full Board Review' => [
                                            'badge' => 'bg-amber-50/70 text-amber-900 border-amber-200/70',
                                            'icon' => 'fa-users text-amber-700',
                                            'label' => 'Full Board Review'
                                        ]
                                    ];

                                    $recLetter = $data->files->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->first()
                                        ?? $data->adminFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->first();
                                    $defMessage = $latestDeficiencies->get($data->id)?->message ?? '';
                                    $alreadyNotified = isset($recentReminders[$data->id]);
                                    $hasReviewers = !empty($data->assigned_reviewers) && count($data->assigned_reviewers) > 0;
                                    $hasValidType = !empty($data->Review_Type) && !in_array($data->Review_Type, ['Unassigned', 'N/A']);
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-4 py-3 align-middle">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="text-[10px] font-mono font-medium text-slate-600 bg-slate-100 border border-slate-200/70 px-1.5 py-0.5 rounded">
                                                #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('admin.view_files', $data->id) }}"
                                           class="font-semibold text-slate-900 text-sm line-clamp-2 leading-snug group-hover:text-[#8B0000] transition-colors"
                                           title="{{ $data->Study_Protocol_title }}">
                                            {{ $data->Study_Protocol_title }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-3 align-middle">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200/80 flex items-center justify-center text-xs font-bold text-slate-700 uppercase shrink-0">
                                                {{ substr($data->researcher->user->first_name ?? 'U', 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-slate-800 truncate">
                                                    {{ $data->researcher->user->first_name ?? '' }}
                                                    {{ $data->researcher->user->last_name ?? 'Unknown' }}
                                                </p>
                                                <p class="text-[10px] text-slate-400 truncate">{{ $data->researcher->user->email ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 align-middle">
                                        @if($data->assigned_reviewers && count($data->assigned_reviewers) > 0)
                                            <div class="flex flex-col gap-1">
                                                @foreach($data->assigned_reviewers as $reviewerId)
                                                    @php
                                                        $reviewerUser = $reviewersById->get($reviewerId);
                                                    @endphp
                                                    @if($reviewerUser)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200/80 font-medium text-[11px] truncate max-w-[125px]" title="{{ $reviewerUser->first_name }} {{ $reviewerUser->last_name }}">
                                                            <i class="fas fa-user-check text-slate-400 text-[9px] shrink-0"></i>
                                                            <span class="truncate">{{ $reviewerUser->first_name }} {{ $reviewerUser->last_name }}</span>
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">None Assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 align-middle whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                            <i class="far fa-calendar-alt text-slate-400 text-[10px]"></i>
                                            <span>{{ $data->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 align-middle whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $docConfig['badge'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $docConfig['dot'] }}"></span>
                                                {{ $docConfig['label'] }}
                                            </span>
                                            @if($data->Status === 'Incomplete - Awaiting Hardcopy')
                                                @if($latestDeficiencies->get($data->id))
                                                    <span class="text-rose-600 cursor-help transition-all hover:scale-110" title="Reason: {{ $latestDeficiencies->get($data->id)->message }}">
                                                        <i class="fas fa-exclamation-circle text-xs drop-shadow-xs"></i>
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 align-middle whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $revConfig['badge'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $revConfig['dot'] }}"></span>
                                            {{ $revConfig['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 align-middle whitespace-nowrap">
                                        <div class="flex flex-col gap-1 items-start">
                                            @if(isset($typeBadgeConfig[$data->Review_Type]))
                                                @php $tConf = $typeBadgeConfig[$data->Review_Type]; @endphp
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-semibold border {{ $tConf['badge'] }}" title="Official Review Type">
                                                    <i class="fas {{ $tConf['icon'] }} text-[9px]"></i>
                                                    {{ $tConf['label'] }}
                                                </span>
                                            @elseif($isNA)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold text-slate-500 italic">N/A</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold text-slate-400 italic">Unassigned</span>
                                            @endif
                                            
                                            @if($hasMid)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-500 bg-slate-100/90 px-1.5 py-0.5 rounded border border-slate-200/80" title="Suggested by Reviewer">
                                                    <i class="fas fa-level-up-alt text-[8px] text-slate-400 rotate-90"></i>
                                                    Suggested: {{ str_replace(' Review', '', $reviewerSuggestedType) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-right align-middle pr-4 relative">
                                        <button type="button"
                                            @click="$dispatch('open-protocol-drawer', {
                                                id: '{{ $data->id }}',
                                                title: {{ json_encode($data->Study_Protocol_title) }},
                                                researcher_name: {{ json_encode(trim(($data->researcher->user->first_name ?? '') . ' ' . ($data->researcher->user->last_name ?? 'Unknown'))) }},
                                                created_at: '{{ $data->created_at->format('M d, Y') }}',
                                                code: '#{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}',
                                                status: {{ json_encode($data->Status) }},
                                                review_type: {{ json_encode($data->Review_Type) }},
                                                ai_suggested_type: {{ json_encode($data->ai_suggested_review_type) }},
                                                view_url: '{{ route('admin.view_files', $data->id) }}',
                                                has_deficiency: {{ in_array($data->Status, ['Incomplete - Awaiting Hardcopy', 'Incomplete Hardcopy']) ? 'true' : 'false' }},
                                                deficiency_message: {{ json_encode($defMessage) }},
                                                can_ai_predict: {{ (empty($data->Review_Type) || in_array($data->Review_Type, ['Unassigned', 'N/A'])) ? 'true' : 'false' }},
                                                rec_letter_url: {{ $recLetter ? json_encode(route('admin.recommendation.view_saved', $data->id)) : 'null' }},
                                                is_reviewed: {{ $data->Status === 'Reviewed' ? 'true' : 'false' }},
                                                is_or_verified: {{ (bool)$data->is_or_verified ? 'true' : 'false' }},
                                                rec_form_url: '{{ route('admin.recommendation.form', $data->id) }}',
                                                already_notified: {{ $alreadyNotified ? 'true' : 'false' }},
                                                can_assign: {{ in_array($data->Status, ['Hardcopy Received', 'Reviewer Assigned', 'Under Review']) ? 'true' : 'false' }},
                                                has_valid_review_type: {{ $hasValidType ? 'true' : 'false' }},
                                                has_reviewers_assigned: {{ $hasReviewers ? 'true' : 'false' }},
                                                assigned_reviewers: {{ json_encode($data->assigned_reviewers ?? []) }},
                                                update_status_url: '{{ route('admin.updateStatus', $data->id) }}'
                                            })"
                                            class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center text-slate-400 hover:text-[#8B0000] hover:bg-slate-100 rounded-xl transition-all ml-auto touch-manipulation focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                                            title="Protocol Actions"
                                            aria-label="Actions for {{ $data->Study_Protocol_title }}">
                                            <i class="fas fa-ellipsis-v text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center text-slate-500 flex-grow flex flex-col items-center justify-center">
                    @if(request()->anyFilled(['search', 'review_types', 'doc_statuses', 'rev_statuses', 'assignment', 'sort_by']))
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                            <i class="fas fa-search text-xl"></i>
                        </div>
                        <h4 class="text-sm font-semibold text-slate-900 mb-1">No matching protocols found</h4>
                        <p class="text-xs text-slate-500 max-w-sm mb-4 leading-relaxed">No active protocols match your current search or filter criteria. Try adjusting your parameters or clear all filters.</p>
                        <a href="{{ route('admin.applications') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200/80 transition-colors shadow-2xs">
                            <i class="fas fa-times-circle text-slate-400"></i> Clear All Filters
                        </a>
                    @else
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                            <i class="fas fa-folder-open text-xl"></i>
                        </div>
                        <h4 class="text-sm font-semibold text-slate-900 mb-1">No active protocols</h4>
                        <p class="text-xs text-slate-500 max-w-sm leading-relaxed">There are currently no research protocols undergoing initial ethical review or revision.</p>
                    @endif
                </div>
            @endif

            @if($datas->total() > 0)
            <div class="p-4 sm:p-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500 mt-auto shrink-0">
                <div class="text-center sm:text-left">
                    Showing <span class="font-semibold text-slate-800 tabular-nums">{{ $datas->firstItem() ?? 0 }}</span> - <span
                        class="font-semibold text-slate-800 tabular-nums">{{ $datas->lastItem() ?? 0 }}</span> of <span
                        class="font-semibold text-slate-800 tabular-nums">{{ $datas->total() }}</span>
                </div>
                <div class="flex gap-2 filter-pagination">
                    <!-- Previous Page Link -->
                    @if ($datas->onFirstPage())
                        <span class="opacity-50 cursor-not-allowed text-slate-400 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $datas->appends(request()->except('page'))->previousPageUrl() }}"
                            class="text-slate-600 hover:text-[#8B0000] hover:bg-slate-50 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                            aria-label="Previous page"><i
                                class="fas fa-chevron-left"></i></a>
                    @endif

                    <!-- Next Page Link -->
                    @if ($datas->hasMorePages())
                        <a href="{{ $datas->appends(request()->except('page'))->nextPageUrl() }}"
                            class="text-slate-600 hover:text-[#8B0000] hover:bg-slate-50 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                            aria-label="Next page"><i
                                class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="opacity-50 cursor-not-allowed text-slate-400 w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 shadow-2xs"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
            @endif
        </div>
