<!-- Revision Status Update Modal -->
<div id="revisionStatusModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300 opacity-0"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" onclick="closeRevisionStatusModal()">
    </div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div id="revisionStatusModalContent"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl scale-95 duration-300 max-h-[90vh] flex flex-col border border-slate-200/80">

            <!-- Header -->
            <div class="bg-white px-5 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-[#8B0000] flex-shrink-0">
                            <i class="fas fa-sync-alt text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold leading-6 text-slate-900 font-heading" id="modal-title">Update
                                Revision Status</h3>
                            <p class="text-xs text-slate-500 mt-0.5" id="revisionStatusModalTitle">Protocol Title</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeRevisionStatusModal()"
                        class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer" aria-label="Close modal">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-5 py-6 sm:p-6 bg-slate-50/50 overflow-y-auto">
                <form id="revisionStatusForm" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="status_action" id="revisionStatusActionInput">

                    <!-- =============================================
                             DELIBERATION NOTES (Required - Top Section)
                             ============================================= -->

                    <!-- Scientific Soundness -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            <i class="fas fa-microscope text-indigo-500 mr-1.5"></i> Scientific Soundness <span
                                class="text-red-500">*</span>
                        </label>
                        <textarea id="deliberation_scientific" name="scientific_soundness" rows="3" readonly
                            placeholder="No feedback provided..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50/80 text-slate-600 cursor-not-allowed shadow-2xs resize-none focus:outline-none leading-relaxed"></textarea>
                    </div>

                    <!-- Ethical Issues -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            <i class="fas fa-balance-scale text-amber-500 mr-1.5"></i> Ethical Issues <span
                                class="text-red-500">*</span>
                        </label>
                        <textarea id="deliberation_ethical" name="ethical_issues" rows="3" readonly
                            placeholder="No feedback provided..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50/80 text-slate-600 cursor-not-allowed shadow-2xs resize-none focus:outline-none leading-relaxed"></textarea>
                    </div>

                    <!-- ICF Issues -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            <i class="fas fa-file-signature text-emerald-500 mr-1.5"></i> Informed Consent Form (ICF)
                            Issues <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deliberation_icf" name="icf_issues" rows="3" readonly
                            placeholder="No feedback provided..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50/80 text-slate-600 cursor-not-allowed shadow-2xs resize-none focus:outline-none leading-relaxed"></textarea>
                    </div>

                    <!-- Summary of Issues and Resolutions -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            <i class="fas fa-list-check text-rose-500 mr-1.5"></i> Summary of Issues and Resolutions <span
                                class="text-red-500">*</span>
                        </label>
                        <textarea id="deliberation_summary" name="summary_of_issues" rows="3" readonly
                            placeholder="No feedback provided..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50/80 text-slate-600 cursor-not-allowed shadow-2xs resize-none focus:outline-none leading-relaxed"></textarea>
                    </div>

                    <!-- =============================================
                             HISTORICAL FEEDBACK ACCORDION 
                             ============================================= -->
                    <div id="historicalFeedbackContainer" class="hidden mt-6 mb-2">
                        <details
                            class="group bg-white border border-slate-200 rounded-xl overflow-hidden shadow-2xs transition-all duration-300">
                            <summary
                                class="flex items-center justify-between cursor-pointer p-4 hover:bg-slate-50 transition-colors focus:outline-none">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-8 w-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 group-open:text-indigo-600 shadow-2xs transition-colors">
                                        <i class="fas fa-history text-xs"></i>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">View Previous Remarks</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="bg-indigo-50 text-indigo-700 border border-indigo-200/60 px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                        id="historicalRoundCount">0</span>
                                    <i
                                        class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-300 group-open:rotate-180"></i>
                                </div>
                            </summary>
                            <div class="p-4 bg-slate-50/50 border-t border-slate-100 space-y-4 max-h-[40vh] overflow-y-auto"
                                id="historicalFeedbackContent">
                                <!-- Dynamic Content Here -->
                            </div>
                        </details>
                    </div>

                    <!-- Divider -->
                    <div class="flex items-center gap-3 pt-2">
                        <div class="h-px bg-slate-200 flex-1"></div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Final
                            Action</span>
                        <div class="h-px bg-slate-200 flex-1"></div>
                    </div>

                    <!-- =============================================
                             STATUS ACTIONS (Bottom Section)
                             Panel Deliberation REMOVED per SOP
                             ============================================= -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Action Taken</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <!-- Modifications Required -->
                            <div onclick="selectRevisionStatus('Modifications Required', this)"
                                class="revision-status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-orange-400 hover:shadow-xs transition-all group">
                                <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                    <i class="fas fa-check-circle text-orange-500"></i>
                                </div>
                                <div
                                    class="icon-box w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-orange-500">
                                    <i class="fas fa-edit text-base"></i>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm">Modifications Required</h4>
                                <p class="text-xs text-slate-500 mt-1">Minor or Major revisions</p>
                            </div>

                            <!-- Disapproved -->
                            <div onclick="selectRevisionStatus('Disapproved', this)"
                                class="revision-status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-red-400 hover:shadow-xs transition-all group">
                                <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                    <i class="fas fa-check-circle text-red-500"></i>
                                </div>
                                <div
                                    class="icon-box w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-red-500">
                                    <i class="fas fa-times-circle text-base"></i>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm">Disapproved</h4>
                                <p class="text-xs text-slate-500 mt-1">Serious ethical violations</p>
                            </div>

                            <!-- Approve -->
                            <div onclick="selectRevisionStatus('Approved', this)"
                                class="revision-status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-emerald-400 hover:shadow-xs transition-all group">
                                <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                    <i class="fas fa-check-circle text-emerald-500"></i>
                                </div>
                                <div
                                    class="icon-box w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-emerald-500">
                                    <i class="fas fa-award text-base"></i>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm">Approve</h4>
                                <p class="text-xs text-slate-500 mt-1">Issue Clearance</p>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Date -->
                    <div>
                        <label for="revisionAppointmentDate" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Set
                            Appointment / Deadline</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="far fa-calendar-alt text-slate-400 text-sm"></i>
                            </div>
                            <input type="date" id="revisionAppointmentDate" name="appointment_date"
                                min="{{ date('Y-m-d') }}"
                                class="h-11 min-h-[44px] w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-2xs transition-all bg-white">
                        </div>
                    </div>

                    <!-- Message Box -->
                    <div>
                        <label for="revisionRemarks" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Notification
                            Message <span class="text-slate-400 font-normal lowercase tracking-normal">(optional)</span></label>
                        <textarea id="revisionRemarks" name="remarks" rows="3"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-2xs transition-all resize-none bg-white"
                            placeholder="Add any specific instructions or remarks for the researcher..."></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeRevisionStatusModal()"
                            class="h-11 min-h-[44px] flex-1 px-4 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-2xs cursor-pointer flex items-center justify-center">
                            Cancel
                        </button>
                        <button type="submit" id="submitRevisionStatusBtn"
                            class="h-11 min-h-[44px] flex-1 px-4 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#6d0000] transition-all shadow-md shadow-red-900/20 flex justify-center items-center gap-2 cursor-pointer">
                            <span>Update & Notify</span> <i class="fas fa-paper-plane text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function selectRevisionStatus(status, element) {
        // 1. Update Hidden Input
        document.getElementById('revisionStatusActionInput').value = status;

        // 2. Visual Selection: Remove active class from all options
        document.querySelectorAll('.revision-status-option').forEach(el => {
            el.classList.remove('border-orange-400', 'bg-orange-50/60', 'border-red-400', 'bg-red-50/60', 'border-emerald-400', 'bg-emerald-50/60');
            el.classList.add('border-slate-200');
            el.querySelector('.check-icon').classList.add('opacity-0');
            const iconBox = el.querySelector('.icon-box');
            iconBox.classList.remove('text-orange-500', 'text-red-500', 'text-emerald-500');
            iconBox.classList.add('text-slate-400');
        });

        // Add active class to clicked option
        element.classList.remove('border-slate-200');

        let activeClass = '';
        let activeText = '';
        const submitBtn = document.getElementById('submitRevisionStatusBtn');

        if (status === 'Modifications Required') {
            activeClass = 'border-orange-400 bg-orange-50/60';
            activeText = 'text-orange-500';
            submitBtn.innerHTML = '<span>Generate Letter</span> <i class="fas fa-file-invoice text-xs"></i>';
        } else if (status === 'Disapproved') {
            activeClass = 'border-red-400 bg-red-50/60';
            activeText = 'text-red-500';
            submitBtn.innerHTML = '<span>Update & Notify</span> <i class="fas fa-paper-plane text-xs"></i>';
        } else if (status === 'Approved') {
            activeClass = 'border-emerald-400 bg-emerald-50/60';
            activeText = 'text-emerald-500';
            submitBtn.innerHTML = '<span>Update & Notify</span> <i class="fas fa-paper-plane text-xs"></i>';
        }

        const classes = activeClass.split(' ');
        element.classList.add(...classes);

        element.querySelector('.check-icon').classList.remove('opacity-0');
        element.querySelector('.icon-box').classList.remove('text-slate-400');
        element.querySelector('.icon-box').classList.add(activeText);
    }

    function openRevisionStatusModal(id, title, currentStatus = null, currentReviewType = null) {
        document.getElementById('revisionStatusModalTitle').textContent = title;
        const form = document.getElementById('revisionStatusForm');
        form.action = `/admin/update-status/${id}`;

        // Reset UI
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('revisionAppointmentDate').value = today;
        document.getElementById('revisionRemarks').value = "";
        document.getElementById('revisionStatusActionInput').value = "";

        // Initialize to blank or loading state
        document.getElementById('deliberation_scientific').value = "Loading review data...";
        document.getElementById('deliberation_ethical').value = "Loading review data...";
        document.getElementById('deliberation_icf').value = "Loading review data...";
        document.getElementById('deliberation_summary').value = "Loading review data...";

        // Auto-fetch reviewer feedback from the server backend
        fetch(`/admin/reviewer-feedback/${id}`)
            .then(response => response.json())
            .then(data => {
                // Populate active fields using Current Round data
                document.getElementById('deliberation_scientific').value = data.current?.scientific_soundness || '';
                document.getElementById('deliberation_ethical').value = data.current?.ethical_issues || '';
                document.getElementById('deliberation_icf').value = data.current?.icf_issues || '';
                document.getElementById('deliberation_summary').value = data.current?.summary_of_issues || '';

                // Handle Historical Data Rendering
                const historyContainer = document.getElementById('historicalFeedbackContainer');
                const historyContent = document.getElementById('historicalFeedbackContent');
                const historyCount = document.getElementById('historicalRoundCount');

                historyContent.innerHTML = ''; // Reset

                if (data.history && data.history.length > 0) {
                    historyContainer.classList.remove('hidden');
                    historyCount.textContent = data.history.length + (data.history.length > 1 ? ' versions' : ' version');

                    data.history.forEach((round, index) => {
                        // Reverse chronological ID for display
                        const roundLabel = `Version ${data.history.length - index}`;

                        const roundHtml = `
                            <div class="border border-slate-200/80 rounded-xl overflow-hidden bg-white shadow-2xs mb-4 last:mb-0">
                                <div class="bg-slate-50/80 px-4 py-2.5 border-b border-slate-100 flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">${roundLabel}</span>
                                    <span class="text-xs text-slate-500 font-medium flex items-center gap-1.5"><i class="far fa-clock text-slate-400"></i> ${round.round_date}</span>
                                </div>
                                <div class="p-4 space-y-3.5">
                                    ${round.scientific_soundness ? `<div><h5 class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-1">Scientific Soundness</h5><p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed border-l-2 border-indigo-200 pl-3">${round.scientific_soundness}</p></div>` : ''}
                                    ${round.ethical_issues ? `<div><h5 class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1">Ethical Issues</h5><p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed border-l-2 border-amber-200 pl-3">${round.ethical_issues}</p></div>` : ''}
                                    ${round.icf_issues ? `<div><h5 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">ICF Issues</h5><p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed border-l-2 border-emerald-200 pl-3">${round.icf_issues}</p></div>` : ''}
                                    ${round.summary_of_issues ? `<div><h5 class="text-xs font-bold text-rose-600 uppercase tracking-wider mb-1">Summary</h5><p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed border-l-2 border-rose-200 pl-3">${round.summary_of_issues}</p></div>` : ''}
                                </div>
                            </div>
                        `;
                        historyContent.insertAdjacentHTML('beforeend', roundHtml);
                    });
                } else {
                    historyContainer.classList.add('hidden');
                }
            })
            .catch(err => {
                console.error('Failed to fetch reviewer feedback', err);
                document.getElementById('deliberation_scientific').value = '';
                document.getElementById('deliberation_ethical').value = '';
                document.getElementById('deliberation_icf').value = '';
                document.getElementById('deliberation_summary').value = '';
                document.getElementById('historicalFeedbackContainer').classList.add('hidden');
            });

        // Reset Box Selection Visuals
        document.querySelectorAll('.revision-status-option').forEach(el => {
            el.classList.remove('border-orange-400', 'bg-orange-50/60', 'border-red-400', 'bg-red-50/60', 'border-emerald-400', 'bg-emerald-50/60');
            el.classList.add('border-slate-200');
            el.querySelector('.check-icon').classList.add('opacity-0');
            const iconBox = el.querySelector('.icon-box');
            iconBox.classList.remove('text-orange-500', 'text-red-500', 'text-emerald-500');
            iconBox.classList.add('text-slate-400');
        });

        // Auto-select current status
        if (currentStatus) {
            const statusMap = {
                'Modifications Required': 0,
                'Disapproved': 1,
                'Approved': 2
            };

            const statusBoxes = document.querySelectorAll('.revision-status-option');

            if (currentStatus in statusMap && statusBoxes[statusMap[currentStatus]]) {
                selectRevisionStatus(currentStatus, statusBoxes[statusMap[currentStatus]]);
            }
        }

        // Show Modal
        const modal = document.getElementById('revisionStatusModal');
        const content = document.getElementById('revisionStatusModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeRevisionStatusModal() {
        const modal = document.getElementById('revisionStatusModal');
        const content = document.getElementById('revisionStatusModalContent');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // Handle Form Submission via AJAX
    document.getElementById('revisionStatusForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const statusAction = document.getElementById('revisionStatusActionInput').value;

        if (!statusAction) {
            alert('Please select an Action Taken.');
            return;
        }

        const btn = document.getElementById('submitRevisionStatusBtn');
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Updating...';

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                closeRevisionStatusModal();

                if (result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    window.location.reload();
                }
            } else {
                if (result.errors) {
                    let errorMsg = 'Validation Error:\n';
                    for (const [key, messages] of Object.entries(result.errors)) {
                        errorMsg += `- ${messages[0]} \n`;
                    }
                    alert(errorMsg);
                } else {
                    alert('Error: ' + (result.message || 'Unknown error'));
                }
            }
        } catch (error) {
            console.error(error);
            alert('An error occurred. Please check the console for details.');
        } finally {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    });
</script>