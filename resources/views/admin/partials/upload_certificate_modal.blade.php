{{-- Generate Certificates Modal --}}
<div id="generateCertificateModal" class="fixed inset-0 z-50 hidden" aria-labelledby="gen-modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0" id="generateCertificateBackdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="generateCertificatePanel">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-[#8B0000] to-[#a52828] px-6 pt-5 pb-4">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                            <i class="fas fa-file-contract text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold leading-6 text-white" id="gen-modal-title">Generate Certification Documents</h3>
                            <p class="text-xs text-red-200 mt-0.5">For: <span id="generateCertificateTitle" class="font-medium text-white"></span></p>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <form id="generateCertificateForm" action="" method="POST">
                    @csrf
                    <div class="bg-white px-6 py-5 space-y-4">

                        {{-- Researcher Name --}}
                        <div>
                            <label for="researcher_name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Researcher Name</label>
                            <input type="text" id="researcher_name" name="researcher_name"
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent"
                                placeholder="e.g. Juan dela Cruz" required>
                        </div>

                        {{-- Protocol Title --}}
                        <div>
                            <label for="cert_protocol_title" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Research Protocol Title</label>
                            <textarea id="cert_protocol_title" name="protocol_title" rows="2"
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent resize-none"
                                placeholder="Full title of the research protocol" required></textarea>
                        </div>

                        {{-- Protocol Code --}}
                        <div>
                            <label for="protocol_code" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Protocol Code / REOC Code <span class="font-normal text-slate-400 normal-case">(Optional)</span></label>
                            <input type="text" id="protocol_code" name="protocol_code"
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent"
                                placeholder="e.g. REOC-2026-001">
                        </div>

                        {{-- Date Row: Approval + Expiry --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="approval_date" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Date of Approval</label>
                                <input type="date" id="approval_date" name="approval_date"
                                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent"
                                    required>
                            </div>
                            <div>
                                <label for="expiry_date" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Certificate Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date"
                                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent"
                                    required>
                            </div>
                        </div>

                        {{-- Pickup Date --}}
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <label for="pickup_date" class="block text-xs font-semibold text-amber-700 uppercase tracking-wider mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i> Researcher Pickup Date
                            </label>
                            <input type="date" id="pickup_date" name="pickup_date"
                                class="block w-full rounded-lg border border-amber-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                                required>
                            <p class="mt-1 text-xs text-amber-600">The researcher will be notified to pick up the documents on this date.</p>
                        </div>

                        {{-- Info Banner --}}
                        <div class="flex items-start gap-3 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                            <i class="fas fa-info-circle text-blue-400 mt-0.5 flex-shrink-0"></i>
                            <p class="text-xs text-blue-700">
                                Two PDF documents will be generated: the <strong>Cover Letter of Approval</strong> and the <strong>Certificate of Exemption</strong>, using the REO templates. The researcher will receive an in-app notification.
                            </p>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-100">
                        <button type="submit"
                            class="inline-flex items-center gap-2 justify-center rounded-lg bg-[#8B0000] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#a52828] transition-colors">
                            <i class="fas fa-stamp"></i>
                            Generate & Notify
                        </button>
                        <button type="button" onclick="closeGenerateCertificateModal()"
                            class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
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
        titleSpan.textContent = title;
        document.getElementById('cert_protocol_title').value = title;
        document.getElementById('researcher_name').value = researcherName || '';

        // Default approval date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('approval_date').value = today;

        // Default expiry to 1 year from today
        const nextYear = new Date();
        nextYear.setFullYear(nextYear.getFullYear() + 1);
        document.getElementById('expiry_date').value = nextYear.toISOString().split('T')[0];

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

    // Keep backward-compat alias so any old references still work
    function openUploadCertificateModal(id, title, researcherName) {
        openGenerateCertificateModal(id, title, researcherName);
    }
    function closeUploadCertificateModal() {
        closeGenerateCertificateModal();
    }
</script>
