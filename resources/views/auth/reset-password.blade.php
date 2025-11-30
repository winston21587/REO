<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | WMSU REO</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="antialiased h-screen flex items-center justify-center overflow-hidden bg-[#1a0505]">

    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/wmsu2.jpg') }}" alt="WMSU Background" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 from-[#8B0000]/90 via-[#1a0505]/95 to-black/90 mix-blend-multiply"></div>
    </div>

    <div class="relative z-10 w-full max-w-md mx-4">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden animate-[fadeInUp_0.5s_ease-out]">
            
            <div class="bg-slate-900 p-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto border border-white/20 shadow-lg mb-4">
                        <i class="fas fa-lock-open text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-heading font-extrabold text-white">Set New Password</h2>
                    <p class="text-slate-400 text-sm mt-2">Secure your account with a new password.</p>
                </div>
            </div>

            <div class="p-8 md:p-10">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="email" value="{{ request()->email }}">

                    <div class="space-y-1">
                        <label for="code" class="text-xs font-bold text-slate-700 uppercase">Verification Code</label>
                        <input type="text" name="code" id="code" required maxlength="6"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] outline-none text-sm tracking-widest font-mono text-center" 
                            placeholder="000000">
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="text-xs font-bold text-slate-700 uppercase">New Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] outline-none text-sm" 
                            placeholder="••••••••">
                    </div>

                    <div class="space-y-1">
                        <label for="password_confirmation" class="text-xs font-bold text-slate-700 uppercase">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] outline-none text-sm" 
                            placeholder="••••••••">
                    </div>

                    <button type="submit" class="w-full bg-[#8B0000] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-900 hover:-translate-y-0.5 transition-all duration-200 text-sm tracking-wide flex items-center justify-center gap-2">
                        <span>Reset Password</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
