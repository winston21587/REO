        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 flex flex-col h-full min-h-[400px]">
            <div class="overflow-x-auto flex-grow overflow-y-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Protocol ID</th>
                            <th class="p-6">Research Title</th>
                            <th class="p-6">Researcher</th>
                            <th class="p-6">Reviewers</th>
                            <th class="p-6">Submission Date</th>
                            <th class="p-6">Document Status</th>
                            <th class="p-6">Reviewer Status</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($datas as $data)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="p-6">
                                    <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                        #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
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
                                            'For Initial Review' => 'bg-blue-50 text-blue-700 border-blue-100', // legacy
                                            'Incomplete - Awaiting Hardcopy' => 'bg-red-50 text-red-700 border-red-100',
                                            'Incomplete Hardcopy' => 'bg-red-50 text-red-700 border-red-100',
                                            'Hardcopy Received' => 'bg-teal-50 text-teal-700 border-teal-100',
                                        ];
                                        $docClass = $docColors[$docStatus] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $docClass }}">
                                            {{ $docStatus }}
                                        </span>
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
                                            'Pending Assignment' => 'bg-slate-50 text-slate-500 border-slate-200',
                                            'Reviewer Assigned' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'Under Review' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                            'Reviewed' => 'bg-green-50 text-green-700 border-green-100',
                                        ];
                                        $revClass = $revColors[$revStatus] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $revClass }}">
                                            {{ $revStatus }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-6 text-right relative">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.away="open = false"
                                            class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>

                                        <div x-show="open"
                                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                                            style="display: none;">
                                            <div class="p-1">
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
                                                    <form action="{{ route('admin.updateStatus', $data->id) }}" method="POST"
                                                        id="undoCompleteForm-{{ $data->id }}">
                                                        @csrf
                                                        <input type="hidden" name="classification" value="Undo Complete">
                                                        <button type="button"
                                                            onclick="confirmUndoComplete('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }})"
                                                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-red-600 rounded-lg transition-colors text-left">
                                                            <i class="fas fa-undo w-4"></i> Undo / Revert
                                                        </button>
                                                    </form>
                                                @else
                                                    <button
                                                        @click="open = false; openStatusModal('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }}, {{ json_encode($data->Status) }})"
                                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                        <i class="fas fa-sync-alt w-4"></i> Update Status
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
                                                        <i class="fas fa-file-pdf w-4"></i> View RC Letter
                                                    </a>

                                                    <!-- Proceed to Revision (Only if not yet finalized) -->
                                                    @if(!in_array($data->Status, ['Panel Deliberation', 'Waiting for Revision', 'Revision Submitted', 'Checking of Revisions']))
                                                        @if($data->is_or_verified)
                                                        <form id="finalizeForm-{{ $data->id }}"
                                                            action="{{ route('admin.recommendation.finalize', $data->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="button"
                                                                onclick="confirmFinalize('{{ $data->id }}', '{{ addslashes($data->Study_Protocol_title) }}')"
                                                                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-green-600 rounded-lg transition-colors text-left">
                                                                <i class="fas fa-check-circle w-4"></i> Proceed to Revision
                                                            </button>
                                                        </form>
                                                        @else
                                                        <div title="Official Receipt must be received and verified before proceeding to revision."
                                                             class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-300 cursor-not-allowed rounded-lg select-none">
                                                            <i class="fas fa-lock w-4"></i>
                                                            <span>Proceed to Revision</span>
                                                            <span class="ml-auto text-[9px] font-bold bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded-full border border-orange-200 leading-none">
                                                                No Receipt
                                                            </span>
                                                        </div>
                                                        <button
                                                            type="button"
                                                            @php
                                                                $researcherUserId = optional(optional($data->researcher)->user)->id;
                                                                $alreadyNotified = $researcherUserId && \App\Models\UserNotification::where('user_id', $researcherUserId)
                                                                    ->where('research_id', $data->id)
                                                                    ->where('type', 'receipt_reminder')
                                                                    ->where('is_read', false)
                                                                    ->where('created_at', '>=', \Carbon\Carbon::now()->subHours(24))
                                                                    ->exists();
                                                            @endphp
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
                                                    @endif
                                                <!-- Generate Recommendation Letter (Only when Reviewed) -->
                                                @if($data->Status === 'Reviewed')
                                                    <a href="{{ route('admin.recommendation.form', $data->id) }}"
                                                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                        <i class="fas fa-file-signature w-4"></i> Recommendation Letter
                                                    </a>
                                                @endif

                                                <!-- Assign Reviewers (Only after Hardcopy Received) -->
                                                @if(in_array($data->Status, ['Hardcopy Received', 'Reviewer Assigned', 'Under Review']))
                                                    <button @click="open = false; $dispatch('open-assign-modal', { id: '{{ $data->id }}', title: {{ json_encode($data->Study_Protocol_title) }}, assigned: {{ json_encode($data->assigned_reviewers ?? []) }} })"
                                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                        <i class="fas fa-users-cog w-4"></i> Assign Reviewers
                                                    </button>
                                                @endif
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
