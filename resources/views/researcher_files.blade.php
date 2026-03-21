<x-user_layout>
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-200 pb-8">
            <div class="space-y-2">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 text-slate-500 hover:text-[#8B0000] transition-colors mb-2 text-sm font-medium">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-red-50 transition-colors">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Back to Titles
                </a>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Document Manager</h2>
                <div class="flex items-center gap-2 text-slate-500 text-sm">
                    <span>Manage uploads for:</span>
                    <span class="font-bold text-[#8B0000] bg-red-50 px-3 py-1 rounded-full border border-red-100">
                        {{ $researchTitle->Study_Protocol_title }}
                    </span>
                </div>
            </div>

            @php
                $canSubmit = in_array($researchTitle->Status, ['Waiting for Revision', 'Incomplete']);
                $isRevision = $researchTitle->Status === 'Waiting for Revision';
                $submitLabel = $isRevision ? 'Submit Revisions' : 'Submit Corrections';
                $submitIcon = $isRevision ? 'fa-paper-plane' : 'fa-check-circle';
            @endphp

            @if($canSubmit)
            <button onclick="document.getElementById('revisionModal').classList.remove('hidden')" 
                    class="group relative inline-flex items-center gap-3 bg-[#8B0000] text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-red-900/30 hover:-translate-y-1 transition-all duration-300">
                <i class="fas {{ $submitIcon }} text-lg group-hover:rotate-12 transition-transform"></i>
                <span>{{ $submitLabel }}</span>
            </button>
            @endif
        </div>



        <!-- Revision/Correction Modal -->
        @if($canSubmit)
        <div id="revisionModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('revisionModal').classList.add('hidden')"></div>
            
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                        <form action="{{ route('submit.revisions', $researchTitle->id) }}" method="POST">
                            @csrf
                            
                            <!-- Premium Header -->
                            <div class="px-8 py-6 bg-[#8B0000] relative overflow-hidden">
                                <!-- Background Pattern/Icon -->
                                <div class="absolute -right-6 -top-6 text-white/10 pointer-events-none">
                                    <i class="fas fa-paper-plane text-[150px] rotate-12"></i>
                                </div>
                                
                                <div class="relative z-10 flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold text-white tracking-tight flex items-center gap-3">
                                            <span class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                                <i class="fas fa-paper-plane text-lg text-white"></i>
                                            </span>
                                            {{ $submitLabel }}
                                        </h3>
                                        <p class="text-red-100 text-sm mt-2 font-medium opacity-90">
                                            @if($isRevision)
                                                Upload revised documents & submit.
                                            @else
                                                Submit corrected documents for review.
                                            @endif
                                        </p>
                                    </div>
                                    <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')" 
                                        class="text-white/70 hover:text-white hover:bg-white/20 rounded-lg p-1 transition-all">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="px-8 py-8 bg-white">
                                <div class="space-y-6">
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex gap-4 items-start">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0 text-blue-600">
                                            <i class="fas fa-info-circle text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-sm">Review Process</h4>
                                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                                @if($isRevision)
                                                    Your submission will be marked as <strong>Waiting for Approval</strong> until reviewed.
                                                @else
                                                    Submitting will notify the Research Ethics Office to process your corrections.
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="revision_message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Add a Note <span class="text-slate-400 font-normal normal-case">(Optional)</span>
                                        </label>
                                        <div class="relative group">
                                            <textarea name="revision_message" id="revision_message" rows="3" 
                                                class="w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-[#8B0000] focus:ring-[#8B0000] text-sm placeholder-slate-400 py-3 px-4 resize-none transition-all group-hover:border-slate-300"
                                                placeholder="{{ $isRevision ? 'Briefly describe your changes...' : 'E.g., Added missing signature page...' }}"></textarea>
                                            <div class="absolute bottom-3 right-3 text-slate-300 pointer-events-none group-focus-within:text-[#8B0000] transition-colors">
                                                <i class="fas fa-pen text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="bg-slate-50 px-8 py-5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100">
                                <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')"
                                    class="inline-flex w-full justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 hover:text-slate-800 sm:w-auto transition-all">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="inline-flex w-full justify-center items-center gap-2 rounded-xl bg-[#8B0000] px-8 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-red-900/40 hover:-translate-y-0.5 sm:w-auto transition-all duration-300">
                                    <span>Confirm Submission</span>
                                    <i class="fas fa-arrow-right text-xs opacity-70"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
            <div class="animate-[fadeInUp_0.3s_ease-out] bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl mb-8 flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-900">Success</h4>
                    <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @php
            $allFiles = $researchTitle->files->merge($researchTitle->adminFiles ?? collect());
            
            $letters = $allFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->sortByDesc('created_at');
            $protocolDocs = $researchTitle->files->whereNotIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter']);

            // "Draft Workspace" = files with revision_number = -1
            $draftFiles = $protocolDocs->where('revision_number', -1)->sortByDesc('created_at');
            
            // "Current Documents" - The absolute latest version of each category (excluding drafts)
            $activeFiles = $protocolDocs->where('revision_number', '!=', -1)->sortByDesc(function ($file) {
                return $file->revision_number ?? 0;
            })->unique('category')->sortByDesc('created_at');

            // "Original Documents" = revision_number === null. This is always the very first file uploaded for a category.
            $originalFiles = $protocolDocs->whereNull('revision_number')->sortByDesc('created_at');
            
            // "Revision Folders" = grouped by revision_number (where > 0, ignoring -1)
            $archivedFiles = $protocolDocs->where('revision_number', '>', 0)->sortByDesc('created_at');
            $revisionFolders = $archivedFiles->groupBy('revision_number')->sortKeys();

            // Next Revision Number
            $nextRevisionNumber = ($protocolDocs->where('revision_number', '>', 0)->max('revision_number') ?? 0) + 1;
            
            $isWaitingForRevision = $researchTitle->Status === 'Waiting for Revision';
            $hasDraftFiles = $draftFiles->isNotEmpty();
        @endphp

        <!-- Recommendation Letters Section -->
        @if($letters->isNotEmpty())
        <div class="mb-10">
            <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-envelope-open-text text-emerald-600"></i>
                Recommendation Letters
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($letters as $file)
                    <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
                        <div class="p-5 flex items-start gap-4 border-b border-emerald-50 bg-emerald-50/30">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                <i class="fas fa-certificate text-xl"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-800 text-sm leading-snug truncate mb-1" title="{{ $file->filename }}">
                                    {{ $file->filename }}
                                </h4>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">
                                    Official Document
                                </span>
                            </div>
                        </div>

                         <!-- Preview Area -->
                        <div class="relative bg-slate-50 flex-1 min-h-[150px] border-b border-slate-100 group-hover:bg-slate-100 transition-colors overflow-hidden">
                             <iframe src="{{ asset($file->filepath) }}#toolbar=0&navpanes=0&scrollbar=0" class="w-full h-full border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
                             <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                                <a href="{{ asset($file->filepath) }}" target="_blank" class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-emerald-600 hover:text-white flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i> View Fullscreen
                                </a>
                            </div>
                        </div>

                        <!-- Read-Only Actions -->
                        <div class="p-4 bg-white">
                            <a href="{{ asset($file->filepath) }}" download class="block w-full py-2.5 px-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 hover:text-emerald-900 rounded-xl text-sm font-bold text-center transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($activeFiles->isNotEmpty() || $originalFiles->isNotEmpty() || $revisionFolders->isNotEmpty())
        <div x-data="{ 
            activeTab: $persist('original').as('reo_rt_{{ $researchTitle->id }}_tab'),
            isDrafting: {{ $hasDraftFiles ? 'true' : 'false' }},
            startDrafting() {
                this.isDrafting = true;
                this.activeTab = 'draft';
            }
        }" class="mb-10">
            <!-- Action Bar -->
            @if($isWaitingForRevision && !$hasDraftFiles)
            <div x-show="!isDrafting" class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-2xl flex items-center justify-between shadow-sm">
                <div>
                    <h4 class="text-lg font-bold text-blue-900 mb-1">Revisions Required</h4>
                    <p class="text-blue-700 text-sm">Please create a new Revision Workspace to upload your corrected documents.</p>
                </div>
                <button @click="startDrafting()" type="button" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="fas fa-plus"></i> Create Revision {{ $nextRevisionNumber }}
                </button>
            </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="flex gap-2 border-b border-slate-200 mb-8 overflow-x-auto custom-scrollbar">
                
                {{-- Original Documents tab: always shown when files with revision_number=null exist or when Incomplete --}}
                @if($originalFiles->isNotEmpty() || $researchTitle->Status === 'Incomplete')
                <button @click="activeTab = 'original'" 
                    :class="activeTab === 'original' ? 'border-[#8B0000] text-[#8B0000]' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="pb-3 px-5 text-sm font-bold border-b-2 whitespace-nowrap transition-all flex items-center gap-2">
                    <i class="fas fa-box-archive"></i> Original Documents
                </button>
                @endif
                
                {{-- Revision Folder tabs --}}
                @foreach($revisionFolders->sortKeys() as $revNum => $files)
                    <button @click="activeTab = 'rev_{{ $revNum }}'" 
                        :class="activeTab === 'rev_{{ $revNum }}' ? 'border-[#8B0000] text-[#8B0000]' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                        class="pb-3 px-5 text-sm font-bold border-b-2 whitespace-nowrap transition-all flex items-center gap-2">
                        <i class="fas fa-folder"></i> Revision {{ $revNum }}
                    </button>
                @endforeach

                {{-- Current Documents tab: only shown when revisions exist, to show the latest --}}
                @if($revisionFolders->isNotEmpty() && $activeFiles->isNotEmpty())
                <button @click="activeTab = 'active'" 
                    :class="activeTab === 'active' ? 'border-[#8B0000] text-[#8B0000]' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="pb-3 px-5 text-sm font-bold border-b-2 whitespace-nowrap transition-all flex items-center gap-2">
                    <i class="fas fa-file-signature"></i> Current Documents
                </button>
                @endif

                @if($isWaitingForRevision)
                <button x-show="isDrafting" @click="activeTab = 'draft'" 
                    :class="activeTab === 'draft' ? 'border-orange-500 text-orange-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                    class="pb-3 px-5 text-sm font-bold border-b-2 whitespace-nowrap transition-all flex items-center gap-2">
                    <i class="fas fa-edit"></i> Drafting Revision {{ $nextRevisionNumber }}
                </button>
                @endif
            </div>

            <!-- ORIGINAL DOCUMENTS VIEW: always shown, always the default tab -->
            @if($originalFiles->isNotEmpty() || $researchTitle->Status === 'Incomplete')
            <div x-show="activeTab === 'original'" class="space-y-6">
                @php
                    $filesByCategory = $originalFiles->groupBy('category');
                    $renderedCategories = [];
                @endphp

                @foreach($requirements as $req)
                    @php
                        $categoryFiles = $filesByCategory->get($req->name, collect());
                        $renderedCategories[] = $req->name;
                        $shouldRender = $categoryFiles->isNotEmpty() || $researchTitle->Status === 'Incomplete';
                    @endphp

                    @if($shouldRender)
                    @php $pKey = 'rt_' . $researchTitle->id . '_orig_' . Str::slug($req->name); @endphp
                    <div x-data="{ expanded: $persist(false).as('{{ $pKey }}') }" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <!-- Header (Collapsible trigger) -->
                        <button @click="expanded = !expanded" class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-100 text-left">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $categoryFiles->isNotEmpty() ? 'bg-indigo-100 text-indigo-600' : 'bg-red-50 text-red-500' }}">
                                    <i class="fas {{ $categoryFiles->isNotEmpty() ? 'fa-folder-open' : 'fa-exclamation-triangle' }}"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-800 uppercase tracking-widest">{{ $req->name }}</h4>
                                    @if($categoryFiles->isNotEmpty())
                                        <p class="text-[10px] font-bold text-slate-400">{{ $categoryFiles->count() }} doc(s) submitted</p>
                                    @else
                                        <p class="text-[10px] font-bold text-red-400">Missing Requirement</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 shadow-sm group">
                                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 group-hover:text-slate-600" :class="expanded ? 'rotate-180' : ''"></i>
                            </div>
                        </button>
                        
                        <!-- Content Grid -->
                        <div x-show="expanded" x-transition class="p-6 bg-slate-50/30" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                @if($categoryFiles->isNotEmpty())
                                    @foreach($categoryFiles as $file)
                                        <x-researcher-file-card :file="$file" :researchTitle="$researchTitle" />
                                    @endforeach
                                    
                                    @if($researchTitle->Status === 'Incomplete' && $req->is_multiple)
                                        <div class="group bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center hover:bg-slate-100 transition-colors shadow-sm">
                                            <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center mb-3">
                                                <i class="fas fa-plus text-xl text-slate-400"></i>
                                            </div>
                                            <h4 class="font-bold text-slate-700 text-xs mb-1">{{ $req->name }}</h4>
                                            <span class="text-xs text-slate-500 mb-4 block">Add Another Document</span>
                                            
                                            <form action="{{ route('add.missing.file', $researchTitle->id) }}" method="POST" enctype="multipart/form-data" class="w-full">
                                                @csrf
                                                <input type="hidden" name="category" value="{{ $req->name }}">
                                                <label class="block cursor-pointer">
                                                    <div class="w-full py-2.5 px-4 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 text-xs font-bold transition-colors flex items-center justify-center gap-2">
                                                        <i class="fas fa-upload animate-bounce-hover"></i>
                                                        <span>Upload Additional</span>
                                                    </div>
                                                    @php
                                                        $accepts = [];
                                                        if (str_contains(strtolower($req->file_type), 'pdf')) $accepts[] = '.pdf';
                                                        if (str_contains(strtolower($req->file_type), 'word')) array_push($accepts, '.doc', '.docx');
                                                        $acceptAttr = count($accepts) > 0 ? implode(',', $accepts) : '';
                                                    @endphp
                                                    <input type="file" name="file" class="hidden" onchange="this.form.submit()" accept="{{ $acceptAttr }}">
                                                </label>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    @if($researchTitle->Status === 'Incomplete')
                                        <div class="group bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center hover:bg-slate-100 transition-colors shadow-sm">
                                            <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center mb-3">
                                                <i class="fas fa-file-upload text-xl text-slate-400"></i>
                                            </div>
                                            <h4 class="font-bold text-slate-700 text-xs mb-1">{{ $req->name }}</h4>
                                            <span class="text-[10px] text-slate-500 mb-4 block">{{ $req->is_required ? 'Required Document' : 'Optional Document' }} (Not Submitted)</span>
                                            
                                            <form action="{{ route('add.missing.file', $researchTitle->id) }}" method="POST" enctype="multipart/form-data" class="w-full">
                                                @csrf
                                                <input type="hidden" name="category" value="{{ $req->name }}">
                                                <label class="block cursor-pointer">
                                                    <div class="w-full py-2.5 px-4 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 text-xs font-bold transition-colors flex items-center justify-center gap-2 group/upload">
                                                        <i class="fas fa-upload group-hover/upload:animate-bounce"></i>
                                                        <span>Upload File</span>
                                                    </div>
                                                    @php
                                                        $accepts = [];
                                                        if (str_contains(strtolower($req->file_type), 'pdf')) $accepts[] = '.pdf';
                                                        if (str_contains(strtolower($req->file_type), 'word')) array_push($accepts, '.doc', '.docx');
                                                        $acceptAttr = count($accepts) > 0 ? implode(',', $accepts) : '';
                                                    @endphp
                                                    <input type="file" name="file" class="hidden" onchange="this.form.submit()" accept="{{ $acceptAttr }}">
                                                </label>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
                
                @foreach($filesByCategory as $category => $categoryFiles)
                    @if(!in_array($category, $renderedCategories))
                        @php $pKey = 'rt_' . $researchTitle->id . '_orig_' . Str::slug($category ?? 'uncat'); @endphp
                        <div x-data="{ expanded: $persist(false).as('{{ $pKey }}') }" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                            <button @click="expanded = !expanded" class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-100 text-left">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-800 uppercase tracking-widest">{{ $category ?? 'Uncategorized' }}</h4>
                                        <p class="text-[10px] font-bold text-slate-400">{{ $categoryFiles->count() }} doc(s)</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 shadow-sm group">
                                    <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 group-hover:text-slate-600" :class="expanded ? 'rotate-180' : ''"></i>
                                </div>
                            </button>
                            <div x-show="expanded" x-transition class="p-6 bg-slate-50/30" style="display: none;">
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                    @foreach($categoryFiles as $file)
                                        <x-researcher-file-card :file="$file" :researchTitle="$researchTitle" />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @endif
            <!-- Active Documents (Current) View: only shows when revisions exist -->
            @if($revisionFolders->isNotEmpty() && $activeFiles->isNotEmpty())
            <div x-show="activeTab === 'active'">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($activeFiles as $file)
                @php 
                    $ext = strtolower($file->filetype);
                    $isPdf = $ext === 'pdf';
                    $isOffice = in_array($ext, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']);
                    
                    $displayName = $file->category ?? 'General Document';

                    $fileTypeLabel = match($file->filetype) {
                        'certificate' => 'Clearance Certificate',
                        default => strtoupper($ext) . ' Document'
                    };

                    if ($isPdf) {
                        $iconClass = 'fa-file-pdf text-[#8B0000]';
                        $bgClass = 'bg-red-50';
                    } elseif (in_array($ext, ['doc', 'docx'])) {
                        $iconClass = 'fa-file-word text-blue-600';
                        $bgClass = 'bg-blue-50';
                    } elseif (in_array($ext, ['ppt', 'pptx'])) {
                         $iconClass = 'fa-file-powerpoint text-orange-600';
                         $bgClass = 'bg-orange-50';
                    } elseif (in_array($ext, ['xls', 'xlsx'])) {
                         $iconClass = 'fa-file-excel text-green-600';
                         $bgClass = 'bg-green-50';
                    } else {
                        $iconClass = 'fa-file text-slate-400';
                        $bgClass = 'bg-slate-50';
                    }
                @endphp

                <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 hover:border-slate-300 transition-all duration-300 flex flex-col">
                    <!-- Header -->
                    <div class="p-5 flex items-start gap-4 border-b border-slate-50 bg-white relative z-10">
                        <div class="w-12 h-12 rounded-xl {{ $bgClass }} flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas {{ $iconClass }} text-xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-bold text-slate-800 text-sm leading-snug truncate mb-1" title="{{ $file->filename }}">
                                {{ $displayName }}
                            </h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $bgClass }} text-slate-600 border border-slate-100">
                                {{ $fileTypeLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- Preview Area -->
                    <div class="relative bg-slate-50 flex-1 min-h-[200px] border-b border-slate-100 group-hover:bg-slate-100 transition-colors overflow-hidden">
                        @if($isPdf)
                            <iframe src="{{ asset($file->filepath) }}#toolbar=0&navpanes=0&scrollbar=0" class="w-full h-full border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
                            <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                                <a href="{{ asset($file->filepath) }}" target="_blank" class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-[#8B0000] hover:text-white flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i> View Fullscreen
                                </a>
                            </div>
                        @elseif($isOffice)
                            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ urlencode(asset($file->filepath)) }}" width="100%" height="100%" class="border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
                            <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                                <a href="https://view.officeapps.live.com/op/view.aspx?src={{ urlencode(asset($file->filepath)) }}" target="_blank" class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-[#8B0000] hover:text-white flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i> View Fullscreen
                                </a>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-8 text-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                    <i class="fas fa-eye-slash text-2xl text-slate-300"></i>
                                </div>
                                <span class="text-xs font-medium text-slate-500">Preview not available</span>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="p-4 bg-white space-y-3">
                        @php
                            $canUpload = $researchTitle->Status === 'Incomplete';
                        @endphp
                        @if($canUpload)
                            <form action="{{ route('update.file', $researchTitle->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="file_id" value="{{ $file->id }}">
                                <label class="block cursor-pointer">
                                    <div class="w-full py-2.5 px-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-[#8B0000] hover:bg-red-50 text-slate-500 hover:text-[#8B0000] text-sm font-bold text-center transition-all duration-200 flex items-center justify-center gap-2 group/upload">
                                        <i class="fas fa-cloud-upload-alt group-hover/upload:animate-bounce"></i>
                                        <span>Upload New Version</span>
                                    </div>
                                    <input type="file" name="file" class="hidden" onchange="this.form.submit()" accept=".{{ $file->filetype }}">
                                </label>
                            </form>
                        @endif
                        <a href="{{ asset($file->filepath) }}" download class="block w-full py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 rounded-xl text-sm font-bold text-center transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
            @endif

            <!-- Revision Folders View -->
            @foreach($revisionFolders->sortKeys() as $revNum => $files)
            <div x-show="activeTab === 'rev_{{ $revNum }}'" style="display: none;" class="space-y-6">
                <!-- Revision Header -->
                <div class="bg-slate-50 rounded-3xl border border-slate-200 p-6 flex items-center justify-between mb-2 pb-4 border-b border-slate-200/60 shadow-sm">
                    <h4 class="text-lg font-bold text-slate-700 flex items-center gap-3">
                        <i class="fas fa-folder text-indigo-400 text-2xl"></i>
                        Revision {{ $revNum }}
                    </h4>
                    <span class="text-xs font-bold text-slate-500 bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-sm">
                        {{ $files->count() }} Documents
                    </span>
                </div>

                @php
                    $revFilesByCategory = $files->groupBy('category');
                    $renderedRevCategories = [];
                @endphp

                @foreach($requirements as $req)
                    @php
                        $categoryFiles = $revFilesByCategory->get($req->name, collect());
                        $renderedRevCategories[] = $req->name;
                    @endphp

                    @if($categoryFiles->isNotEmpty())
                    @php $pKey = 'rt_' . $researchTitle->id . '_rev_' . $revNum . '_' . Str::slug($req->name); @endphp
                    <div x-data="{ expanded: $persist(false).as('{{ $pKey }}') }" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <button @click="expanded = !expanded" class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-100 text-left">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-800 uppercase tracking-widest">{{ $req->name }}</h4>
                                    <p class="text-[10px] font-bold text-slate-400">{{ $categoryFiles->count() }} doc(s)</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 shadow-sm group">
                                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 group-hover:text-slate-600" :class="expanded ? 'rotate-180' : ''"></i>
                            </div>
                        </button>
                        <div x-show="expanded" x-transition class="p-6 bg-slate-50/30" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($categoryFiles as $file)
                                    <x-researcher-readonly-file-card :file="$file" :showRevisionTag="false" />
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
                
                @foreach($revFilesByCategory as $category => $categoryFiles)
                    @if(!in_array($category, $renderedRevCategories))
                    @php $pKey = 'rt_' . $researchTitle->id . '_rev_' . $revNum . '_' . Str::slug($category ?? 'uncat'); @endphp
                    <div x-data="{ expanded: $persist(false).as('{{ $pKey }}') }" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <button @click="expanded = !expanded" class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-100 text-left">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-800 uppercase tracking-widest">{{ $category ?? 'Uncategorized' }}</h4>
                                    <p class="text-[10px] font-bold text-slate-400">{{ $categoryFiles->count() }} doc(s)</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 shadow-sm group">
                                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 group-hover:text-slate-600" :class="expanded ? 'rotate-180' : ''"></i>
                            </div>
                        </button>
                        <div x-show="expanded" x-transition class="p-6 bg-slate-50/30" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($categoryFiles as $file)
                                    <x-researcher-readonly-file-card :file="$file" :showRevisionTag="false" />
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @endforeach

            <!-- DRAFT WORKSPACE VIEW -->
            @if($isWaitingForRevision)
            <div x-show="activeTab === 'draft'" style="display: none;">
                <div class="bg-orange-50/50 rounded-3xl border border-orange-200 p-8 shadow-sm mb-6">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-orange-900 flex items-center gap-2 mb-2">
                            <i class="fas fa-pencil-ruler text-orange-500"></i> Revision {{ $nextRevisionNumber }} Workspace
                        </h3>
                        <p class="text-orange-700 text-sm">Upload your corrected documents below. These act as drafts until you click "Submit Corrections".</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($activeFiles as $file)
                            @php
                                // Check if a draft exists for this exact category
                                $draftFile = $draftFiles->firstWhere('category', $file->category);
                                $hasDraft = $draftFile !== null;
                            @endphp
                            
                            <div class="bg-white rounded-2xl border {{ $hasDraft ? 'border-emerald-300 ring-2 ring-emerald-50' : 'border-orange-200 border-dashed' }} p-5 relative overflow-hidden transition-all">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-lg {{ $hasDraft ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-500' }} flex items-center justify-center">
                                        <i class="fas {{ $hasDraft ? 'fa-check' : 'fa-file-upload' }}"></i>
                                    </div>
                                    <h4 class="font-bold text-sm text-slate-800 flex-1 leading-snug">{{ $file->category }}</h4>
                                </div>

                                @if($hasDraft)
                                    <div class="bg-emerald-50 rounded-xl p-3 mb-3 border border-emerald-100">
                                        <p class="text-xs font-bold text-emerald-800 truncate" title="{{ $draftFile->filename }}">
                                            <i class="fas fa-file-pdf mr-1"></i> {{ $draftFile->filename }}
                                        </p>
                                    </div>
                                    <form action="{{ route('delete.revision.document', $draftFile->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full py-2.5 rounded-xl bg-white border border-red-200 text-red-600 font-bold text-xs hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                                            <i class="fas fa-trash"></i> Remove Draft
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('upload.revision.document', $researchTitle->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="category" value="{{ $file->category }}">
                                        <label class="block cursor-pointer">
                                            <div class="w-full py-3 rounded-xl border-2 border-dashed border-orange-200 hover:border-orange-400 hover:bg-orange-50 text-orange-600 text-xs font-bold text-center transition-all flex flex-col items-center justify-center gap-1 group">
                                                <i class="fas fa-cloud-upload-alt text-lg group-hover:-translate-y-1 transition-transform"></i>
                                                <span>Click to Upload Revised Document</span>
                                            </div>
                                            <input type="file" name="file" class="hidden" onchange="this.form.submit()" accept=".{{ strtolower($file->filetype) }}">
                                        </label>
                                    </form>
                                @endif
                            </div>
                        @endforeach
            @endif
        </div>
        @endif

        <!-- Title Activity Logs Section -->
        <div class="mt-8 md:mt-12 bg-white rounded-2xl md:rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-10">
            <div class="px-5 py-5 md:px-8 md:py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-indigo-600"></i>
                        Activity Log
                    </h3>
                    <p class="text-xs md:text-sm text-slate-500 mt-1">A complete history of status changes and updates for this submission.</p>
                </div>
                <div class="self-start sm:self-auto px-3 py-1.5 md:px-4 md:py-2 bg-indigo-50 text-indigo-700 rounded-lg text-xs md:text-sm font-bold border border-indigo-100 flex items-center gap-2">
                    <i class="fas fa-list-ul"></i> {{ $researchTitle->titleLogs->count() }} Records
                </div>
            </div>

            <div class="p-5 md:p-8">
                @if($researchTitle->titleLogs->isEmpty())
                    <div class="text-center py-10">
                        <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 border border-slate-100">
                            <i class="fas fa-clipboard-list text-slate-300 text-xl md:text-2xl"></i>
                        </div>
                        <h4 class="text-sm md:text-base text-slate-700 font-bold mb-1">No Activity Yet</h4>
                        <p class="text-slate-500 text-xs md:text-sm">Action logs will appear here once the submission is processed.</p>
                    </div>
                @else
                    <div class="relative before:absolute before:inset-y-0 before:left-3 md:before:left-6 before:w-[2px] before:bg-slate-100 space-y-6 md:space-y-8 pl-1">
                        @foreach($researchTitle->titleLogs as $log)
                            @php
                                $actionLower = strtolower($log->action);
                                $isCreation = str_contains($actionLower, 'created') || str_contains($actionLower, 'submitted');
                                $isStatus = str_contains($actionLower, 'status');
                                
                                if($isCreation) {
                                    $icon = 'fa-plus';
                                    $color = 'text-emerald-500';
                                    $bg = 'bg-emerald-50';
                                    $border = 'border-emerald-200';
                                } elseif($isStatus) {
                                    $icon = 'fa-sync-alt';
                                    $color = 'text-blue-500';
                                    $bg = 'bg-blue-50';
                                    $border = 'border-blue-200';
                                } else {
                                    $icon = 'fa-pen';
                                    $color = 'text-orange-500';
                                    $bg = 'bg-orange-50';
                                    $border = 'border-orange-200';
                                }
                            @endphp

                            <div class="relative flex items-start group">
                                <!-- Timeline Node -->
                                <div class="absolute left-0 w-6 h-6 -ml-3 md:w-10 md:h-10 md:-ml-4 rounded-full border-2 md:border-4 border-white flex items-center justify-center {{ $bg }} {{ $color }} z-10 shadow-sm transition-transform group-hover:scale-110">
                                    <i class="fas {{ $icon }} text-[10px] md:text-sm"></i>
                                </div>
                                
                                <!-- Content Box -->
                                <div class="ml-6 md:ml-12 focus:outline-none w-full mb-4 md:mb-6">
                                    <div class="bg-white border hover:border-slate-300 {{ str_contains($log->action, 'Created') ? 'border-l-4 border-l-emerald-500' : 'border-slate-100' }} rounded-xl md:rounded-2xl p-4 md:p-5 shadow-sm transition-all hover:shadow-md">
                                        <div class="flex flex-col gap-2 mb-3">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                <h4 class="text-slate-800 font-bold text-sm md:text-base m-0">{{ $log->action }}</h4>
                                                <span class="text-[10px] md:text-xs font-bold text-slate-400 flex items-center gap-1.5 bg-slate-50 px-2 py-1 md:px-3 md:py-1.5 rounded-lg border border-slate-100 self-start sm:self-auto whitespace-nowrap">
                                                    <i class="far fa-clock"></i>
                                                    {{ $log->created_at->format('M d, Y • h:i A') }}
                                                </span>
                                            </div>
                                            <div>
                                                @if($log->user)
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 md:px-2.5 md:py-1 rounded-md text-[9px] md:text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                                        <i class="fas fa-user-circle"></i>
                                                        {{ $log->user->first_name }} {{ $log->user->last_name }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 md:px-2.5 md:py-1 rounded-md text-[9px] md:text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                                        <i class="fas fa-robot text-slate-400"></i> System
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-slate-600 text-xs md:text-sm leading-relaxed m-0 mt-2 font-medium">
                                            {{ $log->description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </main>
</x-user_layout>