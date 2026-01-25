<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WMSU REO | Research Excellence</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@600;700;800&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="{{ isset($cms['website_logo']) ? asset($cms['website_logo']) : '' }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Alpine.js Plugins & Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif;
        }

        /* Crucial for your Original Slider */
        .slide.active {
            opacity: 1 !important;
            z-index: 1 !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-surface-50 text-slate-800 antialiased selection:bg-[#8B0000] selection:text-white" x-data="{ 
        mobileOpen: false, 
        scrolled: false,
        lastScroll: 0,
        init() {
            // Check initial scroll position
            this.handleScroll();
        },
        handleScroll() {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            const heroHeight = window.innerHeight - 100;
            
            // 1. Color State Logic
            this.scrolled = currentScroll > heroHeight;
            
            // 2. Smart Hide/Show Logic
            const header = document.getElementById('navbar');
            if (header) {
                if (currentScroll > this.lastScroll && currentScroll > 100 && !this.mobileOpen) {
                    header.classList.add('-translate-y-full');
                } else {
                    header.classList.remove('-translate-y-full');
                }
            }
            
            this.lastScroll = currentScroll <= 0 ? 0 : currentScroll;
        }
    }" @scroll.window="handleScroll()">

    <!-- Header / Navbar
         - 'fixed top-0 w-full z-50': Stays at top
         - 'transition-transform duration-300': For the Hide/Show slide effect
         - 'transition-colors duration-300': For the Transparency -> White fade
    -->
    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 border-b border-transparent"
        :class="{ 
            'bg-white/95 backdrop-blur-md shadow-sm border-slate-200/50': scrolled || mobileOpen,
            'bg-transparent': !scrolled && !mobileOpen 
        }">

        <div class="max-w-7xl mx-auto px-6 h-[80px] flex justify-between items-center relative">

            <!-- Logo Section -->
            <div class="flex items-center gap-3 group cursor-pointer" onclick="window.location.href='/'">
                <div
                    class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-white/30 shadow-lg group-hover:scale-105 transition-transform duration-300 relative">
                    <img src="{{ isset($cms['website_logo']) ? asset($cms['website_logo']) : '' }}" alt="Logo"
                        class="w-full h-full object-cover bg-white" />
                    <!-- Dark overlay when scrolled for better contrast if needed, or keep white bg -->
                </div>
                <div class="flex flex-col transition-colors duration-300">
                    <h1 class="text-xl font-extrabold tracking-tight leading-none group-hover:opacity-90"
                        :class="scrolled || mobileOpen ? 'text-[#8B0000]' : 'text-white'">
                        WMSU REO
                    </h1>
                    <span class="text-[10px] font-medium uppercase tracking-widest transition-colors duration-300"
                        :class="scrolled || mobileOpen ? 'text-slate-500' : 'text-white/70'">
                        Research Ethics Office
                    </span>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-6 nav-right">
                <span class="text-sm font-medium transition-colors duration-300"
                    :class="scrolled || mobileOpen ? 'text-slate-600' : 'text-white/80'">
                    Have an account?
                </span>

                <button onclick="location.href='{{ route('login') }}'"
                    class="px-6 py-2.5 rounded-full font-bold text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 active:scale-95 border-2"
                    :class="scrolled || mobileOpen 
                        ? 'bg-[#8B0000] text-white border-transparent hover:bg-[#700000]' 
                        : 'bg-white text-[#8B0000] border-transparent hover:bg-slate-100'">
                    Access Portal
                </button>
            </div>

            <!-- Mobile Hamburger -->
            <button @click="mobileOpen = !mobileOpen"
                class="md:hidden text-2xl p-2 rounded-lg transition-colors focus:outline-none"
                :class="scrolled || mobileOpen ? 'text-slate-800 hover:bg-slate-100' : 'text-white hover:bg-white/10'">
                <!-- Icon swaps based on state -->
                <i class="fas" :class="mobileOpen ? 'fa-times' : 'fa-bars'"></i>
            </button> <!-- Added closing tag here -->
        </div>

        <!-- Mobile Dropdown Menu -->
        <div x-show="mobileOpen" x-collapse x-cloak
            class="md:hidden absolute top-[80px] left-0 w-full bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-xl overflow-hidden">

            <div class="p-6 flex flex-col gap-6">
                <!-- Welcome/Context -->
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-[#8B0000] text-white flex items-center justify-center">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">Welcome Researcher!</p>
                        <p class="text-xs text-slate-500">Access the portal to submit protocols.</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('login') }}"
                        class="flex items-center justify-center gap-2 w-full bg-[#8B0000] text-white py-3 rounded-lg font-bold shadow-md hover:bg-[#7d0000] transition-colors">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="flex items-center justify-center gap-2 w-full bg-white text-slate-700 border border-slate-200 py-3 rounded-lg font-bold hover:bg-slate-50 hover:text-[#8B0000] transition-colors">
                        <i class="fas fa-user-plus"></i> Create Account
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <!-- Scroll logic moved to Alpine.js x-data -->
</body>

</html>