<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Revisions</h1>
                <p class="text-slate-500 mt-2 text-sm">Manage protocols requiring or submitting revisions.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <form action="{{ route('admin.revisions') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search revisions..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-64 shadow-sm">
                    <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#8B0000]">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100">
            <div class="overflow-x-auto min-h-[400px] overflow-y-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Protocol ID</th>
                            <th class="p-6">Research Title</th>
                            <th class="p-6">Researcher</th>
                            <th class="p-6">Last Updated</th>
                            <th class="p-6">Review Type</th>
                            <th class="p-6">Status</th>
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
                                <p class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-[#8B0000] transition-colors" title="{{ $data->Study_Protocol_title }}">
                                    {{ $data->Study_Protocol_title }}
                                </p>
                            </td>
                            <td class="p-6">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                        {{ substr($data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">
                                            {{ $data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'Unknown' }} 
                                            {{ $data->researcher->user->last_name ?? $data->user->last_name ?? '' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400">
                                            {{ $data->researcher->user->email ?? $data->user->email ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <i class="far fa-clock text-slate-400"></i>
                                    {{ $data->updated_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="p-6">
                                @if($data->Review_Type)
                                    @php
                                        $rtColors = [
                                            'Exempt Review' => 'text-emerald-600',
                                            'Expedited Review' => 'text-amber-600',
                                            'Full Board Review' => 'text-red-600',
                                        ];
                                        $rtColor = $rtColors[$data->Review_Type] ?? 'text-slate-500';
                                    @endphp
                                    <div class="text-sm font-bold {{ $rtColor }}">
                                        {{ $data->Review_Type }}
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">—</span>
                                @endif
                            </td>
                            <td class="p-6">
                                @php
                                    $statusColors = [
                                        'Waiting for Revision' => 'text-orange-600',
                                        'Revision Submitted' => 'text-purple-600',
                                        'Corrections Submitted' => 'text-purple-600',
                                        'Checking of Revisions' => 'text-indigo-600',
                                        'Panel Deliberation' => 'text-pink-600',
                                    ];
                                    $colorClass = $statusColors[$data->Status] ?? 'text-slate-500';
                                @endphp
                                <div class="text-sm font-bold {{ $colorClass }}">
                                    {{ $data->Status }}
                                </div>
                            </td>
                            <td class="p-6 text-right relative">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <!-- SIDEBAR DRAWER -->
                                    <div x-show="open" x-cloak style="display: none;" class="relative z-[100]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
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
                                                                        <span class="font-medium truncate">{{ $data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'Unknown' }} {{ $data->researcher->user->last_name ?? $data->user->last_name ?? '' }}</span>
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
                                                            <a href="{{ route('admin.view_files', $data->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                                <i class="fas fa-eye w-4 text-center"></i> View Files
                                                            </a>
                                                            <button onclick="openRevisionStatusModal('{{ $data->id }}', '{{ addslashes($data->Study_Protocol_title) }}', '{{ addslashes($data->Status) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                                <i class="fas fa-sync-alt w-4 text-center"></i> Update Status
                                                            </button>
                                                            
                                                            <button onclick='openRevisionLogsModal(@json($data->revisionLogs))' class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                                <i class="fas fa-history w-4 text-center"></i> View Logs
                                                            </button>

                                                            {{-- RC Letter Actions - Always visible --}}
                                                            @php
                                                                $allLetters = $data->files->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review'])
                                                                    ->merge($data->adminFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review']))
                                                                    ->sortByDesc('created_at');
                                                            @endphp

                                                            @if($allLetters->isNotEmpty())
                                                                @php
                                                                    $currentLetter = $allLetters->filter(fn($l) => in_array($l->filetype, ['Result of Review (Admin Generated)', 'recommendation letter']))->first();
                                                                    $previousLetters = $allLetters->filter(fn($l) => $l->filetype === 'Archived Result of Review');
                                                                @endphp

                                                                @if($currentLetter)
                                                                    <a href="{{ route('admin.recommendation.view_file', $currentLetter->id) }}"
                                                                        target="_blank"
                                                                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                                        <i class="fas fa-file-pdf w-4 text-center"></i> View Current Recommendation Letter
                                                                    </a>
                                                                @endif

                                                                @if($previousLetters->isNotEmpty())
                                                                    <div class="ml-6 border-l-2 border-slate-100 pl-2 space-y-0.5 mb-1">
                                                                        @foreach($previousLetters as $letter)
                                                                            <a href="{{ route('admin.recommendation.view_file', $letter->id) }}"
                                                                                target="_blank"
                                                                                class="flex items-center gap-3 px-2 py-2 text-[11px] font-medium text-slate-400 hover:bg-slate-50 hover:text-slate-600 rounded-lg transition-colors">
                                                                                <i class="fas fa-file-archive w-4 text-center text-[10px]"></i>
                                                                                <span class="truncate">Previous Letter</span>
                                                                                <span class="text-[9px] text-slate-300 font-mono whitespace-nowrap ml-auto">{{ $letter->created_at->format('M d, Y') }}</span>
                                                                            </a>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            @endif

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
                                <p>No revisions found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-slate-100">
                {{ $datas->links() }}
            </div>
        </div>
    </div>

    @include('admin.partials.revision_status_modal')
    
    <!-- Revision Logs Modal -->
    <div id="revisionLogsModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('revisionLogsModal').classList.add('hidden')"></div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-history text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl font-semibold leading-6 text-slate-900" id="modal-title">Revision History</h3>
                                <div class="mt-4 max-h-[60vh] overflow-y-auto space-y-4 pr-2" id="logsContainer">
                                    <!-- Logs will be injected here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick="document.getElementById('revisionLogsModal').classList.add('hidden')"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openRevisionLogsModal(logs) {
            const container = document.getElementById('logsContainer');
            const modal = document.getElementById('revisionLogsModal');
            
            container.innerHTML = ''; // Clear previous logs
            
            if (logs.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-slate-500">
                        <i class="fas fa-info-circle text-2xl mb-2 text-slate-300"></i>
                        <p>No revision logs found for this protocol.</p>
                    </div>
                `;
            } else {
                logs.forEach(log => {
                    const date = new Date(log.created_at).toLocaleString();
                    const message = log.message || '<em class="text-slate-400">No message provided</em>';
                    const userName = log.user ? `${log.user.first_name} ${log.user.last_name}` : 'Unknown User';
                    
                    const logItem = `
                        <div class="relative pl-6 border-l-2 border-slate-200 pb-2 last:pb-0">
                            <div class="absolute -left-[5px] top-1 w-2.5 h-2.5 rounded-full bg-slate-300 ring-4 ring-white"></div>
                            <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-xs font-bold text-slate-700">${userName}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">${date}</span>
                                </div>
                                <p class="text-sm text-slate-600 whitespace-pre-wrap">${message}</p>
                            </div>
                        </div>
                    `;
                    container.innerHTML += logItem;
                });
            }
            
            modal.classList.remove('hidden');
        }
    </script>
</x-admin_layout>