<x-user_layout>
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-200 pb-8">
            <div class="space-y-2">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 text-slate-500 hover:text-[#8B0000] transition-colors mb-2 text-sm font-medium">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-red-50 transition-colors">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Back to Titles
                </a>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Document Manager</h2>
                <div class="flex items-center gap-2 text-slate-500 text-sm">
                    <span>Manage uploads for:</span>
                    <span class="font-bold text-[#8B0000] bg-red-50 px-3 py-1 rounded-full border border-red-100">
                        {{ $researchTitle->Study_Protocol_title }}
                    </span>
                </div>
            </div>

            @php
                $canSubmit = in_array($researchTitle->Status, ['Waiting for Revision', 'Incomplete']);
                $isRevision = $researchTitle->Status === 'Waiting for Revision';
                $submitLabel = $isRevision ? 'Submit Revisions' : 'Submit Corrections';
                $submitIcon = $isRevision ? 'fa-paper-plane' : 'fa-check-circle';
            @endphp

            @if($canSubmit)
            <button onclick="document.getElementById('revisionModal').classList.remove('hidden')" 
                    class="group relative inline-flex items-center gap-3 bg-[#8B0000] text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-red-900/30 hover:-translate-y-1 transition-all duration-300">
                <i class="fas {{ $submitIcon }} text-lg group-hover:rotate-12 transition-transform"></i>
                <span>{{ $submitLabel }}</span>
            </button>
            @endif
        </div>

        <!-- Revision/Correction Modal -->
        @if($canSubmit)
        <div id="revisionModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('revisionModal').classList.add('hidden')"></div>
            
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                        <form action="{{ route('submit.revisions', $researchTitle->id) }}" method="POST">
                            @csrf
                            
                            <!-- Premium Header -->
                            <div class="px-8 py-6 bg-[#8B0000] relative overflow-hidden">
                                <!-- Background Pattern/Icon -->
                                <div class="absolute -right-6 -top-6 text-white/10 pointer-events-none">
                                    <i class="fas fa-paper-plane text-[150px] rotate-12"></i>
                                </div>
                                
                                <div class="relative z-10 flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold text-white tracking-tight flex items-center gap-3">
                                            <span class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                                <i class="fas fa-paper-plane text-lg text-white"></i>
                                            </span>
                                            {{ $submitLabel }}
                                        </h3>
                                        <p class="text-red-100 text-sm mt-2 font-medium opacity-90">
                                            @if($isRevision)
                                                Upload revised documents & submit.
                                            @else
                                                Submit corrected documents for review.
                                            @endif
                                        </p>
                                    </div>
                                    <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')" 
                                        class="text-white/70 hover:text-white hover:bg-white/20 rounded-lg p-1 transition-all">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="px-8 py-8 bg-white">
                                <div class="space-y-6">
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex gap-4 items-start">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0 text-blue-600">
                                            <i class="fas fa-info-circle text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-sm">Review Process</h4>
                                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                                @if($isRevision)
                                                    Your submission will be marked as <strong>Waiting for Approval</strong> until reviewed.
                                                @else
                                                    Submitting will notify the Research Ethics Office to process your corrections.
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="revision_message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Add a Note <span class="text-slate-400 font-normal normal-case">(Optional)</span>
                                        </label>
                                        <div class="relative group">
                                            <textarea name="revision_message" id="revision_message" rows="3" 
                                                class="w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-[#8B0000] focus:ring-[#8B0000] text-sm placeholder-slate-400 py-3 px-4 resize-none transition-all group-hover:border-slate-300"
                                                placeholder="{{ $isRevision ? 'Briefly describe your changes...' : 'E.g., Added missing signature page...' }}"></textarea>
                                            <div class="absolute bottom-3 right-3 text-slate-300 pointer-events-none group-focus-within:text-[#8B0000] transition-colors">
                                                <i class="fas fa-pen text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="bg-slate-50 px-8 py-5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100">
                                <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')"
                                    class="inline-flex w-full justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 hover:text-slate-800 sm:w-auto transition-all">
                                    Cancel
                                </button>
                                <button type="submit" onclick="return confirm('Are you sure you want to submit?')"
                                    class="inline-flex w-full justify-center items-center gap-2 rounded-xl bg-[#8B0000] px-8 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-red-900/40 hover:-translate-y-0.5 sm:w-auto transition-all duration-300">
                                    <span>Confirm Submission</span>
                                    <i class="fas fa-arrow-right text-xs opacity-70"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
            <div class="animate-[fadeInUp_0.3s_ease-out] bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl mb-8 flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-900">Success</h4>
                    <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @php
            // Separate files into two groups
            // Merge files (uploaded by researcher) and adminFiles (generated by admin) to find letters
            $allFiles = $researchTitle->files->merge($researchTitle->adminFiles ?? collect());
            
            $letters = $allFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->sortByDesc('created_at');
            $otherFiles = $researchTitle->files->whereNotIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter']);
        @endphp

        <!-- Recommendation Letters Section -->
        @if($letters->isNotEmpty())
        <div class="mb-10">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-envelope-open-text text-emerald-600"></i>
                Recommendation Letters
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($letters as $file)
                    <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
                        <div class="p-5 flex items-start gap-4 border-b border-emerald-50 bg-emerald-50/30">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                <i class="fas fa-certificate text-xl"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-800 text-sm leading-snug truncate mb-1" title="{{ $file->filename }}">
                                    {{ $file->filename }}
                                </h4>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">
                                    Official Document
                                </span>
                            </div>
                        </div>

                         <!-- Preview Area -->
                        <div class="relative bg-slate-50 flex-1 min-h-[150px] border-b border-slate-100 group-hover:bg-slate-100 transition-colors overflow-hidden">
                             <iframe src="{{ asset($file->filepath) }}#toolbar=0&navpanes=0&scrollbar=0" class="w-full h-full border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
                             <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                                <a href="{{ asset($file->filepath) }}" target="_blank" class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-emerald-600 hover:text-white flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i> View Fullscreen
                                </a>
                            </div>
                        </div>

                        <!-- Read-Only Actions -->
                        <div class="p-4 bg-white">
                            <a href="{{ asset($file->filepath) }}" download class="block w-full py-2.5 px-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 hover:text-emerald-900 rounded-xl text-sm font-bold text-center transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($otherFiles->isNotEmpty())
        <div class="mb-6">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-folder-open text-[#8B0000]"></i>
                Protocol Documents
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($otherFiles as $file)
                @php 
                    $isPdf = in_array($file->filetype, ['pdf']);
                    
                    $fileTypeLabel = match($file->filetype) {
                        'certificate' => 'Clearance Certificate', // Should not be here if filtered, but kept for safety
                        default => $file->filetype
                    };

                    $iconClass = 'fa-file-pdf text-[#8B0000]';
                    $bgClass = 'bg-red-50';
                @endphp

                <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 hover:border-slate-300 transition-all duration-300 flex flex-col">
                    <!-- Header -->
                    <div class="p-5 flex items-start gap-4 border-b border-slate-50 bg-white relative z-10">
                        <div class="w-12 h-12 rounded-xl {{ $bgClass }} flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas {{ $iconClass }} text-xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-bold text-slate-800 text-sm leading-snug truncate mb-1" title="{{ $file->filename }}">
                                {{ $file->filename }}
                            </h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $bgClass }} text-slate-600 border border-slate-100">
                                {{ $fileTypeLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- Preview Area -->
                    <div class="relative bg-slate-50 flex-1 min-h-[200px] border-b border-slate-100 group-hover:bg-slate-100 transition-colors overflow-hidden">
                        @if($isPdf)
                            <iframe src="{{ asset($file->filepath) }}#toolbar=0&navpanes=0&scrollbar=0" class="w-full h-full border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
                            
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                                <a href="{{ asset($file->filepath) }}" target="_blank" class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-[#8B0000] hover:text-white flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i> View Fullscreen
                                </a>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-8 text-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                    <i class="fas fa-eye-slash text-2xl text-slate-300"></i>
                                </div>
                                <span class="text-xs font-medium text-slate-500">Preview not available</span>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="p-4 bg-white space-y-3">
                        <!-- Update Form -->
                        @php
                            // Strict check: Upload allowed if status is 'Waiting for Revision' OR 'Incomplete'
                            $canUpload = in_array($researchTitle->Status, ['Waiting for Revision', 'Incomplete']);
                        @endphp

                        @if($canUpload)
                            <form action="{{ route('update.file', $researchTitle->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="file_id" value="{{ $file->id }}">
                                <label class="block cursor-pointer">
                                    <div class="w-full py-2.5 px-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-[#8B0000] hover:bg-red-50 text-slate-500 hover:text-[#8B0000] text-sm font-bold text-center transition-all duration-200 flex items-center justify-center gap-2 group/upload">
                                        <i class="fas fa-cloud-upload-alt group-hover/upload:animate-bounce"></i>
                                        <span>Upload New Version</span>
                                    </div>
                                    <input type="file" name="file" class="hidden" onchange="this.form.submit()" accept=".{{ $file->filetype }}">
                                </label>
                            </form>
                        @endif

                        <a href="{{ asset($file->filepath) }}" download class="block w-full py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 rounded-xl text-sm font-bold text-center transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        @endif
    </main>
</x-user_layout>