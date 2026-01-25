<x-layout>
    <div class="min-h-screen bg-surface-50 relative">
        
        <div class="bg-red-950 h-[300px] w-full absolute top-0 left-0 z-0 overflow-hidden">
             <img src="{{ isset($contents['terms_header_image']) ? asset($contents['terms_header_image']) : asset('images/wmsu2.jpg') }}" alt="WMSU Campus" class="w-full h-full object-cover opacity-30">
             <div class="absolute inset-0 bg-gradient-to-bl from-brand-dark via-[#500000] to-brand-primary/80 opacity-90"></div>
            <div class="absolute top-10 left-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 pt-32 px-6 pb-20">
            <div class="max-w-4xl mx-auto">
                
                <div class="text-center mb-10 text-white animate-[fadeInUp_0.5s_ease-out]">
                    <h1 class="text-4xl md:text-5xl font-extrabold font-heading tracking-tight mb-4">Terms of Service</h1>
                    <p class="text-red-100 text-lg">Last updated: November 2025</p>
                </div>

                <div class="bg-white p-10 md:p-12 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 animate-[fadeInUp_0.5s_ease-out_0.2s_both]">
                    <div class="prose prose-slate max-w-none leading-relaxed text-slate-600">
                        @if(isset($contents['terms_content']) && !empty($contents['terms_content']))
                            {!! $contents['terms_content'] !!}
                        @else
                            <p class="text-lg text-slate-800 font-medium mb-8">
                                By using the WMSU-REO Portal, you agree to the following terms and conditions governing the submission and review of research protocols.
                            </p>
                            
                            <div class="grid md:grid-cols-2 gap-8 my-8">
                                <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">1. Acceptable Use</h3>
                                    <p class="text-sm">This portal is intended for academic purposes. Misuse, including uploading malicious files or falsifying data, is strictly prohibited.</p>
                                </div>
                                <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">2. Intellectual Property</h3>
                                    <p class="text-sm">Researchers retain ownership of their protocols. You grant REO the right to review and archive documents for compliance.</p>
                                </div>
                                <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">3. Account Responsibility</h3>
                                    <p class="text-sm">Maintain the confidentiality of your credentials. WMSU-REO is not liable for unauthorized access due to negligence.</p>
                                </div>
                                <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2">4. System Availability</h3>
                                    <p class="text-sm">We strive for 99.9% uptime but may schedule maintenance. We are not liable for delays caused by system outages.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-slate-100 text-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-brand-primary font-bold hover:text-red-800 transition-colors group">
                            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>