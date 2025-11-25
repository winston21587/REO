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
                    @forelse($incompleteSubmissions as $sub)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group opacity-75 hover:opacity-100">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg leading-tight group-hover:text-[#8B0000] transition-colors line-clamp-1">{{ $sub->Study_Protocol_title }}</h3>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Submitted at: {{ $sub->created_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xs font-bold text-slate-400 italic">Waiting for resubmission...</span>
                            <span class="bg-slate-100 text-slate-500 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide">Incomplete</span>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center flex flex-col items-center justify-center h-64">
                        <p class="text-slate-400 font-medium">No incomplete submissions found.</p>
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
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Select Review Type</label>
                        
                        <div class="space-y-3">
                            <label class="group flex items-start gap-4 p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-200 transition-all relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <input type="radio" name="review_type" value="Exempt" class="mt-1 text-green-600 focus:ring-green-500">
                                <div>
                                    <span class="block font-bold text-slate-800 text-sm group-hover:text-green-700 transition-colors">Exempt Review (SOP 04)</span>
                                    <span class="block text-xs text-slate-500 mt-1">No human participants or minimal risk.</span>
                                </div>
                            </label>

                            <label class="group flex items-start gap-4 p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-all relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <input type="radio" name="review_type" value="Expedited" class="mt-1 text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="block font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors">Expedited Review (SOP 05)</span>
                                    <span class="block text-xs text-slate-500 mt-1">Minimal risk. Assigned to 2 Primary Reviewers.</span>
                                </div>
                            </label>

                            <label class="group flex items-start gap-4 p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-red-50 hover:border-red-200 transition-all relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#8B0000] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <input type="radio" name="review_type" value="Full Review" class="mt-1 text-[#8B0000] focus:ring-[#8B0000]">
                                <div>
                                    <span class="block font-bold text-slate-800 text-sm group-hover:text-[#8B0000] transition-colors">Full Board Review (SOP 06)</span>
                                    <span class="block text-xs text-slate-500 mt-1">High risk / Vulnerable groups. Requires meeting.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">System Code</span>
                        <span class="font-mono font-bold text-slate-900 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">{{ date('Y') }}-{{ str_pad($pendingSubmissions->count() + 1, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                    <button type="button" onclick="closeTriage()" class="px-5 py-2.5 text-slate-600 font-bold text-sm hover:bg-white hover:text-slate-800 rounded-lg transition-all border border-transparent hover:border-slate-200">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-[#8B0000] text-white font-bold text-sm rounded-lg shadow-lg hover:bg-[#6d0000] hover:shadow-xl transition-all transform hover:-translate-y-0.5">Confirm & Assign</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openTriageModal(id, title) {
            const modal = document.getElementById('triageModal');
            const content = document.getElementById('modalContent');
            const titleEl = document.getElementById('modalTitle');
            const form = document.getElementById('triageForm');
            
            titleEl.textContent = title;
            form.action = `/admin/${id}/set-initial-review`; 
            
            modal.classList.remove('hidden');
            // Small delay to allow display:block to apply before opacity transition
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
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</x-admin_layout>