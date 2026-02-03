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
                                @php
                                    $statusColors = [
                                        'Waiting for Revision' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'Revision Submitted' => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'Checking of Revisions' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                        'Panel Deliberation' => 'bg-pink-50 text-pink-700 border-pink-100',
                                    ];
                                    $colorClass = $statusColors[$data->Status] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }} inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                                    {{ $data->Status }}
                                </span>
                            </td>
                            <td class="p-6 text-right relative">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div x-show="open" 
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                                         style="display: none;">
                                        <div class="p-1">
                                            <a href="{{ route('admin.view_files', $data->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                <i class="fas fa-eye w-4"></i> View Files
                                            </a>
                                            <button onclick="openRevisionStatusModal('{{ $data->id }}', '{{ addslashes($data->Study_Protocol_title) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                <i class="fas fa-sync-alt w-4"></i> Update Status
                                            </button>
                                            
                                            <button onclick='openRevisionLogsModal(@json($data->revisionLogs))' class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                <i class="fas fa-history w-4"></i> View Logs
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">
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