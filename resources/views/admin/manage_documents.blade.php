<x-admin_layout>
    <x-slot name="title">Manage Documents</x-slot>

    <div x-data="{
        isModalOpen: false,
        isDeleteModalOpen: false,
        isEditing: false,
        formAction: '',
        deleteAction: '',
        formData: {
            name: '',
            description: '',
            is_required: true,
            is_multiple: false,
            is_viewable_for_reviewer: true,
            is_downloadable_for_reviewer: true,
            file_type: ''
        },
        selectedFileTypes: [],
        baseStoreUrl: '{{ route('admin.document_requirements.store') }}',
        baseUpdateUrl: '{{ url('admin/document-requirements') }}', // Using URL to append ID easily

        openAddModal() {
            this.isEditing = false;
            this.formAction = this.baseStoreUrl;
            this.formData = {
                name: '',
                description: '',
                is_required: true,
                is_multiple: false,
                is_viewable_for_reviewer: true,
                is_downloadable_for_reviewer: true,
                file_type: ''
            };
            this.selectedFileTypes = ['PDF']; // Default to PDF
            this.isModalOpen = true;
        },

        openEditModal(doc) {
            this.isEditing = true;
            this.formAction = `${this.baseUpdateUrl}/${doc.id}`;
            this.formData = {
                name: doc.name,
                description: doc.description,
                is_required: Boolean(doc.is_required),
                is_multiple: Boolean(doc.is_multiple),
                is_viewable_for_reviewer: Boolean(doc.is_viewable_for_reviewer),
                is_downloadable_for_reviewer: Boolean(doc.is_downloadable_for_reviewer),
                file_type: doc.file_type
            };
            // Split string into array for checkboxes, trimming whitespace
            this.selectedFileTypes = doc.file_type ? doc.file_type.split(',').map(item => item.trim()) : [];
            this.isModalOpen = true;
        },

        confirmDelete(actionUrl) {
            this.deleteAction = actionUrl;
            this.isDeleteModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false;
            this.isDeleteModalOpen = false;
        }
    }" class="space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Document Requirements</h2>
                <p class="text-slate-500 text-sm">Manage the list of documents researchers are required to upload.</p>
            </div>
            <button @click="openAddModal()" 
                class="bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold hover:bg-red-800 transition-all flex items-center gap-2 shadow-lg shadow-red-900/20">
                <i class="fas fa-plus"></i>
                <span>Add Requirement</span>
            </button>
        </div>

        <!-- Content Area -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Document Name</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4 text-center">Multiple</th>
                            <th class="px-6 py-4 text-center">Required</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($documents as $doc)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $doc->name }}</td>
                                <td class="px-6 py-4 text-slate-600 max-w-md truncate" title="{{ $doc->description }}">{{ $doc->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600 font-mono text-xs">{{ $doc->file_type ?? 'PDF' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($doc->is_multiple)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Yes
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $doc->is_required ? 'bg-red-50 text-[#8B0000]' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $doc->is_required ? 'Required' : 'Optional' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 flex justify-end">
                                    <button @click="openEditModal({{ $doc }})" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors inline-block" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <button @click="confirmDelete('{{ route('admin.document_requirements.destroy', $doc->id) }}')" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors inline-block" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fas fa-folder-open text-4xl mb-3 opacity-50"></i>
                                    <p>No requirements found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div x-show="isModalOpen" 
             style="display: none"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <!-- Backdrop -->
            <div x-show="isModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
                 @click="closeModal()"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="isModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-200">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <form :action="formAction" method="POST" id="documentForm">
                            @csrf
                            <!-- Method Spoofing for PUT -->
                            <input type="hidden" name="_method" value="PUT" :disabled="!isEditing">

                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-file-contract text-[#8B0000]"></i>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-xl font-bold leading-6 text-slate-900" id="modal-title" x-text="isEditing ? 'Edit Requirement' : 'Add New Requirement'"></h3>
                                    <p class="text-sm text-slate-500 mt-1">Fill in the details below to <span x-text="isEditing ? 'update' : 'create'"></span> a requirement.</p>
                                    
                                    <div class="mt-6 space-y-5">
                                        
                                        <!-- Name Field -->
                                        <div>
                                            <label for="name" class="block text-sm font-bold text-slate-700 mb-1">Document Name <span class="text-red-500">*</span></label>
                                            <input type="text" name="name" x-model="formData.name" id="name" 
                                                class="w-full rounded-xl border-slate-300 bg-slate-50 shadow-sm focus:border-[#8B0000] focus:ring-[#8B0000] focus:bg-white text-sm py-3 px-4 transition-all duration-200 placeholder:text-slate-400" 
                                                placeholder="e.g. Application Form" required>
                                        </div>

                                        <!-- Description Field -->
                                        <div>
                                            <label for="description" class="block text-sm font-bold text-slate-700 mb-1">Description <span class="text-slate-400 font-normal">(Optional)</span></label>
                                            <textarea name="description" x-model="formData.description" id="description" rows="3" 
                                                class="w-full rounded-xl border-slate-300 bg-slate-50 shadow-sm focus:border-[#8B0000] focus:ring-[#8B0000] focus:bg-white text-sm py-3 px-4 transition-all duration-200 placeholder:text-slate-400"
                                                placeholder="Brief description or instructions..."></textarea>
                                        </div>

                                        <!-- File Type Field (Checkboxes) -->
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Allowed File Types <span class="text-red-500">*</span></label>
                                            <div class="grid grid-cols-2 gap-3">
                                                <label class="relative flex items-center p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors cursor-pointer">
                                                    <input type="checkbox" value="PDF" x-model="selectedFileTypes" class="h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                                    <span class="ml-2 text-sm font-bold text-slate-700">PDF</span>
                                                </label>
                                                <label class="relative flex items-center p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors cursor-pointer">
                                                    <input type="checkbox" value="Word" x-model="selectedFileTypes" class="h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                                    <span class="ml-2 text-sm font-bold text-slate-700">Word (DOC/DOCX)</span>
                                                </label>
                                                <label class="relative flex items-center p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors cursor-pointer">
                                                    <input type="checkbox" value="Others" x-model="selectedFileTypes" class="h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                                    <span class="ml-2 text-sm font-bold text-slate-700">Others</span>
                                                </label>
                                            </div>
                                            <!-- Hidden input to store joined array as string -->
                                            <input type="hidden" name="file_type" :value="selectedFileTypes.join(', ')">
                                        </div>

                                        <!-- Checkboxes -->
                                        <div class="space-y-3">
                                            <!-- Required Checkbox -->
                                            <div class="relative flex items-start p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors cursor-pointer" @click="$refs.is_required.click()">
                                                <div class="flex h-6 items-center">
                                                    <input id="is_required" x-ref="is_required" name="is_required" type="checkbox" value="1" x-model="formData.is_required" 
                                                        class="h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] cursor-pointer">
                                                </div>
                                                <div class="ml-3 text-sm leading-6">
                                                    <label for="is_required" class="font-bold text-slate-900 cursor-pointer">Require this document</label>
                                                    <p class="text-slate-500 text-xs">Researchers must upload this to proceed.</p>
                                                </div>
                                            </div>

                                            <!-- Multiple Files Checkbox -->
                                            <div class="relative flex items-start p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors cursor-pointer" @click="$refs.is_multiple.click()">
                                                <div class="flex h-6 items-center">
                                                    <input id="is_multiple" x-ref="is_multiple" name="is_multiple" type="checkbox" value="1" x-model="formData.is_multiple" 
                                                        class="h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000] cursor-pointer">
                                                </div>
                                                <div class="ml-3 text-sm leading-6">
                                                    <label for="is_multiple" class="font-bold text-slate-900 cursor-pointer">Allow Multiple Files</label>
                                                    <p class="text-slate-500 text-xs">Users can upload more than one file for this requirement.</p>
                                                </div>
                                            </div>

                                            <div class="h-px bg-slate-100 my-2"></div>
                                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest pl-1 mb-2">Reviewer Access Configurations</p>

                                            <!-- Reviewer View Checkbox -->
                                            <div class="relative flex items-start p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors cursor-pointer" @click="$refs.is_viewable_for_reviewer.click()">
                                                <div class="flex h-6 items-center">
                                                    <input id="is_viewable_for_reviewer" x-ref="is_viewable_for_reviewer" name="is_viewable_for_reviewer" type="checkbox" value="1" x-model="formData.is_viewable_for_reviewer" 
                                                        class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                                </div>
                                                <div class="ml-3 text-sm leading-6">
                                                    <label for="is_viewable_for_reviewer" class="font-bold text-slate-900 cursor-pointer">Allow Reviewers to Preview</label>
                                                    <p class="text-slate-500 text-xs">If enabled, reviewers can see the document inside their embedded browser UI.</p>
                                                </div>
                                            </div>

                                            <!-- Reviewer Download Checkbox -->
                                            <div class="relative flex items-start p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors cursor-pointer" @click="$refs.is_downloadable_for_reviewer.click()">
                                                <div class="flex h-6 items-center">
                                                    <input id="is_downloadable_for_reviewer" x-ref="is_downloadable_for_reviewer" name="is_downloadable_for_reviewer" type="checkbox" value="1" x-model="formData.is_downloadable_for_reviewer" 
                                                        class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                                </div>
                                                <div class="ml-3 text-sm leading-6">
                                                    <label for="is_downloadable_for_reviewer" class="font-bold text-slate-900 cursor-pointer">Allow Reviewers to Download</label>
                                                    <p class="text-slate-500 text-xs">If enabled, reviewers can officially download a raw copy of this file to their machine.</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100 gap-3 mt-8 -mx-4 -mb-4 sm:-mx-6 sm:-mb-6">
                                <button type="submit" 
                                    class="inline-flex w-full justify-center rounded-xl bg-[#8B0000] px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-red-800 sm:ml-3 sm:w-auto transition-all shadow-red-900/20">
                                    <span x-text="isEditing ? 'Update Requirement' : 'Create Requirement'"></span>
                                </button>
                                <button type="button" @click="closeModal()" 
                                    class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="isDeleteModalOpen" 
             style="display: none"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <!-- Backdrop -->
            <div x-show="isDeleteModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
                 @click="closeModal()"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="isDeleteModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Delete Requirement</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500">Are you sure you want to delete this document requirement? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100 gap-2">
                        <form :action="deleteAction" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="inline-flex w-full justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-all shadow-red-900/20">
                                Delete
                            </button>
                        </form>
                        <button type="button" @click="closeModal()" 
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-admin_layout>
