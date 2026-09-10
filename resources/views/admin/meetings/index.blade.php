<x-admin_layout>
    <x-slot name="title">Meetings & Agenda | WMSU REO</x-slot>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

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
    }" class="space-y-8 font-['Inter']">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/70">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-['Montserrat'] tracking-tight">Meetings & Agenda</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Institutional Ethics Review Sessions & Agenda Ledger (SOP 17, 18, 19)</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showScheduleModal = true" 
                        type="button"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#8B0000] text-white rounded-xl text-sm font-semibold hover:bg-[#6d0000] transition-all shadow-xs active:scale-[0.98] min-h-[44px] cursor-pointer focus-visible:ring-2 focus-visible:ring-[#8B0000] focus-visible:outline-none">
                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                    <span>Schedule Meeting</span>
                </button>
            </div>
        </div>

        <!-- Next Committee Meeting Spotlight -->
        @if($nextMeeting)
        <div class="bg-[#1a0505] rounded-2xl border border-[#8B0000]/30 shadow-xs p-6 sm:p-8 text-white relative overflow-hidden">
            <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <!-- Left: Meeting Metadata -->
                <div class="space-y-3 flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 text-slate-300 text-xs font-semibold uppercase tracking-wider">
                        <span class="text-[#fca5a5] font-bold">Next Committee Session</span>
                        <span class="text-slate-500">•</span>
                        <span>{{ $nextMeeting->meeting_date->format('l, F j, Y') }}</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-bold font-['Montserrat'] text-white tracking-tight break-words">
                        {{ $nextMeeting->title }}
                    </h2>

                    <div class="flex flex-wrap items-center gap-y-2 gap-x-5 text-sm text-slate-300">
                        <div class="inline-flex items-center gap-2">
                            <i class="fa-regular fa-clock text-slate-400 w-4 text-center" aria-hidden="true"></i>
                            <span class="tabular-nums font-medium">{{ $nextMeeting->meeting_date->format('h:i A') }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-slate-400 w-4 text-center" aria-hidden="true"></i>
                            <span class="font-medium">{{ $nextMeeting->venue ?? 'WMSU Executive Conference Hall' }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2">
                            <i class="fa-solid fa-tag text-slate-400 w-4 text-center" aria-hidden="true"></i>
                            <span class="font-medium">{{ $nextMeeting->type }} Session</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5">
                            <span class="text-slate-400 text-xs">Agenda:</span>
                            <span class="@if($nextMeeting->agenda_status === 'Final') text-emerald-400 @elseif($nextMeeting->agenda_status === 'Provisional') text-amber-400 @else text-slate-300 @endif text-xs font-bold uppercase tracking-wider">
                                {{ $nextMeeting->agenda_status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right: Countdown & Quick Action -->
                <div x-data="{
                    days: '00',
                    hours: '00',
                    minutes: '00',
                    seconds: '00',
                    target: new Date('{{ $nextMeeting->meeting_date->toIso8601String() }}'),
                    start() {
                        this.update();
                        setInterval(() => this.update(), 1000);
                    },
                    update() {
                        const now = new Date().getTime();
                        const distance = this.target - now;
                        if (distance < 0) {
                            this.days = '00'; this.hours = '00'; this.minutes = '00'; this.seconds = '00';
                            return;
                        }
                        this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                        this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                        this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                    }
                }" x-init="start()" class="flex flex-col gap-3 w-full lg:w-auto lg:min-w-[280px]">
                    
                    <!-- Countdown Display Grid -->
                    <div class="grid grid-cols-4 gap-2 text-center bg-white/5 border border-white/10 rounded-xl p-3">
                        <div class="flex flex-col">
                            <span x-text="days" class="font-mono text-2xl font-bold text-white tabular-nums">00</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Days</span>
                        </div>
                        <div class="flex flex-col">
                            <span x-text="hours" class="font-mono text-2xl font-bold text-white tabular-nums">00</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Hrs</span>
                        </div>
                        <div class="flex flex-col">
                            <span x-text="minutes" class="font-mono text-2xl font-bold text-white tabular-nums">00</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mins</span>
                        </div>
                        <div class="flex flex-col">
                            <span x-text="seconds" class="font-mono text-2xl font-bold text-white tabular-nums">00</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Secs</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('admin.meetings.show', $nextMeeting->id) }}" 
                       class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white text-slate-900 rounded-xl hover:bg-slate-100 transition-all font-bold text-sm shadow-xs active:scale-[0.98] min-h-[44px] cursor-pointer">
                        <i class="fa-solid fa-list-check text-[#8B0000]" aria-hidden="true"></i>
                        <span>Prepare Docket & Agenda</span>
                        <i class="fa-solid fa-arrow-right text-xs text-slate-400 ml-1" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-8 text-center">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <i class="fa-regular fa-calendar-xmark text-xl" aria-hidden="true"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 font-['Montserrat']">No Upcoming Committee Meetings</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">There are currently no regular or special committee review sessions on the calendar.</p>
            <button @click="showScheduleModal = true" 
                    type="button"
                    class="inline-flex items-center justify-center gap-2 mt-4 px-4 py-2 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors cursor-pointer min-h-[38px]">
                <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                <span>Schedule First Session</span>
            </button>
        </div>
        @endif

        <!-- Scheduled Committee Meetings Ledger -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <!-- Table Header Bar -->
            <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 font-['Montserrat'] text-base">Scheduled Committee Sessions</h3>
                    <p class="text-xs text-slate-500 mt-0.5">SOP 17 Protocol Deliberations & Docket Assemblies</p>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    {{ $upcomingMeetings->count() }} {{ \Illuminate\Support\Str::plural('Session', $upcomingMeetings->count()) }}
                </span>
            </div>

            <!-- Desktop Single-Pane Ledger (Hidden on < 1024px) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50/70">
                            <th class="py-3 px-6">Date & Time</th>
                            <th class="py-3 px-6">Session Title</th>
                            <th class="py-3 px-6">Session Type</th>
                            <th class="py-3 px-6">Venue</th>
                            <th class="py-3 px-6">Agenda Status</th>
                            <th class="py-3 px-6 text-center">Items</th>
                            <th class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($upcomingMeetings as $meeting)
                        <tr class="hover:bg-slate-50/70 transition-colors group">
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

                            <!-- Title -->
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.meetings.show', $meeting->id) }}" 
                                   class="font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors line-clamp-1 cursor-pointer">
                                    {{ $meeting->title }}
                                </a>
                                <span class="text-xs text-slate-400 block mt-0.5">ID: #MTG-{{ str_pad($meeting->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>

                            <!-- Type -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="text-xs font-semibold text-slate-700">
                                    {{ $meeting->type }}
                                </span>
                            </td>

                            <!-- Venue -->
                            <td class="py-4 px-6 whitespace-nowrap text-xs text-slate-600">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-slate-400 text-xs" aria-hidden="true"></i>
                                    <span>{{ $meeting->venue ?? 'Not Specified' }}</span>
                                </div>
                            </td>

                            <!-- Agenda Status (High-contrast bold uppercase tracking text, zero pill background, zero dot) -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="@if($meeting->agenda_status === 'Final') text-emerald-600 @elseif($meeting->agenda_status === 'Provisional') text-blue-600 @else text-amber-600 @endif text-xs font-bold uppercase tracking-wider">
                                    {{ $meeting->agenda_status }}
                                </span>
                            </td>

                            <!-- Items -->
                            <td class="py-4 px-6 whitespace-nowrap text-center">
                                <span class="tabular-nums font-semibold text-slate-700 text-xs">
                                    {{ $meeting->agendaItems->count() }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.meetings.show', $meeting->id) }}" 
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-500 hover:text-[#8B0000] hover:bg-slate-100 transition-colors cursor-pointer" 
                                       title="Manage Agenda">
                                        <i class="fa-solid fa-pen-to-square text-sm" aria-hidden="true"></i>
                                    </a>
                                    <button type="button"
                                            @click="confirmDelete('{{ route('admin.meetings.destroy', $meeting->id) }}', '{{ addslashes($meeting->title) }}')"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" 
                                            title="Delete Session">
                                        <i class="fa-solid fa-trash text-sm" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                <p class="text-sm">No scheduled committee sessions found in the ledger.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile / Tablet Responsive Card List (Visible on < 1024px) -->
            <div class="lg:hidden divide-y divide-slate-100">
                @forelse($upcomingMeetings as $meeting)
                <div class="p-4 sm:p-5 space-y-3 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 text-[#8B0000] border border-slate-200/80 flex flex-col items-center justify-center shrink-0">
                                <span class="text-[10px] font-bold uppercase tracking-wider">{{ $meeting->meeting_date->format('M') }}</span>
                                <span class="text-base font-bold leading-none tabular-nums font-['Montserrat']">{{ $meeting->meeting_date->format('d') }}</span>
                            </div>
                            <div>
                                <a href="{{ route('admin.meetings.show', $meeting->id) }}" class="font-bold text-slate-900 hover:text-[#8B0000] text-sm block">
                                    {{ $meeting->title }}
                                </a>
                                <p class="text-xs text-slate-500 mt-0.5 tabular-nums">
                                    {{ $meeting->meeting_date->format('l, h:i A') }} • {{ $meeting->type }}
                                </p>
                            </div>
                        </div>
                        <span class="@if($meeting->agenda_status === 'Final') text-emerald-600 @elseif($meeting->agenda_status === 'Provisional') text-blue-600 @else text-amber-600 @endif text-xs font-bold uppercase tracking-wider shrink-0">
                            {{ $meeting->agenda_status }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                        <span class="truncate max-w-[200px]">
                            <i class="fa-solid fa-location-dot text-slate-400 mr-1" aria-hidden="true"></i>
                            {{ $meeting->venue ?? 'Not Specified' }}
                        </span>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="font-medium text-slate-600">{{ $meeting->agendaItems->count() }} Items</span>
                            <a href="{{ route('admin.meetings.show', $meeting->id) }}" class="text-[#8B0000] font-bold hover:underline">Manage</a>
                            <button type="button" 
                                    @click="confirmDelete('{{ route('admin.meetings.destroy', $meeting->id) }}', '{{ addslashes($meeting->title) }}')"
                                    class="text-rose-600 font-bold hover:underline cursor-pointer">Delete</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-500 text-sm">
                    No scheduled committee sessions found in the ledger.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Protocol Review Pipeline & Deliberations (2-Column Operational Grid) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Upcoming Protocol Appointments / Deliberations -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 font-['Montserrat'] text-sm sm:text-base">Upcoming Protocol Deliberations</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Scheduled Panel Consultations & Official Document Pickups</p>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pipeline</span>
                </div>
                
                <div class="divide-y divide-slate-100 text-sm">
                    @forelse($upcomingAppointments as $appointment)
                    @php
                        $isPickup = $appointment->stage === 'Certificate Pickup';
                    @endphp
                    <div class="p-4 hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-start gap-3.5">
                            <!-- Date Stamp -->
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 border border-slate-200/80 flex flex-col items-center justify-center shrink-0">
                                <span class="text-[9px] font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M') }}</span>
                                <span class="text-sm font-bold leading-none tabular-nums font-['Montserrat']">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d') }}</span>
                            </div>
                            <!-- Protocol Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-slate-900 line-clamp-1 text-sm">
                                    {{ $appointment->research->Study_Protocol_title ?? 'Protocol Submission' }}
                                </h4>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="@if($isPickup) text-emerald-600 @else text-blue-600 @endif text-xs font-bold uppercase tracking-wider">
                                        {{ $appointment->stage }}
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500 text-sm">
                        <i class="fa-regular fa-calendar-check text-slate-300 text-xl block mb-2" aria-hidden="true"></i>
                        No upcoming protocol appointments or deliberations currently scheduled.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Protocol Workflow Milestones -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-4 sm:px-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 font-['Montserrat'] text-sm sm:text-base">Recent Protocol Activity</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Chronological Audit Log of Review Status Changes</p>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Activity</span>
                </div>
                
                <div class="divide-y divide-slate-100 text-sm">
                    @forelse($recentActivities as $activity)
                    <div class="p-4 hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-slate-900 line-clamp-1 text-sm">
                                    {{ $activity->Study_Protocol_title }}
                                </h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <!-- High-Contrast Status Typography (Plain English, Zero Pill Background, Zero Dot) -->
                                    <span class="@if($activity->Status === 'Approved') text-emerald-600 @elseif(in_array($activity->Status, ['Modifications Required', 'Waiting for Revision'])) text-rose-600 @elseif($activity->Status === 'For Initial Review') text-amber-600 @else text-indigo-600 @endif text-xs font-bold uppercase tracking-wider">
                                        @if($activity->Status === 'For Initial Review')
                                            TRIAGE
                                        @elseif($activity->Status === 'Modifications Required' || $activity->Status === 'Waiting for Revision')
                                            ACTION REQUIRED
                                        @elseif($activity->Status === 'Approved')
                                            APPROVED
                                        @else
                                            {{ strtoupper($activity->Status) }}
                                        @endif
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-xs text-slate-500 tabular-nums">
                                        {{ $activity->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500 text-sm">
                        <i class="fa-regular fa-clock text-slate-300 text-xl block mb-2" aria-hidden="true"></i>
                        No recent protocol review milestones recorded.
                    </div>
                    @endforelse
                </div>
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
            
            <!-- Scrim -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="showScheduleModal = false"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.stop>
                    
                    <!-- Clean Institutional Header -->
                    <div class="px-6 py-4 border-b border-slate-200/80 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center">
                                <i class="fa-regular fa-calendar-plus text-sm" aria-hidden="true"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 font-['Montserrat'] text-base">Schedule Committee Session</h3>
                        </div>
                        <button @click="showScheduleModal = false" 
                                type="button"
                                class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 cursor-pointer">
                            <i class="fa-solid fa-xmark text-base" aria-hidden="true"></i>
                        </button>
                    </div>
                    
                    <form action="{{ route('admin.meetings.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        
                        <!-- Title Input -->
                        <div class="space-y-1.5 text-left">
                            <label for="meeting_title" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Session Title</label>
                            <input type="text" id="meeting_title" name="title" required 
                                   placeholder="e.g., Full Committee Ethical Review Session" 
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium">
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Type Select -->
                            <div class="space-y-1.5 text-left">
                                <label for="meeting_type" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Session Type</label>
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

                            <!-- Date Picker (Flatpickr) -->
                            <div class="space-y-1.5 text-left">
                                <label for="meeting_date_picker" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Date & Time</label>
                                <input type="text" id="meeting_date_picker" name="meeting_date" required 
                                       placeholder="Select date & time" 
                                       class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium cursor-pointer">
                            </div>
                        </div>

                        <!-- Venue Input -->
                        <div class="space-y-1.5 text-left">
                            <label for="meeting_venue" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Meeting Venue</label>
                            <input type="text" id="meeting_venue" name="venue" 
                                   placeholder="e.g., WMSU Executive Conference Hall (Room 302)" 
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all placeholder:text-slate-400 text-slate-900 font-medium">
                        </div>

                        <!-- Modal Actions -->
                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-2">
                            <button type="button" @click="showScheduleModal = false" 
                                    class="px-4 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors font-semibold text-sm cursor-pointer min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-[#8B0000] hover:bg-[#6d0000] text-white rounded-xl transition-all font-semibold text-sm shadow-xs active:scale-[0.98] min-h-[44px] cursor-pointer inline-flex items-center gap-2">
                                <i class="fa-regular fa-calendar-check text-xs" aria-hidden="true"></i>
                                <span>Confirm & Schedule</span>
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
                        <h3 class="text-base font-bold text-slate-900 font-['Montserrat'] mb-1">Delete Committee Session?</h3>
                        <p class="text-xs text-slate-500 mb-5 leading-relaxed">
                            Are you sure you want to delete <span class="font-bold text-slate-800" x-text="meetingToDelete"></span>? All assembled agenda docket records will be removed. This action cannot be reversed.
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

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#meeting_date_picker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                time_24hr: false,
                disableMobile: "true",
                theme: "airbnb"
            });
        });
    </script>
</x-admin_layout>
