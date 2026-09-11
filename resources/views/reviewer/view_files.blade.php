<x-reviewer_layout>
    @php
        $allFiles = $researchTitle->files->merge($researchTitle->adminFiles ?? collect());
        $letters = $allFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review'])->sortByDesc('created_at');
        $protocolDocs = $researchTitle->files->whereNotIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review']);

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

        $reviewerUploads = $researchTitle->adminFiles()
            ->where('uploaded_by', Auth::id())
            ->where('category', 'like', 'Reviewer Uploads%')
            ->latest()
            ->get();

        $hasRevisions = $revisionFolders->isNotEmpty();
        $isReEvaluation = in_array($researchTitle->Status, ['Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions']);

        // Enriched file mapper matching admin and researcher studios
        $enrichFile = function ($file, $label) {
            $ext = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
            if (!$ext) {
                $ext = strtolower($file->filetype ?? '');
            }
            $icons = [
                'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => 'text-rose-700/80', 'bg' => 'bg-rose-50/60 text-rose-700/80'],
                'doc' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-700/80', 'bg' => 'bg-blue-50/60 text-blue-700/80'],
                'docx' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-700/80', 'bg' => 'bg-blue-50/60 text-blue-700/80'],
                'ppt' => ['icon' => 'fas fa-file-powerpoint', 'color' => 'text-amber-700/80', 'bg' => 'bg-amber-50/60 text-amber-700/80'],
                'pptx' => ['icon' => 'fas fa-file-powerpoint', 'color' => 'text-amber-700/80', 'bg' => 'bg-amber-50/60 text-amber-700/80'],
                'xls' => ['icon' => 'fas fa-file-excel', 'color' => 'text-emerald-700/80', 'bg' => 'bg-emerald-50/60 text-emerald-700/80'],
                'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => 'text-emerald-700/80', 'bg' => 'bg-emerald-50/60 text-emerald-700/80'],
                'jpg' => ['icon' => 'fas fa-file-image', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'],
                'jpeg' => ['icon' => 'fas fa-file-image', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'],
                'png' => ['icon' => 'fas fa-file-image', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'],
                'gif' => ['icon' => 'fas fa-file-image', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'],
                'webp' => ['icon' => 'fas fa-file-image', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'],
            ];
            $attrs = $icons[$ext] ?? ['icon' => 'fas fa-file-alt', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'];
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

        $firstFile = $originalFiles->first() ? $enrichFile($originalFiles->first(), 'Original') : ($activeFiles->first() ? $enrichFile($activeFiles->first(), 'Current') : null);
        $serveRoute = route('reviewer.serve_file', 'FILE_ID');
        $remarksByFileId = $myFileRemarks->map(fn($r) => $r->remarks)->toArray();
    @endphp

    <div class="max-w-7xl mx-auto w-full flex-1 min-h-0 flex flex-col lg:h-full lg:max-h-full gap-2.5"
         x-data="{
            drawerOpen: false,
            drawerTab: 'details',
            openDrawer(tab) {
                this.drawerTab = tab;
                this.drawerOpen = true;
            },
            closeDrawer() {
                this.drawerOpen = false;
            }
         }"
         @keydown.escape.window="if (drawerOpen) closeDrawer()">

        <!-- Top Institutional Header Card (Matching Admin & Researcher Studios) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 border-t-2 border-t-[#8B0000] shadow-xs px-4 py-2.5 sm:px-5 sm:py-3 relative overflow-hidden shrink-0">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5 sm:gap-3">
                
                <!-- Left: Back Button + Code + Status + Title -->
                <div class="flex items-start sm:items-center gap-3.5 min-w-0 flex-1">
                    <a href="{{ $backUrl }}" 
                       class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white border border-slate-200/90 text-slate-600 hover:text-slate-950 hover:bg-slate-50 hover:border-slate-300 transition-colors flex items-center justify-center shrink-0 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs active:scale-95 cursor-pointer"
                       title="Back to Queue" aria-label="Back to Queue">
                        <i class="fas fa-arrow-left text-xs sm:text-sm" aria-hidden="true"></i>
                    </a>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="font-mono text-xs font-bold tabular-nums text-slate-800 bg-slate-100 border border-slate-200/90 px-2 py-0.5 rounded-md shadow-2xs">
                                {{ $researchTitle->reoc_code ?? ('#'.str_pad($researchTitle->id, 5, '0', STR_PAD_LEFT)) }}
                            </span>
                            @php
                                $statusLower = strtolower($researchTitle->Status ?? '');
                                $statusTextColor = match(true) {
                                    str_contains($statusLower, 'disapproved') || str_contains($statusLower, 'major') => 'text-rose-700',
                                    str_contains($statusLower, 'waiting') || str_contains($statusLower, 'minor') => 'text-amber-700',
                                    str_contains($statusLower, 'reviewed') || str_contains($statusLower, 'approved') => 'text-emerald-700',
                                    str_contains($statusLower, 'under review') || str_contains($statusLower, 'reviewing') => 'text-indigo-700',
                                    str_contains($statusLower, 'reviewer assigned') => 'text-blue-700',
                                    default => 'text-slate-700'
                                };
                            @endphp
                            <span class="inline-flex items-center text-xs font-bold uppercase tracking-wider {{ $statusTextColor }}">
                                {{ $researchTitle->Status }}
                            </span>
                        </div>
                        <h1 class="font-heading font-bold text-base sm:text-lg text-slate-950 tracking-tight leading-tight mt-1 truncate" title="{{ $researchTitle->Study_Protocol_title }}">
                            {{ $researchTitle->Study_Protocol_title }}
                        </h1>
                    </div>
                </div>

                <!-- Right: Protocol Metadata & Drawer Action Buttons -->
                <div class="flex flex-col sm:flex-row lg:flex-col sm:items-center lg:items-end justify-between gap-2 shrink-0 pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                    
                    <!-- Metadata Info Row -->
                    <div class="flex items-center gap-2.5 text-xs text-slate-600 flex-wrap">
                        <div class="flex items-center gap-1.5">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#8B0000] to-[#550000] text-white flex items-center justify-center text-[11px] font-bold uppercase shrink-0 shadow-2xs">
                                {{ substr($researchTitle->researcher?->user?->first_name ?? $researchTitle->Created_by ?? 'U', 0, 1) }}
                            </div>
                            <span class="font-bold text-slate-900">
                                {{ $researchTitle->researcher?->user ? ($researchTitle->researcher->user->first_name . ' ' . $researchTitle->researcher->user->last_name) : ($researchTitle->Created_by ?? 'Unknown') }}
                            </span>
                        </div>
                        <span class="text-slate-300">·</span>
                        <span class="tabular-nums font-medium text-slate-600">
                            <i class="far fa-calendar-alt text-slate-400 mr-1" aria-hidden="true"></i>{{ $researchTitle->created_at->format('M d, Y') }}
                        </span>
                        @if(!empty($researchTitle->Review_Type) && !in_array($researchTitle->Review_Type, ['Unassigned', 'N/A']))
                            @php
                                $revTypeBadge = match($researchTitle->Review_Type) {
                                    'Exempt Review' => 'bg-emerald-50 text-emerald-800 border-emerald-200/90',
                                    'Expedited Review' => 'bg-blue-50 text-blue-800 border-blue-200/90',
                                    'Full Board Review' => 'bg-amber-50 text-amber-900 border-amber-200/90',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200/90'
                                };
                            @endphp
                            <span class="font-bold text-[11px] px-2 py-0.5 rounded-md border shadow-2xs {{ $revTypeBadge }}">
                                {{ $researchTitle->Review_Type }}
                            </span>
                        @endif
                    </div>

                    <!-- Quick Intelligence Drawer Triggers -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if($researchTitle->revisionLogs->isNotEmpty())
                            <button type="button" @click="openDrawer('history')" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl border border-slate-200/90 bg-white hover:bg-rose-50/60 hover:border-rose-200 text-slate-700 hover:text-[#8B0000] text-xs font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] h-8 cursor-pointer shadow-2xs group active:scale-95"
                                    title="View Revision Feedback History">
                                <i class="fas fa-history text-xs text-[#8B0000] group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                                <span>Revision History</span>
                                <span class="px-1.5 py-0.5 rounded-full bg-[#8B0000] text-white text-[10px] font-bold tabular-nums shadow-2xs">
                                    {{ $researchTitle->revisionLogs->count() }}
                                </span>
                            </button>
                        @endif

                        <button type="button" @click="openDrawer('details')" 
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl border border-slate-200/90 bg-white hover:bg-blue-50/60 hover:border-blue-200 text-slate-700 hover:text-blue-700 text-xs font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 h-8 cursor-pointer shadow-2xs group active:scale-95"
                                title="View Submission Details">
                            <i class="fas fa-info-circle text-xs text-blue-600 group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                            <span>Details</span>
                        </button>

                        <button type="button" @click="openDrawer('activity')" 
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl border border-slate-200/90 bg-white hover:bg-indigo-50/60 hover:border-indigo-200 text-slate-700 hover:text-indigo-700 text-xs font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 h-8 cursor-pointer shadow-2xs group active:scale-95"
                                title="View Activity Log">
                            <i class="fas fa-list-check text-xs text-indigo-600 group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                            <span>Activity Log</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Main 12-Column File Workspace Grid (Starts immediately below Header) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-stretch flex-1 min-h-0 lg:h-full" 
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
                remarksMap: {{ json_encode($remarksByFileId) }},
                currentRemark: '',
                remarkSaving: false,
                remarkSaved: false,
                remarkError: '',
                showModal: false,
                step: 1,
                init() {
                    if (this.activeFile && this.remarksMap[this.activeFile.id]) {
                        this.currentRemark = this.remarksMap[this.activeFile.id];
                    }
                    this.$watch('activeFile', (file) => {
                        this.remarkSaved = false;
                        this.remarkError = '';
                        this.currentRemark = (file && this.remarksMap[file.id]) ? this.remarksMap[file.id] : '';
                    });
                },
                getUrl(file) {
                    if (!file) return '';
                    return this.serveRoute.replace('FILE_ID', file.id);
                },
                getOfficeUrl(file) {
                    if (!file || !file.public_url) return '';
                    return 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(file.public_url);
                },
                isPdf(file) { return file && file.ext === 'pdf'; },
                isOffice(file) { return file && ['doc','docx','ppt','pptx','xls','xlsx'].includes(file.ext); },
                isImage(file) { return file && ['jpg','jpeg','png','gif','bmp','webp'].includes(file.ext); },
                isLocalHost() {
                    const host = window.location.hostname;
                    return host === 'localhost' || host === '127.0.0.1' || host.endsWith('.test') || host.endsWith('.local');
                },
                selectFile(file) { 
                    this.activeFile = file; 
                },
                async saveCurrentRemark() {
                    if (!this.activeFile) return;
                    this.remarkSaving = true;
                    this.remarkSaved = false;
                    this.remarkError = '';
                    const url = '{{ route('reviewer.save_file_remark', 'FILE_ID') }}'.replace('FILE_ID', this.activeFile.id);
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ remarks: this.currentRemark })
                        });
                        const data = await res.json();
                        if (data.success) {
                            if (this.currentRemark.trim()) {
                                this.remarksMap[this.activeFile.id] = this.currentRemark.trim();
                            } else {
                                delete this.remarksMap[this.activeFile.id];
                            }
                            this.remarkSaved = true;
                            setTimeout(() => { this.remarkSaved = false; }, 3000);
                        } else {
                            this.remarkError = data.message || 'Error saving remark.';
                        }
                    } catch (err) {
                        this.remarkError = 'Network error saving remark.';
                    } finally {
                        this.remarkSaving = false;
                    }
                }
             }">

            <!-- ===== LEFT COLUMN — Document Viewer Studio & Contextual Remarks (7 Cols) ===== -->
            <div class="lg:col-span-7 flex flex-col gap-2 min-h-0 h-full overflow-hidden">

                <!-- Viewer Header & Controls Bar -->
                <div class="bg-gradient-to-r from-white via-white to-slate-50/90 px-4 py-2.5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between gap-2.5 shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-2xs"
                             :class="activeFile ? activeFile.bg : 'bg-slate-100 text-slate-400 border border-slate-200'">
                            <i :class="activeFile ? [activeFile.icon, activeFile.color] : 'fas fa-file text-slate-400'" class="text-sm" aria-hidden="true"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-900 truncate tracking-tight"
                                      x-text="activeFile ? activeFile.label : 'No document selected'"></span>
                                <template x-if="activeFile && activeFile.revision_number">
                                    <span class="text-[10px] font-bold text-indigo-900 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-md shadow-2xs"
                                          x-text="'Revision ' + activeFile.revision_number"></span>
                                </template>
                                <template x-if="activeFile && !activeFile.revision_number && activeFile.group !== 'Letters'">
                                    <span class="text-[10px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200/90 shadow-2xs">Original</span>
                                </template>
                                <template x-if="activeFile && activeFile.group === 'Letters'">
                                    <span class="text-[10px] font-bold text-[#8B0000] bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200 shadow-2xs">Official Letter</span>
                                </template>
                            </div>
                            <p class="text-[11px] text-slate-500 truncate font-mono font-normal max-w-[240px] sm:max-w-sm mt-0.5"
                               x-text="activeFile ? activeFile.filename : ''"></p>
                        </div>
                    </div>

                    <!-- Actions: Open Tab & Download -->
                    <div class="flex items-center gap-1.5 shrink-0" x-show="activeFile">
                        <a :href="isOffice(activeFile) && !isLocalHost() ? getOfficeUrl(activeFile) : getUrl(activeFile)" target="_blank"
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200/90 text-slate-600 hover:text-blue-700 hover:bg-blue-50/60 hover:border-blue-300 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-2xs active:scale-95 cursor-pointer"
                            title="Open in new window" aria-label="Open document in new window">
                            <i class="fas fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                        </a>
                        <a :href="getUrl(activeFile) + '?download=1'" download
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200/90 text-slate-600 hover:text-[#8B0000] hover:bg-red-50/60 hover:border-red-300 transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs active:scale-95 cursor-pointer"
                            title="Download document" aria-label="Download document">
                            <i class="fas fa-download text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <!-- Document Frame Container (Calm, High-Contrast Canvas Surface) -->
                <div id="document-preview-container" class="bg-slate-900/[0.02] rounded-2xl overflow-hidden border border-slate-200/90 shadow-2xs relative flex-1 min-h-0 h-[360px] lg:h-full">
                    
                    <!-- PDF Viewer -->
                    <template x-if="activeFile && isPdf(activeFile)">
                        <iframe :src="getUrl(activeFile)" class="w-full h-full border-0 bg-white"
                            title="PDF Document Viewer"></iframe>
                    </template>

                    <!-- Office Documents Viewer -->
                    <template x-if="activeFile && isOffice(activeFile)">
                        <div class="w-full h-full">
                            <!-- Local Development Office Card -->
                            <template x-if="isLocalHost()">
                                <div class="w-full h-full flex items-center justify-center p-6 bg-slate-900 text-slate-100">
                                    <div class="max-w-sm w-full bg-slate-900/95 rounded-2xl border border-slate-800 p-6 text-center shadow-xl">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center mx-auto mb-3 text-blue-400 shadow-xs">
                                            <i class="fas fa-file-word text-xl" aria-hidden="true"></i>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-2 uppercase tracking-wider">
                                            Office Document
                                        </span>
                                        <h3 class="text-xs font-bold text-white mb-1 break-all" x-text="activeFile.filename"></h3>
                                        <p class="text-[11px] text-slate-400 mb-4" x-text="activeFile.label + ' • ' + activeFile.uploaded_at"></p>
                                        <div class="flex items-center justify-center gap-2">
                                            <a :href="getUrl(activeFile) + '?download=1'" download
                                               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#8B0000] hover:bg-[#6b0000] text-white text-xs font-bold rounded-xl shadow-xs transition-all active:scale-95 cursor-pointer">
                                                <i class="fas fa-download text-xs" aria-hidden="true"></i>
                                                <span>Download Document</span>
                                            </a>
                                            <a :href="getUrl(activeFile)" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-xl border border-slate-700 shadow-xs transition-all active:scale-95 cursor-pointer">
                                                <i class="fas fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                                                <span>Open in New Tab</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <!-- Production Cloud Office Viewer -->
                            <template x-if="!isLocalHost()">
                                <iframe :src="getOfficeUrl(activeFile)" class="w-full h-full border-0 bg-white"
                                    title="Office Document Viewer"></iframe>
                            </template>
                        </div>
                    </template>

                    <!-- Image Viewer -->
                    <template x-if="activeFile && isImage(activeFile)">
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-50/90 p-4 overflow-auto">
                            <img :src="getUrl(activeFile)" :alt="activeFile.filename"
                                class="max-w-full max-h-full object-contain rounded-lg shadow-sm border border-slate-300" />
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template x-if="!activeFile">
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-50/80">
                            <div class="text-center p-6 max-w-sm">
                                <div class="w-10 h-10 bg-white rounded-xl border border-slate-300 flex items-center justify-center mx-auto mb-2 text-slate-500 shadow-2xs">
                                    <i class="fas fa-file-contract text-lg" aria-hidden="true"></i>
                                </div>
                                <h3 class="text-xs font-bold text-slate-900 mb-0.5">No Document Selected</h3>
                                <p class="text-[11px] text-slate-600 font-medium">Choose a document category from the panel on the right to preview.</p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Per-Document Reviewer Remarks Card (Compact, Quiet, and Contextual) -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-3 shrink-0" x-show="activeFile">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-comment-dots text-[11px]" aria-hidden="true"></i>
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 truncate">
                                Reviewer Remarks for <span class="text-[#8B0000]" x-text="activeFile ? activeFile.label : ''"></span>
                            </h4>
                            <span x-show="remarksMap[activeFile ? activeFile.id : '']" 
                                  class="w-2 h-2 rounded-full bg-amber-500 shrink-0" 
                                  title="Remark saved for this file"></span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span x-show="remarkSaved" x-transition 
                                  class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 rounded-md flex items-center gap-1">
                                <i class="fas fa-check text-[9px]"></i> Saved
                            </span>
                            <span x-show="remarkError" x-transition class="text-[10px] font-bold text-rose-600" x-text="remarkError"></span>
                            <button type="button" @click="saveCurrentRemark()" :disabled="remarkSaving"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-[#8B0000] hover:bg-[#6b0000] text-white text-xs font-bold transition-all shadow-2xs cursor-pointer disabled:opacity-60 active:scale-95">
                                <i class="fas" :class="remarkSaving ? 'fa-spinner fa-spin' : 'fa-floppy-disk'" aria-hidden="true"></i>
                                <span x-text="remarkSaving ? 'Saving...' : 'Save Note'"></span>
                            </button>
                        </div>
                    </div>
                    <textarea x-model="currentRemark" rows="2" maxlength="2000"
                              placeholder="Add specific comments, required revisions, or observations for this document..."
                              class="w-full px-3 py-2 text-xs text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8B0000] transition-colors resize-none"></textarea>
                    <div class="flex justify-between items-center text-[10px] text-slate-400 mt-1 px-1">
                        <span>Notes automatically attach to your review ledger.</span>
                        <span x-text="(currentRemark ? currentRemark.length : 0) + ' / 2000 chars'"></span>
                    </div>
                </div>

            </div>

            <!-- ===== RIGHT COLUMN — Document Ledger, Evaluation Actions & Submission (5 Cols) ===== -->
            <div class="lg:col-span-5 flex flex-col gap-2 min-h-0 h-full overflow-hidden">

                <!-- Unified Review Action & Evaluation Documents Studio Card -->
                <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden shrink-0"
                     x-data="{ uploadAccordionOpen: false, myUploadsOpen: false }">
                    
                    <!-- Main Action & Status Bar -->
                    <div class="p-2.5 sm:p-3 flex items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            @if($researchTitle->Status === 'Reviewed')
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center shrink-0">
                                    <i class="fas fa-check-circle text-sm" aria-hidden="true"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 leading-tight">Review Submitted</h4>
                                    <p class="text-[11px] text-slate-500 truncate">Your formal review has been logged.</p>
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-xl bg-[#8B0000]/10 text-[#8B0000] border border-[#8B0000]/20 flex items-center justify-center shrink-0">
                                    <i class="fas fa-gavel text-sm" aria-hidden="true"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <h4 class="text-xs font-bold text-slate-900 leading-tight">
                                            {{ $isReEvaluation ? 'Re-Evaluation in Progress' : 'Review in Progress' }}
                                        </h4>
                                        @if($reviewerUploads->isNotEmpty())
                                            <span class="bg-[#8B0000] text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full tabular-nums">
                                                {{ $reviewerUploads->count() }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <button type="button" @click="myUploadsOpen = !myUploadsOpen" 
                                                class="text-[11px] text-slate-500 hover:text-slate-800 flex items-center gap-1 transition-colors cursor-pointer group">
                                            <i class="fas fa-file-shield text-[10px] text-slate-400 group-hover:text-[#8B0000] transition-colors"></i>
                                            <span>{{ $reviewerUploads->count() }} evaluation file(s)</span>
                                            @if($reviewerUploads->isNotEmpty())
                                                <i class="fas fa-chevron-down text-[8px] text-slate-400 transition-transform duration-200"
                                                   :class="myUploadsOpen ? 'rotate-180 text-[#8B0000]' : ''"></i>
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons Group -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            @if($researchTitle->Status !== 'Reviewed')
                                <button type="button" @click="uploadAccordionOpen = !uploadAccordionOpen"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#8B0000] hover:border-slate-300 text-xs font-semibold transition-all shadow-2xs cursor-pointer active:scale-98">
                                    <i class="fas fa-cloud-arrow-up text-xs text-slate-500"></i>
                                    <span x-text="uploadAccordionOpen ? 'Close Upload' : 'Upload File'"></span>
                                </button>

                                <button type="button" @click="showModal = true"
                                        style="background-color: #047857; color: #ffffff;"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#047857] hover:bg-[#065f46] text-white text-xs font-bold transition-all shadow-xs cursor-pointer active:scale-95">
                                    <i class="fas fa-clipboard-check text-xs text-white" aria-hidden="true"></i>
                                    <span class="text-white font-bold">Complete Review</span>
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-emerald-700">
                                    <i class="fas fa-lock text-[10px]"></i> Finalized
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Form Dropdown -->
                    @if($researchTitle->Status !== 'Reviewed')
                        <div x-show="uploadAccordionOpen" style="display: none;" x-transition class="p-3 border-t border-slate-100 bg-slate-50/50">
                            <form action="{{ route('reviewer.upload', $researchTitle->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2.5">
                                @csrf
                                <input type="hidden" name="category" :value="activeFile ? activeFile.label : 'Technical Evaluation'">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                                            Select Evaluation Document
                                        </label>
                                        <span class="text-[10px] text-slate-400 font-medium">PDF, DOC, DOCX • Max 20MB</span>
                                    </div>
                                    <input type="file" name="files[]" required multiple accept=".pdf,.doc,.docx"
                                        class="w-full text-xs text-slate-600 file:mr-2.5 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-700 transition-colors bg-white border border-slate-200 rounded-xl cursor-pointer">
                                </div>
                                <button type="submit"
                                    class="w-full py-2 px-3 bg-[#8B0000] hover:bg-[#6b0000] text-white text-xs font-bold rounded-xl shadow-2xs transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-98">
                                    <i class="fas fa-upload text-xs" aria-hidden="true"></i>
                                    <span>Upload Document</span>
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Uploaded Files List -->
                    <div x-show="myUploadsOpen || uploadAccordionOpen" style="display: none;" x-transition class="max-h-[160px] overflow-y-auto no-scrollbar divide-y divide-slate-100 bg-white border-t border-slate-100">
                        <div class="px-3 py-1.5 bg-slate-50/80 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Uploaded Evaluation Documents</span>
                            <span class="text-[10px] text-slate-400 tabular-nums">{{ $reviewerUploads->count() }} file(s)</span>
                        </div>
                        @forelse($reviewerUploads as $upload)
                            @php
                                $uExt = strtolower(pathinfo($upload->filename, PATHINFO_EXTENSION));
                                $uIcon = match($uExt) {
                                    'pdf' => 'fas fa-file-pdf text-rose-700',
                                    'doc', 'docx' => 'fas fa-file-word text-blue-700',
                                    default => 'fas fa-file text-slate-500',
                                };
                            @endphp
                            <div class="flex items-center justify-between px-3 py-2 text-xs">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <i class="{{ $uIcon }} text-xs shrink-0" aria-hidden="true"></i>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-slate-800 truncate" title="{{ $upload->filename }}">{{ $upload->filename }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $upload->created_at->format('M d, Y • h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a href="{{ route('reviewer.serve_file', $upload->id) }}" target="_blank"
                                       class="w-7 h-7 rounded-lg border border-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center cursor-pointer shadow-2xs"
                                       title="View file">
                                        <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                    @if($researchTitle->Status !== 'Reviewed')
                                        <form action="{{ route('reviewer.file.delete', $upload->id) }}" method="POST" class="m-0 p-0 inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="Swal.fire({ title: 'Delete Evaluation Document?', text: 'Are you sure you want to remove this evaluation?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Yes, delete it!' }).then((res) => { if(res.isConfirmed) this.closest('form').submit(); });"
                                                    class="w-7 h-7 rounded-lg border border-slate-200 text-rose-500 hover:bg-rose-50 hover:text-rose-700 flex items-center justify-center cursor-pointer shadow-2xs"
                                                    title="Delete evaluation file">
                                                <i class="fas fa-trash-can text-[10px]"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center text-slate-400 text-xs italic">
                                No evaluation documents uploaded yet.
                            </div>
                        @endforelse
                    </div>

                </div>

                <!-- Document Selector with Tactile Segmented Tabs (Matching Admin Studio) -->
                <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden flex flex-col flex-1 min-h-0">
                    
                    <!-- Segmented Control Tab Bar with Interactive Revision Selector -->
                    <div class="p-1.5 border-b border-slate-200/80 bg-slate-50/50 shrink-0 relative" x-data="{ revDropdownOpen: false }">
                        <div id="document-tab-bar" class="flex gap-1 overflow-x-auto no-scrollbar p-0.5 bg-slate-100/80 rounded-xl border border-slate-200/80 cursor-grab select-none">
                            @if($letters->isNotEmpty())
                                <button type="button" @click="activeTab = 'letters'; if (letters.length > 0 && letters[0].files.length > 0 && (!activeFile || activeFile.group !== 'Letters')) { selectFile(letters[0].files[0]); }"
                                    :class="activeTab === 'letters' ? 'bg-white text-rose-950 shadow-2xs border border-rose-200 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-medium'"
                                    class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0">
                                    <i class="fas fa-stamp text-xs" :class="activeTab === 'letters' ? 'text-[#8B0000]' : 'text-slate-400'"></i>
                                    <span>Letters</span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded-full tabular-nums"
                                          :class="activeTab === 'letters' ? 'bg-[#8B0000] text-white font-bold' : 'bg-slate-200/70 text-slate-600 font-medium'">
                                        {{ $letters->count() }}
                                    </span>
                                </button>
                            @endif

                            <button type="button" @click="activeTab = 'original'; if (originalFiles.length > 0 && originalFiles[0].files.length > 0 && (!activeFile || activeFile.group !== 'Original')) { selectFile(originalFiles[0].files[0]); }"
                                :class="activeTab === 'original' ? 'bg-white text-blue-950 shadow-2xs border border-blue-200 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-medium'"
                                class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0">
                                <i class="fas fa-file-contract text-xs" :class="activeTab === 'original' ? 'text-blue-700' : 'text-slate-400'"></i>
                                <span>Original</span>
                                <span class="text-[10px] px-1.5 py-0.2 rounded-full tabular-nums"
                                      :class="activeTab === 'original' ? 'bg-blue-700 text-white font-bold' : 'bg-slate-200/70 text-slate-600 font-medium'">
                                    {{ $originalFiles->count() }}
                                </span>
                            </button>

                            @if($hasRevisions && $revisionFolders->isNotEmpty())
                                @if($revisionFolders->count() === 1)
                                    @php $singleRevNum = $revisionFolders->keys()->first(); $singleRevFiles = $revisionFolders->first(); @endphp
                                    <button type="button" @click="activeTab = 'rev_{{ $singleRevNum }}'; if (revisions['{{ $singleRevNum }}'] && revisions['{{ $singleRevNum }}'].length > 0 && revisions['{{ $singleRevNum }}'][0].files.length > 0) { selectFile(revisions['{{ $singleRevNum }}'][0].files[0]); }"
                                        :class="activeTab === 'rev_{{ $singleRevNum }}' ? 'bg-white text-indigo-950 shadow-2xs border border-indigo-200 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-medium'"
                                        class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0">
                                        <i class="fas fa-code-branch text-xs" :class="activeTab === 'rev_{{ $singleRevNum }}' ? 'text-indigo-600' : 'text-slate-400'"></i>
                                        <span>Rev {{ $singleRevNum }}</span>
                                        <span class="text-[10px] px-1.5 py-0.2 rounded-full tabular-nums"
                                              :class="activeTab === 'rev_{{ $singleRevNum }}' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-200/70 text-slate-600 font-medium'">
                                            {{ $singleRevFiles->count() }}
                                        </span>
                                    </button>
                                @else
                                    <button type="button" 
                                        @click="revDropdownOpen = !revDropdownOpen"
                                        :class="activeTab.startsWith('rev_') ? 'bg-white text-indigo-950 shadow-2xs border border-indigo-200 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-medium'"
                                        class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0"
                                        aria-haspopup="true"
                                        :aria-expanded="revDropdownOpen ? 'true' : 'false'"
                                        title="Select revision cycle">
                                        <i class="fas fa-code-branch text-xs" :class="activeTab.startsWith('rev_') ? 'text-indigo-600' : 'text-slate-400'"></i>
                                        <span x-text="activeTab.startsWith('rev_') ? ('Rev ' + activeTab.replace('rev_', '')) : 'Revisions'"></span>
                                        <span class="text-[10px] px-1.5 py-0.2 rounded-full tabular-nums"
                                              :class="activeTab.startsWith('rev_') ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-200/70 text-slate-600 font-medium'"
                                              x-text="activeTab.startsWith('rev_') ? (revisions[activeTab.replace('rev_', '')] ? revisions[activeTab.replace('rev_', '')].reduce((acc, g) => acc + g.files.length, 0) : '') : '{{ $revisionFolders->count() }}'">
                                        </span>
                                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" 
                                           :class="revDropdownOpen ? 'rotate-180 text-indigo-600' : (activeTab.startsWith('rev_') ? 'text-slate-600' : 'text-slate-400')" aria-hidden="true"></i>
                                    </button>
                                @endif
                            @endif

                            @if($hasRevisions && $activeFiles->isNotEmpty())
                                <button type="button" @click="activeTab = 'current'; if (activeFiles.length > 0 && activeFiles[0].files.length > 0 && (!activeFile || activeFile.group !== 'Current')) { selectFile(activeFiles[0].files[0]); }"
                                    :class="activeTab === 'current' ? 'bg-white text-amber-950 shadow-2xs border border-amber-200 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-medium'"
                                    class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0">
                                    <i class="fas fa-file-signature text-xs" :class="activeTab === 'current' ? 'text-amber-700' : 'text-slate-400'"></i>
                                    <span>Current</span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded-full tabular-nums"
                                          :class="activeTab === 'current' ? 'bg-amber-600 text-white font-bold' : 'bg-slate-200/70 text-slate-600 font-medium'">
                                        {{ $activeFiles->count() }}
                                    </span>
                                </button>
                            @endif
                        </div>

                        {{-- Revision Selection Dropdown Menu --}}
                        @if($hasRevisions && $revisionFolders->count() > 1)
                            <div x-show="revDropdownOpen" 
                                 x-cloak
                                 @click.outside="revDropdownOpen = false"
                                 @keydown.escape.window="revDropdownOpen = false"
                                 x-transition:enter="transition ease-out duration-150 transform"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100 transform"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                 class="absolute right-3 top-full mt-1.5 w-64 bg-white rounded-2xl shadow-lg border border-slate-200/90 py-1.5 z-50 overflow-hidden"
                                 role="menu"
                                 aria-label="Revision cycles">
                                <div class="px-3.5 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                                    <span class="flex items-center gap-1.5 text-indigo-950 font-semibold">
                                        <i class="fas fa-code-branch text-indigo-600 text-xs" aria-hidden="true"></i>
                                        <span>Select Revision Round</span>
                                    </span>
                                    <span class="text-slate-600 font-bold tabular-nums bg-slate-100 px-1.5 py-0.5 rounded text-[10px]">{{ $revisionFolders->count() }} rounds</span>
                                </div>
                                <div class="max-h-64 overflow-y-auto no-scrollbar divide-y divide-slate-100">
                                    @foreach($revisionFolders->sortKeys() as $revNum => $revFiles)
                                        @php
                                            $revDate = $revFiles->first()?->created_at?->format('M d, Y') ?? '';
                                            $isLatest = $loop->last;
                                        @endphp
                                        <button type="button" 
                                                @click="activeTab = 'rev_{{ $revNum }}'; revDropdownOpen = false; if (revisions['{{ $revNum }}'] && revisions['{{ $revNum }}'].length > 0 && revisions['{{ $revNum }}'][0].files.length > 0) { selectFile(revisions['{{ $revNum }}'][0].files[0]); }"
                                                :class="activeTab === 'rev_{{ $revNum }}' ? 'bg-indigo-50/70 text-indigo-950 font-semibold' : 'text-slate-700 hover:bg-slate-50 font-normal'"
                                                class="w-full flex items-center justify-between px-3.5 py-2.5 text-xs transition-colors text-left cursor-pointer group"
                                                role="menuitem">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-bold shrink-0 border shadow-2xs transition-colors"
                                                     :class="activeTab === 'rev_{{ $revNum }}' ? 'bg-indigo-600 text-white border-indigo-700' : 'bg-slate-100 text-slate-700 border-slate-200 group-hover:bg-slate-200'">
                                                    {{ $revNum }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="truncate">Revision {{ $revNum }}</span>
                                                        @if($isLatest)
                                                            <span class="text-[9px] font-semibold px-1.5 py-0.2 rounded bg-emerald-50 text-emerald-800 uppercase tracking-wider shrink-0 border border-emerald-200">Latest</span>
                                                        @endif
                                                    </div>
                                                    @if($revDate)
                                                        <p class="text-[10px] text-slate-400 tabular-nums truncate mt-0.5">{{ $revDate }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                                <span class="text-[10px] font-medium px-2 py-0.5 rounded-full tabular-nums"
                                                      :class="activeTab === 'rev_{{ $revNum }}' ? 'bg-indigo-100 text-indigo-900' : 'bg-slate-100 text-slate-500'">
                                                    {{ $revFiles->count() }} file(s)
                                                </span>
                                                <i x-show="activeTab === 'rev_{{ $revNum }}'" class="fas fa-check text-xs text-indigo-600 ml-1" aria-hidden="true"></i>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Scrollable Category & File Accordion List Area -->
                    <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar p-2">

                        <!-- Letters Tab List -->
                        <div x-show="activeTab === 'letters'" style="display:none;">
                            <template x-for="group in letters" :key="group.category">
                                <div class="mb-1.5 border border-slate-200/80 rounded-xl overflow-hidden bg-white shadow-2xs transition-colors"
                                     x-data="{ expanded: false }">
                                    <button type="button" @click="expanded = !expanded"
                                        :class="expanded ? 'bg-slate-50/80' : 'bg-white'"
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 hover:bg-slate-50 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                        <div class="flex items-center gap-2 min-w-0 pr-2">
                                            <i class="fas fa-folder text-amber-500/90 text-xs shrink-0" aria-hidden="true"></i>
                                            <span class="text-xs font-bold text-slate-900 tracking-tight truncate" x-text="group.category"></span>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span class="text-[10px] font-semibold text-slate-500 bg-slate-100/90 border border-slate-200/70 px-2 py-0.5 rounded-full tabular-nums"
                                                  x-text="group.files.length + ' file' + (group.files.length === 1 ? '' : 's')"></span>
                                            <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                                               :class="expanded ? 'rotate-180 text-slate-600' : ''" aria-hidden="true"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <div class="divide-y divide-slate-100 border-t border-slate-100 p-1 bg-slate-50/30">
                                            <template x-for="file in group.files" :key="file.id">
                                                <button type="button" @click="selectFile(file)"
                                                    :class="activeFile && activeFile.id === file.id 
                                                        ? 'bg-gradient-to-r from-red-50/90 via-rose-50/40 to-white border border-red-200/90 shadow-xs' 
                                                        : 'hover:bg-slate-50 border border-transparent'"
                                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-left transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer rounded-xl my-0.5">
                                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 border transition-all"
                                                         :class="activeFile && activeFile.id === file.id ? 'bg-white border-red-200/80 shadow-2xs ring-2 ring-red-100' : 'bg-white border-slate-200/80 shadow-2xs'">
                                                        <i :class="[file.icon, file.color]" class="text-xs" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center justify-between gap-1.5">
                                                            <p class="text-xs truncate" :class="activeFile && activeFile.id === file.id ? 'font-bold text-[#8B0000]' : 'font-semibold text-slate-900'" x-text="file.filename"></p>
                                                            <span x-show="remarksMap && remarksMap[file.id]" class="w-2 h-2 rounded-full bg-amber-500 shrink-0" title="Has Reviewer Note"></span>
                                                        </div>
                                                        <p class="text-[10px] tabular-nums text-slate-400 font-medium" x-text="file.uploaded_at"></p>
                                                    </div>
                                                    <span x-show="activeFile && activeFile.id === file.id"
                                                          class="flex items-center gap-1.5 text-[10px] font-bold text-white bg-gradient-to-r from-[#8B0000] to-[#700000] px-2.5 py-0.5 rounded-full shadow-2xs shrink-0">
                                                        <i class="fas fa-eye text-[9px]" aria-hidden="true"></i>
                                                        <span>Viewing</span>
                                                    </span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Original Documents Tab List (Single Application Form Accordion open by default) -->
                        <div x-show="activeTab === 'original'" style="display:none;">
                            <template x-if="originalFiles.length === 0">
                                <div class="p-8 text-center text-slate-400">
                                    <i class="fas fa-box-open text-3xl mb-2 block opacity-40" aria-hidden="true"></i>
                                    <p class="text-xs font-medium text-slate-600">No original documents found.</p>
                                </div>
                            </template>
                            <template x-for="group in originalFiles" :key="group.category">
                                <div class="mb-1.5 border border-slate-200/80 rounded-xl overflow-hidden bg-white shadow-2xs transition-colors"
                                     x-data="{ expanded: Boolean(group.category && group.category.toLowerCase().includes('application form')) }">
                                    <button type="button" @click="expanded = !expanded"
                                        :class="expanded ? 'bg-slate-50/80' : 'bg-white'"
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 hover:bg-slate-50 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                        <div class="flex items-center gap-2 min-w-0 pr-2">
                                            <i class="fas fa-folder text-amber-500/90 text-xs shrink-0" aria-hidden="true"></i>
                                            <span class="text-xs font-bold text-slate-900 tracking-tight truncate" x-text="group.category"></span>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span x-show="group.files && group.files.some(f => remarksMap && remarksMap[f.id])" class="w-2 h-2 rounded-full bg-amber-500 shrink-0" title="Contains remarks"></span>
                                            <span class="text-[10px] font-semibold text-slate-500 bg-slate-100/90 border border-slate-200/70 px-2 py-0.5 rounded-full tabular-nums"
                                                  x-text="group.files.length + ' file' + (group.files.length === 1 ? '' : 's')"></span>
                                            <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                                               :class="expanded ? 'rotate-180 text-slate-600' : ''" aria-hidden="true"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <div class="divide-y divide-slate-100 border-t border-slate-100 p-1 bg-slate-50/30">
                                            <template x-for="file in group.files" :key="file.id">
                                                <button type="button" @click="selectFile(file)"
                                                    :class="activeFile && activeFile.id === file.id 
                                                        ? 'bg-gradient-to-r from-red-50/90 via-rose-50/40 to-white border border-red-200/90 shadow-xs' 
                                                        : 'hover:bg-slate-50 border border-transparent'"
                                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-left transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer rounded-xl my-0.5">
                                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 border transition-all"
                                                         :class="activeFile && activeFile.id === file.id ? 'bg-white border-red-200/80 shadow-2xs ring-2 ring-red-100' : 'bg-white border-slate-200/80 shadow-2xs'">
                                                        <i :class="[file.icon, file.color]" class="text-xs" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center justify-between gap-1.5">
                                                            <p class="text-xs truncate" :class="activeFile && activeFile.id === file.id ? 'font-bold text-[#8B0000]' : 'font-semibold text-slate-900'" x-text="file.filename"></p>
                                                            <span x-show="remarksMap && remarksMap[file.id]" class="w-2 h-2 rounded-full bg-amber-500 shrink-0" title="Has Reviewer Note"></span>
                                                        </div>
                                                        <p class="text-[10px] tabular-nums text-slate-400 font-medium" x-text="file.uploaded_at"></p>
                                                    </div>
                                                    <span x-show="activeFile && activeFile.id === file.id"
                                                          class="flex items-center gap-1.5 text-[10px] font-bold text-white bg-gradient-to-r from-[#8B0000] to-[#700000] px-2.5 py-0.5 rounded-full shadow-2xs shrink-0">
                                                        <i class="fas fa-eye text-[9px]" aria-hidden="true"></i>
                                                        <span>Viewing</span>
                                                    </span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Revision Tabs (One per revision round) -->
                        @foreach($revisionFolders->sortKeys() as $revNum => $_)
                            <div x-show="activeTab === 'rev_{{ $revNum }}'" style="display:none;">
                                <div class="px-3 py-1.5 bg-indigo-50/60 border border-indigo-200/80 rounded-xl mb-2 flex items-center justify-between">
                                    <p class="text-xs font-semibold text-indigo-950">Revision {{ $revNum }} Documents</p>
                                </div>
                                <template x-for="group in revisions['{{ $revNum }}']" :key="group.category">
                                    <div class="mb-1.5 border border-slate-200/80 rounded-xl overflow-hidden bg-white shadow-2xs transition-colors"
                                         x-data="{ expanded: Boolean(group.category && group.category.toLowerCase().includes('application form')) }">
                                        <button type="button" @click="expanded = !expanded"
                                            :class="expanded ? 'bg-slate-50/80' : 'bg-white'"
                                            class="w-full flex items-center justify-between px-3.5 py-2.5 hover:bg-slate-50 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                            <div class="flex items-center gap-2 min-w-0 pr-2">
                                                <i class="fas fa-folder text-amber-500/90 text-xs shrink-0" aria-hidden="true"></i>
                                                <span class="text-xs font-bold text-slate-900 tracking-tight truncate" x-text="group.category"></span>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <span x-show="group.files && group.files.some(f => remarksMap && remarksMap[f.id])" class="w-2 h-2 rounded-full bg-amber-500 shrink-0" title="Contains remarks"></span>
                                                <span class="text-[10px] font-semibold text-slate-500 bg-slate-100/90 border border-slate-200/70 px-2 py-0.5 rounded-full tabular-nums"
                                                      x-text="group.files.length + ' file' + (group.files.length === 1 ? '' : 's')"></span>
                                                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                                                   :class="expanded ? 'rotate-180 text-slate-600' : ''" aria-hidden="true"></i>
                                            </div>
                                        </button>
                                        <div x-show="expanded" style="display: none;" x-transition>
                                            <div class="divide-y divide-slate-100 border-t border-slate-100 p-1 bg-slate-50/30">
                                                <template x-for="file in group.files" :key="file.id">
                                                    <button type="button" @click="selectFile(file)"
                                                        :class="activeFile && activeFile.id === file.id 
                                                            ? 'bg-gradient-to-r from-red-50/90 via-rose-50/40 to-white border border-red-200/90 shadow-xs' 
                                                            : 'hover:bg-slate-50 border border-transparent'"
                                                        class="w-full flex items-center gap-2.5 px-3 py-2 text-left transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer rounded-xl my-0.5">
                                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 border transition-all"
                                                             :class="activeFile && activeFile.id === file.id ? 'bg-white border-red-200/80 shadow-2xs ring-2 ring-red-100' : 'bg-white border-slate-200/80 shadow-2xs'">
                                                            <i :class="[file.icon, file.color]" class="text-xs" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex items-center justify-between gap-1.5">
                                                                <p class="text-xs truncate" :class="activeFile && activeFile.id === file.id ? 'font-bold text-[#8B0000]' : 'font-semibold text-slate-900'" x-text="file.filename"></p>
                                                                <span x-show="remarksMap && remarksMap[file.id]" class="w-2 h-2 rounded-full bg-amber-500 shrink-0" title="Has Reviewer Note"></span>
                                                            </div>
                                                            <p class="text-[10px] tabular-nums text-slate-400 font-medium" x-text="file.uploaded_at"></p>
                                                        </div>
                                                        <span x-show="activeFile && activeFile.id === file.id"
                                                              class="flex items-center gap-1.5 text-[10px] font-bold text-white bg-gradient-to-r from-[#8B0000] to-[#700000] px-2.5 py-0.5 rounded-full shadow-2xs shrink-0">
                                                            <i class="fas fa-eye text-[9px]" aria-hidden="true"></i>
                                                            <span>Viewing</span>
                                                        </span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @endforeach

                        <!-- Current (Highest Revision) Documents Tab List -->
                        <div x-show="activeTab === 'current'" style="display:none;">
                            <template x-if="activeFiles.length === 0">
                                <div class="p-8 text-center text-slate-400">
                                    <i class="fas fa-file-signature text-3xl mb-2 block opacity-40" aria-hidden="true"></i>
                                    <p class="text-xs font-medium text-slate-600">No active files found.</p>
                                </div>
                            </template>
                            <template x-for="group in activeFiles" :key="group.category">
                                <div class="mb-1.5 border border-slate-200/80 rounded-xl overflow-hidden bg-white shadow-2xs transition-colors"
                                     x-data="{ expanded: Boolean(group.category && group.category.toLowerCase().includes('application form')) }">
                                    <button type="button" @click="expanded = !expanded"
                                        :class="expanded ? 'bg-slate-50/80' : 'bg-white'"
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 hover:bg-slate-50 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                        <div class="flex items-center gap-2 min-w-0 pr-2">
                                            <i class="fas fa-folder text-amber-500/90 text-xs shrink-0" aria-hidden="true"></i>
                                            <span class="text-xs font-bold text-slate-900 tracking-tight truncate" x-text="group.category"></span>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span x-show="group.files && group.files.some(f => remarksMap && remarksMap[f.id])" class="w-2 h-2 rounded-full bg-amber-500 shrink-0" title="Contains remarks"></span>
                                            <span class="text-[10px] font-semibold text-slate-500 bg-slate-100/90 border border-slate-200/70 px-2 py-0.5 rounded-full tabular-nums"
                                                  x-text="group.files.length + ' file' + (group.files.length === 1 ? '' : 's')"></span>
                                            <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                                               :class="expanded ? 'rotate-180 text-slate-600' : ''" aria-hidden="true"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <div class="divide-y divide-slate-100 border-t border-slate-100 p-1 bg-slate-50/30">
                                            <template x-for="file in group.files" :key="file.id">
                                                <button type="button" @click="selectFile(file)"
                                                    :class="activeFile && activeFile.id === file.id 
                                                        ? 'bg-gradient-to-r from-red-50/90 via-rose-50/40 to-white border border-red-200/90 shadow-xs' 
                                                        : 'hover:bg-slate-50 border border-transparent'"
                                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-left transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer rounded-xl my-0.5">
                                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 border transition-all"
                                                         :class="activeFile && activeFile.id === file.id ? 'bg-white border-red-200/80 shadow-2xs ring-2 ring-red-100' : 'bg-white border-slate-200/80 shadow-2xs'">
                                                        <i :class="[file.icon, file.color]" class="text-xs" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center justify-between gap-1.5">
                                                            <p class="text-xs truncate" :class="activeFile && activeFile.id === file.id ? 'font-bold text-[#8B0000]' : 'font-semibold text-slate-900'" x-text="file.filename"></p>
                                                            <span x-show="remarksMap && remarksMap[file.id]" class="w-2 h-2 rounded-full bg-amber-500 shrink-0" title="Has Reviewer Note"></span>
                                                        </div>
                                                        <p class="text-[10px] tabular-nums text-slate-400 font-medium" x-text="file.uploaded_at"></p>
                                                    </div>
                                                    <span x-show="activeFile && activeFile.id === file.id"
                                                          class="flex items-center gap-1.5 text-[10px] font-bold text-white bg-gradient-to-r from-[#8B0000] to-[#700000] px-2.5 py-0.5 rounded-full shadow-2xs shrink-0">
                                                        <i class="fas fa-eye text-[9px]" aria-hidden="true"></i>
                                                        <span>Viewing</span>
                                                    </span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Complete Review Decision Modal (Two-Step or Direct Evaluation) -->
            <template x-teleport="body">
                <div x-show="showModal" style="display: none;"
                     class="fixed inset-0 z-[100] overflow-y-auto"
                     @keydown.escape.window="showModal = false"
                     aria-labelledby="decision-modal-title" role="dialog" aria-modal="true">
                    
                    <!-- Backdrop -->
                    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                        <div x-show="showModal" x-transition.opacity
                             class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity"
                             @click="showModal = false" aria-hidden="true"></div>
                        
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        
                        <!-- Modal Content Box -->
                        <div x-show="showModal"
                             x-transition:enter="ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200">
                            
                            <!-- Modal Header -->
                            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center shadow-2xs shrink-0">
                                        <i class="fas fa-gavel text-sm" aria-hidden="true"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900 font-heading" id="decision-modal-title">
                                            {{ $isReEvaluation ? 'Submit Re-Evaluation Decision' : 'Submit Review Decision' }}
                                        </h3>
                                        <p class="text-[11px] text-slate-500">Record your official review recommendation for this protocol</p>
                                    </div>
                                </div>
                                <button type="button" @click="showModal = false"
                                        class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                                        aria-label="Close modal">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>

                            <!-- Modal Form -->
                            <form action="{{ route('reviewer.complete_review', $researchTitle->id) }}" method="POST" class="p-6 space-y-4">
                                @csrf

                                @if($reviewerUploads->isEmpty())
                                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-start gap-2.5">
                                        <i class="fas fa-exclamation-triangle text-amber-600 text-sm mt-0.5 shrink-0"></i>
                                        <div>
                                            <p class="font-bold">Evaluation Document Required</p>
                                            <p class="mt-0.5 text-[11px]">You must upload at least one evaluation document (PDF or DOCX) in the right panel before submitting your final decision.</p>
                                        </div>
                                    </div>
                                @endif

                                @if($isReEvaluation)
                                    <!-- Re-Evaluation Decision Selector -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                            Formal Review Decision <span class="text-rose-500">*</span>
                                        </label>
                                        <select name="review_decision" required
                                                class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                            <option value="" disabled selected>Select review decision...</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Minor revision/s required">Minor revision/s required</option>
                                            <option value="Major revision/s required">Major revision/s required</option>
                                            <option value="Disapproved">Disapproved</option>
                                        </select>
                                    </div>

                                    <!-- Overarching Deliberations (Optional / Accordion) -->
                                    <div class="space-y-3 pt-1" x-data="{ delibOpen: false }">
                                        <button type="button" @click="delibOpen = !delibOpen"
                                                class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 cursor-pointer">
                                            <i class="fas fa-clipboard-list"></i>
                                            <span x-text="delibOpen ? 'Hide Deliberation Assessment Fields' : 'Add Deliberation Assessment Details (Optional)'"></span>
                                            <i class="fas fa-chevron-down text-[10px] transition-transform" :class="delibOpen ? 'rotate-180' : ''"></i>
                                        </button>
                                        <div x-show="delibOpen" style="display: none;" x-transition class="space-y-3 p-3 bg-slate-50/70 border border-slate-200/80 rounded-xl">
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Scientific Soundness</label>
                                                <textarea name="scientific_soundness" rows="2" placeholder="Assess methodology, objectives, and feasibility..."
                                                          class="w-full p-2 text-xs bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Ethical Issues & Risk Mitigation</label>
                                                <textarea name="ethical_issues" rows="2" placeholder="Assess human subjects protection, risks, and confidentiality..."
                                                          class="w-full p-2 text-xs bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-600 mb-1">ICF / Informed Consent Issues</label>
                                                <textarea name="icf_issues" rows="2" placeholder="Evaluate consent forms, clarity, and participant comprehension..."
                                                          class="w-full p-2 text-xs bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Summary of Issues & Recommendations</label>
                                                <textarea name="summary_of_issues" rows="2" placeholder="Executive summary for the committee and researcher..."
                                                          class="w-full p-2 text-xs bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Initial Review Decision: Suggested Next Review Type -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                            Suggested Next Review Type <span class="text-rose-500">*</span>
                                        </label>
                                        <select name="suggested_review_type" required
                                                class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                            <option value="" disabled selected>Select suggested review classification...</option>
                                            <option value="Exempt Review">Exempt Review</option>
                                            <option value="Expedited Review">Expedited Review</option>
                                            <option value="Full Board Review">Full Board Review</option>
                                        </select>
                                    </div>
                                @endif

                                <!-- General Deliberation Remarks -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        Executive Remarks / Deliberation Notes
                                    </label>
                                    <textarea name="remarks" rows="3" maxlength="2000"
                                              placeholder="Provide your overall evaluation feedback for the administrative committee..."
                                              class="w-full px-3 py-2 text-xs text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8B0000] transition-colors"></textarea>
                                </div>

                                <!-- Saved Remarks Overview -->
                                <div class="pt-2 border-t border-slate-100">
                                    <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                                        <span class="font-bold text-slate-700">Per-Document Review Notes Attached</span>
                                        <span class="tabular-nums font-semibold">{{ $myFileRemarks->count() }} file note(s)</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400">All document notes saved in your workspace are automatically submitted with this review.</p>
                                </div>

                                <!-- Modal Footer Buttons -->
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                                    <button type="button" @click="showModal = false"
                                            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                                        Cancel
                                    </button>
                                    <button type="submit" :disabled="{{ $reviewerUploads->isEmpty() ? 'true' : 'false' }}"
                                            style="background-color: #047857; color: #ffffff;"
                                            class="px-5 py-2 rounded-xl bg-[#047857] hover:bg-[#065f46] text-white text-xs font-bold transition-all shadow-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5 active:scale-95">
                                        <i class="fas fa-check-circle text-xs text-white" aria-hidden="true"></i>
                                        <span class="text-white font-bold">Finalize Review Submission</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </template>

        </div>

        <!-- ===== SLIDE-OVER INTELLIGENCE DRAWERS (Matching Admin & Researcher Studios) ===== -->
        <template x-teleport="body">
            <div x-show="drawerOpen" 
                 x-cloak
                 style="display: none;"
                 class="fixed inset-0 z-50 overflow-hidden" 
                 role="dialog" 
                 aria-modal="true"
                 aria-label="Protocol Details Drawer">
                
                <!-- Backdrop -->
                <div x-show="drawerOpen"
                     x-transition:enter="transition-opacity ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="closeDrawer()"
                     class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs"></div>

                <!-- Slide-over Drawer Panel -->
                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div x-show="drawerOpen"
                         x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         class="w-screen max-w-md sm:max-w-lg bg-white shadow-2xl flex flex-col border-l border-slate-200">
                        
                        <!-- Drawer Header -->
                        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-[#8B0000]/10 text-[#8B0000] border border-[#8B0000]/20 flex items-center justify-center shrink-0">
                                    <i class="fas" :class="{
                                        'fa-info-circle': drawerTab === 'details',
                                        'fa-list-check': drawerTab === 'activity',
                                        'fa-history': drawerTab === 'history'
                                    }"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 font-heading" x-text="{
                                        'details': 'Submission Details',
                                        'activity': 'Protocol Activity Log',
                                        'history': 'Revision Feedback History'
                                    }[drawerTab]"></h3>
                                    <p class="text-[11px] text-slate-500 font-mono">{{ $researchTitle->reoc_code ?? 'PENDING' }}</p>
                                </div>
                            </div>
                            <button type="button" @click="closeDrawer()"
                                    class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                                    aria-label="Close drawer">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>

                        <!-- Drawer Tab Switcher -->
                        <div class="px-4 py-2 bg-slate-100/70 border-b border-slate-200 flex gap-1 shrink-0">
                            <button type="button" @click="drawerTab = 'details'"
                                    :class="drawerTab === 'details' ? 'bg-white text-slate-900 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="flex-1 py-1 px-2.5 rounded-lg text-xs transition-all cursor-pointer text-center">
                                Details
                            </button>
                            <button type="button" @click="drawerTab = 'activity'"
                                    :class="drawerTab === 'activity' ? 'bg-white text-slate-900 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="flex-1 py-1 px-2.5 rounded-lg text-xs transition-all cursor-pointer text-center">
                                Activity Log
                            </button>
                            @if($researchTitle->revisionLogs->isNotEmpty())
                                <button type="button" @click="drawerTab = 'history'"
                                        :class="drawerTab === 'history' ? 'bg-white text-slate-900 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                        class="flex-1 py-1 px-2.5 rounded-lg text-xs transition-all cursor-pointer text-center">
                                    History
                                </button>
                            @endif
                        </div>

                        <!-- Drawer Content Body -->
                        <div class="flex-1 overflow-y-auto no-scrollbar p-5 space-y-4">
                            
                            <!-- Tab: Details -->
                            <div x-show="drawerTab === 'details'" class="space-y-4">
                                <div class="space-y-3 text-xs">
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Study Protocol Title</p>
                                        <p class="font-bold text-slate-900 leading-snug">{{ $researchTitle->Study_Protocol_title }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">REOC Code</p>
                                            <p class="font-mono font-bold text-slate-800">{{ $researchTitle->reoc_code ?? 'None' }}</p>
                                        </div>
                                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status</p>
                                            <p class="font-bold text-slate-800">{{ $researchTitle->Status }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Research Type</p>
                                            <p class="font-bold text-slate-800">{{ $researchTitle->research_type ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Category</p>
                                            <p class="font-bold text-slate-800">{{ $researchTitle->Research_Category ?? 'Research' }}</p>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lead Researcher</p>
                                        <p class="font-bold text-slate-900">
                                            {{ $researchTitle->researcher?->user ? ($researchTitle->researcher->user->first_name . ' ' . $researchTitle->researcher->user->last_name) : 'Unknown' }}
                                        </p>
                                        <p class="text-slate-500 text-[11px] mt-0.5">{{ $researchTitle->researcher?->user?->email }}</p>
                                    </div>
                                    @if(!empty($researchTitle->co_investigators))
                                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Co-Investigators</p>
                                            <p class="text-slate-700 leading-relaxed">{{ $researchTitle->co_investigators }}</p>
                                        </div>
                                    @endif
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Initial Submission Date</p>
                                        <p class="font-bold text-slate-800">{{ $researchTitle->created_at->format('F d, Y • h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab: Activity Log -->
                            <div x-show="drawerTab === 'activity'" class="space-y-3">
                                <div class="relative pl-3">
                                    <div class="absolute left-3 top-2 bottom-2 w-0.5 bg-slate-200"></div>
                                    <div class="space-y-3">
                                        @forelse($researchTitle->titleLogs as $log)
                                            <div class="flex gap-2.5 relative">
                                                <div class="w-4 h-4 rounded-full bg-slate-100 border-2 border-white flex-shrink-0 z-10 -ml-[7px] flex items-center justify-center mt-0.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-600"></div>
                                                </div>
                                                <div class="min-w-0 flex-1 pb-1">
                                                    <p class="text-xs font-bold text-slate-900 leading-snug">{{ $log->action }}</p>
                                                    <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">{{ $log->description }}</p>
                                                    <p class="text-[10px] font-mono text-slate-400 mt-0.5 tabular-nums">
                                                        {{ $log->created_at->format('M d, Y • h:i A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400 italic py-4 text-center">No activity logs recorded yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Tab: Revision History -->
                            <div x-show="drawerTab === 'history'" class="space-y-3">
                                @forelse($researchTitle->revisionLogs as $log)
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full {{ $log->user?->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }} flex items-center justify-center text-[10px] font-bold">
                                                    <i class="fas {{ $log->user?->role === 'admin' ? 'fa-user-shield' : 'fa-user' }}"></i>
                                                </div>
                                                <p class="text-xs font-bold text-slate-900">
                                                    {{ $log->user?->first_name }} {{ $log->user?->last_name }}
                                                    <span class="text-[10px] font-normal text-slate-400">({{ ucfirst($log->user?->role ?? 'User') }})</span>
                                                </p>
                                            </div>
                                            <span class="text-[10px] text-slate-400 tabular-nums">{{ $log->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-wrap pl-8">{{ $log->message }}</p>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-4 text-center">No revision history logs recorded.</p>
                                @endforelse
                            </div>

                        </div>

                        <!-- Drawer Footer -->
                        <div class="p-3.5 bg-slate-50 border-t border-slate-200 flex justify-end shrink-0">
                            <button type="button" @click="closeDrawer()"
                                    class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition-all shadow-2xs cursor-pointer">
                                Close Details
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </template>

    </div>
</x-reviewer_layout>
