<x-admin_layout>
    <div class="max-w-7xl mx-auto py-8 animate-[fadeInUp_0.5s_ease-out]">

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Recent Submissions Column -->
            <div class="space-y-6 flex flex-col h-full">
                <h2 class="text-2xl font-extrabold text-slate-800 font-heading">Recent Submissions</h2>
                
                <!-- Controls -->
                <div class="flex gap-4">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search submissions..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm bg-white">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                    <div class="relative">
                        <select class="appearance-none pl-4 pr-10 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] bg-white font-medium text-slate-600 cursor-pointer shadow-sm">
                            <option>Submission Date</option>
                            <option>Title</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <!-- Submissions List -->
                <div class="space-y-4 flex-1">
                    @forelse($pendingSubmissions as $sub)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg leading-tight group-hover:text-[#8B0000] transition-colors line-clamp-1" title="{{ $sub->Study_Protocol_title }}">{{ $sub->Study_Protocol_title }}</h3>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Submitted at: {{ $sub->created_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-4">
                            <a href="{{ route('admin.view_files', $sub->id) }}" class="bg-[#dc2626] hover:bg-[#b91c1c] text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                                View Details
                            </a>
                            
                            <div class="flex items-center gap-2">
                                <span class="bg-[#fef08a] text-[#854d0e] px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide">Pending</span>
                                <button onclick="openTriageModal('{{ $sub->id }}', '{{ addslashes($sub->Study_Protocol_title) }}')" class="bg-[#fecaca] hover:bg-[#fca5a5] text-[#991b1b] px-4 py-2 rounded-lg text-xs font-bold transition-all">
                                    Action
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-3xl text-slate-300"></i>
                        </div>
                        <p class="text-slate-500 font-medium">No recent submissions found.</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Pagination (Static for UI) -->
                <div class="flex items-center justify-between pt-4 mt-auto">
                    <span class="text-xs text-slate-400 font-medium">Previous</span>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 rounded-lg bg-[#dc2626] text-white text-xs font-bold shadow-md">1</button>
                        <button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-500 text-xs font-bold transition-colors">2</button>
                        <button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-500 text-xs font-bold transition-colors">3</button>
                        <span class="w-8 h-8 flex items-center justify-center text-slate-400 text-xs">...</span>
                        <button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-500 text-xs font-bold transition-colors">10</button>
                    </div>
                    <span class="text-xs text-slate-500 font-bold cursor-pointer hover:text-[#8B0000]">Next</span>
                </div>
            </div>

            <!-- Incomplete Submissions Column -->
            <div class="space-y-6 flex flex-col h-full">
                <h2 class="text-2xl font-extrabold text-slate-800 font-heading">Incomplete Submissions</h2>
                
                <div class="flex gap-4">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search submissions..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm bg-white">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                    <div class="relative">
                        <select class="appearance-none pl-4 pr-10 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] bg-white font-medium text-slate-600 cursor-pointer shadow-sm">
                            <option>Submission Date</option>
                            <option>Title</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="space-y-4 flex-1">
                    @forelse($incompleteSubmissions as $sub)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group border-l-4 border-l-red-400">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg leading-tight group-hover:text-[#8B0000] transition-colors line-clamp-1" title="{{ $sub->Study_Protocol_title }}">{{ $sub->Study_Protocol_title }}</h3>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Submitted at: {{ $sub->created_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-4">
                            <a href="{{ route('admin.view_files', $sub->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                                <i class="fas fa-folder-open"></i> Check Files
                            </a>
                            
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-red-400 uppercase tracking-wider mr-2">Incomplete</span>
                                <button onclick="openTriageModal('{{ $sub->id }}', '{{ addslashes($sub->Study_Protocol_title) }}')" class="bg-[#fecaca] hover:bg-[#fca5a5] text-[#991b1b] px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm hover:shadow-md active:transform active:scale-95">
                                    Re-Evaluate
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center flex flex-col items-center justify-center h-64">
                        <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check text-2xl text-green-400"></i>
                        </div>
                        <p class="text-slate-400 font-medium">No incomplete submissions found.</p>
                    </div>
                    @endforelse
                </div>
                
                <div class="flex items-center justify-between pt-4 mt-auto">
                    <span class="text-xs text-slate-400 font-medium">Previous</span>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 rounded-lg bg-[#dc2626] text-white text-xs font-bold shadow-md">1</button>
                        <button class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-500 text-xs font-bold transition-colors">2</button>
                    </div>
                    <span class="text-xs text-slate-500 font-bold cursor-pointer hover:text-[#8B0000]">Next</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Triage Modal -->
<div id="triageModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300" id="modalContent">
            <div class="bg-[#1a0505] p-6 border-b border-white/10 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fas fa-gavel text-6xl text-white"></i>
                </div>
                <h3 class="text-white font-bold text-xl relative z-10" id="modal-title">Protocol Classification</h3>
                <p class="text-slate-400 text-xs mt-1 relative z-10">Determine the level of review required (SOP 04/05/06).</p>
            </div>
            
            <form id="triageForm" method="POST" action="">
                @csrf
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Protocol Title</label>
                        <p id="modalTitle" class="text-slate-800 font-bold bg-slate-50 p-4 rounded-xl border border-slate-200 text-sm leading-relaxed"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Document Completeness Check</label>
                        
                        <div class="space-y-3">
                            <label id="label_complete" class="group flex items-start gap-4 p-4 border border-slate-200 rounded-xl cursor-pointer transition-all relative overflow-hidden" onclick="selectOption('Complete')">
                                <div id="bar_complete" class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 opacity-0 transition-opacity"></div>
                                <input type="radio" name="classification" value="Complete" class="mt-1 text-green-600 focus:ring-green-500" checked onchange="toggleAppointment(true)">
                                <div>
                                    <span id="text_complete" class="block font-bold text-slate-800 text-sm transition-colors">Complete Submission</span>
                                    <span class="block text-xs text-slate-500 mt-1">All required documents are present.</span>
                                </div>
                            </label>

                            <label id="label_incomplete" class="group flex items-start gap-4 p-4 border border-slate-200 rounded-xl cursor-pointer transition-all relative overflow-hidden" onclick="selectOption('Incomplete')">
                                <div id="bar_incomplete" class="absolute left-0 top-0 bottom-0 w-1 bg-red-500 opacity-0 transition-opacity"></div>
                                <input type="radio" name="classification" value="Incomplete" class="mt-1 text-red-600 focus:ring-red-500" onchange="toggleAppointment(false)">
                                <div>
                                    <span id="text_incomplete" class="block font-bold text-slate-800 text-sm transition-colors">Incomplete Submission</span>
                                    <span class="block text-xs text-slate-500 mt-1">Return to researcher for revision.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="appointmentField" class="transition-all duration-300">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Set Appointment Date</label>
                        <input type="date" name="appointment_date" class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm">
                    </div>

                    <div id="incompleteFields" class="hidden opacity-0 transition-all duration-300 space-y-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">General Remarks</label>
                            <textarea name="remarks" rows="2" class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-sm placeholder-slate-400" placeholder="e.g., Please review the comments in the attached file..."></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Missing Requirements / Specific Issues</label>
                            
                            <div class="flex gap-2 mb-3">
                                <input type="text" id="reqInput" class="flex-1 p-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]" placeholder="Type missing document (e.g., Scanned ID)...">
                                <button type="button" onclick="addRequirement()" class="bg-slate-800 text-white px-4 rounded-lg hover:bg-slate-700 transition-colors shadow-sm active:transform active:scale-95">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <div id="requirementsList" class="space-y-2 max-h-40 overflow-y-auto pr-1">
                                </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                    <button type="button" onclick="closeTriage()" class="px-5 py-2.5 text-slate-600 font-bold text-sm hover:bg-white hover:text-slate-800 rounded-lg transition-all border border-transparent hover:border-slate-200">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-[#8B0000] text-white font-bold text-sm rounded-lg shadow-lg hover:bg-[#6d0000] hover:shadow-xl transition-all transform hover:-translate-y-0.5">Confirm & Send</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- Requirement List Logic ---
        function addRequirement() {
            const input = document.getElementById('reqInput');
            const container = document.getElementById('requirementsList');
            const value = input.value.trim();

            if (!value) return;

            // Generate unique ID
            const id = Date.now();

            const itemHTML = `
                <div id="req-${id}" class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-200 shadow-sm animate-[fadeIn_0.3s_ease-out] group">
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
        document.getElementById('reqInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addRequirement();
            }
        });

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

        // AJAX Submission
        document.getElementById('triageForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

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
                    alert('Status updated successfully!'); 
                    closeTriage();
                    window.location.reload(); 
                } else {
                    alert(result.message || 'Error occurred');
                }
            } catch (error) {
                console.error(error);
                alert('Unexpected error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    </script>
</x-admin_layout>