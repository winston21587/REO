<x-layout>

  <div class="slider-container relative w-full h-screen overflow-hidden">

    <div
      class="slide active absolute inset-0 w-full h-screen opacity-0 transition-opacity duration-1000 bg-cover bg-center"
      style="background-image: url('{{ isset($contents['hero_image_1']) ? asset($contents['hero_image_1']) : '' }}');">
      <div class="overlay absolute inset-0 bg-black/60 z-10"></div>

      <div
        class="slide-content absolute top-[25%] bottom-auto left-[6%] md:top-auto md:bottom-[30%] md:left-[10%] z-20 max-w-[700px] text-left text-white p-[20px]">
        <h2 class="mb-[20px] text-3xl md:text-5xl font-bold leading-tight drop-shadow-lg">
          {{ $contents['hero_title_1'] ?? 'Empty' }}
        </h2>
        <p class="text-lg md:text-xl drop-shadow-md leading-relaxed">
          {{ $contents['hero_text_1'] ?? 'Empty' }}
        </p>
      </div>
    </div>

    <div class="slide absolute inset-0 w-full h-screen opacity-0 transition-opacity duration-1000 bg-cover bg-center"
      style="background-image: url('{{ isset($contents['hero_image_2']) ? asset($contents['hero_image_2']) : '' }}');">
      <div class="overlay absolute inset-0 bg-black/60 z-10"></div>

      <div
        class="slide-content absolute top-[25%] bottom-auto left-[6%] md:top-auto md:bottom-[30%] md:left-[10%] z-20 max-w-[700px] text-left text-white p-[20px]">
        <h2 class="mb-[20px] text-3xl md:text-5xl font-bold leading-tight drop-shadow-lg">
          {{ $contents['hero_title_2'] ?? 'Empty' }}
        </h2>
        <p class="text-lg md:text-xl drop-shadow-md leading-relaxed">
          {{ $contents['hero_text_2'] ?? 'Empty' }}
        </p>
      </div>
    </div>

    <div class="slide absolute inset-0 w-full h-screen opacity-0 transition-opacity duration-1000 bg-cover bg-center"
      style="background-image: url('{{ isset($contents['hero_image_3']) ? asset($contents['hero_image_3']) : '' }}');">
      <div class="overlay absolute inset-0 bg-black/60 z-10"></div>

      <div
        class="slide-content absolute top-[25%] bottom-auto left-[6%] md:top-auto md:bottom-[30%] md:left-[10%] z-20 max-w-[700px] text-left text-white p-[20px]">
        <h2 class="mb-[20px] text-3xl md:text-5xl font-bold leading-tight drop-shadow-lg">
          {{ $contents['hero_title_3'] ?? 'Empty' }}
        </h2>
        <p class="text-lg md:text-xl drop-shadow-md leading-relaxed">
          {{ $contents['hero_text_3'] ?? 'Empty' }}
        </p>
      </div>
    </div>

    <button id="prev"
      class="arrow left absolute top-1/2 -translate-y-1/2 left-[30px] text-white text-[2rem] p-[10px] bg-none z-[10000] hidden md:block"><i
        class="fas fa-chevron-left"></i></button>
    <button id="next"
      class="arrow right absolute top-1/2 -translate-y-1/2 right-[30px] text-white text-[2rem] p-[10px] bg-none z-[10000] hidden md:block"><i
        class="fas fa-chevron-right"></i></button>
  </div>

  <section id="mission" class="relative py-24 bg-[#f8fafc]">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-2 gap-16 items-center">

        <div class="relative">
          <div class="absolute -inset-4 bg-brand-primary/5 rounded-2xl rotate-3"></div>
          <img src="{{ isset($contents['purpose_image']) ? asset($contents['purpose_image']) : '' }}" alt="WMSU REO Visual"
            class="relative rounded-xl shadow-2xl w-full object-cover z-10 transform transition-transform hover:scale-[1.01] duration-500">
          <div
            class="absolute -bottom-10 -right-10 bg-white p-6 rounded-xl shadow-xl z-20 hidden md:block max-w-xs border-l-4 border-brand-primary">
            <p class="text-brand-dark font-bold text-lg">"{{ $contents['purpose_text'] ?? 'Empty' }}"</p>
            <p class="text-slate-500 text-sm mt-2">- REO Core Value</p>
          </div>
        </div>

        <div class="space-y-8">
          <div>
            <h2 class="text-[#8B0000] text-sm font-bold uppercase tracking-widest mb-2">Our Purpose</h2>
            <h3 class="text-4xl font-bold text-slate-900 mb-6">{{ $contents['purpose_title'] ?? 'Empty' }}</h3>
          </div>

          <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-[#8B0000] rounded-lg flex items-center justify-center mb-4">
              <i class="fas fa-shield-alt text-white text-xl"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-800 mb-2">Mission</h4>
            <p class="text-slate-600 leading-relaxed">{{ $contents['mission_text'] ?? 'Empty' }}</p>
          </div>

          <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-[#8B0000] rounded-lg flex items-center justify-center mb-4">
              <i class="fas fa-eye text-white text-xl"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-800 mb-2">Vision</h4>
            <p class="text-slate-600 leading-relaxed">{{ $contents['vision_text'] ?? 'Empty' }}</p>
          </div>

          <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-[#8B0000] rounded-lg flex items-center justify-center mb-4">
              <i class="fas fa-bullseye text-white text-xl"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-800 mb-2">Goals</h4>
            <p class="text-slate-600 leading-relaxed">{{ $contents['goals_text'] ?? 'Empty' }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div
    class="reoc-join-wrapper max-w-[1400px] mx-auto flex items-center justify-between flex-wrap p-[40px_20px] md:p-[60px_80px]">
    <div class="reoc-join-image flex-1 min-w-[300px] p-[10px]">
      <img src="{{ isset($contents['join_image']) ? asset($contents['join_image']) : './images/join.png' }}" alt="Art Style Image" class="max-w-full h-auto rounded-[12px]">
    </div>
    <div class="reoc-join-content flex-1 min-w-[300px] p-[10px]">
      <div class="before:block before:w-[50px] before:h-[4px] before:bg-[#8B0000] before:mb-[12px]"></div>
      <h2 class="reoc-join-title text-[2.2rem] text-[#333] mb-[20px]">{{ $contents['join_title'] ?? 'Empty' }}</h2>
      <p class="reoc-join-text text-[1rem] text-[#555] leading-[1.6] mb-[30px] font-bold">
        {{ $contents['join_text'] ?? 'Empty' }}
      </p>
      <a href="{{ route('register') }}"
        class="reoc-join-btn inline-block bg-[#8B0000] text-white px-[26px] py-[12px] font-[700] rounded-[6px] hover:bg-red-800 transition-colors">Join
        Us</a>
    </div>
  </div>

  <x-footer />

  <script>
    const slides = document.querySelectorAll('.slide');
    const next = document.getElementById('next');
    const prev = document.getElementById('prev');
    let currentIndex = 0;
    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) {
          slide.classList.add('active');
        }
      });
    }
    next.addEventListener('click', () => {
      currentIndex = (currentIndex + 1) % slides.length;
      showSlide(currentIndex);
    });
    prev.addEventListener('click', () => {
      currentIndex = (currentIndex - 1 + slides.length) % slides.length;
      showSlide(currentIndex);
    });
    setInterval(() => {
      currentIndex = (currentIndex + 1) % slides.length;
      showSlide(currentIndex);
    }, 5000);
    const navbar = document.getElementById('navbar');
    let lastScrollY = window.scrollY;
    const navH1 = navbar.querySelector('h1');
    const navLinks = navbar.querySelectorAll('.nav-right span');
    const navButton = document.getElementById('nav-cta-btn');

    window.addEventListener('scroll', () => {
      const currentScrollY = window.scrollY;
      if (currentScrollY < lastScrollY) {
        navbar.style.top = "0";
        if (currentScrollY > 0) {
          navbar.classList.add('bg-white', 'shadow');
          navbar.classList.remove('bg-transparent');
          navH1.classList.remove('text-white');
          navH1.classList.add('text-[#990101]');
          navLinks.forEach(el => {
            el.classList.remove('text-white/80');
            el.classList.add('text-black');
          });
          if (navButton) {
            navButton.classList.remove('bg-white', 'text-[#990101]');
            navButton.classList.add('bg-[#990101]', 'text-white');
          }
        } else {
          navbar.classList.remove('bg-white', 'shadow');
          navbar.classList.add('bg-transparent');
          navH1.classList.remove('text-[#990101]');
          navH1.classList.add('text-white');
          navLinks.forEach(el => {
            el.classList.remove('text-black');
            el.classList.add('text-white/80');
          });
          if (navButton) {
            navButton.classList.remove('bg-[#990101]', 'text-white');
            navButton.classList.add('bg-white', 'text-[#990101]');
          }
        }
      } else {
        navbar.style.top = "-80px";
      }
      lastScrollY = currentScrollY;
    });

    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    hamburger.addEventListener('click', () => {
      mobileMenu.style.display = mobileMenu.style.display === 'flex' ? 'none' : 'flex';
    });
    window.addEventListener('resize', () => {
      if (window.innerWidth > 768) {
        mobileMenu.style.display = 'none';
      }
    });
    document.addEventListener("DOMContentLoaded", function () {
      const fadeInElements = document.querySelectorAll(".fade-in");
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
          }
        });
      }, { threshold: 0.1 });
      fadeInElements.forEach(el => observer.observe(el));
    });
  </script>
</x-layout>