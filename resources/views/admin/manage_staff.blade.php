<x-admin_layout>
    <div x-data="{ showAddModal: false, showEditModal: false, selectedMember: null, editForm: { id: '', position: '', member_type: '', expertise: '', college: '', training_completed: false } }" class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">REO Membership</h1>
                <p class="text-slate-500 mt-2 text-sm">Manage committee members, expertise, and appointments (SOP 01).</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <button @click="showAddModal = true" class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Add Member
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Members</span>
                <div class="flex justify-between items-end mt-2">
                    <p class="text-3xl font-extrabold text-slate-800">{{ $stats['total'] }}</p>
                    <i class="fas fa-users text-slate-200 text-2xl group-hover:text-[#8B0000] transition-colors"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
                <span class="text-xs font-bold text-blue-500 uppercase tracking-wider">Officers</span>
                <div class="flex justify-between items-end mt-2">
                    <p class="text-3xl font-extrabold text-slate-800">{{ $stats['officers'] }}</p>
                    <i class="fas fa-user-tie text-blue-100 text-2xl group-hover:text-blue-500 transition-colors"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
                <span class="text-xs font-bold text-purple-500 uppercase tracking-wider">Trained Members</span>
                <div class="flex justify-between items-end mt-2">
                    <p class="text-3xl font-extrabold text-slate-800">{{ $stats['trained'] }}</p>
                    <i class="fas fa-certificate text-purple-100 text-2xl group-hover:text-purple-500 transition-colors"></i>
                </div>
            </div>
            <div class="{{ $stats['quorum'] == 'Valid' ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100' }} p-4 rounded-xl border shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
                <span class="text-xs font-bold {{ $stats['quorum'] == 'Valid' ? 'text-green-600' : 'text-red-600' }} uppercase tracking-wider">Quorum Status</span>
                <div class="flex justify-between items-end mt-2">
                    <p class="text-lg font-bold {{ $stats['quorum'] == 'Valid' ? 'text-green-800' : 'text-red-800' }}">{{ $stats['quorum'] }}</p>
                    <i class="fas {{ $stats['quorum'] == 'Valid' ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Members List -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Member Name</th>
                            <th class="p-6">Position & Role</th>
                            <th class="p-6">Expertise</th>
                            <th class="p-6">Training</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($staff as $member)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-[#8B0000] text-white flex items-center justify-center font-bold shadow-sm">
                                        {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-base">{{ $member->first_name }} {{ $member->last_name }}</p>
                                        <p class="text-xs text-slate-400">{{ $member->college ?? 'External Affiliate' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="flex flex-col items-start gap-1">
                                    @if($member->position && $member->position !== 'Member')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-700 border border-amber-200">
                                            {{ $member->position }}
                                        </span>
                                    @endif
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $member->member_type == 'Scientist' ? 'bg-blue-50 text-blue-700 border-blue-100' : ($member->member_type == 'Non-Affiliated' ? 'bg-slate-100 text-slate-600 border-slate-200' : 'bg-purple-50 text-purple-700 border-purple-100') }}">
                                        {{ $member->member_type }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="flex flex-wrap gap-1">
                                    @if($member->expertise)
                                        @foreach($member->expertise as $tag)
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold">{{ $tag }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-slate-400 italic text-xs">None listed</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-6">
                                @if($member->training_completed)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                        <i class="fas fa-check-circle text-green-500"></i> Trained
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                        <i class="fas fa-times-circle text-red-500"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="p-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="selectedMember = {{ $member }}; editForm.id = '{{ $member->id }}'; editForm.position = '{{ $member->position }}'; editForm.member_type = '{{ $member->member_type }}'; editForm.expertise = '{{ implode(', ', $member->expertise ?? []) }}'; editForm.college = '{{ $member->college }}'; editForm.training_completed = {{ $member->training_completed ? 'true' : 'false' }}; showEditModal = true" class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.staff.delete', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this member?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500">
                                No committee members found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Member Modal -->
        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="bg-white px-6 pt-6 pb-4 border-b border-slate-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                                    <i class="fas fa-user-plus text-[#8B0000] text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Add Committee Member</h3>
                                    <p class="text-xs text-slate-500">Register a new member to the REO committee.</p>
                                </div>
                            </div>
                            <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">First Name</label>
                                    <input type="text" name="first_name" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Last Name</label>
                                    <input type="text" name="last_name" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address</label>
                                <input type="email" name="email" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Position</label>
                                    <select name="position" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all appearance-none">
                                        <option value="Member">Member</option>
                                        <option value="Chair">Chair</option>
                                        <option value="Vice-Chair">Vice-Chair</option>
                                        <option value="Secretary">Secretary</option>
                                        <option value="ERP Chair">ERP Chair</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Member Type</label>
                                    <select name="member_type" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all appearance-none">
                                        <option value="Scientist">Scientist (Affiliated)</option>
                                        <option value="Non-Scientist">Non-Scientist (Affiliated)</option>
                                        <option value="Non-Affiliated">Non-Affiliated</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">College / Affiliation</label>
                                <input type="text" name="college" placeholder="e.g. College of Medicine" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Expertise (Comma separated)</label>
                                <input type="text" name="expertise" placeholder="e.g. Medical, Ethics, Clinical Trials" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <input type="checkbox" name="training_completed" value="1" id="training_completed" class="w-4 h-4 text-[#8B0000] border-slate-300 rounded focus:ring-[#8B0000]">
                                <label for="training_completed" class="text-sm text-slate-700 font-medium">Basic Ethics Training Completed</label>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-50 mt-6">
                                <button type="button" @click="showAddModal = false" class="px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition-colors">Cancel</button>
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#8B0000] to-[#600000] text-white text-sm font-bold rounded-xl shadow-lg shadow-red-900/20 hover:shadow-red-900/30 hover:-translate-y-0.5 transition-all">Add Member</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Member Modal -->
        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="bg-white px-6 pt-6 pb-4 border-b border-slate-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                                    <i class="fas fa-user-edit text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Edit Member Details</h3>
                                    <p class="text-xs text-slate-500">Update role and expertise.</p>
                                </div>
                            </div>
                            <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <form :action="`{{ url('/admin/staff') }}/${editForm.id}`" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Position</label>
                                    <select name="position" x-model="editForm.position" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all appearance-none">
                                        <option value="Member">Member</option>
                                        <option value="Chair">Chair</option>
                                        <option value="Vice-Chair">Vice-Chair</option>
                                        <option value="Secretary">Secretary</option>
                                        <option value="ERP Chair">ERP Chair</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Member Type</label>
                                    <select name="member_type" x-model="editForm.member_type" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all appearance-none">
                                        <option value="Scientist">Scientist (Affiliated)</option>
                                        <option value="Non-Scientist">Non-Scientist (Affiliated)</option>
                                        <option value="Non-Affiliated">Non-Affiliated</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">College / Affiliation</label>
                                <input type="text" name="college" x-model="editForm.college" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Expertise (Comma separated)</label>
                                <input type="text" name="expertise" x-model="editForm.expertise" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white outline-none transition-all">
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <input type="checkbox" name="training_completed" value="1" id="edit_training_completed" x-model="editForm.training_completed" class="w-4 h-4 text-[#8B0000] border-slate-300 rounded focus:ring-[#8B0000]">
                                <label for="edit_training_completed" class="text-sm text-slate-700 font-medium">Basic Ethics Training Completed</label>
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-50 mt-6">
                                <button type="button" @click="showEditModal = false" class="px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition-colors">Cancel</button>
                                <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-slate-900/30 hover:-translate-y-0.5 transition-all">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin_layout>