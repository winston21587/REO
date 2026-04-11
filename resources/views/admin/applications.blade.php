<x-admin_layout>

    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">

        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Active Protocols</h1>
                <p class="text-slate-500 mt-2 text-sm">Monitoring Initial Reviews and Revisions.</p>
            </div>
            <div class="flex gap-4 mt-4 md:mt-0 w-full md:w-auto">
                <form action="{{ route('admin.applications') }}" method="GET" class="relative flex w-full gap-4" id="activeProtocolsForm">
                    <!-- Search Input -->
                    <div class="relative flex-1 md:w-64">
                        <input type="text" name="search" id="search_input" value="{{ request('search') }}" placeholder="Search protocols..."
                            class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm bg-white">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>

                    <!-- Filter Drawer Toggle -->
                    <div class="relative" x-data="{ expanded: sessionStorage.getItem('activeProtocolsFilterExpanded') === 'true' }" x-init="$watch('expanded', value => sessionStorage.setItem('activeProtocolsFilterExpanded', value))">
                        <button type="button" @click="expanded = true"
                            class="flex items-center gap-2 px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm transition-colors w-[150px] justify-between">
                            <span><i class="fas fa-filter mr-1 text-slate-400"></i> Filter</span>
                            <i class="fas fa-bars text-xs text-slate-400 transition-transform" :class="expanded ? 'rotate-90' : ''"></i>
                        </button>

                        <!-- Advanced Filter Drawer -->
                        <div x-show="expanded" style="display: none;" class="relative z-[100]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
                            <!-- Background backdrop -->
                            <div x-show="expanded" 
                                 x-transition:enter="ease-in-out duration-300" 
                                 x-transition:enter-start="opacity-0" 
                                 x-transition:enter-end="opacity-100" 
                                 x-transition:leave="ease-in-out duration-300" 
                                 x-transition:leave-start="opacity-100" 
                                 x-transition:leave-end="opacity-0" 
                                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
                                 @click="expanded = false"></div>

                            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                                <div class="absolute inset-0 overflow-hidden">
                                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10"
                                         x-show="expanded"
                                         x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                                         x-transition:enter-start="translate-x-full"
                                         x-transition:enter-end="translate-x-0"
                                         x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                                         x-transition:leave-start="translate-x-0"
                                         x-transition:leave-end="translate-x-full">
                                        
                                        <div class="pointer-events-auto w-screen max-w-xs flex flex-col h-full bg-white shadow-2xl">
                                            <!-- Drawer Header -->
                                            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 flex-none text-left">
                                                <h3 class="font-heading font-extrabold text-lg text-slate-800 leading-tight">Apply Filters</h3>
                                                <button type="button" @click="expanded = false" class="text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-colors w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-full mt-0.5">
                                                    <i class="fas fa-times text-lg"></i>
                                                </button>
                                            </div>

                                            <!-- Drawer Filters List -->
                                            <div class="flex-1 overflow-y-auto w-full pb-10">
                            <!-- Sort Section -->
                            <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Sort By</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="sort_by" value="created_at" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('sort_by', 'created_at') == 'created_at' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Submission Date</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="sort_by" value="Title" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('sort_by') == 'Title' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Title</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Review Type Section -->
                            <div class="p-3 border-b border-slate-100">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Review Type</label>
                                <div class="space-y-2">
                                    @php $selectedTypes = request('review_types', []); @endphp
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="review_types[]" value="Exempt Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Exempt Review', $selectedTypes) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Exempt</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="review_types[]" value="Expedited Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Expedited Review', $selectedTypes) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Expedited</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="review_types[]" value="Full Board Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Full Board Review', $selectedTypes) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Full Board</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Document Status Section -->
                            <div class="p-3 border-b border-slate-100">
                                <label class="block text-[10px] font-extrabold text-[#8B0000] uppercase tracking-widest mb-2">Document Status</label>
                                <div class="space-y-2">
                                    @php $docStatuses = request('doc_statuses', []); @endphp
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="doc_statuses[]" value="Incomplete - Awaiting Hardcopy" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Incomplete - Awaiting Hardcopy', $docStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors text-balance leading-snug">Incomplete - Awaiting Hardcopy</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="doc_statuses[]" value="Incomplete Hardcopy" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Incomplete Hardcopy', $docStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Incomplete Hardcopy</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="doc_statuses[]" value="Hardcopy Received" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000]" {{ in_array('Hardcopy Received', $docStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Hardcopy Received</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Reviewer Status Section -->
                            <div class="p-3 border-b border-slate-100">
                                <label class="block text-[10px] font-extrabold text-blue-700 uppercase tracking-widest mb-2">Reviewer Status</label>
                                <div class="space-y-2">
                                    @php $revStatuses = request('rev_statuses', []); @endphp
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="rev_statuses[]" value="Reviewer Assigned" class="auto-submit-input rounded text-blue-600 focus:ring-blue-600" {{ in_array('Reviewer Assigned', $revStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-blue-600 transition-colors">Reviewer Assigned</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="rev_statuses[]" value="Under Review" class="auto-submit-input rounded text-blue-600 focus:ring-blue-600" {{ in_array('Under Review', $revStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-blue-600 transition-colors">Under Review</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="rev_statuses[]" value="Reviewed" class="auto-submit-input rounded text-blue-600 focus:ring-blue-600" {{ in_array('Reviewed', $revStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-blue-600 transition-colors">Reviewed</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Assignment Section -->
                            <div class="p-3 bg-slate-50/50">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Reviewer Assignment</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="assignment" value="All" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('assignment', 'All') == 'All' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Show All Protocols</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="assignment" value="Unassigned" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('assignment') == 'Unassigned' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Unassigned Only</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="assignment" value="Assigned" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('assignment') == 'Assigned' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Assigned Only</span>
                                    </label>
                                </div>
                            </div>
                                            </div> <!-- End Drawer Filters List -->
                                        </div> <!-- End pointer-events-auto bg-white shadow-2xl -->
                                    </div> <!-- End pointer-events-none flex -->
                                </div> <!-- End absolute inset-0 -->
                            </div> <!-- End fixed inset-0 pointer-events-none -->
                        </div> <!-- End Drawer relative z-[100] modal -->
                    </div> <!-- End Filter Toggle relative x-data -->
                </form> <!-- End Form -->
            </div> <!-- End Header Right flex -->
        </div>

        <div id="active-protocols-wrapper" class="flex-1 flex flex-col min-h-[400px]">
            @include('admin.partials.active_protocols_list')
        </div>
    </div>



    <!-- Include Status Update Modal -->
    @include('admin.partials.status_modal')

    <!-- Assign Reviewer Modal -->
    @php
        $reviewerColleges = $reviewers->pluck('college')->filter()->unique()->sort()->values();
    @endphp
    <div x-data="{
            open: false,
            protocolId: '',
            protocolTitle: '',
            assigned: [],
            search: '',
            collegeFilter: '',
            expandedReviewer: null
         }"
         @open-assign-modal.window="open = true; protocolId = $event.detail.id; protocolTitle = $event.detail.title; assigned = Array.isArray($event.detail.assigned) ? $event.detail.assigned : []; search = ''; collegeFilter = ''; expandedReviewer = null;"
         class="relative z-[9999]"
         aria-labelledby="assign-modal-title" role="dialog" aria-modal="true" style="display: none;" x-show="open">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" x-show="open"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="open = false"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-slate-100"
                    x-show="open" @click.away="open = false"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                    <form :action="'{{ url('admin/applications') }}/' + protocolId + '/assign-reviewers'" method="POST">
                        @csrf

                        <!-- Modal Header -->
                        <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#8B0000] to-red-700 flex items-center justify-center shadow-lg shadow-red-900/20 flex-shrink-0">
                                        <i class="fas fa-users-cog text-white text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 leading-tight" id="assign-modal-title">Assign Reviewer</h3>
                                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-1" x-text="protocolTitle"></p>
                                    </div>
                                </div>
                                <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center transition-colors flex-shrink-0 mt-0.5">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <!-- Search + Filter Row -->
                            <div class="flex gap-2 mt-4">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-slate-400 text-sm"></i>
                                    </div>
                                    <input type="text" x-model="search" placeholder="Search by name or expertise..."
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all">
                                </div>
                                <!-- College Filter -->
                                <div class="relative flex-shrink-0">
                                    <select x-model="collegeFilter"
                                        class="h-full pl-3 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all cursor-pointer appearance-none font-medium">
                                        <option value="">All Colleges</option>
                                        @foreach($reviewerColleges as $college)
                                            <option value="{{ strtolower($college) }}">{{ $college }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-slate-400 text-[10px]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reviewer Cards List -->
                        <div class="px-4 py-3 max-h-[320px] overflow-y-auto space-y-2 custom-scrollbar" x-ref="reviewerCards">
                            @forelse($reviewers as $reviewer)
                                @php
                                    $initials = strtoupper(substr($reviewer->first_name ?? 'U', 0, 1) . substr($reviewer->last_name ?? '', 0, 1));
                                    $activeTitles = \App\Models\Research_title::whereIn('Status', ['Reviewer Assigned','Under Review'])
                                        ->whereJsonContains('assigned_reviewers', (string) $reviewer->id)
                                        ->get(['id','Study_Protocol_title']);
                                    $activeCount = $activeTitles->count();
                                    $colorPalette = ['from-sky-500 to-blue-600', 'from-violet-500 to-purple-600', 'from-emerald-500 to-teal-600', 'from-amber-500 to-orange-600', 'from-rose-500 to-pink-600'];
                                    $avatarColor = $colorPalette[$reviewer->id % count($colorPalette)];
                                    $reviewerCollege = $reviewer->college ?? null;
                                    $searchableText = strtolower($reviewer->first_name . ' ' . $reviewer->last_name . ' ' . ($reviewerCollege ?? '') . ' ' . implode(' ', $reviewer->reviewer?->expertise ?? []));
                                @endphp
                                <div
                                    x-show="
                                        (search === '' || '{{ $searchableText }}'.includes(search.toLowerCase())) &&
                                        (collegeFilter === '' || '{{ strtolower($reviewerCollege ?? '') }}' === collegeFilter)
                                    "
                                    class="rounded-xl border-2 transition-all overflow-hidden"
                                    :class="assigned.map(String).includes('{{ $reviewer->id }}') ? 'border-[#8B0000] shadow-md shadow-red-900/10' : 'border-slate-100 hover:border-slate-200'">

                                    <!-- Card Top: Click to select -->
                                    <label class="flex items-start gap-4 p-4 cursor-pointer group"
                                           :class="assigned.map(String).includes('{{ $reviewer->id }}') ? 'bg-red-50/40' : 'bg-white hover:bg-slate-50/80'">

                                        <input type="radio" name="reviewers[]" value="{{ $reviewer->id }}"
                                            x-model="assigned"
                                            :checked="assigned.map(String).includes('{{ $reviewer->id }}')"
                                            @change="assigned = ['{{ $reviewer->id }}']"
                                            class="sr-only">

                                        <!-- Avatar -->
                                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $avatarColor }} flex items-center justify-center text-white text-sm font-extrabold flex-shrink-0 shadow-md">
                                            {{ $initials }}
                                        </div>

                                        <!-- Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-slate-800 leading-tight">{{ $reviewer->first_name }} {{ $reviewer->last_name }}</p>
                                                    @if($reviewerCollege)
                                                        <p class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                                                            <i class="fas fa-university text-[9px] text-slate-400"></i>
                                                            {{ $reviewerCollege }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <!-- Workload Badge -->
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full flex-shrink-0
                                                    {{ $activeCount === 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($activeCount <= 2 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                                    {{ $activeCount }} reviewing
                                                </span>
                                            </div>

                                            @if($reviewer->reviewer && !empty($reviewer->reviewer->expertise))
                                                <div class="flex flex-wrap gap-1 mt-2">
                                                    @foreach(array_slice($reviewer->reviewer->expertise, 0, 3) as $exp)
                                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-semibold rounded-md border border-slate-200">{{ $exp }}</span>
                                                    @endforeach
                                                    @if(count($reviewer->reviewer->expertise) > 3)
                                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-400 text-[10px] font-semibold rounded-md border border-slate-200">+{{ count($reviewer->reviewer->expertise) - 3 }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Selected checkmark -->
                                        <div class="flex-shrink-0 mt-0.5"
                                             :class="assigned.map(String).includes('{{ $reviewer->id }}') ? 'opacity-100' : 'opacity-0 group-hover:opacity-40'"
                                             style="transition: opacity 0.15s">
                                            <div class="w-5 h-5 rounded-full bg-[#8B0000] flex items-center justify-center shadow-sm">
                                                <i class="fas fa-check text-white text-[9px]"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Active Titles Accordion -->
                                    @if($activeCount > 0)
                                        <div class="border-t border-dashed px-4 py-0"
                                             :class="assigned.map(String).includes('{{ $reviewer->id }}') ? 'border-red-200' : 'border-slate-100'">
                                            <button type="button"
                                                @click.prevent="expandedReviewer = expandedReviewer === '{{ $reviewer->id }}' ? null : '{{ $reviewer->id }}'"
                                                class="w-full flex items-center justify-between py-2.5 text-left group/acc">
                                                <span class="text-[11px] font-bold text-slate-500 group-hover/acc:text-slate-700 transition-colors flex items-center gap-1.5">
                                                    <i class="fas fa-folder-open text-[9px] text-slate-400"></i>
                                                    Currently Reviewing ({{ $activeCount }})
                                                </span>
                                                <i class="fas fa-chevron-down text-[9px] text-slate-400 transition-transform"
                                                   :class="expandedReviewer === '{{ $reviewer->id }}' ? 'rotate-180' : ''"></i>
                                            </button>
                                            <div x-show="expandedReviewer === '{{ $reviewer->id }}'" x-collapse class="pb-3 space-y-1.5">
                                                @foreach($activeTitles as $aTitle)
                                                    <div class="flex items-start gap-2 py-1.5 px-2 rounded-lg bg-slate-50 border border-slate-100">
                                                        <div class="w-1 h-1 rounded-full bg-[#8B0000] mt-1.5 flex-shrink-0"></div>
                                                        <p class="text-[11px] text-slate-600 font-medium leading-snug line-clamp-2">{{ $aTitle->Study_Protocol_title }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="py-10 text-center text-slate-400">
                                    <i class="fas fa-user-slash text-3xl mb-3 text-slate-300"></i>
                                    <p class="text-sm font-medium">No reviewers available.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex gap-3">
                            <button type="button" @click="open = false"
                                class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-red-900 transition-colors shadow-lg shadow-red-900/20">
                                <i class="fas fa-user-check mr-1.5"></i> Save Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function confirmFinalize(id, title) {
            Swal.fire({
                title: 'Finalize Review?',
                html: `
                    <div class="text-left mt-2">
                        <p class="text-slate-600 text-sm mb-4">Are you sure you want to proceed with revision for "<b>${title}</b>"? This will notify the researcher.</p>
                        <div class="mb-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Revision Deadline <span class="text-red-500">*</span></label>
                            <input type="date" id="revision-deadline-input" 
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent bg-slate-50">
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Proceed',
                cancelButtonText: 'Cancel',
                scrollbarPadding: false,
                backdrop: `rgba(15, 23, 42, 0.75)`,
                buttonsStyling: false,
                showClass: {
                    popup: 'animate-[fadeInUp_0.3s_ease-out]'
                },
                hideClass: {
                    popup: 'animate-[fadeOutDown_0.3s_ease-in]'
                },
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                    title: 'font-heading text-xl text-slate-800 font-bold pt-4',
                    confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-900 hover:shadow-xl hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition-all outline-none focus:ring-0 mx-2'
                },
                preConfirm: () => {
                    const dateInput = document.getElementById('revision-deadline-input').value;
                    if (!dateInput) {
                        Swal.showValidationMessage('Please select a deadline date');
                        return false;
                    }
                    return dateInput;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedDate = result.value;
                    
                    // Show Loading State
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Please wait while we finalize the review.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        scrollbarPadding: false,
                        backdrop: `rgba(15, 23, 42, 0.75)`,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                            title: 'font-heading text-xl text-slate-800 font-bold',
                            htmlContainer: 'text-slate-600 text-sm'
                        },
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.getElementById('finalizeForm-' + id);
                    
                    // Append hidden input for deadline
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'deadline';
                    hiddenInput.value = selectedDate;
                    form.appendChild(hiddenInput);

                    form.submit();
                }
            });
        }

        async function notifyReceiptRequired(id, title) {
            const swalCommon = {
                scrollbarPadding: false,
                backdrop: `rgba(15, 23, 42, 0.75)`,
                buttonsStyling: false,
                showClass: { popup: 'animate-[fadeInUp_0.3s_ease-out]' },
                hideClass: { popup: 'animate-[fadeOutDown_0.3s_ease-in]' },
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                    title: 'font-heading text-xl text-slate-800 font-bold pt-4',
                    htmlContainer: 'text-slate-600 text-sm mt-2',
                    confirmButton: 'bg-orange-500 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg hover:bg-orange-600 hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition-all outline-none focus:ring-0 mx-2'
                }
            };

            const { value: customMsg, isConfirmed } = await Swal.fire({
                ...swalCommon,
                title: 'Notify Researcher',
                html: `
                    <div class="text-left space-y-3">
                        <p class="text-slate-600 text-sm">Send a reminder to the researcher for <b>"${title}"</b> to submit their Official Receipt (OR).</p>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Custom Message <span class="text-slate-400 font-normal normal-case">(optional)</span></label>
                            <textarea id="notifyMsgInput" rows="3"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none"
                                placeholder="Leave blank to send the default reminder message..."></textarea>
                        </div>
                        <p class="text-[11px] text-slate-400 italic">⚠ A notification can only be sent once every 24 hours per protocol.</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-bell mr-1"></i> Send Notification',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    return document.getElementById('notifyMsgInput')?.value?.trim() || null;
                }
            });

            if (!isConfirmed) return;

            // Send the AJAX request
            try {
                const response = await fetch(`/admin/protocols/${id}/notify-receipt`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            || document.querySelector('input[name="_token"]')?.value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ message: customMsg })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    Swal.fire({
                        ...swalCommon,
                        title: 'Notification Sent!',
                        text: result.message,
                        icon: 'success',
                        confirmButtonText: 'Done',
                        customClass: {
                            ...swalCommon.customClass,
                            confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg hover:bg-red-900 transition-all outline-none focus:ring-0 mx-2'
                        }
                    });
                } else {
                    Swal.fire({
                        ...swalCommon,
                        title: response.status === 429 ? 'Already Notified' : 'Could Not Send',
                        text: result.message || 'An error occurred. Please try again.',
                        icon: response.status === 429 ? 'info' : 'error',
                        confirmButtonText: 'Okay',
                        customClass: {
                            ...swalCommon.customClass,
                            confirmButton: 'bg-slate-700 text-white px-6 py-2.5 rounded-xl font-bold shadow transition-all outline-none focus:ring-0 mx-2'
                        }
                    });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    ...swalCommon,
                    title: 'System Error',
                    text: 'An unexpected error occurred. Please check your connection.',
                    icon: 'error',
                    confirmButtonText: 'Close',
                });
            }
        }

        function confirmHardcopyReceived(id, title, previousRemarks = '') {
            const date = new Date();
            date.setDate(date.getDate() + 2);
            const minDate = date.toISOString().split('T')[0];

            const prevRemarksHtml = previousRemarks ? `
                <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-xl relative overflow-hidden">
                    <h4 class="text-xs font-extrabold text-red-800 uppercase tracking-widest mb-1 relative z-10 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i> Previous Missing Requirements
                    </h4>
                    <p class="text-sm text-red-900 leading-relaxed font-medium relative z-10">${previousRemarks}</p>
                </div>
            ` : '';

            const html = `
                <div class="text-left space-y-4">
                    ${prevRemarksHtml}
                    <p class="text-sm text-slate-600">Please confirm if the submitted hardcopy is complete and valid.</p>
                    
                    <div class="flex gap-4 mb-4">
                        <label class="flex-1 border p-3 rounded-lg cursor-pointer hover:bg-slate-50 border-slate-200" onclick="window.toggleHardcopyIncomplete(false)">
                            <input type="radio" name="hardcopy_status" value="Hardcopy Complete" class="mr-2" checked> Complete
                        </label>
                        <label class="flex-1 border p-3 rounded-lg cursor-pointer hover:bg-slate-50 border-slate-200" onclick="window.toggleHardcopyIncomplete(true)">
                            <input type="radio" name="hardcopy_status" value="Hardcopy Incomplete" class="mr-2"> Incomplete
                        </label>
                    </div>

                    <div id="incompleteFields" class="hidden space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-100 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Re-assign Deadline <span class="text-red-500">*</span></label>
                            <input type="date" id="hc_appointment_date" value="${minDate}" min="${minDate}" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Missing Requirements / Remarks <span class="text-red-500">*</span></label>
                            <textarea id="hc_remarks" rows="3" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none resize-none" placeholder="List missing requirements..."></textarea>
                        </div>
                    </div>
                </div>
            `;

            Swal.fire({
                title: 'Confirm Hardcopy',
                html: html,
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Submit Assessment',
                cancelButtonText: 'Cancel',
                scrollbarPadding: false,
                backdrop: `rgba(15, 23, 42, 0.75)`,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                    title: 'font-heading text-xl text-slate-800 font-bold',
                    confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-900 flex-1 mx-2',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 flex-1 mx-2'
                },
                didOpen: () => {
                    const toggleHardcopyIncomplete = function(show) {
                        const div = document.getElementById('incompleteFields');
                        if(show) { div.classList.remove('hidden'); }
                        else { div.classList.add('hidden'); }
                    };
                    window.toggleHardcopyIncomplete = toggleHardcopyIncomplete;
                    
                    // Attach event listeners to radio inputs just in case clicking label misses
                    document.querySelectorAll('input[name="hardcopy_status"]').forEach(el => {
                        el.addEventListener('change', (e) => {
                            toggleHardcopyIncomplete(e.target.value === 'Hardcopy Incomplete');
                        });
                    });
                },
                preConfirm: () => {
                    const status = document.querySelector('input[name="hardcopy_status"]:checked').value;
                    const data = new FormData();
                    data.append('classification', status);
                    data.append('_token', '{{ csrf_token() }}');

                    if (status === 'Hardcopy Incomplete') {
                        const date = document.getElementById('hc_appointment_date').value;
                        const remarks = document.getElementById('hc_remarks').value;
                        if (!remarks.trim()) {
                            Swal.showValidationMessage('Please provide remarks/missing requirements.');
                            return false;
                        }
                        if (!date) {
                            Swal.showValidationMessage('Please set a re-assignment deadline.');
                            return false;
                        }
                        data.append('appointment_date', date);
                        data.append('remarks', remarks);
                    }
                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('/admin/update-status/' + id, {
                        method: 'POST',
                        body: result.value,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Hardcopy assessment submitted.',
                                icon: 'success',
                                confirmButtonColor: '#8B0000',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'rounded-xl px-4 py-2 bg-[#8B0000] text-white font-bold'
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message || 'Something went wrong', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'An unexpected error occurred.', 'error');
                    });
                }
            });
        }

        function confirmRevertPhase(id, title, currentStatus) {
            let revertText = "";
            let targetPhase = "";

            switch (currentStatus) {
                case 'Reviewed':
                    revertText = "Reopen the evaluation window? Submissions will step backward.";
                    targetPhase = "Under Review";
                    break;
                case 'Under Review':
                    revertText = "Are you sure? This will step backward to assignment phase.";
                    targetPhase = "Reviewer Assigned";
                    break;
                case 'Reviewer Assigned':
                    revertText = "This will unassign all reviewers and clear their progress!";
                    targetPhase = "Hardcopy Received";
                    break;
                case 'Hardcopy Received':
                case 'Incomplete Hardcopy':
                    revertText = "Step backward to incomplete status?";
                    targetPhase = "Incomplete - Awaiting Hardcopy";
                    break;
                case 'Incomplete - Awaiting Hardcopy':
                default:
                    revertText = "This will cancel the appointment and toss it back to New Submissions.";
                    targetPhase = "Pending (Initial Intake)";
                    break;
            }

            Swal.fire({
                title: 'Step Backward?',
                html: `Are you sure you want to revert "<span class="font-bold">${title}</span>"?<br><br>` + 
                      `<span class="text-red-600 font-bold">${revertText}</span><br>` +
                      `<span class="text-xs text-slate-500">Target Phase: ${targetPhase}</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, Step Backward',
                cancelButtonText: 'Cancel',
                scrollbarPadding: false,
                backdrop: `rgba(15, 23, 42, 0.75)`,
                customClass: {
                    popup: 'rounded-2xl shadow-xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold shadow-lg shadow-red-900/20',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Reverting...',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('revertPhaseForm-' + id).submit();
                }
            });
        }

        // Auto-Submit Logic for Advanced Filters via AJAX
        document.addEventListener('DOMContentLoaded', function() {
            let debounceTimer;
            const form = document.getElementById('activeProtocolsForm');
            
            const fetchProtocols = (params) => {
                const url = `{{ route('admin.applications') }}?${params.toString()}`;
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('active-protocols-wrapper');
                    if (container && data.html) {
                        container.innerHTML = data.html;
                    }
                    window.history.pushState({}, '', url);
                })
                .catch(error => console.error('Error fetching protocols:', error));
            };

            const triggerFetch = () => {
                const params = new URLSearchParams(new FormData(form));
                fetchProtocols(params);
            };

            // Text search input with debounce
            const searchInput = document.getElementById('search_input');
            if(searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        triggerFetch();
                    }, 500); 
                });
            }

            // Checkboxes and radio buttons trigger immediate fetch
            const autoSubmitInputs = document.querySelectorAll('.auto-submit-input');
            autoSubmitInputs.forEach(input => {
                input.addEventListener('change', function() {
                    triggerFetch();
                });
            });

            // Prevent default form submission from reloading the page
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                triggerFetch();
            });

            // Pagination delegation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('.filter-pagination a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    fetchProtocols(new URLSearchParams(url.search));
                }
            });
        });
    </script>
</x-admin_layout>