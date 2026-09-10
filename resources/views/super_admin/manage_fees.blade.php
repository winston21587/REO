<x-super_admin_layout title="Fee Schedule & Tariffs">
    <div class="w-full max-w-7xl mx-auto space-y-8 min-w-0">
        
        <!-- Executive Header & Top Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">Manage Fees</h1>
                <p class="text-slate-500 mt-1.5 text-sm">Institutional review tariff schedule, research category pricing, and submission fees.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('super_admin.revenue_logs') }}" 
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-all shadow-xs min-h-[44px]">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Revenue Logs
                </a>
                <button type="button" onclick="openAddModal()" 
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Category
                </button>
            </div>
        </div>

        <!-- System Alerts -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-emerald-900">{{ session('success') }}</p>
                    <p class="text-xs text-emerald-700">Fee schedule records and submission tariffs updated across the portal.</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-rose-900">Please correct the following errors:</p>
                    <ul class="text-xs text-rose-700 mt-1 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @php
            $totalCount = $categories->count();
            $activeCount = $categories->where('active', 1)->count();
            $courseCats = $categories->where('classification', 'Course Requirement');
            $fundedCats = $categories->where('classification', 'Funded Research');
            
            $courseMin = $courseCats->count() ? $courseCats->min('fee') : 0;
            $courseMax = $courseCats->count() ? $courseCats->max('fee') : 0;
            $fundedMin = $fundedCats->count() ? $fundedCats->min('fee') : 0;
            $fundedMax = $fundedCats->count() ? $fundedCats->max('fee') : 0;
        @endphp

        <!-- Executive Metrics Ribbon -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Defined Categories</span>
                <span class="text-2xl font-extrabold text-slate-900 font-heading tabular-nums mt-1 block">{{ $totalCount }}</span>
                <span class="text-xs text-slate-400 mt-0.5 block">Institutional pricing classifications</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Active Schedules</span>
                <span class="text-2xl font-extrabold text-emerald-600 font-heading tabular-nums mt-1 block">{{ $activeCount }} Active</span>
                <span class="text-xs text-slate-400 mt-0.5 block">Live in submission intake</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Course Tariffs</span>
                <span class="text-2xl font-extrabold text-blue-700 font-heading tabular-nums mt-1 block">
                    ₱{{ number_format($courseMin, 0) }} - ₱{{ number_format($courseMax, 0) }}
                </span>
                <span class="text-xs text-slate-400 mt-0.5 block">Undergraduate to Doctoral</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Funded Research</span>
                <span class="text-2xl font-extrabold text-indigo-700 font-heading tabular-nums mt-1 block">
                    ₱{{ number_format($fundedMin, 0) }} - ₱{{ number_format($fundedMax, 0) }}
                </span>
                <span class="text-xs text-slate-400 mt-0.5 block">Institutional & external grants</span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex-1 min-w-0 max-w-md relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="categorySearch" placeholder="Search categories..." oninput="filterCategoriesTable()"
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[40px]">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap hidden sm:inline-block">Filter:</label>
                <select id="classificationFilter" onchange="filterCategoriesTable()"
                        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[40px]">
                    <option value="">All Classifications</option>
                    <option value="Course Requirement">Course Requirement</option>
                    <option value="Funded Research">Funded Research</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <!-- Categories Ledger Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden min-w-0">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse min-w-[720px]" id="categoriesTable">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/75">
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Category & Scope</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Classification</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Submission Tariff</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="py-4 px-6 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Governance Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/60 transition-colors category-row" 
                                data-name="{{ strtolower($category->name) }}" 
                                data-classification="{{ $category->classification ?? 'Other' }}">
                                <td class="py-4 px-6">
                                    <div class="font-extrabold text-slate-900 text-sm font-heading">{{ $category->name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">ID: #RC-{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($category->classification === 'Funded Research')
                                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-700">Funded Research</span>
                                    @elseif($category->classification === 'Course Requirement')
                                        <span class="text-xs font-bold uppercase tracking-wider text-blue-700">Course Requirement</span>
                                    @else
                                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $category->classification ?? 'Other' }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-xs font-bold text-slate-400">PHP</span>
                                        <span class="text-base font-extrabold text-slate-900 font-heading tabular-nums">
                                            ₱{{ number_format($category->fee, 2) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($category->active)
                                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Active</span>
                                    @else
                                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-2 justify-end">
                                        <button type="button" 
                                                onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->fee }}, {{ $category->active }}, '{{ $category->classification ?? 'Other' }}')" 
                                                class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors min-h-[36px]"
                                                title="Edit category details and tariff">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button type="button" 
                                                onclick="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                                class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors min-h-[36px]"
                                                title="Delete category">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="5" class="py-12 px-6 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-700">No research categories found</p>
                                    <p class="text-xs text-slate-400 mt-1">Get started by defining institutional submission categories.</p>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="noFilterMatchRow" class="hidden">
                            <td colspan="5" class="py-12 px-6 text-center text-slate-400">
                                <p class="font-bold text-slate-700">No categories match your search filters</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting keywords or selecting all classifications.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Add Category Modal -->
    <div id="addFeeModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="addModalTitle">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeAddModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    <form action="{{ route('super_admin.fees.store') }}" method="POST">
                        @csrf
                        <div class="p-6 sm:p-7 space-y-6">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                                <div>
                                    <h3 id="addModalTitle" class="text-lg font-extrabold text-slate-900 font-heading">Add Research Category</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Define a new research protocol classification and submission tariff.</p>
                                </div>
                                <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Category Name <span class="text-rose-600">*</span></label>
                                    <input type="text" name="name" required placeholder="e.g. Undergraduate Thesis"
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Submission Tariff Fee (₱) <span class="text-rose-600">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">₱</span>
                                        <input type="number" step="0.01" min="0" name="fee" required placeholder="0.00"
                                               class="w-full pl-8 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                    </div>
                                    <p class="text-[11px] text-slate-400">Official receipt amount required from researcher during protocol intake.</p>
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Classification <span class="text-rose-600">*</span></label>
                                    <select name="classification" required
                                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                        <option value="Course Requirement" selected>Course Requirement</option>
                                        <option value="Funded Research">Funded Research</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-200">
                            <button type="button" onclick="closeAddModal()" 
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition-colors min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs min-h-[44px]">
                                Save Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editFeeModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeEditModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    <form id="editFeeForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="p-6 sm:p-7 space-y-6">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                                <div>
                                    <h3 id="editModalTitle" class="text-lg font-extrabold text-slate-900 font-heading">Edit Research Category</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Modify pricing structure and active visibility.</p>
                                </div>
                                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Category Name <span class="text-rose-600">*</span></label>
                                    <input type="text" name="name" id="edit_name" required
                                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Submission Tariff Fee (₱) <span class="text-rose-600">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">₱</span>
                                        <input type="number" step="0.01" min="0" name="fee" id="edit_fee" required
                                               class="w-full pl-8 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Classification <span class="text-rose-600">*</span></label>
                                    <select name="classification" id="edit_classification" required
                                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none transition-all min-h-[44px]">
                                        <option value="Funded Research">Funded Research</option>
                                        <option value="Course Requirement">Course Requirement</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="pt-2">
                                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100/70 transition-colors">
                                        <input type="checkbox" name="active" id="edit_active" value="1" 
                                               class="w-4 h-4 rounded text-[#8B0000] focus:ring-[#8B0000] border-slate-300">
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 block">Active Status</span>
                                            <span class="text-[11px] text-slate-500 block">Category will be selectable by researchers during initial protocol intake.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-200">
                            <button type="button" onclick="closeEditModal()" 
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition-colors min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-2.5 bg-[#8B0000] text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#6d0000] active:scale-[0.98] transition-all shadow-xs min-h-[44px]">
                                Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Accessible Delete Confirmation Modal -->
    <div id="deleteFeeModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200">
                    <form id="deleteFeeForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="p-6 sm:p-7 space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto sm:mx-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div>
                                <h3 id="deleteModalTitle" class="text-lg font-extrabold text-slate-900 font-heading">Delete Category</h3>
                                <p class="text-xs text-slate-500 mt-1">Are you sure you want to delete <span id="deleteCategoryName" class="font-bold text-slate-800"></span>? Past protocol submissions and logged official receipts will remain intact.</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-200">
                            <button type="button" onclick="closeDeleteModal()" 
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-100 transition-colors min-h-[44px]">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-rose-700 active:scale-[0.98] transition-all shadow-xs min-h-[44px]">
                                Confirm Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal & Filter Scripts -->
    <script>
        function openAddModal() {
            document.getElementById('addFeeModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addFeeModal').classList.add('hidden');
        }

        function openEditModal(id, name, fee, active, classification) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_fee').value = fee;
            document.getElementById('edit_active').checked = active == 1;
            document.getElementById('edit_classification').value = classification;
            
            document.getElementById('editFeeForm').action = "/super-admin/manage-fees/" + id;
            document.getElementById('editFeeModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editFeeModal').classList.add('hidden');
        }

        function openDeleteModal(id, name) {
            document.getElementById('deleteCategoryName').textContent = name;
            document.getElementById('deleteFeeForm').action = "/super-admin/manage-fees/" + id;
            document.getElementById('deleteFeeModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteFeeModal').classList.add('hidden');
        }

        // Global Escape Key Listener for Modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
                closeDeleteModal();
            }
        });

        // Client-side search and classification filtering
        function filterCategoriesTable() {
            const query = (document.getElementById('categorySearch').value || '').toLowerCase().trim();
            const filter = document.getElementById('classificationFilter').value;
            const rows = document.querySelectorAll('.category-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const classification = row.getAttribute('data-classification') || '';

                const matchesQuery = !query || name.includes(query);
                const matchesClassification = !filter || classification === filter;

                if (matchesQuery && matchesClassification) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noMatchRow = document.getElementById('noFilterMatchRow');
            if (noMatchRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noMatchRow.classList.remove('hidden');
                } else {
                    noMatchRow.classList.add('hidden');
                }
            }
        }
    </script>
</x-super_admin_layout>
