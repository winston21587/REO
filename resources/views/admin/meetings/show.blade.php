<x-admin_layout>
    <x-slot name="title">Agenda Docket: {{ $meeting->title }} | WMSU REO</x-slot>

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
    }" class="space-y-6 font-['Inter']">
    
        <!-- Breadcrumb & Header Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200/70">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                    <a href="{{ route('admin.meetings') }}" class="hover:text-[#8B0000] transition-colors cursor-pointer">Meetings & Agenda</a>
                    <i class="fa-solid fa-chevron-right text-[9px] text-slate-400" aria-hidden="true"></i>
                    <span class="text-slate-700">Agenda Docket</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-['Montserrat'] tracking-tight">
                    {{ $meeting->title }}
                </h1>
                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs sm:text-sm text-slate-600 mt-2">
                    <div class="inline-flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar text-slate-400 w-4 text-center" aria-hidden="true"></i>
                        <span>{{ $meeting->meeting_date->format('F j, Y') }}</span>
                    </div>
                    <span class="text-slate-300">•</span>
                    <div class="inline-flex items-center gap-1.5">
                        <i class="fa-regular fa-clock text-slate-400 w-4 text-center" aria-hidden="true"></i>
                        <span class="tabular-nums">{{ $meeting->meeting_date->format('h:i A') }}</span>
                    </div>
                    <span class="text-slate-300">•</span>
                    <div class="inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-slate-400 w-4 text-center" aria-hidden="true"></i>
                        <span>{{ $meeting->venue ?? 'WMSU Executive Conference Hall' }}</span>
                    </div>
                    <span class="text-slate-300">•</span>
                    <!-- High-Contrast Semantic Typography (No Pill Background, No Dot) -->
                    <div class="inline-flex items-center gap-1">
                        <span class="text-slate-400 text-xs">Status:</span>
                        <span class="@if($meeting->agenda_status === 'Final') text-emerald-600 @elseif($meeting->agenda_status === 'Provisional') text-blue-600 @else text-amber-600 @endif text-xs font-bold uppercase tracking-wider">
                            {{ $meeting->agenda_status }} AGENDA
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Workflow State Actions & Print -->
            <div class="flex flex-wrap items-center gap-2.5 print:hidden">
                @if($meeting->agenda_status === 'Draft')
                <form action="{{ route('admin.meetings.status', $meeting->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="agenda_status" value="Provisional">
                    <button type="submit" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-xs transition-all active:scale-[0.98] min-h-[44px] cursor-pointer">
                        <i class="fa-solid fa-share text-xs" aria-hidden="true"></i>
                        <span>Circulate Provisional Agenda</span>
                    </button>
                </form>
                @elseif($meeting->agenda_status === 'Provisional')
                <form action="{{ route('admin.meetings.status', $meeting->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="agenda_status" value="Final">
                    <button type="submit" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-xs transition-all active:scale-[0.98] min-h-[44px] cursor-pointer">
                        <i class="fa-solid fa-check-double text-xs" aria-hidden="true"></i>
                        <span>Finalize & Lock Docket</span>
                    </button>
                </form>
                @endif
                
                <button onclick="window.print()" 
                        type="button" 
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-semibold shadow-xs transition-all active:scale-[0.98] min-h-[44px] cursor-pointer">
                    <i class="fa-solid fa-print text-xs text-slate-500" aria-hidden="true"></i>
                    <span>Print Docket</span>
                </button>
            </div>
        </div>

        <!-- Main Docket Workspace Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Agenda Docket List (2 Columns) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 font-['Montserrat'] text-base">Formal Agenda Sequence</h3>
                            <p class="text-xs text-slate-500 mt-0.5">SOP 18 Order of Business & Deliberation Docket</p>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            {{ $meeting->agendaItems->count() }} {{ \Illuminate\Support\Str::plural('Item', $meeting->agendaItems->count()) }}
                        </span>
                    </div>
                    
                    <div class="divide-y divide-slate-100">
                        @forelse($meeting->agendaItems as $item)
                        <div class="p-4 sm:p-5 hover:bg-slate-50/70 transition-colors group">
                            <div class="flex items-start gap-4">
                                <!-- Order Sequence Badge -->
                                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-800 border border-slate-200/80 font-mono font-bold text-sm flex items-center justify-center shrink-0 tabular-nums">
                                    {{ $item->order }}
                                </div>
                                <!-- Item Content -->
                                <div class="flex-1 min-w-0">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#8B0000] block">
                                        {{ $item->section }}
                                    </span>
                                    @if($item->content)
                                    <p class="text-sm font-semibold text-slate-900 mt-0.5 leading-snug">
                                        {{ $item->content }}
                                    </p>
                                    @else
                                    <p class="text-xs text-slate-400 italic mt-0.5">No supplementary details provided for this docket item.</p>
                                    @endif
                                </div>
                                <!-- Action Buttons -->
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1.5 shrink-0 print:hidden">
                                    <button type="button"
                                            data-item="{{ json_encode($item) }}"
                                            data-url="{{ route('admin.agenda.update', $item->id) }}"
                                            @click="openEditModal(JSON.parse($el.dataset.item), $el.dataset.url)"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-[#8B0000] hover:bg-slate-100 transition-colors cursor-pointer" 
                                            title="Edit Item">
                                        <i class="fa-solid fa-pen-to-square text-xs pointer-events-none" aria-hidden="true"></i>
                                    </button>
                                    <form action="{{ route('admin.agenda.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this agenda item from the docket?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" 
                                                title="Remove Item">
                                            <i class="fa-solid fa-trash text-xs" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center text-slate-500 text-sm">
                            <p class="font-medium text-slate-700">No agenda items added yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Assemble the docket using standard SOP 18 categories or add custom items below.</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <!-- Bottom Add Trigger -->
                    <div class="p-4 sm:p-5 bg-slate-50/50 border-t border-slate-100 print:hidden">
                        <button @click="showAddModal = true" 
                                type="button" 
                                class="w-full py-3 border-2 border-dashed border-slate-200 hover:border-[#8B0000] text-slate-500 hover:text-[#8B0000] rounded-xl transition-all font-semibold text-xs uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer bg-white hover:bg-red-50/10 min-h-[44px]">
                            <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                            <span>Add Custom Docket Item</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Session Sidebar Information (1 Column) -->
            <div class="space-y-6">
                <!-- Session Intelligence Card -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
                    <h3 class="font-bold text-slate-900 font-['Montserrat'] text-sm uppercase tracking-wider border-b border-slate-100 pb-2">
                        Session Intelligence
                    </h3>
                    <div class="space-y-3.5 text-sm">
                        <div>
                            <span class="block text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-0.5">Meeting Venue</span>
                            <span class="font-semibold text-slate-900">{{ $meeting->venue ?? 'WMSU Conference Room' }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-0.5">Session Classification</span>
                            <span class="font-semibold text-slate-900">{{ $meeting->type }} Review Session</span>
                        </div>
                        <div>
                            <span class="block text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-0.5">Quorum Standard (SOP 17)</span>
                            <span class="font-semibold text-slate-900">Minimum 5 Members Present</span>
                            <p class="text-xs text-slate-500 mt-0.5">Includes primary evaluators and lay/non-institutional members.</p>
                        </div>
                        <div>
                            <span class="block text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-0.5">Docket Total</span>
                            <span class="font-mono font-bold text-slate-900 text-lg tabular-nums">{{ $meeting->agendaItems->count() }}</span>
                            <span class="text-xs text-slate-500 ml-1">Sequenced Items</span>
                        </div>
                    </div>
                </div>

                <!-- SOP 17 Compliance Notice -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200/80">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-scale-balanced text-sm" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">SOP 17 Regulatory Directive</h4>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                The <strong>Provisional Agenda</strong> must be transmitted to all committee members at least <strong>1 week</strong> prior to the meeting date. Revisions must be locked before session commencement.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Item Modal -->
        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200/80 transform transition-all"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.stop>
                    
                    <div class="px-6 py-4 border-b border-slate-200/80 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center">
                                <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 font-['Montserrat'] text-base">Add Agenda Docket Item</h3>
                        </div>
                        <button @click="showAddModal = false" 
                                type="button" 
                                class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 cursor-pointer">
                            <i class="fa-solid fa-xmark text-base" aria-hidden="true"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.meetings.agenda.store', $meeting->id) }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        
                        <!-- Section Selection / Preset -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_section_add" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Section Category</label>
                            <input type="text" id="agenda_section_add" name="section" required 
                                   list="section-presets"
                                   placeholder="e.g., Preliminary Matters, New Business: Protocol Review" 
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium">
                            <datalist id="section-presets">
                                <option value="Preliminary Matters">
                                <option value="Business Arising from Previous Minutes">
                                <option value="New Business: Full Board Protocol Review">
                                <option value="New Business: Expedited & Exempt Approvals">
                                <option value="Other Matters & Announcements">
                                <option value="Closing & Formal Adjournment">
                            </datalist>
                        </div>

                        <!-- Content Details -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_content_add" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Item Details & Discussion Notes</label>
                            <textarea id="agenda_content_add" name="content" rows="3" 
                                      placeholder="Describe the proposal, presenter, or specific action item..." 
                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium resize-none"></textarea>
                        </div>

                        <!-- Sequence Order -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_order_add" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Docket Sequence Order</label>
                            <input type="number" id="agenda_order_add" name="order" value="{{ $meeting->agendaItems->count() + 1 }}" required 
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium tabular-nums">
                        </div>

                        <!-- Modal Actions -->
                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-2">
                            <button type="button" @click="showAddModal = false" 
                                    class="px-4 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors font-semibold text-sm cursor-pointer min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-[#8B0000] hover:bg-[#6d0000] text-white rounded-xl transition-all font-semibold text-sm shadow-xs active:scale-[0.98] min-h-[44px] cursor-pointer inline-flex items-center gap-2">
                                <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                                <span>Add to Docket</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Item Modal -->
        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200/80 transform transition-all"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.stop>
                    
                    <div class="px-6 py-4 border-b border-slate-200/80 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center">
                                <i class="fa-solid fa-pen-to-square text-xs" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 font-['Montserrat'] text-base">Edit Agenda Docket Item</h3>
                        </div>
                        <button @click="showEditModal = false" 
                                type="button" 
                                class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 cursor-pointer">
                            <i class="fa-solid fa-xmark text-base" aria-hidden="true"></i>
                        </button>
                    </div>

                    <form :action="editUrl" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <!-- Section -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_section_edit" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Section Category</label>
                            <input type="text" id="agenda_section_edit" name="section" x-model="editItem.section" required 
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium">
                        </div>

                        <!-- Content -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_content_edit" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Item Details & Discussion Notes</label>
                            <textarea id="agenda_content_edit" name="content" x-model="editItem.content" rows="3" 
                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium resize-none"></textarea>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-2">
                            <button type="button" @click="showEditModal = false" 
                                    class="px-4 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors font-semibold text-sm cursor-pointer min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-[#8B0000] hover:bg-[#6d0000] text-white rounded-xl transition-all font-semibold text-sm shadow-xs active:scale-[0.98] min-h-[44px] cursor-pointer inline-flex items-center gap-2">
                                <i class="fa-solid fa-check text-xs" aria-hidden="true"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-admin_layout>
