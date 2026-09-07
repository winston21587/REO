@php
    $logoUrl = asset('images/reoc-nobg.png');
    $bgImage = asset('images/wmsu2.jpg');
    $targetEmail = old('email', request('email') ?? '');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#8B0000">
    <meta name="description" content="Verify your research identity with Western Mindanao State University Research Ethics Oversight (WMSU REO).">
    <title>Verify Identity | WMSU REO</title>

    <!-- DNS Prefetch & Preconnect for High-Priority Assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Preload Critical LCP Background Image -->
    <link rel="preload" as="image" href="{{ $bgImage }}" fetchpriority="high">

    <!-- Critical Typography with font-display: swap -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
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
        @media (min-height: 520px) {
            body {
                height: 100vh;
                overflow: hidden;
            }
        }
        .font-heading { font-family: 'Montserrat', sans-serif; }
        .bg-\[\#8B0000\] { background-color: #8B0000; }

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

        /* Layout containment for smooth paint isolation */
        .contain-backdrop { contain: strict; }
        .contain-card { contain: layout style; }

        @media (prefers-reduced-motion: reduce) {
            *, ::before, ::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center bg-slate-950 text-slate-700 selection:bg-[#8B0000] selection:text-white relative p-2.5 sm:p-4 md:p-6">
    <x-toast />

    <!-- Ambient Photographic Backdrop (Neutral Dark Slate Vignette, 0 Red Wash, Authentic Campus Photo) -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden contain-backdrop" aria-hidden="true">
        <img src="{{ $bgImage }}"
            width="2048" height="1536"
            fetchpriority="high"
            decoding="async"
            alt="" class="w-full h-full object-cover opacity-55 filter brightness-90">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/65 to-slate-950/80"></div>
    </div>

    <!-- Main Verification Card Shell (Wide & Compact, Fits in 1 Screen) -->
    <main class="relative z-10 w-full max-w-3xl lg:max-w-[860px] my-auto contain-card animate-fade-in-up"
          x-data="verificationHandler({{ json_encode($targetEmail) }}, {{ (session('error') || $errors->any()) ? 'true' : 'false' }})">
        <div class="bg-white/98 backdrop-blur-xl rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden border border-slate-700/30 flex flex-col md:flex-row">
            
            <!-- ========================================================= -->
            <!-- LEFT PANEL: Institutional Identity & Security Notice      -->
            <!-- ========================================================= -->
            <aside class="md:w-[38%] lg:w-[36%] bg-gradient-to-b from-[#0b0f19] via-[#0f172a] to-[#020617] text-white p-5 sm:p-6 lg:p-7 flex flex-col justify-between relative overflow-hidden border-b md:border-b-0 md:border-r border-slate-800 shrink-0">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none" aria-hidden="true"></div>
                <div class="absolute -top-16 -right-16 w-36 h-36 bg-[#8B0000]/15 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>
                <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-slate-700/20 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>
                
                <div class="relative z-10">
                    <!-- Brand Identity Header -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500/15 via-white/10 to-slate-900/40 backdrop-blur-md flex items-center justify-center border border-red-500/30 shadow-lg shadow-red-950/40 shrink-0">
                            <i class="fas fa-shield-alt text-lg text-red-500 drop-shadow-[0_2px_8px_rgba(239,68,68,0.4)]"></i>
                        </div>
                        <div>
                            <span class="font-heading font-bold text-sm lg:text-base tracking-wide block leading-tight text-white">WMSU REO</span>
                            <span class="text-[10px] font-semibold text-red-300 uppercase tracking-wider block">Ethics Portal</span>
                        </div>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-heading font-extrabold text-white tracking-tight leading-snug">Verify Identity</h1>
                    <p class="text-slate-400 text-xs mt-1.5 leading-relaxed">Enter the 6-digit security code dispatched to your registered address.</p>

                    <!-- Security Notice Card (Desktop/Tablet) -->
                    <div class="hidden md:block bg-white/[0.05] p-3 rounded-xl border border-white/10 backdrop-blur-xs shadow-xs mt-4">
                        <div class="flex items-center gap-1.5 mb-1">
                            <i class="fas fa-lock text-xs text-red-400" aria-hidden="true"></i>
                            <h2 class="font-bold text-[10px] text-red-200 uppercase tracking-wider">Protocol Security</h2>
                        </div>
                        <p class="text-[11px] text-slate-300 leading-relaxed">Identity verification protects confidential ethical reviews and study submissions.</p>
                    </div>
                </div>

                <!-- Left Panel Footer (Desktop) -->
                <div class="hidden md:flex items-center justify-between pt-4 border-t border-slate-800/80 relative z-10 mt-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors group">
                        <i class="fas fa-arrow-left text-[10px] group-hover:-translate-x-0.5 transition-transform" aria-hidden="true"></i>
                        <span>Back to Login</span>
                    </a>
                    <span class="text-[10px] text-slate-500">WMSU &bull; REO</span>
                </div>
            </aside>

            <!-- ========================================================= -->
            <!-- RIGHT PANEL: Verification Form & Actions                  -->
            <!-- ========================================================= -->
            <section class="md:w-[62%] lg:w-[64%] p-5 sm:p-7 md:p-8 flex flex-col justify-center bg-white" aria-label="Verification Form">
                <div class="space-y-4 sm:space-y-4.5">
                    <!-- Email Recipient Pill -->
                    <div class="bg-slate-50 border border-slate-200/90 rounded-xl p-2.5 sm:p-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-red-50 text-[#8B0000] flex items-center justify-center text-xs shrink-0 border border-red-100" aria-hidden="true">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-500 leading-none mb-0.5">Code Sent To</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-900 truncate" title="{{ e($targetEmail ?: 'your registered email') }}">
                                    {{ $targetEmail ?: 'your registered email' }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('login') }}" class="text-[11px] font-semibold text-[#8B0000] hover:text-red-900 hover:underline shrink-0 px-1" title="Log in with a different account">
                            Change
                        </a>
                    </div>

                    <!-- OTP Input Form -->
                    <form action="{{ route('verify.submit') }}" method="POST" class="space-y-4 sm:space-y-4.5" id="verificationForm" @submit="handleFormSubmit($event)">
                        @csrf
                        <input type="hidden" name="email" value="{{ $targetEmail }}">
                        <input type="hidden" name="code" :value="fullCode">

                        <!-- Fallback for JavaScript-disabled environments -->
                        <noscript>
                            <div class="mb-3 p-2.5 bg-amber-50 border border-amber-200 text-amber-900 text-xs rounded-xl flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle text-amber-600 shrink-0"></i>
                                <span>JavaScript is disabled. Please enter your 6-digit code:</span>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="code" maxlength="6" pattern="[0-9]{6}" required
                                       class="w-full text-center text-2xl font-bold tracking-[0.5em] py-2.5 border-2 border-slate-300 rounded-xl bg-white"
                                       placeholder="000000" autocomplete="one-time-code">
                            </div>
                        </noscript>
                        
                        <fieldset class="space-y-2">
                            <div class="flex items-center justify-between text-xs font-semibold px-0.5">
                                <legend class="uppercase tracking-wider text-[11px] text-slate-600 font-bold">
                                    Enter 6-Digit Code
                                </legend>
                                <button type="button"
                                        x-show="fullCode.length > 0"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 scale-90"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-100"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-90"
                                        @click="clearAll()"
                                        class="min-h-[28px] text-[11px] font-semibold text-slate-400 hover:text-[#8B0000] transition-colors inline-flex items-center gap-1 focus:outline-none focus:underline"
                                        aria-label="Clear verification code">
                                    <i class="fas fa-times-circle text-[10px]" aria-hidden="true"></i>
                                    <span>Clear</span>
                                </button>
                            </div>

                            <!-- 6 Segmented Cells with 3-3 Cognitive Chunking & Fluid Adaptation -->
                            <div class="flex items-center justify-center gap-1 min-[360px]:gap-1.5 sm:gap-2.5" role="group" aria-label="6-digit security code">
                                <!-- Group 1: Digits 1-3 -->
                                <div class="flex items-center gap-1 min-[360px]:gap-1.5 sm:gap-2">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code"
                                           id="otp-cell-0" x-ref="cell0" x-model="digits[0]"
                                           @input="handleInput(0, $event)" @keydown="handleKeyDown(0, $event)" @paste="handlePaste($event)" @focus="handleFocus($event)"
                                           aria-label="Digit 1 of 6"
                                           :class="hasError ? 'border-rose-300 bg-rose-50/20' : (digits[0] ? 'border-slate-300 bg-white' : 'border-slate-200 bg-slate-50')"
                                           class="w-[36px] min-[360px]:w-[40px] sm:w-11 md:w-12 h-11 min-[360px]:h-12 sm:h-13 text-center font-bold text-xl min-[360px]:text-2xl sm:text-2xl text-slate-900 border-2 rounded-lg sm:rounded-xl focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-red-500/10 focus:outline-none transition-all shadow-sm">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code"
                                           id="otp-cell-1" x-ref="cell1" x-model="digits[1]"
                                           @input="handleInput(1, $event)" @keydown="handleKeyDown(1, $event)" @paste="handlePaste($event)" @focus="handleFocus($event)"
                                           aria-label="Digit 2 of 6"
                                           :class="hasError ? 'border-rose-300 bg-rose-50/20' : (digits[1] ? 'border-slate-300 bg-white' : 'border-slate-200 bg-slate-50')"
                                           class="w-[36px] min-[360px]:w-[40px] sm:w-11 md:w-12 h-11 min-[360px]:h-12 sm:h-13 text-center font-bold text-xl min-[360px]:text-2xl sm:text-2xl text-slate-900 border-2 rounded-lg sm:rounded-xl focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-red-500/10 focus:outline-none transition-all shadow-sm">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code"
                                           id="otp-cell-2" x-ref="cell2" x-model="digits[2]"
                                           @input="handleInput(2, $event)" @keydown="handleKeyDown(2, $event)" @paste="handlePaste($event)" @focus="handleFocus($event)"
                                           aria-label="Digit 3 of 6"
                                           :class="hasError ? 'border-rose-300 bg-rose-50/20' : (digits[2] ? 'border-slate-300 bg-white' : 'border-slate-200 bg-slate-50')"
                                           class="w-[36px] min-[360px]:w-[40px] sm:w-11 md:w-12 h-11 min-[360px]:h-12 sm:h-13 text-center font-bold text-xl min-[360px]:text-2xl sm:text-2xl text-slate-900 border-2 rounded-lg sm:rounded-xl focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-red-500/10 focus:outline-none transition-all shadow-sm">
                                </div>

                                <!-- Cognitive Chunk Separator -->
                                <div class="w-1.5 min-[360px]:w-2 sm:w-3 h-0.5 bg-slate-300 rounded-full mx-0.5 shrink-0" aria-hidden="true"></div>

                                <!-- Group 2: Digits 4-6 -->
                                <div class="flex items-center gap-1 min-[360px]:gap-1.5 sm:gap-2">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code"
                                           id="otp-cell-3" x-ref="cell3" x-model="digits[3]"
                                           @input="handleInput(3, $event)" @keydown="handleKeyDown(3, $event)" @paste="handlePaste($event)" @focus="handleFocus($event)"
                                           aria-label="Digit 4 of 6"
                                           :class="hasError ? 'border-rose-300 bg-rose-50/20' : (digits[3] ? 'border-slate-300 bg-white' : 'border-slate-200 bg-slate-50')"
                                           class="w-[36px] min-[360px]:w-[40px] sm:w-11 md:w-12 h-11 min-[360px]:h-12 sm:h-13 text-center font-bold text-xl min-[360px]:text-2xl sm:text-2xl text-slate-900 border-2 rounded-lg sm:rounded-xl focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-red-500/10 focus:outline-none transition-all shadow-sm">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code"
                                           id="otp-cell-4" x-ref="cell4" x-model="digits[4]"
                                           @input="handleInput(4, $event)" @keydown="handleKeyDown(4, $event)" @paste="handlePaste($event)" @focus="handleFocus($event)"
                                           aria-label="Digit 5 of 6"
                                           :class="hasError ? 'border-rose-300 bg-rose-50/20' : (digits[4] ? 'border-slate-300 bg-white' : 'border-slate-200 bg-slate-50')"
                                           class="w-[36px] min-[360px]:w-[40px] sm:w-11 md:w-12 h-11 min-[360px]:h-12 sm:h-13 text-center font-bold text-xl min-[360px]:text-2xl sm:text-2xl text-slate-900 border-2 rounded-lg sm:rounded-xl focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-red-500/10 focus:outline-none transition-all shadow-sm">
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="one-time-code"
                                           id="otp-cell-5" x-ref="cell5" x-model="digits[5]"
                                           @input="handleInput(5, $event)" @keydown="handleKeyDown(5, $event)" @paste="handlePaste($event)" @focus="handleFocus($event)"
                                           aria-label="Digit 6 of 6"
                                           :class="hasError ? 'border-rose-300 bg-rose-50/20' : (digits[5] ? 'border-slate-300 bg-white' : 'border-slate-200 bg-slate-50')"
                                           class="w-[36px] min-[360px]:w-[40px] sm:w-11 md:w-12 h-11 min-[360px]:h-12 sm:h-13 text-center font-bold text-xl min-[360px]:text-2xl sm:text-2xl text-slate-900 border-2 rounded-lg sm:rounded-xl focus:bg-white focus:border-[#8B0000] focus:ring-4 focus:ring-red-500/10 focus:outline-none transition-all shadow-sm">
                                </div>
                            </div>
                        </fieldset>

                        <!-- Submit Button with Reactive Color State: Calm resting slate -> Blooms to WMSU Maroon #8B0000 on 6 digits -->
                        <button type="submit"
                                :disabled="fullCode.length !== 6 || isSubmitting"
                                :aria-busy="isSubmitting.toString()"
                                :class="fullCode.length === 6 && !isSubmitting
                                    ? 'bg-[#8B0000] hover:bg-[#a00000] text-white shadow-lg shadow-red-900/25 hover:shadow-red-900/40 cursor-pointer'
                                    : 'bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed shadow-none'"
                                class="w-full min-h-[46px] sm:min-h-[48px] font-bold py-3 px-5 rounded-xl active:scale-[0.99] transition-all duration-200 text-sm sm:text-base tracking-wide flex items-center justify-center gap-2 group">
                            <template x-if="!isSubmitting">
                                <span class="inline-flex items-center gap-2">
                                    <span>Confirm Verification</span>
                                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform" aria-hidden="true"></i>
                                </span>
                            </template>
                            <template x-if="isSubmitting">
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-circle-notch fa-spin text-sm" aria-hidden="true"></i>
                                    <span>Verifying Code...</span>
                                </span>
                            </template>
                        </button>

                        <!-- Screen Reader Announcement Live Region -->
                        <div class="sr-only" aria-live="polite" aria-atomic="true" x-text="a11yAnnouncement"></div>
                    </form>

                    <!-- Navigation & Resend Actions -->
                    <div class="pt-3.5 border-t border-slate-100 text-center space-y-2.5">
                        <!-- Resend Row -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-1.5 text-xs text-slate-600">
                            <span>Didn't receive the code?</span>
                            <form action="{{ route('verify.resend') }}" method="POST" class="inline" @submit="handleResendSubmit">
                                @csrf
                                <input type="hidden" name="email" value="{{ $targetEmail }}">
                                <button type="submit"
                                        :disabled="cooldown > 0"
                                        class="min-h-[36px] px-2.5 py-1 text-xs font-bold text-[#8B0000] hover:text-red-900 hover:bg-red-50/80 active:bg-red-100/60 rounded-lg disabled:text-slate-400 disabled:hover:bg-transparent disabled:cursor-not-allowed transition-colors inline-flex items-center gap-1.5">
                                    <i class="fas fa-redo-alt text-[10px]" :class="{'fa-spin': isResending}" aria-hidden="true"></i>
                                    <span x-show="cooldown === 0">Resend Code</span>
                                    <span x-show="cooldown > 0" class="inline-flex items-center gap-1 font-medium text-slate-500">
                                        <span>Resend in</span>
                                        <span class="bg-red-50 text-[#8B0000] font-bold px-1.5 py-0.5 rounded-full text-xs" x-text="cooldown + 's'"></span>
                                    </span>
                                </button>
                            </form>
                        </div>

                        <!-- Closed Tab Helper Note -->
                        <div class="text-[11px] text-slate-500 leading-relaxed max-w-sm mx-auto flex items-center justify-center flex-wrap gap-1">
                            <span>Accidentally closed the tab?</span>
                            <a href="{{ route('login') }}" class="min-h-[32px] inline-flex items-center font-bold text-[#8B0000] hover:text-red-900 hover:underline underline-offset-2 px-1">
                                Log in
                            </a>
                            <span>with your registered email to resume.</span>
                        </div>

                        <!-- Mobile-only Back to Login -->
                        <div class="md:hidden pt-1">
                            <a href="{{ route('login') }}" class="min-h-[38px] px-3 py-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors inline-flex items-center justify-center gap-1.5">
                                <i class="fas fa-arrow-left text-[10px]" aria-hidden="true"></i>
                                <span>Back to Login</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Alpine.js OTP & Interaction Logic -->
    <script>
        function extractDigits(str) {
            if (!str) return '';
            // Normalize full-width Asian digits (０-９) to standard ASCII (0-9)
            const normalized = String(str).replace(/[\uFF10-\uFF19]/g, ch => String.fromCharCode(ch.charCodeAt(0) - 0xFEE0));
            // Extract numbers only
            return normalized.replace(/[^0-9]/g, '');
        }

        function verificationHandler(email, initialHasError) {
            return {
                digits: ['', '', '', '', '', ''],
                isSubmitting: false,
                isResending: false,
                cooldown: 0,
                timer: null,
                a11yAnnouncement: '',
                hasError: initialHasError || false,

                init() {
                    // Auto-focus first input cell
                    this.$nextTick(() => {
                        if (this.$refs.cell0) {
                            this.$refs.cell0.focus();
                        }
                    });

                    if (this.hasError) {
                        this.announce('Verification error: Please check your code and try again.');
                    }

                    // Check if there is an existing cooldown in localStorage
                    try {
                        const savedCooldown = localStorage.getItem('reo_otp_cooldown_' + email);
                        if (savedCooldown) {
                            const parsed = parseInt(savedCooldown, 10);
                            const remaining = Math.ceil((parsed - Date.now()) / 1000);
                            if (remaining > 0 && remaining <= 300) {
                                this.startCooldown(remaining);
                            } else {
                                localStorage.removeItem('reo_otp_cooldown_' + email);
                            }
                        }
                    } catch (e) {
                        console.warn('LocalStorage unavailable', e);
                    }
                },

                get fullCode() {
                    return this.digits.join('');
                },

                announce(msg) {
                    this.a11yAnnouncement = msg;
                },

                clearAll() {
                    this.hasError = false;
                    this.digits = ['', '', '', '', '', ''];
                    for (let i = 0; i < 6; i++) {
                        if (this.$refs['cell' + i]) this.$refs['cell' + i].value = '';
                    }
                    if (this.$refs.cell0) this.$refs.cell0.focus();
                    this.announce('Verification code cleared.');
                },

                handleFocus(event) {
                    event.target.select();
                    if (window.visualViewport) {
                        setTimeout(() => {
                            event.target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);
                    }
                },

                handleInput(idx, event) {
                    this.hasError = false;
                    const input = event.target;
                    let val = extractDigits(input.value);

                    // Support mobile 1-tap SMS/email code autofill
                    if (val.length > 1) {
                        const cleanDigits = val.slice(0, 6);
                        for (let i = 0; i < 6; i++) {
                            const char = cleanDigits[i] || '';
                            this.digits[i] = char;
                            const cell = this.$refs['cell' + i];
                            if (cell) cell.value = char;
                        }
                        const targetIndex = Math.min(cleanDigits.length, 5);
                        const focusCell = this.$refs['cell' + targetIndex];
                        if (focusCell) {
                            focusCell.focus();
                            focusCell.select();
                        }
                        if (this.fullCode.length === 6) {
                            this.announce('6-digit code autofilled. Ready to confirm.');
                        }
                        return;
                    }

                    this.digits[idx] = val;
                    input.value = val;

                    if (val && idx < 5) {
                        const nextCell = this.$refs['cell' + (idx + 1)];
                        if (nextCell) {
                            nextCell.focus();
                            nextCell.select();
                        }
                    }

                    if (this.fullCode.length === 6) {
                        this.announce('All 6 digits entered.');
                    }
                },

                handleKeyDown(idx, event) {
                    if (event.key === 'Backspace') {
                        if (!this.digits[idx] && idx > 0) {
                            const prevCell = this.$refs['cell' + (idx - 1)];
                            if (prevCell) {
                                prevCell.focus();
                                this.digits[idx - 1] = '';
                                prevCell.value = '';
                                event.preventDefault();
                            }
                        } else {
                            this.digits[idx] = '';
                        }
                    } else if (event.key === 'Escape') {
                        this.clearAll();
                        event.preventDefault();
                    } else if (event.key === 'Enter') {
                        if (this.fullCode.length < 6) {
                            event.preventDefault();
                            const nextEmpty = this.digits.findIndex(d => !d);
                            if (nextEmpty !== -1 && this.$refs['cell' + nextEmpty]) {
                                this.$refs['cell' + nextEmpty].focus();
                            }
                        }
                    } else if (event.key === 'ArrowLeft' && idx > 0) {
                        const prevCell = this.$refs['cell' + (idx - 1)];
                        if (prevCell) {
                            prevCell.focus();
                            prevCell.select();
                        }
                    } else if (event.key === 'ArrowRight' && idx < 5) {
                        const nextCell = this.$refs['cell' + (idx + 1)];
                        if (nextCell) {
                            nextCell.focus();
                            nextCell.select();
                        }
                    }
                },

                handlePaste(event) {
                    this.hasError = false;
                    event.preventDefault();
                    const pasteData = (event.clipboardData || window.clipboardData).getData('text');
                    const cleanDigits = extractDigits(pasteData).slice(0, 6);

                    if (!cleanDigits) return;

                    for (let i = 0; i < 6; i++) {
                        const char = cleanDigits[i] || '';
                        this.digits[i] = char;
                        const cell = this.$refs['cell' + i];
                        if (cell) cell.value = char;
                    }

                    const targetIndex = Math.min(cleanDigits.length, 5);
                    const focusCell = this.$refs['cell' + (targetIndex < 6 ? targetIndex : 5)];
                    if (focusCell) {
                        focusCell.focus();
                        focusCell.select();
                    }

                    if (this.fullCode.length === 6) {
                        this.announce('6-digit code pasted. Ready to confirm.');
                    }
                },

                handleFormSubmit(event) {
                    if (this.fullCode.length !== 6 || this.isSubmitting) {
                        event.preventDefault();
                        const nextEmpty = this.digits.findIndex(d => !d);
                        if (nextEmpty !== -1 && this.$refs['cell' + nextEmpty]) {
                            this.$refs['cell' + nextEmpty].focus();
                        }
                        return false;
                    }
                    this.isSubmitting = true;
                    this.announce('Verifying code, please wait...');
                },

                handleResendSubmit() {
                    this.isResending = true;
                    this.announce('Resending verification code...');
                    this.startCooldown(60);
                },

                startCooldown(seconds) {
                    this.cooldown = seconds;
                    try {
                        const expireAt = Date.now() + (seconds * 1000);
                        localStorage.setItem('reo_otp_cooldown_' + email, expireAt.toString());
                    } catch (e) {}

                    if (this.timer) clearInterval(this.timer);
                    this.timer = setInterval(() => {
                        this.cooldown--;
                        if (this.cooldown <= 0) {
                            clearInterval(this.timer);
                            this.cooldown = 0;
                            try {
                                localStorage.removeItem('reo_otp_cooldown_' + email);
                            } catch (e) {}
                        }
                    }, 1000);
                }
            };
        }
    </script>
</body>
</html>