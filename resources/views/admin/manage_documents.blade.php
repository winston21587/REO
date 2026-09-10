<x-dynamic-component :component="auth()->user()?->role === 'super_admin' ? 'super_admin_layout' : 'admin_layout'" title="Manage Documents">
    <div x-data="{
        isModalOpen: false,
        isDeleteModalOpen: false,
        isEditing: false,
        formAction: '',
        deleteAction: '',
        deleteDocName: '',
        searchQuery: '',
        filterRequired: 'all',
        filterFormat: 'all',
        formData: {
            name: '',
            description: '',
            is_required: true,
            is_multiple: false,
            is_viewable_for_reviewer: true,
            is_downloadable_for_reviewer: true,
            file_type: ''
        },
        selectedFileTypes: ['PDF'],
        baseStoreUrl: '{{ route('admin.document_requirements.store') }}',
        baseUpdateUrl: '{{ url('admin/document-requirements') }}',
        documents: {{ Js::from($documents) }},

        get filteredDocuments() {
            return this.documents.filter(doc => {
                // Search query match
                const q = this.searchQuery.toLowerCase().trim();
                const matchesSearch = !q || 
                    (doc.name && doc.name.toLowerCase().includes(q)) || 
                    (doc.description && doc.description.toLowerCase().includes(q)) ||
                    (doc.file_type && doc.file_type.toLowerCase().includes(q));

                // Filter required
                let matchesRequired = true;
                if (this.filterRequired === 'required') {
                    matchesRequired = Boolean(doc.is_required);
                } else if (this.filterRequired === 'optional') {
                    matchesRequired = !Boolean(doc.is_required);
                }

                // Filter format
                let matchesFormat = true;
                if (this.filterFormat !== 'all') {
                    const docTypes = (doc.file_type || '').toLowerCase();
                    if (this.filterFormat === 'pdf') {
                        matchesFormat = docTypes.includes('pdf');
                    } else if (this.filterFormat === 'word') {
                        matchesFormat = docTypes.includes('word') || docTypes.includes('doc');
                    } else if (this.filterFormat === 'others') {
                        matchesFormat = docTypes.includes('other');
                    }
                }

                return matchesSearch && matchesRequired && matchesFormat;
            });
        },

        openAddModal() {
            this.isEditing = false;
            this.formAction = this.baseStoreUrl;
            this.formData = {
                name: '',
                description: '',
                is_required: true,
                is_multiple: false,
                is_viewable_for_reviewer: true,
                is_downloadable_for_reviewer: true,
                file_type: ''
            };
            this.selectedFileTypes = ['PDF'];
            this.isModalOpen = true;
            this.$nextTick(() => {
                const input = document.getElementById('doc_name_input');
                if (input) input.focus();
            });
        },

        openEditModal(doc) {
            this.isEditing = true;
            this.formAction = `${this.baseUpdateUrl}/${doc.id}`;
            this.formData = {
                name: doc.name,
                description: doc.description || '',
                is_required: Boolean(doc.is_required),
                is_multiple: Boolean(doc.is_multiple),
                is_viewable_for_reviewer: Boolean(doc.is_viewable_for_reviewer),
                is_downloadable_for_reviewer: Boolean(doc.is_downloadable_for_reviewer),
                file_type: doc.file_type || ''
            };
            this.selectedFileTypes = doc.file_type ? doc.file_type.split(',').map(item => item.trim()) : ['PDF'];
            this.isModalOpen = true;
            this.$nextTick(() => {
                const input = document.getElementById('doc_name_input');
                if (input) input.focus();
            });
        },

        confirmDelete(doc) {
            this.deleteDocName = doc.name;
            this.deleteAction = `${this.baseUpdateUrl}/${doc.id}`;
            this.isDeleteModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isDeleteModalOpen = false;
        },

        formatTypesList(typesStr) {
            if (!typesStr) return ['PDF'];
            return typesStr.split(',').map(s => s.trim()).filter(Boolean);
        },

        resetFilters() {
            this.searchQuery = '';
            this.filterRequired = 'all';
            this.filterFormat = 'all';
        }
    }" 
    @keydown.escape.window="closeModal()"
    class="w-full max-w-7xl mx-auto space-y-8 min-w-0 pb-12">

        <!-- Executive Header & Top Action -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pb-6 border-b border-slate-200">
            <div class="min-w-0">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">Manage Documents</h1>
                <p class="text-slate-500 mt-1 text-sm max-w-2xl">
                    Institutional document intake checklist, allowed submission formats, and reviewer access governance.
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button type="button"
                        @click="openAddModal()" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#8B0000] text-white font-semibold text-sm hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-[#8B0000] min-h-[44px] cursor-pointer">
                    <i class="fas fa-plus text-xs" aria-hidden="true"></i>
                    <span>Add Requirement</span>
                </button>
            </div>
        </div>

        <!-- Feedback Alert Banners -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-start gap-3 text-emerald-800 text-sm">
                <i class="fas fa-check-circle text-emerald-600 mt-0.5" aria-hidden="true"></i>
                <div class="flex-1 font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200/80 flex items-start gap-3 text-rose-800 text-sm">
                <i class="fas fa-exclamation-circle text-rose-600 mt-0.5" aria-hidden="true"></i>
                <div class="flex-1">
                    <p class="font-bold mb-1">Please review the form errors:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Executive Metrics Ribbon (4 Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Requirements -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Requirements</span>
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 flex-shrink-0">
                        <i class="fas fa-file-contract text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 tabular-nums">{{ $documents->count() }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Registered intake documents</p>
                </div>
            </div>

            <!-- Card 2: Mandatory Intake -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Mandatory Intake</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 flex-shrink-0">
                        <i class="fas fa-asterisk text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 tabular-nums">{{ $documents->where('is_required', true)->count() }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Required for protocol clearance</p>
                </div>
            </div>

            <!-- Card 3: Reviewer Viewable -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Reviewer Viewable</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <i class="fas fa-eye text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 tabular-nums">{{ $documents->where('is_viewable_for_reviewer', true)->count() }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">In-browser preview enabled</p>
                </div>
            </div>

            <!-- Card 4: Multiple Allowed -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Multi-File Allowed</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class="fas fa-copy text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 tabular-nums">{{ $documents->where('is_multiple', true)->count() }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Batch file uploads permitted</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter Control Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                
                <!-- Left: Search Input -->
                <div class="relative flex-1 max-w-md min-w-0">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search text-xs" aria-hidden="true"></i>
                    </div>
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Search by requirement name, description, or format..." 
                           class="w-full pl-10 pr-9 py-2.5 bg-slate-50/70 focus:bg-white border border-slate-200/80 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] transition-all">
                    <button type="button" 
                            x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                            aria-label="Clear search">
                        <i class="fas fa-times-circle text-xs" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Right: Dropdown Filters & Counter -->
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Required Status Filter -->
                    <div class="flex items-center gap-2">
                        <label for="filter_required" class="text-xs font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Intake:</label>
                        <select id="filter_required" 
                                x-model="filterRequired" 
                                class="bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 py-2.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] cursor-pointer">
                            <option value="all">All Intake Types</option>
                            <option value="required">Mandatory Only</option>
                            <option value="optional">Optional Only</option>
                        </select>
                    </div>

                    <!-- File Format Filter -->
                    <div class="flex items-center gap-2">
                        <label for="filter_format" class="text-xs font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Format:</label>
                        <select id="filter_format" 
                                x-model="filterFormat" 
                                class="bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 py-2.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] cursor-pointer">
                            <option value="all">All Allowed Formats</option>
                            <option value="pdf">PDF Documents</option>
                            <option value="word">Word (DOC/DOCX)</option>
                            <option value="others">Other Extensions</option>
                        </select>
                    </div>

                    <!-- Reset Filters Button (visible when active) -->
                    <button type="button" 
                            x-show="searchQuery !== '' || filterRequired !== 'all' || filterFormat !== 'all'" 
                            @click="resetFilters()" 
                            class="px-3 py-2 text-xs font-bold text-slate-600 hover:text-[#8B0000] hover:bg-slate-100 rounded-lg transition-colors cursor-pointer min-h-[44px] flex items-center gap-1.5"
                            aria-label="Reset all search filters">
                        <i class="fas fa-undo text-[10px]" aria-hidden="true"></i>
                        <span>Reset</span>
                    </button>

                    <!-- Filter Count Badge -->
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 px-2 py-1 bg-slate-100 rounded-lg tabular-nums">
                        <span x-text="filteredDocuments.length"></span>
                        <span class="text-slate-400 font-normal">/</span>
                        <span x-text="documents.length"></span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Document Requirements Table Ledger (Desktop & Tablet) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            
            <!-- Desktop Tabular View (visible on lg: 1024px and wider) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            <th scope="col" class="py-3.5 px-6 font-bold">Requirement Specification</th>
                            <th scope="col" class="py-3.5 px-4 font-bold">Allowed Formats</th>
                            <th scope="col" class="py-3.5 px-4 font-bold text-center">Intake Requirement</th>
                            <th scope="col" class="py-3.5 px-4 font-bold text-center">Upload Limit</th>
                            <th scope="col" class="py-3.5 px-4 font-bold text-center">Reviewer Preview</th>
                            <th scope="col" class="py-3.5 px-4 font-bold text-center">Reviewer Download</th>
                            <th scope="col" class="py-3.5 px-6 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-normal">
                        <template x-for="doc in filteredDocuments" :key="doc.id">
                            <tr class="hover:bg-slate-50/60 transition-colors group">
                                <!-- Document Name & Description -->
                                <td class="py-4 px-6 align-top">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-[#8B0000]/10 group-hover:text-[#8B0000] text-slate-500 flex items-center justify-center flex-shrink-0 mt-0.5 transition-colors">
                                            <i class="fas fa-file-alt text-xs" aria-hidden="true"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors" x-text="doc.name"></p>
                                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-2 leading-relaxed" 
                                               :title="doc.description" 
                                               x-text="doc.description ? doc.description : 'No supplementary instructions provided.'"></p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Allowed Formats -->
                                <td class="py-4 px-4 align-top">
                                    <div class="flex flex-wrap gap-1.5 items-center">
                                        <template x-for="fmt in formatTypesList(doc.file_type)" :key="fmt">
                                            <span x-show="fmt.toLowerCase().includes('pdf')" 
                                                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200/60">
                                                <i class="fas fa-file-pdf text-[10px]" aria-hidden="true"></i>
                                                <span>PDF</span>
                                            </span>
                                        </template>
                                        <template x-for="fmt in formatTypesList(doc.file_type)" :key="fmt">
                                            <span x-show="fmt.toLowerCase().includes('word') || fmt.toLowerCase().includes('doc')" 
                                                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200/60">
                                                <i class="fas fa-file-word text-[10px]" aria-hidden="true"></i>
                                                <span>DOCX</span>
                                            </span>
                                        </template>
                                        <template x-for="fmt in formatTypesList(doc.file_type)" :key="fmt">
                                            <span x-show="fmt.toLowerCase().includes('other')" 
                                                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                                <i class="fas fa-paperclip text-[10px]" aria-hidden="true"></i>
                                                <span>OTHER</span>
                                            </span>
                                        </template>
                                    </div>
                                </td>

                                <!-- Required Status (Zero Pill Slop) -->
                                <td class="py-4 px-4 align-top text-center">
                                    <template x-if="doc.is_required">
                                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 inline-flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle text-[10px]" aria-hidden="true"></i>
                                            <span>MANDATORY</span>
                                        </span>
                                    </template>
                                    <template x-if="!doc.is_required">
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                            OPTIONAL
                                        </span>
                                    </template>
                                </td>

                                <!-- Multiple Files Limit (Zero Pill Slop) -->
                                <td class="py-4 px-4 align-top text-center">
                                    <template x-if="doc.is_multiple">
                                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 inline-flex items-center gap-1">
                                            <i class="fas fa-layer-group text-[10px]" aria-hidden="true"></i>
                                            <span>MULTIPLE</span>
                                        </span>
                                    </template>
                                    <template x-if="!doc.is_multiple">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            SINGLE
                                        </span>
                                    </template>
                                </td>

                                <!-- Reviewer Preview Privilege (Zero Pill Slop) -->
                                <td class="py-4 px-4 align-top text-center">
                                    <template x-if="doc.is_viewable_for_reviewer">
                                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 inline-flex items-center gap-1">
                                            <i class="fas fa-eye text-[10px]" aria-hidden="true"></i>
                                            <span>VIEWABLE</span>
                                        </span>
                                    </template>
                                    <template x-if="!doc.is_viewable_for_reviewer">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            RESTRICTED
                                        </span>
                                    </template>
                                </td>

                                <!-- Reviewer Download Privilege (Zero Pill Slop) -->
                                <td class="py-4 px-4 align-top text-center">
                                    <template x-if="doc.is_downloadable_for_reviewer">
                                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 inline-flex items-center gap-1">
                                            <i class="fas fa-download text-[10px]" aria-hidden="true"></i>
                                            <span>ENABLED</span>
                                        </span>
                                    </template>
                                    <template x-if="!doc.is_downloadable_for_reviewer">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            RESTRICTED
                                        </span>
                                    </template>
                                </td>

                                <!-- Action Buttons (Accessible, min 44px touch area) -->
                                <td class="py-4 px-6 align-top text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button"
                                                @click="openEditModal(doc)" 
                                                class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-500 hover:text-blue-700 hover:bg-blue-50 transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer" 
                                                title="Edit Requirement"
                                                aria-label="Edit Document Requirement">
                                            <i class="fas fa-pencil-alt text-xs" aria-hidden="true"></i>
                                        </button>
                                        <button type="button"
                                                @click="confirmDelete(doc)" 
                                                class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-500 hover:text-rose-700 hover:bg-rose-50 transition-colors focus:outline-none focus:ring-2 focus:ring-rose-500 cursor-pointer" 
                                                title="Delete Requirement"
                                                aria-label="Delete Document Requirement">
                                            <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Mobile & Tablet Adaptive Card Ledger (visible below lg: 1024px) -->
            <div class="lg:hidden divide-y divide-slate-100">
                <template x-for="doc in filteredDocuments" :key="doc.id">
                    <div class="p-5 space-y-4 hover:bg-slate-50/50 transition-colors">
                        
                        <!-- Header: Name and Required Indicator -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-900 text-base leading-snug" x-text="doc.name"></h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed" 
                                   x-text="doc.description ? doc.description : 'No description provided.'"></p>
                            </div>
                            <div class="flex-shrink-0">
                                <template x-if="doc.is_required">
                                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 inline-flex items-center gap-1">
                                        <i class="fas fa-asterisk text-[10px]" aria-hidden="true"></i>
                                        <span>MANDATORY</span>
                                    </span>
                                </template>
                                <template x-if="!doc.is_required">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        OPTIONAL
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Formats Bar -->
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Formats:</span>
                            <div class="flex flex-wrap gap-1.5 items-center">
                                <template x-for="fmt in formatTypesList(doc.file_type)" :key="fmt">
                                    <span x-show="fmt.toLowerCase().includes('pdf')" 
                                          class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <i class="fas fa-file-pdf text-[10px]" aria-hidden="true"></i>
                                        <span>PDF</span>
                                    </span>
                                </template>
                                <template x-for="fmt in formatTypesList(doc.file_type)" :key="fmt">
                                    <span x-show="fmt.toLowerCase().includes('word') || fmt.toLowerCase().includes('doc')" 
                                          class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200/60">
                                        <i class="fas fa-file-word text-[10px]" aria-hidden="true"></i>
                                        <span>DOCX</span>
                                    </span>
                                </template>
                                <template x-for="fmt in formatTypesList(doc.file_type)" :key="fmt">
                                    <span x-show="fmt.toLowerCase().includes('other')" 
                                          class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                        <i class="fas fa-paperclip text-[10px]" aria-hidden="true"></i>
                                        <span>OTHER</span>
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Governance Indicators Grid -->
                        <div class="grid grid-cols-3 gap-2 py-2.5 px-3 bg-slate-50/80 rounded-xl border border-slate-100 text-center">
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Upload</span>
                                <span class="text-xs font-bold uppercase tracking-wider" 
                                      :class="doc.is_multiple ? 'text-blue-600' : 'text-slate-600'" 
                                      x-text="doc.is_multiple ? 'Multiple' : 'Single'"></span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Preview</span>
                                <span class="text-xs font-bold uppercase tracking-wider" 
                                      :class="doc.is_viewable_for_reviewer ? 'text-emerald-600' : 'text-slate-400'" 
                                      x-text="doc.is_viewable_for_reviewer ? 'Viewable' : 'Restricted'"></span>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Download</span>
                                <span class="text-xs font-bold uppercase tracking-wider" 
                                      :class="doc.is_downloadable_for_reviewer ? 'text-emerald-600' : 'text-slate-400'" 
                                      x-text="doc.is_downloadable_for_reviewer ? 'Enabled' : 'Restricted'"></span>
                            </div>
                        </div>

                        <!-- Mobile Action Buttons -->
                        <div class="flex items-center gap-2 pt-1">
                            <button type="button" 
                                    @click="openEditModal(doc)" 
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold text-xs hover:bg-slate-50 transition-colors min-h-[44px] cursor-pointer"
                                    aria-label="Edit Requirement">
                                <i class="fas fa-pencil-alt text-slate-500 text-xs" aria-hidden="true"></i>
                                <span>Edit Requirement</span>
                            </button>
                            <button type="button" 
                                    @click="confirmDelete(doc)" 
                                    class="inline-flex items-center justify-center w-11 h-11 rounded-xl border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors min-h-[44px] min-w-[44px] cursor-pointer" 
                                    title="Delete Requirement"
                                    aria-label="Delete Requirement">
                                <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                            </button>
                        </div>

                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredDocuments.length === 0" class="py-16 px-6 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder-open text-2xl" aria-hidden="true"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 font-heading">No document requirements found</h3>
                <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">
                    No records match your active search filter parameters. Try clearing your search query or reset the filter dropdowns.
                </p>
                <div class="mt-5">
                    <button type="button" 
                            @click="resetFilters()" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs uppercase tracking-wider transition-colors min-h-[44px] cursor-pointer">
                        <i class="fas fa-undo text-[10px]" aria-hidden="true"></i>
                        <span>Reset All Filters</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Add / Edit Modal -->
        <div x-show="isModalOpen" 
             style="display: none"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Frosted Scrim Backdrop -->
            <div x-show="isModalOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="closeModal()"></div>

            <!-- Modal Panel Centering -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div x-show="isModalOpen"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-xl border border-slate-200 flex flex-col max-h-[90vh] overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/50 flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 text-[#8B0000] flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-file-contract text-sm" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-heading" 
                                    id="modal-title" 
                                    x-text="isEditing ? 'Edit Document Requirement' : 'Add New Requirement'"></h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Configure document intake rules, allowed formats, and reviewer visibility.
                                </p>
                            </div>
                        </div>
                        <button type="button" 
                                @click="closeModal()" 
                                class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                                aria-label="Close dialog">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Scrollable Form Body with Sticky Footer -->
                    <form :action="formAction" method="POST" id="documentRequirementForm" class="flex-1 flex flex-col min-h-0 overflow-hidden">
                        @csrf
                        <!-- Method Spoofing for PUT -->
                        <input type="hidden" name="_method" value="PUT" :disabled="!isEditing">

                        <div class="p-6 space-y-6 flex-1 overflow-y-auto">
                            
                            <!-- Document Name Field -->
                            <div>
                                <label for="doc_name_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Document Name <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       x-model="formData.name" 
                                       id="doc_name_input" 
                                       class="w-full rounded-xl border border-slate-200 bg-white shadow-xs focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000] text-sm py-2.5 px-3.5 transition-all text-slate-900 placeholder:text-slate-400 min-h-[44px]" 
                                       placeholder="e.g. Research Ethics Application Form" 
                                       required>
                                <p class="text-[11px] text-slate-500 mt-1">Official descriptive name of the document expected from researchers.</p>
                            </div>

                            <!-- Description Field -->
                            <div>
                                <label for="doc_description_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Instructions or Description <span class="text-slate-400 font-normal lowercase">(optional)</span>
                                </label>
                                <textarea name="description" 
                                          x-model="formData.description" 
                                          id="doc_description_input" 
                                          rows="3" 
                                          class="w-full rounded-xl border border-slate-200 bg-white shadow-xs focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000] text-sm py-2.5 px-3.5 transition-all text-slate-900 placeholder:text-slate-400 leading-relaxed"
                                          placeholder="Specific guidelines, required signatures, or formatting instructions for the researcher..."></textarea>
                            </div>

                            <!-- Allowed File Types Field (Checkboxes) -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Allowed Submission Formats <span class="text-rose-600">*</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                    <!-- PDF -->
                                    <label class="relative flex items-center p-3 rounded-xl border transition-all cursor-pointer select-none"
                                           :class="selectedFileTypes.includes('PDF') ? 'border-[#8B0000] bg-red-50/20' : 'border-slate-200 hover:bg-slate-50'">
                                        <input type="checkbox" 
                                               value="PDF" 
                                               x-model="selectedFileTypes" 
                                               class="h-4 w-4 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] cursor-pointer">
                                        <div class="ml-2.5">
                                            <span class="block text-xs font-bold text-slate-900">PDF (.pdf)</span>
                                            <span class="block text-[10px] text-slate-500">Standard document</span>
                                        </div>
                                    </label>

                                    <!-- Word -->
                                    <label class="relative flex items-center p-3 rounded-xl border transition-all cursor-pointer select-none"
                                           :class="selectedFileTypes.includes('Word') ? 'border-[#8B0000] bg-red-50/20' : 'border-slate-200 hover:bg-slate-50'">
                                        <input type="checkbox" 
                                               value="Word" 
                                               x-model="selectedFileTypes" 
                                               class="h-4 w-4 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] cursor-pointer">
                                        <div class="ml-2.5">
                                            <span class="block text-xs font-bold text-slate-900">Word Document</span>
                                            <span class="block text-[10px] text-slate-500">.doc / .docx</span>
                                        </div>
                                    </label>

                                    <!-- Others -->
                                    <label class="relative flex items-center p-3 rounded-xl border transition-all cursor-pointer select-none"
                                           :class="selectedFileTypes.includes('Others') ? 'border-[#8B0000] bg-red-50/20' : 'border-slate-200 hover:bg-slate-50'">
                                        <input type="checkbox" 
                                               value="Others" 
                                               x-model="selectedFileTypes" 
                                               class="h-4 w-4 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] cursor-pointer">
                                        <div class="ml-2.5">
                                            <span class="block text-xs font-bold text-slate-900">Other Formats</span>
                                            <span class="block text-[10px] text-slate-500">Images, zips, spreadsheets</span>
                                        </div>
                                    </label>
                                </div>
                                <!-- Hidden input to store joined array as string -->
                                <input type="hidden" name="file_type" :value="selectedFileTypes.join(', ')">
                            </div>

                            <!-- Intake & Governance Options Section -->
                            <div class="space-y-3 pt-2">
                                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500">Intake Policy</span>

                                <!-- Required Checkbox Card -->
                                <div class="relative flex items-start p-3.5 rounded-xl border border-slate-200/80 hover:bg-slate-50/70 transition-colors cursor-pointer select-none" 
                                     @click="$refs.is_required.click()">
                                    <div class="flex h-5 items-center mt-0.5">
                                        <input id="is_required" 
                                               x-ref="is_required" 
                                               name="is_required" 
                                               type="checkbox" 
                                               value="1" 
                                               x-model="formData.is_required" 
                                               class="h-4 w-4 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] cursor-pointer">
                                    </div>
                                    <div class="ml-3 text-xs leading-5">
                                        <label for="is_required" class="font-bold text-slate-900 cursor-pointer">Mandatory Intake Requirement</label>
                                        <p class="text-slate-500">Researchers must upload this document before their protocol can be submitted for review.</p>
                                    </div>
                                </div>

                                <!-- Multiple Files Checkbox Card -->
                                <div class="relative flex items-start p-3.5 rounded-xl border border-slate-200/80 hover:bg-slate-50/70 transition-colors cursor-pointer select-none" 
                                     @click="$refs.is_multiple.click()">
                                    <div class="flex h-5 items-center mt-0.5">
                                        <input id="is_multiple" 
                                               x-ref="is_multiple" 
                                               name="is_multiple" 
                                               type="checkbox" 
                                               value="1" 
                                               x-model="formData.is_multiple" 
                                               class="h-4 w-4 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] cursor-pointer">
                                    </div>
                                    <div class="ml-3 text-xs leading-5">
                                        <label for="is_multiple" class="font-bold text-slate-900 cursor-pointer">Permit Multi-File Uploads</label>
                                        <p class="text-slate-500">Allows researchers to upload multiple supplementary files under this single requirement.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Reviewer Access Governance Section -->
                            <div class="space-y-3 pt-2">
                                <span class="block text-xs font-bold uppercase tracking-wider text-slate-500">Reviewer Access Privileges</span>

                                <!-- Reviewer View Checkbox Card -->
                                <div class="relative flex items-start p-3.5 rounded-xl border border-slate-200/80 hover:bg-slate-50/70 transition-colors cursor-pointer select-none" 
                                     @click="$refs.is_viewable_for_reviewer.click()">
                                    <div class="flex h-5 items-center mt-0.5">
                                        <input id="is_viewable_for_reviewer" 
                                               x-ref="is_viewable_for_reviewer" 
                                               name="is_viewable_for_reviewer" 
                                               type="checkbox" 
                                               value="1" 
                                               x-model="formData.is_viewable_for_reviewer" 
                                               class="h-4 w-4 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] cursor-pointer">
                                    </div>
                                    <div class="ml-3 text-xs leading-5">
                                        <label for="is_viewable_for_reviewer" class="font-bold text-slate-900 cursor-pointer">Allow Reviewers to Preview</label>
                                        <p class="text-slate-500">Evaluators can view and read the submitted document directly inside their web review dashboard.</p>
                                    </div>
                                </div>

                                <!-- Reviewer Download Checkbox Card -->
                                <div class="relative flex items-start p-3.5 rounded-xl border border-slate-200/80 hover:bg-slate-50/70 transition-colors cursor-pointer select-none" 
                                     @click="$refs.is_downloadable_for_reviewer.click()">
                                    <div class="flex h-5 items-center mt-0.5">
                                        <input id="is_downloadable_for_reviewer" 
                                               x-ref="is_downloadable_for_reviewer" 
                                               name="is_downloadable_for_reviewer" 
                                               type="checkbox" 
                                               value="1" 
                                               x-model="formData.is_downloadable_for_reviewer" 
                                               class="h-4 w-4 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] cursor-pointer">
                                    </div>
                                    <div class="ml-3 text-xs leading-5">
                                        <label for="is_downloadable_for_reviewer" class="font-bold text-slate-900 cursor-pointer">Allow Reviewers to Download</label>
                                        <p class="text-slate-500">Evaluators can download a local copy of this raw file to their local workstation.</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Sticky Pinned Modal Footer -->
                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 flex-shrink-0">
                            <button type="button" 
                                    @click="closeModal()" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-xs ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors min-h-[44px] cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-[#8B0000] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#6d0000] active:scale-[0.98] transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] min-h-[44px] cursor-pointer">
                                <span x-text="isEditing ? 'Update Requirement' : 'Save Requirement'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="isDeleteModalOpen" 
             style="display: none"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="delete-modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Frosted Scrim Backdrop -->
            <div x-show="isDeleteModalOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="closeModal()"></div>

            <!-- Modal Panel Centering -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div x-show="isDeleteModalOpen"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-md border border-slate-200 overflow-hidden">
                    
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-trash-alt text-lg" aria-hidden="true"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold leading-6 text-slate-900 font-heading" id="delete-modal-title">
                                    Delete Requirement
                                </h3>
                                <div class="mt-2 text-xs text-slate-500 space-y-2 leading-relaxed">
                                    <p>
                                        Are you sure you want to delete <span class="font-bold text-slate-900" x-text="deleteDocName"></span>?
                                    </p>
                                    <p class="text-rose-600 font-medium">
                                        This will remove this document requirement from the researcher intake portal. This action cannot be reversed.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Footer -->
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" 
                                @click="closeModal()" 
                                class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-xs ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors min-h-[44px] cursor-pointer">
                            Cancel
                        </button>
                        <form :action="deleteAction" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-700 active:scale-[0.98] transition-all min-h-[44px] cursor-pointer">
                                Delete Requirement
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-dynamic-component>
