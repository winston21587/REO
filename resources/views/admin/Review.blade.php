<x-admin_layout>
    <div class="flex flex-col h-full">
        
        <div class="mb-8 border-b border-slate-200 pb-6">
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading">Initial Review Classification</h1>
            <p class="text-slate-500 mt-2">Classify new submissions into the appropriate review category.</p>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" placeholder="Search protocol or author..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:border-brand-primary outline-none shadow-sm">
            </div>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">filter_list</span>
                <select class="pl-10 pr-8 py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:ring-2 focus:ring-brand-primary outline-none shadow-sm appearance-none">
                    <option value="all">All Types</option>
                    <option value="full">Full Review</option>
                    <option value="expedited">Expedited</option>
                    <option value="exempt">Exempt</option>
                </select>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="p-5">Protocol Details</th>
                        <th class="p-5">Submission Date</th>
                        <th class="p-5">Current Classification</th>
                        <th class="p-5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="p-5">
                            <p class="font-bold text-slate-800 mb-1">Impact of Social Media on Youth Mental Health</p>
                            <p class="text-xs text-slate-500">By <span class="font-semibold text-slate-700">Dr. Maria Cruz</span></p>
                        </td>
                        <td class="p-5 text-sm text-slate-600">Oct 20, 2025</td>
                        <td class="p-5">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">Unclassified</span>
                        </td>
                        <td class="p-5 text-center">
                            <button onclick="openModal('edit-modal-0')" class="text-brand-primary hover:bg-red-50 p-2 rounded-lg transition-colors text-sm font-bold">
                                Classify
                            </button>
                        </td>
                    </tr>
                    
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="p-5">
                            <p class="font-bold text-slate-800 mb-1">AI Ethics Framework for Clinical Trials</p>
                            <p class="text-xs text-slate-500">By <span class="font-semibold text-slate-700">Prof. John Reyes</span></p>
                        </td>
                        <td class="p-5 text-sm text-slate-600">Oct 15, 2025</td>
                        <td class="p-5">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">Full Review</span>
                        </td>
                        <td class="p-5 text-center">
                            <button onclick="openModal('edit-modal-1')" class="text-slate-400 hover:text-brand-primary hover:bg-slate-100 p-2 rounded-lg transition-colors">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="edit-modal-0 hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[scaleIn_0.2s_ease-out]">
            <div class="bg-slate-900 p-5">
                <h2 class="text-lg font-bold text-white">Classify Protocol</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Select Review Type</label>
                    <div class="space-y-2">
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="radio" name="type" class="text-brand-primary focus:ring-brand-primary" checked>
                            <span class="ml-3 text-sm font-bold text-slate-700">Full Review</span>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="radio" name="type" class="text-brand-primary focus:ring-brand-primary">
                            <span class="ml-3 text-sm font-bold text-slate-700">Expedited Review</span>
                        </label>
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="radio" name="type" class="text-brand-primary focus:ring-brand-primary">
                            <span class="ml-3 text-sm font-bold text-slate-700">Exempt from Review</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 flex justify-end gap-3">
                <button onclick="closeModal('edit-modal-0')" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-50 rounded-lg">Cancel</button>
                <button onclick="closeModal('edit-modal-0')" class="px-6 py-2 bg-brand-primary text-white font-bold rounded-lg hover:bg-red-800 transition-colors">Save</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(name) { document.querySelector('.' + name).classList.remove('hidden'); }
        function closeModal(name) { document.querySelector('.' + name).classList.add('hidden'); }
    </script>
</x-admin_layout>