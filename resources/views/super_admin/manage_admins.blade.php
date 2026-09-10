<x-super_admin_layout>
    <script>
        window.__REO_ADMINS = {!! json_encode($users->items()) !!};
    </script>
    <div x-data="{ 
        adminsList: window.__REO_ADMINS || [],
        showAddModal: false, 
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
        filterAffiliation: '{{ request('status') }}',

        openViewModal(id) {
            this.selectedUser = this.adminsList.find(u => u.id === id) || null;
            this.showViewModal = true;
        },

        openEditModal(id) {
            this.selectedUser = this.adminsList.find(u => u.id === id) || null;
            this.showEditModal = true;
        },

        triggerConfirm(title, message, buttonText, action, isDelete = false) {
            this.confirmTitle = title;
            this.confirmMessage = message;
            this.confirmButtonText = buttonText;
            this.confirmFormAction = action;
            this.confirmIsDelete = isDelete;
            this.showConfirmModal = true;
        }
    }" 
    @admins-updated.window="adminsList = $event.detail.users"
    class="w-full max-w-7xl mx-auto space-y-8 min-w-0">
        
        <!-- Header & Executive Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">Manage Admins</h1>
                <p class="text-slate-500 mt-1.5 text-sm">Directory of system administrators and secretariat oversight (SOP 01, SOP 02).</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button @click="showAddModal = true" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 focus:outline-none min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Admin
                </button>
            </div>
        </div>

        <!-- Executive Metrics Ledger -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Admins</span>
                <span class="text-2xl font-extrabold text-slate-900 font-heading tabular-nums mt-1 block">
                    {{ $totalAdmins ?? $users->total() }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Active Accounts</span>
                <span class="text-2xl font-extrabold text-emerald-600 font-heading tabular-nums mt-1 block">
                    {{ $activeCount ?? $users->where('is_verified', true)->count() }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Internal Admins</span>
                <span class="text-2xl font-extrabold text-blue-700 font-heading tabular-nums mt-1 block">
                    {{ $internalCount ?? 0 }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">External Admins</span>
                <span class="text-2xl font-extrabold text-indigo-700 font-heading tabular-nums mt-1 block">
                    {{ $externalCount ?? 0 }}
                </span>
            </div>
        </div>

        <!-- Search & Filter Controls -->
        <form id="admins-filter-form" method="GET" action="{{ route('super_admin.manage_admins') }}" class="bg-white p-4 md:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row gap-4 items-stretch md:items-center">
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
                       class="admin-filter-input w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]" />
            </div>
            
            <!-- Affiliation Filter -->
            <div class="relative w-full md:w-44">
                <select x-model="filterAffiliation" 
                        name="status" 
                        aria-label="Filter by Affiliation"
                        class="admin-filter-input w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
                    <option value="">All Affiliations</option>
                    <option value="internal" {{ request('status') == 'internal' ? 'selected' : '' }}>Internal</option>
                    <option value="external" {{ request('status') == 'external' ? 'selected' : '' }}>External</option>
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
                        class="admin-filter-input w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
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

            <!-- College Filter -->
            <div class="relative w-full md:w-52">
                <select :disabled="filterAffiliation === 'external'" 
                        :class="{ 'opacity-50 cursor-not-allowed bg-slate-100': filterAffiliation === 'external' }" 
                        name="college" 
                        aria-label="Filter by College"
                        class="admin-filter-input w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
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

            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" 
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 focus:outline-none min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Apply
                </button>

                <a id="admins-filter-reset" 
                   href="{{ route('super_admin.manage_admins') }}" 
                   title="Reset all filters"
                   class="{{ (request('search') || request('status') || request('college') || request('account_status')) ? '' : 'hidden ' }}inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors min-h-[44px]">
                    Reset
                </a>
            </div>
        </form>

        <!-- Admins Data Ledger -->
        <div id="admins-table-wrapper" class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
            @include('super_admin.partials.admins_table')
        </div>

        <!-- Add Admin Modal -->
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
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading">Add New Admin</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Create a new account for a system administrator (SOP 01).</p>
                        </div>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <form action="{{ route('super_admin.admins.create') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">First Name</label>
                                <input type="text" name="first_name" required placeholder="John" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Middle Name</label>
                                <input type="text" name="middle_name" placeholder="Quincy" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Last Name</label>
                                <input type="text" name="last_name" required placeholder="Doe" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                                <input type="email" name="email" required placeholder="admin@example.com" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Affiliation</label>
                                <div class="relative">
                                    <select name="affiliation" required class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
                                        <option value="internal">Internal</option>
                                        <option value="external">External</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Label (Role / Designation)</label>
                            <input type="text" name="label" placeholder="e.g. Clerk, Secretariat, Protocol Officer" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
                            <p class="text-[11px] text-slate-500 mt-1">Optional descriptive designation for this admin within the ethics secretariat.</p>
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

        <!-- View Admin Details Modal -->
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
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading">Admin Details</h3>
                            <p class="text-xs text-slate-500 mt-0.5">View administrative credentials and role assignment.</p>
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
                                          :class="selectedUser?.admin?.external_user ? 'text-indigo-700' : 'text-blue-700'"
                                          x-text="selectedUser?.admin?.external_user ? 'EXTERNAL' : 'INTERNAL'">
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1 col-span-2 sm:col-span-1">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Role Designation</span>
                                <div class="text-sm font-semibold text-slate-900 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                                    <span x-text="selectedUser?.admin?.label || 'General Secretariat'"></span>
                                </div>
                            </div>
                            <div class="space-y-1 col-span-2 sm:col-span-1">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">System Role</span>
                                <div class="text-sm font-semibold text-slate-900 bg-slate-50 p-3 rounded-xl border border-slate-200/80 capitalize">
                                    <span x-text="selectedUser?.role || 'Admin'"></span>
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
                                    <span x-text="selectedUser?.admin?.external_user ? 'External Affiliate' : 'Internal WMSU'"></span>
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
                            <h3 class="text-lg font-extrabold text-slate-900 font-heading">Edit Admin Profile</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Update administrator credentials and role label.</p>
                        </div>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6" x-show="selectedUser">
                        <form :action="'/super-admin/admins/' + selectedUser?.id + '/update'" method="POST" class="space-y-4">
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

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                                    <input type="email" name="email" :value="selectedUser?.email" required 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Affiliation</label>
                                    <div class="relative">
                                        <select name="affiliation" :value="selectedUser?.admin?.external_user ? 'external' : 'internal'" required 
                                                class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
                                            <option value="internal">Internal</option>
                                            <option value="external">External</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Label (Role / Designation)</label>
                                <input type="text" name="label" :value="selectedUser?.admin?.label" placeholder="e.g. Clerk, Secretariat, Protocol Officer" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 min-h-[44px]">
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
                        <form :action="confirmFormAction" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE" :disabled="!confirmIsDelete">
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white rounded-xl shadow-xs transition-all active:scale-[0.98] min-h-[44px]"
                                    :class="confirmIsDelete ? 'bg-rose-600 hover:bg-rose-700' : 'bg-[#8B0000] hover:bg-[#6d0000]'"
                                    x-text="confirmButtonText">
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableWrapper = document.getElementById('admins-table-wrapper');
            const form = document.getElementById('admins-filter-form');

            function fetchAdmins(targetUrl, pushState = true) {
                if (!tableWrapper) return;
                
                tableWrapper.style.opacity = '0.5';
                tableWrapper.style.pointerEvents = 'none';

                let url;
                if (targetUrl instanceof URLSearchParams) {
                    url = new URL('{{ route('super_admin.manage_admins') }}', window.location.origin);
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
                        window.dispatchEvent(new CustomEvent('admins-updated', {
                            detail: { users: data.usersData }
                        }));
                    }

                    if (pushState && url.toString() !== window.location.href) {
                        window.history.pushState({}, '', url.toString());
                    }

                    updateResetButton(url.toString());
                })
                .catch(error => {
                    console.error('AJAX Pagination error:', error);
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
                    const resetBtn = document.getElementById('admins-filter-reset');
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
                    fetchAdmins(new URLSearchParams(formData));
                });
            }

            // Filter Inputs Auto-Submit on Change
            document.querySelectorAll('.admin-filter-input').forEach(function(input) {
                if (input.tagName === 'SELECT') {
                    input.addEventListener('change', function() {
                        if (form) {
                            const formData = new FormData(form);
                            fetchAdmins(new URLSearchParams(formData));
                        }
                    });
                }
            });

            // Reset Button Delegation
            const resetBtn = document.getElementById('admins-filter-reset');
            if (resetBtn) {
                resetBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (form) form.reset();
                    fetchAdmins('{{ route('super_admin.manage_admins') }}');
                });
            }

            // Pagination Link Delegation
            document.addEventListener('click', function(e) {
                const link = e.target.closest('#admins-table-wrapper nav a') || e.target.closest('.filter-pagination a');
                if (link && link.href) {
                    e.preventDefault();
                    fetchAdmins(link.href);
                }
            });

            // Browser Back/Forward navigation
            window.addEventListener('popstate', function() {
                fetchAdmins(window.location.href, false);
            });
        });
    </script>
</x-super_admin_layout>