<x-user_layout>
    <x-skeleton-loader />

    <div id="page-content" style="display: none;" class="min-h-screen bg-surface-50 py-8 px-4 sm:px-6 lg:px-8"
        x-data="submissionForm()"
        x-cloak>

        <div class="max-w-7xl mx-auto animate-[fadeInUp_0.5s_ease-out]">

            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading">New Submission</h1>
                <p class="text-slate-500 mt-2 max-w-2xl mx-auto">Submit your research protocol for ethics review. Please
                    ensure all details are accurate and required documents are attached.</p>
            </div>

            <!-- Submission Status Widget -->
            <x-submission-status-widget />

            <form action="{{ route('submit.title') }}" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 lg:grid-cols-3 gap-8" id="submission-form">
                @csrf

                <!-- Left Column: Form Details -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Step 1: Protocol Details -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-[#0f172a] p-4 border-b border-slate-800">
                            <h2 class="text-white font-bold text-lg flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#8B0000] text-white text-sm font-bold">1</span>
                                Protocol Details
                            </h2>
                        </div>

                        <div class="p-6 space-y-6">

                            {{-- ===== Project Classification ===== --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">Project Type <span class="text-red-500">*</span></label>
                                <input type="hidden" name="project_type" id="project_type_input" x-bind:value="projectType" required>
                                <div class="grid grid-cols-2 gap-4">

                                    {{-- Funded Research Button --}}
                                    <button type="button"
                                        @click="projectType = 'Funded Research'; courseSubType = ''; fundingSubType = ''"
                                        :class="projectType === 'Funded Research'
                                            ? 'border-[#8B0000] bg-red-50 text-[#8B0000] shadow-sm shadow-red-100'
                                            : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-[#8B0000] hover:text-[#8B0000]'"
                                        class="flex flex-col items-center justify-center gap-2 p-5 border-2 rounded-2xl transition-all duration-200 font-bold text-sm cursor-pointer">
                                        <div :class="projectType === 'Funded Research' ? 'bg-[#8B0000] text-white' : 'bg-slate-200 text-slate-600'"
                                            class="w-10 h-10 rounded-xl flex items-center justify-center transition-all">
                                            <i class="fas fa-money-bill-wave text-base"></i>
                                        </div>
                                        Funded Research
                                        <p class="text-xs font-normal text-slate-400 text-center leading-tight">Institutional or External Funding</p>
                                    </button>

                                    {{-- Course Requirement Button --}}
                                    <button type="button"
                                        @click="projectType = 'Course Requirement'; fundingSubType = ''"
                                        :class="projectType === 'Course Requirement'
                                            ? 'border-[#8B0000] bg-red-50 text-[#8B0000] shadow-sm shadow-red-100'
                                            : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-[#8B0000] hover:text-[#8B0000]'"
                                        class="flex flex-col items-center justify-center gap-2 p-5 border-2 rounded-2xl transition-all duration-200 font-bold text-sm cursor-pointer">
                                        <div :class="projectType === 'Course Requirement' ? 'bg-[#8B0000] text-white' : 'bg-slate-200 text-slate-600'"
                                            class="w-10 h-10 rounded-xl flex items-center justify-center transition-all">
                                            <i class="fas fa-graduation-cap text-base"></i>
                                        </div>
                                        Course Requirement
                                        <p class="text-xs font-normal text-slate-400 text-center leading-tight">Thesis, Dissertation, or Graduate Study</p>
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
                                <div x-show="projectType === 'Funded Research'" x-transition style="display:none;" class="mt-4">
                                    <input type="hidden" name="funding_type" x-bind:value="fundingSubType">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Funding Source <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach($categories->where('classification', 'Funded Research') as $cat)
                                        <button type="button" @click="fundingSubType = '{{ addslashes($cat->name) }}'; syncFee('{{ addslashes($cat->name) }}')"
                                            :class="fundingSubType === '{{ addslashes($cat->name) }}' ? 'border-[#8B0000] bg-red-50 text-[#8B0000]' : 'border-slate-200 text-slate-600 hover:border-slate-400'"
                                            class="py-2.5 px-4 border-2 rounded-xl text-sm font-semibold transition-all">
                                            <i class="fas {{ $getIcon($cat->name) }} mr-2"></i>{{ $cat->name }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Course Requirement Sub-options --}}
                                <div x-show="projectType === 'Course Requirement'" x-transition style="display:none;" class="mt-4">
                                    <input type="hidden" name="course_type" x-bind:value="courseSubType">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Type of Course Requirement <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach($categories->where('classification', 'Course Requirement') as $cat)
                                        <button type="button" @click="courseSubType = '{{ addslashes($cat->name) }}'; syncFee('{{ addslashes($cat->name) }}')"
                                            :class="courseSubType === '{{ addslashes($cat->name) }}' ? 'border-[#8B0000] bg-red-50 text-[#8B0000]' : 'border-slate-200 text-slate-600 hover:border-slate-400'"
                                            class="py-2.5 px-3 border-2 rounded-xl text-xs font-semibold transition-all text-center">
                                            <i class="fas {{ $getIcon($cat->name) }} mb-1 block text-base"></i>{{ $cat->name }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>

                                <p x-show="!projectType" class="mt-2 text-xs text-slate-400 italic" style="display:none;">Please select a project type to continue.</p>
                            </div>

                            {{-- ===== Study Protocol Title ===== --}}
                            <div class="group">
                                <label for="Study_Protocol_title"
                                    class="block text-sm font-bold text-slate-700 mb-2">Study Protocol Title</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-heading text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                                    </div>
                                    <input type="text" name="Study_Protocol_title" id="Study_Protocol_title"
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-[#8B0000]/10 transition-all duration-200"
                                        placeholder="Enter Study Protocol Title" required>
                                </div>
                            </div>

                            <!-- Research Category -->
                            <div class="group">
                                <label for="Research_Category"
                                    class="block text-sm font-bold text-slate-700 mb-2">Review Fees</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-tag text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                                    </div>
                                    <select name="Research_Category" id="Research_Category"
                                        onchange="toggleOtherCategory(this)"
                                        class="w-full pl-11 pr-10 py-3 bg-slate-100/80 border border-slate-200 rounded-xl text-slate-600 focus:outline-none transition-all duration-200 appearance-none pointer-events-none cursor-not-allowed"
                                        required tabindex="-1">
                                        <option value="" disabled selected>Select Review Fees</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}">
                                                {{ $category->name }} - ₱ {{ number_format($category->fee, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-slate-400"></i>
                                    </div>
                                </div>
                                <!-- Other Category Input -->
                                <div id="other_category_container" class="hidden mt-3 animate-[fadeIn_0.3s_ease-out]">
                                    <label for="other_category"
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Specify
                                        Other Category</label>
                                    <input type="text" name="other_category" id="other_category"
                                        class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000]/10 transition-all"
                                        placeholder="Please specify...">
                                </div>
                            </div>

                            <!-- Research Type -->
                            <div class="group">
                                <label for="research_type" class="block text-sm font-bold text-slate-700 mb-2">Research
                                    Type</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-flask text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                                    </div>
                                    <select name="research_type" id="research_type"
                                        class="w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-[#8B0000]/10 transition-all duration-200 appearance-none cursor-pointer"
                                        required>
                                        <option value="" disabled selected>Select Research Type</option>
                                        <option value="Biomedical Studies">Biomedical Studies</option>
                                        <option value="Health Operations Research">Health Operations Research</option>
                                        <option value="Social Research">Social Research</option>
                                        <option value="Public Health Research">Public Health Research</option>
                                        <option value="Clinical Trials">Clinical Trials</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-slate-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Name of Adviser (mandatory for Course Requirement, hidden for Funded Research) -->
                            <div class="group" x-show="projectType === 'Course Requirement'" x-transition style="display:none;">
                                <label for="Adviser" class="block text-sm font-bold text-slate-700 mb-2">
                                    Name of Adviser <span class="text-red-500">*</span>
                                    <span class="ml-2 text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full border border-amber-200">Required for Students</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-user-tie text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                                    </div>
                                    <input type="text" name="Adviser" id="Adviser"
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-[#8B0000]/10 transition-all duration-200"
                                        placeholder="Enter Adviser Full Name"
                                        :required="projectType === 'Course Requirement'">
                                </div>
                            </div>
                        </div> <!-- End of content div -->
                    </div> <!-- End of Step 1 container div (FIXED LAYOUT) -->

                    <!-- Step 2: Required Documents -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-[#0f172a] p-4 border-b border-slate-800">
                            <h2 class="text-white font-bold text-lg flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#8B0000] text-white text-sm font-bold">2</span>
                                Required Research Documents
                            </h2>
                        </div>

                        <div class="p-6 space-y-6">

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

                                    // Construct label
                                    $label = $requirement->name;
                                    if ($requirement->file_type) {
                                        $label .= ' (' . $requirement->file_type . ')';
                                    }
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

                <!-- Right Column: Submission Summary & AI Check -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">

                        <!-- Submission Summary Card -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden relative">
                            <div class="bg-[#0f172a] p-4 border-b border-slate-800">
                                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                                    <i class="fas fa-clipboard-check text-[#8B0000]"></i> Submission Summary
                                </h3>
                            </div>

                            <div class="p-6 space-y-4">
                                <!-- File Status List -->
                                <div class="space-y-2 mb-6">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Attached Files</p>
                                    <div id="file-status-list" class="space-y-2 text-sm max-h-48 overflow-y-auto">
                                        <div class="text-slate-400 italic text-xs">No files attached yet.</div>
                                    </div>
                                </div>

                                <!-- Status & Submit Section -->
                                <div x-data="submitButtonStatus()"
                                     x-init="loadStatus()"
                                     x-cloak
                                     class="space-y-3">
                                    
                                    <!-- Status Block -->
                                    <div class="bg-white rounded-xl border border-slate-200 p-4 cursor-pointer hover:shadow-md transition-all"
                                         @click="openStatusModal()">
                                        <div class="flex items-center gap-4">
                                            <!-- Icon -->
                                            <div class="flex-shrink-0">
                                                <i class="material-icons text-2xl" :class="canSubmit ? 'text-green-600' : 'text-red-600'" 
                                                   x-text="canSubmit ? 'check_circle' : 'block'"></i>
                                            </div>
                                            <!-- Text -->
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-slate-900" x-text="canSubmit ? 'Ready to Submit' : 'Cannot Submit Right Now'"></h4>
                                                <p class="text-xs text-slate-500 mt-1">Click to see details</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button - Only visible if can submit -->
                                    <button type="submit"
                                        x-show="canSubmit"
                                        :disabled="!canSubmit"
                                        class="w-full text-white font-bold text-lg py-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-3 bg-[#8B0000] hover:bg-red-800 hover:shadow-xl hover:-translate-y-0.5 shadow-lg shadow-red-900/20 cursor-pointer">
                                        <i class="material-icons">send</i>
                                        <span>Submit Research</span>
                                    </button>

                                    <!-- Helper Text (only when can submit) -->
                                    <p class="text-xs text-slate-600 text-center" x-show="canSubmit">
                                        Your submission is complete and ready to be reviewed
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Help Card -->
                        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
                            <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i> Need Help?
                            </h4>
                            <p class="text-sm text-blue-800 mb-4">
                                Download the official templates to ensure your documents meet the requirements.
                            </p>
                            <a href="{{ route('resources') }}"
                                class="text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                Go to Downloadables <i class="fas fa-arrow-right text-xs"></i>
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
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl opacity-0 scale-95"
                    id="ai-modal-panel">

                    <!-- Header -->
                    <div class="bg-[#0f172a] px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modal-title">
                            <i class="fas fa-robot text-[#8B0000]"></i> AI Compliance Check Results
                        </h3>
                        <button type="button" onclick="closeAiModal()"
                            class="text-slate-400 hover:text-white transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                        <!-- Loader State -->
                        <div id="ai-modal-loader" class="hidden flex flex-col items-center justify-center py-12">
                            <div class="relative w-24 h-24 mb-6">
                                <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                                <div
                                    class="absolute inset-0 border-4 border-[#8B0000] rounded-full border-t-transparent animate-spin">
                                </div>
                                <i
                                    class="fas fa-magic absolute inset-0 flex items-center justify-center text-2xl text-[#8B0000] animate-pulse"></i>
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
                            class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 font-bold hover:bg-slate-50 transition-colors">
                            Close
                        </button>
                        <button type="button" onclick="closeAiModal()"
                            class="px-4 py-2 bg-[#8B0000] text-white rounded-lg font-bold hover:bg-red-800 transition-colors shadow-lg shadow-red-900/20">
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
                        const widget = document.querySelector('[x-data*="submissionStatusWidget"]');
                        if (widget && widget.__x) {
                            widget.__x.$data.openModal?.();
                        }
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
                const container = input.closest('.flex');
                const fileNameDisplay = container.querySelector('.file-name');
                const clearBtn = container.querySelector('.clear-btn');

                if (input.files && input.files.length > 0) {
                    const count = input.files.length;
                    fileNameDisplay.textContent = count === 1 ? input.files[0].name : `${count} files selected`;
                    fileNameDisplay.classList.add('text-slate-900', 'font-medium');
                    fileNameDisplay.classList.remove('text-slate-400', 'italic');

                    if (clearBtn) clearBtn.classList.remove('hidden');
                } else {
                    fileNameDisplay.textContent = 'No file chosen';
                    fileNameDisplay.classList.remove('text-slate-900', 'font-medium');
                    fileNameDisplay.classList.add('text-slate-400', 'italic');

                    if (clearBtn) clearBtn.classList.add('hidden');
                }
                updateSidebarFileList();
            }

            function clearFile(btn) {
                const container = btn.closest('.flex');
                const input = container.querySelector('input[type="file"]');

                // Clear the input
                input.value = '';

                // Trigger update to reset UI
                updateFileName(input);
            }

            function updateSidebarFileList() {
                const fileInputs = document.querySelectorAll('input[type="file"]');
                const listContainer = document.getElementById('file-status-list');
                let hasFiles = false;
                let html = '';

                fileInputs.forEach(input => {
                    if (input.files.length > 0) {
                        hasFiles = true;
                        // Get label text from the component
                        const label = input.closest('.border').querySelector('label').innerText.replace('*', '').trim().split('(')[0];

                        Array.from(input.files).forEach(file => {
                            html += `
                                            <div class="group relative bg-slate-50 p-2.5 rounded-lg border border-slate-200 hover:border-slate-300 transition-all">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1 min-w-0 mr-2">
                                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">${label}</p>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <i class="fas fa-file-alt text-[#8B0000] text-xs"></i>
                                                            <p class="text-xs font-bold text-slate-700 truncate" title="${file.name}">${file.name}</p>
                                                        </div>
                                                    </div>
                                                    <div class="shrink-0">
                                                        <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                                    </div>
                                                </div>
                                            </div>`;
                        });
                    }
                });

                if (!hasFiles) {
                    listContainer.innerHTML = '<div class="text-slate-400 italic text-xs">No files attached yet.</div>';
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

            // File Size Validation & Progress Tracking
            document.getElementById('submission-form').addEventListener('submit', function (e) {
                e.preventDefault();

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
                    alert(`Total file size (${sizeInMB} MB) exceeds the maximum limit of 25 MB.\n\nPlease reduce the file sizes or upload fewer files.`);
                    return;
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
                        if (buttonElement && buttonElement.__x) {
                            // Call decrementRemaining on the Alpine component
                            buttonElement.__x.$data.decrementRemaining?.();
                        }
                        
                        // Also update the widget if present
                        const widgetElement = document.querySelector('[x-data*="submissionStatusWidget"]');
                        if (widgetElement && widgetElement.__x) {
                            widgetElement.__x.$data.loadStatus?.();
                        }
                        
                        // Redirect to home after brief moment
                        setTimeout(() => {
                            window.location.href = "{{ route('home') }}";
                        }, 300);
                    } else {
                        if (progressModal) progressModal.classList.add('hidden');
                        alert('An error occurred during upload. Please try again.');
                    }
                };

                xhr.onerror = function () {
                    if (progressModal) progressModal.classList.add('hidden');
                    alert('A network error occurred. Please check your connection.');
                };

                xhr.open('POST', this.action, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
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
                alert('Please attach at least one document before running the AI check.');
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
            border-radius: 3px;
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
                                <div class="absolute inset-0 border-4 border-[#8B0000] rounded-full border-t-transparent animate-spin"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-[#8B0000] animate-bounce"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Uploading Research Files</h3>
                            <p class="text-slate-500 text-sm mb-8 max-w-sm">Please do not close this window or refresh the page. We are securely transferring your documents to our servers.</p>
                            <!-- Progress Bar Container -->
                            <div class="w-full bg-slate-100 rounded-full h-4 mb-4 relative overflow-hidden shadow-inner">
                                <div id="upload-progress-bar" class="bg-gradient-to-r from-[#8B0000] to-red-600 h-full w-0 transition-all duration-300 ease-out shadow-lg relative">
                                    <div class="absolute inset-0 bg-white/20 animate-shimmer"></div>
                                </div>
                            </div>
                            <!-- Progress Stats -->
                            <div class="flex justify-between w-full text-sm font-bold">
                                <span id="upload-percentage" class="text-[#8B0000]">0%</span>
                                <span id="upload-size" class="text-slate-400">0 KB / 0 KB</span>
                            </div>
                        </div>
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

    </div>
</x-user_layout>