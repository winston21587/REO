<x-user_layout>
    @php $user = Auth::user(); @endphp
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-24 right-6 z-50 bg-white border-l-4 border-green-500 rounded-lg shadow-lg p-4 animate-[fadeInLeft_0.3s_ease-out]">
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
        <div x-data="{ show: true }" x-show="show" class="fixed top-24 right-6 z-50 bg-white border-l-4 border-red-500 rounded-lg shadow-lg p-4 animate-[fadeInLeft_0.3s_ease-out]">
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
                <button @click="show = false" class="ml-auto text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
        </div>
    @endif

    <div class="max-w-5xl mx-auto animate-[fadeInUp_0.5s_ease-out]">
        <div class="mb-10 border-b border-slate-200 pb-6">
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading">Account Settings</h1>
            <p class="text-slate-500 mt-2">Manage your profile information, security, and preferences.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:border-brand-primary/30 hover:shadow-lg transition-all group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <span class="material-symbols-outlined text-8xl text-brand-primary">person</span>
                </div>
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">person</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Personal Profile</h3>
                <p class="text-sm text-slate-500 mt-2 mb-8 leading-relaxed">Update your personal details, academic affiliation, and contact information.</p>
                <button onclick="openModal('profile-modal')" class="w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all flex items-center justify-center gap-2">
                    Edit Profile <i class="fas fa-pen text-xs"></i>
                </button>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:border-brand-primary/30 hover:shadow-lg transition-all group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <span class="material-symbols-outlined text-8xl text-brand-primary">lock</span>
                </div>
                <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">lock_reset</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Security</h3>
                <p class="text-sm text-slate-500 mt-2 mb-8 leading-relaxed">Ensure your account stays safe by updating your password regularly.</p>
                <button onclick="openModal('security-modal')" class="w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all flex items-center justify-center gap-2">
                    Update Password <i class="fas fa-key text-xs"></i>
                </button>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:border-brand-primary/30 hover:shadow-lg transition-all group relative overflow-hidden">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">notifications_active</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Notifications</h3>
                <p class="text-sm text-slate-500 mt-2 mb-8 leading-relaxed">Control how you receive updates about your research submissions.</p>
                <button onclick="openModal('notifications-modal')" class="w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all flex items-center justify-center gap-2">
                    Configure Alerts <i class="fas fa-cog text-xs"></i>
                </button>
            </div>



    </div>

        <div class="mt-12 p-8 bg-red-50 rounded-2xl border border-red-100 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h4 class="text-red-800 font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> Danger Zone
                </h4>
                <p class="text-red-600/70 text-sm mt-1">Permanently delete your account and all associated research data. This cannot be undone.</p>
            </div>
            <button onclick="openModal('delete-account-modal')" class="px-6 py-3 bg-white border border-red-200 text-red-600 font-bold text-sm rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm whitespace-nowrap">
                Delete Account
            </button>
        </div>
    </div>

    <div id="delete-account-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[scaleIn_0.2s_ease-out]">
            <div class="bg-red-600 p-6 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-white"></i> Confirm Deletion
                </h3>
                <button onclick="closeModal('delete-account-modal')" class="text-red-100 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
            </div>
            
            <div class="p-8">
                <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                    Are you sure you want to delete your account? This action is <strong class="text-red-600">irreversible</strong>. All your research protocols, files, and appointments will be permanently removed.
                </p>

                <form action="{{ route('settings.delete_account') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Enter Password to Confirm</label>
                        <input type="password" name="password" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all" placeholder="Your password">
                        @error('password', 'delete') 
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div class="pt-2 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('delete-account-modal')" class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 text-white font-bold shadow-lg hover:bg-red-700 transition-all">
                            Yes, Delete My Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="profile-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden animate-[scaleIn_0.2s_ease-out]">
            <div class="bg-slate-900 p-6 flex justify-between items-center border-b border-slate-800">
                <h3 class="text-white font-bold text-lg">Edit Profile</h3>
                <button onclick="closeModal('profile-modal')" class="text-slate-400 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('settings.update_profile') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ Auth::user()->first_name }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ Auth::user()->last_name }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email Address</label>
                    <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full p-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                    <p class="text-[10px] text-slate-400 mt-1">Email cannot be changed. Contact admin for assistance.</p>
                </div>
                
                @if(Auth::user()->external_user)
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Institution / Agency</label>
                    <input type="text" name="institute" value="{{ Auth::user()->institute }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary outline-none">
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">College</label>
                        <div class="relative">
                            <select name="college" id="settingsCollegeSelect" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] outline-none appearance-none cursor-pointer">
                                <option value="" disabled>Select College</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Department</label>
                        <div class="relative">
                            <select name="department" id="settingsDeptSelect" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-[#8B0000] outline-none appearance-none cursor-pointer">
                                <option value="" disabled>Select Department</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('profile-modal')" class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#8B0000] text-white font-bold shadow-lg hover:bg-red-900 transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="security-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[scaleIn_0.2s_ease-out]">
            <div class="bg-slate-900 p-6 flex justify-between items-center border-b border-slate-800">
                <h3 class="text-white font-bold text-lg">Update Password</h3>
                <button onclick="closeModal('security-modal')" class="text-slate-400 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('settings.update_password') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Current Password</label>
                    <input type="password" name="current_password" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-brand-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">New Password</label>
                    <input type="password" name="password" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-brand-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-brand-primary outline-none">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('security-modal')" class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#8B0000] text-white font-bold shadow-lg hover:bg-red-900 transition-all">Update Password</button>
                </div>
            </form>
        </div>
    </div>

    <div id="notifications-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[scaleIn_0.2s_ease-out]">
            <div class="bg-slate-900 p-6 flex justify-between items-center border-b border-slate-800">
                <h3 class="text-white font-bold text-lg">Email Preferences</h3>
                <button onclick="closeModal('notifications-modal')" class="text-slate-400 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('settings.update_email_preferences') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-6">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <div>
                            <span class="font-bold text-slate-700 block group-hover:text-[#8B0000] transition-colors">Submission Status</span>
                            <span class="text-xs text-slate-500">Get notified when your protocol status changes.</span>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="submission_status" class="sr-only peer" {{ ($user->email_preferences['submission_status'] ?? true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div>
                            <span class="font-bold text-slate-700 block group-hover:text-[#8B0000] transition-colors">Appointment Reminders</span>
                            <span class="text-xs text-slate-500">Receive alerts before scheduled meetings.</span>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="appointment_reminders" class="sr-only peer" {{ ($user->email_preferences['appointment_reminders'] ?? true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <div>
                            <span class="font-bold text-slate-700 block group-hover:text-[#8B0000] transition-colors">New Resources Alert</span>
                            <span class="text-xs text-slate-500">Be notified when new templates or guidelines are added.</span>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="new_resources" class="sr-only peer" {{ ($user->email_preferences['new_resources'] ?? false) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]"></div>
                        </div>
                    </label>
                </div>
                
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('notifications-modal')" class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#8B0000] text-white font-bold shadow-lg hover:bg-red-900 transition-all">Save Preferences</button>
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

        // Academic Data Structure (Same as Register)
        const academicData = {
            "College of Computing Studies": {
                "Department of Computer Science": ["BS Computer Science", "BS Information Technology", "Master in Information Technology"],
                "Department of Computer Engineering": ["BS Computer Engineering"]
            },
            "College of Engineering": {
                "Department of Civil Engineering": ["BS Civil Engineering", "BS Sanitary Engineering"],
                "Department of Electrical Engineering": ["BS Electrical Engineering"],
                "Department of Mechanical Engineering": ["BS Mechanical Engineering"]
            },
            "College of Science and Mathematics": {
                "Department of Mathematics": ["BS Mathematics", "BS Statistics"],
                "Department of Biology": ["BS Biology", "MS Biology"],
                "Department of Physics": ["BS Physics"]
            },
            "College of Liberal Arts": {
                "Department of English": ["AB English Language Studies"],
                "Department of Political Science": ["AB Political Science"],
                "Department of Psychology": ["BS Psychology"]
            },
            "College of Teacher Education": {
                "Department of Elementary Education": ["BE Elementary Education"],
                "Department of Secondary Education": ["BE Secondary Education"]
            },
            "College of Nursing": {
                "Department of Nursing": ["BS Nursing"]
            },
            "College of Criminal Justice Education": {
                "Department of Criminology": ["BS Criminology"]
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const collegeSelect = document.getElementById('settingsCollegeSelect');
            const deptSelect = document.getElementById('settingsDeptSelect');
            
            // Only proceed if elements exist (internal users)
            if (collegeSelect && deptSelect) {
                const userCollege = "{{ Auth::user()->college }}";
                const userDept = "{{ Auth::user()->department }}";

                // Populate Colleges
                for (const college in academicData) {
                    const option = document.createElement('option');
                    option.value = college;
                    option.textContent = college;
                    if (college === userCollege) option.selected = true;
                    collegeSelect.appendChild(option);
                }

                // Function to populate departments
                function populateDepartments(selectedCollege, selectedDept = null) {
                    deptSelect.innerHTML = '<option value="" disabled selected>Select Department</option>';
                    
                    if (selectedCollege && academicData[selectedCollege]) {
                        for (const dept in academicData[selectedCollege]) {
                            const option = document.createElement('option');
                            option.value = dept;
                            option.textContent = dept;
                            if (dept === selectedDept) option.selected = true;
                            deptSelect.appendChild(option);
                        }
                    }
                }

                // Initial Population
                if (userCollege) {
                    populateDepartments(userCollege, userDept);
                }

                // Handle Change
                collegeSelect.addEventListener('change', function() {
                    populateDepartments(this.value);
                });
            }
        });
    </script>
</x-user_layout>