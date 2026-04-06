<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | WMSU REO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ isset($cms['website_logo']) ? asset($cms['website_logo']) : '' }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-heading {
            font-family: 'Montserrat', sans-serif;
        }

        .bg-\[\#8B0000\] {
            background-color: #8B0000;
        }

        /* Smooth fade for toggling sections */
        .fade-enter {
            opacity: 0;
            transform: translateY(-10px);
        }

        .fade-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.3s, transform 0.3s;
        }
    </style>
</head>

<body class="antialiased h-screen flex items-center justify-center overflow-hidden bg-[#1a0505]">

    <div class="fixed inset-0 z-0">
        <img src="{{ isset($contents['register_image']) ? asset($contents['register_image']) : asset('images/wmsu2.jpg') }}"
            alt="WMSU Background" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 from-[#8B0000]/90 via-[#1a0505]/95 to-black/90 mix-blend-multiply"></div>
    </div>

    <div
        class="relative z-10 w-full max-w-6xl h-full md:h-[90vh] flex flex-col md:flex-row bg-white rounded-none md:rounded-3xl shadow-2xl overflow-hidden animate-[fadeInUp_0.5s_ease-out]">

        <div
            class="hidden md:flex w-1/3 bg-slate-900 text-white p-10 flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10">
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div
                        class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-lg flex items-center justify-center border border-white/20">
                        <img src="{{ isset($cms['website_logo']) ? asset($cms['website_logo']) : '' }}" class="w-6 h-6">
                    </div>
                    <span class="font-heading font-bold text-xl tracking-wide">WMSU REO</span>
                </div>
                <h2 class="text-3xl font-extrabold leading-tight mb-4">Join the Research Community</h2>
                <p class="text-slate-400 leading-relaxed text-sm">Create your account to submit protocols, track ethics
                    reviews, and collaborate with peers.</p>
            </div>

            <div class="relative z-10">
                <div class="bg-white/5 p-4 rounded-xl border border-white/10 mb-4">
                    <h4 class="font-bold text-sm text-white mb-1">Security Notice</h4>
                    <p class="text-xs text-slate-400">All registrations require email verification. WMSU users must use
                        their institutional email.</p>
                </div>
                <p class="text-[10px] text-slate-600">© 2025 Research Ethics Office</p>
            </div>
        </div>

        <div class="w-full md:w-2/3 bg-white p-8 md:p-10 overflow-y-auto relative">

            <div class="absolute top-8 left-8">
                <a href="{{ route('login') }}"
                    class="group flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-[#8B0000] transition-colors">
                    <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Back to Login
                </a>
            </div>

            <div class="mt-10 max-w-2xl mx-auto">
                <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1">Create Account</h1>
                <p class="text-slate-500 text-sm mb-6">Please fill in your details to get started.</p>

                <!-- NEW TOGGLE -->
                <div class="flex items-center justify-between gap-4 mb-8 bg-slate-50 p-4 rounded-lg border border-slate-100">
                    <label class="inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" name="external_user" id="isNotWmsu" class="sr-only peer" checked>
                        <div id="toggleSwitch"
                            class="relative w-11 h-6 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all transition-colors ring-4 ring-offset-0" style="background-color: #8B0000; box-shadow: 0 0 0 4px rgba(139, 0, 0, 0.2);">
                        </div>
                        <span class="ms-3 text-sm font-bold text-slate-700 select-none">Are you<br class="md:hidden"> from WMSU?</span>
                    </label>
                    <!-- Status Badge -->
                    <div id="statusBadge" class="px-3 py-1.5 rounded-full text-xs font-semibold text-white bg-[#8B0000] whitespace-nowrap flex-shrink-0" style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-check-circle"></i>
                        <span>You are from WMSU</span>
                    </div>
                </div>

                <form id="signupForm" method="POST" action="{{ route('register.internal') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="external_user" id="externalUserValue" value="0">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">First Name</label>
                            <input type="text" name="FirstName" value="{{ old('FirstName') }}" required
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] outline-none text-sm"
                                placeholder="Enter your first name">
                            @error('FirstName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Middle Name <span
                                    class="text-slate-400 font-normal">(Optional)</span></label>
                            <input type="text" name="MiddleName" value="{{ old('MiddleName') }}"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm"
                                placeholder="Enter your middle name">
                            @error('MiddleName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Last Name</label>
                            <input type="text" name="LastName" value="{{ old('LastName') }}" required
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm"
                                placeholder="Enter your last name">
                            @error('LastName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Email Address</label>
                            <input type="email" name="email" id="emailField" value="{{ old('email') }}" required
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm"
                                placeholder="id@wmsu.edu.ph">
                            <p id="emailHint" class="text-[10px] text-[#8B0000] hidden mt-1">* Must be a valid WMSU
                                email (@wmsu.edu.ph)</p>
                            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Contact No.</label>
                            <input type="text" name="contact" value="{{ old('contact') }}" required maxlength="11"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm"
                                placeholder="+63 912 345 6789">
                            @error('contact') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div id="wmsuFields"
                        class="space-y-4 p-5 bg-slate-50 rounded-xl border border-slate-200 transition-all duration-300"
                        x-data="{ 
                            colleges: {{ Js::from($colleges) }},
                            selectedCollege: '{{ old('college') }}',
                            selectedDept: '{{ old('department') }}',
                            selectedProgram: '{{ old('program') }}',

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
                        <h3
                            class="text-xs font-bold text-[#8B0000] uppercase tracking-wider border-b border-slate-200 pb-2 mb-3">
                            Academic Details</h3>

                        <div class="space-y-4">
                            <!-- College Dropdown -->
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-700">College</label>
                                <div class="relative">
                                    <select name="college" x-model="selectedCollege"
                                        @change="selectedDept = ''; selectedProgram = ''" required
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] outline-none text-sm appearance-none cursor-pointer transition-shadow hover:shadow-sm">
                                        <option value="" disabled selected>Select College</option>
                                        @foreach($colleges as $college)
                                            <option value="{{ $college->name }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                                @error('college') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Department Dropdown -->
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-700">Department</label>
                                    <div class="relative">
                                        <select name="department" x-model="selectedDept" @change="selectedProgram = ''"
                                            :disabled="!selectedCollege"
                                            :class="{ 'pointer-events-none opacity-60 bg-slate-100': !selectedCollege, 'bg-white focus:ring-2 focus:ring-[#8B0000]': selectedCollege }"
                                            required
                                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg outline-none text-sm appearance-none transition-all disabled:cursor-not-allowed">
                                            <option value="" disabled selected
                                                x-text="selectedCollege ? 'Select Department' : 'Select College First'">
                                            </option>
                                            <template x-for="dept in currentDepartments" :key="dept.id">
                                                <option :value="dept.name" x-text="dept.name"></option>
                                            </template>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    @error('department') <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Course Dropdown -->
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-700">Course / Program</label>
                                    <div class="relative">
                                        <select name="program" x-model="selectedProgram" :disabled="!selectedDept"
                                            :class="{ 'pointer-events-none opacity-60 bg-slate-100': !selectedDept, 'bg-white focus:ring-2 focus:ring-[#8B0000]': selectedDept }"
                                            required
                                            class="w-full px-4 py-2.5 border border-slate-200 rounded-lg outline-none text-sm appearance-none transition-all disabled:cursor-not-allowed">
                                            <option value="" disabled selected
                                                x-text="!selectedCollege ? 'Select College First' : (!selectedDept ? 'Select Department First' : 'Select Course')">
                                            </option>
                                            <template x-for="prog in currentPrograms" :key="prog.id">
                                                <option :value="prog.name" x-text="prog.name"></option>
                                            </template>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    @error('program') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="externalFields"
                        class="hidden space-y-4 p-5 bg-slate-50 rounded-xl border border-slate-200 transition-all duration-300">
                        <h3
                            class="text-xs font-bold text-blue-700 uppercase tracking-wider border-b border-slate-200 pb-2 mb-3">
                            Affiliation Details</h3>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700">Institute / Agency</label>
                            <input type="text" name="institute"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none text-sm"
                                placeholder="e.g. Department of Science and Technology">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" required
                                    class="w-full px-4 py-2.5 pr-10 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm"
                                    placeholder="Enter your password">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Confirm Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                    class="w-full px-4 py-2.5 pr-10 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm"
                                    placeholder="Confirm your password">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" id="submitBtn"
                            class="w-full bg-[#8B0000] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-900 hover:-translate-y-0.5 transition-all duration-200 text-sm tracking-wide">
                            Complete Registration
                        </button>
                    </div>

                    <p class="text-center text-xs text-slate-500 mt-4">
                        By registering, you agree to our <a href="{{ route('policy.terms') }}"
                            class="text-[#8B0000] font-bold hover:underline">Terms</a>.
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('isNotWmsu');
            const form = document.getElementById('signupForm');
            const wmsuFields = document.getElementById('wmsuFields');
            const externalFields = document.getElementById('externalFields');
            const hiddenExternalInput = document.getElementById('externalUserValue');
            const emailField = document.getElementById('emailField');
            const emailHint = document.getElementById('emailHint');

            // Select inputs for enabling/disabling
            // Note: We use querySelectorAll to find inputs/selects within the container
            const wmsuInputs = wmsuFields.querySelectorAll('input, select');
            const externalInputs = externalFields.querySelectorAll('input, select');

            // College select specifically if needed, though wmsuInputs covers it
            const collegeSelect = wmsuFields.querySelector('select[name="college"]');

            function updateFormState() {
                const statusBadge = document.getElementById('statusBadge');
                const submitBtn = document.getElementById('submitBtn');
                const toggleSwitch = document.getElementById('toggleSwitch');
                
                if (toggle.checked) {
                    // Internal (WMSU) Mode
                    externalFields.classList.add('hidden');
                    wmsuFields.classList.remove('hidden');

                    if (emailField) {
                        emailField.placeholder = "id@wmsu.edu.ph";
                    }
                    if (emailHint) {
                        emailHint.classList.add('hidden');
                    }

                    hiddenExternalInput.value = "0";
                    form.action = "{{ route('register.internal') }}";

                    // Disable External inputs
                    externalInputs.forEach(input => input.disabled = true);
                    wmsuInputs.forEach(input => input.disabled = false);

                    // Re-enable college select if it was disabled
                    if (collegeSelect) collegeSelect.disabled = false;
                    
                    // Update badge
                    statusBadge.className = 'px-3 py-1.5 rounded-full text-xs font-semibold text-white bg-[#8B0000]';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle"></i> <span>You are from WMSU</span>';
                    
                    // Update button to red
                    submitBtn.className = 'w-full bg-[#8B0000] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-900 hover:-translate-y-0.5 transition-all duration-200 text-sm tracking-wide';
                    
                    // Update toggle color to red
                    toggleSwitch.style.backgroundColor = '#8B0000';
                    toggleSwitch.style.boxShadow = '0 0 0 4px rgba(139, 0, 0, 0.2)';

                } else {
                    // External Mode
                    wmsuFields.classList.add('hidden');
                    externalFields.classList.remove('hidden');

                    if (emailField) {
                        emailField.placeholder = "name@example.com";
                    }
                    if (emailHint) {
                        emailHint.classList.add('hidden');
                    }

                    hiddenExternalInput.value = "1";
                    form.action = "{{ route('register.external') }}";

                    // Disable WMSU inputs so they don't block validation
                    wmsuInputs.forEach(input => input.disabled = true);
                    externalInputs.forEach(input => input.disabled = false);
                    
                    // Update badge
                    statusBadge.className = 'px-3 py-1.5 rounded-full text-xs font-semibold text-white bg-blue-600';
                    statusBadge.innerHTML = '<i class="fas fa-building"></i> <span>You are an External</span>';
                    
                    // Update button to blue
                    submitBtn.className = 'w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-900/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all duration-200 text-sm tracking-wide';
                    
                    // Update toggle color to blue
                    toggleSwitch.style.backgroundColor = '#2563eb';
                    toggleSwitch.style.boxShadow = '0 0 0 4px rgba(37, 99, 235, 0.2)';
                }
            }

            // check on load
            if (toggle) {
                toggle.addEventListener('change', updateFormState);
                updateFormState(); // Initialize
            }

            // Frontend Validation for WMSU Email
            if (form) {
                form.addEventListener('submit', function (e) {
                    if (toggle.checked) {
                        const email = emailField.value;
                        if (!email.endsWith('@wmsu.edu.ph')) {
                            e.preventDefault();
                            emailField.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                            emailHint.classList.remove('hidden');
                            emailField.focus();
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>