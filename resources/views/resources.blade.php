<x-user_layout>
    <div class="max-w-5xl mx-auto pt-2 pb-28 sm:py-12 px-4 sm:px-6 animate-[fadeInUp_0.5s_ease-out] relative">

        <!-- Subtle Atmospheric Maroon Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-72 bg-gradient-to-b from-red-100/40 via-red-50/20 to-transparent pointer-events-none -z-10 rounded-full blur-3xl" aria-hidden="true"></div>

        <!-- Page Header -->
        <header class="text-center mb-8 md:mb-12">
            @if(isset($contents['resources_header_image']))
                <img src="{{ asset($contents['resources_header_image']) }}"
                    class="w-full h-48 object-cover rounded-2xl mb-8 shadow-sm" 
                    alt="{{ $contents['resources_title'] ?? 'Resource Library' }} Banner">
            @endif
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                {{ $contents['resources_title'] ?? 'Resource Library' }}
            </h1>
            
            <p class="text-slate-600 font-medium mt-3 text-sm sm:text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                {{ $contents['resources_intro'] ?? 'Download official application forms, assessment templates, and protocol guidelines required for your ethics review submission.' }}
            </p>

            <!-- Quick Search Bar & Interactive Format Chips -->
            <div class="mt-7 max-w-xl mx-auto space-y-3.5">
                <div class="relative flex items-center">
                    <label for="resource-search" class="sr-only">Search forms by keyword, code, or format</label>
                    <i class="fas fa-search absolute left-4 text-slate-400 text-sm pointer-events-none" aria-hidden="true"></i>
                    <input type="text" 
                           id="resource-search" 
                           placeholder="Search forms by keyword, code, or format..." 
                           class="w-full pl-11 pr-24 py-3 bg-white border border-slate-200/90 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                    <span class="absolute right-3.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-extrabold text-[10px] uppercase tracking-wider border border-slate-200/60 hidden sm:inline-block">
                        {{ count($downloadables) }} Forms
                    </span>
                </div>

                <!-- Colorized Category & Format Chips -->
                <div class="flex items-center justify-center gap-2 flex-wrap" id="category-filters" role="group" aria-label="Filter documents by category">
                    <button type="button" data-filter="all" aria-pressed="true" class="filter-chip active inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-2xs border bg-brand-primary text-white border-brand-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                        All ({{ count($downloadables) }})
                    </button>
                    <button type="button" data-filter="mandatory" aria-pressed="false" class="filter-chip inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-2xs border bg-white text-slate-600 border-slate-200 hover:border-red-200 hover:text-brand-primary hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                        <i class="fas fa-star text-[10px] text-amber-500 mr-1.5" aria-hidden="true"></i> Mandatory
                    </button>
                    <button type="button" data-filter="pdf" aria-pressed="false" class="filter-chip inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-2xs border bg-white text-slate-600 border-slate-200 hover:border-red-200 hover:text-red-600 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                        <i class="fas fa-file-pdf text-[10px] text-red-500 mr-1.5" aria-hidden="true"></i> PDF
                    </button>
                    <button type="button" data-filter="doc" aria-pressed="false" class="filter-chip inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-2xs border bg-white text-slate-600 border-slate-200 hover:border-blue-200 hover:text-blue-600 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                        <i class="fas fa-file-word text-[10px] text-blue-500 mr-1.5" aria-hidden="true"></i> Word
                    </button>
                </div>
            </div>
        </header>

        <!-- Section Indicator -->
        <div class="flex items-center gap-4 my-6 md:my-10">
            <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-slate-200 flex-1"></div>
            <h2 class="inline-flex items-center gap-2 text-xs font-black text-brand-primary uppercase tracking-widest bg-red-50/90 px-4 py-1.5 rounded-full border border-red-200/70 shadow-2xs">
                <i class="fas fa-file-signature text-xs" aria-hidden="true"></i>
                <span>Official Forms & Documents</span>
            </h2>
            <div class="h-px bg-gradient-to-r from-slate-200 via-slate-200 to-transparent flex-1"></div>
        </div>

        <!-- MOBILE VIEW (Touch-Optimized Adaptive Cards) -->
        <div class="md:hidden space-y-3.5" id="mobile-resources-container">
            @forelse($downloadables as $resource)
                @php
                    $ext = strtoupper($resource->file_extension ?? pathinfo($resource->file_path, PATHINFO_EXTENSION));
                    $isPdf = in_array($ext, ['PDF']);
                    $isDoc = in_array($ext, ['DOC', 'DOCX']);
                    $typeCategory = $isPdf ? 'pdf' : ($isDoc ? 'doc' : 'other');
                @endphp
                <article class="resource-card bg-white p-4.5 rounded-2xl shadow-sm border {{ $resource->is_mandatory ? 'border-red-200/90 ring-1 ring-red-100' : 'border-slate-200/90' }} flex items-center gap-3.5 relative overflow-hidden transition-all duration-200 active:scale-[0.99]"
                     data-search="{{ strtolower($resource->title . ' ' . $resource->code . ' ' . $ext . ' ' . $resource->description) }}"
                     data-type="{{ $typeCategory }}"
                     data-mandatory="{{ $resource->is_mandatory ? 'true' : 'false' }}">
                    
                    @if($resource->is_mandatory)
                        <div class="absolute top-0 right-0 bg-brand-primary text-white text-[9px] font-black uppercase px-2.5 py-0.5 rounded-bl-lg tracking-wider shadow-2xs z-10 flex items-center gap-1">
                            <i class="fas fa-star text-[7px]" aria-hidden="true"></i>
                            <span>Mandatory</span>
                        </div>
                    @endif

                    <!-- File Type Icon Avatar -->
                    @if($isPdf)
                        <div class="w-12 h-12 rounded-xl text-red-600 bg-red-50/90 border border-red-200/70 flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fas fa-file-pdf text-xl" aria-hidden="true"></i>
                        </div>
                    @elseif($isDoc)
                        <div class="w-12 h-12 rounded-xl text-blue-600 bg-blue-50/90 border border-blue-200/70 flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fas fa-file-word text-xl" aria-hidden="true"></i>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-xl text-brand-primary bg-red-50/90 border border-red-200/70 flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fas fa-file-alt text-xl" aria-hidden="true"></i>
                        </div>
                    @endif

                    <!-- Document Information -->
                    <div class="flex-1 min-w-0 pr-1">
                        <div class="flex items-center gap-1.5 mb-1 flex-wrap {{ $resource->is_mandatory ? 'pr-16' : '' }}">
                            @if($resource->code)
                                <span class="text-[9px] font-black bg-slate-900 text-white px-1.5 py-0.5 rounded tracking-wide shrink-0">
                                    {{ $resource->code }}
                                </span>
                            @endif
                            <h3 class="font-black text-slate-900 text-sm leading-snug break-words">
                                {{ $resource->title }}
                            </h3>
                        </div>
                        @if($resource->description)
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-1.5 font-medium">
                                {{ $resource->description }}
                            </p>
                        @endif
                        
                        <!-- File Metadata Badges (Colorized by Format) -->
                        <div class="flex items-center gap-2">
                            @if($isPdf)
                                <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-md border border-red-200/80 bg-red-50 text-red-700 uppercase tracking-wide">
                                    <i class="fas fa-file-pdf text-[9px]" aria-hidden="true"></i>
                                    <span>PDF</span>
                                </span>
                            @elseif($isDoc)
                                <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-md border border-blue-200/80 bg-blue-50 text-blue-700 uppercase tracking-wide">
                                    <i class="fas fa-file-word text-[9px]" aria-hidden="true"></i>
                                    <span>DOCX</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-md border border-slate-200/80 bg-slate-100 text-slate-700 uppercase tracking-wide">
                                    <i class="fas fa-file-alt text-[9px]" aria-hidden="true"></i>
                                    <span>{{ $ext ?: 'DOC' }}</span>
                                </span>
                            @endif

                            @if($resource->file_size)
                                <span class="text-[10px] text-slate-400 font-semibold">
                                    {{ $resource->file_size }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 44x44px Touch Target Download Button -->
                    <a href="{{ asset($resource->file_path) }}" 
                       download
                       target="_blank" 
                       rel="noopener noreferrer" 
                       aria-label="Download {{ $resource->title }} ({{ $ext }}, {{ $resource->file_size }})"
                       title="Download {{ $resource->title }}"
                       class="w-11 h-11 shrink-0 rounded-xl bg-red-50 hover:bg-brand-primary text-brand-primary hover:text-white border border-red-200/80 hover:border-brand-primary flex items-center justify-center transition-all duration-200 active:scale-95 shadow-2xs hover:shadow-md cursor-pointer group/btn focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                        <i class="fas fa-download text-sm group-hover/btn:translate-y-0.5 transition-transform" aria-hidden="true"></i>
                    </a>
                </article>
            @empty
                <div class="text-center p-10 bg-white rounded-2xl shadow-sm border border-slate-200 text-slate-500">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                        <i class="fas fa-folder-open text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">No Forms Available</h3>
                    <p class="text-xs text-slate-400 max-w-xs mx-auto">There are currently no downloadable resources available at this time.</p>
                </div>
            @endforelse
            
            <div id="mobile-no-results" class="hidden text-center p-8 bg-white rounded-2xl shadow-sm border border-slate-200 text-slate-500">
                <i class="fas fa-search text-2xl text-slate-300 mb-2" aria-hidden="true"></i>
                <p class="text-sm font-bold text-slate-700">No matching documents found</p>
                <p class="text-xs text-slate-400 mt-1">Try searching with a different keyword or selecting "All".</p>
            </div>
        </div>

        <!-- DESKTOP VIEW (Cohesive Card Grid) -->
        <div class="hidden md:block">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8" id="desktop-resources-container">
                @forelse($downloadables as $resource)
                    @php
                        $ext = strtoupper($resource->file_extension ?? pathinfo($resource->file_path, PATHINFO_EXTENSION));
                        $isPdf = in_array($ext, ['PDF']);
                        $isDoc = in_array($ext, ['DOC', 'DOCX']);
                        $typeCategory = $isPdf ? 'pdf' : ($isDoc ? 'doc' : 'other');
                        $cardHoverBorder = $isPdf ? 'hover:border-red-300' : ($isDoc ? 'hover:border-blue-300' : 'hover:border-brand-primary/40');
                    @endphp
                    <article class="resource-card group bg-white p-7 rounded-2xl shadow-sm border {{ $resource->is_mandatory ? 'border-red-200/90 ring-1 ring-red-100' : 'border-slate-200/90' }} {{ $cardHoverBorder }} hover:shadow-2xl hover:shadow-brand-primary/5 transition-all duration-300 relative overflow-hidden flex flex-col h-full"
                         data-search="{{ strtolower($resource->title . ' ' . $resource->code . ' ' . $ext . ' ' . $resource->description) }}"
                         data-type="{{ $typeCategory }}"
                         data-mandatory="{{ $resource->is_mandatory ? 'true' : 'false' }}">
                        
                        <!-- Top-Right Badges -->
                        <div class="absolute top-0 right-0 flex items-center z-10">
                            @if($resource->is_mandatory)
                                <span class="bg-brand-primary text-white text-[10px] font-black uppercase px-3 py-1 rounded-bl-xl shadow-2xs tracking-wider flex items-center gap-1">
                                    <i class="fas fa-star text-[7px]" aria-hidden="true"></i>
                                    <span>Mandatory</span>
                                </span>
                            @endif
                            @if($resource->code)
                                <span class="bg-slate-900 text-white text-[10px] font-mono font-bold px-3 py-1 {{ $resource->is_mandatory ? '' : 'rounded-bl-xl' }} shadow-2xs">
                                    {{ $resource->code }}
                                </span>
                            @endif
                        </div>

                        <!-- Content Row -->
                        <div class="flex items-start gap-5 relative z-10 flex-1 pt-1">
                            @if($isPdf)
                                <div class="w-16 h-16 shrink-0 rounded-2xl bg-gradient-to-br from-red-50 via-red-50/50 to-white border border-red-200/80 text-red-600 shadow-md group-hover:scale-105 group-hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-file-pdf text-2xl" aria-hidden="true"></i>
                                </div>
                            @elseif($isDoc)
                                <div class="w-16 h-16 shrink-0 rounded-2xl bg-gradient-to-br from-blue-50 via-blue-50/50 to-white border border-blue-200/80 text-blue-600 shadow-md group-hover:scale-105 group-hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-file-word text-2xl" aria-hidden="true"></i>
                                </div>
                            @else
                                <div class="w-16 h-16 shrink-0 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-200/80 text-brand-primary shadow-md group-hover:scale-105 group-hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-file-alt text-2xl" aria-hidden="true"></i>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0 pr-4 {{ $resource->is_mandatory || $resource->code ? 'sm:pr-24' : '' }}">
                                <h3 class="text-lg sm:text-xl font-black text-slate-900 group-hover:text-brand-primary transition-colors tracking-tight line-clamp-2" title="{{ $resource->title }}">
                                    {{ $resource->title }}
                                </h3>
                                @if($resource->description)
                                    <p class="text-xs text-slate-500 font-medium mt-2 leading-relaxed line-clamp-3" title="{{ $resource->description }}">
                                        {{ $resource->description }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-auto pt-5 border-t border-slate-100 flex justify-between items-center relative z-10">
                            <div class="flex items-center gap-2">
                                @if($isPdf)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-md border border-red-200/80 bg-red-50 text-red-700 uppercase tracking-wide">
                                        <i class="fas fa-file-pdf text-[9px]" aria-hidden="true"></i>
                                        <span>PDF</span>
                                    </span>
                                @elseif($isDoc)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-md border border-blue-200/80 bg-blue-50 text-blue-700 uppercase tracking-wide">
                                        <i class="fas fa-file-word text-[9px]" aria-hidden="true"></i>
                                        <span>DOCX</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-md border border-slate-200/80 bg-slate-100 text-slate-700 uppercase tracking-wide">
                                        <i class="fas fa-file-alt text-[9px]" aria-hidden="true"></i>
                                        <span>{{ $ext ?: 'DOC' }}</span>
                                    </span>
                                @endif

                                @if($resource->file_size)
                                    <span class="text-[11px] text-slate-400 font-semibold">
                                        {{ $resource->file_size }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ asset($resource->file_path) }}" 
                               download
                               target="_blank" 
                               rel="noopener noreferrer" 
                               aria-label="Download {{ $resource->title }} ({{ $ext }}, {{ $resource->file_size }})"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-extrabold text-xs sm:text-sm text-white bg-brand-primary hover:bg-brand-secondary shadow-md shadow-brand-primary/20 hover:shadow-lg hover:shadow-brand-primary/30 transition-all duration-200 transform hover:-translate-y-0.5 active:scale-95 group/btn cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2">
                                <span>Download</span>
                                <i class="fas fa-download text-xs group-hover/btn:translate-y-0.5 transition-transform" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-2 text-center p-12 bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center justify-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                            <i class="fas fa-folder-open text-3xl" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Resource Library Empty</h3>
                        <p class="text-slate-500 mt-2 max-w-sm text-sm">There are currently no downloadable resources available for researchers at this time. Please check back later.</p>
                    </div>
                @endforelse
            </div>
            
            <div id="desktop-no-results" class="hidden text-center p-12 bg-white rounded-2xl shadow-sm border border-slate-200 text-slate-500">
                <i class="fas fa-search text-3xl text-slate-300 mb-3" aria-hidden="true"></i>
                <p class="text-base font-bold text-slate-700">No matching documents found</p>
                <p class="text-sm text-slate-400 mt-1">Try searching with a different keyword or selecting "All".</p>
            </div>
        </div>
    </div>

    <!-- Client-Side Instant Search & Category Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('resource-search');
            const filterChips = document.querySelectorAll('.filter-chip');
            let currentFilter = 'all';

            function applyFilters() {
                const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
                const cards = document.querySelectorAll('.resource-card');
                let visibleCount = 0;

                cards.forEach(card => {
                    const searchData = card.getAttribute('data-search') || '';
                    const typeData = card.getAttribute('data-type') || '';
                    const isMandatory = card.getAttribute('data-mandatory') === 'true';

                    const matchesSearch = query === '' || searchData.includes(query);
                    let matchesCategory = true;

                    if (currentFilter === 'mandatory') {
                        matchesCategory = isMandatory;
                    } else if (currentFilter === 'pdf') {
                        matchesCategory = typeData === 'pdf';
                    } else if (currentFilter === 'doc') {
                        matchesCategory = typeData === 'doc';
                    }

                    const isVisible = matchesSearch && matchesCategory;
                    card.style.display = isVisible ? '' : 'none';
                    if (isVisible) visibleCount++;
                });

                const mobileNoResults = document.getElementById('mobile-no-results');
                const desktopNoResults = document.getElementById('desktop-no-results');

                if (mobileNoResults) mobileNoResults.classList.toggle('hidden', visibleCount > 0);
                if (desktopNoResults) desktopNoResults.classList.toggle('hidden', visibleCount > 0);
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }

            function updateChipStyles() {
                filterChips.forEach(chip => {
                    const filter = chip.getAttribute('data-filter') || 'all';
                    const isActive = filter === currentFilter;
                    chip.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    if (isActive) {
                        chip.className = 'filter-chip active inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-2xs border bg-brand-primary text-white border-brand-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2';
                    } else {
                        chip.className = 'filter-chip inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-2xs border bg-white text-slate-600 border-slate-200 hover:border-red-200 hover:text-brand-primary hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2';
                    }
                });
            }

            filterChips.forEach(chip => {
                chip.addEventListener('click', function() {
                    currentFilter = this.getAttribute('data-filter') || 'all';
                    updateChipStyles();
                    applyFilters();
                });
            });
        });
    </script>
</x-user_layout>