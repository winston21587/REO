<x-user_layout>
    <x-skeleton-loader />
    
    <div id="page-content" style="display: none;">
    @php $user = Auth::user(); @endphp
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-24 right-6 z-50 bg-white border-l-4 border-green-500 rounded-lg shadow-lg p-4 animate-[fadeInLeft_0.3s_ease-out]">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <div>
                    <p class="font-bold text-slate-800 text-sm">Success</p>
                    <p class="text-xs text-slate-500">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div x-data="{ show: true }" x-show="show"
            class="fixed top-24 right-6 z-50 bg-white border-l-4 border-red-500 rounded-lg shadow-lg p-4 animate-[fadeInLeft_0.3s_ease-out]">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <div>
                    <p class="font-bold text-slate-800 text-sm">Action Failed</p>
                    <ul class="text-xs text-slate-500 list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="ml-auto text-slate-400 hover:text-slate-600"><i
                        class="fas fa-times"></i></button>
            </div>
        </div>
    @endif

    <div class="max-w-5xl mx-auto animate-[fadeInUp_0.5s_ease-out]">
        <div class="mb-6 md:mb-10 border-b border-slate-200 pt-3 pb-4 md:pb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 font-heading">Account Settings</h1>
            <p class="text-slate-500 mt-2 text-sm md:text-base">Manage your profile information, security, and
                preferences.</p>
        </div>

        <!-- MOBILE VIEW (List Style) -->
        <div class="md:hidden space-y-6">

            <!-- Personal Profile Card (Mobile) -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                        <span class="material-symbols-outlined text-3xl">person</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-900 text-lg truncate">{{ $user->first_name }}
                            {{ $user->last_name }}
                        </h3>
                        <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                        @if($user->researcher && $user->researcher->college)
                            <p class="text-[10px] text-blue-600 font-medium mt-1 truncate">
                                {{ $user->researcher->college }}
                            </p>
                        @endif
                    </div>
                    <button onclick="openModal('profile-modal')"
                        class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition-colors">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>
            </div>

            <!-- Settings Group (Mobile) -->
            <div
                class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden divide-y divide-slate-100">

                <!-- Security -->
                <button onclick="openModal('security-modal')"
                    class="w-full p-4 flex items-center gap-4 hover:bg-slate-50 transition-colors group text-left">
                    <div
                        class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl">lock</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-800 text-sm">Security</h4>
                        <p class="text-[11px] text-slate-500">Update password</p>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 text-sm"></i>
                </button>

                <!-- Notifications -->
                <button onclick="openModal('notifications-modal')"
                    class="w-full p-4 flex items-center gap-4 hover:bg-slate-50 transition-colors group text-left">
                    <div
                        class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-800 text-sm">Notifications</h4>
                        <p class="text-[11px] text-slate-500">Email preferences</p>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 text-sm"></i>
                </button>

            </div>

            <!-- Danger Zone (Mobile) -->
            <div class="p-5 rounded-2xl border border-red-200 bg-red-50/50">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                    <h4 class="font-bold text-red-800 text-sm">Danger Zone</h4>
                </div>
                <p class="text-xs text-red-600/80 mb-4 leading-relaxed">Permanently delete your account and all data.
                    Verified action required.</p>
                <button onclick="openModal('delete-account-modal')"
                    class="w-full py-3 bg-white border border-red-200 text-red-600 font-bold text-sm rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                    Delete Account
                </button>
            </div>

        </div>

        <!-- DESKTOP VIEW (Original Grid) -->
        <!-- DESKTOP VIEW (Original Grid) -->
        <div class="hidden md:block">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:border-brand-primary/30 hover:shadow-lg transition-all group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-outlined text-8xl text-brand-primary">person</span>
                    </div>
                    <div
                        class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">person</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Personal Profile</h3>
                    <p class="text-sm text-slate-500 mt-2 mb-8 leading-relaxed">Update your personal details, academic
                        affiliation, and contact information.</p>
                    <button onclick="openModal('profile-modal')"
                        class="w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all flex items-center justify-center gap-2">
                        Edit Profile <i class="fas fa-pen text-xs"></i>
                    </button>
                </div>

                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:border-brand-primary/30 hover:shadow-lg transition-all group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <span class="material-symbols-outlined text-8xl text-brand-primary">lock</span>
                    </div>
                    <div
                        class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">lock_reset</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Security</h3>
                    <p class="text-sm text-slate-500 mt-2 mb-8 leading-relaxed">Ensure your account stays safe by
                        updating
                        your password regularly.</p>
                    <button onclick="openModal('security-modal')"
                        class="w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all flex items-center justify-center gap-2">
                        Update Password <i class="fas fa-key text-xs"></i>
                    </button>
                </div>

                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:border-brand-primary/30 hover:shadow-lg transition-all group relative overflow-hidden">
                    <div
                        class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">notifications_active</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Notifications</h3>
                    <p class="text-sm text-slate-500 mt-2 mb-8 leading-relaxed">Control how you receive updates about
                        your
                        research submissions.</p>
                    <button onclick="openModal('notifications-modal')"
                        class="w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all flex items-center justify-center gap-2">
                        Configure Alerts <i class="fas fa-cog text-xs"></i>
                    </button>
                </div>
            </div>
        </div> <!-- End Desktop Grid -->

        <!-- Custom CSS for Modal Positioning (Bypasses Tailwind Build) -->
        <style>
            .custom-modal-spacing {
                padding-top: 7rem !important;
                /* pt-28 equivalent */
                align-items: flex-start !important;
            }

            @media (min-width: 640px) {

                .custom-modal-spacing,
                .profile-modal-spacing {
                    padding-top: 1rem !important;
                    /* sm:pt-4 */
                    align-items: center !important;
                    /* sm:items-center */
                }
            }

            /* Profile Modal Specific (Adjust this number to move just the profile modal) */
            .profile-modal-spacing {
                padding-top: 5rem !important;
                /* pt-28 equivalent */
                align-items: flex-start !important;
            }
        </style>

        <!-- Desktop Danger Zone (Hidden on Mobile) -->
        <div
            class="hidden md:flex mt-12 p-8 bg-red-50 rounded-2xl border border-red-100 flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h4 class="text-red-800 font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> Danger Zone
                </h4>
                <p class="text-red-600/70 text-sm mt-1">Permanently delete your account and all associated research
                    data. This cannot be undone.</p>
            </div>
            <button onclick="openModal('delete-account-modal')"
                class="px-6 py-3 bg-white border border-red-200 text-red-600 font-bold text-sm rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm whitespace-nowrap">
                Delete Account
            </button>
        </div>
    </div>

    <div id="delete-account-modal"
        class="custom-modal-spacing hidden fixed inset-0 z-[9999] flex justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[80vh] flex flex-col overflow-hidden animate-[scaleIn_0.2s_ease-out] relative z-[10000]">
            <div class="bg-slate-900 p-4 md:p-6 flex justify-between items-center shrink-0 flex-none">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-white"></i> Confirm Deletion
                </h3>
                <button onclick="closeModal('delete-account-modal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-white hover:bg-slate-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar">
                <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                    Are you sure you want to delete your account? This action is <strong
                        class="text-red-600">irreversible</strong>. All your research protocols, files, and appointments
                    will be permanently removed.
                </p>

                <form action="{{ route('settings.delete_account') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Enter Password to
                            Confirm</label>
                        <input type="password" name="password" required
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all"
                            placeholder="Your password">
                        @error('password', 'delete')
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('delete-account-modal')"
                            class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-red-600 text-white font-bold shadow-lg hover:bg-red-700 transition-all">
                            Yes, Delete My Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="profile-modal"
        class="profile-modal-spacing hidden fixed inset-0 z-[9999] flex justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[80vh] flex flex-col overflow-hidden animate-[scaleIn_0.2s_ease-out] relative z-[10000]">
            <div
                class="bg-slate-900 p-4 md:p-6 flex justify-between items-center border-b border-slate-800 shrink-0 flex-none">
                <h3 class="text-white font-bold text-lg">Edit Profile</h3>
                <button onclick="closeModal('profile-modal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-white hover:bg-slate-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('settings.update_profile') }}" method="POST"
                class="p-5 md:p-8 space-y-4 md:space-y-5 overflow-y-auto custom-scrollbar">
                @csrf
                <h3
                    class="text-xs font-bold text-[#8B0000] uppercase tracking-wider border-b border-slate-200 pb-2 mb-3 md:mb-4 mt-2">
                    Personal Information</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-5">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ Auth::user()->first_name }}"
                            class="w-full p-2.5 md:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] focus:ring-1 focus:ring-[#8B0000] outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ Auth::user()->middle_name }}"
                            class="w-full p-2.5 md:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] focus:ring-1 focus:ring-[#8B0000] outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ Auth::user()->last_name }}"
                            class="w-full p-2.5 md:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] focus:ring-1 focus:ring-[#8B0000] outline-none transition-all">
                    </div>
                </div>

                <h3
                    class="text-xs font-bold text-[#8B0000] uppercase tracking-wider border-b border-slate-200 pb-2 mb-4 mt-6">
                    Contact & Security</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email Address</label>
                        <input type="email" value="{{ Auth::user()->email }}" disabled
                            class="w-full p-2.5 md:p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                        <p class="text-[10px] text-slate-400 mt-1">Contact admin to change email.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Contact No.</label>
                        <input type="text" name="contact" value="{{ Auth::user()->researcher->contact ?? '' }}"
                            maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                            class="w-full p-2.5 md:p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] focus:ring-1 focus:ring-[#8B0000] outline-none transition-all"
                            placeholder="09123456789">
                    </div>
                </div>

                @if(Auth::user()->researcher && Auth::user()->researcher->external_user)
                    <h3
                        class="text-xs font-bold text-[#8B0000] uppercase tracking-wider border-b border-slate-200 pb-2 mb-4 mt-6">
                        Affiliation</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Institution / Agency</label>
                        <input type="text" name="institute" value="{{ Auth::user()->researcher->institute }}"
                            class="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] focus:ring-1 focus:ring-[#8B0000] outline-none transition-all">
                    </div>
                @elseif(Auth::user()->researcher)

                    <div class="mt-6 pt-2 bg-slate-50 rounded-xl border border-slate-200 p-5" x-data="{ 
                                                                                    colleges: {{ Js::from($colleges) }},
                                                                                    selectedCollege: '{{ Auth::user()->researcher->college ?? '' }}',
                                                                                    selectedDept: '{{ Auth::user()->researcher->department ?? '' }}',
                                                                                    selectedProgram: '{{ Auth::user()->researcher->program ?? '' }}',

                                                                                    get currentDepartments() {
                                                                                        const college = this.colleges.find(c => c.name === this.selectedCollege);
                                                                                        return college ? college.departments : [];
                                                                                    },

                                                                                    get currentPrograms() {
                                                                                        const dept = this.currentDepartments.find(d => d.name === this.selectedDept);
                                                                                        return dept ? dept.programs : [];
                                                                                    }
                                                                                }">
                        <h3
                            class="text-xs font-bold text-[#8B0000] uppercase tracking-wider border-b border-slate-200 pb-2 mb-4">
                            Academic Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">College</label>
                                <div class="relative">
                                    <select name="college" x-model="selectedCollege"
                                        @change="selectedDept = ''; selectedProgram = ''"
                                        class="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] focus:ring-1 focus:ring-[#8B0000] outline-none appearance-none cursor-pointer hover:shadow-sm transition-shadow">
                                        <option value="" disabled>Select College</option>
                                        <template x-for="college in colleges" :key="college.id">
                                            <option :value="college.name" x-text="college.name"></option>
                                        </template>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Department</label>
                                    <div class="relative">
                                        <select name="department" x-model="selectedDept" @change="selectedProgram = ''"
                                            :disabled="!selectedCollege"
                                            class="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] focus:ring-1 focus:ring-[#8B0000] outline-none appearance-none cursor-pointer hover:shadow-sm transition-shadow disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed">
                                            <option value="" disabled
                                                x-text="selectedCollege ? 'Select Department' : 'Select College First'">
                                            </option>
                                            <template x-for="dept in currentDepartments" :key="dept.id">
                                                <option :value="dept.name" x-text="dept.name"></option>
                                            </template>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Program</label>
                                    <div class="relative">
                                        <select name="program" x-model="selectedProgram" :disabled="!selectedDept"
                                            class="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] focus:ring-1 focus:ring-[#8B0000] outline-none appearance-none cursor-pointer hover:shadow-sm transition-shadow disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed">
                                            <option value="" disabled
                                                x-text="selectedDept ? 'Select Program' : 'Select Department First'">
                                            </option>
                                            <template x-for="prog in currentPrograms" :key="prog.id">
                                                <option :value="prog.name" x-text="prog.name"></option>
                                            </template>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('profile-modal')"
                        class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-[#8B0000] text-white font-bold shadow-lg hover:bg-red-900 transition-all">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="security-modal"
        class="custom-modal-spacing hidden fixed inset-0 z-[9999] flex justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[80vh] flex flex-col overflow-hidden animate-[scaleIn_0.2s_ease-out] relative z-[10000]">
            <div
                class="bg-slate-900 p-4 md:p-6 flex justify-between items-center border-b border-slate-800 shrink-0 flex-none">
                <h3 class="text-white font-bold text-lg">Update Password</h3>
                <button onclick="closeModal('security-modal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-white hover:bg-slate-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('settings.update_password') }}" method="POST"
                class="p-6 md:p-8 space-y-5 overflow-y-auto custom-scrollbar">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Current Password</label>
                    <input type="password" name="current_password" required
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-brand-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">New Password</label>
                    <input type="password" name="password" required
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-brand-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-brand-primary outline-none">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('security-modal')"
                        class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-[#8B0000] text-white font-bold shadow-lg hover:bg-red-900 transition-all">Update
                        Password</button>
                </div>
            </form>
        </div>
    </div>

    <div id="notifications-modal"
        class="custom-modal-spacing hidden fixed inset-0 z-[9999] flex justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[80vh] flex flex-col overflow-hidden animate-[scaleIn_0.2s_ease-out] relative z-[10000]">
            <div
                class="bg-slate-900 p-4 md:p-6 flex justify-between items-center border-b border-slate-800 shrink-0 flex-none">
                <h3 class="text-white font-bold text-lg">Email Preferences</h3>
                <button onclick="closeModal('notifications-modal')"
                    class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-white hover:bg-slate-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('settings.update_email_preferences') }}" method="POST"
                class="p-6 md:p-8 space-y-6 overflow-y-auto custom-scrollbar">
                @csrf
                <div class="space-y-6">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <div>
                            <span
                                class="font-bold text-slate-700 block group-hover:text-[#8B0000] transition-colors">Submission
                                Status</span>
                            <span class="text-xs text-slate-500">Get notified when your protocol status changes.</span>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="submission_status" class="sr-only peer" {{ ($user->email_preferences['submission_status'] ?? true) ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]">
                            </div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div>
                            <span
                                class="font-bold text-slate-700 block group-hover:text-[#8B0000] transition-colors">Appointment
                                Reminders</span>
                            <span class="text-xs text-slate-500">Receive alerts before scheduled meetings.</span>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="appointment_reminders" class="sr-only peer" {{ ($user->email_preferences['appointment_reminders'] ?? true) ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]">
                            </div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div>
                            <span
                                class="font-bold text-slate-700 block group-hover:text-[#8B0000] transition-colors">New
                                Resources Alert</span>
                            <span class="text-xs text-slate-500">Be notified when new templates or guidelines are
                                added.</span>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="new_resources" class="sr-only peer" {{ ($user->email_preferences['new_resources'] ?? false) ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]">
                            </div>
                        </div>
                    </label>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('notifications-modal')"
                        class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-[#8B0000] text-white font-bold shadow-lg hover:bg-red-900 transition-all">Save
                        Preferences</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
    </div>
</x-user_layout>
