<div class="flex flex-col h-full min-h-[400px]">
<div class="flex-grow">
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
                @if($data->Review_Type)
                    @php
                        $rtColors = [
                            'Exempt Review' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Expedited Review' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Full Board Review' => 'bg-red-50 text-red-700 border-red-200',
                        ];
                        $rtColor = $rtColors[$data->Review_Type] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border {{ $rtColor }}">
                        {{ $data->Review_Type }}
                    </span>
                @else
                    <span class="text-xs text-slate-400 italic">—</span>
                @endif
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

                                            {{-- RC Letter Actions --}}
                                            @php
                                                $recLetter = $data->files->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->first()
                                                    ?? $data->adminFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->first();
                                            @endphp

                                            @if($recLetter)
                                            <a href="{{ route('admin.recommendation.view_saved', $data->id) }}"
                                                target="_blank"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                <i class="fas fa-file-pdf w-4 text-center"></i> View Recommendation Letter
                                            </a>
                                            @endif

                                            <a href="{{ route('admin.recommendation.form', $data->id) }}"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                <i class="fas fa-file-signature w-4 text-center"></i> Generate Recommendation Letter
                                            </a>
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
