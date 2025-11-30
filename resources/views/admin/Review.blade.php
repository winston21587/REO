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
                    @forelse($datas as $data)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="p-5">
                            <p class="font-bold text-slate-800 mb-1">{{ $data->Study_Protocol_title }}</p>
                            <p class="text-xs text-slate-500">By <span class="font-semibold text-slate-700">{{ $data->author->first_name }} {{ $data->author->last_name }}</span></p>
                        </td>
                        <td class="p-5 text-sm text-slate-600">{{ $data->created_at->format('M d, Y') }}</td>
                        <td class="p-5">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                {{ $data->Review_Type ?? 'Unclassified' }}
                            </span>
                        </td>
                        <td class="p-5 text-center">
                            <button onclick="openStatusModal('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }})" class="text-brand-primary hover:bg-red-50 p-2 rounded-lg transition-colors text-sm font-bold">
                                Classify
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-400">
                            <p>No protocols found for review.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('admin.partials.status_modal')

</x-admin_layout>