<x-admin_layout>
    <div class="flex flex-col h-full">
        
        <div class="mb-8 border-b border-slate-200 pb-6">
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading">Revision Queue</h1>
            <p class="text-slate-500 mt-2">Monitor protocols returned to researchers for modification.</p>
        </div>

        <div class="flex gap-4 mb-6">
            <button class="px-4 py-2 bg-brand-primary text-white rounded-lg text-sm font-bold shadow-md">All Revisions</button>
            <button class="px-4 py-2 bg-white text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-bold border border-slate-200">Overdue</button>
            <button class="px-4 py-2 bg-white text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-bold border border-slate-200">Resubmitted</button>
        </div>

        <div class="grid gap-4">
            <div class="bg-white p-5 rounded-xl border-l-4 border-green-500 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row items-start md:items-center gap-4">
                <div class="flex-grow">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-bold text-slate-800 text-lg">Effectiveness of Blended Learning</h3>
                        <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 text-[10px] font-bold uppercase">Resubmitted</span>
                    </div>
                    <p class="text-sm text-slate-500">Researcher: <span class="font-semibold">Alice Wonderland</span> • College of Education</p>
                    
                    <div class="mt-3 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-xs text-slate-600"><strong class="text-slate-900">Issue:</strong> Missing signatures on Informed Consent Form page 3.</p>
                    </div>
                </div>
                
                <div class="flex flex-col items-end gap-2 min-w-[140px]">
                    <span class="text-xs font-bold text-slate-400">Returned: 2 days ago</span>
                    <button class="w-full py-2 bg-slate-900 text-white rounded-lg text-xs font-bold hover:bg-brand-primary transition-colors">
                        Review Changes
                    </button>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border-l-4 border-orange-400 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row items-start md:items-center gap-4">
                <div class="flex-grow">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-bold text-slate-800 text-lg">Water Quality Analysis in Zamboanga</h3>
                        <span class="px-2 py-0.5 rounded bg-orange-100 text-orange-700 text-[10px] font-bold uppercase">Pending Researcher Action</span>
                    </div>
                    <p class="text-sm text-slate-500">Researcher: <span class="font-semibold">John Smith</span> • College of Engineering</p>
                    
                    <div class="mt-3 bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-xs text-slate-600"><strong class="text-slate-900">Issue:</strong> Methodology section lacks clear sampling size justification.</p>
                    </div>
                </div>
                
                <div class="flex flex-col items-end gap-2 min-w-[140px]">
                    <span class="text-xs font-bold text-slate-400">Returned: 5 days ago</span>
                    <button class="w-full py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition-colors">
                        Send Reminder
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin_layout>