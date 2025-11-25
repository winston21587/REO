<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Researchers</h1>
                <p class="text-slate-500 mt-2 text-sm">Directory of registered faculty, staff, and student researchers.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <button class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Add Researcher
                </button>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-grow w-full md:w-auto">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#8B0000] focus:bg-white transition-all outline-none" placeholder="Search by name, email, or college..." type="text" />
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button class="px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors flex items-center gap-2 flex-1 md:flex-none justify-center">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <button class="px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors flex items-center gap-2 flex-1 md:flex-none justify-center">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>

        <!-- Users List -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Name / Role</th>
                            <th class="p-6">Contact Info</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold shadow-sm border border-blue-100">DL</div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-base">Dr. David Lee</p>
                                        <p class="text-xs text-slate-400">Faculty Researcher</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6 text-sm text-slate-600">
                                <div class="flex items-center gap-2 group-hover:text-[#8B0000] transition-colors">
                                    <i class="far fa-envelope text-slate-400"></i> david.lee@wmsu.edu.ph
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
                                    <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center font-bold shadow-sm border border-orange-100">JM</div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-base">Jane Miller</p>
                                        <p class="text-xs text-slate-400">Student Researcher</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6 text-sm text-slate-600">
                                <div class="flex items-center gap-2 group-hover:text-[#8B0000] transition-colors">
                                    <i class="far fa-envelope text-slate-400"></i> jane.m@wmsu.edu.ph
                                </div>
                            </td>
                            <td class="p-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
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
            
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center">
                <p class="text-xs text-slate-500">Showing <span class="font-bold text-slate-700">1-10</span> of <span class="font-bold text-slate-700">45</span> users</p>
                <div class="flex gap-1">
                    <button class="px-3 py-1 text-xs font-medium text-slate-400 hover:text-slate-600 border border-slate-200 rounded bg-white disabled:opacity-50" disabled>Previous</button>
                    <button class="px-3 py-1 text-xs font-medium text-slate-400 hover:text-slate-600 border border-slate-200 rounded bg-white">Next</button>
                </div>
            </div>
        </div>
    </div>
</x-admin_layout>