<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | WMSU REO</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Montserrat', sans-serif; }
        .bg-\[\#8B0000\] { background-color: #8B0000; }
        /* Smooth fade for toggling sections */
        .fade-enter { opacity: 0; transform: translateY(-10px); }
        .fade-enter-active { opacity: 1; transform: translateY(0); transition: opacity 0.3s, transform 0.3s; }
    </style>
</head>
<body class="antialiased h-screen flex items-center justify-center overflow-hidden bg-[#1a0505]">

    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/wmsu2.jpg') }}" alt="WMSU Background" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 from-[#8B0000]/90 via-[#1a0505]/95 to-black/90 mix-blend-multiply"></div>
    </div>

    <div class="relative z-10 w-full max-w-6xl h-full md:h-[90vh] flex flex-col md:flex-row bg-white rounded-none md:rounded-3xl shadow-2xl overflow-hidden animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="hidden md:flex w-1/3 bg-slate-900 text-white p-10 flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-lg flex items-center justify-center border border-white/20">
                        <img src="{{ asset('images/reoc-nobg.png') }}" class="w-6 h-6">
                    </div>
                    <span class="font-heading font-bold text-xl tracking-wide">WMSU REO</span>
                </div>
                <h2 class="text-3xl font-extrabold leading-tight mb-4">Join the Research Community</h2>
                <p class="text-slate-400 leading-relaxed text-sm">Create your account to submit protocols, track ethics reviews, and collaborate with peers.</p>
            </div>

            <div class="relative z-10">
                <div class="bg-white/5 p-4 rounded-xl border border-white/10 mb-4">
                    <h4 class="font-bold text-sm text-white mb-1">Security Notice</h4>
                    <p class="text-xs text-slate-400">All registrations require email verification. WMSU users must use their institutional email.</p>
                </div>
                <p class="text-[10px] text-slate-600">© 2025 Research Ethics Office</p>
            </div>
        </div>

        <div class="w-full md:w-2/3 bg-white p-8 md:p-10 overflow-y-auto relative">
            
            <div class="absolute top-8 left-8">
                <a href="{{ route('login') }}" class="group flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-[#8B0000] transition-colors">
                    <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Back to Login
                </a>
            </div>

            <div class="mt-10 max-w-2xl mx-auto">
                <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1">Create Account</h1>
                <p class="text-slate-500 text-sm mb-6">Please fill in your details to get started.</p>

                <!-- NEW TOGGLE -->
                <div class="flex items-center gap-3 mb-8 bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="external_user" id="isNotWmsu" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8B0000]"></div>
                        <span class="ms-3 text-sm font-bold text-slate-700 select-none">Are you NOT from WMSU?</span>
                    </label>
                </div>

                <form id="signupForm" method="POST" action="{{ route('register.internal') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="external_user" id="externalUserValue" value="0">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">First Name</label>
                            <input type="text" name="FirstName" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] outline-none text-sm" placeholder="Juan">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Middle Name <span class="text-slate-400 font-normal">(Opt)</span></label>
                            <input type="text" name="MiddleName" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm" placeholder="D.">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Last Name</label>
                            <input type="text" name="LastName" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm" placeholder="Dela Cruz">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Email Address</label>
                            <input type="email" name="email" id="emailField" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm" placeholder="juan@wmsu.edu.ph">
                            <p id="emailHint" class="text-[10px] text-[#8B0000] hidden mt-1">* Must be a valid WMSU email (@wmsu.edu.ph)</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Contact No.</label>
                            <input type="text" name="contact" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm" placeholder="0912 345 6789">
                        </div>
                    </div>

                    <div id="wmsuFields" class="space-y-4 p-5 bg-slate-50 rounded-xl border border-slate-200 transition-all duration-300">
                        <h3 class="text-xs font-bold text-[#8B0000] uppercase tracking-wider border-b border-slate-200 pb-2 mb-3">Academic Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-700">College</label>
                                <select name="college" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm">
                                    <option value="" disabled selected>Select College</option>
                                    <option value="ICS">College of Computing Studies</option>
                                    <option value="COE">College of Engineering</option>
                                    <option value="CSM">College of Science & Math</option>
                                    </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-700">Department</label>
                                <input type="text" name="department" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm" placeholder="e.g. Computer Science">
                            </div>
                            <div class="space-y-1 md:col-span-2">
                                <label class="text-xs font-bold text-slate-700">Course / Program</label>
                                <input type="text" name="course" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm" placeholder="e.g. BS in Computer Science">
                            </div>
                        </div>
                    </div>

                    <div id="externalFields" class="hidden space-y-4 p-5 bg-slate-50 rounded-xl border border-slate-200 transition-all duration-300">
                        <h3 class="text-xs font-bold text-blue-700 uppercase tracking-wider border-b border-slate-200 pb-2 mb-3">Affiliation Details</h3>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700">Institute / Agency</label>
                            <input type="text" name="institute" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none text-sm" placeholder="e.g. Department of Science and Technology">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Password</label>
                            <input type="password" name="password" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm" placeholder="••••••••">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none text-sm" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#8B0000] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-900 hover:-translate-y-0.5 transition-all duration-200 text-sm tracking-wide">
                            Complete Registration
                        </button>
                    </div>

                    <p class="text-center text-xs text-slate-500 mt-4">
                        By registering, you agree to our <a href="{{ route('policy.terms') }}" class="text-[#8B0000] font-bold hover:underline">Terms</a>.
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('isNotWmsu');
        const wmsuFields = document.getElementById('wmsuFields');
        const externalFields = document.getElementById('externalFields');
        const emailField = document.getElementById('emailField');
        const emailHint = document.getElementById('emailHint');
        const hiddenExternalInput = document.getElementById('externalUserValue');
        const form = document.getElementById('signupForm');

        // Inputs to toggle
        const wmsuInputs = wmsuFields.querySelectorAll('input, select');
        const externalInputs = externalFields.querySelectorAll('input, select');

        // Toggle Form Logic
        function updateFormState() {
            if (toggle.checked) {
                // External Mode
                wmsuFields.classList.add('hidden');
                externalFields.classList.remove('hidden');
                emailField.placeholder = "name@example.com";
                emailHint.classList.add('hidden');
                hiddenExternalInput.value = "1";
                
                // Update Action
                form.action = "{{ route('register.external') }}";

                // Disable WMSU inputs so they don't block validation or send data
                wmsuInputs.forEach(input => input.disabled = true);
                externalInputs.forEach(input => input.disabled = false);

            } else {
                // WMSU Mode
                externalFields.classList.add('hidden');
                wmsuFields.classList.remove('hidden');
                emailField.placeholder = "juan@wmsu.edu.ph";
                hiddenExternalInput.value = "0";

                // Update Action
                form.action = "{{ route('register.internal') }}";

                // Disable External inputs
                externalInputs.forEach(input => input.disabled = true);
                wmsuInputs.forEach(input => input.disabled = false);
            }
        }

        toggle.addEventListener('change', updateFormState);

        // Initialize state on load
        updateFormState();

        // Simple Frontend Validation for WMSU Email
        form.addEventListener('submit', function(e) {
            if (!toggle.checked) {
                const email = emailField.value;
                if (!email.endsWith('@wmsu.edu.ph')) {
                    e.preventDefault();
                    emailField.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                    emailHint.classList.remove('hidden');
                    emailField.focus();
                }
            }
        });
    </script>
</body>
</html>
