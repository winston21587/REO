<div id="notifications-panel" 
    class="hidden absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 z-[100] transform transition-all duration-200 origin-top-right scale-95 opacity-0"
    style="display: none;">
    
    <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-2xl">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/reoc-nobg.png') }}" class="w-6 h-6">
            <h3 class="font-bold text-slate-800">Notifications</h3>
            <span class="bg-[#8B0000] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">3 New</span>
        </div>
        <button class="text-xs font-bold text-[#8B0000] hover:text-red-900 transition-colors">Mark all read</button>
    </div>

    <ul class="max-h-[24rem] overflow-y-auto divide-y divide-slate-50">
        <!-- Unread Notification -->
        <li class="p-4 hover:bg-slate-50 transition-colors cursor-pointer group relative">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#8B0000] opacity-100 group-hover:opacity-80 transition-opacity"></div>
            <div class="flex gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 mb-0.5">New Protocol Submission</p>
                    <p class="text-xs text-slate-500 line-clamp-2">Dr. Jose Rizal submitted "Ethical Implications of..." for review.</p>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">2 mins ago</p>
                </div>
            </div>
        </li>

        <!-- Unread Notification -->
        <li class="p-4 hover:bg-slate-50 transition-colors cursor-pointer group relative">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#8B0000] opacity-100 group-hover:opacity-80 transition-opacity"></div>
            <div class="flex gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 mb-0.5">Expedited Review Required</p>
                    <p class="text-xs text-slate-500 line-clamp-2">Protocol #2024-045 requires immediate attention.</p>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">1 hour ago</p>
                </div>
            </div>
        </li>

        <!-- Read Notification -->
        <li class="p-4 hover:bg-slate-50 transition-colors cursor-pointer opacity-75 hover:opacity-100">
            <div class="flex gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-700 mb-0.5">Meeting Scheduled</p>
                    <p class="text-xs text-slate-500 line-clamp-2">Monthly oversight committee meeting set for Nov 28.</p>
                    <p class="text-xs text-slate-400 mt-1">Yesterday</p>
                </div>
            </div>
        </li>
    </ul>

    <div class="p-3 border-t border-slate-100 bg-slate-50 rounded-b-2xl text-center">
        <a href="#" class="text-xs font-bold text-slate-500 hover:text-[#8B0000] transition-colors uppercase tracking-wider">View All Activity</a>
    </div>
</div>