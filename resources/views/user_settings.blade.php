<x-user_layout>
    @php $user = Auth::user(); @endphp

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-20 sm:top-24 right-4 sm:right-6 z-50 bg-emerald-50 border border-emerald-200 rounded-xl shadow-lg shadow-emerald-500/10 p-4 animate-[fadeInLeft_0.3s_ease-out]">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-600 text-lg" aria-hidden="true"></i>
                <div>
                    <p class="font-bold text-emerald-950 text-sm">Success</p>
                    <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-2 text-emerald-700 hover:text-emerald-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 rounded-lg p-1" aria-label="Dismiss notification">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div x-data="{ show: true }" x-show="show"
            class="fixed top-20 sm:top-24 right-4 sm:right-6 z-50 bg-red-50 border border-red-200 rounded-xl shadow-lg shadow-red-500/10 p-4 animate-[fadeInLeft_0.3s_ease-out]">
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-lg mt-0.5" aria-hidden="true"></i>
                <div>
                    <p class="font-bold text-red-950 text-sm">Action Failed</p>
                    <ul class="text-xs text-red-800 list-disc pl-4 mt-1 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="ml-auto text-red-700 hover:text-red-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 rounded-lg p-1" aria-label="Dismiss error">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="max-w-5xl mx-auto pt-2 pb-28 sm:py-12 px-4 sm:px-6 animate-[fadeInUp_0.5s_ease-out] relative">

        <!-- Page Header -->
        <div class="mb-8 md:mb-10 border-b border-slate-200/80 pt-2 pb-5 md:pb-6">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 font-heading tracking-tight">Account Settings</h1>
            <p class="text-slate-500 mt-1.5 text-sm md:text-base">Manage your institutional researcher profile, security credentials, and email notification preferences.</p>
        </div>

        <!-- MOBILE VIEW: 4-Square Settings Grid (No Scroll Dashboard) -->
        <div class="grid grid-cols-2 gap-3.5 md:hidden">

            <!-- Square 1: Personal Profile -->
            <button type="button" onclick="openModal('profile-modal')"
                class="group bg-white p-4 rounded-2xl shadow-xs border border-slate-200/90 active:scale-[0.97] transition-all duration-150 flex flex-col justify-between text-left hover:border-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary min-h-[148px]">
                <div class="flex items-center justify-between w-full">
                    <div class="w-11 h-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center ring-1 ring-blue-100 shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-user text-base" aria-hidden="true"></i>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-slate-500 group-hover:translate-x-0.5 transition-all" aria-hidden="true"></i>
                </div>
                <div class="mt-3">
                    <h3 class="font-bold text-slate-900 text-sm font-heading leading-tight">Profile</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Details & affiliation</p>
                </div>
            </button>

            <!-- Square 2: Security & Access -->
            <button type="button" onclick="openModal('security-modal')"
                class="group bg-white p-4 rounded-2xl shadow-xs border border-slate-200/90 active:scale-[0.97] transition-all duration-150 flex flex-col justify-between text-left hover:border-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary min-h-[148px]">
                <div class="flex items-center justify-between w-full">
                    <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center ring-1 ring-emerald-100 shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-shield-halved text-base" aria-hidden="true"></i>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-slate-500 group-hover:translate-x-0.5 transition-all" aria-hidden="true"></i>
                </div>
                <div class="mt-3">
                    <h3 class="font-bold text-slate-900 text-sm font-heading leading-tight">Security</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Password & access</p>
                </div>
            </button>

            <!-- Square 3: Notification Alerts -->
            <button type="button" onclick="openModal('notifications-modal')"
                class="group bg-white p-4 rounded-2xl shadow-xs border border-slate-200/90 active:scale-[0.97] transition-all duration-150 flex flex-col justify-between text-left hover:border-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary min-h-[148px]">
                <div class="flex items-center justify-between w-full">
                    <div class="w-11 h-11 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center ring-1 ring-amber-100 shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-bell text-base" aria-hidden="true"></i>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-slate-500 group-hover:translate-x-0.5 transition-all" aria-hidden="true"></i>
                </div>
                <div class="mt-3">
                    <h3 class="font-bold text-slate-900 text-sm font-heading leading-tight">Notifications</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Email alerts & updates</p>
                </div>
            </button>

            <!-- Square 4: Danger Zone -->
            <button type="button" onclick="openModal('delete-account-modal')"
                class="group bg-red-50/50 p-4 rounded-2xl shadow-xs border border-red-200/80 active:scale-[0.97] transition-all duration-150 flex flex-col justify-between text-left hover:bg-red-50/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 min-h-[148px]">
                <div class="flex items-center justify-between w-full">
                    <div class="w-11 h-11 bg-red-100 text-red-600 rounded-xl flex items-center justify-center ring-1 ring-red-200/80 shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-triangle-exclamation text-base" aria-hidden="true"></i>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-red-300 group-hover:text-red-500 group-hover:translate-x-0.5 transition-all" aria-hidden="true"></i>
                </div>
                <div class="mt-3">
                    <h3 class="font-bold text-red-950 text-sm font-heading leading-tight">Danger Zone</h3>
                    <p class="text-[11px] text-red-800/80 mt-0.5 leading-snug">Delete account</p>
                </div>
            </button>

        </div>

        <!-- DESKTOP VIEW: 3-Column Settings Cards Grid + Danger Zone Banner -->
        <div class="hidden md:block">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 items-stretch">

                <!-- Personal Profile Card -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/90 hover:border-slate-300 hover:shadow-md transition-all duration-200 group flex flex-col justify-between h-full">
                    <div class="flex-1 flex flex-col">
                        <div class="mb-6">
                            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm ring-1 ring-blue-100">
                                <i class="fas fa-user text-2xl" aria-hidden="true"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900 font-heading">Personal Profile</h3>
                        <p class="text-sm text-slate-500 mt-2 mb-6 leading-relaxed flex-1">Update your personal details, academic affiliation, and institutional contact records.</p>
                    </div>
                    <button type="button" onclick="openModal('profile-modal')"
                        class="group/btn w-full py-3 min-h-[44px] rounded-xl border border-slate-200 bg-slate-50/70 text-slate-800 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.98] shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary mt-auto">
                        <span>Edit Profile</span>
                        <i class="fas fa-arrow-right text-xs text-slate-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all duration-200" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Security Card -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/90 hover:border-slate-300 hover:shadow-md transition-all duration-200 group flex flex-col justify-between h-full">
                    <div class="flex-1 flex flex-col">
                        <div class="mb-6">
                            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-sm ring-1 ring-emerald-100">
                                <i class="fas fa-shield-halved text-2xl" aria-hidden="true"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900 font-heading">Security & Access</h3>
                        <p class="text-sm text-slate-500 mt-2 mb-6 leading-relaxed flex-1">Protect your account credentials and maintain secure access by updating your password regularly.</p>
                    </div>
                    <button type="button" onclick="openModal('security-modal')"
                        class="group/btn w-full py-3 min-h-[44px] rounded-xl border border-slate-200 bg-slate-50/70 text-slate-800 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.98] shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary mt-auto">
                        <span>Update Password</span>
                        <i class="fas fa-arrow-right text-xs text-slate-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all duration-200" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Notifications Card -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/90 hover:border-slate-300 hover:shadow-md transition-all duration-200 group flex flex-col justify-between h-full">
                    <div class="flex-1 flex flex-col">
                        <div class="mb-6">
                            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm ring-1 ring-amber-100">
                                <i class="fas fa-bell text-2xl" aria-hidden="true"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900 font-heading">Notification Alerts</h3>
                        <p class="text-sm text-slate-500 mt-2 mb-6 leading-relaxed flex-1">Control how and when you receive critical email notifications about your ethics submissions.</p>
                    </div>
                    <button type="button" onclick="openModal('notifications-modal')"
                        class="group/btn w-full py-3 min-h-[44px] rounded-xl border border-slate-200 bg-slate-50/70 text-slate-800 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.98] shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary mt-auto">
                        <span>Configure Alerts</span>
                        <i class="fas fa-arrow-right text-xs text-slate-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all duration-200" aria-hidden="true"></i>
                    </button>
                </div>

            </div>

            <!-- Danger Zone (High-Stakes Administrative Action) -->
            <div class="mt-10 sm:mt-14 p-6 sm:p-8 bg-gradient-to-r from-red-50/90 via-red-50/50 to-white rounded-2xl sm:rounded-3xl border border-red-200/90 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 shadow-sm">
                <div class="flex items-start gap-4 sm:gap-5">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center shrink-0 shadow-inner ring-1 ring-red-200/50">
                        <i class="fas fa-triangle-exclamation text-xl sm:text-2xl" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-700 border border-red-200 shadow-xs">Irreversible Action</span>
                        </div>
                        <h4 class="text-red-950 font-extrabold text-lg sm:text-xl font-heading">Danger Zone</h4>
                        <p class="text-red-800 text-sm mt-1 leading-relaxed max-w-2xl">Permanently delete your researcher account and all associated submission records, uploaded protocol documents, and appointment history. This cannot be undone.</p>
                    </div>
                </div>
                <button type="button" onclick="openModal('delete-account-modal')"
                    class="w-full sm:w-auto px-6 py-3 min-h-[44px] bg-red-600 text-white font-bold text-sm rounded-xl hover:bg-red-700 transition-all shadow-md shadow-red-600/20 whitespace-nowrap active:scale-[0.98] shrink-0 inline-flex items-center justify-center gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                    <i class="fas fa-trash-can text-xs" aria-hidden="true"></i>
                    <span>Delete Account</span>
                </button>
            </div>
        </div>

    </div>

    <!-- ==================================================== -->
    <!-- MODAL 1: DELETE ACCOUNT MODAL -->
    <!-- ==================================================== -->
    <div id="delete-account-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="delete-account-title"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6 bg-slate-900/60 backdrop-blur-sm transition-opacity overflow-y-auto"
        onclick="if(event.target === this) closeModal('delete-account-modal')">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md my-auto max-h-[88vh] flex flex-col overflow-hidden animate-[scaleIn_0.2s_ease-out] relative z-[10000] border border-red-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-red-950/60 p-4 md:p-5 flex justify-between items-center shrink-0 flex-none border-b border-slate-800">
                <h3 id="delete-account-title" class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation text-red-500" aria-hidden="true"></i>
                    <span>Confirm Deletion</span>
                </h3>
                <button type="button" onclick="closeModal('delete-account-modal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800/80 text-white hover:bg-slate-700 transition-colors active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                    aria-label="Close dialog">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Form with Scrollable Body and Locked Sticky Footer -->
            <form action="{{ route('settings.delete_account') }}" method="POST" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                @csrf
                @method('DELETE')

                <div class="p-6 md:p-8 overflow-y-auto [scrollbar-width:thin] flex-1">
                    <p id="delete-help" class="text-slate-600 text-sm mb-6 leading-relaxed">
                        Are you sure you want to delete your account? This action is <strong class="text-red-600 font-bold">irreversible</strong>. All your research protocols, files, and appointments will be permanently removed.
                    </p>

                    <div>
                        <label for="delete_password" class="block text-xs font-bold text-slate-700 uppercase mb-2">Enter Password to Confirm</label>
                        <input type="password" id="delete_password" name="password" required aria-describedby="delete-help"
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition-all placeholder:text-slate-400"
                            placeholder="Your current password">
                        @error('password', 'delete')
                            <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="p-4 sm:p-5 bg-slate-50/90 border-t border-slate-200/80 flex justify-end gap-3 shrink-0 flex-none">
                    <button type="button" onclick="closeModal('delete-account-modal')"
                        class="px-5 py-2.5 min-h-[44px] rounded-xl text-slate-600 font-bold hover:bg-slate-200/60 transition-colors active:scale-95 inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 min-h-[44px] rounded-xl bg-red-600 text-white font-bold shadow-md shadow-red-600/20 hover:bg-red-700 transition-all active:scale-95 inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                        Yes, Delete My Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================================================== -->
    <!-- MODAL 2: PERSONAL PROFILE MODAL -->
    <!-- ==================================================== -->
    <div id="profile-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="profile-modal-title"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6 bg-slate-900/60 backdrop-blur-sm transition-opacity overflow-y-auto"
        onclick="if(event.target === this) closeModal('profile-modal')">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-xl my-auto max-h-[88vh] flex flex-col overflow-hidden animate-[scaleIn_0.2s_ease-out] relative z-[10000]">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-blue-950/60 p-4 md:p-5 flex justify-between items-center border-b border-slate-800 shrink-0 flex-none">
                <h3 id="profile-modal-title" class="text-white font-bold text-lg flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                        <i class="fas fa-user-pen text-sm" aria-hidden="true"></i>
                    </div>
                    <span>Edit Profile</span>
                </h3>
                <button type="button" onclick="closeModal('profile-modal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800/80 text-white hover:bg-slate-700 transition-colors active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                    aria-label="Close dialog">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Form with Scrollable Body and Locked Sticky Footer -->
            <form action="{{ route('settings.update_profile') }}" method="POST" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                @csrf

                <div class="p-5 md:p-7 space-y-4 md:space-y-5 overflow-y-auto [scrollbar-width:thin] flex-1">
                    <h4 class="text-xs font-bold text-brand-primary uppercase tracking-wider border-b border-slate-100 pb-2 mb-3 mt-1 flex items-center gap-2">
                        <i class="fas fa-id-card text-[11px] text-brand-primary" aria-hidden="true"></i>
                        <span>Personal Information</span>
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label for="profile_first_name" class="block text-xs font-bold text-slate-700 uppercase mb-1">First Name</label>
                            <input type="text" id="profile_first_name" name="first_name" value="{{ Auth::user()->first_name }}" required
                                class="w-full p-2.5 md:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all">
                        </div>
                        <div>
                            <label for="profile_middle_name" class="block text-xs font-bold text-slate-700 uppercase mb-1">Middle Name</label>
                            <input type="text" id="profile_middle_name" name="middle_name" value="{{ Auth::user()->middle_name }}"
                                class="w-full p-2.5 md:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all">
                        </div>
                        <div>
                            <label for="profile_last_name" class="block text-xs font-bold text-slate-700 uppercase mb-1">Last Name</label>
                            <input type="text" id="profile_last_name" name="last_name" value="{{ Auth::user()->last_name }}" required
                                class="w-full p-2.5 md:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all">
                        </div>
                    </div>

                    <h4 class="text-xs font-bold text-brand-primary uppercase tracking-wider border-b border-slate-100 pb-2 mb-3 mt-5 flex items-center gap-2">
                        <i class="fas fa-envelope text-[11px] text-brand-primary" aria-hidden="true"></i>
                        <span>Contact Information</span>
                    </h4>
                    <div>
                        <label for="profile_email" class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                        <input type="email" id="profile_email" value="{{ Auth::user()->email }}" disabled aria-describedby="email-help"
                            class="w-full p-2.5 md:p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                        <p id="email-help" class="text-xs text-slate-500 mt-1">Contact the REO Administrator to update your official email address.</p>
                    </div>

                    @if(Auth::user()->researcher && Auth::user()->researcher->external_user)
                        <h4 class="text-xs font-bold text-brand-primary uppercase tracking-wider border-b border-slate-100 pb-2 mb-3 mt-5 flex items-center gap-2">
                            <i class="fas fa-building-columns text-[11px] text-brand-primary" aria-hidden="true"></i>
                            <span>Institutional Affiliation</span>
                        </h4>
                        <div>
                            <label for="profile_institute" class="block text-xs font-bold text-slate-700 uppercase mb-1">Institution / Agency</label>
                            <input type="text" id="profile_institute" name="institute" value="{{ Auth::user()->researcher->institute }}"
                                class="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all">
                        </div>
                    @elseif(Auth::user()->researcher)
                        <div class="mt-5 pt-2 bg-slate-50/80 rounded-xl border border-slate-200 p-4 sm:p-5" x-data="{ 
                            colleges: {{ Js::from($colleges) }},
                            selectedCollege: '{{ Auth::user()->researcher->college ?? '' }}',
                            selectedDept: '{{ Auth::user()->researcher->department ?? '' }}',
                            selectedProgram: '{{ Auth::user()->researcher->program ?? '' }}',

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
                            <h4 class="text-xs font-bold text-brand-primary uppercase tracking-wider border-b border-slate-200 pb-2 mb-3 flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-[11px] text-brand-primary" aria-hidden="true"></i>
                                <span>Academic Affiliation</span>
                            </h4>
                            <div class="space-y-3.5">
                                <div>
                                    <label for="profile_college" class="block text-xs font-bold text-slate-700 uppercase mb-1">College</label>
                                    <div class="relative">
                                        <select id="profile_college" name="college" x-model="selectedCollege"
                                            @change="selectedDept = ''; selectedProgram = ''"
                                            class="w-full p-2.5 sm:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none appearance-none cursor-pointer hover:shadow-sm transition-shadow">
                                            <option value="" disabled>Select College</option>
                                            @foreach($colleges as $college)
                                                <option value="{{ $college->name }}">{{ $college->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                            <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <div>
                                        <label for="profile_department" class="block text-xs font-bold text-slate-700 uppercase mb-1">Department</label>
                                        <div class="relative">
                                            <select id="profile_department" name="department" x-model="selectedDept" @change="selectedProgram = ''"
                                                :disabled="!selectedCollege"
                                                class="w-full p-2.5 sm:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none appearance-none cursor-pointer hover:shadow-sm transition-shadow disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed">
                                                <option value="" disabled x-text="selectedCollege ? 'Select Department' : 'Select College First'"></option>
                                                <template x-for="dept in currentDepartments" :key="dept.id">
                                                    <option :value="dept.name" x-text="dept.name"></option>
                                                </template>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                                <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="profile_program" class="block text-xs font-bold text-slate-700 uppercase mb-1">Program</label>
                                        <div class="relative">
                                            <select id="profile_program" name="program" x-model="selectedProgram" :disabled="!selectedDept"
                                                class="w-full p-2.5 sm:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none appearance-none cursor-pointer hover:shadow-sm transition-shadow disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed">
                                                <option value="" disabled x-text="selectedDept ? 'Select Program' : 'Select Department First'"></option>
                                                <template x-for="prog in currentPrograms" :key="prog.id">
                                                    <option :value="prog.name" x-text="prog.name"></option>
                                                </template>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                                <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-4 sm:p-5 bg-slate-50/90 border-t border-slate-200/80 flex justify-end gap-3 shrink-0 flex-none">
                    <button type="button" onclick="closeModal('profile-modal')"
                        class="px-5 py-2.5 min-h-[44px] rounded-xl text-slate-600 font-bold hover:bg-slate-200/60 transition-colors active:scale-95 inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 min-h-[44px] rounded-xl bg-brand-primary text-white font-bold shadow-md shadow-brand-primary/20 hover:bg-brand-secondary transition-all active:scale-95 inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================================================== -->
    <!-- MODAL 3: SECURITY MODAL (PASSWORD UPDATE) -->
    <!-- ==================================================== -->
    <div id="security-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="security-modal-title"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6 bg-slate-900/60 backdrop-blur-sm transition-opacity overflow-y-auto"
        onclick="if(event.target === this) closeModal('security-modal')">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md my-auto max-h-[88vh] flex flex-col overflow-hidden animate-[scaleIn_0.2s_ease-out] relative z-[10000]">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-emerald-950/60 p-4 md:p-5 flex justify-between items-center border-b border-slate-800 shrink-0 flex-none">
                <h3 id="security-modal-title" class="text-white font-bold text-lg flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <i class="fas fa-shield-halved text-sm" aria-hidden="true"></i>
                    </div>
                    <span>Update Password</span>
                </h3>
                <button type="button" onclick="closeModal('security-modal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800/80 text-white hover:bg-slate-700 transition-colors active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                    aria-label="Close dialog">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Form with Scrollable Body and Locked Sticky Footer -->
            <form action="{{ route('settings.update_password') }}" method="POST" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                @csrf
                <div class="p-6 md:p-8 space-y-4 sm:space-y-5 overflow-y-auto [scrollbar-width:thin] flex-1">
                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase mb-1">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all placeholder:text-slate-400"
                            placeholder="••••••••">
                    </div>
                    <div>
                        <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase mb-1">New Password</label>
                        <input type="password" id="new_password" name="password" required aria-describedby="password-min-length"
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all placeholder:text-slate-400"
                            placeholder="Minimum 8 characters">
                        <p id="password-min-length" class="text-xs text-slate-500 mt-1">Must be at least 8 characters in length.</p>
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm New Password</label>
                        <input type="password" id="new_password_confirmation" name="password_confirmation" required
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none transition-all placeholder:text-slate-400"
                            placeholder="Re-enter new password">
                    </div>
                </div>

                <div class="p-4 sm:p-5 bg-slate-50/90 border-t border-slate-200/80 flex justify-end gap-3 shrink-0 flex-none">
                    <button type="button" onclick="closeModal('security-modal')"
                        class="px-5 py-2.5 min-h-[44px] rounded-xl text-slate-600 font-bold hover:bg-slate-200/60 transition-colors active:scale-95 inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 min-h-[44px] rounded-xl bg-brand-primary text-white font-bold shadow-md shadow-brand-primary/20 hover:bg-brand-secondary transition-all active:scale-95 inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================================================== -->
    <!-- MODAL 4: NOTIFICATIONS MODAL (EMAIL PREFERENCES) -->
    <!-- ==================================================== -->
    <div id="notifications-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="notifications-modal-title"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6 bg-slate-900/60 backdrop-blur-sm transition-opacity overflow-y-auto"
        onclick="if(event.target === this) closeModal('notifications-modal')">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md my-auto max-h-[88vh] flex flex-col overflow-hidden animate-[scaleIn_0.2s_ease-out] relative z-[10000]">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-amber-950/60 p-4 md:p-5 flex justify-between items-center border-b border-slate-800 shrink-0 flex-none">
                <h3 id="notifications-modal-title" class="text-white font-bold text-lg flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center">
                        <i class="fas fa-envelope-open-text text-sm" aria-hidden="true"></i>
                    </div>
                    <span>Email Preferences</span>
                </h3>
                <button type="button" onclick="closeModal('notifications-modal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800/80 text-white hover:bg-slate-700 transition-colors active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                    aria-label="Close dialog">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Form with Scrollable Body and Locked Sticky Footer -->
            <form action="{{ route('settings.update_email_preferences') }}" method="POST" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                @csrf
                <div class="p-6 md:p-8 space-y-4 overflow-y-auto [scrollbar-width:thin] flex-1">
                    <!-- Submission Status -->
                    <label for="pref_submission_status" class="flex items-center justify-between cursor-pointer group p-3.5 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200/60">
                        <div class="pr-3">
                            <span class="font-bold text-slate-800 block text-sm group-hover:text-brand-primary transition-colors">Submission Status</span>
                            <span class="text-xs text-slate-500 mt-0.5 block">Get notified when your protocol status changes.</span>
                        </div>
                        <div class="relative shrink-0">
                            <input type="checkbox" id="pref_submission_status" name="submission_status" class="sr-only peer" {{ ($user->email_preferences['submission_status'] ?? true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-primary/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-primary"></div>
                        </div>
                    </label>

                    <!-- Appointment Reminders -->
                    <label for="pref_appointment_reminders" class="flex items-center justify-between cursor-pointer group p-3.5 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200/60">
                        <div class="pr-3">
                            <span class="font-bold text-slate-800 block text-sm group-hover:text-brand-primary transition-colors">Appointment Reminders</span>
                            <span class="text-xs text-slate-500 mt-0.5 block">Receive alerts before scheduled meetings.</span>
                        </div>
                        <div class="relative shrink-0">
                            <input type="checkbox" id="pref_appointment_reminders" name="appointment_reminders" class="sr-only peer" {{ ($user->email_preferences['appointment_reminders'] ?? true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-primary/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-primary"></div>
                        </div>
                    </label>

                    <!-- New Resources Alert -->
                    <label for="pref_new_resources" class="flex items-center justify-between cursor-pointer group p-3.5 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-200/60">
                        <div class="pr-3">
                            <span class="font-bold text-slate-800 block text-sm group-hover:text-brand-primary transition-colors">New Resources Alert</span>
                            <span class="text-xs text-slate-500 mt-0.5 block">Be notified when new templates or guidelines are added.</span>
                        </div>
                        <div class="relative shrink-0">
                            <input type="checkbox" id="pref_new_resources" name="new_resources" class="sr-only peer" {{ ($user->email_preferences['new_resources'] ?? false) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-primary/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-primary"></div>
                        </div>
                    </label>
                </div>

                <div class="p-4 sm:p-5 bg-slate-50/90 border-t border-slate-200/80 flex justify-end gap-3 shrink-0 flex-none">
                    <button type="button" onclick="closeModal('notifications-modal')"
                        class="px-5 py-2.5 min-h-[44px] rounded-xl text-slate-600 font-bold hover:bg-slate-200/60 transition-colors active:scale-95 inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 min-h-[44px] rounded-xl bg-brand-primary text-white font-bold shadow-md shadow-brand-primary/20 hover:bg-brand-secondary transition-all active:scale-95 inline-flex items-center justify-center focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary">
                        Save Preferences
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Accessible Modal Focus-Trap & Keyboard Controller -->
    <script>
        let lastFocusedElement = null;

        function openModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            lastFocusedElement = document.activeElement;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Auto-focus first actionable input or button
            const focusables = modal.querySelectorAll('input:not([disabled]), select:not([disabled]), button:not([disabled]), textarea:not([disabled])');
            if (focusables.length > 0) {
                setTimeout(() => focusables[0].focus(), 60);
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.add('hidden');
            
            // Only restore scroll if all modals are closed
            const openModals = document.querySelectorAll('[id$="-modal"]:not(.hidden)');
            if (openModals.length === 0) {
                document.body.classList.remove('overflow-hidden');
            }
            
            // Return focus to triggering element
            if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                lastFocusedElement.focus();
            }
        }

        function closeAllModals() {
            document.querySelectorAll('[id$="-modal"]').forEach(modal => {
                modal.classList.add('hidden');
            });
            document.body.classList.remove('overflow-hidden');
            if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                lastFocusedElement.focus();
            }
        }

        // Global Modal Keyboard Handling: Esc dismissal and Tab Focus Trap
        document.addEventListener('keydown', function(e) {
            const activeModal = document.querySelector('[id$="-modal"]:not(.hidden)');
            if (!activeModal) return;

            if (e.key === 'Escape') {
                closeModal(activeModal.id);
                return;
            }

            if (e.key === 'Tab') {
                const focusables = Array.from(activeModal.querySelectorAll('input:not([disabled]), select:not([disabled]), button:not([disabled]), textarea:not([disabled])'));
                if (focusables.length === 0) return;

                const first = focusables[0];
                const last = focusables[focusables.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === first) {
                        e.preventDefault();
                        last.focus();
                    }
                } else {
                    if (document.activeElement === last) {
                        e.preventDefault();
                        first.focus();
                    }
                }
            }
        });
    </script>
</x-user_layout>
