<x-admin_layout>
    <div class="max-w-7xl mx-auto w-full flex-1 min-h-0 flex flex-col lg:h-full lg:max-h-full gap-2.5"
         x-data="{
            drawerOpen: false,
            drawerTab: 'audit',
            drawerSearchTerm: '',
            openDrawer(tab) {
                this.drawerTab = tab;
                this.drawerOpen = true;
            },
            closeDrawer() {
                this.drawerOpen = false;
            }
         }"
         @keydown.escape.window="if (drawerOpen) closeDrawer()">

        <!-- Top Institutional Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200/90 border-t-2 border-t-[#8B0000] shadow-xs px-4 py-2.5 sm:px-5 sm:py-3 relative overflow-hidden shrink-0">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5 sm:gap-3">
                
                <!-- Left: Back Button + Code + Status + Title -->
                <div class="flex items-start sm:items-center gap-3.5 min-w-0 flex-1">
                    <a href="{{ $backUrl }}" 
                       class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white border border-slate-200/90 text-slate-600 hover:text-slate-950 hover:bg-slate-50 hover:border-slate-300 transition-colors flex items-center justify-center shrink-0 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs active:scale-95"
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
                                    str_contains($statusLower, 'modifications required') || str_contains($statusLower, 'disapproved') || str_contains($statusLower, 'major') => 'text-rose-700',
                                    str_contains($statusLower, 'waiting') || str_contains($statusLower, 'minor') => 'text-amber-700',
                                    str_contains($statusLower, 'reviewed') || str_contains($statusLower, 'approved') => 'text-emerald-700',
                                    str_contains($statusLower, 'under review') => 'text-indigo-700',
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

                <!-- Right: Researcher Metadata & Quick Action Group -->
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

                    <!-- Quick Intelligence Action Triggers -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if($auditTrail->isNotEmpty())
                            <button type="button" @click="openDrawer('audit')" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl border border-slate-200/90 bg-white hover:bg-rose-50/60 hover:border-rose-200 text-slate-700 hover:text-[#8B0000] text-xs font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] h-8 cursor-pointer shadow-2xs group active:scale-95"
                                    title="View Complete Audit Trail">
                                <i class="fas fa-stream text-xs text-[#8B0000] group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                                <span>Audit Trail</span>
                                <span class="px-1.5 py-0.5 rounded-full bg-[#8B0000] text-white text-[10px] font-bold tabular-nums shadow-2xs">
                                    {{ $auditTrail->count() }}
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
                            <i class="fas fa-history text-xs text-indigo-600 group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                            <span>Activity Log</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        @php
            $disapprovalFeedback = $researchTitle->feedbacks()->where('type', 'disapproval_remark')->first();
            $reviewerDecisions = $researchTitle->feedbacks()->where('type', 'reviewer_decision')->orderBy('created_at', 'desc')->get();
        @endphp

        <!-- Disapproved Alert Banner (if applicable) -->
        @if($researchTitle->Status === 'Disapproved' && $disapprovalFeedback)
            <div class="p-5 sm:p-6 bg-rose-50/70 border border-rose-200/80 rounded-2xl flex items-start gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                    <i class="fas fa-times-circle text-lg" aria-hidden="true"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-rose-900 mb-1">Protocol Disapproved</h4>
                    <p class="text-xs text-rose-700 mb-3">This research protocol has been marked as disapproved for the following stated reason:</p>
                    <div class="bg-white p-4 rounded-xl border border-rose-200/70 shadow-xs text-xs text-slate-800 whitespace-pre-wrap leading-relaxed">
                        {{ $disapprovalFeedback->message }}
                    </div>
                </div>
            </div>
        @endif

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

            $reviewerDocs = $allFiles->filter(function ($f) {
                return str_starts_with($f->category ?? '', 'Reviewer Uploads');
            })->sortByDesc('created_at');

            $suggestedTypes = $reviewerDocs->whereNotNull('suggested_review_type')->map(function ($f) {
                return [
                    'type' => $f->suggested_review_type,
                    'reviewer' => $f->uploader ? ($f->uploader->first_name . ' ' . $f->uploader->last_name) : 'Unknown User'
                ];
            })->unique('reviewer');

            $hasRevisions = $revisionFolders->isNotEmpty();

            // Enriched file mapper
            $enrichFile = function ($file, $label) {
                $ext = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
                if (!$ext) {
                    $ext = strtolower($file->filetype ?? '');
                }
                $icons = [
                    'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => 'text-rose-600', 'bg' => 'bg-rose-50 text-rose-700 border border-rose-200/90'],
                    'doc' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50 text-blue-700 border border-blue-200/90'],
                    'docx' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50 text-blue-700 border border-blue-200/90'],
                    'ppt' => ['icon' => 'fas fa-file-powerpoint', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50 text-amber-800 border border-amber-200/90'],
                    'pptx' => ['icon' => 'fas fa-file-powerpoint', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50 text-amber-800 border border-amber-200/90'],
                    'xls' => ['icon' => 'fas fa-file-excel', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/90'],
                    'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/90'],
                    'jpg' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50 text-purple-700 border border-purple-200/90'],
                    'jpeg' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50 text-purple-700 border border-purple-200/90'],
                    'png' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50 text-purple-700 border border-purple-200/90'],
                    'gif' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50 text-purple-700 border border-purple-200/90'],
                    'webp' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50 text-purple-700 border border-purple-200/90'],
                ];
                $attrs = $icons[$ext] ?? ['icon' => 'fas fa-file-alt', 'color' => 'text-teal-600', 'bg' => 'bg-teal-50 text-teal-700 border border-teal-200/90'];
                return [
                    'id' => $file->id,
                    'filename' => $file->filename,
                    'label' => $file->category ?? 'Uncategorized',
                    'group' => $label,
                    'ext' => $ext,
                    'revision_number' => $file->revision_number,
                    'uploaded_at' => $file->created_at->format('M d, Y'),
                    'uploaded_by_name' => $file->uploader ? ($file->uploader->first_name . ' ' . $file->uploader->last_name) : 'Unknown User',
                    'icon' => $attrs['icon'],
                    'color' => $attrs['color'],
                    'bg' => $attrs['bg'],
                    'suggested_review_type' => $file->suggested_review_type,
                    'remarks' => $file->remarks ?? '',
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
            $jsReviewerDocs = $groupFiles($reviewerDocs, 'Reviewer Docs');

            $jsRevisions = [];
            foreach ($revisionFolders as $revNum => $files) {
                $jsRevisions[$revNum] = $groupFiles($files, "Revision $revNum");
            }

            $appFormFile = $originalFiles->firstWhere('category', 'Application Form') 
                ?? $originalFiles->first(function($f) { return stripos($f->category ?? '', 'application form') !== false; })
                ?? $activeFiles->firstWhere('category', 'Application Form')
                ?? $originalFiles->first() 
                ?? $activeFiles->first();
            $firstFile = $appFormFile ? $enrichFile($appFormFile, $originalFiles->contains($appFormFile) ? 'Original' : 'Current') : null;
            $serveRoute = route('admin.serve_file', 'FILE_ID');
        @endphp

        <!-- Main 12-Column File Workspace Grid (Starts immediately below Header) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-stretch flex-1 min-h-0 lg:h-full" x-data="{
                activeFile: {{ $firstFile ? json_encode($firstFile) : 'null' }},
                activeTab: 'original',
                originalFiles: {{ json_encode($jsOriginal) }},
                activeFiles: {{ json_encode($jsActive) }},
                letters: {{ json_encode($jsLetters) }},
                reviewerDocs: {{ json_encode($jsReviewerDocs) }},
                revisions: {{ json_encode($jsRevisions) }},
                hasRevisions: {{ $hasRevisions ? 'true' : 'false' }},
                revisionNums: {{ json_encode(array_keys($jsRevisions)) }},
                serveRoute: '{{ $serveRoute }}',
                isLocalEnv: (window.location.hostname.includes('test') || window.location.hostname.includes('localhost') || window.location.hostname === '127.0.0.1'),
                getUrl(file) {
                    if (!file) return '';
                    return this.serveRoute.replace('FILE_ID', file.id);
                },
                getDownloadUrl(file) {
                    if (!file) return '';
                    const url = this.getUrl(file);
                    return url + (url.includes('?') ? '&' : '?') + 'download=1';
                },
                getOfficeUrl(file) {
                    if (!file || !file.public_url) return '';
                    return 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURIComponent(file.public_url);
                },
                isPdf(file) { return file && file.ext === 'pdf'; },
                isOffice(file) { return file && ['doc','docx','ppt','pptx','xls','xlsx'].includes(file.ext); },
                isImage(file) { return file && ['jpg','jpeg','png','gif','bmp','webp'].includes(file.ext); },
                selectFile(file) { this.activeFile = file; }
             }">            <!-- ===== LEFT COLUMN — Document Viewer (7 Cols) ===== -->
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
                        <a :href="isOffice(activeFile) && !isLocalEnv ? getOfficeUrl(activeFile) : getUrl(activeFile)" target="_blank"
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200/90 text-slate-600 hover:text-blue-700 hover:bg-blue-50/60 hover:border-blue-300 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-2xs active:scale-95 cursor-pointer"
                            title="Open in new window" aria-label="Open document in new window">
                            <i class="fas fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                        </a>
                        <a :href="getDownloadUrl(activeFile)" download
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200/90 text-slate-600 hover:text-[#8B0000] hover:bg-red-50/60 hover:border-red-300 transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs active:scale-95 cursor-pointer"
                            title="Download document" aria-label="Download document">
                            <i class="fas fa-download text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <!-- Document Frame Container (Calm, High-Contrast Canvas Surface) -->
                <div id="document-preview-container" class="bg-slate-900/[0.02] rounded-2xl overflow-hidden border border-slate-200/90 shadow-2xs relative flex-1 min-h-0 h-[360px] lg:h-full">
                    <template x-if="activeFile && isPdf(activeFile)">
                        <iframe :src="getUrl(activeFile)" class="w-full h-full border-0 bg-white"
                            title="PDF Document Viewer"></iframe>
                    </template>
                    <template x-if="activeFile && isOffice(activeFile)">
                        <template x-if="!isLocalEnv">
                            <iframe :src="getOfficeUrl(activeFile)" class="w-full h-full border-0 bg-white"
                                title="Office Document Viewer"></iframe>
                        </template>
                        <template x-if="isLocalEnv">
                            <div class="absolute inset-0 flex items-center justify-center bg-slate-50/90 p-6 overflow-auto">
                                <div class="text-center max-w-sm bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-2xs border border-slate-100"
                                         :class="activeFile.bg">
                                        <i :class="[activeFile.icon, activeFile.color]" class="text-2xl" aria-hidden="true"></i>
                                    </div>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 mb-2"
                                          x-text="(activeFile.ext ? activeFile.ext.toUpperCase() : 'DOC') + ' Document'"></span>
                                    <h4 class="text-xs font-bold text-slate-900 truncate mb-1" x-text="activeFile.filename"></h4>
                                    <p class="text-[11px] text-slate-500 mb-4 leading-relaxed">
                                        Local development environment detected. Office documents can be downloaded directly or opened locally.
                                    </p>
                                    <div class="flex items-center justify-center gap-2">
                                        <a :href="getDownloadUrl(activeFile)"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#8B0000] text-white text-xs font-semibold hover:bg-[#700000] transition-colors shadow-2xs">
                                            <i class="fas fa-download text-xs" aria-hidden="true"></i>
                                            <span>Download</span>
                                        </a>
                                        <a :href="getUrl(activeFile)" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors shadow-2xs">
                                            <i class="fas fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                                            <span>Open Raw</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                    <template x-if="activeFile && isImage(activeFile)">
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-50/90 p-4 overflow-auto">
                            <img :src="getUrl(activeFile)" :alt="activeFile.filename"
                                class="max-w-full max-h-full object-contain rounded-lg shadow-sm border border-slate-300" />
                        </div>
                    </template>
                    <template x-if="!activeFile">
                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-b from-slate-50/80 to-slate-100/60">
                            <div class="text-center p-6 max-w-sm">
                                <div class="w-12 h-12 bg-white rounded-2xl border border-slate-200/90 flex items-center justify-center mx-auto mb-3 text-[#8B0000] shadow-xs">
                                    <i class="fas fa-file-signature text-xl" aria-hidden="true"></i>
                                </div>
                                <h3 class="text-xs font-bold text-slate-900 mb-1">No Document Selected</h3>
                                <p class="text-[11px] text-slate-500 font-medium">Choose a document category from the panel on the right to preview.</p>
                            </div>
                        </div>
                    </template>
                </div>

            </div>

            <!-- ===== RIGHT COLUMN — Document Ledger & Categories (5 Cols) ===== -->
            <div class="lg:col-span-5 flex flex-col gap-2 min-h-0 h-full overflow-hidden">

                <!-- AI Review Type Suggestion Card -->
                @if($researchTitle->ai_suggested_review_type)
                    <div class="bg-gradient-to-r from-violet-50/90 via-indigo-50/70 to-purple-50/90 border border-indigo-200/90 p-3 rounded-2xl shadow-2xs relative overflow-hidden shrink-0">
                        <div class="flex items-center justify-between gap-1.5 mb-1">
                            <span class="flex items-center gap-1.5 text-indigo-900 text-[10px] font-bold uppercase tracking-wider">
                                <span class="w-5 h-5 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-[10px] shadow-2xs">
                                    <i class="fas fa-brain" aria-hidden="true"></i>
                                </span>
                                AI Protocol Recommendation
                            </span>
                            <span class="text-[10px] font-bold text-indigo-700 bg-white/90 px-2 py-0.5 rounded-full border border-indigo-200 shadow-2xs">
                                System Suggestion
                            </span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 leading-snug pl-6.5">
                            {{ $researchTitle->ai_suggested_review_type }}
                        </h4>
                    </div>
                @endif

                <!-- Reviewer Remarks Accordion -->
                @php
                    $totalRemarks = $allFileRemarks->flatten()->count();
                @endphp
                @if($totalRemarks > 0)
                    <div class="bg-white rounded-2xl shadow-2xs border border-indigo-200/80 overflow-hidden shrink-0" x-data="{ rrOpen: false }">
                        <button type="button" @click="rrOpen = !rrOpen" 
                                class="w-full flex justify-between items-center px-3.5 py-2.5 bg-gradient-to-r from-indigo-50/60 to-white hover:bg-indigo-50/80 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-[10px] shadow-2xs">
                                    <i class="fas fa-comments" aria-hidden="true"></i>
                                </span>
                                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Reviewer Remarks</span>
                                <span class="bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full tabular-nums shadow-2xs">{{ $totalRemarks }}</span>
                            </div>
                            <i class="fas fa-chevron-up text-xs text-slate-400 transition-transform duration-200" :class="rrOpen ? '' : 'rotate-180'" aria-hidden="true"></i>
                        </button>

                        <div x-show="rrOpen" style="display: none;" x-transition>
                            @php
                                $remarksByReviewer = $allFileRemarks->flatten()->groupBy('reviewer_id');
                            @endphp
                            <div class="p-3 space-y-2 max-h-[300px] overflow-y-auto custom-scrollbar">
                                @foreach($remarksByReviewer as $reviewerId => $remarks)
                                    @php
                                        $reviewer = $remarks->first()->reviewer;
                                        $reviewerName = $reviewer ? ($reviewer->first_name . ' ' . $reviewer->last_name) : 'Unknown Reviewer';
                                        $initial = $reviewer ? strtoupper(substr($reviewer->first_name, 0, 1)) : '?';
                                    @endphp
                                    <div class="border border-slate-200/80 rounded-xl overflow-hidden bg-white shadow-2xs">
                                        <div class="flex items-center gap-2.5 px-3 py-2 bg-gradient-to-r from-slate-50 to-indigo-50/30 border-b border-slate-100">
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-600 to-indigo-800 text-white flex items-center justify-center text-[10px] font-bold shrink-0 shadow-2xs">
                                                {{ $initial }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-900 truncate">{{ $reviewerName }}</p>
                                                <p class="text-[10px] text-slate-500 font-medium">{{ $remarks->count() }} remark(s)</p>
                                            </div>
                                        </div>
                                        <div class="divide-y divide-slate-100">
                                            @foreach($remarks as $remark)
                                                @php
                                                    $remarkFile = $remark->file
                                                        ?? $researchTitle->files->firstWhere('id', $remark->file_id)
                                                        ?? $researchTitle->adminFiles->firstWhere('id', $remark->file_id);
                                                @endphp
                                                <div class="p-2.5">
                                                    @if($remarkFile)
                                                        <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider mb-0.5 truncate" title="{{ $remarkFile->filename }}">
                                                            <i class="fas fa-file-alt mr-1"></i>{{ $remarkFile->category ?? $remarkFile->filename }}
                                                        </p>
                                                    @endif
                                                    <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $remark->remarks }}</p>
                                                    <p class="text-[10px] font-mono tabular-nums text-slate-400 mt-0.5">{{ $remark->updated_at->format('M d, Y • h:i A') }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Version Timeline Accordion -->
                @if($hasRevisions)
                    <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden shrink-0" x-data="{ vtOpen: false }">
                        <button type="button" @click="vtOpen = !vtOpen" 
                                class="w-full flex justify-between items-center px-3.5 py-2.5 bg-gradient-to-r from-slate-50 to-white hover:bg-slate-50 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-[10px] shadow-2xs">
                                    <i class="fas fa-code-branch" aria-hidden="true"></i>
                                </span>
                                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Version History</span>
                            </div>
                            <i class="fas fa-chevron-up text-xs text-slate-400 transition-transform duration-200" :class="vtOpen ? '' : 'rotate-180'" aria-hidden="true"></i>
                        </button>
                        <div x-show="vtOpen" style="display: none;" x-transition>
                            <div class="p-3 border-t border-slate-100 bg-white">
                                <div class="relative pl-3 space-y-2.5">
                                    <div class="absolute left-3 top-2 bottom-2 w-0.5 bg-slate-200 pointer-events-none"></div>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 border-2 border-slate-400 flex items-center justify-center shrink-0 z-10 -ml-3">
                                            <i class="fas fa-box-archive text-slate-600 text-[9px]" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900">Original Submission</p>
                                            <p class="text-[10px] text-slate-500 tabular-nums font-medium">
                                                {{ $originalFiles->first()?->created_at?->format('M d, Y') ?? 'None' }} · {{ $originalFiles->count() }} file(s)
                                            </p>
                                        </div>
                                    </div>
                                    @foreach($revisionFolders->sortKeys() as $revNum => $files)
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-6 h-6 rounded-full bg-indigo-50 border-2 border-indigo-400 flex items-center justify-center shrink-0 z-10 -ml-3">
                                                <span class="text-[9px] font-bold text-indigo-700 tabular-nums">{{ $revNum }}</span>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-slate-900">Revision {{ $revNum }}</p>
                                                <p class="text-[10px] text-slate-500 tabular-nums font-medium">
                                                    {{ $files->first()?->created_at?->format('M d, Y') ?? 'None' }} · {{ $files->count() }} file(s)
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Document Selector with Tactile Segmented Tabs -->
                <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden flex flex-col flex-1 min-h-0">
                    
                    <!-- Segmented Control Tab Bar with Interactive Revision Selector -->
                    <div class="p-1.5 border-b border-slate-200/80 bg-slate-50/50 shrink-0 relative" x-data="{ revDropdownOpen: false }">
                        <div id="document-tab-bar" class="flex gap-1 overflow-x-auto custom-scrollbar p-0.5 bg-slate-100/80 rounded-xl border border-slate-200/80 cursor-grab select-none">
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

                            <template x-if="reviewerDocs.length > 0">
                                <button type="button" @click="activeTab = 'reviewer_docs'; if (reviewerDocs.length > 0 && reviewerDocs[0].files.length > 0 && (!activeFile || activeFile.group !== 'Reviewer Docs')) { selectFile(reviewerDocs[0].files[0]); }"
                                    :class="activeTab === 'reviewer_docs' ? 'bg-white text-emerald-950 shadow-2xs border border-emerald-200 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60 font-medium'"
                                    class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0">
                                    <i class="fas fa-user-edit text-xs" :class="activeTab === 'reviewer_docs' ? 'text-emerald-700' : 'text-slate-400'"></i>
                                    <span>Reports</span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded-full tabular-nums"
                                          :class="activeTab === 'reviewer_docs' ? 'bg-emerald-700 text-white font-bold' : 'bg-slate-200/70 text-slate-600 font-medium'"
                                          x-text="reviewerDocs.reduce((acc, g) => acc + g.files.length, 0)">
                                    </span>
                                </button>
                            </template>

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

                        {{-- Revision Selection Dropdown Menu (Positioned safely outside overflow container) --}}
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
                                <div class="max-h-64 overflow-y-auto custom-scrollbar divide-y divide-slate-100">
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
                    <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar p-2">

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
                                                        <p class="text-xs truncate" :class="activeFile && activeFile.id === file.id ? 'font-bold text-[#8B0000]' : 'font-semibold text-slate-900'" x-text="file.filename"></p>
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

                        <!-- Original Documents Tab List -->
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
                                                        <p class="text-xs truncate" :class="activeFile && activeFile.id === file.id ? 'font-bold text-[#8B0000]' : 'font-semibold text-slate-900'" x-text="file.filename"></p>
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

                        <!-- Revision Tabs (One per revision number) -->
                        @foreach($revisionFolders->sortKeys() as $revNum => $_)
                            <div x-show="activeTab === 'rev_{{ $revNum }}'" style="display:none;">
                                <div class="px-3 py-2 bg-indigo-50/70 border border-indigo-200/80 rounded-xl mb-2 flex items-center justify-between">
                                    <span class="flex items-center gap-2 text-xs font-bold text-indigo-950">
                                        <i class="fas fa-code-branch text-indigo-600 text-xs" aria-hidden="true"></i>
                                        <span>Revision {{ $revNum }} Documents</span>
                                    </span>
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
                                                            <p class="text-xs truncate" :class="activeFile && activeFile.id === file.id ? 'font-bold text-[#8B0000]' : 'font-semibold text-slate-900'" x-text="file.filename"></p>
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

                        <!-- Reviewer Uploads Tab List -->
                        <div x-show="activeTab === 'reviewer_docs'" style="display:none;">
                            <div class="px-3 py-2 bg-emerald-50/70 border border-emerald-200/80 rounded-xl mb-2">
                                <span class="flex items-center gap-2 text-xs font-bold text-emerald-950">
                                    <i class="fas fa-user-edit text-emerald-700 text-xs" aria-hidden="true"></i>
                                    <span>Reviewer Uploaded Reports & Evaluations</span>
                                </span>
                            </div>
                            <template x-for="group in reviewerDocs" :key="group.category">
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
                                                        <p class="text-xs truncate" :class="activeFile && activeFile.id === file.id ? 'font-bold text-[#8B0000]' : 'font-semibold text-slate-900'" x-text="file.filename"></p>
                                                        <p class="text-[10px] font-semibold text-slate-600" x-text="'By: ' + file.uploaded_by_name"></p>
                                                        <div class="flex items-center gap-2 mt-0.5">
                                                            <span class="text-[10px] tabular-nums text-slate-400 font-medium" x-text="file.uploaded_at"></span>
                                                            <span x-show="file.suggested_review_type"
                                                                  class="text-[10px] font-bold px-2 py-0.2 rounded-md border text-emerald-800 bg-emerald-50 border-emerald-200 shadow-2xs"
                                                                  x-text="file.suggested_review_type"></span>
                                                        </div>
                                                        <p x-show="file.remarks"
                                                           class="text-[10px] italic mt-1 line-clamp-2 text-slate-600 font-normal bg-slate-50/80 p-1.5 rounded-lg border border-slate-100"
                                                           x-text="'&quot;' + file.remarks + '&quot;'"></p>
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

                        <!-- Current Documents Tab List -->
                        @if($hasRevisions && $activeFiles->isNotEmpty())
                            <div x-show="activeTab === 'current'" style="display:none;">
                                <div class="px-3 py-2 bg-amber-50/70 border border-amber-200/80 rounded-xl mb-2">
                                    <span class="flex items-center gap-2 text-xs font-bold text-amber-950">
                                        <i class="fas fa-file-signature text-amber-700 text-xs" aria-hidden="true"></i>
                                        <span>Latest Version of Each Document</span>
                                    </span>
                                </div>
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
                                                            <p class="text-xs truncate" :class="activeFile && activeFile.id === file.id ? 'font-bold text-[#8B0000]' : 'font-semibold text-slate-900'" x-text="file.filename"></p>
                                                            <p class="text-[10px] font-semibold tabular-nums"
                                                               :class="activeFile && activeFile.id === file.id ? 'text-indigo-800' : (file.revision_number ? 'text-indigo-600' : 'text-slate-500')"
                                                               x-text="file.revision_number ? 'Revision ' + file.revision_number : 'Original'">
                                                            </p>
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
                        @endif

                    </div>{{-- end scrollable list --}}
                </div>{{-- end tabbed picker --}}

            </div>{{-- end right sidebar --}}
        </div>{{-- end grid --}}

        <!-- ========================================================= -->
        <!-- Unified Protocol Intelligence Slide-over Drawer           -->
        <!-- (Audit Trail, Submission Details, Protocol Activity Log)  -->
        <!-- ========================================================= -->
        <div x-show="drawerOpen" 
             style="display: none;" 
             class="relative z-[100]" 
             aria-labelledby="drawer-title" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="drawerOpen" 
                 x-transition:enter="ease-in-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/30 backdrop-blur-xs transition-opacity" 
                 @click="closeDrawer()"></div>

            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-4 sm:pl-10"
                         x-show="drawerOpen"
                         x-transition:enter="transform transition ease-in-out duration-300"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-300"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full">
                        
                        <div class="pointer-events-auto w-screen max-w-md sm:max-w-lg flex flex-col h-full bg-white shadow-xl border-l border-slate-200/80">
                                               <!-- Drawer Top Institutional Header -->
                            <div class="p-5 border-b border-slate-200/90 bg-white flex items-start justify-between gap-3 shrink-0">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                        <span class="font-mono text-xs font-bold tabular-nums text-slate-900 bg-slate-100 border border-slate-300 px-2.5 py-0.5 rounded-md shadow-2xs">
                                            {{ $researchTitle->reoc_code ?? ('#'.str_pad($researchTitle->id, 5, '0', STR_PAD_LEFT)) }}
                                        </span>
                                        <span class="inline-flex items-center text-xs font-bold uppercase tracking-wider {{ $statusTextColor }}">
                                            {{ $researchTitle->Status }}
                                        </span>
                                    </div>
                                    <h3 id="drawer-title" class="font-heading font-bold text-base sm:text-lg text-slate-950 truncate" title="{{ $researchTitle->Study_Protocol_title }}">
                                        {{ $researchTitle->Study_Protocol_title }}
                                    </h3>
                                    <p class="text-xs text-slate-600 mt-0.5">
                                        Researcher: <span class="font-bold text-slate-900">{{ $researchTitle->researcher?->user ? ($researchTitle->researcher->user->first_name . ' ' . $researchTitle->researcher->user->last_name) : ($researchTitle->Created_by ?? 'Unknown') }}</span>
                                    </p>
                                </div>
                                <button type="button" @click="closeDrawer()" 
                                        class="w-8 h-8 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 transition-all flex items-center justify-center shrink-0 min-w-[36px] min-h-[36px] focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs active:scale-95"
                                        title="Close Drawer (Esc)" aria-label="Close Drawer">
                                    <i class="fas fa-times text-xs" aria-hidden="true"></i>
                                </button>
                            </div>

                            <!-- Drawer Multi-Tab Switcher -->
                            <div class="px-5 py-2.5 bg-slate-50 border-b border-slate-200 shrink-0">
                                <div class="flex gap-1 bg-slate-200/70 p-1 rounded-xl border border-slate-300/80">
                                    @if($auditTrail->isNotEmpty())
                                        <button type="button" @click="drawerTab = 'audit'"
                                                :class="drawerTab === 'audit' ? 'bg-white text-rose-950 shadow-xs border border-rose-200 font-bold' : 'text-slate-600 hover:text-slate-950 hover:bg-white/50 font-semibold'"
                                                class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-xs rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                            <i class="fas fa-stream text-xs" :class="drawerTab === 'audit' ? 'text-[#8B0000]' : 'text-slate-500'"></i>
                                            <span>Audit Trail</span>
                                            <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full tabular-nums"
                                                  :class="drawerTab === 'audit' ? 'bg-[#8B0000] text-white' : 'bg-slate-300/80 text-slate-700'">
                                                {{ $auditTrail->count() }}
                                            </span>
                                        </button>
                                    @endif

                                    <button type="button" @click="drawerTab = 'details'"
                                            :class="drawerTab === 'details' ? 'bg-white text-blue-950 shadow-xs border border-blue-200 font-bold' : 'text-slate-600 hover:text-slate-950 hover:bg-white/50 font-semibold'"
                                            class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-xs rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                        <i class="fas fa-info-circle text-xs" :class="drawerTab === 'details' ? 'text-blue-700' : 'text-slate-500'"></i>
                                        <span>Details</span>
                                    </button>

                                    <button type="button" @click="drawerTab = 'activity'"
                                            :class="drawerTab === 'activity' ? 'bg-white text-indigo-950 shadow-xs border border-indigo-200 font-bold' : 'text-slate-600 hover:text-slate-950 hover:bg-white/50 font-semibold'"
                                            class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-xs rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                                        <i class="fas fa-history text-xs" :class="drawerTab === 'activity' ? 'text-indigo-600' : 'text-slate-500'"></i>
                                        <span>Activity Log</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Drawer Body Content -->
                            <div class="flex-1 overflow-y-auto custom-scrollbar">
                                
                                <!-- Tab 1: Complete Audit Trail -->
                                <div x-show="drawerTab === 'audit'" class="h-full flex flex-col">
                                    <!-- Search input sticky at top of tab -->
                                    <div class="p-4 border-b border-slate-200 bg-slate-50/90 sticky top-0 z-10">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="fas fa-search text-xs" aria-hidden="true"></i>
                                            </div>
                                            <input type="text" x-model="drawerSearchTerm" placeholder="Filter audit trail by user, action, remarks..." 
                                                class="w-full bg-white pl-9 pr-3.5 py-2 border border-slate-300 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-[#8B0000] shadow-2xs">
                                        </div>
                                    </div>
                                    <div class="p-5 space-y-4 relative pl-7">
                                        <div class="absolute left-7 top-6 bottom-6 w-0.5 bg-slate-200 pointer-events-none"></div>
                                        @foreach($auditTrail as $log)
                                            <div class="flex gap-3.5 group" 
                                                 data-search="{{ strtolower(htmlspecialchars($log->action_label . ' ' . $log->actor_name . ' ' . $log->actor_role . ' ' . $log->message)) }}"
                                                 x-show="drawerSearchTerm === '' || $el.dataset.search.includes(drawerSearchTerm.toLowerCase())">
                                                <div class="w-7 h-7 rounded-full {{ $log->color }} {{ $log->border }} flex items-center justify-center border-2 border-white shadow-2xs shrink-0 -ml-3.5">
                                                    <i class="fas {{ $log->icon }} text-[10px]" aria-hidden="true"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-col gap-0.5 mb-1.5">
                                                        <div class="flex items-center justify-between gap-2 flex-wrap">
                                                            <p class="text-xs font-bold text-slate-900">{{ $log->action_label }}</p>
                                                            <span class="text-[10px] font-mono tabular-nums text-slate-500 font-medium">
                                                                {{ $log->created_at->format('M d, Y • h:i A') }}
                                                            </span>
                                                        </div>
                                                        <p class="text-[11px] text-slate-600 font-semibold">
                                                            {{ $log->actor_name }} <span class="text-slate-400 font-normal">({{ $log->actor_role }})</span>
                                                        </p>
                                                    </div>
                                                    <div class="bg-slate-50 border border-slate-200/90 rounded-xl p-3 text-xs text-slate-800 leading-relaxed whitespace-pre-wrap">
                                                        {{ $log->message }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Tab 2: Submission Details -->
                                <div x-show="drawerTab === 'details'" style="display: none;" class="p-5 space-y-4 text-xs">
                                    <div class="space-y-3.5 bg-slate-50 p-4 rounded-xl border border-slate-200/90 shadow-2xs">
                                        <div class="flex justify-between items-center gap-3">
                                            <span class="text-slate-600 font-semibold">Researcher Name:</span>
                                            <span class="font-bold text-slate-900 text-right">
                                                {{ $researchTitle->researcher?->user ? ($researchTitle->researcher->user->first_name . ' ' . $researchTitle->researcher->user->last_name) : ($researchTitle->Created_by ?? 'Unknown') }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center gap-3 pt-2.5 border-t border-slate-200">
                                            <span class="text-slate-600 font-semibold">Submission Date:</span>
                                            <span class="font-bold text-slate-900 tabular-nums">
                                                {{ $researchTitle->created_at->format('M d, Y • h:i A') }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center gap-3 pt-2.5 border-t border-slate-200">
                                            <span class="text-slate-600 font-semibold">Research Category:</span>
                                            <span class="font-bold text-slate-900 text-right">
                                                {{ $researchTitle->Research_Category ?? 'Unspecified' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-start gap-3 pt-2.5 border-t border-slate-200">
                                            <span class="text-slate-600 font-semibold">Assigned Reviewers:</span>
                                            <div class="text-right">
                                                @if($reviewerAssignments->count() > 0)
                                                    <p class="font-bold text-slate-900">{{ $reviewerAssignments->map(fn($r) => $r->first_name . ' ' . $r->last_name)->join(', ') }}</p>
                                                @else
                                                    <span class="italic text-slate-400">None assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center gap-3 pt-2.5 border-t border-slate-200">
                                            <span class="text-slate-600 font-semibold">Total Revisions:</span>
                                            <span class="font-bold text-slate-900">
                                                {{ $researchTitle->files->where('revision_number', '>', 0)->count() }} revision(s) submitted
                                            </span>
                                        </div>
                                        @if($researchTitle->category_fee_at_submission)
                                            <div class="flex justify-between items-center gap-3 pt-2.5 border-t border-slate-200">
                                                <span class="text-slate-600 font-semibold">Fee at Submission:</span>
                                                <span class="font-mono font-bold text-slate-950 tabular-nums">
                                                    ₱{{ number_format($researchTitle->category_fee_at_submission, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($suggestedTypes->count() > 0)
                                        <div class="pt-2">
                                            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                <i class="fas fa-lightbulb text-amber-500" aria-hidden="true"></i> Reviewer Recommendations
                                            </p>
                                            <div class="space-y-2">
                                                @foreach($suggestedTypes as $sugg)
                                                    <div class="flex justify-between items-center py-2 px-3 rounded-xl bg-slate-50 border border-slate-300 shadow-2xs">
                                                        <span class="font-semibold text-slate-800">{{ $sugg['reviewer'] }}</span>
                                                        <span class="font-bold text-amber-800 bg-amber-50 border border-amber-300 px-2 py-0.5 rounded-md">{{ $sugg['type'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Tab 3: Protocol Activity Log -->
                                <div x-show="drawerTab === 'activity'" style="display: none;" class="p-5">
                                    <div class="relative pl-3 space-y-4">
                                        <div class="absolute left-3 top-1 bottom-1 w-0.5 bg-slate-200 pointer-events-none"></div>
                                        @forelse($researchTitle->titleLogs as $log)
                                            <div class="flex gap-3 relative">
                                                <div class="w-5 h-5 rounded-full bg-slate-100 border-2 border-white flex-shrink-0 z-10 -ml-[9px] flex items-center justify-center shadow-2xs">
                                                    <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs font-bold text-slate-900 leading-tight">{{ $log->action }}</p>
                                                    <p class="text-xs text-slate-700 mt-0.5 leading-relaxed">{{ $log->description }}</p>
                                                    <p class="text-[10px] font-mono tabular-nums text-slate-500 font-medium mt-1">{{ $log->created_at->format('M d, Y • h:i A') }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-500 italic">No activity logs recorded.</p>
                                        @endforelse
                                    </div>
                                </div>

                            </div>

                            <!-- Drawer Footer -->
                            <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end shrink-0">
                                <button type="button" @click="closeDrawer()" 
                                        class="px-5 py-2 text-xs font-bold text-slate-700 hover:text-slate-950 bg-white border border-slate-300 rounded-xl shadow-2xs hover:bg-slate-100 transition-all min-h-[38px] focus:outline-none focus:ring-2 focus:ring-[#8B0000] active:scale-95">
                                    Close
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- CV Verification Modal -->
    <div id="cvVerifyModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 opacity-0 transition-opacity duration-300" aria-modal="true" role="dialog" aria-labelledby="cvModalTitle">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col" id="cvVerifyModalContent">
            <!-- Sovereign Obsidian Header -->
            <div class="bg-[#1a0505] p-5 border-b border-white/10 relative overflow-hidden shrink-0">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-white pointer-events-none">
                    <i class="fas fa-id-badge text-5xl transform rotate-12" aria-hidden="true"></i>
                </div>
                <h3 id="cvModalTitle" class="text-white font-bold text-base sm:text-lg relative z-10">Verify CV Classification</h3>
                <p class="text-slate-400 text-xs mt-0.5 relative z-10">Ensure the researcher's CV matches the selected project category.</p>
            </div>
            
            <form id="cvVerifyForm" method="POST" action="{{ route('admin.verifyCvIsolated', $researchTitle->id) }}">
                @csrf
                <div class="p-5 space-y-4">
                    <p class="text-xs text-slate-500 leading-relaxed">Review the researcher's Curriculum Vitae file, then submit verification or flag a classification mismatch.</p>
                    
                    <label class="flex items-start gap-3 p-3.5 border border-slate-200/80 rounded-xl cursor-pointer bg-slate-50/60 hover:bg-emerald-50/60 hover:border-emerald-200 transition-colors">
                        <input type="radio" name="cv_action" value="verify" class="mt-0.5 text-emerald-600 focus:ring-emerald-500" onchange="document.getElementById('cvRemarksBox').classList.add('hidden'); document.getElementById('cv_remarks_isolated').required = false;">
                        <div>
                            <span class="text-xs font-bold text-slate-800">Verify CV</span>
                            <p class="text-[11px] text-slate-500 mt-0.5">Classification accurately reflects the uploaded CV.</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3.5 border border-slate-200/80 rounded-xl cursor-pointer bg-slate-50/60 hover:bg-red-50/60 hover:border-red-200 transition-colors">
                        <input type="radio" name="cv_action" value="invalidate" class="mt-0.5 text-red-600 focus:ring-red-500" onchange="document.getElementById('cvRemarksBox').classList.remove('hidden'); document.getElementById('cv_remarks_isolated').required = true;">
                        <div>
                            <span class="text-xs font-bold text-slate-800">Flag as Invalid</span>
                            <p class="text-[11px] text-slate-500 mt-0.5">Mismatch detected between CV credentials and classification.</p>
                        </div>
                    </label>

                    <div id="cvRemarksBox" class="hidden mt-3">
                        <label for="cv_remarks_isolated" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Reason for Invalidation <span class="text-red-500">*</span></label>
                        <textarea name="cv_remarks" id="cv_remarks_isolated" rows="3"
                            class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 resize-none shadow-xs text-slate-800"
                            placeholder="State reason (e.g., CV indicates student status while submitted under faculty category)..."></textarea>
                    </div>
                </div>
                
                <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/80 flex justify-end gap-2.5 shrink-0">
                    <button type="button" onclick="closeCvVerifyModal()"
                        class="px-4 py-2 text-slate-600 font-bold text-xs hover:bg-white hover:text-slate-800 rounded-xl transition-all border border-transparent hover:border-slate-200 min-h-[44px]">
                        Cancel
                    </button>
                    <button type="submit" id="cvVerifySubmitBtn"
                        class="px-5 py-2 bg-[#8B0000] text-white font-bold text-xs rounded-xl shadow-xs hover:bg-[#6d0000] transition-colors flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-[#8B0000] min-h-[44px]">
                        Submit Decision
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        window.openCvVerifyModal = function() {
            const modal = document.getElementById('cvVerifyModal');
            const content = document.getElementById('cvVerifyModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        window.closeCvVerifyModal = function() {
            const modal = document.getElementById('cvVerifyModal');
            const content = document.getElementById('cvVerifyModalContent');
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        document.getElementById('cvVerifyForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const actionFields = this.querySelectorAll('input[name="cv_action"]:checked');
            if(actionFields.length === 0) {
                alert('Please select an action.');
                return;
            }
            
            const btn = document.getElementById('cvVerifySubmitBtn');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5" aria-hidden="true"></i> Processing...';
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}' },
                    body: new FormData(this)
                });
                const data = await response.json();
                if(response.ok && data.success) {
                    window.location.reload();
                } else {
                    alert('An error occurred while updating CV verification.');
                }
            } catch(error) {
                console.error(error);
                alert('A network error occurred.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        });

        // Interactive Mouse Drag-to-Scroll ("hold click then swipe") & Wheel Scroll for Tab Bar
        (function initTabDragScroll() {
            const setupDragScroll = () => {
                const tabBar = document.getElementById('document-tab-bar');
                if (!tabBar || tabBar.dataset.dragScrollInitialized) return;
                tabBar.dataset.dragScrollInitialized = 'true';

                let isDown = false;
                let startX = 0;
                let scrollLeft = 0;
                let hasDragged = false;

                // Mouse down: Start drag
                tabBar.addEventListener('mousedown', (e) => {
                    if (e.button !== 0) return; // Only left click
                    isDown = true;
                    hasDragged = false;
                    tabBar.classList.add('cursor-grabbing');
                    tabBar.classList.remove('cursor-grab');
                    startX = e.pageX - tabBar.offsetLeft;
                    scrollLeft = tabBar.scrollLeft;
                });

                // Mouse move: Scroll horizontally while holding click
                window.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    const x = e.pageX - tabBar.offsetLeft;
                    const walk = (x - startX);
                    if (Math.abs(walk) > 4) {
                        hasDragged = true;
                    }
                    tabBar.scrollLeft = scrollLeft - walk;
                });

                // Mouse up: End drag and prevent accidental button clicks
                window.addEventListener('mouseup', () => {
                    if (!isDown) return;
                    isDown = false;
                    tabBar.classList.remove('cursor-grabbing');
                    tabBar.classList.add('cursor-grab');

                    if (hasDragged) {
                        const captureClick = (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                        };
                        tabBar.addEventListener('click', captureClick, { capture: true, once: true });
                        setTimeout(() => {
                            tabBar.removeEventListener('click', captureClick, { capture: true });
                        }, 50);
                    }
                });

                // Mouse Wheel: Scroll horizontally when wheeling over the tab bar
                tabBar.addEventListener('wheel', (e) => {
                    if (e.deltaY !== 0 && tabBar.scrollWidth > tabBar.clientWidth) {
                        e.preventDefault();
                        tabBar.scrollLeft += e.deltaY;
                    }
                }, { passive: false });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupDragScroll);
            } else {
                setupDragScroll();
            }
        })();
    </script>
</x-admin_layout>