<x-admin_layout :title="'Analytics & Reports'">
    <style>
        #analytics-dashboard ::selection,
        #filterModal ::selection,
        #seeAllModal ::selection,
        #detailsModal ::selection {
            background-color: rgba(139, 0, 0, 0.15);
            color: #8B0000;
        }
        #filterModal input,
        #filterModal select {
            caret-color: #8B0000;
        }
        .tabular-nums,
        #analytics-dashboard table td,
        #analytics-dashboard table th,
        #detailsModal table td,
        #detailsModal table th {
            font-variant-numeric: tabular-nums;
            -moz-font-feature-settings: "tnum";
            -webkit-font-feature-settings: "tnum";
            font-feature-settings: "tnum";
        }
        #filterModalPanel::-webkit-scrollbar,
        #seeAllModalPanel::-webkit-scrollbar,
        #detailsModalContent::-webkit-scrollbar,
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        #filterModalPanel::-webkit-scrollbar-track,
        #seeAllModalPanel::-webkit-scrollbar-track,
        #detailsModalContent::-webkit-scrollbar-track,
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        #filterModalPanel::-webkit-scrollbar-thumb,
        #seeAllModalPanel::-webkit-scrollbar-thumb,
        #detailsModalContent::-webkit-scrollbar-thumb,
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        #filterModalPanel::-webkit-scrollbar-thumb:hover,
        #seeAllModalPanel::-webkit-scrollbar-thumb:hover,
        #detailsModalContent::-webkit-scrollbar-thumb:hover,
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Suppress sidebar scrollbar */
        aside nav,
        #admin-sidebar-nav,
        #super-admin-sidebar-nav {
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        aside nav::-webkit-scrollbar,
        #admin-sidebar-nav::-webkit-scrollbar,
        #super-admin-sidebar-nav::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
    </style>

    <div id="analytics-dashboard" class="max-w-7xl mx-auto w-full space-y-8 selection:bg-[#8B0000] selection:text-white pt-3 sm:pt-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end pb-6 border-b border-slate-200 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Analytics & Reports</h1>
                <p class="text-slate-500 mt-1 sm:mt-2 text-xs sm:text-sm">Real-time insights into research submission performance.</p>
            </div>
            @php
                $currentYear = (int)date('Y');
                $lastYear = $currentYear - 1;
                $currentMonth = (int)date('n');
                $hasExactDates = !empty(request('exact_start')) && !empty(request('exact_end'));

                $isAllTimePreset = (!$hasExactDates && (int)$startMonth === 1 && (int)$endMonth === 12 && $startYear === 'all' && $endYear === 'all');
                $isThisYearPreset = (!$hasExactDates && (int)$startMonth === 1 && (int)$endMonth === 12 && (string)$startYear === (string)$currentYear && (string)$endYear === (string)$currentYear);
                $isLastYearPreset = (!$hasExactDates && (int)$startMonth === 1 && (int)$endMonth === 12 && (string)$startYear === (string)$lastYear && (string)$endYear === (string)$lastYear);
                $isThisMonthPreset = (!$hasExactDates && (int)$startMonth === $currentMonth && (int)$endMonth === $currentMonth && (string)$startYear === (string)$currentYear && (string)$endYear === (string)$currentYear);

                $activeFilterCount = 0;
                if (!$isAllTimePreset) $activeFilterCount++;
                if (!empty($selectedStatus)) $activeFilterCount++;
                if (!empty($selectedReviewType)) $activeFilterCount++;
                if (!empty($selectedThesisType)) $activeFilterCount++;
                if (!empty($selectedCategory)) $activeFilterCount++;
                if (!empty($selectedAffiliation)) $activeFilterCount++;
                if (!empty($selectedCollege) && $selectedAffiliation !== 'External') $activeFilterCount++;

                $timeframeLabel = 'All Time';
                if ($isThisYearPreset) {
                    $timeframeLabel = "This Year ({$currentYear})";
                } elseif ($isLastYearPreset) {
                    $timeframeLabel = "Last Year ({$lastYear})";
                } elseif ($isThisMonthPreset) {
                    $timeframeLabel = date('M Y');
                } elseif (!$isAllTimePreset && !empty($dateRangeSubtitle)) {
                    $timeframeLabel = $dateRangeSubtitle;
                }
            @endphp

            <div class="flex flex-wrap sm:flex-nowrap gap-2.5 sm:gap-3 mt-4 md:mt-0 items-center justify-start md:justify-end shrink-0">
                <!-- 1. Interactive Timeframe Dropdown (No Overlap with Filter Drawer) -->
                <div x-data="{ timeDropdownOpen: false }" class="relative inline-block text-left" @keydown.escape.window="timeDropdownOpen = false">
                    <button type="button" 
                            @click="timeDropdownOpen = !timeDropdownOpen" 
                            @click.away="timeDropdownOpen = false"
                            aria-haspopup="true"
                            :aria-expanded="timeDropdownOpen.toString()"
                            aria-label="Timeframe preset: {{ $timeframeLabel }}. Click to change range." 
                            class="min-h-[38px] px-3.5 sm:px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 text-slate-700 hover:text-[#8B0000] rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-2xs flex items-center gap-2 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] active:scale-[0.98]">
                        <i class="fas fa-calendar-alt text-[#8B0000] text-xs" aria-hidden="true"></i>
                        <span class="max-w-[150px] sm:max-w-[180px] truncate">{{ $timeframeLabel }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-[#8B0000]': timeDropdownOpen }" aria-hidden="true"></i>
                    </button>

                    <div x-show="timeDropdownOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute left-0 md:left-auto md:right-0 z-50 mt-2 w-60 origin-top-right rounded-2xl bg-white p-1.5 shadow-xl ring-1 ring-black/5 border border-slate-100 focus:outline-none"
                         style="display: none;">
                        <div class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400">Quick Timeframe</div>
                        
                        <!-- All Time -->
                        <button type="button" 
                                onclick="applyTimePreset('all')" 
                                @click="timeDropdownOpen = false"
                                class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold flex items-center justify-between transition-colors cursor-pointer {{ $isAllTimePreset ? 'bg-red-50 text-[#8B0000] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#8B0000]' }}">
                            <span class="flex items-center gap-2.5">
                                <i class="fas fa-infinity text-xs w-4 text-center {{ $isAllTimePreset ? 'text-[#8B0000]' : 'text-slate-400' }}" aria-hidden="true"></i>
                                All Time
                            </span>
                            @if($isAllTimePreset)
                                <i class="fas fa-check text-xs text-[#8B0000]" aria-hidden="true"></i>
                            @endif
                        </button>

                        <!-- This Year -->
                        <button type="button" 
                                onclick="applyTimePreset('this_year')" 
                                @click="timeDropdownOpen = false"
                                class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold flex items-center justify-between transition-colors cursor-pointer {{ $isThisYearPreset ? 'bg-red-50 text-[#8B0000] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#8B0000]' }}">
                            <span class="flex items-center gap-2.5">
                                <i class="fas fa-calendar text-xs w-4 text-center {{ $isThisYearPreset ? 'text-[#8B0000]' : 'text-slate-400' }}" aria-hidden="true"></i>
                                This Year ({{ $currentYear }})
                            </span>
                            @if($isThisYearPreset)
                                <i class="fas fa-check text-xs text-[#8B0000]" aria-hidden="true"></i>
                            @endif
                        </button>

                        <!-- Last Year -->
                        <button type="button" 
                                onclick="applyTimePreset('last_year')" 
                                @click="timeDropdownOpen = false"
                                class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold flex items-center justify-between transition-colors cursor-pointer {{ $isLastYearPreset ? 'bg-red-50 text-[#8B0000] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#8B0000]' }}">
                            <span class="flex items-center gap-2.5">
                                <i class="fas fa-calendar-minus text-xs w-4 text-center {{ $isLastYearPreset ? 'text-[#8B0000]' : 'text-slate-400' }}" aria-hidden="true"></i>
                                Last Year ({{ $lastYear }})
                            </span>
                            @if($isLastYearPreset)
                                <i class="fas fa-check text-xs text-[#8B0000]" aria-hidden="true"></i>
                            @endif
                        </button>

                        <!-- This Month -->
                        <button type="button" 
                                onclick="applyTimePreset('this_month')" 
                                @click="timeDropdownOpen = false"
                                class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold flex items-center justify-between transition-colors cursor-pointer {{ $isThisMonthPreset ? 'bg-red-50 text-[#8B0000] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#8B0000]' }}">
                            <span class="flex items-center gap-2.5">
                                <i class="fas fa-calendar-day text-xs w-4 text-center {{ $isThisMonthPreset ? 'text-[#8B0000]' : 'text-slate-400' }}" aria-hidden="true"></i>
                                This Month ({{ date('M Y') }})
                            </span>
                            @if($isThisMonthPreset)
                                <i class="fas fa-check text-xs text-[#8B0000]" aria-hidden="true"></i>
                            @endif
                        </button>

                        <!-- Past 6 Months -->
                        <button type="button" 
                                onclick="applyTimePreset('past_6_months')" 
                                @click="timeDropdownOpen = false"
                                class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold flex items-center justify-between transition-colors cursor-pointer text-slate-700 hover:bg-slate-50 hover:text-[#8B0000]">
                            <span class="flex items-center gap-2.5">
                                <i class="fas fa-history text-xs w-4 text-center text-slate-400" aria-hidden="true"></i>
                                Past 6 Months
                            </span>
                        </button>

                        <div class="my-1 border-t border-slate-100"></div>

                        <!-- Custom Date Range Option (Opens Filter Drawer) -->
                        <button type="button" 
                                onclick="openCustomDateFilter()" 
                                @click="timeDropdownOpen = false"
                                class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold text-[#8B0000] hover:bg-red-50 flex items-center justify-between transition-colors cursor-pointer">
                            <span class="flex items-center gap-2.5">
                                <i class="fas fa-sliders-h text-xs w-4 text-center text-[#8B0000]" aria-hidden="true"></i>
                                Custom Range...
                            </span>
                            <i class="fas fa-chevron-right text-[10px] text-[#8B0000]/60" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <!-- 2. Filter Data Button (Opens Comprehensive Drawer) -->
                <button type="button" 
                        onclick="openFilterModal()" 
                        aria-label="Open comprehensive filter options{{ $activeFilterCount > 0 ? ', ' . $activeFilterCount . ' active filters applied' : '' }}" 
                        class="min-h-[38px] px-3.5 sm:px-4 py-2 bg-[#8B0000] hover:bg-[#700000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:shadow-md active:scale-[0.98] transition-all flex items-center gap-2 shadow-xs cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-2">
                    <i class="fas fa-filter text-xs" aria-hidden="true"></i>
                    <span>Filter Data</span>
                    @if($activeFilterCount > 0)
                        <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-white text-[#8B0000] text-[11px] font-black shadow-xs">
                            {{ $activeFilterCount }}
                        </span>
                    @endif
                </button>

                <div class="hidden sm:block h-8 w-px bg-slate-200"></div>

                <!-- 3. Consolidated Export Dropdown (Eliminates Button Overflow and Wrapping) -->
                <div x-data="{ exportDropdownOpen: false }" class="relative inline-block text-left" @keydown.escape.window="exportDropdownOpen = false">
                    <button type="button"
                            @click="exportDropdownOpen = !exportDropdownOpen"
                            @click.away="exportDropdownOpen = false"
                            aria-haspopup="true"
                            :aria-expanded="exportDropdownOpen.toString()"
                            aria-label="Export report data options"
                            class="min-h-[38px] px-3.5 sm:px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 text-slate-700 hover:text-[#8B0000] rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-2xs flex items-center gap-2 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] active:scale-[0.98]">
                        <i class="fas fa-download text-xs text-slate-400" aria-hidden="true"></i>
                        <span>Export</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-[#8B0000]': exportDropdownOpen }" aria-hidden="true"></i>
                    </button>

                    <div x-show="exportDropdownOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 -translate-y-1"
                         class="absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-2xl bg-white p-2 shadow-xl ring-1 ring-black/5 border border-slate-100 focus:outline-none"
                         style="display: none;">
                        <div class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center justify-between">
                            <span>Available Formats</span>
                            <span class="text-[9px] font-semibold text-slate-400 font-mono">3 EXPORTS</span>
                        </div>
                        
                        <!-- 1. PDF Export -->
                        <button type="button" 
                                id="exportPdfBtn" 
                                onclick="exportToPdf();" 
                                @click="exportDropdownOpen = false"
                                class="w-full text-left px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-red-50/60 hover:text-[#8B0000] flex items-center gap-3 transition-colors cursor-pointer group min-h-[44px] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                            <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0 border border-red-100 group-hover:scale-105 transition-transform">
                                <i class="fas fa-file-pdf text-sm" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-slate-800 group-hover:text-[#8B0000] flex items-center justify-between">
                                    <span>Export as PDF</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">.pdf</span>
                                </div>
                                <div class="text-[10px] text-slate-400 truncate">Official Executive Report</div>
                            </div>
                        </button>

                        <!-- 2. CSV Export -->
                        <a href="{{ route('admin.analytics.export', request()->query()) }}" 
                           id="exportCsvBtn" 
                           @click="exportDropdownOpen = false"
                           class="w-full text-left px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50/60 hover:text-emerald-800 flex items-center gap-3 transition-colors cursor-pointer group min-h-[44px] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 group-hover:scale-105 transition-transform">
                                <i class="fas fa-file-csv text-sm" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-slate-800 group-hover:text-emerald-800 flex items-center justify-between">
                                    <span>Export to CSV</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">.csv</span>
                                </div>
                                <div class="text-[10px] text-slate-400 truncate">National Ethics Registry Dataset</div>
                            </div>
                        </a>

                        <!-- 3. Word Export -->
                        <a href="{{ route('admin.analytics.export_word', request()->query()) }}" 
                           id="exportWordBtn" 
                           @click="exportDropdownOpen = false"
                           class="w-full text-left px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-blue-50/60 hover:text-blue-800 flex items-center gap-3 transition-colors cursor-pointer group min-h-[44px] focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 group-hover:scale-105 transition-transform">
                                <i class="fas fa-file-word text-sm" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-slate-800 group-hover:text-blue-800 flex items-center justify-between">
                                    <span>Export to Word</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">.docx</span>
                                </div>
                                <div class="text-[10px] text-slate-400 truncate">Standard Institutional Document</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @php
            $hasActiveDateFilter = !$isAllTimePreset;
            $hasAnyActiveFilters = $activeFilterCount > 0;
        @endphp

        @if($hasAnyActiveFilters)
        <div class="flex flex-wrap items-center gap-2 -mt-4 mb-2">
            <span class="text-xs font-bold text-slate-400 mr-0.5 flex items-center gap-1.5">
                <i class="fas fa-sliders-h text-[11px]" aria-hidden="true"></i> Active Filters:
            </span>
            @if($hasActiveDateFilter)
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-slate-200 min-h-[30px]">
                <i class="fas fa-calendar-alt text-[#8B0000]" aria-hidden="true"></i>
                {{ $dateRangeSubtitle ?? 'Custom Period' }}
                <button type="button" onclick="clearFilter('date')" aria-label="Remove date filter" class="inline-flex items-center justify-center w-6 h-6 min-w-[24px] min-h-[24px] rounded-full hover:bg-slate-200 text-slate-400 hover:text-[#8B0000] transition-colors ml-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"><i class="fas fa-times text-xs" aria-hidden="true"></i></button>
            </div>
            @endif
            @if($selectedStatus) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-slate-200 min-h-[30px]">
                <i class="fas fa-info-circle text-slate-400" aria-hidden="true"></i> {{ $selectedStatus }}
                <button type="button" onclick="clearFilter('status')" aria-label="Remove status filter" class="inline-flex items-center justify-center w-6 h-6 min-w-[24px] min-h-[24px] rounded-full hover:bg-slate-200 text-slate-400 hover:text-[#8B0000] transition-colors ml-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"><i class="fas fa-times text-xs" aria-hidden="true"></i></button>
            </div> 
            @endif
            @if($selectedReviewType) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-slate-200 min-h-[30px]">
                <i class="fas fa-clipboard-check text-slate-400" aria-hidden="true"></i> {{ $selectedReviewType }}
                <button type="button" onclick="clearFilter('review_type')" aria-label="Remove review type filter" class="inline-flex items-center justify-center w-6 h-6 min-w-[24px] min-h-[24px] rounded-full hover:bg-slate-200 text-slate-400 hover:text-[#8B0000] transition-colors ml-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"><i class="fas fa-times text-xs" aria-hidden="true"></i></button>
            </div> 
            @endif
            @if($selectedThesisType) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-slate-200 min-h-[30px]">
                <i class="fas fa-book text-slate-400" aria-hidden="true"></i> {{ $selectedThesisType }}
                <button type="button" onclick="clearFilter('thesis_type')" aria-label="Remove thesis type filter" class="inline-flex items-center justify-center w-6 h-6 min-w-[24px] min-h-[24px] rounded-full hover:bg-slate-200 text-slate-400 hover:text-[#8B0000] transition-colors ml-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"><i class="fas fa-times text-xs" aria-hidden="true"></i></button>
            </div> 
            @endif
            @if($selectedCategory) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-slate-200 min-h-[30px]">
                <i class="fas fa-tags text-slate-400" aria-hidden="true"></i> {{ $selectedCategory }}
                <button type="button" onclick="clearFilter('category')" aria-label="Remove category filter" class="inline-flex items-center justify-center w-6 h-6 min-w-[24px] min-h-[24px] rounded-full hover:bg-slate-200 text-slate-400 hover:text-[#8B0000] transition-colors ml-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"><i class="fas fa-times text-xs" aria-hidden="true"></i></button>
            </div> 
            @endif
            @if($selectedAffiliation) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-slate-200 min-h-[30px]">
                <i class="fas fa-users text-slate-400" aria-hidden="true"></i> {{ $selectedAffiliation }}
                <button type="button" onclick="clearFilter('affiliation')" aria-label="Remove affiliation filter" class="inline-flex items-center justify-center w-6 h-6 min-w-[24px] min-h-[24px] rounded-full hover:bg-slate-200 text-slate-400 hover:text-[#8B0000] transition-colors ml-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"><i class="fas fa-times text-xs" aria-hidden="true"></i></button>
            </div> 
            @endif
            @if($selectedCollege && $selectedAffiliation !== 'External') 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-2xs border border-slate-200 min-h-[30px]">
                <i class="fas fa-university text-slate-400" aria-hidden="true"></i> {{ $selectedCollege }}
                <button type="button" onclick="clearFilter('college')" aria-label="Remove college filter" class="inline-flex items-center justify-center w-6 h-6 min-w-[24px] min-h-[24px] rounded-full hover:bg-slate-200 text-slate-400 hover:text-[#8B0000] transition-colors ml-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]"><i class="fas fa-times text-xs" aria-hidden="true"></i></button>
            </div> 
            @endif
            <button type="button" onclick="resetFilters()" class="text-xs font-bold text-[#8B0000] hover:text-red-900 hover:underline px-2 py-1 transition-colors cursor-pointer ml-1">
                Clear all
            </button>
        </div>
        @endif



        <!-- Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Metric Card 1 -->
            <div role="button" tabindex="0"
                 aria-label="View Total Submissions details"
                 onclick="openDetailsModal('submissions', 'Total Submissions')"
                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openDetailsModal('submissions', 'Total Submissions');}"
                 class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group cursor-pointer hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-200/50 hover:border-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] transition-all duration-200">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none" aria-hidden="true">
                    <i class="fas fa-file-alt text-6xl text-[#8B0000]"></i>
                </div>
                <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">Total Submissions</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-extrabold text-slate-800 tabular-nums tracking-tight">{{ number_format($totalSubmissions) }}</h3>
                    @if($submissionsGrowthRate > 0)
                        <span class="text-xs font-bold text-green-600 bg-green-50 px-1.5 py-0.5 rounded mb-1 tabular-nums" title="Increase compared to previous period"><i class="fas fa-arrow-up" aria-hidden="true"></i> {{ $submissionsGrowthRate }}% vs prev</span>
                    @elseif($submissionsGrowthRate < 0)
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded mb-1 tabular-nums" title="Decrease compared to previous period"><i class="fas fa-arrow-down" aria-hidden="true"></i> {{ abs($submissionsGrowthRate) }}% vs prev</span>
                    @else
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded mb-1 tabular-nums" title="No change compared to previous period"><i class="fas fa-minus" aria-hidden="true"></i> 0% vs prev</span>
                    @endif
                </div>
            </div>
            
            <!-- Metric Card 2 -->
            <div role="button" tabindex="0"
                 aria-label="View Approved Protocols details"
                 onclick="openDetailsModal('approved', 'Approved Protocols')"
                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openDetailsModal('approved', 'Approved Protocols');}"
                 class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group cursor-pointer hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-200/50 hover:border-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] transition-all duration-200">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none" aria-hidden="true">
                    <i class="fas fa-check-circle text-6xl text-green-600"></i>
                </div>
                <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">Approved Protocols</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-extrabold text-slate-800 tabular-nums tracking-tight">{{ number_format($approvedCount) }}</h3>
                    <span class="text-xs font-bold text-slate-500 mb-1 tabular-nums">{{ $approvalRate }}% Approval Rate</span>
                </div>
            </div>

            <!-- Metric Card 3 -->
            <div role="button" tabindex="0"
                 aria-label="View Revisions Required details"
                 onclick="openDetailsModal('revisions', 'Revisions Required')"
                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openDetailsModal('revisions', 'Revisions Required');}"
                 class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group cursor-pointer hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-200/50 hover:border-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] transition-all duration-200">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none" aria-hidden="true">
                    <i class="fas fa-exclamation-triangle text-6xl text-orange-500"></i>
                </div>
                <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">Revisions Required</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-extrabold text-slate-800 tabular-nums tracking-tight">{{ number_format($revisionsCount) }}</h3>
                    <span class="text-xs font-bold text-slate-500 mb-1 tabular-nums">{{ $revisionsRate }}% Revision Rate</span>
                </div>
            </div>

            <!-- Metric Card 4 -->
            <div role="button" tabindex="0"
                 aria-label="View Active Researchers details"
                 onclick="openDetailsModal('researchers', 'Active Researchers')"
                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openDetailsModal('researchers', 'Active Researchers');}"
                 class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group cursor-pointer hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-200/50 hover:border-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] transition-all duration-200">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none" aria-hidden="true">
                    <i class="fas fa-users text-6xl text-blue-600"></i>
                </div>
                <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">Active Researchers</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-extrabold text-slate-800 tabular-nums tracking-tight">{{ number_format($activeResearchers) }}</h3>
                    <span class="text-xs font-bold text-slate-500 mb-1">Registered Researchers</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Daily Trend (Takes up 2/3) -->
            <section class="lg:col-span-2 bg-white rounded-2xl shadow-xl border border-slate-100 p-8 relative overflow-hidden flex flex-col">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fas fa-chart-line text-9xl text-[#8B0000]"></i>
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 w-full h-full" x-data="{ viewMode: 'timeline' }">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" x-text="viewMode === 'timeline' ? '{{ addslashes($overviewTitle) }}' : 'Approval Status Distribution'">{{ $overviewTitle }}</h2>
                            <p class="text-xs text-slate-500 font-medium mt-1" x-text="viewMode === 'timeline' ? '{{ addslashes($dateRangeSubtitle ?? 'All Recorded Submissions') }}' : 'Visual breakdown across review statuses'">
                                {{ $dateRangeSubtitle ?? 'All Recorded Submissions' }}
                            </p>
                        </div>
                        
                        <!-- Accessible View Mode Tablist -->
                        <div role="tablist" aria-label="Chart view mode" class="relative inline-flex items-center bg-slate-100 rounded-xl p-1 shrink-0 z-20 shadow-inner">
                            <button type="button"
                                    role="tab"
                                    id="chart-tab-timeline"
                                    aria-controls="chart-panel-timeline"
                                    :aria-selected="viewMode === 'timeline'"
                                    :tabindex="viewMode === 'timeline' ? 0 : -1"
                                    @click="viewMode = 'timeline'"
                                    @keydown.arrow-right.prevent="viewMode = 'pie'; $nextTick(() => document.getElementById('chart-tab-pie')?.focus())"
                                    @keydown.arrow-left.prevent="viewMode = 'pie'; $nextTick(() => document.getElementById('chart-tab-pie')?.focus())"
                                    class="relative z-10 flex items-center justify-center px-3 h-8 rounded-lg text-xs font-bold transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]"
                                    :class="viewMode === 'timeline' ? 'bg-white text-[#8B0000] shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                                <i class="fas fa-chart-line mr-1.5" aria-hidden="true"></i> Timeline
                            </button>
                            <button type="button"
                                    role="tab"
                                    id="chart-tab-pie"
                                    aria-controls="chart-panel-pie"
                                    :aria-selected="viewMode === 'pie'"
                                    :tabindex="viewMode === 'pie' ? 0 : -1"
                                    @click="viewMode = 'pie'"
                                    @keydown.arrow-right.prevent="viewMode = 'timeline'; $nextTick(() => document.getElementById('chart-tab-timeline')?.focus())"
                                    @keydown.arrow-left.prevent="viewMode = 'timeline'; $nextTick(() => document.getElementById('chart-tab-timeline')?.focus())"
                                    class="relative z-10 flex items-center justify-center px-3 h-8 rounded-lg text-xs font-bold transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]"
                                    :class="viewMode === 'pie' ? 'bg-white text-[#8B0000] shadow-sm' : 'text-slate-600 hover:text-slate-900'">
                                <i class="fas fa-chart-pie mr-1.5" aria-hidden="true"></i> Distribution
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 w-full relative min-h-[320px]">
                        <div id="chart-panel-timeline" role="tabpanel" aria-labelledby="chart-tab-timeline" x-show="viewMode === 'timeline'" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 w-full h-full">
                            <canvas id="dailyTrendChart" role="img" aria-label="Submission Trend Chart over time" class="w-full h-full">
                                <p class="sr-only">Submission trend timeline displaying research protocol volume across the selected timeframe.</p>
                                <table class="sr-only" aria-label="Submission Trend Data Table">
                                    <thead><tr><th scope="col">Time Period</th><th scope="col">Submissions</th></tr></thead>
                                    <tbody>
                                        @foreach($dayLabels as $idx => $lbl)
                                        <tr><td>{{ $lbl }}</td><td>{{ $dailyData[$idx] ?? 0 }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </canvas>
                        </div>
                        
                        <div id="chart-panel-pie" role="tabpanel" aria-labelledby="chart-tab-pie" x-show="viewMode === 'pie'" style="display: none;" x-transition:enter="transition-opacity duration-500 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 w-full h-full flex items-center justify-center">
                            <canvas id="approvalPieChart" role="img" aria-label="Approval Status Distribution Chart" class="max-h-full">
                                <p class="sr-only">Distribution of research submissions across approval statuses.</p>
                                <table class="sr-only" aria-label="Approval Status Distribution Table">
                                    <thead><tr><th scope="col">Status</th><th scope="col">Count</th></tr></thead>
                                    <tbody>
                                        @foreach($approvalTrends as $statusKey => $statusCount)
                                        <tr><td>{{ $statusKey }}</td><td>{{ $statusCount }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </canvas>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Right Column: Diagnostic Widgets (Takes up 1/3) -->
            <div class="space-y-8">
                <!-- Widget 1: Top Submitting Colleges / Departments -->
                <section class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">{{ $topSubmittersLabel }}</h3>
                        @if($allSubmitters->count() > 0)
                        <button type="button" onclick="openSeeAllModal()" aria-label="See all submitting entities" class="min-h-[36px] px-2.5 py-1 text-xs font-bold text-[#8B0000] hover:text-[#6e0000] hover:bg-red-50/60 rounded-lg cursor-pointer transition-colors flex items-center gap-1.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                            See All <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </button>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @forelse($topSubmitters as $index => $item)
                        <div>
                            <div role="button" tabindex="0"
                                 aria-label="View {{ $item->name ?? 'Unspecified' }} submissions"
                                 class="flex justify-between items-center mb-1.5 cursor-pointer group hover:text-[#8B0000] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] rounded-lg px-1 transition-colors"
                                 onclick="openDetailsModal('college_specific', '{{ addslashes($item->name ?? 'Unspecified') }}', { college_name: '{{ addslashes($item->name ?? 'Unspecified') }}' })"
                                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openDetailsModal('college_specific', '{{ addslashes($item->name ?? 'Unspecified') }}', { college_name: '{{ addslashes($item->name ?? 'Unspecified') }}' });}">
                                <span class="text-sm font-semibold text-slate-700 truncate pr-3 group-hover:text-[#8B0000] transition-colors">{{ $item->name ?? 'Unspecified' }}</span>
                                <span class="text-sm font-extrabold text-slate-800 group-hover:text-[#8B0000] transition-colors tabular-nums">{{ $item->count }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                <div class="bg-[#8B0000] h-1.5 rounded-full transition-all duration-500" style="width: {{ round(($item->count / $topSubmittersMax) * 100) }}%"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-slate-500 text-center py-6">No submission data available for this timeframe.</p>
                        @endforelse
                    </div>
                </section>

                <!-- Widget 2: Submissions by Stage -->
                <section class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-5">Submissions by Stage</h3>
                    <div class="space-y-4">
                        @foreach($pipelineStages as $stage)
                        <div>
                            <div role="button" tabindex="0"
                                 aria-label="View {{ $stage['label'] }} stage protocols"
                                 class="flex justify-between items-center mb-1.5 cursor-pointer group hover:opacity-80 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] rounded-lg px-1 transition-all"
                                 onclick="openDetailsModal('pipeline', '{{ addslashes($stage['label']) }}', { pipeline_stage: '{{ addslashes($stage['label']) }}' })"
                                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openDetailsModal('pipeline', '{{ addslashes($stage['label']) }}', { pipeline_stage: '{{ addslashes($stage['label']) }}' });}">
                                <span class="text-xs font-semibold text-slate-600 group-hover:text-[#8B0000] transition-colors">{{ $stage['label'] }}</span>
                                @php
                                    $colorMap = [
                                        'slate' => 'bg-slate-100 text-slate-700 border border-slate-200/80',
                                        'blue' => 'bg-blue-50 text-blue-700 border border-blue-200/80',
                                        'amber' => 'bg-amber-50 text-amber-800 border border-amber-200/80',
                                        'emerald' => 'bg-emerald-50 text-emerald-800 border border-emerald-200/80'
                                    ];
                                    $barMap = [
                                        'slate' => 'bg-slate-400',
                                        'blue' => 'bg-blue-500',
                                        'amber' => 'bg-amber-500',
                                        'emerald' => 'bg-emerald-500'
                                    ];
                                @endphp
                                <span class="text-xs font-extrabold px-2 py-0.5 rounded-full tabular-nums {{ $colorMap[$stage['color']] }}">{{ $stage['count'] }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="{{ $barMap[$stage['color']] }} h-2 rounded-full transition-all duration-500" style="width: {{ $pipelineMax > 0 ? round(($stage['count'] / $pipelineMax) * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>

        <!-- Pending / Ongoing Proposals Table -->
        <section id="pipeline-proposals-section" class="bg-white rounded-2xl shadow-lg border border-slate-100 mt-8 overflow-hidden" style="content-visibility: auto; contain-intrinsic-size: 1px 400px;">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-5 p-4 transform translate-x-4 -translate-y-4">
                    <i class="fas fa-tasks text-6xl text-[#8b0000]"></i>
                </div>
                <div class="relative z-10">
                    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">Submissions In Progress</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">List of submissions currently being reviewed or awaiting action.</p>
                </div>
                <div class="flex items-center gap-2 relative z-10">
                    <button type="button" 
                            onclick="openDetailsModal('in_progress', 'Submissions In Progress')" 
                            class="px-3 py-1 bg-[#8B0000] hover:bg-[#6e0000] text-white text-xs font-bold rounded-lg shadow-sm tabular-nums cursor-pointer transition-all hover:shadow-md flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                            title="View All Submissions In Progress">
                        <i class="fas fa-list-ul text-[10px]" aria-hidden="true"></i>
                        <span>{{ $stuckProposals->total() }} In Progress (View All)</span>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Card Layout (md:hidden) -->
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($stuckProposals as $proposal)
                    <div onclick="window.location.href='{{ route('admin.view_files', $proposal->id) }}'" class="p-4 space-y-3 hover:bg-slate-50 cursor-pointer transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <a href="{{ route('admin.view_files', $proposal->id) }}" class="font-bold text-slate-800 text-sm line-clamp-2 hover:text-[#8B0000] transition-colors" title="{{ $proposal->Study_Protocol_title }}">
                                {{ $proposal->Study_Protocol_title }}
                            </a>
                            @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin')
                            <a href="{{ route('admin.view_files', $proposal->id) }}" onclick="event.stopPropagation()" class="shrink-0 inline-flex items-center justify-center w-9 h-9 min-w-[36px] min-h-[36px] rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-[#8B0000] hover:border-[#8B0000] hover:shadow-2xs transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-1" aria-label="View submission {{ $proposal->Study_Protocol_title }}">
                                <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                            </a>
                            @endif
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                            <div class="flex items-center gap-2 font-semibold text-slate-700">
                                <div class="w-6 h-6 rounded-full bg-slate-200/80 flex items-center justify-center text-[11px] font-bold text-slate-600 shrink-0">
                                    {{ substr($proposal->researcher->user->first_name ?? 'U', 0, 1) }}
                                </div>
                                <span class="truncate max-w-[140px]">{{ $proposal->researcher->user->first_name ?? '' }} {{ $proposal->researcher->user->last_name ?? 'Unknown' }}</span>
                            </div>
                            <span class="tabular-nums">{{ $proposal->updated_at->diffForHumans() }}</span>
                        </div>
                        <div>
                            @php
                                $statusTextColor = match($proposal->Status) {
                                    'Pending', 'Incomplete', 'Incomplete - Awaiting Hardcopy' => 'text-slate-600',
                                    'For Initial Review', 'Hardcopy Received - For Initial Review', 'Under Review', 'Reviewer Assigned', 'Hardcopy Received' => 'text-blue-700',
                                    'Waiting for Revision' => 'text-amber-800',
                                    'Revision Submitted', 'Reviewing Revisions' => 'text-purple-700',
                                    'Complete - Awaiting Hardcopy', 'Reviewed', 'Approved' => 'text-emerald-700',
                                    default => 'text-slate-600'
                                };
                                $cleanStatus = str_replace(' - ', ': ', $proposal->Status);
                            @endphp
                            <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap {{ $statusTextColor }}">
                                {{ $cleanStatus }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 mb-3 shadow-xs">
                            <i class="fas fa-check-circle text-2xl" aria-hidden="true"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-800">No submissions currently in progress</p>
                        <p class="text-xs text-slate-500 mt-1">All active submissions have been processed with no actions required.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop & Tablet Table (hidden md:block) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider font-bold border-y border-slate-200">
                            <th class="px-6 py-4">Research Title</th>
                            <th class="px-6 py-4">Researcher</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Last Updated</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stuckProposals as $proposal)
                            <tr onclick="window.location.href='{{ route('admin.view_files', $proposal->id) }}'" class="hover:bg-slate-50 cursor-pointer transition-colors group">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.view_files', $proposal->id) }}" class="font-bold text-slate-800 text-sm max-w-md truncate block group-hover:text-[#8B0000] transition-colors" title="{{ $proposal->Study_Protocol_title }}">
                                        {{ $proposal->Study_Protocol_title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200/80 flex items-center justify-center text-[11px] font-bold text-slate-600 shrink-0">
                                            {{ substr($proposal->researcher->user->first_name ?? 'U', 0, 1) }}
                                        </div>
                                        {{ $proposal->researcher->user->first_name ?? '' }} {{ $proposal->researcher->user->last_name ?? 'Unknown' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusTextColor = match($proposal->Status) {
                                            'Pending', 'Incomplete', 'Incomplete - Awaiting Hardcopy' => 'text-slate-600',
                                            'For Initial Review', 'Hardcopy Received - For Initial Review', 'Under Review', 'Reviewer Assigned', 'Hardcopy Received' => 'text-blue-700',
                                            'Waiting for Revision' => 'text-amber-800',
                                            'Revision Submitted', 'Reviewing Revisions' => 'text-purple-700',
                                            'Complete - Awaiting Hardcopy', 'Reviewed', 'Approved' => 'text-emerald-700',
                                            default => 'text-slate-600'
                                        };
                                        $cleanStatus = str_replace(' - ', ': ', $proposal->Status);
                                    @endphp
                                    <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap {{ $statusTextColor }}">
                                        {{ $cleanStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 font-medium tabular-nums">
                                    {{ $proposal->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.view_files', $proposal->id) }}" class="inline-flex items-center justify-center w-9 h-9 min-w-[36px] min-h-[36px] rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-[#8B0000] hover:border-[#8B0000] hover:shadow-2xs transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-1" title="View Submission" aria-label="View submission {{ $proposal->Study_Protocol_title }}">
                                        <i class="fas fa-external-link-alt text-xs" aria-hidden="true"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 mb-3 shadow-xs">
                                        <i class="fas fa-check-circle text-2xl" aria-hidden="true"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800">No submissions currently in progress</p>
                                    <p class="text-xs text-slate-500 mt-1">All active submissions have been processed with no pending actions required.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($stuckProposals->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500 bg-slate-50">
                    <div>
                        Showing <span class="font-bold text-slate-700">{{ $stuckProposals->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-700">{{ $stuckProposals->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-700">{{ $stuckProposals->total() }}</span>
                    </div>
                    <div class="flex gap-2">
                        @if ($stuckProposals->onFirstPage())
                            <span class="opacity-50 cursor-not-allowed text-slate-400 px-2.5 py-1.5"><i class="fas fa-chevron-left" aria-hidden="true"></i></span>
                        @else
                            <a href="{{ $stuckProposals->previousPageUrl() }}" aria-label="Previous proposals page" class="text-slate-600 hover:text-[#8B0000] hover:bg-white px-2.5 py-1.5 rounded border border-transparent hover:border-slate-200 transition-colors shadow-sm"><i class="fas fa-chevron-left" aria-hidden="true"></i></a>
                        @endif

                        @if ($stuckProposals->hasMorePages())
                            <a href="{{ $stuckProposals->nextPageUrl() }}" aria-label="Next proposals page" class="text-slate-600 hover:text-[#8B0000] hover:bg-white px-2.5 py-1.5 rounded border border-transparent hover:border-slate-200 transition-colors shadow-sm"><i class="fas fa-chevron-right" aria-hidden="true"></i></a>
                        @else
                            <span class="opacity-50 cursor-not-allowed text-slate-400 px-2.5 py-1.5"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
                        @endif
                    </div>
                </div>
            @endif
        </section>
    </div>

    <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // --- Drawer and Filter Logic ---

            document.addEventListener('DOMContentLoaded', function() {
                toggleCollegeFilter();
                toggleExactDates();
            });

            function toggleExactDates() {
                const exactStartInput = document.getElementById('filter_exact_start');
                const exactEndInput = document.getElementById('filter_exact_end');
                const exactStart = exactStartInput ? exactStartInput.value : '';
                const exactEnd = exactEndInput ? exactEndInput.value : '';

                if (exactStart && exactEndInput) {
                    exactEndInput.min = exactStart;
                    if (exactEnd && exactEnd < exactStart) {
                        exactEndInput.value = exactStart;
                    }
                } else if (exactEndInput) {
                    exactEndInput.removeAttribute('min');
                }
                
                const startMonth = document.getElementById('filter_start_month');
                const endMonth = document.getElementById('filter_end_month');
                const startYear = document.getElementById('filter_start_year');
                const endYear = document.getElementById('filter_end_year');
                
                // Disable if either exact date has a value
                const disableMonthsAndYears = (exactStart !== '' || exactEnd !== '');

                if (disableMonthsAndYears) {
                    if (startMonth) { startMonth.disabled = true; startMonth.classList.add('opacity-50', 'bg-slate-100'); }
                    if (endMonth) { endMonth.disabled = true; endMonth.classList.add('opacity-50', 'bg-slate-100'); }
                    if (startYear) { startYear.disabled = true; startYear.classList.add('opacity-50', 'bg-slate-100'); }
                    if (endYear) { endYear.disabled = true; endYear.classList.add('opacity-50', 'bg-slate-100'); }
                } else {
                    if (startMonth) { startMonth.disabled = false; startMonth.classList.remove('opacity-50', 'bg-slate-100'); }
                    if (endMonth) { endMonth.disabled = false; endMonth.classList.remove('opacity-50', 'bg-slate-100'); }
                    if (startYear) { startYear.disabled = false; startYear.classList.remove('opacity-50', 'bg-slate-100'); }
                    if (endYear) { endYear.disabled = false; endYear.classList.remove('opacity-50', 'bg-slate-100'); }
                }
            }

            function toggleCollegeFilter() {
                const affiliationSelect = document.getElementById('filter_affiliation');
                const collegeSelect = document.getElementById('filter_college');
                if (!affiliationSelect || !collegeSelect) return;

                if (affiliationSelect.value === 'External') {
                    collegeSelect.value = '';
                    collegeSelect.disabled = true;
                    collegeSelect.classList.add('opacity-50', 'bg-slate-100');
                } else {
                    collegeSelect.disabled = false;
                    collegeSelect.classList.remove('opacity-50', 'bg-slate-100');
                }
            }

            function openFilterModal() {
                const filterModal = document.getElementById('filterModal');
                const filterModalPanel = document.getElementById('filterModalPanel');
                if (!filterModal) return;
                
                filterModal.classList.remove('hidden');
                // Trigger transitions after removing hidden
                setTimeout(() => {
                    filterModal.classList.remove('opacity-0');
                    filterModalPanel.classList.remove('translate-x-full');
                    filterModalPanel.classList.add('translate-x-0');
                }, 10);
            }

            function closeFilterModal() {
                const filterModal = document.getElementById('filterModal');
                const filterModalPanel = document.getElementById('filterModalPanel');
                if (!filterModal) return;

                filterModal.classList.add('opacity-0');
                filterModalPanel.classList.remove('translate-x-0');
                filterModalPanel.classList.add('translate-x-full');
                
                setTimeout(() => {
                    filterModal.classList.add('hidden');
                }, 300); // match standard tailwind transition duration
            }

            function handleFilterSubmit(e) {
                const applyBtn = document.getElementById('applyFilterBtn');
                const resetBtn = document.getElementById('resetFilterBtn');
                if (applyBtn) {
                    applyBtn.classList.add('pointer-events-none', 'opacity-80');
                    applyBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-1.5" aria-hidden="true"></i> Applying...';
                }
                if (resetBtn) {
                    resetBtn.classList.add('pointer-events-none', 'opacity-50');
                }

                // If exact dates are not both set, ensure month/year dropdowns are enabled so they submit cleanly
                const exactStart = document.getElementById('filter_exact_start');
                const exactEnd = document.getElementById('filter_exact_end');
                if (!exactStart?.value || !exactEnd?.value) {
                    ['filter_start_month', 'filter_end_month', 'filter_start_year', 'filter_end_year'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.disabled = false;
                    });
                }
            }

            function resetFilters() {
                const resetBtn = document.getElementById('resetFilterBtn');
                const applyBtn = document.getElementById('applyFilterBtn');
                if (resetBtn) {
                    resetBtn.classList.add('pointer-events-none', 'opacity-80');
                    resetBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-1.5" aria-hidden="true"></i> Resetting...';
                }
                if (applyBtn) {
                    applyBtn.classList.add('pointer-events-none', 'opacity-50');
                }

                // Ensure all fields are enabled before reset
                ['filter_start_month', 'filter_end_month', 'filter_start_year', 'filter_end_year'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.disabled = false;
                });

                document.getElementById('filter_start_month').value = '1';
                document.getElementById('filter_end_month').value = '12';
                document.getElementById('filter_start_year').value = 'all';
                document.getElementById('filter_end_year').value = 'all';
                document.getElementById('filter_exact_start').value = '';
                document.getElementById('filter_exact_end').value = '';
                
                document.getElementById('filter_status').value = '';
                document.getElementById('filter_review_type').value = '';
                document.getElementById('filter_thesis_type').value = '';
                document.getElementById('filter_category').value = '';
                document.getElementById('filter_affiliation').value = '';
                document.getElementById('filter_college').value = '';
                
                // Clear active filter indicators by submitting
                document.getElementById('filterModalForm').submit();
            }

            function clearFilter(filterType) {
                if (filterType === 'date') {
                    document.getElementById('filter_start_month').value = '1';
                    document.getElementById('filter_end_month').value = '12';
                    document.getElementById('filter_start_year').value = 'all';
                    document.getElementById('filter_end_year').value = 'all';
                    document.getElementById('filter_exact_start').value = '';
                    document.getElementById('filter_exact_end').value = '';
                } else {
                    const input = document.getElementById('filter_' + filterType);
                    if (input) input.value = '';
                }
                
                if (filterType === 'affiliation') {
                    document.getElementById('filter_college').value = '';
                }
                
                document.getElementById('filterModalForm').submit();
            }

            function applyTimePreset(preset) {
                const startMonth = document.getElementById('filter_start_month');
                const endMonth = document.getElementById('filter_end_month');
                const startYear = document.getElementById('filter_start_year');
                const endYear = document.getElementById('filter_end_year');
                const exactStart = document.getElementById('filter_exact_start');
                const exactEnd = document.getElementById('filter_exact_end');

                if (!startMonth || !endMonth || !startYear || !endYear) return;

                if (exactStart) exactStart.value = '';
                if (exactEnd) exactEnd.value = '';

                const currentYear = '{{ date('Y') }}';
                const lastYear = '{{ date('Y') - 1 }}';
                const currentMonth = '{{ (int)date('n') }}';

                if (preset === 'all') {
                    startMonth.value = '1';
                    endMonth.value = '12';
                    startYear.value = 'all';
                    endYear.value = 'all';
                } else if (preset === 'this_year') {
                    startMonth.value = '1';
                    endMonth.value = '12';
                    startYear.value = currentYear;
                    endYear.value = currentYear;
                } else if (preset === 'last_year') {
                    startMonth.value = '1';
                    endMonth.value = '12';
                    startYear.value = lastYear;
                    endYear.value = lastYear;
                } else if (preset === 'this_month') {
                    startMonth.value = currentMonth;
                    endMonth.value = currentMonth;
                    startYear.value = currentYear;
                    endYear.value = currentYear;
                } else if (preset === 'past_6_months') {
                    if (exactStart && exactEnd) {
                        exactStart.value = '{{ date('Y-m-d', strtotime('-6 months')) }}';
                        exactEnd.value = '{{ date('Y-m-d') }}';
                    }
                }

                document.getElementById('filterModalForm').submit();
            }

            function openCustomDateFilter() {
                openFilterModal();
                setTimeout(() => {
                    const exactStart = document.getElementById('filter_exact_start');
                    if (exactStart) {
                        exactStart.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        exactStart.focus();
                    }
                }, 350);
            }

            document.addEventListener('DOMContentLoaded', function() {
                // --- 1. Daily Trend Chart ---
                const ctxDaily = document.getElementById('dailyTrendChart').getContext('2d');
                const gradient = ctxDaily.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(139, 0, 0, 0.2)');
                gradient.addColorStop(1, 'rgba(139, 0, 0, 0)');

                const dailyData = @json($dailyData);
                const dayLabels = @json($dayLabels);

                window.trendChartInstance = new Chart(ctxDaily, {
                    type: 'line',
                    data: {
                        labels: dayLabels,
                        datasets: [{
                            label: 'Submissions',
                            data: dailyData,
                            borderColor: '#8B0000',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#8B0000',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        onHover: (event, chartElement) => {
                            event.native.target.style.cursor = chartElement.length > 0 ? 'pointer' : 'default';
                        },
                        onClick: (e, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const label = dayLabels[index];
                                let extraParams = {};
                                const year = "{{ $startYear === 'all' && $endYear === 'all' ? '' : ($startYear !== 'all' ? $startYear : date('Y')) }}";
                                const month = "{{ $startMonth !== 'all' ? $startMonth : '' }}";
                                
                                if (label.startsWith('Week of ')) {
                                    const rawDateStr = label.replace('Week of ', '');
                                    const yearVal = year ? year : new Date().getFullYear();
                                    const tempDate = new Date(rawDateStr + ", " + yearVal);
                                    if (!isNaN(tempDate)) {
                                        const startIso = tempDate.getFullYear() + '-' + String(tempDate.getMonth() + 1).padStart(2, '0') + '-' + String(tempDate.getDate()).padStart(2, '0');
                                        const endTemp = new Date(tempDate.getTime() + 6 * 86400000);
                                        const endIso = endTemp.getFullYear() + '-' + String(endTemp.getMonth() + 1).padStart(2, '0') + '-' + String(endTemp.getDate()).padStart(2, '0');
                                        extraParams.exact_start = startIso;
                                        extraParams.exact_end = endIso;
                                    }
                                } else if (label.includes(' ')) {
                                   // "Apr 2026"
                                   const tempDate = new Date(label);
                                   if (!isNaN(tempDate)) {
                                       extraParams.exact_start = tempDate.getFullYear() + '-' + String(tempDate.getMonth() + 1).padStart(2, '0') + '-01';
                                       extraParams.exact_end = tempDate.getFullYear() + '-' + String(tempDate.getMonth() + 1).padStart(2, '0') + '-' + new Date(tempDate.getFullYear(), tempDate.getMonth() + 1, 0).getDate();
                                   }
                                } else if (isNaN(label)) {
                                   // "Apr"
                                   const yearVal = year ? year : new Date().getFullYear();
                                   const tempDate = new Date(label + " 1, " + yearVal);
                                   if (!isNaN(tempDate)) {
                                       extraParams.exact_start = tempDate.getFullYear() + '-' + String(tempDate.getMonth() + 1).padStart(2, '0') + '-01';
                                       extraParams.exact_end = tempDate.getFullYear() + '-' + String(tempDate.getMonth() + 1).padStart(2, '0') + '-' + new Date(tempDate.getFullYear(), tempDate.getMonth() + 1, 0).getDate();
                                   }
                                } else if (label.length === 4 && !isNaN(label)) {
                                   // "2026" (Year number)
                                   extraParams.exact_start = label + '-01-01';
                                   extraParams.exact_end = label + '-12-31';
                                } else {
                                   // "21" (Day number)
                                   const yearVal = year ? year : new Date().getFullYear();
                                   let monthVal = month ? month : null;
                                   
                                   // If month is not set in filters, try to guess from the chart context or just use current month
                                   if (!monthVal || monthVal === 'all') {
                                       monthVal = new Date().getMonth() + 1;
                                   }
                                   
                                   const formattedMonth = String(monthVal).padStart(2, '0');
                                   const formattedDay = String(label).padStart(2, '0');
                                   extraParams.exact_start = yearVal + '-' + formattedMonth + '-' + formattedDay;
                                   extraParams.exact_end = yearVal + '-' + formattedMonth + '-' + formattedDay;
                                }

                                if (extraParams.exact_start) {
                                    openDetailsModal('submissions', 'Submissions - ' + label, extraParams);
                                }
                            }
                        },
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } },
                        }
                    }
                });

                // --- 2. Approval Status Doughnut Chart ---
                const ctxPie = document.getElementById('approvalPieChart').getContext('2d');
                const approvalDataMap = @json($approvalTrends);
                
                // Exclude categories with 0 to keep the chart clean, but maintain original mapping
                const filteredLabels = [];
                const filteredData = [];
                const filteredColors = [];
                
                const colorMap = {
                    'Approved': '#10B981',      // Emerald 500
                    'Intake / New': '#A855F7',  // Purple 500
                    'Active Review': '#3B82F6', // Blue 500
                    'In Revision': '#F59E0B',   // Amber 500
                    'Exempt': '#64748B',        // Slate 500
                    'Rejected': '#EF4444'       // Red 500
                };

                for (const [key, value] of Object.entries(approvalDataMap)) {
                    if (value > 0) {
                        filteredLabels.push(key);
                        filteredData.push(value);
                        filteredColors.push(colorMap[key] || '#94A3B8');
                    }
                }

                // Fallback for empty state
                if (filteredData.length === 0) {
                    filteredLabels.push('No Submissions');
                    filteredData.push(1);
                    filteredColors.push('#E2E8F0'); // Light Slate
                }

                const pieChartInstance = new Chart(ctxPie, {
                    type: 'doughnut',
                    data: {
                        labels: filteredLabels,
                        datasets: [{
                            data: filteredData,
                            backgroundColor: filteredColors,
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: (e, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const label = filteredLabels[index];
                                if (label && label !== 'No Submissions') {
                                    openDetailsModal('distribution', 'Status: ' + label, { distribution_status: label });
                                }
                            }
                        },
                        onHover: (event, chartElement) => {
                            event.native.target.style.cursor = chartElement.length > 0 ? 'pointer' : 'default';
                        },
                        plugins: {
                            legend: {
                                position: window.innerWidth < 768 ? 'bottom' : 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: window.innerWidth < 768 ? 12 : 25,
                                    font: { family: 'Inter', size: window.innerWidth < 768 ? 12 : 14, weight: '600' },
                                    color: '#1a0505'
                                }
                            },
                        },
                        cutout: '70%',
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        }
                    }
                });
                window.pieChartInstance = pieChartInstance;

                let doughnutResizeTimer;
                window.addEventListener('resize', () => {
                    clearTimeout(doughnutResizeTimer);
                    doughnutResizeTimer = setTimeout(() => {
                        const newPos = window.innerWidth < 768 ? 'bottom' : 'right';
                        if (pieChartInstance && pieChartInstance.options.plugins.legend.position !== newPos) {
                            pieChartInstance.options.plugins.legend.position = newPos;
                            pieChartInstance.options.plugins.legend.labels.padding = window.innerWidth < 768 ? 12 : 25;
                            pieChartInstance.options.plugins.legend.labels.font.size = window.innerWidth < 768 ? 12 : 14;
                            pieChartInstance.update();
                        }
                    }, 150);
                });

            });

            // --- On-Demand PDF Export Script Loader ---
            function loadExternalScript(src) {
                return new Promise((resolve, reject) => {
                    if (document.querySelector(`script[src="${src}"]`)) return resolve();
                    const s = document.createElement('script');
                    s.src = src;
                    s.onload = resolve;
                    s.onerror = () => reject(new Error(`Failed to load script: ${src}`));
                    document.head.appendChild(s);
                });
            }

            // --- Export to PDF Function (Official Institutional Multi-Page Generator) ---
            async function exportToPdf() {
                const btn = document.getElementById('exportPdfBtn');
                const reportEl = document.getElementById('analytics-pdf-report');
                if (!reportEl) {
                    alert('PDF report template not found.');
                    return;
                }

                // Visual feedback on button
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1" aria-hidden="true"></i> Preparing Report...';
                btn.disabled = true;

                try {

                    // 2. Load heavy libraries on-demand
                    if (typeof html2canvas === 'undefined') {
                        await loadExternalScript('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js');
                    }
                    if (typeof window.jspdf === 'undefined' && typeof jsPDF === 'undefined') {
                        await loadExternalScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
                    }

                    // Wait 150ms for images to render into DOM
                    await new Promise(resolve => setTimeout(resolve, 150));

                    // 3. Render report container via html2canvas with retina scale (2x)
                    const canvas = await html2canvas(reportEl, {
                        scale: 2,
                        useCORS: true,
                        logging: false,
                        backgroundColor: '#ffffff'
                    });

                    const imgData = canvas.toDataURL('image/jpeg', 0.95);
                    const pdfConstructor = window.jspdf?.jsPDF || window.jsPDF;
                    const pdf = new pdfConstructor('p', 'mm', 'a4'); // Portrait A4: 210 x 297 mm
                    
                    const pageWidth = 210;
                    const pageHeight = 297;
                    const margin = 10;
                    const contentWidth = pageWidth - (margin * 2); // 190 mm
                    const contentHeight = pageHeight - (margin * 2); // 277 mm
                    
                    const imgWidth = contentWidth;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    
                    let heightLeft = imgHeight;
                    let position = margin;
                    
                    // First page
                    pdf.addImage(imgData, 'JPEG', margin, position, imgWidth, imgHeight);
                    heightLeft -= contentHeight;

                    // Subsequent pages if content exceeds single page
                    while (heightLeft > 0) {
                        position -= contentHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'JPEG', margin, position, imgWidth, imgHeight);
                        heightLeft -= contentHeight;
                    }

                    // Add running institutional footer & page numbers on every page
                    const totalPages = pdf.internal.getNumberOfPages();
                    for (let i = 1; i <= totalPages; i++) {
                        pdf.setPage(i);
                        pdf.setFontSize(8);
                        pdf.setTextColor(100, 116, 139); // slate-500
                        pdf.text('Western Mindanao State University • Research Ethics Oversight Committee (REOC)', margin, 290);
                        pdf.text(`Page ${i} of ${totalPages}`, pageWidth - margin, 290, { align: 'right' });
                    }

                    pdf.save('WMSU_REOC_Analytics_Report_{{ date("Y-m-d") }}.pdf');
                } catch (err) {
                    console.error('PDF Generation Error:', err);
                    alert('Failed to generate PDF. Please try again.');
                } finally {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            }
        </script>

        <!-- Filter Drawer -->
        <div id="filterModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex justify-end backdrop-blur-sm transition-opacity opacity-0 duration-300" aria-labelledby="filterModalTitle" role="dialog" aria-modal="true" onclick="if(event.target===this) closeFilterModal()">
            <div class="bg-white shadow-2xl w-full max-w-md h-full overflow-y-auto transform transition-transform translate-x-full duration-300 flex flex-col" id="filterModalPanel">
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white sticky top-0 z-10">
                    <h3 id="filterModalTitle" class="text-xl font-bold text-slate-800 flex items-center gap-2"><i class="fas fa-filter text-[#8B0000]" aria-hidden="true"></i> Global Filters</h3>
                    <button type="button" onclick="closeFilterModal()" aria-label="Close filter drawer" class="w-9 h-9 min-w-[36px] min-h-[36px] rounded-xl flex items-center justify-center text-slate-400 hover:text-[#8B0000] hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer transition-colors">
                        <i class="fas fa-times text-lg" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Complete Filter Form -->
                <form id="filterModalForm" method="GET" action="{{ route('admin.analytics') }}" onsubmit="handleFilterSubmit(event)" class="flex-1 flex flex-col">
                    <div class="p-6 space-y-8 flex-1">
                        <!-- Date Range & Basic Status -->
                        <div>
                            <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-4 text-[#8B0000]">Time & Status</h4>
                            <div class="grid grid-cols-1 gap-5">
                                <fieldset>
                                    <legend class="block text-xs font-semibold text-slate-600 mb-2">Month Range <span class="text-xs text-slate-400 font-normal">(Start to End)</span></legend>
                                    <div class="flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <label for="filter_start_month" class="sr-only">Start Month</label>
                                            <select name="start_month" id="filter_start_month" aria-label="Start Month" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                                @foreach(range(1, 12) as $m)
                                                    <option value="{{ $m }}" {{ $startMonth == $m ? 'selected' : '' }}>
                                                        {{ DateTime::createFromFormat('!m', $m)->format('M') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider px-1 shrink-0" aria-hidden="true">to</span>
                                        <div class="relative flex-1">
                                            <label for="filter_end_month" class="sr-only">End Month</label>
                                            <select name="end_month" id="filter_end_month" aria-label="End Month" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                                @foreach(range(1, 12) as $m)
                                                    <option value="{{ $m }}" {{ $endMonth == $m ? 'selected' : '' }}>
                                                        {{ DateTime::createFromFormat('!m', $m)->format('M') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <fieldset>
                                    <legend class="block text-xs font-semibold text-slate-600 mb-2">Year Range <span class="text-xs text-slate-400 font-normal">(Start to End)</span></legend>
                                    <div class="flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <label for="filter_start_year" class="sr-only">Start Year</label>
                                            <select name="start_year" id="filter_start_year" aria-label="Start Year" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                                <option value="all" {{ $startYear === 'all' ? 'selected' : '' }}>Earliest</option>
                                                @foreach($availableYears as $year)
                                                    <option value="{{ $year }}" {{ $startYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                                @if(!in_array(date('Y'), $availableYears->toArray()))
                                                    <option value="{{ date('Y') }}" {{ $startYear == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                                                @endif
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true"><i class="fas fa-chevron-down text-xs"></i></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider px-1 shrink-0" aria-hidden="true">to</span>
                                        <div class="relative flex-1">
                                            <label for="filter_end_year" class="sr-only">End Year</label>
                                            <select name="end_year" id="filter_end_year" aria-label="End Year" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                                <option value="all" {{ $endYear === 'all' ? 'selected' : '' }}>Latest</option>
                                                @foreach($availableYears as $year)
                                                    <option value="{{ $year }}" {{ $endYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                                @if(!in_array(date('Y'), $availableYears->toArray()))
                                                    <option value="{{ date('Y') }}" {{ $endYear == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                                                @endif
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true"><i class="fas fa-chevron-down text-xs"></i></div>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <!-- Exact Date Range (Overrides Dropdowns) -->
                                <fieldset>
                                    <legend class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 text-[#8B0000]">Specific Date <span class="text-xs text-slate-400 font-normal normal-case tracking-normal">(Start to End)</span></legend>
                                    <p class="text-[11px] text-slate-500 mb-2 font-normal leading-relaxed">Selecting a specific date range automatically overrides month and year dropdowns.</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="relative flex-1">
                                            <label for="filter_exact_start" class="sr-only">Specific Start Date</label>
                                            <input type="date" name="exact_start" id="filter_exact_start" aria-label="Specific Start Date" value="{{ request('exact_start') }}" onchange="document.getElementById('filter_exact_end').min = this.value; toggleExactDates()" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors">
                                        </div>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider px-1 shrink-0" aria-hidden="true">to</span>
                                        <div class="relative flex-1">
                                            <label for="filter_exact_end" class="sr-only">Specific End Date</label>
                                            <input type="date" name="exact_end" id="filter_exact_end" aria-label="Specific End Date" value="{{ request('exact_end') }}" onchange="toggleExactDates()" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors">
                                        </div>
                                    </div>
                                </fieldset>

                                <div>
                                    <label for="filter_status" class="block text-xs font-semibold text-slate-600 mb-2">Status</label>
                                    <div class="relative">
                                        <select name="status" id="filter_status" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="">All Statuses</option>
                                            @foreach($availableStatuses as $group => $statuses)
                                                <optgroup label="{{ $group }}">
                                                    @foreach($statuses as $status)
                                                        <option value="{{ $status }}" {{ $selectedStatus == $status ? 'selected' : '' }}>
                                                            {{ $status }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Options -->
                        <div id="advancedFiltersSection" class="pt-6 border-t border-slate-200">
                            <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-4 text-[#8B0000]">Advanced Attributes</h4>
                            <div class="grid grid-cols-1 gap-5">
                                <div>
                                    <label for="filter_review_type" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-clipboard-check text-slate-400" aria-hidden="true"></i> Review Type
                                    </label>
                                    <div class="relative">
                                        <select name="review_type" id="filter_review_type" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="">All Types</option>
                                            @foreach($reviewTypes as $type)
                                                <option value="{{ $type }}" {{ $selectedReviewType == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label for="filter_thesis_type" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-book text-slate-400" aria-hidden="true"></i> Thesis Type
                                    </label>
                                    <div class="relative">
                                        <select name="thesis_type" id="filter_thesis_type" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="">All Types</option>
                                            @foreach($thesisTypes as $type)
                                                <option value="{{ $type }}" {{ $selectedThesisType == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label for="filter_category" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-tags text-slate-400" aria-hidden="true"></i> Research Type
                                    </label>
                                    <div class="relative">
                                        <select name="category" id="filter_category" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="">All Types</option>
                                            @foreach($researchCategories as $cat)
                                                <option value="{{ $cat }}" {{ $selectedCategory == $cat ? 'selected' : '' }}>
                                                    {{ $cat }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label for="filter_affiliation" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-users text-slate-400" aria-hidden="true"></i> Affiliation
                                    </label>
                                    <div class="relative">
                                        <select name="affiliation" id="filter_affiliation" onchange="toggleCollegeFilter()" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="">All Affiliations</option>
                                            <option value="Internal" {{ $selectedAffiliation == 'Internal' ? 'selected' : '' }}>Internal</option>
                                            <option value="External" {{ $selectedAffiliation == 'External' ? 'selected' : '' }}>External</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label for="filter_college" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-university text-slate-400" aria-hidden="true"></i> College
                                    </label>
                                    <div class="relative">
                                        <select name="college" id="filter_college" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="">All Colleges</option>
                                            @foreach($colleges as $college)
                                                <option value="{{ $college->name }}" {{ $selectedCollege == $college->name ? 'selected' : '' }}>
                                                    {{ $college->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400" aria-hidden="true"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="p-6 bg-slate-50 border-t border-slate-200 sticky bottom-0 pb-[max(1.5rem,env(safe-area-inset-bottom))]">
                        <div class="flex gap-3 justify-end">
                            <button type="button" id="resetFilterBtn" onclick="resetFilters()" class="flex-1 min-h-[44px] py-3 bg-white text-slate-700 border border-slate-200 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-2xs cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-400">
                                Reset All Filters
                            </button>
                            <button type="submit" id="applyFilterBtn" class="flex-1 min-h-[44px] py-3 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-red-900 transition-all shadow-2xs hover:shadow-xs flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-1">
                                <i class="fas fa-check" aria-hidden="true"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    
    <!-- See All Submitters Modal -->
    <div id="seeAllModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex justify-end backdrop-blur-sm transition-opacity opacity-0 duration-300" aria-labelledby="seeAllModalTitle" role="dialog" aria-modal="true" onclick="if(event.target===this) closeSeeAllModal()">
        <div class="bg-white shadow-2xl w-full max-w-md h-full overflow-y-auto transform transition-transform translate-x-full duration-300 flex flex-col" id="seeAllModalPanel">
            <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white sticky top-0 z-10">
                <h3 id="seeAllModalTitle" class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-university text-[#8B0000]" aria-hidden="true"></i> {{ $topSubmittersLabel }}
                </h3>
                <button type="button" onclick="closeSeeAllModal()" aria-label="Close all submitters modal" class="w-9 h-9 min-w-[36px] min-h-[36px] rounded-xl flex items-center justify-center text-slate-400 hover:text-[#8B0000] hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer transition-colors">
                    <i class="fas fa-times text-lg" aria-hidden="true"></i>
                </button>
            </div>
            <div class="p-6 space-y-3 flex-1">
                @foreach($allSubmitters as $index => $item)
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-400 w-5 text-right">{{ $index + 1 }}</span>
                    <div role="button" tabindex="0"
                         aria-label="View {{ $item->name ?? 'Unspecified' }} details"
                         class="flex-1 cursor-pointer group hover:text-[#8B0000] focus:outline-none focus:ring-2 focus:ring-[#8B0000] rounded-lg p-1 transition-colors"
                         onclick="openDetailsModal('college_specific', '{{ addslashes($item->name ?? 'Unspecified') }}', { college_name: '{{ addslashes($item->name ?? 'Unspecified') }}' })"
                         onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openDetailsModal('college_specific', '{{ addslashes($item->name ?? 'Unspecified') }}', { college_name: '{{ addslashes($item->name ?? 'Unspecified') }}' });}">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-semibold text-slate-700 truncate pr-3 group-hover:text-[#8B0000] transition-colors" title="{{ $item->name ?? 'Unspecified' }}">{{ $item->name ?? 'Unspecified' }}</span>
                            <span class="text-sm font-extrabold text-slate-800 group-hover:text-[#8B0000] transition-colors">{{ $item->count }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="bg-[#8B0000] h-1.5 rounded-full transition-all duration-500" style="width: {{ round(($item->count / $allSubmittersMax) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex items-center justify-center p-3 sm:p-4 backdrop-blur-sm transition-opacity opacity-0 duration-300" aria-labelledby="detailsModalTitle" role="dialog" aria-modal="true" onclick="if(event.target===this) closeDetailsModal()">
        <div class="bg-white shadow-2xl rounded-2xl w-full max-w-6xl 2xl:max-w-7xl max-h-[90vh] overflow-hidden transform transition-transform scale-95 duration-300 flex flex-col" id="detailsModalPanel">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-200 flex justify-between items-center bg-white sticky top-0 z-10">
                <div class="flex items-center gap-2.5 min-w-0">
                    <h3 id="detailsModalTitle" class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-2 truncate"></h3>
                    <span id="detailsModalBadge" class="hidden px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 shrink-0"></span>
                </div>
                <button type="button" onclick="closeDetailsModal()" aria-label="Close details modal" class="w-9 h-9 min-w-[36px] min-h-[36px] rounded-xl flex items-center justify-center text-slate-400 hover:text-[#8B0000] hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer transition-colors">
                    <i class="fas fa-times text-lg" aria-hidden="true"></i>
                </button>
            </div>
            <div class="p-4 sm:p-6 overflow-y-auto flex-1 h-full min-h-[250px]" id="detailsModalContent">
                <div class="flex items-center justify-center py-20">
                    <i class="fas fa-spinner fa-spin text-4xl text-[#8B0000]" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lastFocusedElement = null;

        function trapFocusInModal(modalElem, e) {
            if (e.key !== 'Tab') return;
            const focusable = modalElem.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (!focusable.length) return;
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }

        function openFilterModal() {
            lastFocusedElement = document.activeElement;
            const filterModal = document.getElementById('filterModal');
            const filterModalPanel = document.getElementById('filterModalPanel');
            if (!filterModal) return;
            
            filterModal.classList.remove('hidden');
            setTimeout(() => {
                filterModal.classList.remove('opacity-0');
                filterModalPanel.classList.remove('translate-x-full');
                filterModalPanel.classList.add('translate-x-0');
                const firstBtn = filterModal.querySelector('button[aria-label="Close filter drawer"]');
                if (firstBtn) firstBtn.focus();
            }, 10);
        }

        function closeFilterModal() {
            const filterModal = document.getElementById('filterModal');
            const filterModalPanel = document.getElementById('filterModalPanel');
            if (!filterModal) return;

            filterModal.classList.add('opacity-0');
            filterModalPanel.classList.remove('translate-x-0');
            filterModalPanel.classList.add('translate-x-full');
            
            setTimeout(() => {
                filterModal.classList.add('hidden');
                if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                    lastFocusedElement.focus();
                }
            }, 300);
        }

        function openSeeAllModal() {
            lastFocusedElement = document.activeElement;
            const modal = document.getElementById('seeAllModal');
            const panel = document.getElementById('seeAllModalPanel');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                panel.classList.remove('translate-x-full');
                panel.classList.add('translate-x-0');
                const closeBtn = modal.querySelector('button[aria-label="Close all submitters modal"]');
                if (closeBtn) closeBtn.focus();
            }, 10);
        }

        function closeSeeAllModal() {
            const modal = document.getElementById('seeAllModal');
            const panel = document.getElementById('seeAllModalPanel');
            modal.classList.add('opacity-0');
            panel.classList.remove('translate-x-0');
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                modal.classList.add('hidden');
                if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                    lastFocusedElement.focus();
                }
            }, 300);
        }

        // Client-side in-memory details cache for instant 0ms modal re-renders
        const detailsCache = new Map();
        let detailsAbortController = null;

        function escapeHtml(str) {
            if (str == null) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        let currentModalData = [];
        let currentModalType = '';
        let currentModalTitle = '';

        window.toggleResearcherProtocols = function(idx) {
            const drawer = document.getElementById('protocols-drawer-' + idx);
            const chevron = document.getElementById('chevron-' + idx);
            if (!drawer) return;
            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180', 'text-[#8B0000]');
            } else {
                drawer.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180', 'text-[#8B0000]');
            }
        };

        window.toggleAllResearcherProtocols = function(expand) {
            const drawers = document.querySelectorAll('[id^="protocols-drawer-"]');
            const chevrons = document.querySelectorAll('[id^="chevron-"]');
            drawers.forEach(d => {
                if (expand) d.classList.remove('hidden');
                else d.classList.add('hidden');
            });
            chevrons.forEach(c => {
                if (expand) c.classList.add('rotate-180', 'text-[#8B0000]');
                else c.classList.remove('rotate-180', 'text-[#8B0000]');
            });
        };

        window.filterModalContent = function(query) {
            const clean = (query || '').toLowerCase().trim();
            const container = document.getElementById('modalTableWrapper');
            const countElem = document.getElementById('modalToolbarCount');
            if (!container) return;

            if (!clean) {
                if (countElem) countElem.innerText = `${currentModalData.length} Total Record${currentModalData.length === 1 ? '' : 's'}`;
                renderTableMarkup(currentModalData, currentModalType, container);
                return;
            }

            let filtered = [];
            if (currentModalType === 'researchers') {
                filtered = currentModalData.filter(item => {
                    const name = (item.name || '').toLowerCase();
                    const email = (item.email || '').toLowerCase();
                    const college = (item.college || '').toLowerCase();
                    const dept = (item.department || '').toLowerCase();
                    const affil = (item.affiliation || '').toLowerCase();
                    const hasProto = (item.protocols || []).some(p => 
                        (p.title || '').toLowerCase().includes(clean) || 
                        (p.status || '').toLowerCase().includes(clean) ||
                        (p.category || '').toLowerCase().includes(clean)
                    );
                    return name.includes(clean) || email.includes(clean) || college.includes(clean) || dept.includes(clean) || affil.includes(clean) || hasProto;
                });
            } else {
                filtered = currentModalData.filter(item => {
                    const title = (item.title || '').toLowerCase();
                    const researcher = (item.researcher || '').toLowerCase();
                    const email = (item.researcher_email || '').toLowerCase();
                    const status = (item.status || '').toLowerCase();
                    const college = (item.college || '').toLowerCase();
                    const dept = (item.department || '').toLowerCase();
                    const category = (item.category || '').toLowerCase();
                    const reviewType = (item.review_type || '').toLowerCase();
                    return title.includes(clean) || researcher.includes(clean) || email.includes(clean) || status.includes(clean) || college.includes(clean) || dept.includes(clean) || category.includes(clean) || reviewType.includes(clean);
                });
            }

            if (countElem) countElem.innerText = `${filtered.length} of ${currentModalData.length} Records`;
            renderTableMarkup(filtered, currentModalType, container);
        };

        function getStatusBadge(status) {
            const s = (status || '').toLowerCase();
            if (s.includes('disapproved') || s.includes('reject')) {
                return 'text-rose-700';
            }
            if (s.includes('approved') || s.includes('complete') || s.includes('exempt')) {
                return 'text-emerald-700';
            }
            if (s.includes('review') || s.includes('received')) {
                return 'text-blue-700';
            }
            if (s.includes('revis')) {
                return 'text-amber-800';
            }
            return 'text-slate-600';
        }

        function renderTableMarkup(data, type, container) {
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12 px-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3 shadow-2xs">
                            <i class="fas fa-search text-lg" aria-hidden="true"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700">No Matching Records</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">No records match your search filter in this category. Try adjusting your query.</p>
                    </div>
                `;
                return;
            }

            let html = '';

            // --- 1. Top Submitting Entities Banner (if college_specific) ---
            if (type === 'college_specific') {
                const entityName = escapeHtml(data[0] ? (data[0].college || 'Entity Submissions') : 'Entity');
                const entityCount = data.length;
                html += `
                    <div class="mb-4 p-3 sm:p-4 bg-gradient-to-r from-red-50/50 via-slate-50 to-white border border-red-100/70 rounded-2xl flex flex-wrap items-center justify-between gap-3 shadow-2xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-[#8B0000] text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-university text-base" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-[#8B0000] uppercase tracking-wider">Submitting Entity</div>
                                <h4 class="text-base font-extrabold text-slate-800 truncate" title="${entityName}">${entityName}</h4>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 shadow-2xs">
                                <i class="fas fa-layer-group text-[#8B0000]"></i>
                                <span>${entityCount} Total Submissions</span>
                            </span>
                        </div>
                    </div>
                `;
            }

            html += '<div class="overflow-x-auto w-full rounded-xl border border-slate-200/80 shadow-2xs bg-white"><table class="w-full text-left border-collapse">';

            // --- 2. Table Headers by Type ---
            if (type === 'researchers') {
                html += `
                    <thead>
                        <tr class="text-slate-600 text-xs font-bold uppercase tracking-wider border-b border-slate-200 bg-slate-50/90">
                            <th scope="col" class="px-5 py-3.5">Researcher</th>
                            <th scope="col" class="px-5 py-3.5">Affiliation & College</th>
                            <th scope="col" class="px-5 py-3.5 text-right sm:text-left">Submitted Protocols & Files</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                `;
                data.forEach((item, idx) => {
                    const safeName = escapeHtml(item.name || 'Unspecified');
                    const safeEmail = escapeHtml(item.email || 'Not Provided');
                    const safeAffil = escapeHtml(item.affiliation || 'Not Provided');
                    const safeCollege = escapeHtml(item.college || 'Not Provided');
                    const safeDept = escapeHtml(item.department || '');
                    const hasProtocols = item.protocols && item.protocols.length > 0;
                    const initial = escapeHtml(item.name ? item.name.charAt(0).toUpperCase() : 'U');

                    let drawerContent = '';
                    if (hasProtocols) {
                        drawerContent = item.protocols.map(p => {
                            const pTitle = escapeHtml(p.title || 'Untitled Protocol');
                            const pUrl = escapeHtml(p.view_url || '#');
                            const pStatus = escapeHtml(p.status || '');
                            const pCat = escapeHtml(p.category || 'General');
                            const pDate = escapeHtml(p.date || 'N/A');

                            return `
                                <div class="py-2.5 first:pt-1 last:pb-1 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/80 px-3 rounded-xl transition-colors border border-transparent hover:border-slate-200/70">
                                    <div class="min-w-0 flex-1">
                                        <a href="${pUrl}" class="text-xs sm:text-sm font-bold text-slate-800 hover:text-[#8B0000] transition-colors block truncate" title="${pTitle}">
                                            <i class="fas fa-file-alt text-[#8B0000] mr-1.5 text-xs"></i>${pTitle}
                                        </a>
                                        <div class="flex flex-wrap items-center gap-2 mt-0.5 text-[11px] text-slate-500">
                                            <span>Category: <strong class="text-slate-700 font-semibold">${pCat}</strong></span>
                                            <span>•</span>
                                            <span class="tabular-nums">Date: ${pDate}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                        ${pStatus ? `<span class="text-xs font-semibold uppercase tracking-wider ${getStatusBadge(p.status)}">${pStatus}</span>` : ''}
                                        <a href="${pUrl}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#8B0000] hover:bg-[#6e0000] text-white rounded-xl text-xs font-bold transition-all shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                            <i class="fas fa-folder-open text-xs"></i> View Files
                                        </a>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    }

                    html += `
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 align-middle">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-red-50 text-[#8B0000] border border-red-200/80 flex items-center justify-center text-xs font-extrabold shrink-0 shadow-2xs">
                                        ${initial}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-bold text-slate-800 truncate" title="${safeName}">${safeName}</div>
                                        <div class="text-xs text-slate-500 truncate" title="${safeEmail}">${safeEmail}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 align-middle">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider ${item.affiliation === 'Internal' ? 'bg-blue-50 text-blue-700 border border-blue-200/80' : 'bg-indigo-50 text-indigo-700 border border-indigo-200/80'}">${safeAffil}</span>
                                <div class="text-xs text-slate-700 font-semibold truncate mt-0.5" title="${safeCollege}">${safeCollege}</div>
                                ${safeDept ? `<div class="text-[11px] text-slate-500 truncate" title="${safeDept}">${safeDept}</div>` : ''}
                            </td>
                            <td class="px-5 py-4 align-middle text-right sm:text-left">
                                ${hasProtocols ? `
                                    <button type="button" 
                                            onclick="toggleResearcherProtocols(${idx})" 
                                            class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-red-50/70 hover:border-red-300 text-xs font-bold text-slate-700 hover:text-[#8B0000] transition-all cursor-pointer shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                                            title="Click to expand protocols for ${safeName}">
                                        <i class="fas fa-folder text-[#8B0000] text-xs"></i>
                                        <span>${item.protocols.length} Protocol${item.protocols.length === 1 ? '' : 's'}</span>
                                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" id="chevron-${idx}"></i>
                                    </button>
                                ` : `
                                    <span class="text-xs text-slate-400 italic px-2">0 protocols</span>
                                `}
                            </td>
                        </tr>
                        ${hasProtocols ? `
                            <tr id="protocols-drawer-${idx}" class="hidden bg-slate-50/70 border-b border-slate-200 transition-all">
                                <td colspan="3" class="px-4 sm:px-6 py-4">
                                    <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 shadow-2xs space-y-2.5">
                                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center justify-between px-1">
                                            <span>Submitted Protocols by <strong class="text-slate-800">${safeName}</strong></span>
                                            <span class="text-slate-400 tabular-nums">${item.protocols.length} item${item.protocols.length === 1 ? '' : 's'}</span>
                                        </div>
                                        <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto custom-scrollbar pr-1">
                                            ${drawerContent}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        ` : ''}
                    `;
                });
            } else if (type === 'revisions') {
                html += `
                    <thead>
                        <tr class="text-slate-600 text-xs font-bold uppercase tracking-wider border-b border-slate-200 bg-slate-50/90">
                            <th scope="col" class="px-5 py-3.5">Protocol Details</th>
                            <th scope="col" class="px-5 py-3.5">Researcher</th>
                            <th scope="col" class="px-5 py-3.5">Revision Status</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Revision History</th>
                            <th scope="col" class="px-5 py-3.5">Date</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                `;
                data.forEach(item => {
                    const safeTitle = escapeHtml(item.title || 'Untitled');
                    const safeResearcher = escapeHtml(item.researcher || 'Unknown');
                    const safeCollege = escapeHtml(item.college || 'Unassigned');
                    const safeStatus = escapeHtml(item.status || 'Unknown');
                    const safeReview = escapeHtml(item.review_type || 'Standard');
                    const safeDate = escapeHtml(item.date || 'Not Provided');
                    const safeRevisions = escapeHtml(item.revisions || '0');
                    const feedbacksCount = item.feedbacks_count || 0;
                    const viewUrl = escapeHtml(item.view_url || '#');

                    html += `
                        <tr onclick="window.location.href='${viewUrl}'" class="hover:bg-amber-50/30 cursor-pointer transition-colors group">
                            <td class="px-5 py-3.5 max-w-xs">
                                <a href="${viewUrl}" onclick="event.stopPropagation()" class="text-sm font-bold text-slate-800 group-hover:text-[#8B0000] transition-colors truncate block" title="${safeTitle}">
                                    ${safeTitle}
                                </a>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200/80">${safeReview}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 max-w-[190px]">
                                <div class="text-sm font-semibold text-slate-800 truncate" title="${safeResearcher}">${safeResearcher}</div>
                                <div class="text-xs text-slate-500 truncate" title="${safeCollege}">${safeCollege}</div>
                            </td>
                            <td class="px-5 py-3.5 text-sm">
                                <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap ${getStatusBadge(item.status)}">
                                    ${safeStatus}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200 shadow-2xs">
                                    <i class="fas fa-history text-[10px]" aria-hidden="true"></i>
                                    <span>${safeRevisions} Revisions</span>
                                </span>
                                ${feedbacksCount > 0 ? `<div class="text-[10px] text-slate-500 font-medium mt-0.5">${feedbacksCount} feedback notes</div>` : ''}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-500 tabular-nums whitespace-nowrap font-medium">${safeDate}</td>
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                <a href="${viewUrl}" onclick="event.stopPropagation()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#8B0000] hover:bg-[#6e0000] text-white rounded-xl text-xs font-bold transition-all shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                    <i class="fas fa-folder-open text-xs"></i> View Files
                                </a>
                            </td>
                        </tr>
                    `;
                });
            } else if (type === 'college_specific') {
                html += `
                    <thead>
                        <tr class="text-slate-600 text-xs font-bold uppercase tracking-wider border-b border-slate-200 bg-slate-50/90">
                            <th scope="col" class="px-5 py-3.5">Protocol Details</th>
                            <th scope="col" class="px-5 py-3.5">Lead Researcher</th>
                            <th scope="col" class="px-5 py-3.5">Review Type</th>
                            <th scope="col" class="px-5 py-3.5">Status</th>
                            <th scope="col" class="px-5 py-3.5">Submission Date</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                `;
                data.forEach(item => {
                    const safeTitle = escapeHtml(item.title || 'Untitled');
                    const safeCategory = escapeHtml(item.category || 'General');
                    const safeResearcher = escapeHtml(item.researcher || 'Unknown');
                    const safeEmail = escapeHtml(item.researcher_email || '');
                    const safeReview = escapeHtml(item.review_type || 'Standard');
                    const safeStatus = escapeHtml(item.status || 'Unknown');
                    const safeDate = escapeHtml(item.date || 'Not Provided');
                    const viewUrl = escapeHtml(item.view_url || '#');

                    html += `
                        <tr onclick="window.location.href='${viewUrl}'" class="hover:bg-red-50/40 cursor-pointer transition-colors group">
                            <td class="px-5 py-3.5 max-w-xs">
                                <a href="${viewUrl}" onclick="event.stopPropagation()" class="text-sm font-bold text-slate-800 group-hover:text-[#8B0000] transition-colors truncate block" title="${safeTitle}">
                                    ${safeTitle}
                                </a>
                                <div class="text-xs text-slate-500 mt-0.5 truncate" title="Category: ${safeCategory}">
                                    Category: <span class="font-semibold text-slate-700">${safeCategory}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 max-w-[190px]">
                                <div class="text-sm font-semibold text-slate-800 truncate" title="${safeResearcher}">${safeResearcher}</div>
                                ${safeEmail ? `<div class="text-xs text-slate-500 truncate" title="${safeEmail}">${safeEmail}</div>` : ''}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200/80">
                                    ${safeReview}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap ${getStatusBadge(item.status)}">
                                    ${safeStatus}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-500 tabular-nums whitespace-nowrap font-medium">${safeDate}</td>
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                <a href="${viewUrl}" onclick="event.stopPropagation()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 hover:border-[#8B0000] text-slate-700 hover:text-[#8B0000] hover:bg-red-50/50 rounded-xl text-xs font-bold transition-all shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                    <i class="fas fa-folder-open text-[#8B0000]"></i> View Files
                                </a>
                            </td>
                        </tr>
                    `;
                });
            } else {
                // --- 3. Default Submissions / Stages / Pipeline / Distribution Layout ---
                html += `
                    <thead>
                        <tr class="text-slate-600 text-xs font-bold uppercase tracking-wider border-b border-slate-200 bg-slate-50/90">
                            <th scope="col" class="px-5 py-3.5">Protocol Details</th>
                            <th scope="col" class="px-5 py-3.5">Researcher & Affiliation</th>
                            <th scope="col" class="px-5 py-3.5">Status</th>
                            <th scope="col" class="px-5 py-3.5">Date</th>
                            <th scope="col" class="px-5 py-3.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                `;
                data.forEach(item => {
                    const safeTitle = escapeHtml(item.title || 'Untitled');
                    const safeResearcher = escapeHtml(item.researcher || 'Unknown');
                    const safeCollege = escapeHtml(item.college || 'Unassigned');
                    const safeReview = escapeHtml(item.review_type || 'Standard');
                    const safeStatus = escapeHtml(item.status || 'Unknown');
                    const safeDate = escapeHtml(item.date || 'Not Provided');
                    const viewUrl = escapeHtml(item.view_url || '#');

                    html += `
                        <tr onclick="window.location.href='${viewUrl}'" class="hover:bg-red-50/40 cursor-pointer transition-colors group">
                            <td class="px-5 py-3.5 max-w-xs">
                                <a href="${viewUrl}" onclick="event.stopPropagation()" class="text-sm font-bold text-slate-800 group-hover:text-[#8B0000] transition-colors truncate block" title="${safeTitle}">
                                    ${safeTitle}
                                </a>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200/80">${safeReview}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 max-w-[190px]">
                                <div class="text-sm font-semibold text-slate-800 truncate" title="${safeResearcher}">${safeResearcher}</div>
                                <div class="text-xs text-slate-500 truncate" title="${safeCollege}">${safeCollege}</div>
                            </td>
                            <td class="px-5 py-3.5 text-sm">
                                <span class="inline-flex items-center text-xs font-semibold whitespace-nowrap ${getStatusBadge(item.status)}">
                                    ${safeStatus}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-500 tabular-nums whitespace-nowrap font-medium">${safeDate}</td>
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                <a href="${viewUrl}" onclick="event.stopPropagation()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 hover:border-[#8B0000] text-slate-700 hover:text-[#8B0000] hover:bg-red-50/50 rounded-xl text-xs font-bold transition-all shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                    <i class="fas fa-folder-open text-[#8B0000]"></i> View Files
                                </a>
                            </td>
                        </tr>
                    `;
                });
            }

            html += '</tbody></table></div>';
            container.innerHTML = html;
        }

        function renderDetailsTable(data, type, contentElem) {
            currentModalData = Array.isArray(data) ? data : [];
            currentModalType = type;

            const badgeElem = document.getElementById('detailsModalBadge');
            if (badgeElem) {
                badgeElem.innerText = `${currentModalData.length} records`;
                badgeElem.classList.remove('hidden');
            }

            if (currentModalData.length === 0) {
                contentElem.innerHTML = `
                    <div class="text-center py-16 px-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3 shadow-xs">
                            <i class="fas fa-folder-open text-xl" aria-hidden="true"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700">No Records Found</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">There are no records matching this category in the selected timeframe. Try broadening your date or status filters.</p>
                    </div>
                `;
                return;
            }

            let toolbarHtml = `
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 shadow-2xs">
                            <i class="fas fa-list-check text-slate-500"></i>
                            <span id="modalToolbarCount">${currentModalData.length} Total Record${currentModalData.length === 1 ? '' : 's'}</span>
                        </span>
                        ${type === 'researchers' ? `
                            <div class="flex items-center gap-1 ml-1">
                                <button type="button" onclick="toggleAllResearcherProtocols(true)" class="px-2.5 py-1 text-xs font-bold text-slate-600 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-colors focus:outline-none focus:ring-1 focus:ring-[#8B0000]">
                                    <i class="fas fa-chevron-down text-[10px] mr-1"></i>Expand All
                                </button>
                                <span class="text-slate-300">|</span>
                                <button type="button" onclick="toggleAllResearcherProtocols(false)" class="px-2.5 py-1 text-xs font-bold text-slate-600 hover:text-[#8B0000] hover:bg-slate-100 rounded-lg transition-colors focus:outline-none focus:ring-1 focus:ring-[#8B0000]">
                                    <i class="fas fa-chevron-up text-[10px] mr-1"></i>Collapse All
                                </button>
                            </div>
                        ` : ''}
                    </div>
                    <div class="relative w-full sm:w-72">
                        <i class="fas fa-search absolute left-3 top-2.5 text-xs text-slate-400 pointer-events-none" aria-hidden="true"></i>
                        <input type="text" 
                               id="modalSearchFilter" 
                               placeholder="Search in records..." 
                               oninput="filterModalContent(this.value)" 
                               class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:bg-white transition-all text-slate-800 placeholder-slate-400">
                    </div>
                </div>
                <div id="modalTableWrapper"></div>
            `;

            contentElem.innerHTML = toolbarHtml;
            renderTableMarkup(currentModalData, type, document.getElementById('modalTableWrapper'));
        }

        function openDetailsModal(type, title, extraParams = {}) {
            lastFocusedElement = document.activeElement;
            const modal = document.getElementById('detailsModal');
            const panel = document.getElementById('detailsModalPanel');
            const titleElem = document.getElementById('detailsModalTitle');
            const contentElem = document.getElementById('detailsModalContent');

            titleElem.innerText = title;

            // Generate deterministic cache key
            const params = new URLSearchParams(window.location.search);
            params.set('type', type);
            Object.entries(extraParams).forEach(([key, value]) => params.set(key, value));
            const cacheKey = params.toString();

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                panel.classList.remove('scale-95');
                panel.classList.add('scale-100');
                const closeBtn = modal.querySelector('button[aria-label="Close details modal"]');
                if (closeBtn) closeBtn.focus();
            }, 10);

            // Instant 0ms cache retrieval if already fetched
            if (detailsCache.has(cacheKey)) {
                renderDetailsTable(detailsCache.get(cacheKey), type, contentElem);
                return;
            }

            // Abort previous in-flight request to avoid race condition
            if (detailsAbortController) {
                detailsAbortController.abort();
            }
            detailsAbortController = new AbortController();
            const signal = detailsAbortController.signal;

            contentElem.innerHTML = '<div class="flex items-center justify-center py-20"><i class="fas fa-spinner fa-spin text-4xl text-[#8B0000]" aria-hidden="true"></i></div>';

            fetch(`{{ route('admin.analytics.details') }}?${cacheKey}`, { signal })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    detailsCache.set(cacheKey, data);
                    renderDetailsTable(data, type, contentElem);
                })
                .catch(error => {
                    if (error.name === 'AbortError') return;
                    console.error('Error fetching details:', error);
                    const safeTitle = title.replace(/'/g, "\\'");
                    const safeParams = JSON.stringify(extraParams).replace(/"/g, '&quot;');
                    contentElem.innerHTML = `
                        <div class="text-center py-16 px-4">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-50 text-red-600 mb-3 shadow-xs">
                                <i class="fas fa-exclamation-triangle text-xl" aria-hidden="true"></i>
                            </div>
                            <h4 class="text-base font-bold text-slate-800 mb-1">Unable to Load Category Details</h4>
                            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">A network error or timeout occurred while retrieving records. Please check your connection and try again.</p>
                            <button type="button" onclick="openDetailsModal('${type}', '${safeTitle}', ${safeParams})" class="inline-flex items-center gap-2 px-4 py-2 bg-[#8B0000] hover:bg-red-900 text-white rounded-xl text-xs font-bold tracking-wider transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                <i class="fas fa-redo-alt text-xs" aria-hidden="true"></i> Retry Request
                            </button>
                        </div>
                    `;
                });
        }

        function closeDetailsModal() {
            if (detailsAbortController) {
                detailsAbortController.abort();
            }
            const modal = document.getElementById('detailsModal');
            const panel = document.getElementById('detailsModalPanel');
            const badge = document.getElementById('detailsModalBadge');
            if (badge) badge.classList.add('hidden');
            modal.classList.add('opacity-0');
            panel.classList.remove('scale-100');
            panel.classList.add('scale-95');
            setTimeout(() => { 
                modal.classList.add('hidden'); 
                if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                    lastFocusedElement.focus();
                }
            }, 300);
        }

        // Move modals to body to avoid stacking context issues with header
        document.addEventListener('DOMContentLoaded', function() {
            const filterModal = document.getElementById('filterModal');
            const seeAllModal = document.getElementById('seeAllModal');
            const detailsModal = document.getElementById('detailsModal');
            if (filterModal) document.body.appendChild(filterModal);
            if (seeAllModal) document.body.appendChild(seeAllModal);
            if (detailsModal) document.body.appendChild(detailsModal);
        });

        // Global Modal Keyboard Management (Escape to dismiss, Tab to trap)
        document.addEventListener('keydown', function(e) {
            const filterModal = document.getElementById('filterModal');
            const seeAllModal = document.getElementById('seeAllModal');
            const detailsModal = document.getElementById('detailsModal');

            const activeModal = [filterModal, seeAllModal, detailsModal].find(
                m => m && !m.classList.contains('hidden') && !m.classList.contains('opacity-0')
            );

            if (!activeModal) return;

            if (e.key === 'Escape') {
                if (activeModal === filterModal) closeFilterModal();
                if (activeModal === seeAllModal) closeSeeAllModal();
                if (activeModal === detailsModal) closeDetailsModal();
            } else if (e.key === 'Tab') {
                trapFocusInModal(activeModal, e);
            }
        });
    </script>

    <!-- Off-Screen Institutional Printable PDF Report Template (Captured by html2canvas for PDF Export) -->
    <div id="analytics-pdf-report" style="position: fixed; left: -9999px; top: 0; width: 1000px; min-height: 1390px; display: flex; flex-direction: column; background: #ffffff; color: #0f172a; padding: 36px 40px; box-sizing: border-box; font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <!-- Institutional Letterhead -->
        <div style="text-align: center; margin-bottom: 20px; border-bottom: 3px solid #8B0000; padding-bottom: 16px;">
            <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; color: #64748b; text-transform: uppercase;">Republic of the Philippines</div>
            <div style="font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -0.01em; margin-top: 3px;">WESTERN MINDANAO STATE UNIVERSITY</div>
            <div style="font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 800; color: #8B0000; letter-spacing: 0.06em; text-transform: uppercase; margin-top: 3px;">RESEARCH ETHICS OVERSIGHT COMMITTEE (REOC)</div>
            <div style="font-size: 10.5px; color: #64748b; margin-top: 3px;">Normal Road, Baliwasan, Zamboanga City 7000 • reo@wmsu.edu.ph • www.wmsu.edu.ph</div>
        </div>

        <!-- Document Title & Metadata -->
        <div style="margin-bottom: 20px;">
            <div style="margin-bottom: 12px;">
                <h1 style="font-family: 'Montserrat', sans-serif; font-size: 19px; font-weight: 800; color: #0f172a; margin: 0; text-transform: uppercase; letter-spacing: -0.01em;">Executive Analytics & Protocol Monitoring Report</h1>
                <p style="font-size: 11.5px; color: #64748b; margin: 4px 0 0 0; font-weight: 500;">Comprehensive Research Protocol Submission Metrics & Institutional Review Analytics</p>
            </div>

            <!-- Metadata Box -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px;">
                <div>
                    <div style="font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; margin-bottom: 2px;">Reporting Scope</div>
                    <div style="font-size: 11px; font-weight: 700; color: #0f172a; line-height: 1.4;">{{ $overviewTitle }} ({{ $dateRangeSubtitle ?? 'All Time' }})</div>
                </div>
                <div>
                    <div style="font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; margin-bottom: 2px;">Generated On</div>
                    <div style="font-size: 11px; font-weight: 700; color: #0f172a; line-height: 1.4;">{{ date('M d, Y • h:i A') }}</div>
                </div>
                <div>
                    <div style="font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; margin-bottom: 2px;">Administrator</div>
                    <div style="font-size: 11px; font-weight: 700; color: #0f172a; line-height: 1.4;">{{ Auth::user()->first_name ?? 'System' }} {{ Auth::user()->last_name ?? 'Admin' }}</div>
                </div>
                <div>
                    <div style="font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em; margin-bottom: 2px;">Active Filters</div>
                    <div style="font-size: 11px; font-weight: 700; color: #0f172a; line-height: 1.4; word-break: break-word;">
                        {{ $selectedStatus ?: 'All Statuses' }} • {{ $selectedReviewType ?: 'All Types' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: Executive KPI Summary -->
        <div style="margin-bottom: 20px;">
            <div style="font-family: 'Montserrat', sans-serif; font-size: 12.5px; font-weight: 800; text-transform: uppercase; color: #8B0000; letter-spacing: 0.05em; margin-bottom: 10px;">1. Key Performance Indicators</div>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; background: #ffffff;">
                    <div style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; color: #64748b;">Total Submissions</div>
                    <div style="font-size: 24px; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ number_format($totalSubmissions) }}</div>
                    <div style="font-size: 10.5px; color: #16a34a; font-weight: 600; margin-top: 2px;">Recorded Intake Volume</div>
                </div>
                <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; background: #ffffff;">
                    <div style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; color: #64748b;">Approved Protocols</div>
                    <div style="font-size: 24px; font-weight: 800; color: #15803d; margin-top: 4px;">{{ number_format($approvedCount) }}</div>
                    <div style="font-size: 10.5px; color: #64748b; font-weight: 600; margin-top: 2px;">{{ $approvalRate }}% Clearance Rate</div>
                </div>
                <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; background: #ffffff;">
                    <div style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; color: #64748b;">Revisions Required</div>
                    <div style="font-size: 24px; font-weight: 800; color: #b45309; margin-top: 4px;">{{ number_format($revisionsCount) }}</div>
                    <div style="font-size: 10.5px; color: #64748b; font-weight: 600; margin-top: 2px;">{{ $revisionsRate }}% Revision Rate</div>
                </div>
                <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; background: #ffffff;">
                    <div style="font-size: 10.5px; font-weight: 700; text-transform: uppercase; color: #64748b;">Active Researchers</div>
                    <div style="font-size: 24px; font-weight: 800; color: #1d4ed8; margin-top: 4px;">{{ number_format($activeResearchers) }}</div>
                    <div style="font-size: 10.5px; color: #64748b; font-weight: 600; margin-top: 2px;">Registered Investigators</div>
                </div>
            </div>
        </div>

        <!-- Main Data Grid: Two Columns (Left: Monthly Submissions + Workflow Pipeline; Right: Status Distribution + Submitting Colleges) -->
        <div style="display: flex; gap: 18px; align-items: flex-start; margin-bottom: 18px;">
            {{-- Left Column (approx 40% width): Section 2 Monthly Submissions & Section 3 Pipeline Stages --}}
            <div style="flex: 0.88; min-width: 0; display: flex; flex-direction: column; gap: 16px;">
                {{-- Section 2: Monthly Submissions --}}
                <div>
                    <div style="font-family: 'Montserrat', sans-serif; font-size: 12.5px; font-weight: 800; text-transform: uppercase; color: #8B0000; letter-spacing: 0.05em; margin-bottom: 6px;">2. Monthly Submissions</div>
                    <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; background: #ffffff;">
                        <div style="font-size: 11px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Protocols Submitted Per Month</div>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="padding: 5px 6px; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; font-size: 10px; text-align: left; border-bottom: 2px solid #8B0000;">Month</th>
                                    <th style="padding: 5px 6px; text-align: right; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; font-size: 10px; border-bottom: 2px solid #8B0000;">Protocols</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dayLabels as $idx => $lbl)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 4px 6px; font-weight: 600; color: #334155; font-size: 10.5px;">{{ $lbl }}</td>
                                    <td style="padding: 4px 6px; text-align: right; font-weight: 700; color: #0f172a; font-size: 10.5px;">
                                        {{ $dailyData[$idx] ?? 0 }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background: #f8fafc;">
                                    <td style="padding: 5px 6px; font-weight: 800; color: #0f172a; text-transform: uppercase; font-size: 9.5px; letter-spacing: 0.05em; border-top: 2px solid #e2e8f0;">Total</td>
                                    <td style="padding: 5px 6px; text-align: right; font-weight: 800; color: #8B0000; font-size: 11px; border-top: 2px solid #e2e8f0;">{{ array_sum($dailyData) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Section 3: Workflow Pipeline Stages --}}
                <div>
                    <div style="font-family: 'Montserrat', sans-serif; font-size: 12.5px; font-weight: 800; text-transform: uppercase; color: #8B0000; letter-spacing: 0.05em; margin-bottom: 6px;">3. Workflow Pipeline Stages</div>
                    <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; background: #ffffff;">
                        <div style="font-size: 11px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Review Stages Breakdown</div>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="padding: 5px 6px; text-align: left; color: #64748b; font-size: 10px; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Stage</th>
                                    <th style="padding: 5px 6px; text-align: right; color: #64748b; font-size: 10px; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Count</th>
                                    <th style="padding: 5px 6px; text-align: right; color: #64748b; font-size: 10px; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pipelineStages as $stage)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 5px 6px; font-weight: 600; color: #334155; font-size: 10.5px;">{{ $stage['label'] }}</td>
                                    <td style="padding: 5px 6px; text-align: right; font-weight: 700; color: #0f172a; font-size: 10.5px;">{{ $stage['count'] }}</td>
                                    <td style="padding: 5px 6px; text-align: right; color: #64748b; font-size: 10.5px;">{{ $totalSubmissions > 0 ? round(($stage['count'] / $totalSubmissions) * 100) : 0 }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right Column (approx 60% width): Approval Status Distribution & Expanded Submitting Colleges --}}
            <div style="flex: 1.32; min-width: 0; display: flex; flex-direction: column; gap: 16px;">
                {{-- Approval Status Distribution --}}
                <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; background: #ffffff;">
                    @php
                        $statusColorMap = [
                            'Approved' => ['bg' => '#10b981', 'light' => '#ecfdf5'],
                            'Intake / New' => ['bg' => '#a855f7', 'light' => '#faf5ff'],
                            'Active Review' => ['bg' => '#3b82f6', 'light' => '#eff6ff'],
                            'In Revision' => ['bg' => '#f59e0b', 'light' => '#fffbeb'],
                            'Exempt' => ['bg' => '#64748b', 'light' => '#f8fafc'],
                            'Rejected' => ['bg' => '#ef4444', 'light' => '#fef2f2']
                        ];
                    @endphp
                    <div style="font-size: 11px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Approval Status Distribution</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 7px;">
                        @foreach($approvalTrends as $statusLabel => $statusCount)
                        @php
                            $colors = $statusColorMap[$statusLabel] ?? ['bg' => '#8B0000', 'light' => '#fff5f5'];
                            $pct = $totalSubmissions > 0 ? round(($statusCount / $totalSubmissions) * 100) : 0;
                        @endphp
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border-radius: 6px; background: {{ $colors['light'] }}; border-left: 3.5px solid {{ $colors['bg'] }};">
                            <span style="font-size: 10px; font-weight: 600; color: #334155;">{{ $statusLabel }}</span>
                            <span style="font-size: 10.5px; font-weight: 800; color: {{ $colors['bg'] }}; margin-left: 6px;">{{ $statusCount }} <span style="font-size: 9px; font-weight: 600; color: #64748b;">({{ $pct }}%)</span></span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Submitting Colleges & Entities --}}
                <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; background: #ffffff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <div style="font-family: 'Montserrat', sans-serif; font-size: 12.5px; font-weight: 800; text-transform: uppercase; color: #8B0000; letter-spacing: 0.05em;">Submitting Colleges & Entities</div>
                        <div style="font-size: 10px; font-weight: 600; color: #64748b;">All Entities ({{ count($allSubmitters) }})</div>
                    </div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="padding: 5px 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; font-size: 10px; text-align: left; color: #475569; border-bottom: 2px solid #8B0000;">Entity / College</th>
                                <th style="padding: 5px 6px; text-align: right; width: 60px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; font-size: 10px; color: #475569; border-bottom: 2px solid #8B0000;">Protocols</th>
                                <th style="padding: 5px 6px; text-align: right; width: 45px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; font-size: 10px; color: #475569; border-bottom: 2px solid #8B0000;">Share</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allSubmitters as $item)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 3.5px 6px; font-weight: 600; color: #334155; line-height: 1.35; font-size: 10px;">{{ $item->name ?? 'Unspecified' }}</td>
                                <td style="padding: 3.5px 6px; text-align: right; font-weight: 700; color: #0f172a; font-size: 10px;">{{ $item->count }}</td>
                                <td style="padding: 3.5px 6px; text-align: right; color: #64748b; font-size: 10px;">{{ $totalSubmissions > 0 ? round(($item->count / $totalSubmissions) * 100) : 0 }}%</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" style="padding: 6px; text-align: center; color: #94a3b8; font-size: 10px;">No data available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Official Authentication & Sign-Off -->
        <div style="margin-top: auto; padding-top: 20px; border-top: 1.5px solid #e2e8f0; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <div style="font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.06em; margin-bottom: 38px;">Prepared By:</div>
                <div style="border-bottom: 1.5px solid #334155; width: 250px;"></div>
                <div style="font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 800; color: #0f172a; margin-top: 6px; text-transform: uppercase;">{{ Auth::user()->first_name ?? 'System' }} {{ Auth::user()->last_name ?? 'Admin' }}</div>
                <div style="font-size: 9.5px; color: #64748b; font-weight: 600; margin-top: 2px;">{{ strtoupper(Auth::user()->role ?? 'ADMIN') }}, Research Ethics Office</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.06em; margin-bottom: 38px;">Noted & Certified Correct:</div>
                <div style="border-bottom: 1.5px solid #334155; width: 250px; margin-left: auto;"></div>
                <div style="font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 800; color: #0f172a; margin-top: 6px; text-transform: uppercase;">REOC CHAIRPERSON / SECRETARIAT</div>
                <div style="font-size: 9.5px; color: #64748b; font-weight: 600; margin-top: 2px;">Western Mindanao State University</div>
            </div>
        </div>
    </div>
</x-admin_layout>