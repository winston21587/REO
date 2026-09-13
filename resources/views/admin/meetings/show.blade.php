<x-admin_layout>
    <x-slot name="title">Agenda: {{ $meeting->title }} | WMSU REO</x-slot>

    <div x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        editItem: { id: '', section: '', content: '', order: 1, protocol_id: '' },
        editUrl: '',
        openEditModal(item, url) {
            this.editItem = { ...item };
            this.editUrl = url;
            this.showEditModal = true;
        }
    }" class="space-y-6 font-['Inter']">
    
        <!-- Top Navigation & Actions Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 print:hidden">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                    <a href="{{ route('admin.meetings') }}" class="hover:text-[#8B0000] transition-colors cursor-pointer inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-[10px]" aria-hidden="true"></i>
                        <span>Meetings</span>
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-700">Agenda Details</span>
                </nav>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-['Montserrat'] tracking-tight">
                        {{ $meeting->title }}
                    </h1>
                    <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700">
                        {{ $meeting->type }} Session
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    {{ $meeting->meeting_date->format('l, F j, Y \a\t h:i A') }} • {{ $meeting->venue ?? 'WMSU Executive Conference Hall' }}
                </p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex items-center gap-2.5">
                <button onclick="window.print()" 
                        type="button" 
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-semibold shadow-xs transition-all active:scale-[0.98] min-h-[44px] cursor-pointer"
                        title="Print FR.016 Meeting Agenda">
                    <i class="fa-solid fa-print text-xs text-slate-500" aria-hidden="true"></i>
                    <span>Print Agenda (FR.016)</span>
                </button>
                <button @click="showAddModal = true" 
                        type="button" 
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-semibold hover:bg-[#6d0000] transition-all shadow-xs active:scale-[0.98] min-h-[44px] cursor-pointer">
                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    <span>Add Topic</span>
                </button>
            </div>
        </div>

        <!-- Official Letterhead for Print (Hidden on screen) -->
        <div class="hidden print:block text-center border-b-2 border-slate-900 pb-4 mb-6">
            <h3 class="text-xs uppercase font-bold tracking-widest text-slate-600">Western Mindanao State University</h3>
            <h1 class="text-xl font-extrabold uppercase font-['Montserrat'] text-slate-900">Research Ethics Oversight Committee (REOC)</h1>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">WMSU REOC SOP 18 / Form FR.016 - Meeting Agenda</p>
            <h2 class="text-sm font-bold text-slate-800 mt-3">{{ $meeting->title }} ({{ $meeting->type }} Review Session)</h2>
            <p class="text-xs text-slate-600 mt-0.5">
                Date & Time: {{ $meeting->meeting_date->format('F j, Y, h:i A') }} | Venue: {{ $meeting->venue ?? 'WMSU Conference Room' }}
            </p>
            <p class="text-xs font-bold text-slate-700 mt-1 uppercase">
                Status: {{ $meeting->agenda_status === 'Final' ? 'Official Final Agenda' : ($meeting->agenda_status === 'Provisional' ? 'Provisional Agenda (Circulated to Committee)' : 'Draft Agenda') }}
            </p>
        </div>

        <!-- Agenda Lifecycle Stepper Banner (Screen only) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs print:hidden">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Stepper Progress Display -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">SOP 18 Agenda Progress Lifecycle</span>
                        <span class="text-xs text-slate-500 font-medium">Notice of Meeting Distribution: 1 week before session</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 sm:gap-4 text-center">
                        <!-- Step 1: Draft -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold mb-1.5 transition-colors
                                @if($meeting->agenda_status === 'Draft') bg-amber-500 text-white ring-4 ring-amber-100
                                @else bg-emerald-500 text-white @endif">
                                @if($meeting->agenda_status !== 'Draft')
                                    <i class="fa-solid fa-check text-[10px]" aria-hidden="true"></i>
                                @else
                                    1
                                @endif
                            </div>
                            <span class="text-xs font-bold @if($meeting->agenda_status === 'Draft') text-slate-900 @else text-slate-500 @endif">1. Drafting</span>
                            <span class="text-[10px] text-slate-400 hidden sm:inline">Secretary prepares docket</span>
                        </div>

                        <!-- Step 2: Circulated (Provisional) -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold mb-1.5 transition-colors
                                @if($meeting->agenda_status === 'Provisional') bg-blue-600 text-white ring-4 ring-blue-100
                                @elseif($meeting->agenda_status === 'Final') bg-emerald-500 text-white
                                @else bg-slate-100 text-slate-400 border border-slate-200 @endif">
                                @if($meeting->agenda_status === 'Final')
                                    <i class="fa-solid fa-check text-[10px]" aria-hidden="true"></i>
                                @else
                                    2
                                @endif
                            </div>
                            <span class="text-xs font-bold @if($meeting->agenda_status === 'Provisional') text-slate-900 @else text-slate-500 @endif">2. Circulated</span>
                            <span class="text-[10px] text-slate-400 hidden sm:inline">Sent to committee members</span>
                        </div>

                        <!-- Step 3: Finalized -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold mb-1.5 transition-colors
                                @if($meeting->agenda_status === 'Final') bg-emerald-600 text-white ring-4 ring-emerald-100
                                @else bg-slate-100 text-slate-400 border border-slate-200 @endif">
                                @if($meeting->agenda_status === 'Final')
                                    <i class="fa-solid fa-lock text-[10px]" aria-hidden="true"></i>
                                @else
                                    3
                                @endif
                            </div>
                            <span class="text-xs font-bold @if($meeting->agenda_status === 'Final') text-slate-900 @else text-slate-500 @endif">3. Approved</span>
                            <span class="text-[10px] text-slate-400 hidden sm:inline">Adopted at session</span>
                        </div>
                    </div>
                </div>

                <!-- Workflow Action Button -->
                <div class="lg:border-l lg:border-slate-100 lg:pl-6 flex flex-col justify-center gap-2">
                    @if($meeting->agenda_status === 'Draft')
                        <form action="{{ route('admin.meetings.status', $meeting->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="agenda_status" value="Provisional">
                            <button type="submit" 
                                    class="inline-flex items-center justify-center gap-2 w-full px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-xs transition-all active:scale-[0.98] min-h-[44px] cursor-pointer">
                                <i class="fa-solid fa-paper-plane text-xs" aria-hidden="true"></i>
                                <span>Circulate to Committee</span>
                            </button>
                        </form>
                        <p class="text-[11px] text-slate-400 text-center">Marks agenda as provisional notice for member review.</p>
                    @elseif($meeting->agenda_status === 'Provisional')
                        <div class="flex flex-wrap items-center gap-2">
                            <form action="{{ route('admin.meetings.status', $meeting->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="agenda_status" value="Final">
                                <button type="submit" 
                                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-xs transition-all active:scale-[0.98] min-h-[44px] cursor-pointer">
                                    <i class="fa-solid fa-check-double text-xs" aria-hidden="true"></i>
                                    <span>Adopt & Finalize Agenda</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.meetings.status', $meeting->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="agenda_status" value="Draft">
                                <button type="submit" 
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all min-h-[44px] cursor-pointer">
                                    <i class="fa-solid fa-arrow-rotate-left text-xs" aria-hidden="true"></i>
                                    <span>Revert to Draft</span>
                                </button>
                            </form>
                        </div>
                        <p class="text-[11px] text-slate-400 text-center">Adopted by committee quorum at Call to Order.</p>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                <i class="fa-solid fa-lock text-xs" aria-hidden="true"></i>
                                <span>Official Approved Docket</span>
                            </span>
                            <form action="{{ route('admin.meetings.status', $meeting->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="agenda_status" value="Draft">
                                <button type="submit" 
                                        class="text-xs font-bold text-slate-500 hover:text-[#8B0000] hover:underline px-2 py-1 cursor-pointer">
                                    Unlock / Edit
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Meeting Quick Specs & SOP Compliance Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Session Schedule</span>
                <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $meeting->meeting_date->format('M d, Y') }}</span>
                <span class="text-slate-500">{{ $meeting->meeting_date->format('h:i A') }} ({{ $meeting->type }})</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Location / Venue</span>
                <span class="font-bold text-slate-800 text-sm mt-0.5 block truncate">{{ $meeting->venue ?? 'WMSU Conference Room' }}</span>
                <span class="text-slate-500">In-person / Zoom Hybrid</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Quorum Standard (SOP 17)</span>
                <span class="font-bold text-slate-800 text-sm mt-0.5 block">Min. 5 Members</span>
                <span class="text-slate-500">Includes Primary & Lay Members</span>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">COI & Ethics Directive</span>
                <span class="font-bold text-slate-800 text-sm mt-0.5 block">COI Declaration</span>
                <span class="text-slate-500">Mandatory Recusal during voting</span>
            </div>
        </div>

        <!-- Attendees & Quorum Roster (SOP 17 & SOP 19) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden print:border-slate-900">
            <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-slate-900 font-['Montserrat'] text-base">Committee Attendance & Quorum Roster</h2>
                        @php
                            $confirmedCount = $meeting->attendees->where('status', 'Confirmed')->count();
                            $isQuorumMet = $confirmedCount >= 5;
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $isQuorumMet ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $confirmedCount }}/5 Confirmed ({{ $isQuorumMet ? 'Quorum Met' : 'Pending Quorum' }})
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">SOP 17 Notice of Meeting & Attendance confirmation tracking for panel deliberation.</p>
                </div>
            </div>

            <!-- Attendance List -->
            <div class="p-4 sm:p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @forelse($meeting->attendees as $attendee)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-2 text-xs">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-slate-900 truncate">
                                        {{ $attendee->user->first_name }} {{ $attendee->user->last_name }}
                                    </span>
                                    <span class="text-[10px] font-semibold text-slate-400 capitalize">({{ $attendee->role }})</span>
                                </div>
                                <span class="text-[11px] text-slate-500 block truncate">{{ $attendee->user->email }}</span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $attendee->status === 'Confirmed' ? 'bg-emerald-100 text-emerald-800' : 
                                       ($attendee->status === 'Regrets' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $attendee->status }}
                                </span>
                                <form action="{{ route('admin.meetings.attendees.destroy', [$meeting->id, $attendee->id]) }}" method="POST" onsubmit="return confirm('Remove attendee?');" class="print:hidden">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-1 cursor-pointer" title="Remove">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-4 text-center text-xs text-slate-400 border border-dashed border-slate-200 rounded-xl">
                            No committee members formally invited yet. Use the invite form below to notify reviewers.
                        </div>
                    @endforelse
                </div>

                <!-- Invite Member Form (Screen only) -->
                @if($availableReviewers->isNotEmpty())
                    <form action="{{ route('admin.meetings.attendees.store', $meeting->id) }}" method="POST" class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2.5 print:hidden">
                        @csrf
                        <div class="flex-1 min-w-[200px]">
                            <select name="user_id" required class="w-full text-xs rounded-lg border-slate-200 bg-white py-2 px-3 focus:ring-[#8B0000] focus:border-[#8B0000]">
                                <option value="">Select Committee Member / Reviewer...</option>
                                @foreach($availableReviewers as $rev)
                                    @if(!$meeting->attendees->contains('user_id', $rev->id))
                                        <option value="{{ $rev->id }}">{{ $rev->first_name }} {{ $rev->last_name }} ({{ $rev->email }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="w-36">
                            <select name="role" class="w-full text-xs rounded-lg border-slate-200 bg-white py-2 px-3 focus:ring-[#8B0000] focus:border-[#8B0000]">
                                <option value="reviewer">Reviewer</option>
                                <option value="chair">Panel Chair</option>
                                <option value="consultant">Independent Consultant</option>
                                <option value="secretariat">Secretariat</option>
                            </select>
                        </div>
                        <button type="submit" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-[#8B0000] text-white hover:bg-[#6d0000] transition-colors cursor-pointer shrink-0">
                            <i class="fa-solid fa-user-plus mr-1 text-[11px]"></i> Invite Member
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Agenda Topics Ordered List -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-900 font-['Montserrat'] text-base">Order of Business & Deliberation Docket</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Official chronological items per WMSU REOC SOP 18 (FR.016).</p>
                </div>
                <div class="flex items-center gap-2 print:hidden">
                    <button @click="showAddModal = true" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#8B0000] text-white rounded-lg text-xs font-bold hover:bg-[#6d0000] transition-colors cursor-pointer">
                        <i class="fa-solid fa-plus text-[10px]" aria-hidden="true"></i>
                        <span>Add Item</span>
                    </button>
                </div>
            </div>

            <!-- Items List -->
            <div class="divide-y divide-slate-100">
                @forelse($meeting->agendaItems as $item)
                @php
                    $isProtocolItem = !empty($item->protocol_id) || str_contains(strtolower($item->section), 'protocol') || str_contains(strtolower($item->content ?? ''), 'protocol');
                @endphp
                <div class="p-4 sm:p-5 hover:bg-slate-50/70 transition-colors group">
                    <div class="flex items-start gap-4">
                        <!-- Order Number Badge -->
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-800 border border-slate-200 font-bold text-xs flex items-center justify-center shrink-0 tabular-nums">
                            {{ $item->order }}
                        </div>

                        <!-- Content Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-[#8B0000]">
                                    {{ $item->section }}
                                </span>
                                @if($item->protocol)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $item->protocol->Review_Type }} Review
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                        @if($item->protocol->Status === 'Approved') bg-emerald-50 text-emerald-700
                                        @elseif($item->protocol->Status === 'Panel Deliberation') bg-indigo-50 text-indigo-700
                                        @else bg-amber-50 text-amber-700 @endif">
                                        {{ $item->protocol->Status }}
                                    </span>
                                @endif
                            </div>

                            @if($item->content)
                                <p class="text-sm font-semibold text-slate-900 mt-1 leading-snug">
                                    {{ $item->content }}
                                </p>
                            @endif

                            @if($item->protocol)
                                <div class="mt-2.5 p-3 bg-slate-50 border border-slate-200/80 rounded-xl text-xs flex items-start gap-3">
                                    <i class="fa-solid fa-file-shield text-[#8B0000] mt-0.5 text-sm" aria-hidden="true"></i>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="font-bold text-slate-900 leading-snug">{{ $item->protocol->Study_Protocol_title }}</span>
                                            <span class="font-mono text-[10px] text-slate-400 shrink-0">#{{ $item->protocol->id }}</span>
                                        </div>
                                        <div class="text-[11px] text-slate-600 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <span><strong class="text-slate-700">Category:</strong> {{ $item->protocol->Research_Category }}</span>
                                            <span>•</span>
                                            <span><strong class="text-slate-700">Researcher:</strong> {{ $item->protocol->researcher?->user ? ($item->protocol->researcher->user->first_name . ' ' . $item->protocol->researcher->user->last_name) : 'N/A' }}</span>
                                            @if($item->protocol->reviewers->isNotEmpty())
                                                <span>•</span>
                                                <span class="text-brand-primary font-semibold">
                                                    <strong>Assigned Reviewers:</strong> {{ $item->protocol->reviewers->map(fn($r) => $r->first_name . ' ' . $r->last_name)->join(', ') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions (Hidden on print) -->
                        <div class="flex items-center gap-1 shrink-0 print:hidden opacity-80 group-hover:opacity-100 transition-opacity">
                            <button type="button"
                                    data-item="{{ json_encode($item) }}"
                                    data-url="{{ route('admin.agenda.update', $item->id) }}"
                                    @click="openEditModal(JSON.parse($el.dataset.item), $el.dataset.url)"
                                    class="w-8 h-8 rounded-lg text-slate-400 hover:text-[#8B0000] hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer" 
                                    title="Edit Item">
                                <i class="fa-solid fa-pen-to-square text-xs pointer-events-none" aria-hidden="true"></i>
                            </button>
                            <form action="{{ route('admin.agenda.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this agenda topic from the docket?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer" 
                                        title="Remove Item">
                                    <i class="fa-solid fa-trash text-xs" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-slate-500">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <i class="fa-regular fa-folder-open text-xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">No agenda topics yet</h3>
                    <p class="text-xs text-slate-400 mt-1">Start by adding standard committee discussion topics.</p>
                    <button @click="showAddModal = true" 
                            type="button" 
                            class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 bg-[#8B0000] text-white rounded-xl text-xs font-bold hover:bg-[#6d0000] transition-colors cursor-pointer">
                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                        <span>Add Topic</span>
                    </button>
                </div>
                @endforelse
            </div>

            <!-- Bottom Add Row (Screen only) -->
            <div class="p-4 bg-slate-50/50 border-t border-slate-100 text-center print:hidden">
                <button @click="showAddModal = true" 
                        type="button" 
                        class="w-full py-3 border-2 border-dashed border-slate-200 hover:border-[#8B0000] text-slate-500 hover:text-[#8B0000] rounded-xl transition-all font-semibold text-xs uppercase tracking-wider flex items-center justify-center gap-2 cursor-pointer bg-white hover:bg-red-50/10 min-h-[44px]">
                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    <span>Add Another Agenda Item</span>
                </button>
            </div>
        </div>

        <!-- Print-Only Signatures Block (Form FR.016 requirement) -->
        <div class="hidden print:block pt-12 mt-8 border-t border-slate-300">
            <div class="grid grid-cols-2 gap-12 text-center text-xs text-slate-700">
                <div>
                    <div class="border-b border-slate-800 pb-1 w-3/4 mx-auto font-bold uppercase text-slate-900">
                        REOC Committee Secretary
                    </div>
                    <span class="block mt-1">Prepared by</span>
                </div>
                <div>
                    <div class="border-b border-slate-800 pb-1 w-3/4 mx-auto font-bold uppercase text-slate-900">
                        REOC Committee Chair
                    </div>
                    <span class="block mt-1">Approved for Deliberation</span>
                </div>
            </div>
        </div>

        <!-- Add Topic Modal -->
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
                    
                    <div class="px-6 py-4 border-b border-slate-200/80 flex items-center justify-between bg-slate-50/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center">
                                <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 font-['Montserrat'] text-base">Add Agenda Topic</h3>
                        </div>
                        <button @click="showAddModal = false" 
                                type="button" 
                                class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 cursor-pointer">
                            <i class="fa-solid fa-xmark text-base" aria-hidden="true"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.meetings.agenda.store', $meeting->id) }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        
                        <!-- Section Category -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_section_add" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">SOP 18 Section Category</label>
                            <input type="text" id="agenda_section_add" name="section" required 
                                   list="section-presets"
                                   placeholder="e.g. Preliminary Matters, New Business: Protocol Review, Other Matters" 
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium">
                            <datalist id="section-presets">
                                <option value="Preliminary Matters">
                                <option value="Business Arising">
                                <option value="New Business: Protocol Review">
                                <option value="New Business: Resubmissions">
                                <option value="New Business: Post-Approval Submissions">
                                <option value="Report on Expedited & Exempt Decisions">
                                <option value="Other Matters">
                                <option value="Closing">
                            </datalist>
                        </div>

                        <!-- Link to System Protocol (Optional) -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_protocol_add" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                Link Research Protocol <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <select id="agenda_protocol_add" name="protocol_id" 
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all cursor-pointer text-slate-900 font-medium">
                                <option value="">-- No protocol linked (General meeting item) --</option>
                                @foreach($availableProtocols as $p)
                                    <option value="{{ $p->id }}">
                                        #{{ $p->id }} - {{ \Illuminate\Support\Str::limit($p->Study_Protocol_title, 45) }} ({{ $p->Status }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Item Content -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_content_add" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Discussion Notes / Title</label>
                            <textarea id="agenda_content_add" name="content" rows="3" 
                                      placeholder="Summary of matters for deliberation or action..." 
                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium resize-none"></textarea>
                        </div>

                        <!-- Sequence Order -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_order_add" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Order Sequence</label>
                            <input type="number" id="agenda_order_add" name="order" value="{{ $meeting->agendaItems->count() + 1 }}" required 
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium tabular-nums">
                        </div>

                        <!-- Actions -->
                        <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
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

        <!-- Edit Topic Modal -->
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
                    
                    <div class="px-6 py-4 border-b border-slate-200/80 flex items-center justify-between bg-slate-50/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center">
                                <i class="fa-solid fa-pen-to-square text-xs" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 font-['Montserrat'] text-base">Edit Agenda Topic</h3>
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
                                   list="section-presets-edit"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium">
                            <datalist id="section-presets-edit">
                                <option value="Preliminary Matters">
                                <option value="Business Arising">
                                <option value="New Business: Protocol Review">
                                <option value="New Business: Resubmissions">
                                <option value="New Business: Post-Approval Submissions">
                                <option value="Report on Expedited & Exempt Decisions">
                                <option value="Other Matters">
                                <option value="Closing">
                            </datalist>
                        </div>

                        <!-- Link to System Protocol (Optional) -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_protocol_edit" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                Link Research Protocol <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <select id="agenda_protocol_edit" name="protocol_id" x-model="editItem.protocol_id"
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all cursor-pointer text-slate-900 font-medium">
                                <option value="">-- No protocol linked (General item) --</option>
                                @foreach($availableProtocols as $p)
                                    <option value="{{ $p->id }}">
                                        #{{ $p->id }} - {{ \Illuminate\Support\Str::limit($p->Study_Protocol_title, 45) }} ({{ $p->Status }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Content -->
                        <div class="space-y-1.5 text-left">
                            <label for="agenda_content_edit" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Discussion Notes / Title</label>
                            <textarea id="agenda_content_edit" name="content" x-model="editItem.content" rows="3" 
                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium resize-none"></textarea>
                        </div>

                        <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
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
