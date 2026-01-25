<x-layout>
    <div class="min-h-screen bg-surface-50 relative">
        
        <div class="bg-red-950 h-[300px] w-full absolute top-0 left-0 z-0 overflow-hidden">
            <img src="{{ isset($contents['privacy_header_image']) ? asset($contents['privacy_header_image']) : asset('images/wmsu1.jpg') }}" alt="WMSU Campus" class="w-full h-full object-cover opacity-40 scale-105">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-primary/90 to-brand-dark/90 mix-blend-multiply"></div>
            <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 pt-32 px-6 pb-20">
            <div class="max-w-4xl mx-auto">
                
                <div class="text-center mb-10 text-white animate-[fadeInUp_0.5s_ease-out]">
                    <h1 class="text-4xl md:text-5xl font-extrabold font-heading tracking-tight mb-4">Privacy Policy</h1>
                    <p class="text-red-100 text-lg">Last updated: November 2025</p>
                </div>

                <div class="bg-white p-10 md:p-12 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 animate-[fadeInUp_0.5s_ease-out_0.2s_both]">
                    <div class="prose prose-slate max-w-none leading-relaxed text-slate-600">
                        @if(isset($contents['privacy_content']) && !empty($contents['privacy_content']))
                            {!! $contents['privacy_content'] !!}
                        @else
                            <p class="text-lg text-slate-800 font-medium mb-8 border-l-4 border-brand-primary pl-4">
                                The Western Mindanao State University Research Ethics Office (WMSU-REO) is committed to protecting your personal data. This Privacy Policy explains how we collect, use, and safeguard your information.
                            </p>
                            
                            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4 flex items-center gap-3">
                                <span class="bg-red-50 text-brand-primary w-8 h-8 rounded-lg flex items-center justify-center text-sm">1</span>
                                Information We Collect
                            </h3>
                            <p>We collect personal information necessary for the processing of research ethics applications, including but not limited to names, email addresses, institutional affiliations, and research protocol details.</p>

                            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4 flex items-center gap-3">
                                <span class="bg-red-50 text-brand-primary w-8 h-8 rounded-lg flex items-center justify-center text-sm">2</span>
                                Use of Information
                            </h3>
                            <p>Your data is used solely for the purpose of reviewing research protocols, scheduling appointments, and issuing ethics clearance certificates.</p>

                            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4 flex items-center gap-3">
                                <span class="bg-red-50 text-brand-primary w-8 h-8 rounded-lg flex items-center justify-center text-sm">3</span>
                                Data Security
                            </h3>
                            <p>We implement industry-standard security measures to protect your data from unauthorized access, alteration, or disclosure.</p>
                        @endif
                    </div>
                    
                    <div class="mt-12 pt-8 border-t border-slate-100 text-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-brand-primary font-bold hover:text-red-800 transition-colors group">
                            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>