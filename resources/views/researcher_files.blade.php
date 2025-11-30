<x-user_layout>
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="flex flex-col md:flex-row justify-between items-start mb-8 border-b border-slate-200 pb-6">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-sm mb-1">
                    <a href="{{ route('home') }}" class="hover:text-brand-primary transition-colors"><i class="fas fa-arrow-left"></i> Back to Titles</a>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 font-heading">Document Manager</h2>
                <p class="text-slate-500 text-sm">Manage uploads for: <span class="font-semibold text-brand-primary">{{ $researchTitle->Study_Protocol_title }}</span></p>
            </div>
        </div>

        @if(session('success'))
            <div class="animate-[fadeInUp_0.3s_ease-out] bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-600"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
        @foreach($researchTitle->files as $file)
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md transition-shadow group">
                @php 
                    $isPdf = $file->filetype === 'pdf' || $file->filetype === 'recommendation letter' || $file->filetype === 'certificate'; 
                @endphp
                <div class="p-4 flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0 text-[#8B0000]">
                            @if($isPdf)
                                <i class="fas fa-file-pdf text-xl"></i>
                            @else
                                <i class="fas fa-file-alt text-xl"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-700 text-sm truncate" title="{{ $file->filename }}">
                                {{ $file->filename }}
                            </h4>
                            <p class="text-xs text-slate-400 mt-0.5 uppercase tracking-wider font-bold">
                                {{ $file->filetype === 'recommendation letter' ? 'Recommendation Letter' : ($file->filetype === 'certificate' ? 'Clearance Certificate' : $file->filetype) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ asset($file->filepath) }}" target="_blank" class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-colors" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>

                <!-- Preview Area -->
                <div class="bg-slate-50 border-t border-slate-100 h-48 relative group-hover:bg-slate-100 transition-colors">
                    @if($isPdf)
                        <iframe src="{{ asset($file->filepath) }}" class="w-full h-full border-0 pointer-events-none"></iframe>
                        <a href="{{ asset($file->filepath) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/5 transition-colors">
                            <span class="px-4 py-2 bg-white rounded-lg shadow-sm text-xs font-bold text-slate-700 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                                Click to View
                            </span>
                        </a>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                            <i class="fas fa-eye-slash text-3xl mb-2"></i>
                            <span class="text-xs">Preview not available</span>
                        </div>
                    @endif
                </div>

                <!-- Update Form (Hidden for Generated Files) -->
                @if($file->filetype !== 'recommendation letter' && $file->filetype !== 'certificate')
                    <div class="p-3 bg-white border-t border-slate-100">
                        <form action="{{ route('update.file', $researchTitle->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                            @csrf
                            <input type="hidden" name="file_id" value="{{ $file->id }}">
                            <label class="flex-1 cursor-pointer">
                                <span class="block w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-500 text-center hover:bg-slate-100 hover:text-slate-700 transition-colors truncate">
                                    <i class="fas fa-sync-alt mr-1"></i> Update Revision
                                </span>
                                <input type="file" name="file" class="hidden" onchange="this.form.submit()">
                            </label>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
        </div>
    </main>
</x-user_layout>