<x-layout>

  <div class="slider-container relative w-full h-screen overflow-hidden">
    <div
      class="slide active absolute inset-0 w-full h-screen opacity-0 transition-opacity duration-1000 bg-cover bg-center"
      style="background-image: url('./images/reocpic.jpg');">
      <div class="overlay absolute inset-0 bg-black/60 z-10"></div>
      <div
        class="slide-content absolute top-1/2 left-[7%] -translate-y-1/2 z-20 max-w-[600px] text-left text-white p-[20px] md:left-[7%]">
        <h2 class="mb-[10px] text-[2.5rem] md:text-[2.5rem] font-bold">Welcome to REO: Where Research Meets
          Collaboration</h2>
        <p class="text-[1.1rem] leading-[1.5] md:text-[1.1rem]">At REO, we’re creating a space where students, faculty,
          and researchers come together to innovate, share knowledge, and grow. Whether you're looking to collaborate on
          research projects, share valuable resources, or connect with peers REO is your go-to hub for academic
          success. Join our thriving community today and be a part of something bigger.</p>
      </div>
    </div>
    <div class="slide absolute inset-0 w-full h-screen opacity-0 transition-opacity duration-1000 bg-cover bg-center"
      style="background-image: url('./images/reoc2.jpg');">
      <div class="overlay absolute inset-0 bg-black/60 z-10"></div>
      <div
        class="slide-content absolute top-1/2 left-[7%] -translate-y-1/2 z-20 max-w-[600px] text-left text-white p-[20px] md:left-[7%]">
        <h2 class="mb-[10px] text-[2.5rem] md:text-[2.5rem] font-bold">Collaborate, Share, Achieves</h2>
        <p class="text-[1.1rem] leading-[1.5] md:text-[1.1rem]">
          The power of collaboration lies in the exchange of ideas. REO makes it easy for students to pass materials,
          participate in discussions, and contribute to ongoing research projects. From sharing research papers to
          organizing study sessions, everything you need to stay connected and productive is just a click away.The power
          of collaboration lies in the exchange of ideas. REO makes it easy for students to pass materials, participate
          in discussions, and contribute to ongoing research projects. From sharing research papers to organizing study
          sessions, everything you need to stay connected and productive is just a click away.</p>
      </div>
    </div>
    <div class="slide absolute inset-0 w-full h-screen opacity-0 transition-opacity duration-1000 bg-cover bg-center"
      style="background-image: url('./images/reoc3.jpg');">
      <div class="overlay absolute inset-0 bg-black/60 z-10"></div>
      <div
        class="slide-content absolute top-1/2 left-[7%] -translate-y-1/2 z-20 max-w-[600px] text-left text-white p-[20px] md:left-[7%]">
        <h2 class="mb-[10px] text-[2.5rem] md:text-[2.5rem] font-bold">Effortless Meeting & Event Coordination</h2>
        <p class="text-[1.1rem] leading-[1.5] md:text-[1.1rem]">

          Tired of juggling schedules and missing important events? REO streamlines the process of meeting
          organization. Plan study groups, research sessions, or school events with ease. Our intuitive platform lets
          you set dates, send invitations, and manage attendance—all in one place. Stay on top of your academic goals
          without the hassle.</p>
      </div>
    </div>
    <button id="prev"
      class="arrow left absolute top-1/2 -translate-y-1/2 left-[30px] text-white text-[2rem] p-[10px] bg-none z-[10000] hidden md:block"><i
        class="fas fa-chevron-left"></i></button>
    <button id="next"
      class="arrow right absolute top-1/2 -translate-y-1/2 right-[30px] text-white text-[2rem] p-[10px] bg-none z-[10000] hidden md:block"><i
        class="fas fa-chevron-right"></i></button>
  </div>
  <div
    class="msg-container max-w-[1200px] mx-auto p-[20px] gap-[20px] flex flex-col md:flex-row md:items-start md:py-[40px] md:px-[20px]">
    <div class="msg md:w-[60%] md:pr-[40px]">
      <hr class="hr opacity-[0.25] my-[24px] border-t">
      <h3 class="text-[#990101] text-[2.5rem] font-[700] my-[1rem]">Mission</h3>
      <p class="text-[1rem] mb-[1rem]">WMSU-REO/CERC safeguards the general welfare of human participants and animal
        subjects in the conduct of researches.

      </p>
      <hr class="hr opacity-[0.25] my-[24px] border-t">
      <h3 class="text-[#990101] text-[2.5rem] font-[700] my-[1rem]">Vision</h3>
      <p class="text-[1rem] mb-[1rem]">The Western Mindanao State University Research Ethics Office(WMSU
        REO) / College Research Ethics Committee (CERC) is an accredited board instituted to conduct ethics review in
        various fields of researches that involve human participants and animal subjects in the University and the
        region.</p>
      <hr class="hr opacity-[0.25] my-[24px] border-t">
      <h3 class="text-[#990101] text-[2.5rem] font-[700] my-[1rem]">Goals</h3>
      <p class="text-[1rem] mb-[1rem]">WMSU-REO attempts to achieve the following goals: 1. Conduct a quality and
        standard ethical review process for all researches in order to safeguard the rights and welfare of participants
        in any research. 2. Establish and maintain a pool of professional multidisciplinary reviewers to do expedited
        and full review procedure. 3. Ensure compliance to ethical standards in the implementation of research
        protocols.</p>
    </div>
    <img class="msg2 w-full md:w-[36%] md:sticky md:top-[20px]" src="./images/msg2.png" alt="WMSU REO Visual">
  </div>
  {{-- <x-f-a-q /> --}}

  <div
    class="reoc-join-wrapper max-w-[1400px] mx-auto flex items-center justify-between flex-wrap p-[60px_80px] md:p-[60px_80px]">
    <div class="reoc-join-image flex-1 min-w-[300px] p-[10px]">
      <img src="./images/join.png" alt="Art Style Image" class="max-w-full h-auto rounded-[12px]">
    </div>
    <div class="reoc-join-content flex-1 min-w-[300px] p-[10px]">
      <div class="before:block before:w-[50px] before:h-[4px] before:bg-[#990101] before:mb-[12px]"></div>
      <h2 class="reoc-join-title text-[2.2rem] text-[#333] mb-[20px]">Join Us Now</h2>
      <p class="reoc-join-text text-[1rem] text-[#555] leading-[1.6] mb-[30px] font-bold">Why should you join REOC? As a
        premier school research facility, REO offers you access to cutting-edge technology, expert mentorship, and a
        vibrant community passionate about innovation. Be part of groundbreaking projects and help shape the future of
        research and developments.</p>
      <a href="{{ route('register') }}"
        class="reoc-join-btn inline-block bg-[#990101] text-white px-[26px] py-[12px] font-[700] rounded-[6px]">Join
        Us</a>
    </div>
  </div>


  <x-footer/>
  
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