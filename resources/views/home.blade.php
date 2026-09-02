<x-user_layout>
    <x-skeleton-loader />
    
    <div id="page-content" style="display: none;" class="max-w-5xl mx-auto animate-[fadeInUp_0.5s_ease-out]">

        <!-- Welcome Section & Primary Action (Adapted for Mobile & Desktop) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 md:mb-8 pt-3 md:pt-5">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 font-heading tracking-tight leading-tight">
                    Welcome back, <span class="text-brand-primary">{{ explode(' ', Auth::user()->first_name)[0] }}</span>!
                </h1>
                <p class="text-slate-500 mt-1 md:mt-2 text-sm md:text-base">Here is the status of your research submissions.</p>
            </div>
            
            <a href="{{ route('submit') }}" class="group inline-flex items-center justify-center gap-2 bg-brand-primary text-white px-5 py-3 sm:py-2.5 rounded-xl font-bold shadow-md shadow-brand-primary/20 hover:bg-brand-secondary hover:shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all duration-200 shrink-0 text-sm md:text-base min-h-[44px]">
                <i class="fas fa-plus-circle text-base group-hover:rotate-90 transition-transform duration-300" aria-hidden="true"></i>
                <span>New Submission</span>
            </a>
        </div>

        <!-- Global Validation Errors (e.g., OR Upload failures) -->
        @if($errors->any())
            <div role="alert" class="mb-8 p-5 bg-red-50/90 border border-red-200/80 rounded-2xl shadow-xs animate-[fadeIn_0.3s_ease-out] motion-reduce:animate-none">
                <div class="flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-red-100 text-brand-primary flex items-center justify-center shrink-0" aria-hidden="true">
                        <i class="fas fa-exclamation-circle text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-red-900 font-bold text-sm">Action Failed</h3>
                        <ul class="text-red-800 text-xs sm:text-sm list-disc list-inside mt-1.5 space-y-1">
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
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-12 text-center">
                <div class="w-16 h-16 sm:w-24 sm:h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <i class="fas fa-file-upload text-2xl sm:text-4xl text-brand-primary" aria-hidden="true"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2 sm:mb-3">No Submission Yet</h2>
                <p class="text-slate-500 max-w-md mx-auto mb-6 sm:mb-8 text-sm sm:text-base leading-relaxed">You haven't submitted a research protocol yet. Start your application to get your ethics review underway.</p>
                <a href="{{ route('submit') }}"
                    class="inline-flex items-center justify-center gap-2.5 bg-brand-primary text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold text-sm sm:text-base shadow-md shadow-brand-primary/20 hover:bg-brand-secondary hover:shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all duration-200 min-h-[44px]">
                    <i class="fas fa-plus-circle" aria-hidden="true"></i>
                    <span>Start New Submission</span>
                </a>
            </div>
        @else
            <div class="space-y-6 md:space-y-8"> <!-- Calibrated vertical card rhythm -->
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
                        } elseif (str_contains($checkStatus, 'Complete - Awaiting Hardcopy') || str_contains($checkStatus, 'Hardcopy Received') || str_contains($checkStatus, 'For Initial Review') || str_contains($checkStatus, 'Under Review') || str_contains($checkStatus, 'Reviewer Assigned') || str_contains($checkStatus, 'Reviewed')) {
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
                    <!-- UNIFIED RESPONSIVE CARD                    -->
                    <!-- ========================================== -->
                    <div class="bg-gradient-to-br from-white to-slate-50/50 rounded-2xl shadow-xl shadow-slate-200/40 border border-slate-100/50 overflow-hidden relative group transition-all duration-300 hover:shadow-2xl hover:shadow-slate-200/50">
                        <div class="p-5 md:p-8 relative z-10">
                            <!-- Header & Actions -->
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-2 md:mb-4">
                                <!-- Left Content -->
                                <div class="flex-1">
                                    <h2 class="text-xl md:text-3xl font-extrabold text-slate-900 leading-snug md:leading-tight tracking-tight max-w-3xl mb-3 pl-1 mt-1 break-words hyphens-auto">
                                        {{ $title->Study_Protocol_title }}
                                    </h2>
                                    
                                    <div class="flex items-center flex-wrap gap-2 pl-1 mt-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 tracking-wide uppercase mr-2 shadow-sm border border-{{ $statusColor }}-100/50">
                                            <i class="fas {{ $statusIcon }}" aria-hidden="true"></i>
                                            {{ $title->Status ?? $title->status ?? 'Pending' }}
                                        </span>
                                        
                                        <span class="text-slate-500 font-medium text-sm flex items-center gap-1.5 mr-3" title="Date Submitted">
                                            <i class="far fa-calendar-alt text-slate-400" aria-hidden="true"></i> {{ $title->created_at->format('M d, Y') }}
                                        </span>
                                        <button type="button" aria-haspopup="dialog" aria-expanded="false" onclick="document.getElementById('info-modal-{{ $title->id }}').showModal()" class="text-slate-600 hover:text-slate-900 hover:bg-slate-100 bg-transparent px-3 py-1.5 rounded-lg font-bold text-sm flex items-center gap-2 transition-colors min-h-[36px] focus-visible:ring-2 focus-visible:ring-brand-primary focus:outline-none cursor-pointer">
                                            <i class="fas fa-info-circle text-slate-400" aria-hidden="true"></i> Details
                                        </button>
                                        <button type="button" aria-haspopup="dialog" aria-expanded="false" onclick="document.getElementById('log-modal-{{ $title->id }}').showModal()" class="text-slate-600 hover:text-indigo-700 hover:bg-slate-100 bg-transparent px-3 py-1.5 rounded-lg font-bold text-sm flex items-center gap-2 transition-colors min-h-[36px] focus-visible:ring-2 focus-visible:ring-brand-primary focus:outline-none cursor-pointer">
                                            <i class="fas fa-history text-slate-400" aria-hidden="true"></i> Logs
                                        </button>
                                    </div>
                                </div>

                                <!-- Right Actions -->
                                <div class="flex flex-col gap-3 w-full md:min-w-[180px] md:w-auto mt-4 md:mt-0 shrink-0">
                                    <a href="{{ route('manage.files', $title->id) }}" class="w-full py-2.5 px-5 bg-brand-primary text-white rounded-xl font-bold text-sm md:text-base text-center shadow-md shadow-brand-primary/20 hover:shadow-lg hover:bg-brand-secondary transition-all flex items-center justify-center gap-2 active:scale-95 border border-transparent">
                                        <i class="fas {{ $checkStatus === 'Incomplete' ? 'fa-file-upload' : 'fa-folder-open' }}"></i> 
                                        {{ $checkStatus === 'Incomplete' ? 'Add Files' : 'Manage Files' }}
                                    </a>

                                    @if($title->status === 'Incomplete')
                                        <div class="w-full px-3 py-2 text-xs md:text-sm text-orange-600 font-bold text-center bg-orange-50/80 backdrop-blur-sm rounded-lg border border-orange-100 shadow-sm">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Check Requirements
                                        </div>
                                    @endif

                                    @if($title->status === 'Returned' || $title->status === 'Waiting for Revision')
                                        <button class="w-full py-2.5 md:py-3 px-6 bg-white border border-orange-200 text-orange-700 rounded-xl font-bold hover:bg-orange-50 transition-colors flex items-center justify-center gap-2 text-sm md:text-base active:scale-95 shadow-sm hover:shadow-md">
                                            <i class="fas fa-comment-alt"></i> View Feedback
                                        </button>
                                    @endif

                                    {{-- CV Classification Invalid Alert (Actionable) --}}
                                    @if($title->cv_verification_status === 'Invalid')
                                        <div class="w-full px-4 py-3 bg-red-50/80 backdrop-blur-sm border border-red-200 rounded-xl shadow-sm">
                                            <div class="flex items-center gap-2 mb-2">
                                                <i class="fas fa-id-card text-red-500 text-sm"></i>
                                                <span class="text-sm font-bold text-red-800">CV Mismatch</span>
                                            </div>
                                            <p class="text-xs text-red-700 mb-3 leading-relaxed hidden md:block">{{ Str::limit($title->cv_rejection_remarks, 80) }}</p>
                                            <button onclick="document.getElementById('cv-correct-modal-{{ $title->id }}').showModal()"
                                                class="w-full py-2 px-3 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center gap-2 active:scale-95 shadow-md">
                                                <i class="fas fa-edit"></i> Correct Project
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Original Mobile Tracker (2 rows) -->
                            <!-- Mobile Tracker -->
                            <div class="md:hidden pt-2 relative mt-0">
                                <!-- First Row (3 steps: Submission, Review, Revision) -->
                                <div class="relative px-1 mb-6">
                                    <!-- Background Line -->
                                    <div class="absolute top-3 left-4 right-4 h-1.5 bg-slate-100 rounded-full z-0"></div>
                                    <!-- Progress Line -->
                                    <div class="absolute top-3 left-4 h-1.5 bg-brand-primary rounded-full z-0 transition-all duration-1000 ease-out"
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
                                                <div class="w-6 h-6 shrink-0 rounded-full flex items-center justify-center border-2 transition-all duration-300 bg-white relative z-10 {{ $isActive ? 'border-brand-primary text-brand-primary' : 'border-slate-200 text-slate-300' }} {{ $isCurrent ? 'scale-105 shadow-md shadow-brand-primary/20 ring-2 ring-brand-primary/10' : '' }}">
                                                    <i class="fas {{ $data['icon'] }} {{ $isActive ? '' : 'text-slate-300' }} text-[7px]" aria-hidden="true"></i>
                                                </div>
                                                <span class="block text-[8px] font-bold uppercase tracking-wide transition-colors duration-300 text-center {{ $isActive ? 'text-brand-primary' : 'text-slate-500' }} leading-tight max-w-[45px] line-clamp-2">
                                                    {{ $data['label'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Second Row (2 steps: Deliberation, Certificate) -->
                                <div class="relative px-1">
                                    <!-- Background Line -->
                                    <div class="absolute top-3 left-4 right-4 h-1.5 bg-slate-100 rounded-full z-0"></div>
                                    <!-- Progress Line -->
                                    <div class="absolute top-3 left-4 h-1.5 bg-brand-primary rounded-full z-0 transition-all duration-1000 ease-out"
                                         style="width: calc({{ $currentStep <= 3 ? 0 : (($currentStep - 3) / 2 * 100) }}%)"></div>

                                    <!-- Steps -->
                                    <div class="flex justify-between relative z-10 w-full px-8">
                                        @foreach([4, 5] as $step)
                                            @php 
                                                $data = $steps[$step];
                                                $isActive = $step <= $currentStep; 
                                                $isCurrent = $step === $currentStep; 
                                            @endphp
                                            <div class="flex flex-col items-center gap-0.5 group">
                                                <div class="w-6 h-6 shrink-0 rounded-full flex items-center justify-center border-2 transition-all duration-300 bg-white relative z-10 {{ $isActive ? 'border-brand-primary text-brand-primary' : 'border-slate-200 text-slate-300' }} {{ $isCurrent ? 'scale-105 shadow-md shadow-brand-primary/20 ring-2 ring-brand-primary/10' : '' }}">
                                                    <i class="fas {{ $data['icon'] }} {{ $isActive ? '' : 'text-slate-300' }} text-[7px]" aria-hidden="true"></i>
                                                </div>
                                                <span class="block text-[8px] font-bold uppercase tracking-wide transition-colors duration-300 text-center {{ $isActive ? 'text-brand-primary' : 'text-slate-500' }} leading-tight max-w-[45px] line-clamp-2">
                                                    {{ $data['label'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="h-4"></div>
                            </div>

                            <!-- Original Desktop Tracker -->
                            <div class="hidden md:block pt-2 relative mt-2">
                                <div class="relative px-8">
                                    <!-- Progress Line -->
                                    <div class="absolute top-[1.125rem] left-[3.25rem] right-[3.25rem] h-1.5 rounded-full z-0 overflow-hidden bg-slate-200/80">
                                        <div class="absolute top-0 left-0 h-full bg-brand-primary rounded-full transition-all duration-1000 ease-out" 
                                             style="width: {{ ($currentStep - 1) / (count($steps) - 1) * 100 }}%"></div>
                                    </div>
                                    
                                    <!-- Steps -->
                                    <div class="flex justify-between relative z-10 w-full">
                                        @foreach($steps as $step => $data)
                                            @php $isActive = $step <= $currentStep; $isCurrent = $step === $currentStep; @endphp
                                            <div class="relative flex flex-col items-center group">
                                                <!-- Icon Bubble -->
                                                <div class="w-10 h-10 shrink-0 rounded-full flex items-center justify-center border-[3px] transition-all duration-300 bg-white relative z-20 
                                                    {{ $isActive ? 'border-brand-primary text-brand-primary' : 'border-slate-200 text-slate-300' }} 
                                                    {{ $isCurrent ? 'scale-110 shadow-lg shadow-brand-primary/20 ring-4 ring-brand-primary/10' : '' }}">
                                                    <i class="fas {{ $data['icon'] }} {{ $isActive ? '' : 'text-slate-300' }} text-sm" aria-hidden="true"></i>
                                                </div>
                                                
                                                <!-- Label -->
                                                <div class="absolute top-full mt-3 text-center pointer-events-none z-20">
                                                    <span class="block text-[11px] font-bold uppercase tracking-widest transition-colors duration-300 {{ $isActive ? 'text-brand-primary' : 'text-slate-500' }} whitespace-nowrap">
                                                        {{ $data['label'] }}
                                                    </span>
                                                    @if($isCurrent)
                                                        <span class="inline-block mt-1 px-2.5 py-1 bg-red-50 text-brand-primary text-[9px] font-bold rounded-full border border-red-100">
                                                            Current
                                                        </span>
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
                                aria-labelledby="info-modal-title-{{ $title->id }}"
                                onclick="if (event.target === this) this.close()"
                                class="m-auto rounded-3xl p-0 backdrop:bg-slate-900/60 backdrop:backdrop-blur-xs w-full max-w-xl max-h-[85vh] open:animate-[fadeIn_0.2s_ease-out] motion-reduce:animate-none border border-slate-200 shadow-2xl overflow-hidden focus:outline-none">
                                <div class="bg-white flex flex-col h-full max-h-[85vh]">
                                    <!-- Header -->
                                    <div class="px-6 py-5 border-b border-slate-100 bg-white flex items-center justify-between shrink-0 z-20">
                                        <div class="flex items-center gap-3 pr-3 min-w-0">
                                            <div class="w-10 h-10 rounded-2xl bg-red-50 text-brand-primary border border-red-100 flex items-center justify-center shrink-0 shadow-xs" aria-hidden="true">
                                                <i class="fas fa-file-invoice text-base"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <h3 id="info-modal-title-{{ $title->id }}" class="font-extrabold text-lg text-slate-900 leading-tight">Protocol Details</h3>
                                                <p class="text-xs text-slate-500 font-medium truncate max-w-[220px] sm:max-w-xs mt-0.5" title="{{ $title->Study_Protocol_title }}">
                                                    {{ $title->Study_Protocol_title }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2.5 shrink-0">
                                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 font-mono text-xs font-bold rounded-lg border border-slate-200/80" aria-label="Reference ID: #{{ str_pad($title->id, 6, '0', STR_PAD_LEFT) }}">
                                                #{{ str_pad($title->id, 6, '0', STR_PAD_LEFT) }}
                                            </span>
                                            <button type="button" onclick="document.getElementById('info-modal-{{ $title->id }}').close()"
                                                aria-label="Close protocol details dialog"
                                                class="w-10 h-10 rounded-full bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors flex items-center justify-center border border-slate-200 shrink-0 min-w-[40px] min-h-[40px] focus-visible:ring-2 focus-visible:ring-brand-primary focus:outline-none cursor-pointer">
                                                <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-4 sm:p-6 md:p-7 space-y-4 flex-1 overflow-y-auto min-h-0 bg-slate-50/30 overscroll-contain">
                                        <!-- Status & Classifications Card -->
                                        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-xs">
                                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                                <i class="fas fa-tags text-[10px] text-slate-400" aria-hidden="true"></i>
                                                <span>Status & Classifications</span>
                                            </span>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 border border-{{ $statusColor }}-100/60 shadow-xs">
                                                    <i class="fas {{ $statusIcon }}"></i>
                                                    {{ $title->Status ?? $title->status ?? 'Pending Review' }}
                                                </span>
                                                
                                                @if($title->Review_Type && $title->Review_Type !== 'N/A')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/60 shadow-xs">
                                                        <i class="fas fa-clipboard-list text-indigo-500"></i>
                                                        {{ $title->Review_Type }}
                                                    </span>
                                                @endif
                                                
                                                {{-- CV State --}}
                                                @if($title->cv_verification_status === 'Invalid')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100/60 shadow-xs">
                                                        <i class="fas fa-id-card text-red-500"></i>
                                                        CV Mismatch
                                                    </span>
                                                @elseif($title->cv_verification_status === 'Valid')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-100/60 shadow-xs">
                                                        <i class="fas fa-check-circle text-violet-500"></i>
                                                        CV Verified
                                                    </span>
                                                @endif

                                                {{-- OR State --}}
                                                @if($title->Official_Receipt_Number && !$title->is_or_verified)
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/60 shadow-xs">
                                                        <i class="fas fa-hourglass-half text-slate-400"></i>
                                                        OR Pending
                                                    </span>
                                                @elseif($title->is_or_verified)
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/60 shadow-xs">
                                                        <i class="fas fa-check-circle text-emerald-500"></i>
                                                        OR Verified
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Key Metadata Bento Grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <!-- Category -->
                                            <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-xs flex flex-col justify-between">
                                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                    <i class="fas fa-graduation-cap text-slate-400" aria-hidden="true"></i>
                                                    <span>Research Category</span>
                                                </span>
                                                <p class="text-slate-900 font-bold text-sm leading-snug">
                                                    {{ $title->Research_Category ?? 'Uncategorized' }}
                                                </p>
                                            </div>

                                            <!-- Adviser -->
                                            <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-xs flex flex-col justify-between">
                                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                    <i class="fas fa-user-tie text-slate-400" aria-hidden="true"></i>
                                                    <span>Research Adviser</span>
                                                </span>
                                                <p class="text-slate-900 font-bold text-sm leading-snug">
                                                    {{ $title->Adviser ?? 'Not Specified' }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Assigned Reviewers -->
                                        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-xs">
                                            <div class="flex items-center justify-between mb-2.5">
                                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                                    <i class="fas fa-user-shield text-slate-400" aria-hidden="true"></i>
                                                    <span>Assigned Reviewers</span>
                                                </span>
                                                @if($title->assigned_reviewers && count($title->assigned_reviewers) > 0)
                                                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                                                        {{ count($title->assigned_reviewers) }} Assigned
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if($title->assigned_reviewers && count($title->assigned_reviewers) > 0)
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                                    @foreach($title->assigned_reviewers as $reviewerId)
                                                        @php
                                                            $reviewerUser = \App\Models\User::find($reviewerId);
                                                        @endphp
                                                        @if($reviewerUser)
                                                            <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                                                    <i class="fas fa-user-check"></i>
                                                                </div>
                                                                <div class="truncate">
                                                                    <p class="text-xs font-bold text-slate-800 truncate leading-tight">
                                                                        {{ $reviewerUser->first_name }} {{ $reviewerUser->last_name }}
                                                                    </p>
                                                                    <p class="text-[10px] text-slate-400 truncate">Ethics Committee</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-dashed border-slate-200 text-slate-400 text-xs">
                                                    <i class="fas fa-info-circle text-slate-400"></i>
                                                    <span class="italic">No reviewers assigned yet.</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                            <!-- Admin Actions Section (Revenue Tracking) -->
                                            <div class="pt-4 border-t border-slate-100">
                                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                                    <i class="fas fa-money-check-alt text-emerald-600"></i> Revenue Tracking
                                                </h4>
                                                
                                                <div class="rounded-2xl p-4 border 
                                                    {{ $title->is_or_verified ? 'bg-emerald-50/50 border-emerald-200' : 
                                                      ($title->Official_Receipt_Number ? 'bg-indigo-50/50 border-indigo-200' : 'bg-orange-50/50 border-orange-200') }}">
                                                    
                                                    @if(!$title->Official_Receipt_Number)
                                                        <!-- State 1: Awaiting Researcher -->
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center gap-3">
                                                                <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600">
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
                                                                <p class="text-lg font-mono font-bold text-indigo-900 bg-white px-3 py-1.5 rounded-xl border border-indigo-100 inline-block w-max">#{{ $title->Official_Receipt_Number }}</p>
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
                                                            <p class="text-lg font-mono font-bold text-emerald-800 bg-white px-3 py-2 rounded-xl border border-emerald-100">#{{ $title->Official_Receipt_Number }}</p>
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
                                aria-labelledby="log-modal-title-{{ $title->id }}"
                                onclick="if (event.target === this) this.close()"
                                class="m-auto rounded-3xl p-0 backdrop:bg-slate-900/60 backdrop:backdrop-blur-xs w-full max-w-2xl max-h-[85vh] open:animate-[fadeIn_0.2s_ease-out] motion-reduce:animate-none border border-slate-200 shadow-2xl overflow-hidden focus:outline-none">
                                <div class="bg-white flex flex-col h-full max-h-[85vh]">
                                    <!-- Static Header -->
                                    <div class="px-6 py-5 border-b border-slate-100 bg-white flex items-center justify-between shrink-0 z-20">
                                        <div class="flex items-center gap-3 pr-3 min-w-0">
                                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center shrink-0 shadow-xs" aria-hidden="true">
                                                <i class="fas fa-history text-base"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <h3 id="log-modal-title-{{ $title->id }}" class="font-extrabold text-lg text-slate-900 leading-tight">Activity Log</h3>
                                                <p class="text-xs text-slate-500 font-medium truncate max-w-[220px] sm:max-w-md mt-0.5" title="{{ $title->Study_Protocol_title }}">
                                                    Audit trail for <span class="font-semibold text-slate-700">{{ $title->Study_Protocol_title }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2.5 shrink-0">
                                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 font-mono text-xs font-bold rounded-lg border border-slate-200/80">
                                                {{ $title->titleLogs->count() }} events
                                            </span>
                                            <button type="button" onclick="document.getElementById('log-modal-{{ $title->id }}').close()"
                                                aria-label="Close activity log dialog"
                                                class="w-10 h-10 rounded-full bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors flex items-center justify-center border border-slate-200 shrink-0 min-w-[40px] min-h-[40px] focus-visible:ring-2 focus-visible:ring-brand-primary focus:outline-none cursor-pointer">
                                                <i class="fas fa-times text-sm" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Scrollable Content -->
                                    <div class="p-4 sm:p-6 md:p-7 bg-slate-50/40 flex-1 overflow-y-auto min-h-0 overscroll-contain">
                                        @if($title->titleLogs->isEmpty())
                                            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-200 p-8">
                                                <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center mx-auto mb-3 text-slate-300 border border-slate-100">
                                                    <i class="fas fa-clipboard-list text-2xl"></i>
                                                </div>
                                                <h4 class="text-slate-800 font-bold text-sm mb-1">No Activity Logged</h4>
                                                <p class="text-slate-500 text-xs max-w-xs mx-auto leading-relaxed">Events and status updates will be chronologically tracked here as this submission progresses.</p>
                                            </div>
                                        @else
                                            <div class="relative before:absolute before:top-3 before:bottom-3 before:left-[19px] before:w-0.5 before:bg-slate-200/80 space-y-4">
                                                @foreach($title->titleLogs as $log)
                                                    @php
                                                        $actionLower = strtolower($log->action);
                                                        $isCreation = str_contains($actionLower, 'created') || str_contains($actionLower, 'submitted');
                                                        $isStatus = str_contains($actionLower, 'status');
                                                        
                                                        if($isCreation) {
                                                            $icon = 'fa-plus';
                                                            $nodeBg = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                                            $accentTag = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                                        } elseif($isStatus) {
                                                            $icon = 'fa-arrows-rotate';
                                                            $nodeBg = 'bg-blue-50 text-blue-600 border-blue-100';
                                                            $accentTag = 'bg-blue-50 text-blue-700 border-blue-100';
                                                        } else {
                                                            $icon = 'fa-pen-nib';
                                                            $nodeBg = 'bg-amber-50 text-amber-600 border-amber-100';
                                                            $accentTag = 'bg-amber-50 text-amber-700 border-amber-100';
                                                        }
                                                    @endphp

                                                    <div class="relative flex items-start gap-4 group">
                                                        <!-- Compact Node Icon -->
                                                        <div class="w-10 h-10 rounded-2xl border {{ $nodeBg }} flex items-center justify-center shrink-0 z-10 shadow-xs transition-transform duration-200 group-hover:scale-105 bg-white">
                                                            <i class="fas {{ $icon }} text-xs"></i>
                                                        </div>
                                                        
                                                        <!-- Event Card -->
                                                        <div class="flex-1 bg-white border border-slate-100 rounded-2xl p-4 shadow-xs hover:shadow-sm hover:border-slate-200 transition-all duration-200">
                                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 sm:gap-3 mb-2">
                                                                <div class="flex items-center gap-2 flex-wrap">
                                                                    <h4 class="text-slate-900 font-bold text-sm leading-tight m-0">
                                                                        {{ $log->action }}
                                                                    </h4>
                                                                    
                                                                    @if($log->user)
                                                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200/60">
                                                                            <i class="fas fa-user-circle text-slate-400"></i>
                                                                            {{ $log->user->first_name }} {{ $log->user->last_name }}
                                                                        </span>
                                                                    @else
                                                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200/60">
                                                                            <i class="fas fa-robot text-slate-400"></i> System
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <span class="text-[11px] font-medium text-slate-500 flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
                                                                    <i class="far fa-clock text-slate-400 text-[10px]" aria-hidden="true"></i>
                                                                    {{ $log->created_at->format('M d, Y • h:i A') }}
                                                                </span>
                                                            </div>

                                                            <p class="text-slate-600 text-xs sm:text-[13px] leading-relaxed m-0 font-medium pt-2 border-t border-slate-50">
                                                                {{ $log->description }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
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
                                            :class="cvProjectType === 'Funded Research' ? 'ring-2 ring-brand-primary bg-red-50 text-red-900 border-red-200' : 'bg-white hover:bg-slate-50 border-slate-200'"
                                            class="py-3 rounded-xl text-xs font-bold border flex flex-col items-center gap-1.5 transition-all text-slate-800">
                                            <i class="fas fa-money-bill-wave text-base"></i> Funded Research
                                        </button>
                                        <button type="button" @click="cvProjectType = 'Course Requirement'; cvSubType = ''"
                                            :class="cvProjectType === 'Course Requirement' ? 'ring-2 ring-amber-500 bg-amber-50 text-amber-900 border-amber-200' : 'bg-white hover:bg-slate-50 border-slate-200'"
                                            class="py-3 rounded-xl text-xs font-bold border flex flex-col items-center gap-1.5 transition-all text-slate-800">
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
                                            :class="cvSubType === '{{ $cat->name }}' ? 'ring-2 ring-brand-primary bg-red-50 font-bold' : 'bg-slate-50 hover:bg-slate-100'"
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
                    <div class="pt-10 md:pt-12 border-t border-slate-200/80">
                        <h3 class="text-slate-500 font-bold text-xs sm:text-sm uppercase tracking-wider mb-5 text-center">Resources & Assistance</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <a href="{{ route('instructions') }}"
                                class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md hover:border-blue-200 transition-all group">
                                <div
                                    class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 group-hover:scale-105 transition-transform" aria-hidden="true">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <span
                                        class="block font-bold text-slate-800 group-hover:text-blue-700 transition-colors text-sm sm:text-base">Guidelines</span>
                                    <span class="text-xs sm:text-sm text-slate-500">Read submission rules</span>
                                </div>
                                <i
                                    class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-blue-500 transition-colors text-sm" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('resources') }}"
                                class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md hover:border-indigo-200 transition-all group">
                                <div
                                    class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 group-hover:scale-105 transition-transform" aria-hidden="true">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div>
                                    <span
                                        class="block font-bold text-slate-800 group-hover:text-indigo-700 transition-colors text-sm sm:text-base">Templates</span>
                                    <span class="text-xs sm:text-sm text-slate-500">Download official forms</span>
                                </div>
                                <i
                                    class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-indigo-500 transition-colors text-sm" aria-hidden="true"></i>
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