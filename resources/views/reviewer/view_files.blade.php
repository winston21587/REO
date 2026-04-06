<x-reviewer_layout>
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
            // Get all active files from the highest revision per category
            $activeFiles = $protocolDocs->where('revision_number', '!=', -1)
                ->groupBy('category')
                ->map(function ($categoryFiles) {
                    $maxRev = $categoryFiles->max('revision_number');
                    return $categoryFiles->where('revision_number', $maxRev);
                })
                ->flatten()
                ->sortByDesc('created_at');

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
                    'label' => $file->category ?? 'Uncategorized',
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

            $groupFiles = function($collection, $groupLabel) use ($enrichFile) {
                $grouped = [];
                foreach ($collection as $f) {
                    $cat = $f->category ?? 'Uncategorized';
                    if (!isset($grouped[$cat])) {
                        $grouped[$cat] = [];
                    }
                    $grouped[$cat][] = $enrichFile($f, $groupLabel);
                }
                $result = [];
                foreach ($grouped as $cat => $files) {
                    $result[] = [
                        'category' => $cat,
                        'files' => $files
                    ];
                }
                return $result;
            };

            $jsOriginal = $groupFiles($originalFiles, 'Original');
            $jsActive   = $groupFiles($activeFiles, 'Current');
            $jsLetters  = $groupFiles($letters, 'Letters');

            $jsRevisions = [];
            foreach ($revisionFolders as $revNum => $files) {
                $jsRevisions[$revNum] = $groupFiles($files, "Revision $revNum");
            }

            $firstFile = $originalFiles->first() ? $enrichFile($originalFiles->first(), 'Original') : ($activeFiles->first() ? $enrichFile($activeFiles->first(), 'Current') : null);
            $serveRoute = route('reviewer.serve_file', 'FILE_ID');
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
                requirementsMap: {{ json_encode($requirementsMap) }},
                serveRoute: '{{ $serveRoute }}',
                getUrl(file) {
                    if (!file) return '';
                    return this.serveRoute.replace('FILE_ID', file.id);
                },
                getOfficeUrl(file) {
                    if (!file || !file.public_url) return '';
                    return 'https://view.officeapps.live.com/op/view.aspx?src=' + encodeURIComponent(file.public_url);
                },
                isViewable(file) {
                    if (!file) return false;
                    const req = this.requirementsMap[file.label];
                    return req ? (req.is_viewable_for_reviewer == 1 || req.is_viewable_for_reviewer === true) : true;
                },
                isDownloadable(file) {
                    if (!file) return false;
                    const req = this.requirementsMap[file.label];
                    return req ? (req.is_downloadable_for_reviewer == 1 || req.is_downloadable_for_reviewer === true) : true;
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
                        <a x-show="isViewable(activeFile)" :href="isOffice(activeFile) ? getOfficeUrl(activeFile) : getUrl(activeFile)" target="_blank" class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all" title="Open in new tab">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a x-show="isDownloadable(activeFile)" :href="getUrl(activeFile)" download class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>

                <!-- Viewer pane -->
                <div class="bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-800 relative" style="height: 70vh; min-height: 480px;">
                    <template x-if="activeFile && isViewable(activeFile) && isPdf(activeFile)">
                        <iframe :src="getUrl(activeFile)" class="w-full h-full border-0 bg-white" title="PDF Viewer"></iframe>
                    </template>
                    <template x-if="activeFile && isViewable(activeFile) && isOffice(activeFile)">
                        <iframe :src="getOfficeUrl(activeFile)" class="w-full h-full border-0 bg-white" title="Office Viewer"></iframe>
                    </template>
                    <template x-if="activeFile && !isViewable(activeFile)">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-eye-slash text-4xl text-slate-600"></i>
                                </div>
                                <p class="text-slate-400 font-medium">Preview restricted by administrator</p>
                            </div>
                        </div>
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

                <!-- Reviewer Upload Section -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 mt-4">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt text-[#8B0000]"></i> Upload Evaluation Documents
                    </h3>
                    <form action="{{ route('reviewer.upload', $researchTitle->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 relative">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Category</label>
                                <select name="category" required class="w-full text-sm font-semibold rounded-xl border-slate-200 focus:border-[#8B0000] focus:ring focus:ring-[#8B0000]/20 bg-slate-50/80 text-slate-700 h-11 transition-all">
                                    <option value="">-- Select Document Category --</option>
                                    @foreach($requirementsMap as $req)
                                        <option value="{{ $req['name'] }}">{{ $req['name'] }}</option>
                                    @endforeach
                                    <option value="Other">Other Evaluation Material</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">File</label>
                                <input type="file" name="file" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700 transition-colors bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                            </div>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="bg-[#8B0000] hover:bg-red-900 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-md shadow-red-900/20 flex items-center gap-2 text-sm focus:ring-4 focus:ring-red-100">
                                <i class="fas fa-upload"></i> Upload Document
                            </button>
                        </div>
                    </form>

                    @php
                        $reviewerUploads = $researchTitle->adminFiles()->where('uploaded_by', Auth::id())->where('category', 'like', 'Reviewer Uploads%')->latest()->get();
                    @endphp
                    @if($reviewerUploads->isNotEmpty())
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">My Uploaded Evaluations</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($reviewerUploads as $upload)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-slate-300 transition-all group">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-10 h-10 rounded-lg bg-white shadow-sm border border-slate-100 flex flex-shrink-0 items-center justify-center text-[#8B0000]">
                                        <i class="fas fa-file-alt text-lg"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ $upload->filename }}</p>
                                        <p class="text-[10px] text-slate-500 font-medium mt-0.5 truncate">{{ str_replace('Reviewer Uploads - ', '', $upload->category) }} • {{ $upload->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('reviewer.serve_file', $upload->id) }}" target="_blank" class="w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 flex flex-shrink-0 items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
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
                            <template x-for="group in letters" :key="group.category">
                                <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2" x-data="{ expanded: false }">
                                    <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 bg-slate-50/80 hover:bg-slate-100 transition-colors">
                                        <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest" x-text="group.category"></h4>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded-full border border-slate-200" x-text="group.files.length + ' file(s)'"></span>
                                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <template x-for="file in group.files" :key="file.id">
                                            <button @click="selectFile(file)"
                                                :class="activeFile && activeFile.id === file.id ? 'bg-emerald-50 border-l-4 border-emerald-500' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all bg-white border-t border-slate-100">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="file.bg">
                                                    <i :class="[file.icon, file.color]"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold text-slate-800 truncate" x-text="file.filename"></p>
                                                    <p class="text-[10px] text-slate-400" x-text="file.uploaded_at"></p>
                                                </div>
                                                <div x-show="activeFile && activeFile.id === file.id" class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
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
                            <template x-for="group in originalFiles" :key="group.category">
                                <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2" x-data="{ expanded: false }">
                                    <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 bg-slate-50/80 hover:bg-slate-100 transition-colors">
                                        <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest" x-text="group.category"></h4>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded-full border border-slate-200" x-text="group.files.length + ' file(s)'"></span>
                                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <template x-for="file in group.files" :key="file.id">
                                            <button @click="selectFile(file)"
                                                :class="activeFile && activeFile.id === file.id ? 'bg-red-50 border-l-4 border-[#8B0000]' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all bg-white border-t border-slate-100">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="file.bg">
                                                    <i :class="[file.icon, file.color]"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold text-slate-800 truncate" x-text="file.filename"></p>
                                                    <p class="text-[10px] text-slate-400" x-text="file.uploaded_at"></p>
                                                </div>
                                                <div x-show="activeFile && activeFile.id === file.id" class="w-2 h-2 rounded-full bg-[#8B0000] flex-shrink-0"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Revision tabs (one per revision number) -->
                        @foreach($revisionFolders->sortKeys() as $revNum => $_)
                        <div x-show="activeTab === 'rev_{{ $revNum }}'" style="display:none;">
                            <div class="px-4 py-2.5 bg-indigo-50/60 border-b border-indigo-100 flex items-center justify-between mb-2">
                                <p class="text-xs font-extrabold text-indigo-600 uppercase tracking-wider">Revision {{ $revNum }}</p>
                            </div>
                            <template x-for="group in revisions['{{ $revNum }}']" :key="group.category">
                                <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2" x-data="{ expanded: false }">
                                    <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 bg-slate-50/80 hover:bg-slate-100 transition-colors">
                                        <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest" x-text="group.category"></h4>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded-full border border-slate-200" x-text="group.files.length + ' file(s)'"></span>
                                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <template x-for="file in group.files" :key="file.id">
                                            <button @click="selectFile(file)"
                                                :class="activeFile && activeFile.id === file.id ? 'bg-indigo-50 border-l-4 border-indigo-500' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all bg-white border-t border-slate-100">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="file.bg">
                                                    <i :class="[file.icon, file.color]"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold text-slate-800 truncate" x-text="file.filename"></p>
                                                    <p class="text-[10px] text-slate-400" x-text="file.uploaded_at"></p>
                                                </div>
                                                <div x-show="activeFile && activeFile.id === file.id" class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @endforeach

                        <!-- Current Documents tab -->
                        @if($hasRevisions && $activeFiles->isNotEmpty())
                        <div x-show="activeTab === 'current'" style="display:none;">
                            <div class="px-4 py-2.5 bg-red-50/60 border-b border-red-100 mb-2">
                                <p class="text-xs font-extrabold text-[#8B0000] uppercase tracking-wider">Latest Version of Each Document</p>
                            </div>
                            <template x-for="group in activeFiles" :key="group.category">
                                <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2" x-data="{ expanded: false }">
                                    <button @click="expanded = !expanded" class="w-full flex items-center justify-between px-4 py-3 bg-slate-50/80 hover:bg-slate-100 transition-colors">
                                        <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest" x-text="group.category"></h4>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded-full border border-slate-200" x-text="group.files.length + ' file(s)'"></span>
                                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <template x-for="file in group.files" :key="file.id">
                                            <button @click="selectFile(file)"
                                                :class="activeFile && activeFile.id === file.id ? 'bg-red-50 border-l-4 border-[#8B0000]' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all bg-white border-t border-slate-100">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="file.bg">
                                                    <i :class="[file.icon, file.color]"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold text-slate-800 truncate" x-text="file.filename"></p>
                                                    <p class="text-[10px] font-bold" 
                                                       :class="file.revision_number ? 'text-indigo-500' : 'text-slate-400'"
                                                       x-text="file.revision_number ? 'Revision ' + file.revision_number : 'Original'"></p>
                                                </div>
                                                <div x-show="activeFile && activeFile.id === file.id" class="w-2 h-2 rounded-full bg-[#8B0000] flex-shrink-0"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
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

                <!-- Activity Log -->
                @if($researchTitle->titleLogs && $researchTitle->titleLogs->isNotEmpty())
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex-shrink-0 mt-4">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-history text-blue-400"></i> Activity Log
                    </p>
                    <div class="relative pl-4 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                        <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-slate-100"></div>
                        <div class="space-y-4">
                            @foreach($researchTitle->titleLogs as $log)
                            <div class="flex gap-3 relative z-10">
                                <div class="w-6 h-6 rounded-full bg-slate-100 border-2 border-slate-300 flex items-center justify-center flex-shrink-0 -ml-3 mt-0.5">
                                    <i class="fas fa-circle text-slate-400" style="font-size: 6px;"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700 leading-tight">{{ $log->action }}</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">{{ $log->description }}</p>
                                    <p class="text-[9px] text-slate-400 mt-1">{{ $log->created_at->format('M d, Y • h:i A') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>{{-- end sidebar --}}
        </div>{{-- end grid --}}
    </div>
</x-reviewer_layout>
