<x-user_layout>
    @php
        $totalCount = $downloadables->count();
        $mandatoryCount = $downloadables->where('is_mandatory', true)->count();
        $supplementaryCount = $totalCount - $mandatoryCount;
        $pdfCount = $downloadables->filter(function($d) {
            $ext = strtoupper($d->file_extension ?? pathinfo($d->file_path, PATHINFO_EXTENSION));
            return $ext === 'PDF';
        })->count();
        $docCount = $downloadables->filter(function($d) {
            $ext = strtoupper($d->file_extension ?? pathinfo($d->file_path, PATHINFO_EXTENSION));
            return in_array($ext, ['DOC', 'DOCX']);
        })->count();
    @endphp

    <div class="max-w-7xl mx-auto space-y-6 pb-24 lg:pb-12">

        <!-- Institutional Header & Executive Summary -->
        <header class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Left: Title & Institutional Breadcrumb -->
                <div class="max-w-2xl min-w-0">
                    <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2" aria-label="Breadcrumb">
                        <span>Research Ethics Oversight</span>
                        <span class="text-slate-300" aria-hidden="true">/</span>
                        <span class="text-[#8B0000]">Downloadable Resources</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading tracking-tight">
                        {{ $contents['resources_title'] ?? 'Downloadable Forms & Templates' }}
                    </h1>
                    <p class="text-sm text-slate-600 font-normal mt-2 leading-relaxed max-w-[70ch]">
                        {{ $contents['resources_intro'] ?? 'Official ethical review application forms, assessment templates, and protocol guidelines required for Western Mindanao State University ethics board submissions.' }}
                    </p>
                </div>

                <!-- Right: High-Density Metric Counters -->
                <div class="grid grid-cols-3 gap-3 sm:gap-4 shrink-0 lg:border-l lg:border-slate-100 lg:pl-8">
                    <div class="bg-slate-50/80 rounded-xl p-3 sm:p-4 border border-slate-100 min-w-[90px] sm:min-w-[110px]">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total</span>
                        <span class="text-xl sm:text-2xl font-black text-slate-900 font-heading tabular-nums block mt-0.5" id="stat-total">
                            {{ $totalCount }}
                        </span>
                        <span class="text-xs text-slate-400 mt-0.5 block">Repository</span>
                    </div>
                    <div class="bg-slate-50/80 rounded-xl p-3 sm:p-4 border border-slate-100 min-w-[90px] sm:min-w-[110px]">
                        <span class="text-xs font-bold text-rose-600 uppercase tracking-wider block">Required</span>
                        <span class="text-xl sm:text-2xl font-black text-rose-600 font-heading tabular-nums block mt-0.5">
                            {{ $mandatoryCount }}
                        </span>
                        <span class="text-xs text-slate-400 mt-0.5 block">Mandatory</span>
                    </div>
                    <div class="bg-slate-50/80 rounded-xl p-3 sm:p-4 border border-slate-100 min-w-[90px] sm:min-w-[110px]">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Templates</span>
                        <span class="text-xl sm:text-2xl font-black text-slate-700 font-heading tabular-nums block mt-0.5">
                            {{ $supplementaryCount }}
                        </span>
                        <span class="text-xs text-slate-400 mt-0.5 block">Supplementary</span>
                    </div>
                </div>
            </div>

            @if(!empty($contents['resources_header_image']))
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <img src="{{ asset($contents['resources_header_image']) }}"
                         class="w-full h-44 sm:h-56 object-cover rounded-xl border border-slate-200/80 block" 
                         alt="{{ $contents['resources_title'] ?? 'Resource Library' }} Banner"
                         width="1200"
                         height="224"
                         loading="lazy">
                </div>
            @endif
        </header>

        <!-- Search & Category Filtering Control Bar -->
        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 sm:p-5" aria-label="Search and filter downloads">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                <!-- Search Field with Focus State & Mobile Immunity -->
                <div class="relative w-full xl:max-w-md">
                    <label for="resource-search" class="sr-only">Search downloadable protocol forms</label>
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" aria-hidden="true">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           id="resource-search" 
                           placeholder="Search forms by title, code, or keyword..." 
                           class="w-full pl-10 pr-16 py-2.5 bg-slate-50/60 border border-slate-200 rounded-xl text-base sm:text-sm font-medium text-slate-900 placeholder-slate-400 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all"
                           autocomplete="off">
                    
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center gap-1.5">
                        <button type="button" 
                                id="clear-search-btn" 
                                class="hidden p-1 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer" 
                                aria-label="Clear search input">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <kbd class="hidden sm:inline-flex items-center px-1.5 py-0.5 text-xs font-semibold text-slate-400 bg-white border border-slate-200 rounded shadow-2xs">
                            /
                        </kbd>
                    </div>
                </div>

                <!-- Structured Segmented Category Filters -->
                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap" id="category-filters" role="tablist" aria-label="Filter documents by requirement and format">
                    <button type="button" 
                            data-filter="all" 
                            role="tab" 
                            aria-selected="true" 
                            class="filter-chip active inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer border bg-slate-900 text-white border-slate-900 shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1">
                        <span>All Forms</span>
                        <span class="px-1.5 py-0.2 rounded bg-slate-800 text-white text-xs tabular-nums" id="badge-count-all">{{ $totalCount }}</span>
                    </button>
                    <button type="button" 
                            data-filter="mandatory" 
                            role="tab" 
                            aria-selected="false" 
                            class="filter-chip inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer border bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1">
                        <span>Mandatory</span>
                        <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 text-xs tabular-nums">{{ $mandatoryCount }}</span>
                    </button>
                    <button type="button" 
                            data-filter="supplementary" 
                            role="tab" 
                            aria-selected="false" 
                            class="filter-chip inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer border bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1">
                        <span>Supplementary</span>
                        <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 text-xs tabular-nums">{{ $supplementaryCount }}</span>
                    </button>
                    <button type="button" 
                            data-filter="pdf" 
                            role="tab" 
                            aria-selected="false" 
                            class="filter-chip inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer border bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1">
                        <span class="text-rose-600">PDF</span>
                        <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 text-xs tabular-nums">{{ $pdfCount }}</span>
                    </button>
                    <button type="button" 
                            data-filter="doc" 
                            role="tab" 
                            aria-selected="false" 
                            class="filter-chip inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer border bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1">
                        <span class="text-blue-600">Word</span>
                        <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 text-xs tabular-nums">{{ $docCount }}</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- DESKTOP VIEW: High-Density Academic Oversight Ledger Table (≥ 1024px) -->
        <div class="hidden lg:block">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden min-w-0">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[768px]" id="desktop-resources-table">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75">
                                <th scope="col" class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Form Code & Protocol Title
                                </th>
                                <th scope="col" class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Requirement Type
                                </th>
                                <th scope="col" class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Format & Specifications
                                </th>
                                <th scope="col" class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($downloadables as $resource)
                                @php
                                    $ext = strtoupper($resource->file_extension ?? pathinfo($resource->file_path, PATHINFO_EXTENSION));
                                    $isPdf = in_array($ext, ['PDF']);
                                    $isDoc = in_array($ext, ['DOC', 'DOCX']);
                                    $isExcel = in_array($ext, ['XLS', 'XLSX', 'CSV']);
                                    $typeCategory = $isPdf ? 'pdf' : ($isDoc ? 'doc' : 'other');
                                    $downloadUrl = asset($resource->file_path);
                                @endphp
                                <tr class="resource-item hover:bg-slate-50/60 transition-colors"
                                    data-search="{{ strtolower($resource->title . ' ' . $resource->code . ' ' . $ext . ' ' . $resource->description) }}"
                                    data-type="{{ $typeCategory }}"
                                    data-mandatory="{{ $resource->is_mandatory ? 'true' : 'false' }}">
                                    
                                    <!-- Form Code & Protocol Title -->
                                    <td class="py-4 px-6 align-middle">
                                        <div class="flex items-start gap-3 min-w-0">
                                            @if($isPdf)
                                                <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200/60 text-rose-600 flex items-center justify-center shrink-0 mt-0.5 shadow-2xs" aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h2a1 1 0 001-1V9a1 1 0 00-1-1H9v6z" />
                                                    </svg>
                                                </div>
                                            @elseif($isDoc)
                                                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200/60 text-blue-600 flex items-center justify-center shrink-0 mt-0.5 shadow-2xs" aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6m-6 4h6m-6 4h4" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200/60 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 shadow-2xs" aria-hidden="true">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11h6m-6 4h6" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    @if($resource->code)
                                                        <span class="text-xs font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200/60 tabular-nums">
                                                            {{ $resource->code }}
                                                        </span>
                                                    @endif
                                                    <h2 class="text-sm font-bold text-slate-900 font-heading hover:text-[#8B0000] transition-colors leading-snug">
                                                        {{ $resource->title }}
                                                    </h2>
                                                </div>
                                                @if($resource->description)
                                                    <p class="text-xs text-slate-500 font-normal mt-1 leading-relaxed max-w-xl line-clamp-1" title="{{ $resource->description }}">
                                                        {{ $resource->description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Requirement Status (Plain high-contrast text, ZERO background pills, ZERO dots) -->
                                    <td class="py-4 px-6 align-middle whitespace-nowrap">
                                        @if($resource->is_mandatory)
                                            <span class="text-xs font-bold uppercase tracking-wider text-rose-600">
                                                Mandatory
                                            </span>
                                        @else
                                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                                Supplementary
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Format & File Size Specifications -->
                                    <td class="py-4 px-6 align-middle whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if($isPdf)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200/60">
                                                    PDF
                                                </span>
                                            @elseif($isDoc)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200/60">
                                                    DOCX
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                    {{ $ext ?: 'DOC' }}
                                                </span>
                                            @endif
                                            <span class="text-slate-300" aria-hidden="true">•</span>
                                            <span class="text-xs text-slate-500 tabular-nums">
                                                {{ $resource->file_size ?: 'Standard' }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Download Action Button -->
                                    <td class="py-4 px-6 align-middle text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-3 justify-end">
                                            <a href="{{ $downloadUrl }}" 
                                               download
                                               target="_blank" 
                                               rel="noopener noreferrer" 
                                               aria-label="Download {{ $resource->title }} ({{ $ext }}, {{ $resource->file_size }})"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-[#8B0000] hover:bg-[#6d0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-150 active:scale-[0.98] shadow-2xs hover:shadow-xs min-h-[40px] cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1">
                                                <span>Download</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-16 px-6 text-center">
                                        <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-800 font-heading">No Downloadable Forms Available</h3>
                                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                            The ethics review board repository has no active downloadable forms configured at this time.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Desktop Search No Results State -->
                <div id="desktop-no-results" class="hidden p-12 text-center bg-white border-t border-slate-100">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 font-heading">No Matching Forms Found</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        No downloadable protocol forms match your search query or selected category filter.
                    </p>
                    <button type="button" 
                            class="reset-filters-btn mt-4 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors cursor-pointer min-h-[36px]">
                        <span>Reset Search and Filters</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- MOBILE & TABLET VIEW: Touch-Ergonomic Adaptive Cards (< 1024px) -->
        <div class="lg:hidden space-y-3.5" id="mobile-resources-container">
            @forelse($downloadables as $resource)
                @php
                    $ext = strtoupper($resource->file_extension ?? pathinfo($resource->file_path, PATHINFO_EXTENSION));
                    $isPdf = in_array($ext, ['PDF']);
                    $isDoc = in_array($ext, ['DOC', 'DOCX']);
                    $typeCategory = $isPdf ? 'pdf' : ($isDoc ? 'doc' : 'other');
                    $downloadUrl = asset($resource->file_path);
                @endphp
                <article class="resource-item bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3.5 transition-all active:scale-[0.99]"
                         data-search="{{ strtolower($resource->title . ' ' . $resource->code . ' ' . $ext . ' ' . $resource->description) }}"
                         data-type="{{ $typeCategory }}"
                         data-mandatory="{{ $resource->is_mandatory ? 'true' : 'false' }}">
                    
                    <!-- Format Icon Tile -->
                    @if($isPdf)
                        <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-200/60 text-rose-600 flex items-center justify-center shrink-0 shadow-2xs" aria-hidden="true">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h2a1 1 0 001-1V9a1 1 0 00-1-1H9v6z" />
                            </svg>
                        </div>
                    @elseif($isDoc)
                        <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-200/60 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs" aria-hidden="true">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6m-6 4h6m-6 4h4" />
                            </svg>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200/60 text-emerald-600 flex items-center justify-center shrink-0 shadow-2xs" aria-hidden="true">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11h6m-6 4h6" />
                            </svg>
                        </div>
                    @endif

                    <!-- Card Content Information -->
                    <div class="flex-1 min-w-0 pr-1">
                        <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                            @if($resource->code)
                                <span class="text-xs font-mono font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200/60 shrink-0 tabular-nums">
                                    {{ $resource->code }}
                                </span>
                            @endif
                            <h2 class="text-sm font-bold text-slate-900 font-heading leading-snug break-words">
                                {{ $resource->title }}
                            </h2>
                        </div>

                        @if($resource->description)
                            <p class="text-xs text-slate-500 font-normal line-clamp-2 leading-relaxed mb-1.5" title="{{ $resource->description }}">
                                {{ $resource->description }}
                            </p>
                        @endif

                        <!-- Metadata Row (Plain uppercase tracking status text, format specification) -->
                        <div class="flex items-center gap-2 flex-wrap">
                            @if($resource->is_mandatory)
                                <span class="text-xs font-bold uppercase tracking-wider text-rose-600">
                                    Mandatory
                                </span>
                            @else
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Supplementary
                                </span>
                            @endif

                            <span class="text-slate-300" aria-hidden="true">•</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-600">
                                {{ $ext }}
                            </span>

                            @if($resource->file_size)
                                <span class="text-slate-300" aria-hidden="true">•</span>
                                <span class="text-xs text-slate-400 tabular-nums">
                                    {{ $resource->file_size }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Guaranteed ≥ 44×44px Touch Target Download Button -->
                    <a href="{{ $downloadUrl }}" 
                       download
                       target="_blank" 
                       rel="noopener noreferrer" 
                       aria-label="Download {{ $resource->title }} ({{ $ext }}, {{ $resource->file_size }})"
                       class="w-11 h-11 shrink-0 rounded-xl bg-[#8B0000] hover:bg-[#6d0000] text-white flex items-center justify-center shadow-2xs hover:shadow-xs transition-all active:scale-95 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>
                </article>
            @empty
                <div class="p-8 text-center bg-white rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 font-heading">No Downloadable Forms Available</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
                        The ethics review board repository has no active downloadable forms configured at this time.
                    </p>
                </div>
            @endforelse

            <!-- Mobile Search No Results State -->
            <div id="mobile-no-results" class="hidden p-8 text-center bg-white rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 font-heading">No Matching Forms Found</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">
                    No downloadable protocol forms match your search query or selected category filter.
                </p>
                <button type="button" 
                        class="reset-filters-btn mt-4 inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors cursor-pointer min-h-[44px]">
                    <span>Reset Search and Filters</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Client-Side Instant Search, Keyboard Shortcuts, and Filter Engine -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('resource-search');
            const clearSearchBtn = document.getElementById('clear-search-btn');
            const filterChips = document.querySelectorAll('.filter-chip');
            const resetBtns = document.querySelectorAll('.reset-filters-btn');
            const desktopNoResults = document.getElementById('desktop-no-results');
            const mobileNoResults = document.getElementById('mobile-no-results');
            const statTotal = document.getElementById('stat-total');
            const badgeCountAll = document.getElementById('badge-count-all');
            
            let currentFilter = 'all';

            function applyFilters() {
                const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
                const items = document.querySelectorAll('.resource-item');
                let visibleCount = 0;

                if (clearSearchBtn) {
                    clearSearchBtn.classList.toggle('hidden', query.length === 0);
                }

                items.forEach(function(item) {
                    const searchData = item.getAttribute('data-search') || '';
                    const typeData = item.getAttribute('data-type') || '';
                    const isMandatory = item.getAttribute('data-mandatory') === 'true';

                    const matchesSearch = query === '' || searchData.indexOf(query) !== -1;
                    let matchesCategory = true;

                    if (currentFilter === 'mandatory') {
                        matchesCategory = isMandatory;
                    } else if (currentFilter === 'supplementary') {
                        matchesCategory = !isMandatory;
                    } else if (currentFilter === 'pdf') {
                        matchesCategory = typeData === 'pdf';
                    } else if (currentFilter === 'doc') {
                        matchesCategory = typeData === 'doc';
                    }

                    const isVisible = matchesSearch && matchesCategory;
                    item.style.display = isVisible ? '' : 'none';
                    if (isVisible) {
                        visibleCount++;
                    }
                });

                // Update empty state indicators
                const isSearchingOrFiltered = query.length > 0 || currentFilter !== 'all';
                const hasNoResults = visibleCount === 0 && isSearchingOrFiltered;

                if (desktopNoResults) {
                    desktopNoResults.classList.toggle('hidden', !hasNoResults);
                }
                if (mobileNoResults) {
                    mobileNoResults.classList.toggle('hidden', !hasNoResults);
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);

                // Keyboard shortcuts: "/" to focus search, "Escape" to clear
                window.addEventListener('keydown', function(e) {
                    if (e.key === '/' && document.activeElement !== searchInput && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                        e.preventDefault();
                        searchInput.focus();
                        searchInput.select();
                    } else if (e.key === 'Escape' && document.activeElement === searchInput) {
                        searchInput.value = '';
                        applyFilters();
                        searchInput.blur();
                    }
                });
            }

            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.focus();
                        applyFilters();
                    }
                });
            }

            function updateChipStyles() {
                filterChips.forEach(function(chip) {
                    const filter = chip.getAttribute('data-filter') || 'all';
                    const isActive = filter === currentFilter;
                    chip.setAttribute('aria-selected', isActive ? 'true' : 'false');

                    if (isActive) {
                        chip.className = 'filter-chip active inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer border bg-slate-900 text-white border-slate-900 shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1';
                        const countBadge = chip.querySelector('span:last-child');
                        if (countBadge) {
                            countBadge.className = 'px-1.5 py-0.2 rounded bg-slate-800 text-white text-xs tabular-nums';
                        }
                    } else {
                        chip.className = 'filter-chip inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer border bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:text-slate-900 shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:ring-offset-1';
                        const countBadge = chip.querySelector('span:last-child');
                        if (countBadge) {
                            countBadge.className = 'px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 text-xs tabular-nums';
                        }
                    }
                });
            }

            filterChips.forEach(function(chip) {
                chip.addEventListener('click', function() {
                    currentFilter = this.getAttribute('data-filter') || 'all';
                    updateChipStyles();
                    applyFilters();
                });
            });

            resetBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    currentFilter = 'all';
                    if (searchInput) {
                        searchInput.value = '';
                    }
                    updateChipStyles();
                    applyFilters();
                });
            });
        });
    </script>
</x-user_layout>