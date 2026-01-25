<x-layout>
    <div class="min-h-screen bg-surface-50 relative">
        
        <div class="bg-red-950 h-[300px] w-full absolute top-0 left-0 z-0 overflow-hidden">
             <img src="{{ isset($contents['accessibility_header_image']) ? asset($contents['accessibility_header_image']) : asset('images/wmsu3.jpg') }}" alt="WMSU Campus" class="w-full h-full object-cover opacity-30">
             <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-brand-dark to-brand-primary/80 opacity-95"></div>
        </div>

        <div class="relative z-10 pt-32 px-6 pb-20">
            <div class="max-w-4xl mx-auto">
                
                <div class="text-center mb-10 text-white animate-[fadeInUp_0.5s_ease-out]">
                    <h1 class="text-4xl md:text-5xl font-extrabold font-heading tracking-tight mb-4">Accessibility</h1>
                    <p class="text-slate-300 text-lg">Our commitment to inclusivity.</p>
                </div>

                <div class="bg-white p-10 md:p-12 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 animate-[fadeInUp_0.5s_ease-out_0.2s_both]">
                    <div class="prose prose-slate max-w-none leading-relaxed text-slate-600">
                        @if(isset($contents['accessibility_content']) && !empty($contents['accessibility_content']))
                            {!! $contents['accessibility_content'] !!}
                        @else
                            <p class="text-lg text-slate-800 font-medium mb-6">
                                WMSU-REO is committed to ensuring digital accessibility for people with disabilities. We are continually improving the user experience for everyone and applying the relevant accessibility standards.
                            </p>
                             
                            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">Conformance Status</h3>
                            <p>The Web Content Accessibility Guidelines (WCAG) defines requirements for designers and developers to improve accessibility for people with disabilities. We strive to adhere to <strong>WCAG 2.1 Level AA</strong> standards.</p>

                            <h3 class="text-xl font-bold text-slate-900 mt-8 mb-4">Feedback & Support</h3>
                            <p>We welcome your feedback on the accessibility of the REO Portal. Please let us know if you encounter accessibility barriers:</p>
                            
                            <div class="mt-6 bg-brand-primary/5 rounded-2xl p-6 border border-brand-primary/10">
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm text-brand-primary"><i class="fas fa-phone"></i></div>
                                        <span class="font-medium text-slate-700">{{ $contents['footer_phone'] ?? 'Empty'  }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm text-brand-primary"><i class="fas fa-envelope"></i></div>
                                        <span class="font-medium text-slate-700">{{ $contents['footer_email'] ?? 'Empty' }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm text-brand-primary"><i class="fas fa-map-marker-alt"></i></div>
                                        <span class="font-medium text-slate-700">{{ $contents['footer_address'] ?? 'Empty' }}</span>
                                    </li>
                                </ul>
                            </div>
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