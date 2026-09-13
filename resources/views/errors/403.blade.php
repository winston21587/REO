<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 • Access Restricted — WMSU REO</title>
    <link rel="icon" type="image/png" href="{{ asset('images/reoc-nobg.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
        .font-heading {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-[#fbf9f9] text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-[#8B0000] selection:text-white">

    <!-- ===== INSTITUTIONAL HEADER BAR ===== -->
    <header class="w-full bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center shrink-0 p-1.5">
                <img src="{{ asset('images/reoc-nobg.png') }}"
                     alt="WMSU REO Seal"
                     width="32"
                     height="32"
                     class="w-full h-full object-contain"
                     onerror="this.onerror=null; this.src='{{ asset('images/wmsu-logo.png') }}';">
            </div>
            <div class="min-w-0">
                <h1 class="font-heading font-extrabold text-sm sm:text-base text-slate-900 tracking-tight leading-tight truncate">
                    WMSU REO
                </h1>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider truncate">
                    Research Ethics Oversight Committee
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" aria-hidden="true"></span>
            <span class="hidden sm:inline">Protected Session</span>
            <span class="sm:hidden">Secured</span>
        </div>
    </header>

    <!-- ===== MAIN CONTENT BODY ===== -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 md:p-8 relative overflow-hidden">
        <!-- Ambient Watermark & Crimson Glow -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none -z-10 opacity-3">
            <img src="{{ asset('images/reoc-nobg.png') }}" alt="" class="w-[520px] h-[520px] object-contain">
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-red-100/40 rounded-full blur-3xl pointer-events-none -z-10" aria-hidden="true"></div>

        <!-- Centered Institutional Card -->
        <div class="w-full max-w-lg bg-white rounded-2xl border border-slate-200/90 shadow-xl shadow-slate-900/5 overflow-hidden animate-[fadeInUp_0.4s_ease-out]">
            
            <!-- Top Crimson Accent Bar -->
            <div class="h-1.5 bg-gradient-to-r from-[#8B0000] via-[#B22222] to-[#8B0000]"></div>

            <div class="p-6 sm:p-8 text-center">

                <!-- Shield Badge -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-red-50 border border-red-100 text-[#8B0000] flex items-center justify-center mx-auto mb-5 shadow-xs">
                    <i class="fas fa-shield-halved text-2xl sm:text-3xl" aria-hidden="true"></i>
                </div>

                <!-- Headline -->
                <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight mb-2">
                    Access Restricted
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 mb-6 leading-relaxed">
                    The requested portal page is reserved for authorized institutional roles per WMSU REOC Standard Operating Procedures.
                </p>

                <!-- Active Session Diagnostic Card -->
                @auth
                    @php
                        $user = Auth::user();
                        $roleSlug = $user->role;
                        $roleTitle = match($roleSlug) {
                            'super_admin' => 'Super Administrator',
                            'admin'       => 'Administrator',
                            'reviewer'    => 'Ethics Reviewer',
                            'researcher'  => 'Research Proponent',
                            default       => 'Authorized User',
                        };
                        $roleBadgeColor = match($roleSlug) {
                            'super_admin' => 'bg-purple-100 text-purple-900 border-purple-200',
                            'admin'       => 'bg-amber-100 text-amber-900 border-amber-200',
                            'reviewer'    => 'bg-rose-100 text-rose-900 border-rose-200',
                            default       => 'bg-blue-100 text-blue-900 border-blue-200',
                        };
                    @endphp
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 sm:p-4.5 text-left mb-6 space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-lg bg-[#8B0000] text-white font-bold text-xs flex items-center justify-center shrink-0 uppercase">
                                    {{ substr($user->first_name ?? 'U', 0, 1) }}{{ substr($user->last_name ?? '', 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate font-mono">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border shrink-0 {{ $roleBadgeColor }}">
                                {{ $roleTitle }}
                            </span>
                        </div>

                        <div class="border-t border-slate-200/70 pt-2.5">
                            <p class="text-[11px] text-slate-600 leading-snug">
                                Signed in with <strong class="text-slate-800">{{ $roleTitle }}</strong> privileges. This section requires different institutional permissions.
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('dashboard') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#8B0000] hover:bg-[#700000] active:scale-[0.98] text-white font-bold text-xs sm:text-sm shadow-md shadow-[#8B0000]/20 hover:shadow-lg transition-all duration-150 min-h-[44px] cursor-pointer focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#8B0000]">
                            <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i>
                            <span>Return to My Dashboard</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white hover:bg-slate-50 active:scale-[0.98] border border-slate-300 text-slate-700 font-semibold text-xs sm:text-sm shadow-2xs hover:border-slate-400 transition-all duration-150 min-h-[44px] cursor-pointer focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-400">
                                <i class="fas fa-sign-out-alt text-xs text-slate-500" aria-hidden="true"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>

                @else
                    <!-- Guest View -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 text-left mb-6">
                        <p class="text-xs text-slate-600 leading-relaxed">
                            You are not currently authenticated. Please sign in with an authorized institutional account to access this protocol ledger.
                        </p>
                    </div>

                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#8B0000] hover:bg-[#700000] active:scale-[0.98] text-white font-bold text-sm shadow-md hover:shadow-lg transition-all duration-150 min-h-[44px] cursor-pointer focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#8B0000]">
                        <i class="fas fa-sign-in-alt text-xs" aria-hidden="true"></i>
                        <span>Sign In to Portal</span>
                    </a>
                @endauth

            </div>
        </div>
    </main>

    <!-- ===== INSTITUTIONAL FOOTER BAR ===== -->
    <footer class="w-full border-t border-slate-200/80 bg-white/80 backdrop-blur-xs py-3 px-4 sm:px-8 text-center text-[11px] text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-1.5">
        <span>Western Mindanao State University • Research Ethics Office (REO)</span>
        <span class="font-mono text-[10px] text-slate-400">PHREB Level III Accredited • SOP Section 2.1</span>
    </footer>

</body>
</html>
