<!-- View Certificates Modal -->
<div id="viewCertificatesModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0" id="viewCertificatesBackdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="viewCertificatesPanel">
                
                <!-- Header -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-100">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-50 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-certificate text-green-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-slate-900" id="modal-title">View Certificates</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500">Access the approved documents below.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Links -->
                <div class="bg-white px-4 py-5 sm:p-6 space-y-3">
                    <a id="viewApprovalLetterBtn" href="#" target="_blank" class="flex items-center justify-between w-full px-4 py-3 bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-xl border border-slate-200 hover:border-blue-200 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-blue-500 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <span class="font-medium text-sm">Cover Letter of Approval</span>
                        </div>
                        <i class="fas fa-external-link-alt text-slate-400 group-hover:text-blue-400"></i>
                    </a>

                    <a id="viewCertificateBtn" href="#" target="_blank" class="flex items-center justify-between w-full px-4 py-3 bg-slate-50 hover:bg-green-50 text-slate-700 hover:text-green-700 rounded-xl border border-slate-200 hover:border-green-200 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-green-500 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-award"></i>
                            </div>
                            <span class="font-medium text-sm">Clearance Certificate</span>
                        </div>
                        <i class="fas fa-external-link-alt text-slate-400 group-hover:text-green-400"></i>
                    </a>
                </div>

                <!-- Footer -->
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="closeViewCertificatesModal()" class="inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openViewCertificatesModal(approvalLetterUrl, certificateUrl) {
        const modal = document.getElementById('viewCertificatesModal');
        const backdrop = document.getElementById('viewCertificatesBackdrop');
        const panel = document.getElementById('viewCertificatesPanel');
        
        const approvalBtn = document.getElementById('viewApprovalLetterBtn');
        const certificateBtn = document.getElementById('viewCertificateBtn');

        // Set Links
        approvalBtn.href = approvalLetterUrl;
        certificateBtn.href = certificateUrl;

        // Show Modal
        modal.classList.remove('hidden');
        
        // Animate In
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
    }

    function closeViewCertificatesModal() {
        const modal = document.getElementById('viewCertificatesModal');
        const backdrop = document.getElementById('viewCertificatesBackdrop');
        const panel = document.getElementById('viewCertificatesPanel');

        // Animate Out
        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
