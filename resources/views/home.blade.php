<x-user_layout>
    <x-skeleton-loader />
    
    <div id="page-content" style="display: none;" class="max-w-5xl mx-auto animate-[fadeInUp_0.5s_ease-out]">

        <!-- Welcome Section -->
        <!-- Mobile Header (Compact) -->
        <div class="md:hidden mb-4 pt-2">
            <h1 class="text-xl font-extrabold text-slate-900 font-heading tracking-tight leading-tight">
                Welcome back, <span class="text-[#8B0000]">{{ explode(' ', Auth::user()->first_name)[0] }}</span>!
            </h1>
            <p class="text-slate-500 mt-1 text-sm">Here is the status of your research submissions.</p>
        </div>

        <!-- Desktop Header (Original Spacious) -->
        <div class="hidden md:flex justify-between items-center mb-8 pt-5">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading">
                    Welcome back, <span class="text-[#8B0000]">{{ explode(' ', Auth::user()->first_name)[0] }}</span>!
                </h1>
                <p class="text-slate-500 mt-2 text-lg">Here is the status of your research submissions.</p>
            </div>
            
            <a href="{{ route('submit') }}" class="group flex items-center gap-2 bg-[#8B0000] text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-900 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <i class="fas fa-plus-circle text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                <span>New Submission</span>
            </a>
        </div>

        <!-- Global Validation Errors (e.g., OR Upload failures) -->
        @if($errors->any())
            <div class="mb-8 p-4 bg-red-50 border-l-4 border-[#8B0000] rounded-r-xl shadow-sm animate-[fadeIn_0.3s_ease-out]">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-[#8B0000] mt-0.5 mr-3 text-lg"></i>
                    <div>
                        <h3 class="text-[#8B0000] font-bold">Action Failed</h3>
                        <ul class="text-red-700 text-sm list-disc list-inside mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if($titles->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-file-upload text-4xl text-[#8B0000]"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-3">No Submission Yet</h2>
                <p class="text-slate-500 max-w-md mx-auto mb-8 text-lg">You haven't submitted a research protocol yet. Start
                    your application to get your ethics review underway.</p>
                <a href="{{ route('submit') }}"
                    class="inline-flex items-center gap-3 bg-[#8B0000] text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-plus-circle"></i>
                    <span>Start New Submission</span>
                </a>
            </div>
        @else
            <div class="space-y-12"> <!-- Added space between cards -->
                @foreach($titles as $title)
                    @php
                        // Tracker Logic
                        $steps = [
                            1 => ['label' => 'Submission', 'icon' => 'fa-paper-plane'],
                            2 => ['label' => 'Review', 'icon' => 'fa-search'],
                            3 => ['label' => 'Revision', 'icon' => 'fa-edit'],
                            4 => ['label' => 'Deliberation', 'icon' => 'fa-clipboard-check'],
                            5 => ['label' => 'Certificate', 'icon' => 'fa-certificate'],
                        ];

                        $currentStep = 1;
                        $status = $title->Status ?? $title->status ?? '';
                        $checkStatus = trim($status);

                        if (in_array($checkStatus, ['Incomplete', 'Pending', 'Submitted', 'Rejected'])) {
                            $currentStep = 1;
                        } elseif (str_contains($checkStatus, 'Complete - Awaiting Hardcopy') || str_contains($checkStatus, 'Hardcopy Received') || str_contains($checkStatus, 'For Initial Review') || str_contains($checkStatus, 'Under Review')) {
                            $currentStep = 2; // Review Tracker
                        } elseif (stripos($checkStatus, 'Approved') !== false || stripos($checkStatus, 'Certification') !== false) {
                            $currentStep = 5;
                        } elseif ($checkStatus === 'Panel Deliberation') {
                            $currentStep = 4;
                        } elseif (
                            in_array($checkStatus, [
                                'Waiting for Revision',
                                'Modifications Required',
                                'Revision Submitted',
                                'Reviewing Revisions',
                                'Submission of Revisions / Resubmission',
                                'Returned',
                                'Disapproved'
                            ])
                        ) {
                            $currentStep = 3;
                        } elseif (
                            in_array($checkStatus, [
                                'For Initial Review',
                                'Hardcopy Received - For Initial Review'
                            ])
                        ) {
                            $currentStep = 2;
                        }

                        $statusColor = match ($title->Status ?? $title->status) {
                            'Approved' => 'green',
                            'Returned', 'Waiting for Revision', 'Modifications Required', 'Incomplete' => 'orange',
                            'Panel Deliberation' => 'blue',
                            'Disapproved', 'Rejected' => 'red',
                            default => 'orange',
                        };
                        $statusIcon = match ($title->Status ?? $title->status) {
                            'Approved' => 'fa-check-circle',
                            'Returned', 'Waiting for Revision', 'Modifications Required' => 'fa-edit',
                            'Incomplete' => 'fa-exclamation-circle',
                            'Panel Deliberation' => 'fa-users',
                            'Disapproved', 'Rejected' => 'fa-times-circle',
                            default => 'fa-clock',
                        };
                        $hasLetter = $title->files->where('filetype', 'Result of Review (Admin Generated)')->isNotEmpty();
                    @endphp

                    <!-- ========================================== -->
                    <!-- MOBILE CARD (Compact Design)               -->
                    <!-- ========================================== -->
                    <div class="md:hidden bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden relative group mb-6">
                        <div class="p-5 relative z-10">
                            <!-- Mobile Header -->
                            <div class="mb-4">
                                <div class="flex flex-col gap-2 mb-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-{{ $statusColor }}-50 text-{{ $statusColor }}-600 border border-{{ $statusColor }}-100 flex items-center gap-1.5">
                                            <i class="fas {{ $statusIcon }} text-[10px]"></i>
                                            {{ $title->Status ?? $title->status ?? 'Pending' }}
                                        </span>

                                        @if($title->Review_Type && $title->Review_Type !== 'N/A')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center gap-1.5">
                                                <i class="fas fa-clipboard-list text-[10px]"></i>
                                                {{ $title->Review_Type }}
                                            </span>
                                        @endif
                                    </div>
                                    <h2 class="text-xl font-extrabold text-slate-900 leading-snug">
                                        {{ $title->Study_Protocol_title }}
                                    </h2>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-slate-400 text-xs font-medium">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $title->created_at->format('M d, Y') }}
                                        </span>
                                        <div class="flex items-center gap-3">
                                            <button onclick="document.getElementById('info-modal-{{ $title->id }}').showModal()"
                                                class="text-slate-500 hover:text-[#8B0000] font-semibold text-xs transition-colors flex items-center gap-1">
                                                <i class="fas fa-info-circle"></i> Details
                                            </button>
                                            <button onclick="document.getElementById('log-modal-{{ $title->id }}').showModal()"
                                                class="text-indigo-500 hover:text-indigo-700 font-semibold text-xs transition-colors flex items-center gap-1">
                                                <i class="fas fa-history"></i> Logs
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mobile Actions (Grid) -->
                                <div class="grid grid-cols-2 gap-3 mt-4">
                                    <a href="{{ route('manage.files', $title->id) }}"
                                        class="col-span-2 w-full py-2.5 px-6 bg-[#8B0000] text-white rounded-xl font-bold text-center shadow-md shadow-red-900/10 hover:bg-red-800 transition-all flex items-center justify-center gap-2 text-sm">
                                        <i class="fas {{ $checkStatus === 'Incomplete' ? 'fa-file-upload' : 'fa-folder-open' }}"></i> 
                                        {{ $checkStatus === 'Incomplete' ? 'Add Files' : 'Manage Files' }}
                                    </a>

                                    @if($title->Official_Receipt_Number && !$title->is_or_verified)
                                        <div class="col-span-2 w-full py-2.5 px-6 bg-slate-50 text-slate-500 rounded-xl font-bold flex items-center justify-center gap-2 text-sm border border-slate-200 cursor-not-allowed shadow-inner">
                                            <i class="fas fa-hourglass-half text-indigo-400 animate-pulse"></i> OR Pending Verification
                                        </div>
                                    @elseif($title->is_or_verified)
                                        <div class="col-span-2 w-full py-2.5 px-6 bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 rounded-xl font-bold flex items-center justify-center gap-2 text-sm border border-emerald-200 shadow-sm cursor-default">
                                            <i class="fas fa-check-circle text-emerald-500"></i> OR Verified
                                        </div>
                                    @endif

                                    @if($title->status === 'Approved')
                                        <div class="col-span-2 w-full py-2.5 px-6 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold flex items-center justify-center gap-2 text-sm">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </div>
                                    @endif

                                    @if($title->status === 'Incomplete')
                                        <div class="col-span-2 px-3 py-2 text-xs text-orange-600 font-bold text-center bg-orange-50 rounded-lg border border-orange-100">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Check Requirements
                                        </div>
                                    @endif
                                    
                                     @if($title->status === 'Returned' || $title->status === 'Waiting for Revision')
                                        <button class="col-span-2 w-full py-2.5 px-6 bg-white border border-orange-200 text-orange-600 rounded-xl font-bold hover:bg-orange-50 transition-colors flex items-center justify-center gap-2 text-sm">
                                            <i class="fas fa-comment-alt"></i> Feedback
                                        </button>
                                    @endif

                                    {{-- CV Alert (Mobile) --}}
                                    @if($title->cv_verification_status === 'Invalid')
                                        <div class="col-span-2 px-3 py-2 bg-red-50 border border-red-200 rounded-lg">
                                            <p class="text-xs font-bold text-red-700 flex items-center gap-1 mb-1.5"><i class="fas fa-id-card"></i> CV Mismatch</p>
                                            <button onclick="document.getElementById('cv-correct-modal-{{ $title->id }}').showModal()"
                                                class="w-full py-1.5 text-xs font-bold bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center gap-1.5">
                                                <i class="fas fa-edit"></i> Correct Project Type
                                            </button>
                                        </div>
                                    @elseif($title->cv_verification_status === 'Valid')
                                        <div class="col-span-2 w-full py-2.5 px-4 bg-violet-50 border border-violet-200 text-violet-700 rounded-xl font-bold flex items-center justify-center gap-2 text-xs">
                                            <i class="fas fa-check-circle text-violet-500"></i> CV Verified
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Mobile Horizontal Tracker -->
                            <div class="pt-4 border-t border-slate-100 relative mt-4">
                                <!-- First Row (3 steps: Submission, Review, Revision) -->
                                <div class="relative px-1 mb-6">
                                    <!-- Background Line -->
                                    <div class="absolute top-3 left-4 right-4 h-0.5 bg-slate-100 rounded-full z-0"></div>
                                    <!-- Progress Line -->
                                    <div class="absolute top-3 left-4 h-0.5 bg-[#8B0000] rounded-full z-0 transition-all duration-1000 ease-out"
                                         style="width: calc({{ min($currentStep, 3) === 1 ? 0 : ($currentStep >= 3 ? 100 : (($currentStep - 1) / 2 * 100)) }}%)"></div>

                                    <!-- Steps -->
                                    <div class="flex justify-between relative z-10 w-full px-2">
                                        @foreach([1, 2, 3] as $step)
                                            @php 
                                                $data = $steps[$step];
                                                $isActive = $step <= $currentStep; 
                                                $isCurrent = $step === $currentStep; 
                                            @endphp
                                            <div class="flex flex-col items-center gap-0.5 group">
                                                <div class="w-6 h-6 shrink-0 rounded-full flex items-center justify-center border-2 transition-all duration-300 bg-white relative z-10 {{ $isActive ? 'border-[#8B0000] text-[#8B0000]' : 'border-slate-200 text-slate-300' }} {{ $isCurrent ? 'scale-105 shadow-md shadow-red-900/20 ring-1 ring-red-50' : '' }}">
                                                    <i class="fas {{ $data['icon'] }} {{ $isActive ? '' : 'text-slate-300' }} text-[7px]"></i>
                                                </div>
                                                <span class="block text-[7px] font-bold uppercase tracking-tight transition-colors duration-300 text-center {{ $isActive ? 'text-[#8B0000]' : 'text-slate-400' }} leading-tight max-w-[45px] line-clamp-2">
                                                    {{ $data['label'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Second Row (2 steps: Deliberation, Certificate) -->
                                <div class="relative px-1">
                                    <!-- Background Line -->
                                    <div class="absolute top-3 left-4 right-4 h-0.5 bg-slate-100 rounded-full z-0"></div>
                                    <!-- Progress Line -->
                                    <div class="absolute top-3 left-4 h-0.5 bg-[#8B0000] rounded-full z-0 transition-all duration-1000 ease-out"
                                         style="width: calc({{ $currentStep <= 3 ? 0 : (($currentStep - 3) / 2 * 100) }}%)"></div>

                                    <!-- Steps -->
                                    <div class="flex justify-center gap-12 relative z-10 w-full px-2">
                                        @foreach([4, 5] as $step)
                                            @php 
                                                $data = $steps[$step];
                                                $isActive = $step <= $currentStep; 
                                                $isCurrent = $step === $currentStep; 
                                            @endphp
                                            <div class="flex flex-col items-center gap-0.5 group">
                                                <div class="w-6 h-6 shrink-0 rounded-full flex items-center justify-center border-2 transition-all duration-300 bg-white relative z-10 {{ $isActive ? 'border-[#8B0000] text-[#8B0000]' : 'border-slate-200 text-slate-300' }} {{ $isCurrent ? 'scale-105 shadow-md shadow-red-900/20 ring-1 ring-red-50' : '' }}">
                                                    <i class="fas {{ $data['icon'] }} {{ $isActive ? '' : 'text-slate-300' }} text-[7px]"></i>
                                                </div>
                                                <span class="block text-[7px] font-bold uppercase tracking-tight transition-colors duration-300 text-center {{ $isActive ? 'text-[#8B0000]' : 'text-slate-400' }} leading-tight max-w-[45px] line-clamp-2">
                                                    {{ $data['label'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- DESKTOP CARD (Spacious Original Design)    -->
                    <!-- ========================================== -->
                    <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden relative group">
                        <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                            <i class="fas {{ $statusIcon }} text-9xl text-{{ $statusColor }}-600"></i>
                        </div>

                        <div class="p-8 relative z-10">
                            <!-- Header & Actions -->
                            <div class="flex items-center justify-between gap-6 mb-8">
                                <div>
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="flex items-center gap-2">
                                            <span class="px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 flex items-center gap-2">
                                                <i class="fas {{ $statusIcon }}"></i>
                                                {{ $title->Status ?? $title->status ?? 'Pending Review' }}
                                            </span>
                                            @if($title->Review_Type && $title->Review_Type !== 'N/A')
                                                <span class="px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700 flex items-center gap-2">
                                                    <i class="fas fa-clipboard-list"></i>
                                                    {{ $title->Review_Type }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-slate-400 text-sm font-medium ml-auto">
                                            <i class="far fa-calendar-alt mr-1"></i> Submitted on {{ $title->created_at->format('F d, Y') }}
                                        </span>
                                    </div>
                                    <h2 class="text-3xl font-extrabold text-slate-900 leading-tight max-w-3xl mb-2 pl-1">
                                        {{ $title->Study_Protocol_title }}
                                    </h2>
                                    <div class="flex items-center gap-4 pl-1 mt-3">
                                        <button onclick="document.getElementById('info-modal-{{ $title->id }}').showModal()" class="text-[#8B0000] font-bold text-sm hover:underline flex items-center gap-1">
                                            <i class="fas fa-info-circle"></i> View Protocol Details
                                        </button>
                                        <span class="text-slate-300">|</span>
                                        <button onclick="document.getElementById('log-modal-{{ $title->id }}').showModal()" class="text-indigo-600 font-bold text-sm hover:underline flex items-center gap-1">
                                            <i class="fas fa-history"></i> View Activity Log
                                        </button>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 min-w-[200px]">
                                    <a href="{{ route('manage.files', $title->id) }}" class="w-full py-3 px-6 bg-[#8B0000] text-white rounded-xl font-bold text-center shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                        <i class="fas {{ $checkStatus === 'Incomplete' ? 'fa-file-upload' : 'fa-folder-open' }}"></i> 
                                        {{ $checkStatus === 'Incomplete' ? 'Add Files' : 'Manage Files' }}
                                    </a>

                                    @if($title->Official_Receipt_Number && !$title->is_or_verified)
                                        <div class="w-full py-3 px-6 bg-slate-50 text-slate-500 rounded-xl font-bold flex items-center justify-center gap-2 border border-slate-200 cursor-not-allowed shadow-inner">
                                            <i class="fas fa-hourglass-half text-indigo-400 animate-pulse"></i> OR Pending Verification
                                        </div>
                                    @elseif($title->is_or_verified)
                                        <div class="w-full py-3 px-6 bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 rounded-xl font-bold flex items-center justify-center gap-2 border border-emerald-200 shadow-sm cursor-default">
                                            <i class="fas fa-check-circle text-emerald-500"></i> OR Verified
                                        </div>
                                    @endif
                                    @if($title->status === 'Approved')
                                        <div class="w-full py-3 px-6 bg-green-50 border-2 border-green-500 text-green-700 rounded-xl font-bold flex items-center justify-center gap-2">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </div>
                                    @endif
                                    @if($title->status === 'Returned' || $title->status === 'Waiting for Revision')
                                        <button class="w-full py-3 px-6 bg-white border-2 border-orange-100 text-orange-700 rounded-xl font-bold hover:bg-orange-50 transition-colors flex items-center justify-center gap-2">
                                            <i class="fas fa-comment-alt"></i> View Feedback
                                        </button>
                                    @endif

                                    {{-- CV Classification Invalid Alert --}}
                                    @if($title->cv_verification_status === 'Invalid')
                                        <div class="w-full px-4 py-3 bg-red-50 border-2 border-red-200 rounded-xl">
                                            <div class="flex items-center gap-2 mb-2">
                                                <i class="fas fa-id-card text-red-500 text-sm"></i>
                                                <span class="text-sm font-bold text-red-800">CV Mismatch Detected</span>
                                            </div>
                                            <p class="text-xs text-red-700 mb-3 leading-relaxed">{{ Str::limit($title->cv_rejection_remarks, 80) }}</p>
                                            <button onclick="document.getElementById('cv-correct-modal-{{ $title->id }}').showModal()"
                                                class="w-full py-2 px-3 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                                                <i class="fas fa-edit"></i> Correct Project Type
                                            </button>
                                        </div>
                                    @elseif($title->cv_verification_status === 'Valid')
                                        <div class="w-full py-3 px-6 bg-gradient-to-r from-violet-50 to-violet-100 text-violet-700 rounded-xl font-bold flex items-center justify-center gap-2 border border-violet-200 shadow-sm cursor-default">
                                            <i class="fas fa-check-circle text-violet-500"></i> CV Verified
                                        </div>
                                    @endif
                            </div>

                            <!-- Desktop Tracker -->
                            <div class="pt-8 border-t border-slate-100 relative mt-8">
                                <div class="relative px-8">
                                    <!-- Progress Line -->
                                    <div class="absolute top-[1.125rem] left-[3.25rem] right-[3.25rem] h-1.5 rounded-full z-0 overflow-hidden shadow-sm" style="background-color: #e2e8f0;">
                                        <div class="absolute top-0 left-0 h-full bg-[#8B0000] rounded-full transition-all duration-1000 ease-out" 
                                             style="width: {{ ($currentStep - 1) / (count($steps) - 1) * 100 }}%"></div>
                                    </div>
                                    
                                    <!-- Steps -->
                                    <div class="flex justify-between relative z-10 w-full">
                                        @foreach($steps as $step => $data)
                                            @php $isActive = $step <= $currentStep; $isCurrent = $step === $currentStep; @endphp
                                            <div class="relative flex flex-col items-center group">
                                                <!-- Icon Bubble -->
                                                <div class="w-10 h-10 shrink-0 rounded-full flex items-center justify-center border-[3px] transition-all duration-300 bg-white relative z-20 
                                                    {{ $isActive ? 'border-[#8B0000] text-[#8B0000]' : 'border-slate-200 text-slate-300' }} 
                                                    {{ $isCurrent ? 'scale-110 shadow-lg shadow-red-900/20 ring-4 ring-red-50' : '' }}">
                                                    <i class="fas {{ $data['icon'] }} {{ $isActive ? '' : 'text-slate-300' }} text-sm"></i>
                                                </div>
                                                
                                                <!-- Label -->
                                                <div class="absolute top-full mt-3 text-center pointer-events-none z-20">
                                                    <span class="block text-[10px] font-bold uppercase tracking-wide transition-colors duration-300 {{ $isActive ? 'text-[#8B0000]' : 'text-slate-400' }} whitespace-nowrap">
                                                        {{ $data['label'] }}
                                                    </span>
                                                    @if($isCurrent)
                                                        <span class="inline-block text-[8px] font-bold text-[#8B0000] bg-red-50 px-2 py-0.5 rounded-full mt-0.5 whitespace-nowrap">Current</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Bottom Spacer to accommodate labels -->
                                    <div class="h-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                            <!-- Info Modal -->
                            <dialog id="info-modal-{{ $title->id }}"
                                class="m-auto rounded-2xl p-0 backdrop:bg-slate-900/50 w-full max-w-md open:animate-[fadeIn_0.2s_ease-out]">
                                <div class="bg-white p-6">
                                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                                        <h3 class="font-bold text-lg text-slate-800">Protocol Details</h3>
                                        <button onclick="document.getElementById('info-modal-{{ $title->id }}').close()"
                                            class="text-slate-400 hover:text-slate-600 transition-colors">
                                            <i class="fas fa-times text-xl"></i>
                                        </button>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label
                                                class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Category</label>
                                            <p class="text-slate-800 font-bold bg-slate-50 p-3 rounded-lg">
                                                {{ $title->Research_Category }}
                                            </p>
                                        </div>
                                        <div>
                                            <label
                                                class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Adviser</label>
                                            <p class="text-slate-800 font-bold bg-slate-50 p-3 rounded-lg">{{ $title->Adviser }}</p>
                                        </div>
                                        <div>
                                            <label
                                                class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Reference
                                                ID</label>
                                            <p class="text-slate-600 font-mono bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                #{{ str_pad($title->id, 6, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Assigned Reviewers</label>
                                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 flex flex-col gap-2">
                                                @if($title->assigned_reviewers && count($title->assigned_reviewers) > 0)
                                                    @foreach($title->assigned_reviewers as $reviewerId)
                                                        @php
                                                            $reviewerUser = \App\Models\User::find($reviewerId);
                                                        @endphp
                                                        @if($reviewerUser)
                                                            <span class="text-slate-800 font-medium text-sm flex items-center gap-2">
                                                                <i class="fas fa-user-check text-green-600 opacity-70"></i>
                                                                {{ $reviewerUser->first_name }} {{ $reviewerUser->last_name }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span class="text-slate-500 text-sm italic">None Assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                            <!-- Admin Actions Section -->
                                            <div class="mt-8 pt-6 border-t border-slate-100">
                                                <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                                                    <i class="fas fa-money-check-alt text-emerald-600"></i> Revenue Tracking
                                                </h4>
                                                
                                                <div class="rounded-xl p-4 border 
                                                    {{ $title->is_or_verified ? 'bg-emerald-50/50 border-emerald-200' : 
                                                      ($title->Official_Receipt_Number ? 'bg-indigo-50/50 border-indigo-200' : 'bg-orange-50/50 border-orange-200') }}">
                                                    
                                                    @if(!$title->Official_Receipt_Number)
                                                        <!-- State 1: Awaiting Researcher -->
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center gap-3">
                                                                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                                                                    <i class="fas fa-hourglass-half"></i>
                                                                </div>
                                                                <div>
                                                                    <h5 class="text-sm font-bold text-orange-800">Awaiting Upload</h5>
                                                                    <p class="text-xs text-orange-600 mt-0.5">Researcher has not submitted a receipt yet.</p>
                                                                </div>
                                                            </div>
                                                            <span class="px-2.5 py-1 bg-orange-100 text-orange-700 rounded-md text-[10px] font-bold uppercase shrink-0">Pending</span>
                                                        </div>
                                                    
                                                    @elseif($title->Official_Receipt_Number && !$title->is_or_verified)
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex flex-col">
                                                                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-1">Receipt Submitted</span>
                                                                <p class="text-lg font-mono font-bold text-indigo-900 bg-white px-3 py-1.5 rounded border border-indigo-100 inline-block w-max">#{{ $title->Official_Receipt_Number }}</p>
                                                            </div>
                                                            <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-md text-[10px] font-bold uppercase shrink-0 flex items-center gap-1">
                                                                <i class="fas fa-eye"></i> Pending Verification
                                                            </span>
                                                        </div>
                                                        <div class="mt-3 pt-3 border-t border-indigo-100/50">
                                                            <p class="text-xs text-indigo-600 font-medium"><i class="fas fa-info-circle mr-1"></i> Receipt is awaiting review in the Revenue Logs.</p>
                                                        </div>

                                                    @else
                                                        <!-- State 3: Verified -->
                                                        <div class="flex items-center justify-between mb-2">
                                                            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Official Receipt Number</span>
                                                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-[10px] font-bold uppercase flex items-center gap-1">
                                                                <i class="fas fa-shield-check"></i> Verified
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-lg font-mono font-bold text-emerald-800 bg-white px-3 py-2 rounded border border-emerald-100">#{{ $title->Official_Receipt_Number }}</p>
                                                            @if($title->or_file_path)
                                                                <a href="{{ asset($title->or_file_path) }}" target="_blank" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 hover:underline flex items-center gap-1">
                                                                    <i class="fas fa-image"></i> View File
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </dialog>

                            <!-- Activity Log Modal -->
                            <dialog id="log-modal-{{ $title->id }}"
                                class="m-auto rounded-3xl p-0 backdrop:bg-slate-900/60 w-full max-w-2xl open:animate-[fadeIn_0.2s_ease-out] border border-slate-200 shadow-2xl overflow-hidden">
                                
                                <!-- Static Header -->
                                <div class="px-5 py-5 md:px-8 md:py-6 border-b border-slate-100 bg-white flex items-center justify-between sticky top-0 z-20">
                                    <div class="pr-4">
                                        <h3 class="text-xl md:text-2xl font-bold text-slate-900 flex items-center gap-2 md:gap-3">
                                            <i class="fas fa-history text-indigo-600"></i>
                                            Activity Log
                                        </h3>
                                        <p class="text-xs md:text-sm text-slate-500 mt-1 max-w-[200px] md:max-w-md truncate" title="{{ $title->Study_Protocol_title }}">
                                            Tracking: <span class="font-semibold text-slate-700">{{ $title->Study_Protocol_title }}</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3 md:gap-4 shrink-0">
                                        <div class="px-3 py-1.5 md:px-4 md:py-2 bg-indigo-50 text-indigo-700 rounded-lg text-xs md:text-sm font-bold border border-indigo-100 hidden sm:flex items-center gap-2">
                                            <i class="fas fa-list-ul"></i> {{ $title->titleLogs->count() }} Records
                                        </div>
                                        <button onclick="document.getElementById('log-modal-{{ $title->id }}').close()"
                                            class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors flex items-center justify-center border border-slate-200 shrink-0">
                                            <i class="fas fa-times text-base md:text-lg"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Scrollable Content -->
                                <div class="p-5 md:p-8 bg-slate-50/50 max-h-[70vh] overflow-y-auto">
                                    @if($title->titleLogs->isEmpty())
                                        <div class="text-center py-8 md:py-12 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                            <div class="w-14 h-14 md:w-20 md:h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 md:mb-5 border border-slate-100">
                                                <i class="fas fa-clipboard-list text-slate-300 text-2xl md:text-3xl"></i>
                                            </div>
                                            <h4 class="text-slate-800 font-bold text-base md:text-lg mb-1 md:mb-2">No Activity Yet</h4>
                                            <p class="text-slate-500 text-xs md:text-sm max-w-sm mx-auto px-4">Action logs will appear here once the submission is processed or modified.</p>
                                        </div>
                                    @else
                                        <div class="relative before:absolute before:inset-y-0 before:left-3 md:before:left-[1.35rem] before:w-0.5 before:bg-slate-200/60 space-y-6 md:space-y-8 pl-1 pb-4">
                                            @foreach($title->titleLogs as $log)
                                                @php
                                                    $actionLower = strtolower($log->action);
                                                    $isCreation = str_contains($actionLower, 'created') || str_contains($actionLower, 'submitted');
                                                    $isStatus = str_contains($actionLower, 'status');
                                                    
                                                    if($isCreation) {
                                                        $icon = 'fa-plus';
                                                        $color = 'text-emerald-500';
                                                        $bg = 'bg-emerald-50';
                                                        $border = 'border-emerald-200';
                                                        $cardBorder = 'border-l-4 border-l-emerald-500';
                                                    } elseif($isStatus) {
                                                        $icon = 'fa-sync-alt';
                                                        $color = 'text-blue-500';
                                                        $bg = 'bg-blue-50';
                                                        $border = 'border-blue-200';
                                                        $cardBorder = 'border-l-4 border-l-blue-500';
                                                    } else {
                                                        $icon = 'fa-pen';
                                                        $color = 'text-orange-500';
                                                        $bg = 'bg-orange-50';
                                                        $border = 'border-orange-200';
                                                        $cardBorder = 'border-l-4 border-l-orange-500';
                                                    }
                                                @endphp

                                                <div class="relative flex items-start group">
                                                    <!-- Timeline Node -->
                                                    <div class="absolute left-0 w-6 h-6 ml-0 md:w-12 md:h-12 md:-ml-2 rounded-full border-2 md:border-4 border-white flex items-center justify-center {{ $bg }} {{ $color }} z-10 shadow-sm transition-transform group-hover:scale-110 group-hover:shadow-md">
                                                        <i class="fas {{ $icon }} text-[10px] md:text-base"></i>
                                                    </div>
                                                    
                                                    <!-- Content Box -->
                                                    <div class="ml-9 md:ml-16 focus:outline-none w-full">
                                                        <div class="bg-white border {{ $cardBorder }} border-y-slate-100 border-r-slate-100 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-sm transition-all hover:shadow-md">
                                                            <div class="flex flex-col gap-2 mb-3 md:mb-4">
                                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 md:gap-4">
                                                                    <h4 class="text-slate-800 font-bold text-sm md:text-lg m-0">{{ $log->action }}</h4>
                                                                    <span class="text-[10px] md:text-xs font-bold text-slate-500 flex items-center gap-1.5 md:gap-2 bg-slate-50 px-2 py-1.5 md:px-3 md:py-2 rounded-lg border border-slate-100 self-start sm:self-auto whitespace-nowrap">
                                                                        <i class="far fa-clock text-slate-400"></i>
                                                                        {{ $log->created_at->format('M d, Y • h:i A') }}
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    @if($log->user)
                                                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 md:px-3 md:py-1.5 rounded-lg text-[9px] md:text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                                                            <i class="fas fa-user-circle"></i>
                                                                            {{ $log->user->first_name }} {{ $log->user->last_name }}
                                                                        </span>
                                                                    @else
                                                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 md:px-3 md:py-1.5 rounded-lg text-[9px] md:text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                                                            <i class="fas fa-robot text-slate-400"></i> System
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <p class="text-slate-600 text-xs md:text-[15px] leading-relaxed m-0 font-medium">
                                                                {{ $log->description }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </dialog>


                    {{-- CV Correction Modal (per title) --}}
                    @if($title->cv_verification_status === 'Invalid')
                    @php
                        $cvFunded = \App\Models\ResearchCategory::where('classification', 'Funded Research')->get();
                        $cvCourse = \App\Models\ResearchCategory::where('classification', 'Course Requirement')->get();
                    @endphp
                    <dialog id="cv-correct-modal-{{ $title->id }}"
                        class="m-auto rounded-2xl p-0 backdrop:bg-slate-900/60 w-full max-w-lg border border-slate-200 shadow-2xl overflow-hidden"
                        x-data="{ cvProjectType: '{{ $title->project_type }}', cvSubType: '' }">
                        <form method="POST" action="{{ route('researcher.cv.correct', $title->id) }}">
                            @csrf
                            <div class="bg-[#0f172a] px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                                <h3 class="text-base font-bold text-white flex items-center gap-2">
                                    <i class="fas fa-edit text-amber-400"></i> Correct Project Type
                                </h3>
                                <button type="button"
                                    onclick="document.getElementById('cv-correct-modal-{{ $title->id }}').close()"
                                    class="text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="p-6 bg-white space-y-5">
                                <div class="p-3 bg-red-50 border border-red-200 rounded-xl">
                                    <p class="text-xs text-red-800 font-bold flex items-center gap-1.5 mb-1"><i class="fas fa-exclamation-triangle"></i> Admin Remarks</p>
                                    <p class="text-xs text-red-700 leading-relaxed">{{ $title->cv_rejection_remarks }}</p>
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Project Type <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" @click="cvProjectType = 'Funded Research'; cvSubType = ''"
                                            :class="cvProjectType === 'Funded Research' ? 'ring-2 ring-[#8B0000] bg-red-50 text-red-800' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
                                            class="py-3 rounded-xl text-xs font-bold border border-slate-200 flex flex-col items-center gap-1.5 transition-all">
                                            <i class="fas fa-money-bill-wave text-base"></i> Funded Research
                                        </button>
                                        <button type="button" @click="cvProjectType = 'Course Requirement'; cvSubType = ''"
                                            :class="cvProjectType === 'Course Requirement' ? 'ring-2 ring-amber-500 bg-amber-50 text-amber-800' : 'bg-slate-50 text-slate-600 hover:bg-slate-100'"
                                            class="py-3 rounded-xl text-xs font-bold border border-slate-200 flex flex-col items-center gap-1.5 transition-all">
                                            <i class="fas fa-graduation-cap text-base"></i> Course Requirement
                                        </button>
                                    </div>
                                    <input type="hidden" name="project_type" :value="cvProjectType">
                                </div>

                                <div x-show="cvProjectType === 'Funded Research'" x-transition style="display:none;">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Specific Type <span class="text-red-500">*</span></label>
                                    <div class="space-y-1.5">
                                        @foreach($cvFunded as $cat)
                                        <button type="button" @click="cvSubType = '{{ $cat->name }}'"
                                            :class="cvSubType === '{{ $cat->name }}' ? 'ring-2 ring-[#8B0000] bg-red-50 font-bold' : 'bg-slate-50 hover:bg-slate-100'"
                                            class="w-full text-left px-3 py-2 rounded-lg text-sm border border-slate-200 transition-all">
                                            {{ $cat->name }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>

                                <div x-show="cvProjectType === 'Course Requirement'" x-transition style="display:none;">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Specific Type <span class="text-red-500">*</span></label>
                                    <div class="space-y-1.5">
                                        @foreach($cvCourse as $cat)
                                        <button type="button" @click="cvSubType = '{{ $cat->name }}'"
                                            :class="cvSubType === '{{ $cat->name }}' ? 'ring-2 ring-amber-500 bg-amber-50 font-bold' : 'bg-slate-50 hover:bg-slate-100'"
                                            class="w-full text-left px-3 py-2 rounded-lg text-sm border border-slate-200 transition-all">
                                            {{ $cat->name }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>

                                <input type="hidden" name="sub_type" :value="cvSubType">

                                <div x-show="cvProjectType === 'Course Requirement'" x-transition style="display:none;">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Adviser Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="Adviser"
                                        :required="cvProjectType === 'Course Requirement'"
                                        value="{{ $title->Adviser }}"
                                        class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                                        placeholder="Enter your adviser's full name">
                                </div>

                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button"
                                        onclick="document.getElementById('cv-correct-modal-{{ $title->id }}').close()"
                                        class="px-4 py-2 text-sm bg-slate-100 text-slate-700 font-bold rounded-lg hover:bg-slate-200 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-5 py-2 text-sm bg-[#8B0000] text-white font-bold rounded-lg hover:bg-red-800 transition-colors shadow-sm shadow-red-900/20 flex items-center gap-2">
                                        <i class="fas fa-save text-xs"></i> Save Correction
                                    </button>
                                </div>
                            </div>
                        </form>
                    </dialog>
                    @endif

                 @endforeach

                    <!-- Resources & Assistance Section (Moved to Bottom) -->
                    <div class="pt-12 border-t border-slate-200">
                        <h3 class="text-slate-400 font-bold text-sm uppercase tracking-wider mb-6 text-center">Resources &
                            Assistance</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <a href="{{ route('instructions') }}"
                                class="flex items-center gap-4 p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group">
                                <div
                                    class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <span
                                        class="block font-bold text-slate-800 group-hover:text-blue-700 transition-colors">Guidelines</span>
                                    <span class="text-sm text-slate-500">Read submission rules</span>
                                </div>
                                <i
                                    class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                            </a>

                            <a href="{{ route('resources') }}"
                                class="flex items-center gap-4 p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all group">
                                <div
                                    class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div>
                                    <span
                                        class="block font-bold text-slate-800 group-hover:text-indigo-700 transition-colors">Templates</span>
                                    <span class="text-sm text-slate-500">Download official forms</span>
                                </div>
                                <i
                                    class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $titles->links() }}
                </div>

        @endif
    </div>
    </div>
</x-user_layout>