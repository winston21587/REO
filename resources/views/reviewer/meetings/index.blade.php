<x-reviewer_layout title="Meetings & Agenda">
    @php
        $upcomingCount = $upcomingMeetings->count();
    @endphp

    <div class="w-full space-y-6 pb-12">
        
        <!-- ===== PAGE HEADER ===== -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-200/80">
            <div>
                <div class="flex flex-wrap items-baseline gap-2">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 font-heading tracking-tight leading-tight">
                        Meetings & Agenda
                    </h1>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                        ({{ $upcomingCount }} {{ Str::plural('Upcoming Session', $upcomingCount) }})
                    </span>
                </div>
                <p class="text-slate-500 mt-1 text-xs sm:text-sm max-w-2xl leading-relaxed">
                    View REOC committee deliberation sessions, inspect assigned protocol agenda slots, and confirm your meeting attendance.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('reviewer.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold transition-all shadow-2xs min-h-[44px]">
                    <i class="fas fa-clipboard-list text-slate-400" aria-hidden="true"></i>
                    <span>Assigned Protocols</span>
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-check-circle text-emerald-600 text-base shrink-0"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- ===== UPCOMING MEETINGS SESSIONS ===== -->
        <div class="space-y-6">
            @forelse($upcomingMeetings as $meeting)
                @php
                    $myAttendance = $meeting->attendees->firstWhere('user_id', $userId);
                    $attendanceStatus = $myAttendance?->status ?? 'Invited';
                    
                    // Filter agenda items containing protocols assigned to this reviewer
                    $assignedItems = $meeting->agendaItems->filter(function($item) use ($userId) {
                        if (!$item->protocol) return false;
                        $hasAssignment = $item->protocol->reviewers->contains('id', $userId);
                        $hasJson = in_array((string)$userId, (array)($item->protocol->assigned_reviewers ?? []))
                                || in_array((int)$userId, (array)($item->protocol->assigned_reviewers ?? []));
                        return $hasAssignment || $hasJson;
                    });
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition-all duration-200 hover:border-slate-300">
                    
                    <!-- Session Header Card -->
                    <div class="p-5 sm:p-6 bg-slate-50/60 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-5">
                        
                        <!-- Left: Calendar Date Badge & Title -->
                        <div class="flex items-start gap-4">
                            <!-- Calendar Date Block -->
                            <div class="shrink-0 w-16 sm:w-20 rounded-xl overflow-hidden border border-slate-200/90 bg-white text-center shadow-2xs">
                                <div class="bg-brand-primary text-white text-[10px] sm:text-[11px] font-bold uppercase tracking-wider py-1">
                                    {{ $meeting->meeting_date ? $meeting->meeting_date->format('M') : 'TBD' }}
                                </div>
                                <div class="py-1.5 sm:py-2">
                                    <span class="block text-xl sm:text-2xl font-extrabold text-slate-900 font-heading leading-none">
                                        {{ $meeting->meeting_date ? $meeting->meeting_date->format('d') : '--' }}
                                    </span>
                                    <span class="block text-[10px] font-semibold text-slate-400 uppercase mt-0.5">
                                        {{ $meeting->meeting_date ? $meeting->meeting_date->format('D') : '' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Meeting Info -->
                            <div class="space-y-1.5 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="font-bold uppercase tracking-wider text-brand-primary">
                                        {{ $meeting->type ?? 'Regular Session' }}
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="font-bold uppercase tracking-wider {{ $meeting->agenda_status === 'Final' ? 'text-emerald-700' : 'text-slate-500' }}">
                                        Agenda: {{ $meeting->agenda_status ?? 'Provisional' }}
                                    </span>
                                </div>

                                <h2 class="text-base sm:text-lg font-bold text-slate-900 font-heading tracking-tight leading-snug">
                                    {{ $meeting->title }}
                                </h2>

                                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-slate-500 pt-0.5">
                                    <span class="flex items-center gap-1.5 font-medium text-slate-700">
                                        <i class="fas fa-clock text-slate-400" aria-hidden="true"></i>
                                        {{ $meeting->meeting_date ? $meeting->meeting_date->format('h:i A') : 'Time TBD' }}
                                    </span>
                                    <span class="flex items-center gap-1.5 text-slate-600">
                                        <i class="fas fa-map-marker-alt text-slate-400" aria-hidden="true"></i>
                                        {{ $meeting->venue ?? 'WMSU REOC Conference Room' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Attendance Response & Actions -->
                        <div class="flex flex-col sm:items-end gap-2.5 shrink-0 pt-3 md:pt-0 border-t md:border-t-0 border-slate-200/60">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-slate-500 font-medium">Your Attendance:</span>
                                <span class="font-bold uppercase tracking-wider flex items-center gap-1.5
                                    {{ $attendanceStatus === 'Confirmed' ? 'text-emerald-700' : 
                                       ($attendanceStatus === 'Regrets' ? 'text-rose-700' : 
                                       'text-amber-700') }}">
                                    @if($attendanceStatus === 'Confirmed')
                                        <i class="fas fa-check text-[11px]" aria-hidden="true"></i> Confirmed
                                    @elseif($attendanceStatus === 'Regrets')
                                        <i class="fas fa-times text-[11px]" aria-hidden="true"></i> Regrets
                                    @else
                                        <i class="fas fa-clock text-[11px]" aria-hidden="true"></i> Awaiting Response
                                    @endif
                                </span>
                            </div>

                            <div class="flex w-full sm:w-auto items-center p-1 bg-slate-100/90 rounded-xl border border-slate-200/90 gap-1">
                                <form method="POST" action="{{ route('reviewer.meetings.attendance', $meeting->id) }}" class="flex-1 sm:flex-initial">
                                    @csrf
                                    <input type="hidden" name="status" value="Confirmed">
                                    <button type="submit" 
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs rounded-lg transition-all cursor-pointer min-h-[44px] sm:min-h-[38px] whitespace-nowrap
                                            {{ $attendanceStatus === 'Confirmed' 
                                               ? 'bg-white text-emerald-700 font-bold shadow-xs border border-slate-200/80' 
                                               : 'text-slate-600 hover:text-slate-900 font-medium' }}">
                                        <i class="fas fa-check text-[11px]" aria-hidden="true"></i>
                                        <span>Confirm</span>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('reviewer.meetings.attendance', $meeting->id) }}" class="flex-1 sm:flex-initial">
                                    @csrf
                                    <input type="hidden" name="status" value="Regrets">
                                    <button type="submit" 
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs rounded-lg transition-all cursor-pointer min-h-[44px] sm:min-h-[38px] whitespace-nowrap
                                            {{ $attendanceStatus === 'Regrets' 
                                               ? 'bg-white text-rose-700 font-bold shadow-xs border border-slate-200/80' 
                                               : 'text-slate-600 hover:text-slate-900 font-medium' }}">
                                        <i class="fas fa-times text-[11px]" aria-hidden="true"></i>
                                        <span>Send Regrets</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Protocols Section (Deliberation Focus) -->
                    <div class="p-5 sm:p-6 space-y-3">
                        
                        <!-- Protocols Accordion Dropdown -->
                        <div x-data="{ openProtocols: false }">
                            <button @click="openProtocols = !openProtocols" 
                                    type="button"
                                    class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-50/80 hover:bg-slate-100/80 border border-slate-200/80 text-xs font-semibold text-slate-700 transition-all cursor-pointer">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-file-signature text-brand-primary" aria-hidden="true"></i>
                                    <span class="font-bold uppercase tracking-wider text-slate-900">
                                        Protocols Requiring Your Deliberation ({{ $assignedItems->count() }})
                                    </span>
                                    <span class="text-[11px] text-slate-400 font-medium hidden sm:inline">• SOP 19 Full Board Review</span>
                                </span>
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] text-slate-500 font-medium hidden md:inline" x-text="openProtocols ? 'Hide Protocols' : 'View Protocols'"></span>
                                    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200 text-slate-400" :class="{ 'rotate-180': openProtocols }" aria-hidden="true"></i>
                                </div>
                            </button>

                            <div x-show="openProtocols" x-collapse x-cloak class="mt-3 space-y-3">
                                @if($assignedItems->isNotEmpty())
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                        @foreach($assignedItems as $item)
                                            @php
                                                $protocol = $item->protocol;
                                            @endphp
                                            <div class="p-4 rounded-xl bg-slate-50/90 border border-slate-200/90 flex flex-col justify-between gap-3 hover:border-slate-300 transition-all">
                                                <div class="space-y-1.5">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <span class="text-[11px] font-bold uppercase tracking-wider text-brand-primary">
                                                            {{ $item->section }}
                                                        </span>
                                                        <span class="text-[11px] font-semibold text-slate-600 font-mono">
                                                            {{ $protocol->reoc_code ?: ('#' . $protocol->id) }}
                                                        </span>
                                                    </div>

                                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 line-clamp-2 leading-snug" title="{{ $protocol->Study_Protocol_title }}">
                                                        {{ $protocol->Study_Protocol_title }}
                                                    </h4>

                                                    @php
                                                        $protocolRevColor = match($protocol->Review_Type) {
                                                            'Exempt Review' => 'text-emerald-700',
                                                            'Expedited Review' => 'text-blue-700',
                                                            'Full Board Review', 'Full Board' => 'text-amber-700',
                                                            default => 'text-amber-700'
                                                        };
                                                    @endphp
                                                    <div class="text-[11px] text-slate-500 flex items-center gap-3 flex-wrap">
                                                        <span><i class="fas fa-tag mr-1 text-slate-400"></i>{{ $protocol->Research_Category ?? 'Research' }}</span>
                                                        <span class="font-bold uppercase tracking-wider {{ $protocolRevColor }}">
                                                            <i class="fas fa-list-check mr-1 text-slate-400" aria-hidden="true"></i>{{ $protocol->Review_Type ?? 'Full Board' }}
                                                        </span>
                                                        <span class="font-bold uppercase tracking-wider text-indigo-700">{{ $protocol->Status ?? 'Under Review' }}</span>
                                                    </div>
                                                </div>

                                                <div class="pt-3 border-t border-slate-200/70 flex items-center justify-end">
                                                    <a href="{{ route('reviewer.view_files', $protocol->id) }}" 
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white hover:bg-brand-primary text-brand-primary hover:text-white border border-brand-primary/30 text-xs font-bold transition-all shadow-2xs min-h-[38px]">
                                                        <span>Evaluate Protocol Files</span>
                                                        <i class="fas fa-arrow-right text-[10px]" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex items-start sm:items-center gap-3.5 p-4 rounded-xl bg-slate-50/80 border border-slate-200/80 text-xs text-slate-600">
                                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200/90 flex items-center justify-center shrink-0 text-slate-400 shadow-2xs">
                                            <i class="fas fa-info-circle text-xs text-slate-400" aria-hidden="true"></i>
                                        </div>
                                        <div class="space-y-0.5">
                                            <p class="font-semibold text-slate-800">No designated protocols for primary presentation in this session</p>
                                            <p class="text-slate-500 text-[11px] leading-relaxed">Your attendance contributes to committee quorum and collegial voting across all agenda items.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Full Committee Agenda Accordion -->
                        <div x-data="{ open: false }" class="pt-1">
                            <button @click="open = !open" 
                                    type="button"
                                    class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-50/60 hover:bg-slate-100/70 border border-slate-200/70 text-xs font-semibold text-slate-700 transition-all cursor-pointer">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-list-ol text-slate-400" aria-hidden="true"></i>
                                    <span x-text="open ? 'Hide Full Committee Agenda' : 'View Full Committee Agenda ({{ $meeting->agendaItems->count() }} items)'"></span>
                                </span>
                                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200 text-slate-400" :class="{ 'rotate-180': open }" aria-hidden="true"></i>
                            </button>

                            <div x-show="open" x-collapse x-cloak class="mt-3 space-y-2">
                                @forelse($meeting->agendaItems as $agenda)
                                    <div class="p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 text-xs flex items-start gap-3">
                                        <span class="w-6 h-6 rounded-lg bg-slate-200 text-slate-700 font-bold flex items-center justify-center shrink-0 text-[11px] tabular-nums">
                                            {{ $agenda->order }}
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900">{{ $agenda->section }}</span>
                                                @if($agenda->protocol)
                                                    <span class="text-[10px] font-mono font-semibold text-slate-600">
                                                        {{ $agenda->protocol->reoc_code ?: ('#' . $agenda->protocol->id) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-slate-600 mt-0.5">{{ $agenda->content ?: ($agenda->protocol?->Study_Protocol_title ?? 'Committee Discussion') }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-2">No detailed agenda items drafted yet.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <!-- ===== DATABASE EMPTY STATE (Dignified Institutional) ===== -->
                <div class="py-16 px-6 text-center bg-white rounded-2xl border border-slate-200/90 shadow-2xs max-w-2xl mx-auto">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-center mx-auto mb-4 text-slate-400 shadow-2xs">
                        <i class="fas fa-calendar-check text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 tracking-tight font-heading">
                        No Upcoming Meetings Scheduled
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1.5 max-w-md mx-auto leading-relaxed">
                        You currently have no scheduled committee deliberation meetings. When the Secretariat convenes an ethics deliberation session involving your assigned protocols, it will appear here.
                    </p>
                    <div class="mt-6 flex items-center justify-center">
                        <a href="{{ route('reviewer.dashboard') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-primary hover:bg-brand-secondary text-white text-xs font-bold transition-all shadow-xs min-h-[44px] cursor-pointer active:scale-98">
                            <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                            <span>View Assigned Protocols</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- ===== PAST MEETINGS ARCHIVE SECTION ===== -->
        @if($pastMeetings->isNotEmpty())
            <div class="pt-8 border-t border-slate-200/80 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center gap-2">
                        <i class="fas fa-history text-slate-400" aria-hidden="true"></i>
                        <span>Past Deliberation Sessions ({{ $pastMeetings->count() }})</span>
                    </h3>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-3 px-4 sm:px-6 whitespace-nowrap">Deliberation Session</th>
                                    <th class="py-3 px-4 hidden sm:table-cell whitespace-nowrap">Date & Time</th>
                                    <th class="py-3 px-4 hidden md:table-cell">Venue</th>
                                    <th class="py-3 px-4 text-right sm:px-6 whitespace-nowrap">Attendance Record</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($pastMeetings as $past)
                                    @php
                                        $pastAttendee = $past->attendees->firstWhere('user_id', $userId);
                                        $record = $pastAttendee?->status ?? 'Not Recorded';
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-3.5 px-4 sm:px-6 font-bold text-slate-900 align-middle">
                                            {{ $past->title }}
                                        </td>
                                        <td class="py-3.5 px-4 hidden sm:table-cell text-slate-600 tabular-nums whitespace-nowrap align-middle">
                                            {{ $past->meeting_date ? $past->meeting_date->format('M d, Y • h:i A') : '—' }}
                                        </td>
                                        <td class="py-3.5 px-4 hidden md:table-cell text-slate-500 align-middle">
                                            {{ $past->venue ?? 'REOC Conference Hall' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right sm:px-6 whitespace-nowrap align-middle">
                                            <span class="text-xs font-bold uppercase tracking-wider 
                                                {{ $record === 'Confirmed' ? 'text-emerald-700' : 
                                                   ($record === 'Regrets' ? 'text-rose-700' : 
                                                   'text-slate-500') }}">
                                                {{ $record }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-reviewer_layout>
