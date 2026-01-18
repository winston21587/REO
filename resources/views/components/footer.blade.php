<footer class="bg-red-950 text-white border-t border-white/10 font-sans">
    <div class="max-w-7xl mx-auto px-6 py-8 md:py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12 text-center md:text-left">

            <div class="col-span-1 md:col-span-1 flex flex-col items-center md:items-start">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('images/reoc-nobg.png') }}" alt="WMSU Logo"
                        class="w-12 h-12 bg-white rounded-full p-0.5">
                    <div class="text-left">
                        <h3 class="font-heading font-bold text-xl tracking-wide">WMSU REO</h3>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest">Research Ethics Office</p>
                    </div>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Safeguarding the rights and welfare of human participants in research through ethical review and
                    compliance.
                </p>
                <div class="flex gap-4 justify-center md:justify-start">
                    <a href="https://www.facebook.com/profile.php?id=100066732383288" target="_blank"
                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#1877F2] transition-colors text-white/70 hover:text-white">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="mailto:reo@wmsu.edu.ph"
                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-brand-primary transition-colors text-white/70 hover:text-white">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6">Quick Access</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li><a href="{{ route('home') }}" class="hover:text-brand-primary transition-colors">Dashboard</a>
                    </li>
                    <li><a href="{{ route('resources') }}" class="hover:text-brand-primary transition-colors">Download
                            Forms</a></li>
                    <li><a href="{{ route('instructions') }}"
                            class="hover:text-brand-primary transition-colors">Submission Guide</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-brand-primary transition-colors">Researcher
                            Login</a></li>
                </ul>
            </div>

            <div class="flex flex-col items-center md:items-start">
                <h4 class="font-bold text-lg mb-6">Contact Us</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li class="flex flex-col md:flex-row items-center md:items-start gap-2 md:gap-3">
                        <i class="fas fa-map-marker-alt mt-1 text-brand-primary hidden md:block"></i>
                        <span class="md:hidden font-bold text-brand-primary mb-1">Address</span>
                        <span>Normal Road, Baliwasan,<br>Zamboanga City 7000</span>
                    </li>
                    <li class="flex flex-col md:flex-row items-center gap-2 md:gap-3">
                        <i class="fas fa-phone-alt text-brand-primary hidden md:block"></i>
                        <span class="md:hidden font-bold text-brand-primary">Phone</span>
                        <span>(062) 991-1771 loc 123</span>
                    </li>
                    <li class="flex flex-col md:flex-row items-center gap-2 md:gap-3">
                        <i class="fas fa-envelope text-brand-primary hidden md:block"></i>
                        <span class="md:hidden font-bold text-brand-primary">Email</span>
                        <span>reo@wmsu.edu.ph</span>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-6">Legal</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li><a href="{{ route('policy.privacy') }}"
                            class="hover:text-brand-primary transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('policy.terms') }}" class="hover:text-brand-primary transition-colors">Terms
                            of Service</a></li>
                    <li><a href="{{ route('policy.accessibility') }}"
                            class="hover:text-brand-primary transition-colors">Accessibility</a></li>
                </ul>
                <div class="mt-8 pt-8 border-t border-white/10">
                    <p class="text-xs text-slate-500">
                        &copy; {{ date('Y') }} WMSU Research Ethics Office.<br>All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>