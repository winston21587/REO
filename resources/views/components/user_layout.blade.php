<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>REO</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#ea2a2a",
            "background-light": "#f8f6f6",
            "background-dark": "#211111",
          },
          fontFamily: {
            display: ["Inter"],
          },
          borderRadius: {
            DEFAULT: "0.25rem",
            lg: "0.5rem",
            xl: "0.75rem",
            full: "9999px",
          },
        },
      },
    };
  </script>
  <style>
    .form-select {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ea2a2a' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 0.5rem center;
      background-repeat: no-repeat;
      background-size: 1.5em 1.5em;
      padding-right: 2.5rem;
    }

    /* Sidebar slide effect */
    .sidebar {
      transition: transform 0.3s ease;
    }

    .sidebar-hidden {
      transform: translateX(-100%);
    }

    @media (min-width: 768px) {
      .sidebar-hidden {
        transform: none;
      }
    }
  </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display">
  <div class="flex min-h-screen relative">
    <aside id="sidebar"
      class="sidebar fixed top-0 left-0 h-screen w-64 bg-background-light dark:bg-background-dark/50 flex-col border-r border-background-dark/10 dark:border-background-light/10 sidebar-hidden md:flex z-30">

      <div
        class="flex items-center justify-between text-background-dark dark:text-background-light p-4 border-b border-background-dark/10 dark:border-background-light/10 h-16">
        <div class="flex items-center gap-3">
          <img src="{{ asset('images/reoc-nobg.png') }}" alt="REO LOGO" class="w-8 h-8">
          <h1 class="text-xl font-bold">REO</h1>
        </div>
        <button id="close-sidebar" class="md:hidden text-background-dark dark:text-background-light">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <!-- Close button for mobile -->

      <nav class="flex-grow p-4 overflow-y-auto">
        <ul>
          <li class="mb-2">
            <a class=" {{ request()->routeIs('home') ? 'bg-primary/10 text-primary ' : '' }} flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors "
              href="{{ route('home') }}">
              <span class="material-symbols-outlined">dashboard</span>
              Dashboard
            </a>
          </li>
          <li class="mb-2">
            <a class="{{ request()->routeIs('submit') ? 'bg-primary/10 text-primary ' : '' }} flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
              href="{{ route('submit') }}">
              <span class="material-symbols-outlined">upload_file</span>
              Submissions
            </a>
          </li>
          <li class="mb-2">
            <a class="{{ request()->routeIs('resources') ? 'bg-primary/10 text-primary ' : '' }}  flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
              href="{{ route('resources') }}">
              <span class="material-symbols-outlined">source</span> Resources
            </a>
          </li>
          <li class="mb-2">
            <a class="{{ request()->routeIs('instructions') ? 'bg-primary/10 text-primary ' : '' }}  flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
              href="{{ route('instructions') }}">
              <span class="material-symbols-outlined">developer_guide</span>instructions
            </a>
          </li>
          <li class="mb-2">
            <a class="{{ request()->routeIs('settings') ? 'bg-primary/10 text-primary ' : '' }}  flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
              href="{{ route('settings') }}">
              <span class="material-symbols-outlined">settings</span> Settings
            </a>
          </li>
          <li class="mb-2">
            <a class="flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
              href="{{ route('logout') }}">
              <span class="material-symbols-outlined">logout</span> Logout
            </a>
          </li>
        </ul>
      </nav>
    </aside>
    <!-- Main Content -->
    <div class="flex flex-col flex-grow md:ml-64 transition-all duration-300">

      <header
        class="bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm sticky top-0 z-20 border-b border-background-dark/10 dark:border-background-light/10 ">
        <div class="flex items-center justify-between h-16 px-4">
          <div class="flex items-center gap-3">
            <!-- Menu button -->
            <button id="open-sidebar" class="md:hidden text-background-dark dark:text-background-light">
              <span class="material-symbols-outlined">menu</span>
            </button>

            <h2 class="text-lg font-semibold text-background-dark dark:text-background-light">

              {{ strtoupper(Auth::user()->name) }}

            </h2>
          </div>

          <div class="flex items-center gap-4">
            <button id="notif-btn" class="relative text-background-dark dark:text-background-light">
              <span class="material-symbols-outlined text-2xl">notifications</span>
              <span {{-- notification indicator --}}
                class=" absolute -top-1 -right-1 h-3 w-3 rounded-full bg-primary border-2 border-background-light dark:border-background-dark"></span>
            </button>
            <button  onclick="openProfModal()" class="w-10 h-10 rounded-full bg-cover bg-center ring-2 ring-primary/20"
              style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBUo3VHN1LRMtK9aNhen245LilHHSLXIrtLKlotsyxTHyVGhayRL8zNquJXw6RnS6w3Q0152eT5U6bt8ck4mF7eFpIFpxOgI7VHA3TIwNPPng0h51rN3qet1yBHHAGhQsJAnPrzlgu75y4StUVrgbljvWQVn6dmUPmWxzIQlksem15dg5ux2LvdBKr2d0Y6uqbXxp8gejWg4JYY14SlvDwfzrjwyB4yqbK_fnkQ3hH1dLsbP8oLAIXhmvNeL3Jl2FyDIxoWpWO9v8kx');">
            </button>
          </div>
        </div>

      </header>
      <x-notification-tab/>
      <x-profile/>

      {{ $slot }}



    </div>
  </div>
</body>
<script>
  const sidebar = document.getElementById("sidebar");
  const openBtn = document.getElementById("open-sidebar");
  const closeBtn = document.getElementById("close-sidebar");
  const notifbtn = document.getElementById("notif-btn");
  const notifpanel = document.getElementById("notifications-panel");


  openBtn.addEventListener("click", () => sidebar.classList.remove("sidebar-hidden"));
  closeBtn.addEventListener("click", () => sidebar.classList.add("sidebar-hidden"));

  notifbtn.addEventListener("click", () => {
    notifpanel.classList.toggle("hidden");
  });
  document.addEventListener("click", (event) => {
    if (!notifbtn.contains(event.target) && !notifpanel.contains(event.target)) {
      notifpanel.classList.add("hidden");
    }
  });



</script>

</html>