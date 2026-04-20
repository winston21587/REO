        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 flex flex-col h-full min-h-[400px]">
            <div class="overflow-x-auto flex-grow overflow-y-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Research Title</th>
                            <th class="p-6">Researcher</th>
                            <th class="p-6">Reviewers</th>
                            <th class="p-6">Submission Date</th>
                            <th class="p-6">Document Status</th>
                            <th class="p-6">Reviewer Status</th>
                            <th class="p-6">Review Type</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($datas as $data)
                            <tr class="hover:bg-slate-50/80 transition-colors group">

                                <td class="p-6">
                                    <p class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-[#8B0000] transition-colors"
                                        title="{{ $data->Study_Protocol_title }}">
                                        {{ $data->Study_Protocol_title }}
                                    </p>
                                </td>
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                            {{ substr($data->researcher->user->first_name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-700">
                                                {{ $data->researcher->user->first_name ?? '' }}
                                                {{ $data->researcher->user->last_name ?? 'Unknown' }}
                                            </p>
                                            <p class="text-[10px] text-slate-400">{{ $data->researcher->user->email ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6">
                                    @if($data->assigned_reviewers && count($data->assigned_reviewers) > 0)
                                        <div class="flex flex-col gap-1">
                                            @foreach($data->assigned_reviewers as $reviewerId)
                                                @php
                                                    $reviewerUser = \App\Models\User::find($reviewerId);
                                                @endphp
                                                @if($reviewerUser)
                                                    <span class="text-sm font-medium text-slate-700">
                                                        <i class="fas fa-user-check text-green-600 mr-1 opacity-70"></i>
                                                        {{ $reviewerUser->first_name }} {{ $reviewerUser->last_name }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">None Assigned</span>
                                    @endif
                                </td>
                                <td class="p-6">
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <i class="far fa-calendar text-slate-400"></i>
                                        {{ $data->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="p-6">
                                    @php
                                        $isAdvanced = in_array($data->Status, ['Reviewer Assigned', 'Under Review', 'Reviewed']);
                                        $docStatus = $isAdvanced ? 'Hardcopy Received' : $data->Status;
                                        
                                        $docColors = [
                                            'For Initial Review' => 'text-blue-600', // legacy
                                            'Incomplete - Awaiting Hardcopy' => 'text-red-600',
                                            'Incomplete Hardcopy' => 'text-red-600',
                                            'Hardcopy Received' => 'text-teal-600',
                                        ];
                                        $docClass = $docColors[$docStatus] ?? 'text-slate-500';
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="text-sm font-bold {{ $docClass }}">
                                            {{ $docStatus }}
                                        </div>
                                        @if($data->Status === 'Incomplete - Awaiting Hardcopy')
                                            @php
                                                $latestDeficiency = \App\Models\SubmissionFeedback::where('research_title_id', $data->id)
                                                    ->where('type', 'hardcopy_deficiency')
                                                    ->latest()
                                                    ->first();
                                            @endphp
                                            @if($latestDeficiency)
                                                <span class="text-red-500 cursor-help transition-all hover:scale-110" title="Reason: {{ $latestDeficiency->message }}">
                                                    <i class="fas fa-exclamation-circle text-sm drop-shadow-sm"></i>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="p-6">
                                    @php
                                        $revStatus = $isAdvanced ? $data->Status : 'Pending Assignment';
                                        
                                        $revColors = [
                                            'Pending Assignment' => 'text-slate-400 italic opacity-80',
                                            'Reviewer Assigned' => 'text-blue-600',
                                            'Under Review' => 'text-indigo-600',
                                            'Reviewed' => 'text-green-600',
                                        ];
                                        $revClass = $revColors[$revStatus] ?? 'text-slate-500';
                                    @endphp
                                    <div class="text-sm font-bold {{ $revClass }}">
                                        {{ $revStatus }}
                                    </div>
                                </td>
                                <td class="p-6">
                                    @php
                                        $reviewerSuggestedType = $data->adminFiles->whereNotNull('suggested_review_type')->first()?->suggested_review_type;
                                        $hasTop = !empty($data->Review_Type) && $data->Review_Type !== 'Unassigned' && $data->Review_Type !== 'N/A';
                                        $isNA = $data->Review_Type === 'N/A';
                                        $hasMid = !empty($reviewerSuggestedType);
                                        $typeColors = [
                                            'Exempt Review' => 'text-emerald-600',
                                            'Expedited Review' => 'text-blue-600',
                                            'Full Board Review' => 'text-amber-600'
                                        ];
                                    @endphp
                                    <div class="flex flex-col gap-0.5 {{ !$hasTop ? 'opacity-60' : '' }}">
                                        @if($hasTop)
                                            <div class="flex items-center cursor-default" title="Official Review Type">
                                                <span class="text-sm font-bold {{ $typeColors[$data->Review_Type] ?? 'text-slate-500' }} tracking-tight leading-tight">{{ $data->Review_Type }}</span>
                                            </div>
                                        @elseif($isNA)
                                            <span class="text-sm font-semibold text-slate-500 italic tracking-tight leading-tight">N/A</span>
                                        @else
                                            <span class="text-sm font-semibold text-slate-500 italic leading-tight">Unassigned</span>
                                        @endif
                                        
                                        @if($hasMid)
                                            <div class="flex items-start gap-1.5 mt-0.5 {{ $hasTop ? 'opacity-60' : 'opacity-80' }}" title="Suggested by Reviewer">
                                                <i class="fas fa-level-up-alt text-[9px] text-slate-400 rotate-90 mt-[3px]"></i>
                                                <span class="text-[10px] font-bold text-slate-500 leading-tight">Suggested: {{ str_replace(' Review', '', $reviewerSuggestedType) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-6 text-right relative">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.away="open = false"
                                            class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>

                                        <!-- SIDEBAR DRAWER -->
                                        <div x-show="open" style="display: none;" class="relative z-[100]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
                                            <!-- Background backdrop -->
                                            <div x-show="open" 
                                                 x-transition:enter="ease-in-out duration-300" 
                                                 x-transition:enter-start="opacity-0" 
                                                 x-transition:enter-end="opacity-100" 
                                                 x-transition:leave="ease-in-out duration-300" 
                                                 x-transition:leave-start="opacity-100" 
                                                 x-transition:leave-end="opacity-0" 
                                                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
                                                 @click="open = false"></div>

                                            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                                                <div class="absolute inset-0 overflow-hidden">
                                                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10"
                                                         x-show="open"
                                                         x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                                                         x-transition:enter-start="translate-x-full"
                                                         x-transition:enter-end="translate-x-0"
                                                         x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                                                         x-transition:leave-start="translate-x-0"
                                                         x-transition:leave-end="translate-x-full">
                                                        
                                                        <div class="pointer-events-auto w-screen max-w-sm flex flex-col h-full bg-white shadow-2xl">
                                                            <!-- Drawer Header -->
                                                            <div class="px-6 py-6 border-b border-slate-100 flex justify-between items-start bg-slate-50 flex-none text-left">
                                                                <div class="pr-3 w-full">
                                                                    <h3 class="font-heading font-extrabold text-lg text-slate-800 leading-tight line-clamp-3" title="{{ $data->Study_Protocol_title }}">
                                                                        {{ $data->Study_Protocol_title }}
                                                                    </h3>
                                                                    
                                                                    <div class="mt-3 space-y-1.5">
                                                                        <div class="flex items-center gap-2 text-sm text-slate-600">
                                                                            <i class="fas fa-user-circle text-slate-400 w-4 text-center"></i>
                                                                            <span class="font-medium truncate">{{ $data->researcher->user->first_name ?? '' }} {{ $data->researcher->user->last_name ?? 'Unknown' }}</span>
                                                                        </div>
                                                                        <div class="flex items-center gap-2 text-xs text-slate-500">
                                                                            <i class="far fa-calendar-alt text-slate-400 w-4 text-center"></i>
                                                                            <span>{{ $data->created_at->format('M d, Y') }}</span>
                                                                            <span class="mx-1 text-slate-300">•</span>
                                                                            <span class="text-[#8B0000] font-mono font-bold">#{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button @click="open = false" class="text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-colors w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-full mt-0.5">
                                                                    <i class="fas fa-times text-lg"></i>
                                                                </button>
                                                            </div>

                                                            <!-- Drawer Actions List -->
                                                            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                                                <a href="{{ route('admin.view_files', $data->id) }}"
                                                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                    <i class="fas fa-eye w-4"></i> View Details
                                                </a>

                                                @if(in_array($data->Status, ['Incomplete - Awaiting Hardcopy', 'Incomplete Hardcopy']))
                                                    @php
                                                        $defMessage = '';
                                                        $def = \App\Models\SubmissionFeedback::where('research_title_id', $data->id)->where('type', 'hardcopy_deficiency')->latest()->first();
                                                        $defMessage = $def ? $def->message : '';
                                                    @endphp
                                                    <button type="button" @click="open = false; confirmHardcopyReceived('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }}, {{ json_encode($defMessage) }})"
                                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-green-600 rounded-lg transition-colors text-left">
                                                        <i class="fas fa-file-import w-4"></i> Receive Hardcopy
                                                    </button>

                                                @else
                                                    <button
                                                        @click="open = false; openStatusModal('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }}, {{ json_encode($data->Status) }}, {{ json_encode($data->Review_Type) }})"
                                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                        <i class="fas fa-sync-alt w-4"></i> Update Status
                                                    </button>
                                                @endif

                                                @if(empty($data->Review_Type) || in_array($data->Review_Type, ['Unassigned', 'N/A']))
                                                    <button
                                                        @click="open = false; openAiPredictModal('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }}, '{{ $data->ai_suggested_review_type }}')"
                                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                        <i class="fas fa-magic w-4"></i> AI Review Predict
                                                    </button>
                                                @endif

                                                @php
                                                    // Check for letter in both relationships
                                                    $recLetter = $data->files->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->first()
                                                        ?? $data->adminFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->first();
                                                @endphp

                                                @if($recLetter)
                                                    <!-- View Letter -->
                                                    <a href="{{ route('admin.recommendation.view_saved', $data->id) }}"
                                                        target="_blank"
                                                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                        <i class="fas fa-file-pdf w-4"></i> View Recommendation Letter
                                                    </a>
                                                @endif

                                                <!-- Generate Recommendation Letter (Only when Reviewed) -->
                                                @if($data->Status === 'Reviewed')
                                                    @if($data->is_or_verified)
                                                        <a href="{{ route('admin.recommendation.form', $data->id) }}"
                                                            class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                            <i class="fas fa-file-signature w-4"></i> Generate Recommendation Letter
                                                        </a>
                                                    @else
                                                        <div title="Official Receipt must be received and verified before generating a Recommendation Letter."
                                                             class="flex items-center gap-3 px-4 py-3 rounded-lg select-none">
                                                            <i class="fas fa-lock w-4 text-slate-300"></i>
                                                            <span class="text-sm font-medium text-slate-300 flex-1 truncate">Generate Recommendation Letter</span>
                                                            <span class="ml-auto text-[9px] font-bold bg-orange-50 text-orange-500 px-1.5 py-0.5 rounded-full border border-orange-200 leading-none whitespace-nowrap">
                                                                OR Required
                                                            </span>
                                                        </div>
                                                        @php
                                                            $researcherUserId = optional(optional($data->researcher)->user)->id;
                                                            $alreadyNotified = $researcherUserId && \App\Models\UserNotification::where('user_id', $researcherUserId)
                                                                ->where('research_id', $data->id)
                                                                ->where('type', 'receipt_reminder')
                                                                ->where('is_read', false)
                                                                ->where('created_at', '>=', \Carbon\Carbon::now()->subHours(24))
                                                                ->exists();
                                                        @endphp
                                                        <button
                                                            type="button"
                                                            @if($alreadyNotified)
                                                            disabled
                                                            title="A receipt reminder has already been sent. Awaiting researcher response."
                                                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-400 bg-slate-50 rounded-lg cursor-not-allowed select-none text-left"
                                                            @else
                                                            onclick="notifyReceiptRequired('{{ $data->id }}', '{{ addslashes($data->Study_Protocol_title) }}')"
                                                            title="Send a notification reminding the researcher to submit their Official Receipt."
                                                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-orange-600 hover:bg-orange-50 rounded-lg transition-colors text-left"
                                                            @endif
                                                        >
                                                            @if($alreadyNotified)
                                                                <i class="fas fa-clock w-4 text-slate-400"></i>
                                                                <span>Awaiting Response</span>
                                                                <span class="ml-auto text-[9px] font-bold bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full border border-slate-200 leading-none whitespace-nowrap">
                                                                    Notified
                                                                </span>
                                                            @else
                                                                <i class="fas fa-bell w-4"></i>
                                                                <span>Notify Researcher</span>
                                                                <span class="ml-auto text-[9px] font-bold bg-orange-50 text-orange-500 px-1.5 py-0.5 rounded-full border border-orange-200 leading-none">
                                                                    OR Required
                                                                </span>
                                                            @endif
                                                        </button>
                                                    @endif
                                                @endif

                                                <!-- Assign Reviewers (Only after Hardcopy Received) -->
                                                @if(in_array($data->Status, ['Hardcopy Received', 'Reviewer Assigned', 'Under Review']))
                                                    @php 
                                                        $hasReviewersAssigned = !empty($data->assigned_reviewers) && count($data->assigned_reviewers) > 0;
                                                        $hasValidReviewType = !empty($data->Review_Type) && !in_array($data->Review_Type, ['Unassigned', 'N/A']);
                                                    @endphp
                                                    @if($hasValidReviewType)
                                                        <button @click="open = false; $dispatch('open-assign-modal', { id: '{{ $data->id }}', title: {{ json_encode($data->Study_Protocol_title) }}, assigned: {{ json_encode($data->assigned_reviewers ?? []) }}, reviewType: {{ json_encode($data->Review_Type) }} })"
                                                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                            <i class="fas {{ $hasReviewersAssigned ? 'fa-user-edit' : 'fa-users-cog' }} w-4"></i> 
                                                            {{ $hasReviewersAssigned ? 'Change Reviewer(s)' : 'Assign Reviewer(s)' }}
                                                        </button>
                                                    @else
                                                        <div title="Review Classification must be set before assigning reviewers." class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-400 bg-slate-50 rounded-lg cursor-not-allowed select-none text-left">
                                                            <i class="fas fa-users-slash w-4"></i> Assign Reviewer(s)
                                                            <span class="ml-auto text-[9px] font-bold bg-slate-200 text-slate-500 px-1.5 py-0.5 rounded-full border border-slate-300 leading-none whitespace-nowrap">
                                                                Set Type First
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endif

                                                <hr class="border-slate-100 my-1">

                                                <!-- Global Revert Phase Button -->
                                                <form action="{{ route('admin.updateStatus', $data->id) }}" method="POST"
                                                    id="revertPhaseForm-{{ $data->id }}">
                                                    @csrf
                                                    <input type="hidden" name="classification" value="Revert Phase">
                                                    <button type="button"
                                                        onclick="confirmRevertPhase('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }}, '{{ $data->Status }}')"
                                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-red-600 rounded-lg transition-colors text-left">
                                                        <i class="fas fa-undo-alt w-4"></i> Step Backward (Undo)
                                                    </button>
                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-400">
                                    <i class="fas fa-folder-open text-4xl mb-4 text-slate-300"></i>
                                    <p>No active review or revision protocols found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($datas->total() > 0)
            <div class="p-6 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500">
                <div>
                    Showing <span class="font-bold text-slate-700">{{ $datas->firstItem() ?? 0 }}</span> - <span
                        class="font-bold text-slate-700">{{ $datas->lastItem() ?? 0 }}</span> of <span
                        class="font-bold text-slate-700">{{ $datas->total() }}</span>
                </div>
                <div class="flex gap-2 filter-pagination">
                    <!-- Previous Page Link -->
                    @if ($datas->onFirstPage())
                        <span class="opacity-50 cursor-not-allowed text-slate-400"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $datas->appends(request()->except('page'))->previousPageUrl() }}"
                            class="text-slate-600 hover:text-[#8B0000] transition-colors"><i
                                class="fas fa-chevron-left"></i></a>
                    @endif

                    <!-- Next Page Link -->
                    @if ($datas->hasMorePages())
                        <a href="{{ $datas->appends(request()->except('page'))->nextPageUrl() }}"
                            class="text-slate-600 hover:text-[#8B0000] transition-colors"><i
                                class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="opacity-50 cursor-not-allowed text-slate-400"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
            @endif
        </div>
