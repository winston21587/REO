<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | WMSU REO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Montserrat', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen flex items-center justify-center p-8 bg-white relative">
        <div class="w-full max-w-md relative z-10">
            <div class="text-center md:text-left mb-10">
                <h2 class="text-3xl font-bold text-slate-900 font-heading">Change Your Password</h2>
                <p class="mt-2 text-slate-500">For security reasons, please change your password before continuing to your account.</p>
            </div>

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 text-xs font-bold px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.updateFirstLogin') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider">New Password</label>
                    </div>
                    <div class="relative group" x-data="{ show: false }">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#8B0000] transition-colors">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] transition-all outline-none text-slate-800 placeholder-slate-400 font-medium"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-700 focus:outline-none">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="password_confirmation" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Confirm New Password</label>
                    </div>
                    <div class="relative group" x-data="{ show: false }">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#8B0000] transition-colors">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                            class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] transition-all outline-none text-slate-800 placeholder-slate-400 font-medium"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-700 focus:outline-none">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#8B0000] hover:bg-red-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-900/20 transition-all duration-200 flex justify-center items-center gap-2 group mt-8">
                    <span>Update Password</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
            
            <div class="mt-8 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-slate-500 hover:text-red-600 font-medium transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
