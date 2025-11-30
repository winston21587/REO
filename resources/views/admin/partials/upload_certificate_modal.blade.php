<!-- Upload Certificate Modal -->
<div id="uploadCertificateModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0" id="uploadCertificateBackdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="uploadCertificatePanel">
                
                <!-- Header -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-100">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-upload text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-slate-900" id="modal-title">Upload Certification Documents</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500">Upload the approved documents for <span id="uploadCertificateTitle" class="font-medium text-slate-900"></span>.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form id="uploadCertificateForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 py-5 sm:p-6 space-y-4">
                        
                        <!-- Cover Letter -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Cover Letter of Approval</label>
                            <input type="file" name="cover_letter" accept=".pdf" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                            " required>
                            <p class="mt-1 text-xs text-slate-500">PDF only</p>
                        </div>

                        <!-- Certificate -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Research Ethics Clearance Certificate</label>
                            <input type="file" name="certificate" accept=".pdf" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                            " required>
                            <p class="mt-1 text-xs text-slate-500">PDF only</p>
                        </div>

                        <!-- Pickup Date -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Pickup Date</label>
                            <input type="date" name="pickup_date" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <p class="mt-1 text-xs text-slate-500">Date when the researcher can pick up the documents.</p>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition-colors">
                            Upload & Notify
                        </button>
                        <button type="button" onclick="closeUploadCertificateModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openUploadCertificateModal(id, title) {
        const modal = document.getElementById('uploadCertificateModal');
        const backdrop = document.getElementById('uploadCertificateBackdrop');
        const panel = document.getElementById('uploadCertificatePanel');
        const form = document.getElementById('uploadCertificateForm');
        const titleSpan = document.getElementById('uploadCertificateTitle');

        // Set Form Action
        form.action = `/admin/certificate/upload/${id}`;
        
        // Set Title
        titleSpan.textContent = title;

        // Show Modal
        modal.classList.remove('hidden');
        
        // Animate In
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
    }

    function closeUploadCertificateModal() {
        const modal = document.getElementById('uploadCertificateModal');
        const backdrop = document.getElementById('uploadCertificateBackdrop');
        const panel = document.getElementById('uploadCertificatePanel');

        // Animate Out
        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
