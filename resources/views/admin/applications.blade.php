<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Active Protocols</h1>
                <p class="text-slate-500 mt-2 text-sm">Monitoring Initial Reviews and Revisions.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <form action="{{ route('admin.applications') }}" method="GET" class="relative">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search protocols..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-64 shadow-sm">
                    <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#8B0000]">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <div class="relative" x-data="{ openFilter: false }">
                    <button @click="openFilter = !openFilter" @click.away="openFilter = false" class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                        <i class="fas fa-filter"></i> {{ request('status') ? 'Filtered' : 'Filter' }}
                    </button>
                    <div x-show="openFilter" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                         style="display: none;">
                        <div class="p-1">
                            <a href="{{ route('admin.applications') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Show All (In Review)</a>
                            <div class="h-px bg-slate-100 my-1"></div>
                            <a href="{{ route('admin.applications', ['status' => 'For Initial Review']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">For Initial Review</a>
                            <a href="{{ route('admin.applications', ['status' => 'Waiting for Revision']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Waiting for Revision</a>
                            <a href="{{ route('admin.applications', ['status' => 'Submission of Revisions / Resubmission']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Resubmitted</a>
                            <a href="{{ route('admin.applications', ['status' => 'Checking of Revisions']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Checking of Revisions</a>
                        </div>
                    </div>
                </div>
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
                                <p class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-[#8B0000] transition-colors" title="{{ $data->Study_Protocol_title }}">
                                    {{ $data->Study_Protocol_title }}
                                </p>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                        {{ substr($data->researcher->user->first_name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">{{ $data->researcher->user->first_name ?? '' }} {{ $data->researcher->user->last_name ?? 'Unknown' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $data->researcher->user->email ?? '' }}</p>
                                    </div>
                                </div>
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
                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div x-show="open" 
                                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                                         style="display: none;">
                                        <div class="p-1">
                                            <a href="{{ route('admin.view_files', $data->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                <i class="fas fa-eye w-4"></i> View Details
                                            </a>
                                            
                                            @if($data->Status === 'Complete - Awaiting Hardcopy')
                                                <form action="{{ route('admin.updateStatus', $data->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Hardcopy Received - For Initial Review">
                                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-green-600 rounded-lg transition-colors text-left">
                                                        <i class="fas fa-file-import w-4"></i> Receive Hardcopy
                                                    </button>
                                                </form>
                                            @else
                                                <button @click="open = false; openStatusModal('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }})" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
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
                                                <a href="{{ route('admin.recommendation.view_saved', $data->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                    <i class="fas fa-file-pdf w-4"></i> View RC Letter
                                                </a>
                                                
                                                <!-- Proceed to Revision (Only if not yet finalized) -->
                                                @if(!in_array($data->Status, ['Panel Deliberation', 'Waiting for Revision', 'Revision Submitted', 'Checking of Revisions']))
                                                    <form action="{{ route('admin.recommendation.finalize', $data->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to finalize this review and proceed to the next stage?');">
                                                        @csrf
                                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-green-600 rounded-lg transition-colors text-left">
                                                            <i class="fas fa-check-circle w-4"></i> Proceed to Revision
                                                        </button>
                                                    </form>
                                                @endif
                                            @elseif($data->Review_Type)
                                                <!-- Generate Letter -->
                                                <a href="{{ route('admin.recommendation.form', $data->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                    <i class="fas fa-file-signature w-4"></i> Result of Review
                                                </a>
                                            @endif

                                            <button @click="open = false; $dispatch('open-feature-modal')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                <i class="fas fa-users-cog w-4"></i> Assign Reviewers
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
                                <p>No active review or revision protocols found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Include Status Update Modal -->
    @include('admin.partials.status_modal')

    <!-- Feature Not Available Modal -->
    <div x-data="{ open: false }" 
         @open-feature-modal.window="open = true" 
         class="relative z-[9999]" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true"
         style="display: none;"
         x-show="open">
        
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm"
                     x-show="open"
                     @click.away="open = false"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-info-circle text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Feature Coming Soon</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500">This feature is yet to be added.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" @click="open = false" class="inline-flex w-full justify-center rounded-xl bg-[#8B0000] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-900 sm:ml-3 sm:w-auto transition-colors">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin_layout>