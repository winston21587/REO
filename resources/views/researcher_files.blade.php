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
            <form action="{{ route('submit.revisions', $researchTitle->id) }}" method="POST">
                @csrf
                <button type="submit" class="group relative inline-flex items-center gap-3 bg-gradient-to-r from-[#8B0000] to-red-700 text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:shadow-red-900/30 hover:-translate-y-1 transition-all duration-300" onclick="return confirm('Are you sure you have updated all necessary files?')">
                    <span class="absolute inset-0 bg-white/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <i class="fas fa-paper-plane text-lg group-hover:rotate-12 transition-transform"></i>
                    <span>Submit Revisions</span>
                </button>
            </form>
            @endif
        </div>

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

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($researchTitle->files as $file)
            @php 
                $isPdf = in_array($file->filetype, ['pdf', 'recommendation letter', 'certificate', 'Result of Review (Admin Generated)']);
                
                $fileTypeLabel = match($file->filetype) {
                    'recommendation letter' => 'Recommendation Letter',
                    'certificate' => 'Clearance Certificate',
                    'Result of Review (Admin Generated)' => 'Result of Review',
                    default => $file->filetype
                };

                $iconClass = match($file->filetype) {
                    'recommendation letter' => 'fa-envelope-open-text text-purple-600',
                    'certificate' => 'fa-certificate text-amber-500', 
                    'Result of Review (Admin Generated)' => 'fa-clipboard-check text-emerald-600',
                    default => 'fa-file-pdf text-[#8B0000]'
                };
                
                $bgClass = match($file->filetype) {
                    'recommendation letter' => 'bg-purple-50',
                    'certificate' => 'bg-amber-50',
                    'Result of Review (Admin Generated)' => 'bg-emerald-50',
                    default => 'bg-red-50'
                };
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
                    @if(!in_array($file->filetype, ['recommendation letter', 'certificate', 'Result of Review (Admin Generated)']))
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
    </main>
</x-user_layout>