@php
    $logoUrl = !empty($contents['website_logo']) ? asset($contents['website_logo']) : (!empty($cms['website_logo']) ? asset($cms['website_logo']) : asset('images/reoc-nobg.png'));
    $bgImage = isset($contents['register_image']) ? asset($contents['register_image']) : asset('images/wmsu2.jpg');

    // Performance Optimization: Strip Eloquent timestamps/metadata to reduce JSON payload by ~80%
    $optimizedColleges = $colleges->map(function ($college) {
        return [
            'id' => $college->id,
            'name' => $college->name,
            'departments' => ($college->departments ?? collect())->map(function ($dept) {
                return [
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'programs' => ($dept->programs ?? collect())->map(function ($prog) {
                        return [
                            'id' => $prog->id,
                            'name' => $prog->name,
                        ];
                    })->values(),
                ];
            })->values(),
        ];
    })->values();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#8B0000">
    <meta name="description" content="Register as a researcher with the Western Mindanao State University Research Ethics Oversight (WMSU REO) to submit protocols, track ethics reviews, and collaborate across institutions.">
    <title>Create Account | WMSU REO</title>

    <!-- DNS Prefetch & Preconnect for High-Priority Assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Preload Critical LCP Background Image -->
    <link rel="preload" as="image" href="{{ $bgImage }}" fetchpriority="high">

    <!-- Critical Typography with font-display: swap -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ $logoUrl }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-top: max(1rem, env(safe-area-inset-top));
            padding-bottom: max(1rem, env(safe-area-inset-bottom));
            padding-left: max(0.75rem, env(safe-area-inset-left));
            padding-right: max(0.75rem, env(safe-area-inset-right));
        }

        .font-heading {
            font-family: 'Montserrat', sans-serif;
        }

        .bg-\[\#8B0000\] {
            background-color: #8B0000;
        }

        .text-\[\#8B0000\] {
            color: #8B0000;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Layout containment for smooth paint isolation */
        .contain-backdrop {
            contain: strict;
        }

        .contain-card {
            contain: layout style;
        }

        /* Custom scrollbar matching neutral dark palette */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #020617;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>

<body class="antialiased min-h-screen md:h-screen flex items-center justify-center bg-slate-950 text-slate-700 selection:bg-[#8B0000] selection:text-white relative md:overflow-hidden p-2.5 sm:p-4 md:p-4 lg:p-5">

    {{-- Floating Toast Notifications (Errors & Status) --}}
    <x-toast />

    <!-- Ambient Photographic Backdrop (Neutral Dark, 0 Red Tint, Authentic Image) -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden contain-backdrop" aria-hidden="true">
        <img src="{{ $bgImage }}"
            width="2048" height="1536"
            fetchpriority="high"
            decoding="async"
            alt="" class="w-full h-full object-cover opacity-60 filter brightness-95">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/65 to-slate-950/80"></div>
    </div>

    <!-- Main Registration Card Shell (Wider & Ergonomically Proportioned) -->
    <div
        class="relative z-10 w-full max-w-6xl xl:max-w-[1240px] md:max-h-[96vh] bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden border border-slate-700/40 flex flex-col md:flex-row contain-card animate-[fadeInUp_0.5s_ease-out]">

        <!-- ========================================================= -->
        <!-- DESKTOP LEFT SIDEBAR: Institutional Identity & Atmosphere -->
        <!-- ========================================================= -->
        <aside
            class="hidden md:flex md:w-[30%] lg:w-[28%] bg-gradient-to-b from-[#0b0f19] via-[#0f172a] to-[#020617] text-white p-5 lg:p-6 flex-col justify-between relative overflow-hidden shrink-0 border-r border-slate-800/80 shadow-inner"
            aria-label="Institutional Context">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none" aria-hidden="true">
            </div>

            <!-- Sleek Ambient Glow Accents -->
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-slate-700/20 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
            <div class="absolute -bottom-24 -right-24 w-52 h-52 bg-amber-500/10 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-5 lg:mb-6">
                    <div
                        class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20 p-1.5 shadow-inner shrink-0">
                        <img src="{{ $logoUrl }}" width="40" height="40" decoding="async" class="w-full h-full object-contain" alt="WMSU REO Logo">
                    </div>
                    <div>
                        <span class="font-heading font-bold text-base lg:text-lg tracking-wide block leading-tight text-white">WMSU REO</span>
                        <span class="text-[10px] font-semibold text-amber-200/75 uppercase tracking-wider block">Research Ethics Oversight</span>
                    </div>
                </div>

                <h2 class="text-xl lg:text-2xl font-extrabold leading-tight mb-2 text-white font-heading">Join the Research Community</h2>
                <p class="text-slate-300 leading-relaxed text-xs lg:text-sm">Create your researcher account to submit protocols, track ethics reviews, and collaborate across institutions.</p>
            </div>

            <div class="relative z-10 pt-4">
                <div class="bg-white/[0.05] p-3 rounded-xl border border-white/10 mb-3 backdrop-blur-xs shadow-xs">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-shield-halved text-xs text-amber-400" aria-hidden="true"></i>
                        <h3 class="font-bold text-[10px] text-amber-200 uppercase tracking-wider">Security Notice</h3>
                    </div>
                    <p class="text-[11px] text-slate-300 leading-relaxed">All registrations require email verification. WMSU researchers must register using their official <code class="text-amber-300 font-mono text-[10px] bg-black/40 px-1 py-0.5 rounded border border-amber-400/25">@wmsu.edu.ph</code> address.</p>
                </div>
                <p class="text-[10px] text-slate-400 font-medium">© {{ date('Y') }} Research Ethics Oversight · WMSU</p>
            </div>
        </aside>

        <!-- ========================================================= -->
        <!-- RIGHT FORM PANE: Streamlined Ergonomic Layout            -->
        <!-- ========================================================= -->
        <main class="w-full md:w-[70%] lg:w-[72%] bg-white p-4 sm:p-5 md:p-6 lg:p-7 relative flex flex-col justify-center md:overflow-y-auto max-h-full" aria-label="Registration Form">

            <!-- MOBILE ONLY: Top Header with Back to Login & Logo -->
            <div class="flex md:hidden flex-col gap-3 mb-3 pb-3 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 active:scale-98 px-3.5 py-1.5 rounded-full transition-all focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#8B0000]">
                        <i class="fas fa-arrow-left text-xs text-slate-500" aria-hidden="true"></i>
                        <span>Back to Login</span>
                    </a>
                    <div class="flex items-center gap-2">
                        <img src="{{ $logoUrl }}" width="28" height="28" decoding="async" class="w-7 h-7 object-contain" alt="WMSU REO Logo">
                        <span class="font-heading font-bold text-sm text-slate-900">WMSU REO</span>
                    </div>
                </div>
            </div>

            <!-- DESKTOP ONLY: Back to Login link -->
            <div class="hidden md:flex items-center justify-between mb-2">
                <a href="{{ route('login') }}"
                    class="group inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#8B0000] transition-colors py-0.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#8B0000] rounded-sm">
                    <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1" aria-hidden="true"></i>
                    <span>Back to Login</span>
                </a>
                <span class="text-xs text-slate-400 font-medium">Researcher Registration</span>
            </div>

            <div class="w-full max-w-3xl xl:max-w-4xl mx-auto">
                <!-- Title Header -->
                <div class="mb-2.5">
                    <h1 class="text-xl sm:text-2xl font-heading font-extrabold text-slate-900 tracking-tight">Create Account</h1>
                    <p class="text-slate-500 text-xs mt-0.5">Please fill in your details to register as a researcher.</p>
                </div>


                <!-- AFFILIATION TOGGLE SWITCH CARD (Warm Tinted) -->
                <div id="toggleCard" class="flex items-center justify-between gap-3 p-2.5 sm:p-3 mb-2.5 bg-gradient-to-r from-rose-50/70 via-slate-50 to-slate-50 rounded-2xl border border-rose-200/70 transition-all">
                    <label for="isNotWmsu" class="inline-flex items-center cursor-pointer select-none gap-3 flex-1 min-w-0">
                        <div class="relative shrink-0 flex items-center">
                            <input type="checkbox" name="external_user" id="isNotWmsu" role="switch" aria-checked="true" aria-label="Are you affiliated with WMSU?" class="sr-only peer" checked>
                            <div id="toggleTrack"
                                class="w-11 h-6 bg-[#8B0000] rounded-full transition-colors relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-transform after:translate-x-5 shadow-xs shadow-red-950/20 peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-[#8B0000]">
                            </div>
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs sm:text-sm font-bold text-slate-800 block leading-snug">Are you from WMSU?</span>
                            <span id="toggleSubtext" class="text-[11px] text-slate-500 hidden sm:block">WMSU faculty, staff, or student</span>
                        </div>
                    </label>

                    <!-- Status Pill (Responsive text for mobile) -->
                    <div id="statusBadge" class="px-3 py-1 rounded-full text-xs font-semibold text-white bg-[#8B0000] flex items-center gap-1.5 shrink-0 shadow-xs shadow-red-950/20" aria-live="polite">
                        <i class="fas fa-check-circle text-xs" aria-hidden="true"></i>
                        <span id="statusBadgeText">
                            <span class="sm:hidden">WMSU</span>
                            <span class="hidden sm:inline">WMSU Researcher</span>
                        </span>
                    </div>
                </div>

                <!-- MAIN REGISTRATION FORM -->
                <form id="signupForm" method="POST" action="{{ route('register.internal') }}"
                    x-data="{ 
                        submitting: false, 
                        pass: '', 
                        confirmPass: '',
                        get passMismatch() { 
                            return this.confirmPass.length > 0 && this.pass !== this.confirmPass; 
                        } 
                    }"
                    @submit="if (submitting) { $event.preventDefault(); return false; } submitting = true"
                    class="space-y-2 sm:space-y-2.5">
                    @csrf
                    <input type="hidden" name="external_user" id="externalUserValue" value="0">

                    <!-- NAME FIELDS ROW (3 Columns Desktop, 1 Column Mobile) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-2.5">
                        <div class="space-y-1">
                            <label for="FirstName" class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                First Name <span class="text-rose-600" aria-hidden="true">*</span>
                            </label>
                            <input type="text" name="FirstName" id="FirstName" value="{{ old('FirstName') }}" required
                                maxlength="255"
                                autocomplete="given-name"
                                aria-required="true"
                                aria-invalid="{{ $errors->has('FirstName') ? 'true' : 'false' }}"
                                @error('FirstName') aria-describedby="FirstName-error" @enderror
                                class="w-full min-h-[44px] h-11 px-3.5 py-2 bg-white border @error('FirstName') border-red-500 ring-1 ring-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none text-base sm:text-sm transition-all shadow-2xs caret-[#8B0000]"
                                placeholder="e.g. Maria">
                            @error('FirstName') <p id="FirstName-error" class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="MiddleName" class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-baseline gap-1 truncate">
                                <span>Middle</span>
                                <span class="text-slate-400 font-normal text-[10px] lowercase">(optional)</span>
                            </label>
                            <input type="text" name="MiddleName" id="MiddleName" value="{{ old('MiddleName') }}"
                                maxlength="255"
                                autocomplete="additional-name"
                                class="w-full min-h-[44px] h-11 px-3.5 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none text-base sm:text-sm transition-all shadow-2xs caret-[#8B0000]"
                                placeholder="e.g. Santos">
                            @error('MiddleName') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="LastName" class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                Last Name <span class="text-rose-600" aria-hidden="true">*</span>
                            </label>
                            <input type="text" name="LastName" id="LastName" value="{{ old('LastName') }}" required
                                maxlength="255"
                                autocomplete="family-name"
                                aria-required="true"
                                aria-invalid="{{ $errors->has('LastName') ? 'true' : 'false' }}"
                                @error('LastName') aria-describedby="LastName-error" @enderror
                                class="w-full min-h-[44px] h-11 px-3.5 py-2 bg-white border @error('LastName') border-red-500 ring-1 ring-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none text-base sm:text-sm transition-all shadow-2xs caret-[#8B0000]"
                                placeholder="e.g. Dela Cruz">
                            @error('LastName') <p id="LastName-error" class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- EMAIL FIELD -->
                    <div class="space-y-1">
                        <label for="emailField" class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            Email Address <span class="text-rose-600" aria-hidden="true">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" name="email" id="emailField" value="{{ old('email') }}" required
                                maxlength="255"
                                autocomplete="email"
                                aria-required="true"
                                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                aria-describedby="emailHint @error('email') email-error @enderror"
                                class="w-full min-h-[44px] h-11 pl-10 pr-4 py-2 bg-white border @error('email') border-red-500 ring-1 ring-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none text-base sm:text-sm transition-all shadow-2xs caret-[#8B0000]"
                                placeholder="id@wmsu.edu.ph">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-envelope text-xs" aria-hidden="true"></i>
                            </div>
                        </div>

                        <p id="emailHint" class="text-[11px] text-[#8B0000] hidden mt-1 flex items-center gap-1.5 font-semibold" role="alert">
                            <i class="fas fa-info-circle text-xs" aria-hidden="true"></i>
                            <span>WMSU researchers must use their institutional email (<code>@wmsu.edu.ph</code>)</span>
                        </p>

                        @error('email')
                            <p id="email-error" class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WMSU ACADEMIC DETAILS SECTION (Harmonious Tint) -->
                    <div id="wmsuFields"
                        class="p-2.5 sm:p-3 bg-gradient-to-br from-slate-50 via-slate-50/80 to-rose-50/30 rounded-2xl border border-slate-200/90 transition-all duration-300 space-y-2 shadow-2xs"
                        x-data="{ 
                            colleges: {{ Js::from($optimizedColleges) }},
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
                        
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-1.5">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-[#8B0000] text-xs" aria-hidden="true"></i>
                                <h3 class="text-xs font-bold text-[#8B0000] uppercase tracking-wider">Academic Affiliation</h3>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium">Required for WMSU</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 sm:gap-2.5">
                            <!-- College Dropdown -->
                            <div class="space-y-1">
                                <label for="collegeSelect" class="text-xs font-bold text-slate-700 block">
                                    College <span class="text-rose-600" aria-hidden="true">*</span>
                                </label>
                                <div class="relative">
                                    <select name="college" id="collegeSelect" x-model="selectedCollege"
                                        @change="selectedDept = ''; selectedProgram = ''" required
                                        aria-required="true"
                                        class="w-full min-h-[44px] h-11 px-3.5 pr-8 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none text-base sm:text-xs appearance-none cursor-pointer transition-shadow hover:shadow-2xs truncate">
                                        <option value="" disabled selected>Select College</option>
                                        @foreach($colleges as $college)
                                            <option value="{{ $college->name }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2.5 pointer-events-none text-slate-400">
                                        <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                                    </div>
                                </div>
                                @error('college') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Department Dropdown -->
                            <div class="space-y-1">
                                <label for="deptSelect" class="text-xs font-bold text-slate-700 block">
                                    Department <span class="text-rose-600" aria-hidden="true">*</span>
                                </label>
                                <div class="relative">
                                    <select name="department" id="deptSelect" x-model="selectedDept" @change="selectedProgram = ''"
                                        :disabled="!selectedCollege"
                                        :class="{ 'pointer-events-none opacity-60 bg-slate-100': !selectedCollege, 'bg-white focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000]': selectedCollege }"
                                        required
                                        aria-required="true"
                                        class="w-full min-h-[44px] h-11 px-3.5 pr-8 py-2 border border-slate-200 rounded-xl outline-none text-base sm:text-xs appearance-none transition-all disabled:cursor-not-allowed truncate">
                                        <option value="" disabled selected
                                            x-text="selectedCollege ? 'Select Department' : 'Select College First'">
                                        </option>
                                        <template x-for="dept in currentDepartments" :key="dept.id">
                                            <option :value="dept.name" x-text="dept.name"></option>
                                        </template>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2.5 pointer-events-none text-slate-400">
                                        <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                                    </div>
                                </div>
                                @error('department') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Course / Program Dropdown -->
                            <div class="space-y-1">
                                <label for="programSelect" class="text-xs font-bold text-slate-700 block">
                                    Course / Program <span class="text-rose-600" aria-hidden="true">*</span>
                                </label>
                                <div class="relative">
                                    <select name="program" id="programSelect" x-model="selectedProgram" :disabled="!selectedDept"
                                        :class="{ 'pointer-events-none opacity-60 bg-slate-100': !selectedDept, 'bg-white focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000]': selectedDept }"
                                        required
                                        aria-required="true"
                                        class="w-full min-h-[44px] h-11 px-3.5 pr-8 py-2 border border-slate-200 rounded-xl outline-none text-base sm:text-xs appearance-none transition-all disabled:cursor-not-allowed truncate">
                                        <option value="" disabled selected
                                            x-text="!selectedCollege ? 'Select College First' : (!selectedDept ? 'Select Department First' : 'Select Course')">
                                        </option>
                                        <template x-for="prog in currentPrograms" :key="prog.id">
                                            <option :value="prog.name" x-text="prog.name"></option>
                                        </template>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2.5 pointer-events-none text-slate-400">
                                        <i class="fas fa-chevron-down text-xs" aria-hidden="true"></i>
                                    </div>
                                </div>
                                @error('program') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- EXTERNAL AFFILIATION SECTION -->
                    <div id="externalFields"
                        class="hidden p-2.5 sm:p-3 bg-slate-50/90 rounded-2xl border border-slate-200/90 transition-all duration-300 space-y-2 shadow-2xs">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-1.5">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-building text-slate-700 text-xs" aria-hidden="true"></i>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">External Affiliation</h3>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium">Non-WMSU</span>
                        </div>
                        <div class="space-y-1">
                            <label for="instituteInput" class="text-xs font-bold text-slate-700 block">
                                Institution / Agency / Organization <span class="text-rose-600" aria-hidden="true">*</span>
                            </label>
                            <input type="text" name="institute" id="instituteInput"
                                maxlength="155"
                                class="w-full min-h-[44px] h-11 px-3.5 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none text-base sm:text-sm transition-all shadow-2xs caret-[#8B0000]"
                                placeholder="e.g. Department of Science and Technology (DOST)">
                            @error('institute') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- PASSWORD FIELDS ROW -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-2.5">
                        <div class="space-y-1">
                            <label for="password" class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                Password <span class="text-rose-600" aria-hidden="true">*</span>
                            </label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" id="password" required
                                    minlength="6"
                                    maxlength="255"
                                    x-model="pass"
                                    autocomplete="new-password"
                                    aria-required="true"
                                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                    @error('password') aria-describedby="password-error" @enderror
                                    class="w-full min-h-[44px] h-11 pl-3.5 pr-11 py-2 bg-white border @error('password') border-red-500 ring-1 ring-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none text-base sm:text-sm transition-all shadow-2xs caret-[#8B0000]"
                                    placeholder="Minimum 6 characters">
                                <button type="button" @click="show = !show"
                                    :aria-label="show ? 'Hide password' : 'Show password'"
                                    :aria-pressed="show"
                                    class="absolute inset-y-0 right-0 w-11 h-11 flex items-center justify-center text-slate-400 hover:text-slate-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] rounded-xl cursor-pointer">
                                    <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                                </button>
                            </div>
                            @error('password') <p id="password-error" class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="password_confirmation" class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                Confirm Password <span class="text-rose-600" aria-hidden="true">*</span>
                            </label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                                    minlength="6"
                                    maxlength="255"
                                    x-model="confirmPass"
                                    autocomplete="new-password"
                                    aria-required="true"
                                    :class="{ 'border-red-500 ring-1 ring-red-500': passMismatch }"
                                    class="w-full min-h-[44px] h-11 pl-3.5 pr-11 py-2 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none text-base sm:text-sm transition-all shadow-2xs caret-[#8B0000]"
                                    placeholder="Repeat your password">
                                <button type="button" @click="show = !show"
                                    :aria-label="show ? 'Hide password' : 'Show password'"
                                    :aria-pressed="show"
                                    class="absolute inset-y-0 right-0 w-11 h-11 flex items-center justify-center text-slate-400 hover:text-slate-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000] rounded-xl cursor-pointer">
                                    <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p x-show="passMismatch" x-cloak class="text-[11px] text-red-600 mt-1 flex items-center gap-1 font-medium" role="alert">
                                <i class="fas fa-circle-exclamation text-xs" aria-hidden="true"></i>
                                <span>Passwords do not match</span>
                            </p>
                        </div>
                    </div>

                    <!-- SUBMIT CTA BUTTON WITH CRIMSON GRADIENT & DEPTH -->
                    <div class="pt-1 sm:pt-1.5">
                        <button type="submit" id="submitBtn" :disabled="submitting"
                            class="w-full min-h-[44px] h-11 bg-gradient-to-r from-[#8B0000] via-[#940000] to-[#7A0000] hover:from-[#7A0000] hover:to-[#6B0000] active:scale-[0.99] disabled:opacity-75 disabled:cursor-not-allowed text-white font-bold py-2.5 rounded-xl shadow-md shadow-red-950/20 hover:shadow-red-950/30 transition-all duration-200 text-sm tracking-wide flex items-center justify-center gap-2 cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#8B0000]">
                            <template x-if="!submitting">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-user-plus text-xs" aria-hidden="true"></i>
                                    <span id="submitBtnText">Complete Registration</span>
                                </span>
                            </template>
                            <template x-if="submitting">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-circle-notch fa-spin text-sm" aria-hidden="true"></i>
                                    <span>Creating Account...</span>
                                </span>
                            </template>
                        </button>
                    </div>

                    <!-- FOOTER TERMS & LOGIN ROW (Single Row on Desktop) -->
                    <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 pt-1 gap-1">
                        <span>By registering, you agree to our <a href="{{ route('policy.terms') }}"
                            class="text-[#8B0000] hover:text-[#7A0000] font-bold hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#8B0000] rounded-xs">Terms & Conditions</a>.</span>
                        <span>Already have an account? <a href="{{ route('login') }}" class="font-bold text-[#8B0000] hover:text-[#7A0000] hover:underline ml-1 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#8B0000] rounded-xs">Log in</a></span>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- State & Validation Controller Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('isNotWmsu');
            const form = document.getElementById('signupForm');
            const wmsuFields = document.getElementById('wmsuFields');
            const externalFields = document.getElementById('externalFields');
            const hiddenExternalInput = document.getElementById('externalUserValue');
            const emailField = document.getElementById('emailField');
            const emailHint = document.getElementById('emailHint');
            const statusBadge = document.getElementById('statusBadge');
            const toggleSubtext = document.getElementById('toggleSubtext');
            const toggleTrack = document.getElementById('toggleTrack');
            const toggleCard = document.getElementById('toggleCard');
            const instituteInput = document.getElementById('instituteInput');

            // Select inputs for enabling/disabling
            const wmsuInputs = wmsuFields.querySelectorAll('select, input');

            function updateFormState() {
                const isWmsu = toggle.checked;
                toggle.setAttribute('aria-checked', isWmsu ? 'true' : 'false');

                if (isWmsu) {
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

                    // Enable WMSU inputs, disable external
                    wmsuInputs.forEach(input => {
                        input.disabled = false;
                    });
                    if (instituteInput) {
                        instituteInput.disabled = true;
                        instituteInput.required = false;
                    }

                    // Update toggle visual with crimson theme
                    if (toggleCard) {
                        toggleCard.className = 'flex items-center justify-between gap-3 p-2.5 sm:p-3 mb-2.5 bg-gradient-to-r from-rose-50/70 via-slate-50 to-slate-50 rounded-2xl border border-rose-200/70 transition-all';
                    }
                    toggleTrack.className = 'w-11 h-6 bg-[#8B0000] rounded-full transition-colors relative after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-transform after:translate-x-5 shadow-xs shadow-red-950/20 peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-[#8B0000]';
                    if (toggleSubtext) toggleSubtext.textContent = 'WMSU faculty, staff, or student';

                    // Update badge
                    statusBadge.className = 'px-3 py-1.5 rounded-full text-xs font-semibold text-white bg-[#8B0000] flex items-center gap-1.5 shrink-0 shadow-xs shadow-red-950/20';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle text-xs" aria-hidden="true"></i> <span><span class="sm:hidden">WMSU</span><span class="hidden sm:inline">WMSU Researcher</span></span>';

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

                    // Disable WMSU inputs so browser HTML5 validation doesn't block submit
                    wmsuInputs.forEach(input => {
                        input.disabled = true;
                    });
                    if (instituteInput) {
                        instituteInput.disabled = false;
                        instituteInput.required = true;
                    }

                    // Update toggle visual with slate partner theme
                    if (toggleCard) {
                        toggleCard.className = 'flex items-center justify-between gap-3 p-2.5 sm:p-3 mb-2.5 bg-slate-50 rounded-2xl border border-slate-200/90 transition-all';
                    }
                    toggleTrack.className = 'w-11 h-6 bg-slate-600 rounded-full transition-colors relative after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-transform after:translate-x-0 shadow-xs peer-focus-visible:ring-2 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-slate-700';
                    if (toggleSubtext) toggleSubtext.textContent = 'Non-WMSU institutional partner or affiliate';

                    // Update badge
                    statusBadge.className = 'px-3 py-1.5 rounded-full text-xs font-semibold text-white bg-slate-800 flex items-center gap-1.5 shrink-0 shadow-xs';
                    statusBadge.innerHTML = '<i class="fas fa-building text-xs" aria-hidden="true"></i> <span><span class="sm:hidden">External</span><span class="hidden sm:inline">External Partner</span></span>';
                }
            }

            // Sync on change
            if (toggle) {
                toggle.addEventListener('change', updateFormState);
                updateFormState(); // Initialize on DOM load
            }

            // Frontend validation for WMSU email
            if (form) {
                form.addEventListener('submit', function (e) {
                    if (toggle.checked) {
                        const email = (emailField.value || '').trim().toLowerCase();
                        if (!email.endsWith('@wmsu.edu.ph')) {
                            e.preventDefault();
                            emailField.classList.add('border-red-500', 'ring-2', 'ring-red-500/20');
                            emailHint.classList.remove('hidden');
                            emailField.focus();

                            // Reset Alpine submitting state if blocked by client-side rule
                            if (form._x_dataStack && form._x_dataStack[0]) {
                                form._x_dataStack[0].submitting = false;
                            }
                        }
                    }
                });
            }

            if (emailField) {
                emailField.addEventListener('input', function () {
                    emailField.classList.remove('border-red-500', 'ring-2', 'ring-red-500/20');
                    if (emailHint) emailHint.classList.add('hidden');
                });
            }
        });
    </script>
</body>

</html>