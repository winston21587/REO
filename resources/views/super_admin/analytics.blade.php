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

                <button id="exportPdfBtn" onclick="exportToPdf()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fas fa-download"></i> Export PDF
                </button>

                <a href="{{ route('super_admin.analytics.export', request()->query()) }}" id="exportCsvBtn" class="px-4 py-2 bg-white border border-slate-200 text-[#8B0000] rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        <div class="flex flex-wrap gap-2 -mt-4 mb-2">
            <div class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm border border-slate-200">
                <i class="fas fa-calendar-alt text-slate-400"></i>
                @if($selectedMonth === 'all' && $selectedYear === 'all')
                    All Time
                @else
                    {{ $selectedMonth === 'all' ? 'All Months' : DateTime::createFromFormat('!m', $selectedMonth)->format('M') }} {{ $selectedYear === 'all' ? 'All Years' : $selectedYear }}
                @endif
                @if($selectedMonth !== 'all' || $selectedYear !== 'all')
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
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group">
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
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group">
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
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group">
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
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group">
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
                        <p class="text-3xl font-extrabold text-slate-900">{{ $selectedMonth === 'all' ? 'Monthly Overview' : 'Daily Overview' }}</p>
                        <p class="text-sm text-slate-500 font-medium mb-1.5">{{ $selectedMonth === 'all' ? 'All Months' : DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear === 'all' ? 'All Years' : $selectedYear }}</p>
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
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-sm font-semibold text-slate-700 truncate pr-3">{{ $item->name ?? 'Unspecified' }}</span>
                                <span class="text-sm font-extrabold text-slate-800">{{ $item->count }}</span>
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
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-xs font-semibold text-slate-600">{{ $stage['label'] }}</span>
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

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // --- Drawer and Filter Logic ---

            document.addEventListener('DOMContentLoaded', function() {
                toggleCollegeFilter();
            });

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
                document.getElementById('filter_month').value = '{{ date("n") }}';
                
                const yearSelect = document.getElementById('filter_year');
                const currentYear = '{{ date("Y") }}';
                let hasYear = Array.from(yearSelect.options).some(opt => opt.value === currentYear);
                if (hasYear) {
                    yearSelect.value = currentYear;
                }
                
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
                    document.getElementById('filter_month').value = 'all';
                    document.getElementById('filter_year').value = 'all';
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
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Month</label>
                                    <div class="relative">
                                        <select name="month" id="filter_month" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="all" {{ $selectedMonth === 'all' ? 'selected' : '' }}>All Months</option>
                                            @foreach(range(1, 12) as $m)
                                                <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2">Year</label>
                                    <div class="relative">
                                        <select name="year" id="filter_year" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>All Years</option>
                                            @foreach($availableYears as $year)
                                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                            @if(!in_array(date('Y'), $availableYears->toArray()))
                                                 <option value="{{ date('Y') }}" {{ $selectedYear == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                                            @endif
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
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
                                        <i class="fas fa-tags text-slate-400"></i> Category
                                    </label>
                                    <div class="relative">
                                        <select name="category" id="filter_category" class="w-full px-4 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-400 transition-colors appearance-none pr-10">
                                            <option value="">All Categories</option>
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
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-semibold text-slate-700 truncate pr-3">{{ $item->name ?? 'Unspecified' }}</span>
                            <span class="text-sm font-extrabold text-slate-800">{{ $item->count }}</span>
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

    <script>
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
            if (filterModal) document.body.appendChild(filterModal);
            if (seeAllModal) document.body.appendChild(seeAllModal);
        });
    </script>
</x-super_admin_layout>

