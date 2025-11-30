<x-user_layout>
    <div class="max-w-5xl mx-auto animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading">
                Welcome back, <span class="text-[#8B0000]">{{ explode(' ', Auth::user()->first_name)[0] }}</span>!
            </h1>
            <p class="text-slate-500 mt-2 text-lg">Here is the status of your research submission.</p>
        </div>

        @if($titles->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-file-upload text-4xl text-[#8B0000]"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-3">No Submission Yet</h2>
                <p class="text-slate-500 max-w-md mx-auto mb-8 text-lg">You haven't submitted a research protocol yet. Start your application to get your ethics review underway.</p>
                <a href="{{ route('submit') }}" class="inline-flex items-center gap-3 bg-[#8B0000] text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-plus-circle"></i>
                    <span>Start New Submission</span>
                </a>
            </div>
        @else
            @php
                $title = $titles->first();
                
                // Tracker Logic
                $steps = [
                    1 => ['label' => 'Submitted', 'icon' => 'fa-paper-plane'],
                    2 => ['label' => 'Ongoing Review', 'icon' => 'fa-search'],
                    3 => ['label' => 'Deliberation', 'icon' => 'fa-clipboard-check'],
                    4 => ['label' => 'Certificate', 'icon' => 'fa-certificate'],
                ];

                $currentStep = 1;
                $status = $title->status ?? '';

                if (str_contains($status, 'Approved') || str_contains($status, 'Complete')) {
                    $currentStep = 4;
                } elseif ($status === 'Panel Deliberation') {
                    $currentStep = 3;
                } elseif (in_array($status, [
                    'For Initial Review', 
                    'Waiting for Revision', 
                    'Revision Submitted', 
                    'Checking of Revisions', 
                    'Submission of Revisions / Resubmission', 
                    'Hardcopy Received - For Initial Review'
                ])) {
                    $currentStep = 2;
                }

                $statusColor = match($title->status) {
                    'Approved' => 'green',
                    'Returned', 'Waiting for Revision' => 'orange',
                    'Panel Deliberation' => 'blue',
                    default => 'orange',
                };
                $statusIcon = match($title->status) {
                    'Approved' => 'fa-check-circle',
                    'Returned', 'Waiting for Revision' => 'fa-exclamation-circle',
                    'Panel Deliberation' => 'fa-users',
                    default => 'fa-clock',
                };
            @endphp

            <div class="space-y-8">
                <!-- Submission Tracker -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                    <h3 class="text-slate-800 font-bold mb-8 flex items-center gap-2">
                        <i class="fas fa-route text-[#8B0000]"></i> Submission Progress
                    </h3>
                    <div class="relative">
                        <!-- Progress Bar Background -->
                        <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 rounded-full z-0"></div>
                        
                        <!-- Active Progress Bar -->
                        <div class="absolute top-1/2 left-0 h-1 bg-[#8B0000] -translate-y-1/2 rounded-full z-0 transition-all duration-1000 ease-out"
                             style="width: {{ ($currentStep - 1) / (count($steps) - 1) * 100 }}%"></div>

                        <div class="relative z-10 flex justify-between">
                            @foreach($steps as $step => $data)
                                @php
                                    $isCompleted = $step < $currentStep;
                                    $isCurrent = $step === $currentStep;
                                    $isActive = $step <= $currentStep;
                                @endphp
                                <div class="flex flex-col items-center group">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center border-4 transition-all duration-300 bg-white
                                        {{ $isActive ? 'border-[#8B0000] text-[#8B0000]' : 'border-slate-200 text-slate-300' }}
                                        {{ $isCurrent ? 'scale-110 shadow-lg shadow-red-900/20' : '' }}">
                                        <i class="fas {{ $data['icon'] }} {{ $isActive ? '' : 'text-slate-300' }} text-lg"></i>
                                    </div>
                                    <span class="mt-3 text-xs font-bold uppercase tracking-wider transition-colors duration-300
                                        {{ $isActive ? 'text-[#8B0000]' : 'text-slate-400' }}">
                                        {{ $data['label'] }}
                                    </span>
                                    @if($isCurrent)
                                        <span class="absolute -bottom-6 text-[10px] font-bold text-slate-400 animate-pulse">Current Stage</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Status Hero Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden relative group">
                    <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="fas {{ $statusIcon }} text-9xl text-{{ $statusColor }}-600"></i>
                    </div>
                    
                    <div class="p-8 relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 flex items-center gap-2">
                                        <i class="fas {{ $statusIcon }}"></i>
                                        {{ $title->status ?? 'Pending Review' }}
                                    </span>
                                    <span class="text-slate-400 text-sm font-medium">
                                        <i class="far fa-calendar-alt mr-1"></i> Submitted on {{ $title->created_at->format('F d, Y') }}
                                    </span>
                                </div>
                                <h2 class="text-3xl font-extrabold text-slate-900 leading-tight max-w-3xl">
                                    {{ $title->Study_Protocol_title }}
                                </h2>
                            </div>
                            
                            <div class="flex flex-col gap-3 min-w-[200px]">
                                <a href="{{ route('manage.files', $title->id) }}" class="w-full py-3 px-6 bg-[#8B0000] text-white rounded-xl font-bold text-center shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-folder-open"></i> Manage Files
                                </a>
                                
                                @php
                                    $recommendationLetter = $title->files->firstWhere('filetype', 'recommendation letter');
                                    $certificate = $title->files->firstWhere('filetype', 'certificate');
                                @endphp

                                @if($recommendationLetter)
                                    <a href="{{ route('manage.files', $title->id) }}" class="w-full py-3 px-6 bg-white border-2 border-[#8B0000] text-[#8B0000] rounded-xl font-bold hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                                        <i class="fas fa-certificate"></i> View Recommendation Letter
                                    </a>
                                @endif

                                @if($certificate)
                                    <a href="{{ route('manage.files', $title->id) }}" class="w-full py-3 px-6 bg-green-50 border-2 border-green-500 text-green-700 rounded-xl font-bold hover:bg-green-100 transition-colors flex items-center justify-center gap-2">
                                        <i class="fas fa-award"></i> Download Certificate
                                    </a>
                                @endif

                                @if($title->status === 'Returned' || $title->status === 'Waiting for Revision')
                                    <button class="w-full py-3 px-6 bg-white border-2 border-orange-100 text-orange-700 rounded-xl font-bold hover:bg-orange-50 transition-colors flex items-center justify-center gap-2">
                                        <i class="fas fa-comment-alt"></i> View Feedback
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar (Visual Flair) -->
                    <div class="h-1.5 w-full bg-slate-100 mt-4">
                        <div class="h-full bg-{{ $statusColor }}-500 rounded-r-full" style="width: {{ $title->status === 'Approved' ? '100%' : ($title->status === 'Returned' ? '70%' : '35%') }}"></div>
                    </div>
                </div>

                <!-- Details Grid -->
                @php
                    $hasLetter = $title->files->where('filetype', 'Result of Review (Admin Generated)')->isNotEmpty();
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 {{ $hasLetter ? 'lg:grid-cols-3' : 'lg:grid-cols-2' }} gap-8 items-stretch">
                    
                    <!-- Protocol Info Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 flex flex-col h-full hover:shadow-md transition-shadow duration-300">
                        <h3 class="text-slate-800 font-bold mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
                            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                            <span class="text-lg">Protocol Info</span>
                        </h3>
                        <div class="space-y-6 flex-1">
                            <div class="group">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block group-hover:text-[#8B0000] transition-colors">Category</label>
                                <p class="text-slate-800 font-bold text-lg">{{ $title->Research_Category }}</p>
                            </div>
                            <div class="group">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block group-hover:text-[#8B0000] transition-colors">Adviser</label>
                                <p class="text-slate-800 font-bold text-lg">{{ $title->Adviser }}</p>
                            </div>
                            <div class="group">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block group-hover:text-[#8B0000] transition-colors">Reference ID</label>
                                <p class="text-slate-600 font-mono bg-slate-50 px-3 py-1 rounded-lg inline-block border border-slate-100">#{{ str_pad($title->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 p-8 flex flex-col h-full hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                        <!-- Decorative Background -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-bl-full -mr-10 -mt-10 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                        
                        <h3 class="text-blue-900 font-bold mb-6 flex items-center gap-3 relative z-10 border-b border-blue-100/50 pb-4">
                            <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-blue-600 shadow-sm">
                                <i class="fas fa-life-ring text-xl"></i>
                            </div>
                            <span class="text-lg">Resources & Help</span>
                        </h3>
                        
                        <div class="flex-1 flex flex-col justify-center gap-4 relative z-10">
                            <p class="text-blue-800 mb-2 font-medium">Need to revise your documents? Access our guidelines and templates below.</p>
                            
                            <a href="{{ route('instructions') }}" class="flex items-center gap-4 p-4 bg-white rounded-xl border border-blue-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group/link">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover/link:scale-110 transition-transform">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800 group-hover/link:text-blue-700 transition-colors">View Guidelines</span>
                                    <span class="text-xs text-slate-500">Read the submission rules</span>
                                </div>
                                <i class="fas fa-arrow-right ml-auto text-blue-300 group-hover/link:text-blue-500 transition-colors"></i>
                            </a>

                            <a href="{{ route('resources') }}" class="flex items-center gap-4 p-4 bg-white rounded-xl border border-blue-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group/link">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover/link:scale-110 transition-transform">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800 group-hover/link:text-indigo-700 transition-colors">Download Templates</span>
                                    <span class="text-xs text-slate-500">Get official forms</span>
                                </div>
                                <i class="fas fa-arrow-right ml-auto text-indigo-300 group-hover/link:text-indigo-500 transition-colors"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Recommendation Letter Card (Conditional) -->
                    @if($hasLetter)
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100 p-8 flex flex-col h-full hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                        <!-- Decorative Background -->
                        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-emerald-100 rounded-full opacity-30 group-hover:scale-125 transition-transform duration-700"></div>
                        
                        <h3 class="text-emerald-900 font-bold mb-6 flex items-center gap-3 relative z-10 border-b border-emerald-100/50 pb-4">
                            <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-emerald-600 shadow-sm">
                                <i class="fas fa-envelope-open-text text-xl"></i>
                            </div>
                            <span class="text-lg">Result of Review</span>
                        </h3>
                        
                        <div class="flex-1 flex flex-col justify-between relative z-10">
                            <div class="mb-6">
                                <p class="text-emerald-800 font-medium text-lg mb-2">Review Completed</p>
                                <p class="text-emerald-600/80 text-sm">Your official review result letter has been generated and is ready for viewing.</p>
                            </div>
                            
                            <a href="{{ route('recommendation.view', $title->id) }}" target="_blank" class="w-full py-4 bg-white text-emerald-700 rounded-xl font-bold text-center shadow-sm border border-emerald-100 hover:bg-emerald-600 hover:text-white hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 group/btn">
                                <i class="fas fa-eye group-hover/btn:scale-110 transition-transform"></i>
                                <span>View Official Letter</span>
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        @endif
    </div>
</x-user_layout>