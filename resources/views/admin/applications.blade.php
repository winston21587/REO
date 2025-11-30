<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Active Protocols</h1>
                <p class="text-slate-500 mt-2 text-sm">Monitoring Initial Reviews and Revisions.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <form action="{{ route('admin.applications') }}" method="GET" class="relative">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search protocols..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-64 shadow-sm">
                    <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#8B0000]">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <div class="relative" x-data="{ openFilter: false }">
                    <button @click="openFilter = !openFilter" @click.away="openFilter = false" class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                        <i class="fas fa-filter"></i> {{ request('status') ? 'Filtered' : 'Filter' }}
                    </button>
                    <div x-show="openFilter" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                         style="display: none;">
                        <div class="p-1">
                            <a href="{{ route('admin.applications') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Show All (In Review)</a>
                            <div class="h-px bg-slate-100 my-1"></div>
                            <a href="{{ route('admin.applications', ['status' => 'For Initial Review']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">For Initial Review</a>
                            <a href="{{ route('admin.applications', ['status' => 'Waiting for Revision']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Waiting for Revision</a>
                            <a href="{{ route('admin.applications', ['status' => 'Submission of Revisions / Resubmission']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Resubmitted</a>
                            <a href="{{ route('admin.applications', ['status' => 'Checking of Revisions']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg">Checking of Revisions</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                        @forelse($datas as $data)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="p-6">
                                <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                    #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="p-6">
                                <p class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-[#8B0000] transition-colors" title="{{ $data->Study_Protocol_title }}">
                                    {{ $data->Study_Protocol_title }}
                                </p>
                                <p class="text-xs text-slate-400 mt-1">{{ $data->review_type ?? 'Standard Review' }}</p>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                        {{ substr($data->author->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">{{ $data->author->name ?? 'Unknown' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $data->author->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <i class="far fa-calendar text-slate-400"></i>
                                    {{ $data->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="p-6">
                                @php
                                    $statusColors = [
                                        'For Initial Review' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'Waiting for Revision' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'Submission of Revisions / Resubmission' => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'Checking of Revisions' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    ];
                                    $colorClass = $statusColors[$data->Status] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }} inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                                    {{ $data->Status }}
                                </span>
                            </td>
                            <td class="p-6 text-right relative">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div x-show="open" 
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                                         style="display: none;">
                                        <div class="p-1">
                                            <a href="{{ route('admin.view_files', $data->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                <i class="fas fa-eye w-4"></i> View Details
                                            </a>
                                            <button @click="open = false; openStatusModal('{{ $data->id }}', '{{ addslashes($data->Study_Protocol_title) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                <i class="fas fa-sync-alt w-4"></i> Update Status
                                            </button>
                                            <button @click="open = false; openReviewersModal('{{ $data->id }}', '{{ addslashes($data->Study_Protocol_title) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors text-left">
                                                <i class="fas fa-users-cog w-4"></i> Assign Reviewers
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">
                                <i class="fas fa-folder-open text-4xl mb-4 text-slate-300"></i>
                                <p>No active review or revision protocols found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeStatusModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div id="statusModalContent" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg scale-95 duration-300">
                    
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-[#8B0000] to-[#600000] px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white font-heading" id="modal-title">Update Status</h3>
                        <button onclick="closeStatusModal()" class="text-white/70 hover:text-white transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="px-6 py-6 space-y-6">
                        <p id="statusModalTitle" class="text-sm font-medium text-slate-500 border-b border-slate-100 pb-4"></p>

                        <form id="statusForm" method="POST" class="space-y-6">
                            @csrf
                            
                            <!-- 1. AI Analysis Section -->
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        <i class="fas fa-robot text-[#8B0000] mr-1"></i> AI Analysis
                                    </h4>
                                    <span id="aiStatusBadge" class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-200 text-slate-500">Ready</span>
                                </div>
                                
                                <div id="aiLoading" class="hidden text-center py-4">
                                    <i class="fas fa-circle-notch fa-spin text-[#8B0000] text-xl"></i>
                                    <p class="text-xs text-slate-500 mt-2">Reading Assessment Form...</p>
                                </div>

                                <div id="aiResult" class="hidden space-y-2">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5">
                                            <i class="fas fa-lightbulb text-yellow-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Suggested Review Type:</p>
                                            <p id="aiSuggestionText" class="text-sm font-bold text-slate-800">Expedited Review</p>
                                            <p id="aiReasoning" class="text-xs text-slate-400 mt-1 italic">Based on checked boxes in the form.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="aiError" class="hidden text-center py-2">
                                    <p class="text-xs text-red-500"><i class="fas fa-exclamation-circle"></i> Could not analyze file.</p>
                                </div>
                            </div>

                            <!-- 2. Review Type Selection (Box Style) -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">Review Classification</label>
                                <input type="hidden" id="reviewTypeInput" name="review_type" required>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                                    <!-- Option 1: Expedited -->
                                    <div class="review-option cursor-pointer relative rounded-xl border-2 border-slate-200 p-4 hover:border-[#8B0000]/50 hover:bg-red-50/50 transition-all group" onclick="selectReviewType('Expedited', this)">
                                        <div class="absolute top-3 right-3 opacity-0 check-icon text-[#8B0000]">
                                            <i class="fas fa-check-circle text-lg"></i>
                                        </div>
                                        <div class="mb-2 text-slate-400 group-hover:text-[#8B0000] icon-box">
                                            <i class="fas fa-running text-2xl"></i>
                                        </div>
                                        <h5 class="font-bold text-slate-700 text-sm mb-1">Expedited</h5>
                                        <p class="text-[10px] text-slate-500 leading-tight">Minimal risk, faster processing.</p>
                                    </div>

                                    <!-- Option 2: Exempt -->
                                    <div class="review-option cursor-pointer relative rounded-xl border-2 border-slate-200 p-4 hover:border-[#8B0000]/50 hover:bg-red-50/50 transition-all group" onclick="selectReviewType('Exempt', this)">
                                        <div class="absolute top-3 right-3 opacity-0 check-icon text-[#8B0000]">
                                            <i class="fas fa-check-circle text-lg"></i>
                                        </div>
                                        <div class="mb-2 text-slate-400 group-hover:text-[#8B0000] icon-box">
                                            <i class="fas fa-shield-alt text-2xl"></i>
                                        </div>
                                        <h5 class="font-bold text-slate-700 text-sm mb-1">Exempt</h5>
                                        <p class="text-[10px] text-slate-500 leading-tight">Less than minimal risk.</p>
                                    </div>

                                    <!-- Option 3: Full Review -->
                                    <div class="review-option cursor-pointer relative rounded-xl border-2 border-slate-200 p-4 hover:border-[#8B0000]/50 hover:bg-red-50/50 transition-all group" onclick="selectReviewType('Full Review', this)">
                                        <div class="absolute top-3 right-3 opacity-0 check-icon text-[#8B0000]">
                                            <i class="fas fa-check-circle text-lg"></i>
                                        </div>
                                        <div class="mb-2 text-slate-400 group-hover:text-[#8B0000] icon-box">
                                            <i class="fas fa-users text-2xl"></i>
                                        </div>
                                        <h5 class="font-bold text-slate-700 text-sm mb-1">Full Review</h5>
                                        <p class="text-[10px] text-slate-500 leading-tight">High risk, requires meeting.</p>
                                    </div>
                                </div>

                                <!-- Recommendation Letter Button (Moved Here) -->
                                <div id="recommendationSection" class="hidden flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200 animate-[fadeIn_0.3s_ease-out]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-[#8B0000]">
                                            <i class="fas fa-file-signature"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-700">Recommendation Letter</h4>
                                            <p class="text-[10px] text-slate-500">Generate Result of Review Form</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span id="letterIndicator" class="hidden text-[10px] font-bold px-2 py-1 rounded bg-green-100 text-green-600 flex items-center gap-1">
                                            <i class="fas fa-check-circle"></i> Generated
                                        </span>
                                        <a id="recommendationBtn" href="#" target="_blank" class="px-3 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 hover:text-[#8B0000] transition-colors shadow-sm">
                                            Generate
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Appointment Date -->
                            <div>
                                <label for="appointmentDate" class="block text-sm font-bold text-slate-700 mb-2">Set Appointment / Deadline</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="far fa-calendar-alt text-slate-400"></i>
                                    </div>
                                    <input type="date" id="appointmentDate" name="appointment_date" class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm transition-all" required>
                                </div>
                            </div>

                            <!-- 4. Message Box -->
                            <div>
                                <label for="remarks" class="block text-sm font-bold text-slate-700 mb-2">Notification Message <span class="text-slate-400 font-normal text-xs">(Optional)</span></label>
                                <textarea id="remarks" name="remarks" rows="3" class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm transition-all resize-none" placeholder="Add any specific instructions or remarks for the researcher..."></textarea>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" onclick="closeStatusModal()" class="flex-1 px-4 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" id="submitStatusBtn" class="flex-1 px-4 py-3 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#6d0000] transition-colors shadow-lg shadow-red-900/20 flex justify-center items-center gap-2">
                                    <span>Update & Notify</span> <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectReviewType(type, element) {
            // 1. Update Hidden Input
            document.getElementById('reviewTypeInput').value = type;

            // 2. Visual Selection
            // Remove active class from all options
            document.querySelectorAll('.review-option').forEach(el => {
                el.classList.remove('border-[#8B0000]', 'bg-red-50');
                el.classList.add('border-slate-200');
                el.querySelector('.check-icon').classList.add('opacity-0');
                el.querySelector('.icon-box').classList.remove('text-[#8B0000]');
                el.querySelector('.icon-box').classList.add('text-slate-400');
            });

            // Add active class to clicked option
            element.classList.remove('border-slate-200');
            element.classList.add('border-[#8B0000]', 'bg-red-50');
            element.querySelector('.check-icon').classList.remove('opacity-0');
            element.querySelector('.icon-box').classList.remove('text-slate-400');
            element.querySelector('.icon-box').classList.add('text-[#8B0000]');
            
            // 3. Show Recommendation Button & Update Link
            const recSection = document.getElementById('recommendationSection');
            const recBtn = document.getElementById('recommendationBtn');
            
            recSection.classList.remove('hidden');
            
            // Get base URL (without query params)
            let baseUrl = recBtn.getAttribute('data-base-href');
            if (!baseUrl) {
                baseUrl = recBtn.href.split('?')[0];
                recBtn.setAttribute('data-base-href', baseUrl);
            }
            
            recBtn.href = `${baseUrl}?review_type=${encodeURIComponent(type)}`;
        }

        async function openStatusModal(id, title) {
            document.getElementById('statusModalTitle').textContent = title;
            const form = document.getElementById('statusForm');
            form.action = `/admin/update-status/${id}`;
            
            // Update Recommendation Letter Button Base Link
            const recBtn = document.getElementById('recommendationBtn');
            recBtn.href = `/admin/recommendation-letter/${id}`;
            recBtn.setAttribute('data-base-href', `/admin/recommendation-letter/${id}`);
            
            // Hide section initially
            document.getElementById('recommendationSection').classList.add('hidden');
            
            // Check if letter exists
            document.getElementById('letterIndicator').classList.add('hidden');
            try {
                const response = await fetch(`/admin/check-file-status/${id}`);
                const data = await response.json();
                if (data.has_recommendation_letter) {
                    document.getElementById('letterIndicator').classList.remove('hidden');
                }
            } catch (e) {
                console.log('Could not check file status');
            }

            // Reset UI
            document.getElementById('reviewTypeInput').value = "";
            document.getElementById('appointmentDate').value = "";
            document.getElementById('remarks').value = ""; // Reset message box
            
            // Reset Box Selection Visuals
            document.querySelectorAll('.review-option').forEach(el => {
                el.classList.remove('border-[#8B0000]', 'bg-red-50');
                el.classList.add('border-slate-200');
                el.querySelector('.check-icon').classList.add('opacity-0');
                el.querySelector('.icon-box').classList.remove('text-[#8B0000]');
                el.querySelector('.icon-box').classList.add('text-slate-400');
            });

            document.getElementById('aiResult').classList.add('hidden');
            document.getElementById('aiError').classList.add('hidden');
            document.getElementById('aiLoading').classList.remove('hidden');
            document.getElementById('aiStatusBadge').textContent = "Analyzing...";
            document.getElementById('aiStatusBadge').className = "text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-600 animate-pulse";

            // Show Modal
            const modal = document.getElementById('statusModal');
            const content = document.getElementById('statusModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);

            // Trigger AI Analysis
            try {
                const response = await fetch(`/admin/analyze-protocol-type/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();

                document.getElementById('aiLoading').classList.add('hidden');

                if (data.found && data.suggestion) {
                    document.getElementById('aiResult').classList.remove('hidden');
                    document.getElementById('aiSuggestionText').textContent = data.suggestion.recommended_type;
                    document.getElementById('aiReasoning').textContent = data.suggestion.reasoning;
                    
                    // Auto-select if high confidence
                    if (data.suggestion.confidence === 'High') {
                        const type = data.suggestion.recommended_type;
                        // Find the box that matches and click it
                        const boxes = document.querySelectorAll('.review-option');
                        if (type.includes('Expedited')) selectReviewType('Expedited Review', boxes[0]);
                        else if (type.includes('Exempt')) selectReviewType('Exempt Review', boxes[1]);
                        else if (type.includes('Full')) selectReviewType('Full Board Review', boxes[2]);
                    }

                    document.getElementById('aiStatusBadge').textContent = "Complete";
                    document.getElementById('aiStatusBadge').className = "text-[10px] font-bold px-2 py-0.5 rounded bg-green-100 text-green-600";
                } else {
                    throw new Error(data.message || "Analysis failed");
                }

            } catch (error) {
                console.error(error);
                document.getElementById('aiLoading').classList.add('hidden');
                document.getElementById('aiError').classList.remove('hidden');
                document.getElementById('aiStatusBadge').textContent = "Failed";
                document.getElementById('aiStatusBadge').className = "text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-600";
            }
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
        document.getElementById('statusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('submitStatusBtn');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Updating...';

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();

                if (result.success) {
                    closeStatusModal();
                    // Optional: Reload page or update table row
                    window.location.reload(); 
                } else {
                    alert('Error: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });
    </script>

</x-admin_layout>