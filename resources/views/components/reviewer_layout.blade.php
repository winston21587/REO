<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REO Reviewer Portal</title>

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

<body class="bg-[#faf8f8] text-slate-800">

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
                        <h1 class="font-heading font-bold text-lg tracking-wide">REO Reviewer</h1>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest">Protocol Review</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto">
                <div>
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Activities</p>
                    <div class="space-y-1">
                        <!-- My Assigned Protocols -->
                        <a href="{{ route('reviewer.dashboard') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition-all {{ request()->routeIs('reviewer.dashboard') ? 'bg-white/10 text-white border-l-4 border-red-500' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-clipboard-list w-5 text-center"></i>
                            <span class="flex-1">Assigned Protocols</span>
                        </a>

                        <!-- Reviewed Protocols -->
                        <a href="{{ route('reviewer.reviewed_titles') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition-all {{ request()->routeIs('reviewer.reviewed_titles') ? 'bg-white/10 text-white border-l-4 border-red-500' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-check-circle w-5 text-center"></i>
                            <span class="flex-1">Reviewed Protocols</span>
                        </a>

                        <!-- Re-Evaluation -->
                        <a href="{{ route('reviewer.reevaluation') }}"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition-all {{ request()->routeIs('reviewer.reevaluation') ? 'bg-white/10 text-white border-l-4 border-red-500' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i class="fas fa-sync-alt w-5 text-center"></i>
                            <span class="flex-1">Re-Evaluation</span>
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
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->first_name ?? 'Reviewer' }}
                        </p>
                        <p class="text-xs text-slate-400 truncate">System Reviewer</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-white transition-colors"><i
                                class="fas fa-sign-out-alt"></i></button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-full overflow-hidden relative bg-[#faf8f8]">
            <header
                class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-50">
                <h2 class="text-xl font-bold text-slate-800 font-heading">{{ $title ?? 'Reviewer Dashboard' }}</h2>
                <div class="flex items-center gap-4 relative">
                    <!-- Notification Trigger -->
                    <button
                        class="notification-trigger w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-[#8B0000] transition-colors relative focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 group">
                        <i class="fas fa-bell text-xl group-hover:scale-110 transition-transform"></i>
                        <span
                            class="absolute top-1 right-1 w-3 h-3 bg-[#8B0000] border-2 border-white rounded-full animate-pulse shadow-lg hidden"></span>
                    </button>
                </div>
            </header>

            <!-- Notification Toast Card -->
            <div id="notification-toast"
                class="fixed top-24 right-6 hidden bg-white border border-slate-200 rounded-xl shadow-lg p-4 z-[60] opacity-0 scale-95 transition-all duration-300 max-w-xs">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-[#8B0000] rounded-lg flex items-center justify-center text-white flex-shrink-0">
                        <i class="fas fa-bell text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-sm text-slate-800">New Notifications</h4>
                        <p class="text-xs text-slate-600">You have unread notifications</p>
                    </div>
                    <button onclick="document.getElementById('notification-toast').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 ml-2 flex-shrink-0">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Global Notification Tab -->
            <x-notification-tab />

            <div class="flex-1 overflow-y-auto p-8 relative z-0">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Inject generic notification JavaScript -->
    <x-notification-script />
</body>

</html>