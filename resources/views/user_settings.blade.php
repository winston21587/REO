<x-user_layout>
    @php 
        $user = Auth::user(); 
        $initials = strtoupper(substr($user->first_name ?? 'R', 0, 1) . substr($user->last_name ?? 'E', 0, 1));
        $isExternal = $user->researcher && $user->researcher->external_user;
    @endphp

    {{-- Flash Feedback Alerts --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-20 sm:top-24 right-4 sm:right-6 z-50 bg-emerald-50 border border-emerald-200 rounded-xl shadow-lg shadow-emerald-500/10 p-4 animate-[fadeInLeft_0.3s_ease-out] flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base" aria-hidden="true"></i>
            <div>
                <p class="font-bold text-emerald-950 text-xs">Success</p>
                <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="ml-2 text-emerald-700 hover:text-emerald-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 rounded-lg p-1 cursor-pointer" aria-label="Dismiss notification">
                <i class="fa-solid fa-xmark text-xs" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div x-data="{ show: true }" x-show="show"
            class="fixed top-20 sm:top-24 right-4 sm:right-6 z-50 bg-rose-50 border border-rose-200 rounded-xl shadow-lg shadow-rose-500/10 p-4 animate-[fadeInLeft_0.3s_ease-out] flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base mt-0.5" aria-hidden="true"></i>
            <div>
                <p class="font-bold text-rose-950 text-xs">Action Failed</p>
                <ul class="text-xs text-rose-800 list-disc pl-4 mt-0.5 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button @click="show = false" class="ml-auto text-rose-700 hover:text-rose-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-600 rounded-lg p-1 cursor-pointer" aria-label="Dismiss error">
                <i class="fa-solid fa-xmark text-xs" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    <div class="max-w-6xl mx-auto w-full flex-1 min-h-0 flex flex-col justify-start py-2 sm:py-4 px-4 sm:px-6 lg:px-8 font-['Inter']"
         x-data="{ 
            activeTab: window.location.hash ? window.location.hash.replace('#', '') : 'profile',
            showDeleteModal: false,
            showCurrentPw: false,
            showNewPw: false,
            showConfirmPw: false,
            setTab(tab) {
                this.activeTab = tab;
                window.location.hash = tab;
            }
         }"
         x-cloak>

        <!-- Page Header & Researcher Badge (Separated Layout with Matching Typography) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 pb-4.5 border-b border-slate-200/80 mb-6 sm:mb-7 shrink-0">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 font-['Montserrat'] tracking-tight leading-tight">Account Settings</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-normal">Manage your researcher profile, academic affiliation, credentials, and notification settings.</p>
            </div>
            <!-- Researcher Identity Card (Mobile Full-Width Spaced / Desktop Inline Right) -->
            <div class="w-full sm:w-auto flex items-center justify-between sm:justify-start gap-3 px-4 py-2.5 sm:py-2 bg-white rounded-xl border border-slate-200/90 shadow-2xs">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#8B0000] to-[#5a0000] text-white flex items-center justify-center font-semibold text-xs shadow-2xs font-['Montserrat'] shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs sm:text-xs font-bold sm:font-semibold text-slate-800 leading-tight truncate">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </div>
                        <div class="hidden sm:flex text-xs text-slate-500 items-center gap-1.5 mt-0.5 font-medium">
                            <span class="inline-block w-1.5 h-1.5 rounded-full {{ $isExternal ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                            <span>{{ $isExternal ? 'External Researcher' : 'Internal Researcher' }}</span>
                        </div>
                    </div>
                </div>
                <!-- Mobile Affiliation Pill -->
                <div class="sm:hidden shrink-0">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $isExternal ? 'bg-amber-50 text-amber-800 border border-amber-200/80' : 'bg-emerald-50 text-emerald-800 border border-emerald-200/80' }}">
                        <span class="inline-block w-1.5 h-1.5 rounded-full {{ $isExternal ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                        <span>{{ $isExternal ? 'External Researcher' : 'Internal Researcher' }}</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Layout: Sidebar Navigation + Content Panes with Distinct Separation -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start flex-1 min-h-0">

            <!-- Navigation Sidebar -->
            <div class="lg:col-span-4 space-y-5 shrink-0">
                
                <!-- Desktop Navigation Menu -->
                <nav class="hidden lg:block bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-3 sm:p-3.5 space-y-1.5" aria-label="Settings navigation">
                    <button type="button" @click="setTab('profile')"
                        :class="activeTab === 'profile' ? 'bg-[#8B0000]/8 text-[#8B0000] font-semibold shadow-2xs ring-1 ring-[#8B0000]/15' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium'"
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-xs sm:text-sm transition-all duration-150 text-left cursor-pointer min-h-[46px] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                             :class="activeTab === 'profile' ? 'bg-white text-[#8B0000] shadow-xs' : 'bg-slate-100/80 text-slate-400'">
                            <i class="fa-solid fa-user text-xs" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-xs sm:text-sm leading-tight">Profile & Affiliation</span>
                            <span class="block text-xs text-slate-400 font-normal mt-0.5 truncate">Academic details & contact</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs transition-transform" 
                           :class="activeTab === 'profile' ? 'text-[#8B0000] translate-x-0.5' : 'text-slate-300'" aria-hidden="true"></i>
                    </button>

                    <button type="button" @click="setTab('security')"
                        :class="activeTab === 'security' ? 'bg-[#8B0000]/8 text-[#8B0000] font-semibold shadow-2xs ring-1 ring-[#8B0000]/15' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium'"
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-xs sm:text-sm transition-all duration-150 text-left cursor-pointer min-h-[46px] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                             :class="activeTab === 'security' ? 'bg-white text-[#8B0000] shadow-xs' : 'bg-slate-100/80 text-slate-400'">
                            <i class="fa-solid fa-shield-halved text-xs" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-xs sm:text-sm leading-tight">Security & Password</span>
                            <span class="block text-xs text-slate-400 font-normal mt-0.5 truncate">Authentication & credentials</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs transition-transform" 
                           :class="activeTab === 'security' ? 'text-[#8B0000] translate-x-0.5' : 'text-slate-300'" aria-hidden="true"></i>
                    </button>

                    <button type="button" @click="setTab('notifications')"
                        :class="activeTab === 'notifications' ? 'bg-[#8B0000]/8 text-[#8B0000] font-semibold shadow-2xs ring-1 ring-[#8B0000]/15' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium'"
                        class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-xs sm:text-sm transition-all duration-150 text-left cursor-pointer min-h-[46px] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                             :class="activeTab === 'notifications' ? 'bg-white text-[#8B0000] shadow-xs' : 'bg-slate-100/80 text-slate-400'">
                            <i class="fa-solid fa-bell text-xs" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-xs sm:text-sm leading-tight">Notifications</span>
                            <span class="block text-xs text-slate-400 font-normal mt-0.5 truncate">Status and meeting alerts</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs transition-transform" 
                           :class="activeTab === 'notifications' ? 'text-[#8B0000] translate-x-0.5' : 'text-slate-300'" aria-hidden="true"></i>
                    </button>

                    <div class="pt-2.5 mt-2.5 border-t border-slate-100">
                        <button type="button" @click="setTab('danger')"
                            :class="activeTab === 'danger' ? 'bg-rose-50 text-rose-700 font-semibold shadow-2xs ring-1 ring-rose-200/60' : 'text-rose-900/70 hover:bg-slate-50 hover:text-rose-700 font-medium'"
                            class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-xs sm:text-sm transition-all duration-150 text-left cursor-pointer min-h-[46px] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                                 :class="activeTab === 'danger' ? 'bg-white text-rose-600 shadow-xs' : 'bg-rose-50 text-rose-500'">
                                <i class="fa-solid fa-triangle-exclamation text-xs" aria-hidden="true"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="block text-xs sm:text-sm leading-tight">Danger Zone</span>
                                <span class="block text-xs text-rose-400 font-normal mt-0.5 truncate">Account termination</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs transition-transform" 
                               :class="activeTab === 'danger' ? 'text-rose-600 translate-x-0.5' : 'text-slate-300'" aria-hidden="true"></i>
                        </button>
                    </div>
                </nav>

                <!-- Mobile Segmented Tab Strip -->
                <div class="lg:hidden flex items-center gap-1.5 p-1.5 bg-slate-100 rounded-2xl overflow-x-auto no-scrollbar">
                    <button type="button" @click="setTab('profile')"
                        :class="activeTab === 'profile' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 font-medium hover:text-slate-900'"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer min-h-[40px]">
                        <i class="fa-solid fa-user text-xs text-[#8B0000]"></i>
                        <span>Profile</span>
                    </button>
                    <button type="button" @click="setTab('security')"
                        :class="activeTab === 'security' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 font-medium hover:text-slate-900'"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer min-h-[40px]">
                        <i class="fa-solid fa-shield-halved text-xs text-[#8B0000]"></i>
                        <span>Security</span>
                    </button>
                    <button type="button" @click="setTab('notifications')"
                        :class="activeTab === 'notifications' ? 'bg-white text-slate-900 font-semibold shadow-xs' : 'text-slate-600 font-medium hover:text-slate-900'"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer min-h-[40px]">
                        <i class="fa-solid fa-bell text-xs text-[#8B0000]"></i>
                        <span>Alerts</span>
                    </button>
                    <button type="button" @click="setTab('danger')"
                        :class="activeTab === 'danger' ? 'bg-rose-600 text-white font-semibold shadow-xs' : 'text-rose-600 font-medium hover:text-rose-700'"
                        class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2.5 rounded-xl text-xs whitespace-nowrap transition-all cursor-pointer min-h-[40px]">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                        <span>Danger</span>
                    </button>
                </div>

                <!-- Researcher Institutional Note Box -->
                <div class="hidden lg:block p-5 sm:p-5.5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs space-y-2.5 text-xs text-slate-500 leading-relaxed">
                    <div class="flex items-center gap-2 text-slate-800 font-semibold">
                        <i class="fa-solid fa-shield-check text-[#8B0000]" aria-hidden="true"></i>
                        <span>Institutional Verification</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        All protocol submissions, review decisions, and certificate issuances remain permanently associated with your registered researcher identity.
                    </p>
                </div>
            </div>

            <!-- Content Panes -->
            <div class="lg:col-span-8 flex-1 min-h-0">

                <!-- PANE 1: Personal Profile & Academic Affiliation -->
                <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    
                    <div class="px-6 sm:px-8 py-5 border-b border-slate-100/90 flex items-center justify-between gap-4 bg-slate-50/40 shrink-0">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-800 font-['Montserrat']">Personal Profile & Affiliation</h2>
                            <p class="text-xs text-slate-500 mt-0.5 font-normal">Update your formal name and academic or institutional affiliation records.</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-id-card text-sm" aria-hidden="true"></i>
                        </div>
                    </div>

                    <form action="{{ route('settings.update_profile') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                        @csrf

                        <!-- Personal Name Section -->
                        <div class="space-y-3">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-signature text-slate-400"></i>
                                <span>Official Name Records</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-4.5">
                                <div class="space-y-1.5">
                                    <label for="first_name" class="block text-xs font-semibold text-slate-600">
                                        First Name <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                        class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-2xs min-h-[44px]">
                                </div>

                                <div class="space-y-1.5">
                                    <label for="middle_name" class="block text-xs font-semibold text-slate-600">
                                        Middle Name
                                    </label>
                                    <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                                        class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-2xs min-h-[44px]">
                                </div>

                                <div class="space-y-1.5">
                                    <label for="last_name" class="block text-xs font-semibold text-slate-600">
                                        Last Name <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                        class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-2xs min-h-[44px]">
                                </div>
                            </div>
                        </div>

                        <!-- Email Information (Read-only Institutional Safeguard) -->
                        <div class="space-y-2 pt-1">
                            <label for="profile_email" class="block text-xs font-semibold text-slate-600">
                                Registered Email Address
                            </label>
                            <div class="relative">
                                <input type="email" id="profile_email" value="{{ $user->email }}" disabled
                                    class="w-full px-4 py-2.5 sm:py-3 bg-slate-50 border border-slate-200/90 rounded-xl text-sm font-medium text-slate-500 cursor-not-allowed min-h-[44px] pr-10">
                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-lock text-xs" aria-hidden="true"></i>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-info text-slate-400"></i>
                                <span>Official institutional email changes must be processed through the REO Secretariat.</span>
                            </p>
                        </div>

                        <!-- Affiliation Block (External or Internal Cascade) -->
                        <div class="pt-5 border-t border-slate-100/90">
                            @if($isExternal)
                                <div class="space-y-1.5">
                                    <label for="profile_institute" class="block text-xs font-semibold text-slate-600">
                                        Affiliated Institution / Agency <span class="text-rose-600">*</span>
                                    </label>
                                    <input type="text" id="profile_institute" name="institute" value="{{ old('institute', $user->researcher->institute ?? '') }}" required
                                        placeholder="e.g., Department of Health / Western Mindanao Medical Center"
                                        class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-2xs min-h-[44px]">
                                </div>
                            @elseif($user->researcher)
                                <div class="space-y-3.5" x-data="{ 
                                    colleges: {{ Js::from($colleges) }},
                                    selectedCollege: '{{ old('college', $user->researcher->college ?? '') }}',
                                    selectedDept: '{{ old('department', $user->researcher->department ?? '') }}',
                                    selectedProgram: '{{ old('program', $user->researcher->program ?? '') }}',

                                    initNormalization() {
                                        let cMatch = this.colleges.find(c => String(c.name).trim().toLowerCase() === String(this.selectedCollege).trim().toLowerCase());
                                        if(cMatch && this.selectedCollege !== cMatch.name) this.selectedCollege = cMatch.name;
                                        
                                        setTimeout(() => {
                                            let depts = this.currentDepartments;
                                            let dMatch = depts.find(d => String(d.name).trim().toLowerCase() === String(this.selectedDept).trim().toLowerCase());
                                            if(dMatch && this.selectedDept !== dMatch.name) this.selectedDept = dMatch.name;
                                            
                                            setTimeout(() => {
                                                let progs = this.currentPrograms;
                                                let pMatch = progs.find(p => String(p.name).trim().toLowerCase() === String(this.selectedProgram).trim().toLowerCase());
                                                if(pMatch && this.selectedProgram !== pMatch.name) this.selectedProgram = pMatch.name;
                                            }, 50);
                                        }, 50);
                                    },

                                    get currentDepartments() {
                                        if (!this.selectedCollege) return [];
                                        const college = this.colleges.find(c => String(c.name).trim().toLowerCase() === String(this.selectedCollege).trim().toLowerCase());
                                        if(!college || !college.departments) return [];
                                        return Array.isArray(college.departments) ? college.departments : Object.values(college.departments);
                                    },

                                    get currentPrograms() {
                                        if (!this.selectedDept) return [];
                                        const depts = this.currentDepartments;
                                        if(!depts || depts.length === 0) return [];
                                        const dept = depts.find(d => String(d.name).trim().toLowerCase() === String(this.selectedDept).trim().toLowerCase());
                                        if(!dept || !dept.programs) return [];
                                        return Array.isArray(dept.programs) ? dept.programs : Object.values(dept.programs);
                                    }
                                }" x-init="initNormalization()">
                                    
                                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fa-solid fa-graduation-cap text-slate-400"></i>
                                        <span>Academic Department & Program</span>
                                    </div>

                                    <!-- College Dropdown -->
                                    <div class="space-y-1.5">
                                        <label for="profile_college" class="block text-xs font-semibold text-slate-600">
                                            College <span class="text-rose-600">*</span>
                                        </label>
                                        <div class="relative">
                                            <select id="profile_college" name="college" x-model="selectedCollege"
                                                @change="selectedDept = ''; selectedProgram = ''" required
                                                class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all appearance-none cursor-pointer pr-10 min-h-[44px]">
                                                <option value="" disabled>Select College</option>
                                                @foreach($colleges as $college)
                                                    <option value="{{ $college->name }}">{{ $college->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-4.5">
                                        <!-- Department Dropdown -->
                                        <div class="space-y-1.5">
                                            <label for="profile_department" class="block text-xs font-semibold text-slate-600">
                                                Department
                                            </label>
                                            <div class="relative">
                                                <select id="profile_department" name="department" x-model="selectedDept" @change="selectedProgram = ''"
                                                    :disabled="!selectedCollege"
                                                    class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all appearance-none cursor-pointer pr-10 min-h-[44px] disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed">
                                                    <option value="" disabled x-text="selectedCollege ? 'Select Department' : 'Select College First'"></option>
                                                    <template x-for="dept in currentDepartments" :key="dept.id">
                                                        <option :value="dept.name" x-text="dept.name"></option>
                                                    </template>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                                    <i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Program Dropdown -->
                                        <div class="space-y-1.5">
                                            <label for="profile_program" class="block text-xs font-semibold text-slate-600">
                                                Program / Degree Course
                                            </label>
                                            <div class="relative">
                                                <select id="profile_program" name="program" x-model="selectedProgram" :disabled="!selectedDept"
                                                    class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all appearance-none cursor-pointer pr-10 min-h-[44px] disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed">
                                                    <option value="" disabled x-text="selectedDept ? 'Select Program' : 'Select Department First'"></option>
                                                    <template x-for="prog in currentPrograms" :key="prog.id">
                                                        <option :value="prog.name" x-text="prog.name"></option>
                                                    </template>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                                    <i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="pt-5 border-t border-slate-100/90 flex items-center justify-end gap-3">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#8B0000] hover:bg-[#700000] text-white rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs active:scale-[0.98] min-h-[42px] cursor-pointer focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:outline-none">
                                <i class="fa-solid fa-check text-xs" aria-hidden="true"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PANE 2: Security & Password -->
                <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    
                    <div class="px-6 sm:px-8 py-5 border-b border-slate-100/90 flex items-center justify-between gap-4 bg-slate-50/40 shrink-0">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-800 font-['Montserrat']">Security & Password</h2>
                            <p class="text-xs text-slate-500 mt-0.5 font-normal">Ensure your researcher account is secure with a strong password.</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200/60">
                            <i class="fa-solid fa-shield-halved text-sm" aria-hidden="true"></i>
                        </div>
                    </div>

                    <form action="{{ route('settings.update_password') }}" method="POST" class="p-6 sm:p-8 space-y-5 sm:space-y-6">
                        @csrf

                        <!-- Current Password -->
                        <div class="space-y-1.5">
                            <label for="current_password" class="block text-xs font-semibold text-slate-600">
                                Current Password <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showCurrentPw ? 'text' : 'password'" id="current_password" name="current_password" required
                                    placeholder="Enter current password"
                                    class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-2xs min-h-[44px] pr-10">
                                <button type="button" @click="showCurrentPw = !showCurrentPw"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                                    :aria-label="showCurrentPw ? 'Hide password' : 'Show password'">
                                    <i class="fa-solid" :class="showCurrentPw ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Passwords Grid (New + Confirm side by side on desktop) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                            <div class="space-y-1.5">
                                <label for="new_password" class="block text-xs font-semibold text-slate-600">
                                    New Password <span class="text-rose-600">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showNewPw ? 'text' : 'password'" id="new_password" name="password" required minlength="8"
                                        placeholder="At least 8 characters"
                                        class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-2xs min-h-[44px] pr-10">
                                    <button type="button" @click="showNewPw = !showNewPw"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                                        :aria-label="showNewPw ? 'Hide password' : 'Show password'">
                                        <i class="fa-solid" :class="showNewPw ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label for="new_password_confirmation" class="block text-xs font-semibold text-slate-600">
                                    Confirm New Password <span class="text-rose-600">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showConfirmPw ? 'text' : 'password'" id="new_password_confirmation" name="password_confirmation" required minlength="8"
                                        placeholder="Re-enter password"
                                        class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-2xs min-h-[44px] pr-10">
                                    <button type="button" @click="showConfirmPw = !showConfirmPw"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                                        :aria-label="showConfirmPw ? 'Hide password' : 'Show password'">
                                        <i class="fa-solid" :class="showConfirmPw ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Password Standard Specs -->
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100/90 flex items-center gap-2.5 text-xs text-slate-500">
                            <i class="fa-solid fa-circle-info text-slate-400 shrink-0" aria-hidden="true"></i>
                            <span>At least 8 characters with a combination of uppercase letters, numbers, and symbols.</span>
                        </div>

                        <!-- Form Actions -->
                        <div class="pt-5 border-t border-slate-100/90 flex items-center justify-end gap-3">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#8B0000] hover:bg-[#700000] text-white rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs active:scale-[0.98] min-h-[42px] cursor-pointer focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:outline-none">
                                <i class="fa-solid fa-key text-xs" aria-hidden="true"></i>
                                <span>Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PANE 3: Notification Alerts & Email Preferences -->
                <div x-show="activeTab === 'notifications'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    
                    <div class="px-6 sm:px-8 py-5 border-b border-slate-100/90 flex items-center justify-between gap-4 bg-slate-50/40 shrink-0">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-800 font-['Montserrat']">Notification Preferences</h2>
                            <p class="text-xs text-slate-500 mt-0.5 font-normal">Control which email notifications and protocol status updates you receive.</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200/60">
                            <i class="fa-solid fa-bell text-sm" aria-hidden="true"></i>
                        </div>
                    </div>

                    <form action="{{ route('settings.update_email_preferences') }}" method="POST" class="p-6 sm:p-8 space-y-4">
                        @csrf

                        <!-- Item 1: Submission Status -->
                        <label for="pref_submission_status" class="flex items-start justify-between gap-5 p-4.5 sm:p-5 rounded-2xl border border-slate-200/80 hover:border-slate-300 hover:bg-slate-50/50 transition-all cursor-pointer group">
                            <div class="space-y-1">
                                <div class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-[#8B0000] transition-colors flex items-center gap-2">
                                    <span>Protocol Submission & Review Status</span>
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-semibold">Recommended</span>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                                    Real-time email notifications as your protocol advances through triage, revisions, and approval.
                                </p>
                            </div>
                            <div class="relative shrink-0 pt-0.5">
                                <input type="checkbox" id="pref_submission_status" name="submission_status" class="sr-only peer" {{ ($user->email_preferences['submission_status'] ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#8B0000]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]"></div>
                            </div>
                        </label>

                        <!-- Item 2: Appointment Reminders -->
                        <label for="pref_appointment_reminders" class="flex items-start justify-between gap-5 p-4.5 sm:p-5 rounded-2xl border border-slate-200/80 hover:border-slate-300 hover:bg-slate-50/50 transition-all cursor-pointer group">
                            <div class="space-y-1">
                                <div class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-[#8B0000] transition-colors">
                                    <span>Meeting & Appointment Reminders</span>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                                    Advance email notifications for delivery deadlines, consultative sessions, and committee hearings.
                                </p>
                            </div>
                            <div class="relative shrink-0 pt-0.5">
                                <input type="checkbox" id="pref_appointment_reminders" name="appointment_reminders" class="sr-only peer" {{ ($user->email_preferences['appointment_reminders'] ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#8B0000]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]"></div>
                            </div>
                        </label>

                        <!-- Item 3: New Resources Alert -->
                        <label for="pref_new_resources" class="flex items-start justify-between gap-5 p-4.5 sm:p-5 rounded-2xl border border-slate-200/80 hover:border-slate-300 hover:bg-slate-50/50 transition-all cursor-pointer group">
                            <div class="space-y-1">
                                <div class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-[#8B0000] transition-colors">
                                    <span>Institutional Guidelines & SOP Updates</span>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed mt-1">
                                    Notices when REO updates policy guidelines, submission templates, or research ethics schedules.
                                </p>
                            </div>
                            <div class="relative shrink-0 pt-0.5">
                                <input type="checkbox" id="pref_new_resources" name="new_resources" class="sr-only peer" {{ ($user->email_preferences['new_resources'] ?? false) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#8B0000]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]"></div>
                            </div>
                        </label>

                        <!-- Form Actions -->
                        <div class="pt-5 border-t border-slate-100/90 flex items-center justify-end gap-3">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#8B0000] hover:bg-[#700000] text-white rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs active:scale-[0.98] min-h-[42px] cursor-pointer focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:outline-none">
                                <i class="fa-solid fa-check text-xs" aria-hidden="true"></i>
                                <span>Save Preferences</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PANE 4: Danger Zone -->
                <div x-show="activeTab === 'danger'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-2xl border border-rose-200 shadow-2xs overflow-hidden">
                    
                    <div class="px-6 sm:px-8 py-5 border-b border-rose-100/80 flex items-center justify-between gap-4 bg-rose-50/40 shrink-0">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 text-xs font-semibold uppercase tracking-wider mb-1">
                                Permanent Safeguard
                            </div>
                            <h2 class="text-base sm:text-lg font-bold text-rose-950 font-['Montserrat']">Danger Zone: Account Deletion</h2>
                            <p class="text-xs text-rose-800 mt-0.5 font-normal">Irreversible removal of your researcher profile and active submissions.</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-2xs">
                            <i class="fa-solid fa-triangle-exclamation text-sm" aria-hidden="true"></i>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="p-5 sm:p-6 bg-rose-50/40 rounded-2xl border border-rose-200/70 text-xs text-rose-900 leading-relaxed space-y-2.5">
                            <p class="font-semibold text-xs sm:text-sm text-rose-950">What happens when you delete your account?</p>
                            <ul class="list-disc pl-4 space-y-1.5 text-rose-800">
                                <li>All active protocol submissions will be permanently marked as <strong>Terminated</strong> in the ethics ledger.</li>
                                <li>Your uploaded protocol files, proposal packages, and reviewer remarks will be archived for audit compliance.</li>
                                <li>You will be immediately logged out and your login credentials deactivated.</li>
                            </ul>
                        </div>

                        <div class="pt-5 border-t border-rose-100/80 flex items-center justify-between gap-4">
                            <div class="text-xs text-slate-500">
                                Requires current password verification to proceed.
                            </div>
                            <button type="button" @click="showDeleteModal = true"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs active:scale-[0.98] min-h-[42px] cursor-pointer focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:outline-none">
                                <i class="fa-solid fa-trash-can text-xs" aria-hidden="true"></i>
                                <span>Delete Researcher Account</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Secure Account Deletion Modal -->
        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" @click="showDeleteModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl sm:rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-rose-100"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.stop>
                    
                    <div class="p-6 sm:p-7 text-center">
                        <div class="w-11 h-11 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3.5 ring-1 ring-rose-200">
                            <i class="fa-solid fa-triangle-exclamation text-lg" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 font-['Montserrat'] mb-1">Confirm Account Termination</h3>
                        <p class="text-xs text-slate-500 mb-5 leading-relaxed">
                            This action is <strong class="text-rose-600 font-semibold">permanent and irreversible</strong>. Enter your current password to authorize permanent deletion.
                        </p>
                        
                        <form action="{{ route('settings.delete_account') }}" method="POST" class="space-y-4 text-left">
                            @csrf
                            @method('DELETE')
                            
                            <div class="space-y-1.5">
                                <label for="modal_delete_password" class="block text-xs font-semibold text-slate-600">
                                    Current Password <span class="text-rose-600">*</span>
                                </label>
                                <input type="password" id="modal_delete_password" name="password" required
                                    placeholder="Enter your current password"
                                    class="w-full px-4 py-2.5 sm:py-3 bg-white border border-slate-200/90 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all shadow-2xs min-h-[44px]">
                            </div>

                            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100/90 mt-5">
                                <button type="button" @click="showDeleteModal = false" 
                                        class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors font-semibold text-xs sm:text-sm cursor-pointer h-10">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl transition-all shadow-xs font-semibold text-xs sm:text-sm active:scale-[0.98] min-h-[42px] cursor-pointer inline-flex items-center gap-2">
                                    <i class="fa-solid fa-trash-can text-xs" aria-hidden="true"></i>
                                    <span>Confirm Deletion</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-user_layout>
