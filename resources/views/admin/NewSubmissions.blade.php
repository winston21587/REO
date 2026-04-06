<x-admin_layout>
    <div class="max-w-7xl mx-auto animate-[fadeInUp_0.5s_ease-out]">

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

            <!-- Recent Submissions Column -->
            <div class="space-y-6 flex flex-col h-full">
                <h2 class="text-2xl font-extrabold text-slate-800 font-heading">Recent Submissions</h2>

                <!-- Controls -->
                <div class="flex gap-4" x-data="{ expanded: false }">
                    <div class="relative flex-1">
                        <input type="text" name="recent_search" id="recent_search_input" value="{{ request('recent_search') }}"
                            placeholder="Search submissions..."
                            class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm bg-white">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                    <div class="relative">
                        <button type="button" @click="expanded = !expanded" @click.outside="expanded = false"
                            class="flex items-center gap-2 px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm transition-colors w-[150px] justify-between">
                            <span><i class="fas fa-filter mr-1 text-slate-400"></i> Filter</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Advanced Dropdown -->
                        <div x-show="expanded" x-cloak x-transition.opacity.duration.200ms @click.stop
                            class="absolute right-0 mt-2 w-64 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                            
                            <!-- Sort Section -->
                            <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Sort By</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="recent_sort" value="created_at" class="recent-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('recent_sort', 'created_at') == 'created_at' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Submission Date</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="recent_sort" value="Title" class="recent-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('recent_sort') == 'Title' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Title</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Filter Section Removed (Not applicable for Initial Intake) -->
                        </div>
                    </div>
                </div>

                <!-- Submissions List -->
                <!-- Submissions List -->
                <div id="recent-submissions-wrapper" class="flex-1 flex flex-col min-h-[400px]">
                    @include('admin.partials.recent_submissions_list')
                </div>
            </div>

            <!-- Incomplete Submissions Column -->
            <div class="space-y-6 flex flex-col h-full">
                <h2 class="text-2xl font-extrabold text-slate-800 font-heading">Incomplete Submissions</h2>

                <div class="flex gap-4" x-data="{ expanded: false }">
                    <div class="relative flex-1">
                        <input type="text" name="incomplete_search" id="incomplete_search_input" value="{{ request('incomplete_search') }}"
                            placeholder="Search submissions..."
                            class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm bg-white">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                    <div class="relative">
                        <button type="button" @click="expanded = !expanded" @click.outside="expanded = false"
                            class="flex items-center gap-2 px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm transition-colors w-[150px] justify-between">
                            <span><i class="fas fa-filter mr-1 text-slate-400"></i> Filter</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Advanced Dropdown -->
                        <div x-show="expanded" x-cloak x-transition.opacity.duration.200ms @click.stop
                            class="absolute right-0 mt-2 w-64 bg-white border border-slate-200 rounded-xl shadow-xl z-[60] overflow-hidden">
                            
                            <!-- Sort Section -->
                            <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Sort By</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="incomplete_sort" value="created_at" class="incomplete-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('incomplete_sort', 'created_at') == 'created_at' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Submission Date</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="incomplete_sort" value="Title" class="incomplete-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('incomplete_sort') == 'Title' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-[#8B0000] transition-colors">Title</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Filter Section Removed (Not applicable for Initial Intake) -->
                        </div>
                    </div>
                </div>

                <div id="incomplete-submissions-wrapper" class="flex-1 flex flex-col min-h-[400px]">
                    @include('admin.partials.incomplete_submissions_list')
                </div>
            </div>

        </div>
    </div>

    <!-- Triage Modal -->
    <div id="triageModal"
        class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col"
            id="modalContent">
            <div class="bg-[#1a0505] p-6 border-b border-white/10 relative overflow-hidden flex-shrink-0">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fas fa-gavel text-6xl text-white"></i>
                </div>
                <h3 class="text-white font-bold text-xl relative z-10" id="modal-title">Protocol Classification</h3>
                <p class="text-slate-400 text-xs mt-1 relative z-10">Determine the level of review required (SOP
                    04/05/06).</p>
            </div>

            <form id="triageForm" method="POST" action="" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 space-y-6 overflow-y-auto flex-1">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Protocol
                            Title</label>
                        <p id="modalTitle"
                            class="text-slate-800 font-bold bg-slate-50 p-4 rounded-xl border border-slate-200 text-sm leading-relaxed">
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Document
                            Completeness Check</label>

                        <div class="space-y-3">
                            <label id="label_complete"
                                class="group flex items-start gap-4 p-4 border border-slate-200 rounded-xl cursor-pointer transition-all relative overflow-hidden"
                                onclick="selectOption('Complete')">
                                <div id="bar_complete"
                                    class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 opacity-0 transition-opacity">
                                </div>
                                <input type="radio" name="classification" value="Complete"
                                    class="mt-1 text-green-600 focus:ring-green-500" checked
                                    onchange="toggleAppointment(true)">
                                <div>
                                    <span id="text_complete"
                                        class="block font-bold text-slate-800 text-sm transition-colors">Complete
                                        Submission</span>
                                    <span class="block text-xs text-slate-500 mt-1">All required documents are
                                        present.</span>
                                </div>
                            </label>

                            <label id="label_incomplete"
                                class="group flex items-start gap-4 p-4 border border-slate-200 rounded-xl cursor-pointer transition-all relative overflow-hidden"
                                onclick="selectOption('Incomplete')">
                                <div id="bar_incomplete"
                                    class="absolute left-0 top-0 bottom-0 w-1 bg-red-500 opacity-0 transition-opacity">
                                </div>
                                <input type="radio" name="classification" value="Incomplete"
                                    class="mt-1 text-red-600 focus:ring-red-500" onchange="toggleAppointment(false)">
                                <div>
                                    <span id="text_incomplete"
                                        class="block font-bold text-slate-800 text-sm transition-colors">Incomplete
                                        Submission</span>
                                    <span class="block text-xs text-slate-500 mt-1">Return to researcher for
                                        revision.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="appointmentField"
                        class="transition-all duration-300 bg-white p-4 rounded-2xl border border-slate-200">
                        <label
                            class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Hardcopy
                            Deadline / Appointment</label>
                        <input type="date" name="appointment_date" min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                            class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm bg-slate-50 font-medium text-slate-700">
                    </div>

                    <div id="incompleteFields" class="hidden opacity-0 transition-all duration-300 space-y-4">

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">General
                                Remarks</label>
                            <textarea name="remarks" rows="2"
                                class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm placeholder-slate-400"
                                placeholder="e.g., Please review the comments in the attached file..."></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Missing
                                Requirements / Specific Issues</label>

                            <div class="flex gap-2 mb-3">
                                <input type="text" id="reqInput" list="requirementsOptions"
                                    class="flex-1 p-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                                    placeholder="Select or type missing document...">
                                <datalist id="requirementsOptions">
                                    @foreach($requirements as $req)
                                        <option value="{{ $req->name }}">
                                    @endforeach
                                </datalist>

                                <button type="button" onclick="addRequirement()"
                                    class="bg-slate-800 text-white px-4 rounded-lg hover:bg-slate-700 transition-colors shadow-sm active:transform active:scale-95">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <div id="requirementsList" class="space-y-2 max-h-40 overflow-y-auto pr-1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3 flex-shrink-0">
                    <button type="button" onclick="closeTriage()"
                        class="px-5 py-2.5 text-slate-600 font-bold text-sm hover:bg-white hover:text-slate-800 rounded-lg transition-all border border-transparent hover:border-slate-200">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-[#8B0000] text-white font-bold text-sm rounded-lg shadow-lg hover:bg-[#6d0000] hover:shadow-xl transition-all transform hover:-translate-y-0.5">Confirm
                        & Send</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // --- Requirement List Logic ---
        function addRequirement() {
            const input = document.getElementById('reqInput');
            const container = document.getElementById('requirementsList');
            const value = input.value.trim();

            if (!value) return;

            // Prevent duplicate entries
            const existingValues = [...container.querySelectorAll('input[name="missing_requirements[]"]')]
                .map(el => el.value.toLowerCase());
            if (existingValues.includes(value.toLowerCase())) {
                // Find and flash the existing item
                [...container.querySelectorAll('div[id^="req-"]')].forEach(el => {
                    const span = el.querySelector('span');
                    if (span && span.title.toLowerCase() === value.toLowerCase()) {
                        el.classList.add('ring-2', 'ring-red-400', 'bg-red-50');
                        setTimeout(() => el.classList.remove('ring-2', 'ring-red-400', 'bg-red-50'), 1200);
                    }
                });
                // Show inline hint below the datalist input
                let hint = document.getElementById('reqDuplicateHint');
                if (!hint) {
                    hint = document.createElement('p');
                    hint.id = 'reqDuplicateHint';
                    hint.className = 'text-xs text-red-500 font-bold mt-1';
                    input.closest('.flex').insertAdjacentElement('afterend', hint);
                }
                hint.textContent = '"' + value + '" is already in the list.';
                setTimeout(() => { if (hint) hint.textContent = ''; }, 2000);
                input.value = '';
                input.focus();
                return;
            }

            // Clear any previous hint
            const hint = document.getElementById('reqDuplicateHint');
            if (hint) hint.textContent = '';

            // Generate unique ID
            const id = Date.now();

            const itemHTML = `
                <div id="req-${id}" class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-200 shadow-sm animate-[fadeIn_0.3s_ease-out] group transition-all duration-300">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></div>
                        <span class="text-sm font-medium text-slate-700 truncate" title="${value}">${value}</span>
                        <input type="hidden" name="missing_requirements[]" value="${value}">
                    </div>
                    <button type="button" onclick="removeRequirement('${id}')" class="text-slate-400 hover:text-red-500 transition-colors p-1 opacity-50 group-hover:opacity-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', itemHTML);
            input.value = '';
            input.focus();
        }

        function removeRequirement(id) {
            const el = document.getElementById(`req-${id}`);
            if (el) {
                el.style.opacity = '0';
                el.style.transform = 'translateX(10px)';
                setTimeout(() => el.remove(), 200);
            }
        }

        // Allow "Enter" key to add item
        document.getElementById('reqInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addRequirement();
            }
        });

        // Also clear the requirement list when the modal resets (when opening triage modal)
        const _origOpenTriage = window.openTriageModal;
        window._triageModalClean = function() {
            document.getElementById('requirementsList').innerHTML = '';
            const hint = document.getElementById('reqDuplicateHint');
            if (hint) hint.textContent = '';
            const reqInput = document.getElementById('reqInput');
            if (reqInput) reqInput.value = '';
        };

        // --- Toggle & Modal Logic ---

        function toggleAppointment(isComplete) {
            const appointmentField = document.getElementById('appointmentField');
            const appointmentInput = appointmentField.querySelector('input');

            const incompleteFields = document.getElementById('incompleteFields');
            const remarksInput = incompleteFields.querySelector('textarea');

            if (isComplete) {
                // Show Appointment, Hide Incomplete
                appointmentField.classList.remove('hidden');
                setTimeout(() => appointmentField.classList.remove('opacity-0'), 10);
                appointmentInput.required = true;

                incompleteFields.classList.add('opacity-0');
                setTimeout(() => incompleteFields.classList.add('hidden'), 300);
                remarksInput.required = false;

                // Clear inputs
                remarksInput.value = '';
                document.getElementById('requirementsList').innerHTML = ''; // Clear list

            } else {
                // Hide Appointment, Show Incomplete
                appointmentField.classList.add('opacity-0');
                setTimeout(() => appointmentField.classList.add('hidden'), 300);
                appointmentInput.required = false;
                appointmentInput.value = '';

                incompleteFields.classList.remove('hidden');
                setTimeout(() => incompleteFields.classList.remove('opacity-0'), 10);
                remarksInput.required = true;
            }
        }

        function selectOption(type) {
            // Reset Visuals
            ['complete', 'incomplete'].forEach(t => {
                const label = document.getElementById(`label_${t}`);
                const bar = document.getElementById(`bar_${t}`);
                const text = document.getElementById(`text_${t}`);

                // Color mapping: complete=green, incomplete=red
                const color = t === 'complete' ? 'green' : 'red';

                label.classList.remove(`bg-${color}-50`, `border-${color}-200`);
                bar.classList.remove('opacity-100');
                bar.classList.add('opacity-0');
                text.classList.remove(`text-${color}-700`);
            });

            // Apply Active State
            const activeColor = type === 'Complete' ? 'green' : 'red';
            const activeLabel = document.getElementById(`label_${type.toLowerCase()}`);
            const activeBar = document.getElementById(`bar_${type.toLowerCase()}`);
            const activeText = document.getElementById(`text_${type.toLowerCase()}`);

            activeLabel.classList.add(`bg-${activeColor}-50`, `border-${activeColor}-200`);
            activeBar.classList.remove('opacity-0');
            activeBar.classList.add('opacity-100');
            activeText.classList.add(`text-${activeColor}-700`);
        }

        function openTriageModal(id, title) {
            const modal = document.getElementById('triageModal');
            const content = document.getElementById('modalContent');
            const titleEl = document.getElementById('modalTitle');
            const form = document.getElementById('triageForm');

            titleEl.textContent = title;
            form.action = `/admin/update-status/${id}`;

            // Set Date Defaults (2 days from now)
            const dateInput = document.querySelector('input[name="appointment_date"]');
            const targetDate = new Date();
            targetDate.setDate(targetDate.getDate() + 2);
            const minDate = targetDate.toISOString().split('T')[0];

            dateInput.value = minDate;
            dateInput.min = minDate;

            // Clear requirements list and hints on every open
            if (window._triageModalClean) window._triageModalClean();

            // Default to Complete
            document.querySelector('input[value="Complete"]').checked = true;
            toggleAppointment(true);
            selectOption('Complete');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeTriage() {
            const modal = document.getElementById('triageModal');
            const content = document.getElementById('modalContent');

            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // AJAX Submission with SweetAlert2
        document.getElementById('triageForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Visual Feedback
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Processing...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                const result = await response.json();

                if (response.ok) {
                    closeTriage();

                    // Success Modal
                    await Swal.fire({
                        title: 'Success!',
                        text: 'Protocol status has been updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Great!',
                        confirmButtonColor: '#8B0000',
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
                            title: 'font-heading text-2xl text-slate-800 font-bold pt-4',
                            htmlContainer: 'text-slate-600 text-sm mt-2',
                            confirmButton: 'bg-[#8B0000] text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-900 hover:shadow-xl hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2'
                        }
                    });

                    window.location.reload();
                } else {
                    // Error Modal
                    Swal.fire({
                        title: 'Action Failed',
                        text: result.message || 'Unable to update status. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Okay',
                        confirmButtonColor: '#334155',
                        scrollbarPadding: false,
                        backdrop: `rgba(15, 23, 42, 0.75)`,
                        buttonsStyling: false,
                        showClass: {
                            popup: 'animate-[shake_0.5s_ease-in-out]'
                        },
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                            title: 'font-heading text-2xl text-slate-800 font-bold pt-4',
                            htmlContainer: 'text-slate-600 text-sm mt-2',
                            confirmButton: 'bg-slate-800 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-slate-700 hover:shadow-xl hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2'
                        }
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    title: 'System Error',
                    text: 'Something went wrong. Please check your connection.',
                    icon: 'warning',
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#334155',
                    scrollbarPadding: false,
                    backdrop: `rgba(15, 23, 42, 0.75)`,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                        title: 'font-heading text-2xl text-slate-800 font-bold pt-4',
                        htmlContainer: 'text-slate-600 text-sm mt-2',
                        confirmButton: 'bg-slate-800 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-slate-700 hover:shadow-xl hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2'
                    }
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = [
                'recent_search_input', 'recent_sort_input',
                'incomplete_search_input', 'incomplete_sort_input'
            ];
            
            let debounceTimer;

            // Helper to fetch and update
            const fetchSubmissions = (params) => {
                const url = `{{ request()->url() }}?${params.toString()}`;
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update Containers
                    if (data.recent) {
                      const recentContainer = document.getElementById('recent-submissions-wrapper');
                      if (recentContainer) recentContainer.innerHTML = data.recent;
                    }
                    if (data.incomplete) {
                      const incompleteContainer = document.getElementById('incomplete-submissions-wrapper');
                      if (incompleteContainer) incompleteContainer.innerHTML = data.incomplete;
                    }

                    // Update URL
                    window.history.pushState({}, '', url);
                })
                .catch(error => console.error('Error:', error));
            };

            // Event Listeners for Search Inputs
            ['recent_search_input', 'incomplete_search_input'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;

                el.addEventListener('input', (e) => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        const params = new URLSearchParams(window.location.search);
                        
                        if (id === 'recent_search_input') {
                            params.set('recent_search', e.target.value);
                            params.delete('pending_page'); // Reset pagination
                        } else {
                            params.set('incomplete_search', e.target.value);
                            params.delete('incomplete_page');
                        }

                        fetchSubmissions(params);
                    }, 500);
                });
            });

            // Event Listeners for Advanced Filter Inputs (Sort Radios & Review Checkboxes)
            document.querySelectorAll('.recent-filter-input, .incomplete-filter-input').forEach(input => {
                input.addEventListener('change', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        const params = new URLSearchParams(window.location.search);

                        // Capture Recent Sort
                        const recentSortNode = document.querySelector('input[name="recent_sort"]:checked');
                        if (recentSortNode) params.set('recent_sort', recentSortNode.value);

                        // Capture Recent Review Types (Multiple Checkboxes)
                        params.delete('recent_review_types[]'); // Clear existing
                        document.querySelectorAll('input[name="recent_review_types[]"]:checked').forEach(cb => {
                            params.append('recent_review_types[]', cb.value);
                        });

                        // Capture Incomplete Sort
                        const incompleteSortNode = document.querySelector('input[name="incomplete_sort"]:checked');
                        if (incompleteSortNode) params.set('incomplete_sort', incompleteSortNode.value);

                        // Capture Incomplete Review Types (Multiple Checkboxes)
                        params.delete('incomplete_review_types[]'); // Clear existing
                        document.querySelectorAll('input[name="incomplete_review_types[]"]:checked').forEach(cb => {
                            params.append('incomplete_review_types[]', cb.value);
                        });

                        // Important: Don't reset pagination here unless specifically requested, 
                        // as users might be changing filters while deep in pagination.
                        // Actually, it's conventional to reset to page 1 when filters change.
                        if (input.classList.contains('recent-filter-input')) params.delete('pending_page');
                        if (input.classList.contains('incomplete-filter-input')) params.delete('incomplete_page');

                        fetchSubmissions(params);
                    }, 100); // Shorter delay for checkboxes/radios
                });
            });

            // Event Delegation for Pagination
            document.addEventListener('click', (e) => {
                const link = e.target.closest('.pagination-link');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const params = new URLSearchParams(url.search);
                    fetchSubmissions(params);
                }
            });
        });
    </script>
</x-admin_layout>