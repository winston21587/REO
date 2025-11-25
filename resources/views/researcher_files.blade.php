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
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden transition-all hover:shadow-xl">
                
                <div class="bg-slate-50 p-4 border-b border-slate-100 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $file->filetype === 'pdf' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                            <i class="fas {{ $file->filetype === 'pdf' ? 'fa-file-pdf' : 'fa-file-word' }} text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">{{ ucfirst($file->category ?? 'Document') }}</h3>
                            <p class="text-xs text-slate-400 truncate max-w-[200px]">{{ $file->filename }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-white border border-slate-200 text-xs font-bold text-slate-500 shadow-sm">
                        v1.0
                    </span>
                </div>

                <div class="bg-slate-100 p-1 relative group">
                    @if($file->filetype === 'pdf')
                        <iframe src="{{ asset($file->filepath) }}" class="w-full h-[500px] rounded-b-lg border-0 bg-white"></iframe>
                    @else
                        <iframe src="https://docs.google.com/gview?url={{ urlencode(asset($file->filepath)) }}&embedded=true" class="w-full h-[500px] rounded-b-lg border-0 bg-white"></iframe>
                    @endif
                    
                    <div class="absolute inset-0 bg-brand-primary/5 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>

                <div class="p-6 bg-white border-t border-slate-100">
                    <form action="{{ route('update.file', $researchTitle->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="file_id" value="{{ $file->id }}">
                        
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Update Revision</label>
                        
                        <div class="flex gap-3">
                            <div class="relative flex-grow">
                                <input type="file" name="file" accept=".pdf,.doc,.docx" required 
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    onchange="document.getElementById('fileName-{{$file->id}}').textContent = this.files[0].name">
                                <div class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-3 text-sm text-slate-500">
                                    <i class="fas fa-cloud-upload-alt text-slate-400"></i>
                                    <span id="fileName-{{$file->id}}" class="truncate">Choose new file...</span>
                                </div>
                            </div>
                            <button type="submit" class="px-6 py-2.5 bg-brand-primary text-white font-bold rounded-xl hover:bg-red-800 transition-colors shadow-lg shadow-red-900/20 flex items-center gap-2">
                                <i class="fas fa-sync-alt text-xs"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
        </div>
    </main>
</x-user_layout>