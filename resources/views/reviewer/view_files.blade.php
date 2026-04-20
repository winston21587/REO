<x-reviewer_layout>
    <div class="max-w-7xl mx-auto py-8 animate-[fadeInUp_0.5s_ease-out]">

        <!-- Top Navigation & Header -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6 p-6 bg-gradient-to-r from-white to-slate-50 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full blur-3xl opacity-50 -mr-16 -mt-16 pointer-events-none">
            </div>
            <div class="flex items-center gap-5 relative z-10">
                <a href="{{ $backUrl }}"
                    class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white border border-slate-200 text-slate-400 hover:border-[#8B0000] hover:text-[#8B0000] hover:shadow-md hover:-translate-x-1 transition-all duration-300">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span
                            class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-extrabold uppercase tracking-widest border border-slate-200 shadow-sm">
                            {{ $researchTitle->reoc_code ?? 'PENDING-ID' }}
                        </span>
                        <span
                            class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-extrabold uppercase tracking-widest border border-amber-100 flex items-center gap-1.5 shadow-sm">
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
                        <div
                            class="w-10 h-10 rounded-full {{ $log->user->role === 'admin' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center border-2 border-white shadow-sm flex-shrink-0">
                            <i class="fas {{ $log->user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }} text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800">{{ $log->user->first_name }} {{ $log->user->last_name }}
                                <span class="text-xs font-normal text-slate-500">({{ ucfirst($log->user->role) }})</span>
                            </p>
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
            $enrichFile = function ($file, $label) {
                $ext = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
                if (!$ext)
                    $ext = strtolower($file->filetype ?? '');
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

            $groupFiles = function ($collection, $groupLabel) use ($enrichFile) {
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
            $jsActive = $groupFiles($activeFiles, 'Current');
            $jsLetters = $groupFiles($letters, 'Letters');

            $jsRevisions = [];
            foreach ($revisionFolders as $revNum => $files) {
                $jsRevisions[$revNum] = $groupFiles($files, "Revision $revNum");
            }

            $jsAllFilesFlat = [];
            foreach ([$jsOriginal, $jsActive, $jsLetters] as $jsGroupList) {
                foreach ($jsGroupList as $group) {
                    foreach ($group['files'] as $f) {
                        $jsAllFilesFlat[] = $f;
                    }
                }
            }
            foreach ($jsRevisions as $rev) {
                foreach ($rev as $group) {
                    foreach ($group['files'] as $f) {
                        $jsAllFilesFlat[] = $f;
                    }
                }
            }

            $firstFile = $originalFiles->first() ? $enrichFile($originalFiles->first(), 'Original') : ($activeFiles->first() ? $enrichFile($activeFiles->first(), 'Current') : null);
            $serveRoute = route('reviewer.serve_file', 'FILE_ID');
        @endphp

        @php
            $remarksByFileId = $myFileRemarks->map(fn($r) => $r->remarks)->toArray();
        @endphp
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{
                activeFile: {{ $firstFile ? json_encode($firstFile) : 'null' }},
                activeTab: 'original',
                originalFiles: {{ json_encode($jsOriginal) }},
                activeFiles: {{ json_encode($jsActive) }},
                letters: {{ json_encode($jsLetters) }},
                revisions: {{ json_encode($jsRevisions) }},
                hasRevisions: {{ $hasRevisions ? 'true' : 'false' }},
                revisionNums: {{ json_encode(array_keys($jsRevisions)) }},
                allFilesFlat: {{ json_encode($jsAllFilesFlat) }},
                requirementsMap: {{ json_encode($requirementsMap) }},
                serveRoute: '{{ $serveRoute }}',
                remarksMap: {{ json_encode($remarksByFileId) }},
                currentRemark: '',
                remarkSaving: false,
                remarkSaved: false,
                remarkError: '',
                getFilesForActiveTab() {
                    if (!this.activeFile) return [];
                    const files = Array.isArray(this.allFilesFlat) ? this.allFilesFlat : Object.values(this.allFilesFlat || {});
                    return files.filter(f => f.group === this.activeFile.group);
                },

                getUrl(file) {
                    if (!file) return '';
                    return this.serveRoute.replace('FILE_ID', file.id);
                },
                getOfficeUrl(file) {
                    if (!file || !file.public_url) return '';
                    return 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(file.public_url);
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
                isImage(file) { return file && ['jpg','jpeg','png','gif','bmp','webp'].includes(file.ext); },
                selectFile(file) {
                    this.activeFile = file;
                    this.currentRemark = file ? (this.remarksMap[file.id] || '') : '';
                    this.remarkSaved = false;
                    this.remarkError = '';
                },
                async saveRemark() {
                    if (!this.activeFile) return;
                    this.remarkSaving = true;
                    this.remarkSaved = false;
                    this.remarkError = '';
                    try {
                        const res = await fetch(`/reviewer/file-remark/${this.activeFile.id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                remarks: this.currentRemark,
                                research_title_id: '{{ $researchTitle->id }}'
                            })
                        });
                        if (res.ok) {
                            this.remarksMap[this.activeFile.id] = this.currentRemark;
                            this.remarkSaved = true;
                            setTimeout(() => this.remarkSaved = false, 2500);
                        } else {
                            this.remarkError = 'Failed to save. Try again.';
                        }
                    } catch(e) {
                        this.remarkError = 'Network error.';
                    }
                    this.remarkSaving = false;
                }
             }" x-init="currentRemark = activeFile ? (remarksMap[activeFile.id] || '') : ''">

            <!-- ===== LEFT — Viewer ===== -->
            <div class="lg:col-span-8 flex flex-col gap-4">

                <!-- Top bar -->
                <div
                    class="flex items-center justify-between bg-white/90 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0 text-[#8B0000]">
                            <i :class="activeFile ? activeFile.icon : 'fas fa-file'" class="text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Currently Viewing
                            </p>
                            <p class="text-sm font-bold text-slate-800 truncate max-w-[320px]"
                                x-text="activeFile ? activeFile.label : 'No file selected'"></p>
                        </div>
                        <template x-if="activeFile && activeFile.revision_number">
                            <span
                                class="ml-2 text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100"
                                x-text="'Revision ' + activeFile.revision_number"></span>
                        </template>
                        <template x-if="activeFile && !activeFile.revision_number && activeFile.group !== 'Letters'">
                            <span
                                class="ml-2 text-[10px] font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded-full border border-slate-200">Original</span>
                        </template>
                    </div>
                    <div class="flex gap-1" x-show="activeFile">
                        <a x-show="isViewable(activeFile)"
                            :href="isOffice(activeFile) ? getOfficeUrl(activeFile) : getUrl(activeFile)" target="_blank"
                            class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all"
                            title="Open in new tab">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a x-show="isDownloadable(activeFile)" :href="getUrl(activeFile)" download
                            class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all"
                            title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>

                <!-- Viewer pane -->
                <div class="bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-800 relative"
                    style="height: 70vh; min-height: 480px;">
                    <template x-if="activeFile && isViewable(activeFile) && isPdf(activeFile)">
                        <iframe :src="getUrl(activeFile)" class="w-full h-full border-0 bg-white"
                            title="PDF Viewer"></iframe>
                    </template>
                    <template x-if="activeFile && isViewable(activeFile) && isOffice(activeFile)">
                        <iframe :src="getOfficeUrl(activeFile)" class="w-full h-full border-0 bg-white"
                            title="Office Viewer"></iframe>
                    </template>
                    <template x-if="activeFile && isViewable(activeFile) && isImage(activeFile)">
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-900 p-4 overflow-auto">
                            <img :src="getUrl(activeFile)" :alt="activeFile.filename"
                                class="max-w-full max-h-full object-contain rounded-lg shadow-lg" />
                        </div>
                    </template>
                    <template x-if="activeFile && !isViewable(activeFile)">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div
                                    class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-eye-slash text-4xl text-slate-600"></i>
                                </div>
                                <p class="text-slate-400 font-medium">Preview restricted by administrator</p>
                            </div>
                        </div>
                    </template>
                    <template x-if="!activeFile">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div
                                    class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-file-alt text-4xl text-slate-600"></i>
                                </div>
                                <p class="text-slate-400 font-medium">Select a file from the panel</p>
                            </div>
                        </div>
                    </template>
                </div>{{-- end viewer pane --}}

                <!-- ===== Inline File Remark Panel ===== -->
                <div x-show="activeFile" style="display:none;"
                    class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-1.5" x-data="{ dropdownOpen: false }">
                            <p
                                class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest flex items-center gap-1.5 m-0">
                                <i class="fas fa-comment-alt text-indigo-400"></i>
                                My Remark for
                            </p>
                            <div class="relative flex items-center" @click.outside="dropdownOpen = false">
                                <button type="button" @click="dropdownOpen = !dropdownOpen"
                                    class="text-[10px] text-indigo-600 font-bold bg-transparent border-none cursor-pointer focus:outline-none flex items-center gap-1 hover:text-indigo-800 transition-colors uppercase tracking-widest p-0 m-0 leading-none">
                                    <span class="max-w-[200px] truncate"
                                        x-text="activeFile ? activeFile.label : 'Select document...'"></span>
                                    <i class="fas fa-chevron-down opacity-80 transition-transform duration-200 ml-0.5"
                                        :class="dropdownOpen ? 'rotate-180' : ''" style="font-size: 8px;"></i>
                                </button>
                                <div x-show="dropdownOpen" x-transition.opacity.duration.150ms style="display: none;"
                                    class="origin-top-left absolute left-0 top-[120%] mt-1 w-64 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 border border-slate-100 overflow-hidden">
                                    <div class="py-1 max-h-60 overflow-y-auto">
                                        <template x-for="file in getFilesForActiveTab()" :key="file.id">
                                            <button type="button" @click="selectFile(file); dropdownOpen = false"
                                                class="w-full text-left px-4 py-2.5 text-[11px] font-bold transition-colors border-l-4 leading-normal"
                                                :class="activeFile && activeFile.id === file.id ? 'bg-indigo-50 text-indigo-700 border-indigo-500' : 'text-slate-600 border-transparent hover:bg-slate-50'">
                                                <span x-text="file.label"
                                                    class="block truncate mix-blend-multiply"></span>
                                            </button>
                                        </template>
                                        <template x-if="getFilesForActiveTab().length === 0">
                                            <div
                                                class="px-4 py-3 text-[11px] text-slate-400 font-medium flex flex-col gap-1">
                                                <span>No documents available</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Saved indicator -->
                        <span x-show="remarkSaved" x-transition.opacity
                            class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Saved!
                        </span>
                        <span x-show="!!remarkError" class="text-[10px] font-bold text-red-500"
                            x-text="remarkError"></span>
                    </div>
                    <textarea x-model="currentRemark" placeholder="Add your remark or comment about this document..."
                        rows="3"
                        class="w-full px-3 py-2.5 text-sm text-slate-700 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent outline-none shadow-sm resize-none bg-slate-50 transition-all"></textarea>
                    <div class="flex justify-end mt-2">
                        <button type="button" @click="saveRemark()" :disabled="remarkSaving"
                            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                            <i class="fas" :class="remarkSaving ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                            <span x-text="remarkSaving ? 'Saving...' : 'Save Remark'"></span>
                        </button>
                    </div>
                </div>{{-- end remark panel --}}

            </div>{{-- end left col lg:col-span-8 --}}

            <!-- ===== RIGHT — Sidebar ===== -->
            <div class="lg:col-span-4 flex flex-col gap-4 pb-8">

                <!-- PI Card -->
                <div
                    class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden flex-shrink-0">
                    <div
                        class="absolute top-0 right-0 w-28 h-28 bg-gradient-to-br from-slate-50 to-slate-100 rounded-bl-full -mr-6 -mt-6">
                    </div>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Principal
                        Investigator</p>
                    <div class="flex items-center gap-4 relative z-10">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#8B0000] to-red-900 text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-red-900/20 ring-4 ring-red-50 flex-shrink-0">
                            {{ substr($researchTitle->researcher->user->first_name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 leading-tight">
                                {{ $researchTitle->researcher->user->first_name ?? 'Unknown' }}
                                {{ $researchTitle->researcher->user->last_name ?? '' }}
                            </p>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wide mt-0.5">
                                {{ $researchTitle->researcher->college ?? 'External' }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $researchTitle->Research_Category }}</p>
                        </div>
                    </div>
                </div>

                <!-- Version Timeline -->
                @if($hasRevisions)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex-shrink-0">
                        <p
                            class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-code-branch text-indigo-400"></i> Version Timeline
                        </p>
                        <div class="relative pl-4">
                            <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-slate-100"></div>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-100 border-2 border-slate-300 flex items-center justify-center flex-shrink-0 z-10 -ml-4">
                                        <i class="fas fa-box-archive text-slate-500 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-700">Original Submission</p>
                                        <p class="text-[10px] text-slate-400">
                                            {{ $originalFiles->first()?->created_at?->format('M d, Y') ?? '—' }} ·
                                            {{ $originalFiles->count() }} doc(s)
                                        </p>
                                    </div>
                                </div>
                                @foreach($revisionFolders->sortKeys() as $revNum => $files)
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-indigo-50 border-2 border-indigo-300 flex items-center justify-center flex-shrink-0 z-10 -ml-4">
                                            <span class="text-[10px] font-black text-indigo-600">{{ $revNum }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-700">Revision {{ $revNum }}</p>
                                            <p class="text-[10px] text-slate-400">
                                                {{ $files->first()?->created_at?->format('M d, Y') ?? '—' }} ·
                                                {{ $files->count() }} doc(s)
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Reviewer Upload Section -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 overflow-hidden flex-shrink-0"
                    x-show="activeFile" style="display: none;">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt text-[#8B0000]"></i> <span
                            x-text="'Upload review for ' + (activeFile ? activeFile.label : '')"></span>
                    </h3>
                    <form action="{{ route('reviewer.upload', $researchTitle->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-4 relative">
                        @csrf
                        <input type="hidden" name="category" :value="activeFile ? activeFile.label : 'Other'">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-2">Evaluation
                                    Document</label>
                                <input type="file" name="files[]" required multiple
                                    class="w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700 transition-colors bg-slate-50 border border-slate-200 rounded-xl cursor-pointer">
                            </div>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="w-full bg-[#8B0000] hover:bg-red-900 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-md shadow-red-900/20 flex items-center justify-center gap-2 text-xs focus:ring-4 focus:ring-red-100">
                                <i class="fas fa-upload"></i> Upload Evaluation
                            </button>
                        </div>
                    </form>

                    @php
                        $reviewerUploads = $researchTitle->adminFiles()->where('uploaded_by', Auth::id())->where('category', 'like', 'Reviewer Uploads%')->latest()->get();
                    @endphp
                    @if($reviewerUploads->isNotEmpty())
                        <div class="mt-6 pt-5 border-t border-slate-100">
                            <div class="flex flex-col gap-3 mb-4">
                                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest m-0">My
                                    Uploaded Evaluations</p>
                                @if($researchTitle->Status !== 'Reviewed')
                                    @if(in_array($researchTitle->Status, ['Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions']))
                                        <div x-data="{ 
                                                                                                                                                        showModal: false, 
                                                                                                                                                        step: 1,
                                                                                                                                                        scientific_soundness: '',
                                                                                                                                                        ethical_issues: '',
                                                                                                                                                        icf_issues: '',
                                                                                                                                                        summary_of_issues: '',
                                                                                                                                                        stepOneValid() {
                                                                                                                                                            return this.scientific_soundness.trim() !== '' 
                                                                                                                                                                && this.ethical_issues.trim() !== '' 
                                                                                                                                                                && this.icf_issues.trim() !== '' 
                                                                                                                                                                && this.summary_of_issues.trim() !== '';
                                                                                                                                                        },
                                                                                                                                                        proceedToStep2() {
                                                                                                                                                            if (!this.stepOneValid()) {
                                                                                                                                                                alert('Please fill out all deliberation fields before proceeding.');
                                                                                                                                                                return;
                                                                                                                                                            }
                                                                                                                                                            this.step = 2;
                                                                                                                                                        },
                                                                                                                                                        resetWizard() {
                                                                                                                                                            this.step = 1;
                                                                                                                                                            this.scientific_soundness = '';
                                                                                                                                                            this.ethical_issues = '';
                                                                                                                                                            this.icf_issues = '';
                                                                                                                                                            this.summary_of_issues = '';
                                                                                                                                                        }
                                                                                                                                                    }"
                                            class="w-full">
                                            <button type="button" @click="resetWizard(); showModal = true"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm shadow-green-900/20 flex items-center justify-center gap-2 text-xs transition-all">
                                                <i class="fas fa-check-circle"></i> Complete Review
                                            </button>

                                            <!-- Embedded CSS for the custom thin scrollbar -->
                                            <style>
                                                .custom-scrollbar::-webkit-scrollbar {
                                                    width: 6px;
                                                }

                                                .custom-scrollbar::-webkit-scrollbar-track {
                                                    background: transparent;
                                                }

                                                .custom-scrollbar::-webkit-scrollbar-thumb {
                                                    background-color: #cbd5e1;
                                                    border-radius: 10px;
                                                }

                                                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                                                    background-color: #94a3b8;
                                                }
                                            </style>

                                            <!-- Two-Step Wizard Modal -->
                                            <template x-teleport="body">
                                                <div x-show="showModal" style="display: none;"
                                                    class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title"
                                                    role="dialog" aria-modal="true">
                                                    <div
                                                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                        <div x-show="showModal" x-transition.opacity
                                                            class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"
                                                            @click="showModal = false" aria-hidden="true"></div>
                                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                            aria-hidden="true">&#8203;</span>
                                                        <div x-show="showModal" x-transition.scale.origin.bottom
                                                            class="inline-flex flex-col align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl lg:max-w-6xl sm:w-full border border-slate-100 max-h-[90vh]">

                                                            <!-- Header -->
                                                            <div
                                                                class="bg-white px-6 pt-5 pb-4 border-b border-slate-100 flex-shrink-0">
                                                                <div class="flex items-center justify-between">
                                                                    <div class="flex items-center gap-3">
                                                                        <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                                                            :class="step === 1 ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600'">
                                                                            <i class="fas"
                                                                                :class="step === 1 ? 'fa-clipboard-list' : 'fa-gavel'"></i>
                                                                        </div>
                                                                        <div>
                                                                            <h3 class="text-lg leading-6 font-bold text-slate-900"
                                                                                id="modal-title"
                                                                                x-text="step === 1 ? 'Step 1: Deliberation Assessment' : 'Step 2: Final Action'">
                                                                            </h3>
                                                                            <p class="text-xs text-slate-500 mt-0.5"
                                                                                x-text="step === 1 ? 'Document your assessment before casting a vote' : 'Review your summary and select your recommendation'">
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" @click="showModal = false"
                                                                        class="text-slate-400 hover:text-slate-500 transition-colors">
                                                                        <i class="fas fa-times text-xl"></i>
                                                                    </button>
                                                                </div>

                                                                <!-- Step Indicator -->
                                                                <div class="flex items-center gap-2 mt-4">
                                                                    <div class="flex items-center gap-1.5">
                                                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                                                                            :class="step === 1 ? 'bg-blue-600 text-white' : 'bg-green-500 text-white'">
                                                                            <span x-show="step === 1">1</span>
                                                                            <i x-show="step === 2"
                                                                                class="fas fa-check text-[10px]"></i>
                                                                        </div>
                                                                        <span class="text-xs font-bold"
                                                                            :class="step === 1 ? 'text-blue-600' : 'text-green-500'">Deliberation</span>
                                                                    </div>
                                                                    <div class="flex-1 h-0.5 rounded-full transition-colors"
                                                                        :class="step === 2 ? 'bg-green-500' : 'bg-slate-200'"></div>
                                                                    <div class="flex items-center gap-1.5">
                                                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                                                                            :class="step === 2 ? 'bg-green-600 text-white' : 'bg-slate-200 text-slate-400'">
                                                                            2
                                                                        </div>
                                                                        <span class="text-xs font-bold"
                                                                            :class="step === 2 ? 'text-green-600' : 'text-slate-400'">Action</span>
                                                                    </div>

                                                                </div>

                                                                <!-- Form wraps both steps -->
                                                                <form
                                                                    action="{{ route('reviewer.complete_review', $researchTitle->id) }}"
                                                                    method="POST" class="m-0 flex-1 overflow-y-auto">
                                                                    @csrf

                                                                    <!-- ============================================ -->
                                                                    <!-- STEP 1: Deliberation Assessment              -->
                                                                    <!-- ============================================ -->
                                                                    <div x-show="step === 1" class="px-6 py-5">



                                                                        <!-- 2-COLUMN SPLIT-PANE GRID -->
                                                                        <div
                                                                            class="grid grid-cols-1 xl:grid-cols-2 gap-8 xl:gap-10">

                                                                            <!-- LEFT COLUMN (Document Remarks) -->
                                                                            <div
                                                                                class="xl:border-r xl:border-slate-100 xl:pr-8 xl:h-[55vh] flex flex-col">
                                                                                {{-- Per-file remarks for RESEARCHER files --}}
                                                                                @php
                                                                                    $filesToReview = $activeFiles->isNotEmpty() ? $activeFiles : $originalFiles;
                                                                                @endphp
                                                                                <label
                                                                                    class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-3 border-b border-slate-100 pb-2">Document-Specific
                                                                                    Remarks (Optional)</label>

                                                                                <div
                                                                                    class="space-y-3 flex-1 overflow-y-auto pr-2 custom-scrollbar">
                                                                                    @foreach($filesToReview as $file)
                                                                                        <div
                                                                                            class="group relative bg-white border border-slate-200 rounded-xl p-4 transition-all hover:shadow-md hover:border-slate-300 w-full mb-3">
                                                                                            <div class="flex items-start gap-4 mb-3">
                                                                                                <div
                                                                                                    class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center flex-shrink-0 transition-colors group-hover:bg-indigo-100">
                                                                                                    <i
                                                                                                        class="fas fa-file-invoice text-indigo-600 text-sm"></i>
                                                                                                </div>
                                                                                                <div class="flex-1 min-w-0">
                                                                                                    <h4 class="text-sm font-bold text-slate-800 truncate"
                                                                                                        title="{{ $file->filename }}">
                                                                                                        {{ $file->filename }}
                                                                                                    </h4>
                                                                                                    <p
                                                                                                        class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mt-0.5">
                                                                                                        {{ $file->category }}
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            @php
                                                                                                $existingRemark = isset($myFileRemarks[$file->id]) ? $myFileRemarks[$file->id]->remarks : '';
                                                                                            @endphp
                                                                                            <div class="relative">
                                                                                                <div
                                                                                                    class="absolute inset-y-0 left-0 pl-3 pt-3 pointer-events-none">
                                                                                                    <i
                                                                                                        class="fas fa-comment-dots text-slate-300"></i>
                                                                                                </div>
                                                                                                <textarea
                                                                                                    name="file_remarks[{{ $file->id }}]"
                                                                                                    rows="2"
                                                                                                    placeholder="Add specific remarks for this document..."
                                                                                                    class="w-full pl-10 pr-4 py-2.5 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none shadow-sm resize-none transition-all placeholder:text-slate-400 min-h-[60px]">{{ $existingRemark }}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>

                                                                            <!-- RIGHT COLUMN (Overarching Issues) -->
                                                                            <div
                                                                                class="space-y-6 flex-1 overflow-y-auto pr-4 custom-scrollbar xl:h-[55vh]">
                                                                                <!-- Scientific Soundness -->
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-sm font-bold text-slate-800 mb-2 mt-4 xl:mt-0">
                                                                                        <i
                                                                                            class="fas fa-microscope text-indigo-500 mr-1.5 hover:scale-110 transition-transform inline-block"></i>
                                                                                        Scientific Soundness <span
                                                                                            class="text-red-500">*</span>
                                                                                    </label>
                                                                                    <textarea x-model="scientific_soundness"
                                                                                        name="scientific_soundness" rows="3"
                                                                                        required
                                                                                        placeholder="Evaluate the scientific merit: research design, methodology, data analysis plan, sample size justification..."
                                                                                        class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white outline-none shadow-sm resize-y transition-all placeholder:text-slate-400 min-h-[90px] leading-relaxed"></textarea>
                                                                                </div>

                                                                                <!-- Ethical Issues -->
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-sm font-bold text-slate-800 mb-2">
                                                                                        <i
                                                                                            class="fas fa-balance-scale text-amber-500 mr-1.5 hover:scale-110 transition-transform inline-block"></i>
                                                                                        Ethical Issues <span
                                                                                            class="text-red-500">*</span>
                                                                                    </label>
                                                                                    <textarea x-model="ethical_issues"
                                                                                        name="ethical_issues" rows="3" required
                                                                                        placeholder="Assess ethical considerations: risk-benefit ratio, privacy, confidentiality, vulnerable populations, potential harm..."
                                                                                        class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none shadow-sm resize-y transition-all placeholder:text-slate-400 min-h-[90px] leading-relaxed"></textarea>
                                                                                </div>

                                                                                <!-- ICF Issues -->
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-sm font-bold text-slate-800 mb-2">
                                                                                        <i
                                                                                            class="fas fa-file-signature text-emerald-500 mr-1.5 hover:scale-110 transition-transform inline-block"></i>
                                                                                        Informed Consent Form (ICF) Issues <span
                                                                                            class="text-red-500">*</span>
                                                                                    </label>
                                                                                    <textarea x-model="icf_issues" name="icf_issues"
                                                                                        rows="3" required
                                                                                        placeholder="Review the informed consent: clarity of language, voluntariness, adequate disclosure, comprehension, documentation process..."
                                                                                        class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white outline-none shadow-sm resize-y transition-all placeholder:text-slate-400 min-h-[90px] leading-relaxed"></textarea>
                                                                                </div>

                                                                                <!-- Summary of Issues and Resolutions -->
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-sm font-bold text-slate-800 mb-2">
                                                                                        <i
                                                                                            class="fas fa-list-check text-rose-500 mr-1.5 hover:scale-110 transition-transform inline-block"></i>
                                                                                        Summary
                                                                                        of Issues and Resolutions <span
                                                                                            class="text-red-500">*</span>
                                                                                    </label>
                                                                                    <textarea x-model="summary_of_issues"
                                                                                        name="summary_of_issues" rows="3" required
                                                                                        placeholder="Summarize: which issues were resolved, which remain unresolved, and your recommendations for unresolved issues..."
                                                                                        class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 focus:bg-white outline-none shadow-sm resize-y transition-all placeholder:text-slate-400 min-h-[110px] leading-relaxed"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Step 1 Buttons -->
                                                                    <div x-show="step === 1"
                                                                        class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
                                                                        <button type="button" @click="showModal = false"
                                                                            class="flex-1 inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-2.5 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none transition-colors">
                                                                            Cancel
                                                                        </button>
                                                                        <button type="button" @click="proceedToStep2()"
                                                                            class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl shadow-sm px-4 py-2.5 bg-blue-600 text-sm font-bold text-white hover:bg-blue-700 focus:outline-none transition-colors border border-transparent">
                                                                            Save Deliberation & Proceed <i
                                                                                class="fas fa-arrow-right text-xs"></i>
                                                                        </button>
                                                                    </div>

                                                                    <!-- ============================================ -->
                                                                    <!-- STEP 2: Final Action                        -->
                                                                    <!-- ============================================ -->
                                                                    <div x-show="step === 2" style="display: none;"
                                                                        class="px-6 py-5 space-y-4">

                                                                        <!-- Read-only Summary Reference -->
                                                                        <div
                                                                            class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                                                            <p
                                                                                class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">
                                                                                <i class="fas fa-quote-left mr-1"></i> Your Summary
                                                                                of
                                                                                Issues
                                                                            </p>
                                                                            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap"
                                                                                x-text="summary_of_issues"></p>
                                                                        </div>

                                                                        <!-- Re-Evaluation: Action Taken -->
                                                                        <div class="text-left">
                                                                            <label
                                                                                class="block text-xs font-bold text-slate-700 mb-2">
                                                                                <i class="fas fa-gavel text-rose-400 mr-1"></i>
                                                                                Action
                                                                                Taken
                                                                                <span class="text-red-400">*</span>
                                                                            </label>
                                                                            <div class="relative">
                                                                                <div
                                                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                    <i
                                                                                        class="fas fa-gavel text-slate-400 text-sm"></i>
                                                                                </div>
                                                                                <select name="review_decision" required
                                                                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-300 transition-colors appearance-none">
                                                                                    <option value="" disabled selected>Select a
                                                                                        recommendation...</option>
                                                                                    <option value="Approved">✅ Approved (No further
                                                                                        revisions needed)</option>
                                                                                    <option value="Minor revision/s required">🟡
                                                                                        Minor
                                                                                        revision/s required</option>
                                                                                    <option value="Major revision/s required">🟠
                                                                                        Major
                                                                                        revision/s required</option>
                                                                                    <option value="Disapproved">❌ Disapproved
                                                                                    </option>
                                                                                </select>
                                                                                <div
                                                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                                                    <i
                                                                                        class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="text-left">
                                                                            <label
                                                                                class="block text-xs font-bold text-slate-700 mb-2">Brief
                                                                                Remarks (Optional)</label>
                                                                            <textarea name="remarks" rows="2"
                                                                                placeholder="Brief summary of your decision..."
                                                                                class="w-full px-3 py-2 text-sm text-slate-700 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none shadow-sm"></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Step 2 Buttons -->
                                                                    <div x-show="step === 2" style="display: none;"
                                                                        class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
                                                                        <button type="button" @click="step = 1"
                                                                            class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl border border-slate-200 shadow-sm px-4 py-2.5 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none transition-colors">
                                                                            <i class="fas fa-arrow-left text-xs"></i> Back to
                                                                            Deliberation
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl shadow-sm px-4 py-2.5 bg-green-600 text-sm font-bold text-white hover:bg-green-700 focus:outline-none transition-colors border border-transparent">
                                                                            <i class="fas fa-check-circle"></i> Finalize Review
                                                                        </button>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                            </template>
                                        </div>
                                    @else
                                        <div x-data="{ showModal: false }" class="w-full">
                                            <button type="button" @click="showModal = true"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm shadow-green-900/20 flex items-center justify-center gap-2 text-xs transition-all">
                                                <i class="fas fa-check-circle"></i> Complete Review
                                            </button>

                                            <!-- Simple Single-Step Modal -->
                                            <template x-teleport="body">
                                                <div x-show="showModal" style="display: none;"
                                                    class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title"
                                                    role="dialog" aria-modal="true">
                                                    <div
                                                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                        <div x-show="showModal" x-transition.opacity
                                                            class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"
                                                            @click="showModal = false" aria-hidden="true"></div>
                                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                            aria-hidden="true">&#8203;</span>
                                                        <div x-show="showModal" x-transition.scale.origin.bottom
                                                            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">

                                                            <!-- Header -->
                                                            <div class="bg-white px-6 pt-5 pb-4 border-b border-slate-100">
                                                                <div class="flex items-center justify-between">
                                                                    <div class="flex items-center gap-3">
                                                                        <div
                                                                            class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                                                            <i class="fas fa-check-circle"></i>
                                                                        </div>
                                                                        <div>
                                                                            <h3 class="text-lg leading-6 font-bold text-slate-900"
                                                                                id="modal-title">Complete Review</h3>
                                                                            <p class="text-xs text-slate-500 mt-0.5">Submit your
                                                                                final
                                                                                recommendation for this protocol.</p>
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" @click="showModal = false"
                                                                        class="text-slate-400 hover:text-slate-500 transition-colors">
                                                                        <i class="fas fa-times text-xl"></i>
                                                                    </button>
                                                                </div>
                                                                <p
                                                                    class="text-xs text-slate-500 bg-amber-50 border border-amber-100 px-3 py-2 rounded-xl mt-4">
                                                                    <i class="fas fa-exclamation-triangle text-amber-500 mr-1"></i>
                                                                    You <span class="font-bold">might not be able to modify</span>
                                                                    your
                                                                    uploads once marked as reviewed.
                                                                </p>
                                                            </div>

                                                            <form
                                                                action="{{ route('reviewer.complete_review', $researchTitle->id) }}"
                                                                method="POST" class="m-0">
                                                                @csrf
                                                                <div class="px-6 py-5">
                                                                    <div class="text-left">
                                                                        <label class="block text-xs font-bold text-slate-700 mb-2">
                                                                            <i class="fas fa-layer-group text-indigo-400 mr-1"></i>
                                                                            Suggested Next Review Type <span
                                                                                class="text-red-400">*</span>
                                                                        </label>
                                                                        <div class="relative">
                                                                            <div
                                                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                <i
                                                                                    class="fas fa-layer-group text-slate-400 text-sm"></i>
                                                                            </div>
                                                                            <select name="suggested_review_type" required
                                                                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-300 transition-colors appearance-none">
                                                                                <option value="" disabled selected>Select a review
                                                                                    type...</option>
                                                                                <option value="Exempt Review">Exempt Review</option>
                                                                                <option value="Expedited Review">Expedited Review
                                                                                </option>
                                                                                <option value="Full Board Review">Full Board Review
                                                                                </option>
                                                                            </select>
                                                                            <div
                                                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                                                <i
                                                                                    class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
                                                                    <button type="button" @click="showModal = false"
                                                                        class="flex-1 inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-2.5 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none transition-colors">
                                                                        Cancel
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl shadow-sm px-4 py-2.5 bg-green-600 text-sm font-bold text-white hover:bg-green-700 focus:outline-none transition-colors border border-transparent">
                                                                        <i class="fas fa-check-circle"></i> Finalize Review
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    @endif
                                @else
                                    <div
                                        class="w-full text-center px-3 py-2 bg-green-50 text-green-600 rounded-xl text-xs font-bold border border-green-100">
                                        <i class="fas fa-check"></i> Review Completed
                                    </div>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 gap-2">
                                @foreach($reviewerUploads as $upload)
                                    <div x-data="{ removing: false, deleted: false }" x-show="!deleted" x-transition.opacity
                                        class="flex items-center justify-between p-2 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-slate-300 transition-all group">
                                        <div class="flex items-center gap-2 overflow-hidden">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 flex flex-shrink-0 items-center justify-center text-[#8B0000]">
                                                <i class="fas fa-file-alt text-sm"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-[10px] font-bold text-slate-800 truncate"
                                                    title="{{ $upload->filename }}">{{ $upload->filename }}</p>
                                                <p class="text-[9px] text-slate-500 font-medium truncate">
                                                    {{ str_replace('Reviewer Uploads - ', '', $upload->category) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all">
                                            @if($researchTitle->Status !== 'Reviewed')
                                                <!-- Delete Button -->
                                                <button @click="if(!confirm('Are you sure you want to remove this evaluation?')) return;
                                                                                                removing = true; 
                                                                                                fetch('{{ route('reviewer.file.delete', $upload->id) }}', { 
                                                                                                    method: 'DELETE', 
                                                                                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } 
                                                                                                }).then(res => { 
                                                                                                    if(res.ok) deleted = true; 
                                                                                                    else { removing = false; Swal.fire('Error', 'Failed to remove file', 'error'); } 
                                                                                                });" :disabled="removing"
                                                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 flex flex-shrink-0 items-center justify-center transition-colors disabled:opacity-50">
                                                    <i class="fas fa-times text-xs" x-show="!removing"></i>
                                                    <i class="fas fa-circle-notch fa-spin text-xs" x-show="removing"
                                                        style="display:none;"></i>
                                                </button>
                                            @endif

                                            <!-- View Button -->
                                            <a href="{{ route('reviewer.serve_file', $upload->id) }}" target="_blank"
                                                class="w-7 h-7 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 flex flex-shrink-0 items-center justify-center transition-all">
                                                <i class="fas fa-external-link-alt text-xs"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Tabbed File Picker -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0">
                    <!-- Tab bar -->
                    <div
                        class="flex gap-1 border-b border-slate-100 bg-slate-50/60 px-2 pt-2 overflow-x-auto custom-scrollbar flex-shrink-0">
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
                                <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2"
                                    x-init="$watch('activeFile', f => { expanded = (f && group.files.some(file => file.id === f.id)) }); expanded = (activeFile && group.files.some(f => f.id === activeFile.id));"
                                    x-data="{ expanded: false }">
                                    <button @click="expanded = !expanded"
                                        class="w-full flex items-center justify-between px-4 py-3 bg-slate-50/80 hover:bg-slate-100 transition-colors">
                                        <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest"
                                            x-text="group.category"></h4>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded-full border border-slate-200"
                                                x-text="group.files.length + ' file(s)'"></span>
                                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"
                                                :class="expanded ? 'rotate-180' : ''"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <template x-for="file in group.files" :key="file.id">
                                            <button @click="selectFile(file)"
                                                :class="activeFile && activeFile.id === file.id ? 'bg-emerald-50 border-l-4 border-emerald-500' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all bg-white border-t border-slate-100">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                                    :class="file.bg">
                                                    <i :class="[file.icon, file.color]"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold text-slate-800 truncate"
                                                        x-text="file.filename"></p>
                                                    <p class="text-[10px] text-slate-400" x-text="file.uploaded_at"></p>
                                                </div>
                                                <div x-show="activeFile && activeFile.id === file.id"
                                                    class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></div>
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
                                <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2"
                                    x-init="$watch('activeFile', f => { expanded = (f && group.files.some(file => file.id === f.id)) }); expanded = (activeFile && group.files.some(f => f.id === activeFile.id));"
                                    x-data="{ expanded: false }">
                                    <button @click="expanded = !expanded"
                                        class="w-full flex items-center justify-between px-4 py-3 bg-slate-50/80 hover:bg-slate-100 transition-colors">
                                        <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest"
                                            x-text="group.category"></h4>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded-full border border-slate-200"
                                                x-text="group.files.length + ' file(s)'"></span>
                                            <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"
                                                :class="expanded ? 'rotate-180' : ''"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <template x-for="file in group.files" :key="file.id">
                                            <button @click="selectFile(file)"
                                                :class="activeFile && activeFile.id === file.id ? 'bg-red-50 border-l-4 border-[#8B0000]' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all bg-white border-t border-slate-100">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                                    :class="file.bg">
                                                    <i :class="[file.icon, file.color]"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold text-slate-800 truncate"
                                                        x-text="file.filename"></p>
                                                    <p class="text-[10px] text-slate-400" x-text="file.uploaded_at"></p>
                                                </div>
                                                <div x-show="activeFile && activeFile.id === file.id"
                                                    class="w-2 h-2 rounded-full bg-[#8B0000] flex-shrink-0"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Revision tabs (one per revision number) -->
                        @foreach($revisionFolders->sortKeys() as $revNum => $_)
                            <div x-show="activeTab === 'rev_{{ $revNum }}'" style="display:none;">
                                <div
                                    class="px-4 py-2.5 bg-indigo-50/60 border-b border-indigo-100 flex items-center justify-between mb-2">
                                    <p class="text-xs font-extrabold text-indigo-600 uppercase tracking-wider">Revision
                                        {{ $revNum }}
                                    </p>
                                </div>
                                <template x-for="group in revisions['{{ $revNum }}']" :key="group.category">
                                    <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2"
                                        x-init="$watch('activeFile', f => { expanded = (f && group.files.some(file => file.id === f.id)) }); expanded = (activeFile && group.files.some(f => f.id === activeFile.id));"
                                        x-data="{ expanded: false }">
                                        <button @click="expanded = !expanded"
                                            class="w-full flex items-center justify-between px-4 py-3 bg-slate-50/80 hover:bg-slate-100 transition-colors">
                                            <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest"
                                                x-text="group.category"></h4>
                                            <div class="flex items-center gap-3">
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded-full border border-slate-200"
                                                    x-text="group.files.length + ' file(s)'"></span>
                                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"
                                                    :class="expanded ? 'rotate-180' : ''"></i>
                                            </div>
                                        </button>
                                        <div x-show="expanded" style="display: none;" x-transition>
                                            <template x-for="file in group.files" :key="file.id">
                                                <button @click="selectFile(file)"
                                                    :class="activeFile && activeFile.id === file.id ? 'bg-indigo-50 border-l-4 border-indigo-500' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                                    class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all bg-white border-t border-slate-100">
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                                        :class="file.bg">
                                                        <i :class="[file.icon, file.color]"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-sm font-bold text-slate-800 truncate"
                                                            x-text="file.filename"></p>
                                                        <p class="text-[10px] text-slate-400" x-text="file.uploaded_at"></p>
                                                    </div>
                                                    <div x-show="activeFile && activeFile.id === file.id"
                                                        class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></div>
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
                                    <p class="text-xs font-extrabold text-[#8B0000] uppercase tracking-wider">Latest Version
                                        of Each Document</p>
                                </div>
                                <template x-for="group in activeFiles" :key="group.category">
                                    <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2"
                                        x-init="$watch('activeFile', f => { expanded = (f && group.files.some(file => file.id === f.id)) }); expanded = (activeFile && group.files.some(f => f.id === activeFile.id));"
                                        x-data="{ expanded: false }">
                                        <button @click="expanded = !expanded"
                                            class="w-full flex items-center justify-between px-4 py-3 bg-slate-50/80 hover:bg-slate-100 transition-colors">
                                            <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest"
                                                x-text="group.category"></h4>
                                            <div class="flex items-center gap-3">
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded-full border border-slate-200"
                                                    x-text="group.files.length + ' file(s)'"></span>
                                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200"
                                                    :class="expanded ? 'rotate-180' : ''"></i>
                                            </div>
                                        </button>
                                        <div x-show="expanded" style="display: none;" x-transition>
                                            <template x-for="file in group.files" :key="file.id">
                                                <button @click="selectFile(file)"
                                                    :class="activeFile && activeFile.id === file.id ? 'bg-red-50 border-l-4 border-[#8B0000]' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                                    class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all bg-white border-t border-slate-100">
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                                        :class="file.bg">
                                                        <i :class="[file.icon, file.color]"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-sm font-bold text-slate-800 truncate"
                                                            x-text="file.filename"></p>
                                                        <p class="text-[10px] font-bold"
                                                            :class="file.revision_number ? 'text-indigo-500' : 'text-slate-400'"
                                                            x-text="file.revision_number ? 'Revision ' + file.revision_number : 'Original'">
                                                        </p>
                                                    </div>
                                                    <div x-show="activeFile && activeFile.id === file.id"
                                                        class="w-2 h-2 rounded-full bg-[#8B0000] flex-shrink-0"></div>
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
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Submission
                        Details</p>
                    <div class="space-y-2.5">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500 flex items-center gap-2"><i
                                    class="fas fa-tag text-blue-400 w-3"></i> Category</span>
                            <span
                                class="text-xs font-bold text-slate-800 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">{{ $researchTitle->Research_Category }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500 flex items-center gap-2"><i
                                    class="fas fa-calendar text-purple-400 w-3"></i> Submitted</span>
                            <span
                                class="text-xs font-bold text-slate-800">{{ $researchTitle->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500 flex items-center gap-2"><i
                                    class="fas fa-code-branch text-indigo-400 w-3"></i> Revisions</span>
                            <span
                                class="text-xs font-bold {{ $hasRevisions ? 'text-indigo-600' : 'text-slate-500' }} bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100">
                                {{ $revisionFolders->count() }} submitted
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Activity Log -->
                @if($researchTitle->titleLogs && $researchTitle->titleLogs->isNotEmpty())
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex-shrink-0 mt-4">
                        <p
                            class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-history text-blue-400"></i> Activity Log
                        </p>
                        <div class="relative pl-4 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                            <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-slate-100"></div>
                            <div class="space-y-4">
                                @foreach($researchTitle->titleLogs as $log)
                                    <div class="flex gap-3 relative z-10">
                                        <div
                                            class="w-6 h-6 rounded-full bg-slate-100 border-2 border-slate-300 flex items-center justify-center flex-shrink-0 -ml-3 mt-0.5">
                                            <i class="fas fa-circle text-slate-400" style="font-size: 6px;"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-700 leading-tight">{{ $log->action }}</p>
                                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $log->description }}</p>
                                            <p class="text-[9px] text-slate-400 mt-1">
                                                {{ $log->created_at->format('M d, Y • h:i A') }}
                                            </p>
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