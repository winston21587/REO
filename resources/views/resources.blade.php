<x-user_layout>
    <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="text-center mb-16">
            @if(isset($contents['resources_header_image']))
                <img src="{{ asset($contents['resources_header_image']) }}" class="w-full h-48 object-cover rounded-2xl mb-8 shadow-sm">
            @endif
            <h1 class="text-4xl font-extrabold text-slate-900 font-heading tracking-tight">{{ $contents['resources_title'] ?? 'Resource Library' }}</h1>
            <p class="text-slate-500 mt-4 text-lg max-w-2xl mx-auto">{{ $contents['resources_intro'] ?? 'Download the official application and assessment forms required for your ethics review submission.' }}</p>
        </div>

        <div class="flex items-center gap-4 mb-12">
            <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-slate-200 flex-1"></div>
            <span class="text-xs font-bold text-[#8B0000] uppercase tracking-widest bg-red-50 px-4 py-1.5 rounded-full border border-red-100 shadow-sm">Mandatory Forms</span>
            <div class="h-px bg-gradient-to-r from-slate-200 via-slate-200 to-transparent flex-1"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Application Form -->
            <div class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-[#8B0000]/30 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-[#8B0000] text-white text-[10px] font-bold px-4 py-1.5 rounded-bl-xl shadow-md z-10">FR.002</div>
                <!-- Decorative Circle -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-red-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="flex items-start gap-6 relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-50 to-white border border-red-100 flex items-center justify-center text-[#8B0000] shadow-sm group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                        <span class="material-symbols-outlined text-3xl">description</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors">Application Form</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">The primary document for initiating a research ethics review. Required for all new submissions.</p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wide">DOCX</span>
                        <span class="text-[10px] text-slate-400 font-medium">154 KB</span>
                    </div>
                    <a href="{{ asset('src/2-FR.002 Application Form.docx') }}" class="flex items-center gap-2 text-sm font-bold text-white bg-[#8B0000] px-4 py-2 rounded-lg hover:bg-red-800 hover:shadow-lg hover:shadow-red-900/20 transition-all transform hover:-translate-y-0.5">
                        <span>Download</span>
                        <span class="material-symbols-outlined text-lg">download</span>
                    </a>
                </div>
            </div>

            <!-- Study Protocol Assessment -->
            <div class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-[#8B0000]/30 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-slate-700 text-white text-[10px] font-bold px-4 py-1.5 rounded-bl-xl shadow-md z-10">FR.004</div>
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-slate-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="flex items-start gap-6 relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-100 flex items-center justify-center text-slate-700 shadow-sm group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                        <span class="material-symbols-outlined text-3xl">assignment</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors">Study Protocol Assessment</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">Self-assessment checklist for your research protocol. Helps ensure compliance with standards.</p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wide">DOCX</span>
                        <span class="text-[10px] text-slate-400 font-medium">89 KB</span>
                    </div>
                    <a href="{{ asset('src/4-FR.004 Study Protocol  Assessment Form - Copy.docx') }}" class="flex items-center gap-2 text-sm font-bold text-slate-700 bg-slate-100 px-4 py-2 rounded-lg hover:bg-[#8B0000] hover:text-white hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        <span>Download</span>
                        <span class="material-symbols-outlined text-lg">download</span>
                    </a>
                </div>
            </div>

            <!-- Informed Consent Form -->
            <div class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-[#8B0000]/30 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-slate-700 text-white text-[10px] font-bold px-4 py-1.5 rounded-bl-xl shadow-md z-10">FR.005</div>
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-slate-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="flex items-start gap-6 relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-100 flex items-center justify-center text-slate-700 shadow-sm group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                        <span class="material-symbols-outlined text-3xl">handshake</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors">Informed Consent Form</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">Guidelines and assessment for participant consent. Critical for studies involving human subjects.</p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wide">DOCX</span>
                        <span class="text-[10px] text-slate-400 font-medium">112 KB</span>
                    </div>
                    <a href="{{ asset('src/5 -FR.005 Informed Consent Assessment Form.docx') }}" class="flex items-center gap-2 text-sm font-bold text-slate-700 bg-slate-100 px-4 py-2 rounded-lg hover:bg-[#8B0000] hover:text-white hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        <span>Download</span>
                        <span class="material-symbols-outlined text-lg">download</span>
                    </a>
                </div>
            </div>

            <!-- Exempt Review Form -->
            <div class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-[#8B0000]/30 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-slate-700 text-white text-[10px] font-bold px-4 py-1.5 rounded-bl-xl shadow-md z-10">FR.006</div>
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-slate-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="flex items-start gap-6 relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-100 flex items-center justify-center text-slate-700 shadow-sm group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                        <span class="material-symbols-outlined text-3xl">rule</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors">Exempt Review Form</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">For studies qualifying for exemption from full review. Check criteria before downloading.</p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wide">DOCX</span>
                        <span class="text-[10px] text-slate-400 font-medium">95 KB</span>
                    </div>
                    <a href="{{ asset('src/6- FR.006 EXEMPT REVIEW ASSESSMENT FORM.docx') }}" class="flex items-center gap-2 text-sm font-bold text-slate-700 bg-slate-100 px-4 py-2 rounded-lg hover:bg-[#8B0000] hover:text-white hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        <span>Download</span>
                        <span class="material-symbols-outlined text-lg">download</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-user_layout>