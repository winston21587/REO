<x-super_admin_layout>
    <div class="space-y-6">
        <!-- Header & Stats Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 font-heading">Revenue Logs</h1>
                <p class="text-sm text-slate-500 mt-1">Track financial accumulation across all officially receipted submissions</p>
            </div>
            
            <!-- Date Filters -->
            <form method="GET" action="{{ route('super_admin.revenue_logs') }}" class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
                <select name="month" class="px-3 py-2 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none cursor-pointer">
                    <option value="">All Months</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="year" class="px-3 py-2 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none cursor-pointer">
                    <option value="">All Years</option>
                    @foreach(range(date('Y') - 5, date('Y')) as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="exact_date" value="{{ request('exact_date') }}" class="px-3 py-2 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-xs font-bold uppercase focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none cursor-pointer">

                <div class="w-px h-8 bg-slate-200 mx-1"></div>

                <button type="submit" class="px-4 py-2 bg-[#8B0000] text-white rounded-lg text-xs font-bold uppercase hover:bg-red-800 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('super_admin.revenue_logs') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold uppercase hover:bg-slate-200 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </form>
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
                    <p class="text-sm text-red-200 mt-2">Based only on submissions with a logged Official Receipt.</p>
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
                            <th class="p-6">Official Receipt</th>
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
                                <td class="p-6">
                                    @if(!$log->Official_Receipt_Number)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-orange-50 text-orange-600 border border-orange-200">
                                            <i class="fas fa-clock"></i> Pending Payment
                                        </span>
                                    @elseif($log->Official_Receipt_Number && !$log->is_or_verified)
                                        <div class="flex flex-col gap-2 items-start mt-1">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-200">
                                                <i class="fas fa-eye"></i> Pending Verification
                                            </span>
                                            <span class="font-mono text-[10px] text-slate-500 mb-1">Receipt: #{{ $log->Official_Receipt_Number }}</span>
                                            
                                            <div class="flex items-center gap-1.5 w-full">
                                                @if($log->or_file_path)
                                                    <a href="{{ asset($log->or_file_path) }}" target="_blank" title="View Picture" class="w-7 h-7 bg-white text-indigo-600 border border-indigo-200 rounded text-xs font-bold hover:bg-indigo-50 transition-colors flex items-center justify-center shrink-0">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @endif
                                                <button type="button" onclick="document.getElementById('verify-modal-{{ $log->id }}').showModal()" title="Verify Receipt" class="w-7 h-7 bg-white text-emerald-600 border border-emerald-200 rounded text-xs font-bold hover:bg-emerald-50 transition-colors flex items-center justify-center shrink-0">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" onclick="document.getElementById('reject-modal-{{ $log->id }}').showModal()" title="Reject Receipt" class="w-7 h-7 bg-white text-red-600 border border-red-200 rounded text-xs font-bold hover:bg-red-50 transition-colors flex items-center justify-center shrink-0">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>

                                            <!-- Verify OR Modal -->
                                            <dialog id="verify-modal-{{ $log->id }}" class="m-auto rounded-3xl p-0 backdrop:bg-slate-900/60 w-full max-w-md overflow-hidden bg-white shadow-2xl transition-all open:animate-[zoomIn_0.3s_ease-out]">
                                                <!-- Header -->
                                                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-emerald-50">
                                                    <h3 class="text-lg font-bold text-emerald-800 flex items-center gap-2">
                                                        <i class="fas fa-check-circle text-emerald-600"></i> Verify Receipt
                                                    </h3>
                                                    <button type="button" onclick="document.getElementById('verify-modal-{{ $log->id }}').close()" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                                                        <i class="fas fa-times text-lg"></i>
                                                    </button>
                                                </div>
                                                <!-- Body -->
                                                <div class="p-6">
                                                    <p class="text-slate-600 mb-6 text-sm text-center">Are you sure you want to verify this receipt? This will securely add the <strong class="text-emerald-700">₱{{ number_format($log->category_fee_at_submission, 2) }}</strong> fee to the Total Accumulated Revenue.</p>
                                                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                                                        <button type="button" onclick="document.getElementById('verify-modal-{{ $log->id }}').close()" class="flex-1 px-4 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors text-center">Cancel</button>
                                                        <form action="{{ route('admin.or_number.verify', $log->id) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <button type="submit" class="w-full px-4 py-3 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                                                                <i class="fas fa-check"></i> Confirm Verify
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </dialog>

                                            <!-- Reject OR Modal -->
                                            <dialog id="reject-modal-{{ $log->id }}" class="m-auto rounded-3xl p-0 backdrop:bg-slate-900/60 w-full max-w-md overflow-hidden bg-white shadow-2xl transition-all open:animate-[zoomIn_0.3s_ease-out]">
                                                <!-- Header -->
                                                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-red-50">
                                                    <h3 class="text-lg font-bold text-red-800 flex items-center gap-2">
                                                        <i class="fas fa-exclamation-triangle text-red-600"></i> Reject Receipt
                                                    </h3>
                                                    <button type="button" onclick="document.getElementById('reject-modal-{{ $log->id }}').close()" class="text-red-400 hover:text-red-600 transition-colors">
                                                        <i class="fas fa-times text-lg"></i>
                                                    </button>
                                                </div>
                                                <!-- Body -->
                                                <div class="p-6">
                                                    <p class="text-slate-600 mb-6 text-sm text-center">Are you sure you want to reject this receipt? The current receipt file will be discarded, and the researcher will be required to upload a new one.</p>
                                                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                                                        <button type="button" onclick="document.getElementById('reject-modal-{{ $log->id }}').close()" class="flex-1 px-4 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors text-center">Cancel</button>
                                                        <form action="{{ route('admin.or_number.reject', $log->id) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <button type="submit" class="w-full px-4 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                                                                <i class="fas fa-times-circle"></i> Confirm Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </dialog>
                                        </div>
                                    @else
                                        <div class="flex flex-col gap-1 items-start">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                <i class="fas fa-check-circle"></i> Verified
                                            </span>
                                            <span class="font-mono text-[10px] font-bold text-slate-800">#{{ $log->Official_Receipt_Number }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-6 text-right">
                                    @if($log->is_or_verified)
                                        <span class="font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg text-sm inline-block shadow-sm">
                                            + ₱ {{ number_format($log->category_fee_at_submission, 2) }}
                                        </span>
                                    @else
                                        <span class="font-bold text-slate-400 text-sm italic inline-block line-through decoration-slate-300">
                                            ₱ {{ number_format($log->category_fee_at_submission, 2) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-400">
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
