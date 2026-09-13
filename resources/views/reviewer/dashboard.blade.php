<x-reviewer_layout :title="$pageTitle ?? 'Assigned Protocols'">
    @php
        $isReeval = request()->routeIs('reviewer.reevaluation');
        $currentTitle = $pageTitle ?? ($isReeval ? 'Re-Evaluation' : 'Assigned Protocols');
        $currentDescription = $pageDescription ?? ($isReeval 
            ? 'Review protocols that have been revised by the researcher and returned for secondary evaluation.' 
            : 'Review the research protocols assigned to you by the administrative oversight committee.');
        
        $totalCount = $titles->count();
        $underReviewCount = $titles->whereIn('Status', ['Under Review', 'Reviewing Revisions'])->count();
        $pendingRevisionCount = $titles->whereIn('Status', ['Waiting for Revision', 'Revision Submitted'])->count();
        $assignedCount = $titles->where('Status', 'Reviewer Assigned')->count();
        
        $categories = $titles->pluck('Research_Category')->filter()->unique()->values();
        $reviewTypes = $titles->pluck('Review_Type')->filter()->unique()->values();
        $categoryCounts = $titles->groupBy('Research_Category')->map->count();
        $reviewTypeCounts = $titles->groupBy('Review_Type')->map->count();
    @endphp

    <div class="w-full space-y-6 pb-12"
         x-data="{
             search: '',
             filterCategory: 'all',
             filterType: 'all',
             filterStatus: 'all',
             isMobile: window.innerWidth < 768,
             viewMode: (window.innerWidth < 768 ? 'grid' : (localStorage.getItem('reo_reviewer_view') || 'table')),
             init() {
                 window.addEventListener('resize', () => {
                     this.isMobile = window.innerWidth < 768;
                 });
             },
             setView(mode) {
                 this.viewMode = mode;
                 localStorage.setItem('reo_reviewer_view', mode);
             },
             resetFilters() {
                 this.search = '';
                 this.filterCategory = 'all';
                 this.filterType = 'all';
                 this.filterStatus = 'all';
             },
             hasActiveFilters() {
                 return this.search.trim().length > 0 || this.filterCategory !== 'all' || this.filterType !== 'all' || this.filterStatus !== 'all';
             },
             matches(item) {
                 const q = this.search.toLowerCase().trim();
                 const matchesSearch = !q || 
                     (item.title && item.title.toLowerCase().includes(q)) || 
                     (item.code && item.code.toLowerCase().includes(q)) ||
                     (item.category && item.category.toLowerCase().includes(q)) ||
                     (item.researcher && item.researcher.toLowerCase().includes(q));
                 
                 const matchesCat = this.filterCategory === 'all' || item.category === this.filterCategory;
                 const matchesType = this.filterType === 'all' || item.type === this.filterType;
                 const matchesStatus = this.filterStatus === 'all' || item.status === this.filterStatus;
                 
                 return matchesSearch && matchesCat && matchesType && matchesStatus;
             }
         }">

        <!-- ===== PAGE HEADER ===== -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-5 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 font-heading tracking-tight leading-tight">
                        {{ $currentTitle }}
                    </h1>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                        ({{ $totalCount }} {{ Str::plural('Protocol', $totalCount) }})
                    </span>
                </div>
                <p class="text-slate-500 mt-1 text-xs sm:text-sm max-w-2xl leading-relaxed">
                    {{ $currentDescription }}
                </p>
            </div>

            <!-- Quick Metrics Strip (Unified 3-col on mobile, side-by-side pills on desktop) -->
            @if(!$isReeval)
            <div class="grid grid-cols-3 divide-x divide-slate-100 bg-white rounded-2xl border border-slate-200/90 shadow-2xs py-2.5 px-1 sm:flex sm:w-auto sm:divide-x-0 sm:gap-2.5 sm:p-0 sm:bg-transparent sm:border-0 sm:shadow-none">
                <div class="flex flex-col items-center justify-center px-1 text-center sm:flex-row sm:items-center sm:gap-2 sm:px-3.5 sm:py-2 sm:rounded-xl sm:bg-white sm:border sm:border-slate-200/90 sm:shadow-2xs">
                    <span class="text-[11px] font-medium text-slate-500 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>
                        <span>Assigned</span>
                    </span>
                    <span class="font-bold text-xs sm:text-sm text-slate-900 tabular-nums">{{ $assignedCount }}</span>
                </div>
                <div class="flex flex-col items-center justify-center px-1 text-center sm:flex-row sm:items-center sm:gap-2 sm:px-3.5 sm:py-2 sm:rounded-xl sm:bg-white sm:border sm:border-slate-200/90 sm:shadow-2xs">
                    <span class="text-[11px] font-medium text-slate-500 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
                        <span class="sm:hidden">In Review</span>
                        <span class="hidden sm:inline">Under Review</span>
                    </span>
                    <span class="font-bold text-xs sm:text-sm text-slate-900 tabular-nums">{{ $underReviewCount }}</span>
                </div>
                <div class="flex flex-col items-center justify-center px-1 text-center sm:flex-row sm:items-center sm:gap-2 sm:px-3.5 sm:py-2 sm:rounded-xl sm:bg-white sm:border sm:border-slate-200/90 sm:shadow-2xs">
                    <span class="text-[11px] font-medium text-slate-500 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                        <span class="sm:hidden">Revisions</span>
                        <span class="hidden sm:inline">Pending Revision</span>
                    </span>
                    <span class="font-bold text-xs sm:text-sm text-slate-900 tabular-nums">{{ $pendingRevisionCount }}</span>
                </div>
            </div>
            @endif
        </div>

        @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-check-circle text-emerald-600 text-base shrink-0"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if($totalCount > 0)
        <!-- ===== OPERATIONAL TOOLBAR ===== -->
        <div class="flex flex-col gap-3 bg-white p-3 sm:p-4 rounded-2xl border border-slate-200/90 shadow-2xs">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-2.5 sm:gap-3">
                
                <!-- Live Search Bar -->
                <div class="relative flex-1 w-full min-w-0 lg:min-w-[260px]">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search text-xs" aria-hidden="true"></i>
                    </div>
                    <input type="text" 
                           x-model="search"
                           placeholder="Search protocols..."
                           aria-label="Search assigned protocols"
                           class="w-full pl-9 pr-9 py-2.5 text-base sm:text-xs font-medium bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl focus:outline-hidden focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all min-h-[44px]">
                    <button x-show="search.length > 0" 
                            @click="search = ''" 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                            aria-label="Clear search">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Filters and View Switcher -->
                <div class="flex items-center gap-2 sm:gap-2.5 w-full lg:w-auto">
                    <!-- Dropdowns Group (Clean 2-col grid on mobile, inline on sm+) -->
                    @php
                        $hasCatFilter = $categories->count() > 1;
                        $hasTypeFilter = $reviewTypes->count() > 1;
                    @endphp
                    @if($hasCatFilter || $hasTypeFilter)
                    <div class="{{ ($hasCatFilter && $hasTypeFilter) ? 'grid grid-cols-2' : 'flex' }} gap-2 w-full sm:flex sm:w-auto sm:items-center sm:gap-2.5">
                        
                        <!-- Custom Category Popover -->
                        @if($hasCatFilter)
                        <div class="relative w-full sm:w-auto" x-data="{ open: false }">
                            <button type="button" 
                                    @click="open = !open" 
                                    @click.outside="open = false"
                                    @keydown.escape="open = false"
                                    :class="filterCategory !== 'all' ? 'border-brand-primary bg-rose-50/40 text-brand-primary font-bold shadow-xs' : 'text-slate-700 bg-slate-50 hover:bg-white focus:bg-white'"
                                    class="w-full sm:w-auto sm:min-w-[145px] flex items-center justify-between gap-2 px-3 sm:px-3.5 py-2.5 text-xs font-semibold border border-slate-200 rounded-xl transition-all cursor-pointer min-h-[44px]">
                                <div class="flex items-center gap-1.5 truncate">
                                    <i class="fas fa-tag text-[10px] shrink-0" :class="filterCategory !== 'all' ? 'text-brand-primary' : 'text-slate-400'"></i>
                                    <span x-text="filterCategory === 'all' ? 'Categories' : filterCategory" class="truncate"></span>
                                </div>
                                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-brand-primary' : ''"></i>
                            </button>

                            <!-- Custom Floating Popover Menu -->
                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                 class="absolute left-0 top-full mt-2 w-[280px] max-w-[calc(100vw-2rem)] bg-white rounded-2xl border border-slate-200/90 shadow-xl z-50 p-2 space-y-1">
                                
                                <div class="px-2.5 py-1.5 flex items-center justify-between border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                    <span>Research Category</span>
                                    <button type="button" 
                                            x-show="filterCategory !== 'all'" 
                                            @click="filterCategory = 'all'; open = false;"
                                            class="text-brand-primary text-[10px] font-bold hover:underline cursor-pointer">
                                        Reset
                                    </button>
                                </div>

                                <button type="button" 
                                        @click="filterCategory = 'all'; open = false;"
                                        :class="filterCategory === 'all' ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:bg-slate-50'"
                                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs transition-colors min-h-[44px] cursor-pointer">
                                    <span class="truncate">All Categories</span>
                                    <span class="text-[11px] font-semibold text-slate-400 tabular-nums">({{ $totalCount }})</span>
                                </button>

                                @foreach($categories as $cat)
                                <button type="button" 
                                        @click="filterCategory = '{{ $cat }}'; open = false;"
                                        :class="filterCategory === '{{ $cat }}' ? 'bg-rose-50 text-brand-primary font-bold' : 'text-slate-700 hover:bg-slate-50'"
                                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs transition-colors min-h-[44px] cursor-pointer text-left">
                                    <span class="truncate pr-2">{{ $cat }}</span>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="text-[11px] font-semibold text-slate-400 tabular-nums">({{ $categoryCounts[$cat] ?? 0 }})</span>
                                        <i x-show="filterCategory === '{{ $cat }}'" class="fas fa-check text-brand-primary text-xs"></i>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Custom Review Type Popover -->
                        @if($hasTypeFilter)
                        <div class="relative w-full sm:w-auto" x-data="{ open: false }">
                            <button type="button" 
                                    @click="open = !open" 
                                    @click.outside="open = false"
                                    @keydown.escape="open = false"
                                    :class="filterType !== 'all' ? 'border-brand-primary bg-rose-50/40 text-brand-primary font-bold shadow-xs' : 'text-slate-700 bg-slate-50 hover:bg-white focus:bg-white'"
                                    class="w-full sm:w-auto sm:min-w-[140px] flex items-center justify-between gap-2 px-3 sm:px-3.5 py-2.5 text-xs font-semibold border border-slate-200 rounded-xl transition-all cursor-pointer min-h-[44px]">
                                <div class="flex items-center gap-1.5 truncate">
                                    <i class="fas fa-layer-group text-[10px] shrink-0" :class="filterType !== 'all' ? 'text-brand-primary' : 'text-slate-400'"></i>
                                    <span x-text="filterType === 'all' ? 'Review Types' : filterType" class="truncate"></span>
                                </div>
                                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-brand-primary' : ''"></i>
                            </button>

                            <!-- Custom Floating Popover Menu -->
                            <div x-show="open" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                 class="absolute right-0 top-full mt-2 w-[260px] max-w-[calc(100vw-2rem)] bg-white rounded-2xl border border-slate-200/90 shadow-xl z-50 p-2 space-y-1">
                                
                                <div class="px-2.5 py-1.5 flex items-center justify-between border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                    <span>Review Type</span>
                                    <button type="button" 
                                            x-show="filterType !== 'all'" 
                                            @click="filterType = 'all'; open = false;"
                                            class="text-brand-primary text-[10px] font-bold hover:underline cursor-pointer">
                                        Reset
                                    </button>
                                </div>

                                <button type="button" 
                                        @click="filterType = 'all'; open = false;"
                                        :class="filterType === 'all' ? 'bg-slate-100 text-slate-900 font-bold' : 'text-slate-600 hover:bg-slate-50'"
                                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs transition-colors min-h-[44px] cursor-pointer">
                                    <span class="truncate">All Review Types</span>
                                    <span class="text-[11px] font-semibold text-slate-400 tabular-nums">({{ $totalCount }})</span>
                                </button>

                                @foreach($reviewTypes as $type)
                                <button type="button" 
                                        @click="filterType = '{{ $type }}'; open = false;"
                                        :class="filterType === '{{ $type }}' ? 'bg-rose-50 text-brand-primary font-bold' : 'text-slate-700 hover:bg-slate-50'"
                                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs transition-colors min-h-[44px] cursor-pointer text-left">
                                    <span class="truncate pr-2">{{ $type }}</span>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="text-[11px] font-semibold text-slate-400 tabular-nums">({{ $reviewTypeCounts[$type] ?? 0 }})</span>
                                        <i x-show="filterType === '{{ $type }}'" class="fas fa-check text-brand-primary text-xs"></i>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- View Mode Toggle Buttons (Desktop Only: hidden on mobile) -->
                    <div class="hidden md:inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200/80 shrink-0 self-center">
                        <button type="button"
                                @click="setView('table')"
                                :class="viewMode === 'table' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-2 text-xs rounded-lg transition-all flex items-center gap-1.5 cursor-pointer min-h-[38px]"
                                title="Table Ledger View">
                            <i class="fas fa-table-list text-xs" aria-hidden="true"></i>
                            <span class="hidden lg:inline">Ledger</span>
                        </button>
                        <button type="button"
                                @click="setView('grid')"
                                :class="viewMode === 'grid' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-2 text-xs rounded-lg transition-all flex items-center gap-1.5 cursor-pointer min-h-[38px]"
                                title="Card Grid View">
                            <i class="fas fa-grip text-xs" aria-hidden="true"></i>
                            <span class="hidden lg:inline">Cards</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Active Filters Strip -->
            <div x-show="hasActiveFilters()" 
                 x-cloak
                 class="flex items-center gap-2 pt-2.5 border-t border-slate-100 flex-wrap text-xs">
                <span class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Filtered:</span>
                
                <template x-if="search.trim().length > 0">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                        <span>Search: "<span x-text="search" class="font-semibold"></span>"</span>
                        <button type="button" @click="search = ''" class="text-slate-400 hover:text-slate-600 cursor-pointer" aria-label="Remove search filter">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    </span>
                </template>

                <template x-if="filterCategory !== 'all'">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-brand-primary text-xs font-semibold border border-brand-primary/20">
                        <span x-text="filterCategory" class="truncate max-w-[140px]"></span>
                        <button type="button" @click="filterCategory = 'all'" class="text-brand-primary/60 hover:text-brand-primary cursor-pointer" aria-label="Remove category filter">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    </span>
                </template>

                <template x-if="filterType !== 'all'">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-brand-primary text-xs font-semibold border border-brand-primary/20">
                        <span x-text="filterType"></span>
                        <button type="button" @click="filterType = 'all'" class="text-brand-primary/60 hover:text-brand-primary cursor-pointer" aria-label="Remove review type filter">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                    </span>
                </template>

                <button type="button" 
                        @click="resetFilters()"
                        class="ml-auto text-[11px] font-bold text-brand-primary hover:text-brand-secondary underline underline-offset-2 cursor-pointer py-1">
                    Clear all
                </button>
            </div>
        </div>

        <!-- ===== PROTOCOL LEDGER TABLE VIEW ===== -->
        <div x-show="viewMode === 'table' && !isMobile" class="hidden md:block bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 sm:px-6 whitespace-nowrap">REOC Code</th>
                            <th class="py-3.5 px-4">Study Protocol Title</th>
                            <th class="py-3.5 px-4 hidden md:table-cell">Category</th>
                            <th class="py-3.5 px-4 hidden lg:table-cell whitespace-nowrap">Review Type</th>
                            <th class="py-3.5 px-4 hidden sm:table-cell whitespace-nowrap">Assigned Date</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Status</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @foreach($titles as $title)
                        @php
                            $statusTextColor = match($title->Status) {
                                'Approved' => 'text-emerald-700',
                                'Disapproved' => 'text-rose-700',
                                'Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions' => 'text-amber-700',
                                'Reviewer Assigned' => 'text-blue-700',
                                default => 'text-indigo-700',
                            };

                            $revTypeTextColor = match($title->Review_Type) {
                                'Exempt Review' => 'text-emerald-700',
                                'Expedited Review' => 'text-blue-700',
                                'Full Board Review' => 'text-amber-700',
                                default => 'text-slate-600'
                            };

                            $showResearcher = auth()->user()->reviewer?->show_researcher_identity && $title->researcher?->user;
                            $researcherName = $showResearcher 
                                ? ($title->researcher->user->first_name . ' ' . $title->researcher->user->last_name) 
                                : '';
                        @endphp
                        <tr x-show="matches({
                                title: {{ json_encode($title->Study_Protocol_title) }},
                                code: {{ json_encode($title->reoc_code ?? '') }},
                                category: {{ json_encode($title->Research_Category ?? '') }},
                                type: {{ json_encode($title->Review_Type ?? '') }},
                                status: {{ json_encode($title->Status ?? '') }},
                                researcher: {{ json_encode($researcherName) }}
                            })"
                            class="hover:bg-slate-50/70 transition-colors group">
                            
                            <!-- Code / REOC -->
                            <td class="py-4 px-4 sm:px-6 align-middle whitespace-nowrap">
                                <span class="font-mono text-xs font-semibold text-slate-600">
                                    {{ $title->reoc_code ?: ('#' . $title->id) }}
                                </span>
                            </td>

                            <!-- Protocol Title & Researcher -->
                            <td class="py-4 px-4 align-middle max-w-md">
                                <a href="{{ route('reviewer.view_files', $title->id) }}" 
                                   class="font-bold text-slate-900 group-hover:text-brand-primary transition-colors line-clamp-2 leading-snug">
                                    {{ $title->Study_Protocol_title }}
                                </a>
                                @if($showResearcher)
                                <div class="flex items-center gap-1.5 text-xs text-slate-500 mt-1">
                                    <i class="fas fa-user-graduate text-slate-400 text-[11px]" aria-hidden="true"></i>
                                    <span class="truncate">{{ $researcherName }}</span>
                                </div>
                                @endif
                                <div class="flex items-center gap-2 mt-1.5 md:hidden text-[11px] text-slate-500 flex-wrap">
                                    <span>{{ $title->Research_Category ?? 'No Category' }}</span>
                                    <span>•</span>
                                    <span class="font-bold uppercase tracking-wider {{ $revTypeTextColor }}">{{ $title->Review_Type ?? 'Pending Type' }}</span>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-4 px-4 align-middle hidden md:table-cell text-slate-600 font-medium">
                                {{ $title->Research_Category ?? '—' }}
                            </td>

                            <!-- Review Type (Colored Letters, No Background Fill) -->
                            <td class="py-4 px-4 align-middle hidden lg:table-cell whitespace-nowrap">
                                <span class="text-xs font-bold uppercase tracking-wider {{ $revTypeTextColor }}">
                                    {{ $title->Review_Type ?? 'Pending' }}
                                </span>
                            </td>

                            <!-- Assigned Date -->
                            <td class="py-4 px-4 align-middle hidden sm:table-cell text-slate-500 tabular-nums whitespace-nowrap">
                                {{ $title->created_at ? $title->created_at->format('M d, Y') : '—' }}
                            </td>

                            <!-- High-Contrast Status Typography (Per DESIGN.md) -->
                            <td class="py-4 px-4 align-middle whitespace-nowrap">
                                <span class="text-xs font-bold uppercase tracking-wider {{ $statusTextColor }}">
                                    {{ $title->Status ?? 'Under Review' }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="py-4 px-4 sm:px-6 align-middle text-right whitespace-nowrap">
                                <a href="{{ route('reviewer.view_files', $title->id) }}" 
                                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-brand-primary text-slate-700 hover:text-white border border-slate-200 hover:border-brand-primary text-xs font-semibold transition-all shadow-2xs min-h-[44px] whitespace-nowrap cursor-pointer">
                                    <i class="fas fa-folder-open text-xs" aria-hidden="true"></i>
                                    <span>View Files</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== PROTOCOL CARDS GRID VIEW ===== -->
        <div x-show="viewMode === 'grid' || isMobile" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach($titles as $title)
            @php
                $statusTextColor = match($title->Status) {
                    'Approved' => 'text-emerald-700',
                    'Disapproved' => 'text-rose-700',
                    'Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions' => 'text-amber-700',
                    'Reviewer Assigned' => 'text-blue-700',
                    default => 'text-indigo-700',
                };

                $revTypeTextColor = match($title->Review_Type) {
                    'Exempt Review' => 'text-emerald-700',
                    'Expedited Review' => 'text-blue-700',
                    'Full Board Review' => 'text-amber-700',
                    default => 'text-slate-600'
                };

                $showResearcher = auth()->user()->reviewer?->show_researcher_identity && $title->researcher?->user;
                $researcherName = $showResearcher 
                    ? ($title->researcher->user->first_name . ' ' . $title->researcher->user->last_name) 
                    : '';
            @endphp
            <div x-show="matches({
                    title: {{ json_encode($title->Study_Protocol_title) }},
                    code: {{ json_encode($title->reoc_code ?? '') }},
                    category: {{ json_encode($title->Research_Category ?? '') }},
                    type: {{ json_encode($title->Review_Type ?? '') }},
                    status: {{ json_encode($title->Status ?? '') }},
                    researcher: {{ json_encode($researcherName) }}
                })"
                class="group relative overflow-hidden rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex flex-col p-5 sm:p-6">
                
                <!-- Status Badge Header -->
                <div class="mb-3.5 flex items-center justify-between gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider {{ $statusTextColor }} truncate">
                        {{ $title->Status ?? 'Under Review' }}
                    </span>
                    
                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest font-mono">
                        {{ $title->reoc_code ?: ('#' . $title->id) }}
                    </span>
                </div>

                <!-- Protocol Title -->
                <h3 class="font-bold text-slate-900 text-sm sm:text-base leading-snug mb-3 line-clamp-3 group-hover:text-brand-primary transition-colors tracking-tight" 
                    title="{{ $title->Study_Protocol_title }}">
                    {{ $title->Study_Protocol_title }}
                </h3>

                <!-- Meta Details -->
                <div class="space-y-2 mb-5 text-xs">
                    <div class="flex items-center gap-2 text-slate-600">
                        <i class="fas fa-tag text-slate-400 w-4 text-center shrink-0" aria-hidden="true"></i>
                        <span class="font-medium truncate">{{ $title->Research_Category ?? 'No Category' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-file-signature text-slate-400 w-4 text-center shrink-0" aria-hidden="true"></i>
                        <span class="text-xs font-bold uppercase tracking-wider {{ $revTypeTextColor }} truncate">{{ $title->Review_Type ?? 'Pending Type' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-500 text-[11px]">
                        <i class="fas fa-calendar-day text-slate-400 w-4 text-center shrink-0" aria-hidden="true"></i>
                        <span class="truncate">Assigned: {{ $title->created_at ? $title->created_at->format('M d, Y') : '—' }}</span>
                    </div>

                    @if($showResearcher)
                    <div class="flex items-center gap-2 text-slate-600 pt-1.5 border-t border-slate-100">
                        <i class="fas fa-user-graduate text-slate-400 w-4 text-center shrink-0" aria-hidden="true"></i>
                        <span class="font-medium truncate" title="{{ $researcherName }}">
                            {{ $researcherName }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Action Button (Min 44px Touch Target) -->
                <div class="mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('reviewer.view_files', $title->id) }}" 
                       class="w-full min-h-[44px] py-2.5 px-4 bg-slate-50 hover:bg-slate-100/90 text-slate-700 hover:text-slate-900 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 border border-slate-200 hover:border-slate-300 flex items-center justify-center gap-2 shadow-2xs active:scale-[0.99] cursor-pointer">
                        <i class="fas fa-folder-open text-sm text-slate-500" aria-hidden="true"></i>
                        <span>View Protocol Files</span>
                        <i class="fas fa-arrow-right text-[10px] ml-1 opacity-60 group-hover:translate-x-0.5 transition-transform" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Filter Empty State (When Search Yields No Match) -->
        <div x-cloak 
             x-show="false" 
             id="filter-empty-message"
             class="py-12 px-6 text-center bg-white rounded-2xl border border-slate-200/90 shadow-2xs">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-filter-circle-xmark text-xl"></i>
            </div>
            <h3 class="font-bold text-sm text-slate-800">No protocols match your search criteria</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Try clearing search filters or changing the category/type selection.</p>
            <button type="button" 
                    @click="resetFilters()" 
                    class="mt-4 inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors cursor-pointer min-h-[44px]">
                <i class="fas fa-rotate-left text-xs"></i>
                <span>Reset Filters</span>
            </button>
        </div>

        @else
        <!-- ===== DATABASE EMPTY STATE (Dignified Institutional) ===== -->
        <div class="py-16 px-6 text-center bg-white rounded-2xl border border-slate-200/90 shadow-2xs max-w-2xl mx-auto">
            <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-center mx-auto mb-4 text-slate-400 shadow-2xs">
                <i class="fas fa-clipboard-check text-2xl" aria-hidden="true"></i>
            </div>
            <h3 class="font-bold text-base text-slate-900 tracking-tight font-heading">
                {{ $isReeval ? 'No Protocols Awaiting Re-Evaluation' : 'No Assigned Protocols Found' }}
            </h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-1.5 max-w-md mx-auto leading-relaxed">
                {{ $isReeval 
                    ? 'There are currently no revised submissions returned from researchers awaiting your secondary review.' 
                    : 'You currently have no active protocol evaluations assigned by the ethics committee. Newly assigned protocols will automatically appear here.' }}
            </p>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('reviewer.reviewed_titles') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-bold transition-all shadow-2xs min-h-[44px] cursor-pointer">
                    <i class="fas fa-check-circle text-emerald-600" aria-hidden="true"></i>
                    <span>View Reviewed Archive</span>
                </a>
                <a href="{{ url()->current() }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-800 border border-slate-200 text-xs font-bold transition-all shadow-2xs min-h-[44px] cursor-pointer">
                    <i class="fas fa-rotate text-slate-400" aria-hidden="true"></i>
                    <span>Refresh Ledger</span>
                </a>
            </div>
        </div>
        @endif

    </div>
</x-reviewer_layout>
