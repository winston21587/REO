<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | WMSU REO</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Montserrat', sans-serif; }
        .bg-\[\#8B0000\] { background-color: #8B0000; }
        .text-\[\#8B0000\] { color: #8B0000; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="min-h-screen flex h-screen overflow-hidden">
        
        <div class="hidden md:flex w-1/2 relative text-white flex-col justify-center p-12 lg:p-16">
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/wmsu1.jpg') }}" class="w-full h-full object-cover" alt="WMSU Campus">
                <div class="absolute inset-0 bg-black/30"></div>
            </div>
            
            <div class="relative z-10 max-w-lg animate-[fadeInUp_0.8s_ease-out]">
                <div class="w-16 h-16 flex items-center justify-center mb-8">
                    <img src="{{ asset('images/reoc-nobg.png') }}" class="w-10 h-10 drop-shadow-md">
                </div>
                
                <h1 class="text-5xl lg:text-6xl font-extrabold font-heading tracking-tight leading-tight drop-shadow-xl">
                    Research <br> Excellence.
                </h1>
                
                <p class="mt-6 text-lg text-white/90 font-medium leading-relaxed drop-shadow-lg max-w-md">
                    Your gateway to ethical research review and compliance at Western Mindanao State University.
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white relative">
            
            <div class="absolute inset-0 md:hidden z-0">
                <img src="{{ asset('images/wmsu1.jpg') }}" class="w-full h-full object-cover opacity-10">
            </div>

            <div class="w-full max-w-md relative z-10">

                <div class="mb-6">
                    <a href="{{ route('index') }}" class="text-slate-500 hover:text-[#8B0000] transition-colors font-bold text-sm inline-flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>
                
                <div class="text-center md:text-left mb-10">
                    <h2 class="text-3xl font-bold text-slate-900 font-heading">Welcome Back</h2>
                    <p class="mt-2 text-slate-500">Please sign in to your researcher account.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email Address</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#8B0000] transition-colors">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" required autofocus 
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] transition-all outline-none text-slate-800 placeholder-slate-400 font-medium"
                                placeholder="name@wmsu.edu.ph">
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Password</label>
                            <a href="{{ route('password.request') }}" class="text-xs text-[#8B0000] font-bold hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#8B0000] transition-colors">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input id="password" type="password" name="password" required 
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] transition-all outline-none text-slate-800 placeholder-slate-400 font-medium"
                                placeholder="••••••••">
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full bg-[#8B0000] hover:bg-red-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-900/20 transition-all duration-200 flex justify-center items-center gap-2 group mt-8">
                        <span>Sign In</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <div class="mt-10 pt-6 border-t border-slate-100 text-center">
                    <p class="text-slate-500 text-sm">
                        New to REO? <a href="{{ route('register') }}" class="text-[#8B0000] font-bold hover:underline ml-1">Create an account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>