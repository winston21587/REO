<x-user_layout>
    <div class="max-w-5xl mx-auto pt-2 pb-28 sm:py-12 px-4 sm:px-6 animate-[fadeInUp_0.5s_ease-out] relative">

        <!-- Subtle Atmospheric Maroon Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-72 bg-gradient-to-b from-red-100/40 via-red-50/20 to-transparent pointer-events-none -z-10 rounded-full blur-3xl" aria-hidden="true"></div>

        <!-- Page Header -->
        <header class="text-center mb-8 md:mb-16">
            @if(isset($contents['instructions_header_image']))
                <img src="{{ asset($contents['instructions_header_image']) }}"
                    class="w-full h-48 object-cover rounded-2xl mb-8 shadow-sm" 
                    alt="{{ $contents['instructions_title'] ?? 'Submission Roadmap' }} Banner">
            @endif

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                {{ $contents['instructions_title'] ?? 'Submission Roadmap' }}
            </h1>

            <p class="text-slate-600 font-medium mt-3 text-sm sm:text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                {{ $contents['instructions_intro'] ?? 'Follow these steps to ensure a smooth and successful ethics review process.' }}
            </p>
        </header>

        <!-- MOBILE VIEW (Touch-Optimized Adaptive Cards with Connected Roadmap) -->
        <div class="md:hidden space-y-4">

            <!-- Step 1 Mobile -->
            <article class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 relative overflow-hidden">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="inline-flex items-center justify-center bg-brand-primary text-white text-[11px] font-black px-3 py-1 rounded-full shadow-2xs tracking-wider">
                        STAGE 01
                    </span>
                    <span class="text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">Initial Step</span>
                </div>
                <h2 class="font-black text-slate-900 text-lg leading-snug mb-1.5">
                    Download Resources
                </h2>
                <p class="text-xs text-slate-600 mb-4 leading-relaxed font-medium">
                    Get copies of the Application Form and applicable Assessment Forms from our library.
                </p>
                <a href="{{ route('resources') }}"
                   aria-label="Navigate to Resource Library to download application and assessment forms"
                   class="w-full min-h-[44px] flex items-center justify-center gap-2.5 text-sm font-extrabold text-brand-primary bg-red-50/90 border border-red-200/80 hover:bg-brand-primary hover:text-white px-5 py-3 rounded-xl transition-all duration-200 active:scale-[0.98] shadow-2xs group/btn focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                    <i class="fas fa-folder-open text-sm" aria-hidden="true"></i>
                    <span>Go to Resources</span>
                    <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform" aria-hidden="true"></i>
                </a>
            </article>

            <!-- Step 2 Mobile -->
            <article class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="inline-flex items-center justify-center bg-slate-900 text-white text-[11px] font-black px-3 py-1 rounded-full shadow-2xs tracking-wider">
                        STAGE 02
                    </span>
                    <span class="text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">Documentation</span>
                </div>
                <h2 class="font-black text-slate-900 text-lg leading-snug mb-1.5">
                    Prepare Documents
                </h2>
                <p class="text-xs text-slate-600 mb-3.5 leading-relaxed font-medium">
                    Ensure all files match format requirements before uploading to the portal.
                </p>
                <div class="grid grid-cols-1 gap-2 bg-slate-50/80 p-3 rounded-xl border border-slate-200/70">
                    @php
                        $mobileRequirements = [
                            ['title' => 'Application Form (Signed)', 'ext' => 'PDF', 'isPdf' => true],
                            ['title' => 'Research Protocol (Lines)', 'ext' => 'PDF', 'isPdf' => true],
                            ['title' => 'Technical Clearance', 'ext' => 'PDF', 'isPdf' => true],
                            ['title' => 'Consent Forms', 'ext' => 'PDF', 'isPdf' => true],
                            ['title' => 'CV of Researcher/s', 'ext' => 'PDF', 'isPdf' => true],
                            ['title' => 'Assessment Forms', 'ext' => 'DOCX', 'isPdf' => false]
                        ];
                    @endphp
                    @foreach($mobileRequirements as $req)
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-white border border-slate-200/80 shadow-2xs">
                            <div class="flex items-center gap-2 min-w-0 pr-1">
                                <i class="fas fa-check-circle text-emerald-500 text-xs shrink-0" aria-hidden="true"></i>
                                <span class="text-xs font-bold text-slate-800 truncate">{{ $req['title'] }}</span>
                            </div>
                            <span class="text-[9px] font-black px-1.5 py-0.5 rounded border shrink-0 {{ $req['isPdf'] ? 'bg-red-50 text-red-700 border-red-200/80' : 'bg-blue-50 text-blue-700 border-blue-200/80' }}">
                                {{ $req['ext'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </article>

            <!-- Step 3 Mobile -->
            <article class="bg-gradient-to-br from-white via-red-50/20 to-white p-5 rounded-2xl shadow-sm border border-red-200/90 ring-1 ring-red-100/90 relative overflow-hidden">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="inline-flex items-center justify-center bg-brand-primary text-white text-[11px] font-black px-3 py-1 rounded-full shadow-2xs tracking-wider">
                        STAGE 03 • CORE
                    </span>
                    <span class="text-[10px] font-black uppercase text-brand-primary tracking-wider">Key Action</span>
                </div>
                <h2 class="font-black text-slate-900 text-lg leading-snug mb-1.5">
                    Upload Submission
                </h2>
                <p class="text-xs text-slate-600 mb-4 leading-relaxed font-medium">
                    Upload your compiled files to the portal. Our AI will pre-screen for formatting errors.
                </p>
                <a href="{{ route('submit') }}"
                   aria-label="Proceed to protocol submission page"
                   class="w-full min-h-[44px] flex items-center justify-center gap-2.5 text-sm font-extrabold text-white bg-brand-primary hover:bg-brand-secondary px-5 py-3.5 rounded-xl shadow-md shadow-brand-primary/20 hover:shadow-lg transition-all duration-200 active:scale-[0.98] group/btn focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                    <i class="fas fa-cloud-arrow-up text-base" aria-hidden="true"></i>
                    <span>Start Submission</span>
                    <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform" aria-hidden="true"></i>
                </a>
            </article>

            <!-- Step 4 Mobile -->
            <article class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <span class="inline-flex items-center justify-center bg-slate-900 text-white text-[11px] font-black px-3 py-1 rounded-full shadow-2xs tracking-wider">
                        STAGE 04
                    </span>
                    <span class="text-[10px] font-extrabold uppercase text-slate-500 tracking-wider">Physical Copy</span>
                </div>
                <h2 class="font-black text-slate-900 text-lg leading-snug mb-1.5">
                    Payment & Hard Copies
                </h2>
                <div class="text-xs text-slate-600 space-y-2 leading-relaxed font-medium">
                    <p>
                        <span class="font-black text-slate-800">1.</span> Secure a Payment Slip from the REO Office and pay at the University Cashier.
                    </p>
                    <p>
                        <span class="font-black text-slate-800">2.</span> Submit hard copies with the Official Receipt.
                    </p>
                </div>
                <div class="mt-3 p-3 rounded-xl bg-amber-50/80 border border-amber-200/80 flex items-start gap-2.5">
                    <i class="fas fa-envelope-open-text text-amber-600 text-sm shrink-0 mt-0.5" aria-hidden="true"></i>
                    <p class="text-[11px] text-amber-900 leading-relaxed font-semibold">
                        Use an <strong class="text-brand-primary font-black">expanded long envelope</strong> color-coded by college.
                    </p>
                </div>
            </article>

            <!-- Step 5 Mobile -->
            <article class="bg-emerald-50/70 p-5 rounded-2xl border border-emerald-200/90 flex gap-3.5 items-start">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-2xs mt-0.5">
                    <i class="fas fa-flag-checkered text-base" aria-hidden="true"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 block mb-0.5">Final Stage</span>
                    <h2 class="font-black text-emerald-950 text-base sm:text-lg mb-1">
                        Wait for Review
                    </h2>
                    <p class="text-xs text-emerald-800 font-medium leading-relaxed">
                        Monitor your dashboard for status updates. You will be notified if an appointment or revisions are needed.
                    </p>
                </div>
            </article>

        </div>

        <!-- DESKTOP VIEW (Connected Timeline Roadmap) -->
        <div class="hidden md:block relative">

            <!-- Step 1 Desktop -->
            <div class="relative flex gap-8 mb-12 group">
                <!-- Connector to Stage 02 -->
                <div class="absolute left-8 top-16 -bottom-12 w-0.5 -translate-x-1/2 bg-gradient-to-b from-brand-primary to-slate-300 pointer-events-none" aria-hidden="true"></div>
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-primary to-brand-secondary text-white flex items-center justify-center z-10 shrink-0 shadow-md shadow-brand-primary/25 transition-transform duration-300 group-hover:scale-105">
                    <span class="text-xl font-black tracking-tight">01</span>
                </div>
                <article class="bg-white p-7 sm:p-8 rounded-2xl shadow-sm border border-slate-200/90 flex-1 hover:shadow-xl hover:border-slate-300 hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-28 h-28 bg-red-50/50 rounded-bl-full pointer-events-none -mr-4 -mt-4 transition-transform duration-300 group-hover:scale-125" aria-hidden="true"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-brand-primary bg-red-50 px-2.5 py-0.5 rounded-md border border-red-200/60">Stage 01 • Preparatory</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-2.5 tracking-tight">
                            Download Resources
                        </h2>
                        <p class="text-sm text-slate-600 mb-6 max-w-xl leading-relaxed font-medium">
                            Get copies of the Application Form and applicable Assessment Forms from our library.
                        </p>
                        <a href="{{ route('resources') }}"
                           aria-label="Navigate to Resource Library to download application and assessment forms"
                           class="inline-flex items-center gap-2.5 text-sm font-extrabold text-brand-primary bg-red-50/90 border border-red-200/80 hover:bg-brand-primary hover:text-white px-5 py-2.5 rounded-xl transition-all duration-200 shadow-2xs hover:shadow-md group/btn focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                            <i class="fas fa-folder-open text-sm" aria-hidden="true"></i>
                            <span>Go to Resources</span>
                            <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            </div>

            <!-- Step 2 Desktop -->
            <div class="relative flex gap-8 mb-12 group">
                <!-- Connector to Stage 03 -->
                <div class="absolute left-8 top-16 -bottom-12 w-0.5 -translate-x-1/2 bg-gradient-to-b from-slate-300 to-brand-primary pointer-events-none" aria-hidden="true"></div>
                <div class="w-16 h-16 rounded-2xl bg-white border-2 border-slate-300 group-hover:border-slate-900 text-slate-800 flex items-center justify-center z-10 shrink-0 shadow-sm transition-all duration-300 group-hover:scale-105">
                    <span class="text-xl font-black tracking-tight">02</span>
                </div>
                <article class="bg-white p-7 sm:p-8 rounded-2xl shadow-sm border border-slate-200/90 flex-1 hover:shadow-xl hover:border-slate-300 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md border border-slate-200/80">Stage 02 • Documentation</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-2.5 tracking-tight">
                        Prepare Documents
                    </h2>
                    <p class="text-sm text-slate-600 mb-5 leading-relaxed font-medium">
                        Ensure all files adhere to mandatory formatting and file type requirements.
                    </p>
                    
                    <!-- Tactile Document Grid -->
                    <div class="bg-slate-50/80 p-4 rounded-xl border border-slate-200/70">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @php
                                $desktopRequirements = [
                                    ['title' => 'Application Form (Signed)', 'ext' => 'PDF', 'isPdf' => true],
                                    ['title' => 'Research Protocol (Lines)', 'ext' => 'PDF', 'isPdf' => true],
                                    ['title' => 'Technical Clearance', 'ext' => 'PDF', 'isPdf' => true],
                                    ['title' => 'Consent Forms', 'ext' => 'PDF', 'isPdf' => true],
                                    ['title' => 'CV of Researcher/s', 'ext' => 'PDF', 'isPdf' => true],
                                    ['title' => 'Assessment Forms', 'ext' => 'DOCX', 'isPdf' => false]
                                ];
                            @endphp
                            @foreach($desktopRequirements as $req)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-white border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
                                    <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                        <i class="fas fa-check-circle text-emerald-500 text-sm shrink-0" aria-hidden="true"></i>
                                        <span class="text-xs font-bold text-slate-800 truncate">{{ $req['title'] }}</span>
                                    </div>
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded border shrink-0 {{ $req['isPdf'] ? 'bg-red-50 text-red-700 border-red-200/80' : 'bg-blue-50 text-blue-700 border-blue-200/80' }}">
                                        {{ $req['ext'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>
            </div>

            <!-- Step 3 Desktop -->
            <div class="relative flex gap-8 mb-12 group">
                <!-- Connector to Stage 04 -->
                <div class="absolute left-8 top-16 -bottom-12 w-0.5 -translate-x-1/2 bg-gradient-to-b from-brand-primary to-slate-300 pointer-events-none" aria-hidden="true"></div>
                <div class="w-16 h-16 rounded-2xl bg-brand-primary border-2 border-brand-primary text-white flex items-center justify-center z-10 shrink-0 shadow-md shadow-brand-primary/25 ring-4 ring-red-100 transition-transform duration-300 group-hover:scale-105">
                    <span class="text-xl font-black tracking-tight">03</span>
                </div>
                <article class="bg-gradient-to-br from-white via-red-50/20 to-white p-7 sm:p-8 rounded-2xl shadow-sm border border-red-200/90 ring-1 ring-red-100/90 flex-1 hover:shadow-xl hover:border-red-300 hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-red-100/40 rounded-bl-full pointer-events-none -mr-4 -mt-4 transition-transform duration-300 group-hover:scale-125" aria-hidden="true"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-brand-primary bg-red-50 px-2.5 py-0.5 rounded-md border border-red-200/60">Stage 03 • Core Action</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-2.5 tracking-tight">
                            Upload Submission
                        </h2>
                        <p class="text-sm text-slate-600 mb-6 max-w-xl leading-relaxed font-medium">
                            Upload your compiled files to the portal. Our AI will pre-screen for formatting errors.
                        </p>
                        <a href="{{ route('submit') }}"
                           aria-label="Proceed to protocol submission page"
                           class="inline-flex items-center gap-2.5 text-sm font-extrabold text-white bg-brand-primary hover:bg-brand-secondary px-6 py-3.5 rounded-xl shadow-md shadow-brand-primary/20 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 active:scale-95 group/btn focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                            <i class="fas fa-cloud-arrow-up text-base" aria-hidden="true"></i>
                            <span>Start Submission</span>
                            <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            </div>

            <!-- Step 4 Desktop -->
            <div class="relative flex gap-8 mb-12 group">
                <!-- Connector to Stage 05 -->
                <div class="absolute left-8 top-16 -bottom-12 w-0.5 -translate-x-1/2 bg-gradient-to-b from-slate-300 to-emerald-500 pointer-events-none" aria-hidden="true"></div>
                <div class="w-16 h-16 rounded-2xl bg-white border-2 border-slate-300 group-hover:border-slate-900 text-slate-800 flex items-center justify-center z-10 shrink-0 shadow-sm transition-all duration-300 group-hover:scale-105">
                    <span class="text-xl font-black tracking-tight">04</span>
                </div>
                <article class="bg-white p-7 sm:p-8 rounded-2xl shadow-sm border border-slate-200/90 flex-1 hover:shadow-xl hover:border-slate-300 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md border border-slate-200/80">Stage 04 • Physical Copy</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-2.5 tracking-tight">
                        Payment & Hard Copies
                    </h2>
                    <div class="text-sm text-slate-600 space-y-2 leading-relaxed font-medium">
                        <p>
                            <span class="font-black text-slate-900">1.</span> Secure a Payment Slip from the REO Office and pay at the University Cashier.
                        </p>
                        <p>
                            <span class="font-black text-slate-900">2.</span> Submit hard copies accompanied by the Official Receipt.
                        </p>
                    </div>
                    <div class="mt-4 p-4 rounded-xl bg-amber-50/80 border border-amber-200/80 flex items-start gap-3">
                        <i class="fas fa-envelope-open-text text-amber-600 text-base shrink-0 mt-0.5" aria-hidden="true"></i>
                        <p class="text-xs text-amber-900 leading-relaxed font-semibold">
                            Physical copies must be packaged in an <strong class="text-brand-primary font-black">expanded long envelope</strong> color-coded by college.
                        </p>
                    </div>
                </article>
            </div>

            <!-- Step 5 Desktop -->
            <div class="relative flex gap-8 group">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-700 text-white flex items-center justify-center z-10 shrink-0 shadow-md shadow-emerald-600/25 transition-transform duration-300 group-hover:scale-105">
                    <i class="fas fa-flag-checkered text-xl" aria-hidden="true"></i>
                </div>
                <article class="bg-emerald-50/70 p-7 sm:p-8 rounded-2xl border border-emerald-200/90 flex-1 hover:shadow-xl hover:border-emerald-300 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-md border border-emerald-200/80">Final Stage</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-emerald-950 mb-2.5 tracking-tight">
                        Wait for Review
                    </h2>
                    <p class="text-sm text-emerald-800 leading-relaxed font-medium">
                        Monitor your dashboard for status updates. You will be notified if an appointment or revisions are needed.
                    </p>
                </article>
            </div>
        </div>

    </div>
</x-user_layout>