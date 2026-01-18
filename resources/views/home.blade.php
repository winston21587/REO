<x-user_layout>
    <div class="max-w-5xl mx-auto animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading">
                Welcome back, <span class="text-[#8B0000]">{{ explode(' ', Auth::user()->first_name)[0] }}</span>!
            </h1>
            <p class="text-slate-500 mt-2 text-lg">Here is the status of your research submissions.</p>
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
            <div class="space-y-12"> <!-- Added space between cards -->
            @foreach($titles as $title)
                @php
                    // Tracker Logic
                    $steps = [
                        1 => ['label' => 'Submitted', 'icon' => 'fa-paper-plane'],
                        2 => ['label' => 'Review', 'icon' => 'fa-search'],
                        3 => ['label' => 'Revision', 'icon' => 'fa-edit'],
                        4 => ['label' => 'Deliberation', 'icon' => 'fa-clipboard-check'],
                        5 => ['label' => 'Certificate', 'icon' => 'fa-certificate'],
                    ];

                    $currentStep = 1;
                    $status = $title->Status ?? $title->status ?? '';
                    $checkStatus = trim($status);

                    if (stripos($checkStatus, 'Incomplete') !== false) {
                        $currentStep = 1;
                    } elseif (stripos($checkStatus, 'Approved') !== false || (stripos($checkStatus, 'Complete') !== false && stripos($checkStatus, 'Incomplete') === false) || stripos($checkStatus, 'Certification') !== false) {
                        $currentStep = 5;
                    } elseif ($checkStatus === 'Panel Deliberation') {
                        $currentStep = 4;
                    } elseif (in_array($checkStatus, [
                        'Waiting for Revision', 
                        'Modifications Required',
                        'Revision Submitted', 
                        'Checking of Revisions', 
                        'Submission of Revisions / Resubmission', 
                        'Returned',
                        'Disapproved'
                    ])) {
                        $currentStep = 3;
                    } elseif (in_array($checkStatus, [
                        'For Initial Review', 
                        'Hardcopy Received - For Initial Review'
                    ])) {
                        $currentStep = 2;
                    }

                    $statusColor = match($title->status) {
                        'Approved' => 'green',
                        'Returned', 'Waiting for Revision', 'Modifications Required', 'Incomplete' => 'orange',
                        'Panel Deliberation' => 'blue',
                        'Disapproved' => 'red',
                        default => 'orange',
                    };
                    $statusIcon = match($title->status) {
                        'Approved' => 'fa-check-circle',
                        'Returned', 'Waiting for Revision', 'Modifications Required' => 'fa-edit',
                        'Incomplete' => 'fa-exclamation-circle',
                        'Panel Deliberation' => 'fa-users',
                        'Disapproved' => 'fa-times-circle',
                        default => 'fa-clock',
                    };
                    $hasLetter = $title->files->where('filetype', 'Result of Review (Admin Generated)')->isNotEmpty();
                @endphp

                <!-- Single Protocol Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden relative group">
                    <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="fas {{ $statusIcon }} text-9xl text-{{ $statusColor }}-600"></i>
                    </div>
                    
                    <div class="p-8 relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 flex items-center gap-2">
                                        <i class="fas {{ $statusIcon }}"></i>
                                        {{ $title->Status ?? $title->status ?? 'Pending Review' }}
                                    </span>
                                    <span class="text-slate-400 text-sm font-medium">
                                        <i class="far fa-calendar-alt mr-1"></i> Submitted on {{ $title->created_at->format('F d, Y') }}
                                    </span>
                                </div>
                                <h2 class="text-3xl font-extrabold text-slate-900 leading-tight max-w-3xl mb-2">
                                    {{ $title->Study_Protocol_title }}
                                </h2>
                                <button onclick="document.getElementById('info-modal-{{ $title->id }}').showModal()" class="text-[#8B0000] font-bold text-sm hover:underline flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i> View Protocol Details
                                </button>
                            </div>
                            
                            <div class="flex flex-col gap-3 min-w-[200px]">
                                <a href="{{ route('manage.files', $title->id) }}" class="w-full py-3 px-6 bg-[#8B0000] text-white rounded-xl font-bold text-center shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-folder-open"></i> Manage Files
                                </a>
                                
                                <div class="flex flex-col gap-2">
                                @if($title->status === 'Approved')
                                    <div class="w-full py-3 px-6 bg-green-50 border-2 border-green-500 text-green-700 rounded-xl font-bold flex items-center justify-center gap-2">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </div>
                                @elseif($hasLetter)
                                    <a href="{{ route('recommendation.view', $title->id) }}" target="_blank" class="w-full py-3 px-6 bg-white border-2 border-emerald-500 text-emerald-700 rounded-xl font-bold hover:bg-emerald-50 transition-colors flex items-center justify-center gap-2">
                                        <i class="fas fa-envelope-open-text"></i> View Result Letter
                                    </a>
                                @endif

                                @if($title->status === 'Incomplete')
                                     <!-- Incomplete Status Action, maybe link to manage files or show message -->
                                     <div class="px-3 py-2 text-xs text-orange-600 font-bold text-center bg-orange-50 rounded-lg border border-orange-100">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Check Requirements
                                     </div>
                                @endif
                                </div>

                                @if($title->status === 'Returned' || $title->status === 'Waiting for Revision')
                                    <button class="w-full py-3 px-6 bg-white border-2 border-orange-100 text-orange-700 rounded-xl font-bold hover:bg-orange-50 transition-colors flex items-center justify-center gap-2">
                                        <i class="fas fa-comment-alt"></i> View Feedback
                                    </button>
                                @endif
                            </div>
                        </div>

                         <!-- Embedded Tracker -->
                        <div class="pt-6 border-t border-slate-100">
                             <div class="relative px-4">
                                <!-- Progress Bar Background -->
                                <div class="absolute top-1/2 left-4 right-4 h-1 bg-slate-100 -translate-y-1/2 rounded-full z-0"></div>
                                
                                <!-- Active Progress Bar -->
                                <div class="absolute top-1/2 left-4 h-1 bg-[#8B0000] -translate-y-1/2 rounded-full z-0 transition-all duration-1000 ease-out"
                                     style="width: {{ ($currentStep - 1) / (count($steps) - 1) * 100 }}%"></div>

                                <div class="relative z-10 flex justify-between">
                                    @foreach($steps as $step => $data)
                                        @php
                                            $isCompleted = $step < $currentStep;
                                            $isCurrent = $step === $currentStep;
                                            $isActive = $step <= $currentStep;
                                        @endphp
                                        <div class="flex flex-col items-center group">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 transition-all duration-300 bg-white
                                                {{ $isActive ? 'border-[#8B0000] text-[#8B0000]' : 'border-slate-200 text-slate-300' }}
                                                {{ $isCurrent ? 'scale-110 shadow-lg shadow-red-900/20' : '' }}">
                                                <i class="fas {{ $data['icon'] }} {{ $isActive ? '' : 'text-slate-300' }} text-sm"></i>
                                            </div>
                                            <span class="mt-2 text-[10px] font-bold uppercase tracking-wider transition-colors duration-300
                                                {{ $isActive ? 'text-[#8B0000]' : 'text-slate-400' }}">
                                                {{ $data['label'] }}
                                            </span>
                                            @if($isCurrent)
                                                <div class="absolute -bottom-8 w-48 flex flex-col items-center">
                                                    <span class="text-[9px] font-bold text-[#8B0000] animate-pulse text-center">{{ $title->status }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Modal -->
                <dialog id="info-modal-{{ $title->id }}" class="m-auto rounded-2xl p-0 backdrop:bg-slate-900/50 w-full max-w-md open:animate-[fadeIn_0.2s_ease-out]">
                    <div class="bg-white p-6">
                        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                            <h3 class="font-bold text-lg text-slate-800">Protocol Details</h3>
                            <button onclick="document.getElementById('info-modal-{{ $title->id }}').close()" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Category</label>
                                <p class="text-slate-800 font-bold bg-slate-50 p-3 rounded-lg">{{ $title->Research_Category }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Adviser</label>
                                <p class="text-slate-800 font-bold bg-slate-50 p-3 rounded-lg">{{ $title->Adviser }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 block">Reference ID</label>
                                <p class="text-slate-600 font-mono bg-slate-50 p-3 rounded-lg border border-slate-100">#{{ str_pad($title->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </div>
                </dialog>
            @endforeach

            <!-- Resources & Assistance Section (Moved to Bottom) -->
            <div class="pt-12 border-t border-slate-200">
                <h3 class="text-slate-400 font-bold text-sm uppercase tracking-wider mb-6 text-center">Resources & Assistance</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <a href="{{ route('instructions') }}" class="flex items-center gap-4 p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <span class="block font-bold text-slate-800 group-hover:text-blue-700 transition-colors">Guidelines</span>
                            <span class="text-sm text-slate-500">Read submission rules</span>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                    </a>

                    <a href="{{ route('resources') }}" class="flex items-center gap-4 p-6 bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all group">
                        <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                            <i class="fas fa-download"></i>
                        </div>
                        <div>
                            <span class="block font-bold text-slate-800 group-hover:text-indigo-700 transition-colors">Templates</span>
                            <span class="text-sm text-slate-500">Download official forms</span>
                        </div>
                         <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
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
</x-user_layout>