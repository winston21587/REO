<x-user_layout>
    <x-skeleton-loader />

    <div id="page-content" style="display: none;" class="max-w-7xl mx-auto pt-2 pb-28 sm:py-12 px-3.5 sm:px-6 lg:px-8 animate-[fadeInUp_0.5s_ease-out] relative"
        x-data="submissionForm()"
        x-cloak>

        <!-- Subtle Atmospheric Maroon Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-5xl h-72 bg-gradient-to-b from-red-100/40 via-red-50/20 to-transparent pointer-events-none -z-10 rounded-full blur-3xl" aria-hidden="true"></div>

        <div>

            <!-- Header -->
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading tracking-tight">New Submission</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5 sm:mt-2 max-w-xl mx-auto leading-relaxed">Submit your research protocol for ethics review. Please ensure all details are accurate and required documents are attached.</p>
            </div>

            <form action="{{ route('submit.title') }}" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 lg:grid-cols-3 gap-8" id="submission-form">
                @csrf

                <!-- Left Column: Form Details -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Step 1: Protocol Details -->
                    <div class="bg-white rounded-3xl shadow-sm overflow-hidden transition-all duration-300">
                        <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-100/60 bg-white flex items-start sm:items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <h2 class="text-slate-900 font-black text-lg sm:text-2xl leading-tight font-heading tracking-tight">Protocol Details</h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 sm:mt-1 font-medium">Classification, title, and initial ethics parameters</p>
                            </div>
                            <span class="text-[11px] sm:text-xs font-black text-brand-primary uppercase tracking-wider bg-red-50/80 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full ring-1 ring-brand-primary/20 shrink-0">Step 1 of 2</span>
                        </div>

                        <div class="p-4.5 sm:p-7 space-y-5 sm:space-y-6">

                            {{-- ===== Project Classification ===== --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-3">Project Type <span class="text-brand-primary">*</span></label>
                                <input type="hidden" name="project_type" id="project_type_input" x-bind:value="projectType" required>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                    {{-- Funded Research Button --}}
                                    <button type="button"
                                        @click="projectType = 'Funded Research'; courseSubType = ''; fundingSubType = ''"
                                        :class="projectType === 'Funded Research'
                                            ? 'border-2 border-brand-primary bg-gradient-to-b from-red-50/60 to-white text-brand-primary shadow-sm ring-4 ring-brand-primary/10'
                                            : 'border border-slate-200/80 bg-slate-50/60 text-slate-800 hover:border-slate-300 hover:bg-white hover:shadow-sm'"
                                        class="flex flex-col items-center justify-center gap-2.5 sm:gap-3 p-4 sm:p-6 rounded-2xl transition-all duration-200 font-bold text-sm cursor-pointer active:scale-95 text-center group">
                                        <div :class="projectType === 'Funded Research' ? 'bg-brand-primary text-white shadow-sm' : 'bg-slate-200/80 text-slate-600 group-hover:bg-slate-200'"
                                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center transition-all shadow-xs">
                                            <i class="fas fa-sack-dollar text-xl sm:text-2xl" aria-hidden="true"></i>
                                        </div>
                                        <span class="text-base sm:text-lg font-black tracking-tight">Funded Research</span>
                                        <p class="text-xs font-medium text-slate-500 text-center leading-relaxed max-w-[220px]">Institutional grant or external commissioned funding</p>
                                    </button>

                                    {{-- Course Requirement Button --}}
                                    <button type="button"
                                        @click="projectType = 'Course Requirement'; fundingSubType = ''"
                                        :class="projectType === 'Course Requirement'
                                            ? 'border-2 border-brand-primary bg-gradient-to-b from-red-50/60 to-white text-brand-primary shadow-sm ring-4 ring-brand-primary/10'
                                            : 'border border-slate-200/80 bg-slate-50/60 text-slate-800 hover:border-slate-300 hover:bg-white hover:shadow-sm'"
                                        class="flex flex-col items-center justify-center gap-2.5 sm:gap-3 p-4 sm:p-6 rounded-2xl transition-all duration-200 font-bold text-sm cursor-pointer active:scale-95 text-center group">
                                        <div :class="projectType === 'Course Requirement' ? 'bg-brand-primary text-white shadow-sm' : 'bg-slate-200/80 text-slate-600 group-hover:bg-slate-200'"
                                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center transition-all shadow-xs">
                                            <i class="fas fa-graduation-cap text-xl sm:text-2xl" aria-hidden="true"></i>
                                        </div>
                                        <span class="text-base sm:text-lg font-black tracking-tight">Course Requirement</span>
                                        <p class="text-xs font-medium text-slate-500 text-center leading-relaxed max-w-[220px]">Undergraduate thesis, master's thesis, or doctoral dissertation</p>
                                    </button>
                                </div>

                                @php
                                    $getIcon = function($name) {
                                        $n = strtolower($name);
                                        if (strpos($n, 'undergrad') !== false) return 'fa-book';
                                        if (strpos($n, 'master') !== false || strpos($n, 'graduate') !== false) return 'fa-user-graduate';
                                        if (strpos($n, 'dissertation') !== false) return 'fa-scroll';
                                        if (strpos($n, 'institution') !== false) return 'fa-university';
                                        if (strpos($n, 'external') !== false) return 'fa-globe';
                                        return 'fa-tag';
                                    };
                                @endphp

                                {{-- Funded Research Sub-options --}}
                                <div x-show="projectType === 'Funded Research'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" style="display:none;" class="mt-4 origin-top">
                                    <input type="hidden" name="funding_type" x-bind:value="fundingSubType">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Funding Source <span class="text-brand-primary">*</span></label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($categories->where('classification', 'Funded Research') as $cat)
                                        <button type="button" @click="fundingSubType = '{{ addslashes($cat->name) }}'; syncFee('{{ addslashes($cat->name) }}')"
                                            :class="fundingSubType === '{{ addslashes($cat->name) }}' ? 'border-brand-primary bg-brand-primary/5 text-brand-primary ring-1 ring-brand-primary/20 font-bold' : 'border-slate-100 bg-white text-slate-700 hover:border-slate-200 hover:bg-slate-50'"
                                            class="py-2.5 px-4 border rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                                            <i class="fas {{ $getIcon($cat->name) }} mr-1" aria-hidden="true"></i><span>{{ $cat->name }}</span>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Course Requirement Sub-options --}}
                                <div x-show="projectType === 'Course Requirement'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" style="display:none;" class="mt-4 origin-top">
                                    <input type="hidden" name="course_type" x-bind:value="courseSubType">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Type of Course Requirement <span class="text-brand-primary">*</span></label>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        @foreach($categories->where('classification', 'Course Requirement') as $cat)
                                        <button type="button" @click="courseSubType = '{{ addslashes($cat->name) }}'; syncFee('{{ addslashes($cat->name) }}')"
                                            :class="courseSubType === '{{ addslashes($cat->name) }}' ? 'border-brand-primary bg-brand-primary/5 text-brand-primary ring-1 ring-brand-primary/20 font-bold' : 'border-slate-100 bg-white text-slate-700 hover:border-slate-200 hover:bg-slate-50'"
                                            class="py-3 px-3 border rounded-xl text-xs font-semibold transition-all text-center flex flex-col items-center justify-center gap-1.5 cursor-pointer active:scale-95">
                                            <i class="fas {{ $getIcon($cat->name) }} text-base" aria-hidden="true"></i><span>{{ $cat->name }}</span>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>

                                <p x-show="!projectType" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="mt-2 text-xs text-slate-400 italic origin-top" style="display:none;">Please select a project type to continue.</p>
                            </div>

                            {{-- ===== Study Protocol Title ===== --}}
                            <div class="group">
                                <label for="Study_Protocol_title"
                                    class="block text-sm font-bold text-slate-800 mb-2">Study Protocol Title <span class="text-brand-primary">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-heading text-slate-400 group-focus-within:text-brand-primary transition-colors" aria-hidden="true"></i>
                                    </div>
                                    <input type="text" name="Study_Protocol_title" id="Study_Protocol_title"
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition-all duration-200"
                                        placeholder="Enter full descriptive study protocol title" required>
                                </div>
                            </div>

                            <!-- Research Category / Review Fees -->
                            <div class="group">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 sm:gap-2 mb-2">
                                    <label for="Research_Category" class="text-sm font-bold text-slate-800">Review Fees & Classification</label>
                                    <span class="text-[11px] font-medium text-slate-500 bg-slate-100 px-2.5 py-0.5 sm:py-1 rounded-full self-start sm:self-auto inline-flex items-center">
                                        <i class="fas fa-lock text-[10px] text-slate-400 mr-1.5" aria-hidden="true"></i>
                                        <span>Auto-synced with Project Type</span>
                                    </span>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-tag text-slate-400 group-focus-within:text-brand-primary transition-colors" aria-hidden="true"></i>
                                    </div>
                                    <select name="Research_Category" id="Research_Category"
                                        onchange="toggleOtherCategory(this)"
                                        class="w-full pl-11 pr-10 py-3 bg-slate-100/80 border border-slate-200 rounded-xl text-slate-700 focus:outline-none transition-all duration-200 appearance-none pointer-events-none cursor-not-allowed font-medium text-sm"
                                        required tabindex="-1">
                                        <option value="" disabled selected>Select Review Fees</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}">
                                                {{ $category->name }} - ₱ {{ number_format($category->fee, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-slate-400 text-xs" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <!-- Other Category Input -->
                                <div id="other_category_container" class="hidden mt-3 animate-[fadeIn_0.3s_ease-out]">
                                    <label for="other_category"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Specify
                                        Other Category</label>
                                    <input type="text" name="other_category" id="other_category"
                                        class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/10 transition-all"
                                        placeholder="Please specify...">
                                </div>
                            </div>

                            <!-- Research Type -->
                            <div class="group">
                                <label for="research_type" class="block text-sm font-bold text-slate-800 mb-2">Research Type <span class="text-brand-primary">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-flask text-slate-400 group-focus-within:text-brand-primary transition-colors" aria-hidden="true"></i>
                                    </div>
                                    <select name="research_type" id="research_type"
                                        class="w-full pl-11 pr-10 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition-all duration-200 appearance-none cursor-pointer text-sm font-medium"
                                        required>
                                        <option value="" disabled selected>Select Research Type</option>
                                        <option value="Biomedical Studies">Biomedical Studies</option>
                                        <option value="Health Operations Research">Health Operations Research</option>
                                        <option value="Social Research">Social Research</option>
                                        <option value="Public Health Research">Public Health Research</option>
                                        <option value="Clinical Trials">Clinical Trials</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-slate-400 text-xs" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Name of Adviser (mandatory for Course Requirement, hidden for Funded Research) -->
                            <div class="group origin-top" x-show="projectType === 'Course Requirement'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" style="display:none;">
                                <label for="Adviser" class="block text-sm font-bold text-slate-800 mb-2">
                                    Name of Adviser <span class="text-brand-primary">*</span>
                                    <span class="ml-2 text-[10px] font-bold bg-amber-50 text-amber-800 px-2 py-0.5 rounded-full border border-amber-200">Required for Students</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-user-tie text-slate-400 group-focus-within:text-brand-primary transition-colors" aria-hidden="true"></i>
                                    </div>
                                    <input type="text" name="Adviser" id="Adviser"
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition-all duration-200"
                                        placeholder="Enter Adviser Full Name"
                                        :required="projectType === 'Course Requirement'">
                                </div>
                            </div>
                        </div> <!-- End of content div -->
                    </div> <!-- End of Step 1 container div -->

                    <!-- Step 2: Required Documents -->
                    <div class="bg-white rounded-3xl shadow-sm overflow-hidden transition-all duration-300">
                        <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-100/60 bg-white flex items-start sm:items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <h2 class="text-slate-900 font-black text-lg sm:text-2xl leading-tight font-heading tracking-tight">
                                    <span class="sm:hidden">Required Documents</span>
                                    <span class="hidden sm:inline">Required Research Documents</span>
                                </h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5 sm:mt-1 font-medium">Attach formal protocols, CVs, tools, and consent forms</p>
                            </div>
                            <span class="text-[11px] sm:text-xs font-black text-slate-600 uppercase tracking-wider bg-slate-100 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full shrink-0">Step 2 of 2</span>
                        </div>

                        <div class="p-4.5 sm:p-7 space-y-4 sm:space-y-5">
                            <!-- Document Guidance Strip -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 px-4.5 py-3.5 bg-slate-50/90 rounded-2xl text-xs sm:text-sm text-slate-600 border border-slate-100/80">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <i class="fas fa-info-circle text-brand-primary text-sm shrink-0" aria-hidden="true"></i>
                                    <span class="leading-relaxed">
                                        Formats: <strong class="text-slate-800 font-bold">PDF, Word, Images</strong> &bull; Total limit: <strong class="text-slate-800 font-bold whitespace-nowrap">25 MB</strong>
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 shrink-0">
                                    <span class="text-brand-primary font-black text-sm leading-none">*</span>
                                    <span class="whitespace-nowrap">Required fields</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                            @foreach($requirements as $requirement)
                                @php
                                    // Determine accept attribute based on file_type
                                    $accept = [];
                                    $types = explode(',', $requirement->file_type);
                                    foreach ($types as $type) {
                                        $type = trim($type);
                                        if ($type === 'PDF')
                                            $accept[] = '.pdf';
                                        if ($type === 'Word')
                                            $accept[] = '.doc,.docx';
                                        if ($type === 'Others')
                                            $accept[] = '.jpg,.jpeg,.png,.gif,.bmp,.webp';
                                    }
                                    $acceptStr = !empty($accept) ? implode(',', $accept) : '';

                                    // Clean document title without redundant format suffix (badge handles format)
                                    $label = $requirement->name;
                                @endphp

                                <x-file-upload-item
                                    name="files[{{ $requirement->id }}]{{ $requirement->is_multiple ? '[]' : '' }}"
                                    label="{{ $label }}" accept="{{ $acceptStr }}"
                                    required="{{ $requirement->is_required ? 'true' : 'false' }}"
                                    multiple="{{ $requirement->is_multiple ? 'true' : 'false' }}" />
                            @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Submission Summary & Status -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">

                        <!-- Submission Summary Card -->
                        <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-100/60 bg-white flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-red-50 text-brand-primary border border-red-100/80 flex items-center justify-center shrink-0 shadow-xs" aria-hidden="true">
                                        <i class="fas fa-clipboard-check text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-base text-slate-900 leading-tight font-heading">Submission Summary</h3>
                                        <p class="text-xs text-slate-500 mt-0.5">Readiness & files checklist</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-5">
                                <!-- File Status List -->
                                <div>
                                    <div class="flex items-center justify-between mb-2.5">
                                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Attached Files</span>
                                        <span id="attached-count-badge" class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">0 Files</span>
                                    </div>
                                    <div id="file-status-list" class="space-y-2 text-sm max-h-48 overflow-y-auto pr-1">
                                        <div class="text-slate-400 italic text-xs p-3 bg-slate-50 rounded-xl text-center">No files attached yet.</div>
                                    </div>
                                </div>

                                <!-- Status & Submit Section -->
                                <div x-data="submitButtonStatus()"
                                     x-init="loadStatus()"
                                     x-cloak
                                     class="space-y-3.5 pt-3 border-t border-slate-100/60">
                                    
                                    <!-- Quota Status Trigger Block -->
                                    <div @click="openStatusModal()"
                                         @keydown.enter="openStatusModal()"
                                         @keydown.space.prevent="openStatusModal()"
                                         role="button"
                                         tabindex="0"
                                         aria-haspopup="dialog"
                                         aria-label="View ethics review submission quota and rate limit details"
                                         class="group relative overflow-hidden rounded-2xl p-4 border transition-all duration-200 cursor-pointer active:scale-[0.99] focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2"
                                         :class="canSubmit 
                                             ? 'bg-gradient-to-br from-emerald-50/40 via-white to-white border-emerald-200/90 hover:border-emerald-300 hover:shadow-md hover:shadow-emerald-950/5' 
                                             : 'bg-gradient-to-br from-red-50/70 via-white to-white border-red-200 hover:border-red-300 hover:shadow-md hover:shadow-red-950/5'">
                                        
                                        <div class="flex items-center gap-3.5">
                                            <!-- Icon -->
                                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 transition-transform duration-200 group-hover:scale-105"
                                                 :class="canSubmit 
                                                     ? 'bg-emerald-50 border border-emerald-200/90 text-emerald-600 shadow-2xs' 
                                                     : 'bg-red-50 border border-red-200/90 text-brand-primary shadow-2xs'">
                                                <i class="fas text-lg" :class="canSubmit ? 'fa-circle-check' : 'fa-clock-rotate-left'" aria-hidden="true"></i>
                                            </div>
                                            <!-- Text -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2">
                                                    <h4 class="font-bold text-sm text-slate-900 tracking-tight leading-tight" 
                                                        x-text="canSubmit ? 'Ready to Submit' : 'Submission Quota Reached'"></h4>
                                                    
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full shrink-0"
                                                          :class="canSubmit 
                                                              ? 'bg-emerald-100/70 text-emerald-800 border border-emerald-200/60' 
                                                              : 'bg-red-100 text-red-800 border border-red-200'">
                                                        <span class="w-1.5 h-1.5 rounded-full" :class="canSubmit ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'"></span>
                                                        <span x-text="dailyRemaining !== null ? (dailyRemaining + ' Left') : 'Active'"></span>
                                                    </span>
                                                </div>
                                                
                                                <p class="text-xs mt-1 flex items-center justify-between text-slate-500 group-hover:text-slate-700 transition-colors">
                                                    <span class="truncate" x-text="canSubmit ? 'Click to view quota & rate limits' : 'Temporarily paused • View details'"></span>
                                                    <i class="fas fa-chevron-right text-[10px] text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition-all ml-1.5 shrink-0" aria-hidden="true"></i>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button - Only visible if can submit -->
                                    <button type="submit"
                                        x-show="canSubmit"
                                        :disabled="!canSubmit"
                                        class="w-full text-white font-black text-base py-4 px-5 rounded-xl transition-all duration-200 flex items-center justify-center gap-3 bg-gradient-to-r from-brand-primary to-brand-secondary shadow-lg shadow-brand-primary/25 hover:shadow-xl hover:-translate-y-0.5 active:scale-95 cursor-pointer min-h-[52px] focus-visible:ring-4 focus-visible:ring-brand-primary/30 focus:outline-none">
                                        <i class="fas fa-paper-plane text-base" aria-hidden="true"></i>
                                        <span>Submit Protocol</span>
                                    </button>

                                    <!-- Helper Text (only when can submit) -->
                                    <p class="text-xs text-slate-500 text-center leading-relaxed" x-show="canSubmit">
                                        Your submission will be queued for formal REO ethics evaluation.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Help Card -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                            <h4 class="font-extrabold text-slate-900 text-sm mb-2 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs" aria-hidden="true">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                Need Help?
                            </h4>
                            <p class="text-xs text-slate-600 mb-3.5 leading-relaxed">
                                Download official forms and review protocol preparation instructions before submitting.
                            </p>
                            <a href="{{ route('resources') }}"
                                class="text-xs font-bold text-brand-primary hover:text-brand-secondary inline-flex items-center gap-1.5 transition-colors">
                                <span>Go to Downloadables</span>
                                <i class="fas fa-arrow-right text-[10px]" aria-hidden="true"></i>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- AI Results Modal -->
    <div id="ai-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity opacity-0" id="ai-modal-backdrop">
        </div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal Panel -->
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl opacity-0 scale-95 border border-slate-200"
                    id="ai-modal-panel">

                    <!-- Header -->
                    <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modal-title">
                            <i class="fas fa-robot text-brand-primary" aria-hidden="true"></i> <span>AI Compliance Check Results</span>
                        </h3>
                        <button type="button" onclick="closeAiModal()"
                            aria-label="Close AI results dialog"
                            class="text-slate-400 hover:text-white transition-colors active:scale-95 cursor-pointer">
                            <i class="fas fa-times text-xl" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                        <!-- Loader State -->
                        <div id="ai-modal-loader" class="hidden flex flex-col items-center justify-center py-12">
                            <div class="relative w-24 h-24 mb-6">
                                <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                                <div
                                    class="absolute inset-0 border-4 border-brand-primary rounded-full border-t-transparent animate-spin">
                                </div>
                                <i
                                    class="fas fa-magic absolute inset-0 flex items-center justify-center text-2xl text-brand-primary animate-pulse" aria-hidden="true"></i>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 mb-2">Analyzing Documents...</h4>
                            <p class="text-slate-500 text-center max-w-md">Our AI is scanning your attached files for
                                missing signatures, formatting errors, and compliance with REO standards.</p>
                        </div>

                        <!-- Results State -->
                        <div id="ai-modal-content" class="hidden prose prose-slate max-w-none">
                            <!-- Content injected via JS -->
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" onclick="closeAiModal()"
                            class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-slate-700 font-bold hover:bg-slate-50 transition-colors active:scale-95 cursor-pointer">
                            Close
                        </button>
                        <button type="button" onclick="closeAiModal()"
                            class="px-5 py-2 bg-brand-primary text-white rounded-xl font-bold hover:bg-brand-secondary transition-colors shadow-md shadow-brand-primary/20 active:scale-95 cursor-pointer">
                            I Understand
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reusable File Upload Component -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('submissionForm', () => ({
                    projectType: '',       // 'Funded Research' or 'Course Requirement'
                    fundingSubType: '',    // 'Institutionally Funded' or 'Externally Funded'
                    courseSubType: '',     // 'Undergraduate Thesis', 'MA Graduate Thesis', 'Dissertation'
                    syncFee(categoryName) {
                        const select = document.getElementById('Research_Category');
                        if (select) {
                            select.value = categoryName;
                            // Trigger the onchange logic (like revealing 'Other' or updating fee display)
                            select.dispatchEvent(new Event('change'));
                        }
                    }
                }));

                // Submit Button Status Component
                Alpine.data('submitButtonStatus', () => ({
                    canSubmit: true,
                    dailyRemaining: 10,
                    loading: true,

                    openStatusModal() {
                        window.dispatchEvent(new CustomEvent('open-quota-modal'));
                    },

                    async loadStatus() {
                        this.loading = true;
                        try {
                            const response = await fetch('{{ route("api.submission_status") }}', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                credentials: 'same-origin',
                            });

                            if (response.ok) {
                                const data = await response.json();
                                this.canSubmit = data.can_submit;
                                this.dailyRemaining = data.status?.daily?.remaining ?? 10;
                            }
                        } catch (error) {
                            console.error('Failed to load submission status:', error);
                            // Default to allowing submission on error
                            this.canSubmit = true;
                        } finally {
                            this.loading = false;
                            // Refresh every 15 seconds for faster updates
                            setTimeout(() => this.loadStatus(), 15000);
                        }
                    },

                    // Instantly decrement remaining when form submits
                    decrementRemaining() {
                        if (this.dailyRemaining > 0) {
                            this.dailyRemaining--;
                        }
                        if (this.dailyRemaining === 0) {
                            this.canSubmit = false;
                        }
                    }
                }));
            });

            function updateFileName(input) {
                const item = input.closest('[data-file-item]') || input.closest('.group') || input.parentElement;
                if (!item) return;

                // Clear validation error styling when user selects or updates file
                item.classList.remove('field-has-error', 'ring-2', 'ring-rose-500', 'border-rose-400', 'bg-rose-50/40');

                const fileNameDisplay = item.querySelector('.file-name');
                const clearBtn = item.querySelector('.clear-btn');

                if (input.files && input.files.length > 0) {
                    const count = input.files.length;
                    if (fileNameDisplay) {
                        fileNameDisplay.textContent = count === 1 ? input.files[0].name : `${count} files selected`;
                        fileNameDisplay.classList.add('text-slate-800', 'font-bold');
                        fileNameDisplay.classList.remove('text-slate-500', 'italic');
                    }
                    if (clearBtn) {
                        clearBtn.classList.remove('hidden');
                    }
                    item.classList.add('bg-white', 'border-emerald-200/80', 'shadow-xs');
                    item.classList.remove('bg-slate-50/60');
                } else {
                    if (fileNameDisplay) {
                        fileNameDisplay.textContent = 'No file chosen';
                        fileNameDisplay.classList.remove('text-slate-800', 'font-bold');
                        fileNameDisplay.classList.add('text-slate-500', 'italic');
                    }
                    if (clearBtn) {
                        clearBtn.classList.add('hidden');
                    }
                    item.classList.remove('bg-white', 'border-emerald-200/80', 'shadow-xs');
                    item.classList.add('bg-slate-50/60');
                }
                updateSidebarFileList();
            }

            function clearFile(btn) {
                const item = btn.closest('[data-file-item]') || btn.closest('.group') || btn.parentElement.parentElement;
                if (!item) return;

                const input = item.querySelector('input[type="file"]');
                if (input) {
                    input.value = '';
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    updateFileName(input);
                }
            }

            function updateSidebarFileList() {
                const fileInputs = document.querySelectorAll('input[type="file"]');
                const listContainer = document.getElementById('file-status-list');
                const countBadge = document.getElementById('attached-count-badge');
                let hasFiles = false;
                let fileCount = 0;
                let html = '';

                fileInputs.forEach(input => {
                    if (input.files && input.files.length > 0) {
                        hasFiles = true;
                        const item = input.closest('[data-file-item]') || input.closest('.group');
                        const labelEl = item?.querySelector('label');
                        const label = labelEl ? labelEl.childNodes[0].textContent.trim() : 'Document';

                        Array.from(input.files).forEach(file => {
                            fileCount++;
                            html += `
                                <div class="group relative bg-slate-50/70 p-2.5 rounded-xl border border-slate-200/80 hover:border-slate-300 hover:bg-white transition-all">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0 mr-2">
                                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider truncate">${label}</p>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <i class="fas fa-file-alt text-brand-primary text-xs" aria-hidden="true"></i>
                                                <p class="text-xs font-bold text-slate-800 truncate" title="${file.name}">${file.name}</p>
                                            </div>
                                        </div>
                                        <div class="shrink-0 text-emerald-600">
                                            <i class="fas fa-check-circle text-sm" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>`;
                        });
                    }
                });

                if (countBadge) {
                    countBadge.textContent = `${fileCount} ${fileCount === 1 ? 'File' : 'Files'}`;
                }

                if (!hasFiles) {
                    listContainer.innerHTML = '<div class="text-slate-400 italic text-xs p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">No files attached yet.</div>';
                } else {
                    listContainer.innerHTML = html;
                }
            }

            function toggleOtherCategory(select) {
                const container = document.getElementById('other_category_container');
                const input = document.getElementById('other_category');

                if (select.value === 'Other') {
                    container.classList.remove('hidden');
                    input.required = true;
                    input.focus();
                } else {
                    container.classList.add('hidden');
                    input.required = false;
                    input.value = '';
                }
            }

            // Submission Error Modal Controller
            let firstInvalidElement = null;

            function openSubmissionErrorModal() {
                const errorModal = document.getElementById('submission-error-modal');
                const errorBackdrop = document.getElementById('submission-error-backdrop');
                const errorPanel = document.getElementById('submission-error-panel');
                if (!errorModal) return;
                errorModal.classList.remove('hidden');
                setTimeout(() => {
                    if (errorBackdrop) errorBackdrop.classList.remove('opacity-0');
                    if (errorPanel) {
                        errorPanel.classList.remove('opacity-0', 'scale-95');
                        errorPanel.classList.add('opacity-100', 'scale-100');
                    }
                }, 10);
            }

            function closeSubmissionErrorModal() {
                const errorModal = document.getElementById('submission-error-modal');
                const errorBackdrop = document.getElementById('submission-error-backdrop');
                const errorPanel = document.getElementById('submission-error-panel');
                if (!errorModal) return;
                if (errorBackdrop) errorBackdrop.classList.add('opacity-0');
                if (errorPanel) {
                    errorPanel.classList.remove('opacity-100', 'scale-100');
                    errorPanel.classList.add('opacity-0', 'scale-95');
                }
                setTimeout(() => {
                    errorModal.classList.add('hidden');
                }, 300);
            }

            // Close modal on Escape key
            window.addEventListener('keydown', function (e) {
                const errorModal = document.getElementById('submission-error-modal');
                if (e.key === 'Escape' && errorModal && !errorModal.classList.contains('hidden')) {
                    closeSubmissionErrorModal();
                }
            });

            function clearFieldErrors() {
                document.querySelectorAll('.field-has-error').forEach(card => {
                    card.classList.remove('field-has-error', 'ring-2', 'ring-rose-500', 'border-rose-400', 'bg-rose-50/40');
                });
            }

            function findFieldElement(key) {
                if (!key) return null;
                // 1. Direct name match
                let el = document.querySelector(`[name="${key}"]`);
                if (el) return el;

                // 2. files.X or files.X.Y notation
                if (key.startsWith('files.')) {
                    const parts = key.split('.');
                    const reqId = parts[1];
                    el = document.querySelector(`input[name="files[${reqId}]"]`) ||
                         document.querySelector(`input[name="files[${reqId}][]"]`) ||
                         document.querySelector(`[data-field-name*="files[${reqId}]"]`);
                    if (el) return el;
                }

                // 3. Prefix match
                el = document.querySelector(`[name^="${key}"]`) ||
                     document.querySelector(`[data-field-name^="${key}"]`);
                return el;
            }

            function highlightField(el) {
                if (!el) return;
                const container = el.closest('[data-file-item]') || el.closest('.group') || el;
                if (container) {
                    container.classList.add('field-has-error', 'ring-2', 'ring-rose-500', 'border-rose-400', 'bg-rose-50/40', 'transition-all');
                }
            }

            function jumpToField(key) {
                closeSubmissionErrorModal();
                const el = findFieldElement(key);
                if (!el) return;
                const container = el.closest('[data-file-item]') || el.closest('.group') || el;
                setTimeout(() => {
                    container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    container.classList.add('animate-bounce');
                    setTimeout(() => container.classList.remove('animate-bounce'), 1000);
                    if (el.tagName === 'INPUT' && el.type === 'file') {
                        const chooseBtn = container.querySelector('label span');
                        if (chooseBtn) chooseBtn.focus();
                    } else if (typeof el.focus === 'function') {
                        el.focus();
                    }
                }, 320);
            }

            function reviewAndFixFields() {
                closeSubmissionErrorModal();
                if (firstInvalidElement) {
                    setTimeout(() => {
                        firstInvalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstInvalidElement.classList.add('animate-bounce');
                        setTimeout(() => firstInvalidElement.classList.remove('animate-bounce'), 1000);
                        const input = firstInvalidElement.querySelector('input, select, textarea') || firstInvalidElement;
                        if (input && typeof input.focus === 'function') input.focus();
                    }, 320);
                }
            }

            function showSubmissionErrorModal(data, statusCode) {
                clearFieldErrors();
                firstInvalidElement = null;

                const titleEl = document.getElementById('submission-error-title');
                const subtitleEl = document.getElementById('submission-error-subtitle');
                const badgeEl = document.getElementById('submission-error-badge');
                const messageEl = document.getElementById('submission-error-message');
                const listEl = document.getElementById('submission-error-list');
                const iconEl = document.getElementById('submission-error-icon');
                const iconBoxEl = document.getElementById('submission-error-icon-box');
                const actionBtn = document.getElementById('submission-error-action-btn');
                const actionText = document.getElementById('submission-error-action-text');

                if (!titleEl || !messageEl || !listEl) return;

                listEl.innerHTML = '';
                badgeEl.classList.add('hidden');

                // Default appearance
                iconBoxEl.className = 'w-12 h-12 rounded-2xl bg-rose-100 text-brand-primary flex items-center justify-center shrink-0 shadow-xs border border-rose-200/70';
                iconEl.className = 'fas fa-file-circle-exclamation text-xl';

                if (statusCode === 429) {
                    // Rate limit / Quota reached
                    titleEl.textContent = 'Submission Quota Reached';
                    subtitleEl.textContent = 'Hourly or daily submission limit has been reached.';
                    iconBoxEl.className = 'w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 shadow-xs border border-amber-200';
                    iconEl.className = 'fas fa-clock-rotate-left text-xl';
                    messageEl.textContent = data.error || data.message || 'Hourly submission limit reached. Please wait before submitting again.';

                    listEl.innerHTML = `
                        <div class="p-3.5 rounded-xl bg-amber-50/80 border border-amber-200/80 text-xs text-amber-900 space-y-1.5 leading-relaxed">
                            <div class="font-bold flex items-center gap-1.5 text-amber-950">
                                <i class="fas fa-shield-halved text-amber-600"></i>
                                <span>WMSU REO Rate Limit Protection</span>
                            </div>
                            <p>To preserve server availability for all university researchers, submissions are temporarily throttled. Your quota automatically restores when the cooldown window ends.</p>
                            <p class="font-semibold text-amber-950 mt-1">Note: Form validation corrections do not consume your quota.</p>
                        </div>
                    `;

                    if (actionBtn && actionText) {
                        actionText.textContent = 'View Quota Details';
                        actionBtn.onclick = function() {
                            closeSubmissionErrorModal();
                            const btn = document.querySelector('[x-data*="submitButtonStatus"]');
                            if (btn && window.Alpine) Alpine.$data(btn)?.openStatusModal?.();
                        };
                    }
                } else if (statusCode === 413) {
                    // File size limit exceeded
                    titleEl.textContent = 'File Size Limit Exceeded';
                    subtitleEl.textContent = 'The uploaded files exceed allowable package size.';
                    iconBoxEl.className = 'w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 shadow-xs border border-amber-200';
                    iconEl.className = 'fas fa-hard-drive text-xl';
                    messageEl.textContent = data.error || data.message || 'Total file size exceeds the university limit of 25MB.';

                    listEl.innerHTML = `
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-1.5 leading-relaxed">
                            <p class="font-bold text-slate-900">Tips for reducing upload size:</p>
                            <ul class="list-disc list-inside space-y-1 text-slate-600">
                                <li>Compress PDF documents using Adobe Acrobat or standard PDF optimizers.</li>
                                <li>Avoid high-resolution uncompressed image scans inside Word or PDF attachments.</li>
                                <li>Ensure individual files do not exceed 25MB.</li>
                            </ul>
                        </div>
                    `;

                    if (actionBtn && actionText) {
                        actionText.textContent = 'Review Files';
                        actionBtn.onclick = reviewAndFixFields;
                    }
                } else if (statusCode === 422 && data.errors) {
                    // Validation errors
                    titleEl.textContent = 'Submission Requirements Not Met';
                    subtitleEl.textContent = 'Please correct the flagged document issues below.';
                    messageEl.textContent = data.message || 'Some documents do not match the required format or are missing.';

                    const errorKeys = Object.keys(data.errors);
                    const totalCount = Object.values(data.errors).flat().length;

                    badgeEl.textContent = `${totalCount} ${totalCount === 1 ? 'Issue' : 'Issues'}`;
                    badgeEl.classList.remove('hidden');

                    let html = '';
                    errorKeys.forEach(key => {
                        const messages = Array.isArray(data.errors[key]) ? data.errors[key] : [data.errors[key]];
                        const el = findFieldElement(key);
                        if (el) {
                            highlightField(el);
                            if (!firstInvalidElement) {
                                firstInvalidElement = el.closest('[data-file-item]') || el.closest('.group') || el;
                            }
                        }

                        messages.forEach(msg => {
                            const isFileErr = key.startsWith('files') || msg.toLowerCase().includes('file') || msg.toLowerCase().includes('pdf') || msg.toLowerCase().includes('doc');
                            const iconClass = isFileErr ? 'fa-file-circle-xmark text-rose-500' : 'fa-circle-exclamation text-amber-500';
                            
                            html += `
                                <div class="group p-3 rounded-xl bg-slate-50/80 hover:bg-rose-50/40 border border-slate-200/80 hover:border-rose-200 transition-all flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-2.5 min-w-0 flex-1">
                                        <i class="fas ${iconClass} text-sm mt-0.5 shrink-0" aria-hidden="true"></i>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 leading-snug break-words">${msg}</span>
                                    </div>
                                    <button type="button" onclick="jumpToField('${key}')" class="shrink-0 text-[11px] font-bold text-brand-primary hover:text-brand-secondary bg-white hover:bg-rose-50 px-2.5 py-1 rounded-lg border border-slate-200 hover:border-rose-200 transition-all flex items-center gap-1 shadow-2xs cursor-pointer">
                                        <span>Fix</span>
                                        <i class="fas fa-arrow-right text-[9px]"></i>
                                    </button>
                                </div>
                            `;
                        });
                    });

                    listEl.innerHTML = html;

                    if (actionBtn && actionText) {
                        actionText.textContent = 'Review & Fix Files';
                        actionBtn.onclick = reviewAndFixFields;
                    }
                } else {
                    // General / Network errors
                    titleEl.textContent = data.title || 'Upload Interrupted';
                    subtitleEl.textContent = 'An issue occurred while processing your submission.';
                    messageEl.textContent = data.message || data.error || 'A network error occurred. Please verify your internet connection and try again.';

                    if (data.errors) {
                        let html = '';
                        Object.values(data.errors).flat().forEach(msg => {
                            html += `
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700">
                                    ${msg}
                                </div>
                            `;
                        });
                        listEl.innerHTML = html;
                    }

                    if (actionBtn && actionText) {
                        actionText.textContent = 'Dismiss';
                        actionBtn.onclick = closeSubmissionErrorModal;
                    }
                }

                openSubmissionErrorModal();
            }

            // Auto-clear field error highlight when user interacts with form inputs
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('submission-form');
                if (form) {
                    form.querySelectorAll('input, select, textarea').forEach(input => {
                        const clearOnError = function() {
                            const item = this.closest('[data-file-item]') || this.closest('.group');
                            if (item) {
                                item.classList.remove('field-has-error', 'ring-2', 'ring-rose-500', 'border-rose-400', 'bg-rose-50/40');
                            }
                        };
                        input.addEventListener('input', clearOnError);
                        input.addEventListener('change', clearOnError);
                    });
                }
            });

            // File Size Validation & Progress Tracking
            let isSubmitting = false;
            document.getElementById('submission-form').addEventListener('submit', function (e) {
                e.preventDefault();
                if (isSubmitting) return;

                const fileInputs = document.querySelectorAll('input[type="file"]');
                let totalSize = 0;
                const maxSize = 25 * 1024 * 1024; // 25MB

                fileInputs.forEach(input => {
                    if (input.files.length > 0) {
                        Array.from(input.files).forEach(file => {
                            totalSize += file.size;
                        });
                    }
                });

                if (totalSize > maxSize) {
                    const sizeInMB = (totalSize / (1024 * 1024)).toFixed(2);
                    showSubmissionErrorModal({
                        title: 'Total File Size Exceeded',
                        message: `The total upload size is ${sizeInMB} MB, which exceeds the university limit of 25 MB.`,
                        errors: {
                            'file_size': ['Please compress or optimize documents before submitting.']
                        }
                    }, 413);
                    return;
                }

                // Prevent double submission
                isSubmitting = true;
                const submitBtn = document.querySelector('button[type="submit"]');
                let originalBtnText = '';
                if (submitBtn) {
                    originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                }

                // Show Progress Modal
                const progressModal = document.getElementById('upload-progress-modal');
                const progressBar = document.getElementById('upload-progress-bar');
                const percentageText = document.getElementById('upload-percentage');
                const sizeText = document.getElementById('upload-size');
                
                if (progressModal) progressModal.classList.remove('hidden');

                // Prepare Form Data
                const formData = new FormData(this);
                const xhr = new XMLHttpRequest();

                // Helper to format bytes
                const formatBytes = (bytes, decimals = 2) => {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                }

                // Track Upload Progress
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        const percentComplete = Math.round((e.loaded / e.total) * 100);
                        if (progressBar) progressBar.style.width = percentComplete + '%';
                        if (percentageText) percentageText.textContent = percentComplete + '%';
                        
                        const loadedFormatted = formatBytes(e.loaded);
                        const totalFormatted = formatBytes(e.total);
                        if (sizeText) sizeText.textContent = `${loadedFormatted} / ${totalFormatted}`;
                    }
                });

                // Handle Completion
                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        // Success - Instantly update remaining count before redirect
                        const buttonElement = document.querySelector('[x-data*="submitButtonStatus"]');
                        if (buttonElement && window.Alpine) {
                            Alpine.$data(buttonElement)?.decrementRemaining?.();
                        }
                        
                        // Also update the widget if present
                        const widgetElement = document.querySelector('[x-data*="submissionStatusWidget"]');
                        if (widgetElement && window.Alpine) {
                            Alpine.$data(widgetElement)?.loadStatus?.();
                        }
                        
                        let redirectUrl = "{{ route('home') }}";
                        try {
                            const res = JSON.parse(xhr.responseText);
                            if (res.redirect) redirectUrl = res.redirect;
                        } catch (e) {}

                        // Redirect to home after brief moment
                        setTimeout(() => {
                            window.location.href = redirectUrl;
                        }, 300);
                    } else {
                        if (progressModal) progressModal.classList.add('hidden');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                            isSubmitting = false;
                        }

                        let errorData = null;
                        try {
                            errorData = JSON.parse(xhr.responseText);
                        } catch (e) {
                            errorData = { message: xhr.statusText || 'An unexpected error occurred during upload. Please try again.' };
                        }

                        showSubmissionErrorModal(errorData, xhr.status);
                    }
                };

                xhr.onerror = function () {
                    if (progressModal) progressModal.classList.add('hidden');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                        isSubmitting = false;
                    }
                    showSubmissionErrorModal({
                        title: 'Network Interruption',
                        message: 'A network connectivity error occurred. Please verify your internet connection and try again.'
                    }, 0);
                };

                xhr.open('POST', this.action, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.send(formData);
            });
        </script>

    <script>
        // AI Check Logic
        const checkBtn = document.getElementById('check-btn');
        const modal = document.getElementById('ai-modal');
        const modalBackdrop = document.getElementById('ai-modal-backdrop');
        const modalPanel = document.getElementById('ai-modal-panel');
        const modalLoader = document.getElementById('ai-modal-loader');
        const modalContent = document.getElementById('ai-modal-content');

        function openAiModal() {
            modal.classList.remove('hidden');
            // Animate in
            setTimeout(() => {
                modalBackdrop.classList.remove('opacity-0');
                modalPanel.classList.remove('opacity-0', 'scale-95');
                modalPanel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeAiModal() {
            // Animate out
            modalBackdrop.classList.add('opacity-0');
            modalPanel.classList.remove('opacity-100', 'scale-100');
            modalPanel.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        async function performAiCheck() {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            const formData = new FormData();
            let hasFiles = false;

            fileInputs.forEach(input => {
                if (input.files.length > 0) {
                    hasFiles = true;
                    Array.from(input.files).forEach(file => {
                        // Use the input name (e.g., files[application_form]) as the key
                        // This allows the backend to identify the document type
                        formData.append(input.name, file);
                    });
                }
            });

            if (!hasFiles) {
                showSubmissionErrorModal({
                    title: 'No Documents Attached',
                    message: 'Please attach at least one research document before running the Pre-submission AI Check.'
                }, 400);
                return;
            }

            // Show Modal & Loader
            openAiModal();
            modalLoader.classList.remove('hidden');
            modalContent.classList.add('hidden');
            modalContent.innerHTML = '';

            try {
                const response = await fetch("/submit/ai-check", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                // Hide Loader, Show Content
                modalLoader.classList.add('hidden');
                modalContent.classList.remove('hidden');

                if (data.results) {
                    let html = `
                        <div class="overflow-hidden rounded-xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Document</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Issues / Comments</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-200">
                    `;

                    data.results.forEach(item => {
                        const isPass = item.status.toLowerCase() === 'pass';
                        const statusBadge = isPass
                            ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i> Pass</span>`
                            : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i> Fail</span>`;

                        const issuesText = item.issues === 'All clear'
                            ? `<span class="text-slate-400 italic">No issues found.</span>`
                            : `<span class="text-slate-700">${item.issues}</span>`;

                        html += `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">
                                    ${item.document_name}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${statusBadge}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    ${issuesText}
                                </td>
                            </tr>
                        `;
                    });

                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    modalContent.innerHTML = html;
                } else if (data.feedback) {
                    // Fallback for old string response if any
                    let html = data.feedback
                        .replace(/\*\*(.*?)\*\*/g, '<strong class="text-slate-900">$1</strong>')
                        .replace(/\n/g, '<br>');
                    modalContent.innerHTML = html;
                } else {
                    modalContent.innerHTML = `<div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center gap-3"><i class="fas fa-exclamation-circle text-xl"></i> <div><strong>Error:</strong> ${data.error || 'Unknown error occurred.'}</div></div>`;
                }
            } catch (error) {
                modalLoader.classList.add('hidden');
                modalContent.classList.remove('hidden');
                modalContent.innerHTML = `<div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center gap-3"><i class="fas fa-exclamation-triangle text-xl"></i> <div><strong>System Error:</strong> AI Service Unavailable. Please try again later.</div></div>`;
            }
        }
    </script>

    <style>
        @keyframes progress {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(300%);
            }
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <!-- Upload Progress Modal -->
    <div id="upload-progress-modal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    <div class="px-8 py-10">
                        <div class="flex flex-col items-center text-center">
                            <!-- Animated Icon -->
                            <div class="relative w-24 h-24 mb-8">
                                <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                                <div class="absolute inset-0 border-4 border-brand-primary rounded-full border-t-transparent animate-spin"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-brand-primary animate-pulse" aria-hidden="true"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Uploading Research Files</h3>
                            <p class="text-slate-500 text-sm mb-8 max-w-sm">Please do not close this window or refresh the page. We are securely transferring your documents to our servers.</p>
                            <!-- Progress Bar Container -->
                            <div class="w-full bg-slate-100 rounded-full h-4 mb-4 relative overflow-hidden shadow-inner">
                                <div id="upload-progress-bar" class="bg-gradient-to-r from-brand-primary to-brand-secondary h-full w-0 transition-all duration-300 ease-out shadow-lg shadow-brand-primary/10 relative">
                                    <div class="absolute inset-0 bg-white/20 animate-shimmer"></div>
                                </div>
                            </div>
                            <!-- Progress Stats -->
                            <div class="flex justify-between w-full text-sm font-bold">
                                <span id="upload-percentage" class="text-brand-primary">0%</span>
                                <span id="upload-size" class="text-slate-500 font-medium">0 KB / 0 KB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submission Error & Validation Modal -->
    <div id="submission-error-modal" class="fixed inset-0 z-[70] hidden" aria-labelledby="submission-error-title" role="dialog" aria-modal="true">
        <div id="submission-error-backdrop" onclick="closeSubmissionErrorModal()" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity duration-300 opacity-0 cursor-pointer"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-4">
                <div id="submission-error-panel" class="pointer-events-auto relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all duration-300 opacity-0 scale-95 sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    
                    <!-- Header -->
                    <div class="px-6 pt-6 pb-4 sm:px-8 sm:pt-7 sm:pb-5 border-b border-slate-100 flex items-start justify-between gap-4 bg-gradient-to-b from-rose-50/60 to-white">
                        <div class="flex items-start gap-3.5">
                            <div id="submission-error-icon-box" class="w-12 h-12 rounded-2xl bg-rose-100 text-brand-primary flex items-center justify-center shrink-0 shadow-xs border border-rose-200/70">
                                <i id="submission-error-icon" class="fas fa-file-circle-exclamation text-xl" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 id="submission-error-title" class="text-lg sm:text-xl font-black text-slate-900 font-heading tracking-tight">Submission Notice</h3>
                                    <span id="submission-error-badge" class="text-[11px] font-extrabold bg-rose-100 text-rose-700 px-2.5 py-0.5 rounded-full border border-rose-200 hidden"></span>
                                </div>
                                <p id="submission-error-subtitle" class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Please review the requirements and correct the indicated items.</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeSubmissionErrorModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition-all flex items-center justify-center shrink-0" aria-label="Close modal">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-5 sm:px-8 space-y-4">
                        <!-- Primary Alert Message -->
                        <div id="submission-error-alert" class="p-3.5 rounded-2xl bg-rose-50/90 border border-rose-200/80 text-xs sm:text-sm text-rose-900 leading-relaxed flex items-start gap-2.5">
                            <i id="submission-error-alert-icon" class="fas fa-circle-exclamation text-rose-600 mt-0.5 shrink-0 text-base" aria-hidden="true"></i>
                            <div id="submission-error-message" class="flex-1 font-medium">Some required documents are missing or formatted incorrectly.</div>
                        </div>

                        <!-- Dynamic List of Errors -->
                        <div id="submission-error-list" class="space-y-2 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                            <!-- Items injected dynamically -->
                        </div>

                        <!-- Policy Footer inside body -->
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center justify-between text-xs text-slate-600">
                            <div class="flex items-center gap-2 min-w-0">
                                <i class="fas fa-file-circle-check text-emerald-600 shrink-0"></i>
                                <span class="truncate">Accepted: <strong class="text-slate-800 font-semibold">PDF, DOC, DOCX</strong></span>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-500 shrink-0">25MB per file</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 sm:px-8 sm:py-5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-2.5">
                        <button type="button" onclick="closeSubmissionErrorModal()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-200/70 hover:text-slate-900 font-bold text-xs sm:text-sm transition-all text-center">
                            Dismiss
                        </button>
                        <button type="button" id="submission-error-action-btn" onclick="reviewAndFixFields()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-brand-primary hover:bg-brand-secondary text-white font-bold text-xs sm:text-sm shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-2">
                            <span id="submission-error-action-text">Review &amp; Fix Files</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite;
        }
    </style>

    <!-- Quota Status Details Modal (Modal Only, triggered from sidebar) -->
    <x-submission-status-widget :show-trigger="false" />
</x-user_layout>