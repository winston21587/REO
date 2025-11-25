<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Meetings & Agenda</h1>
                <p class="text-slate-500 mt-2 text-sm">Schedule and manage committee meetings.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <button class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-plus"></i> Schedule Meeting
                </button>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Upcoming Meetings List -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800">Upcoming Meetings</h3>
                        <a href="#" class="text-xs font-bold text-[#8B0000] hover:underline">View Calendar</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($meetings as $meeting)
                        <div class="p-6 hover:bg-slate-50 transition-colors group">
                            <div class="flex items-start justify-between">
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0 w-16 h-16 bg-slate-100 rounded-xl flex flex-col items-center justify-center border border-slate-200 text-slate-600">
                                        <span class="text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($meeting['date'])->format('M') }}</span>
                                        <span class="text-2xl font-extrabold text-[#8B0000]">{{ \Carbon\Carbon::parse($meeting['date'])->format('d') }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 group-hover:text-[#8B0000] transition-colors">{{ $meeting['title'] }}</h4>
                                        <div class="flex items-center gap-4 mt-2 text-xs text-slate-500">
                                            <span class="flex items-center gap-1"><i class="far fa-clock"></i> {{ $meeting['time'] }}</span>
                                            <span class="flex items-center gap-1"><i class="fas fa-map-marker-alt"></i> {{ $meeting['location'] }}</span>
                                        </div>
                                        <div class="mt-3 flex items-center gap-2">
                                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase">{{ $meeting['status'] }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $meeting['agenda_count'] }} Agenda Items</span>
                                        </div>
                                    </div>
                                </div>
                                <button class="p-2 text-slate-300 hover:text-[#8B0000] transition-colors">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center text-slate-500">
                            <p>No upcoming meetings scheduled.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions / Stats -->
            <div class="space-y-6">
                <div class="bg-[#1a0505] text-white rounded-2xl shadow-xl p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-10">
                        <i class="fas fa-calendar-check text-8xl"></i>
                    </div>
                    <h3 class="font-bold text-lg relative z-10">Next Meeting</h3>
                    <div class="mt-6 relative z-10">
                        <p class="text-4xl font-extrabold text-[#8B0000]">5</p>
                        <p class="text-sm text-slate-400">Days Remaining</p>
                    </div>
                    <button class="mt-6 w-full py-3 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-bold transition-colors relative z-10">
                        Prepare Agenda
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6">
                    <h3 class="font-bold text-slate-800 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-slate-50 text-sm font-medium text-slate-600 hover:text-[#8B0000] transition-colors flex items-center gap-3">
                            <i class="fas fa-plus-circle text-slate-400"></i> Create New Agenda
                        </button>
                        <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-slate-50 text-sm font-medium text-slate-600 hover:text-[#8B0000] transition-colors flex items-center gap-3">
                            <i class="fas fa-envelope text-slate-400"></i> Send Invitations
                        </button>
                        <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-slate-50 text-sm font-medium text-slate-600 hover:text-[#8B0000] transition-colors flex items-center gap-3">
                            <i class="fas fa-file-alt text-slate-400"></i> Generate Minutes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin_layout>