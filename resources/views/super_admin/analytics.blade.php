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
            </div>
        </div>
        
        <!-- Active Filters Display -->
        <div class="flex flex-wrap gap-2 -mt-4 mb-2">
            <span class="px-2.5 py-1 bg-red-50 text-[#8B0000] border border-red-100 rounded-lg text-xs font-bold flex items-center gap-1">
                <i class="fas fa-calendar-alt opacity-70"></i> {{ $selectedMonth === 'all' ? 'All Months' : DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear === 'all' ? 'All Years' : $selectedYear }}
            </span>
            @if($selectedStatus) 
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-xs font-bold flex items-center gap-1">
                <i class="fas fa-info-circle opacity-50"></i> {{ $selectedStatus }}
            </span> 
            @endif
            @if($selectedReviewType) 
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-xs font-bold flex items-center gap-1">
                <i class="fas fa-clipboard-check opacity-50"></i> {{ $selectedReviewType }}
            </span> 
            @endif
            @if($selectedThesisType) 
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-xs font-bold flex items-center gap-1">
                <i class="fas fa-book opacity-50"></i> {{ $selectedThesisType }}
            </span> 
            @endif
            @if($selectedCategory) 
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-xs font-bold flex items-center gap-1">
                <i class="fas fa-tags opacity-50"></i> {{ $selectedCategory }}
            </span> 
            @endif
            @if($selectedAffiliation) 
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-xs font-bold flex items-center gap-1">
                <i class="fas fa-users opacity-50"></i> {{ $selectedAffiliation }}
            </span> 
            @endif
            @if($selectedCollege && $selectedAffiliation !== 'External') 
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-xs font-bold flex items-center gap-1">
                <i class="fas fa-university opacity-50"></i> {{ $selectedCollege }}
            </span> 
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
                    <span class="text-xs font-bold text-green-600 bg-green-50 px-1.5 py-0.5 rounded mb-1"><i class="fas fa-arrow-up"></i> 12%</span>
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
                    <i class="fas fa-clock text-6xl text-orange-500"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Avg. Review Time</p>
                <div class="flex items-end gap-2 mt-2">
                    <h3 class="text-3xl font-extrabold text-slate-800">14</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">Days</span>
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
                    <span class="text-xs font-bold text-green-600 bg-green-50 px-1.5 py-0.5 rounded mb-1"><i class="fas fa-arrow-up"></i> 5%</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Daily Trend (Takes up 2/3) -->
            <section class="lg:col-span-2 bg-white rounded-2xl shadow-xl border border-slate-100 p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fas fa-chart-line text-9xl text-[#8B0000]"></i>
                </div>
                
                <div class="relative z-10">
                    <h2 class="text-sm font-bold text-[#8B0000] uppercase tracking-widest mb-1">Submission Trends</h2>
                    <div class="flex items-end gap-4 mb-8">
                        <p class="text-5xl font-extrabold text-slate-900">Daily Overview</p>
                        <p class="text-sm text-slate-500 font-medium mb-1.5">{{ $selectedMonth === 'all' ? 'All Months' : DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear === 'all' ? 'All Years' : $selectedYear }}</p>
                    </div>

                    <div class="h-80 w-full">
                        <canvas id="dailyTrendChart"></canvas>
                    </div>
                </div>
            </section>

            <!-- Right Column: Pie Charts (Takes up 1/3) -->
            <div class="space-y-8">
                <!-- Pie Chart 1: Review Type -->
                <section class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Review Type Distribution ({{ $selectedYear }})</h3>
                    <div class="h-64">
                        <canvas id="reviewTypeChart"></canvas>
                    </div>
                </section>

                <!-- Pie Chart 2: Status Overview -->
                <section class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Approval Status ({{ $selectedYear }})</h3>
                    <div class="h-64">
                        <canvas id="statusChart"></canvas>
                    </div>
                </section>
            </div>
        </div>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // --- Modal and Filter Logic ---
            let currentMode = 'normal';

            // Check URL parameters to see if advanced filters were applied on load
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('review_type') || urlParams.get('thesis_type') || urlParams.get('category') || urlParams.get('affiliation') || urlParams.get('college')) {
                    switchFilterTab('advanced');
                }
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
                    filterModalPanel.classList.remove('scale-95', 'opacity-0');
                    filterModalPanel.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeFilterModal() {
                const filterModal = document.getElementById('filterModal');
                const filterModalPanel = document.getElementById('filterModalPanel');
                if (!filterModal) return;

                filterModal.classList.add('opacity-0');
                filterModalPanel.classList.remove('scale-100', 'opacity-100');
                filterModalPanel.classList.add('scale-95', 'opacity-0');
                
                setTimeout(() => {
                    filterModal.classList.add('hidden');
                }, 300); // match standard tailwind transition duration
            }

            function switchFilterTab(mode) {
                currentMode = mode;
                const tabNormal = document.getElementById('tab-normal');
                const tabAdvanced = document.getElementById('tab-advanced');
                const advancedSection = document.getElementById('advancedFiltersSection');
                
                if (mode === 'normal') {
                    // Update Tabs
                    tabNormal.className = "flex-1 py-3 text-sm font-bold border-b-2 border-[#8B0000] text-[#8B0000] bg-white transition-colors focus:outline-none";
                    tabAdvanced.className = "flex-1 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors focus:outline-none";
                    // Hide Advanced Section
                    advancedSection.classList.add('hidden');
                } else {
                    // Update Tabs
                    tabAdvanced.className = "flex-1 py-3 text-sm font-bold border-b-2 border-[#8B0000] text-[#8B0000] bg-white transition-colors focus:outline-none";
                    tabNormal.className = "flex-1 py-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors focus:outline-none";
                    // Show Advanced Section
                    advancedSection.classList.remove('hidden');
                }
            }

            function prepareFilterSubmit() {
                // If in normal mode, clear advanced inputs before submitting
                if (currentMode === 'normal') {
                    document.getElementById('filter_review_type').value = '';
                    document.getElementById('filter_thesis_type').value = '';
                    document.getElementById('filter_category').value = '';
                    document.getElementById('filter_affiliation').value = '';
                    document.getElementById('filter_college').value = '';
                }
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

                // --- 2. Review Type Pie Chart ---
                const ctxReview = document.getElementById('reviewTypeChart').getContext('2d');
                const reviewStats = @json($reviewTypeStats);
                
                new Chart(ctxReview, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(reviewStats),
                        datasets: [{
                            data: Object.values(reviewStats),
                            backgroundColor: ['#8B0000', '#F59E0B', '#10B981', '#3B82F6'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
                        },
                        cutout: '70%'
                    }
                });

                // --- 3. Status Pie Chart ---
                const ctxStatus = document.getElementById('statusChart').getContext('2d');
                const statusStats = @json($statusStats);

                new Chart(ctxStatus, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(statusStats),
                        datasets: [{
                            data: Object.values(statusStats),
                            backgroundColor: [
                                '#10B981', // Approved (Green)
                                '#EF4444'  // Disapproved (Red)
                            ].concat(['#CBD5E1', '#F59E0B']), // Fallback colors
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
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

        <!-- Filter Modal -->
        <div id="filterModal" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="filterModalPanel">
                <!-- Modal Header -->
                <div class="px-8 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2"><i class="fas fa-filter text-[#8B0000]"></i> Filter Analytics Data</h3>
                    <button type="button" onclick="closeFilterModal()" class="text-slate-400 hover:text-[#8B0000] focus:outline-none transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex border-b border-slate-200 bg-slate-50/50 px-8 pt-4">
                    <button type="button" id="tab-normal" onclick="switchFilterTab('normal')" class="pb-3 px-6 text-sm font-bold border-b-2 border-[#8B0000] text-[#8B0000] transition-colors focus:outline-none flex items-center gap-2">
                        <i class="fas fa-calendar-alt"></i> Date Range
                    </button>
                    <button type="button" id="tab-advanced" onclick="switchFilterTab('advanced')" class="pb-3 px-6 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors focus:outline-none flex items-center gap-2">
                        <i class="fas fa-sliders-h"></i> Advanced Metrics
                    </button>
                </div>

                <!-- Complete Filter Form -->
                <form id="filterModalForm" method="GET" action="{{ route('super_admin.analytics') }}" class="p-8">
                    <div class="space-y-8">
                        <!-- Date Range & Basic Status (Always visible but grouped) -->
                        <div>
                            <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-4 text-[#8B0000]">Basic Filter</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                        <!-- Advanced Options (Hidden in Normal Tab) -->
                        <div id="advancedFiltersSection" class="hidden border-t border-slate-200 pt-6 animate-[fadeIn_0.3s_ease-out]">
                            <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-4 text-[#8B0000]">Advance Filter</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                    <div class="mt-8 flex gap-3 justify-end pt-6 border-t border-slate-200">
                        <button type="button" onclick="closeFilterModal()" class="px-6 py-2.5 bg-white border border-slate-300 text-slate-600 rounded-xl text-sm font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm">
                            Cancel
                        </button>
                        <button type="button" onclick="resetFilters()" class="px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold uppercase tracking-wider hover:bg-slate-200 transition-colors shadow-sm">
                            Reset to Default
                        </button>
                        <button type="submit" onclick="prepareFilterSubmit()" class="px-8 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-bold uppercase tracking-wider hover:bg-red-900 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                            <i class="fas fa-check"></i> Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Move modal to body to avoid stacking context issues with header
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('filterModal');
            if (modal) {
                document.body.appendChild(modal);
            }
        });
    </script>
</x-super_admin_layout>
