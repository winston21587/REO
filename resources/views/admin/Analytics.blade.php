<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Analytics & Reports</h1>
                <p class="text-slate-500 mt-2 text-sm">Real-time insights into research submission performance.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <button class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
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

        <section class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <i class="fas fa-chart-line text-9xl text-[#8B0000]"></i>
            </div>
            
            <div class="relative z-10">
                <h2 class="text-sm font-bold text-[#8B0000] uppercase tracking-widest mb-1">Submission Trends</h2>
                <div class="flex items-end gap-4 mb-8">
                    <p class="text-5xl font-extrabold text-slate-900">Monthly Overview</p>
                </div>

                <div class="h-80 w-full">
                    <canvas id="submissionTrendChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('submissionTrendChart').getContext('2d');
                
                // Create Gradient
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(139, 0, 0, 0.2)'); // #8B0000 with opacity
                gradient.addColorStop(1, 'rgba(139, 0, 0, 0)');

                const monthlyData = @json($monthlyData);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Submissions',
                            data: monthlyData,
                            borderColor: '#8B0000',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#8B0000',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4 // Smooth curve
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                titleFont: {
                                    family: "'Inter', sans-serif",
                                    size: 13
                                },
                                bodyFont: {
                                    family: "'Inter', sans-serif",
                                    size: 13,
                                    weight: 'bold'
                                },
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' Submissions';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        family: "'Inter', sans-serif",
                                        size: 11
                                    },
                                    color: '#64748b',
                                    padding: 10,
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        family: "'Inter', sans-serif",
                                        size: 11,
                                        weight: 'bold'
                                    },
                                    color: '#94a3b8',
                                    padding: 10
                                }
                            }
                        }
                    }
                });
            });
        </script>

        <div class="grid md:grid-cols-2 gap-8">
            <section class="bg-white p-8 rounded-2xl shadow-lg border border-slate-100 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Completion Status</h3>
                    <div class="flex items-end gap-4 mb-6">
                        <p class="text-4xl font-extrabold text-slate-900">{{ $completionRate }}%</p>
                        <span class="mb-2 text-sm text-slate-500 font-medium">Completion Rate</span>
                    </div>
                </div>
                
                <div class="flex items-end gap-4 h-40">
                    <div class="flex-1 flex flex-col justify-end gap-2 group cursor-pointer">
                        <div class="w-full bg-[#8B0000] rounded-t-lg relative group-hover:bg-red-700 transition-all" style="height: {{ $totalSubmissions > 0 ? ($doneCount / $totalSubmissions) * 100 : 0 }}%;">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">{{ $doneCount }}</div>
                        </div>
                        <p class="text-center text-xs font-bold text-slate-500">Done</p>
                    </div>
                    <div class="flex-1 flex flex-col justify-end gap-2 group cursor-pointer">
                        <div class="w-full bg-slate-200 rounded-t-lg relative group-hover:bg-slate-300 transition-all" style="height: {{ $totalSubmissions > 0 ? ($activeCount / $totalSubmissions) * 100 : 0 }}%;">
                             <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">{{ $activeCount }}</div>
                        </div>
                        <p class="text-center text-xs font-bold text-slate-500">Active</p>
                    </div>
                    <div class="flex-1 flex flex-col justify-end gap-2 group cursor-pointer">
                        <div class="w-full bg-slate-100 rounded-t-lg relative group-hover:bg-slate-200 transition-all" style="height: {{ $totalSubmissions > 0 ? ($pendingCount / $totalSubmissions) * 100 : 0 }}%;">
                             <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">{{ $pendingCount }}</div>
                        </div>
                        <p class="text-center text-xs font-bold text-slate-500">Pending</p>
                    </div>
                </div>
            </section>

            <section class="bg-slate-900 text-white p-8 rounded-2xl shadow-lg relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#8B0000] rounded-full blur-3xl opacity-30"></div>

                <h3 class="text-lg font-bold mb-6 relative z-10">AI Compliance Checks</h3>
                
                <div class="space-y-6 relative z-10">
                    <div>
                        <div class="flex justify-between mb-2 text-sm font-medium">
                            <span class="text-slate-300">AI Generated Content (Avg)</span>
                            <span class="text-white">{{ $avgAiScore }}%</span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-2">
                            <div class="bg-[#8B0000] h-2 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.5)] transition-all duration-1000 ease-out" style="width: {{ $avgAiScore }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-2 text-sm font-medium">
                            <span class="text-slate-300">Human Verified</span>
                            <span class="text-white">{{ $humanVerifiedRate }}%</span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.5)] transition-all duration-1000 ease-out" style="width: {{ $humanVerifiedRate }}%"></div>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-white/5 rounded-xl border border-white/10">
                        <p class="text-xs text-slate-400 leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i> 
                            Most flagged issues relate to missing page numbers in "Research Protocol" documents.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-admin_layout>