<x-admin_layout title="Page Content Manager">
    <div class="max-w-7xl mx-auto py-8 relative">
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2 animate-[fadeIn_0.5s]">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.cms.content.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <!-- Quick Navigation Sidebar -->
                <div class="hidden lg:block w-64 flex-shrink-0 sticky top-4">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-4 bg-slate-50 border-b border-slate-100 font-bold text-slate-700">
                            <i class="fas fa-list-ul mr-2 text-[#8B0000]"></i> Pages
                        </div>
                        <nav class="p-2 space-y-1">
                            <a href="#section-resources" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-red-50 hover:text-[#8B0000] font-medium transition-colors">
                                <i class="fas fa-download w-5 text-center mr-1"></i> Resources
                            </a>
                            <a href="#section-instructions" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-red-50 hover:text-[#8B0000] font-medium transition-colors">
                                <i class="fas fa-book w-5 text-center mr-1"></i> Instructions
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="flex-1 space-y-8 w-full">
                    
                    <!-- Resources (Downloadables) -->
                    <div id="section-resources" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24">
                        <div class="flex items-center justify-between mb-8 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-download"></i>
                                </div>
                                Resources Page
                            </h3>
                            <input type="hidden" name="section" value="resources" disabled> <!-- Dynamic section handling in Controller -->
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Page Title</label>
                                    <input type="text" name="resources_title" value="{{ $contents['resources_title'] ?? 'Resources & Downloads' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Introduction Text</label>
                                    <textarea name="resources_intro" rows="5" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white outline-none">{{ $contents['resources_intro'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Header Image</label>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                    @if(isset($contents['resources_header_image']))
                                        <img src="{{ asset($contents['resources_header_image']) }}" class="w-full h-40 object-cover rounded-lg mb-3">
                                    @endif
                                    <input type="file" name="resources_header_image" accept="image/*" class="w-full text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div id="section-instructions" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24">
                         <div class="flex items-center justify-between mb-8 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-book"></i>
                                </div>
                                Instructions Page
                            </h3>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Page Title</label>
                                    <input type="text" name="instructions_title" value="{{ $contents['instructions_title'] ?? 'Submission Guidelines' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Introduction Text</label>
                                    <textarea name="instructions_intro" rows="5" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white outline-none">{{ $contents['instructions_intro'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Header Image</label>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                    @if(isset($contents['instructions_header_image']))
                                        <img src="{{ asset($contents['instructions_header_image']) }}" class="w-full h-40 object-cover rounded-lg mb-3">
                                    @endif
                                    <input type="file" name="instructions_header_image" accept="image/*" class="w-full text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Fixed Save Button -->
            <div class="fixed bottom-6 right-8 z-50 animate-[fadeInUp_0.5s]">
                <button type="submit" class="group bg-[#8B0000] text-white px-6 py-3 rounded-full font-bold hover:bg-red-800 transition-all shadow-xl shadow-red-900/20 transform hover:-translate-y-1 hover:scale-105 flex items-center gap-3 ring-2 ring-white border border-[#8B0000]">
                    <span class="bg-white/20 p-1.5 rounded-full group-hover:bg-white/30 transition-colors">
                        <i class="fas fa-save text-sm"></i>
                    </span>
                    <span class="text-sm tracking-wide">Save Changes</span>
                </button>
            </div>

        </form>
    </div>
</x-admin_layout>
