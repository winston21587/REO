<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>REO | Researcher Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Alpine.js Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-heading {
            font-family: 'Montserrat', 'sans-serif';
        }

        [x-cloak] {
            display: none !important;
        }

        /* Active Link Styling (Desktop) */
        .nav-item.active {
            background: linear-gradient(to right, rgba(139, 0, 0, 0.2), transparent);
            border-left: 4px solid #8B0000;
            color: white;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-surface-50 text-slate-800 antialiased">
    <div class="min-h-screen bg-surface-50 flex" x-data="{ sidebarOpen: true, mobileOpen: false }">

        <!-- Sidebar (Desktop Only) -->
        <aside
            class="hidden lg:flex lg:sticky lg:top-0 lg:h-screen lg:inset-y-auto left-0 z-[100] w-64 bg-[#1a0505] text-white border-r border-white/5 flex-col transition-all duration-300"
            :class="{'lg:w-64': sidebarOpen, 'lg:w-20': !sidebarOpen}">

            <!-- Logo Area -->
            <div
                class="h-20 shrink-0 flex items-center justify-center border-b border-white/5 bg-gradient-to-r from-[#8B0000]/20 to-transparent relative">
                <div class="flex items-center gap-3" :class="{'px-6': sidebarOpen, 'px-0': !sidebarOpen}">
                    <div
                        class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-lg overflow-hidden transition-all duration-300">
                        <img src="{{ asset('images/reoc-nobg.png') }}" alt="REO Logo" class="h-6 w-auto">
                    </div>
                    <span
                        class="font-heading font-extrabold text-xl text-white tracking-tight whitespace-nowrap transition-opacity duration-300"
                        x-show="sidebarOpen" x-transition>
                        REO <span class="text-brand-primary">Portal</span>
                    </span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-6 overflow-y-auto custom-scrollbar">

                <!-- Main Menu -->
                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 transition-opacity duration-300"
                        x-show="sidebarOpen">Main Menu</p>
                    <div class="space-y-1">
                        <x-nav-link href="{{ route('home') }}" route="home" mode="desktop">
                            <i class="fas fa-home w-5 text-center"></i>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                x-show="sidebarOpen">Dashboard</span>
                        </x-nav-link>

                        <x-nav-link href="{{ route('submit') }}" route="submit" mode="desktop">
                            <i class="fas fa-plus-circle w-5 text-center"></i>
                            <span class="whitespace-nowrap transition-opacity duration-300" x-show="sidebarOpen">New
                                Submission</span>
                        </x-nav-link>
                    </div>
                </div>

                <!-- Resources -->
                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 transition-opacity duration-300"
                        x-show="sidebarOpen">Resources</p>
                    <div class="space-y-1">
                        <x-nav-link href="{{ route('resources') }}" route="resources" mode="desktop">
                            <i class="fas fa-folder-open w-5 text-center"></i>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                x-show="sidebarOpen">Downloadables</span>
                        </x-nav-link>

                        <x-nav-link href="{{ route('instructions') }}" route="instructions" mode="desktop">
                            <i class="fas fa-book w-5 text-center"></i>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                x-show="sidebarOpen">Guidelines</span>
                        </x-nav-link>
                    </div>
                </div>

                <!-- Account -->
                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 transition-opacity duration-300"
                        x-show="sidebarOpen">Account</p>
                    <div class="space-y-1">
                        <x-nav-link href="{{ route('settings') }}" route="settings" mode="desktop">
                            <i class="fas fa-cog w-5 text-center"></i>
                            <span class="whitespace-nowrap transition-opacity duration-300"
                                x-show="sidebarOpen">Settings</span>
                        </x-nav-link>
                    </div>
                </div>

            </nav>

            <!-- Sign Out -->
            <div class="p-4 border-t border-white/10 bg-black/20 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-r-lg text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200 group">
                        <i
                            class="fas fa-sign-out-alt w-5 text-center transition-colors text-slate-400 group-hover:text-white"></i>
                        <span class="whitespace-nowrap transition-opacity duration-300" x-show="sidebarOpen">
                            Sign Out
                        </span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 relative">

            <!-- Mobile Header (Glassmorphism) -->
            <header id="mobile-header"
                class="lg:hidden fixed top-0 w-full z-[200] bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm transition-transform duration-300">
                <div class="px-4 h-16 flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full overflow-hidden ring-1 ring-slate-200 shadow-sm">
                            <img src="{{ asset('images/reoc-nobg.png') }}" alt="Logo"
                                class="w-full h-full object-cover bg-white" />
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-base font-extrabold tracking-tight leading-none text-[#8B0000]">
                                WMSU REO
                            </h1>
                            <span class="text-[9px] font-medium uppercase tracking-widest text-slate-500">
                                Research Ethics Office
                            </span>
                        </div>
                    </div>
                    <!-- Hamburger Button (Animated) -->
                    <button @click="mobileOpen = !mobileOpen"
                        class="w-10 h-10 flex flex-col items-center justify-center gap-1.5 focus:outline-none group">
                        <span class="w-6 h-0.5 bg-slate-800 rounded-full transition-all duration-300 origin-center"
                            :class="{'rotate-45 translate-y-2': mobileOpen}"></span>
                        <span class="w-6 h-0.5 bg-slate-800 rounded-full transition-all duration-300"
                            :class="{'opacity-0 scale-0': mobileOpen}"></span>
                        <span class="w-6 h-0.5 bg-slate-800 rounded-full transition-all duration-300 origin-center"
                            :class="{'-rotate-45 -translate-y-2': mobileOpen}"></span>
                    </button>
                </div>

                <!-- Mobile Dropdown Menu -->
                <div x-show="mobileOpen" x-collapse x-cloak
                    class="absolute top-16 left-0 w-full bg-white border-b border-slate-200 shadow-xl overflow-hidden backdrop-blur-xl bg-white/95 z-[201]">

                    <div class="p-4 space-y-6 max-h-[80vh] overflow-y-auto">

                        <!-- User Profile Snippet -->
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div
                                class="w-12 h-12 rounded-full bg-[#8B0000] text-white flex items-center justify-center text-lg font-bold shadow-md">
                                {{ substr(Auth::user()->first_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ Auth::user()->first_name }}
                                    {{ Auth::user()->last_name }}
                                </p>
                                <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <!-- Menu Links -->
                        <div class="space-y-1">
                            <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Navigation
                            </p>

                            <x-nav-link href="{{ route('home') }}" route="home" mode="mobile">
                                <i class="fas fa-home w-5 text-center text-lg"></i>
                                <span class="font-medium">Dashboard</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('submit') }}" route="submit" mode="mobile">
                                <i class="fas fa-plus-circle w-5 text-center text-lg"></i>
                                <span class="font-medium">New Submission</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('resources') }}" route="resources" mode="mobile">
                                <i class="fas fa-folder-open w-5 text-center text-lg"></i>
                                <span class="font-medium">Downloadables</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('instructions') }}" route="instructions" mode="mobile">
                                <i class="fas fa-book w-5 text-center text-lg"></i>
                                <span class="font-medium">Guidelines</span>
                            </x-nav-link>

                            <x-nav-link href="{{ route('settings') }}" route="settings" mode="mobile">
                                <i class="fas fa-cog w-5 text-center text-lg"></i>
                                <span class="font-medium">Settings</span>
                            </x-nav-link>
                        </div>

                        <!-- Sign Out Button -->
                        <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-100">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-4 px-6 py-4 text-slate-600 hover:bg-red-50 hover:text-[#8B0000] rounded-xl transition-all duration-200">
                                <i class="fas fa-sign-out-alt w-5 text-center text-lg"></i>
                                <span class="font-medium">Sign Out</span>
                            </button>
                        </form>

                    </div>
                </div>
            </header>

            <!-- Desktop Header -->
            @if(request()->routeIs('home'))
                <header
                    class="hidden lg:flex items-center justify-end px-8 py-4 bg-white border-b border-slate-200 sticky top-0 z-30">
                    <div class="flex items-center gap-4 relative">
                        <button id="notification-btn"
                            class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-[#8B0000] transition-colors relative focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2">
                            <i class="fas fa-bell text-xl"></i>
                            <span
                                class="absolute top-2 right-2 w-2.5 h-2.5 bg-[#8B0000] border-2 border-white rounded-full animate-pulse"></span>
                        </button>
                        <x-notification-tab />
                    </div>
                </header>
            @endif

            <main class="flex-1 p-6 pt-20 lg:pt-6">
                <x-profile />
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        // Notification Toggle Script
        document.addEventListener('DOMContentLoaded', function () {
            // --- Smart Mobile Header Scroll Logic ---
            const mobileHeader = document.getElementById('mobile-header');
            let lastScrollTop = 0;

            if (mobileHeader) {
                window.addEventListener('scroll', () => {
                    const scrollTop = window.scrollY || document.documentElement.scrollTop;

                    // Logic: Scroll Down (>0) -> Hide. Scroll Up -> Show.
                    if (scrollTop > lastScrollTop && scrollTop > 50) {
                        mobileHeader.classList.add('-translate-y-full');
                    } else {
                        mobileHeader.classList.remove('-translate-y-full');
                    }
                    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                });
            }

            // --- Notification Logic ---
            const btn = document.getElementById('notification-btn');
            const panel = document.getElementById('notifications-panel');

            if (btn && panel) {
                let isOpen = false;

                function toggleNotifications(e) {
                    e.stopPropagation();
                    isOpen = !isOpen;

                    if (isOpen) {
                        panel.style.display = 'block';
                        setTimeout(() => {
                            panel.classList.remove('opacity-0', 'scale-95');
                            panel.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    } else {
                        panel.classList.remove('opacity-100', 'scale-100');
                        panel.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            panel.style.display = 'none';
                        }, 200);
                    }
                }

                document.addEventListener('click', function (e) {
                    if (isOpen && !panel.contains(e.target) && !btn.contains(e.target)) {
                        toggleNotifications(e);
                    }
                });

                btn.addEventListener('click', toggleNotifications);
            }
        });
    </script>
    <x-first_time_popup />
    <x-flash />
</body>

</html>