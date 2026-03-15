        <div class="bg-white rounded-2xl shadow-xl border border-slate-100">
            <div class="overflow-x-auto min-h-[400px] overflow-y-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Protocol ID</th>
                            <th class="p-6">Research Title</th>
                            <th class="p-6">Researcher</th>
                            <th class="p-6">Reviewers</th>
                            <th class="p-6">Submission Date</th>
                            <th class="p-6">Status / Review Type</th>
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
                                        $statusColors = [
                                            'For Initial Review' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'Complete - Awaiting Hardcopy' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                            'Hardcopy Received - For Initial Review' => 'bg-teal-50 text-teal-700 border-teal-100',
                                            'Waiting for Revision' => 'bg-orange-50 text-orange-700 border-orange-100',
                                            'Revision Submitted' => 'bg-purple-50 text-purple-700 border-purple-100',
                                            'Panel Deliberation' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                            'Approved' => 'bg-green-50 text-green-700 border-green-100',
                                            'Submission of Revisions / Resubmission' => 'bg-purple-50 text-purple-700 border-purple-100',
                                            'Checking of Revisions' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                        ];
                                        $colorClass = $statusColors[$data->Status] ?? 'bg-slate-50 text-slate-700 border-slate-100';

                                        // Prioritize Status for specific workflow steps, otherwise use Review Type if available
                                        $displayStatus = $data->Status;
                                        if ($data->Review_Type && !in_array($data->Status, ['Complete - Awaiting Hardcopy', 'Hardcopy Received - For Initial Review'])) {
                                            $displayStatus = $data->Review_Type;
                                        }
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }}">
                                        {{ $displayStatus }}
                                    </span>
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

                                                @if($data->Status === 'Complete - Awaiting Hardcopy')
                                                    <form action="{{ route('admin.updateStatus', $data->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status"
                                                            value="Hardcopy Received - For Initial Review">
                                                        <button type="submit"
                                                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-green-600 rounded-lg transition-colors text-left">
                                                            <i class="fas fa-file-import w-4"></i> Receive Hardcopy
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.updateStatus', $data->id) }}" method="POST"
                                                        id="undoCompleteForm-{{ $data->id }}">
                                                        @csrf
                                                        <input type="hidden" name="classification" value="Undo Complete">
                                                        <button type="button"
                                                            onclick="confirmUndoComplete('{{ $data->id }}', '{{ addslashes($data->Study_Protocol_title) }}')"
                                                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-red-600 rounded-lg transition-colors text-left">
                                                            <i class="fas fa-undo w-4"></i> Undo / Revert
                                                        </button>
                                                    </form>
                                                @else
                                                    <button
                                                        @click="open = false; openStatusModal('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }})"
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
                                                    @endif
                                                @elseif($data->Review_Type)
                                                    <!-- Generate Letter -->
                                                    <a href="{{ route('admin.recommendation.form', $data->id) }}"
                                                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                        <i class="fas fa-file-signature w-4"></i> Result of Review
                                                    </a>
                                                @endif

                                                <button @click="open = false; $dispatch('open-assign-modal', { id: '{{ $data->id }}', title: {{ json_encode($data->Study_Protocol_title) }}, assigned: {{ json_encode($data->assigned_reviewers ?? []) }} })"
                                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                    <i class="fas fa-users-cog w-4"></i> Assign Reviewers
                                                </button>
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
