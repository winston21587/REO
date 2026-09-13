<x-admin_layout>
    <div class="h-[calc(100vh-80px)] flex flex-col">
        
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading">Appointments</h1>
                <p class="text-slate-500 text-sm mt-1">Schedule and manage consultation sessions.</p>
            </div>
            <button onclick="openModal('add-appointment-modal')" class="bg-brand-primary hover:bg-red-800 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-red-900/20 flex items-center gap-2 transition-all hover:-translate-y-0.5">
                <span class="material-symbols-outlined">add</span>
                <span>New Schedule</span>
            </button>
        </div>

        <div class="flex flex-col xl:flex-row gap-8 h-full overflow-hidden">
            
            @php
                $currentMonth = now()->format('F Y');
                $daysInMonth = now()->daysInMonth;
                $todayDate = now()->day;
                $firstDayOfWeek = now()->startOfMonth()->dayOfWeek;
            @endphp
            <div class="xl:w-1/3 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-lg text-slate-800">{{ $currentMonth }}</h2>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-semibold px-2.5 py-1 bg-rose-50 text-[#8B0000] rounded-lg">Live Docket</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider py-1">{{ $day }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-1 text-center">
                    @for ($pad = 0; $pad < $firstDayOfWeek; $pad++)
                        <div class="h-9 w-9 mx-auto"></div>
                    @endfor
                    @for ($i = 1; $i <= $daysInMonth; $i++)
                        @php $isToday = ($i == $todayDate); @endphp
                        <button type="button" class="h-9 w-9 mx-auto rounded-xl flex items-center justify-center text-xs font-semibold transition-all cursor-pointer
                            {{ $isToday ? 'bg-[#8B0000] text-white shadow-sm ring-2 ring-[#8B0000]/30 font-bold' : 'text-slate-700 hover:bg-slate-100' }}">
                            {{ $i }}
                        </button>
                    @endfor
                </div>
            </div>

            <div class="xl:w-2/3 bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Upcoming Sessions</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-xl border border-slate-200 hover:border-brand-primary/30 hover:shadow-md transition-all bg-white">
                        <div class="flex flex-col items-center justify-center min-w-[60px] bg-red-50 rounded-lg p-2 text-brand-primary">
                            <span class="text-xs font-bold uppercase">{{ now()->format('M') }}</span>
                            <span class="text-xl font-extrabold leading-none">{{ now()->format('d') }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-900">Ethics Committee Consultation Session</h4>
                            <p class="text-sm text-slate-500 flex items-center gap-2 mt-1">
                                <span class="material-symbols-outlined text-base">schedule</span> 10:00 AM - 11:30 AM
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span class="material-symbols-outlined text-base">person</span> REO Secretariat
                            </p>
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors flex-1 sm:flex-none">Reschedule</button>
                            <button class="px-4 py-2 text-xs font-bold text-white bg-slate-800 rounded-lg hover:bg-slate-700 transition-colors flex-1 sm:flex-none">Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="add-appointment-modal hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden animate-[scaleIn_0.2s_ease-out]">
            <div class="bg-slate-900 p-6 flex justify-between items-center">
                <h2 class="text-lg font-bold text-white">Schedule Appointment</h2>
                <button onclick="closeModal('add-appointment-modal')" class="text-white/50 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
            </div>
            
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Research Title</label>
                    <select class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] outline-none">
                        <option>Select a pending protocol...</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Appointment Date & Time</label>
                    <input type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none cursor-pointer accent-[#8B0000] font-medium text-slate-800"
                           onclick="this.showPicker && this.showPicker()">
                </div>


                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Notes / Instructions</label>
                    <textarea rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary outline-none" placeholder="E.g. Please bring hard copies..."></textarea>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 flex justify-end gap-3">
                <button onclick="closeModal('add-appointment-modal')" class="px-5 py-2.5 text-slate-500 font-bold hover:bg-slate-50 rounded-xl transition-colors">Cancel</button>
                <button class="px-6 py-2.5 bg-brand-primary text-white font-bold rounded-xl hover:bg-red-800 shadow-lg shadow-red-900/20 transition-colors">Set Schedule</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(name) { document.querySelector('.' + name).classList.remove('hidden'); }
        function closeModal(name) { document.querySelector('.' + name).classList.add('hidden'); }
    </script>
</x-admin_layout>