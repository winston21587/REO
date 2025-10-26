{{-- <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
<header>
    <nav class="bg-red-700 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white font-bold text-xl">REO</a>
            <ul class="flex space-x-6">
                <li><a href="/" class="text-white hover:text-blue-200">Home</a></li>
                <li><a href="{{ route('login') }}" class="text-white hover:text-blue-200">Login</a></li>
            </ul>
        </div>
    </nav>
</header>

<main class="container">
    {{ $slot }}
</main>
</body>
</html> --}}




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WMSU REO</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            montserrat: ['Montserrat', 'sans-serif'],
          },
        },
      },
    };
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <style>
    .slide.active { opacity: 1 !important; z-index: 1 !important; }
    .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 1s ease-out, transform 1s ease-out; }
    .fade-in.visible { opacity: 1; transform: translateY(0); }
  </style>
</head>
<body class="font-montserrat bg-[#f4f4f4]">
  <nav id="navbar" class="fixed top-0 left-0 w-full h-[60px] flex justify-between items-center px-[30px] z-[1000] transition-all duration-300 bg-transparent">
    <div class="flex items-center">
      <div class="w-[40px] h-[40px] rounded-full mr-[10px] overflow-hidden">
        <img src="{{ asset('images/reoc-nobg.png') }}" alt="Logo" class="w-full h-full object-cover" />
      </div>
  <h1 class="text-white transition-colors text-xl font-bold">WMSU REO</h1>
    </div>
    <div class="nav-right hidden md:flex items-center">
  <a href="login.php" class="mr-[15px] text-[1rem] no-underline cursor-pointer text-white font-bold">have an account?</a>
  <button onclick="location.href='{{route('login')}}'" type="button" class="px-[22px] py-[9px] rounded-[4px] text-[1rem] bg-gradient-to-br from-[#8B0000] to-[#B22222] text-white font-bold">Login</button>
    </div>
    <div id="hamburger" class="hamburger block md:hidden text-[1.5rem] text-white cursor-pointer"><i class="fas fa-bars"></i></div>
  </nav>
  <div id="mobileMenu" class="mobile-menu hidden fixed top-[60px] right-0 bg-white/95 w-[200px] shadow-md flex-col z-[1000]">
    <a href="{{route('login')}}" class="text-black p-[15px] border-b border-gray-200 text-left text-[1rem]"> have an account?</a>
    <button onclick="location.href='{{route('login')}}'" type="button" class="t
    ext-black p-[15px] text-left bg-none border-none">Login</button>
  </div>
  
  <main>
    {{ $slot }}
  </main>


</body>
</html>