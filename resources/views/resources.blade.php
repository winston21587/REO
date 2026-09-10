<x-user_layout>
    <div class="max-w-5xl mx-auto pt-2 pb-28 sm:py-12 px-4 sm:px-6 animate-[fadeInUp_0.5s_ease-out] relative">

        <!-- Subtle Atmospheric Maroon Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-72 bg-gradient-to-b from-red-100/40 via-red-50/20 to-transparent pointer-events-none -z-10 rounded-full blur-3xl" aria-hidden="true"></div>

        <!-- Page Header -->
        <header class="text-center mb-7 sm:mb-10">
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
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search text-sm" aria-hidden="true"></i>
                    </div>
                    <input type="text" 
                           id="resource-search" 
                           placeholder="Search forms by keyword, code, or format..." 
                           class="w-full pl-11 pr-4 sm:pr-28 py-3.5 bg-white border border-slate-200/90 rounded-2xl text-base sm:text-sm font-medium text-slate-800 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] transition-all"
                           autocomplete="off">
                    <div class="absolute inset-y-0 right-0 pr-3.5 hidden sm:flex items-center pointer-events-none">
                        <span id="forms-count-badge" class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-extrabold text-[10px] uppercase tracking-wider border border-slate-200/70 select-none">
                            <span id="forms-count-num">{{ count($downloadables) }}</span> {{ count($downloadables) === 1 ? 'Form' : 'Forms' }}
                        </span>
                    </div>
                </div>

                <!-- Interactive Category & Format Chips (Horizontal swipeable on mobile, centered on tablet/desktop) -->
                <div class="flex items-center gap-2 overflow-x-auto sm:justify-center [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden py-1 px-1" id="category-filters" role="group" aria-label="Filter documents by category">
                    <button type="button" data-filter="all" aria-pressed="true" class="filter-chip active shrink-0 inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-sm border bg-[#8B0000] text-white border-[#8B0000] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-2">
                        All ({{ count($downloadables) }})
                    </button>
                    <button type="button" data-filter="mandatory" aria-pressed="false" class="filter-chip shrink-0 inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-sm border bg-white text-slate-600 border-slate-200 hover:border-red-200 hover:text-[#8B0000] hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-2">
                        <i class="fas fa-star text-[10px] text-amber-500 mr-1.5" aria-hidden="true"></i> Mandatory
                    </button>
                    <button type="button" data-filter="pdf" aria-pressed="false" class="filter-chip shrink-0 inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-sm border bg-white text-slate-600 border-slate-200 hover:border-red-200 hover:text-red-600 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-2">
                        <i class="fas fa-file-pdf text-[10px] text-red-500 mr-1.5" aria-hidden="true"></i> PDF
                    </button>
                    <button type="button" data-filter="doc" aria-pressed="false" class="filter-chip shrink-0 inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-sm border bg-white text-slate-600 border-slate-200 hover:border-blue-200 hover:text-blue-600 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-2">
                        <i class="fas fa-file-word text-[10px] text-blue-500 mr-1.5" aria-hidden="true"></i> Word
                    </button>
                </div>
            </div>
        </header>

        <!-- Section Indicator with Generous Breathing Room -->
        <div class="flex items-center gap-4 my-7 sm:my-9">
            <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-slate-200 flex-1"></div>
            <div class="inline-flex items-center gap-2 text-xs font-black text-[#8B0000] uppercase tracking-widest bg-red-50/90 px-4 py-1.5 rounded-full border border-red-200/70 shadow-xs select-none">
                <i class="fas fa-file-lines text-xs text-[#8B0000]" aria-hidden="true"></i>
                <span>Official Forms & Documents</span>
            </div>
            <div class="h-px bg-gradient-to-r from-slate-200 via-slate-200 to-transparent flex-1"></div>
        </div>

        <!-- Resource Cards Grid (Responsive 1-col on mobile, 2-col on desktop) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 lg:gap-8" id="resources-grid">
            @forelse($downloadables as $resource)
                @php
                    $ext = strtoupper($resource->file_extension ?? pathinfo($resource->file_path, PATHINFO_EXTENSION));
                    $isPdf = in_array($ext, ['PDF']);
                    $isDoc = in_array($ext, ['DOC', 'DOCX']);
                    $typeCategory = $isPdf ? 'pdf' : ($isDoc ? 'doc' : 'other');
                @endphp
                <article class="resource-card group bg-white p-5 sm:p-7 rounded-2xl shadow-sm border border-slate-200/90 hover:border-[#8B0000]/30 hover:shadow-xl transition-all duration-300 flex flex-col justify-between relative overflow-hidden h-full"
                     data-search="{{ strtolower($resource->title . ' ' . $resource->code . ' ' . $ext . ' ' . $resource->description) }}"
                     data-type="{{ $typeCategory }}"
                     data-mandatory="{{ $resource->is_mandatory ? 'true' : 'false' }}">
                    
                    <div>
                        <!-- Card Header: Document Icon + Badges -->
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <!-- Document Icon Avatar -->
                            @if($isPdf)
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0 rounded-2xl bg-gradient-to-br from-red-50 via-red-50/60 to-white border border-red-200/80 text-red-600 flex items-center justify-center shadow-sm group-hover:scale-105 group-hover:shadow-md transition-all duration-300">
                                    <i class="fas fa-file-pdf text-xl sm:text-2xl" aria-hidden="true"></i>
                                </div>
                            @elseif($isDoc)
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0 rounded-2xl bg-gradient-to-br from-blue-50 via-blue-50/60 to-white border border-blue-200/80 text-blue-600 flex items-center justify-center shadow-sm group-hover:scale-105 group-hover:shadow-md transition-all duration-300">
                                    <i class="fas fa-file-word text-xl sm:text-2xl" aria-hidden="true"></i>
                                </div>
                            @else
                                <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-200/80 text-[#8B0000] flex items-center justify-center shadow-sm group-hover:scale-105 group-hover:shadow-md transition-all duration-300">
                                    <i class="fas fa-file-lines text-xl sm:text-2xl" aria-hidden="true"></i>
                                </div>
                            @endif

                            <!-- Badges: Code & Requirement Status -->
                            <div class="flex items-center gap-1.5 flex-wrap justify-end">
                                @if($resource->code)
                                    <span class="bg-slate-900 text-white text-[10px] font-mono font-bold px-2.5 py-0.5 rounded shadow-xs tracking-wide">
                                        {{ $resource->code }}
                                    </span>
                                @endif

                                @if($resource->is_mandatory)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-red-50 text-[#8B0000] border border-red-200/80 shadow-xs">
                                        <i class="fas fa-star text-[8px] text-amber-500" aria-hidden="true"></i>
                                        <span>Mandatory</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 border border-slate-200/60">
                                        <span>Supplementary</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Document Details -->
                        <div>
                            <h3 class="text-base sm:text-lg font-black text-slate-900 group-hover:text-[#8B0000] transition-colors tracking-tight leading-snug line-clamp-2" title="{{ $resource->title }}">
                                {{ $resource->title }}
                            </h3>
                            @if($resource->description)
                                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1.5 leading-relaxed line-clamp-3" title="{{ $resource->description }}">
                                    {{ $resource->description }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400 italic mt-1.5">No additional instructions provided.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Card Footer: Format/Size & Download Action -->
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
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
                                <span class="inline-flex items-center gap-1 text-[10px] font-black px-2.5 py-1 rounded-md border border-slate-200 bg-slate-100 text-slate-700 uppercase tracking-wide">
                                    <i class="fas fa-file-lines text-[9px]" aria-hidden="true"></i>
                                    <span>{{ $ext ?: 'DOC' }}</span>
                                </span>
                            @endif

                            @if($resource->file_size)
                                <span class="text-xs text-slate-400 font-semibold tabular-nums">
                                    {{ $resource->file_size }}
                                </span>
                            @endif
                        </div>

                        <!-- 44px min touch target Download Button -->
                        <a href="{{ asset($resource->file_path) }}" 
                           download
                           target="_blank" 
                           rel="noopener noreferrer" 
                           aria-label="Download {{ $resource->title }} ({{ $ext }}, {{ $resource->file_size }})"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-[#8B0000] hover:bg-[#700000] shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5 active:scale-95 group/btn cursor-pointer min-h-[40px] sm:min-h-[44px] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-2">
                            <span>Download</span>
                            <i class="fas fa-download text-xs group-hover/btn:translate-y-0.5 transition-transform" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-1 md:col-span-2 text-center p-12 bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center justify-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                        <i class="fas fa-folder-open text-3xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No Forms Available</h3>
                    <p class="text-slate-500 mt-2 max-w-sm text-sm">There are currently no downloadable resources available at this time. Please check back later.</p>
                </div>
            @endforelse

            <!-- No Search Results Found State -->
            <div id="no-results" class="hidden col-span-1 md:col-span-2 text-center p-10 sm:p-12 bg-white rounded-2xl shadow-sm border border-slate-200 text-slate-500">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                    <i class="fas fa-search text-2xl text-slate-400" aria-hidden="true"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800">No matching forms found</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Try searching with a different keyword or selecting "All" to view all available resources.</p>
                <button type="button" id="reset-filters-btn" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-[#8B0000] bg-red-50 hover:bg-red-100 border border-red-200 transition-colors cursor-pointer">
                    <i class="fas fa-rotate-left text-xs" aria-hidden="true"></i>
                    <span>Reset Search & Filters</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Client-Side Instant Search & Category Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('resource-search');
            const filterChips = document.querySelectorAll('.filter-chip');
            const cards = document.querySelectorAll('.resource-card');
            const noResults = document.getElementById('no-results');
            const resetBtn = document.getElementById('reset-filters-btn');
            const countNum = document.getElementById('forms-count-num');
            let currentFilter = 'all';

            function applyFilters() {
                const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
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

                if (noResults) {
                    noResults.classList.toggle('hidden', visibleCount > 0);
                }

                if (countNum) {
                    countNum.textContent = visibleCount;
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);

                // Keyboard shortcut: Escape to clear
                window.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && document.activeElement === searchInput) {
                        searchInput.value = '';
                        applyFilters();
                        searchInput.blur();
                    }
                });
            }

            function updateChipStyles() {
                filterChips.forEach(chip => {
                    const filter = chip.getAttribute('data-filter') || 'all';
                    const isActive = filter === currentFilter;
                    chip.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    if (isActive) {
                        chip.className = 'filter-chip active shrink-0 inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-sm border bg-[#8B0000] text-white border-[#8B0000] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-2';
                    } else {
                        chip.className = 'filter-chip shrink-0 inline-flex items-center px-4 py-2 rounded-full text-xs font-bold transition-all cursor-pointer shadow-sm border bg-white text-slate-600 border-slate-200 hover:border-red-200 hover:text-[#8B0000] hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-2';
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

            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    currentFilter = 'all';
                    if (searchInput) {
                        searchInput.value = '';
                    }
                    updateChipStyles();
                    applyFilters();
                });
            }
        });
    </script>
</x-user_layout>