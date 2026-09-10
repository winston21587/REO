<x-admin_layout>
    <script>
        window.__REO_RESEARCHERS = {!! json_encode($users->items()) !!};
    </script>
    <div x-data="{ 
        usersList: window.__REO_RESEARCHERS || [],
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
        titleSearch: '',
        filterAffiliation: '{{ request('status') }}',

        openViewModal(id) {
            this.selectedUser = this.usersList.find(u => u.id === id) || null;
            this.showViewModal = true;
        },

        openEditModal(id) {
            this.selectedUser = this.usersList.find(u => u.id === id) || null;
            this.showEditModal = true;
        },

        triggerConfirm(title, message, buttonText, action, isDelete = false) {
            this.confirmTitle = title;
            this.confirmMessage = message;
            this.confirmButtonText = buttonText;
            this.confirmFormAction = action;
            this.confirmIsDelete = isDelete;
            this.showConfirmModal = true;
        },

        get filteredTitles() {
            if (!this.selectedUser || !this.selectedUser.researcher || !this.selectedUser.researcher.research_titles) return [];
            if (!this.titleSearch) return this.selectedUser.researcher.research_titles;
            const search = this.titleSearch.toLowerCase();
            return this.selectedUser.researcher.research_titles.filter(t => 
                (t.Study_Protocol_title && t.Study_Protocol_title.toLowerCase().includes(search)) || 
                (t.Status && t.Status.toLowerCase().includes(search))
            );
        }
    }" class="w-full max-w-7xl mx-auto space-y-8 min-w-0">
        
        <!-- Header & Executive Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">Researchers</h1>
                <p class="text-slate-500 mt-1.5 text-sm">Directory of registered faculty, staff, and student researchers (SOP 17, 18, 19).</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button @click="showAddModal = true" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 focus:outline-none min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Researcher
                </button>
            </div>
        </div>

        <!-- Executive Metrics Ledger -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Researchers</span>
                <span class="text-2xl font-extrabold text-slate-900 font-heading tabular-nums mt-1 block">
                    {{ $totalResearchers ?? $users->total() }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Active Accounts</span>
                <span class="text-2xl font-extrabold text-emerald-600 font-heading tabular-nums mt-1 block">
                    {{ $activeCount ?? $users->where('is_verified', true)->count() }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Internal Faculty</span>
                <span class="text-2xl font-extrabold text-blue-700 font-heading tabular-nums mt-1 block">
                    {{ $internalCount ?? 0 }}
                </span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">External Investigators</span>
                <span class="text-2xl font-extrabold text-indigo-700 font-heading tabular-nums mt-1 block">
                    {{ $externalCount ?? 0 }}
                </span>
            </div>
        </div>

        <!-- Search & Filter Controls -->
        <form method="GET" action="{{ route('admin.manage_users') }}" class="bg-white p-4 md:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row gap-4 items-stretch md:items-center">
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
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm md:text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]" />
            </div>
            
            <!-- Affiliation Filter -->
            <div class="relative w-full md:w-44">
                <select x-model="filterAffiliation" 
                        name="status" 
                        aria-label="Filter by Affiliation"
                        class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
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
                        class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
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
                        class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer min-h-[44px]">
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

                @if(request('search') || request('status') || request('college') || request('account_status'))
                <a href="{{ route('admin.manage_users') }}" 
                   title="Reset all filters"
                   class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors min-h-[44px]">
                    Reset
                </a>
                @endif
            </div>
        </form>

        <!-- Researchers Data Ledger -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[760px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="py-4 px-6">Researcher / Role</th>
                            <th class="py-4 px-6">Contact Email</th>
                            <th class="py-4 px-6">Affiliation & College</th>
                            <th class="py-4 px-6">Account Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Name / Role -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-[#8B0000] border border-slate-200 flex items-center justify-center font-bold text-sm shrink-0 font-heading">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 text-sm truncate">
                                            {{ $user->first_name }} {{ $user->middle_name ? $user->middle_name . ' ' : '' }}{{ $user->last_name }}
                                        </p>
                                        <p class="text-xs text-slate-500 truncate mt-0.5">
                                            {{ $user->researcher?->college ?? ($user->researcher?->external_user ? ($user->researcher?->institute ?? 'External Investigator') : 'Researcher') }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Email -->
                            <td class="py-4 px-6">
                                <a href="mailto:{{ $user->email }}" class="inline-flex items-center gap-2 text-sm text-slate-700 hover:text-[#8B0000] transition-colors group">
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-[#8B0000] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="truncate">{{ $user->email }}</span>
                                </a>
                            </td>

                            <!-- Affiliation & College (High-Contrast Semantic Typography - NO pills, NO dots) -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-0.5">
                                    @if(!$user->researcher?->external_user)
                                        <span class="text-xs font-bold uppercase tracking-wider text-blue-700">Internal</span>
                                        <span class="text-xs text-slate-500 truncate max-w-[220px]" title="{{ $user->researcher?->college ?? 'WMSU Faculty/Staff' }}">
                                            {{ $user->researcher?->college ?? 'WMSU Faculty/Staff' }}
                                        </span>
                                    @else
                                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-700">External</span>
                                        <span class="text-xs text-slate-500 truncate max-w-[220px]" title="{{ $user->researcher?->institute ?? 'External Institution' }}">
                                            {{ $user->researcher?->institute ?? 'External Institution' }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status (High-Contrast Semantic Typography - NO pills, NO dots) -->
                            <td class="py-4 px-6">
                                @if($user->is_verified)
                                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Active</span>
                                @elseif($user->email_verified_at)
                                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600">Deactivated</span>
                                @else
                                    <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Pending</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" 
                                            @click.away="open = false" 
                                            aria-label="Actions for {{ $user->first_name }}"
                                            class="w-9 h-9 inline-flex items-center justify-center text-slate-500 hover:text-[#8B0000] hover:bg-slate-100 rounded-xl transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 z-20 mt-1 w-52 origin-top-right rounded-xl bg-white shadow-xl border border-slate-200/80 focus:outline-none overflow-hidden py-1.5" 
                                         style="display: none;">
                                        
                                        <!-- View Details Trigger -->
                                        <button @click="openViewModal({{ $user->id }}); open = false;" 
                                                class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] flex items-center gap-2.5 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Dossier
                                        </button>

                                        <!-- Edit Profile Trigger -->
                                        <button @click="openEditModal({{ $user->id }}); open = false;" 
                                                class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] flex items-center gap-2.5 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit Profile
                                        </button>
                                        
                                        <!-- Toggle Status Trigger -->
                                        <button type="button" 
                                                @click="open = false; triggerConfirm(
                                                    '{{ $user->is_verified ? 'Deactivate Account' : 'Activate Account' }}', 
                                                    'Are you sure you want to {{ $user->is_verified ? 'deactivate' : 'activate' }} the account for {{ $user->first_name }} {{ $user->last_name }}?', 
                                                    '{{ $user->is_verified ? 'Deactivate Account' : 'Activate Account' }}', 
                                                    '{{ route('admin.users.toggle_status', $user->id) }}',
                                                    false
                                                )" 
                                                class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] flex items-center gap-2.5 transition-colors cursor-pointer">
                                            @if($user->is_verified)
                                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                                Deactivate Account
                                            @else
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Activate Account
                                            @endif
                                        </button>

                                        <div class="border-t border-slate-100 my-1"></div>

                                        <!-- Delete Researcher Trigger -->
                                        <button type="button" 
                                                @click="open = false; triggerConfirm(
                                                    'Delete Researcher Record', 
                                                    'Are you sure you want to delete {{ $user->first_name }} {{ $user->last_name }}? This action is permanent and removes all associated investigator records.', 
                                                    'Confirm Deletion', 
                                                    '{{ route('admin.users.delete', $user->id) }}',
                                                    true
                                                )" 
                                                class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 flex items-center gap-2.5 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete User
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800">No Researchers Found</h3>
                                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">No investigator records match your search or filter criteria.</p>
                                @if(request('search') || request('status') || request('college') || request('account_status'))
                                <div class="mt-4">
                                    <a href="{{ route('admin.manage_users') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#8B0000] hover:underline uppercase tracking-wider">
                                        Clear All Filters
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                {{ $users->links() }}
            </div>
            @endif
        </div>

        <!-- Add Researcher Modal (Frosted Scrim Rule) -->
        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

            <!-- Modal Window -->
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    
                    <!-- Header -->
                    <div class="bg-white px-6 py-5 border-b border-slate-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 text-[#8B0000] flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 font-heading">Add New Researcher</h3>
                                <p class="text-xs text-slate-500">Register faculty or external investigator credentials.</p>
                            </div>
                        </div>
                        <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6">
                        <form action="{{ route('admin.users.create') }}" method="POST" class="space-y-4" x-data="{ affiliation: 'internal' }">
                            @csrf
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">First Name</label>
                                    <input type="text" name="first_name" required placeholder="John" 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-400">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Middle Name <span class="text-slate-400 font-normal normal-case">(Optional)</span></label>
                                    <input type="text" name="middle_name" placeholder="Doe" 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-400">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Last Name</label>
                                <input type="text" name="last_name" required placeholder="Smith" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-400">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                                <input type="email" name="email" required placeholder="researcher@wmsu.edu.ph" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-400">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Affiliation Classification</label>
                                <select name="affiliation" x-model="affiliation" required 
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all cursor-pointer text-slate-800">
                                    <option value="internal">Internal (WMSU Faculty/Staff/Student)</option>
                                    <option value="external">External Investigator</option>
                                </select>
                            </div>

                            <div class="space-y-1.5" x-show="affiliation === 'internal'">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">College Department</label>
                                <select name="college" :required="affiliation === 'internal'" 
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all cursor-pointer text-slate-800">
                                    <option value="" disabled selected>Select College</option>
                                    @foreach($colleges as $college)
                                        <option value="{{ $college->name }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                                    <input type="password" name="password" required placeholder="••••••••" 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-400">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required placeholder="••••••••" 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-400">
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200/80 mt-6">
                                <button type="button" @click="showAddModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer min-h-[44px]">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-[#8B0000] hover:bg-[#6d0000] text-white text-sm font-bold rounded-xl shadow-xs hover:shadow-sm active:scale-[0.98] transition-all cursor-pointer min-h-[44px]">
                                    Create Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Researcher Dossier Modal -->
        <div x-show="showViewModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showViewModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    
                    <!-- Header -->
                    <div class="bg-white px-6 py-5 border-b border-slate-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5 text-[#8B0000]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 font-heading">Researcher Dossier</h3>
                                <p class="text-xs text-slate-500">Investigator profile and ethics protocol ledger.</p>
                            </div>
                        </div>
                        <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6 space-y-6" x-show="selectedUser">
                        <!-- Profile Card -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                            <div class="w-14 h-14 rounded-full bg-white border border-slate-200 flex items-center justify-center text-xl font-bold text-[#8B0000] font-heading shrink-0">
                                <span x-text="(selectedUser?.first_name ? selectedUser.first_name.charAt(0) : '') + (selectedUser?.last_name ? selectedUser.last_name.charAt(0) : '')"></span>
                            </div>
                            <div class="min-w-0 flex-grow">
                                <h4 class="text-base font-bold text-slate-900 truncate font-heading" 
                                    x-text="selectedUser ? (selectedUser.first_name + (selectedUser.middle_name ? ' ' + selectedUser.middle_name : '') + ' ' + selectedUser.last_name) : ''"></h4>
                                <p class="text-sm text-slate-600 truncate mt-0.5" x-text="selectedUser ? selectedUser.email : ''"></p>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="text-xs font-bold uppercase tracking-wider" 
                                          :class="selectedUser?.is_verified ? 'text-emerald-600' : 'text-amber-600'"
                                          x-text="selectedUser?.is_verified ? 'Active' : (selectedUser?.email_verified_at ? 'Deactivated' : 'Pending')"></span>
                                    <span class="text-slate-300">·</span>
                                    <span class="text-xs text-slate-500" x-text="selectedUser ? ('Joined ' + new Date(selectedUser.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })) : ''"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Institutional Data -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="font-bold text-slate-500 uppercase tracking-wider block">Affiliation Type</span>
                                <span class="text-sm font-bold text-slate-900 mt-1 block"
                                      x-text="selectedUser?.researcher?.external_user ? 'External Investigator' : 'Internal (WMSU)'"></span>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="font-bold text-slate-500 uppercase tracking-wider block"
                                      x-text="selectedUser?.researcher?.external_user ? 'Institution / Agency' : 'College Unit'"></span>
                                <span class="text-sm font-bold text-slate-900 mt-1 block truncate"
                                      x-text="selectedUser?.researcher?.external_user ? (selectedUser?.researcher?.institute || 'Not specified') : (selectedUser?.researcher?.college || 'Not assigned')"></span>
                            </div>
                        </div>

                        <!-- Submitted Titles Section -->
                        <div class="space-y-3 pt-4 border-t border-slate-200/80">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                    <span>Submitted Study Protocols</span>
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-mono text-[11px]" 
                                          x-text="selectedUser?.researcher?.research_titles?.length || 0"></span>
                                </h4>
                            </div>

                            <!-- Search Filter -->
                            <div class="relative">
                                <input type="text" 
                                       x-model="titleSearch" 
                                       placeholder="Filter protocols by title or status..." 
                                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Titles List -->
                            <div class="max-h-[260px] overflow-y-auto space-y-2 pr-1">
                                <template x-for="title in filteredTitles" :key="title.id">
                                    <div class="p-3 rounded-xl border border-slate-200/80 bg-white hover:border-[#8B0000]/40 transition-all shadow-xs">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex justify-between items-start gap-2">
                                                <h5 class="text-xs font-bold text-slate-900 leading-snug" x-text="title.Study_Protocol_title"></h5>
                                                <!-- Semantic status typography (no background pills, no dots) -->
                                                <span class="shrink-0 text-[10px] font-bold uppercase tracking-wider"
                                                      :class="{
                                                          'text-emerald-600': title.Status === 'Approved' || title.Status === 'Completed',
                                                          'text-amber-600': title.Status === 'Pending' || title.Status.includes('Incomplete') || title.Status.includes('Triage'),
                                                          'text-blue-600': title.Status.includes('Review') || title.Status.includes('Under'),
                                                          'text-rose-600': title.Status === 'Disapproved' || title.Status.includes('Deficiencies'),
                                                          'text-slate-600': !['Approved', 'Completed', 'Pending', 'Disapproved'].some(s => title.Status.includes(s))
                                                      }"
                                                      x-text="title.Status"></span>
                                            </div>
                                            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                                                <span x-text="'Submitted: ' + new Date(title.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })"></span>
                                                <a :href="'/admin/view_files/' + title.id" 
                                                   class="font-bold text-[#8B0000] hover:underline inline-flex items-center gap-1">
                                                    Inspect Files
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="filteredTitles.length === 0">
                                    <div class="py-8 text-center text-slate-400 text-xs">
                                        <p x-text="titleSearch ? 'No protocols match your search.' : 'No study protocols submitted yet.'"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="pt-4 border-t border-slate-200/80 flex items-center justify-end gap-3">
                            <button @click="showViewModal = false; showEditModal = true" 
                                    class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-colors shadow-xs cursor-pointer min-h-[44px]">
                                Edit Profile
                            </button>
                            <button @click="showViewModal = false" 
                                    class="px-5 py-2.5 bg-[#8B0000] hover:bg-[#6d0000] text-white text-sm font-bold rounded-xl shadow-xs transition-colors cursor-pointer min-h-[44px]">
                                Close Dossier
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
            
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    
                    <div class="bg-white px-6 py-5 border-b border-slate-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5 text-[#8B0000]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 font-heading">Edit Researcher Profile</h3>
                                <p class="text-xs text-slate-500">Update investigator identity details.</p>
                            </div>
                        </div>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-6" x-show="selectedUser">
                        <form :action="'/admin/users/' + (selectedUser ? selectedUser.id : '') + '/update'" method="POST" class="space-y-4">
                            @csrf
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">First Name</label>
                                    <input type="text" name="first_name" :value="selectedUser ? selectedUser.first_name : ''" required 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Middle Name</label>
                                    <input type="text" name="middle_name" :value="selectedUser ? (selectedUser.middle_name || '') : ''" 
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Last Name</label>
                                <input type="text" name="last_name" :value="selectedUser ? selectedUser.last_name : ''" required 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                                <input type="email" name="email" :value="selectedUser ? selectedUser.email : ''" required 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200/80 mt-6">
                                <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer min-h-[44px]">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-[#8B0000] hover:bg-[#6d0000] text-white text-sm font-bold rounded-xl shadow-xs transition-all cursor-pointer min-h-[44px]">
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
             class="fixed inset-0 z-[100] overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showConfirmModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    
                    <div class="bg-white px-6 pt-8 pb-6 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full" 
                             :class="confirmIsDelete ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600'">
                            <template x-if="confirmIsDelete">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </template>
                            <template x-if="!confirmIsDelete">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </template>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-bold text-slate-900 font-heading" x-text="confirmTitle"></h3>
                            <p class="mt-2 text-sm text-slate-500 leading-relaxed" x-text="confirmMessage"></p>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="showConfirmModal = false" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-200/80 rounded-xl transition-colors cursor-pointer min-h-[44px]">
                            Cancel
                        </button>
                        <form :action="confirmFormAction" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <template x-if="confirmIsDelete">
                                @method('DELETE')
                            </template>
                            <button type="submit" 
                                    class="w-full px-6 py-2.5 text-sm font-bold text-white rounded-xl shadow-xs transition-all cursor-pointer min-h-[44px]"
                                    :class="confirmIsDelete ? 'bg-rose-600 hover:bg-rose-700' : 'bg-[#8B0000] hover:bg-[#6d0000]'"
                                    x-text="confirmButtonText">
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin_layout>