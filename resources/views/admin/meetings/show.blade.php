<x-admin_layout>
    <x-slot name="title">Meeting Agenda</x-slot>

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        editItem: { id: '', section: '', content: '' },
        editUrl: '',
        openEditModal(item, url) {
            this.editItem = item;
            this.editUrl = url;
            this.showEditModal = true;
        }
    }" class="space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-slate-500 text-sm mb-1">
                <a href="{{ route('admin.meetings') }}" class="hover:text-[#8B0000]">Meetings</a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span>Agenda Builder</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $meeting->title }}</h1>
            <div class="flex items-center gap-3 text-sm text-slate-600 mt-1">
                <div class="flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar"></i>
                    <span>{{ $meeting->meeting_date->format('F j, Y') }}</span>
                </div>
                <span>•</span>
                <div class="flex items-center gap-1.5">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ $meeting->meeting_date->format('h:i A') }}</span>
                </div>
                <span>•</span>
                <span class="@if($meeting->agenda_status === 'Final') text-green-600 @elseif($meeting->agenda_status === 'Provisional') text-amber-600 @else text-slate-500 @endif font-medium px-2 py-0.5 bg-slate-100 rounded-full text-xs">
                    {{ $meeting->agenda_status }} Status
                </span>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            @if($meeting->agenda_status === 'Draft')
            <form action="{{ route('admin.meetings.status', $meeting->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="agenda_status" value="Provisional">
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors shadow-sm text-sm font-medium">
                    <i class="fa-solid fa-check mr-1"></i> Mark as Provisional
                </button>
            </form>
            @elseif($meeting->agenda_status === 'Provisional')
            <form action="{{ route('admin.meetings.status', $meeting->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="agenda_status" value="Final">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm text-sm font-medium">
                    <i class="fa-solid fa-check-double mr-1"></i> Finalize Agenda
                </button>
            </form>
            @endif
            
            <button class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors shadow-sm text-sm font-medium">
                <i class="fa-solid fa-print mr-1"></i> Print Agenda
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Agenda Items List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-semibold text-slate-800">Agenda Items</h3>
                    <span class="text-xs text-slate-500">SOP 18 Standard Format</span>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @foreach($meeting->agendaItems as $item)
                    <div class="p-4 hover:bg-slate-50 transition-colors group">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-slate-100 text-slate-500 rounded flex items-center justify-center font-mono text-sm font-bold">
                                {{ $item->order }}
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-medium text-slate-900">{{ $item->section }}</h4>
                                @if($item->content)
                                <p class="text-sm text-slate-600 mt-1">{{ $item->content }}</p>
                                @else
                                <p class="text-sm text-slate-400 italic mt-1">No details added.</p>
                                @endif
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
                                <button 
                                    data-item="{{ json_encode($item) }}"
                                    data-url="{{ route('admin.agenda.update', $item->id) }}"
                                    @click="openEditModal(JSON.parse($el.dataset.item), $el.dataset.url)"
                                    class="p-1.5 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded transition-colors" 
                                    title="Edit Item">
                                    <i class="fa-solid fa-pen text-sm pointer-events-none"></i>
                                </button>
                                <form action="{{ route('admin.agenda.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Remove Item">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    <button @click="showAddModal = true" class="w-full py-2 border-2 border-dashed border-slate-300 text-slate-500 rounded-lg hover:border-[#8B0000] hover:text-[#8B0000] transition-colors font-medium text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add Custom Agenda Item</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar / Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="font-semibold text-slate-800 mb-4">Meeting Details</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-slate-500 text-xs uppercase tracking-wider mb-1">Venue</span>
                        <span class="font-medium text-slate-900">{{ $meeting->venue ?? 'Not set' }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-xs uppercase tracking-wider mb-1">Type</span>
                        <span class="font-medium text-slate-900">{{ $meeting->type }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-xs uppercase tracking-wider mb-1">Quorum Status</span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-600 font-medium text-xs">
                            <i class="fa-solid fa-users"></i>
                            Check on Meeting Day
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold text-blue-900 text-sm">SOP Reminder</h4>
                        <p class="text-blue-700 text-xs mt-1 leading-relaxed">
                            The <strong>Provisional Agenda</strong> must be distributed to members at least <strong>1 week</strong> before the meeting (SOP 17).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100 transform transition-all">
                
                <!-- Premium Header -->
                <div class="bg-gradient-to-r from-[#8B0000] to-[#600000] px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <i class="fa-solid fa-plus text-white text-sm"></i>
                        </div>
                        <h3 class="font-bold text-white text-lg tracking-wide">Add Agenda Item</h3>
                    </div>
                    <button @click="showAddModal = false" class="text-white/70 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.meetings.agenda.store', $meeting->id) }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    
                    <!-- Section Title -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Section Title</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-heading text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                            </div>
                            <input type="text" name="section" required placeholder="e.g., Opening Remarks" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all placeholder:text-slate-400 text-slate-800 font-medium">
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Content / Details</label>
                        <div class="relative group">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <i class="fa-solid fa-align-left text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                            </div>
                            <textarea name="content" rows="3" placeholder="Enter agenda details..." 
                                      class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all placeholder:text-slate-400 text-slate-800 font-medium resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Order Sequence</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-sort-numeric-down text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                            </div>
                            <input type="number" name="order" value="{{ $meeting->agendaItems->count() + 1 }}" required 
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all placeholder:text-slate-400 text-slate-800 font-medium">
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-2">
                        <button type="button" @click="showAddModal = false" class="px-5 py-2.5 text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-xl transition-colors font-bold text-sm">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#8B0000] to-[#600000] text-white rounded-xl hover:shadow-lg hover:shadow-red-900/20 hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Item</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100 transform transition-all">
                
                <!-- Premium Header -->
                <div class="bg-gradient-to-r from-[#8B0000] to-[#600000] px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                        </div>
                        <h3 class="font-bold text-white text-lg tracking-wide">Edit Agenda Item</h3>
                    </div>
                    <button @click="showEditModal = false" class="text-white/70 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form :action="editUrl" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <!-- Section Title -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Section Title</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-heading text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                            </div>
                            <input type="text" name="section" x-model="editItem.section" required 
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all placeholder:text-slate-400 text-slate-800 font-medium">
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Content / Details</label>
                        <div class="relative group">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <i class="fa-solid fa-align-left text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                            </div>
                            <textarea name="content" x-model="editItem.content" rows="3" 
                                      class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all placeholder:text-slate-400 text-slate-800 font-medium resize-none"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-2">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-xl transition-colors font-bold text-sm">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#8B0000] to-[#600000] text-white rounded-xl hover:shadow-lg hover:shadow-red-900/20 hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-check"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>
</x-admin_layout>
