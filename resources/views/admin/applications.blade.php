<x-admin_layout :title="'Active Protocols'">

    <div class="max-w-7xl mx-auto w-full min-h-[calc(100vh-5rem)] flex flex-col justify-between space-y-8 selection:bg-[#8B0000] selection:text-white pt-3 sm:pt-4">
        <div class="space-y-8 flex-1 flex flex-col">

        @php
            $activeFilterCount = (request('search') ? 1 : 0) 
                + (request('review_types') ? count(request('review_types')) : 0) 
                + (request('doc_statuses') ? count(request('doc_statuses')) : 0) 
                + (request('rev_statuses') ? count(request('rev_statuses')) : 0) 
                + ((request('assignment') && request('assignment') !== 'All') ? 1 : 0) 
                + ((request('sort_by') && request('sort_by') !== 'created_at') ? 1 : 0);
        @endphp

        <div class="pb-6 border-b border-slate-200">
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Active Protocols</h1>
                    <p class="text-slate-500 mt-1 sm:mt-2 text-xs sm:text-sm">Monitor and manage research protocols undergoing initial ethical review and revision assessment.</p>
                </div>
                <div class="flex gap-2 sm:gap-3 mt-1 xl:mt-0 w-full xl:w-auto">
                    <form action="{{ route('admin.applications') }}" method="GET" class="relative flex w-full xl:w-auto items-center gap-2 sm:gap-3" id="activeProtocolsForm">
                        <!-- Search Input -->
                        <div class="relative flex-1 w-full sm:w-64 md:w-72">
                            <input type="text" name="search" id="search_input" value="{{ request('search') }}" placeholder="Search protocols..."
                                aria-label="Search active protocols"
                                class="w-full pl-9 sm:pl-10 pr-4 py-2 sm:py-2.5 h-10 sm:h-11 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-2xs bg-white">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs sm:text-sm"></i>
                        </div>

                        <!-- Filter Drawer Toggle -->
                        <div class="relative shrink-0" x-data="{ expanded: sessionStorage.getItem('activeProtocolsFilterExpanded') === 'true' }" x-init="$watch('expanded', value => sessionStorage.setItem('activeProtocolsFilterExpanded', value))">
                            <button type="button" @click="expanded = true"
                                :aria-expanded="expanded ? 'true' : 'false'"
                                aria-label="Open filter options"
                                class="flex items-center gap-2.5 px-3.5 sm:px-4 py-2 sm:py-2.5 h-10 sm:h-11 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs transition-colors cursor-pointer touch-manipulation">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-filter text-slate-400 text-xs" aria-hidden="true"></i> 
                                    <span>Filter</span>
                                    @if($activeFilterCount > 0)
                                        <span class="w-4 h-4 rounded-full bg-[#8B0000] text-white text-[11px] font-semibold flex items-center justify-center tabular-nums leading-none">
                                            {{ $activeFilterCount }}
                                        </span>
                                    @endif
                                </span>
                                <i class="fas fa-bars text-xs text-slate-400 transition-transform" :class="expanded ? 'rotate-90' : ''" aria-hidden="true"></i>
                            </button>

                        <!-- Advanced Filter Drawer -->
                        <div x-show="expanded" @keydown.escape.window="if (expanded) expanded = false" style="display: none;" class="relative z-[100]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
                            <!-- Background backdrop -->
                            <div x-show="expanded" 
                                 x-transition:enter="ease-in-out duration-300" 
                                 x-transition:enter-start="opacity-0" 
                                 x-transition:enter-end="opacity-100" 
                                 x-transition:leave="ease-in-out duration-300" 
                                 x-transition:leave-start="opacity-100" 
                                 x-transition:leave-end="opacity-0" 
                                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity cursor-pointer" 
                                 @click="expanded = false"></div>

                            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                                <div class="absolute inset-0 overflow-hidden">
                                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-4 sm:pl-10"
                                         x-show="expanded"
                                         x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                                         x-transition:enter-start="translate-x-full"
                                         x-transition:enter-end="translate-x-0"
                                         x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                                         x-transition:leave-start="translate-x-0"
                                         x-transition:leave-end="translate-x-full">
                                        
                                        <div class="pointer-events-auto w-screen max-w-[280px] sm:max-w-xs flex flex-col h-full bg-white shadow-2xl">
                                            <!-- Drawer Header -->
                                            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 flex-none text-left">
                                                <h3 class="font-heading font-bold text-base text-slate-800 leading-tight">Apply Filters</h3>
                                                <button type="button" @click="expanded = false" aria-label="Close filter options" class="text-slate-400 hover:text-[#8B0000] hover:bg-slate-100 transition-colors w-9 h-9 min-w-[36px] min-h-[36px] flex-shrink-0 flex items-center justify-center rounded-xl cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                                    <i class="fas fa-times text-base" aria-hidden="true"></i>
                                                </button>
                                            </div>

                                            <!-- Drawer Filters List -->
                                            <div class="flex-1 overflow-y-auto w-full pb-10">
                            <!-- Sort Section -->
                            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Sort By</label>
                                <div class="space-y-2.5">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="sort_by" value="created_at" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ request('sort_by', 'created_at') == 'created_at' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Submission Date</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="sort_by" value="Title" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ request('sort_by') == 'Title' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Title</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Review Type Section -->
                            <div class="p-4 border-b border-slate-100">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Review Type</label>
                                <div class="space-y-2.5">
                                    @php $selectedTypes = request('review_types', []); @endphp
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="review_types[]" value="Exempt Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ in_array('Exempt Review', $selectedTypes) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Exempt</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="review_types[]" value="Expedited Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ in_array('Expedited Review', $selectedTypes) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Expedited</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="review_types[]" value="Full Board Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ in_array('Full Board Review', $selectedTypes) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Full Board</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Document Status Section -->
                            <div class="p-4 border-b border-slate-100">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Document Status</label>
                                <div class="space-y-2.5">
                                    @php $docStatuses = request('doc_statuses', []); @endphp
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="doc_statuses[]" value="Incomplete - Awaiting Hardcopy" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ in_array('Incomplete - Awaiting Hardcopy', $docStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors text-balance leading-snug">Incomplete: Awaiting Hardcopy</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="doc_statuses[]" value="Incomplete Hardcopy" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ in_array('Incomplete Hardcopy', $docStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Incomplete Hardcopy</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="doc_statuses[]" value="Hardcopy Received" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ in_array('Hardcopy Received', $docStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Hardcopy Received</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Reviewer Status Section -->
                            <div class="p-4 border-b border-slate-100">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Reviewer Status</label>
                                <div class="space-y-2.5">
                                    @php $revStatuses = request('rev_statuses', []); @endphp
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="rev_statuses[]" value="Reviewer Assigned" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ in_array('Reviewer Assigned', $revStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Reviewer Assigned</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="rev_statuses[]" value="Under Review" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ in_array('Under Review', $revStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Under Review</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="rev_statuses[]" value="Reviewed" class="auto-submit-input rounded text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ in_array('Reviewed', $revStatuses) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Reviewed</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Assignment Section -->
                            <div class="p-4 bg-slate-50/50">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Reviewer Assignment</label>
                                <div class="space-y-2.5">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="assignment" value="All" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ request('assignment', 'All') == 'All' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Show All Protocols</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="assignment" value="Unassigned" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ request('assignment') == 'Unassigned' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Unassigned Only</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="assignment" value="Assigned" class="auto-submit-input text-[#8B0000] focus:ring-[#8B0000] accent-[#8B0000] cursor-pointer" {{ request('assignment') == 'Assigned' ? 'checked' : '' }}>
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

        @if($activeFilterCount > 0)
            <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                <span class="text-xs font-semibold text-slate-500 mr-1 flex items-center gap-1.5">
                    <i class="fas fa-sliders text-slate-400 text-xs"></i> Active filters:
                </span>
                @if(request('search'))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/80 shadow-2xs hover:bg-slate-200/60 transition-colors">
                        <span class="text-slate-400 font-normal">Search:</span> "{{ request('search') }}"
                        <a href="{{ route('admin.applications', request()->except('search')) }}" class="hover:text-rose-600 ml-0.5 text-slate-400 transition-colors" title="Remove search filter" aria-label="Remove search filter">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                @endif
                @if(request('review_types'))
                    @foreach(request('review_types') as $rType)
                        @php
                            $remTypes = array_values(array_diff(request('review_types'), [$rType]));
                            $remParams = request()->except('review_types');
                            if (!empty($remTypes)) { $remParams['review_types'] = $remTypes; }
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/80 shadow-2xs hover:bg-slate-200/60 transition-colors">
                            <span class="text-slate-400 font-normal">Type:</span> {{ $rType }}
                            <a href="{{ route('admin.applications', $remParams) }}" class="hover:text-rose-600 ml-0.5 text-slate-400 transition-colors" title="Remove {{ $rType }} filter" aria-label="Remove {{ $rType }} filter">
                                <i class="fas fa-times text-xs"></i>
                            </a>
                        </span>
                    @endforeach
                @endif
                @if(request('doc_statuses'))
                    @foreach(request('doc_statuses') as $dStatus)
                        @php
                            $remDoc = array_values(array_diff(request('doc_statuses'), [$dStatus]));
                            $remParams = request()->except('doc_statuses');
                            if (!empty($remDoc)) { $remParams['doc_statuses'] = $remDoc; }
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/80 shadow-2xs hover:bg-slate-200/60 transition-colors">
                            <span class="text-slate-400 font-normal">Doc:</span> {{ $dStatus }}
                            <a href="{{ route('admin.applications', $remParams) }}" class="hover:text-rose-600 ml-0.5 text-slate-400 transition-colors" title="Remove {{ $dStatus }} filter" aria-label="Remove {{ $dStatus }} filter">
                                <i class="fas fa-times text-xs"></i>
                            </a>
                        </span>
                    @endforeach
                @endif
                @if(request('rev_statuses'))
                    @foreach(request('rev_statuses') as $rvStatus)
                        @php
                            $remRev = array_values(array_diff(request('rev_statuses'), [$rvStatus]));
                            $remParams = request()->except('rev_statuses');
                            if (!empty($remRev)) { $remParams['rev_statuses'] = $remRev; }
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/80 shadow-2xs hover:bg-slate-200/60 transition-colors">
                            <span class="text-slate-400 font-normal">Review:</span> {{ $rvStatus }}
                            <a href="{{ route('admin.applications', $remParams) }}" class="hover:text-rose-600 ml-0.5 text-slate-400 transition-colors" title="Remove {{ $rvStatus }} filter" aria-label="Remove {{ $rvStatus }} filter">
                                <i class="fas fa-times text-xs"></i>
                            </a>
                        </span>
                    @endforeach
                @endif
                @if(request('assignment') && request('assignment') !== 'All')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/80 shadow-2xs hover:bg-slate-200/60 transition-colors">
                        <span class="text-slate-400 font-normal">Assignment:</span> {{ request('assignment') }}
                        <a href="{{ route('admin.applications', request()->except('assignment')) }}" class="hover:text-rose-600 ml-0.5 text-slate-400 transition-colors" title="Remove assignment filter" aria-label="Remove assignment filter">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                @endif
                @if(request('sort_by') && request('sort_by') !== 'created_at')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200/80 shadow-2xs hover:bg-slate-200/60 transition-colors">
                        <span class="text-slate-400 font-normal">Sort:</span> {{ request('sort_by') }}
                        <a href="{{ route('admin.applications', request()->except('sort_by')) }}" class="hover:text-rose-600 ml-0.5 text-slate-400 transition-colors" title="Remove sort" aria-label="Remove sort">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                @endif

                <a href="{{ route('admin.applications') }}" class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 hover:text-[#8B0000] ml-1.5 transition-colors">
                    <i class="fas fa-times-circle text-[11px]"></i> Clear all
                </a>
            </div>
        @endif
    </div>

        <div id="active-protocols-wrapper" class="flex-1 flex flex-col min-h-[400px]">
            @include('admin.partials.active_protocols_list')
        </div>
    </div>
</div>

    <!-- Include Status Update Modal -->
    @include('admin.partials.status_modal')
    @include('admin.partials.ai_predict_modal')
    @include('admin.partials.protocol_action_drawer')

    <!-- Assign Reviewer Modal -->
    @php
        $reviewerColleges = $reviewers->pluck('college')->filter()->unique()->sort()->values();
    @endphp
    <div x-data="{
    open: false,
    protocolId: '',
    protocolTitle: '',
    reviewType: '',
    assigned: [],
    initialAssignedCount: 0,
    search: '',
    collegeFilter: '',
    expandedReviewer: null,
    maxSelection: function() {
        return 99;
    },
    toggleSelection: function(id) {
        if (this.assigned.includes(id)) {
            this.assigned = this.assigned.filter(val => val !== id);
        } else {
            this.assigned.push(id);
        }
    }
}"
@open-assign-modal.window="
    open = true; 
    protocolId = $event.detail.id; 
    protocolTitle = $event.detail.title; 
    reviewType = $event.detail.reviewType;
    assigned = Array.isArray($event.detail.assigned) ? [...$event.detail.assigned] : []; 
    initialAssignedCount = assigned.length; 
    search = ''; 
    collegeFilter = ''; 
    expandedReviewer = null;
    
    // Store the title in the hidden input for the AI function
    document.getElementById('current-protocol-title').value = $event.detail.title;
"
    @keydown.escape.window="if (open) open = false"
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
                <div class="relative transform overflow-hidden rounded-2xl sm:rounded-[2rem] bg-white text-left shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] transition-all w-full max-w-xl mx-3 sm:mx-auto border border-slate-100"
                    x-show="open" @click.away="open = false"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95">

                    <form :action="'{{ url('admin/applications') }}/' + protocolId + '/assign-reviewers'" method="POST">
                        @csrf

                        <!-- Modal Header -->
                        <div class="px-4 sm:px-7 pt-5 sm:pt-7 pb-2">
                            <div class="flex items-start justify-between gap-3 sm:gap-4">
                                <div class="flex items-center gap-2.5 sm:gap-3.5 min-w-0">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-[1rem] bg-red-50 flex items-center justify-center border border-red-100/50 flex-shrink-0">
                                        <i class="fas fa-users-cog text-[#8B0000] text-base sm:text-lg"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-base sm:text-[1.15rem] font-bold text-slate-900 tracking-tight leading-tight" id="assign-modal-title" x-text="initialAssignedCount > 0 ? 'Change Assigned Reviewer(s)' : 'Assign Reviewer(s)'">Assign Reviewer(s)</h3>
                                        <p class="text-[11px] sm:text-xs text-slate-500 mt-1 font-medium line-clamp-1">
                                            <span x-text="protocolTitle"></span>
                                            <span x-show="reviewType" class="ml-1 px-1.5 py-0.5 bg-slate-100 rounded text-[11px] uppercase font-bold text-slate-600" x-text="reviewType"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <!-- AI Suggest Reviewer Button -->
                                    <button type="button" 
                                        id="ai-suggest-reviewer-btn"
                                        onclick="suggestReviewerWithAI()"
                                        class="px-2.5 sm:px-3 py-1.5 bg-[#8B0000] text-white rounded-xl text-xs font-semibold hover:bg-[#6d0000] focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-1 transition-all shadow-2xs flex items-center gap-1.5">
                                        <i class="fas fa-magic text-xs"></i> <span class="hidden sm:inline">Suggest Reviewer</span><span class="sm:hidden">AI</span>
                                    </button>
                                    <button type="button" @click="open = false" aria-label="Close assign reviewer modal" class="text-slate-400 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-300 w-8 h-8 rounded-lg flex items-center justify-center transition-all flex-shrink-0">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden input for storing protocol title -->
                            <input type="hidden" id="current-protocol-title" value="">
                            
                            <!-- Selection Counter -->
                            <div class="mt-2 pb-1">
                                <p class="text-xs font-semibold text-slate-600 flex items-center gap-2">
                                    <i class="fas fa-check-circle text-emerald-600" x-show="assigned.length > 0"></i>
                                    <i class="fas fa-info-circle text-blue-600" x-show="assigned.length === 0"></i>
                                    <span x-show="assigned.length > 0"><span x-text="assigned.length" class="text-emerald-700 tabular-nums font-bold"></span> reviewer(s) selected</span>
                                    <span x-show="assigned.length === 0" class="text-slate-500">No reviewers selected yet</span>
                                </p>
                            </div>
                            <!-- Search + Filter Row -->
                            <div class="flex flex-col sm:flex-row gap-2 mt-3 sm:mt-5">
                                <div class="relative flex-1 group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#8B0000]">
                                        <i class="fas fa-search text-slate-400 group-focus-within:text-[#8B0000] text-xs"></i>
                                    </div>
                                    <input type="text" x-model="search" placeholder="Search by reviewer name, department, or expertise..."
                                        aria-label="Search reviewers by name, department, or expertise"
                                        class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-100/60 border border-transparent rounded-full text-xs sm:text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-200 transition-all">
                                </div>
                                <!-- College Filter -->
                                <div class="relative flex-shrink-0 group">
                                    <select x-model="collegeFilter"
                                        aria-label="Filter reviewers by college"
                                        class="w-full sm:w-auto h-full pl-4 pr-9 py-2.5 sm:py-3 bg-slate-100/60 border border-transparent rounded-full text-xs sm:text-sm text-slate-700 focus:outline-none focus:bg-white focus:ring-4 focus:ring-red-500/10 focus:border-red-200 transition-all cursor-pointer appearance-none font-medium">
                                        <option value="">All Colleges</option>
                                        @foreach($reviewerColleges as $college)
                                            <option value="{{ strtolower($college) }}">{{ $college }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-slate-400 text-xs group-focus-within:text-[#8B0000]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Reviewer Cards List -->
                        <div class="px-4 sm:px-7 py-4 sm:py-5 max-h-[360px] overflow-y-auto space-y-3 custom-scrollbar" x-ref="reviewerCards">
                            @forelse($reviewers as $reviewer)
                                @php
                                    $initials = strtoupper(substr($reviewer->first_name ?? 'U', 0, 1) . substr($reviewer->last_name ?? '', 0, 1));
                                    $activeTitles = collect($reviewerActiveTitles[(string)$reviewer->id] ?? []);
                                    $activeCount = $activeTitles->count();
                                    $colorPalette = ['from-slate-700 to-slate-900', 'from-red-800 to-red-950', 'from-slate-600 to-slate-800', 'from-amber-700 to-amber-900', 'from-rose-800 to-rose-950'];
                                    $avatarColor = $colorPalette[$reviewer->id % count($colorPalette)];
                                    $reviewerCollege = $reviewer->college ?? null;
                                    $searchableText = strtolower($reviewer->first_name . ' ' . $reviewer->last_name . ' ' . ($reviewerCollege ?? '') . ' ' . implode(' ', $reviewer->reviewer?->expertise ?? []));
                                @endphp
                                <div
                                    x-show="
                                        (search === '' || '{{ $searchableText }}'.includes(search.toLowerCase())) &&
                                        (collegeFilter === '' || '{{ strtolower($reviewerCollege ?? '') }}' === collegeFilter)
                                    "
                                    class="rounded-[1.25rem] border transition-all duration-300 relative overflow-hidden group/card"
                                    :class="assigned.map(String).includes('{{ $reviewer->id }}') ? 'bg-red-50/30 border-red-200 shadow-md shadow-red-900/5 translate-y-0' : 'bg-white border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200 hover:-translate-y-0.5'">
                                    
                                    <!-- Selection Left Indicator -->
                                    <div class="absolute left-0 top-0 bottom-0 w-[4px] bg-[#8B0000] transition-transform duration-300 origin-left"
                                         :class="assigned.map(String).includes('{{ $reviewer->id }}') ? 'scale-x-100' : 'scale-x-0 group-hover/card:bg-slate-200 group-hover/card:scale-x-100'"></div>

                                    <!-- Card Top: Click to select -->
                                    <label class="flex items-start gap-4 p-5 pl-6 cursor-pointer relative z-10 w-full"
                                           @click.prevent="toggleSelection('{{ $reviewer->id }}')">

                                        <input type="checkbox" name="reviewers[]" value="{{ $reviewer->id }}"
                                            :checked="assigned.map(String).includes('{{ $reviewer->id }}')"
                                            class="sr-only">

                                        <!-- Avatar -->
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br {{ $avatarColor }} flex items-center justify-center text-white text-sm tracking-wide font-bold flex-shrink-0 shadow-sm">
                                            {{ $initials }}
                                        </div>

                                        <!-- Info -->
                                        <div class="flex-1 min-w-0 pr-8">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-bold text-slate-800 leading-tight group-hover/card:text-[#8B0000] transition-colors">{{ $reviewer->first_name }} {{ $reviewer->last_name }}</p>
                                                    @if($reviewerCollege)
                                                        <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1.5 font-medium">
                                                             <i class="fas fa-university text-[11px] opacity-70"></i>
                                                            {{ $reviewerCollege }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <!-- Workload Badge -->
                                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full flex-shrink-0 tracking-wide border
                                                    {{ $activeCount === 0 ? 'bg-emerald-50 text-emerald-800 border-emerald-200/80' : ($activeCount <= 2 ? 'bg-amber-50 text-amber-900 border-amber-200/80' : 'bg-red-50 text-red-800 border-red-200/80') }}">
                                                    {{ $activeCount }} reviewing
                                                </span>
                                            </div>

                                            @if($reviewer->reviewer && !empty($reviewer->reviewer->expertise))
                                                <div class="flex flex-wrap items-center gap-1.5 mt-2.5">
                                                    @foreach(array_slice($reviewer->reviewer->expertise, 0, 3) as $index => $exp)
                                                        @if($index > 0)
                                                            <span class="text-slate-300 text-[11px]">&bull;</span>
                                                        @endif
                                                        <span class="text-slate-500 text-[11px] font-medium tracking-tight lowercase">{{ $exp }}</span>
                                                    @endforeach
                                                    @if(count($reviewer->reviewer->expertise) > 3)
                                                        <span class="text-slate-300 text-[11px]">&bull;</span>
                                                        <span class="text-slate-400 text-[11px] font-medium tracking-tight lowercase font-style-italic">+{{ count($reviewer->reviewer->expertise) - 3 }} more</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Selected checkmark -->
                                        <div class="absolute right-5 top-1/2 -translate-y-1/2 flex-shrink-0"
                                             :class="assigned.map(String).includes('{{ $reviewer->id }}') ? 'opacity-100 scale-100' : 'opacity-0 scale-75 group-hover/card:opacity-30 group-hover/card:scale-100'"
                                             style="transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1)">
                                            <div class="w-6 h-6 rounded-full bg-[#8B0000] flex items-center justify-center shadow-lg shadow-red-900/20">
                                                <i class="fas fa-check text-white text-[11px]"></i>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Active Titles Accordion -->
                                    @if($activeCount > 0)
                                        <div class="border-t transition-colors px-6 py-0 bg-slate-50/50"
                                             :class="assigned.map(String).includes('{{ $reviewer->id }}') ? 'border-red-100' : 'border-slate-100'">
                                            <button type="button"
                                                @click.prevent="expandedReviewer = expandedReviewer === '{{ $reviewer->id }}' ? null : '{{ $reviewer->id }}'"
                                                class="w-full flex items-center justify-between py-3 text-left group/acc">
                                                <span class="text-[11px] font-bold text-slate-500 group-hover/acc:text-slate-800 transition-colors flex items-center gap-2">
                                                    <i class="fas fa-folder-open text-slate-400 group-hover/acc:text-slate-600 transition-colors"></i>
                                                    Currently Reviewing ({{ $activeCount }})
                                                </span>
                                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-300 group-hover/acc:text-slate-800"
                                                   :class="expandedReviewer === '{{ $reviewer->id }}' ? 'rotate-180' : ''"></i>
                                            </button>
                                            <div x-show="expandedReviewer === '{{ $reviewer->id }}'" x-collapse class="pb-4 space-y-2">
                                                 @foreach($activeTitles as $aTitle)
                                                    @php
                                                        $displayTitle = is_array($aTitle) 
                                                            ? ($aTitle['title'] ?? $aTitle['Study_Protocol_title'] ?? 'Untitled Protocol') 
                                                            : ($aTitle->title ?? $aTitle->Study_Protocol_title ?? 'Untitled Protocol');
                                                    @endphp
                                                    <div class="flex items-start gap-2.5 py-2 px-3 rounded-xl bg-white border border-slate-100 shadow-sm">
                                                        <div class="w-1.5 h-1.5 rounded-full bg-[#8B0000]/80 mt-1.5 flex-shrink-0"></div>
                                                        <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ $displayTitle }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="py-10 text-center text-slate-400">
                                    <i class="fas fa-user-slash text-3xl mb-3 text-slate-300"></i>
                                    <p class="text-sm font-semibold text-slate-700 mb-1">No reviewers match criteria</p>
                                    <p class="text-xs text-slate-400">Try searching with a different term or choosing "All Colleges".</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Footer -->
                        <div class="px-5 sm:px-7 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between rounded-b-2xl">
                            <button type="button" @click="open = false; confirmUnchooseReviewer($event.target.closest('form'), protocolTitle, () => { assigned = [] }, () => { open = true })" x-show="initialAssignedCount > 0" x-transition.opacity
                                class="w-full sm:w-auto px-4 py-2.5 bg-red-50 text-red-700 rounded-xl text-xs sm:text-sm font-semibold hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-300 transition-colors border border-red-200 flex-shrink-0 whitespace-nowrap">
                                <i class="fas fa-user-times mr-1.5"></i> Clear Assigned Reviewers
                            </button>
                            <div class="flex gap-2.5 w-full sm:w-auto justify-end sm:ml-auto">
                                <button type="button" @click="open = false"
                                    class="flex-1 sm:flex-none px-5 py-2.5 bg-white border border-slate-200 text-slate-600 hover:text-slate-800 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-300 transition-all shadow-2xs whitespace-nowrap">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="flex-1 sm:flex-none px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs sm:text-sm font-semibold hover:bg-[#6d0000] focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 transition-all shadow-2xs flex items-center justify-center whitespace-nowrap min-w-[150px]">
                                    <i class="fas fa-user-check mr-2"></i> Save Assignment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>

    // Add this function to suggest a reviewer using AI
    function suggestReviewerWithAI() {
        // Get the protocol title from the hidden input
        const protocolTitle = document.getElementById('current-protocol-title').value;
        const suggestionBtn = document.getElementById('ai-suggest-reviewer-btn');
        
        if (!protocolTitle || protocolTitle === 'Loading...') {
            showModalAlert('No protocol title available. Please try again.', 'error');
            return;
        }
        
        // Disable button and show loading state
        suggestionBtn.disabled = true;
        const originalHTML = suggestionBtn.innerHTML;
        suggestionBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Analyzing...';
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                        document.querySelector('input[name="_token"]')?.value;
        
        if (!csrfToken) {
            showModalAlert('CSRF token not found. Please refresh the page.', 'error');
            suggestionBtn.disabled = false;
            suggestionBtn.innerHTML = originalHTML;
            return;
        }
        
        fetch('/admin/predict/suggest-reviewer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ title: protocolTitle })
        })
        .then(async res => {
            if (!res.ok) {
                const text = await res.text();
                throw new Error(`HTTP ${res.status}: ${text.substring(0, 100)}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('AI Response:', data);
            
            if (data.success && data.suggested_reviewer_id) {
                // Only highlight and show result - NO auto-select
                highlightSuggestedReviewer(data.suggested_reviewer_id, data.suggested_reviewer_name, data.suggested_reviewer_expertise);
            } else {
                showModalAlert('AI suggestion failed: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(err => {
            console.error('AI suggestion error:', err);
            showModalAlert('Failed to get AI suggestion: ' + err.message, 'error');
        })
        .finally(() => {
            suggestionBtn.disabled = false;
            suggestionBtn.innerHTML = originalHTML;
        });
    }

    function highlightSuggestedReviewer(reviewerId, reviewerName, reviewerExpertise) {
        // Find the reviewer card by looking for the checkbox with the matching value
        const allCards = document.querySelectorAll('[x-show]');
        let found = false;
        
        for (const card of allCards) {
            const checkbox = card.querySelector(`input[type="checkbox"][value="${reviewerId}"]`);
            if (checkbox) {
                // Scroll to the reviewer
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Add highlight effect with animation (NO auto-check)
                card.style.transition = 'all 0.3s ease';
                card.style.boxShadow = '0 0 0 3px #8B0000, 0 0 0 6px rgba(139, 0, 0, 0.2)';
                card.style.backgroundColor = '#fef2f2';
                
                // Create and show the AI suggestion result card
                showAISuggestionResult(reviewerName, reviewerExpertise);
                
                // Remove highlight after 5 seconds
                setTimeout(() => {
                    card.style.boxShadow = '';
                    card.style.backgroundColor = '';
                    // Fade out the result card
                    const resultCard = document.getElementById('ai-suggestion-result');
                    if (resultCard) {
                        resultCard.style.opacity = '0';
                        resultCard.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            if (resultCard) resultCard.remove();
                        }, 300);
                    }
                }, 5000);
                
                found = true;
                break;
            }
        }
        
        if (!found) {
            console.log('Reviewer not found in list, ID:', reviewerId);
            // Try to find by name in the text content
            for (const card of allCards) {
                if (card.textContent.includes(reviewerName)) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card.style.backgroundColor = '#fef2f2';
                    card.style.boxShadow = '0 0 0 3px #8B0000, 0 0 0 6px rgba(139, 0, 0, 0.2)';
                    
                    // Show result card
                    showAISuggestionResult(reviewerName, reviewerExpertise);
                    
                    setTimeout(() => {
                        card.style.backgroundColor = '';
                        card.style.boxShadow = '';
                        const resultCard = document.getElementById('ai-suggestion-result');
                        if (resultCard) {
                            resultCard.style.opacity = '0';
                            resultCard.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                if (resultCard) resultCard.remove();
                            }, 300);
                        }
                    }, 5000);
                    found = true;
                    break;
                }
            }
        }
        
        if (!found) {
            showModalAlert(`AI suggests: ${reviewerName}\n\nExpertise: ${reviewerExpertise}\n\nPlease find and select them manually from the list.`, 'info');
        }
    }

    function showAISuggestionResult(reviewerName, reviewerExpertise) {
        // Remove any existing result card
        const existingResult = document.getElementById('ai-suggestion-result');
        if (existingResult) existingResult.remove();
        
        // Create the result card HTML with more detailed expertise display
        const resultHTML = `
                <div id="ai-suggestion-result" class="fixed top-24 left-1/2 -translate-x-1/2 z-[10000] w-[420px] bg-white border border-red-200/80 rounded-2xl shadow-2xl p-4 animate-slide-down ring-1 ring-black/5" style="animation: slideDown 0.3s ease-out;">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-[#8B0000] flex items-center justify-center shadow-lg">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-bold text-[#8B0000] text-sm">AI Suggested Reviewer</h4>
                        <button onclick="document.getElementById('ai-suggestion-result').remove()" class="text-gray-400 hover:text-[#8B0000] transition-colors">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <p class="font-bold text-slate-800 text-base">${escapeHtml(reviewerName)}</p>
                    <div class="mt-2 p-2 bg-red-50 rounded-lg border border-red-100">
                        <p class="text-[11px] text-[#8B0000] font-bold uppercase tracking-wider mb-1">Matching Expertise</p>
                        <p class="text-xs text-slate-700 font-medium">${escapeHtml(reviewerExpertise)}</p>
                    </div>
                    <div class="mt-2 pt-2 border-t border-red-100">
                        <p class="text-[11px] text-[#8B0000] flex items-center gap-1">
                            <i class="fas fa-hand-pointer text-xs"></i>
                            Please manually select this reviewer
                        </p>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        // Insert the result card into the body
        document.body.insertAdjacentHTML('beforeend', resultHTML);
        
        // Auto-remove after 6 seconds
        setTimeout(() => {
            const resultCard = document.getElementById('ai-suggestion-result');
            if (resultCard) {
                resultCard.style.opacity = '0';
                resultCard.style.transform = 'translateY(-20px)';
                resultCard.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    if (resultCard) resultCard.remove();
                }, 300);
            }
        }, 6000);
    }

    function showModalAlert(message, type = 'info') {
        // Remove any existing alert
        const existingAlert = document.getElementById('ai-modal-alert');
        if (existingAlert) existingAlert.remove();
        
        const bgColor = type === 'error' ? 'bg-red-500' : (type === 'success' ? 'bg-green-500' : 'bg-blue-500');
        const icon = type === 'error' ? 'exclamation-triangle' : (type === 'success' ? 'check-circle' : 'info-circle');
        
        const alertHTML = `
            <div id="ai-modal-alert" class="fixed top-24 left-1/2 -translate-x-1/2 z-[10000] w-96 ${bgColor} text-white rounded-xl shadow-2xl p-4 animate-slide-down" style="animation: slideDown 0.3s ease-out;">
                <div class="flex items-center gap-3">
                    <i class="fas fa-${icon} text-white text-lg"></i>
                    <p class="text-sm font-medium flex-1">${escapeHtml(message)}</p>
                    <button onclick="document.getElementById('ai-modal-alert').remove()" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', alertHTML);
        
        setTimeout(() => {
            const alert = document.getElementById('ai-modal-alert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    if (alert) alert.remove();
                }, 300);
            }
        }, 4000);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // Add CSS animations if not already present
    if (!document.querySelector('#ai-reviewer-styles')) {
        const style = document.createElement('style');
        style.id = 'ai-reviewer-styles';
        style.textContent = `
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateX(-50%) translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(-50%) translateY(0);
                }
            }
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .animate-slide-down {
                animation: slideDown 0.3s ease-out;
            }
        `;
        document.head.appendChild(style);
    }

        function confirmUnchooseReviewer(form, title, clearSelectionCallback, cancelCallback) {
            Swal.fire({
                title: 'Remove Assigned Reviewers?',
                html: `<p class="text-slate-600 text-sm mt-2">Are you sure you want to remove all assigned reviewers for protocol:<br><br><b>${title}</b>?</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Remove Reviewers',
                cancelButtonText: 'Keep Current',
                scrollbarPadding: false,
                backdrop: `rgba(15, 23, 42, 0.75)`,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6 z-[10000]',
                    title: 'font-heading text-xl text-slate-800 font-bold',
                    confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-900 outline-none mx-2',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 outline-none mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    clearSelectionCallback();
                    // Small timeout to allow Alpine to update the DOM (uncheck radios binding to reviewers[] array)
                    setTimeout(() => form.submit(), 50);
                } else if (result.isDismissed) {
                    if (cancelCallback) cancelCallback();
                }
            });
        }

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
                    confirmButton: 'bg-amber-800 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg hover:bg-amber-900 hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition-all outline-none focus:ring-0 mx-2'
                }
            };

            const { value: customMsg, isConfirmed } = await Swal.fire({
                ...swalCommon,
                title: 'Send Official Receipt Reminder',
                html: `
                    <div class="text-left space-y-3">
                        <p class="text-slate-600 text-sm">Send a formal reminder to the researcher for <b>"${title}"</b> to submit their Official Receipt (OR).</p>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Custom Note <span class="text-slate-400 font-normal normal-case">(optional)</span></label>
                            <textarea id="notifyMsgInput" rows="3"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 resize-none"
                                placeholder="Enter specific payment instructions or leave blank to send the standard reminder..."></textarea>
                        </div>
                        <p class="text-[11px] text-slate-400 italic">⚠ Reminders can only be sent once every 24 hours per protocol.</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-bell mr-1.5"></i> Send Reminder',
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
                        title: 'Reminder Sent',
                        text: result.message || 'Official Receipt reminder has been sent to the researcher.',
                        icon: 'success',
                        confirmButtonText: 'Close',
                        customClass: {
                            ...swalCommon.customClass,
                            confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg hover:bg-red-900 transition-all outline-none focus:ring-0 mx-2'
                        }
                    });
                } else {
                    Swal.fire({
                        ...swalCommon,
                        title: response.status === 429 ? 'Reminder Already Sent' : 'Unable to Send Reminder',
                        text: result.message || 'An error occurred. Please try again.',
                        icon: response.status === 429 ? 'info' : 'error',
                        confirmButtonText: 'Understood',
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
                        <i class="fas fa-exclamation-circle"></i> Previous Deficiencies Noted
                    </h4>
                    <p class="text-sm text-red-900 leading-relaxed font-medium relative z-10">${previousRemarks}</p>
                </div>
            ` : '';

            const html = `
                <div class="text-left space-y-4">
                    ${prevRemarksHtml}
                    <p class="text-sm text-slate-600">Please verify whether the submitted physical protocol documents are complete and valid.</p>
                    
                    <div class="flex gap-4 mb-4">
                        <label class="flex-1 border p-3 rounded-lg cursor-pointer hover:bg-slate-50 border-slate-200" onclick="window.toggleHardcopyIncomplete(false)">
                            <input type="radio" name="hardcopy_status" value="Hardcopy Complete" class="mr-2" checked> Complete Hardcopy
                        </label>
                        <label class="flex-1 border p-3 rounded-lg cursor-pointer hover:bg-slate-50 border-slate-200" onclick="window.toggleHardcopyIncomplete(true)">
                            <input type="radio" name="hardcopy_status" value="Hardcopy Incomplete" class="mr-2"> Incomplete Hardcopy (Deficient)
                        </label>
                    </div>

                    <div id="incompleteFields" class="hidden space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-100 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Resubmission Deadline <span class="text-red-500">*</span></label>
                            <input type="date" id="hc_appointment_date" value="${minDate}" min="${minDate}" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Missing Requirements</label>
                            <div class="relative mb-3">
                                <input type="text" id="hc_reqInput" list="hc_requirementsOptions"
                                    class="w-full p-3 pr-10 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all"
                                    placeholder="Select or enter missing document requirement...">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-search text-slate-400"></i>
                                </div>
                            </div>
                            <!-- Inline hint container -->
                            <p id="hc_reqDuplicateHint" class="text-xs text-red-500 font-bold mb-2"></p>
                            <div id="hc_requirementsList" class="space-y-2 max-h-40 overflow-y-auto pr-1 mb-3">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Administrative Remarks</label>
                            <textarea id="hc_remarks" rows="3" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none resize-none" placeholder="Add specific instructions for the researcher regarding missing or incomplete items..."></textarea>
                        </div>
                    </div>
                </div>
            `;

            Swal.fire({
                title: 'Assess Hardcopy Submission',
                html: html,
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Submit Hardcopy Assessment',
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
                    
                    document.querySelectorAll('input[name="hardcopy_status"]').forEach(el => {
                        el.addEventListener('change', (e) => {
                            toggleHardcopyIncomplete(e.target.value === 'Hardcopy Incomplete');
                        });
                    });

                    // Tag pill logic
                    const reqInput = document.getElementById('hc_reqInput');
                    
                    window.hc_removeRequirement = function(id) {
                        const el = document.getElementById(`hc_req-${id}`);
                        if (el) {
                            el.style.opacity = '0';
                            el.style.transform = 'translateX(10px)';
                            setTimeout(() => el.remove(), 200);
                        }
                    };

                    const addReq = function() {
                        const val = reqInput.value.trim();
                        if (!val) return;

                        const container = document.getElementById('hc_requirementsList');
                        const existingValues = [...container.querySelectorAll('input[name="hc_missing_requirements[]"]')].map(el => el.value.toLowerCase());
                        
                        if (existingValues.includes(val.toLowerCase())) {
                            let hint = document.getElementById('hc_reqDuplicateHint');
                            hint.textContent = '"' + val + '" is already added to the missing list.';
                            setTimeout(() => { if (hint) hint.textContent = ''; }, 2000);
                            reqInput.value = '';
                            reqInput.focus();
                            return;
                        }
                        
                        const id = Date.now();
                        const itemHTML = `
                            <div id="hc_req-${id}" class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-200 shadow-sm animate-[fadeIn_0.3s_ease-out] group transition-all duration-300">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></div>
                                    <span class="text-sm font-medium text-slate-700 truncate" title="${val}">${val}</span>
                                    <input type="hidden" name="hc_missing_requirements[]" value="${val}">
                                </div>
                                <button type="button" onclick="window.hc_removeRequirement('${id}')" class="text-slate-400 hover:text-red-500 transition-colors p-1 opacity-50 group-hover:opacity-100">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', itemHTML);
                        reqInput.value = '';
                        reqInput.focus();
                    };

                    if (reqInput) {
                        reqInput.addEventListener('keypress', function (e) {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                addReq();
                            }
                        });
                        reqInput.addEventListener('input', function(e) {
                            const val = this.value.trim();
                            const options = Array.from(document.querySelectorAll('#hc_requirementsOptions option')).map(o => o.value);
                            if (options.includes(val)) {
                                addReq();
                            }
                        });
                    }
                },
                preConfirm: () => {
                    const status = document.querySelector('input[name="hardcopy_status"]:checked').value;
                    const data = new FormData();
                    data.append('classification', status);
                    data.append('_token', '{{ csrf_token() }}');

                    if (status === 'Hardcopy Incomplete') {
                        const date = document.getElementById('hc_appointment_date').value;
                        const remarks = document.getElementById('hc_remarks').value;
                        const missingInputs = document.querySelectorAll('#hc_requirementsList input[name="hc_missing_requirements[]"]');
                        
                        if (missingInputs.length === 0 && !remarks.trim()) {
                            Swal.showValidationMessage('Please specify at least one missing document requirement or provide administrative remarks.');
                            return false;
                        }
                        if (!date) {
                            Swal.showValidationMessage('Please set a resubmission deadline.');
                            return false;
                        }
                        data.append('appointment_date', date);
                        data.append('remarks', remarks);
                        
                        missingInputs.forEach(input => {
                            data.append('missing_requirements[]', input.value);
                        });
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
                                title: 'Assessment Submitted',
                                text: 'The hardcopy assessment has been successfully recorded.',
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
                    revertText = "Reopen reviewer evaluation? The protocol will return to 'Under Review' status for reviewers to revise comments.";
                    targetPhase = "Under Review";
                    break;
                case 'Under Review':
                    revertText = "Step back to assignment? The protocol will return to 'Reviewer Assigned' status.";
                    targetPhase = "Reviewer Assigned";
                    break;
                case 'Reviewer Assigned':
                    revertText = "Remove assigned reviewers? The protocol will return to 'Hardcopy Received' and reviewers must be re-assigned.";
                    targetPhase = "Hardcopy Received";
                    break;
                case 'Hardcopy Received':
                case 'Incomplete Hardcopy':
                    revertText = "Step back to intake? The protocol will return to 'Incomplete - Awaiting Hardcopy' status.";
                    targetPhase = "Incomplete - Awaiting Hardcopy";
                    break;
                case 'Incomplete - Awaiting Hardcopy':
                default:
                    revertText = "Cancel the hardcopy appointment? The protocol will return to Initial Intake in New Submissions.";
                    targetPhase = "Pending (Initial Intake)";
                    break;
            }

            Swal.fire({
                title: 'Revert Protocol Stage?',
                html: `Are you sure you want to revert "<span class="font-bold">${title}</span>"?<br><br>` + 
                      `<span class="text-red-600 font-bold">${revertText}</span><br>` +
                      `<span class="text-xs text-slate-500">Target Phase: ${targetPhase}</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, Revert Stage',
                cancelButtonText: 'Keep Current Stage',
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
    
    @php
        $globalRequirements = \App\Models\DocumentRequirement::all();
    @endphp
    <!-- Global Datalist Component injected for SweetAlert2 scopes -->
    <datalist id="hc_requirementsOptions">
        @foreach($globalRequirements as $req)
            <option value="{{ $req->name }}">
        @endforeach
    </datalist>

</x-admin_layout>