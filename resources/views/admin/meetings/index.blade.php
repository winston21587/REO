<x-admin_layout>
    <x-slot name="title">Meetings & Agenda | WMSU REO</x-slot>


    <div x-data="{ 
        showScheduleModal: false, 
        showDeleteModal: false, 
        meetingToDelete: '',
        deleteUrl: '',
        confirmDelete(url, title) {
            this.meetingToDelete = title;
            this.deleteUrl = url;
            this.showDeleteModal = true;
        }
    }" class="space-y-6 font-['Inter']">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-['Montserrat'] tracking-tight">Meetings & Agenda</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Schedule committee review sessions and organize meeting agendas.</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showScheduleModal = true" 
                        type="button"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-semibold hover:bg-[#6d0000] transition-all shadow-xs active:scale-[0.98] min-h-[44px] cursor-pointer focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:outline-none">
                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    <span>Schedule Meeting</span>
                </button>
            </div>
        </div>

        <!-- Executive Overview: 3 Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Card 1: Next Meeting Spotlight -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Next Upcoming Session</span>
                        @if($nextMeeting)
                            @if($nextMeeting->agenda_status === 'Final')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70">Finalized</span>
                            @elseif($nextMeeting->agenda_status === 'Provisional')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/70">Circulated</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/70">Draft</span>
                            @endif
                        @endif
                    </div>

                    @if($nextMeeting)
                        <h3 class="font-bold text-slate-900 text-base font-['Montserrat'] line-clamp-1">
                            {{ $nextMeeting->title }}
                        </h3>
                        <div class="space-y-1.5 mt-2 text-xs text-slate-600">
                            <p class="flex items-center gap-2">
                                <i class="fa-regular fa-calendar text-slate-400 w-4" aria-hidden="true"></i>
                                <span class="font-semibold text-slate-800">{{ $nextMeeting->meeting_date->format('l, F j, Y') }}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fa-regular fa-clock text-slate-400 w-4" aria-hidden="true"></i>
                                <span>{{ $nextMeeting->meeting_date->format('h:i A') }}</span>
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-500">{{ $nextMeeting->type }} Session</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-slate-400 w-4" aria-hidden="true"></i>
                                <span class="truncate">{{ $nextMeeting->venue ?? 'WMSU Conference Room' }}</span>
                            </p>
                        </div>
                    @else
                        <div class="py-4 text-center text-slate-400 text-xs">
                            <i class="fa-regular fa-calendar-check text-2xl text-slate-300 block mb-1" aria-hidden="true"></i>
                            No sessions currently scheduled
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100">
                    @if($nextMeeting)
                        <a href="{{ route('admin.meetings.show', $nextMeeting->id) }}" 
                           class="inline-flex items-center justify-between w-full px-3.5 py-2 bg-slate-50 hover:bg-[#8B0000] text-slate-700 hover:text-white rounded-xl text-xs font-bold transition-all cursor-pointer group">
                            <span>Manage Agenda ({{ $nextMeeting->agendaItems->count() }} items)</span>
                            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform" aria-hidden="true"></i>
                        </a>
                    @else
                        <button @click="showScheduleModal = true" 
                                type="button" 
                                class="text-xs font-bold text-[#8B0000] hover:underline cursor-pointer">
                            + Schedule a new meeting
                        </button>
                    @endif
                </div>
            </div>

            <!-- Card 2: Total Scheduled Sessions -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Scheduled Sessions</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-slate-900 font-['Montserrat'] tabular-nums">{{ $upcomingMeetings->count() }}</span>
                        <span class="text-xs text-slate-500">Upcoming {{ \Illuminate\Support\Str::plural('meeting', $upcomingMeetings->count()) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">
                        Ethics committee sessions active in the review schedule.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-600">
                    <i class="fa-solid fa-users-viewfinder text-slate-400" aria-hidden="true"></i>
                    <span>Review panel & deliberative sessions</span>
                </div>
            </div>

            <!-- Card 3: Agenda Readiness -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Agenda Readiness</span>
                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <div class="bg-emerald-50/70 border border-emerald-100 rounded-xl p-2.5">
                            <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Finalized</span>
                            <span class="text-xl font-extrabold text-emerald-700 font-['Montserrat'] tabular-nums">
                                {{ $upcomingMeetings->where('agenda_status', 'Final')->count() }}
                            </span>
                        </div>
                        <div class="bg-amber-50/70 border border-amber-100 rounded-xl p-2.5">
                            <span class="text-xs font-bold text-amber-800 uppercase tracking-wider block">In Progress</span>
                            <span class="text-xl font-extrabold text-amber-700 font-['Montserrat'] tabular-nums">
                                {{ $upcomingMeetings->where('agenda_status', '!=', 'Final')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Draft or circulated to committee</span>
                    <span class="font-bold text-slate-700">{{ $upcomingMeetings->sum(fn($m) => $m->agendaItems->count()) }} Total Topics</span>
                </div>
            </div>
        </div>

        <!-- Meetings Ledger Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <!-- Header Bar -->
            <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-900 font-['Montserrat'] text-base">All Scheduled Meetings</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Click on any meeting to view, edit, or print its agenda.</p>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider bg-white border border-slate-200 px-3 py-1 rounded-lg">
                    {{ $upcomingMeetings->count() }} {{ \Illuminate\Support\Str::plural('Meeting', $upcomingMeetings->count()) }}
                </span>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50/40">
                            <th class="py-3 px-6">Date & Time</th>
                            <th class="py-3 px-6">Meeting Title</th>
                            <th class="py-3 px-6">Type</th>
                            <th class="py-3 px-6">Venue</th>
                            <th class="py-3 px-6">Agenda Status</th>
                            <th class="py-3 px-6 text-center">Topics</th>
                            <th class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($upcomingMeetings as $meeting)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <!-- Date & Time -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-[#8B0000] border border-slate-200/80 flex flex-col items-center justify-center shrink-0">
                                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ $meeting->meeting_date->format('M') }}</span>
                                        <span class="text-lg font-bold leading-none tabular-nums font-['Montserrat']">{{ $meeting->meeting_date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $meeting->meeting_date->format('l') }}</p>
                                        <p class="text-xs text-slate-500 tabular-nums">{{ $meeting->meeting_date->format('h:i A') }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Meeting Title -->
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.meetings.show', $meeting->id) }}" 
                                   class="font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors line-clamp-1 cursor-pointer">
                                    {{ $meeting->title }}
                                </a>
                                <span class="text-xs text-slate-400 block mt-0.5">ID: #MTG-{{ str_pad($meeting->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>

                            <!-- Type -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-100 text-slate-700">
                                    {{ $meeting->type }}
                                </span>
                            </td>

                            <!-- Venue -->
                            <td class="py-4 px-6 text-xs text-slate-600 max-w-[200px] truncate">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-slate-400 text-xs shrink-0" aria-hidden="true"></i>
                                    <span class="truncate">{{ $meeting->venue ?? 'Conference Room' }}</span>
                                </div>
                            </td>

                            <!-- Agenda Status Badge -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($meeting->agenda_status === 'Final')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Finalized
                                    </span>
                                @elseif($meeting->agenda_status === 'Provisional')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/70">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Circulated
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/70">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>

                            <!-- Topics Count -->
                            <td class="py-4 px-6 whitespace-nowrap text-center">
                                <span class="tabular-nums font-semibold text-slate-700 text-xs bg-slate-100 px-2.5 py-1 rounded-md">
                                    {{ $meeting->agendaItems->count() }} {{ \Illuminate\Support\Str::plural('item', $meeting->agendaItems->count()) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.meetings.show', $meeting->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#8B0000]/10 hover:bg-[#8B0000] text-[#8B0000] hover:text-white transition-all text-xs font-bold cursor-pointer" 
                                       title="View and Edit Agenda">
                                        <i class="fa-solid fa-list-check" aria-hidden="true"></i>
                                        <span>Agenda</span>
                                    </a>
                                    <button type="button"
                                            @click="confirmDelete('{{ route('admin.meetings.destroy', $meeting->id) }}', '{{ addslashes($meeting->title) }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" 
                                            title="Delete Meeting">
                                        <i class="fa-solid fa-trash text-xs" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                        <i class="fa-regular fa-calendar-xmark text-xl" aria-hidden="true"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 font-['Montserrat']">No Upcoming Meetings</h3>
                                    <p class="text-xs text-slate-500">There are no ethics review meetings scheduled yet.</p>
                                    <button @click="showScheduleModal = true" 
                                            type="button"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#8B0000] text-white rounded-xl text-xs font-bold hover:bg-[#6d0000] transition-colors cursor-pointer min-h-[40px]">
                                        <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                                        <span>Schedule First Meeting</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Schedule Meeting Modal -->
        <div x-show="showScheduleModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="showScheduleModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
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
                                <i class="fa-regular fa-calendar-plus text-sm" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 font-['Montserrat'] text-base">Schedule Committee Meeting</h3>
                        </div>
                        <button @click="showScheduleModal = false" 
                                type="button"
                                class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 cursor-pointer">
                            <i class="fa-solid fa-xmark text-base" aria-hidden="true"></i>
                        </button>
                    </div>
                    
                    <form action="{{ route('admin.meetings.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        
                        <!-- Meeting Title -->
                        <div class="space-y-1.5 text-left">
                            <label for="meeting_title" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Meeting Title</label>
                            <input type="text" id="meeting_title" name="title" required 
                                   placeholder="e.g., Committee Regular Review Session" 
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium">
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Meeting Type -->
                            <div class="space-y-1.5 text-left">
                                <label for="meeting_type" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Meeting Type</label>
                                <div class="relative">
                                    <select id="meeting_type" name="type" required 
                                            class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all appearance-none cursor-pointer text-slate-900 font-medium pr-8">
                                        <option value="Regular">Regular Session</option>
                                        <option value="Special">Special Session</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Date Picker -->
                            <div class="space-y-1.5 text-left">
                                <label for="meeting_date_picker" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Date & Time</label>
                                <input type="datetime-local" id="meeting_date_picker" name="meeting_date" required 
                                       min="{{ now()->format('Y-m-d\TH:i') }}"
                                       class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all text-slate-900 font-medium cursor-pointer accent-[#8B0000]"
                                       onclick="this.showPicker && this.showPicker()">
                            </div>
                        </div>

                        <!-- Venue -->
                        <div class="space-y-1.5 text-left">
                            <label for="meeting_venue" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Meeting Location / Venue</label>
                            <input type="text" id="meeting_venue" name="venue" 
                                   placeholder="e.g., WMSU Executive Conference Hall or Online (Zoom)" 
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium">
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-500 border border-slate-100 flex items-start gap-2">
                            <i class="fa-solid fa-circle-info text-slate-400 mt-0.5" aria-hidden="true"></i>
                            <span>Standard agenda items (Call to Order, Quorum, Minutes, Protocol Review, Adjournment) will be automatically initialized.</span>
                        </div>

                        <!-- Modal Actions -->
                        <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="showScheduleModal = false" 
                                    class="px-4 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors font-semibold text-sm cursor-pointer min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-[#8B0000] hover:bg-[#6d0000] text-white rounded-xl transition-all font-semibold text-sm shadow-xs active:scale-[0.98] min-h-[44px] cursor-pointer inline-flex items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-xs" aria-hidden="true"></i>
                                <span>Schedule Meeting</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="showDeleteModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.stop>
                    
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-trash-can text-xl" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 font-['Montserrat'] mb-1">Delete Meeting?</h3>
                        <p class="text-xs text-slate-500 mb-5 leading-relaxed">
                            Are you sure you want to delete <span class="font-bold text-slate-800" x-text="meetingToDelete"></span>? Its agenda topics will be permanently removed.
                        </p>
                        
                        <form :action="deleteUrl" method="POST" class="flex items-center justify-center gap-3">
                            @csrf
                            @method('DELETE')
                            <button type="button" @click="showDeleteModal = false" 
                                    class="px-4 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors font-semibold text-sm cursor-pointer min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl transition-all shadow-xs font-semibold text-sm active:scale-[0.98] min-h-[44px] cursor-pointer">
                                Confirm Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin_layout>
