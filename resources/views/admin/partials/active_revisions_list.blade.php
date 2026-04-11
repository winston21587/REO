<div class="flex flex-col h-full min-h-[400px]">
<div class="flex-grow">
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
                @if($data->Review_Type)
                <span class="inline-block mt-1 px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">
                    {{ $data->Review_Type }}
                </span>
                @endif
            </td>
            <td class="p-6">
                <div class="flex items-center gap-3">
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
                        'Waiting for Revision' => 'text-orange-600',
                        'Revision Submitted' => 'text-purple-600',
                        'Corrections Submitted' => 'text-emerald-600',
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
                    
                    <div x-show="open" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                         style="display: none;">
                        <div class="p-1">
                            <a href="{{ route('admin.view_files', $data->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                <i class="fas fa-eye w-4"></i> View Files
                            </a>
                            <button onclick="openRevisionStatusModal('{{ $data->id }}', '{{ addslashes($data->Study_Protocol_title) }}', '{{ addslashes($data->Status) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
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
                <p>No revisions found matching the selected filters.</p>
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
