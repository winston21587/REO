<x-super_admin_layout>
    <div id="analytics-dashboard" class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Analytics & Reports</h1>
                <p class="text-slate-500 mt-2 text-sm">Real-time insights into research submission performance.</p>
            </div>
            <div class="flex gap-3 mt-4 md:mt-0 items-center flex-wrap justify-end">
                <button onclick="openFilterModal()" class="px-4 py-2 bg-gradient-to-r from-[#8B0000] to-red-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:shadow-lg transition-all flex items-center gap-2 shadow-sm">
                    <i class="fas fa-filter"></i> Filter Data
                </button>

                <div class="h-8 w-px bg-slate-200"></div>



                <a href="{{ route('super_admin.analytics.export', request()->query()) }}" id="exportCsvBtn" class="px-4 py-2 bg-white border border-slate-200 text-[#8B0000] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        <div class="flex flex-wrap gap-2 -mt-4 mb-2">
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm border border-slate-200">
                <i class="fas fa-calendar-alt text-slate-400"></i>
                @if($startMonth === 'all' && $endMonth === 'all' && $startYear === 'all' && $endYear === 'all')
                    All Time
                @else
                    {{ $startMonth === 'all' ? 'Jan' : DateTime::createFromFormat('!m', $startMonth)->format('M') }}
                    {{ $startYear === 'all' ? 'All' : $startYear }}
                    - 
                    {{ $endMonth === 'all' ? 'Dec' : DateTime::createFromFormat('!m', $endMonth)->format('M') }}
                    {{ $endYear === 'all' ? 'All' : $endYear }}
                @endif
                @if($startMonth !== 'all' || $endMonth !== 'all' || $startYear !== 'all' || $endYear !== 'all')
                    <button type="button" onclick="clearFilter('date')" class="text-slate-400 hover:text-red-500 transition-colors ml-1 focus:outline-none"><i class="fas fa-times"></i></button>
                @endif
            </div>
            @if($selectedStatus) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm border border-slate-200">
                <i class="fas fa-info-circle text-slate-400"></i> {{ $selectedStatus }}
                <button type="button" onclick="clearFilter('status')" class="text-slate-400 hover:text-red-500 transition-colors ml-1 focus:outline-none"><i class="fas fa-times"></i></button>
            </div> 
            @endif
            @if($selectedReviewType) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm border border-slate-200">
                <i class="fas fa-clipboard-check text-slate-400"></i> {{ $selectedReviewType }}
                <button type="button" onclick="clearFilter('review_type')" class="text-slate-400 hover:text-red-500 transition-colors ml-1 focus:outline-none"><i class="fas fa-times"></i></button>
            </div> 
            @endif
            @if($selectedThesisType) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm border border-slate-200">
                <i class="fas fa-book text-slate-400"></i> {{ $selectedThesisType }}
                <button type="button" onclick="clearFilter('thesis_type')" class="text-slate-400 hover:text-red-500 transition-colors ml-1 focus:outline-none"><i class="fas fa-times"></i></button>
            </div> 
            @endif
            @if($selectedCategory) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm border border-slate-200">
                <i class="fas fa-tags text-slate-400"></i> {{ $selectedCategory }}
                <button type="button" onclick="clearFilter('category')" class="text-slate-400 hover:text-red-500 transition-colors ml-1 focus:outline-none"><i class="fas fa-times"></i></button>
            </div> 
            @endif
            @if($selectedAffiliation) 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm border border-slate-200">
                <i class="fas fa-users text-slate-400"></i> {{ $selectedAffiliation }}
                <button type="button" onclick="clearFilter('affiliation')" class="text-slate-400 hover:text-red-500 transition-colors ml-1 focus:outline-none"><i class="fas fa-times"></i></button>
            </div> 
            @endif
            @if($selectedCollege && $selectedAffiliation !== 'External') 
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm border border-slate-200">
                <i class="fas fa-university text-slate-400"></i> {{ $selectedCollege }}
                <button type="button" onclick="clearFilter('college')" class="text-slate-400 hover:text-red-500 transition-colors ml-1 focus:outline-none"><i class="fas fa-times"></i></button>
            </div> 
            @endif
        </div>



        <!-- Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Metric Card 1 -->
            <div onclick="openDetailsModal('submissions', 'Total Submissions')" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group cursor-pointer hover:shadow-md hover:border-slate-300 transition-all">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-file-alt text-6xl text-[#8B0000]"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Submissions</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalSubmissions) }}</h3>
                    @if($submissionsGrowthRate > 0)
                        <span class="text-xs font-bold text-green-600 bg-green-50 px-1.5 py-0.5 rounded mb-1"><i class="fas fa-arrow-up"></i> {{ $submissionsGrowthRate }}%</span>
                    @elseif($submissionsGrowthRate < 0)
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded mb-1"><i class="fas fa-arrow-down"></i> {{ abs($submissionsGrowthRate) }}%</span>
                    @else
                        <span class="text-xs font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded mb-1"><i class="fas fa-minus"></i> 0%</span>
                    @endif
                </div>
            </div>
            
            <!-- Metric Card 2 -->
            <div onclick="openDetailsModal('approved', 'Approved Submissions')" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group cursor-pointer hover:shadow-md hover:border-slate-300 transition-all">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-check-circle text-6xl text-green-600"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Approved</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($approvedCount) }}</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">{{ $approvalRate }}% Rate</span>
                </div>
            </div>

            <!-- Metric Card 3 -->
            <div onclick="openDetailsModal('revisions', 'Submissions Requiring Revisions')" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group cursor-pointer hover:shadow-md hover:border-slate-300 transition-all">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-exclamation-triangle text-6xl text-orange-500"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Revisions</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($revisionsCount) }}</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">{{ $revisionsRate }}% Bounce Rate</span>
                </div>
            </div>

            <!-- Metric Card 4 -->
            <div onclick="openDetailsModal('researchers', 'Active Researchers')" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group cursor-pointer hover:shadow-md hover:border-slate-300 transition-all">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-users text-6xl text-blue-600"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Researchers</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($activeResearchers) }}</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Daily Trend (Takes up 2/3) -->
            <section class="lg:col-span-2 bg-white rounded-2xl shadow-xl border border-slate-100 p-8 relative overflow-hidden flex flex-col">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fas fa-chart-line text-9xl text-[#8B0000]"></i>
                </div>
                
                <div class="relative z-10 flex flex-col flex-1 w-full h-full">
                    <h2 class="text-sm font-bold text-[#8B0000] uppercase tracking-widest mb-1">Submission Trends</h2>
                    <div class="flex items-end gap-4 mb-8">
                        <p class="text-3xl font-extrabold text-slate-900">{{ $overviewTitle }}</p>
                        <p class="text-sm text-slate-500 font-medium mb-1.5">
                            @if($startMonth === 'all' && $endMonth === 'all' && $startYear === 'all' && $endYear === 'all')
                                All Years
                            @else
                                {{ $startMonth === 'all' ? 'January' : DateTime::createFromFormat('!m', $startMonth)->format('F') }} {{ $startYear === 'all' ? 'All' : $startYear }}
                                to 
                                {{ $endMonth === 'all' ? 'December' : DateTime::createFromFormat('!m', $endMonth)->format('F') }} {{ $endYear === 'all' ? 'All' : $endYear }}
                            @endif
                        </p>
                    </div>

                    <div class="flex-1 w-full relative min-h-[320px]">
                        <canvas id="dailyTrendChart" class="absolute inset-0 w-full h-full"></canvas>
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
                        <button type="button" onclick="openSeeAllModal()" class="text-xs font-semibold text-[#8B0000] hover:text-red-700 transition-colors flex items-center gap-1">
                            See All <i class="fas fa-arrow-right text-[10px]"></i>
                        </button>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @forelse($topSubmitters as $index => $item)
                        <div>
                            <div class="flex justify-between items-center mb-1.5 cursor-pointer group hover:text-[#8B0000]" onclick="openDetailsModal('college_specific', '{{ addslashes($item->name ?? 'Unspecified') }}', { college_name: '{{ addslashes($item->name ?? 'Unspecified') }}' })">
                                <span class="text-sm font-semibold text-slate-700 truncate pr-3 group-hover:text-[#8B0000] transition-colors">{{ $item->name ?? 'Unspecified' }}</span>
                                <span class="text-sm font-extrabold text-slate-800 group-hover:text-[#8B0000] transition-colors">{{ $item->count }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                <div class="bg-[#8B0000] h-1.5 rounded-full transition-all duration-500" style="width: {{ round(($item->count / $topSubmittersMax) * 100) }}%"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-slate-400 text-center py-6">No submission data available</p>
                        @endforelse
                    </div>
                </section>

                <!-- Widget 2: Active Pipeline -->
                <section class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-5">Active Pipeline</h3>
                    <div class="space-y-4">
                        @foreach($pipelineStages as $stage)
                        <div>
                            <div class="flex justify-between items-center mb-1.5 cursor-pointer group hover:opacity-80 transition-opacity" onclick="openDetailsModal('pipeline', '{{ addslashes($stage['label']) }}', { pipeline_stage: '{{ addslashes($stage['label']) }}' })">
                                <span class="text-xs font-semibold text-slate-600 group-hover:text-[#8B0000] transition-colors">{{ $stage['label'] }}</span>
                                @php
                                    $colorMap = ['slate' => 'bg-slate-200 text-slate-700', 'blue' => 'bg-blue-100 text-blue-700', 'amber' => 'bg-amber-100 text-amber-700', 'emerald' => 'bg-emerald-100 text-emerald-700'];
                                    $barMap = ['slate' => 'bg-slate-400', 'blue' => 'bg-blue-500', 'amber' => 'bg-amber-500', 'emerald' => 'bg-emerald-500'];
                                @endphp
                                <span class="text-xs font-extrabold px-2 py-0.5 rounded-full {{ $colorMap[$stage['color']] }}">{{ $stage['count'] }}</span>
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
        <section class="bg-white rounded-2xl shadow-lg border border-slate-100 mt-8 overflow-hidden animate-[fadeInUp_0.7s_ease-out]">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-5 p-4 transform translate-x-4 -translate-y-4">
                    <i class="fas fa-tasks text-6xl text-[#8b0000]"></i>
                </div>
                <div class="relative z-10">
                    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">Ongoing Pipeline Proposals</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Quick-access list of submissions currently requiring action.</p>
                </div>
                <div class="flex items-center gap-2 relative z-10">
                    <span class="px-3 py-1 bg-[#8B0000] text-white text-xs font-bold rounded-lg shadow-sm">{{ count($stuckProposals) }} Pending</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider font-bold border-y border-slate-100">
                            <th class="px-6 py-4">Protocol Title</th>
                            <th class="px-6 py-4">Researcher</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Last Updated</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stuckProposals as $proposal)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 text-sm max-w-md truncate group-hover:text-[#8B0000] transition-colors" title="{{ $proposal->Study_Protocol_title }}">
                                        {{ $proposal->Study_Protocol_title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                            {{ substr($proposal->researcher->user->first_name ?? 'U', 0, 1) }}
                                        </div>
                                        {{ $proposal->researcher->user->first_name ?? '' }} {{ $proposal->researcher->user->last_name ?? 'Unknown' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusFormat = match($proposal->Status) {
                                            'Pending', 'Incomplete', 'Incomplete - Awaiting Hardcopy' => 'bg-slate-100 text-slate-600',
                                            'For Initial Review', 'Hardcopy Received - For Initial Review', 'Under Review' => 'bg-blue-100 text-blue-700',
                                            'Waiting for Revision' => 'bg-amber-100 text-amber-700',
                                            'Complete - Awaiting Hardcopy' => 'bg-emerald-100 text-emerald-700',
                                            default => 'bg-slate-100 text-slate-600'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest {{ $statusFormat }}">
                                        {{ $proposal->Status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                                    {{ $proposal->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.view_files', $proposal->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-[#8B0000] hover:border-[#8B0000] hover:shadow-sm transition-all focus:outline-none" title="View Submission">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fas fa-check-circle text-4xl mb-3 text-slate-200"></i>
                                    <p class="text-sm font-semibold">The pipeline is completely clear!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($stuckProposals->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500 bg-slate-50">
                    <div>
                        Showing <span class="font-bold text-slate-700">{{ $stuckProposals->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-700">{{ $stuckProposals->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-700">{{ $stuckProposals->total() }}</span>
                    </div>
                    <div class="flex gap-2">
                        @if ($stuckProposals->onFirstPage())
                            <span class="opacity-50 cursor-not-allowed text-slate-400 px-2.5 py-1.5"><i class="fas fa-chevron-left"></i></span>
                        @else
                            <a href="{{ $stuckProposals->previousPageUrl() }}" class="text-slate-600 hover:text-[#8B0000] hover:bg-white px-2.5 py-1.5 rounded border border-transparent hover:border-slate-200 transition-colors shadow-sm"><i class="fas fa-chevron-left"></i></a>
                        @endif

                        @if ($stuckProposals->hasMorePages())
                            <a href="{{ $stuckProposals->nextPageUrl() }}" class="text-slate-600 hover:text-[#8B0000] hover:bg-white px-2.5 py-1.5 rounded border border-transparent hover:border-slate-200 transition-colors shadow-sm"><i class="fas fa-chevron-right"></i></a>
                        @else
                            <span class="opacity-50 cursor-not-allowed text-slate-400 px-2.5 py-1.5"><i class="fas fa-chevron-right"></i></span>
                        @endif
                    </div>
                </div>
            @endif
        </section>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // --- Drawer and Filter Logic ---

            document.addEventListener('DOMContentLoaded', function() {
                toggleCollegeFilter();
                toggleExactDates();
            });

            function toggleExactDates() {
                const exactStart = document.getElementById('filter_exact_start').value;
                const exactEnd = document.getElementById('filter_exact_end').value;
                
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

            function prepareFilterSubmit() {
                // All filters are always visible in the drawer — no clearing needed
            }

            function resetFilters() {
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

            document.addEventListener('DOMContentLoaded', function() {
                // --- 1. Daily Trend Chart ---
                const ctxDaily = document.getElementById('dailyTrendChart').getContext('2d');
                const gradient = ctxDaily.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(139, 0, 0, 0.2)');
                gradient.addColorStop(1, 'rgba(139, 0, 0, 0)');

                const dailyData = @json($dailyData);
                const dayLabels = @json($dayLabels);

                new Chart(ctxDaily, {
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
                        onClick: (e, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const label = dayLabels[index];
                                let extraParams = {};
                                const year = "{{ $startYear === 'all' && $endYear === 'all' ? '' : ($startYear !== 'all' ? $startYear : date('Y')) }}";
                                const month = "{{ $startMonth !== 'all' ? $startMonth : '' }}";
                                
                                if (label.includes(' ')) {
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
                            x: { grid: { display: false } }
                        }
                    }
                });

            });

            // --- Export to PDF Function ---
            function exportToPdf() {
                const element = document.getElementById('analytics-dashboard');
                const btn = document.getElementById('exportPdfBtn');
                
                // Visual feedback
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
                btn.disabled = true;

                // Use html2canvas to capture the element
                html2canvas(element, {
                    scale: 2, // Higher quality
                    useCORS: true, // Handle external images if any
                    logging: false
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jspdf.jsPDF('l', 'mm', 'a4'); // Landscape, mm, A4
                    
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();
                    
                    const imgWidth = pageWidth;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    
                    // Add image to PDF
                    pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                    pdf.save('Analytics_Report_{{ date("Y-m-d") }}.pdf');
                    
                    // Reset button
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }).catch(err => {
                    console.error('PDF Generation Error:', err);
                    alert('Failed to generate PDF. Please check console for details.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            }
        </script>

        <!-- PDF Export Libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

        <!-- Filter Drawer -->
        <div id="filterModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex justify-end backdrop-blur-sm transition-opacity opacity-0 duration-300" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" onclick="if(event.target===this) closeFilterModal()">
            <div class="bg-white shadow-2xl w-full max-w-md h-full overflow-y-auto transform transition-transform translate-x-full duration-300 flex flex-col" id="filterModalPanel">
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white sticky top-0 z-10">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2"><i class="fas fa-filter text-[#8B0000]"></i> Global Filters</h3>
                    <button type="button" onclick="closeFilterModal()" class="text-slate-400 hover:text-[#8B0000] focus:outline-none transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Complete Filter Form -->
                <form id="filterModalForm" method="GET" action="{{ route('super_admin.analytics') }}" class="flex-1 flex flex-col">
                    <div class="p-6 space-y-8 flex-1">
                        <!-- Date Range & Basic Status -->
                        <div>
                            <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-4 text-[#8B0000]">Time & Status</h4>
                            <div class="grid grid-cols-1 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Month Range <span class="text-[10px] text-slate-400 font-normal">(Start to End)</span></label>
                                    <div class="flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <select name="start_month" id="filter_start_month" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                                @foreach(range(1, 12) as $m)
                                                    <option value="{{ $m }}" {{ $startMonth == $m ? 'selected' : '' }}>
                                                        {{ DateTime::createFromFormat('!m', $m)->format('M') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                        <span class="text-slate-400 font-bold">-</span>
                                        <div class="relative flex-1">
                                            <select name="end_month" id="filter_end_month" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                                @foreach(range(1, 12) as $m)
                                                    <option value="{{ $m }}" {{ $endMonth == $m ? 'selected' : '' }}>
                                                        {{ DateTime::createFromFormat('!m', $m)->format('M') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Year Range <span class="text-[10px] text-slate-400 font-normal">(Start to End)</span></label>
                                    <div class="flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <select name="start_year" id="filter_start_year" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                                <option value="all" {{ $startYear === 'all' ? 'selected' : '' }}>Earliest</option>
                                                @foreach($availableYears as $year)
                                                    <option value="{{ $year }}" {{ $startYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                                @if(!in_array(date('Y'), $availableYears->toArray()))
                                                    <option value="{{ date('Y') }}" {{ $startYear == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                                                @endif
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><i class="fas fa-chevron-down text-xs"></i></div>
                                        </div>
                                        <span class="text-slate-400 font-bold">-</span>
                                        <div class="relative flex-1">
                                            <select name="end_year" id="filter_end_year" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                                <option value="all" {{ $endYear === 'all' ? 'selected' : '' }}>Latest</option>
                                                @foreach($availableYears as $year)
                                                    <option value="{{ $year }}" {{ $endYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                                @if(!in_array(date('Y'), $availableYears->toArray()))
                                                    <option value="{{ date('Y') }}" {{ $endYear == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                                                @endif
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><i class="fas fa-chevron-down text-xs"></i></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Exact Date Range (Overrides Dropdowns) -->
                                <div>
                                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 text-[#8B0000]">Specific Date <span class="text-[10px] text-slate-400 font-normal normal-case tracking-normal">(Start to End)</span></h4>
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="relative flex-1">
                                            <input type="date" name="exact_start" id="filter_exact_start" value="{{ request('exact_start') }}" onchange="document.getElementById('filter_exact_end').min = this.value; toggleExactDates()" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors">
                                        </div>
                                        <span class="text-slate-400 font-bold">-</span>
                                        <div class="relative flex-1">
                                            <input type="date" name="exact_end" id="filter_exact_end" value="{{ request('exact_end') }}" onchange="toggleExactDates()" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Status</label>
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
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
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
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-clipboard-check text-slate-400"></i> Review Type
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
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-book text-slate-400"></i> Thesis Type
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
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-tags text-slate-400"></i> Research Type
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
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-users text-slate-400"></i> Affiliation
                                    </label>
                                    <div class="relative">
                                        <select name="affiliation" id="filter_affiliation" onchange="toggleCollegeFilter()" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="">All Affiliations</option>
                                            <option value="Internal" {{ $selectedAffiliation == 'Internal' ? 'selected' : '' }}>Internal</option>
                                            <option value="External" {{ $selectedAffiliation == 'External' ? 'selected' : '' }}>External</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fas fa-university text-slate-400"></i> College
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
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="p-6 bg-slate-50 border-t border-slate-200 sticky bottom-0">
                        <div class="flex gap-3 justify-end">
                            <button type="button" onclick="resetFilters()" class="flex-1 py-3 bg-white text-slate-700 border border-slate-200 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm">
                                Reset
                            </button>
                            <button type="submit" onclick="prepareFilterSubmit()" class="flex-1 py-3 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-red-900 transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i> Apply
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- See All Submitters Modal -->
    <div id="seeAllModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex justify-end backdrop-blur-sm transition-opacity opacity-0 duration-300" onclick="if(event.target===this) closeSeeAllModal()">
        <div class="bg-white shadow-2xl w-full max-w-md h-full overflow-y-auto transform transition-transform translate-x-full duration-300 flex flex-col" id="seeAllModalPanel">
            <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white sticky top-0 z-10">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-university text-[#8B0000]"></i> {{ $topSubmittersLabel }}
                </h3>
                <button type="button" onclick="closeSeeAllModal()" class="text-slate-400 hover:text-[#8B0000] focus:outline-none transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-3 flex-1">
                @foreach($allSubmitters as $index => $item)
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-400 w-5 text-right">{{ $index + 1 }}</span>
                    <div class="flex-1 cursor-pointer group hover:text-[#8B0000]" onclick="openDetailsModal('college_specific', '{{ addslashes($item->name ?? 'Unspecified') }}', { college_name: '{{ addslashes($item->name ?? 'Unspecified') }}' })">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-semibold text-slate-700 truncate pr-3 group-hover:text-[#8B0000] transition-colors">{{ $item->name ?? 'Unspecified' }}</span>
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
    <div id="detailsModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-300" onclick="if(event.target===this) closeDetailsModal()">
        <div class="bg-white shadow-2xl rounded-2xl w-full max-w-4xl max-h-[80vh] overflow-hidden transform transition-transform scale-95 duration-300 flex flex-col" id="detailsModalPanel">
            <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-white sticky top-0 z-10">
                <h3 id="detailsModalTitle" class="text-xl font-bold text-slate-800 flex items-center gap-2"></h3>
                <button type="button" onclick="closeDetailsModal()" class="text-slate-400 hover:text-[#8B0000] focus:outline-none transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1 h-full min-h-[300px]" id="detailsModalContent">
                <div class="flex items-center justify-center py-20">
                    <i class="fas fa-spinner fa-spin text-4xl text-[#8B0000]"></i>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDetailsModal(type, title, extraParams = {}) {
            const modal = document.getElementById('detailsModal');
            const panel = document.getElementById('detailsModalPanel');
            const titleElem = document.getElementById('detailsModalTitle');
            const contentElem = document.getElementById('detailsModalContent');

            titleElem.innerText = title;
            contentElem.innerHTML = '<div class="flex items-center justify-center py-20"><i class="fas fa-spinner fa-spin text-4xl text-[#8B0000]"></i></div>';
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                panel.classList.remove('scale-95');
                panel.classList.add('scale-100');
            }, 10);

            // Fetch data
            const params = new URLSearchParams(window.location.search);
            params.set('type', type);
            Object.entries(extraParams).forEach(([key, value]) => params.set(key, value));
            
            fetch(`{{ route('super_admin.analytics.details') }}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        contentElem.innerHTML = '<div class="text-center py-20 text-slate-400">No data available for this category.</div>';
                        return;
                    }

                    let html = '<table class="w-full text-left border-collapse">';
                    if (type === 'researchers') {
                        html += `
                            <thead>
                                <tr class="text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Affiliation</th>
                                    <th class="px-4 py-3">College</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                        `;
                        data.forEach(item => {
                            html += `
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-semibold text-slate-700">${item.name}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">${item.email}</td>
                                    <td class="px-4 py-3 text-sm"><span class="px-2 py-1 rounded-full text-[10px] font-bold ${item.affiliation === 'Internal' ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600'}">${item.affiliation}</span></td>
                                    <td class="px-4 py-3 text-sm text-slate-500">${item.college}</td>
                                </tr>
                            `;
                        });
                    } else {
                        html += `
                            <thead>
                                <tr class="text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                                    <th class="px-4 py-3">Protocol Title</th>
                                    <th class="px-4 py-3">Researcher</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Date</th>
                                    ${type === 'revisions' ? '<th class="px-4 py-3 text-center">Revision #</th>' : ''}
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                        `;
                        data.forEach(item => {
                            html += `
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-semibold text-slate-700 max-w-xs truncate" title="${item.title}">${item.title}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">${item.researcher}</td>
                                    <td class="px-4 py-3 text-sm"><span class="px-2 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">${item.status}</span></td>
                                    <td class="px-4 py-3 text-sm text-slate-500">${item.date}</td>
                                    ${type === 'revisions' ? `<td class="px-4 py-3 text-sm text-center font-bold text-[#8B0000]">${item.revisions}</td>` : ''}
                                </tr>
                            `;
                        });
                    }
                    html += '</tbody></table>';
                    contentElem.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching details:', error);
                    contentElem.innerHTML = '<div class="text-center py-20 text-red-500">Failed to load data. Please try again.</div>';
                });
        }

        function closeDetailsModal() {
            const modal = document.getElementById('detailsModal');
            const panel = document.getElementById('detailsModalPanel');
            modal.classList.add('opacity-0');
            panel.classList.remove('scale-100');
            panel.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        function openSeeAllModal() {
            const modal = document.getElementById('seeAllModal');
            const panel = document.getElementById('seeAllModalPanel');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                panel.classList.remove('translate-x-full');
                panel.classList.add('translate-x-0');
            }, 10);
        }
        function closeSeeAllModal() {
            const modal = document.getElementById('seeAllModal');
            const panel = document.getElementById('seeAllModalPanel');
            modal.classList.add('opacity-0');
            panel.classList.remove('translate-x-0');
            panel.classList.add('translate-x-full');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
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
    </script>
</x-super_admin_layout>

