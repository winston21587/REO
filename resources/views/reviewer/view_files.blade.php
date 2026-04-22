<x-reviewer_layout>
    <div class="max-w-7xl mx-auto py-8 animate-[fadeInUp_0.5s_ease-out]">

        <!-- Top Navigation & Header -->
        <div
            class="flex items-center mb-6 gap-4 p-4 bg-gradient-to-r from-white to-slate-50 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full blur-3xl opacity-50 -mr-16 -mt-16 pointer-events-none">
            </div>
            <div class="flex items-center gap-4 relative z-10 w-full">
                <a href="{{ $backUrl }}"
                    class="flex items-center justify-center w-12 h-12 rounded-xl bg-white border border-slate-200 text-slate-400 hover:border-[#8B0000] hover:text-[#8B0000] hover:shadow-md transition-all duration-300 flex-shrink-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span
                            class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-extrabold uppercase tracking-widest border border-slate-200 shadow-sm">{{ $researchTitle->reoc_code ?? 'PENDING-ID' }}</span>
                        <span
                            class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-extrabold uppercase tracking-widest border border-amber-100 flex items-center gap-1 shadow-sm"><i
                                class="fas fa-clock text-[10px]"></i> {{ $researchTitle->Status }}</span>
                    </div>
                    <h1 class="text-xl font-black text-slate-900 font-heading leading-tight tracking-tight truncate"
                        title="{{ $researchTitle->Study_Protocol_title }}">{{ $researchTitle->Study_Protocol_title }}
                    </h1>
                </div>
            </div>
        </div>

        @if($researchTitle->revisionLogs->isNotEmpty())
            <div class="mb-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
                x-data="{ open: false }">
                <button @click="open = !open"
                    class="w-full px-6 py-4 flex justify-between items-center bg-slate-50/50 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-history text-blue-500"></i>
                        <span class="text-xs font-extrabold text-blue-600 uppercase tracking-widest">Revision History
                            Feedback</span>
                    </div>
                    <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" style="display: none;" x-transition>
                    <div
                        class="p-6 space-y-6 border-t border-slate-100 bg-white max-h-[400px] overflow-y-auto custom-scrollbar">
                        @foreach($researchTitle->revisionLogs as $log)
                            <div class="flex gap-4">
                                <div
                                    class="w-10 h-10 rounded-full {{ $log->user->role === 'admin' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center border-2 border-white shadow-sm flex-shrink-0">
                                    <i
                                        class="fas {{ $log->user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }} text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-slate-800">{{ $log->user->first_name }}
                                        {{ $log->user->last_name }} <span
                                            class="text-xs font-normal text-slate-500">({{ ucfirst($log->user->role) }})</span>
                                    </p>
                                    <p class="text-xs text-slate-400 mb-2">{{ $log->created_at->format('M d, Y • h:i A') }}</p>
                                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap">
                                            {{ $log->message }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
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

            $groupFiles = function ($collection, $groupLabel) use ($enrichFile, $requirementsMap) {
                $grouped = [];
                foreach ($collection as $f) {
                    $cat = $f->category ?? 'Uncategorized';
                    // Skip files that are not viewable for reviewers
                    $req = $requirementsMap[$cat] ?? null;
                    if ($req && !($req['is_viewable_for_reviewer'] == 1 || $req['is_viewable_for_reviewer'] === true)) {
                        continue;
                    }
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
            <div class="lg:col-span-6 flex flex-col gap-4">

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




                <!-- Below Viewer Details -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ subOpen: false, actOpen: false }">
                    <!-- Submission Details Accordion -->
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0 flex flex-col justify-start">
                        <button @click="subOpen = !subOpen"
                            class="w-full flex justify-between items-center px-5 py-4 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-400"></i>
                                <span
                                    class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Submission
                                    Details</span>
                            </div>
                            <i class="fas fa-chevron-up text-xs text-slate-400 transition-transform duration-300"
                                :class="subOpen ? '' : 'rotate-180'"></i>
                        </button>
                        <div x-show="subOpen" x-transition>
                            <div class="p-5 border-t border-slate-100 space-y-4 bg-white">
                                <div class="flex justify-between items-start gap-3">
                                    <div class="flex items-center gap-2 text-blue-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-tag w-4 text-center"></i> Category
                                    </div>
                                    <div
                                        class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 text-right text-xs font-bold text-slate-700">
                                        {{ $researchTitle->Research_Category ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-start gap-3">
                                    <div
                                        class="flex items-center gap-2 text-emerald-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-flask w-4 text-center"></i> Type
                                    </div>
                                    <div
                                        class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 text-right text-xs font-bold text-slate-700">
                                        {{ $researchTitle->research_type ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center gap-3">
                                    <div
                                        class="flex items-center gap-2 text-purple-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-calendar-alt w-4 text-center"></i> Submitted
                                    </div>
                                    <div class="text-xs font-bold text-slate-800">
                                        {{ $researchTitle->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center gap-3">
                                    <div
                                        class="flex items-center gap-2 text-indigo-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-code-branch w-4 text-center"></i> Revisions
                                    </div>
                                    <div
                                        class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 text-indigo-700 text-xs font-bold">
                                        {{ $revisionFolders->count() }} submitted
                                    </div>
                                </div>
                                @if(auth()->user()->reviewer?->show_researcher_identity)
                                <div class="flex justify-between items-start gap-3 border-t border-slate-100 pt-3 mt-3">
                                    <div class="flex items-center gap-2 text-rose-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-user-graduate w-4 text-center"></i> Researcher
                                    </div>
                                    <div class="flex flex-col items-end gap-1">
                                        <div class="bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-100 text-right text-xs font-bold text-rose-700">
                                            {{ $researchTitle->researcher->user->first_name ?? '' }} {{ $researchTitle->researcher->user->last_name ?? '' }}
                                        </div>
                                        <span class="text-[10px] text-slate-500 font-bold">{{ $researchTitle->researcher->user->email ?? '' }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Activity Log Accordion -->
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0 flex flex-col justify-start">
                        <button @click="actOpen = !actOpen"
                            class="w-full flex justify-between items-center px-5 py-4 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-history text-indigo-400"></i>
                                <span
                                    class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Activity
                                    Log</span>
                            </div>
                            <i class="fas fa-chevron-up text-xs text-slate-400 transition-transform duration-300"
                                :class="actOpen ? '' : 'rotate-180'"></i>
                        </button>
                        <div x-show="actOpen" x-transition>
                            <div class="p-5 border-t border-slate-100 bg-white">
                                <div class="relative pl-3 max-h-[420px] overflow-y-auto custom-scrollbar">
                                    <div class="absolute left-3 top-1 bottom-1 w-0.5 bg-slate-100"></div>
                                    <div class="space-y-4">
                                        @forelse($researchTitle->titleLogs as $log)
                                            <div class="flex gap-4 relative">
                                                <div
                                                    class="w-6 h-6 rounded-full bg-slate-100 border-4 border-white flex-shrink-0 z-10 -ml-[11px] flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-indigo-300"></div>
                                                </div>
                                                <div class="pb-1">
                                                    <p class="text-sm font-bold text-slate-800 leading-tight block">
                                                        {{ $log->action }}
                                                    </p>
                                                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                                        {{ $log->description }}
                                                    </p>
                                                    <p
                                                        class="text-[9px] font-bold text-slate-400 mt-1.5 uppercase tracking-wider">
                                                        {{ $log->created_at->format('M d, Y • h:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400 italic ml-4">No activity logs recorded.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- end left col lg:col-span-8 --}}

            <!-- ===== RIGHT — Sidebar ===== -->
            <div class="lg:col-span-6 flex flex-col gap-6 pb-8 items-start">

                <!-- Column 1 (Now visually below due to order-2) -->
                <div class="w-full order-2">
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
                                                        <p class="text-[10px] text-slate-400" x-text="file.uploaded_at">
                                                        </p>
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
                                                        <p class="text-[10px] text-slate-400" x-text="file.uploaded_at">
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
                                                            <p class="text-[10px] text-slate-400" x-text="file.uploaded_at">
                                                            </p>
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
                                        <p class="text-xs font-extrabold text-[#8B0000] uppercase tracking-wider">Latest
                                            Version
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
                </div><!-- End Column 1 -->

                <!-- Column 2 (Now visually above due to order-1) -->
                <div class="flex flex-col w-full gap-4 order-1">






                    <!-- Side-by-Side Flex Wrapping -->

                    <div class="w-full">
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
                                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest m-0">
                                            My
                                            Uploaded Evaluations</p>
                                        @if($researchTitle->Status !== 'Reviewed')
                                            @if(in_array($researchTitle->Status, ['Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions']))
                                                <div x-data="{ 
                                                                                                                                                                                                                                                                                                                                            showModal: false, 
                                                                                                                                                                                                                                                                                                                                            step: 1,
                                                                                                                                                                                                                                                                                                                                            showValidationError: false,
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
                                                                                                                                                                                                                                                                                                                                                    this.showValidationError = true;
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
                                                    class="w-full relative">
                                                    <button type="button" @click="resetWizard(); showModal = true"
                                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm shadow-green-900/20 flex items-center justify-center gap-2 text-xs transition-all">
                                                        <i class="fas fa-check-circle"></i> Complete Review
                                                    </button>

                                                    <!-- Validation Error Modal -->
                                                    <template x-teleport="body">
                                                        <div x-show="showValidationError" style="display: none;"
                                                            class="fixed inset-0 z-[120] overflow-y-auto"
                                                            aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                            <div
                                                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                                <div x-show="showValidationError" x-transition.opacity
                                                                    class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"
                                                                    @click="showValidationError = false" aria-hidden="true"></div>
                                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                                    aria-hidden="true">&#8203;</span>
                                                                <div x-show="showValidationError" x-transition.scale.origin.bottom
                                                                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                                                                    <div
                                                                        class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-slate-100">
                                                                        <div class="sm:flex sm:items-start">
                                                                            <div
                                                                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                                <i
                                                                                    class="fas fa-exclamation-triangle text-red-600"></i>
                                                                            </div>
                                                                            <div
                                                                                class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                                <h3 class="text-lg leading-6 font-bold text-slate-900"
                                                                                    id="modal-title">Incomplete Fields</h3>
                                                                                <div class="mt-2">
                                                                                    <p class="text-sm text-slate-500">Please provide
                                                                                        your assessment for all deliberation fields
                                                                                        (Scientific Soundness, Ethical Issues, ICF
                                                                                        Issues, and Summary of Issues) before
                                                                                        proceeding to cast your vote.</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl">
                                                                        <button type="button" @click="showValidationError = false"
                                                                            class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm font-bold text-white hover:bg-red-700 transition-colors focus:outline-none w-full sm:w-auto">
                                                                            I Understand
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>

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
                                                            class="fixed inset-0 z-[100] overflow-y-auto"
                                                            aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                            <div
                                                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                                <div x-show="showModal" x-transition.opacity
                                                                    class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"
                                                                    @click="showModal = false" aria-hidden="true"></div>
                                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                                    aria-hidden="true">&#8203;</span>
                                                                <div x-show="showModal" x-transition.scale.origin.bottom
                                                                    class="inline-flex flex-col align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl lg:max-w-6xl sm:w-full border border-slate-100 max-h-[90vh]">

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
                                                                                :class="step === 2 ? 'bg-green-500' : 'bg-slate-200'">
                                                                            </div>
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
                                                                                        {{-- Per-file remarks for RESEARCHER files
                                                                                        --}}
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
                                                                                                    <div
                                                                                                        class="flex items-start gap-4 mb-3">
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
                                                                                                name="ethical_issues" rows="3"
                                                                                                required
                                                                                                placeholder="Assess ethical considerations: risk-benefit ratio, privacy, confidentiality, vulnerable populations, potential harm..."
                                                                                                class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none shadow-sm resize-y transition-all placeholder:text-slate-400 min-h-[90px] leading-relaxed"></textarea>
                                                                                        </div>

                                                                                        <!-- ICF Issues -->
                                                                                        <div>
                                                                                            <label
                                                                                                class="block text-sm font-bold text-slate-800 mb-2">
                                                                                                <i
                                                                                                    class="fas fa-file-signature text-emerald-500 mr-1.5 hover:scale-110 transition-transform inline-block"></i>
                                                                                                Informed Consent Form (ICF) Issues
                                                                                                <span class="text-red-500">*</span>
                                                                                            </label>
                                                                                            <textarea x-model="icf_issues"
                                                                                                name="icf_issues" rows="3" required
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
                                                                                                name="summary_of_issues" rows="3"
                                                                                                required
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
                                                                                        <i class="fas fa-quote-left mr-1"></i> Your
                                                                                        Summary
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
                                                                                        <i
                                                                                            class="fas fa-gavel text-rose-400 mr-1"></i>
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
                                                                                            <option value="" disabled selected>
                                                                                                Select a
                                                                                                recommendation...</option>
                                                                                            <option value="Approved">✅ Approved (No
                                                                                                further
                                                                                                revisions needed)</option>
                                                                                            <option
                                                                                                value="Minor revision/s required">🟡
                                                                                                Minor
                                                                                                revision/s required</option>
                                                                                            <option
                                                                                                value="Major revision/s required">🟠
                                                                                                Major
                                                                                                revision/s required</option>
                                                                                            <option value="Disapproved">❌
                                                                                                Disapproved
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
                                                                                    <i class="fas fa-arrow-left text-xs"></i> Back
                                                                                    to
                                                                                    Deliberation
                                                                                </button>
                                                                                <button type="submit"
                                                                                    class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl shadow-sm px-4 py-2.5 bg-green-600 text-sm font-bold text-white hover:bg-green-700 focus:outline-none transition-colors border border-transparent">
                                                                                    <i class="fas fa-check-circle"></i> Finalize
                                                                                    Review
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
                                                            class="fixed inset-0 z-[100] overflow-y-auto"
                                                            aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                            <div
                                                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                                <div x-show="showModal" x-transition.opacity
                                                                    class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"
                                                                    @click="showModal = false" aria-hidden="true"></div>
                                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                                    aria-hidden="true">&#8203;</span>
                                                                <div x-show="showModal" x-transition.scale.origin.bottom
                                                                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-slate-100">

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
                                                                                    <p class="text-xs text-slate-500 mt-0.5">Submit
                                                                                        your
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
                                                                            <i
                                                                                class="fas fa-exclamation-triangle text-amber-500 mr-1"></i>
                                                                            You <span class="font-bold">might not be able to
                                                                                modify</span>
                                                                            your
                                                                            uploads once marked as reviewed.
                                                                        </p>
                                                                    </div>

                                                                    <form
                                                                        action="{{ route('reviewer.complete_review', $researchTitle->id) }}"
                                                                        method="POST" class="m-0 p-6">
                                                                        @csrf
                                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                                                            <!-- Left Column: Document Remarks -->
                                                                            <div
                                                                                class="space-y-4 max-h-[60vh] overflow-y-auto pr-3 custom-scrollbar border-r border-slate-100">
                                                                                <h4
                                                                                    class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                                                                                    <i class="fas fa-comments text-indigo-500"></i>
                                                                                    Individual Document Remarks
                                                                                </h4>
                                                                                @foreach($activeFiles as $file)
                                                                                    @php
                                                                                        $cat = $file->category ?? 'Uncategorized';
                                                                                        $ext = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
                                                                                    @endphp
                                                                                    <div class="mb-5 last:mb-0">
                                                                                        <label
                                                                                            class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                                                                            <span
                                                                                                class="truncate pr-2">{{ $cat }}</span>
                                                                                        </label>
                                                                                        <textarea name="file_remarks[{{ $file->id }}]"
                                                                                            rows="2"
                                                                                            class="w-full px-3 py-2 text-sm text-slate-700 border border-slate-200 bg-slate-50 hover:bg-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none shadow-sm transition-all"
                                                                                            placeholder="Write your notes for {{ $cat }}..."></textarea>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>


                                                                            <!-- Right Column: Final Decision -->
                                                                            <div class="flex flex-col h-full pl-2">
                                                                                <h4
                                                                                    class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                                                                                    <i
                                                                                        class="fas fa-layer-group text-indigo-500"></i>
                                                                                    Final Decision
                                                                                </h4>

                                                                                <div class="text-left mb-6 flex-grow">
                                                                                    <label
                                                                                        class="block text-xs font-bold text-slate-700 mb-2">
                                                                                        Suggested Next Review Type <span
                                                                                            class="text-red-400">*</span></label>
                                                                                    <div class="relative">
                                                                                        <div
                                                                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                                            <i
                                                                                                class="fas fa-layer-group text-slate-400 text-sm"></i>
                                                                                        </div>
                                                                                        <select name="suggested_review_type"
                                                                                            required
                                                                                            class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-[#8B0000] focus:border-transparent outline-none shadow-sm cursor-pointer hover:border-slate-300 transition-colors appearance-none">
                                                                                            <option value="" disabled selected>
                                                                                                Select a review type...</option>
                                                                                            <option value="Exempt Review">Exempt
                                                                                                Review</option>
                                                                                            <option value="Expedited Review">
                                                                                                Expedited Review</option>
                                                                                            <option value="Full Board Review">Full
                                                                                                Board Review</option>
                                                                                        </select>
                                                                                        <div
                                                                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                                                            <i
                                                                                                class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div
                                                                                    class="flex gap-3 mt-auto border-t border-slate-100 pt-6">
                                                                                    <button type="button" @click="showModal = false"
                                                                                        class="flex-1 inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-3 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none transition-colors">
                                                                                        Cancel
                                                                                    </button>
                                                                                    <button type="submit"
                                                                                        class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl shadow-sm px-4 py-3 bg-green-600 text-sm font-bold text-white hover:bg-green-700 focus:outline-none transition-colors border border-transparent">
                                                                                        <i class="fas fa-check-circle"></i> Finalize
                                                                                        Review
                                                                                    </button>
                                                                                </div>
                                                                            </div>
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
                                            <div x-data="{ removing: false, deleted: false }" x-show="!deleted"
                                                x-transition.opacity
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
                                                <div
                                                    class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all">
                                                    @if($researchTitle->Status !== 'Reviewed')
                                                        <!-- Delete Button -->
                                                        <form action="{{ route('reviewer.file.delete', $upload->id) }}"
                                                            method="POST" class="m-0 p-0 inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                onclick="Swal.fire({ title: 'Remove Evaluation?', text: 'Are you sure you want to remove this evaluation?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Yes, remove it!' }).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } });"
                                                                class="w-7 h-7 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 flex flex-shrink-0 items-center justify-center transition-colors">
                                                                <i class="fas fa-times text-xs"></i>
                                                            </button>
                                                        </form>
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
                        <!-- Version Timeline Accordion -->
                        @if($hasRevisions)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0 cursor-pointer"
                                x-data="{ timelineOpen: false }">
                                <button @click="timelineOpen = !timelineOpen"
                                    class="w-full flex justify-between items-center px-5 py-4 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-code-branch text-indigo-400"></i>
                                        <span
                                            class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Version
                                            Timeline</span>
                                    </div>
                                    <i class="fas fa-chevron-up text-xs text-slate-400 transition-transform duration-300"
                                        :class="timelineOpen ? '' : 'rotate-180'"></i>
                                </button>
                                <div x-show="timelineOpen" x-transition>
                                    <div class="p-5 border-t border-slate-100 bg-white">
                                        <div class="relative pl-4 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
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
                                                            {{ $originalFiles->first()?->created_at?->format('M d, Y') ?? '—' }}
                                                            ·
                                                            {{ $originalFiles->count() }} doc(s)
                                                        </p>
                                                    </div>
                                                </div>
                                                @foreach($revisionFolders->sortKeys() as $revNum => $files)
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="w-8 h-8 rounded-full bg-indigo-50 border-2 border-indigo-300 flex items-center justify-center flex-shrink-0 z-10 -ml-4">
                                                            <span
                                                                class="text-[10px] font-black text-indigo-600">{{ $revNum }}</span>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs font-bold text-slate-700">Revision {{ $revNum }}
                                                            </p>
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
                                </div>
                            </div>
                        @endif


                    </div><!-- End Column 2 -->
                </div>{{-- end sidebar --}}
            </div>{{-- end grid --}}
        </div>
</x-reviewer_layout>


