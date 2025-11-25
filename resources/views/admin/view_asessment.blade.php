<x-admin_layout>
    <x-slot name="title">Review Submission</x-slot>

    <div class="flex flex-col h-[calc(100vh-100px)] gap-6">
        
        <!-- Top Bar -->
        <div class="flex justify-between items-start shrink-0">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                    <a href="{{ route('admin.NewSubmissions') }}" class="hover:text-[#8B0000] transition-colors"><i class="fas fa-arrow-left mr-1"></i> Back to Queue</a>
                    <span>/</span>
                    <span>Submission #{{ $research->id }}</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 leading-tight max-w-4xl">{{ $research->title }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold border border-slate-200">
                    Status: {{ $research->status }}
                </span>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6 min-h-0">
            
            <!-- Left Column: Document Viewer (Placeholder) -->
            <div class="lg:col-span-2 bg-slate-900 rounded-xl overflow-hidden shadow-lg flex flex-col relative group">
                <!-- Mock Toolbar -->
                <div class="bg-slate-800 p-3 flex items-center justify-between text-slate-400 text-sm border-b border-slate-700">
                    <div class="flex gap-4">
                        <button class="hover:text-white"><i class="fas fa-search-plus"></i></button>
                        <button class="hover:text-white"><i class="fas fa-search-minus"></i></button>
                    </div>
                    <div>Page 1 of 10</div>
                    <button class="hover:text-white"><i class="fas fa-download"></i> Download</button>
                </div>
                
                <!-- Viewer Area -->
                <div class="flex-1 bg-slate-500/10 flex items-center justify-center relative">
                    <div class="text-center p-10">
                        <i class="fas fa-file-pdf text-6xl text-slate-600 mb-4"></i>
                        <p class="text-slate-400 font-medium">Document Preview Unavailable</p>
                        <p class="text-slate-500 text-sm mt-2">Please download the file to view contents.</p>
                        <button class="mt-6 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-sm font-bold transition-colors">
                            Download PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Assessment & Decision -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Assessment & Decision</h3>
                </div>
                
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    
                    <!-- Metadata -->
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-slate-500">Researcher</span>
                            <span class="font-medium text-slate-900">{{ $research->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-slate-500">Department</span>
                            <span class="font-medium text-slate-900">{{ $research->department ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-50">
                            <span class="text-slate-500">Submitted</span>
                            <span class="font-medium text-slate-900">{{ $research->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- AI Suggestion -->
                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-robot text-blue-600 text-xs"></i>
                            </div>
                            <span class="text-xs font-bold text-blue-700 uppercase tracking-wide">AI Recommendation</span>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed">
                            Based on keyword analysis, this protocol matches criteria for:
                            <strong class="block mt-1 text-blue-800 text-lg">{{ $ai_suggestion }}</strong>
                        </p>
                    </div>

                    <!-- Decision Form -->
                    <form action="{{ route('admin.submitDecision', $research->id) }}" method="POST" class="space-y-6 mt-4">
                        @csrf
                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-700 uppercase">Recommendation Letter / Remarks</label>
                            <textarea name="recommendation_letter" rows="5" 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] outline-none text-sm transition-all resize-none" 
                                placeholder="Draft your review notes or recommendation letter here..."></textarea>
                            <p class="text-[10px] text-slate-400">This content will be attached to the notification email.</p>
                        </div>

                        <div class="space-y-3 pt-4 border-t border-slate-100">
                            <label class="text-xs font-bold text-slate-700 uppercase">Final Decision</label>
                            
                            <div class="grid grid-cols-1 gap-3">
                                <button type="submit" name="review_type" value="Exempt" 
                                    class="group relative flex items-center justify-between w-full p-4 bg-white border border-slate-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all text-left">
                                    <div>
                                        <span class="block font-bold text-slate-700 group-hover:text-green-700">Exempt Review</span>
                                        <span class="text-xs text-slate-400 group-hover:text-green-600/70">Low risk, standard protocols</span>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 group-hover:border-green-500 group-hover:bg-green-500 transition-all"></div>
                                </button>

                                <button type="submit" name="review_type" value="Expedited" 
                                    class="group relative flex items-center justify-between w-full p-4 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all text-left">
                                    <div>
                                        <span class="block font-bold text-slate-700 group-hover:text-blue-700">Expedited Review</span>
                                        <span class="text-xs text-slate-400 group-hover:text-blue-600/70">Minimal risk, minor procedures</span>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 group-hover:border-blue-500 group-hover:bg-blue-500 transition-all"></div>
                                </button>

                                <button type="submit" name="review_type" value="Full" 
                                    class="group relative flex items-center justify-between w-full p-4 bg-white border border-slate-200 rounded-xl hover:border-[#8B0000] hover:bg-red-50 transition-all text-left">
                                    <div>
                                        <span class="block font-bold text-slate-700 group-hover:text-[#8B0000]">Full Board Review</span>
                                        <span class="text-xs text-slate-400 group-hover:text-red-600/70">High risk, vulnerable subjects</span>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 group-hover:border-[#8B0000] group-hover:bg-[#8B0000] transition-all"></div>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin_layout>