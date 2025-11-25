<x-user_layout>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
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

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:border-brand-primary/30 hover:shadow-lg transition-all group relative overflow-hidden">
                <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">tune</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Preferences</h3>
                <p class="text-sm text-slate-500 mt-2 mb-8 leading-relaxed">Customize your dashboard view and accessibility settings.</p>
                <button onclick="openModal('preferences-modal')" class="w-full py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold text-sm hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all flex items-center justify-center gap-2">
                    Customize View <i class="fas fa-sliders-h text-xs"></i>
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
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">College</label>
                        <input type="text" name="college" value="{{ Auth::user()->college }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Department</label>
                        <input type="text" name="department" value="{{ Auth::user()->department }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-brand-primary outline-none">
                    </div>
                </div>
                @endif

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('profile-modal')" class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-primary text-white font-bold shadow-lg hover:bg-red-900 transition-all">Save Changes</button>
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
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-primary text-white font-bold shadow-lg hover:bg-red-900 transition-all">Update Password</button>
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
            <div class="p-8 space-y-6">
                <label class="flex items-center justify-between cursor-pointer">
                    <span class="font-bold text-slate-700">Submission Status</span>
                    <input type="checkbox" checked class="w-5 h-5 text-brand-primary rounded focus:ring-brand-primary">
                </label>
                <label class="flex items-center justify-between cursor-pointer">
                    <span class="font-bold text-slate-700">Appointment Reminders</span>
                    <input type="checkbox" checked class="w-5 h-5 text-brand-primary rounded focus:ring-brand-primary">
                </label>
                <label class="flex items-center justify-between cursor-pointer">
                    <span class="font-bold text-slate-700">New Resources Alert</span>
                    <input type="checkbox" class="w-5 h-5 text-brand-primary rounded focus:ring-brand-primary">
                </label>
                
                <div class="pt-4 flex justify-end">
                    <button onclick="closeModal('notifications-modal')" class="px-6 py-2.5 rounded-xl bg-brand-primary text-white font-bold shadow-lg hover:bg-red-900 transition-all">Save Preferences</button>
                </div>
            </div>
        </div>
    </div>

    <div id="preferences-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[scaleIn_0.2s_ease-out]">
            <div class="bg-slate-900 p-6 flex justify-between items-center border-b border-slate-800">
                <h3 class="text-white font-bold text-lg">Display Settings</h3>
                <button onclick="closeModal('preferences-modal')" class="text-slate-400 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Theme</label>
                    <div class="flex gap-4">
                        <button class="flex-1 py-3 border-2 border-brand-primary bg-red-50 text-brand-primary font-bold rounded-xl">Light</button>
                        <button class="flex-1 py-3 border border-slate-200 text-slate-500 font-bold rounded-xl hover:border-slate-400">Dark</button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-3">Table Density</label>
                    <select class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold outline-none">
                        <option>Comfortable</option>
                        <option>Compact</option>
                    </select>
                </div>
                
                <div class="pt-4 flex justify-end">
                    <button onclick="closeModal('preferences-modal')" class="px-6 py-2.5 rounded-xl bg-brand-primary text-white font-bold shadow-lg hover:bg-red-900 transition-all">Apply Changes</button>
                </div>
            </div>
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
</x-user_layout>