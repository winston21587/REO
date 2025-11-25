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

<div id="statusModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300" id="statusModalContent">
            <div class="bg-[#1a0505] p-6 border-b border-white/10">
                <h3 class="text-white font-bold text-lg">Update Review Status</h3>
                <p id="statusModalTitle" class="text-slate-400 text-xs mt-1 line-clamp-1">Protocol Title</p>
            </div>
            <form id="statusForm" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                    <div class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-1">AI Recommendation</p>
                        <p class="text-sm text-slate-700">Based on content analysis:</p>
                        <div class="mt-2 inline-flex items-center gap-2 bg-white px-3 py-1 rounded-lg border border-blue-200 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                            <span class="text-sm font-bold text-slate-800">Expedited Review</span>
                        </div>
                    </div>
                </div>

                <div class="border-b border-slate-100 pb-6">
                    <div class="flex justify-between items-end mb-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Step 1: Required Action</label>
                        <span id="fileSavedBadge" class="hidden bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">
                            <i class="fas fa-check-circle"></i> File Saved
                        </span>
                    </div>

                    <div id="step1Box" class="flex items-center justify-between bg-slate-50 p-4 rounded-xl border border-slate-200 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                                <i class="fas fa-file-contract text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Result of Review</p>
                                <p id="step1Desc" class="text-xs text-slate-400">Generate and save to unlock Step 2.</p>
                            </div>
                        </div>
                        <a id="generateBtnLink" href="#" target="_blank" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-50 hover:text-[#8B0000] hover:border-[#8B0000] transition-all shadow-sm flex items-center gap-2">
                            <i class="fas fa-print"></i> <span id="generateBtnText">Open Generator</span>
                        </a>
                    </div>
                </div>

                <div id="statusSection" class="space-y-4 opacity-50 grayscale pointer-events-none transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Step 2: Finalize Status</label>
                        <span id="lockMessage" class="text-[10px] text-red-500 font-bold"><i class="fas fa-lock"></i> Generate letter to unlock</span>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">Set Review Classification</label>
                        <select id="reviewTypeSelect" name="review_type" disabled class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] bg-slate-50">
                            <option value="Exempt">Exempt Review</option>
                            <option value="Expedited" selected>Expedited Review</option>
                            <option value="Full Board">Full Board Review</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">New Application Status</label>
                        <select id="statusSelect" name="status" disabled class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] bg-slate-50">
                            <option value="Waiting for Revision">Waiting for Revision</option>
                            <option value="Under Review">Under Review</option>
                            <option value="Approved">Approved</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-slate-600 font-bold text-sm hover:bg-slate-50 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" id="submitStatusBtn" disabled class="px-4 py-2 bg-[#8B0000] text-white font-bold text-sm rounded-lg hover:bg-[#6d0000] transition-colors shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        Confirm Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function openStatusModal(id, title) {
            document.getElementById('statusModalTitle').textContent = title;
            document.getElementById('statusForm').action = `/admin/update-status/${id}`;
            const genLink = document.getElementById('generateBtnLink');
            genLink.href = `/admin/letter/create/${id}`;
            
            // 1. Reset UI to Locked State
            lockStep2();

            // 2. Check if file already exists
            try {
                const response = await fetch(`/admin/check-file-status/${id}`);
                const data = await response.json();

                if (data.has_letter) {
                    unlockStep2(); // Auto unlock if file exists
                    document.getElementById('generateBtnText').textContent = "View / Regenerate";
                } else {
                    document.getElementById('generateBtnText').textContent = "Open Generator";
                    
                    // Add listener to unlock when clicked (Optimistic unlock)
                    // In a real app, you might want to wait for them to come back, 
                    // but for smooth UX, we unlock after they click the generator.
                    genLink.onclick = function() {
                        setTimeout(unlockStep2, 2000); 
                    };
                }
            } catch (error) {
                console.error("Error checking file status", error);
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

        function lockStep2() {
            const statusSection = document.getElementById('statusSection');
            const submitBtn = document.getElementById('submitStatusBtn');
            const inputs = ['reviewTypeSelect', 'statusSelect'];
            const lockMsg = document.getElementById('lockMessage');
            const badge = document.getElementById('fileSavedBadge');
            const box = document.getElementById('step1Box');

            statusSection.classList.add('opacity-50', 'grayscale', 'pointer-events-none');
            submitBtn.disabled = true;
            inputs.forEach(id => document.getElementById(id).disabled = true);
            lockMsg.classList.remove('hidden');
            badge.classList.add('hidden');
            box.classList.remove('border-green-200', 'bg-green-50');
        }

        function unlockStep2() {
            const statusSection = document.getElementById('statusSection');
            const submitBtn = document.getElementById('submitStatusBtn');
            const inputs = ['reviewTypeSelect', 'statusSelect'];
            const lockMsg = document.getElementById('lockMessage');
            const badge = document.getElementById('fileSavedBadge');
            const box = document.getElementById('step1Box');

            statusSection.classList.remove('opacity-50', 'grayscale', 'pointer-events-none');
            submitBtn.disabled = false;
            inputs.forEach(id => document.getElementById(id).disabled = false);
            lockMsg.classList.add('hidden');
            
            // Show Success Indicators
            badge.classList.remove('hidden');
            box.classList.add('border-green-200', 'bg-green-50');
            box.classList.remove('border-slate-200', 'bg-slate-50');
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            const content = document.getElementById('statusModalContent');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    </script>

</x-admin_layout>