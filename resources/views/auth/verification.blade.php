<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account | WMSU REO</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Montserrat', sans-serif; }
        .bg-\[\#8B0000\] { background-color: #8B0000; }
    </style>
</head>
<body class="antialiased h-screen flex items-center justify-center overflow-hidden bg-[#1a0505]">

    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/wmsu3.jpg') }}" alt="WMSU Background" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 from-black/80 via-[#1a0505]/90 to-[#8B0000]/80 mix-blend-multiply"></div>
    </div>

    <div class="relative z-10 w-full max-w-md mx-4">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden animate-[fadeInUp_0.5s_ease-out]">
            
            <div class="bg-slate-900 p-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto border border-white/20 shadow-lg mb-4">
                        <i class="fas fa-shield-alt text-2xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-heading font-extrabold text-white">Verify Identity</h2>
                    <p class="text-slate-400 text-sm mt-2">Secure your research account.</p>
                </div>
            </div>

            <div class="p-8 md:p-10">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 text-xs font-bold px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 text-xs font-bold px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                        {{ session('error') }}
                    </div>
                @endif
                <div class="text-center mb-8">
                    <p class="text-sm text-slate-600">
                        We've sent a 6-digit code to<br>
                        <strong class="text-slate-900 font-bold block mt-1 text-base">{{ request('email') ?? 'your email address' }}</strong>
                    </p>
                </div>

                <form action="{{ route('verify.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="email" value="{{ request('email') }}">
                    
                    <div>
                        <label for="code" class="sr-only">Verification Code</label>
                        <input type="text" name="code" id="code" maxlength="6" required autofocus
                            class="w-full text-center text-4xl font-bold tracking-[0.5em] py-4 border-b-2 border-slate-200 focus:border-[#8B0000] outline-none transition-all placeholder-slate-200 text-slate-800 bg-transparent" 
                            placeholder="000000" autocomplete="off">
                    </div>

                    <button type="submit" class="w-full bg-[#8B0000] text-white font-bold py-4 rounded-xl shadow-lg shadow-red-900/20 hover:bg-red-900 hover:-translate-y-0.5 transition-all duration-200 text-sm tracking-wide flex items-center justify-center gap-2 group">
                        <span>Confirm Code</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center space-y-4">
                    <div class="text-xs text-slate-500">
                        Didn't receive the code? 
                        <form action="#" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-[#8B0000] font-bold hover:underline">Resend Code</button>
                        </form>
                    </div>
                    
                    <div>
                        <a href="{{ route('logout') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 inline-flex items-center gap-1 transition-colors">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit when 6 digits are entered (Optional UX enhancement)
        const codeInput = document.getElementById('code');
        codeInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, ''); // Only numbers
            if (this.value.length === 6) {
                // Uncomment next line if you want auto-submit
                // this.form.submit();
            }
        });
    </script>
</body>
</html>