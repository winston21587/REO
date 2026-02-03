<x-admin_layout>
    <div id="analytics-dashboard" class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Analytics & Reports</h1>
                <p class="text-slate-500 mt-2 text-sm">Real-time insights into research submission performance.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0 items-center">
                <!-- Date Filter Form -->
                <form method="GET" action="{{ route('admin.analytics') }}" class="flex gap-2 items-center">
                    <select name="month" class="px-3 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endforeach
                    </select>

                    <select name="year" class="px-3 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                        @if(!in_array(date('Y'), $availableYears->toArray()))
                             <option value="{{ date('Y') }}" {{ $selectedYear == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                        @endif
                    </select>

                    <button type="submit" class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-red-800 transition-colors shadow-sm">
                        Filter
                    </button>
                </form>

                <div class="h-8 w-px bg-slate-200 mx-2"></div>

                <button id="exportPdfBtn" onclick="exportToPdf()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fas fa-download"></i> Export PDF
                </button>
            </div>
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
                        <p class="text-sm text-slate-500 font-medium mb-1.5">{{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}</p>
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
    </div>
</x-admin_layout>