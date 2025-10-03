<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>REO</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
      rel="stylesheet"
    />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
  </head>
  <body class="bg-background-light dark:bg-background-dark font-display">
    <div class="flex min-h-screen">
      <aside
        class="w-64 bg-background-light dark:bg-background-dark/50 flex-col border-r border-background-dark/10 dark:border-background-light/10 hidden md:flex"
      >
        <div
          class="flex items-center gap-4 text-background-dark dark:text-background-light p-4 border-b border-background-dark/10 dark:border-background-light/10 h-16"
        >
          <svg
            class="h-8 w-8 text-primary"
            fill="none"
            viewBox="0 0 48 48"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z"
              fill="currentColor"
            ></path>
          </svg>
          <h1 class="text-xl font-bold">REO</h1>
        </div>
        <nav class="flex-grow p-4">
          <ul>
            <li class="mb-2">
              <a
                class="flex items-center gap-3 bg-primary/10 text-primary font-semibold p-3 rounded-lg"
                href="{{ route('home') }}"
              >
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
              </a>
            </li>
            <li class="mb-2">
              <a
                class="flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
                href="{{ route('home') }}"
              >
                <span class="material-symbols-outlined">upload_file</span>
                Submissions
              </a>
            </li>
            <li class="mb-2">
              <a
                class="flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
                href="{{ route('resources') }}"
              >
                <span class="material-symbols-outlined">source</span> Resources
              </a>
            </li>            
            <li class="mb-2">
              <a
                class="flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
                href="{{ route('instructions') }}"
              >
                <span class="material-symbols-outlined">developer_guide</span>instructions
              </a>
            </li>
            <li class="mb-2">
              <a
                class="flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
                href="{{ route('home') }}"
              >
                <span class="material-symbols-outlined">settings</span> Settings
              </a>
            </li>
            <li class="mb-2">
              <a
                class="flex items-center gap-3 text-background-dark dark:text-background-light hover:bg-primary/10 hover:text-primary p-3 rounded-lg transition-colors"
                href="{{ route('logout') }}"
              >
                <span class="material-symbols-outlined">logout</span> Logout
              </a>
            </li>
          </ul>
        </nav>
      </aside>
      <div class="flex flex-col flex-grow">
          <header
            class="bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm sticky top-0 z-10"
          >
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
              <div class="flex items-center justify-between h-16">
                <button
                  class="md:hidden text-background-dark dark:text-background-light"
                >
                  <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="flex-grow"></div>
                <div class="flex items-center gap-4">
                  <button
                    class="relative text-background-dark dark:text-background-light"
                  >
                    <span class="material-symbols-outlined">notifications</span>
                    <span
                      class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-primary border-2 border-background-light dark:border-background-dark"
                    ></span>

                  </button>

                  <div class="relative group">

                    <button>
                      <div
                        class="w-10 h-10 rounded-full bg-cover bg-center"
                        style="
                          background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDFt9iQwMVvPdYzKFrXuEfATs1yI7V7G7wqJtGhFVBAtH5Udlv3l_1K4GTC48oKlcyigKxZ9wJvGLWApfifueiCct_VmPwxADj5Vtgt-_KSFfFBrXANyQNLwOGQRRr1Gp7uV1pYoiajPdFQLb9sDsxTKdJgvzBHLvD7lU6KrgkvYaceoDcwCtJ-nSdWXBcbcXZbu_9khLMoJu3nDomCmFJN8I28uHSFu1rCbZghfq3Y8kJswFC3TRUuuhKQ_ZmMICvzkTtoCOpvp54M');
                        "
                      ></div>
                    </button>
                    <div class="profile_tab">

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </header>
            {{ $slot }}
                    
        <div class="fixed bottom-8 right-8">
          <button
            class="bg-primary text-white rounded-full h-16 w-16 flex items-center justify-center shadow-lg hover:bg-primary/90 transition-colors"
          >
            <span class="material-symbols-outlined text-3xl">help_outline</span>
          </button>
        </div>
      </div>
    </div>
  </body>
</html>






        