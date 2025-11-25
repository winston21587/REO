<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Active Protocols</h1>
                <p class="text-slate-500 mt-2 text-sm">Manage and monitor ongoing research protocols.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <div class="relative">
                    <input type="text" placeholder="Search protocols..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-64 shadow-sm">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>
                <button class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Protocol ID</th>
                            <th class="p-6">Research Title</th>
                            <th class="p-6">Principal Investigator</th>
                            <th class="p-6">Submission Date</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($datas as $data)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="p-6">
                                <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                    #{{ str_pad($data['id'], 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="p-6">
                                <p class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-[#8B0000] transition-colors">{{ $data['title'] }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $data['ReviewType'] ?? 'Standard Review' }}</p>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                        {{ substr($data['name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">{{ $data['name'] }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $data['email'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <i class="far fa-calendar text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="p-6">
                                @php
                                    $statusColors = [
                                        'Review' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'Revision' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'Complete' => 'bg-green-50 text-green-700 border-green-100',
                                        'Finalization' => 'bg-purple-50 text-purple-700 border-purple-100',
                                    ];
                                    $colorClass = $statusColors[$data['status']] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }} inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-50"></span>
                                    {{ $data['status'] }}
                                </span>
                                @if(isset($data['RevisionStage']))
                                    <p class="text-[10px] text-slate-400 mt-1 ml-2">{{ $data['RevisionStage'] }}</p>
                                @endif
                            </td>
                            <td class="p-6 text-right">
                                <button class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all" title="View Details">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (Static for now) -->
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center">
                <p class="text-xs text-slate-500">Showing <span class="font-bold text-slate-700">1-{{ count($datas) }}</span> of <span class="font-bold text-slate-700">{{ count($datas) }}</span> protocols</p>
                <div class="flex gap-1">
                    <button class="px-3 py-1 text-xs font-medium text-slate-400 hover:text-slate-600 disabled:opacity-50" disabled>Previous</button>
                    <button class="px-3 py-1 text-xs font-medium text-white bg-[#8B0000] rounded shadow-sm">1</button>
                    <button class="px-3 py-1 text-xs font-medium text-slate-400 hover:text-slate-600 disabled:opacity-50" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>
</x-admin_layout>