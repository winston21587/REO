@if(Auth::check() && !Auth::user()->first_time)

    <div class="fixed inset-0 z-[9999] flex items-center justify-center px-4 sm:px-0">
        <!-- Backdrop with blur -->
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity duration-300"></div>

        <!-- Modal Container -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-100 animate-[fadeInUp_0.4s_ease-out]">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#8B0000] to-red-900 p-6 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3 text-white">
                    <div class="p-2 bg-white/10 rounded-lg backdrop-blur-md border border-white/20">
                        <i class="fas fa-file-contract text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-heading font-bold tracking-wide">Non-Disclosure Agreement</h2>
                        <p class="text-xs text-red-100 opacity-80">Please review the terms carefully.</p>
                    </div>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="p-8 overflow-y-auto custom-scrollbar space-y-6 text-sm text-slate-600 leading-relaxed">
                
                <!-- Dry Run Notice -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-4 animate-pulse">
                    <div class="text-amber-600">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-amber-800 font-bold mb-1">Temporary Agreement (Dry Run)</h3>
                        <p class="text-amber-700 text-xs">
                            Please be advised that this system is currently in a <strong>Dry Run / Testing Phase</strong>. 
                            All data submitted will be recorded for validation purposes. By proceeding, you acknowledge this testing environment.
                        </p>
                    </div>
                </div>
                
                <!-- Section 1 -->
                <div class="space-y-2">
                    <h3 class="text-[#8B0000] font-bold text-base flex items-center gap-2">
                        <i class="fas fa-shield-alt text-xs opacity-70"></i> Confidentiality & Conflict of Interest
                    </h3>
                    <p class="text-justify bg-slate-50 p-4 rounded-xl border border-slate-100">
                        Researcher acknowledges that during the course of their interaction with the Organization's file submission portal, they may have access to confidential and proprietary information, including but not limited to research data, unpublished findings, and sensitive personal information (collectively, "Confidential Information").
                    </p>
                </div>

                <!-- Section 2 -->
                <div class="space-y-2">
                    <h3 class="text-[#8B0000] font-bold text-base flex items-center gap-2">
                        <i class="fas fa-user-secret text-xs opacity-70"></i> Confidentiality
                    </h3>
                    <p class="text-justify">
                        Any written information provided to the WMSU REO that is confidential, privileged, or proprietary in nature shall be identified accordingly. All confidential information (and any copies and notes thereof) shall remain the sole property of the WMSU REO.
                    </p>
                </div>

                <!-- Section 3 -->
                <div class="space-y-2">
                    <h3 class="text-[#8B0000] font-bold text-base flex items-center gap-2">
                        <i class="fas fa-handshake text-xs opacity-70"></i> Conflict of Interest
                    </h3>
                    <p class="text-justify">
                        It is recognized that the potential for conflict of interest will always exist; however, there is concomitant faith in the ability of the WMSU REO to manage these conflict issues, if any, in such a way that the ultimate outcome of protection of human subject remains.
                    </p>
                    <p class="text-justify">
                        It is the policy of the WMSU REO that no member may participate in the review, comment, or approval of any activity in which he/she has a conflict of interest except to provide information as requested by the WMSU REO.
                    </p>
                    <p class="text-justify">
                        The WMSU REO will immediately disclose any actual or potential conflict of interest that may have in relation to any particular proposal submitted for review, and to abstain from any participation in discussion or recommendation in respect of such proposals.
                    </p>
                </div>

                <!-- Agreement Footer -->
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs text-slate-500 italic text-center">
                        By clicking "Accept," Researcher acknowledges that they have read, understood, and agree to be bound by the terms and conditions of this Non-Disclosure Agreement.
                    </p>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-6 bg-slate-50 border-t border-slate-200 flex justify-end items-center gap-3 shrink-0">
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-200 transition-all duration-200">
                        Decline & Logout
                    </button>
                </form>

                <form action="{{ route('accept.terms') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-[#8B0000] text-white shadow-lg shadow-red-900/20 hover:bg-red-900 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                        <span>Accept Agreement</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom Scrollbar for the modal content */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

@endif