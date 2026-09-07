@php
    $logoUrl = !empty($contents['website_logo']) ? asset($contents['website_logo']) : (!empty($cms['website_logo']) ? asset($cms['website_logo']) : asset('images/reoc-nobg.png'));
    $campusImage = isset($contents['login_image']) ? asset($contents['login_image']) : asset('images/wmsu1.jpg');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | WMSU REO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ $logoUrl }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        .text-\[\#8B0000\] {
            color: #8B0000;
        }
    </style>
</head>

<body class="bg-slate-50 md:bg-white text-slate-800 antialiased min-h-screen">
    <x-toast />

    <div class="min-h-screen flex flex-col md:flex-row h-screen overflow-hidden">

        <!-- ========================================================= -->
        <!-- DESKTOP LEFT HERO SECTION (Unchanged / Reverted to Normal) -->
        <!-- ========================================================= -->
        <div class="hidden md:flex w-1/2 relative text-white flex-col justify-center p-12 lg:p-16">
            <div class="absolute inset-0 z-0">
                <img src="{{ $campusImage }}"
                    class="w-full h-full object-cover" alt="WMSU Campus">
                <div class="absolute inset-0 bg-black/30"></div>
            </div>

            <div class="relative z-10 max-w-lg animate-[fadeInUp_0.8s_ease-out]">
                <div class="w-16 h-16 flex items-center justify-center mb-8">
                    <img src="{{ $logoUrl }}"
                        class="w-10 h-10 drop-shadow-md" alt="WMSU REO Logo">
                </div>

                <h1
                    class="text-5xl lg:text-6xl font-extrabold font-heading tracking-tight leading-tight drop-shadow-xl text-white">
                    Research <br> Excellence.
                </h1>

                <p class="mt-6 text-lg text-white/90 font-medium leading-relaxed drop-shadow-lg max-w-md">
                    Your gateway to ethical research review and compliance at Western Mindanao State University.
                </p>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- RIGHT FORM SECTION (Desktop Normal + Enhanced Mobile)      -->
        <!-- ========================================================= -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-5 sm:p-8 bg-[#faf8f8] md:bg-white relative overflow-y-auto h-full">

            <div class="w-full max-w-md relative z-10 my-auto py-4">

                <!-- MOBILE ONLY: Top Navigation Bar (Back to Home) -->
                <div class="flex md:hidden items-center mb-6">
                    <a href="{{ route('index') }}"
                        class="inline-flex items-center gap-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:border-slate-300 px-3.5 py-2 rounded-full shadow-xs transition-all">
                        <i class="fas fa-arrow-left text-xs text-slate-500" aria-hidden="true"></i>
                        <span>Back to Home</span>
                    </a>
                </div>

                <!-- MOBILE ONLY: Institutional Identity Header -->
                <div class="flex md:hidden items-center gap-3.5 mb-6 bg-white p-3.5 rounded-2xl border border-slate-200/70 shadow-xs">
                    <img src="{{ $logoUrl }}" class="w-10 h-10 object-contain shrink-0" alt="REO Logo">
                    <div class="min-w-0">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 truncate">Western Mindanao State University</div>
                        <div class="text-sm font-extrabold text-slate-900 font-heading truncate">Research Ethics Oversight</div>
                    </div>
                </div>

                <!-- DESKTOP ONLY: Normal Back to Home Link -->
                <div class="hidden md:block mb-6">
                    <a href="{{ route('index') }}"
                        class="text-slate-500 hover:text-[#8B0000] transition-colors font-bold text-sm inline-flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>

                <!-- Form Card Shell: Card on Mobile, Clean Flat on Desktop -->
                <div class="bg-white md:bg-transparent rounded-3xl md:rounded-none border border-slate-200/80 md:border-0 shadow-xl md:shadow-none shadow-slate-200/40 p-6 sm:p-8 md:p-0">

                    <div class="text-center md:text-left mb-8 md:mb-10">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 font-heading">Welcome Back</h2>
                        <p class="mt-2 text-sm sm:text-base text-slate-500">Please sign in to your researcher account.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-6"
                        x-data="{ show: false, submitting: false }"
                        @submit="submitting = true">
                        @csrf

                        <div class="space-y-2">
                            <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email
                                Address</label>
                            <div class="relative group">
                                <span
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#8B0000] transition-colors">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                    autocomplete="email" autocapitalize="off" spellcheck="false"
                                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] transition-all outline-none text-slate-800 placeholder-slate-400 font-medium @error('email') border-red-500 @enderror"
                                    placeholder="id@wmsu.edu.ph">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <label for="password"
                                    class="text-xs font-bold text-slate-500 uppercase tracking-wider">Password</label>
                            </div>
                            <div class="relative group">
                                <span
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#8B0000] transition-colors">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                    autocomplete="current-password"
                                    class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] transition-all outline-none text-slate-800 placeholder-slate-400 font-medium @error('password') border-red-500 @enderror"
                                    placeholder="••••••••">
                                <button type="button" @click="show = !show"
                                    :aria-label="show ? 'Hide password' : 'Show password'"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-700 focus:outline-none cursor-pointer">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror

                            <div class="flex items-center justify-between mt-2 pt-1">
                                <label for="remember" class="inline-flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" id="remember" name="remember" value="1"
                                        class="w-4 h-4 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]/20 accent-[#8B0000] cursor-pointer">
                                    <span class="text-xs font-medium text-slate-600">Remember me</span>
                                </label>

                                <a href="{{ route('password.request') }}"
                                    class="text-xs text-[#8B0000] font-bold hover:underline">Forgot password?</a>
                            </div>
                        </div>

                        <button type="submit" :disabled="submitting"
                            class="w-full bg-[#8B0000] hover:bg-red-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-900/20 transition-all duration-200 flex justify-center items-center gap-2 group mt-8 cursor-pointer disabled:opacity-75">
                            <template x-if="!submitting">
                                <span class="inline-flex items-center gap-2">
                                    <span>Sign In</span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </span>
                            </template>
                            <template x-if="submitting">
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-circle-notch fa-spin text-sm"></i>
                                    <span>Signing In...</span>
                                </span>
                            </template>
                        </button>
                    </form>

                    <div class="mt-10 pt-6 border-t border-slate-100 text-center">
                        <p class="text-slate-500 text-sm">
                            New to REO? <a href="{{ route('register') }}"
                                class="text-[#8B0000] font-bold hover:underline ml-1">Create an account</a>
                        </p>
                    </div>

                </div>

                <!-- MOBILE ONLY: Support Note -->
                <div class="mt-6 text-center text-xs text-slate-500 md:hidden">
                    Having trouble signing in? Contact the <a href="mailto:reo@wmsu.edu.ph" class="text-slate-700 underline font-semibold">Ethics Secretariat</a>
                </div>

                <!-- MOBILE ONLY: Legal Footer -->
                <div class="mt-6 pt-4 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-500 md:hidden">
                    <span>&copy; {{ date('Y') }} WMSU REOC</span>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('policy.privacy') }}" class="hover:underline">Privacy Policy</a>
                        <span>•</span>
                        <a href="{{ route('policy.terms') }}" class="hover:underline">Terms</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>