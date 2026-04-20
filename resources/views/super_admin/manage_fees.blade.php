<x-super_admin_layout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 font-heading">Manage Fees</h1>
                <p class="text-sm text-slate-500 mt-1">Configure pricing for different research categories</p>
            </div>
            <button onclick="document.getElementById('addFeeModal').classList.remove('hidden')" class="px-4 py-2.5 bg-[#8B0000] text-white text-sm font-bold rounded-xl hover:bg-red-800 transition-colors shadow-lg shadow-red-900/20 flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Category
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-100 flex items-center gap-3 text-sm font-medium animate-[fadeIn_0.5s_ease-out]">
                <i class="fas fa-check-circle text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-100 flex items-start gap-3 text-sm font-medium">
                <i class="fas fa-exclamation-circle text-lg mt-0.5"></i>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Categories Table -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Category Name</th>
                            <th class="p-6">Classification</th>
                            <th class="p-6">Current Fee</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="p-6">
                                    <p class="font-bold text-slate-800">{{ $category->name }}</p>
                                </td>
                                <td class="p-6">
                                    <span class="px-3 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $category->classification ?? 'Other' }}
                                    </span>
                                </td>
                                <td class="p-6">
                                    <span class="font-bold text-[#8B0000] bg-red-50 px-3 py-1 rounded-lg">
                                        ₱ {{ number_format($category->fee, 2) }}
                                    </span>
                                </td>
                                <td class="p-6">
                                    @if($category->active)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">Inactive</span>
                                    @endif
                                </td>
                                <td class="p-6 text-right">
                                    <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->fee }}, {{ $category->active }}, '{{ $category->classification ?? 'Other' }}')" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('super_admin.fees.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category? This will not affect past submissions.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-slate-400">
                                    <i class="fas fa-tags text-4xl mb-4 text-slate-300"></i>
                                    <p>No research categories found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addFeeModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addFeeModal').classList.add('hidden')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form action="{{ route('super_admin.fees.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <i class="fas fa-plus-circle text-[#8B0000]"></i> Add Research Category
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Category Name</label>
                                    <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000]/20" required placeholder="e.g. Undergraduate Thesis">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Submission Fee (₱)</label>
                                    <input type="number" step="0.01" name="fee" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000]/20" required placeholder="0.00">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Classification</label>
                                    <select name="classification" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000]/20" required>
                                        <option value="Funded Research">Funded Research</option>
                                        <option value="Course Requirement">Course Requirement</option>
                                        <option value="Other" selected>Other (None)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2 border-t border-slate-100">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-[#8B0000] px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-red-800 transition-colors">
                                Save Category
                            </button>
                            <button type="button" onclick="document.getElementById('addFeeModal').classList.add('hidden')" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editFeeModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editFeeModal').classList.add('hidden')"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form id="editFeeForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <i class="fas fa-edit text-blue-500"></i> Edit Research Category
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Category Name</label>
                                    <input type="text" name="name" id="edit_name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000]/20" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Submission Fee (₱)</label>
                                    <input type="number" step="0.01" name="fee" id="edit_fee" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000]/20" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Classification</label>
                                    <select name="classification" id="edit_classification" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#8B0000] focus:ring-2 focus:ring-[#8B0000]/20" required>
                                        <option value="Funded Research">Funded Research</option>
                                        <option value="Course Requirement">Course Requirement</option>
                                        <option value="Other">Other (None)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer mt-4">
                                        <input type="checkbox" name="active" id="edit_active" value="1" class="rounded text-[#8B0000] focus:ring-[#8B0000] w-5 h-5">
                                        <span class="text-sm font-medium text-slate-700">Active (Visible to Researchers)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2 border-t border-slate-100">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition-colors">
                                Update Category
                            </button>
                            <button type="button" onclick="document.getElementById('editFeeModal').classList.add('hidden')" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name, fee, active, classification) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_fee').value = fee;
            document.getElementById('edit_active').checked = active == 1 ? true : false;
            document.getElementById('edit_classification').value = classification;
            
            // Set form action dynamically
            document.getElementById('editFeeForm').action = "/super-admin/manage-fees/" + id;
            
            document.getElementById('editFeeModal').classList.remove('hidden');
        }
    </script>
</x-super_admin_layout>
