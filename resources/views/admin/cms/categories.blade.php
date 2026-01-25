<x-admin_layout title="Research Categories">
    <div class="max-w-6xl mx-auto py-8" x-data="{ showEditModal: false, editCategory: {} }">
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Create New -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Add New Category</h3>
            <form action="{{ route('admin.cms.categories.store') }}" method="POST" class="flex gap-4 items-end">
                @csrf
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Category Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Description (Optional)</label>
                    <input type="text" name="description" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none">
                </div>
                <button type="submit" class="bg-[#8B0000] text-white px-6 py-2.5 rounded-lg font-bold hover:bg-red-800 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Add
                </button>
            </form>
        </div>

        <!-- List -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Existing Categories</h3>
            </div>
            
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-slate-600 text-sm">{{ $category->description ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($category->active)
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Active</span>
                                @else
                                    <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-bold">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button @click="showEditModal = true; editCategory = {{ $category }}" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.cms.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showEditModal = false"></div>
            <div class="bg-white rounded-2xl w-full max-w-lg relative z-10 p-6 shadow-xl animate-[fadeInUp_0.3s_ease-out]">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Edit Category</h3>
                
                <form :action="'{{ route('admin.cms.categories.update', 0) }}'.replace('/0', '/' + editCategory.id)" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Name</label>
                            <input type="text" name="name" x-model="editCategory.name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                            <textarea name="description" x-model="editCategory.description" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none"></textarea>
                        </div>
                        <div class="flex items-center gap-2">
                             <input type="checkbox" name="active" value="1" id="active" :checked="editCategory.active == 1" class="rounded text-[#8B0000] focus:ring-[#8B0000]">
                             <label for="active" class="text-sm font-bold text-slate-700">Active</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-100 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" class="bg-[#8B0000] text-white px-6 py-2 rounded-lg font-bold hover:bg-red-800 transition-colors">Update Category</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin_layout>
