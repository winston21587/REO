<x-layout>
  @include('loading-screen.loading-screen')

  <div id="content">
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

  <!-- FAQ Section -->
  <section class="faq-section py-16 md:py-24">
    <div class="max-w-4xl mx-auto px-6">
      <!-- Section Title -->
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Frequently Asked Questions</h2>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto">Answers to common questions about the Research Ethics Review process</p>
        <div class="w-16 h-1 bg-[#8B0000] mx-auto mt-6 rounded-full"></div>
      </div>

      <!-- FAQ Accordion -->
      <div class="space-y-4">
        <!-- FAQ Item 1 -->
        <div class="faq-item bg-white rounded-lg border-l-4 border-[#8B0000] shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between bg-white hover:bg-slate-50 transition-colors duration-300 focus:outline-none">
            <div class="flex items-center gap-4 flex-1">
              <div class="w-10 h-10 rounded-lg bg-[#FFF4F4] flex items-center justify-center flex-shrink-0">
                <i class="fas fa-question text-[#8B0000] text-lg font-bold"></i>
              </div>
              <span class="text-lg font-semibold text-slate-900">What is the purpose of the Research Ethics Review?</span>
            </div>
            <i class="fas fa-chevron-down text-[#8B0000] font-bold transition-transform duration-300"></i>
          </button>
          <div class="faq-content hidden px-6 py-5 bg-white border-t border-slate-100">
            <p class="text-slate-700 leading-relaxed">The Research Ethics Review ensures that all studies involving human participants adhere to established ethical standards, safeguard participants' rights, and uphold the integrity of the research process.</p>
          </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="faq-item bg-white rounded-lg border-l-4 border-[#8B0000] shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between bg-white hover:bg-slate-50 transition-colors duration-300 focus:outline-none">
            <div class="flex items-center gap-4 flex-1">
              <div class="w-10 h-10 rounded-lg bg-[#FFF4F4] flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-[#8B0000] text-lg font-bold"></i>
              </div>
              <span class="text-lg font-semibold text-slate-900">How long does the ethics review process take?</span>
            </div>
            <i class="fas fa-chevron-down text-[#8B0000] font-bold transition-transform duration-300"></i>
          </button>
          <div class="faq-content hidden px-6 py-5 bg-white border-t border-slate-100">
            <p class="text-slate-700 leading-relaxed">The review process typically takes 2 to 4 weeks, depending on the completeness of the submission and the volume of applications being processed.</p>
          </div>
        </div>

        <!-- FAQ Item 3 -->
        <div class="faq-item bg-white rounded-lg border-l-4 border-[#8B0000] shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between bg-white hover:bg-slate-50 transition-colors duration-300 focus:outline-none">
            <div class="flex items-center gap-4 flex-1">
              <div class="w-10 h-10 rounded-lg bg-[#FFF4F4] flex items-center justify-center flex-shrink-0">
                <i class="fas fa-file-alt text-[#8B0000] text-lg font-bold"></i>
              </div>
              <span class="text-lg font-semibold text-slate-900">What documents are required for submission?</span>
            </div>
            <i class="fas fa-chevron-down text-[#8B0000] font-bold transition-transform duration-300"></i>
          </button>
          <div class="faq-content hidden px-6 py-5 bg-white border-t border-slate-100">
            <p class="text-slate-700 leading-relaxed">Applicants are required to submit a completed application form, a detailed research protocol, informed consent forms, and any additional supporting documents relevant to the study.</p>
          </div>
        </div>

        <!-- FAQ Item 4 -->
        <div class="faq-item bg-white rounded-lg border-l-4 border-[#8B0000] shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between bg-white hover:bg-slate-50 transition-colors duration-300 focus:outline-none">
            <div class="flex items-center gap-4 flex-1">
              <div class="w-10 h-10 rounded-lg bg-[#FFF4F4] flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user-check text-[#8B0000] text-lg font-bold"></i>
              </div>
              <span class="text-lg font-semibold text-slate-900">Who is eligible to submit a research proposal?</span>
            </div>
            <i class="fas fa-chevron-down text-[#8B0000] font-bold transition-transform duration-300"></i>
          </button>
          <div class="faq-content hidden px-6 py-5 bg-white border-t border-slate-100">
            <p class="text-slate-700 leading-relaxed">Faculty members, staff, and students of WMSU, as well as authorized external collaborators, are eligible to submit research proposals for ethical review.</p>
          </div>
        </div>

        <!-- FAQ Item 5 -->
        <div class="faq-item bg-white rounded-lg border-l-4 border-[#8B0000] shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between bg-white hover:bg-slate-50 transition-colors duration-300 focus:outline-none">
            <div class="flex items-center gap-4 flex-1">
              <div class="w-10 h-10 rounded-lg bg-[#FFF4F4] flex items-center justify-center flex-shrink-0">
                <i class="fas fa-bell text-[#8B0000] text-lg font-bold"></i>
              </div>
              <span class="text-lg font-semibold text-slate-900">How will applicants be informed of the review outcome?</span>
            </div>
            <i class="fas fa-chevron-down text-[#8B0000] font-bold transition-transform duration-300"></i>
          </button>
          <div class="faq-content hidden px-6 py-5 bg-white border-t border-slate-100">
            <p class="text-slate-700 leading-relaxed">Applicants will be notified of the review outcome through email and via updates on their account dashboard.</p>
          </div>
        </div>

        <!-- FAQ Item 6 -->
        <div class="faq-item bg-white rounded-lg border-l-4 border-[#8B0000] shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between bg-white hover:bg-slate-50 transition-colors duration-300 focus:outline-none">
            <div class="flex items-center gap-4 flex-1">
              <div class="w-10 h-10 rounded-lg bg-[#FFF4F4] flex items-center justify-center flex-shrink-0">
                <i class="fas fa-chart-gantt text-[#8B0000] text-lg font-bold"></i>
              </div>
              <span class="text-lg font-semibold text-slate-900">Is it possible to track the status of a submission?</span>
            </div>
            <i class="fas fa-chevron-down text-[#8B0000] font-bold transition-transform duration-300"></i>
          </button>
          <div class="faq-content hidden px-6 py-5 bg-white border-t border-slate-100">
            <p class="text-slate-700 leading-relaxed">Yes, applicants may monitor the progress and current status of their submission through their account dashboard.</p>
          </div>
        </div>

        <!-- FAQ Item 7 -->
        <div class="faq-item bg-white rounded-lg border-l-4 border-[#8B0000] shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between bg-white hover:bg-slate-50 transition-colors duration-300 focus:outline-none">
            <div class="flex items-center gap-4 flex-1">
              <div class="w-10 h-10 rounded-lg bg-[#FFF4F4] flex items-center justify-center flex-shrink-0">
                <i class="fas fa-certificate text-[#8B0000] text-lg font-bold"></i>
              </div>
              <span class="text-lg font-semibold text-slate-900">Will a certificate of approval be issued?</span>
            </div>
            <i class="fas fa-chevron-down text-[#8B0000] font-bold transition-transform duration-300"></i>
          </button>
          <div class="faq-content hidden px-6 py-5 bg-white border-t border-slate-100">
            <p class="text-slate-700 leading-relaxed">Yes, an official certificate of approval will be issued for approved submissions and can be downloaded from the applicant's dashboard.</p>
          </div>
        </div>

        <!-- FAQ Item 8 -->
        <div class="faq-item bg-white rounded-lg border-l-4 border-[#8B0000] shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
          <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between bg-white hover:bg-slate-50 transition-colors duration-300 focus:outline-none">
            <div class="flex items-center gap-4 flex-1">
              <div class="w-10 h-10 rounded-lg bg-[#FFF4F4] flex items-center justify-center flex-shrink-0">
                <i class="fas fa-headset text-[#8B0000] text-lg font-bold"></i>
              </div>
              <span class="text-lg font-semibold text-slate-900">Who should be contacted for further assistance?</span>
            </div>
            <i class="fas fa-chevron-down text-[#8B0000] font-bold transition-transform duration-300"></i>
          </button>
          <div class="faq-content hidden px-6 py-5 bg-white border-t border-slate-100">
            <p class="text-slate-700 leading-relaxed">For inquiries or assistance, please contact <span class="font-semibold text-[#8B0000]">reoc@wmsu.edu.ph</span> or use the contact form available on the official website.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

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
    if (navbar) {
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
              if (navH1) {
                navH1.classList.remove('text-white');
                navH1.classList.add('text-[#990101]');
              }
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
              if (navH1) {
                navH1.classList.remove('text-[#990101]');
                navH1.classList.add('text-white');
              }
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
    }

    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
          mobileMenu.style.display = mobileMenu.style.display === 'flex' ? 'none' : 'flex';
        });
        window.addEventListener('resize', () => {
          if (window.innerWidth > 768) {
            mobileMenu.style.display = 'none';
          }
        });
    }

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

    const initFAQ = () => {
      const faqToggles = document.querySelectorAll('.faq-toggle');
      
      faqToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
          e.preventDefault();
          const faqItem = this.parentElement;
          const faqContent = faqItem.querySelector('.faq-content');
          const icon = this.querySelector('.fa-chevron-down');
          
          document.querySelectorAll('.faq-item').forEach(item => {
            if (item !== faqItem) {
              const content = item.querySelector('.faq-content');
              const chevron = item.querySelector('.fa-chevron-down');
              content.classList.add('hidden');
              chevron.style.transform = 'rotate(0deg)';
            }
          });
          
          faqContent.classList.toggle('hidden');
          if (faqContent.classList.contains('hidden')) {
            icon.style.transform = 'rotate(0deg)';
          } else {
            icon.style.transform = 'rotate(180deg)';
          }
        });
      });
    };
    
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initFAQ);
    } else {
      initFAQ();
    }
  </script>

  </div>

</x-layout>