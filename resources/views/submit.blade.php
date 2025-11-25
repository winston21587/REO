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

                                    <!-- AI Loader & Results Removed (Moved to Modal) -->
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

    <!-- AI Results Modal -->
    <div id="ai-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity opacity-0" id="ai-modal-backdrop"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal Panel -->
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl opacity-0 scale-95" id="ai-modal-panel">
                    
                    <!-- Header -->
                    <div class="bg-[#0f172a] px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modal-title">
                            <i class="fas fa-robot text-[#8B0000]"></i> AI Compliance Check Results
                        </h3>
                        <button type="button" onclick="closeAiModal()" class="text-slate-400 hover:text-white transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        
                        <!-- Loader State -->
                        <div id="ai-modal-loader" class="hidden flex flex-col items-center justify-center py-12">
                            <div class="relative w-24 h-24 mb-6">
                                <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                                <div class="absolute inset-0 border-4 border-[#8B0000] rounded-full border-t-transparent animate-spin"></div>
                                <i class="fas fa-magic absolute inset-0 flex items-center justify-center text-2xl text-[#8B0000] animate-pulse"></i>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 mb-2">Analyzing Documents...</h4>
                            <p class="text-slate-500 text-center max-w-md">Our AI is scanning your attached files for missing signatures, formatting errors, and compliance with REO standards.</p>
                        </div>

                        <!-- Results State -->
                        <div id="ai-modal-content" class="hidden prose prose-slate max-w-none">
                            <!-- Content injected via JS -->
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" onclick="closeAiModal()" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 font-bold hover:bg-slate-50 transition-colors">
                            Close
                        </button>
                        <button type="button" onclick="closeAiModal()" class="px-4 py-2 bg-[#8B0000] text-white rounded-lg font-bold hover:bg-red-800 transition-colors shadow-lg shadow-red-900/20">
                            I Understand
                        </button>
                    </div>
                </div>
            </div>
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
            const fileNameDisplay = input.closest('.flex').querySelector('.file-name');
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
    </script>
    @endverbatim

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
                        formData.append('research_files[]', file);
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
                const response = await fetch("{{ route('submit.ai_check') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
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
            0% { transform: translateX(-100%); }
            100% { transform: translateX(300%); }
        }
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-user_layout>