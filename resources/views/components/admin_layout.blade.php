<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>REO Admin</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#ea2a2a",
                        "background-light": "#fcf8f8",
                        "background-dark": "#211111",
                    },
                    fontFamily: {
                        display: ["Work Sans", "Noto Sans", "sans-serif"],
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
        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display h-full">
    <div class="relative flex min-h-screen w-full h-full flex-col group/design-root overflow-x-hidden">
        <div class="flex min-h-screen relative">
            <aside
                class="w-64 fixed top-0 left-0  h-screen bg-background-light dark:bg-background-dark p-4 border-r border-[#e7d0d0] dark:border-gray-700 md:flex z-30">
                <div class="flex flex-col gap-8 h-full">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                                data-alt="Admin avatar" style="
                    background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDJiwfGLyHmu7yhnfus7l7MP65IdvEDHo1UoLGrzApnnUtlloncupvUt34XU1YK9l8XmhB-3Tqg5VOtzE0rGX6Fjz1oM-7Gtnqva8M1w3TITwaTyAusBr-TSPbvM4DvfxG_pLPHSi1UByo2PFwn4jMw52psyfbHSyW332B0-t7ZjrBKlx1UV01I4cKTCH753h918dbAOvg9fW8UO4Xhcd3BjjnzB0K7-t0vjiAC9XPwpUvWhtSXjEnNuQ5QoYRhHUlphLuz7IZq1HyI');
                  "></div>
                            <div class="flex flex-col">
                                <h1 class="text-[#1b0e0e] dark:text-white text-base font-medium leading-normal">
                                    Admin
                                </h1>
                                <p class="text-[#994d4d] dark:text-gray-400 text-sm font-normal leading-normal">
                                    admin@research.com
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            {{-- side bar links --}}
                            <a class="{{ request()->routeIs('admin.analytics') ? 'bg-primary/10 ' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                href="{{ route('admin.analytics') }}">
                                <span class="material-symbols-outlined text-[#1b0e0e] dark:text-white">analytics</span>
                                <p class="{{ request()->routeIs('admin.analytics') ? 'text-primary ' : '' }} text-[#1b0e0e] dark:text-white text-sm font-medium leading-normal">
                                    Analytics
                                </p>
                            </a>
                            <a class="{{ request()->routeIs('admin.NewSubmissions') ? 'bg-primary/10 ' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                href="{{ route('admin.NewSubmissions') }}">
                                <span
                                    class="material-symbols-outlined text-[#1b0e0e] dark:text-white">create_new_folder</span>
                                <p class="text-[#1b0e0e] {{ request()->routeIs('admin.NewSubmissions') ? 'text-primary ' : '' }} dark:text-white text-sm font-medium leading-normal">
                                    New Submissions
                                </p>
                            </a>  
                            <a class="{{ request()->routeIs('admin.Review') ? 'bg-primary/10 ' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                href="{{ route('admin.Review') }}">
                                <span
                                    class="material-symbols-outlined text-[#1b0e0e] dark:text-white">folder_data</span>
                                <p class="text-[#1b0e0e] {{ request()->routeIs('admin.Review') ? 'text-primary ' : '' }} dark:text-white text-sm font-medium leading-normal">
                                    Reviews
                                </p>
                            </a> 
                            <a class="{{ request()->routeIs('admin.Revision') ? 'bg-primary/10 ' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                href="{{ route('admin.Revision') }}">
                                <span
                                    class="material-symbols-outlined text-[#1b0e0e] dark:text-white">folder_match</span>
                                <p class="text-[#1b0e0e] {{ request()->routeIs('admin.Revision') ? 'text-primary ' : '' }} dark:text-white text-sm font-medium leading-normal">
                                    Revisions
                                </p>
                            </a>                             

                            <a class="{{ request()->routeIs('admin.appointment') ? 'bg-primary/10 ' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                href="{{ route('admin.appointment') }}">
                                <span
                                    class="material-symbols-outlined text-[#1b0e0e] dark:text-white">calendar_today</span>
                                <p class="text-[#1b0e0e] {{ request()->routeIs('admin.appointment') ? 'text-primary ' : '' }} dark:text-white text-sm font-medium leading-normal">
                                    Appointments
                                </p>
                            </a>                                                                             
                            <a class=" flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                href="">
                                <span
                                    class="material-symbols-outlined text-[#1b0e0e] dark:text-white">archive</span>
                                <p class="text-[#1b0e0e]  dark:text-white text-sm font-medium leading-normal">
                                    Content Management
                                </p>
                            </a>                                
                            <a class="{{ request()->routeIs('admin.manage_users') ? 'bg-primary/10 ' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                href="{{ route('admin.manage_users') }}">
                                <span
                                    class="material-symbols-outlined text-[#1b0e0e] dark:text-white">group</span>
                                <p class="text-[#1b0e0e] {{ request()->routeIs('admin.manage_users') ? 'text-primary ' : '' }} dark:text-white text-sm font-medium leading-normal">
                                    Users
                                </p>
                            </a>  
                            <a class="{{ request()->routeIs('admin.manage_staff') ? 'bg-primary/10 ' : '' }} flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                href="{{ route('admin.manage_staff') }}">
                                <span
                                    class="material-symbols-outlined text-[#1b0e0e] dark:text-white">person_apron</span>
                                <p class="text-[#1b0e0e] {{ request()->routeIs('admin.manage_staff') ? 'text-primary ' : '' }} dark:text-white text-sm font-medium leading-normal">
                                    Admin
                                </p>
                            </a>  





                        </div>
                    </div>
                    <div class="mt-auto">
                        <button
                            class="flex min-w-[84px] w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
            <a class="flex items-center gap-3 text-white  hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
              href="{{ route('logout') }}">
              <span class="material-symbols-outlined">logout</span> Logout
            </a>                        </button>
                    </div>
                </div>
            </aside>


            <main class="flex flex-col flex-grow md:ml-64 transition-all duration-300 p-5">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>