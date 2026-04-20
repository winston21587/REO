<x-admin_layout>
    <div class="max-w-7xl mx-auto py-8 animate-[fadeInUp_0.5s_ease-out]">

        <!-- Top Navigation & Header -->
        <div class="flex items-center mb-6 gap-4 p-4 bg-gradient-to-r from-white to-slate-50 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full blur-3xl opacity-50 -mr-16 -mt-16 pointer-events-none"></div>
            <div class="flex items-center gap-4 relative z-10 w-full">
                <a href="{{ $backUrl }}" class="flex items-center justify-center w-12 h-12 rounded-xl bg-white border border-slate-200 text-slate-400 hover:border-[#8B0000] hover:text-[#8B0000] hover:shadow-md transition-all duration-300 flex-shrink-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-extrabold uppercase tracking-widest border border-slate-200 shadow-sm">{{ $researchTitle->reoc_code ?? 'PENDING-ID' }}</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-extrabold uppercase tracking-widest border border-amber-100 flex items-center gap-1 shadow-sm">{{ $researchTitle->Status }}</span>
                    </div>
                    <h1 class="text-xl font-black text-slate-900 font-heading leading-tight tracking-tight truncate">{{ $researchTitle->Study_Protocol_title }}</h1>
                </div>
            </div>
        </div>

        @if($researchTitle->revisionLogs->isNotEmpty())
            <div class="mb-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open" class="w-full px-6 py-4 flex justify-between items-center bg-slate-50/50 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-history text-blue-500"></i>
                        <span class="text-xs font-extrabold text-blue-600 uppercase tracking-widest">Revision History Feedback</span>
                    </div>
                    <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" style="display: none;" x-transition>
                    <div class="p-6 space-y-6 border-t border-slate-100 bg-white max-h-[400px] overflow-y-auto custom-scrollbar">
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
                </div>
            </div>
        @endif

        @php
            $disapprovalFeedback = $researchTitle->feedbacks()->where('type', 'disapproval_remark')->first();
            $reviewerDecisions = $researchTitle->feedbacks()->where('type', 'reviewer_decision')->orderBy('created_at', 'desc')->get();
        @endphp

        @if($researchTitle->Status === 'Disapproved' && $disapprovalFeedback)
            <div
                class="mb-8 p-6 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-4 shadow-sm animate-[fadeInUp_0.5s_ease-out]">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                    <i class="fas fa-times-circle text-lg"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-red-900 mb-1">Protocol Disapproved</h4>
                    <p class="text-red-800 text-sm font-medium mb-3">This research protocol has been disapproved due to the
                        following reason:</p>
                    <div class="bg-white p-4 rounded-xl border border-red-100 shadow-sm">
                        <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $disapprovalFeedback->message }}</p>
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
                    'jpg' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
                    'jpeg' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
                    'png' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
                    'gif' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
                    'bmp' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
                    'webp' => ['icon' => 'fas fa-file-image', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
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
                    'uploaded_by_name' => $file->uploader ? ($file->uploader->first_name . ' ' . $file->uploader->last_name) : 'Unknown User',
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

            $firstFile = $originalFiles->first() ? $enrichFile($originalFiles->first(), 'Original') : ($activeFiles->first() ? $enrichFile($activeFiles->first(), 'Current') : null);
            $serveRoute = route('admin.serve_file', 'FILE_ID');
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{
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
                selectFile(file) { this.activeFile = file; }
             }">

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
                        <a :href="isOffice(activeFile) ? getOfficeUrl(activeFile) : getUrl(activeFile)" target="_blank"
                            class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all"
                            title="Open in new tab">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a :href="getUrl(activeFile)" download
                            class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all"
                            title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>

                <!-- Viewer pane: sticky behavior removed to support layout additions below -->
                <div class="bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-800 relative"
                    style="height: 70vh; min-height: 480px;">
                    <template x-if="activeFile && isPdf(activeFile)">
                        <iframe :src="getUrl(activeFile)" class="w-full h-full border-0 bg-white"
                            title="PDF Viewer"></iframe>
                    </template>
                    <template x-if="activeFile && isOffice(activeFile)">
                        <iframe :src="getOfficeUrl(activeFile)" class="w-full h-full border-0 bg-white"
                            title="Office Viewer"></iframe>
                    </template>
                    <template x-if="activeFile && isImage(activeFile)">
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-900 p-4 overflow-auto">
                            <img :src="getUrl(activeFile)" :alt="activeFile.filename"
                                class="max-w-full max-h-full object-contain rounded-lg shadow-lg" />
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
                </div>

                <!-- Below Viewer Details -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ subOpen: false, actOpen: false }">
                    <!-- Submission Details Accordion -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0 flex flex-col justify-start">
                        <button @click="subOpen = !subOpen" class="w-full flex justify-between items-center px-5 py-4 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-400"></i>
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Submission Details</span>
                            </div>
                            <i class="fas fa-chevron-up text-xs text-slate-400 transition-transform duration-300" :class="subOpen ? '' : 'rotate-180'"></i>
                        </button>
                        <div x-show="subOpen" x-transition>
                            <div class="p-5 border-t border-slate-100 space-y-4 bg-white">
                                <div class="flex justify-between items-start gap-3">
                                    <div class="flex items-center gap-2 text-blue-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-tag w-4 text-center"></i> Category
                                    </div>
                                    <div class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 text-right text-xs font-bold text-slate-700">
                                        {{ $researchTitle->Research_Category ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-start gap-3">
                                    <div class="flex items-center gap-2 text-emerald-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-flask w-4 text-center"></i> Type
                                    </div>
                                    <div class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 text-right text-xs font-bold text-slate-700">
                                        {{ $researchTitle->research_type ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center gap-3">
                                    <div class="flex items-center gap-2 text-purple-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-calendar-alt w-4 text-center"></i> Submitted
                                    </div>
                                    <div class="text-xs font-bold text-slate-800">
                                        {{ $researchTitle->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center gap-3">
                                    <div class="flex items-center gap-2 text-orange-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-users w-4 text-center"></i> Reviewers
                                    </div>
                                    <div class="text-xs font-bold text-slate-800 text-right">
                                        @if($reviewerAssignments->count() > 0)
                                            {{ $reviewerAssignments->map(fn($r) => $r->first_name . ' ' . $r->last_name)->join(', ') }}
                                        @else
                                            <span class="italic text-slate-400">None assigned</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex justify-between items-center gap-3">
                                    <div class="flex items-center gap-2 text-indigo-500 font-bold text-sm flex-shrink-0">
                                        <i class="fas fa-code-branch w-4 text-center"></i> Revisions
                                    </div>
                                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 text-indigo-700 text-xs font-bold">
                                        {{ $researchTitle->files->where('revision_number', '>', 0)->count() }} submitted
                                    </div>
                                </div>

                                @if($suggestedTypes->count() > 0)
                                    <div class="mt-6 pt-4 border-t border-slate-100">
                                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <i class="fas fa-lightbulb text-amber-500"></i> Suggested Review Types
                                        </p>
                                        <div class="space-y-2.5">
                                            @foreach($suggestedTypes as $sugg)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs font-extrabold text-slate-700">{{ $sugg['reviewer'] }}</span>
                                                    <span class="px-3 py-1 rounded-md border border-amber-200 text-amber-600 bg-amber-50 text-[10px] font-extrabold">{{ $sugg['type'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Activity Log Accordion -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0 flex flex-col justify-start">
                        <button @click="actOpen = !actOpen" class="w-full flex justify-between items-center px-5 py-4 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-history text-indigo-400"></i>
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Activity Log</span>
                            </div>
                            <i class="fas fa-chevron-up text-xs text-slate-400 transition-transform duration-300" :class="actOpen ? '' : 'rotate-180'"></i>
                        </button>
                        <div x-show="actOpen" x-transition>
                            <div class="p-5 border-t border-slate-100 bg-white">
                                <div class="relative pl-3 max-h-[420px] overflow-y-auto custom-scrollbar">
                                    <div class="absolute left-3 top-1 bottom-1 w-0.5 bg-slate-100"></div>
                                    <div class="space-y-4">
                                        @forelse($researchTitle->titleLogs as $log)
                                            <div class="flex gap-4 relative">
                                                <div class="w-6 h-6 rounded-full bg-slate-100 border-4 border-white flex-shrink-0 z-10 -ml-[11px] flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-indigo-300"></div>
                                                </div>
                                                <div class="pb-1">
                                                    <p class="text-sm font-bold text-slate-800 leading-tight block">{{ $log->action }}</p>
                                                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">{{ $log->description }}</p>
                                                    <p class="text-[9px] font-bold text-slate-400 mt-1.5 uppercase tracking-wider">{{ $log->created_at->format('M d, Y • h:i A') }}</p>
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

                    {{-- Researcher Classification Badge --}}
                    @if($researchTitle->project_type)
                        <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                            <div class="flex items-center gap-2">
                                @php
                                    $isFunded = $researchTitle->project_type === 'Funded Research';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $isFunded ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    <i class="fas {{ $isFunded ? 'fa-money-bill-wave' : 'fa-graduation-cap' }}"></i>
                                    {{ $researchTitle->project_type }}
                                </span>
                                @if($researchTitle->funding_type || $researchTitle->course_type)
                                    <span class="text-[10px] text-slate-400 font-semibold">
                                        {{ $researchTitle->funding_type ?? $researchTitle->course_type }}
                                    </span>
                                @endif
                            </div>
                            @if($researchTitle->Adviser)
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <i class="fas fa-user-tie text-slate-400 w-3"></i>
                                    <span class="font-semibold">Adviser:</span>
                                    <span>{{ $researchTitle->Adviser }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

            </div>

            <div class="lg:col-span-4 flex flex-col gap-4 pb-8">

                <!-- AI Prediction Card -->
                @if($researchTitle->ai_suggested_review_type)
                    <div class="bg-indigo-50 border border-indigo-100 p-5 rounded-2xl shadow-sm text-center flex-shrink-0 relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 text-indigo-100/50 transform rotate-12 transition-transform group-hover:rotate-18 duration-500 pointer-events-none">
                            <i class="fas fa-robot text-7xl"></i>
                        </div>
                        <div class="relative z-10 text-left">
                            <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider mb-2 flex items-center gap-1.5"><i class="fas fa-magic"></i> AI Predict Result</p>
                            <h4 class="text-lg font-black text-indigo-900 leading-tight">{{ $researchTitle->ai_suggested_review_type }}</h4>
                        </div>
                    </div>
                @endif

                <!-- ===== Reviewer File Remarks ===== -->
                @php
                    $totalRemarks = $allFileRemarks->flatten()->count();
                @endphp
                @if($totalRemarks > 0)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex-shrink-0">
                        <p
                            class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-comments text-indigo-400"></i>
                            Reviewer Remarks
                            <span
                                class="ml-auto bg-indigo-100 text-indigo-700 text-[9px] font-black px-2 py-0.5 rounded-full">{{ $totalRemarks }}</span>
                        </p>

                        @php
                            // Group remarks by reviewer for a cleaner view
                            $remarksByReviewer = $allFileRemarks->flatten()->groupBy('reviewer_id');
                        @endphp

                        <div class="space-y-4 max-h-[420px] overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($remarksByReviewer as $reviewerId => $remarks)
                                @php
                                    $reviewer = $remarks->first()->reviewer;
                                    $reviewerName = $reviewer ? ($reviewer->first_name . ' ' . $reviewer->last_name) : 'Unknown Reviewer';
                                    $initial = $reviewer ? strtoupper(substr($reviewer->first_name, 0, 1)) : '?';
                                @endphp
                                <div class="border border-slate-100 rounded-xl overflow-hidden">
                                    {{-- Reviewer header --}}
                                    <div class="flex items-center gap-2.5 px-3 py-2.5 bg-indigo-50 border-b border-indigo-100">
                                        <div
                                            class="w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] font-black flex-shrink-0">
                                            {{ $initial }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-indigo-900 truncate">{{ $reviewerName }}</p>
                                            <p class="text-[9px] text-indigo-500 font-medium">{{ $remarks->count() }} file
                                                remark(s)</p>
                                        </div>
                                    </div>
                                    {{-- Per-file remarks --}}
                                    <div class="divide-y divide-slate-50">
                                        @foreach($remarks as $remark)
                                            @php
                                                // Find the file this remark belongs to
                                                $remarkFile = $researchTitle->files->firstWhere('id', $remark->researcher_file_id)
                                                    ?? $researchTitle->adminFiles->firstWhere('id', $remark->researcher_file_id);
                                            @endphp
                                            <div class="px-3 py-2.5">
                                                @if($remarkFile)
                                                    <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mb-1 truncate"
                                                        title="{{ $remarkFile->filename }}">
                                                        <i class="fas fa-file-alt text-slate-300 mr-1"></i>
                                                        {{ $remarkFile->category ?? $remarkFile->filename }}
                                                    </p>
                                                @endif
                                                <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-wrap">
                                                    {{ $remark->remarks }}</p>
                                                <p class="text-[9px] text-slate-400 mt-1">
                                                    {{ $remark->updated_at->format('M d, Y • h:i A') }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif







                <!-- Version Timeline Accordion -->
                @if($hasRevisions)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0" x-data="{ vtOpen: false }">
                        <button @click="vtOpen = !vtOpen" class="w-full flex justify-between items-center px-5 py-4 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                            <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-code-branch text-indigo-400"></i> Version Timeline
                            </span>
                            <i class="fas fa-chevron-up text-xs text-slate-400 transition-transform duration-300" :class="vtOpen ? '' : 'rotate-180'"></i>
                        </button>
                        <div x-show="vtOpen" x-transition>
                            <div class="p-5 border-t border-slate-100 bg-white">
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
                                                    {{ $originalFiles->count() }} doc(s)</p>
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
                                                        {{ $files->count() }} doc(s)</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

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

                        <template x-if="reviewerDocs.length > 0">
                            <button @click="activeTab = 'reviewer_docs'"
                                :class="activeTab === 'reviewer_docs' ? 'bg-white border-b-2 border-slate-700 text-slate-900' : 'text-slate-500 hover:text-slate-700'"
                                class="flex items-center gap-1.5 px-3 py-2 text-xs font-bold whitespace-nowrap transition-all rounded-t-lg">
                                <i class="fas fa-user-edit text-slate-500"></i> Reports
                            </button>
                        </template>

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
                                        {{ $revNum }}</p>
                                </div>
                                <template x-for="group in revisions['{{ $revNum }}']" :key="group.category">
                                    <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2"
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

                        <!-- Reviewer Uploads tab -->
                        <div x-show="activeTab === 'reviewer_docs'" style="display:none;">
                            <div class="px-4 py-2.5 bg-slate-100/60 border-b border-slate-200 mb-2">
                                <p class="text-xs font-extrabold text-slate-600 uppercase tracking-wider">Reviewer
                                    Uploads</p>
                            </div>
                            <template x-for="group in reviewerDocs" :key="group.category">
                                <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2"
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
                                                :class="activeFile && activeFile.id === file.id ? 'bg-[#8B0000] border-l-4 border-[#8B0000] text-white' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all bg-white border-t border-slate-100">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                                    :class="file.bg">
                                                    <i :class="[file.icon, file.color]"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold truncate"
                                                        :class="activeFile && activeFile.id === file.id ? 'text-slate-800' : 'text-slate-800'"
                                                        x-text="file.filename"></p>
                                                    <p class="text-[10px] text-slate-500 font-medium mt-0.5"
                                                        x-text="'Uploaded by: ' + file.uploaded_by_name"></p>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <p class="text-[10px] text-slate-400"
                                                            x-text="'On: ' + file.uploaded_at"></p>
                                                        <span x-show="file.suggested_review_type"
                                                            class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold uppercase bg-emerald-50 text-emerald-600 border border-emerald-200"
                                                            x-text="file.suggested_review_type"></span>
                                                    </div>
                                                    <p x-show="file.remarks"
                                                        class="text-[10px] italic text-slate-500 mt-1 line-clamp-2"
                                                        x-text="'&quot;' + file.remarks + '&quot;'"></p>
                                                </div>
                                                <div x-show="activeFile && activeFile.id === file.id"
                                                    class="w-2 h-2 rounded-full bg-[#8B0000] flex-shrink-0"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Current Documents tab -->
                        @if($hasRevisions && $activeFiles->isNotEmpty())
                            <div x-show="activeTab === 'current'" style="display:none;">
                                <div class="px-4 py-2.5 bg-red-50/60 border-b border-red-100 mb-2">
                                    <p class="text-xs font-extrabold text-[#8B0000] uppercase tracking-wider">Latest Version
                                        of Each Document</p>
                                </div>
                                <template x-for="group in activeFiles" :key="group.category">
                                    <div class="mb-2 border border-slate-100 rounded-lg overflow-hidden mx-2"
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



            </div>{{-- end sidebar --}}
        </div>{{-- end grid --}}
    </div>
</x-admin_layout>