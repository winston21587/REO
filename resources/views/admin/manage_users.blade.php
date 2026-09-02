<x-admin_layout>
    <div x-data="{ 
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
                t.Study_Protocol_title.toLowerCase().includes(search) || 
                t.Status.toLowerCase().includes(search)
            );
        }
    }" class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Researchers</h1>
                <p class="text-slate-500 mt-2 text-sm">Directory of registered faculty, staff, and student researchers.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <button @click="showAddModal = true" class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Add Researcher
                </button>
            </div>
        </div>

        <!-- Search & Filter -->
        <form x-data="{ filterAffiliation: '{{ request('status') }}' }" method="GET" action="{{ route('admin.manage_users') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-grow w-full md:w-auto">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white transition-all outline-none" placeholder="Search by name, email..." type="text" />
            </div>
            
            <!-- Affiliation Filter -->
            <div class="relative w-full md:w-40">
                <select x-model="filterAffiliation" name="status" class="w-full pl-4 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                    <option value="">All Affiliations</option>
                    <option value="internal" {{ request('status') == 'internal' ? 'selected' : '' }}>Internal</option>
                    <option value="external" {{ request('status') == 'external' ? 'selected' : '' }}>External</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
            </div>

            <!-- College Filter -->
            <div class="relative w-full md:w-48">
                <select :disabled="filterAffiliation === 'external'" :class="{ 'opacity-60 cursor-not-allowed bg-slate-100': filterAffiliation === 'external' }" name="college" class="w-full pl-4 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                    <option value="">All Colleges</option>
                    @foreach($colleges as $college)
                        <option value="{{ $college->name }}" {{ request('college') == $college->name ? 'selected' : '' }}>{{ $college->name }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
            </div>



            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#7A0000] transition-colors flex items-center gap-2 shadow-lg shadow-red-900/20">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </div>
        </form>

        <!-- Users List -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100">
            <div class="overflow-x-auto md:overflow-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Name / Role</th>
                            <th class="p-6">Contact Info</th>
                            <th class="p-6">Affiliation</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold shadow-sm border border-blue-100">
                                        {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-base">{{ $user->first_name }} {{ $user->last_name }}</p>
                                        <p class="text-xs text-slate-400">{{ $user->researcher?->college ?? 'Researcher' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6 text-sm text-slate-600">
                                <div class="flex items-center gap-2 group-hover:text-[#8B0000] transition-colors">
                                    <i class="far fa-envelope text-slate-400"></i> {{ $user->email }}
                                </div>
                            </td>
                            <td class="p-6">
                                @if(!$user->researcher?->external_user)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Internal
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> External
                                </span>
                                @endif
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-1.5 pl-1">
                                    @if($user->is_verified)
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_4px_rgba(34,197,94,0.4)]"></div>
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Active</span>
                                    @elseif($user->email_verified_at)
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-400"></div>
                                        <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Deactivated</span>
                                    @else
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Pending</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-6 text-right relative">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-500 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden" 
                                         style="display: none;">
                                        <div class="py-1" role="menu" aria-orientation="vertical">
                                            <button @click="selectedUser = {{ $user }}; showViewModal = true" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] flex items-center gap-2 transition-colors">
                                                <i class="fas fa-eye w-4"></i> View Details
                                            </button>
                                            
                                            <button type="button" 
                                                    @click="triggerConfirm(
                                                        '{{ $user->is_verified ? 'Deactivate Account' : 'Activate Account' }}', 
                                                        'Are you sure you want to {{ $user->is_verified ? 'deactivate' : 'activate' }} {{ $user->first_name }}\'s account?', 
                                                        '{{ $user->is_verified ? 'Deactivate' : 'Activate' }}', 
                                                        '{{ route('admin.users.toggle_status', $user->id) }}',
                                                        false
                                                    )" 
                                                    class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] flex items-center gap-2 transition-colors">
                                                <i class="fas fa-ban w-4"></i> {{ $user->is_verified ? 'Deactivate' : 'Activate' }}
                                            </button>

                                            <button type="button" 
                                                    @click="triggerConfirm(
                                                        'Delete Researcher Account', 
                                                        'Are you sure you want to delete this researcher? This action cannot be undone and all associated data will be removed.', 
                                                        'Delete User', 
                                                        '{{ route('admin.users.delete', $user->id) }}',
                                                        true
                                                    )" 
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2 transition-colors">
                                                <i class="fas fa-trash w-4"></i> Delete User
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-500">
                                No researchers found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Add Researcher Modal -->
        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <!-- Header -->
                    <div class="bg-white px-6 pt-6 pb-4 border-b border-slate-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                                    <i class="fas fa-user-plus text-[#8B0000] text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900" id="modal-title">Add New Researcher</h3>
                                    <p class="text-xs text-slate-500">Create a new account for a researcher.</p>
                                </div>
                            </div>
                            <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6">
                        <form action="{{ route('admin.users.create') }}" method="POST" class="space-y-5" x-data="{ affiliation: 'internal' }">
                            @csrf
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">First Name</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" name="first_name" required placeholder="John" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Middle Name <span class="text-slate-400 font-normal normal-case">(Optional)</span></label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" name="middle_name" placeholder="Doe" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Last Name</label>
                                <div class="relative">
                                    <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="text" name="last_name" required placeholder="Smith" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="email" name="email" required placeholder="john.doe@wmsu.edu.ph" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Affiliation</label>
                                <div class="relative">
                                    <i class="fas fa-sitemap absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <select name="affiliation" x-model="affiliation" required class="w-full pl-9 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all appearance-none cursor-pointer text-slate-600">
                                        <option value="internal">Internal</option>
                                        <option value="external">External</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider" :class="affiliation === 'external' ? 'opacity-50' : ''">College</label>
                                <div class="relative">
                                    <i class="fas fa-graduation-cap absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs" :class="affiliation === 'external' ? 'opacity-50' : ''"></i>
                                    <select name="college" :required="affiliation === 'internal'" :disabled="affiliation === 'external'" 
                                            :class="affiliation === 'external' ? 'opacity-50 cursor-not-allowed bg-slate-100' : 'bg-slate-50 cursor-pointer focus:ring-2 focus:ring-[#8B0000] focus:bg-white'"
                                            class="w-full pl-9 pr-8 py-2.5 border border-slate-200 rounded-xl text-sm outline-none transition-all appearance-none text-slate-600">
                                        <option value="" disabled selected>Select College</option>
                                        @foreach($colleges as $college)
                                            <option value="{{ $college->name }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs" :class="affiliation === 'external' ? 'opacity-50' : ''"></i>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="password" name="password" required placeholder="••••••••" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Confirm Password</label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-50 mt-6">
                                <button type="button" @click="showAddModal = false" class="px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#8B0000] to-[#600000] text-white text-sm font-bold rounded-xl shadow-lg shadow-red-900/20 hover:shadow-red-900/30 hover:-translate-y-0.5 transition-all">
                                    Create Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- View User Modal -->
        <div x-show="showViewModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showViewModal = false"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <!-- Header -->
                    <div class="bg-white px-6 pt-6 pb-4 border-b border-slate-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                                    <i class="fas fa-user-circle text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Researcher Details</h3>
                                    <p class="text-xs text-slate-500">View complete researcher information.</p>
                                </div>
                            </div>
                            <button @click="showViewModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6 space-y-6" x-if="selectedUser">
                        <!-- Profile Header -->
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-16 h-16 rounded-full bg-white border-2 border-white shadow-sm flex items-center justify-center text-2xl font-bold text-[#8B0000]">
                                <span x-text="selectedUser.first_name.charAt(0) + selectedUser.last_name.charAt(0)"></span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900" x-text="selectedUser.first_name + (selectedUser.middle_name ? ' ' + selectedUser.middle_name : '') + ' ' + selectedUser.last_name"></h4>
                                <p class="text-sm text-slate-500" x-text="selectedUser.email"></p>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 gap-4">
                            <!-- External: show institution -->
                            <template x-if="selectedUser.researcher && selectedUser.researcher.external_user">
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Institution / Agency</label>
                                    <div class="flex items-center gap-2 text-slate-700 bg-white p-3 rounded-lg border border-slate-200">
                                        <i class="fas fa-building text-slate-400"></i>
                                        <span x-text="(selectedUser.researcher && selectedUser.researcher.institute) ? selectedUser.researcher.institute : 'Not Provided'"></span>
                                    </div>
                                </div>
                            </template>

                            <!-- Internal: show college, department, program -->
                            <template x-if="!selectedUser.researcher || !selectedUser.researcher.external_user">
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="space-y-1">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">College</label>
                                        <div class="flex items-center gap-2 text-slate-700 bg-white p-3 rounded-lg border border-slate-200">
                                            <i class="fas fa-graduation-cap text-slate-400"></i>
                                            <span x-text="(selectedUser.researcher && selectedUser.researcher.college) ? selectedUser.researcher.college : 'Not Assigned'"></span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Department</label>
                                        <div class="flex items-center gap-2 text-slate-700 bg-white p-3 rounded-lg border border-slate-200">
                                            <i class="fas fa-sitemap text-slate-400"></i>
                                            <span x-text="(selectedUser.researcher && selectedUser.researcher.department) ? selectedUser.researcher.department : 'Not Assigned'"></span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Program</label>
                                        <div class="flex items-center gap-2 text-slate-700 bg-white p-3 rounded-lg border border-slate-200">
                                            <i class="fas fa-book-open text-slate-400"></i>
                                            <span x-text="(selectedUser.researcher && selectedUser.researcher.program) ? selectedUser.researcher.program : 'Not Assigned'"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Role</label>
                                    <div class="flex items-center gap-2 text-slate-700 bg-white p-3 rounded-lg border border-slate-200">
                                        <i class="fas fa-id-badge text-slate-400"></i>
                                        <span class="capitalize" x-text="selectedUser.role"></span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Joined Date</label>
                                    <div class="flex items-center gap-2 text-slate-700 bg-white p-3 rounded-lg border border-slate-200">
                                        <i class="fas fa-calendar-alt text-slate-400"></i>
                                        <span x-text="new Date(selectedUser.created_at).toLocaleDateString()"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submitted Titles Section -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-file-alt text-[#8B0000]"></i> Submitted Titles
                                </h4>
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold" 
                                      x-text="selectedUser.researcher?.research_titles?.length || 0"></span>
                            </div>

                            <!-- Title Search -->
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <input type="text" 
                                       x-model="titleSearch" 
                                       placeholder="Search titles or status..." 
                                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                            </div>

                            <!-- Titles List -->
                            <div class="max-h-[300px] overflow-y-auto custom-scrollbar space-y-2 pr-1">
                                <template x-for="title in filteredTitles" :key="title.id">
                                    <div class="p-3 rounded-xl border border-slate-100 bg-white hover:border-[#8B0000]/30 transition-all group shadow-sm">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex justify-between items-start gap-3">
                                                <h5 class="text-xs font-bold text-slate-800 leading-tight group-hover:text-[#8B0000] transition-colors" x-text="title.Study_Protocol_title"></h5>
                                                <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                                                      :class="{
                                                          'bg-emerald-50 text-emerald-700': title.Status === 'Approved' || title.Status === 'Completed',
                                                          'bg-amber-50 text-amber-700': title.Status === 'Pending' || title.Status.includes('Incomplete'),
                                                          'bg-blue-50 text-blue-700': title.Status.includes('Review') || title.Status.includes('Under'),
                                                          'bg-red-50 text-red-700': title.Status === 'Disapproved',
                                                          'bg-slate-50 text-slate-700': !['Approved', 'Completed', 'Pending', 'Disapproved'].some(s => title.Status.includes(s))
                                                      }"
                                                      x-text="title.Status"></span>
                                            </div>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-[10px] text-slate-400" x-text="'Submitted: ' + new Date(title.created_at).toLocaleDateString()"></span>
                                                <a :href="'/admin/view_files/' + title.id" 
                                                   class="text-[10px] font-bold text-[#8B0000] hover:underline flex items-center gap-1">
                                                    View Details <i class="fas fa-external-link-alt text-[8px]"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="filteredTitles.length === 0">
                                    <div class="py-12 text-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-folder-open text-slate-300 text-xl"></i>
                                        </div>
                                        <p class="text-xs text-slate-500 font-medium" x-text="titleSearch ? 'No matches found.' : 'No titles submitted yet.'"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="pt-4 border-t border-slate-50 flex justify-end gap-3">
                            <button @click="showViewModal = false; showEditModal = true" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                                <i class="fas fa-edit mr-1.5"></i> Edit Profile
                            </button>
                            <button @click="showViewModal = false" class="px-4 py-2 bg-slate-900 text-white text-sm font-bold rounded-lg hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20">
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
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="bg-white px-6 pt-6 pb-4 border-b border-slate-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                                    <i class="fas fa-user-edit text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Edit Researcher Profile</h3>
                                    <p class="text-xs text-slate-500">Update researcher details.</p>
                                </div>
                            </div>
                            <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6" x-if="selectedUser">
                        <form :action="'/admin/users/' + selectedUser.id + '/update'" method="POST" class="space-y-5">
                            @csrf
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">First Name</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" name="first_name" :value="selectedUser.first_name" required class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Middle Name</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" name="middle_name" :value="selectedUser.middle_name" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Last Name</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" name="last_name" :value="selectedUser.last_name" required class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input type="email" name="email" :value="selectedUser.email" required class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                                </div>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-50 mt-6">
                                <button type="button" @click="showEditModal = false" class="px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-800 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-900/20 hover:shadow-blue-900/30 hover:-translate-y-0.5 transition-all">
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
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showConfirmModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="bg-white px-6 pt-8 pb-6 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full" :class="confirmIsDelete ? 'bg-red-50' : 'bg-amber-50'">
                            <i class="fas" :class="confirmIsDelete ? 'fa-exclamation-triangle text-red-600 text-2xl' : 'fa-info-circle text-amber-600 text-2xl'"></i>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-xl font-bold text-slate-900" x-text="confirmTitle"></h3>
                            <p class="mt-3 text-sm text-slate-500 leading-relaxed" x-text="confirmMessage"></p>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-center gap-3">
                        <button type="button" @click="showConfirmModal = false" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-200 rounded-xl transition-all duration-200">
                            Cancel
                        </button>
                        <form :action="confirmFormAction" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <template x-if="confirmIsDelete">
                                @method('DELETE')
                            </template>
                            <button type="submit" 
                                    class="w-full px-8 py-2.5 text-sm font-bold text-white rounded-xl shadow-lg transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0"
                                    :class="confirmIsDelete ? 'bg-red-600 hover:bg-red-700 shadow-red-900/20' : 'bg-[#8B0000] hover:bg-[#7A0000] shadow-red-900/20'"
                                    x-text="confirmButtonText">
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin_layout>