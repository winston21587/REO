    <!-- Revision Status Update Modal -->
    <div id="revisionStatusModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300 opacity-0" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeRevisionStatusModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div id="revisionStatusModalContent" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl scale-95 duration-300 max-h-[90vh] flex flex-col">
                
                <!-- Header -->
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-[#8B0000]">
                                <i class="fas fa-sync-alt text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold leading-6 text-slate-900 font-heading" id="modal-title">Update Revision Status</h3>
                                <p class="text-xs text-slate-500 mt-0.5" id="revisionStatusModalTitle">Protocol Title</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeRevisionStatusModal()" class="text-slate-400 hover:text-slate-500 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="px-4 py-6 sm:p-6 bg-slate-50/50 overflow-y-auto">
                    <form id="revisionStatusForm" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="status_action" id="revisionStatusActionInput">

                        <!-- Status Actions -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3">Status Actions</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <!-- Modifications Required -->
                                <div onclick="selectRevisionStatus('Modifications Required', this)" class="revision-status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-orange-400 hover:shadow-md transition-all group">
                                    <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                        <i class="fas fa-check-circle text-orange-500"></i>
                                    </div>
                                    <div class="icon-box w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-orange-500">
                                        <i class="fas fa-edit text-lg"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-800 text-sm">Modifications Required</h4>
                                    <p class="text-[10px] text-slate-500 mt-1">Minor or Major revisions</p>
                                </div>

                                <!-- Disapproved -->
                                <div onclick="selectRevisionStatus('Disapproved', this)" class="revision-status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-red-400 hover:shadow-md transition-all group">
                                    <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                        <i class="fas fa-check-circle text-red-500"></i>
                                    </div>
                                    <div class="icon-box w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-red-500">
                                        <i class="fas fa-times-circle text-lg"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-800 text-sm">Disapproved</h4>
                                    <p class="text-[10px] text-slate-500 mt-1">Serious ethical violations</p>
                                </div>

                                <!-- Panel Deliberation -->
                                <div onclick="selectRevisionStatus('Panel Deliberation', this)" class="revision-status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-blue-400 hover:shadow-md transition-all group">
                                    <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                        <i class="fas fa-check-circle text-blue-500"></i>
                                    </div>
                                    <div class="icon-box w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-blue-500">
                                        <i class="fas fa-gavel text-lg"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-800 text-sm">Panel Deliberation</h4>
                                    <p class="text-[10px] text-slate-500 mt-1">Schedule meeting</p>
                                </div>

                                <!-- Approve -->
                                <div onclick="selectRevisionStatus('Approved', this)" class="revision-status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-green-400 hover:shadow-md transition-all group">
                                    <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    </div>
                                    <div class="icon-box w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-green-500">
                                        <i class="fas fa-award text-lg"></i>
                                    </div>
                                    <h4 class="font-bold text-slate-800 text-sm">Approve</h4>
                                    <p class="text-[10px] text-slate-500 mt-1">Issue Clearance</p>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Date -->
                        <div>
                            <label for="revisionAppointmentDate" class="block text-sm font-bold text-slate-700 mb-2">Set Appointment / Deadline</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="far fa-calendar-alt text-slate-400"></i>
                                </div>
                                <input type="date" id="revisionAppointmentDate" name="appointment_date" min="{{ date('Y-m-d') }}" class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm transition-all">
                            </div>
                        </div>

                        <!-- Message Box -->
                        <div>
                            <label for="revisionRemarks" class="block text-sm font-bold text-slate-700 mb-2">Notification Message <span class="text-slate-400 font-normal text-xs">(Optional)</span></label>
                            <textarea id="revisionRemarks" name="remarks" rows="3" class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm transition-all resize-none" placeholder="Add any specific instructions or remarks for the researcher..."></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-4 border-t border-slate-100">
                            <button type="button" onclick="closeRevisionStatusModal()" class="flex-1 px-4 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" id="submitRevisionStatusBtn" class="flex-1 px-4 py-3 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#6d0000] transition-colors shadow-lg shadow-red-900/20 flex justify-center items-center gap-2">
                                <span>Update & Notify</span> <i class="fas fa-paper-plane"></i>
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

            // 2. Visual Selection
            // Remove active class from all options
            document.querySelectorAll('.revision-status-option').forEach(el => {
                el.classList.remove('border-orange-400', 'bg-orange-50', 'border-blue-400', 'bg-blue-50', 'border-green-400', 'bg-green-50');
                el.classList.add('border-slate-200');
                el.querySelector('.check-icon').classList.add('opacity-0');
                // Reset icon colors
                const iconBox = el.querySelector('.icon-box');
                iconBox.classList.remove('text-orange-500', 'text-blue-500', 'text-green-500');
                iconBox.classList.add('text-slate-400');
            });

            // Add active class to clicked option
            element.classList.remove('border-slate-200');
            
            let activeClass = '';
            let activeText = '';
            
            if (status === 'Modifications Required') {
                activeClass = 'border-orange-400 bg-orange-50';
                activeText = 'text-orange-500';
            } else if (status === 'Disapproved') {
                activeClass = 'border-red-400 bg-red-50';
                activeText = 'text-red-500';
            } else if (status === 'Panel Deliberation') {
                activeClass = 'border-blue-400 bg-blue-50';
                activeText = 'text-blue-500';
            } else if (status === 'Approved') {
                activeClass = 'border-green-400 bg-green-50';
                activeText = 'text-green-500';
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
            
            // Hide Panel Deliberation if not Full Board Review
            const panelDeliberationOption = document.querySelector('.revision-status-option[onclick*="Panel Deliberation"]');
            if (panelDeliberationOption) {
                if (currentReviewType !== 'Full Board Review') {
                    panelDeliberationOption.style.display = 'none';
                } else {
                    panelDeliberationOption.style.display = 'block';
                }
            }
            
            // Reset Box Selection Visuals
            document.querySelectorAll('.revision-status-option').forEach(el => {
                el.classList.remove('border-orange-400', 'bg-orange-50', 'border-red-400', 'bg-red-50', 'border-blue-400', 'bg-blue-50', 'border-green-400', 'bg-green-50');
                el.classList.add('border-slate-200');
                el.querySelector('.check-icon').classList.add('opacity-0');
                el.querySelector('.icon-box').classList.remove('text-orange-500', 'text-red-500', 'text-blue-500', 'text-green-500');
                el.querySelector('.icon-box').classList.add('text-slate-400');
            });

            // Auto-select current status
            if (currentStatus) {
                const statusMap = {
                    'Modifications Required': 0,
                    'Disapproved': 1,
                    'Panel Deliberation': 2,
                    'Approved': 3
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
        document.getElementById('revisionStatusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Client-side Validation
            const statusAction = document.getElementById('revisionStatusActionInput').value;
            const appointmentDate = document.getElementById('revisionAppointmentDate').value;
            
            if (!statusAction) {
                alert('Please select a Status Action.');
                return;
            }

            if (statusAction === 'Panel Deliberation' && !appointmentDate) {
                alert('Please set an Appointment Date.');
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
                    window.location.reload(); 
                } else {
                    if (result.errors) {
                        let errorMsg = 'Validation Error:\n';
                        for (const [key, messages] of Object.entries(result.errors)) {
                            errorMsg += `- ${messages[0]}\n`;
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
