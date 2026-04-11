<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300 opacity-0"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeStatusModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div id="statusModalContent"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl scale-95 duration-300 max-h-[90vh] flex flex-col">

            <!-- Header -->
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-[#8B0000]">
                            <i class="fas fa-sync-alt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold leading-6 text-slate-900 font-heading" id="modal-title">Update
                                Status</h3>
                            <p class="text-xs text-slate-500 mt-0.5" id="statusModalTitle">Protocol Title</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeStatusModal()"
                        class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-4 py-6 sm:p-6 bg-slate-50/50 overflow-y-auto">
                <form id="statusForm" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="status_action" id="statusActionInput">



                    <!-- 1. Review Classification -->
                    <div class="{{ request()->routeIs('admin.revisions') ? 'hidden' : '' }}">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Review Classification</label>
                        <div class="flex items-center justify-between p-4 bg-white border border-slate-200 rounded-xl shadow-sm">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Current Review Type</span>
                                <span class="font-bold text-slate-800 text-sm" id="currentReviewTypeDisplay">Unassigned</span>
                            </div>
                            <div class="flex-1 w-full max-w-[240px]">
                                <select name="review_type" id="reviewTypeSelect" class="hidden">
                                    <option value="">-- Change Review Type --</option>
                                    <option value="Unassigned">Unassigned (N/A)</option>
                                    <option value="Exempt Review">Exempt Review</option>
                                    <option value="Expedited Review">Expedited Review</option>
                                    <option value="Full Board Review">Full Board Review</option>
                                </select>
                                
                                <div x-data="{ 
                                        open: false,
                                        value: '',
                                        lockedType: 'Unassigned',
                                        get displayValue() {
                                            const map = {
                                                'Unassigned': 'Unassigned (N/A)',
                                                'Exempt Review': 'Exempt Review',
                                                'Expedited Review': 'Expedited Review',
                                                'Full Board Review': 'Full Board Review'
                                            };
                                            return map[this.value] || '-- Change Review Type --';
                                        },
                                        selectOption(val) {
                                            if (val === this.lockedType) return;
                                            this.value = val;
                                            const sel = document.getElementById('reviewTypeSelect');
                                            if(sel) sel.value = val;
                                            this.open = false;
                                        }
                                    }" 
                                    @update-review-options.window="lockedType = $event.detail.locked; value = ''; if(document.getElementById('reviewTypeSelect')) document.getElementById('reviewTypeSelect').value = '';"
                                    @click.outside="open = false" 
                                    class="relative w-full">

                                   <button type="button" @click.prevent="open = !open" 
                                       class="flex items-center justify-between w-full px-4 py-2 text-sm bg-slate-50 border border-slate-200 hover:border-slate-300 hover:bg-slate-100 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] transition-all">
                                       <span x-text="displayValue" class="font-bold text-slate-700 truncate pr-2" :class="{ 'text-slate-400 font-medium': !value }"></span>
                                       <i class="fas fa-chevron-down text-slate-400 text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                   </button>

                                   <div x-show="open" x-transition.opacity.duration.150ms x-transition:enter-start="transform scale-95" x-transition:enter-end="transform scale-100" style="display: none;" 
                                       class="absolute z-50 w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden py-1">
                                       
                                       <!-- Option: Unassigned -->
                                       <button type="button" @click.prevent="selectOption('Unassigned')"
                                           :class="{ 'opacity-40 cursor-not-allowed bg-slate-50 relative overflow-hidden': lockedType === 'Unassigned', 'hover:bg-slate-50 hover:pl-5': lockedType !== 'Unassigned' && value !== 'Unassigned', 'bg-red-50 text-[#8B0000] border-l-2 border-[#8B0000]': value === 'Unassigned' }"
                                           class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-slate-600 transition-all">
                                           <span class="text-left w-full" x-text="lockedType === 'Unassigned' ? 'Unassigned (N/A) (Current)' : 'Unassigned (N/A)'"></span>
                                           <i x-show="lockedType === 'Unassigned'" class="fas fa-ban text-slate-300 text-[10px] ml-2 flex-shrink-0"></i>
                                           <div x-show="lockedType === 'Unassigned'" class="absolute inset-0 bg-repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(0,0,0,0.02) 10px, rgba(0,0,0,0.02) 20px)"></div>
                                       </button>

                                       <!-- Option: Exempt Review -->
                                       <button type="button" @click.prevent="selectOption('Exempt Review')"
                                           :class="{ 'opacity-40 cursor-not-allowed bg-slate-50 relative overflow-hidden': lockedType === 'Exempt Review', 'hover:bg-slate-50 hover:pl-5': lockedType !== 'Exempt Review' && value !== 'Exempt Review', 'bg-red-50 text-[#8B0000] border-l-2 border-[#8B0000]': value === 'Exempt Review' }"
                                           class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-slate-600 transition-all">
                                           <span class="text-left w-full" x-text="lockedType === 'Exempt Review' ? 'Exempt Review (Current)' : 'Exempt Review'"></span>
                                           <i x-show="lockedType === 'Exempt Review'" class="fas fa-ban text-slate-300 text-[10px] ml-2 flex-shrink-0"></i>
                                           <div x-show="lockedType === 'Exempt Review'" class="absolute inset-0 bg-repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(0,0,0,0.02) 10px, rgba(0,0,0,0.02) 20px)"></div>
                                       </button>

                                       <!-- Option: Expedited Review -->
                                       <button type="button" @click.prevent="selectOption('Expedited Review')"
                                           :class="{ 'opacity-40 cursor-not-allowed bg-slate-50 relative overflow-hidden': lockedType === 'Expedited Review', 'hover:bg-slate-50 hover:pl-5': lockedType !== 'Expedited Review' && value !== 'Expedited Review', 'bg-red-50 text-[#8B0000] border-l-2 border-[#8B0000]': value === 'Expedited Review' }"
                                           class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-slate-600 transition-all">
                                           <span class="text-left w-full" x-text="lockedType === 'Expedited Review' ? 'Expedited Review (Current)' : 'Expedited Review'"></span>
                                           <i x-show="lockedType === 'Expedited Review'" class="fas fa-ban text-slate-300 text-[10px] ml-2 flex-shrink-0"></i>
                                           <div x-show="lockedType === 'Expedited Review'" class="absolute inset-0 bg-repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(0,0,0,0.02) 10px, rgba(0,0,0,0.02) 20px)"></div>
                                       </button>

                                       <!-- Option: Full Board Review -->
                                       <button type="button" @click.prevent="selectOption('Full Board Review')"
                                           :class="{ 'opacity-40 cursor-not-allowed bg-slate-50 relative overflow-hidden': lockedType === 'Full Board Review', 'hover:bg-slate-50 hover:pl-5': lockedType !== 'Full Board Review' && value !== 'Full Board Review', 'bg-red-50 text-[#8B0000] border-l-2 border-[#8B0000]': value === 'Full Board Review' }"
                                           class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-slate-600 transition-all">
                                           <span class="text-left w-full" x-text="lockedType === 'Full Board Review' ? 'Full Board Review (Current)' : 'Full Board Review'"></span>
                                           <i x-show="lockedType === 'Full Board Review'" class="fas fa-ban text-slate-300 text-[10px] ml-2 flex-shrink-0"></i>
                                           <div x-show="lockedType === 'Full Board Review'" class="absolute inset-0 bg-repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(0,0,0,0.02) 10px, rgba(0,0,0,0.02) 20px)"></div>
                                       </button>

                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- 2. Status Actions -->
                    <div class="{{ request()->routeIs('admin.applications') ? 'hidden' : '' }}">
                        <label class="block text-sm font-bold text-slate-700 mb-3">Status Actions</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <!-- Needs Revision -->
                            <div onclick="selectStatus('Modifications Required', this)"
                                class="status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-orange-400 hover:shadow-md transition-all group">
                                <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                    <i class="fas fa-check-circle text-orange-500"></i>
                                </div>
                                <div
                                    class="icon-box w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-orange-500">
                                    <i class="fas fa-edit text-lg"></i>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm">Needs Revision</h4>
                                <p class="text-[10px] text-slate-500 mt-1">Request changes</p>
                            </div>

                            <!-- Panel Deliberation -->
                            <div onclick="selectStatus('Panel Deliberation', this)"
                                class="status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-blue-400 hover:shadow-md transition-all group">
                                <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                    <i class="fas fa-check-circle text-blue-500"></i>
                                </div>
                                <div
                                    class="icon-box w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-blue-500">
                                    <i class="fas fa-gavel text-lg"></i>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm">Panel Deliberation</h4>
                                <p class="text-[10px] text-slate-500 mt-1">Schedule meeting</p>
                            </div>

                            <!-- Approve -->
                            <div onclick="selectStatus('Approved', this)"
                                class="status-option cursor-pointer relative bg-white border border-slate-200 rounded-xl p-4 hover:border-green-400 hover:shadow-md transition-all group">
                                <div class="absolute top-3 right-3 opacity-0 transition-opacity check-icon">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div
                                    class="icon-box w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 mb-3 transition-colors group-hover:text-green-500">
                                    <i class="fas fa-award text-lg"></i>
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm">Approve</h4>
                                <p class="text-[10px] text-slate-500 mt-1">Issue Clearance</p>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Appointment Date -->
                    <div>
                        <label for="appointmentDate" class="block text-sm font-bold text-slate-700 mb-2">Set Appointment
                            / Deadline</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="far fa-calendar-alt text-slate-400"></i>
                            </div>
                            <input type="date" id="appointmentDate" name="appointment_date"
                                min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm transition-all">
                        </div>
                    </div>

                    <!-- 4. Message Box -->
                    <div>
                        <label for="remarks" class="block text-sm font-bold text-slate-700 mb-2">Notification Message
                            <span class="text-slate-400 font-normal text-xs">(Optional)</span></label>
                        <textarea id="remarks" name="remarks" rows="3"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm transition-all resize-none"
                            placeholder="Add any specific instructions or remarks for the researcher..."></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeStatusModal()"
                            class="flex-1 px-4 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="submitStatusBtn"
                            class="flex-1 px-4 py-3 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#6d0000] transition-colors shadow-lg shadow-red-900/20 flex justify-center items-center gap-2">
                            <span>{{ request()->routeIs('admin.applications') ? 'Update Status' : 'Update & Notify' }}</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const isApplicationsPage = {{ request()->routeIs('admin.applications') ? 'true' : 'false' }};

    function selectStatus(status, element) {
        // 1. Update Hidden Input
        document.getElementById('statusActionInput').value = status;
        document.getElementById('reviewTypeSelect').value = ''; // Clear review type dropdown selection
        // Remove active class from all options (Status Actions)
        document.querySelectorAll('.status-option').forEach(el => {
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

    async function openStatusModal(id, title, currentStatus = null, currentReviewType = null) {
        document.getElementById('statusModalTitle').textContent = title;
        const form = document.getElementById('statusForm');
        form.action = `/admin/update-status/${id}`;

        // Reset UI
        const reviewTypeSelect = document.getElementById('reviewTypeSelect');
        reviewTypeSelect.value = "";
        document.getElementById('currentReviewTypeDisplay').textContent = currentReviewType || 'Unassigned';
        
        // Dynamically disable currently selected review type
        Array.from(reviewTypeSelect.options).forEach(opt => {
            if (opt.value) {
                let baseText = opt.value === 'Unassigned' ? 'Unassigned (N/A)' : opt.value;
                if (opt.value === currentReviewType || (!currentReviewType && opt.value === 'Unassigned')) {
                    opt.disabled = true;
                    opt.textContent = baseText + ' (Current)';
                } else {
                    opt.disabled = false;
                    opt.textContent = baseText;
                }
            }
        });
        
        // Broadcast custom event to sync with the Alpine UI dropdown
        window.dispatchEvent(new CustomEvent('update-review-options', { detail: { locked: currentReviewType || 'Unassigned' } }));

        const date = new Date();
        date.setDate(date.getDate() + 2);
        const minDate = date.toISOString().split('T')[0];
        const apptInput = document.getElementById('appointmentDate');
        apptInput.value = minDate;
        apptInput.min = minDate;
        document.getElementById('remarks').value = ""; // Reset message box



        // Auto-select current status
        if (currentStatus) {
            const statusMap = {
                'Modifications Required': 0,
                'Panel Deliberation': 1,
                'Approved': 2
            };

            const statusBoxes = document.querySelectorAll('.status-option');

            if (currentStatus in statusMap && statusBoxes[statusMap[currentStatus]]) {
                selectStatus(currentStatus, statusBoxes[statusMap[currentStatus]]);
            }
        }

        // Show Modal
        const modal = document.getElementById('statusModal');
        const content = document.getElementById('statusModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }



    function closeStatusModal() {
        const modal = document.getElementById('statusModal');
        const content = document.getElementById('statusModalContent');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // Handle Form Submission via AJAX for better UX
    document.getElementById('statusForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        // Client-side Validation
        const reviewType = document.getElementById('reviewTypeSelect').value;
        const statusAction = document.getElementById('statusActionInput').value;
        const appointmentDate = document.getElementById('appointmentDate').value;

        // Common SweetAlert2 Config
        const commonSwalConfig = {
            scrollbarPadding: false,
            backdrop: `rgba(15, 23, 42, 0.75)`,
            buttonsStyling: false,
            showClass: {
                popup: 'animate-[fadeInUp_0.3s_ease-out]'
            },
            hideClass: {
                popup: 'animate-[fadeOutDown_0.3s_ease-in]'
            },
            customClass: {
                popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                title: 'font-heading text-xl text-slate-800 font-bold pt-4',
                htmlContainer: 'text-slate-600 text-sm mt-2',
                confirmButton: 'bg-[#8B0000] text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-900 hover:shadow-xl hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2',
                cancelButton: 'bg-slate-100 text-slate-600 px-8 py-3 rounded-xl font-bold hover:bg-slate-200 transition-all outline-none focus:ring-0 mx-2'
            }
        };

        // If on Applications page, Review Type is required
        if (isApplicationsPage && !reviewType) {
            Swal.fire({
                ...commonSwalConfig,
                title: 'Classification Required',
                text: 'Please select a Review Classification (Expedited, Exempt, or Full Board) to proceed.',
                icon: 'warning',
                confirmButtonText: 'Continue', // Changed to Continue
                confirmButtonColor: '#8B0000',
            });
            return;
        }

        // Appointment Date is required if Review Type is selected or for specific statuses
        if ((reviewType || statusAction === 'Panel Deliberation') && !appointmentDate) {
            Swal.fire({
                ...commonSwalConfig,
                title: 'Date Required',
                text: 'Please set an Appointment Date or Deadline for this action.',
                icon: 'warning',
                confirmButtonText: 'Okay',
                confirmButtonColor: '#8B0000',
            });
            return;
        }

        const btn = document.getElementById('submitStatusBtn');
        const originalText = btn.innerHTML;
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
                closeStatusModal();

                await Swal.fire({
                    ...commonSwalConfig,
                    title: 'Status Updated!',
                    text: 'The protocol status has been successfully updated.',
                    icon: 'success',
                    confirmButtonText: 'Great!',
                    confirmButtonColor: '#8B0000',
                });

                window.location.reload();
            } else {
                // Handle Validation Errors
                let errorMsg = result.message || 'Unknown error';

                if (result.errors) {
                    errorMsg = 'Please verify the following:\n';
                    for (const [key, messages] of Object.entries(result.errors)) {
                        errorMsg += `\n• ${messages[0]}`;
                    }
                }

                Swal.fire({
                    ...commonSwalConfig,
                    title: 'Update Failed',
                    text: errorMsg,
                    icon: 'error',
                    confirmButtonText: 'Okay',
                    confirmButtonColor: '#334155',
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                ...commonSwalConfig,
                title: 'System Error',
                text: 'An unexpected error occurred. Please check your connection or console.',
                icon: 'error',
                confirmButtonText: 'Close',
                confirmButtonColor: '#334155',
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
</script>