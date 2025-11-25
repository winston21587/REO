<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Active Protocols</h1>
                <p class="text-slate-500 mt-2 text-sm">Manage and monitor ongoing research protocols.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <div class="relative">
                    <input type="text" placeholder="Search protocols..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-64 shadow-sm">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                <div class="relative" x-data="{ openFilter: false }">
                    <button @click="openFilter = !openFilter" @click.away="openFilter = false" class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <!-- Filter Dropdown -->
                    <div x-show="openFilter" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                         style="display: none;">
                        <div class="p-1">
                            <a href="{{ route('admin.applications') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">All Protocols</a>
                            <a href="{{ route('admin.applications', ['status' => 'Waiting for Revision']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Waiting for Revision</a>
                            <a href="{{ route('admin.applications', ['status' => 'Panel Deliberation']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Panel Deliberation</a>
                            <a href="{{ route('admin.applications', ['status' => 'Submission of Revisions / Resubmission']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Resubmission</a>
                            <a href="{{ route('admin.applications', ['status' => 'Checking of Revisions']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Checking of Revisions</a>
                            <a href="{{ route('admin.applications', ['status' => 'Approval / Issuance of Certificate']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Approved</a>
                            <a href="{{ route('admin.applications', ['status' => 'Disapproval']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Disapproved</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100">
            <div class="overflow-x-auto min-h-[400px] overflow-y-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Protocol ID</th>
                            <th class="p-6">Research Title</th>
                            <th class="p-6">Principal Investigator</th>
                            <th class="p-6">Submission Date</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($datas as $data)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="p-6">
                                <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                    #{{ str_pad($data['id'], 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="p-6">
                                <p class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-[#8B0000] transition-colors">{{ $data['title'] }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $data['ReviewType'] ?? 'Standard Review' }}</p>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                        {{ substr($data['name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">{{ $data['name'] }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $data['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <i class="far fa-calendar text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="p-6">
                                @php
                                    $statusColors = [
                                        'Waiting for Revision' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'Panel Deliberation' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'Submission of Revisions / Resubmission' => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'Checking of Revisions' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                        'Approval / Issuance of Certificate' => 'bg-green-50 text-green-700 border-green-100',
                                        'Disapproval' => 'bg-red-50 text-red-700 border-red-100',
                                    ];
                                    $colorClass = $statusColors[$data['status']] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }} inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                                    {{ $data['status'] }}
                                </span>
                                @if(isset($data['RevisionStage']))
                                    <p class="text-[10px] text-slate-400 mt-1 ml-2">{{ $data['RevisionStage'] }}</p>
                                @endif
                            </td>
                            <td class="p-6 text-right relative">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                                         style="display: none;">
                                        
                                        <div class="p-1">
                                            <a href="{{ route('admin.view_files', $data['id']) }}" @click="open = false" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                <i class="fas fa-eye w-4"></i> View Details
                                            </a>
                                            <button @click="open = false; openStatusModal('{{ $data['id'] }}', '{{ addslashes($data['title']) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                <i class="fas fa-sync-alt w-4"></i> Update Status
                                            </button>
                                            <button @click="open = false; openReviewersModal('{{ $data['id'] }}', '{{ addslashes($data['title']) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                <i class="fas fa-users-cog w-4"></i> Assign Reviewers
                                            </button>
                                            <button @click="open = false; openRevisionsModal('{{ $data['id'] }}', '{{ addslashes($data['title']) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                <i class="fas fa-history w-4"></i> View Revisions
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (Static for now) -->
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center">
                <p class="text-xs text-slate-500">Showing <span class="font-bold text-slate-700">1-{{ count($datas) }}</span> of <span class="font-bold text-slate-700">{{ count($datas) }}</span> protocols</p>
                <div class="flex gap-1">
                    <button class="px-3 py-1 text-xs font-medium text-slate-400 hover:text-slate-600 disabled:opacity-50" disabled>Previous</button>
                    <button class="px-3 py-1 text-xs font-medium text-white bg-[#8B0000] rounded shadow-sm">1</button>
                    <button class="px-3 py-1 text-xs font-medium text-slate-400 hover:text-slate-600 disabled:opacity-50" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="statusModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="statusModalContent">
            <div class="bg-[#1a0505] p-6 border-b border-white/10">
                <h3 class="text-white font-bold text-lg">Update Protocol Status</h3>
                <p id="statusModalTitle" class="text-slate-400 text-xs mt-1 line-clamp-1">Protocol Title</p>
            </div>
            <form id="statusForm" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">New Status</label>
                    <select name="status" class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                        <option value="Waiting for Revision">Waiting for Revision</option>
                        <option value="Panel Deliberation">Panel Deliberation</option>
                        <option value="Submission of Revisions / Resubmission">Submission of Revisions / Resubmission</option>
                        <option value="Checking of Revisions">Checking of Revisions</option>
                        <option value="Approval / Issuance of Certificate">Approval / Issuance of Certificate</option>
                        <option value="Disapproval">Disapproval</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Appointed Date</label>
                    <input type="date" name="appointment_date" class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Remarks (Optional)</label>
                    <textarea name="remarks" rows="3" class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]" placeholder="Add any comments..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-slate-600 font-bold text-sm hover:bg-slate-50 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#8B0000] text-white font-bold text-sm rounded-lg hover:bg-[#6d0000] transition-colors shadow-md">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assign Reviewers Modal -->
    <div id="reviewersModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300" id="reviewersModalContent">
            <div class="bg-[#1a0505] p-6 border-b border-white/10">
                <h3 class="text-white font-bold text-lg">Assign Reviewers</h3>
                <p id="reviewersModalTitle" class="text-slate-400 text-xs mt-1 line-clamp-1">Protocol Title</p>
            </div>
            <form id="reviewersForm" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Primary Reviewer</label>
                    <select name="primary_reviewer" class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                        <option value="">Select Reviewer...</option>
                        <option value="1">Dr. Jose Rizal (Forestry)</option>
                        <option value="2">Dr. Maria Clara (Education)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Secondary Reviewer</label>
                    <select name="secondary_reviewer" class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                        <option value="">Select Reviewer...</option>
                        <option value="3">Dr. Apolinario Mabini (Medicine)</option>
                        <option value="4">Dr. Andres Bonifacio (Engineering)</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeReviewersModal()" class="px-4 py-2 text-slate-600 font-bold text-sm hover:bg-slate-50 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#8B0000] text-white font-bold text-sm rounded-lg hover:bg-[#6d0000] transition-colors shadow-md">Assign Reviewers</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Revisions Modal -->
    <div id="revisionsModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[80vh]" id="revisionsModalContent">
            <div class="bg-[#1a0505] p-6 border-b border-white/10 shrink-0">
                <h3 class="text-white font-bold text-lg">Revision History</h3>
                <p id="revisionsModalTitle" class="text-slate-400 text-xs mt-1 line-clamp-1">Protocol Title</p>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-4">
                <!-- Mock Revision Item 1 -->
                <div class="bg-white p-4 rounded-xl border-l-4 border-orange-400 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="px-2 py-0.5 rounded bg-orange-100 text-orange-700 text-[10px] font-bold uppercase">Revision Required</span>
                            <p class="text-xs text-slate-400 mt-1">Nov 24, 2024</p>
                        </div>
                        <span class="text-xs font-bold text-slate-500">Appointed: Nov 30, 2024</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-xs text-slate-600"><strong class="text-slate-900">Remarks:</strong> Methodology section lacks clear sampling size justification. Please revise accordingly.</p>
                    </div>
                </div>

                <!-- Mock Revision Item 2 -->
                <div class="bg-white p-4 rounded-xl border-l-4 border-blue-500 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-bold uppercase">Under Review</span>
                            <p class="text-xs text-slate-400 mt-1">Nov 20, 2024</p>
                        </div>
                        <span class="text-xs font-bold text-slate-500">Appointed: Nov 25, 2024</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-xs text-slate-600"><strong class="text-slate-900">Remarks:</strong> Initial review commenced. Assigned to primary reviewers.</p>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end shrink-0">
                <button type="button" onclick="closeRevisionsModal()" class="px-4 py-2 bg-slate-200 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-300 transition-colors">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Status Modal Functions
        function openStatusModal(id, title) {
            const modal = document.getElementById('statusModal');
            const content = document.getElementById('statusModalContent');
            const form = document.getElementById('statusForm');
            
            document.getElementById('statusModalTitle').textContent = title;
            form.action = `/admin/update-status/${id}`; // Dynamic Action
            
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

        // Reviewers Modal Functions
        function openReviewersModal(id, title) {
            const modal = document.getElementById('reviewersModal');
            const content = document.getElementById('reviewersModalContent');
            const form = document.getElementById('reviewersForm');

            document.getElementById('reviewersModalTitle').textContent = title;
            form.action = `/admin/assign-reviewers/${id}`; // Dynamic Action (Route needs to exist)
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeReviewersModal() {
            const modal = document.getElementById('reviewersModal');
            const content = document.getElementById('reviewersModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // Revisions Modal Functions
        function openRevisionsModal(id, title) {
            const modal = document.getElementById('revisionsModal');
            const content = document.getElementById('revisionsModalContent');
            
            document.getElementById('revisionsModalTitle').textContent = title;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeRevisionsModal() {
            const modal = document.getElementById('revisionsModal');
            const content = document.getElementById('revisionsModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // Generic AJAX Form Handler
        function handleAjaxForm(formId, modalCloseFunc) {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert(result.message || 'Action completed successfully.');
                        if (modalCloseFunc) modalCloseFunc();
                        window.location.reload();
                    } else {
                        alert(result.message || 'An error occurred.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An unexpected error occurred.');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        }

        // Initialize Handlers
        document.addEventListener('DOMContentLoaded', function() {
            handleAjaxForm('statusForm', closeStatusModal);
            handleAjaxForm('reviewersForm', closeReviewersModal);
        });
    </script>
</x-admin_layout>