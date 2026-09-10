<x-super_admin_layout>
    <script>
        window.__REO_REVIEWERS = {!! json_encode($users->items()) !!};
    </script>
    <div x-data="{ 
        reviewersList: window.__REO_REVIEWERS || [],
        showAddModal: {{ $errors->any() ? 'true' : 'false' }}, 
        showViewModal: false, 
        showEditModal: false,
        selectedUser: null,
        showConfirmModal: false,
        confirmTitle: '',
        confirmMessage: '',
        confirmButtonText: '',
        confirmFormAction: '',
        confirmMethod: 'POST',
        confirmIsDelete: false,
        confirmFormId: null,
        filterAffiliation: '{{ request('status') }}',

        openViewModal(id) {
            this.selectedUser = this.reviewersList.find(u => u.id === id) || null;
            this.showViewModal = true;
        },

        openEditModal(id) {
            this.selectedUser = this.reviewersList.find(u => u.id === id) || null;
            this.showEditModal = true;
        },

        triggerConfirm(title, message, buttonText, action, isDelete = false, formId = null) {
            this.confirmTitle = title;
            this.confirmMessage = message;
            this.confirmButtonText = buttonText;
            this.confirmFormAction = action;
            this.confirmIsDelete = isDelete;
            this.confirmFormId = formId;
            this.showConfirmModal = true;
        }
    }" 
    @reviewers-updated.window="reviewersList = $event.detail.users"
    class="w-full max-w-7xl mx-auto space-y-8 min-w-0">
        
        <!-- Header & Executive Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">Manage Reviewers</h1>
                <p class="text-slate-500 mt-1.5 text-sm">Directory of ethics review board members and evaluators (SOP 03, SOP 07).</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button @click="showAddModal = true" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 focus:outline-none min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Reviewer
                </button>
            </div>
        </div>

        <!-- Executive Metrics Ledger -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Reviewers</span>
                <span id="metric-total-reviewers" class="text-2xl font-extrabold text-slate-900 font-heading tabular-nums mt-1 block">
                    {{ $totalReviewers ?? $users->total() }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Active Accounts</span>
                <span id="metric-active-reviewers" class="text-2xl font-extrabold text-emerald-600 font-heading tabular-nums mt-1 block">
                    {{ $activeCount ?? $users->where('is_verified', true)->count() }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Internal Reviewers</span>
                <span id="metric-internal-reviewers" class="text-2xl font-extrabold text-blue-700 font-heading tabular-nums mt-1 block">
                    {{ $internalCount ?? 0 }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">External Reviewers</span>
                <span id="metric-external-reviewers" class="text-2xl font-extrabold text-indigo-700 font-heading tabular-nums mt-1 block">
                    {{ $externalCount ?? 0 }}
                </span>
            </div>
        </div>

        <!-- Global Protocol Blindness / Researcher Identity Visibility Control -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-[#8B0000] border border-slate-200 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-extrabold text-slate-900 font-heading">Researcher Identity Visibility</h3>
                        <span class="text-[11px] font-bold uppercase tracking-wider {{ $globalVisibility ? 'text-emerald-600' : 'text-blue-700' }}">
                            ({{ $globalVisibility ? 'Open Review' : 'Blind Review Active' }})
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Control whether reviewers can see researcher identifying information during protocol evaluations.</p>
                </div>
            </div>

            <form id="globalVisibilityForm" action="{{ route('super_admin.reviewers.global_visibility') }}" method="POST"
                @submit.prevent="triggerConfirm(
                    'Update Global Visibility?', 
                    'This action will update the evaluation privacy policy for ALL reviewers in the system. Are you sure you want to proceed?', 
                    'Yes, Apply Policy', 
                    $el.action,
                    false,
                    'globalVisibilityForm'
                )"
                class="flex items-center gap-2 w-full md:w-auto justify-end">
                @csrf
                <div class="relative w-full sm:w-auto">
                    <select name="show_researcher_identity" 
                            aria-label="Researcher Identity Visibility Setting"
                            class="w-full sm:w-auto pl-3.5 pr-9 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[40px]">
                        <option value="0" @selected(!$globalVisibility)>Hide Identities (Blind Review)</option>
                        <option value="1" @selected($globalVisibility)>Show Identities (Open Review)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <button type="submit" 
                        class="shrink-0 px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 active:scale-[0.98] transition-all shadow-xs min-h-[40px]">
                    Apply Policy
                </button>
            </form>
        </div>

        <!-- Search & Filter Controls -->
        <form id="reviewers-filter-form" method="GET" action="{{ route('super_admin.manage_reviewers') }}" class="bg-white p-4 md:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row gap-4 items-stretch md:items-center">
            <!-- Search -->
            <div class="relative flex-grow min-w-0">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input name="search" 
                       value="{{ request('search') }}" 
                       type="text"
                       placeholder="Search by name, email..." 
                       aria-label="Search by name, email"
                       class="reviewer-filter-input w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]" />
            </div>
            
            <!-- Affiliation Filter -->
            <div class="relative w-full md:w-44">
                <select x-model="filterAffiliation" 
                        name="status" 
                        aria-label="Filter by Affiliation"
                        class="reviewer-filter-input w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
                    <option value="">All Affiliations</option>
                    <option value="internal" {{ request('status') == 'internal' ? 'selected' : '' }}>Internal (WMSU)</option>
                    <option value="external" {{ request('status') == 'external' ? 'selected' : '' }}>External</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- College Filter -->
            <div class="relative w-full md:w-52">
                <select :disabled="filterAffiliation === 'external'" 
                        :class="{ 'opacity-50 cursor-not-allowed bg-slate-100': filterAffiliation === 'external' }" 
                        name="college" 
                        aria-label="Filter by College"
                        class="reviewer-filter-input w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
                    <option value="">All Colleges</option>
                    @foreach($colleges as $college)
                        <option value="{{ $college->name }}" {{ request('college') == $college->name ? 'selected' : '' }}>{{ $college->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Account Status Filter -->
            <div class="relative w-full md:w-44">
                <select name="account_status" 
                        aria-label="Filter by Account Status"
                        class="reviewer-filter-input w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('account_status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="deactivated" {{ request('account_status') == 'deactivated' ? 'selected' : '' }}>Deactivated</option>
                    <option value="pending" {{ request('account_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" 
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 focus:outline-none min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Apply
                </button>

                <a id="reviewers-filter-reset" 
                   href="{{ route('super_admin.manage_reviewers') }}" 
                   title="Reset all filters"
                   class="{{ (request('search') || request('status') || request('college') || request('account_status')) ? '' : 'hidden ' }}inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors min-h-[44px]">
                    Reset
                </a>
            </div>
        </form>

        <!-- Reviewers Data Ledger -->
        <div id="reviewers-table-wrapper" class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
            @include('super_admin.partials.reviewers_table')
        </div>

        <!-- Reviewed Titles Modal (JS-driven, Frosted Scrim) -->
        <div id="reviewedTitlesModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
            <!-- Frosted Scrim Backdrop -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeReviewedModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80 flex flex-col max-h-[85vh]">
                    <!-- Header -->
                    <div class="bg-white px-6 py-5 border-b border-slate-200 flex items-center justify-between shrink-0">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading" id="reviewedModalReviewerName">Reviewer Protocols</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                <span id="reviewedModalCount" class="font-bold text-[#8B0000]">0</span>
                                <span id="reviewedModalLabel"> evaluated protocols</span>
                            </p>
                        </div>
                        <button onclick="closeReviewedModal()" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Search Filter inside modal -->
                    <div class="px-6 py-3 border-b border-slate-100 shrink-0 bg-slate-50/50">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" id="reviewedModalSearch"
                                oninput="filterReviewedTitles(this.value)"
                                placeholder="Filter reviewed protocol titles..."
                                aria-label="Filter reviewed protocol titles"
                                class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Title List -->
                    <div class="overflow-y-auto flex-1 px-6 py-4 divide-y divide-slate-100" id="reviewedTitlesList">
                        <!-- Populated by JS -->
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 shrink-0 flex justify-end bg-slate-50/50">
                        <button onclick="closeReviewedModal()" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-colors min-h-[44px]">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Reviewer Modal -->
        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Frosted Scrim Backdrop -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="showAddModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-98"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-98">
                    
                    <!-- Header -->
                    <div class="bg-white px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading">Add New Reviewer</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Register a new evaluator for the ethics review committee (SOP 03).</p>
                        </div>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <form action="{{ route('super_admin.reviewers.create') }}" method="POST" class="p-6 space-y-4">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs space-y-1">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">First Name</label>
                                <input type="text" name="first_name" required placeholder="John" value="{{ old('first_name') }}" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Middle Name</label>
                                <input type="text" name="middle_name" placeholder="Quincy" value="{{ old('middle_name') }}" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Last Name</label>
                                <input type="text" name="last_name" required placeholder="Doe" value="{{ old('last_name') }}" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" required placeholder="reviewer@example.com" value="{{ old('email') }}" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">College Unit (Optional)</label>
                            <div class="relative">
                                <select name="college" class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
                                    <option value="">Select College (Optional)</option>
                                    @foreach($colleges as $college)
                                        <option value="{{ $college->name }}" @selected(old('college') == $college->name)>{{ $college->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Expertise / Specialization</label>
                            <input type="text" name="expertise" placeholder="e.g. Clinical Trials, Public Health, Bioethics" value="{{ old('expertise') }}" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                            <p class="text-[11px] text-slate-500 mt-1">Comma-separated list of research disciplines or ethics review qualifications.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="training_completed" value="1" @checked(old('training_completed'))
                                       class="w-4 h-4 text-[#8B0000] rounded border-slate-300 focus:ring-[#8B0000]">
                                <span class="text-xs font-bold text-slate-800">Ethics Training Completed</span>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="external_user" value="1" @checked(old('external_user'))
                                       class="w-4 h-4 text-[#8B0000] rounded border-slate-300 focus:ring-[#8B0000]">
                                <span class="text-xs font-bold text-slate-800">External Evaluator</span>
                            </label>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200">
                            <button type="button" @click="showAddModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" class="px-6 py-2.5 bg-[#8B0000] text-white text-sm font-bold rounded-xl shadow-xs hover:bg-[#6d0000] active:scale-[0.98] transition-all min-h-[44px]">
                                Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Reviewer Details Modal -->
        <div x-show="showViewModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="showViewModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-98"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-98">
                    
                    <!-- Header -->
                    <div class="bg-white px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading">Reviewer Details</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Evaluation credentials, disciplines, and review board status.</p>
                        </div>
                        <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-6" x-show="selectedUser">
                        <!-- Profile Card -->
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                            <div class="w-14 h-14 rounded-full bg-white border border-slate-200 shadow-xs flex items-center justify-center text-xl font-extrabold text-[#8B0000] font-heading shrink-0">
                                <span x-text="(selectedUser?.first_name?.charAt(0) || '') + (selectedUser?.last_name?.charAt(0) || '')"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-base font-extrabold text-slate-900 font-heading truncate" 
                                    x-text="selectedUser ? (selectedUser.first_name + (selectedUser.middle_name ? ' ' + selectedUser.middle_name : '') + ' ' + selectedUser.last_name) : ''"></h4>
                                <p class="text-xs text-slate-500 truncate mt-0.5" x-text="selectedUser?.email"></p>
                                
                                <div class="mt-2 flex items-center gap-3">
                                    <span class="text-xs font-bold uppercase tracking-wider"
                                          :class="selectedUser?.is_verified ? 'text-emerald-600' : (selectedUser?.email_verified_at ? 'text-rose-600' : 'text-amber-600')"
                                          x-text="selectedUser?.is_verified ? 'ACTIVE' : (selectedUser?.email_verified_at ? 'DEACTIVATED' : 'PENDING')">
                                    </span>
                                    <span class="text-slate-300">|</span>
                                    <span class="text-xs font-bold uppercase tracking-wider"
                                          :class="selectedUser?.reviewer?.external_user ? 'text-indigo-700' : 'text-blue-700'"
                                          x-text="selectedUser?.reviewer?.external_user ? 'EXTERNAL' : 'INTERNAL'">
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1 col-span-2">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">College / Institutional Unit</span>
                                <div class="text-sm font-semibold text-slate-900 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                                    <span x-text="selectedUser?.reviewer?.college || 'WMSU Review Board'"></span>
                                </div>
                            </div>

                            <div class="space-y-1 col-span-2">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Areas of Expertise</span>
                                <div class="text-sm font-semibold text-slate-900 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                                    <span x-text="(selectedUser?.reviewer?.expertise && selectedUser.reviewer.expertise.length) ? selectedUser.reviewer.expertise.join(', ') : 'None specified'"></span>
                                </div>
                            </div>

                            <div class="space-y-1 col-span-2 sm:col-span-1">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Training Status</span>
                                <div class="text-sm font-semibold bg-slate-50 p-3 rounded-xl border border-slate-200/80"
                                     :class="selectedUser?.reviewer?.training_completed ? 'text-emerald-700' : 'text-slate-600'">
                                    <span x-text="selectedUser?.reviewer?.training_completed ? 'Training Completed' : 'Pending Training'"></span>
                                </div>
                            </div>

                            <div class="space-y-1 col-span-2 sm:col-span-1">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Protocol Blindness</span>
                                <div class="text-sm font-semibold text-slate-900 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                                    <span x-text="selectedUser?.reviewer?.show_researcher_identity ? 'Open Evaluation' : 'Blind Evaluation'"></span>
                                </div>
                            </div>

                            <div class="space-y-1 col-span-2 sm:col-span-1">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Joined Date</span>
                                <div class="text-sm font-semibold text-slate-900 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                                    <span x-text="selectedUser?.created_at ? new Date(selectedUser.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) : '—'"></span>
                                </div>
                            </div>

                            <div class="space-y-1 col-span-2 sm:col-span-1">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Affiliation Type</span>
                                <div class="text-sm font-semibold text-slate-900 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                                    <span x-text="selectedUser?.reviewer?.external_user ? 'External Evaluator' : 'Internal Faculty'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Footer -->
                        <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                            <button @click="showViewModal = false; openEditModal(selectedUser.id)" 
                                    class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors min-h-[44px] inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Profile
                            </button>
                            <button @click="showViewModal = false" 
                                    class="px-6 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-colors min-h-[44px]">
                                Close Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="showEditModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-98"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-98">
                    
                    <!-- Header -->
                    <div class="bg-white px-6 py-5 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading">Edit Reviewer Profile</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Update credentials and individual protocol visibility.</p>
                        </div>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6" x-show="selectedUser">
                        <form :action="'/super-admin/reviewers/' + selectedUser?.id + '/update'" method="POST" class="space-y-4">
                            @csrf
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">First Name</label>
                                    <input type="text" name="first_name" :value="selectedUser?.first_name" required 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Middle Name</label>
                                    <input type="text" name="middle_name" :value="selectedUser?.middle_name" 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Last Name</label>
                                    <input type="text" name="last_name" :value="selectedUser?.last_name" required 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                                <input type="email" name="email" :value="selectedUser?.email" required 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                            </div>

                            <div class="pt-2">
                                <label class="flex items-start gap-3.5 cursor-pointer p-4 bg-slate-50 border border-slate-200/80 rounded-xl hover:bg-slate-100/70 transition-colors">
                                    <input type="checkbox" name="show_researcher_identity" value="1" 
                                           :checked="selectedUser?.reviewer?.show_researcher_identity"
                                           class="w-4 h-4 text-[#8B0000] rounded border-slate-300 focus:ring-[#8B0000] mt-0.5">
                                    <div>
                                        <span class="text-sm font-bold text-slate-900 block">Show Researcher Identity</span>
                                        <span class="text-xs text-slate-500 font-normal block mt-0.5">Allow this reviewer to inspect researcher names and credentials during protocol reviews.</span>
                                    </div>
                                </label>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200">
                                <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors min-h-[44px]">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-[#8B0000] text-white text-sm font-bold rounded-xl shadow-xs hover:bg-[#6d0000] active:scale-[0.98] transition-all min-h-[44px]">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div x-show="showConfirmModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="showConfirmModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-98"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-98">
                    
                    <div class="bg-white p-6 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full" 
                             :class="confirmIsDelete ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600'">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="confirmIsDelete" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                <path x-show="!confirmIsDelete" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading" x-text="confirmTitle"></h3>
                            <p class="mt-2 text-xs text-slate-500 leading-relaxed" x-text="confirmMessage"></p>
                        </div>
                    </div>

                    <div class="bg-slate-50/80 px-6 py-4 border-t border-slate-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" @click="showConfirmModal = false" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors min-h-[44px]">
                            Cancel
                        </button>
                        
                        <div class="w-full sm:w-auto">
                            <!-- Direct Route Form -->
                            <form :action="confirmFormAction" method="POST" x-show="!confirmFormId">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE" :disabled="!confirmIsDelete">
                                <button type="submit" 
                                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white rounded-xl shadow-xs transition-all active:scale-[0.98] min-h-[44px]"
                                        :class="confirmIsDelete ? 'bg-rose-600 hover:bg-rose-700' : 'bg-[#8B0000] hover:bg-[#6d0000]'"
                                        x-text="confirmButtonText">
                                </button>
                            </form>

                            <!-- External Form Trigger (e.g. Global Visibility) -->
                            <button x-show="confirmFormId" 
                                    @click="document.getElementById(confirmFormId).submit()"
                                    type="button"
                                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-xs transition-all active:scale-[0.98] min-h-[44px]"
                                    x-text="confirmButtonText">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-super_admin_layout>

<script>
    let _allReviewedTitles = [];
    const _viewFilesBase = '{{ url('admin/view_files') }}';

    function openReviewedModal(reviewerName, count, titlesData) {
        _allReviewedTitles = titlesData;

        document.getElementById('reviewedModalReviewerName').textContent = reviewerName;
        document.getElementById('reviewedModalCount').textContent = count;
        document.getElementById('reviewedModalLabel').textContent = count === 1 ? ' evaluated protocol' : ' evaluated protocols';
        document.getElementById('reviewedModalSearch').value = '';

        renderReviewedTitles(titlesData);

        const modal = document.getElementById('reviewedTitlesModal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeReviewedModal() {
        document.getElementById('reviewedTitlesModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function filterReviewedTitles(query) {
        const q = query.trim().toLowerCase();
        const filtered = q === ''
            ? _allReviewedTitles
            : _allReviewedTitles.filter(item => item.title.toLowerCase().includes(q));
        renderReviewedTitles(filtered, query.trim());
    }

    function renderReviewedTitles(items, highlight = '') {
        const container = document.getElementById('reviewedTitlesList');
        if (!items || items.length === 0) {
            container.innerHTML = `
                <div class="py-10 text-center text-slate-400">
                    <p class="text-sm font-medium">No protocols match your search query.</p>
                </div>`;
            return;
        }

        container.innerHTML = items.map((item, i) => {
            let displayTitle = escapeHtml(item.title);
            if (highlight) {
                const regex = new RegExp(`(${escapeRegex(highlight)})`, 'gi');
                displayTitle = displayTitle.replace(regex, '<mark class="bg-amber-100 text-amber-900 rounded px-0.5">$1</mark>');
            }
            const viewUrl = `${_viewFilesBase}/${item.id}`;
            return `
                <div class="flex items-center gap-3 py-3 ${i !== 0 ? 'border-t border-slate-100' : ''}">
                    <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                        <span class="text-[10px] font-extrabold text-[#8B0000]">${i + 1}</span>
                    </div>
                    <p class="text-sm text-slate-800 font-medium leading-snug flex-1">${displayTitle}</p>
                    <a href="${viewUrl}"
                       class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-[#8B0000] bg-slate-50 border border-slate-200 hover:bg-red-50 hover:border-red-200 transition-colors whitespace-nowrap min-h-[32px]"
                       title="View protocol review dossier">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        View Details
                    </a>
                </div>`;
        }).join('');
    }

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function escapeRegex(str) {
        return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeReviewedModal();
    });

    // AJAX Pagination and Filter Handler for Manage Reviewers
    document.addEventListener('DOMContentLoaded', function() {
        const tableWrapper = document.getElementById('reviewers-table-wrapper');
        const form = document.getElementById('reviewers-filter-form');

        function fetchReviewers(targetUrl, pushState = true) {
            if (!tableWrapper) return;
            
            tableWrapper.style.opacity = '0.5';
            tableWrapper.style.pointerEvents = 'none';

            let url;
            if (targetUrl instanceof URLSearchParams) {
                url = new URL('{{ route('super_admin.manage_reviewers') }}', window.location.origin);
                targetUrl.forEach((value, key) => {
                    if (value !== '') {
                        url.searchParams.set(key, value);
                    }
                });
            } else if (typeof targetUrl === 'string') {
                url = new URL(targetUrl, window.location.origin);
            } else {
                url = new URL(window.location.href);
            }

            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.html) {
                    tableWrapper.innerHTML = data.html;
                    if (window.Alpine) {
                        window.Alpine.initTree(tableWrapper);
                    }
                }

                if (data.usersData) {
                    window.dispatchEvent(new CustomEvent('reviewers-updated', {
                        detail: { users: data.usersData }
                    }));
                }

                if (data.totalReviewers !== undefined) {
                    const el = document.getElementById('metric-total-reviewers');
                    if (el) el.textContent = data.totalReviewers;
                }
                if (data.activeCount !== undefined) {
                    const el = document.getElementById('metric-active-reviewers');
                    if (el) el.textContent = data.activeCount;
                }
                if (data.internalCount !== undefined) {
                    const el = document.getElementById('metric-internal-reviewers');
                    if (el) el.textContent = data.internalCount;
                }
                if (data.externalCount !== undefined) {
                    const el = document.getElementById('metric-external-reviewers');
                    if (el) el.textContent = data.externalCount;
                }

                if (pushState && url.toString() !== window.location.href) {
                    window.history.pushState({}, '', url.toString());
                }

                updateResetButton(url.toString());
            })
            .catch(error => {
                console.error('AJAX Reviewers Pagination error:', error);
                window.location.href = url.toString();
            })
            .finally(() => {
                tableWrapper.style.opacity = '1';
                tableWrapper.style.pointerEvents = 'auto';
            });
        }

        function updateResetButton(urlStr) {
            try {
                const parsed = new URL(urlStr, window.location.origin);
                const hasFilters = parsed.searchParams.get('search') || 
                                   parsed.searchParams.get('status') || 
                                   parsed.searchParams.get('college') ||
                                   parsed.searchParams.get('account_status');
                const resetBtn = document.getElementById('reviewers-filter-reset');
                if (resetBtn) {
                    if (hasFilters) {
                        resetBtn.classList.remove('hidden');
                    } else {
                        resetBtn.classList.add('hidden');
                    }
                }
            } catch(e) {}
        }

        // Form Submit Interception
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                fetchReviewers(new URLSearchParams(formData));
            });
        }

        // Filter Inputs Auto-Submit on Change
        document.querySelectorAll('.reviewer-filter-input').forEach(function(input) {
            if (input.tagName === 'SELECT') {
                input.addEventListener('change', function() {
                    if (form) {
                        const formData = new FormData(form);
                        fetchReviewers(new URLSearchParams(formData));
                    }
                });
            }
        });

        // Reset Button Delegation
        const resetBtn = document.getElementById('reviewers-filter-reset');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (form) form.reset();
                fetchReviewers('{{ route('super_admin.manage_reviewers') }}');
            });
        }

        // Pagination Link Delegation
        document.addEventListener('click', function(e) {
            const link = e.target.closest('#reviewers-table-wrapper nav a') || e.target.closest('.filter-pagination a');
            if (link && link.href) {
                e.preventDefault();
                fetchReviewers(link.href);
            }
        });

        // Browser Back/Forward navigation
        window.addEventListener('popstate', function() {
            fetchReviewers(window.location.href, false);
        });
    });
</script>
