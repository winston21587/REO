<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>REO Admin Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ !empty($cms['website_logo']) ? asset($cms['website_logo']) : asset('images/reoc-nobg.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            background-color: rgba(139, 0, 0, 0.35);
            color: #ffffff;
            font-weight: 700;
            box-shadow: inset 4px 0 0 0 #dc2626;
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

        /* Completely hide scrollbars on sidebar navigation across all browsers */
        aside nav,
        #super-admin-sidebar-nav,
        .sidebar-no-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }
        aside nav::-webkit-scrollbar,
        #super-admin-sidebar-nav::-webkit-scrollbar,
        .sidebar-no-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
    </style>
</head>

<body class="bg-[#faf8f8] text-slate-800">

    <div class="flex h-screen overflow-hidden" x-data="{ mobileOpen: false }">
        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex w-72 bg-[#1a0505] text-white flex-col shadow-2xl z-30 relative shrink-0">
            <div
                class="h-20 flex items-center px-8 border-b border-white/5 bg-gradient-to-r from-[#8B0000]/20 to-transparent">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-lg overflow-hidden shrink-0">
                        <img src="{{ !empty($cms['website_logo']) ? asset($cms['website_logo']) : asset('images/reoc-nobg.png') }}"
                             alt="WMSU REO Logo"
                             class="w-6 h-6 object-contain">
                    </div>
                    <div>
                        <span class="font-heading font-bold text-lg tracking-wide block" role="presentation">REO Super Admin</span>
                        <p class="text-[10px] text-slate-300/80 uppercase tracking-widest">System Oversight</p>
                    </div>
                </div>
            </div>

            <nav id="super-admin-sidebar-nav" class="flex-1 px-4 py-6 space-y-8 overflow-y-auto sidebar-no-scrollbar [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

                <div>
                    <p class="px-4 text-[10px] font-extrabold text-slate-300 uppercase tracking-widest mb-3">Review Process
                    </p>
                    <div class="space-y-1">
                        <a href="{{ route('super_admin.analytics') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.analytics') ? 'active' : '' }}">
                            <i class="fas fa-chart-line w-5 text-center"></i>
                            <span class="flex-1">Analytics</span>
                        </a>
                        <a href="{{ route('admin.NewSubmissions') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.NewSubmissions') ? 'active font-bold text-white' : '' }}">
                            <i class="fas fa-inbox w-5 text-center"></i>
                            <span class="flex-1">Initial Intake</span>
                            @if(!empty($sidebarCounts['pendingIntake']))
                                <span class="bg-[#8B0000] text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full shadow-xs">{{ $sidebarCounts['pendingIntake'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.applications') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.applications') ? 'active' : '' }}">
                            <i class="fas fa-folder-open w-5 text-center"></i>
                            <span class="flex-1">Active Protocols</span>
                            @if(!empty($sidebarCounts['activeProtocols']))
                                <span class="bg-[#8B0000] text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full shadow-xs">{{ $sidebarCounts['activeProtocols'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.revisions') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.revisions') ? 'active' : '' }}">
                            <i class="fas fa-sync-alt w-5 text-center"></i>
                            <span class="flex-1">Revisions</span>
                            @if(!empty($sidebarCounts['revisions']))
                                <span class="bg-[#8B0000] text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full shadow-xs">{{ $sidebarCounts['revisions'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.certifications') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.certifications') ? 'active' : '' }}">
                            <i class="fas fa-certificate w-5 text-center"></i>
                            <span class="flex-1">Certifications</span>
                            @if(!empty($sidebarCounts['pendingCertifications']))
                                <span class="bg-[#8B0000] text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full shadow-xs">{{ $sidebarCounts['pendingCertifications'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-4 text-[10px] font-extrabold text-slate-300 uppercase tracking-widest mb-3">Committee</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.meetings') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.meetings*') ? 'active' : '' }}">
                            <i class="fas fa-users w-5 text-center"></i>
                            <span class="flex-1">Meetings & Agenda</span>
                        </a>
                        <a href="{{ route('admin.manage_users') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.manage_users') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate w-5 text-center"></i>
                            <span class="flex-1">Researchers</span>
                        </a>
                        <a href="{{ route('super_admin.manage_admins') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.manage_admins') ? 'active' : '' }}">
                            <i class="fas fa-users-cog w-5 text-center"></i>
                            <span class="flex-1">Manage Admins</span>
                        </a>
                        <a href="{{ route('super_admin.manage_reviewers') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.manage_reviewers') ? 'active' : '' }}">
                            <i class="fas fa-user-check w-5 text-center"></i>
                            <span class="flex-1">Manage Reviewers</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-4 text-[10px] font-extrabold text-slate-300 uppercase tracking-widest mb-3">System Management</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.cms.content') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.cms.content') ? 'active' : '' }}">
                            <i class="fas fa-desktop w-5 text-center"></i>
                            <span class="flex-1">Website Content</span>
                        </a>

                        <a href="{{ route('super_admin.manage_fees') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.manage_fees') ? 'active' : '' }}">
                            <i class="fas fa-tags w-5 text-center"></i>
                            <span class="flex-1">Manage Fees</span>
                        </a>
                        <a href="{{ route('admin.cms.index') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.cms.index') || request()->routeIs('admin.cms.pages') ? 'active' : '' }}">
                            <i class="fas fa-file-alt w-5 text-center"></i>
                            <span class="flex-1">Page Content</span>
                        </a>
                        <a href="{{ route('admin.cms.departments') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.cms.departments') ? 'active' : '' }}">
                            <i class="fas fa-building w-5 text-center"></i>
                            <span class="flex-1">Departments & Programs</span>
                        </a>
                        <a href="{{ route('admin.manage_documents') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.manage_documents') ? 'active' : '' }}">
                            <i class="fas fa-file-contract w-5 text-center"></i>
                            <span class="flex-1">Manage Documents</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-6 mb-3">Logs</p>
                    <div class="space-y-1">
                        <a href="{{ route('super_admin.revenue_logs') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.revenue_logs') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
                            <span class="flex-1">Revenue Logs</span>
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
                        <p class="text-xs text-slate-300 truncate">Super Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/10 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000]" aria-label="Log out of account">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        <script>
            (function() {
                try {
                    const sidebar = document.getElementById("super-admin-sidebar-nav");
                    if (!sidebar) return;
                    
                    // 1. Immediately restore scroll position before initial paint (prevents flicker)
                    const saved = localStorage.getItem("superAdminSidebarScroll");
                    if (saved !== null) {
                        sidebar.scrollTop = parseInt(saved, 10);
                    }
                    
                    // 2. If active item is outside visible range, ensure it's in view
                    const active = sidebar.querySelector(".active");
                    if (active) {
                        const rect = active.getBoundingClientRect();
                        const navRect = sidebar.getBoundingClientRect();
                        if (rect.top < navRect.top || rect.bottom > navRect.bottom) {
                            active.scrollIntoView({ block: "nearest" });
                            localStorage.setItem("superAdminSidebarScroll", sidebar.scrollTop);
                        }
                    }

                    // 3. Save scroll position on scroll, click, and beforeunload
                    sidebar.addEventListener("scroll", function () {
                        localStorage.setItem("superAdminSidebarScroll", sidebar.scrollTop);
                    }, { passive: true });

                    sidebar.addEventListener("click", function (e) {
                        if (e.target.closest("a")) {
                            localStorage.setItem("superAdminSidebarScroll", sidebar.scrollTop);
                        }
                    });

                    window.addEventListener("beforeunload", function () {
                        localStorage.setItem("superAdminSidebarScroll", sidebar.scrollTop);
                    });
                } catch (e) {}
            })();
        </script>

        <!-- Mobile Drawer Backdrop -->
        <div x-show="mobileOpen"
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 md:hidden"
             @click="mobileOpen = false"
             aria-hidden="true"></div>

        <!-- Mobile Slide-Over Sidebar Drawer -->
        <aside x-show="mobileOpen"
               x-cloak
               x-transition:enter="transition ease-in-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-300 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-[#1a0505] text-white flex flex-col shadow-2xl md:hidden pt-[env(safe-area-inset-top)] pb-[max(1rem,env(safe-area-inset-bottom))]"
               role="dialog"
               aria-modal="true"
               aria-label="Mobile Navigation Drawer">
            <div class="h-20 flex items-center justify-between px-6 border-b border-white/10 bg-gradient-to-r from-[#8B0000]/25 to-transparent">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-lg overflow-hidden shrink-0">
                        <img src="{{ !empty($cms['website_logo']) ? asset($cms['website_logo']) : asset('images/reoc-nobg.png') }}"
                             alt="WMSU REO Logo"
                             class="w-6 h-6 object-contain">
                    </div>
                    <div class="min-w-0">
                        <span class="font-heading font-bold text-base tracking-wide truncate block" role="presentation">REO Super Admin</span>
                        <p class="text-[10px] text-slate-300/80 uppercase tracking-widest truncate">System Oversight</p>
                    </div>
                </div>
                <button type="button"
                        @click="mobileOpen = false"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                        aria-label="Close navigation menu">
                    <i class="fas fa-times text-sm" aria-hidden="true"></i>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto sidebar-no-scrollbar [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                <div>
                    <p class="px-4 text-[10px] font-extrabold text-slate-300 uppercase tracking-widest mb-3">Review Process</p>
                    <div class="space-y-1">
                        <a href="{{ route('super_admin.analytics') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.analytics') ? 'active' : '' }}">
                            <i class="fas fa-chart-line w-5 text-center"></i>
                            <span class="flex-1">Analytics</span>
                        </a>
                        <a href="{{ route('admin.NewSubmissions') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.NewSubmissions') ? 'active font-bold text-white' : '' }}">
                            <i class="fas fa-inbox w-5 text-center"></i>
                            <span class="flex-1">Initial Intake</span>
                            @if(!empty($sidebarCounts['pendingIntake']))
                                <span class="bg-[#8B0000] text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full shadow-xs">{{ $sidebarCounts['pendingIntake'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.applications') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.applications') ? 'active' : '' }}">
                            <i class="fas fa-folder-open w-5 text-center"></i>
                            <span class="flex-1">Active Protocols</span>
                            @if(!empty($sidebarCounts['activeProtocols']))
                                <span class="bg-[#8B0000] text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full shadow-xs">{{ $sidebarCounts['activeProtocols'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.revisions') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.revisions') ? 'active' : '' }}">
                            <i class="fas fa-sync-alt w-5 text-center"></i>
                            <span class="flex-1">Revisions</span>
                            @if(!empty($sidebarCounts['revisions']))
                                <span class="bg-[#8B0000] text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full shadow-xs">{{ $sidebarCounts['revisions'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.certifications') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.certifications') ? 'active' : '' }}">
                            <i class="fas fa-certificate w-5 text-center"></i>
                            <span class="flex-1">Certifications</span>
                            @if(!empty($sidebarCounts['pendingCertifications']))
                                <span class="bg-[#8B0000] text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full shadow-xs">{{ $sidebarCounts['pendingCertifications'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-4 text-[10px] font-extrabold text-slate-300 uppercase tracking-widest mb-3">Committee</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.meetings') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.meetings*') ? 'active' : '' }}">
                            <i class="fas fa-users w-5 text-center"></i>
                            <span class="flex-1">Meetings & Agenda</span>
                        </a>
                        <a href="{{ route('admin.manage_users') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.manage_users') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate w-5 text-center"></i>
                            <span class="flex-1">Researchers</span>
                        </a>
                        <a href="{{ route('super_admin.manage_admins') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.manage_admins') ? 'active' : '' }}">
                            <i class="fas fa-user-shield w-5 text-center"></i>
                            <span class="flex-1">Manage Admins</span>
                        </a>
                        <a href="{{ route('super_admin.manage_reviewers') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.manage_reviewers') ? 'active' : '' }}">
                            <i class="fas fa-user-check w-5 text-center"></i>
                            <span class="flex-1">Manage Reviewers</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-4 text-[10px] font-extrabold text-slate-300 uppercase tracking-widest mb-3">System Management</p>
                    <div class="space-y-1">
                        <a href="{{ route('super_admin.manage_fees') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.manage_fees') ? 'active' : '' }}">
                            <i class="fas fa-receipt w-5 text-center"></i>
                            <span class="flex-1">Manage Fees</span>
                        </a>
                        <a href="{{ route('admin.manage_documents') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.manage_documents') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice w-5 text-center"></i>
                            <span class="flex-1">Manage Documents</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-4 text-[10px] font-extrabold text-slate-300 uppercase tracking-widest mb-3">Logs</p>
                    <div class="space-y-1">
                        <a href="{{ route('super_admin.revenue_logs') }}"
                            class="nav-item flex items-center gap-3 px-4 py-2.5 rounded-r-lg text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('super_admin.revenue_logs') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
                            <span class="flex-1">Revenue Logs</span>
                        </a>
                    </div>
                </div>
            </nav>

            <div class="p-4 border-t border-white/10 bg-black/20">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-10 h-10 rounded-lg bg-[#8B0000] flex items-center justify-center text-white font-bold shadow-md">
                        {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->first_name ?? 'Admin' }}</p>
                        <p class="text-xs text-slate-300 truncate">Super Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-11 h-11 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/10 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000]" aria-label="Log out of account">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        {{-- ARCHITECTURAL RULE: Do NOT re-introduce any topbar, navbar, or header bar in Admin/Superadmin layouts. Content views render their own full page titles and headers directly. --}}
        <main class="flex-1 flex flex-col h-full overflow-hidden relative bg-[#faf8f8]">
            <!-- Mobile Navigation Trigger (Mobile only, floating to eliminate full topbar) -->
            <div class="md:hidden fixed top-[max(0.875rem,env(safe-area-inset-top))] left-[max(0.875rem,env(safe-area-inset-left))] z-40 print:hidden">
                <button type="button"
                        @click="mobileOpen = true"
                        class="w-11 h-11 rounded-xl bg-white/95 backdrop-blur-md shadow-md border border-slate-200/90 flex items-center justify-center text-slate-700 hover:text-slate-950 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                        aria-label="Open navigation menu">
                    <i class="fas fa-bars text-base" aria-hidden="true"></i>
                </button>
            </div>

            <div class="flex-1 {{ request()->routeIs('admin.view_files*') ? 'overflow-y-auto lg:overflow-hidden p-3 sm:p-4 lg:p-3.5 pt-16 md:pt-3.5 lg:pt-3.5' : 'overflow-y-auto p-4 sm:p-5 lg:p-6 pt-16 md:pt-5 lg:pt-6' }} flex flex-col">
                <x-boneyard-skeleton role="admin" type="admin" contentId="super-admin-page-content" />
                <div id="super-admin-page-content" class="w-full flex-1 flex flex-col">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
    <x-toast />
</body>

</html>