<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WMSU REO | Research Excellence</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Montserrat', sans-serif; }
        
        /* Crucial for your Original Slider */
        .slide.active { opacity: 1 !important; z-index: 1 !important; }
    </style>
</head>
<body class="bg-surface-50 text-slate-800 antialiased selection:bg-[#8B0000] selection:text-white">

    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 border-b border-transparent">
        <div class="max-w-7xl mx-auto px-6 h-[80px] flex justify-between items-center">
            <div class="flex items-center gap-3 group cursor-pointer" onclick="window.location.href='/'">
                <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-white/30 shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/reoc-nobg.png') }}" alt="Logo" class="w-full h-full object-cover bg-white" />
                </div>
                <div class="flex flex-col">
                    <h1 class="text-white text-xl font-extrabold tracking-tight leading-none group-hover:opacity-90 transition-opacity">WMSU REO</h1>
                    <span class="text-white/70 text-[10px] font-medium uppercase tracking-widest">Research Ethics Office</span>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-6 nav-right">
                <span class="text-sm font-medium text-white/80">
                    Have an account?
                </span>
                <button id="nav-cta-btn" onclick="location.href='{{ route('login') }}'" class="bg-white text-[#8B0000] px-6 py-2.5 rounded-full font-bold text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 active:scale-95">
                    Access Portal
                </button>
            </div>

            <div id="hamburger" class="md:hidden text-2xl text-white cursor-pointer p-2 hover:bg-white/10 rounded-lg transition-colors">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <div id="mobileMenu" class="fixed inset-0 bg-[#1a0505]/95 z-40 flex flex-col justify-center items-center gap-8 opacity-0 pointer-events-none transition-opacity duration-300 backdrop-blur-xl">
        <button id="closeMobile" class="absolute top-6 right-6 text-white text-3xl"><i class="fas fa-times"></i></button>
        <span class="text-lg text-white/60">Have an account?</span>
        <a href="{{ route('login') }}" class="text-3xl font-bold text-white">Login</a>
        <a href="{{ route('register') }}" class="text-xl font-bold text-white/70 border-t border-white/10 pt-4 mt-4">Register</a>
    </div>
  
    <main>
        {{ $slot }}
    </main>

    <script>
        const navbar = document.getElementById('navbar');
        const navTexts = document.querySelectorAll('.nav-right span');
        const navBtn = document.getElementById('nav-cta-btn'); // Targeted ID
        const navLogoText = document.querySelector('nav h1');
        const navLogoSub = document.querySelector('nav span');
        const hamburger = document.getElementById('hamburger');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                // Scrolled State (White Bar)
                navbar.classList.add('bg-white/90', 'backdrop-blur-md', 'shadow-sm', 'border-slate-200/50');
                navbar.classList.remove('border-transparent');
                
                // Text Colors
                navLogoText.classList.replace('text-white', 'text-[#8B0000]');
                navLogoSub.classList.replace('text-white/70', 'text-slate-500');
                navTexts.forEach(el => el.classList.replace('text-white/80', 'text-slate-600'));
                hamburger.classList.replace('text-white', 'text-slate-800');
                
                // Button: RED Background, WHITE Text
                navBtn.classList.remove('bg-white', 'text-[#8B0000]');
                navBtn.classList.add('bg-[#8B0000]', 'text-white'); 
            } else {
                // Top State (Transparent)
                navbar.classList.remove('bg-white/90', 'backdrop-blur-md', 'shadow-sm', 'border-slate-200/50');
                navbar.classList.add('border-transparent');
                
                // Text Colors
                navLogoText.classList.replace('text-[#8B0000]', 'text-white');
                navLogoSub.classList.replace('text-slate-500', 'text-white/70');
                navTexts.forEach(el => el.classList.replace('text-slate-600', 'text-white/80'));
                hamburger.classList.replace('text-slate-800', 'text-white');

                // Button: WHITE Background, RED Text
                navBtn.classList.add('bg-white', 'text-[#8B0000]');
                navBtn.classList.remove('bg-[#8B0000]', 'text-white');
            }
        });

        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobile = document.getElementById('closeMobile');

        hamburger.addEventListener('click', () => {
            mobileMenu.classList.remove('opacity-0', 'pointer-events-none');
        });
        closeMobile.addEventListener('click', () => {
            mobileMenu.classList.add('opacity-0', 'pointer-events-none');
        });
    </script>
</body>
</html>