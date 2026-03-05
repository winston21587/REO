<x-admin_layout>
    <div class="max-w-7xl mx-auto py-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Top Navigation & Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6 p-6 bg-gradient-to-r from-white to-slate-50 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full blur-3xl opacity-50 -mr-16 -mt-16 pointer-events-none"></div>
            <div class="flex items-center gap-5 relative z-10">
                <a href="{{ $backUrl }}" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white border border-slate-200 text-slate-400 hover:border-[#8B0000] hover:text-[#8B0000] hover:shadow-md hover:-translate-x-1 transition-all duration-300">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-extrabold uppercase tracking-widest border border-slate-200 shadow-sm">
                            {{ $researchTitle->reoc_code ?? 'PENDING-ID' }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-extrabold uppercase tracking-widest border border-amber-100 flex items-center gap-1.5 shadow-sm">
                            <i class="fas fa-clock text-[10px]"></i> {{ $researchTitle->Status }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 font-heading leading-tight tracking-tight">
                        {{ $researchTitle->Study_Protocol_title }}
                    </h1>
                </div>
            </div>
        </div>

        @if($researchTitle->revisionLogs->isNotEmpty())
        <div class="mb-8 space-y-4">
            <div class="flex items-center gap-4">
                <div class="h-px bg-slate-200 flex-1"></div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Revision History</span>
                <div class="h-px bg-slate-200 flex-1"></div>
            </div>
            @foreach($researchTitle->revisionLogs as $log)
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full {{ $log->user->role === 'admin' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center border-2 border-white shadow-sm flex-shrink-0">
                    <i class="fas {{ $log->user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }} text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-slate-800">{{ $log->user->first_name }} {{ $log->user->last_name }} <span class="text-xs font-normal text-slate-500">({{ ucfirst($log->user->role) }})</span></p>
                    <p class="text-xs text-slate-400 mb-2">{{ $log->created_at->format('M d, Y • h:i A') }}</p>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap">{{ $log->message }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @php
            $allFiles = $researchTitle->files->merge($researchTitle->adminFiles ?? collect());
            $letters = $allFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->sortByDesc('created_at');
            $protocolDocs = $researchTitle->files->whereNotIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter']);

            $originalFiles = $protocolDocs->whereNull('revision_number')->sortByDesc('created_at');
            $archivedFiles = $protocolDocs->where('revision_number', '>', 0)->sortByDesc('created_at');
            $revisionFolders = $archivedFiles->groupBy('revision_number')->sortKeys();
            $activeFiles = $protocolDocs->where('revision_number', '!=', -1)->sortByDesc(function ($file) {
                return $file->revision_number ?? 0;
            })->unique('category')->sortByDesc('created_at');

            $hasRevisions = $revisionFolders->isNotEmpty();

            // Build enriched file sets for Alpine (all JSON encoded via x-data)
            $enrichFile = function($file, $label) {
                $ext = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
                if (!$ext) $ext = strtolower($file->filetype ?? '');
                $icons = [
                    'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => 'text-red-700', 'bg' => 'bg-red-50'],
                    'doc' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                    'docx' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                    'ppt' => ['icon' => 'fas fa-file-powerpoint', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
                    'pptx' => ['icon' => 'fas fa-file-powerpoint', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
                    'xls' => ['icon' => 'fas fa-file-excel', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
                    'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
                ];
                $attrs = $icons[$ext] ?? ['icon' => 'fas fa-file', 'color' => 'text-slate-400', 'bg' => 'bg-slate-100'];
                return [
                    'id' => $file->id,
                    'filename' => $file->filename,
                    'label' => $file->category ?? $file->filename,
                    'group' => $label,
                    'ext' => $ext,
                    'revision_number' => $file->revision_number,
                    'uploaded_at' => $file->created_at->format('M d, Y'),
                    'icon' => $attrs['icon'],
                    'color' => $attrs['color'],
                    'bg' => $attrs['bg'],
                    'public_url' => asset($file->filepath),
                ];
            };

            $jsOriginal = $originalFiles->map(fn($f) => $enrichFile($f, 'Original'))->values();
            $jsActive   = $activeFiles->map(fn($f) => $enrichFile($f, 'Current'))->values();
            $jsLetters  = $letters->map(fn($f) => $enrichFile($f, 'Letters'))->values();

            $jsRevisions = [];
            foreach ($revisionFolders as $revNum => $files) {
                $jsRevisions[$revNum] = $files->map(fn($f) => $enrichFile($f, "Revision $revNum"))->values()->toArray();
            }

            $firstFile = $jsOriginal->first() ?? $jsActive->first() ?? null;
            $serveRoute = route('admin.serve_file', 'FILE_ID');
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8"
             x-data="{
                activeFile: {{ $firstFile ? json_encode($firstFile) : 'null' }},
                activeTab: 'original',
                originalFiles: {{ json_encode($jsOriginal) }},
                activeFiles: {{ json_encode($jsActive) }},
                letters: {{ json_encode($jsLetters) }},
                revisions: {{ json_encode($jsRevisions) }},
                hasRevisions: {{ $hasRevisions ? 'true' : 'false' }},
                revisionNums: {{ json_encode(array_keys($jsRevisions)) }},
                serveRoute: '{{ $serveRoute }}',
                getUrl(file) {
                    if (!file) return '';
                    return this.serveRoute.replace('FILE_ID', file.id);
                },
                getOfficeUrl(file) {
                    if (!file || !file.public_url) return '';
                    return 'https://view.officeapps.live.com/op/view.aspx?src=' + encodeURIComponent(file.public_url);
                },
                isPdf(file) { return file && file.ext === 'pdf'; },
                isOffice(file) { return file && ['doc','docx','ppt','pptx','xls','xlsx'].includes(file.ext); },
                selectFile(file) { this.activeFile = file; }
             }">

            <!-- ===== LEFT — Viewer ===== -->
            <div class="lg:col-span-8 flex flex-col gap-4">

                <!-- Top bar -->
                <div class="flex items-center justify-between bg-white/90 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0 text-[#8B0000]">
                            <i :class="activeFile ? activeFile.icon : 'fas fa-file'" class="text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Currently Viewing</p>
                            <p class="text-sm font-bold text-slate-800 truncate max-w-[320px]" x-text="activeFile ? activeFile.label : 'No file selected'"></p>
                        </div>
                        <template x-if="activeFile && activeFile.revision_number">
                            <span class="ml-2 text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100" x-text="'Revision ' + activeFile.revision_number"></span>
                        </template>
                        <template x-if="activeFile && !activeFile.revision_number && activeFile.group !== 'Letters'">
                            <span class="ml-2 text-[10px] font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded-full border border-slate-200">Original</span>
                        </template>
                    </div>
                    <div class="flex gap-1" x-show="activeFile">
                        <a :href="isOffice(activeFile) ? getOfficeUrl(activeFile) : getUrl(activeFile)" target="_blank" class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all" title="Open in new tab">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a :href="getUrl(activeFile)" download class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>

                <!-- Viewer pane: sticky so it stays visible while sidebar scrolls -->
                <div class="sticky top-4 bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-800 relative" style="height: 70vh; min-height: 480px;">
                    <template x-if="activeFile && isPdf(activeFile)">
                        <iframe :src="getUrl(activeFile)" class="w-full h-full border-0 bg-white" title="PDF Viewer"></iframe>
                    </template>
                    <template x-if="activeFile && isOffice(activeFile)">
                        <iframe :src="getOfficeUrl(activeFile)" class="w-full h-full border-0 bg-white" title="Office Viewer"></iframe>
                    </template>
                    <template x-if="!activeFile">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-file-alt text-4xl text-slate-600"></i>
                                </div>
                                <p class="text-slate-400 font-medium">Select a file from the panel</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ===== RIGHT — Sidebar ===== -->
            <div class="lg:col-span-4 flex flex-col gap-4 pb-8">

                <!-- PI Card -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden flex-shrink-0">
                    <div class="absolute top-0 right-0 w-28 h-28 bg-gradient-to-br from-slate-50 to-slate-100 rounded-bl-full -mr-6 -mt-6"></div>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Principal Investigator</p>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#8B0000] to-red-900 text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-red-900/20 ring-4 ring-red-50 flex-shrink-0">
                            {{ substr($researchTitle->researcher->user->first_name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 leading-tight">{{ $researchTitle->researcher->user->first_name ?? 'Unknown' }} {{ $researchTitle->researcher->user->last_name ?? '' }}</p>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wide mt-0.5">{{ $researchTitle->researcher->college ?? 'External' }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $researchTitle->Research_Category }}</p>
                        </div>
                    </div>
                </div>

                <!-- Version Timeline -->
                @if($hasRevisions)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex-shrink-0">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-code-branch text-indigo-400"></i> Version Timeline
                    </p>
                    <div class="relative pl-4">
                        <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-slate-100"></div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-slate-300 flex items-center justify-center flex-shrink-0 z-10 -ml-4">
                                    <i class="fas fa-box-archive text-slate-500 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700">Original Submission</p>
                                    <p class="text-[10px] text-slate-400">{{ $originalFiles->first()?->created_at?->format('M d, Y') ?? '—' }} · {{ $originalFiles->count() }} doc(s)</p>
                                </div>
                            </div>
                            @foreach($revisionFolders->sortKeys() as $revNum => $files)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 border-2 border-indigo-300 flex items-center justify-center flex-shrink-0 z-10 -ml-4">
                                    <span class="text-[10px] font-black text-indigo-600">{{ $revNum }}</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700">Revision {{ $revNum }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $files->first()?->created_at?->format('M d, Y') ?? '—' }} · {{ $files->count() }} doc(s)</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tabbed File Picker -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0">
                    <!-- Tab bar -->
                    <div class="flex gap-1 border-b border-slate-100 bg-slate-50/60 px-2 pt-2 overflow-x-auto custom-scrollbar flex-shrink-0">
                        @if($letters->isNotEmpty())
                        <button @click="activeTab = 'letters'"
                            :class="activeTab === 'letters' ? 'bg-white border-b-2 border-emerald-500 text-emerald-700' : 'text-slate-500 hover:text-slate-700'"
                            class="flex items-center gap-1.5 px-3 py-2 text-xs font-bold whitespace-nowrap transition-all rounded-t-lg">
                            <i class="fas fa-certificate text-emerald-500"></i> Letters
                        </button>
                        @endif

                        <button @click="activeTab = 'original'"
                            :class="activeTab === 'original' ? 'bg-white border-b-2 border-slate-500 text-slate-800' : 'text-slate-500 hover:text-slate-700'"
                            class="flex items-center gap-1.5 px-3 py-2 text-xs font-bold whitespace-nowrap transition-all rounded-t-lg">
                            <i class="fas fa-box-archive text-slate-400"></i> Original
                        </button>

                        @foreach($revisionFolders->sortKeys() as $revNum => $_)
                        <button @click="activeTab = 'rev_{{ $revNum }}'"
                            :class="activeTab === 'rev_{{ $revNum }}' ? 'bg-white border-b-2 border-indigo-500 text-indigo-700' : 'text-slate-500 hover:text-slate-700'"
                            class="flex items-center gap-1.5 px-3 py-2 text-xs font-bold whitespace-nowrap transition-all rounded-t-lg">
                            <i class="fas fa-folder text-indigo-400"></i> Rev {{ $revNum }}
                        </button>
                        @endforeach

                        @if($hasRevisions && $activeFiles->isNotEmpty())
                        <button @click="activeTab = 'current'"
                            :class="activeTab === 'current' ? 'bg-white border-b-2 border-[#8B0000] text-[#8B0000]' : 'text-slate-500 hover:text-slate-700'"
                            class="flex items-center gap-1.5 px-3 py-2 text-xs font-bold whitespace-nowrap transition-all rounded-t-lg">
                            <i class="fas fa-file-signature text-[#8B0000]"></i> Current
                        </button>
                        @endif
                    </div>

                    <!-- Scrollable file list area -->
                    <div class="overflow-y-auto custom-scrollbar" style="min-height: 320px; max-height: 480px;">

                        <!-- Letters tab -->
                        <div x-show="activeTab === 'letters'" style="display:none;">
                            <template x-for="file in letters" :key="file.id">
                                <button @click="selectFile(file)"
                                    :class="activeFile && activeFile.id === file.id ? 'bg-emerald-50 border-l-4 border-emerald-500' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="file.bg">
                                        <i :class="[file.icon, file.color]"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-slate-800 truncate" x-text="file.label"></p>
                                        <p class="text-[10px] text-slate-400" x-text="file.uploaded_at"></p>
                                    </div>
                                    <div x-show="activeFile && activeFile.id === file.id" class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></div>
                                </button>
                            </template>
                        </div>

                        <!-- Original Documents tab -->
                        <div x-show="activeTab === 'original'" style="display:none;">
                            <template x-if="originalFiles.length === 0">
                                <div class="p-8 text-center text-slate-400">
                                    <i class="fas fa-box-open text-3xl mb-2 block opacity-30"></i>
                                    <p class="text-sm">No original documents found.</p>
                                </div>
                            </template>
                            <template x-for="file in originalFiles" :key="file.id">
                                <button @click="selectFile(file)"
                                    :class="activeFile && activeFile.id === file.id ? 'bg-red-50 border-l-4 border-[#8B0000]' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="file.bg">
                                        <i :class="[file.icon, file.color]"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-slate-800 truncate" x-text="file.label"></p>
                                        <p class="text-[10px] text-slate-400" x-text="file.uploaded_at"></p>
                                    </div>
                                    <div x-show="activeFile && activeFile.id === file.id" class="w-2 h-2 rounded-full bg-[#8B0000] flex-shrink-0"></div>
                                </button>
                            </template>
                        </div>

                        <!-- Revision tabs (one per revision number) -->
                        @foreach($revisionFolders->sortKeys() as $revNum => $_)
                        <div x-show="activeTab === 'rev_{{ $revNum }}'" style="display:none;">
                            <div class="px-4 py-2.5 bg-indigo-50/60 border-b border-indigo-100 flex items-center justify-between">
                                <p class="text-xs font-extrabold text-indigo-600 uppercase tracking-wider">Revision {{ $revNum }}</p>
                                <span class="text-[10px] font-bold text-indigo-500 bg-indigo-100 px-2 py-0.5 rounded-full">
                                    {{ count($jsRevisions[$revNum] ?? []) }} docs
                                </span>
                            </div>
                            <template x-for="file in (revisions['{{ $revNum }}'] || [])" :key="file.id">
                                <button @click="selectFile(file)"
                                    :class="activeFile && activeFile.id === file.id ? 'bg-indigo-50 border-l-4 border-indigo-500' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="file.bg">
                                        <i :class="[file.icon, file.color]"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-slate-800 truncate" x-text="file.label"></p>
                                        <p class="text-[10px] text-slate-400" x-text="file.uploaded_at"></p>
                                    </div>
                                    <div x-show="activeFile && activeFile.id === file.id" class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></div>
                                </button>
                            </template>
                        </div>
                        @endforeach

                        <!-- Current Documents tab -->
                        @if($hasRevisions && $activeFiles->isNotEmpty())
                        <div x-show="activeTab === 'current'" style="display:none;">
                            <div class="px-4 py-2.5 bg-red-50/60 border-b border-red-100">
                                <p class="text-xs font-extrabold text-[#8B0000] uppercase tracking-wider">Latest Version of Each Document</p>
                            </div>
                            <template x-for="file in activeFiles" :key="file.id">
                                <button @click="selectFile(file)"
                                    :class="activeFile && activeFile.id === file.id ? 'bg-red-50 border-l-4 border-[#8B0000]' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="file.bg">
                                        <i :class="[file.icon, file.color]"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-slate-800 truncate" x-text="file.label"></p>
                                        <p class="text-[10px] font-bold" 
                                           :class="file.revision_number ? 'text-indigo-500' : 'text-slate-400'"
                                           x-text="file.revision_number ? 'Revision ' + file.revision_number : 'Original'"></p>
                                    </div>
                                    <div x-show="activeFile && activeFile.id === file.id" class="w-2 h-2 rounded-full bg-[#8B0000] flex-shrink-0"></div>
                                </button>
                            </template>
                        </div>
                        @endif

                    </div>{{-- end scrollable list --}}
                </div>{{-- end tabbed picker --}}

                <!-- Submission Meta -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex-shrink-0">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Submission Details</p>
                    <div class="space-y-2.5">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500 flex items-center gap-2"><i class="fas fa-tag text-blue-400 w-3"></i> Category</span>
                            <span class="text-xs font-bold text-slate-800 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">{{ $researchTitle->Research_Category }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500 flex items-center gap-2"><i class="fas fa-calendar text-purple-400 w-3"></i> Submitted</span>
                            <span class="text-xs font-bold text-slate-800">{{ $researchTitle->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500 flex items-center gap-2"><i class="fas fa-code-branch text-indigo-400 w-3"></i> Revisions</span>
                            <span class="text-xs font-bold {{ $hasRevisions ? 'text-indigo-600' : 'text-slate-500' }} bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100">
                                {{ $revisionFolders->count() }} submitted
                            </span>
                        </div>
                    </div>
                </div>

            </div>{{-- end sidebar --}}
        </div>{{-- end grid --}}
    </div>
</x-admin_layout>