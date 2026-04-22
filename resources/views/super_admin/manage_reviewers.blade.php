<x-super_admin_layout>
    <div x-data="{ 
        showAddModal: {{ $errors->any() ? 'true' : 'false' }}, 
        showViewModal: false, 
        selectedUser: null,
        showConfirmModal: false,
        confirmTitle: '',
        confirmMessage: '',
        confirmButtonText: '',
        confirmFormAction: '',
        confirmMethod: 'POST',
        confirmIsDelete: false,
        confirmFormId: null,

        triggerConfirm(title, message, buttonText, action, isDelete = false, formId = null) {
            this.confirmTitle = title;
            this.confirmMessage = message;
            this.confirmButtonText = buttonText;
            this.confirmFormAction = action;
            this.confirmIsDelete = isDelete;
            this.confirmFormId = formId;
            this.showConfirmModal = true;
        }
    }" class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Reviewers</h1>
                <p class="text-slate-500 mt-2 text-sm">Directory of system reviewers.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <button @click="showAddModal = true" class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-user-shield"></i> Add Reviewer
                </button>
            </div>
        </div>

        <!-- Search & Filter -->
        <form x-data="{ filterAffiliation: '{{ request('status') }}' }" method="GET" action="{{ route('super_admin.manage_reviewers') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4 items-center">
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

            <!-- Account Status Filter -->
            <div class="relative w-full md:w-40">
                <select name="account_status" class="w-full pl-4 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('account_status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="deactivated" {{ request('account_status') == 'deactivated' ? 'selected' : '' }}>Deactivated</option>
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
            </div>



            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#7A0000] transition-colors flex items-center gap-2 shadow-lg shadow-red-900/20">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </div>
        </form>


        <div class="flex items-center justify-between bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 mt-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-eye text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Researcher Identity Visibility</h3>
                    <p class="text-[11px] text-slate-500">Toggle whether all reviewers can see researcher names/emails.</p>
                </div>
            </div>
            <form id="globalVisibilityForm" action="{{ route('super_admin.reviewers.global_visibility') }}" method="POST"
                @submit.prevent="triggerConfirm(
                    'Update Global Visibility?', 
                    'This action will apply the chosen visibility setting to ALL reviewers in the system. Are you sure you want to proceed?', 
                    'Yes, Apply to All', 
                    $el.action,
                    false,
                    'globalVisibilityForm'
                )">
                @csrf
                <div class="flex items-center gap-3">
                    <select name="show_researcher_identity" class="pl-4 pr-10 py-2 bg-white border border-emerald-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-emerald-500 transition-all outline-none appearance-none cursor-pointer text-slate-700">
                        <option value="0" @selected(!$globalVisibility)>Hide Identities (Blind)</option>
                        <option value="1" @selected($globalVisibility)>Show Identities (Open)</option>
                    </select>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm">
                        Apply Globally
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100">
            <div class="overflow-x-auto md:overflow-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Name / Role</th>
                            <th class="p-6">Contact Info</th>
                            <th class="p-6">Affiliation</th>
                            <th class="p-6">Reviewed Titles</th>
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
                                        <p class="font-bold text-slate-800 text-base">{{ $user->first_name }} {{ $user->middle_name ? $user->middle_name . ' ' : '' }}{{ $user->last_name }}</p>
                                        <p class="text-xs text-slate-400">{{ $user->role }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6 text-sm text-slate-600">
                                <div class="flex items-center gap-2 group-hover:text-[#8B0000] transition-colors">
                                    <i class="far fa-envelope text-slate-400"></i> {{ $user->email }}
                                </div>
                            </td>
                            <td class="p-6">
                                @if(!$user->reviewer?->external_user)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Internal
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> External
                                </span>
                                @endif
                            </td>
                            @php
                                $reviewedTitles = \App\Models\Research_title::where('Status', 'Reviewed')
                                    ->whereJsonContains('assigned_reviewers', (string) $user->id)
                                    ->get(['id','Study_Protocol_title']);
                                $reviewedCount = $reviewedTitles->count();
                            @endphp
                            <td class="p-6">
                                @if($reviewedCount > 0)
                                    <button type="button"
                                        onclick="openReviewedModal(
                                            '{{ addslashes($user->first_name . ($user->middle_name ? ' ' . $user->middle_name : '') . ' ' . $user->last_name) }}',
                                            {{ $reviewedCount }},
                                            {{ json_encode($reviewedTitles->map(fn($t) => ['id' => $t->id, 'title' => $t->Study_Protocol_title])) }}
                                        )"
                                        class="flex items-center gap-2 group hover:text-[#8B0000] transition-colors">
                                        <span class="text-sm font-extrabold text-[#8B0000] bg-red-50 border border-red-200 rounded-full px-2.5 py-0.5 group-hover:bg-red-100 transition-colors">
                                            {{ $reviewedCount }}
                                        </span>
                                        <span class="text-xs text-slate-500 font-medium group-hover:text-[#8B0000] transition-colors">
                                            {{ $reviewedCount === 1 ? 'title' : 'titles' }}
                                        </span>
                                        <i class="fas fa-external-link-alt text-[9px] text-slate-300 group-hover:text-[#8B0000] transition-colors"></i>
                                    </button>
                                @else
                                    <span class="text-xs font-medium text-slate-400 italic">No reviews yet</span>
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
                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
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
                                                        '{{ route('super_admin.reviewers.toggle_status', $user->id) }}',
                                                        false
                                                    )" 
                                                    class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] flex items-center gap-2 transition-colors">
                                                <i class="fas fa-ban w-4"></i> {{ $user->is_verified ? 'Deactivate' : 'Activate' }}
                                            </button>

                                            <button type="button" 
                                                    @click="triggerConfirm(
                                                        'Delete Reviewer Account', 
                                                        'Are you sure you want to delete this reviewer? This action cannot be undone and all associated data will be removed.', 
                                                        'Delete Reviewer', 
                                                        '{{ route('super_admin.reviewers.delete', $user->id) }}',
                                                        true
                                                    )" 
                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2 transition-colors">
                                                <i class="fas fa-trash w-4"></i> Delete Reviewer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">
                                No Reviewers found.
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

        <!-- Reviewed Titles Modal (shared, JS-driven) -->
        <div id="reviewedTitlesModal" class="fixed inset-0 z-[9999] hidden" aria-modal="true" role="dialog">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeReviewedModal()"></div>

            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-100 w-full max-w-lg flex flex-col max-h-[80vh]"
                     style="animation: fadeInUp 0.25s ease-out">

                    <!-- Modal Header -->
                    <div class="px-6 pt-6 pb-4 border-b border-slate-100 flex items-start justify-between gap-4 flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#8B0000] to-red-700 flex items-center justify-center shadow-lg shadow-red-900/20 flex-shrink-0">
                                <i class="fas fa-clipboard-check text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900" id="reviewedModalReviewerName">Reviewer</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <span id="reviewedModalCount" class="font-bold text-[#8B0000]">0</span>
                                    <span id="reviewedModalLabel"> reviewed protocols</span>
                                </p>
                            </div>
                        </div>
                        <button onclick="closeReviewedModal()" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center transition-colors flex-shrink-0">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Search inside modal -->
                    <div class="px-6 py-3 border-b border-slate-50 flex-shrink-0">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-slate-400 text-xs"></i>
                            </div>
                            <input type="text" id="reviewedModalSearch"
                                oninput="filterReviewedTitles(this.value)"
                                placeholder="Filter titles..."
                                class="w-full pl-9 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Title List -->
                    <div class="overflow-y-auto flex-1 px-6 py-4" id="reviewedTitlesList">
                        <!-- Populated by JS -->
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-100 flex-shrink-0 flex justify-end">
                        <button onclick="closeReviewedModal()" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors">
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
                                    <h3 class="text-lg font-bold text-slate-900" id="modal-title">Add New Reviewer</h3>
                                    <p class="text-xs text-slate-500">Create a new account for a reviewer.</p>
                                </div>
                            </div>
                            <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6" x-data="{ 
                        colleges: {{ Js::from($colleges) }},
                        selectedCollege: '',
                        selectedDept: '',
                        selectedProgram: '',
                        
                        get currentDepartments() {
                            const college = this.colleges.find(c => c.name === this.selectedCollege);
                            return college ? college.departments : [];
                        },
                        
                        get currentPrograms() {
                            const dept = this.currentDepartments.find(d => d.name === this.selectedDept);
                            return dept ? dept.programs : [];
                        }
                    }">
                        <form action="{{ route('super_admin.reviewers.create') }}" method="POST" class="space-y-5">
                            @csrf

                            @if ($errors->any())
                                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <div class="grid grid-cols-3 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">First Name</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" name="first_name" required placeholder="John" value="{{ old('first_name') }}" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Middle Name</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" name="middle_name" placeholder="Quincy" value="{{ old('middle_name') }}" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Last Name</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" name="last_name" required placeholder="Doe" value="{{ old('last_name') }}" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5 w-full col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                                    <div class="relative">
                                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="email" name="email" required placeholder="reviewer@example.com" value="{{ old('email') }}" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5 w-full col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">College (Optional)</label>
                                    <div class="relative">
                                        <select name="college" x-model="selectedCollege" @change="selectedDept = ''; selectedProgram = ''" class="w-full pl-3 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                                            <option value="" selected>Select College (Optional)</option>
                                            <template x-for="college in colleges" :key="college.id">
                                                <option :value="college.name" x-text="college.name"></option>
                                            </template>
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5 w-full col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Expertise (Comma separated)</label>
                                    <div class="relative">
                                        <i class="fas fa-lightbulb absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" name="expertise" placeholder="e.g. Data Science, Machine Learning" value="{{ old('expertise') }}" class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all placeholder:text-slate-300">
                                    </div>
                                </div>
                            </div>



                            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100 mt-2">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="training_completed" value="1" class="peer sr-only">
                                        <div class="w-5 h-5 border-2 border-slate-300 rounded peer-checked:bg-[#8B0000] peer-checked:border-[#8B0000] transition-all flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider group-hover:text-[#8B0000] transition-colors">Training Completed</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="external_user" value="1" class="peer sr-only">
                                        <div class="w-5 h-5 border-2 border-slate-300 rounded peer-checked:bg-[#8B0000] peer-checked:border-[#8B0000] transition-all flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider group-hover:text-[#8B0000] transition-colors">External User</span>
                                </label>
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
                                    <h3 class="text-lg font-bold text-slate-900">Reviewer Details</h3>
                                    <p class="text-xs text-slate-500">View complete reviewer information.</p>
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
                                <div class="mt-2 text-sm flex gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border"
                                          :class="selectedUser.is_verified ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-red-50 text-red-700 border-red-100'">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="selectedUser.is_verified ? 'bg-emerald-500' : 'bg-red-500'"></span>
                                        <span x-text="selectedUser.is_verified ? 'Active' : 'Deactivated'"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1 w-full col-span-2">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">College</label>
                                    <div class="flex items-center gap-2 text-slate-700 bg-white p-3 rounded-lg border border-slate-200">
                                        <i class="fas fa-university text-slate-400"></i>
                                        <span x-text="selectedUser.reviewer?.college || 'None'"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1 w-full col-span-2">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Expertise</label>
                                    <div class="flex items-center gap-2 text-slate-700 bg-white p-3 rounded-lg border border-slate-200">
                                        <i class="fas fa-lightbulb text-slate-400"></i>
                                        <span x-text="(selectedUser.reviewer?.expertise && selectedUser.reviewer.expertise.length) ? selectedUser.reviewer.expertise.join(', ') : 'None specified'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Details</label>
                                    <div class="flex items-center gap-2 text-slate-700 bg-white p-3 rounded-lg border border-slate-200 text-sm h-full">
                                        <div class="flex flex-col gap-2">
                                            <span class="inline-flex items-center gap-2">
                                                <i class="fas px-1" :class="selectedUser.reviewer?.training_completed ? 'fa-check text-green-500' : 'fa-times text-red-500'"></i>
                                                Training Completed
                                            </span>
                                            <span class="inline-flex items-center gap-2">
                                                <i class="fas px-1" :class="selectedUser.reviewer?.external_user ? 'fa-check text-green-500' : 'fa-times text-slate-300'"></i>
                                                External User
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2 flex flex-col justify-between">
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
                                    <h3 class="text-lg font-bold text-slate-900">Edit Reviewer Profile</h3>
                                    <p class="text-xs text-slate-500">Update reviewer details.</p>
                                </div>
                            </div>
                            <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6" x-if="selectedUser">
                        <form :action="'/super-admin/reviewers/' + selectedUser.id + '/update'" method="POST" class="space-y-5">
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

                            <div class="space-y-1.5 pt-2">
                                <label class="flex items-center gap-3 cursor-pointer p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="show_researcher_identity" value="1" class="sr-only peer" :checked="selectedUser.reviewer?.show_researcher_identity">
                                        <div class="block bg-slate-300 w-10 h-6 rounded-full peer-checked:bg-[#8B0000] transition-colors"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-800">Show Researcher Identity</span>
                                        <span class="text-xs text-slate-500 font-normal">Allow this reviewer to see the researcher's name and email during evaluations.</span>
                                    </div>
                                </label>
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
                        <div class="w-full sm:w-auto">
                            <form :action="confirmFormAction" method="POST" x-show="!confirmFormId">
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
                            <button x-show="confirmFormId" 
                                    @click="document.getElementById(confirmFormId).submit()"
                                    type="button"
                                    class="w-full px-8 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0"
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
        _allReviewedTitles = titlesData; // array of {id, title}

        document.getElementById('reviewedModalReviewerName').textContent = reviewerName;
        document.getElementById('reviewedModalCount').textContent = count;
        document.getElementById('reviewedModalLabel').textContent = count === 1 ? ' reviewed protocol' : ' reviewed protocols';
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
                    <i class="fas fa-search text-2xl mb-3 text-slate-300"></i>
                    <p class="text-sm font-medium">No titles match your search.</p>
                </div>`;
            return;
        }

        container.innerHTML = items.map((item, i) => {
            let displayTitle = escapeHtml(item.title);
            if (highlight) {
                const regex = new RegExp(`(${escapeRegex(highlight)})`, 'gi');
                displayTitle = displayTitle.replace(regex, '<mark class="bg-yellow-100 text-yellow-900 rounded px-0.5">$1</mark>');
            }
            const viewUrl = `${_viewFilesBase}/${item.id}`;
            return `
                <div class="flex items-center gap-3 py-3 ${i !== 0 ? 'border-t border-slate-50' : ''}">
                    <div class="w-6 h-6 rounded-full bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-[10px] font-extrabold text-[#8B0000]">${i + 1}</span>
                    </div>
                    <p class="text-sm text-slate-700 font-medium leading-snug flex-1">${displayTitle}</p>
                    <a href="${viewUrl}"
                       class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-[#8B0000] bg-red-50 border border-red-200 hover:bg-red-100 hover:border-red-300 transition-colors whitespace-nowrap"
                       title="View protocol files">
                        <i class="fas fa-folder-open text-[10px]"></i>
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

    // Close on Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeReviewedModal();
    });
</script>
