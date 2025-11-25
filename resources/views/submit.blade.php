<x-user_layout>
    <div class="min-h-screen bg-surface-50 py-8 px-4 sm:px-6 lg:px-8" x-data="submissionForm()">
        
        <div class="max-w-7xl mx-auto animate-[fadeInUp_0.5s_ease-out]">
            
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading">New Submission</h1>
                <p class="text-slate-500 mt-2 max-w-2xl mx-auto">Submit your research protocol for ethics review. Please ensure all details are accurate and required documents are attached.</p>
            </div>

            <form action="{{ route('submit.title') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8" id="submission-form">
                @csrf

                <!-- Left Column: Form Details -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Step 1: Protocol Details -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-[#0f172a] p-4 border-b border-slate-800">
                            <h2 class="text-white font-bold text-lg flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#8B0000] text-white text-sm font-bold">1</span>
                                Protocol Details
                            </h2>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Study Protocol Title -->
                            <div class="group">
                                <label for="Study_Protocol_title" class="block text-sm font-bold text-slate-700 mb-2">Study Protocol Title</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-heading text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                                    </div>
                                    <input type="text" name="Study_Protocol_title" id="Study_Protocol_title" 
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-[#8B0000]/10 transition-all duration-200"
                                        placeholder="Enter study protocol title" required>
                                </div>
                            </div>

                            <!-- Research Category -->
                            <div class="group">
                                <label for="Research_Category" class="block text-sm font-bold text-slate-700 mb-2">Research Category</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                                    </div>
                                    <select name="Research_Category" id="Research_Category" 
                                        class="w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-[#8B0000]/10 transition-all duration-200 appearance-none cursor-pointer" required>
                                        <option value="" disabled selected>Select research category</option>
                                        <option value="Faculty Research">Faculty Research</option>
                                        <option value="Graduate Student Research">Graduate Student Research</option>
                                        <option value="Undergraduate Student Research">Undergraduate Student Research</option>
                                        <option value="Externally Funded Research">Externally Funded Research</option>
                                        <option value="Institutional Research">Institutional Research</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-slate-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Name of Adviser -->
                            <div class="group">
                                <label for="Adviser" class="block text-sm font-bold text-slate-700 mb-2">Name of Adviser</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-user-tie text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                                    </div>
                                    <input type="text" name="Adviser" id="Adviser" 
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-[#8B0000]/10 transition-all duration-200"
                                        placeholder="Enter adviser's name" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Required Documents -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-[#0f172a] p-4 border-b border-slate-800">
                            <h2 class="text-white font-bold text-lg flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#8B0000] text-white text-sm font-bold">2</span>
                                Required Research Documents
                            </h2>
                        </div>

                        <div class="p-6 space-y-6">
                            
                            <!-- Application Form -->
                            <x-file-upload-item 
                                name="files[application_form]" 
                                label="Application Form for Research Ethics Review - WMSU-REO-FR-001 (PDF)" 
                                accept=".pdf" required="true" />

                            <!-- Research Protocol -->
                            <x-file-upload-item 
                                name="files[research_protocol]" 
                                label="Research Protocol / Proposal (with page and line numbers, PDF)" 
                                accept=".pdf" required="true" />

                            <!-- Technical Clearance -->
                            <x-file-upload-item 
                                name="files[technical_clearance]" 
                                label="Technical Review Clearance (with panel signatures, PDF)" 
                                accept=".pdf" required="true" />

                            <!-- Data Collection -->
                            <x-file-upload-item 
                                name="files[data_collection_instruments]" 
                                label="Data Collection Instrument/s (with page and line numbers, PDF)" 
                                accept=".pdf" required="true" />

                            <!-- Informed Consent -->
                            <x-file-upload-item 
                                name="files[informed_consent]" 
                                label="Informed Consent / Assent (with page and line numbers, PDF)" 
                                accept=".pdf" required="true" />

                            <!-- CV -->
                            <x-file-upload-item 
                                name="files[curriculum_vitae]" 
                                label="Curriculum Vitae of Researcher/s (PDF)" 
                                accept=".pdf" required="true" />

                            <!-- Study Protocol Assessment -->
                            <x-file-upload-item 
                                name="files[study_protocol_form]" 
                                label="Completed Study Protocol Assessment Form - WMSU-REO-FR-004 (Word)" 
                                accept=".doc,.docx" required="true" />

                            <!-- Informed Consent Assessment -->
                            <x-file-upload-item 
                                name="files[informed_consent_form]" 
                                label="Completed Informed Consent Assessment Form - WMSU-REO-FR-005 (Word)" 
                                accept=".doc,.docx" required="true" />

                            <!-- Exempt Review Assessment -->
                            <x-file-upload-item 
                                name="files[exempt_review_form]" 
                                label="Completed Exempt Review Assessment Form - WMSU-REO-FR-006 (Word)" 
                                accept=".doc,.docx" required="true" />

                            <!-- Supplementary Docs -->
                            <x-file-upload-item 
                                name="files[supplementary_docs][]" 
                                label="Supplementary Documents (NCIP Clearance, MOA, MOU, etc., PDF, optional)" 
                                accept=".pdf" required="false" multiple="true" />

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
                            
                            <div class="p-6">
                                <!-- File Status List -->
                                <div class="space-y-3 mb-6">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Attached Files</p>
                                    <div id="file-status-list" class="space-y-2 text-sm">
                                        <div class="text-slate-400 italic text-xs">No files attached yet.</div>
                                    </div>
                                </div>

                                <!-- AI Check Section -->
                                <div class="border-t border-slate-100 pt-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-bold text-slate-800 flex items-center gap-2">
                                            <i class="fas fa-robot text-[#8B0000]"></i> AI Compliance Check
                                        </h4>
                                        <span class="text-[10px] font-bold bg-green-100 text-green-700 px-2 py-1 rounded-full">BETA</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mb-4">
                                        Scan all attached files for missing signatures or formatting errors before submitting.
                                    </p>

                                    <button type="button" onclick="performAiCheck()" id="check-btn" class="w-full py-3 bg-white border-2 border-[#8B0000] text-[#8B0000] font-bold rounded-xl hover:bg-red-50 transition-all shadow-sm flex items-center justify-center gap-2 group">
                                        <i class="fas fa-magic group-hover:animate-pulse"></i> Check with AI
                                    </button>

                                    <!-- AI Loader -->
                                    <div id="ai-loader" class="hidden mt-4">
                                        <div class="flex items-center gap-2 text-xs text-[#8B0000] font-bold mb-2">
                                            <span class="w-2 h-2 bg-[#8B0000] rounded-full animate-ping"></span> 
                                            <span>Analyzing Documents...</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-[#8B0000] h-full rounded-full animate-[progress_1.5s_infinite_linear] w-1/3"></div>
                                        </div>
                                    </div>

                                    <!-- AI Results -->
                                    <div id="ai-results" class="hidden mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200 text-sm text-slate-700 max-h-60 overflow-y-auto custom-scrollbar">
                                        <div id="ai-feedback-content" class="prose prose-sm prose-red"></div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="mt-6 pt-6 border-t border-slate-100">
                                    <button type="submit" class="w-full bg-[#8B0000] text-white font-bold text-lg py-4 rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-3">
                                        <span>Submit Research</span>
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
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
                            <a href="{{ route('resources') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                Go to Downloadables <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Reusable File Upload Component -->
    @verbatim
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('submissionForm', () => ({
                // Logic for file inputs can go here if needed
            }));
        });

        function updateFileName(input) {
            const fileNameDisplay = input.parentElement.querySelector('.file-name');
            if (input.files && input.files.length > 0) {
                const count = input.files.length;
                fileNameDisplay.textContent = count === 1 ? input.files[0].name : `${count} files selected`;
                fileNameDisplay.classList.add('text-slate-900', 'font-medium');
                fileNameDisplay.classList.remove('text-slate-400', 'italic');
            } else {
                fileNameDisplay.textContent = 'No file chosen';
                fileNameDisplay.classList.remove('text-slate-900', 'font-medium');
                fileNameDisplay.classList.add('text-slate-400', 'italic');
            }
            updateSidebarFileList();
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
                    const label = input.closest('.border').querySelector('label').innerText.replace('*', '').trim().split('(')[0]; // Clean up label
                    
                    html += `
                        <div class="flex items-center justify-between bg-slate-50 p-2 rounded border border-slate-100">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <span class="truncate max-w-[150px] font-medium text-slate-700">${label}</span>
                            </div>
                            <span class="text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded font-bold">Ready</span>
                        </div>`;
                }
            });

            if (!hasFiles) {
                listContainer.innerHTML = '<div class="text-slate-400 italic text-xs">No files attached yet.</div>';
            } else {
                listContainer.innerHTML = html;
            }
        }
    </script>
    @endverbatim

    <script>
        // AI Check Logic
        const checkBtn = document.getElementById('check-btn');
        const loader = document.getElementById('ai-loader');
        const results = document.getElementById('ai-results');
        const feedback = document.getElementById('ai-feedback-content');

        async function performAiCheck() {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            const formData = new FormData();
            let hasFiles = false;

            fileInputs.forEach(input => {
                if (input.files.length > 0) {
                    hasFiles = true;
                    Array.from(input.files).forEach(file => {
                        formData.append('research_files[]', file);
                    });
                }
            });

            if (!hasFiles) {
                alert('Please attach at least one document before running the AI check.');
                return;
            }

            checkBtn.disabled = true;
            checkBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            checkBtn.classList.add('opacity-75');
            loader.classList.remove('hidden');
            results.classList.add('hidden');

            try {
                const response = await fetch("{{ route('submit.ai_check') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                    body: formData
                });

                const data = await response.json();
                loader.classList.add('hidden');
                results.classList.remove('hidden');
                checkBtn.disabled = false;
                checkBtn.innerHTML = '<i class="fas fa-magic"></i> Check with AI';
                checkBtn.classList.remove('opacity-75');

                if (data.feedback) {
                    let html = data.feedback
                        .replace(/\*\*(.*?)\*\*/g, '<strong class="text-slate-900">$1</strong>')
                        .replace(/\n/g, '<br>');
                    feedback.innerHTML = html; 
                } else {
                    feedback.innerHTML = `<div class="p-3 bg-red-50 text-red-700 rounded-lg border border-red-100 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Error: ${data.error}</div>`;
                }
            } catch (error) {
                loader.classList.add('hidden');
                checkBtn.disabled = false;
                checkBtn.innerHTML = '<i class="fas fa-magic"></i> Check with AI';
                checkBtn.classList.remove('opacity-75');
                alert('AI Service Unavailable. Please try again later.');
            }
        }
    </script>
    
    <style>
        @keyframes progress {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(300%); }
        }
        /* Custom Scrollbar for AI Results */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-user_layout>