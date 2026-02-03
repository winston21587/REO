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

            @if($researchTitle->Status === 'Waiting for Revision')
            <button onclick="document.getElementById('revisionModal').classList.remove('hidden')" 
                    class="group relative inline-flex items-center gap-3 bg-gradient-to-r from-[#8B0000] to-red-700 text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:shadow-red-900/30 hover:-translate-y-1 transition-all duration-300">
                <span class="absolute inset-0 bg-white/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <i class="fas fa-paper-plane text-lg group-hover:rotate-12 transition-transform"></i>
                <span>Submit Revisions</span>
            </button>
            @endif
        </div>

        <!-- Revision Note Modal -->
        @if($researchTitle->Status === 'Waiting for Revision')
        <div id="revisionModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('revisionModal').classList.add('hidden')"></div>
            
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <form action="{{ route('submit.revisions', $researchTitle->id) }}" method="POST">
                            @csrf
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <i class="fas fa-pencil-alt text-[#8B0000]"></i>
                                    </div>
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                        <h3 class="text-xl font-semibold leading-6 text-slate-900" id="modal-title">Submit Revisions</h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-slate-500 mb-4">
                                                You are about to submit your revised documents. Please confirm your changes and include a brief note describing what you have updated.
                                            </p>
                                            <label for="revision_message" class="block text-sm font-medium text-slate-700 mb-1">Revision Note (Optional)</label>
                                            <textarea name="revision_message" id="revision_message" rows="4" 
                                                class="w-full rounded-xl border-slate-200 shadow-sm focus:border-[#8B0000] focus:ring-[#8B0000] text-sm"
                                                placeholder="E.g., Updated the methodology section as requested..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="submit" onclick="return confirm('Are you sure you want to submit?')"
                                    class="inline-flex w-full justify-center rounded-xl bg-[#8B0000] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto transition-colors">
                                    Submit
                                </button>
                                <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')"
                                    class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                                    Cancel
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
                            // Strict check: Upload only allowed if status is 'Waiting for Revision'
                            $canUpload = ($researchTitle->Status === 'Waiting for Revision');
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
                                    <input type="file" name="file" class="hidden" onchange="this.form.submit()">
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