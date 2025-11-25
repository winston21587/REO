<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">REO Membership</h1>
                <p class="text-slate-500 mt-2 text-sm">Manage committee members, expertise, and appointments (SOP 01).</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <button class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Add Member
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Members</span>
                <div class="flex justify-between items-end mt-2">
                    <p class="text-3xl font-extrabold text-slate-800">15</p>
                    <i class="fas fa-users text-slate-200 text-2xl group-hover:text-[#8B0000] transition-colors"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
                <span class="text-xs font-bold text-blue-500 uppercase tracking-wider">Scientists</span>
                <div class="flex justify-between items-end mt-2">
                    <p class="text-3xl font-extrabold text-slate-800">10</p>
                    <i class="fas fa-microscope text-blue-100 text-2xl group-hover:text-blue-500 transition-colors"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
                <span class="text-xs font-bold text-purple-500 uppercase tracking-wider">Lay Members</span>
                <div class="flex justify-between items-end mt-2">
                    <p class="text-3xl font-extrabold text-slate-800">5</p>
                    <i class="fas fa-heart text-purple-100 text-2xl group-hover:text-purple-500 transition-colors"></i>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-xl border border-green-100 shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
                <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Quorum Status</span>
                <div class="flex justify-between items-end mt-2">
                    <p class="text-lg font-bold text-green-800">Valid</p>
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Members List -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Member Name</th>
                            <th class="p-6">Role (SOP 01)</th>
                            <th class="p-6">Expertise</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-[#8B0000] text-white flex items-center justify-center font-bold shadow-sm">
                                        JR
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-base">Dr. Jose Rizal</p>
                                        <p class="text-xs text-slate-400">College of Medicine</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-blue-100">Scientist</span>
                            </td>
                            <td class="p-6">
                                <div class="flex flex-wrap gap-1">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold">Medical</span>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold">Clinical Trials</span>
                                </div>
                            </td>
                            <td class="p-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Active
                                </span>
                            </td>
                            <td class="p-6 text-right">
                                <button class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>

                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold shadow-sm">
                                        MC
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-base">Ms. Maria Clara</p>
                                        <p class="text-xs text-slate-400">External Affiliate</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-purple-100">Non-Scientist</span>
                            </td>
                            <td class="p-6">
                                <div class="flex flex-wrap gap-1">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold">Community</span>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold">Ethics</span>
                                </div>
                            </td>
                            <td class="p-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Active
                                </span>
                            </td>
                            <td class="p-6 text-right">
                                <button class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin_layout>