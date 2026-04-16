<x-user_layout>
    <x-skeleton-loader />
    
    <div id="page-content" style="display: none;" class="max-w-5xl mx-auto py-12 px-4 sm:px-6 animate-[fadeInUp_0.5s_ease-out]">

        <div class="text-center mb-10 md:mb-16">
            @if(isset($contents['instructions_header_image']))
                <img src="{{ asset($contents['instructions_header_image']) }}"
                    class="w-full h-48 object-cover rounded-2xl mb-8 shadow-sm">
            @endif
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 font-heading">
                {{ $contents['instructions_title'] ?? 'Submission Roadmap' }}</h1>
            <p class="text-slate-500 mt-4 text-base md:text-lg max-w-2xl mx-auto">
                {{ $contents['instructions_intro'] ?? 'Follow these steps to ensure a smooth and successful ethics review process.' }}
            </p>
        </div>

        <!-- MOBILE VIEW (Vertical Stack) -->
        <div class="md:hidden space-y-4">

            <!-- Step 1 Mobile -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 mb-3">
                    <span class="bg-[#8B0000] text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">STEP
                        1</span>
                    <h3 class="font-bold text-slate-900 text-lg">Download Resources</h3>
                </div>
                <p class="text-sm text-slate-500 mb-4">Get copies of the Application Form and applicable Assessment
                    Forms from our library.</p>
                <a href="{{ route('resources') }}"
                    class="w-full flex items-center justify-center gap-2 text-sm font-bold text-[#8B0000] bg-red-50 px-4 py-3 rounded-lg hover:bg-[#8B0000] hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-lg">source</span> Go to Resources
                </a>
            </div>

            <!-- Step 2 Mobile -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 mb-3">
                    <span
                        class="bg-slate-100 text-slate-600 text-xs font-bold px-2.5 py-1 rounded-full border border-slate-200">STEP
                        2</span>
                    <h3 class="font-bold text-slate-900 text-lg">Prepare Documents</h3>
                </div>
                <p class="text-sm text-slate-500 mb-4">Ensure all files are PDF (unless specified) and formatted
                    correctly.</p>
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <ul class="space-y-2 text-sm text-slate-700 font-medium">
                        <li class="flex items-center gap-2"><span class="text-green-500 text-lg">●</span> Application
                            Form (Signed)</li>
                        <li class="flex items-center gap-2"><span class="text-green-500 text-lg">●</span> Research
                            Protocol (Lines)</li>
                        <li class="flex items-center gap-2"><span class="text-green-500 text-lg">●</span> Technical
                            Clearance</li>
                        <li class="flex items-center gap-2"><span class="text-green-500 text-lg">●</span> Consent Forms
                        </li>
                        <li class="flex items-center gap-2"><span class="text-green-500 text-lg">●</span> CV of
                            Researcher/s</li>
                        <li class="flex items-center gap-2"><span class="text-green-500 text-lg">●</span> Assessment
                            Forms (Word)</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3 Mobile -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 border-l-4 border-l-[#8B0000]">
                <div class="flex items-center gap-3 mb-3">
                    <span
                        class="bg-slate-100 text-slate-600 text-xs font-bold px-2.5 py-1 rounded-full border border-slate-200">STEP
                        3</span>
                    <h3 class="font-bold text-slate-900 text-lg">Upload Submission</h3>
                </div>
                <p class="text-sm text-slate-500 mb-4">Upload your complied files to the portal.</p>
                <a href="{{ route('submit') }}"
                    class="w-full flex items-center justify-center gap-2 text-sm font-bold text-white bg-[#8B0000] px-4 py-3 rounded-lg shadow-md shadow-red-900/20 active:scale-95 transition-transform">
                    <span class="material-symbols-outlined text-lg">upload_file</span> Start Submission
                </a>
            </div>

            <!-- Step 4 Mobile -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 mb-3">
                    <span
                        class="bg-slate-100 text-slate-600 text-xs font-bold px-2.5 py-1 rounded-full border border-slate-200">STEP
                        4</span>
                    <h3 class="font-bold text-slate-900 text-lg">Payment & Hard Copies</h3>
                </div>
                <div class="text-sm text-slate-600 space-y-2">
                    <p>1. Secure a Payment Slip from the REO Office and pay at the University Cashier.</p>
                    <p>2. Submit hard copies in an <strong class="text-[#8B0000]">expanded long envelope</strong>
                        (color-coded by college) with the Official Receipt.</p>
                </div>
            </div>

            <!-- Final Step Mobile -->
            <div class="bg-green-50 p-5 rounded-xl border border-green-200 flex gap-4 items-start">
                <span class="material-symbols-outlined text-3xl text-green-600 shrink-0 mt-1">flag</span>
                <div>
                    <h3 class="font-bold text-green-900 text-lg mb-1">Wait for Review</h3>
                    <p class="text-sm text-green-800">Monitor your dashboard for status updates. You will be notified if
                        an appointment is needed.</p>
                </div>
            </div>

        </div>

        <!-- DESKTOP VIEW (Original Timeline) -->
        <div class="hidden md:block relative">
            <!-- Vertical Line -->
            <div
                class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-slate-200 via-slate-200 to-transparent">
            </div>

            <!-- Step 1 -->
            <div class="relative flex gap-8 mb-16 group">
                <div
                    class="w-16 h-16 bg-white border-4 border-[#8B0000] rounded-full flex items-center justify-center z-10 shrink-0 shadow-lg shadow-red-900/10 transition-transform duration-300 group-hover:scale-110">
                    <span class="text-2xl font-bold text-[#8B0000]">1</span>
                </div>
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex-1 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full -mr-4 -mt-4 opacity-50 transition-transform group-hover:scale-150">
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 relative z-10">Download Resources</h3>
                    <p class="text-slate-600 mb-6 relative z-10">Get copies of the Application Form and applicable
                        Assessment Forms from our library.</p>
                    <a href="{{ route('resources') }}"
                        class="inline-flex items-center gap-2 text-sm font-bold text-[#8B0000] bg-red-50 px-5 py-2.5 rounded-xl hover:bg-[#8B0000] hover:text-white transition-all duration-300 relative z-10">
                        <span class="material-symbols-outlined text-lg">source</span> Go to Resources
                    </a>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="relative flex gap-8 mb-16 group">
                <div
                    class="w-16 h-16 bg-white border-4 border-slate-200 group-hover:border-[#8B0000] rounded-full flex items-center justify-center z-10 shrink-0 shadow-sm transition-all duration-300 group-hover:scale-110">
                    <span
                        class="text-2xl font-bold text-slate-400 group-hover:text-[#8B0000] transition-colors">2</span>
                </div>
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex-1 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Prepare Documents</h3>
                    <p class="text-slate-600 mb-6">Ensure all files are PDF (unless specified) and formatted correctly.
                    </p>
                    <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-slate-700 font-medium">
                            <li class="flex items-center gap-3"><i
                                    class="fas fa-check-circle text-green-500 text-lg"></i> Application Form (Signed)
                            </li>
                            <li class="flex items-center gap-3"><i
                                    class="fas fa-check-circle text-green-500 text-lg"></i> Research Protocol (Lines)
                            </li>
                            <li class="flex items-center gap-3"><i
                                    class="fas fa-check-circle text-green-500 text-lg"></i> Technical Clearance</li>
                            <li class="flex items-center gap-3"><i
                                    class="fas fa-check-circle text-green-500 text-lg"></i> Consent Forms</li>
                            <li class="flex items-center gap-3"><i
                                    class="fas fa-check-circle text-green-500 text-lg"></i> CV of Researcher/s</li>
                            <li class="flex items-center gap-3"><i
                                    class="fas fa-check-circle text-green-500 text-lg"></i> Assessment Forms (Word)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="relative flex gap-8 mb-16 group">
                <div
                    class="w-16 h-16 bg-white border-4 border-slate-200 group-hover:border-[#8B0000] rounded-full flex items-center justify-center z-10 shrink-0 shadow-sm transition-all duration-300 group-hover:scale-110">
                    <span
                        class="text-2xl font-bold text-slate-400 group-hover:text-[#8B0000] transition-colors">3</span>
                </div>
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex-1 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-l-transparent hover:border-l-[#8B0000]">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Upload Submission</h3>
                    <p class="text-slate-600 mb-6">Upload your complied files to the portal. Our AI will pre-screen for
                        formatting errors.</p>
                    <a href="{{ route('submit') }}"
                        class="inline-flex items-center gap-2 text-sm font-bold text-white bg-[#8B0000] px-6 py-3 rounded-xl hover:bg-red-800 hover:shadow-lg hover:shadow-red-900/30 transition-all duration-300 transform hover:-translate-y-0.5">
                        <span class="material-symbols-outlined text-lg">upload_file</span> Start Submission
                    </a>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="relative flex gap-8 mb-16 group">
                <div
                    class="w-16 h-16 bg-white border-4 border-slate-200 group-hover:border-[#8B0000] rounded-full flex items-center justify-center z-10 shrink-0 shadow-sm transition-all duration-300 group-hover:scale-110">
                    <span
                        class="text-2xl font-bold text-slate-400 group-hover:text-[#8B0000] transition-colors">4</span>
                </div>
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex-1 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Payment & Hard Copies</h3>
                    <p class="text-slate-600 leading-relaxed">
                        1. Secure a Payment Slip from the REO Office and pay at the University Cashier.<br>
                        2. Submit hard copies in an <strong class="text-[#8B0000]">expanded long envelope</strong>
                        (color-coded by college) with the Official Receipt.
                    </p>
                </div>
            </div>

            <!-- Final Step -->
            <div class="relative flex gap-8 group">
                <div
                    class="w-16 h-16 bg-green-50 border-4 border-green-500 rounded-full flex items-center justify-center z-10 shrink-0 shadow-lg shadow-green-900/10 transition-transform duration-300 group-hover:scale-110">
                    <span class="material-symbols-outlined text-3xl text-green-600">flag</span>
                </div>
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex-1 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Wait for Review</h3>
                    <p class="text-slate-600">Monitor your dashboard for status updates. You will be notified if an
                        appointment is needed.</p>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-user_layout>