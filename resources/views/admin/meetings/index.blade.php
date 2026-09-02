

<x-admin_layout>
    <x-slot name="title">Meetings & Agenda</x-slot>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

    <div x-data="{ 
        showScheduleModal: false, 
        showDeleteModal: false, 
        meetingToDelete: null,
        deleteUrl: '',
        confirmDelete(url, title) {
            this.meetingToDelete = title;
            this.deleteUrl = url;
            this.showDeleteModal = true;
        }
    }" class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Meetings & Agenda</h1>
            <p class="text-slate-600 mt-1">Schedule and manage committee meetings (SOP 17, 18, 19).</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showScheduleModal = true" class="inline-flex items-center gap-2 px-4 py-2 bg-[#8B0000] text-white rounded-lg hover:bg-[#6b0000] transition-colors shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>Schedule Meeting</span>
            </button>
        </div>
    </div>

    <!-- Next Meeting Card -->
    @if($nextMeeting)
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <div class="flex items-center gap-2 text-white/70 text-sm font-medium mb-2">
                    <span class="px-2 py-0.5 rounded-full bg-white/10 border border-white/10">Next Scheduled Meeting</span>
                    <span>•</span>
                    <span>{{ $nextMeeting->meeting_date->format('l, F j, Y') }}</span>
                </div>
                <h2 class="text-3xl font-bold mb-2">{{ $nextMeeting->title }}</h2>
                <div class="flex items-center gap-4 text-white/80">
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-clock"></i>
                        <span>{{ $nextMeeting->meeting_date->format('h:i A') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>{{ $nextMeeting->venue ?? 'TBD' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-tag"></i>
                        <span>{{ $nextMeeting->type }}</span>
                    </div>
                </div>
            </div>

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
            }" x-init="start()" class="flex flex-col gap-3 min-w-[240px]">
                <div class="text-center p-4 bg-white/10 rounded-lg backdrop-blur-sm border border-white/10">
                    <div class="flex items-center justify-center gap-2 text-2xl font-bold font-mono">
                        <div class="flex flex-col">
                            <span x-text="days"></span>
                            <span class="text-[10px] font-normal text-white/60 uppercase tracking-wider">Days</span>
                        </div>
                        <span class="text-white/40 -mt-4">:</span>
                        <div class="flex flex-col">
                            <span x-text="hours"></span>
                            <span class="text-[10px] font-normal text-white/60 uppercase tracking-wider">Hrs</span>
                        </div>
                        <span class="text-white/40 -mt-4">:</span>
                        <div class="flex flex-col">
                            <span x-text="minutes"></span>
                            <span class="text-[10px] font-normal text-white/60 uppercase tracking-wider">Mins</span>
                        </div>
                        <span class="text-white/40 -mt-4">:</span>
                        <div class="flex flex-col">
                            <span x-text="seconds"></span>
                            <span class="text-[10px] font-normal text-white/60 uppercase tracking-wider">Secs</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.meetings.show', $nextMeeting->id) }}" class="flex items-center justify-center gap-2 px-4 py-2 bg-white text-slate-900 rounded-lg hover:bg-slate-100 transition-colors font-medium">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Prepare Agenda</span>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Upcoming Meetings List -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-semibold text-slate-800">Upcoming Meetings</h3>
            <span class="text-xs font-medium px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full">{{ $upcomingMeetings->count() }} Scheduled</span>
        </div>
        
        <div class="divide-y divide-slate-100">
            @forelse($upcomingMeetings as $meeting)
            <div class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 text-[#8B0000] rounded-lg flex flex-col items-center justify-center border border-indigo-100">
                        <span class="text-xs font-bold uppercase">{{ $meeting->meeting_date->format('M') }}</span>
                        <span class="text-lg font-bold leading-none">{{ $meeting->meeting_date->format('d') }}</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-slate-900 group-hover:text-[#8B0000] transition-colors">{{ $meeting->title }}</h4>
                        <div class="flex items-center gap-3 text-sm text-slate-500 mt-0.5">
                            <span>{{ $meeting->meeting_date->format('h:i A') }}</span>
                            <span>•</span>
                            <span>{{ $meeting->type }}</span>
                            <span>•</span>
                            <span class="@if($meeting->agenda_status === 'Final') text-green-600 @elseif($meeting->agenda_status === 'Provisional') text-amber-600 @else text-slate-500 @endif font-medium text-xs px-2 py-0.5 bg-slate-100 rounded-full">
                                {{ $meeting->agenda_status }} Agenda
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('admin.meetings.show', $meeting->id) }}" class="p-2 text-slate-500 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-colors" title="Manage Agenda">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <button 
                        data-url="{{ route('admin.meetings.destroy', $meeting->id) }}"
                        data-title="{{ $meeting->title }}"
                        @click="showDeleteModal = true; meetingToDelete = $el.dataset.title; deleteUrl = $el.dataset.url"
                        class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                        title="Delete Meeting">
                        <i class="fa-solid fa-trash pointer-events-none"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-500">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 mb-3">
                    <i class="fa-regular fa-calendar-xmark text-xl"></i>
                </div>
                <p>No upcoming meetings scheduled.</p>
            </div>
            @endforelse
        </div>
    </div>



    <!-- Next Protocol Appointment Hero Card -->
    @php
        $nextAppointment = $upcomingAppointments->first();
    @endphp
    @if($nextAppointment)
    <div class="bg-gradient-to-br from-[#8B0000] to-[#500000] rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <div class="flex items-center gap-2 text-white/80 text-sm font-medium mb-2">
                    <span class="px-2 py-0.5 rounded-full bg-white/20 border border-white/10">Next Protocol Appointment</span>
                    <span>•</span>
                    <span>{{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('l, F j, Y') }}</span>
                </div>
                <h2 class="text-3xl font-bold mb-2 line-clamp-1">{{ $nextAppointment->research->Study_Protocol_title ?? 'Unknown Protocol' }}</h2>
                <div class="flex items-center gap-4 text-white/90">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>{{ $nextAppointment->stage === 'Certificate Pickup' ? 'REO Office' : 'Conference Room' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid {{ $nextAppointment->stage === 'Certificate Pickup' ? 'fa-certificate' : 'fa-users' }}"></i>
                        <span>{{ $nextAppointment->stage }}</span>
                    </div>
                </div>
            </div>

            <div x-data="{
                days: '00',
                hours: '00',
                minutes: '00',
                seconds: '00',
                target: new Date('{{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->toIso8601String() }}'),
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
            }" x-init="start()" class="flex flex-col gap-3 min-w-[240px]">
                <div class="text-center p-4 bg-white/10 rounded-lg backdrop-blur-sm border border-white/10">
                    <div class="flex items-center justify-center gap-2 text-2xl font-bold font-mono">
                        <div class="flex flex-col">
                            <span x-text="days"></span>
                            <span class="text-[10px] font-normal text-white/60 uppercase tracking-wider">Days</span>
                        </div>
                        <span class="text-white/40 -mt-4">:</span>
                        <div class="flex flex-col">
                            <span x-text="hours"></span>
                            <span class="text-[10px] font-normal text-white/60 uppercase tracking-wider">Hrs</span>
                        </div>
                        <span class="text-white/40 -mt-4">:</span>
                        <div class="flex flex-col">
                            <span x-text="minutes"></span>
                            <span class="text-[10px] font-normal text-white/60 uppercase tracking-wider">Mins</span>
                        </div>
                        <span class="text-white/40 -mt-4">:</span>
                        <div class="flex flex-col">
                            <span x-text="seconds"></span>
                            <span class="text-[10px] font-normal text-white/60 uppercase tracking-wider">Secs</span>
                        </div>
                    </div>
                </div>
                <button class="flex items-center justify-center gap-2 px-4 py-2 bg-white text-[#8B0000] rounded-lg hover:bg-red-50 transition-colors font-bold">
                    <i class="fa-solid fa-eye"></i>
                    <span>View Details</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Protocol Key Dates Section -->
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Upcoming Protocol Appointments -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-semibold text-slate-800">Upcoming Protocol Appointments</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($upcomingAppointments as $appointment)
                <div class="p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start gap-3">
                        @php
                            $isPickup = $appointment->stage === 'Certificate Pickup';
                            $colorClass = $isPickup ? 'green' : 'blue';
                            $icon = $isPickup ? 'fa-certificate' : 'fa-users';
                        @endphp
                        <div class="flex-shrink-0 w-10 h-10 bg-{{ $colorClass }}-50 text-{{ $colorClass }}-600 rounded-lg flex flex-col items-center justify-center border border-{{ $colorClass }}-100">
                            <span class="text-[10px] font-bold uppercase">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M') }}</span>
                            <span class="text-sm font-bold leading-none">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d') }}</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 line-clamp-1">{{ $appointment->research->Study_Protocol_title ?? 'Unknown Protocol' }}</h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                                <span class="px-1.5 py-0.5 bg-{{ $colorClass }}-100 text-{{ $colorClass }}-700 rounded-md font-medium flex items-center gap-1">
                                    <i class="fas {{ $icon }} text-[10px]"></i> {{ $appointment->stage }}
                                </span>
                                <span>•</span>
                                <span class="font-medium text-slate-400 capitalize">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-slate-500 text-sm">
                    No upcoming appointments.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Protocol Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-semibold text-slate-800">Recent Protocol Activity</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentActivities as $activity)
                <div class="p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            @if($activity->Status === 'Approved')
                                <i class="fa-solid fa-circle-check text-green-500 text-lg"></i>
                            @elseif($activity->Status === 'Modifications Required' || $activity->Status === 'Waiting for Revision')
                                <i class="fa-solid fa-circle-exclamation text-orange-500 text-lg"></i>
                            @elseif($activity->Status === 'For Initial Review')
                                <i class="fa-solid fa-circle-play text-blue-500 text-lg"></i>
                            @else
                                <i class="fa-solid fa-circle-info text-slate-400 text-lg"></i>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 line-clamp-1">{{ $activity->Study_Protocol_title }}</h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                                <span class="font-medium 
                                    @if($activity->Status === 'Approved') text-green-600 
                                    @elseif(in_array($activity->Status, ['Modifications Required', 'Waiting for Revision'])) text-orange-600 
                                    @elseif($activity->Status === 'For Initial Review') text-blue-600 
                                    @endif">
                                    {{ $activity->Status }}
                                </span>
                                <span>•</span>
                                <span>{{ $activity->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-slate-500 text-sm">
                    No recent activity.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Schedule Modal (Alpine.js) -->
    <div x-show="showScheduleModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showScheduleModal = false"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Premium Header -->
                <div class="bg-gradient-to-r from-[#8B0000] to-[#600000] px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <i class="fa-regular fa-calendar-plus text-white text-sm"></i>
                        </div>
                        <h3 class="font-bold text-white text-lg tracking-wide">Schedule Meeting</h3>
                    </div>
                    <button @click="showScheduleModal = false" class="text-white/70 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                
                <form action="{{ route('admin.meetings.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    
                    <!-- Title Input -->
                    <div class="space-y-1.5 text-left">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Meeting Title</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-heading text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                            </div>
                            <input type="text" name="title" required placeholder="e.g., Regular Review Meeting" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all placeholder:text-slate-400 text-slate-800 font-medium">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-5">
                        <!-- Type Select -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Meeting Type</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-tag text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                                </div>
                                <select name="type" required class="w-full pl-10 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all appearance-none cursor-pointer text-slate-800 font-medium">
                                    <option value="Regular">Regular</option>
                                    <option value="Special">Special</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Date Picker (Flatpickr) -->
                        <div class="space-y-1.5 text-left">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Date & Time</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <i class="fa-regular fa-calendar text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                                </div>
                                <input type="text" id="meeting_date_picker" name="meeting_date" required placeholder="Select date..." 
                                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all placeholder:text-slate-400 text-slate-800 font-medium cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Venue Input -->
                    <div class="space-y-1.5 text-left">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Venue</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-location-dot text-slate-400 group-focus-within:text-[#8B0000] transition-colors"></i>
                            </div>
                            <input type="text" name="venue" placeholder="e.g., Conference Room A" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all placeholder:text-slate-400 text-slate-800 font-medium">
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-2">
                        <button type="button" @click="showScheduleModal = false" class="px-5 py-2.5 text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-xl transition-colors font-bold text-sm">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#8B0000] to-[#600000] text-white rounded-xl hover:shadow-lg hover:shadow-red-900/20 hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
                            <i class="fa-regular fa-calendar-check"></i>
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
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showDeleteModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Delete Meeting?</h3>
                    <p class="text-sm text-slate-500 mb-6">
                        Are you sure you want to delete <span class="font-bold text-slate-800" x-text="meetingToDelete"></span>? This action cannot be undone.
                    </p>
                    
                    <form :action="deleteUrl" method="POST" class="flex items-center justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="showDeleteModal = false" class="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors font-medium text-sm">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm font-medium text-sm">Delete Meeting</button>
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
