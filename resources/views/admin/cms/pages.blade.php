<x-admin_layout title="Page Content Manager">
    <div class="max-w-7xl mx-auto py-8 relative">
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2 animate-[fadeIn_0.5s]">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif



        <div class="flex flex-col lg:flex-row gap-8 items-start">
                


                <!-- Main Content Area -->
                <div class="flex-1 space-y-8 w-full">
                    

                    <!-- Downloadables Manager -->
                    <div id="section-downloadables" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                Manage Downloadable Forms
                            </h3>
                            <button type="button" onclick="document.getElementById('add-downloadable-modal').classList.remove('hidden')" class="bg-[#0f172a] text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-slate-800 transition-colors flex items-center gap-2">
                                <i class="fas fa-plus"></i> Add New Form
                            </button>
                        </div>

                        <!-- Existing Downloadables Table -->
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                                        <th class="p-4 font-bold">Code / Title</th>
                                        <th class="p-4 font-bold">File Info</th>
                                        <th class="p-4 font-bold">Type</th>
                                        <th class="p-4 font-bold text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    @forelse($downloadables as $resource)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="p-4">
                                                <div class="font-bold text-slate-900">{{ $resource->title }}</div>
                                                @if($resource->code)
                                                    <div class="text-[10px] font-bold text-white bg-slate-600 px-1.5 py-0.5 rounded inline-block mt-1">{{ $resource->code }}</div>
                                                @endif
                                                <div class="text-xs text-slate-500 mt-1 max-w-xs truncate" title="{{ $resource->description }}">{{ $resource->description }}</div>
                                            </td>
                                            <td class="p-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">{{ $resource->file_extension }}</span>
                                                    <span class="text-xs text-slate-500">{{ $resource->file_size }}</span>
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                @if($resource->is_mandatory)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-[#8B0000]">
                                                        Mandatory
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                                        Supplementary
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <!-- Edit Button triggers modal, filling data via Alpine or JS -->
                                                    <button type="button" onclick="editDownloadable({{ $resource->id }}, '{{ addslashes($resource->title) }}', '{{ addslashes($resource->code) }}', '{{ addslashes($resource->description) }}', {{ $resource->is_mandatory ? 'true' : 'false' }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="Edit">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </button>
                                                    <form action="{{ route('admin.cms.downloadables.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this resource?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors" title="Delete">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-8 text-center text-slate-500">
                                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
                                                    <i class="fas fa-file-excel text-2xl text-slate-300"></i>
                                                </div>
                                                <p class="font-medium text-slate-900">No resources uploaded yet.</p>
                                                <p class="text-sm mt-1">Click "Add New Form" to get started.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>
            </div>



    </div>

    <!-- Modals -->
    <!-- Add Modal -->
    <div id="add-downloadable-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-lg m-4 relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="document.getElementById('add-downloadable-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-plus-circle text-[#8B0000]"></i> Add New Downloadable
            </h3>
            <form action="{{ route('admin.cms.downloadables.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="e.g., Application Form" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#8B0000]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Code (Optional)</label>
                        <input type="text" name="code" placeholder="e.g., FR.002" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#8B0000]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Description</label>
                        <textarea name="description" rows="3" placeholder="Brief description..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#8B0000]"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">File <span class="text-red-500">*</span></label>
                        <input type="file" name="file" required class="w-full text-sm">
                        <p class="text-[10px] text-slate-400 mt-1">Max size: 25MB. PDF, DOCX, DOC</p>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="is_mandatory" value="1" id="is_mandatory_add" class="rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                        <label for="is_mandatory_add" class="text-sm font-medium text-slate-700">Mark as Mandatory Form</label>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('add-downloadable-modal').classList.add('hidden')" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-100 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-[#8B0000] text-white font-bold rounded-lg shadow-md hover:bg-red-800 transition-colors">Save Resource</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-downloadable-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-lg m-4 relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="document.getElementById('edit-downloadable-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-edit text-[#8B0000]"></i> Edit Downloadable
            </h3>
            <form id="edit-downloadable-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="edit_title" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#8B0000]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Code (Optional)</label>
                        <input type="text" name="code" id="edit_code" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#8B0000]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#8B0000]"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Replace File (Optional)</label>
                        <input type="file" name="file" class="w-full text-sm">
                        <p class="text-[10px] text-slate-400 mt-1">Leave blank to keep existing file.</p>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="is_mandatory" value="1" id="edit_is_mandatory" class="rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                        <label for="edit_is_mandatory" class="text-sm font-medium text-slate-700">Mark as Mandatory Form</label>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('edit-downloadable-modal').classList.add('hidden')" class="px-4 py-2 text-slate-600 font-bold hover:bg-slate-100 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-[#8B0000] text-white font-bold rounded-lg shadow-md hover:bg-red-800 transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editDownloadable(id, title, code, description, is_mandatory) {
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_is_mandatory').checked = is_mandatory;
            
            let form = document.getElementById('edit-downloadable-form');
            // Assuming the APP_URL isn't explicitly needed, relative routes work.
            form.action = "{{ url('/admin/cms/downloadables') }}/" + id;
            
            document.getElementById('edit-downloadable-modal').classList.remove('hidden');
        }
    </script>
</x-admin_layout>
