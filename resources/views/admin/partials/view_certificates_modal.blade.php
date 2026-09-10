<!-- View Certificates Modal (Swiss Modernism 2.0 Academic Ledger) -->
<div id="viewCertificatesModal" class="fixed inset-0 z-[120] hidden" aria-labelledby="view-certs-modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop with warm obsidian blur -->
    <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity duration-300 opacity-0" id="viewCertificatesBackdrop" onclick="closeViewCertificatesModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
        <div class="flex min-h-full items-end sm:items-center justify-center p-3 sm:p-4 text-center">
            <div class="pointer-events-auto relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl border border-slate-200/90 transition-all duration-300 sm:my-8 w-full max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="viewCertificatesPanel">
                
                <!-- Institutional Header -->
                <div class="bg-gradient-to-b from-[#faf8f8] to-slate-50 px-6 py-5 border-b border-slate-200/80 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5 min-w-0">
                        <div class="w-11 h-11 rounded-xl bg-[#8B0000]/10 border border-[#8B0000]/20 flex items-center justify-center text-[#8B0000] shrink-0 mt-0.5 shadow-xs">
                            <i class="fas fa-stamp text-lg" aria-hidden="true"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-base font-extrabold text-slate-900 font-heading tracking-tight" id="view-certs-modal-title">
                                    Official Clearance Documents
                                </h3>
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                                    Issued
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Officially certified documents released by the Research Ethics Oversight Committee.
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="closeViewCertificatesModal()" 
                            class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition-colors cursor-pointer shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]" 
                            aria-label="Close modal">
                        <i class="fas fa-times text-base" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Protocol Dynamic Context Pill -->
                <div id="viewCertsContextBar" class="px-6 py-2.5 bg-slate-100/70 border-b border-slate-200/60 flex items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-2 min-w-0">
                        <span id="viewCertsCode" class="font-mono font-bold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200/80 shrink-0">#00000</span>
                        <span id="viewCertsTitle" class="text-slate-600 truncate font-medium max-w-[280px]" title="Protocol title">Research Protocol</span>
                    </div>
                    <span id="viewCertsResearcher" class="text-slate-500 text-[11px] shrink-0 font-medium hidden sm:inline-block">Researcher</span>
                </div>

                <!-- Document Ledger Tiles -->
                <div class="p-6 space-y-3.5">
                    
                    <!-- Document Tile 1: Cover Letter of Approval (REO-Form 02) -->
                    <div class="p-4 rounded-xl border border-slate-200/80 bg-white hover:border-slate-300 hover:shadow-sm transition-all group">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center shrink-0 group-hover:bg-[#8B0000]/10 group-hover:text-[#8B0000] group-hover:border-[#8B0000]/20 transition-colors">
                                    <i class="fas fa-file-invoice text-base" aria-hidden="true"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-bold text-sm text-slate-900 leading-tight">Cover Letter of Approval</h4>
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded border border-slate-200/60">REO-Form 02</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 leading-snug">
                                        Official university endorsement conveying ethical clearance and approved period.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Bar for Cover Letter -->
                        <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <a id="viewApprovalLetterBtn" href="#" target="_blank" rel="noopener noreferrer" 
                               class="inline-flex items-center gap-2 h-9 px-3.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-300 transition-all cursor-pointer group/btn focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                                <i class="fas fa-eye text-slate-400 group-hover/btn:text-slate-600 transition-colors" aria-hidden="true"></i>
                                <span>Preview Document</span>
                                <i class="fas fa-external-link-alt text-[10px] text-slate-400 group-hover/btn:translate-x-0.5 transition-transform" aria-hidden="true"></i>
                            </a>
                            <a id="downloadApprovalLetterBtn" href="#" download
                               class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                                <i class="fas fa-download text-slate-400" aria-hidden="true"></i>
                                <span>Download</span>
                            </a>
                        </div>
                    </div>

                    <!-- Document Tile 2: Certificate of Exemption (REO-Form 01) -->
                    <div class="p-4 rounded-xl border border-slate-200/80 bg-white hover:border-slate-300 hover:shadow-sm transition-all group">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-100 transition-colors">
                                    <i class="fas fa-award text-base" aria-hidden="true"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-bold text-sm text-slate-900 leading-tight">Certificate of Exemption</h4>
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 bg-emerald-50 text-emerald-800 rounded border border-emerald-200/80">REO-Form 01</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 leading-snug">
                                        Ethics Clearance Certificate verifying exemption from full board protocol review.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Bar for Certificate -->
                        <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <a id="viewCertificateBtn" href="#" target="_blank" rel="noopener noreferrer" 
                               class="inline-flex items-center gap-2 h-9 px-3.5 rounded-lg text-xs font-semibold text-emerald-800 bg-emerald-50/70 hover:bg-emerald-100/70 border border-emerald-200/80 hover:border-emerald-300 transition-all cursor-pointer group/btn focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600">
                                <i class="fas fa-eye text-emerald-600 group-hover/btn:text-emerald-700 transition-colors" aria-hidden="true"></i>
                                <span>Preview Certificate</span>
                                <i class="fas fa-external-link-alt text-[10px] text-emerald-600 group-hover/btn:translate-x-0.5 transition-transform" aria-hidden="true"></i>
                            </a>
                            <a id="downloadCertificateBtn" href="#" download
                               class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 transition-all cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                                <i class="fas fa-download text-slate-400" aria-hidden="true"></i>
                                <span>Download</span>
                            </a>
                        </div>
                    </div>

                    <!-- Archival Security Notice -->
                    <div class="p-3 bg-slate-50/80 rounded-xl border border-slate-200/60 flex items-center gap-2.5 text-xs text-slate-600">
                        <i class="fas fa-shield-halved text-emerald-600 shrink-0 text-sm" aria-hidden="true"></i>
                        <span class="leading-relaxed">
                            These clearance documents are legally archived under Western Mindanao State University research governance.
                        </span>
                    </div>

                </div>

                <!-- Footer -->
                <div class="bg-slate-50/90 px-6 py-3.5 border-t border-slate-200/80 flex items-center justify-between">
                    <span class="text-[11px] font-mono text-slate-400">
                        WMSU • REO OVERSIGHT
                    </span>
                    <button type="button" onclick="closeViewCertificatesModal()" 
                            class="h-10 min-h-[40px] px-5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 uppercase tracking-wider bg-white hover:bg-slate-100 transition-colors cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                        Close Ledger
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openViewCertificatesModal(approvalLetterUrl, certificateUrl, code, title, researcher) {
        const modal = document.getElementById('viewCertificatesModal');
        const backdrop = document.getElementById('viewCertificatesBackdrop');
        const panel = document.getElementById('viewCertificatesPanel');
        
        const approvalBtn = document.getElementById('viewApprovalLetterBtn');
        const approvalDl = document.getElementById('downloadApprovalLetterBtn');
        const certificateBtn = document.getElementById('viewCertificateBtn');
        const certificateDl = document.getElementById('downloadCertificateBtn');

        const codeEl = document.getElementById('viewCertsCode');
        const titleEl = document.getElementById('viewCertsTitle');
        const researcherEl = document.getElementById('viewCertsResearcher');
        const contextBar = document.getElementById('viewCertsContextBar');

        // Set Links
        const validLetter = approvalLetterUrl || '#';
        const validCert = certificateUrl || '#';

        if (approvalBtn) approvalBtn.href = validLetter;
        if (approvalDl) approvalDl.href = validLetter;
        if (certificateBtn) certificateBtn.href = validCert;
        if (certificateDl) certificateDl.href = validCert;

        // Set Context metadata if provided
        if (code || title || researcher) {
            if (codeEl) codeEl.textContent = code || 'PROTOCOL';
            if (titleEl) {
                titleEl.textContent = title || '';
                titleEl.title = title || '';
            }
            if (researcherEl) researcherEl.textContent = researcher || '';
            if (contextBar) contextBar.classList.remove('hidden');
        } else {
            if (contextBar) contextBar.classList.add('hidden');
        }

        // Show Modal
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Animate In
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        });
    }

    function closeViewCertificatesModal() {
        const modal = document.getElementById('viewCertificatesModal');
        const backdrop = document.getElementById('viewCertificatesBackdrop');
        const panel = document.getElementById('viewCertificatesPanel');

        // Animate Out
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 250);
    }

    // Keyboard navigation (Escape key to dismiss modal)
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('viewCertificatesModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeViewCertificatesModal();
            }
        }
    });
</script>
