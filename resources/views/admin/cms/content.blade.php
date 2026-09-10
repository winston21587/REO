<x-admin_layout title="Website Content Manager">
    <div class="w-full max-w-7xl mx-auto space-y-8 min-w-0">
        
        <!-- Header & Top Action -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">Website Content</h1>
                <p class="text-slate-500 mt-1.5 text-sm">Public portal branding, landing pages, hero banners, and disclosure policies.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('index') }}" target="_blank" 
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-all shadow-xs min-h-[44px]">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Preview Portal
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-emerald-900">{{ session('success') }}</p>
                    <p class="text-xs text-emerald-700">All public portal assets and text content have been updated.</p>
                </div>
            </div>
        @endif

        <!-- Executive Metrics Ribbon -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Managed Sections</span>
                <span class="text-2xl font-extrabold text-slate-900 font-heading tabular-nums mt-1 block">7</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Hero Slides</span>
                <span class="text-2xl font-extrabold text-blue-700 font-heading tabular-nums mt-1 block">3 Active</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Legal Disclosures</span>
                <span class="text-2xl font-extrabold text-indigo-700 font-heading tabular-nums mt-1 block">3 Policies</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Portal Status</span>
                <span class="text-2xl font-extrabold text-emerald-600 font-heading tabular-nums mt-1 block">LIVE</span>
            </div>
        </div>

        <form action="{{ route('admin.cms.content.update') }}" method="POST" enctype="multipart/form-data" id="websiteContentForm" class="space-y-8">
            @csrf
            
            <!-- Mobile Horizontal Anchor Pills (< lg) -->
            <div class="lg:hidden bg-white p-2 rounded-2xl border border-slate-200/80 shadow-xs overflow-x-auto flex items-center gap-1.5 scrollbar-none">
                <a href="#section-branding" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">Branding</a>
                <a href="#section-hero" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">Hero Slides</a>
                <a href="#section-mission" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">Mission & Vision</a>
                <a href="#section-purpose" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">Purpose & Join</a>
                <a href="#section-auth" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">Auth Pages</a>
                <a href="#section-legal" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">Legal</a>
                <a href="#section-footer" class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">Footer</a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start min-w-0">
                
                <!-- Desktop Sticky Navigation Sidebar -->
                <div class="hidden lg:block w-64 shrink-0 sticky top-6">
                    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200/80">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Content Directory</span>
                            <span class="text-sm font-extrabold text-slate-900 font-heading block mt-0.5">Jump to Section</span>
                        </div>
                        <nav class="p-2 space-y-1">
                            <a href="#section-branding" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                                General Branding
                            </a>
                            <a href="#section-hero" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Hero Slider (3 Slides)
                            </a>
                            <a href="#section-mission" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Mission, Vision & Goals
                            </a>
                            <a href="#section-purpose" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Purpose & Join Sections
                            </a>
                            <a href="#section-auth" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Auth Page Backdrops
                            </a>
                            <a href="#section-legal" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Legal Disclosures
                            </a>
                            <a href="#section-footer" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Footer Information
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content Editor Sections -->
                <div class="flex-1 space-y-8 w-full min-w-0">
                    
                    <!-- 1. General Branding (Logo) -->
                    <div id="section-branding" class="bg-white p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 scroll-mt-24 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 font-heading">General Branding</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Primary institutional seal and emblem across header, footer, and authentication screens.</p>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">BRANDING</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                            <!-- Preview Box -->
                            <div class="flex flex-col items-center justify-center p-6 bg-slate-50 rounded-2xl border border-slate-200/80 text-center">
                                @if(isset($contents['website_logo']))
                                    <div class="w-28 h-28 p-2 rounded-xl bg-white border border-slate-200 shadow-xs flex items-center justify-center mb-3">
                                        <img src="{{ asset($contents['website_logo']) }}" alt="Website Logo" class="max-w-full max-h-full object-contain">
                                    </div>
                                    <a href="{{ asset($contents['website_logo']) }}" target="_blank" 
                                       class="text-xs font-bold text-[#8B0000] hover:underline inline-flex items-center gap-1">
                                        View Current Asset
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @else
                                    <div class="w-28 h-28 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 mb-3">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-xs text-slate-400 font-medium">No custom logo</span>
                                @endif
                            </div>

                            <!-- Upload Inputs -->
                            <div class="md:col-span-2 space-y-3">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Upload New Logo</label>
                                <input type="file" name="website_logo" accept="image/*" 
                                       class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer min-h-[44px]">
                                <div class="space-y-1 text-xs text-slate-500">
                                    <p class="font-medium">• Recommended format: Transparent PNG or SVG asset.</p>
                                    <p>• Displayed in Portal Navigation Header, System Footer, and Public Login/Registration screens.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Hero Section (Slider - 3 Slides) -->
                    <div id="section-hero" class="bg-white p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 scroll-mt-24 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 font-heading">Hero Slider</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Primary welcoming banner sequence on the public research portal.</p>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">3 SLIDES</span>
                        </div>
                        
                        <div class="space-y-6">
                            @for($i=1; $i<=3; $i++)
                                <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-[#8B0000] text-white flex items-center justify-center font-heading font-extrabold text-xs">
                                                0{{ $i }}
                                            </div>
                                            <h4 class="font-extrabold text-slate-900 text-sm font-heading">Slide 0{{ $i }} Headline & Imagery</h4>
                                        </div>
                                        @if(isset($contents['hero_image_' . $i]))
                                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Image Attached</span>
                                        @else
                                            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Default Backdrop</span>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="space-y-3">
                                            <div class="space-y-1">
                                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Slide Title</label>
                                                <input type="text" name="hero_title_{{ $i }}" 
                                                       value="{{ ($contents['hero_title_' . $i] ?? '') !== 'Empty' ? ($contents['hero_title_' . $i] ?? '') : '' }}" 
                                                       placeholder="e.g. Advancing Research Excellence"
                                                       class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Slide Description</label>
                                                <textarea name="hero_text_{{ $i }}" rows="3" 
                                                          placeholder="Brief paragraph summarizing this feature banner..."
                                                          class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 leading-relaxed">{{ ($contents['hero_text_' . $i] ?? '') !== 'Empty' ? ($contents['hero_text_' . $i] ?? '') : '' }}</textarea>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Background Photography</label>
                                            
                                            @if(isset($contents['hero_image_' . $i]))
                                                <div class="relative rounded-xl overflow-hidden border border-slate-200 aspect-[16/7] bg-slate-900 group">
                                                    <img src="{{ asset($contents['hero_image_' . $i]) }}" alt="Slide {{ $i }}" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                        <a href="{{ asset($contents['hero_image_' . $i]) }}" target="_blank" 
                                                           class="px-3 py-1.5 bg-white/90 rounded-lg text-xs font-bold text-slate-900 shadow-sm hover:bg-white transition-colors">
                                                            View Full Image
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            <input type="file" name="hero_image_{{ $i }}" accept="image/*" 
                                                   class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-slate-700 hover:file:bg-slate-50 cursor-pointer min-h-[40px]">
                                            <p class="text-[11px] text-slate-500">Supported: High-res JPG/PNG (1920x800 recommended).</p>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- 3. Mission / Vision / Goals -->
                    <div id="section-mission" class="bg-white p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 scroll-mt-24 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 font-heading">Mission, Vision & Goals</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Foundational institutional ethics commitments published on the public portal.</p>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">FOUNDATIONAL</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            @foreach(['mission' => 'Institutional Mission', 'vision' => 'Strategic Vision', 'goals' => 'Core Objectives & Goals'] as $item => $heading)
                                <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-wider">
                                        {{ $heading }}
                                    </label>
                                    <textarea name="{{ $item }}_text" rows="7" 
                                              placeholder="Enter {{ strtolower($heading) }} statement..."
                                              class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all leading-relaxed placeholder:text-slate-400">{{ ($contents[$item . '_text'] ?? '') !== 'Empty' ? ($contents[$item . '_text'] ?? '') : '' }}</textarea>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 4. Purpose & Join Us -->
                    <div id="section-purpose" class="bg-white p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 scroll-mt-24 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 font-heading">Purpose & Join Us</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Informational story sections displayed to prospective researchers and committee members.</p>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">PORTAL SECTIONS</span>
                        </div>

                        <div class="space-y-6">
                            <!-- Purpose -->
                            <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-extrabold text-slate-900 text-sm font-heading">Our Purpose & Mandate</h4>
                                    @if(isset($contents['purpose_image']))
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Asset Loaded</span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                    <div class="md:col-span-2 space-y-3">
                                        <div class="space-y-1">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Headline</label>
                                            <input type="text" name="purpose_title" 
                                                   value="{{ ($contents['purpose_title'] ?? '') !== 'Empty' ? ($contents['purpose_title'] ?? '') : '' }}" 
                                                   placeholder="e.g. Safeguarding Research Ethics"
                                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Content Narrative</label>
                                            <textarea name="purpose_text" rows="4" 
                                                      placeholder="Detailed explanation of the research ethics office purpose..."
                                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all leading-relaxed">{{ ($contents['purpose_text'] ?? '') !== 'Empty' ? ($contents['purpose_text'] ?? '') : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Feature Image</label>
                                        @if(isset($contents['purpose_image']))
                                            <div class="relative rounded-xl overflow-hidden border border-slate-200 aspect-[16/10] bg-slate-900 group">
                                                <img src="{{ asset($contents['purpose_image']) }}" alt="Purpose" class="w-full h-full object-cover">
                                                <a href="{{ asset($contents['purpose_image']) }}" target="_blank" 
                                                   class="absolute top-2 right-2 p-1.5 bg-white/90 rounded-lg text-slate-700 hover:text-[#8B0000] shadow-xs">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" name="purpose_image" accept="image/*" 
                                               class="w-full text-xs text-slate-600 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-slate-700 hover:file:bg-slate-50 cursor-pointer min-h-[40px]">
                                    </div>
                                </div>
                            </div>

                            <!-- Join Us -->
                            <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-extrabold text-slate-900 text-sm font-heading">Call to Action: Join Us</h4>
                                    @if(isset($contents['join_image']))
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Asset Loaded</span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                    <div class="md:col-span-2 space-y-3">
                                        <div class="space-y-1">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">CTA Title</label>
                                            <input type="text" name="join_title" 
                                                   value="{{ ($contents['join_title'] ?? '') !== 'Empty' ? ($contents['join_title'] ?? '') : '' }}" 
                                                   placeholder="e.g. Conduct Ethical Research with Us"
                                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Invitation Text</label>
                                            <textarea name="join_text" rows="4" 
                                                      placeholder="Guidance for researchers and reviewers wishing to collaborate..."
                                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all leading-relaxed">{{ ($contents['join_text'] ?? '') !== 'Empty' ? ($contents['join_text'] ?? '') : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Feature Image</label>
                                        @if(isset($contents['join_image']))
                                            <div class="relative rounded-xl overflow-hidden border border-slate-200 aspect-[16/10] bg-slate-900 group">
                                                <img src="{{ asset($contents['join_image']) }}" alt="Join Us" class="w-full h-full object-cover">
                                                <a href="{{ asset($contents['join_image']) }}" target="_blank" 
                                                   class="absolute top-2 right-2 p-1.5 bg-white/90 rounded-lg text-slate-700 hover:text-[#8B0000] shadow-xs">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" name="join_image" accept="image/*" 
                                               class="w-full text-xs text-slate-600 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-slate-700 hover:file:bg-slate-50 cursor-pointer min-h-[40px]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Auth Page Backdrops -->
                    <div id="section-auth" class="bg-white p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 scroll-mt-24 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 font-heading">Authentication Backdrops</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Hero background photography utilized on the Login and Registration screens.</p>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">AUTH SCREENS</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Login Backdrop -->
                            <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-3">
                                <h4 class="font-extrabold text-slate-900 text-sm font-heading">Login Screen Backdrop</h4>
                                @if(isset($contents['login_image']))
                                    <div class="relative rounded-xl overflow-hidden border border-slate-200 aspect-[16/9] bg-slate-900 group">
                                        <img src="{{ asset($contents['login_image']) }}" alt="Login Backdrop" class="w-full h-full object-cover">
                                        <a href="{{ asset($contents['login_image']) }}" target="_blank" 
                                           class="absolute top-2 right-2 p-1.5 bg-white/90 rounded-lg text-slate-700 hover:text-[#8B0000] shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <div class="aspect-[16/9] rounded-xl bg-slate-100 border border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs mt-1">Default background active</span>
                                    </div>
                                @endif
                                <input type="file" name="login_image" accept="image/*" 
                                       class="w-full text-xs text-slate-600 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-slate-700 hover:file:bg-slate-50 cursor-pointer min-h-[40px]">
                            </div>

                            <!-- Register Backdrop -->
                            <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-3">
                                <h4 class="font-extrabold text-slate-900 text-sm font-heading">Register Screen Backdrop</h4>
                                @if(isset($contents['register_image']))
                                    <div class="relative rounded-xl overflow-hidden border border-slate-200 aspect-[16/9] bg-slate-900 group">
                                        <img src="{{ asset($contents['register_image']) }}" alt="Register Backdrop" class="w-full h-full object-cover">
                                        <a href="{{ asset($contents['register_image']) }}" target="_blank" 
                                           class="absolute top-2 right-2 p-1.5 bg-white/90 rounded-lg text-slate-700 hover:text-[#8B0000] shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <div class="aspect-[16/9] rounded-xl bg-slate-100 border border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs mt-1">Default background active</span>
                                    </div>
                                @endif
                                <input type="file" name="register_image" accept="image/*" 
                                       class="w-full text-xs text-slate-600 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-slate-700 hover:file:bg-slate-50 cursor-pointer min-h-[40px]">
                            </div>
                        </div>
                    </div>

                    <!-- 6. Legal & Compliance Disclosures -->
                    <div id="section-legal" class="bg-white p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 scroll-mt-24 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 font-heading">Legal & Compliance Disclosures</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Statutory privacy policies, terms of service, and accessibility statements (HTML enabled).</p>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">COMPLIANCE</span>
                        </div>
                        
                        <div class="space-y-6">
                            @foreach(['Privacy Policy' => 'privacy', 'Terms of Service' => 'terms', 'Accessibility Statement' => 'accessibility'] as $label => $key)
                                <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-extrabold text-slate-900 text-sm font-heading">{{ $label }}</h4>
                                        <span class="text-[11px] font-mono font-bold text-slate-500">/legal/{{ $key }}</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                        <div class="md:col-span-2 space-y-1.5">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Document Content (HTML Formatted)</label>
                                            <textarea name="{{ $key }}_content" rows="10" 
                                                      class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all shadow-xs leading-relaxed"
                                                      placeholder="Enter HTML or markdown content for {{ $label }}...">{{ $contents[$key . '_content'] ?? '' }}</textarea>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Header Cover Image</label>
                                            @if(isset($contents[$key . '_header_image']))
                                                <div class="relative rounded-xl overflow-hidden border border-slate-200 aspect-[16/9] bg-slate-900 group">
                                                    <img src="{{ asset($contents[$key . '_header_image']) }}" alt="{{ $label }}" class="w-full h-full object-cover">
                                                    <a href="{{ asset($contents[$key . '_header_image']) }}" target="_blank" 
                                                       class="absolute top-2 right-2 p-1.5 bg-white/90 rounded-lg text-slate-700 hover:text-[#8B0000] shadow-xs">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="aspect-[16/9] rounded-xl bg-slate-100 border border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                                    <span class="text-xs">No cover image</span>
                                                </div>
                                            @endif
                                            <input type="file" name="{{ $key }}_header_image" accept="image/*" 
                                                   class="w-full text-xs text-slate-600 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-slate-700 hover:file:bg-slate-50 cursor-pointer min-h-[40px]">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 7. Footer Information & Direct Contact -->
                    <div id="section-footer" class="bg-white p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 scroll-mt-24 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 font-heading">Footer & Institutional Contact</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Physical office location, secretariat contact lines, and social links.</p>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">FOOTER</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Footer Mission Statement</label>
                                    <textarea name="footer_description" rows="3" 
                                              placeholder="Concise mandate for the bottom of every public portal page..."
                                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all leading-relaxed">{{ ($contents['footer_description'] ?? '') !== 'Empty' ? ($contents['footer_description'] ?? '') : '' }}</textarea>
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Official Facebook URL</label>
                                    <input type="text" name="footer_facebook" 
                                           value="{{ ($contents['footer_facebook'] ?? '') !== 'Empty' ? ($contents['footer_facebook'] ?? '') : '' }}" 
                                           placeholder="https://facebook.com/WMSUREO"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Secretariat Email</label>
                                    <input type="email" name="footer_email" 
                                           value="{{ $contents['footer_email'] ?? 'reo@wmsu.edu.ph' }}" 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Office Contact Telephone / Mobile</label>
                                    <input type="text" name="footer_phone" 
                                           value="{{ ($contents['footer_phone'] ?? '') !== 'Empty' ? ($contents['footer_phone'] ?? '') : '' }}" 
                                           placeholder="e.g. (062) 991-1771 loc 1234"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Office Physical Address</label>
                                    <textarea name="footer_address" rows="5" 
                                              placeholder="Research Ethics Office, Western Mindanao State University, Normal Road, Baliwasan..."
                                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all leading-relaxed">{{ ($contents['footer_address'] ?? '') !== 'Empty' ? ($contents['footer_address'] ?? '') : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sticky Bottom Action Bar -->
                    <div class="sticky bottom-6 z-30 bg-white/95 backdrop-blur-md border border-slate-200/80 rounded-2xl p-4 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 text-xs text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span>Changes apply instantly across all public portal routes.</span>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <a href="{{ route('index') }}" target="_blank" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors text-center min-h-[44px]">
                                Preview Portal
                            </a>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 focus:outline-none min-h-[44px]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</x-admin_layout>
