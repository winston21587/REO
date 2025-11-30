<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>REO | Researcher Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#1a0505',
                            primary: '#8B0000', // Crimson
                            secondary: '#5c0000',
                            light: '#ffffff'
                        },
                        surface: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0'
                        }
                    },
                    fontFamily: {
                        heading: ['Montserrat', 'sans-serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Montserrat', 'sans-serif'; }
        [x-cloak] { display: none !important; }
        
        /* Active Link Styling */
        .nav-item.active {
            background: linear-gradient(to right, rgba(139, 0, 0, 0.2), transparent);
            border-left: 4px solid #8B0000;
            color: white;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-surface-50 text-slate-800 antialiased">
    <div class="min-h-screen bg-surface-50 flex" x-data="{ sidebarOpen: true, mobileOpen: false }">
        
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#1a0505] text-white border-r border-white/5 transition-transform duration-300 ease-in-out transform lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen shadow-xl lg:shadow-none flex flex-col"
            :class="{'translate-x-0': mobileOpen, '-translate-x-full': !mobileOpen, 'lg:w-64': sidebarOpen, 'lg:w-20': !sidebarOpen}">
            
            <!-- Logo Area -->
            <div class="h-20 shrink-0 flex items-center justify-center border-b border-white/5 bg-gradient-to-r from-[#8B0000]/20 to-transparent relative">
                <div class="flex items-center gap-3" :class="{'px-6': sidebarOpen, 'px-0': !sidebarOpen}">
                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-lg overflow-hidden transition-all duration-300" :class="{'w-8 h-8': sidebarOpen, 'w-8 h-8': !sidebarOpen}">
                        <img src="{{ asset('images/reoc-nobg.png') }}" alt="REO Logo" class="h-6 w-auto">
                    </div>
                    <span class="font-heading font-extrabold text-xl text-white tracking-tight whitespace-nowrap transition-opacity duration-300" 
                        x-show="sidebarOpen" x-transition>
                        REO <span class="text-brand-primary">Portal</span>
                    </span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
                
                <div class="mb-6">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 transition-opacity duration-300" x-show="sidebarOpen">Main Menu</p>
                    
                    <a href="{{ route('home') }}" 
                       class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group {{ request()->routeIs('home') ? 'active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-home w-5 text-center transition-colors {{ request()->routeIs('home') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="whitespace-nowrap transition-opacity duration-300" x-show="sidebarOpen">Dashboard</span>
                    </a>

                    <a href="{{ route('submit') }}" 
                       class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group {{ request()->routeIs('submit') ? 'active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-plus-circle w-5 text-center transition-colors {{ request()->routeIs('submit') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="whitespace-nowrap transition-opacity duration-300" x-show="sidebarOpen">New Submission</span>
                    </a>
                </div>

                <div class="mb-6">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 transition-opacity duration-300" x-show="sidebarOpen">Resources</p>
                    
                    <a href="{{ route('resources') }}" 
                       class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group {{ request()->routeIs('resources') ? 'active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-folder-open w-5 text-center transition-colors {{ request()->routeIs('resources') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="whitespace-nowrap transition-opacity duration-300" x-show="sidebarOpen">Downloadables</span>
                    </a>

                    <a href="{{ route('instructions') }}" 
                       class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group {{ request()->routeIs('instructions') ? 'active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-book w-5 text-center transition-colors {{ request()->routeIs('instructions') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="whitespace-nowrap transition-opacity duration-300" x-show="sidebarOpen">Guidelines</span>
                    </a>
                </div>

                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 transition-opacity duration-300" x-show="sidebarOpen">Account</p>
                    
                    <a href="{{ route('settings') }}" 
                       class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg transition-all duration-200 group {{ request()->routeIs('settings') ? 'active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i class="fas fa-cog w-5 text-center transition-colors {{ request()->routeIs('settings') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                        <span class="whitespace-nowrap transition-opacity duration-300" x-show="sidebarOpen">Settings</span>
                    </a>
                </div>
            </nav>

            <!-- Sign Out Section -->
            <div class="p-4 border-t border-white/10 bg-black/20 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-r-lg text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200 group">
                        <i class="fas fa-sign-out-alt w-5 text-center transition-colors text-slate-400 group-hover:text-white"></i>
                        <span class="whitespace-nowrap transition-opacity duration-300" x-show="sidebarOpen">Sign Out ({{ Auth::user()->first_name ?? 'User' }})</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div x-show="mobileOpen" @click="mobileOpen = false" x-transition.opacity 
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Mobile Header -->
            <div class="lg:hidden p-4 flex items-center justify-between bg-white border-b border-slate-200">
                <button @click="mobileOpen = !mobileOpen" class="p-2 text-slate-600 hover:text-brand-primary transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex items-center gap-4">
                    <!-- Mobile Notification Trigger (simplified, assumes same script works if ID matches or we need duplicate IDs which is bad) -->
                    <!-- For now, let's rely on the desktop one or just show profile on mobile -->
                    <x-profile />
                </div>
            </div>

            <!-- Desktop Header -->
            @if(request()->routeIs('home'))
            <header class="hidden lg:flex items-center justify-end px-8 py-4 bg-white border-b border-slate-200 sticky top-0 z-30">
                <div class="flex items-center gap-4 relative">
                    <button id="notification-btn" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-[#8B0000] transition-colors relative focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-[#8B0000] border-2 border-white rounded-full animate-pulse"></span>
                    </button>
                    <x-notification-tab />
                </div>
            </header>
            @endif

            <main class="flex-1 overflow-y-auto p-6">
                <x-profile/>
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const overlay = document.createElement('div');
        
        overlay.className = 'fixed inset-0 bg-black/50 z-40 md:hidden hidden backdrop-blur-sm transition-opacity';
        document.body.appendChild(overlay);

        function toggleSidebar() {
            if (!sidebar) return; // Guard clause
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        if(openBtn) openBtn.addEventListener('click', toggleSidebar);
        if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Notification Toggle Script
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('notification-btn');
            const panel = document.getElementById('notifications-panel');
            
            if(btn && panel) {
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

                document.addEventListener('click', function(e) {
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