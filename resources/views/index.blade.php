<x-layout>
    
  <div class="slider-container relative w-full h-screen overflow-hidden">
    <div class="slide active absolute inset-0 w-full h-screen opacity-0 transition-opacity duration-1000 bg-cover bg-center" style="background-image: url('./images/reocpic.jpg');">
      <div class="overlay absolute inset-0 bg-black/60 z-10"></div>
      <div class="slide-content absolute top-1/2 left-[7%] -translate-y-1/2 z-20 max-w-[600px] text-left text-white p-[20px] md:left-[7%]">
  <h2 class="mb-[10px] text-[2.5rem] md:text-[2.5rem] font-bold">page 1 head</h2>
  <p class="text-[1.1rem] leading-[1.5] md:text-[1.1rem]">page 1 text</p>
      </div>
    </div>
    <div class="slide absolute inset-0 w-full h-screen opacity-0 transition-opacity duration-1000 bg-cover bg-center" style="background-image: url('./images/reoc2.jpg');">
      <div class="overlay absolute inset-0 bg-black/60 z-10"></div>
      <div class="slide-content absolute top-1/2 left-[7%] -translate-y-1/2 z-20 max-w-[600px] text-left text-white p-[20px] md:left-[7%]">
  <h2 class="mb-[10px] text-[2.5rem] md:text-[2.5rem] font-bold">page 2 head</h2>
  <p class="text-[1.1rem] leading-[1.5] md:text-[1.1rem]">page 2 text</p>
      </div>
    </div>
    <div class="slide absolute inset-0 w-full h-screen opacity-0 transition-opacity duration-1000 bg-cover bg-center" style="background-image: url('./images/reoc3.jpg');">
      <div class="overlay absolute inset-0 bg-black/60 z-10"></div>
      <div class="slide-content absolute top-1/2 left-[7%] -translate-y-1/2 z-20 max-w-[600px] text-left text-white p-[20px] md:left-[7%]">
  <h2 class="mb-[10px] text-[2.5rem] md:text-[2.5rem] font-bold">page 3 head</h2>
  <p class="text-[1.1rem] leading-[1.5] md:text-[1.1rem]">page 3 text</p>
      </div>
    </div>
    <button id="prev" class="arrow left absolute top-1/2 -translate-y-1/2 left-[30px] text-white text-[2rem] p-[10px] bg-none z-[10000] hidden md:block"><i class="fas fa-chevron-left"></i></button>
    <button id="next" class="arrow right absolute top-1/2 -translate-y-1/2 right-[30px] text-white text-[2rem] p-[10px] bg-none z-[10000] hidden md:block"><i class="fas fa-chevron-right"></i></button>
  </div>
  <div class="msg-container max-w-[1200px] mx-auto p-[20px] gap-[20px] flex flex-col md:flex-row md:items-start md:py-[40px] md:px-[20px]">
    <div class="msg md:w-[60%] md:pr-[40px]">
      <hr class="hr opacity-[0.25] my-[24px] border-t">
      <h3 class="text-[#990101] text-[2.5rem] font-[700] my-[1rem]">Mission</h3>
  <p class="text-[1rem] mb-[1rem]">MISSION TEXT SAMPLE</p>
      <hr class="hr opacity-[0.25] my-[24px] border-t">
      <h3 class="text-[#990101] text-[2.5rem] font-[700] my-[1rem]">Vision</h3>
  <p class="text-[1rem] mb-[1rem]">VISION TEXT SAMPLE</p>
      <hr class="hr opacity-[0.25] my-[24px] border-t">
      <h3 class="text-[#990101] text-[2.5rem] font-[700] my-[1rem]">Goals</h3>
  <p class="text-[1rem] mb-[1rem]">GOALS TEXT SAMPLE</p>
    </div>
    <img class="msg2 w-full md:w-[36%] md:sticky md:top-[20px]" src="./images/msg2.png" alt="WMSU REOC Visual">
  </div>
     <x-f-a-q/>

    <div class="reoc-join-wrapper max-w-[1400px] mx-auto flex items-center justify-between flex-wrap p-[60px_80px] md:p-[60px_80px]">
        <div class="reoc-join-image flex-1 min-w-[300px] p-[10px]">
            <img src="./images/join.png" alt="Art Style Image" class="max-w-full h-auto rounded-[12px]">
        </div>
        <div class="reoc-join-content flex-1 min-w-[300px] p-[10px]">
            <div class="before:block before:w-[50px] before:h-[4px] before:bg-[#990101] before:mb-[12px]"></div>
            <h2 class="reoc-join-title text-[2.2rem] text-[#333] mb-[20px]">Join Us Now</h2>
            <p class="reoc-join-text text-[1rem] text-[#555] leading-[1.6] mb-[30px] font-bold">JOIN US TEXT SAMPLE</p>
      <a href="signup.php" class="reoc-join-btn inline-block bg-[#990101] text-white px-[26px] py-[12px] font-[700] rounded-[6px]">Join Us</a>
    </div>
  </div>
  
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
    const navLinks = navbar.querySelectorAll('.nav-right a');
    const navButton = navbar.querySelector('.nav-right button');
    window.addEventListener('scroll', () => {
      const currentScrollY = window.scrollY;
      if (currentScrollY < lastScrollY) {
        navbar.style.top = "0";
        if (currentScrollY > 0) {
          navbar.classList.add('bg-white', 'shadow');
          navbar.classList.remove('bg-transparent');
          navH1.classList.remove('text-white');
          navH1.classList.add('text-black');
          navLinks.forEach(el => {
            el.classList.remove('text-white');
            el.classList.add('text-black');
          });
          if (navButton) {
            navButton.classList.add('text-white');
            navButton.classList.remove('text-black');
          }
        } else {
          navbar.classList.remove('bg-white', 'shadow');
          navbar.classList.add('bg-transparent');
          navH1.classList.remove('text-black');
          navH1.classList.add('text-white');
          navLinks.forEach(el => {
            el.classList.remove('text-black');
            el.classList.add('text-white');
          });
          if (navButton) {
            navButton.classList.add('text-white');
            navButton.classList.remove('text-black');
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
      if(window.innerWidth > 768) {
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


