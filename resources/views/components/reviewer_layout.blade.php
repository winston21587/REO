<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Reviewer Portal' }} • WMSU REO</title>

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
            background: linear-gradient(to right, rgba(139, 0, 0, 0.2), transparent);
            border-left: 2px solid #8B0000;
            color: white;
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
    </style>
</head>

<body class="bg-surface-canvas text-slate-800 antialiased min-h-screen flex flex-col selection:bg-brand-primary/10 selection:text-brand-primary print:bg-white print:text-black"
      x-data="{ mobileDrawerOpen: false }"
      @keydown.escape.window="mobileDrawerOpen = false">

    <!-- Overall Layout Shell -->
    <div class="flex h-screen overflow-hidden w-full relative print:h-auto print:overflow-visible print:block">

        <!-- ===== DESKTOP SIDEBAR (Visible lg:flex, hidden on mobile) ===== -->
        <aside class="hidden lg:flex lg:w-64 bg-[#1a0505] text-white flex-col border-r border-white/5 shrink-0 z-50 relative print:hidden">
            <!-- Logo Area (Matching Researcher side) -->
            <div class="h-20 shrink-0 flex items-center justify-start border-b border-white/5 bg-gradient-to-r from-[#8B0000]/20 to-transparent relative">
                <div class="flex items-center gap-3 px-6 min-w-0 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-lg overflow-hidden transition-all duration-300 flex-shrink-0">
                        <img src="{{ $logoUrl }}" 
                             alt="WMSU REO Logo" 
                             width="28"
                             height="28"
                             fetchpriority="high"
                             class="h-7 w-auto object-contain"
                             onerror="this.onerror=null; this.src='{{ asset('images/reoc-nobg.png') }}';">
                    </div>
                    <span class="font-heading font-extrabold text-lg text-white tracking-tight truncate block min-w-0"
                          title="{{ Auth::user()->first_name ?? 'Reviewer' }} {{ Auth::user()->last_name ?? '' }}">
                        {{ Auth::user()->first_name ?? 'Reviewer' }} {{ Auth::user()->last_name ?? '' }}
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 p-4 space-y-6 overflow-y-auto" aria-label="Reviewer primary navigation">
                <!-- Protocol Reviews -->
                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Protocol Reviews</p>
                    <div class="space-y-1">
                        <!-- Assigned Protocols -->
                        <a href="{{ route('reviewer.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group relative text-sm font-medium focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 focus-visible:ring-offset-[#1a0505] {{ request()->routeIs('reviewer.dashboard') ? 'nav-item active font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-clipboard-list w-5 text-center {{ request()->routeIs('reviewer.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            <span class="whitespace-nowrap">Assigned Protocols</span>
                        </a>

                        <!-- Reviewed Protocols -->
                        <a href="{{ route('reviewer.reviewed_titles') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group relative text-sm font-medium focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 focus-visible:ring-offset-[#1a0505] {{ request()->routeIs('reviewer.reviewed_titles') ? 'nav-item active font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-check-circle w-5 text-center {{ request()->routeIs('reviewer.reviewed_titles') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            <span class="whitespace-nowrap">Reviewed Protocols</span>
                        </a>

                        <!-- Re-Evaluation -->
                        <a href="{{ route('reviewer.reevaluation') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group relative text-sm font-medium focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 focus-visible:ring-offset-[#1a0505] {{ request()->routeIs('reviewer.reevaluation') ? 'nav-item active font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-rotate w-5 text-center {{ request()->routeIs('reviewer.reevaluation') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                            <span class="whitespace-nowrap">Re-Evaluation</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Sign Out (Matching Researcher side) -->
            <div class="p-4 border-t border-white/10 bg-black/20 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-r-lg text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200 group cursor-pointer focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 focus-visible:ring-offset-[#1a0505]">
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

            <!-- Drawer Container -->
            <div x-show="mobileDrawerOpen"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative w-4/5 max-w-xs h-full bg-[#1a0505] text-white flex flex-col shadow-2xl z-50">
                
                <!-- Drawer Header (Matching Researcher side) -->
                <div class="h-20 shrink-0 flex items-center justify-between px-6 border-b border-white/5 bg-gradient-to-r from-[#8B0000]/20 to-transparent relative">
                    <div class="flex items-center gap-3 min-w-0 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-lg overflow-hidden shrink-0">
                            <img src="{{ $logoUrl }}" 
                                 alt="WMSU REO Logo" 
                                 width="28"
                                 height="28"
                                 loading="lazy"
                                 class="h-7 w-auto object-contain"
                                 onerror="this.onerror=null; this.src='{{ asset('images/reoc-nobg.png') }}';">
                        </div>
                        <span class="font-heading font-extrabold text-base text-white tracking-tight truncate block min-w-0"
                              title="{{ Auth::user()->first_name ?? 'Reviewer' }} {{ Auth::user()->last_name ?? '' }}">
                            {{ Auth::user()->first_name ?? 'Reviewer' }} {{ Auth::user()->last_name ?? '' }}
                        </span>
                    </div>
                    <button @click="mobileDrawerOpen = false" 
                            aria-label="Close navigation menu"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-colors focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary">
                        <i class="fas fa-times text-sm" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Drawer Links -->
                <nav class="flex-1 p-4 space-y-6 overflow-y-auto">
                    <div>
                        <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Protocol Reviews</p>
                        <div class="space-y-1">
                            <a href="{{ route('reviewer.dashboard') }}"
                               @click="mobileDrawerOpen = false"
                               class="flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group relative text-sm font-medium {{ request()->routeIs('reviewer.dashboard') ? 'nav-item active font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-clipboard-list w-5 text-center {{ request()->routeIs('reviewer.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                                <span class="whitespace-nowrap">Assigned Protocols</span>
                            </a>

                            <a href="{{ route('reviewer.reviewed_titles') }}"
                               @click="mobileDrawerOpen = false"
                               class="flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group relative text-sm font-medium {{ request()->routeIs('reviewer.reviewed_titles') ? 'nav-item active font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-check-circle w-5 text-center {{ request()->routeIs('reviewer.reviewed_titles') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                                <span class="whitespace-nowrap">Reviewed Protocols</span>
                            </a>

                            <a href="{{ route('reviewer.reevaluation') }}"
                               @click="mobileDrawerOpen = false"
                               class="flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group relative text-sm font-medium {{ request()->routeIs('reviewer.reevaluation') ? 'nav-item active font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-rotate w-5 text-center {{ request()->routeIs('reviewer.reevaluation') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                                <span class="whitespace-nowrap">Re-Evaluation</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Drawer Sign Out -->
                <div class="p-4 border-t border-white/10 bg-black/20 shrink-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-r-lg text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200 group cursor-pointer focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary min-h-[44px]">
                            <i class="fas fa-sign-out-alt w-5 text-center transition-colors text-slate-400 group-hover:text-white"></i>
                            <span class="whitespace-nowrap text-sm font-medium">Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ===== MAIN CONTENT AREA ===== -->
        <main class="flex-1 flex flex-col h-full overflow-hidden relative bg-surface-canvas min-w-0 print:h-auto print:overflow-visible print:bg-white">
            
            <!-- ===== DESKTOP HEADER (Hidden on Mobile, and removed for assigned, reviewed, re-evaluation, and view_files tabs) ===== -->
            @if(!request()->routeIs('reviewer.dashboard', 'reviewer.reviewed_titles', 'reviewer.reevaluation', 'reviewer.view_files'))
            <header class="hidden lg:flex h-20 bg-white/90 backdrop-blur-md border-b border-slate-200/80 items-center justify-between px-8 sticky top-0 z-30 shrink-0 print:hidden">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-bold text-slate-900 font-heading tracking-tight">{{ $title ?? 'Reviewer Workspace' }}</h2>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">
                        Reviewer Active
                    </span>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Notification Trigger -->
                    <button aria-label="Open notifications center"
                            class="notification-trigger w-11 h-11 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-600 hover:text-brand-primary hover:border-brand-primary/40 hover:bg-white hover:shadow-xs transition-all relative focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary group cursor-pointer">
                        <i class="fas fa-bell text-base group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-brand-primary rounded-full animate-pulse ring-2 ring-white hidden"></span>
                    </button>
                </div>
            </header>
            @endif

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
                    <!-- Mobile Notification Button -->
                    <button aria-label="Open notifications"
                            class="notification-trigger w-11 h-11 min-h-[44px] min-w-[44px] rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-700 active:bg-slate-100 relative focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary cursor-pointer">
                        <i class="fas fa-bell text-sm" aria-hidden="true"></i>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-brand-primary rounded-full animate-pulse ring-2 ring-white hidden"></span>
                    </button>

                    <!-- Mobile Drawer Toggle Button -->
                    <button @click="mobileDrawerOpen = true"
                            aria-label="Open reviewer menu"
                            class="w-11 h-11 min-h-[44px] min-w-[44px] rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-700 active:bg-slate-100 cursor-pointer focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary">
                        <i class="fas fa-bars text-sm" aria-hidden="true"></i>
                    </button>
                </div>
            </header>

            <!-- Notification Toast Card -->
            <div id="notification-toast"
                 class="fixed top-20 right-4 sm:right-6 hidden bg-white border border-slate-200 rounded-2xl shadow-xl p-4 z-[60] opacity-0 scale-95 transition-all duration-300 max-w-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand-primary rounded-xl flex items-center justify-center text-white shrink-0 shadow-xs">
                        <i class="fas fa-bell text-base" aria-hidden="true"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-sm text-slate-900">New Protocol Notice</h4>
                        <p class="text-xs text-slate-500 mt-0.5">You have unread reviewer updates</p>
                    </div>
                    <button onclick="document.getElementById('notification-toast').classList.add('hidden')"
                            aria-label="Dismiss notification toast"
                            class="text-slate-400 hover:text-slate-600 p-1 shrink-0">
                        <i class="fas fa-times text-sm" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- Global Notification Modal / Drawer Component -->
            <x-notification-tab />

            <!-- Page Body with Mobile Bottom Clearance -->
            <div class="flex-1 {{ request()->routeIs('reviewer.view_files*') ? 'overflow-y-auto lg:overflow-hidden p-3 sm:p-4 lg:p-3.5' : 'overflow-y-auto p-4 sm:p-6 lg:p-8 pb-28 lg:pb-8' }} flex flex-col relative z-0 print:overflow-visible print:p-0 print:pb-0">
                <x-boneyard-skeleton role="reviewer" type="reviewer" contentId="reviewer-page-content" />
                <div id="reviewer-page-content" class="w-full flex-1 flex flex-col">
                    {{ $slot }}
                </div>
            </div>

            <!-- ===== MOBILE BOTTOM NAVIGATION BAR (Visible on Mobile/Tablet) ===== -->
            <nav class="flex lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/90 px-2 py-1.5 justify-around items-center shadow-lg shadow-slate-950/5 print:hidden"
                 aria-label="Reviewer Mobile Navigation">
                
                <!-- Assigned Protocols -->
                <a href="{{ route('reviewer.dashboard') }}"
                   class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all min-h-[48px] min-w-[64px] {{ request()->routeIs('reviewer.dashboard') ? 'text-brand-primary font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                    <i class="fas fa-clipboard-list text-base mb-1" aria-hidden="true"></i>
                    <span class="text-[10px] tracking-tight">Assigned</span>
                    @if(request()->routeIs('reviewer.dashboard'))
                        <span class="w-1 h-1 rounded-full bg-brand-primary mt-0.5"></span>
                    @endif
                </a>

                <!-- Reviewed Protocols -->
                <a href="{{ route('reviewer.reviewed_titles') }}"
                   class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all min-h-[48px] min-w-[64px] {{ request()->routeIs('reviewer.reviewed_titles') ? 'text-brand-primary font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                    <i class="fas fa-check-circle text-base mb-1" aria-hidden="true"></i>
                    <span class="text-[10px] tracking-tight">Reviewed</span>
                    @if(request()->routeIs('reviewer.reviewed_titles'))
                        <span class="w-1 h-1 rounded-full bg-brand-primary mt-0.5"></span>
                    @endif
                </a>

                <!-- Re-Evaluation -->
                <a href="{{ route('reviewer.reevaluation') }}"
                   class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all min-h-[48px] min-w-[64px] {{ request()->routeIs('reviewer.reevaluation') ? 'text-brand-primary font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                    <i class="fas fa-rotate text-base mb-1" aria-hidden="true"></i>
                    <span class="text-[10px] tracking-tight">Re-Eval</span>
                    @if(request()->routeIs('reviewer.reevaluation'))
                        <span class="w-1 h-1 rounded-full bg-brand-primary mt-0.5"></span>
                    @endif
                </a>

                <!-- Menu / Profile -->
                <button @click="mobileDrawerOpen = true"
                        type="button"
                        class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all min-h-[48px] min-w-[64px] text-slate-500 hover:text-slate-800 cursor-pointer">
                    <i class="fas fa-bars text-base mb-1" aria-hidden="true"></i>
                    <span class="text-[10px] tracking-tight">Menu</span>
                </button>
            </nav>

        </main>
    </div>

    <!-- Global Notification Scripts -->
    <x-notification-script />
    <x-toast />
</body>

</html>