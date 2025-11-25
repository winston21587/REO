<x-admin_layout>
    <div class="max-w-7xl mx-auto py-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Top Navigation & Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.NewSubmissions') }}" class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:border-[#8B0000] hover:text-[#8B0000] transition-all shadow-sm">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider border border-slate-200">
                            {{ $researchTitle->reoc_code ?? 'PENDING-ID' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-md bg-yellow-50 text-yellow-700 text-[10px] font-bold uppercase tracking-wider border border-yellow-100 flex items-center gap-1">
                            <i class="fas fa-clock text-[10px]"></i> Pending
                        </span>
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-900 font-heading leading-tight max-w-2xl">
                        {{ $researchTitle->Study_Protocol_title }}
                    </h1>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button class="px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm flex items-center gap-2">
                    <i class="fas fa-download"></i> Download All
                </button>
                <button class="px-4 py-2 bg-[#8B0000] text-white font-bold text-sm rounded-xl hover:bg-[#6d0000] shadow-lg shadow-red-900/20 transition-all flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit Details
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-[calc(100vh-200px)]">
            
            <!-- Left Column: Document Preview (2/3 width) -->
            <div class="lg:col-span-2 flex flex-col gap-4 h-full">
                <!-- File Tabs -->
                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                    @forelse($researchTitle->files as $index => $file)
                    <button class="px-4 py-2 rounded-t-lg text-sm font-bold flex items-center gap-2 whitespace-nowrap transition-colors {{ $index === 0 ? 'bg-slate-800 text-white' : 'bg-white text-slate-500 hover:text-slate-800' }}">
                        <i class="fas fa-file-pdf {{ $index === 0 ? 'text-red-400' : 'text-slate-400' }}"></i> 
                        {{ $file->filename ?? 'Protocol_Document.pdf' }}
                    </button>
                    @empty
                    <button class="px-4 py-2 rounded-t-lg bg-slate-800 text-white text-sm font-bold flex items-center gap-2">
                        <i class="fas fa-file-pdf text-red-400"></i> No Files Uploaded
                    </button>
                    @endforelse
                </div>

                <!-- PDF Viewer Container -->
                <div class="flex-1 bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-800 relative group">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-file-pdf text-4xl text-slate-600"></i>
                            </div>
                            <p class="text-slate-500 font-medium">Document Preview Unavailable</p>
                            <p class="text-slate-600 text-xs mt-2">Please download the file to view contents.</p>
                            <button class="mt-6 px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold rounded-lg transition-all border border-slate-700">
                                <i class="fas fa-download mr-2"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Metadata & Actions (1/3 width) -->
            <div class="flex flex-col gap-6 h-full overflow-y-auto pr-2 custom-scrollbar">
                
                <!-- Researcher Card -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Principal Investigator</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#8B0000] to-red-900 text-white flex items-center justify-center font-bold text-lg shadow-md">
                            {{ substr($researchTitle->author->first_name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-base">{{ $researchTitle->author->first_name ?? 'Unknown' }} {{ $researchTitle->author->last_name ?? '' }}</p>
                            <p class="text-xs text-slate-500 font-medium">{{ $researchTitle->author->college ?? 'External Researcher' }}</p>
                            <div class="flex gap-2 mt-2">
                                <a href="mailto:{{ $researchTitle->author->email ?? '#' }}" class="text-xs bg-slate-50 text-slate-600 px-2 py-1 rounded border border-slate-200 hover:border-slate-300 transition-colors">
                                    <i class="fas fa-envelope mr-1"></i> Email
                                </a>
                                <a href="#" class="text-xs bg-slate-50 text-slate-600 px-2 py-1 rounded border border-slate-200 hover:border-slate-300 transition-colors">
                                    <i class="fas fa-user mr-1"></i> Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submission Details -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Submission Details</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                            <span class="text-sm text-slate-500 font-medium">Category</span>
                            <span class="text-sm font-bold text-slate-800">{{ $researchTitle->Research_Category }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                            <span class="text-sm text-slate-500 font-medium">Submitted</span>
                            <span class="text-sm font-bold text-slate-800">{{ $researchTitle->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">Completeness</span>
                            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-md border border-green-100">
                                <i class="fas fa-check-circle mr-1"></i> 100%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Panel -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex-1 flex flex-col">
                    <div class="mb-4 pb-4 border-b border-slate-100">
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fas fa-gavel text-[#8B0000]"></i> Initial Classification
                        </h4>
                        <p class="text-xs text-slate-500 mt-1">Determine the review level (SOP 04/05/06).</p>
                    </div>

                    <form action="#" class="space-y-4 flex-1 flex flex-col">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Review Type</label>
                            <div class="grid grid-cols-1 gap-2">
                                <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="radio" name="review_type" value="Exempt" class="text-green-600 focus:ring-green-500">
                                    <div>
                                        <span class="block text-sm font-bold text-slate-700">Exempt</span>
                                        <span class="block text-[10px] text-slate-400">Minimal risk</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="radio" name="review_type" value="Expedited" class="text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <span class="block text-sm font-bold text-slate-700">Expedited</span>
                                        <span class="block text-[10px] text-slate-400">2 Reviewers</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="radio" name="review_type" value="Full" class="text-[#8B0000] focus:ring-[#8B0000]">
                                    <div>
                                        <span class="block text-sm font-bold text-slate-700">Full Board</span>
                                        <span class="block text-[10px] text-slate-400">High risk</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mt-auto pt-4">
                            <button class="w-full bg-[#8B0000] text-white font-bold py-3 rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-900 transition-all transform hover:-translate-y-0.5">
                                Confirm & Assign
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-admin_layout>