<x-admin_layout title="Page Content Manager">
    <div class="w-full max-w-7xl mx-auto space-y-8 min-w-0">
        
        <!-- Executive Header & Top Actions -->
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div class="max-w-xl">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">Page Content</h1>
                <p class="text-slate-500 mt-1.5 text-sm">Public resource repository, downloadable protocol templates, and submission roadmap instructions.</p>
            </div>
            <div class="flex flex-col xl:flex-row items-stretch xl:items-center gap-2.5 w-full xl:w-auto shrink-0">
                <div class="grid grid-cols-2 xl:flex xl:items-center gap-2 w-full xl:w-auto">
                    <a href="{{ route('resources') }}" target="_blank" 
                       class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-all shadow-xs min-h-[44px] whitespace-nowrap">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Preview Resources
                    </a>
                    <a href="{{ route('instructions') }}" target="_blank" 
                       class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-all shadow-xs min-h-[44px] whitespace-nowrap">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Preview Roadmap
                    </a>
                </div>
                <button type="button" onclick="openAddModal()" 
                        class="w-full xl:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs min-h-[44px] whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Downloadable
                </button>
            </div>
        </div>

        <!-- System Alerts -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-emerald-900">{{ session('success') }}</p>
                    <p class="text-xs text-emerald-700">Public resource repository and page configurations updated successfully.</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-rose-900">Please correct the following errors:</p>
                    <ul class="text-xs text-rose-700 mt-1 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @php
            $totalDownloadables = $downloadables->count();
            $mandatoryCount = $downloadables->where('is_mandatory', true)->count();
            $supplementaryCount = $downloadables->where('is_mandatory', false)->count();
        @endphp

        <!-- Executive Metrics Ribbon -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Managed Pages</span>
                <span class="text-2xl font-extrabold text-slate-900 font-heading tabular-nums mt-1 block">2 Active</span>
                <span class="text-xs text-slate-400 mt-0.5 block">Resources & Instructions</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Downloadable Forms</span>
                <span class="text-2xl font-extrabold text-blue-700 font-heading tabular-nums mt-1 block">{{ $totalDownloadables }} Files</span>
                <span class="text-xs text-slate-400 mt-0.5 block">Active institutional templates</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Mandatory Forms</span>
                <span class="text-2xl font-extrabold text-rose-600 font-heading tabular-nums mt-1 block">{{ $mandatoryCount }} Required</span>
                <span class="text-xs text-slate-400 mt-0.5 block">Prerequisites for protocol intake</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Supplementary</span>
                <span class="text-2xl font-extrabold text-indigo-700 font-heading tabular-nums mt-1 block">{{ $supplementaryCount }} Optional</span>
                <span class="text-xs text-slate-400 mt-0.5 block">Auxiliary templates & guides</span>
            </div>
        </div>

        <!-- Quick Jump Anchor Chips -->
        <div class="bg-white p-2 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-2 overflow-x-auto scrollbar-none">
            <a href="#section-forms" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                Downloadable Forms & Templates
            </a>
            <a href="#section-pages" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                Public Page Headers & Introductions
            </a>
        </div>

        <!-- SECTION 1: Downloadable Forms Manager -->
        <div id="section-forms" class="space-y-4 scroll-mt-24">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 font-heading">Downloadable Protocol Forms</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Manage official institutional templates, application packets, and supplementary guides.</p>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">PUBLIC INTAKE REPOSITORY</span>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="flex-1 min-w-0 max-w-md relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="resourceSearch" placeholder="Search forms by title, code, or description..." oninput="filterResourcesTable()"
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[40px]">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap hidden sm:inline-block">Filter:</label>
                    <select id="requirementFilter" onchange="filterResourcesTable()"
                            class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[40px]">
                        <option value="">All Requirements</option>
                        <option value="Mandatory">Mandatory Only</option>
                        <option value="Supplementary">Supplementary Only</option>
                    </select>
                </div>
            </div>

            <!-- Downloadables Ledger Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden min-w-0">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[720px]" id="resourcesTable">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75">
                                <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Form Code & Title</th>
                                <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">File Specifications</th>
                                <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Requirement Type</th>
                                <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Governance Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($downloadables as $resource)
                                <tr class="hover:bg-slate-50/60 transition-colors resource-row"
                                    data-title="{{ strtolower($resource->title) }}"
                                    data-code="{{ strtolower($resource->code ?? '') }}"
                                    data-desc="{{ strtolower($resource->description ?? '') }}"
                                    data-mandatory="{{ $resource->is_mandatory ? 'Mandatory' : 'Supplementary' }}">
                                    <td class="py-4 px-6">
                                        <div class="flex items-start gap-2.5">
                                            @if($resource->code)
                                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider mt-0.5">[{{ $resource->code }}]</span>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="font-extrabold text-slate-900 text-sm font-heading">{{ $resource->title }}</div>
                                                @if($resource->description)
                                                    <div class="text-xs text-slate-500 mt-0.5 max-w-md line-clamp-1" title="{{ $resource->description }}">{{ $resource->description }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ $resource->file_extension }}</span>
                                            <span class="text-slate-300">•</span>
                                            <span class="text-xs text-slate-500 tabular-nums">{{ $resource->file_size }}</span>
                                        </div>
                                        @if($resource->file_path)
                                            <a href="{{ asset($resource->file_path) }}" target="_blank" 
                                               class="text-[11px] font-bold text-[#8B0000] hover:underline inline-flex items-center gap-1 mt-0.5">
                                                <span>Direct Download</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($resource->is_mandatory)
                                            <span class="text-xs font-bold uppercase tracking-wider text-rose-600">Mandatory</span>
                                        @else
                                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Supplementary</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-2 justify-end">
                                            <button type="button" 
                                                    onclick="editDownloadable({{ $resource->id }}, '{{ addslashes($resource->title) }}', '{{ addslashes($resource->code ?? '') }}', '{{ addslashes($resource->description ?? '') }}', {{ $resource->is_mandatory ? 'true' : 'false' }})" 
                                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors min-h-[36px]"
                                                    title="Edit form information">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button type="button" 
                                                    onclick="openDeleteModal({{ $resource->id }}, '{{ addslashes($resource->title) }}')"
                                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors min-h-[36px]"
                                                    title="Delete form">
                                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="4" class="py-12 px-6 text-center text-slate-400">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="font-bold text-slate-700">No downloadable forms registered yet</p>
                                        <p class="text-xs text-slate-400 mt-1">Upload institutional protocol templates and guidelines.</p>
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="noFilterMatchRow" class="hidden">
                                <td colspan="4" class="py-12 px-6 text-center text-slate-400">
                                    <p class="font-bold text-slate-700">No downloadable forms match your search filters</p>
                                    <p class="text-xs text-slate-400 mt-1">Try adjusting your keywords or requirement filter.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Public Pages Content Form -->
        <div id="section-pages" class="space-y-6 scroll-mt-24 pt-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 font-heading">Public Page Introductions & Banners</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Customize hero headers and intro statements for the Resource Library and Submission Roadmap pages.</p>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">PAGE HEADERS</span>
            </div>

            <form action="{{ route('admin.cms.content.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <input type="hidden" name="section" value="pages">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Resources Page Header Card -->
                    <div class="bg-white p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 font-heading">Resources Page</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Displayed at public route <code class="text-slate-700 font-bold">/resources</code></p>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">PAGE 01</span>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Page Headline Title</label>
                                <input type="text" name="resources_title" 
                                       value="{{ $contents['resources_title'] ?? 'Resources & Downloads' }}" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Introduction Subtext</label>
                                <textarea name="resources_intro" rows="4" 
                                          placeholder="Official guidelines, downloadable application forms, and requirements..."
                                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all leading-relaxed">{{ $contents['resources_intro'] ?? '' }}</textarea>
                            </div>

                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Header Banner Backdrop</label>
                                @if(isset($contents['resources_header_image']))
                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                                        <div class="w-full h-32 rounded-lg bg-slate-200 overflow-hidden">
                                            <img src="{{ asset($contents['resources_header_image']) }}" alt="Resources Header Banner" class="w-full h-full object-cover">
                                        </div>
                                        <a href="{{ asset($contents['resources_header_image']) }}" target="_blank" 
                                           class="text-xs font-bold text-[#8B0000] hover:underline inline-flex items-center gap-1">
                                            <span>View Current Image</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="resources_header_image" accept="image/*"
                                       class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer min-h-[44px]">
                            </div>
                        </div>
                    </div>

                    <!-- Instructions Page Header Card -->
                    <div class="bg-white p-6 sm:p-7 rounded-2xl shadow-xs border border-slate-200/80 space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 font-heading">Instructions Page</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Displayed at public route <code class="text-slate-700 font-bold">/instructions</code></p>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">PAGE 02</span>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Page Headline Title</label>
                                <input type="text" name="instructions_title" 
                                       value="{{ $contents['instructions_title'] ?? 'Submission Guidelines' }}" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Introduction Subtext</label>
                                <textarea name="instructions_intro" rows="4" 
                                          placeholder="Step-by-step roadmap for submitting human subject protocols..."
                                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all leading-relaxed">{{ $contents['instructions_intro'] ?? '' }}</textarea>
                            </div>

                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Header Banner Backdrop</label>
                                @if(isset($contents['instructions_header_image']))
                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                                        <div class="w-full h-32 rounded-lg bg-slate-200 overflow-hidden">
                                            <img src="{{ asset($contents['instructions_header_image']) }}" alt="Instructions Header Banner" class="w-full h-full object-cover">
                                        </div>
                                        <a href="{{ asset($contents['instructions_header_image']) }}" target="_blank" 
                                           class="text-xs font-bold text-[#8B0000] hover:underline inline-flex items-center gap-1">
                                            <span>View Current Image</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="instructions_header_image" accept="image/*"
                                       class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer min-h-[44px]">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sticky Save Changes Action Bar -->
                <div class="sticky bottom-6 z-30 bg-white/95 backdrop-blur-md border border-slate-200/80 rounded-2xl p-4 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 text-xs text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span>Changes to page titles, intros, and banners update immediately upon saving.</span>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 focus:outline-none min-h-[44px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Save Page Content
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <!-- Modals -->

    <!-- Add Downloadable Modal -->
    <div id="add-downloadable-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="addDownloadableTitle">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeAddModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    <form action="{{ route('admin.cms.downloadables.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="p-6 sm:p-7 space-y-6">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                                <div>
                                    <h3 id="addDownloadableTitle" class="text-lg font-extrabold text-slate-900 font-heading">Add Downloadable Form</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Upload a new document or protocol template for public download.</p>
                                </div>
                                <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Document Title <span class="text-rose-600">*</span></label>
                                    <input type="text" name="title" required placeholder="e.g. Application for Initial Review"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Form Code (Optional)</label>
                                    <input type="text" name="code" placeholder="e.g. WMSU-REC-01"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Brief Description (Optional)</label>
                                    <textarea name="description" rows="3" placeholder="Explain the context or target researchers for this document..."
                                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all leading-relaxed"></textarea>
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Document File <span class="text-rose-600">*</span></label>
                                    <input type="file" name="file" required accept=".pdf,.doc,.docx"
                                           class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer min-h-[44px]">
                                    <p class="text-[11px] text-slate-400">Supported formats: PDF, DOCX, DOC (max 25MB).</p>
                                </div>

                                <div class="pt-2">
                                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100/70 transition-colors">
                                        <input type="checkbox" name="is_mandatory" value="1" id="is_mandatory_add" 
                                               class="w-4 h-4 rounded text-[#8B0000] focus:ring-[#8B0000] border-slate-300">
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 block">Mark as Mandatory Intake Requirement</span>
                                            <span class="text-[11px] text-slate-500 block">Highlights this document as compulsory for initial ethics submissions.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-200">
                            <button type="button" onclick="closeAddModal()" 
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition-colors min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs min-h-[44px]">
                                Save Resource
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Downloadable Modal -->
    <div id="edit-downloadable-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="editDownloadableTitle">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeEditModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    <form id="edit-downloadable-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="p-6 sm:p-7 space-y-6">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                                <div>
                                    <h3 id="editDownloadableTitle" class="text-lg font-extrabold text-slate-900 font-heading">Edit Downloadable Form</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Update metadata or replace existing document asset.</p>
                                </div>
                                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Document Title <span class="text-rose-600">*</span></label>
                                    <input type="text" name="title" id="edit_title" required
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Form Code (Optional)</label>
                                    <input type="text" name="code" id="edit_code"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Brief Description (Optional)</label>
                                    <textarea name="description" id="edit_description" rows="3"
                                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all leading-relaxed"></textarea>
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Replace Document File (Optional)</label>
                                    <input type="file" name="file" accept=".pdf,.doc,.docx"
                                           class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border file:border-slate-200 file:text-xs file:font-bold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer min-h-[44px]">
                                    <p class="text-[11px] text-slate-400">Leave blank to retain current file.</p>
                                </div>

                                <div class="pt-2">
                                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100/70 transition-colors">
                                        <input type="checkbox" name="is_mandatory" value="1" id="edit_is_mandatory" 
                                               class="w-4 h-4 rounded text-[#8B0000] focus:ring-[#8B0000] border-slate-300">
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 block">Mark as Mandatory Intake Requirement</span>
                                            <span class="text-[11px] text-slate-500 block">Highlights this document as compulsory for initial ethics submissions.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-200">
                            <button type="button" onclick="closeEditModal()" 
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition-colors min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs min-h-[44px]">
                                Update Resource
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Accessible Delete Confirmation Modal -->
    <div id="delete-downloadable-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200">
                    <form id="delete-downloadable-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="p-6 sm:p-7 space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto sm:mx-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div>
                                <h3 id="deleteModalTitle" class="text-lg font-extrabold text-slate-900 font-heading">Delete Form</h3>
                                <p class="text-xs text-slate-500 mt-1">Are you sure you want to delete <span id="deleteResourceTitle" class="font-bold text-slate-800"></span>? This document will be immediately removed from the public Resource Library.</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-200">
                            <button type="button" onclick="closeDeleteModal()" 
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition-colors min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-rose-700 active:scale-[0.98] transition-all shadow-xs min-h-[44px]">
                                Confirm Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openAddModal() {
            document.getElementById('add-downloadable-modal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('add-downloadable-modal').classList.add('hidden');
        }

        function editDownloadable(id, title, code, description, is_mandatory) {
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_is_mandatory').checked = is_mandatory;
            
            let form = document.getElementById('edit-downloadable-form');
            form.action = "{{ url('/admin/cms/downloadables') }}/" + id;
            
            document.getElementById('edit-downloadable-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-downloadable-modal').classList.add('hidden');
        }

        function openDeleteModal(id, title) {
            document.getElementById('deleteResourceTitle').textContent = title;
            document.getElementById('delete-downloadable-form').action = "{{ url('/admin/cms/downloadables') }}/" + id;
            document.getElementById('delete-downloadable-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-downloadable-modal').classList.add('hidden');
        }

        // Global Escape Key Listener for Modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
                closeDeleteModal();
            }
        });

        // Client-side search and requirement filtering
        function filterResourcesTable() {
            const query = (document.getElementById('resourceSearch').value || '').toLowerCase().trim();
            const filter = document.getElementById('requirementFilter').value;
            const rows = document.querySelectorAll('.resource-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const title = row.getAttribute('data-title') || '';
                const code = row.getAttribute('data-code') || '';
                const desc = row.getAttribute('data-desc') || '';
                const mandatory = row.getAttribute('data-mandatory') || '';

                const matchesQuery = !query || title.includes(query) || code.includes(query) || desc.includes(query);
                const matchesFilter = !filter || mandatory === filter;

                if (matchesQuery && matchesFilter) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noMatchRow = document.getElementById('noFilterMatchRow');
            if (noMatchRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noMatchRow.classList.remove('hidden');
                } else {
                    noMatchRow.classList.add('hidden');
                }
            }
        }
    </script>
</x-admin_layout>
