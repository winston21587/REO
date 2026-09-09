{{-- Generate Certificates Modal --}}
<div id="generateCertificateModal" class="fixed inset-0 z-[120] hidden" aria-labelledby="gen-modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity opacity-0" id="generateCertificateBackdrop" onclick="closeGenerateCertificateModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="pointer-events-auto relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl border border-slate-200/80 transition-all sm:my-8 sm:w-full sm:max-w-xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="generateCertificatePanel">

                {{-- Header --}}
                <div class="bg-[#1a0505] px-6 py-5 border-b border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-white shrink-0">
                            <i class="fas fa-file-contract text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base font-bold text-white font-heading truncate" id="gen-modal-title">Generate Certification Documents</h3>
                            <p class="text-xs text-rose-200/80 mt-0.5 truncate">For: <span id="generateCertificateTitle" class="font-semibold text-white"></span></p>
                        </div>
                    </div>
                    <button type="button" onclick="closeGenerateCertificateModal()" class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl text-white/60 hover:text-white hover:bg-white/10 transition-colors cursor-pointer shrink-0" aria-label="Close modal">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>

                {{-- Form --}}
                <form id="generateCertificateForm" action="" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="generate">
                    <div class="bg-white px-6 py-5 space-y-4 max-h-[calc(100vh-220px)] overflow-y-auto">

                        {{-- Researcher Name --}}
                        <div>
                            <label for="researcher_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Researcher Name</label>
                            <input type="text" id="researcher_name" name="researcher_name"
                                   class="block w-full rounded-xl border border-slate-200 bg-slate-50/70 px-3.5 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-shadow"
                                   placeholder="e.g. Juan dela Cruz" required>
                        </div>

                        {{-- Protocol Title --}}
                        <div>
                            <label for="cert_protocol_title" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Research Protocol Title</label>
                            <textarea id="cert_protocol_title" name="protocol_title" rows="2"
                                      class="block w-full rounded-xl border border-slate-200 bg-slate-50/70 px-3.5 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent resize-none transition-shadow"
                                      placeholder="Full title of the research protocol" required></textarea>
                        </div>

                        {{-- Protocol Code --}}
                        <div>
                            <label for="protocol_code" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Protocol Code / REOC Code <span class="font-normal text-slate-400 normal-case">(Optional)</span></label>
                            <input type="text" id="protocol_code" name="protocol_code"
                                   class="block w-full rounded-xl border border-slate-200 bg-slate-50/70 px-3.5 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-shadow"
                                   placeholder="e.g. REOC-2026-001">
                        </div>

                        {{-- Date Row: Approval + Expiry --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="approval_date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Date of Approval</label>
                                <input type="date" id="approval_date" name="approval_date"
                                       class="block w-full rounded-xl border border-slate-200 bg-slate-50/70 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-shadow"
                                       required>
                            </div>
                            <div>
                                <label for="expiry_date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Certificate Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date"
                                       class="block w-full rounded-xl border border-slate-200 bg-slate-50/70 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-shadow"
                                       required>
                            </div>
                        </div>

                        {{-- Pickup Date --}}
                        <div class="p-4 bg-amber-50/80 border border-amber-200/80 rounded-xl space-y-1.5">
                            <label for="pickup_date" class="block text-xs font-bold text-amber-900 uppercase tracking-wider">
                                <i class="fas fa-calendar-alt mr-1 text-amber-700"></i> Researcher Pickup Date
                            </label>
                            <input type="date" id="pickup_date" name="pickup_date"
                                   class="block w-full rounded-xl border border-amber-300/80 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow"
                                   required>
                            <p class="text-xs text-amber-800">The researcher will be notified to pick up the documents on this date.</p>
                        </div>

                        {{-- Info Banner --}}
                        <div class="flex items-start gap-3 p-4 bg-blue-50/70 border border-blue-200/70 rounded-xl">
                            <i class="fas fa-info-circle text-blue-600 text-sm mt-0.5 shrink-0"></i>
                            <p class="text-xs text-blue-900 leading-relaxed">
                                Two PDF documents will be generated: the <strong>Cover Letter of Approval</strong> and the <strong>Certificate of Exemption</strong>, using the REO templates. The researcher will receive an in-app notification.
                            </p>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="bg-slate-50/80 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100">
                        <button type="submit"
                                class="inline-flex items-center gap-2 justify-center rounded-xl bg-[#8B0000] px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#a52828] focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 transition-all cursor-pointer">
                            <i class="fas fa-stamp text-xs"></i>
                            <span>Generate & Notify</span>
                        </button>
                        <button type="button" onclick="closeGenerateCertificateModal()"
                                class="inline-flex justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-2xs border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-colors cursor-pointer">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openGenerateCertificateModal(id, title, researcherName) {
        const modal     = document.getElementById('generateCertificateModal');
        const backdrop  = document.getElementById('generateCertificateBackdrop');
        const panel     = document.getElementById('generateCertificatePanel');
        const form      = document.getElementById('generateCertificateForm');
        const titleSpan = document.getElementById('generateCertificateTitle');

        // Set route
        form.action = `/admin/certificate/generate/${id}`;

        // Pre-fill fields
        if (titleSpan) titleSpan.textContent = title;
        const certTitleInput = document.getElementById('cert_protocol_title');
        if (certTitleInput) certTitleInput.value = title;
        const researcherInput = document.getElementById('researcher_name');
        if (researcherInput) researcherInput.value = researcherName || '';

        // Default approval date to today
        const today = new Date().toISOString().split('T')[0];
        const approvalInput = document.getElementById('approval_date');
        if (approvalInput) approvalInput.value = today;

        // Default expiry to 1 year from today
        const nextYear = new Date();
        nextYear.setFullYear(nextYear.getFullYear() + 1);
        const expiryInput = document.getElementById('expiry_date');
        if (expiryInput) expiryInput.value = nextYear.toISOString().split('T')[0];

        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
    }

    function closeGenerateCertificateModal() {
        const modal    = document.getElementById('generateCertificateModal');
        const backdrop = document.getElementById('generateCertificateBackdrop');
        const panel    = document.getElementById('generateCertificatePanel');

        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');

        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // Keep backward-compat alias
    function openUploadCertificateModal(id, title, researcherName) {
        openGenerateCertificateModal(id, title, researcherName);
    }
    function closeUploadCertificateModal() {
        closeGenerateCertificateModal();
    }
</script>
