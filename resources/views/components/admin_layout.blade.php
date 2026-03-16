@if(Auth::check() && Auth::user()->role === 'super_admin')
<x-super_admin_layout>
    {{ $slot }}
</x-super_admin_layout>
@else
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REO Admin Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ isset($cms['website_logo']) ? asset($cms['website_logo']) : '' }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-heading {
            font-family: 'Montserrat', sans-serif;
        }

        /* Active Link Styling */
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
            background: rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Dark scrollbar for main content area (light bg) */
        main ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        main ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
        }

        main ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <div class="flex h-screen overflow-hidden">
        <aside class="w-72 bg-[#1a0505] text-white flex flex-col shadow-2xl z-50 relative">
            <div
                class="h-20 flex items-center px-8 border-b border-white/5 bg-gradient-to-r from-[#8B0000]/20 to-transparent">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-lg overflow-hidden">
                        <img src="{{ isset($cms['website_logo']) ? asset($cms['website_logo']) : '' }}" class="w-6 h-6">
                    </div>
                    <div>
                        <h1 class="font-heading font-bold text-lg tracking-wide">REO Admin</h1>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest">Oversight Comm.</p>
                    </div>
                </div>
            </div>

            <nav id="admin-sidebar-nav" class="flex-1 px-4 py-6 space-y-8 overflow-y-auto custom-scrollbar">

                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Review Process
                    </p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.analytics') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition-all {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                            <i class="fas fa-chart-line w-5 text-center"></i>
                            <span class="flex-1">Analytics</span>
                        </a>
                        <a href="{{ route('admin.NewSubmissions') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition-all {{ request()->routeIs('admin.NewSubmissions') ? 'active' : '' }}">
                            <i class="fas fa-inbox w-5 text-center"></i>
                            <span class="flex-1">Initial Intake</span>
                            @if($pendingCount = \App\Models\Research_title::where('Status', 'Pending')->count())
                                <span
                                    class="bg-[#8B0000] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.applications') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition-all {{ request()->routeIs('admin.applications') ? 'active' : '' }}">
                            <i class="fas fa-folder-open w-5 text-center"></i>
                            <span class="flex-1">Active Protocols</span>
                        </a>
                        <a href="{{ route('admin.revisions') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition-all {{ request()->routeIs('admin.revisions') ? 'active' : '' }}">
                            <i class="fas fa-sync-alt w-5 text-center"></i>
                            <span class="flex-1">Revisions</span>
                        </a>
                        <a href="{{ route('admin.certifications') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition-all {{ request()->routeIs('admin.certifications') ? 'active' : '' }}">
                            <i class="fas fa-certificate w-5 text-center"></i>
                            <span class="flex-1">Certifications</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Committee</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.meetings') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition-all {{ request()->routeIs('admin.meetings*') ? 'active' : '' }}">
                            <i class="fas fa-users w-5 text-center"></i>
                            <span class="flex-1">Meetings & Agenda</span>
                        </a>
                        <a href="{{ route('admin.manage_users') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition-all {{ request()->routeIs('admin.manage_users') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate w-5 text-center"></i>
                            <span class="flex-1">Researchers</span>
                        </a>
                        <!-- Temporary Sidebar link requested by user -->
                        <a href="{{ route('test.model') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                            <i class="fas fa-robot text-purple-400 w-5 text-center"></i>
                            <span class="flex-1">AI Test UI</span>
                        </a>
                    </div>
                </div>



            </nav>

            <div class="p-4 border-t border-white/10 bg-black/20">
                <div class="flex items-center gap-3 px-2">
                    <div
                        class="w-10 h-10 rounded-lg bg-[#8B0000] flex items-center justify-center text-white font-bold shadow-md">
                        {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->first_name ?? 'Admin' }}</p>
                        <p class="text-xs text-slate-400 truncate">System Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-white transition-colors"><i
                                class="fas fa-sign-out-alt"></i></button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-full overflow-hidden relative bg-slate-50">
            <header
                class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
                <h2 class="text-xl font-bold text-slate-800 font-heading">{{ $title ?? 'Dashboard' }}</h2>
                <div class="flex items-center gap-4 relative">
                    <button id="notification-btn"
                        class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-[#8B0000] transition-colors relative focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2">
                        <i class="fas fa-bell text-xl"></i>
                        <span
                            class="absolute top-2 right-2 w-2.5 h-2.5 bg-[#8B0000] border-2 border-white rounded-full animate-pulse"></span>
                    </button>

                    <!-- Notification Component -->
                    <x-notification-tab />
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const btn = document.getElementById('notification-btn');
                        const panel = document.getElementById('notifications-panel');
                        let isOpen = false;

                        // Toggle function
                        function toggleNotifications(e) {
                            e.stopPropagation();
                            isOpen = !isOpen;

                            if (isOpen) {
                                panel.style.display = 'block';
                                // Small delay to allow display:block to apply before transition
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

                        // Click outside to close
                        document.addEventListener('click', function (e) {
                            if (isOpen && !panel.contains(e.target) && !btn.contains(e.target)) {
                                toggleNotifications(e);
                            }
                        });

                        btn.addEventListener('click', toggleNotifications);
                    });
                </script>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                {{ $slot }}
            </div>
        </main>
        <script>
            // Sidebar Scroll Memory
            document.addEventListener("DOMContentLoaded", function () {
                const sidebar = document.getElementById("admin-sidebar-nav");
                
                // Restore scroll position
                if (localStorage.getItem("adminSidebarScroll")) {
                    sidebar.scrollTop = localStorage.getItem("adminSidebarScroll");
                }

                // Save scroll position on scroll
                sidebar.addEventListener("scroll", function () {
                    localStorage.setItem("adminSidebarScroll", sidebar.scrollTop);
                });
            });
        </script>
    </div>

</body>

</html>
@endif