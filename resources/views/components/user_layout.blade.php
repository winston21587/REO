<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>REO | Researcher Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ isset($cms['website_logo']) ? asset($cms['website_logo']) : '' }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Alpine.js Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
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

<body class="bg-[#faf8f8] text-slate-800 antialiased"
    x-data="{ sidebarOpen: true, mobileOpen: false, mobileMenuOpen: false }">
    <div class="min-h-screen bg-[#faf8f8] flex">

        <!-- Sidebar (Desktop Only) -->
        <aside
            class="hidden lg:flex lg:sticky lg:top-0 lg:h-screen lg:inset-y-auto left-0 z-[100] w-64 bg-[#1a0505] text-white border-r border-white/5 flex-col transition-all duration-300"
            :class="{'lg:w-64': sidebarOpen, 'lg:w-20': !sidebarOpen}">

            <!-- Logo Area -->
            <div
                class="h-20 shrink-0 flex items-center justify-start border-b border-white/5 bg-gradient-to-r from-[#8B0000]/20 to-transparent relative">
                <div class="flex items-center gap-3 px-8 min-w-0 overflow-hidden">
                    <div
                        class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-lg overflow-hidden transition-all duration-300 flex-shrink-0">
                        <img src="{{ asset('images/reoc-nobg.png') }}" alt="REO Logo" class="h-7 w-auto">
                    </div>
                    <span
                        class="font-heading font-extrabold text-lg text-white tracking-tight transition-opacity duration-300 truncate block min-w-0"
                        x-show="sidebarOpen" x-transition
                        title="{{ Auth::user()->first_name ?? 'User' }} {{ Auth::user()->last_name ?? '' }}">
                        {{ Auth::user()->first_name ?? 'User' }} {{ Auth::user()->last_name ?? '' }}
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
                                x-show="sidebarOpen">Home</span>
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

        <!-- Mobile Header (Glassmorphism) - RESTORED BUT SIMPLIFIED -->
        <header id="mobile-header"
            class="lg:hidden fixed top-0 w-full z-[100] bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm transition-transform duration-300">
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

                <!-- Notification Trigger Only (No Hamburger) -->
                <button
                    class="notification-trigger w-12 h-12 flex items-center justify-center text-slate-500 hover:text-[#8B0000] rounded-full hover:bg-slate-50 transition-all relative focus:outline-none active:scale-95 group">
                    <i class="fas fa-bell text-2xl group-hover:scale-110 transition-transform"></i>
                    <span
                        class="absolute top-1 right-1 w-3 h-3 bg-[#8B0000] border-2 border-white rounded-full animate-pulse shadow-lg hidden"></span>
                </button>
            </div>
        </header>
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 relative z-0 pb-20 lg:pb-0">



            <!-- Desktop Header -->
            @if(request()->routeIs('home'))
                <header
                    class="hidden lg:flex items-center justify-end px-8 py-4 bg-white border-b border-slate-200 sticky top-0 z-30">
                    <div class="flex items-center gap-4 relative">
                        <!-- Desktop Notification Trigger -->
                        <button
                            class="notification-trigger w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-[#8B0000] transition-colors relative focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 group">
                            <i class="fas fa-bell text-2xl group-hover:scale-110 transition-transform"></i>
                            <span
                                class="absolute top-1 right-1 w-3 h-3 bg-[#8B0000] border-2 border-white rounded-full animate-pulse shadow-lg hidden"></span>
                        </button>
                    </div>
                </header>
            @endif

            <!-- Notification Toast Card (Appears on First Login Only) -->
            <div id="notification-toast" class="fixed top-24 right-6 hidden bg-white border border-slate-200 rounded-xl shadow-lg p-4 z-40 opacity-0 scale-95 transition-all duration-300 max-w-xs cursor-pointer hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#8B0000] rounded-lg flex items-center justify-center text-white flex-shrink-0">
                        <i class="fas fa-bell text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-sm text-slate-800">New Notifications</h4>
                        <p class="text-xs text-slate-600">You have unread notifications</p>
                    </div>
                    <button id="close-notification-toast" class="text-slate-400 hover:text-slate-600 ml-2 flex-shrink-0">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Global Notification Tab (Available for both Mobile & Desktop) -->
            <x-notification-tab />

            <main class="flex-1 p-6 pt-20 lg:pt-6">
                <x-profile />
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Bottom Navigation (Mobile Only) - Moved to Root Scope -->
    <nav id="bottom-nav"
        class="lg:hidden fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 z-[10001] pb-safe shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] transition-transform duration-300 transform">
        <div class="flex w-full h-16 items-end pb-2 relative">
            <!-- Home -->
            <a href="{{ route('home') }}"
                class="flex-1 flex flex-col items-center justify-center h-full text-slate-500 hover:text-[#8B0000] {{ request()->routeIs('home') ? 'text-[#8B0000]' : '' }} transition-colors group">
                <i
                    class="fas fa-home text-xl mb-1 transform group-active:scale-90 transition-transform {{ request()->routeIs('home') ? 'scale-110' : '' }}"></i>
                <span class="text-[10px] font-medium">Home</span>
            </a>

            <!-- Resources -->
            <a href="{{ route('resources') }}"
                class="flex-1 flex flex-col items-center justify-center h-full text-slate-500 hover:text-[#8B0000] {{ request()->routeIs('resources') ? 'text-[#8B0000]' : '' }} transition-colors group">
                <i
                    class="fas fa-folder-open text-xl mb-1 transform group-active:scale-90 transition-transform {{ request()->routeIs('resources') ? 'scale-110' : '' }}"></i>
                <span class="text-[10px] font-medium">Resources</span>
            </a>

            <!-- New Submission (Floating Center) -->
            <div class="flex-1 relative h-full flex items-center justify-center">
                <a href="{{ route('submit') }}"
                    class="absolute -top-5 bg-[#8B0000] text-white w-14 h-14 rounded-full shadow-lg shadow-red-900/40 flex items-center justify-center ring-4 ring-white transform active:scale-95 transition-all hover:-translate-y-1 z-50">
                    <i class="fas fa-plus text-2xl"></i>
                </a>
            </div>

            <!-- Guidelines (Converted from Alerts) -->
            <a href="{{ route('instructions') }}"
                class="flex-1 flex flex-col items-center justify-center h-full text-slate-500 hover:text-[#8B0000] {{ request()->routeIs('instructions') ? 'text-[#8B0000]' : '' }} transition-colors group">
                <i
                    class="fas fa-book text-xl mb-1 transform group-active:scale-90 transition-transform {{ request()->routeIs('instructions') ? 'scale-110' : '' }}"></i>
                <span class="text-[10px] font-medium">Guidelines</span>
            </a>

            <!-- Menu Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="flex-1 flex flex-col items-center justify-center h-full text-slate-500 hover:text-[#8B0000] transition-colors group appearance-none focus:outline-none">
                <div class="w-6 h-6 rounded-full bg-slate-200 overflow-hidden mb-1 ring-1 ring-slate-100">
                    @if(Auth::check())
                        <span
                            class="flex items-center justify-center w-full h-full text-xs font-bold text-slate-600 bg-slate-200">
                            {{ substr(Auth::user()->first_name, 0, 1) }}
                        </span>
                    @else
                        <i class="fas fa-user text-xs text-slate-500 mt-1"></i>
                    @endif
                </div>
                <span class="text-[10px] font-medium">Menu</span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu Drawer (Slide Up) - Moved to Root Scope -->
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-[10002]" style="display: none;">
        <!-- Backdrop -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
            @click="mobileMenuOpen = false"></div>

        <!-- Drawer Content -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="absolute bottom-0 left-0 w-full bg-white rounded-t-[32px] shadow-2xl overflow-hidden max-h-[85vh] flex flex-col ring-1 ring-black/5">

            <!-- Handle & Header -->
            <div
                class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white relative shrink-0">
                <div class="w-12 h-1.5 bg-slate-200 rounded-full absolute left-1/2 -translate-x-1/2 top-3"></div>
                <h3 class="font-heading font-extrabold text-xl text-slate-800 mt-2">Menu</h3>
                <button @click="mobileMenuOpen = false"
                    class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition-colors mt-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 space-y-6 overflow-y-auto">
                <!-- Profile Card (Redesigned) -->
                <div class="bg-gradient-to-r from-slate-900 to-[#8B0000] rounded-2xl p-1 shadow-lg relative group">
                    <div
                        class="bg-gradient-to-r from-slate-900 to-[#8B0000] rounded-xl p-5 flex items-center gap-4 relative overflow-hidden">
                        <!-- Background Pattern -->
                        <div class="absolute top-0 right-0 p-4 opacity-10 transform translate-x-4 -translate-y-4">
                            <i class="fas fa-user-circle text-8xl text-white"></i>
                        </div>

                        <!-- Content -->
                        <div class="relative z-10 shrink-0">
                            <div
                                class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-xl font-bold border-2 border-white/50 text-white shadow-inner">
                                {{ substr(Auth::user()->first_name ?? 'U', 0, 1) }}
                            </div>
                        </div>
                        <div class="relative z-10 min-w-0 flex-1 text-white">
                            <h4 class="font-bold text-lg truncate leading-tight">
                                {{ Auth::user()->first_name ?? 'User' }} {{ Auth::user()->last_name ?? '' }}
                            </h4>
                            <p class="text-white/70 text-sm truncate mb-1">{{ Auth::user()->email ?? '' }}</p>
                            <a href="{{ route('settings') }}"
                                class="text-xs font-bold text-yellow-400 hover:text-yellow-300 flex items-center gap-1">
                                <span>Manage Profile</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Grid Menu (Redesigned) -->
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('instructions') }}"
                        class="flex flex-col items-center p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-white hover:border-blue-200 hover:shadow-lg hover:-translate-y-0.5 transition-all group active:scale-95">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform shadow-sm group-hover:shadow-md">
                            <i class="fas fa-book"></i>
                        </div>
                        <span class="font-bold text-slate-700 text-sm">Guidelines</span>
                    </a>
                    <a href="{{ route('settings') }}"
                        class="flex flex-col items-center p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-white hover:border-indigo-200 hover:shadow-lg hover:-translate-y-0.5 transition-all group active:scale-95">
                        <div
                            class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform shadow-sm group-hover:shadow-md">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span class="font-bold text-slate-700 text-sm">Settings</span>
                    </a>
                </div>

                <!-- Sign Out (Redesigned) -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 rounded-xl border-2 border-slate-100 text-slate-600 font-bold hover:bg-red-50 hover:text-[#8B0000] hover:border-red-100 transition-all flex items-center justify-center gap-2 group active:scale-95">
                        <span
                            class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center transition-colors group-hover:bg-white">
                            <i class="fas fa-sign-out-alt"></i>
                        </span>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        #bottom-nav {
            transition: transform 0.3s ease-in-out;
            will-change: transform;
        }
    </style>
    <script>
        // Notification Toggle Script
        document.addEventListener('DOMContentLoaded', function () {
            // --- Smart Mobile Bottom Nav Scroll Logic ---
            // --- Smart Mobile Bottom Nav Scroll Logic ---
            const bottomNav = document.getElementById('bottom-nav');

            if (bottomNav) {
                let lastScrollTop = 0;

                // Function to handle scroll logic
                const handleScroll = () => {
                    // Try to get scroll position from multiple sources
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
                    console.log('Current ScrollTop:', scrollTop); // Debugging

                    if (scrollTop <= 0) {
                        bottomNav.style.transform = 'translateY(0)';
                        lastScrollTop = 0;
                        console.log('ScrollTop <= 0, showing bottomNav'); // Debugging
                        return;
                    }

                    if (scrollTop > lastScrollTop) {
                        // Scrolling DOWN -> HIDE
                        bottomNav.style.transform = 'translateY(100%)';
                        console.log('Scrolling DOWN, hiding bottomNav'); // Debugging
                    } else {
                        // Scrolling UP -> SHOW
                        bottomNav.style.transform = 'translateY(0)';
                        console.log('Scrolling UP, showing bottomNav'); // Debugging
                    }
                    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                };

                // Attach to likely scroll containers
                window.addEventListener('scroll', handleScroll, { passive: true });
                document.body.addEventListener('scroll', handleScroll, { passive: true });
                document.documentElement.addEventListener('scroll', handleScroll, { passive: true });
            }

            // --- Notification Logic ---
            // Select ALL notification triggers (Mobile & Desktop)
            const btns = document.querySelectorAll('.notification-trigger');
            const panel = document.getElementById('notifications-panel');

            if (btns.length > 0 && panel) {
                let isOpen = false;

                function toggleNotifications(e) {
                    e.stopPropagation();
                    isOpen = !isOpen;

                    if (isOpen) {
                        // Opening panel - hide red dots
                        btns.forEach(btn => {
                            const dot = btn.querySelector('span.animate-pulse');
                            if (dot) {
                                dot.classList.add('hidden');
                            }
                        });
                        
                        panel.style.display = 'block';
                        setTimeout(() => {
                            panel.classList.remove('opacity-0', 'scale-95');
                            panel.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    } else {
                        // Closing panel - always hide red dots (they stay hidden)
                        // They only reappear when NEW notifications actually arrive
                        btns.forEach(btn => {
                            const dot = btn.querySelector('span.animate-pulse');
                            if (dot) {
                                dot.classList.add('hidden');
                            }
                        });
                        
                        panel.classList.remove('opacity-100', 'scale-100');
                        panel.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            panel.style.display = 'none';
                        }, 200);
                    }
                }

                // Attach event to all buttons
                btns.forEach(btn => btn.addEventListener('click', toggleNotifications));

                document.addEventListener('click', function (e) {
                    // Check if click is outside panel AND outside ANY trigger button
                    let clickedInsideButton = false;
                    btns.forEach(btn => {
                        if (btn.contains(e.target)) clickedInsideButton = true;
                    });

                    if (isOpen && !panel.contains(e.target) && !clickedInsideButton) {
                        // Close it
                        isOpen = true; // wait, logic was: toggle(e) toggles layout. 
                        // If we are open, we want to close.
                        toggleNotifications(e);
                    }
                });

                // --- Notification Management System ---
                let lastNotificationCount = 0;
                let initialNotificationCount = null; // Track initial count
                let hasShownLoginToast = sessionStorage.getItem('notificationLoginToastShown') === 'true';
                
                // Show toast only on first login (appears once)
                function showLoginNotificationToast(initialCount) {
                    if (!hasShownLoginToast && initialCount > 0) {
                        const toast = document.getElementById('notification-toast');
                        if (toast) {
                            toast.classList.remove('hidden', 'opacity-0', 'scale-95');
                            toast.classList.add('opacity-100', 'scale-100');
                            
                            // Auto-hide toast after 6 seconds
                            setTimeout(() => {
                                toast.classList.add('opacity-0', 'scale-95');
                                setTimeout(() => {
                                    toast.classList.add('hidden');
                                }, 300);
                            }, 6000);
                            
                            // Mark that we've shown the login toast (only once per session)
                            sessionStorage.setItem('notificationLoginToastShown', 'true');
                            hasShownLoginToast = true;
                        }
                    }
                }

                // Check for new notifications via AJAX
                function checkForNewNotifications() {
                    fetch('{{ route("notifications.api.unread") }}')
                        .then(response => response.json())
                        .then(data => {
                            const currentCount = data.unread_count;
                            
                            // Update red dot visibility - hide if panel is open or if no unread
                            const notificationTriggers = document.querySelectorAll('.notification-trigger');
                            notificationTriggers.forEach(trigger => {
                                const dot = trigger.querySelector('span.animate-pulse');
                                if (dot) {
                                    // Hide dot if no unread OR if panel is open
                                    if (currentCount > 0 && !isOpen) {
                                        // Show dot if there are unread and panel is closed
                                        dot.classList.remove('hidden');
                                    } else {
                                        // Hide dot otherwise
                                        dot.classList.add('hidden');
                                    }
                                }
                            });
                            
                            // Update notification panel count if it exists
                            const notifPanel = document.getElementById('notifications-panel');
                            if (notifPanel) {
                                const unreadSpans = notifPanel.querySelectorAll('span');
                                unreadSpans.forEach(span => {
                                    if (span.textContent.includes('unread')) {
                                        if (currentCount > 0) {
                                            span.textContent = currentCount + ' unread';
                                        }
                                    }
                                });
                            }
                            
                            // Show toast only if new notifications arrived (currentCount > lastNotificationCount)
                            if (currentCount > lastNotificationCount && hasShownLoginToast) {
                                const toast = document.getElementById('notification-toast');
                                if (toast) {
                                    toast.classList.remove('hidden', 'opacity-0', 'scale-95');
                                    toast.classList.add('opacity-100', 'scale-100');
                                    
                                    setTimeout(() => {
                                        toast.classList.add('opacity-0', 'scale-95');
                                        setTimeout(() => {
                                            toast.classList.add('hidden');
                                        }, 300);
                                    }, 5000);
                                }
                            }
                            
                            lastNotificationCount = currentCount;
                        })
                        .catch(error => console.error('Error checking notifications:', error));
                }

                // Initialize notifications on page load
                function initializeNotifications() {
                    fetch('{{ route("notifications.api.unread") }}')
                        .then(response => response.json())
                        .then(data => {
                            initialNotificationCount = data.unread_count;
                            lastNotificationCount = data.unread_count;
                            
                            // Show login toast if there are unread on first load
                            showLoginNotificationToast(initialNotificationCount);
                            
                            // Update red dots
                            const notificationTriggers = document.querySelectorAll('.notification-trigger');
                            notificationTriggers.forEach(trigger => {
                                const dot = trigger.querySelector('span.animate-pulse');
                                if (dot && initialNotificationCount > 0 && !isOpen) {
                                    dot.classList.remove('hidden');
                                }
                            });
                        })
                        .catch(error => console.error('Error initializing notifications:', error));
                }

                // Initialize on page load
                initializeNotifications();
                
                // Check for notifications every 3 seconds (faster updates)
                setInterval(checkForNewNotifications, 3000);
            }
        });
    </script>
    <x-first_time_popup />
    <x-flash />
</body>

</html>