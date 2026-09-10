<x-dynamic-component :component="auth()->user()?->role === 'super_admin' ? 'super_admin_layout' : 'admin_layout'" title="Departments & Programs">
    <div x-data="{
        searchQuery: '',
        filterScope: 'all',
        expandedColleges: {},
        allExpanded: false,

        // Modals
        showCollegeModal: false,
        showDeptModal: false,
        showProgModal: false,
        showDeleteModal: false,

        // Modal Data
        editCollege: { id: null, name: '', code: '', color_assign: '' },
        editDept: { id: null, name: '', code: '', college_id: null },
        editProg: { id: null, name: '', code: '', department_id: null },
        selectedCollegeId: null,

        // Delete Modal State
        deleteType: '', // 'college', 'department', 'program'
        deleteTitle: '',
        deleteMessage: '',
        deleteAction: '',

        // Base URLs
        baseCollegeUrl: '{{ url('admin/cms/colleges') }}',
        baseDeptUrl: '{{ url('admin/cms/departments') }}',
        baseProgUrl: '{{ url('admin/cms/programs') }}',

        // Raw Colleges Data
        collegesData: {{ Js::from($colleges) }},

        init() {
            // Initially keep all colleges closed/collapsed by default
            this.collegesData.forEach(c => {
                this.expandedColleges[c.id] = false;
            });

            // Auto-expand matching colleges when typing in search
            this.$watch('searchQuery', (value) => {
                if (value && value.trim().length > 0) {
                    this.collegesData.forEach(c => {
                        if (this.collegeMatchesSearch(c)) {
                            this.expandedColleges[c.id] = true;
                        }
                    });
                }
            });
        },

        toggleCollege(id) {
            this.expandedColleges[id] = !this.expandedColleges[id];
            const vals = Object.values(this.expandedColleges);
            this.allExpanded = vals.length > 0 && vals.every(v => v === true);
        },

        toggleAll() {
            this.allExpanded = !this.allExpanded;
            this.collegesData.forEach(c => {
                this.expandedColleges[c.id] = this.allExpanded;
            });
        },

        // College Modal Handlers
        openAddCollege() {
            this.editCollege = { id: null, name: '', code: '', color_assign: '' };
            this.showCollegeModal = true;
            this.$nextTick(() => {
                const el = document.getElementById('college_name_input');
                if (el) el.focus();
            });
        },

        openEditCollege(c) {
            this.editCollege = {
                id: c.id,
                name: c.name,
                code: c.code,
                color_assign: c.color_assign || ''
            };
            this.showCollegeModal = true;
            this.$nextTick(() => {
                const el = document.getElementById('college_name_input');
                if (el) el.focus();
            });
        },

        // Department Modal Handlers
        openAddDept(collegeId) {
            this.selectedCollegeId = collegeId;
            this.editDept = { id: null, name: '', code: '', college_id: collegeId };
            this.showDeptModal = true;
            this.$nextTick(() => {
                const el = document.getElementById('dept_name_input');
                if (el) el.focus();
            });
        },

        openEditDept(dept, collegeId) {
            this.selectedCollegeId = collegeId;
            this.editDept = {
                id: dept.id,
                name: dept.name,
                code: dept.code,
                college_id: collegeId
            };
            this.showDeptModal = true;
            this.$nextTick(() => {
                const el = document.getElementById('dept_name_input');
                if (el) el.focus();
            });
        },

        // Program Modal Handlers
        openEditProg(prog) {
            this.editProg = {
                id: prog.id,
                name: prog.name,
                code: prog.code || '',
                department_id: prog.department_id
            };
            this.showProgModal = true;
            this.$nextTick(() => {
                const el = document.getElementById('prog_name_input');
                if (el) el.focus();
            });
        },

        // Accessible Delete Modal Handlers
        confirmDeleteCollege(c) {
            this.deleteType = 'College';
            this.deleteTitle = c.name + ' (' + c.code + ')';
            this.deleteMessage = 'Deleting this college will permanently cascade and delete all departments and degree programs registered under it. This action cannot be undone.';
            this.deleteAction = `${this.baseCollegeUrl}/${c.id}`;
            this.showDeleteModal = true;
        },

        confirmDeleteDept(d) {
            this.deleteType = 'Department';
            this.deleteTitle = d.name + ' (' + d.code + ')';
            this.deleteMessage = 'Deleting this department will permanently remove all degree programs registered under it. This action cannot be undone.';
            this.deleteAction = `${this.baseDeptUrl}/${d.id}`;
            this.showDeleteModal = true;
        },

        confirmDeleteProg(p) {
            this.deleteType = 'Degree Program';
            this.deleteTitle = p.name;
            this.deleteMessage = 'Are you sure you want to delete this degree program? This action cannot be undone.';
            this.deleteAction = `${this.baseProgUrl}/${p.id}`;
            this.showDeleteModal = true;
        },

        closeAllModals() {
            this.showCollegeModal = false;
            this.showDeptModal = false;
            this.showProgModal = false;
            this.showDeleteModal = false;
        },

        // Matching helper for client-side search
        collegeMatchesSearch(college) {
            const q = this.searchQuery.toLowerCase().trim();
            if (!q) return true;

            // Check college name or code
            if (college.name.toLowerCase().includes(q) || college.code.toLowerCase().includes(q)) {
                return true;
            }

            // Check departments and programs
            if (college.departments && college.departments.length > 0) {
                for (const dept of college.departments) {
                    if (dept.name.toLowerCase().includes(q) || dept.code.toLowerCase().includes(q)) {
                        return true;
                    }
                    if (dept.programs && dept.programs.length > 0) {
                        for (const prog of dept.programs) {
                            if (prog.name.toLowerCase().includes(q) || (prog.code && prog.code.toLowerCase().includes(q))) {
                                return true;
                            }
                        }
                    }
                }
            }
            return false;
        },

        // Filter scope helper
        collegeMatchesScope(college) {
            if (this.filterScope === 'all') return true;
            const deptCount = college.departments ? college.departments.length : 0;
            if (this.filterScope === 'has_departments') return deptCount > 0;
            if (this.filterScope === 'no_departments') return deptCount === 0;
            if (this.filterScope === 'has_programs') {
                return college.departments && college.departments.some(d => d.programs && d.programs.length > 0);
            }
            return true;
        },

        get visibleColleges() {
            return this.collegesData.filter(c => this.collegeMatchesSearch(c) && this.collegeMatchesScope(c));
        }
    }" 
    @keydown.escape.window="closeAllModals()"
    class="w-full max-w-7xl mx-auto space-y-8 min-w-0 pb-12">

        <!-- Executive Header & Top Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pb-6 border-b border-slate-200">
            <div class="min-w-0">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">Departments & Programs</h1>
                <p class="text-slate-500 mt-1 text-sm max-w-2xl">
                    Institutional academic hierarchy, college faculties, department registries, and degree program curricula.
                </p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button type="button" 
                        @click="openAddCollege()" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#8B0000] text-white font-semibold text-sm hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-[#8B0000] min-h-[44px] cursor-pointer">
                    <i class="fas fa-plus text-xs" aria-hidden="true"></i>
                    <span>Add College</span>
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
                    <p class="font-bold mb-1">Please check the form inputs:</p>
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
            <!-- Card 1: Total Colleges -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Colleges / Faculties</span>
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 flex-shrink-0">
                        <i class="fas fa-university text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 tabular-nums">{{ $colleges->count() }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Academic divisions</p>
                </div>
            </div>

            <!-- Card 2: Academic Departments -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Departments</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class="fas fa-building text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 tabular-nums">{{ $colleges->sum(fn($c) => $c->departments->count()) }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Instructional units</p>
                </div>
            </div>

            <!-- Card 3: Degree Programs -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Degree Programs</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <i class="fas fa-graduation-cap text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-heading text-3xl font-extrabold text-slate-900 tabular-nums">{{ $colleges->sum(fn($c) => $c->departments->sum(fn($d) => $d->programs->count())) }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Accredited curricula</p>
                </div>
            </div>

            <!-- Card 4: Average Hierarchy Density -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-sm transition-all min-w-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Avg Programs / Dept</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 flex-shrink-0">
                        <i class="fas fa-sitemap text-sm" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="mt-3">
                    @php
                        $deptCount = $colleges->sum(fn($c) => $c->departments->count());
                        $progCount = $colleges->sum(fn($c) => $c->departments->sum(fn($d) => $d->programs->count()));
                        $avg = $deptCount > 0 ? number_format($progCount / $deptCount, 1) : '0.0';
                    @endphp
                    <span class="font-heading text-3xl font-extrabold text-slate-900 tabular-nums">{{ $avg }}</span>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Program spread per unit</p>
                </div>
            </div>
        </div>

        <!-- Search & Control Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                
                <!-- Left: Search Input -->
                <div class="relative flex-1 max-w-md min-w-0">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search text-xs" aria-hidden="true"></i>
                    </div>
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Search colleges, codes, departments, or programs..." 
                           class="w-full pl-10 pr-9 py-2.5 bg-slate-50/70 focus:bg-white border border-slate-200/80 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] transition-all">
                    <button type="button" 
                            x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                            aria-label="Clear search">
                        <i class="fas fa-times-circle text-xs" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Right: Scope Filter & Expand/Collapse Toggle -->
                <div class="flex flex-wrap items-center gap-3">
                    
                    <!-- Scope Filter Dropdown -->
                    <div class="flex items-center gap-2">
                        <label for="filter_scope" class="text-xs font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Show:</label>
                        <select id="filter_scope" 
                                x-model="filterScope" 
                                class="bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 py-2.5 pl-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[44px] cursor-pointer">
                            <option value="all">All Colleges</option>
                            <option value="has_departments">With Departments</option>
                            <option value="has_programs">With Degree Programs</option>
                            <option value="no_departments">Empty Units (No Depts)</option>
                        </select>
                    </div>

                    <!-- Expand / Collapse All Button -->
                    <button type="button" 
                            @click="toggleAll()" 
                            class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200/80 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold uppercase tracking-wider transition-colors min-h-[44px] cursor-pointer"
                            aria-label="Toggle all college sections">
                        <i class="fas" :class="allExpanded ? 'fa-compress-alt' : 'fa-expand-alt'" aria-hidden="true"></i>
                        <span x-text="allExpanded ? 'Collapse All' : 'Expand All'"></span>
                    </button>

                    <!-- Reset Filters Button -->
                    <button type="button" 
                            x-show="searchQuery !== '' || filterScope !== 'all'" 
                            @click="searchQuery = ''; filterScope = 'all'" 
                            class="px-3 py-2 text-xs font-bold text-slate-600 hover:text-[#8B0000] hover:bg-slate-100 rounded-lg transition-colors cursor-pointer min-h-[44px] flex items-center gap-1.5"
                            aria-label="Reset all search filters">
                        <i class="fas fa-undo text-[10px]" aria-hidden="true"></i>
                        <span>Reset</span>
                    </button>

                    <!-- Count Badge -->
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 px-2.5 py-2 bg-slate-100 rounded-lg tabular-nums">
                        <span x-text="visibleColleges.length"></span>
                        <span class="text-slate-400 font-normal">/</span>
                        <span x-text="collegesData.length"></span>
                    </div>

                </div>

            </div>
        </div>

        <!-- Colleges Accordion / Structural Ledger -->
        <div class="space-y-6">
            <template x-for="college in visibleColleges" :key="college.id">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden transition-all duration-200 hover:border-slate-300">
                    
                    <!-- College Header Bar -->
                    <div class="p-5 sm:p-6 bg-slate-50/70 border-b border-slate-200/80 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                        
                        <!-- Left: College Identification & Stats (Clickable) -->
                        <div class="flex items-start sm:items-center gap-3.5 min-w-0 cursor-pointer select-none group/title"
                             @click="toggleCollege(college.id)"
                             role="button"
                             :aria-expanded="expandedColleges[college.id] ? 'true' : 'false'">
                            <!-- Code Badge Container -->
                            <div class="w-12 h-12 rounded-xl bg-slate-900 group-hover/title:bg-[#8B0000] text-white flex flex-col items-center justify-center flex-shrink-0 shadow-xs transition-colors">
                                <span class="text-xs font-extrabold tracking-wider font-heading uppercase" x-text="college.code ? college.code.slice(0, 4) : 'COL'"></span>
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-lg font-bold text-slate-900 group-hover/title:text-[#8B0000] font-heading leading-snug transition-colors" x-text="college.name"></h2>
                                    <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 bg-white border border-slate-200 text-slate-600 rounded-md shadow-2xs" 
                                          x-text="college.code"></span>
                                </div>
                                <div class="flex items-center gap-3 mt-1 text-xs text-slate-500 font-medium">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fas fa-building text-[10px] text-slate-400" aria-hidden="true"></i>
                                        <span class="tabular-nums font-bold text-slate-700" x-text="college.departments ? college.departments.length : 0"></span>
                                        <span>Departments</span>
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fas fa-graduation-cap text-[10px] text-slate-400" aria-hidden="true"></i>
                                        <span class="tabular-nums font-bold text-slate-700" 
                                              x-text="college.departments ? college.departments.reduce((acc, d) => acc + (d.programs ? d.programs.length : 0), 0) : 0"></span>
                                        <span>Programs</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: College Action Buttons & Expand Toggle -->
                        <div class="flex flex-wrap items-center gap-2 self-start xl:self-center flex-shrink-0 pt-2 xl:pt-0 border-t xl:border-t-0 border-slate-200/60 w-full xl:w-auto justify-end">
                            
                            <!-- Add Department Button -->
                            <button type="button" 
                                    @click="openAddDept(college.id)" 
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-[#8B0000]/30 bg-red-50/50 hover:bg-[#8B0000] text-[#8B0000] hover:text-white text-xs font-bold transition-all min-h-[40px] cursor-pointer"
                                    title="Add department to this college"
                                    aria-label="Add Department to College">
                                <i class="fas fa-plus text-[10px]" aria-hidden="true"></i>
                                <span>Add Department</span>
                            </button>

                            <!-- Edit College Button -->
                            <button type="button" 
                                    @click="openEditCollege(college)" 
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-blue-700 hover:bg-blue-50 transition-colors flex items-center justify-center min-h-[40px] min-w-[40px] cursor-pointer"
                                    title="Edit College Information"
                                    aria-label="Edit College">
                                <i class="fas fa-pencil-alt text-xs" aria-hidden="true"></i>
                            </button>

                            <!-- Delete College Button -->
                            <button type="button" 
                                    @click="confirmDeleteCollege(college)" 
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-rose-700 hover:bg-rose-50 transition-colors flex items-center justify-center min-h-[40px] min-w-[40px] cursor-pointer"
                                    title="Delete College"
                                    aria-label="Delete College">
                                <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                            </button>

                            <!-- Accordion Toggle Button -->
                            <button type="button" 
                                    @click="toggleCollege(college.id)" 
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors flex items-center justify-center min-h-[40px] min-w-[40px] cursor-pointer"
                                    :title="expandedColleges[college.id] ? 'Collapse College' : 'Expand College'"
                                    :aria-expanded="expandedColleges[college.id] ? 'true' : 'false'">
                                <i class="fas text-xs transition-transform duration-200" 
                                   :class="expandedColleges[college.id] ? 'fa-chevron-up' : 'fa-chevron-down'" aria-hidden="true"></i>
                            </button>

                        </div>

                    </div>

                    <!-- Collapsible Departments List Body -->
                    <div x-show="expandedColleges[college.id]" 
                         x-transition:enter="ease-out duration-200"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-[5000px]"
                         class="p-5 sm:p-6 bg-white">
                        
                        <!-- When Departments Exist -->
                        <template x-if="college.departments && college.departments.length > 0">
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                                <template x-for="dept in college.departments" :key="dept.id">
                                    <div class="rounded-xl border border-slate-200/90 bg-slate-50/40 p-4 sm:p-5 flex flex-col justify-between hover:border-slate-300 transition-all">
                                        
                                        <!-- Department Header -->
                                        <div>
                                            <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-200/60">
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <h3 class="font-bold text-slate-900 text-sm leading-snug" x-text="dept.name"></h3>
                                                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-slate-200/80 text-slate-700 rounded" 
                                                              x-text="dept.code"></span>
                                                    </div>
                                                    <p class="text-xs text-slate-500 mt-0.5 font-medium tabular-nums">
                                                        <span x-text="dept.programs ? dept.programs.length : 0"></span> Degree Program(s)
                                                    </p>
                                                </div>
                                                
                                                <!-- Department Edit & Delete Actions (Always visible, accessible) -->
                                                <div class="flex items-center gap-1 flex-shrink-0">
                                                    <button type="button" 
                                                            @click="openEditDept(dept, college.id)" 
                                                            class="w-8 h-8 rounded-lg text-slate-500 hover:text-blue-700 hover:bg-blue-100/50 flex items-center justify-center transition-colors cursor-pointer" 
                                                            title="Edit Department"
                                                            aria-label="Edit Department">
                                                        <i class="fas fa-pencil-alt text-xs" aria-hidden="true"></i>
                                                    </button>
                                                    <button type="button" 
                                                            @click="confirmDeleteDept(dept)" 
                                                            class="w-8 h-8 rounded-lg text-slate-500 hover:text-rose-700 hover:bg-rose-100/50 flex items-center justify-center transition-colors cursor-pointer" 
                                                            title="Delete Department"
                                                            aria-label="Delete Department">
                                                        <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Programs Registered List -->
                                            <div class="mt-3.5 space-y-2">
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Curriculum Programs:</span>
                                                
                                                <template x-if="dept.programs && dept.programs.length > 0">
                                                    <div class="space-y-1.5">
                                                        <template x-for="prog in dept.programs" :key="prog.id">
                                                            <div class="flex items-center justify-between gap-2 py-1.5 px-2.5 rounded-lg bg-white border border-slate-200/70 hover:border-slate-300 transition-colors">
                                                                <div class="flex items-center gap-2 min-w-0">
                                                                    <i class="fas fa-circle text-[6px] text-slate-300 flex-shrink-0" aria-hidden="true"></i>
                                                                    <span class="text-xs font-semibold text-slate-800 truncate" :title="prog.name" x-text="prog.name"></span>
                                                                    <span x-show="prog.code" class="text-[10px] font-mono text-slate-400 uppercase" x-text="prog.code"></span>
                                                                </div>
                                                                
                                                                <!-- Program Edit/Delete Buttons -->
                                                                <div class="flex items-center gap-0.5 flex-shrink-0">
                                                                    <button type="button" 
                                                                            @click="openEditProg(prog)" 
                                                                            class="w-7 h-7 rounded text-slate-400 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition-colors cursor-pointer" 
                                                                            title="Edit Program"
                                                                            aria-label="Edit Program">
                                                                        <i class="fas fa-pencil-alt text-[10px]" aria-hidden="true"></i>
                                                                    </button>
                                                                    <button type="button" 
                                                                            @click="confirmDeleteProg(prog)" 
                                                                            class="w-7 h-7 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer" 
                                                                            title="Delete Program"
                                                                            aria-label="Delete Program">
                                                                        <i class="fas fa-times text-xs" aria-hidden="true"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>

                                                <template x-if="!dept.programs || dept.programs.length === 0">
                                                    <p class="text-xs text-slate-400 italic py-1">No degree programs registered under this department.</p>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Quick Add Program Form for this department -->
                                        <form action="{{ route('admin.cms.programs.store') }}" method="POST" class="mt-4 pt-3 border-t border-slate-200/60 flex items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="department_id" :value="dept.id">
                                            <div class="relative flex-1">
                                                <input type="text" 
                                                       name="name" 
                                                       placeholder="Add degree program to this department..." 
                                                       required 
                                                       class="w-full px-3 py-2 text-xs text-slate-900 placeholder:text-slate-400 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent min-h-[38px] transition-all">
                                            </div>
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center gap-1 px-3 py-2 bg-slate-900 hover:bg-[#8B0000] text-white text-xs font-bold rounded-xl transition-all min-h-[38px] cursor-pointer flex-shrink-0"
                                                    title="Register Program">
                                                <i class="fas fa-plus text-[10px]" aria-hidden="true"></i>
                                                <span class="hidden sm:inline">Add</span>
                                            </button>
                                        </form>

                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- When College has No Departments -->
                        <template x-if="!college.departments || college.departments.length === 0">
                            <div class="py-10 text-center border-2 border-dashed border-slate-200 rounded-xl">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-building text-base" aria-hidden="true"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-700">No departments in this college yet</p>
                                <p class="text-xs text-slate-400 mt-0.5">Start structuring this faculty by creating its first academic department.</p>
                                <button type="button" 
                                        @click="openAddDept(college.id)" 
                                        class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-[#8B0000] hover:text-white text-slate-700 text-xs font-bold transition-all min-h-[40px] cursor-pointer">
                                    <i class="fas fa-plus text-[10px]" aria-hidden="true"></i>
                                    <span>Add First Department</span>
                                </button>
                            </div>
                        </template>

                    </div>

                </div>
            </template>
        </div>

        <!-- Global Empty State (Search / Filter yields 0 colleges) -->
        <div x-show="visibleColleges.length === 0" class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-university text-2xl" aria-hidden="true"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800 font-heading">No academic units found</h3>
            <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">
                No colleges match your active search terms or filter constraints. Try clearing your search parameters.
            </p>
            <div class="mt-5">
                <button type="button" 
                        @click="searchQuery = ''; filterScope = 'all'" 
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs uppercase tracking-wider transition-colors min-h-[44px] cursor-pointer">
                    <i class="fas fa-undo text-[10px]" aria-hidden="true"></i>
                    <span>Reset All Filters</span>
                </button>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- MODAL 1: College Add / Edit Modal              -->
        <!-- ============================================== -->
        <div x-show="showCollegeModal" 
             style="display: none"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="college-modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <div x-show="showCollegeModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showCollegeModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div x-show="showCollegeModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-slate-200 flex flex-col max-h-[90vh] overflow-hidden">
                    
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/50 flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 text-[#8B0000] flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-university text-sm" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-heading" 
                                    id="college-modal-title" 
                                    x-text="editCollege.id ? 'Edit College Details' : 'Register New College'"></h3>
                                <p class="text-xs text-slate-500 mt-0.5">Define academic faculty identity and official abbreviation.</p>
                            </div>
                        </div>
                        <button type="button" 
                                @click="showCollegeModal = false" 
                                class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                                aria-label="Close dialog">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <form :action="editCollege.id ? `${baseCollegeUrl}/${editCollege.id}` : '{{ route('admin.cms.colleges.store') }}'" 
                          method="POST" 
                          class="flex-1 flex flex-col min-h-0 overflow-hidden">
                        @csrf
                        <template x-if="editCollege.id">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="p-6 space-y-5 flex-1 overflow-y-auto">
                            <div>
                                <label for="college_name_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    College / Faculty Name <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       x-model="editCollege.name" 
                                       id="college_name_input" 
                                       required 
                                       class="w-full rounded-xl border border-slate-200 bg-white shadow-xs focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000] text-sm py-2.5 px-3.5 transition-all text-slate-900 placeholder:text-slate-400 min-h-[44px]"
                                       placeholder="e.g. College of Computing Studies">
                            </div>

                            <div>
                                <label for="college_code_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Official College Code <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" 
                                       name="code" 
                                       x-model="editCollege.code" 
                                       id="college_code_input" 
                                       required 
                                       maxlength="10"
                                       class="w-full rounded-xl border border-slate-200 bg-white shadow-xs focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000] text-sm py-2.5 px-3.5 transition-all text-slate-900 uppercase placeholder:text-slate-400 font-mono min-h-[44px]"
                                       placeholder="e.g. CCS">
                                <p class="text-[11px] text-slate-500 mt-1">Unique short acronym used for protocol tagging and analytics.</p>
                            </div>

                            <div>
                                <label for="college_color_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Brand Color Hex <span class="text-slate-400 font-normal lowercase">(optional)</span>
                                </label>
                                <div class="flex items-center gap-3">
                                    <input type="text" 
                                           name="color_assign" 
                                           x-model="editCollege.color_assign" 
                                           id="college_color_input" 
                                           class="w-full rounded-xl border border-slate-200 bg-white shadow-xs focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000] text-sm py-2.5 px-3.5 transition-all text-slate-900 placeholder:text-slate-400 font-mono min-h-[44px]"
                                           placeholder="e.g. #8B0000">
                                </div>
                            </div>
                        </div>

                        <!-- Pinned Footer -->
                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 flex-shrink-0">
                            <button type="button" 
                                    @click="showCollegeModal = false" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-xs ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors min-h-[44px] cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-[#8B0000] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#6d0000] active:scale-[0.98] transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] min-h-[44px] cursor-pointer">
                                <span x-text="editCollege.id ? 'Update College' : 'Save College'"></span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- MODAL 2: Department Add / Edit Modal           -->
        <!-- ============================================== -->
        <div x-show="showDeptModal" 
             style="display: none"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="dept-modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <div x-show="showDeptModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showDeptModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div x-show="showDeptModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-slate-200 flex flex-col max-h-[90vh] overflow-hidden">
                    
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/50 flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-building text-sm" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-heading" 
                                    id="dept-modal-title" 
                                    x-text="editDept.id ? 'Edit Department' : 'Add Department'"></h3>
                                <p class="text-xs text-slate-500 mt-0.5">Instructional department registration under chosen college.</p>
                            </div>
                        </div>
                        <button type="button" 
                                @click="showDeptModal = false" 
                                class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                                aria-label="Close dialog">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <form :action="editDept.id ? `${baseDeptUrl}/${editDept.id}` : '{{ route('admin.cms.departments.store') }}'" 
                          method="POST" 
                          class="flex-1 flex flex-col min-h-0 overflow-hidden">
                        @csrf
                        <template x-if="editDept.id">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="college_id" :value="selectedCollegeId || editDept.college_id">

                        <div class="p-6 space-y-5 flex-1 overflow-y-auto">
                            <div>
                                <label for="dept_name_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Department Name <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       x-model="editDept.name" 
                                       id="dept_name_input" 
                                       required 
                                       class="w-full rounded-xl border border-slate-200 bg-white shadow-xs focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000] text-sm py-2.5 px-3.5 transition-all text-slate-900 placeholder:text-slate-400 min-h-[44px]"
                                       placeholder="e.g. Department of Computer Science">
                            </div>

                            <div>
                                <label for="dept_code_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Department Code <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" 
                                       name="code" 
                                       x-model="editDept.code" 
                                       id="dept_code_input" 
                                       required 
                                       maxlength="10"
                                       class="w-full rounded-xl border border-slate-200 bg-white shadow-xs focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000] text-sm py-2.5 px-3.5 transition-all text-slate-900 uppercase placeholder:text-slate-400 font-mono min-h-[44px]"
                                       placeholder="e.g. CS">
                                <p class="text-[11px] text-slate-500 mt-1">Unique department identifier within the institution.</p>
                            </div>
                        </div>

                        <!-- Pinned Footer -->
                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 flex-shrink-0">
                            <button type="button" 
                                    @click="showDeptModal = false" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-xs ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors min-h-[44px] cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-[#8B0000] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#6d0000] active:scale-[0.98] transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] min-h-[44px] cursor-pointer">
                                <span x-text="editDept.id ? 'Update Department' : 'Save Department'"></span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- MODAL 3: Program Edit Modal                    -->
        <!-- ============================================== -->
        <div x-show="showProgModal" 
             style="display: none"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="prog-modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <div x-show="showProgModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showProgModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div x-show="showProgModal"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-md border border-slate-200 flex flex-col max-h-[90vh] overflow-hidden">
                    
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/50 flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-graduation-cap text-sm" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 font-heading" id="prog-modal-title">Edit Degree Program</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Modify program name and curriculum code.</p>
                            </div>
                        </div>
                        <button type="button" 
                                @click="showProgModal = false" 
                                class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                                aria-label="Close dialog">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <form :action="`${baseProgUrl}/${editProg.id}`" 
                          method="POST" 
                          class="flex-1 flex flex-col min-h-0 overflow-hidden">
                        @csrf
                        @method('PUT')

                        <div class="p-6 space-y-5 flex-1 overflow-y-auto">
                            <div>
                                <label for="prog_name_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Degree Program Title <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       x-model="editProg.name" 
                                       id="prog_name_input" 
                                       required 
                                       class="w-full rounded-xl border border-slate-200 bg-white shadow-xs focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000] text-sm py-2.5 px-3.5 transition-all text-slate-900 placeholder:text-slate-400 min-h-[44px]"
                                       placeholder="e.g. Bachelor of Science in Computer Science">
                            </div>

                            <div>
                                <label for="prog_code_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Program Code <span class="text-slate-400 font-normal lowercase">(optional)</span>
                                </label>
                                <input type="text" 
                                       name="code" 
                                       x-model="editProg.code" 
                                       id="prog_code_input" 
                                       class="w-full rounded-xl border border-slate-200 bg-white shadow-xs focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000] text-sm py-2.5 px-3.5 transition-all text-slate-900 placeholder:text-slate-400 font-mono min-h-[44px]"
                                       placeholder="e.g. BSCS">
                            </div>
                        </div>

                        <!-- Pinned Footer -->
                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 flex-shrink-0">
                            <button type="button" 
                                    @click="showProgModal = false" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-xs ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors min-h-[44px] cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-[#8B0000] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#6d0000] active:scale-[0.98] transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] min-h-[44px] cursor-pointer">
                                Update Program
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- MODAL 4: Accessible Delete Confirmation Modal  -->
        <!-- ============================================== -->
        <div x-show="showDeleteModal" 
             style="display: none"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="delete-dialog-title" 
             role="dialog" 
             aria-modal="true">
            
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showDeleteModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div x-show="showDeleteModal"
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
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold leading-6 text-slate-900 font-heading" id="delete-dialog-title">
                                    Delete <span x-text="deleteType"></span>
                                </h3>
                                <div class="mt-2 text-xs text-slate-500 space-y-2 leading-relaxed">
                                    <p>
                                        Are you sure you want to remove <span class="font-bold text-slate-900" x-text="deleteTitle"></span>?
                                    </p>
                                    <p class="text-rose-600 font-medium" x-text="deleteMessage"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Footer -->
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" 
                                @click="showDeleteModal = false" 
                                class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-xs ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors min-h-[44px] cursor-pointer">
                            Cancel
                        </button>
                        <form :action="deleteAction" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-700 active:scale-[0.98] transition-all min-h-[44px] cursor-pointer">
                                Confirm Deletion
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-dynamic-component>
