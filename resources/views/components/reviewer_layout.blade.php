<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Reviewer Portal' }} • WMSU REO</title>
    <link rel="icon" type="image/png" href="{{ asset('images/reoc-nobg.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- impeccable-disable overused-font -- institutional brand fonts defined in DESIGN.md -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    @php
        $logoUrl = !empty($cms['website_logo']) ? asset($cms['website_logo']) : asset('images/reoc-nobg.png');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $logoUrl }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif; /* impeccable-disable-line overused-font */
        }

        .font-heading {
            font-family: 'Montserrat', sans-serif;
        }

        /* Active Link Styling (Desktop & Mobile Drawer) */
        .nav-item.active {
            background: rgba(139, 0, 0, 0.25);
            border: 1px solid rgba(139, 0, 0, 0.45);
            color: white;
            font-weight: 600;
            border-radius: 0.75rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        main ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        main ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        main ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Completely hide scrollbars across all browsers */
        .sidebar-no-scrollbar,
        .no-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }
        .sidebar-no-scrollbar::-webkit-scrollbar,
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
    </style>
</head>

<body class="bg-surface-canvas text-slate-800 antialiased min-h-screen flex flex-col selection:bg-brand-primary/10 selection:text-brand-primary print:bg-white print:text-black"
      x-data="{ mobileDrawerOpen: false }"
      @keydown.escape.window="mobileDrawerOpen = false">

    <!-- Overall Layout Shell -->
    <div class="flex h-screen overflow-hidden w-full relative print:h-auto print:overflow-visible print:block">        <!-- ===== DESKTOP SIDEBAR (Visible lg:flex, hidden on mobile) ===== -->
        <aside class="hidden lg:flex lg:w-64 bg-[#1a0505] text-white flex-col border-r border-white/5 shrink-0 z-50 relative print:hidden">
            <!-- Logo & User Identity Area -->
            <div class="h-20 shrink-0 flex items-center justify-start border-b border-white/5 bg-gradient-to-r from-[#8B0000]/20 to-transparent relative">
                <div class="flex items-center gap-3 px-5 min-w-0 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center shadow-md overflow-hidden transition-all duration-300 shrink-0 border border-white/10">
                        <img src="{{ $logoUrl }}" 
                             alt="WMSU REO Logo" 
                             width="28" 
                             height="28" 
                             fetchpriority="high" 
                             class="h-7 w-auto object-contain"
                             onerror="this.onerror=null; this.src='{{ asset('images/reoc-nobg.png') }}';">
                    </div>
                    <span class="font-heading font-extrabold text-base text-white tracking-tight truncate block min-w-0"
                          title="{{ Auth::user()->first_name ?? 'Reviewer' }} {{ Auth::user()->last_name ?? '' }}">
                        {{ Auth::user()->first_name ?? 'Reviewer' }} {{ Auth::user()->last_name ?? '' }}
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 p-3.5 space-y-6 overflow-y-auto" aria-label="Reviewer primary navigation">
                <!-- Protocol Reviews -->
                <div>
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Protocol Reviews</p>
                    <div class="space-y-1">
                        <!-- Assigned Protocols -->
                        <a href="{{ route('reviewer.dashboard') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 group relative text-sm font-medium focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary min-h-[44px] {{ request()->routeIs('reviewer.dashboard') ? 'nav-item active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-clipboard-list w-5 text-center {{ request()->routeIs('reviewer.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            <span class="whitespace-nowrap">Assigned Protocols</span>
                        </a>

                        <!-- Reviewed Protocols -->
                        <a href="{{ route('reviewer.reviewed_titles') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 group relative text-sm font-medium focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary min-h-[44px] {{ request()->routeIs('reviewer.reviewed_titles') ? 'nav-item active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-check-circle w-5 text-center {{ request()->routeIs('reviewer.reviewed_titles') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            <span class="whitespace-nowrap">Reviewed Protocols</span>
                        </a>

                        <!-- Re-Evaluation -->
                        <a href="{{ route('reviewer.reevaluation') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 group relative text-sm font-medium focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary min-h-[44px] {{ request()->routeIs('reviewer.reevaluation') ? 'nav-item active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-rotate w-5 text-center {{ request()->routeIs('reviewer.reevaluation') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            <span class="whitespace-nowrap">Re-Evaluation</span>
                        </a>

                        <!-- Meetings & Agenda -->
                        <a href="{{ route('reviewer.meetings') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 group relative text-sm font-medium focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary min-h-[44px] {{ request()->routeIs('reviewer.meetings*') ? 'nav-item active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-calendar-check w-5 text-center {{ request()->routeIs('reviewer.meetings*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            <span class="whitespace-nowrap">Meetings & Agenda</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Sign Out (Matching Researcher side) -->
            <div class="p-3.5 border-t border-white/10 bg-black/20 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-300 hover:bg-white/5 hover:text-white transition-all duration-200 group cursor-pointer focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary min-h-[44px]">
                        <i class="fas fa-sign-out-alt w-5 text-center transition-colors text-slate-400 group-hover:text-white"></i>
                        <span class="whitespace-nowrap text-sm font-medium">Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ===== MOBILE DRAWER (Slide-over panel for small/medium devices) ===== -->
        <div x-show="mobileDrawerOpen" 
             x-cloak
             style="display: none;"
             class="fixed inset-0 z-50 lg:hidden" 
             role="dialog" 
             aria-modal="true"
             aria-label="Reviewer Navigation Menu">
            
            <!-- Backdrop Overlay -->
            <div x-show="mobileDrawerOpen"
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileDrawerOpen = false"
                 class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs"></div>

            <!-- Drawer Container (Redesigned for Mobile) -->
            <div x-show="mobileDrawerOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative w-[85vw] max-w-xs h-full bg-gradient-to-b from-[#1e0707] via-[#140404] to-[#0c0202] text-white flex flex-col shadow-2xl z-50 border-r border-white/10">
                
                <!-- Drawer Header: Reviewer Identity Card -->
                <div class="p-5 border-b border-white/10 bg-gradient-to-r from-brand-primary/20 via-transparent to-transparent shrink-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="relative shrink-0">
                                <div class="w-11 h-11 rounded-2xl bg-white/10 border border-white/15 p-1 flex items-center justify-center shadow-md">
                                    <img src="{{ $logoUrl }}" 
                                         alt="WMSU REO Logo" 
                                         width="32" 
                                         height="32" 
                                         class="h-8 w-auto object-contain"
                                         onerror="this.onerror=null; this.src='{{ asset('images/reoc-nobg.png') }}';">
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-[#1e0707]"></span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-heading font-bold text-sm text-white tracking-tight truncate"
                                    title="{{ Auth::user()->first_name ?? 'Reviewer' }} {{ Auth::user()->last_name ?? '' }}">
                                    {{ Auth::user()->first_name ?? 'Reviewer' }} {{ Auth::user()->last_name ?? '' }}
                                </h3>
                                <p class="text-[11px] font-semibold text-rose-300/80 uppercase tracking-wider truncate mt-0.5">
                                    {{ Auth::user()->reviewer?->college ?? 'Ethics Reviewer' }}
                                </p>
                            </div>
                        </div>

                        <!-- Close Button -->
                        <button @click="mobileDrawerOpen = false" 
                                aria-label="Close navigation menu"
                                class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer shrink-0">
                            <i class="fas fa-times text-xs" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <!-- Drawer Scrollable Content -->
                <div class="flex-1 p-3.5 pb-6 space-y-5 overflow-y-auto no-scrollbar">
                    
                    <!-- Protocol Reviews Group -->
                    <div>
                        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center justify-between">
                            <span>Protocol Reviews</span>
                            <span class="text-[10px] text-brand-primary/80 font-semibold lowercase">portal</span>
                        </p>
                        <div class="space-y-1.5">
                            <!-- Assigned Protocols -->
                            <a href="{{ route('reviewer.dashboard') }}"
                               @click="mobileDrawerOpen = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group min-h-[44px] {{ request()->routeIs('reviewer.dashboard') ? 'bg-gradient-to-r from-brand-primary/40 to-brand-primary/10 border border-brand-primary/50 text-white shadow-xs' : 'text-slate-300 hover:bg-white/5 hover:text-white border border-transparent' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('reviewer.dashboard') ? 'bg-brand-primary text-white shadow-xs' : 'bg-white/5 text-slate-400 group-hover:text-white' }}">
                                    <i class="fas fa-clipboard-list text-xs"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-semibold block truncate leading-tight">Assigned Protocols</span>
                                    <span class="text-[10px] text-slate-400 block truncate leading-normal">Active initial reviews</span>
                                </div>
                                @if(request()->routeIs('reviewer.dashboard'))
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-primary shrink-0 animate-pulse"></span>
                                @endif
                            </a>

                            <!-- Reviewed Protocols -->
                            <a href="{{ route('reviewer.reviewed_titles') }}"
                               @click="mobileDrawerOpen = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group min-h-[44px] {{ request()->routeIs('reviewer.reviewed_titles') ? 'bg-gradient-to-r from-brand-primary/40 to-brand-primary/10 border border-brand-primary/50 text-white shadow-xs' : 'text-slate-300 hover:bg-white/5 hover:text-white border border-transparent' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('reviewer.reviewed_titles') ? 'bg-brand-primary text-white shadow-xs' : 'bg-white/5 text-slate-400 group-hover:text-white' }}">
                                    <i class="fas fa-check-circle text-xs"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-semibold block truncate leading-tight">Reviewed Protocols</span>
                                    <span class="text-[10px] text-slate-400 block truncate leading-normal">Evaluated archival ledger</span>
                                </div>
                                @if(request()->routeIs('reviewer.reviewed_titles'))
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-primary shrink-0 animate-pulse"></span>
                                @endif
                            </a>

                            <!-- Re-Evaluation -->
                            <a href="{{ route('reviewer.reevaluation') }}"
                               @click="mobileDrawerOpen = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group min-h-[44px] {{ request()->routeIs('reviewer.reevaluation') ? 'bg-gradient-to-r from-brand-primary/40 to-brand-primary/10 border border-brand-primary/50 text-white shadow-xs' : 'text-slate-300 hover:bg-white/5 hover:text-white border border-transparent' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('reviewer.reevaluation') ? 'bg-brand-primary text-white shadow-xs' : 'bg-white/5 text-slate-400 group-hover:text-white' }}">
                                    <i class="fas fa-rotate text-xs"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-semibold block truncate leading-tight">Re-Evaluation</span>
                                    <span class="text-[10px] text-slate-400 block truncate leading-normal">Resubmitted protocol revisions</span>
                                </div>
                                @if(request()->routeIs('reviewer.reevaluation'))
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-primary shrink-0 animate-pulse"></span>
                                @endif
                            </a>

                            <!-- Meetings & Agenda -->
                            <a href="{{ route('reviewer.meetings') }}"
                               @click="mobileDrawerOpen = false"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group min-h-[44px] {{ request()->routeIs('reviewer.meetings*') ? 'bg-gradient-to-r from-brand-primary/40 to-brand-primary/10 border border-brand-primary/50 text-white shadow-xs' : 'text-slate-300 hover:bg-white/5 hover:text-white border border-transparent' }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('reviewer.meetings*') ? 'bg-brand-primary text-white shadow-xs' : 'bg-white/5 text-slate-400 group-hover:text-white' }}">
                                    <i class="fas fa-calendar-check text-xs"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-semibold block truncate leading-tight">Meetings & Agenda</span>
                                    <span class="text-[10px] text-slate-400 block truncate leading-normal">Deliberation schedules & RSVP</span>
                                </div>
                                @if(request()->routeIs('reviewer.meetings*'))
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-primary shrink-0 animate-pulse"></span>
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Quick Resources & University Navigation -->
                    <div>
                        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Institutional Resources</p>
                        <div class="space-y-1.5">
                            <a href="{{ route('index') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-slate-300 hover:bg-white/5 hover:text-white transition-all duration-200 group min-h-[44px] border border-transparent">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center shrink-0 text-slate-400 group-hover:text-white">
                                        <i class="fas fa-building-columns text-xs"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs font-medium block truncate">WMSU REO Portal</span>
                                        <span class="text-[10px] text-slate-500 block truncate">Public research ethics gateway</span>
                                    </div>
                                </div>
                                <i class="fas fa-arrow-up-right-from-square text-[10px] text-slate-500 group-hover:text-slate-300 shrink-0"></i>
                            </a>

                            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-xs text-slate-400 space-y-1.5">
                                <div class="flex items-center justify-between text-[11px] font-medium text-slate-300">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-eye-slash text-[10px] text-brand-primary"></i>
                                        Reviewer Anonymity
                                    </span>
                                    <span class="text-[10px] font-mono text-emerald-400 uppercase">Protected</span>
                                </div>
                                <p class="text-[10px] text-slate-500 leading-tight">
                                    Evaluations submitted through this portal are anonymized per WMSU SOP Section 4.3.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Drawer Footer: System Trust & Sign Out -->
                <div class="p-4 border-t border-white/10 bg-black/40 shrink-0 space-y-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-rose-200 hover:text-white bg-white/[0.04] hover:bg-brand-primary/30 border border-white/10 hover:border-brand-primary/50 transition-all duration-200 group cursor-pointer min-h-[44px]">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-sign-out-alt text-xs text-rose-400 group-hover:text-white transition-colors"></i>
                                <span class="text-xs font-semibold">Sign Out</span>
                            </div>
                            <i class="fas fa-arrow-right text-[10px] text-slate-500 group-hover:text-white group-hover:translate-x-0.5 transition-all"></i>
                        </button>
                    </form>

                    <div class="flex items-center justify-between text-[10px] text-slate-500 font-mono pt-1">
                        <span>WMSU REOC v2.4</span>
                        <span>Authorized Session</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== MAIN CONTENT AREA ===== -->
        <main class="flex-1 flex flex-col h-full overflow-hidden relative bg-surface-canvas min-w-0 print:h-auto print:overflow-visible print:bg-white">

            <!-- ===== MOBILE TOP BAR (Visible on Mobile/Tablet) ===== -->
            <header class="flex lg:hidden h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 items-center justify-between px-4 sticky top-0 z-30 shrink-0 print:hidden">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center shrink-0">
                        <img src="{{ $logoUrl }}" 
                             alt="REO" 
                             width="24"
                             height="24"
                             fetchpriority="high"
                             class="w-6 h-6 object-contain"
                             onerror="this.onerror=null; this.src='{{ asset('images/reoc-nobg.png') }}';">
                    </div>
                    <div class="min-w-0">
                        <h2 class="font-heading font-bold text-sm text-slate-900 truncate leading-tight">WMSU REO</h2>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider truncate">Reviewer Portal</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Mobile Drawer Toggle Button -->
                    <button @click="mobileDrawerOpen = true"
                            aria-label="Open reviewer menu"
                            class="w-11 h-11 min-h-[44px] min-w-[44px] rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-700 active:bg-slate-100 cursor-pointer focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary">
                        <i class="fas fa-bars text-sm" aria-hidden="true"></i>
                    </button>
                </div>
            </header>

            <!-- Page Body with Mobile Bottom Clearance -->
            <div class="flex-1 {{ request()->routeIs('reviewer.view_files*') ? 'overflow-y-auto lg:overflow-hidden p-3 sm:p-4 lg:p-3.5 pb-[max(5.5rem,calc(env(safe-area-inset-bottom)+4.5rem))] lg:pb-3.5 min-h-0' : 'overflow-y-auto p-4 sm:p-6 lg:p-8 pb-[max(6.5rem,calc(env(safe-area-inset-bottom)+5.5rem))] lg:pb-8' }} flex flex-col relative z-0 print:overflow-visible print:p-0 print:pb-0">
                <x-boneyard-skeleton role="reviewer" type="reviewer" contentId="reviewer-page-content" />
                <div id="reviewer-page-content" class="w-full flex-1 min-h-0 flex flex-col">
                    {{ $slot }}
                </div>
            </div>

            <!-- ===== MOBILE BOTTOM NAVIGATION BAR (Visible on Mobile/Tablet) ===== -->
            <nav class="flex lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/90 px-2 pt-1.5 justify-around items-center shadow-lg shadow-slate-950/5 print:hidden"
                 style="padding-bottom: max(0.375rem, env(safe-area-inset-bottom));"
                 aria-label="Reviewer Mobile Navigation">
                
                <!-- Assigned Protocols -->
                <a href="{{ route('reviewer.dashboard') }}"
                   class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all active:scale-95 duration-150 min-h-[48px] min-w-[64px] {{ request()->routeIs('reviewer.dashboard') ? 'text-brand-primary font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                    <i class="fas fa-clipboard-list text-base mb-1" aria-hidden="true"></i>
                    <span class="text-[10px] tracking-tight">Assigned</span>
                    @if(request()->routeIs('reviewer.dashboard'))
                        <span class="w-1 h-1 rounded-full bg-brand-primary mt-0.5"></span>
                    @endif
                </a>

                <!-- Reviewed Protocols -->
                <a href="{{ route('reviewer.reviewed_titles') }}"
                   class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all active:scale-95 duration-150 min-h-[48px] min-w-[64px] {{ request()->routeIs('reviewer.reviewed_titles') ? 'text-brand-primary font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                    <i class="fas fa-check-circle text-base mb-1" aria-hidden="true"></i>
                    <span class="text-[10px] tracking-tight">Reviewed</span>
                    @if(request()->routeIs('reviewer.reviewed_titles'))
                        <span class="w-1 h-1 rounded-full bg-brand-primary mt-0.5"></span>
                    @endif
                </a>

                <!-- Re-Evaluation -->
                <a href="{{ route('reviewer.reevaluation') }}"
                   class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all active:scale-95 duration-150 min-h-[48px] min-w-[64px] {{ request()->routeIs('reviewer.reevaluation') ? 'text-brand-primary font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                    <i class="fas fa-rotate text-base mb-1" aria-hidden="true"></i>
                    <span class="text-[10px] tracking-tight">Re-Eval</span>
                    @if(request()->routeIs('reviewer.reevaluation'))
                        <span class="w-1 h-1 rounded-full bg-brand-primary mt-0.5"></span>
                    @endif
                </a>

                <!-- Meetings & Agenda -->
                <a href="{{ route('reviewer.meetings') }}"
                   class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all active:scale-95 duration-150 min-h-[48px] min-w-[64px] {{ request()->routeIs('reviewer.meetings*') ? 'text-brand-primary font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                    <i class="fas fa-calendar-check text-base mb-1" aria-hidden="true"></i>
                    <span class="text-[10px] tracking-tight">Meetings</span>
                    @if(request()->routeIs('reviewer.meetings*'))
                        <span class="w-1 h-1 rounded-full bg-brand-primary mt-0.5"></span>
                    @endif
                </a>
            </nav>

        </main>
    </div>

    <x-toast />
</body>

</html>