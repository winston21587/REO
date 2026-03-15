<x-super_admin_layout>
    <div class="space-y-6">
        <!-- Header & Stats Section -->
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Revenue Logs</h1>
            <p class="text-sm text-slate-500 mt-1">Track financial accumulation across all submissions</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Revenue Card -->
            <div class="bg-gradient-to-br from-[#8B0000] to-red-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-6 -top-6 text-white/10 text-9xl">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-red-100 text-xs font-bold uppercase tracking-widest mb-1">Total Accumulated Revenue</p>
                    <h2 class="text-4xl font-extrabold font-heading">₱ {{ number_format($totalRevenue, 2) }}</h2>
                    <p class="text-sm text-red-200 mt-2">Based on all historically logged submission fees.</p>
                </div>
            </div>

            <!-- Total Submissions Logged Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Logs</p>
                    <h2 class="text-2xl font-extrabold text-slate-800">{{ number_format($submissions->total()) }}</h2>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 flex flex-col h-full min-h-[400px]">
            <div class="overflow-x-auto flex-grow overflow-y-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Date</th>
                            <th class="p-6">Protocol ID / Title</th>
                            <th class="p-6">Researcher</th>
                            <th class="p-6">Research Category</th>
                            <th class="p-6 text-right">Fee Logged</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($submissions as $log)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="p-6">
                                    <div class="text-sm font-medium text-slate-800">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase">{{ $log->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="p-6">
                                    <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded mb-1 inline-block">
                                        #{{ str_pad($log->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <p class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-[#8B0000] transition-colors" title="{{ $log->Study_Protocol_title }}">
                                        {{ $log->Study_Protocol_title }}
                                    </p>
                                </td>
                                <td class="p-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                            {{ substr($log->researcher->user->first_name ?? $log->user->first_name ?? $log->Created_by ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-700">
                                                {{ $log->researcher->user->first_name ?? $log->user->first_name ?? $log->Created_by ?? 'Unknown' }} 
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6 text-sm text-slate-600 font-medium">
                                    {{ $log->Research_Category }}
                                </td>
                                <td class="p-6 text-right">
                                    <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg text-sm inline-block">
                                        + ₱ {{ number_format($log->category_fee_at_submission, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-slate-400">
                                    <i class="fas fa-receipt text-4xl mb-4 text-slate-300"></i>
                                    <p>No revenue logs available yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($submissions->total() > 0)
            <div class="p-6 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500">
                <div>
                    Showing <span class="font-bold text-slate-700">{{ $submissions->firstItem() ?? 0 }}</span> - <span
                        class="font-bold text-slate-700">{{ $submissions->lastItem() ?? 0 }}</span> of <span
                        class="font-bold text-slate-700">{{ $submissions->total() }}</span>
                </div>
                <div class="flex gap-2">
                    {{ $submissions->links('pagination::tailwind') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</x-super_admin_layout>
