<x-user_layout>
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

        @php
            $canSubmit = in_array($researchTitle->Status, ['Waiting for Revision', 'Incomplete']);
            $isRevision = $researchTitle->Status === 'Waiting for Revision';
            $submitLabel = $isRevision ? 'Submit Revisions' : 'Submit Corrections';
            $submitIcon = $isRevision ? 'fa-paper-plane' : 'fa-check-circle';

            $statusLower = strtolower($researchTitle->Status ?? '');
            $statusTextColor = match(true) {
                str_contains($statusLower, 'modifications required') || str_contains($statusLower, 'disapproved') || str_contains($statusLower, 'major') => 'text-rose-700',
                str_contains($statusLower, 'waiting') || str_contains($statusLower, 'minor') || str_contains($statusLower, 'incomplete') => 'text-amber-700',
                str_contains($statusLower, 'reviewed') || str_contains($statusLower, 'approved') => 'text-emerald-700',
                str_contains($statusLower, 'under review') => 'text-indigo-700',
                str_contains($statusLower, 'reviewer assigned') => 'text-blue-700',
                default => 'text-slate-700'
            };

            $allFiles = $researchTitle->files->merge($researchTitle->adminFiles ?? collect());
            $letters = $allFiles->whereIn('filetype', [
                'Result of Review (Admin Generated)', 
                'recommendation letter', 
                'Archived Result of Review',
                'Approval Letter',
                'certificate'
            ])->sortByDesc('created_at');
            $protocolDocs = $researchTitle->files->whereNotIn('filetype', [
                'Result of Review (Admin Generated)', 
                'recommendation letter', 
                'Archived Result of Review',
                'Approval Letter',
                'certificate'
            ]);

            $originalFiles = $protocolDocs->whereNull('revision_number')->sortByDesc('created_at');
            $archivedFiles = $protocolDocs->where('revision_number', '>', 0)->sortByDesc('created_at');
            $revisionFolders = $archivedFiles->groupBy('revision_number')->sortKeys();

            // Consolidated latest version of each category (excluding drafts -1)
            $activeFiles = $protocolDocs->where('revision_number', '!=', -1)
                ->groupBy('category')
                ->map(function ($categoryFiles) {
                    $maxRev = $categoryFiles->max('revision_number');
                    return $categoryFiles->where('revision_number', $maxRev);
                })
                ->flatten()
                ->sortByDesc('created_at');

            // Draft workspace files (revision_number = -1)
            $draftFiles = $protocolDocs->where('revision_number', -1)->sortByDesc('created_at');

            $hasRevisions = $revisionFolders->isNotEmpty();
            $hasDraftFiles = $draftFiles->isNotEmpty();
            $nextRevisionNumber = ($protocolDocs->where('revision_number', '>', 0)->max('revision_number') ?? 0) + 1;
            $canReplace = in_array($researchTitle->Status ?? '', ['Incomplete', 'Pending', 'Pending (Initial Intake)']);

            // Enriched file mapper for JavaScript / Alpine
            $enrichFile = function ($file, $label) {
                $ext = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
                if (!$ext) {
                    $ext = strtolower($file->filetype ?? '');
                }
                $icons = [
                    'pdf'  => ['icon' => 'fas fa-file-pdf', 'color' => 'text-rose-700/80', 'bg' => 'bg-rose-50/60 text-rose-700/80'],
                    'doc'  => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-700/80', 'bg' => 'bg-blue-50/60 text-blue-700/80'],
                    'docx' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-700/80', 'bg' => 'bg-blue-50/60 text-blue-700/80'],
                    'ppt'  => ['icon' => 'fas fa-file-powerpoint', 'color' => 'text-amber-700/80', 'bg' => 'bg-amber-50/60 text-amber-700/80'],
                    'pptx' => ['icon' => 'fas fa-file-powerpoint', 'color' => 'text-amber-700/80', 'bg' => 'bg-amber-50/60 text-amber-700/80'],
                    'xls'  => ['icon' => 'fas fa-file-excel', 'color' => 'text-emerald-700/80', 'bg' => 'bg-emerald-50/60 text-emerald-700/80'],
                    'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => 'text-emerald-700/80', 'bg' => 'bg-emerald-50/60 text-emerald-700/80'],
                    'jpg'  => ['icon' => 'fas fa-file-image', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'],
                    'jpeg' => ['icon' => 'fas fa-file-image', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'],
                    'png'  => ['icon' => 'fas fa-file-image', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'],
                    'webp' => ['icon' => 'fas fa-file-image', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'],
                ];
                $attrs = $icons[$ext] ?? ['icon' => 'fas fa-file-alt', 'color' => 'text-slate-500', 'bg' => 'bg-slate-100/70 text-slate-600'];

                $remarksList = [];
                if (isset($file->reviewerRemarks) && $file->reviewerRemarks->isNotEmpty()) {
                    foreach ($file->reviewerRemarks as $rem) {
                        $reviewerName = $rem->reviewer ? ($rem->reviewer->first_name . ' ' . $rem->reviewer->last_name) : 'Reviewer';
                        $remarksList[] = [
                            'reviewer' => $reviewerName,
                            'text' => $rem->remarks,
                            'date' => $rem->updated_at ? $rem->updated_at->timezone('Asia/Manila')->format('M d, Y • h:i A') : ''
                        ];
                    }
                }

                return [
                    'id' => $file->id,
                    'filename' => $file->filename,
                    'label' => $file->category ?? ($file->filetype === 'certificate' ? 'Ethics Clearance Certificate' : ($file->filetype === 'Approval Letter' ? 'Cover Letter of Approval' : 'Uncategorized')),
                    'group' => $label,
                    'ext' => $ext,
                    'revision_number' => $file->revision_number,
                    'uploaded_at' => $file->created_at ? $file->created_at->format('M d, Y') : '',
                    'icon' => $attrs['icon'],
                    'color' => $attrs['color'],
                    'bg' => $attrs['bg'],
                    'remarks' => $remarksList,
                    'has_remarks' => !empty($remarksList),
                    'public_url' => asset($file->filepath),
                    'delete_url' => route('delete.revision.document', $file->id),
                ];
            };

            $groupFiles = function ($collection, $groupLabel) use ($enrichFile) {
                $grouped = [];
                foreach ($collection as $f) {
                    $cat = $f->category;
                    if (!$cat) {
                        if ($f->filetype === 'certificate') {
                            $cat = 'Clearance Certificate';
                        } elseif ($f->filetype === 'Approval Letter') {
                            $cat = 'Approval Letter';
                        } elseif (str_contains($f->filetype ?? '', 'Result of Review')) {
                            $cat = 'Result of Review';
                        } else {
                            $cat = 'Uncategorized';
                        }
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
            $jsDraft = $groupFiles($draftFiles, 'Draft');

            $jsRevisions = [];
            foreach ($revisionFolders as $revNum => $files) {
                $jsRevisions[$revNum] = $groupFiles($files, "Revision $revNum");
            }

            // Determine default starting active file: prioritize Application Form if available
            $appFormFile = $originalFiles->firstWhere('category', 'Application Form') 
                ?? $activeFiles->firstWhere('category', 'Application Form');
            $firstFile = $appFormFile 
                ? $enrichFile($appFormFile, $appFormFile->revision_number ? 'Current' : 'Original')
                : ($originalFiles->first() 
                    ? $enrichFile($originalFiles->first(), 'Original') 
                    : ($activeFiles->first() ? $enrichFile($activeFiles->first(), 'Current') : ($letters->first() ? $enrichFile($letters->first(), 'Letters') : null)));
            
            $serveRoute = route('researcher.serve_file', 'FILE_ID');

            // Collect all file reviewer remarks for aggregated review accordion
            $allRemarks = collect();
            foreach ($protocolDocs as $doc) {
                if (isset($doc->reviewerRemarks) && $doc->reviewerRemarks->isNotEmpty()) {
                    foreach ($doc->reviewerRemarks as $rem) {
                        $allRemarks->push([
                            'file_id' => $doc->id,
                            'filename' => $doc->filename,
                            'category' => $doc->category ?? 'Uncategorized',
                            'reviewer' => $rem->reviewer ? ($rem->reviewer->first_name . ' ' . $rem->reviewer->last_name) : 'Reviewer',
                            'initial' => $rem->reviewer ? strtoupper(substr($rem->reviewer->first_name, 0, 1)) : 'R',
                            'text' => $rem->remarks,
                            'date' => $rem->updated_at ? $rem->updated_at->timezone('Asia/Manila')->format('M d, Y • h:i A') : ''
                        ]);
                    }
                }
            }
        @endphp

        <!-- Top Institutional Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs px-4 py-2 sm:px-5 sm:py-2.5 relative overflow-hidden shrink-0">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5 sm:gap-3">
                
                <!-- Left: Back Button + Code + Status + Title -->
                <div class="flex items-start sm:items-center gap-3.5 min-w-0 flex-1">
                    <a href="{{ route('home') }}" 
                       class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center shrink-0 focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs active:scale-95 cursor-pointer"
                       title="Back to Titles" aria-label="Back to Titles">
                        <i class="fas fa-arrow-left text-xs sm:text-sm" aria-hidden="true"></i>
                    </a>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono text-xs font-semibold tabular-nums text-slate-700 bg-slate-50 border border-slate-200 px-2.5 py-0.5 rounded-md shadow-2xs">
                                {{ $researchTitle->reoc_code ?? ('#'.str_pad($researchTitle->id, 5, '0', STR_PAD_LEFT)) }}
                            </span>
                            <span class="inline-flex items-center text-xs font-bold uppercase tracking-wider {{ $statusTextColor }}">
                                {{ $researchTitle->Status }}
                            </span>
                        </div>
                        <h1 class="font-heading font-bold text-base sm:text-lg text-slate-900 tracking-tight leading-tight mt-0.5 truncate" title="{{ $researchTitle->Study_Protocol_title }}">
                            {{ $researchTitle->Study_Protocol_title }}
                        </h1>
                    </div>
                </div>

                <!-- Right: Researcher Metadata & Quick Actions -->
                <div class="flex flex-col sm:flex-row lg:flex-col sm:items-center lg:items-end justify-between gap-1.5 shrink-0 pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                    
                    <!-- Metadata Info Row -->
                    <div class="flex items-center gap-2.5 text-xs text-slate-500 flex-wrap">
                        <span class="tabular-nums font-medium text-slate-500">
                            <i class="far fa-calendar-alt text-slate-400 mr-1" aria-hidden="true"></i>Submitted {{ $researchTitle->created_at->format('M d, Y') }}
                        </span>
                        @if(!empty($researchTitle->Review_Type) && !in_array($researchTitle->Review_Type, ['Unassigned', 'N/A']))
                            <span class="text-slate-300">·</span>
                            <span class="font-medium text-slate-600 bg-slate-50 border border-slate-200/80 text-[11px] px-2 py-0.5 rounded-md">
                                {{ $researchTitle->Review_Type }}
                            </span>
                        @endif
                    </div>

                    <!-- Actions Pill Group -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <button type="button" @click="openDrawer('details')" 
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-slate-700 hover:text-slate-900 text-xs font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] h-8 cursor-pointer shadow-2xs group active:scale-95"
                                title="View Submission Details">
                            <i class="fas fa-info-circle text-xs text-slate-400 group-hover:text-[#8B0000] transition-colors" aria-hidden="true"></i>
                            <span>Details</span>
                        </button>

                        <button type="button" @click="openDrawer('activity')" 
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-slate-700 hover:text-slate-900 text-xs font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] h-8 cursor-pointer shadow-2xs group active:scale-95"
                                title="View Activity Log">
                            <i class="fas fa-history text-xs text-slate-400 group-hover:text-[#8B0000] transition-colors" aria-hidden="true"></i>
                            <span>Activity Log</span>
                        </button>

                        @if($canSubmit)
                            <button type="button" onclick="document.getElementById('revisionModal').classList.remove('hidden')"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-xl bg-brand-primary hover:bg-brand-secondary text-white text-xs font-bold transition-all shadow-md shadow-red-950/20 hover:shadow-red-950/30 hover:-translate-y-0.5 active:scale-95 h-8 cursor-pointer">
                                <i class="fas {{ $submitIcon }} text-xs" aria-hidden="true"></i>
                                <span>{{ $submitLabel }}</span>
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-300 text-emerald-900 rounded-2xl flex items-center justify-between gap-3 text-xs shadow-2xs shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 bg-rose-50 border border-rose-300 text-rose-900 rounded-2xl flex items-center justify-between gap-3 text-xs shadow-2xs shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-rose-600"></i>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-rose-700 hover:text-rose-950">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Main 12-Column File Workspace Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-stretch flex-1 min-h-0 lg:h-full" 
             x-data="{
                activeFile: {{ $firstFile ? json_encode($firstFile) : 'null' }},
                activeTab: '{{ $letters->isNotEmpty() && $originalFiles->isEmpty() ? 'letters' : 'original' }}',
                originalFiles: {{ json_encode($jsOriginal) }},
                activeFiles: {{ json_encode($jsActive) }},
                letters: {{ json_encode($jsLetters) }},
                revisions: {{ json_encode($jsRevisions) }},
                draftFiles: {{ json_encode($jsDraft) }},
                hasRevisions: {{ $hasRevisions ? 'true' : 'false' }},
                hasDraftFiles: {{ $hasDraftFiles ? 'true' : 'false' }},
                isWaitingForRevision: {{ $isRevision ? 'true' : 'false' }},
                canReplace: {{ $canReplace ? 'true' : 'false' }},
                serveRoute: '{{ $serveRoute }}',
                isUploading: false,
                uploadCategory: '',
                replaceFileId: null,

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
                    return ['localhost', '127.0.0.1'].includes(window.location.hostname) || window.location.hostname.endsWith('.test') || window.location.hostname.endsWith('.local');
                },
                selectFile(file) { this.activeFile = file; },

                // Upload handling (Replace, Missing, Draft)
                async handleFileUpload(fileInput, url, extraFields = {}) {
                    const file = fileInput.files[0];
                    if (!file) return;

                    this.isUploading = true;
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');
                    for (const [key, val] of Object.entries(extraFields)) {
                        formData.append(key, val);
                    }

                    const progressModal = document.getElementById('upload-progress-modal');
                    const progressBar = document.getElementById('upload-progress-bar');
                    const percentageText = document.getElementById('upload-percentage');
                    const sizeText = document.getElementById('upload-size');

                    if (progressModal) {
                        progressBar.style.width = '0%';
                        percentageText.textContent = '0%';
                        sizeText.textContent = '0 KB / 0 KB';
                        progressModal.classList.remove('hidden');
                    }

                    const formatBytes = (bytes) => {
                        if (bytes === 0) return '0 Bytes';
                        const k = 1024;
                        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                    };

                    try {
                        const xhr = new XMLHttpRequest();
                        const uploadPromise = new Promise((resolve, reject) => {
                            xhr.upload.addEventListener('progress', (e) => {
                                if (e.lengthComputable) {
                                    const percent = Math.round((e.loaded / e.total) * 100);
                                    if (progressBar) progressBar.style.width = percent + '%';
                                    if (percentageText) percentageText.textContent = percent + '%';
                                    if (sizeText) sizeText.textContent = `${formatBytes(e.loaded)} / ${formatBytes(e.total)}`;
                                }
                            });
                            xhr.onload = () => {
                                if (xhr.status >= 200 && xhr.status < 300) {
                                    try {
                                        resolve(JSON.parse(xhr.responseText));
                                    } catch(e) {
                                        resolve({ success: true });
                                    }
                                } else {
                                    let errorMsg = 'Upload failed';
                                    try {
                                        const err = JSON.parse(xhr.responseText);
                                        errorMsg = err.message || err.error || errorMsg;
                                    } catch(e) {}
                                    reject(new Error(errorMsg));
                                }
                            };
                            xhr.onerror = () => reject(new Error('Network error during upload'));
                        });

                        xhr.open('POST', url);
                        xhr.setRequestHeader('Accept', 'application/json');
                        xhr.send(formData);

                        const data = await uploadPromise;
                        if (data.success || data.file) {
                            if (progressModal) progressModal.classList.add('hidden');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Document uploaded successfully! Updating workspace...',
                                timer: 1200,
                                showConfirmButton: false
                            });
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            throw new Error(data.error || 'Server rejected file.');
                        }
                    } catch (error) {
                        if (progressModal) progressModal.classList.add('hidden');
                        Swal.fire({ icon: 'error', title: 'Upload Failed', text: error.message });
                    } finally {
                        this.isUploading = false;
                        fileInput.value = '';
                    }
                },

                // Action: Replace Existing File
                replaceExisting(fileId, event) {
                    this.handleFileUpload(event.target, '{{ route('update.file', $researchTitle->id) }}', {
                        file_id: fileId,
                        _method: 'PUT'
                    });
                },

                // Action: Upload Missing Requirement
                uploadMissing(category, event) {
                    this.handleFileUpload(event.target, '{{ route('add.missing.file', $researchTitle->id) }}', {
                        category: category
                    });
                },

                // Action: Upload to Draft Workspace
                uploadDraft(category, event) {
                    this.handleFileUpload(event.target, '{{ route('upload.revision.document', $researchTitle->id) }}', {
                        category: category
                    });
                },

                // Action: Delete from Draft Workspace
                async deleteDraft(fileId, deleteUrl) {
                    const result = await Swal.fire({
                        title: 'Remove Draft Document?',
                        text: 'Are you sure you want to remove this document from your draft workspace?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, remove',
                        cancelButtonText: 'Cancel'
                    });

                    if (!result.isConfirmed) return;

                    try {
                        const response = await fetch(deleteUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ _method: 'DELETE' })
                        });

                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({ icon: 'success', title: 'Removed', text: 'Document removed from draft workspace.', timer: 1000, showConfirmButton: false });
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            throw new Error(data.error || 'Failed to remove file.');
                        }
                    } catch(e) {
                        Swal.fire({ icon: 'error', title: 'Error', text: e.message });
                    }
                }
             }">

            <!-- ===== LEFT COLUMN — Document Viewer Console (7 Cols) ===== -->
            <div class="lg:col-span-7 flex flex-col gap-2 min-h-0 h-full overflow-hidden">

                <!-- Viewer Header & Controls Bar -->
                <div class="bg-white px-3.5 py-1.5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between gap-2.5 shrink-0">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 border border-slate-200/80 shadow-2xs"
                             :class="activeFile ? activeFile.bg : 'bg-slate-50 text-slate-400'">
                            <i :class="activeFile ? [activeFile.icon, activeFile.color] : 'fas fa-file text-slate-400'" class="text-xs" aria-hidden="true"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-semibold text-slate-950 truncate tracking-tight"
                                      x-text="activeFile ? activeFile.label : 'No document selected'"></span>
                                <template x-if="activeFile && activeFile.revision_number && activeFile.revision_number > 0">
                                    <span class="text-[10px] font-semibold text-indigo-800 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded-md"
                                          x-text="'Revision ' + activeFile.revision_number"></span>
                                </template>
                                <template x-if="activeFile && activeFile.revision_number === -1">
                                    <span class="text-[10px] font-semibold text-orange-800 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded-md">Drafting Version</span>
                                </template>
                                <template x-if="activeFile && !activeFile.revision_number && activeFile.group !== 'Letters'">
                                    <span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200/70">Original</span>
                                </template>
                                <template x-if="activeFile && activeFile.group === 'Letters'">
                                    <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">Official Letter</span>
                                </template>
                            </div>
                            <p class="text-xs text-slate-500 truncate font-mono font-medium max-w-[240px] sm:max-w-sm mt-0.5"
                               x-text="activeFile ? activeFile.filename : ''"></p>
                        </div>
                    </div>

                    <!-- Viewer Actions: Open Tab & Download -->
                    <div class="flex items-center gap-1.5 shrink-0" x-show="activeFile">
                        <a :href="isOffice(activeFile) && !isLocalHost() ? getOfficeUrl(activeFile) : getUrl(activeFile)" target="_blank"
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-[#8B0000] hover:bg-slate-50 hover:border-slate-300 transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs active:scale-95 cursor-pointer"
                            title="Open in new window" aria-label="Open document in new window">
                            <i class="fas fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                        </a>
                        <a :href="getUrl(activeFile) + '?download=1'" download
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-[#8B0000] hover:bg-slate-50 hover:border-slate-300 transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs active:scale-95 cursor-pointer"
                            title="Download document" aria-label="Download document">
                            <i class="fas fa-download text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <!-- Document Frame Container -->
                <div id="document-preview-container" class="bg-slate-100/70 rounded-2xl overflow-hidden border border-slate-200/90 shadow-2xs relative flex-1 min-h-0 h-[360px] lg:h-full">
                    <template x-if="activeFile && isPdf(activeFile)">
                        <iframe :src="getUrl(activeFile)" class="w-full h-full border-0 bg-white"
                            title="PDF Document Viewer"></iframe>
                    </template>
                    <template x-if="activeFile && isOffice(activeFile)">
                        <div class="w-full h-full">
                            <template x-if="isLocalHost()">
                                <div class="w-full h-full flex items-center justify-center p-6 bg-slate-50">
                                    <div class="max-w-sm w-full bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-6 text-center">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-200 flex items-center justify-center mx-auto mb-3 text-blue-700 shadow-2xs">
                                            <i class="fas fa-file-word text-xl" aria-hidden="true"></i>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-800 border border-blue-200 mb-2 uppercase tracking-wider">
                                            Office Document
                                        </span>
                                        <h3 class="text-xs font-bold text-slate-900 mb-1 break-all" x-text="activeFile.filename"></h3>
                                        <p class="text-[11px] text-slate-500 mb-4" x-text="activeFile.label + ' • ' + activeFile.uploaded_at"></p>
                                        <div class="flex items-center justify-center gap-2">
                                            <a :href="getUrl(activeFile) + '?download=1'" download
                                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-brand-primary hover:bg-brand-secondary text-white text-xs font-semibold rounded-xl shadow-2xs transition-all active:scale-95 cursor-pointer">
                                                <i class="fas fa-download text-xs"></i>
                                                <span>Download Document</span>
                                            </a>
                                            <a :href="getUrl(activeFile)" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 shadow-2xs transition-all active:scale-95 cursor-pointer">
                                                <i class="fas fa-arrow-up-right-from-square text-xs text-slate-400"></i>
                                                <span>Open Raw</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!isLocalHost()">
                                <iframe :src="getOfficeUrl(activeFile)" class="w-full h-full border-0 bg-white"
                                    title="Office Document Viewer"></iframe>
                            </template>
                        </div>
                    </template>
                    <template x-if="activeFile && isImage(activeFile)">
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-50/90 p-4 overflow-auto">
                            <img :src="getUrl(activeFile)" :alt="activeFile.filename"
                                class="max-w-full max-h-full object-contain rounded-lg shadow-sm border border-slate-200" />
                        </div>
                    </template>
                    <template x-if="!activeFile">
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-50/80">
                            <div class="text-center p-6 max-w-sm">
                                <div class="w-10 h-10 bg-white rounded-xl border border-slate-200 flex items-center justify-center mx-auto mb-2 text-slate-400 shadow-2xs">
                                    <i class="fas fa-file-contract text-base" aria-hidden="true"></i>
                                </div>
                                <h3 class="text-xs font-semibold text-slate-800 mb-0.5">No Document Selected</h3>
                                <p class="text-[11px] text-slate-500 font-medium">Choose a document category from the panel on the right to preview.</p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Contextual Reviewer Remarks Callout (Appears below viewer when active file has remarks) -->
                <div x-show="activeFile && activeFile.has_remarks" style="display: none;" 
                     class="bg-amber-50/60 border border-amber-200/80 rounded-2xl p-2.5 shadow-2xs shrink-0">
                    <div class="flex items-center gap-2 mb-1.5">
                        <i class="fas fa-exclamation-circle text-amber-600 text-xs"></i>
                        <span class="text-xs font-semibold text-amber-950 uppercase tracking-wider">Reviewer Action Required on this Document</span>
                    </div>
                    <div class="space-y-1.5 max-h-24 overflow-y-auto custom-scrollbar">
                        <template x-for="rem in (activeFile ? activeFile.remarks : [])" :key="rem.reviewer + rem.date">
                            <div class="bg-white p-2.5 rounded-xl border border-amber-200 text-xs">
                                <div class="flex justify-between items-center text-[10px] text-slate-500 font-bold mb-0.5">
                                    <span class="text-amber-900" x-text="rem.reviewer"></span>
                                    <span x-text="rem.date" class="tabular-nums"></span>
                                </div>
                                <p class="text-slate-800 whitespace-pre-wrap leading-relaxed" x-text="rem.text"></p>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- ===== RIGHT COLUMN — Document Ledger & Categories (5 Cols) ===== -->
            <div class="lg:col-span-5 flex flex-col gap-2 min-h-0 h-full overflow-hidden">

                <!-- Stage-Specific Remarks Callout (Deliberation Notes or Admin Deficiency) -->
                @if($stageRemark)
                    @php
                        $isRevStage = $researchTitle->Status === 'Waiting for Revision';
                        $missingDocs = is_array($stageRemark->missing_requirements) ? $stageRemark->missing_requirements : (json_decode($stageRemark->missing_requirements ?? '[]', true) ?? []);
                    @endphp
                    <div class="bg-white rounded-2xl shadow-2xs border border-slate-300/90 overflow-hidden shrink-0" x-data="{ stageOpen: true }">
                        <button type="button" @click="stageOpen = !stageOpen" 
                                class="w-full flex justify-between items-center px-3.5 py-2 {{ $isRevStage ? 'bg-indigo-50/80 hover:bg-indigo-100/80 text-indigo-950' : 'bg-amber-50/80 hover:bg-amber-100/80 text-amber-950' }} transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                            <div class="flex items-center gap-2">
                                <i class="fas {{ $isRevStage ? 'fa-file-signature text-indigo-600' : 'fa-exclamation-triangle text-amber-600' }} text-xs" aria-hidden="true"></i>
                                <span class="text-xs font-bold uppercase tracking-wider">
                                    {{ $isRevStage ? 'Panel Deliberation Notes' : 'Admin Deficiency Remarks' }}
                                </span>
                            </div>
                            <i class="fas fa-chevron-up text-xs text-slate-500 transition-transform duration-200" :class="stageOpen ? '' : 'rotate-180'" aria-hidden="true"></i>
                        </button>

                        <div x-show="stageOpen" style="display: none;" x-transition class="p-3 border-t border-slate-200 bg-white max-h-48 overflow-y-auto custom-scrollbar space-y-2">
                            @if($stageRemark->message)
                                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 whitespace-pre-wrap leading-relaxed">
                                    {{ $stageRemark->message }}
                                </div>
                            @endif

                            @if(!empty($missingDocs))
                                <div class="bg-amber-50/60 rounded-xl border border-amber-200 p-2.5">
                                    <p class="text-[10px] font-bold text-amber-900 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                        <i class="fas fa-list-check"></i> Action Required Documents
                                    </p>
                                    <ul class="space-y-1">
                                        @foreach($missingDocs as $doc)
                                            <li class="text-xs text-slate-800 flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                                <span class="font-medium">{{ $doc }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Aggregated Reviewer Remarks Accordion -->
                @if($allRemarks->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-2xs border border-slate-300/90 overflow-hidden shrink-0" x-data="{ rrOpen: false }">
                        <button type="button" @click="rrOpen = !rrOpen" 
                                class="w-full flex justify-between items-center px-3.5 py-2 bg-slate-50 hover:bg-slate-100 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-comments text-indigo-600 text-xs" aria-hidden="true"></i>
                                <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Reviewer Remarks</span>
                                <span class="bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full tabular-nums">{{ $allRemarks->count() }}</span>
                            </div>
                            <i class="fas fa-chevron-up text-xs text-slate-500 transition-transform duration-200" :class="rrOpen ? '' : 'rotate-180'" aria-hidden="true"></i>
                        </button>

                        <div x-show="rrOpen" style="display: none;" x-transition>
                            <div class="p-3 space-y-2.5 max-h-[260px] overflow-y-auto custom-scrollbar">
                                @foreach($allRemarks->groupBy('reviewer') as $reviewerName => $remarks)
                                    <div class="border border-slate-300/80 rounded-xl overflow-hidden bg-white shadow-2xs">
                                        <div class="flex items-center gap-2.5 px-3 py-1.5 bg-slate-50 border-b border-slate-200">
                                            <div class="w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                                {{ $remarks->first()['initial'] }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-900 truncate">{{ $reviewerName }}</p>
                                                <p class="text-[10px] text-slate-500 font-medium">{{ $remarks->count() }} remark(s)</p>
                                            </div>
                                        </div>
                                        <div class="divide-y divide-slate-100">
                                            @foreach($remarks as $rem)
                                                <div class="p-2.5">
                                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5 truncate">
                                                        <i class="fas fa-file-alt mr-1"></i>{{ $rem['category'] }}
                                                    </p>
                                                    <p class="text-xs text-slate-800 leading-relaxed whitespace-pre-wrap">{{ $rem['text'] }}</p>
                                                    <p class="text-[10px] font-mono tabular-nums text-slate-500 mt-0.5">{{ $rem['date'] }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Version History Timeline Accordion -->
                @if($hasRevisions)
                    <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden shrink-0" x-data="{ vtOpen: false }">
                        <button type="button" @click="vtOpen = !vtOpen" 
                                class="w-full flex justify-between items-center px-3.5 py-2 bg-white hover:bg-slate-50/80 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-code-branch text-indigo-600 text-xs" aria-hidden="true"></i>
                                <span class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Version History</span>
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
                    <div class="p-1 border-b border-slate-100 bg-slate-50/50 shrink-0 relative" x-data="{ revDropdownOpen: false }">
                        <div id="document-tab-bar" class="flex gap-1 overflow-x-auto custom-scrollbar p-0.5 bg-slate-100/80 rounded-xl border border-slate-200/80 cursor-grab select-none">
                            @if($letters->isNotEmpty())
                                <button type="button" @click="activeTab = 'letters'; if (letters.length > 0 && letters[0].files.length > 0 && (!activeFile || activeFile.group !== 'Letters')) { selectFile(letters[0].files[0]); }"
                                    :class="activeTab === 'letters' ? 'bg-white text-slate-950 shadow-2xs border border-slate-200/90 font-semibold' : 'text-slate-500 hover:text-slate-800 hover:bg-white/40 font-medium'"
                                    class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0">
                                    <i class="fas fa-stamp text-xs" :class="activeTab === 'letters' ? 'text-[#8B0000]' : 'text-slate-400'"></i>
                                    <span>Letters</span>
                                    <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded-full tabular-nums"
                                          :class="activeTab === 'letters' ? 'bg-slate-100 text-slate-700 border border-slate-200/80' : 'bg-slate-200/70 text-slate-500'">
                                        {{ $letters->count() }}
                                    </span>
                                </button>
                            @endif

                            <button type="button" @click="activeTab = 'original'; if (originalFiles.length > 0 && originalFiles[0].files.length > 0 && (!activeFile || activeFile.group !== 'Original')) { selectFile(originalFiles[0].files[0]); }"
                                :class="activeTab === 'original' ? 'bg-white text-slate-950 shadow-2xs border border-slate-200/90 font-semibold' : 'text-slate-500 hover:text-slate-800 hover:bg-white/40 font-medium'"
                                class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0">
                                <i class="fas fa-file-contract text-xs" :class="activeTab === 'original' ? 'text-[#8B0000]' : 'text-slate-400'"></i>
                                <span>Original</span>
                                <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded-full tabular-nums"
                                      :class="activeTab === 'original' ? 'bg-slate-100 text-slate-700 border border-slate-200/80' : 'bg-slate-200/70 text-slate-500'">
                                    {{ $originalFiles->count() }}
                                </span>
                            </button>

                            @if($hasRevisions && $revisionFolders->isNotEmpty())
                                @if($revisionFolders->count() === 1)
                                    @php $singleRevNum = $revisionFolders->keys()->first(); $singleRevFiles = $revisionFolders->first(); @endphp
                                    <button type="button" @click="activeTab = 'rev_{{ $singleRevNum }}'; if (revisions['{{ $singleRevNum }}'] && revisions['{{ $singleRevNum }}'].length > 0 && revisions['{{ $singleRevNum }}'][0].files.length > 0) { selectFile(revisions['{{ $singleRevNum }}'][0].files[0]); }"
                                        :class="activeTab === 'rev_{{ $singleRevNum }}' ? 'bg-white text-slate-950 shadow-2xs border border-slate-200/90 font-semibold' : 'text-slate-500 hover:text-slate-800 hover:bg-white/40 font-medium'"
                                        class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0">
                                        <i class="fas fa-code-branch text-xs" :class="activeTab === 'rev_{{ $singleRevNum }}' ? 'text-indigo-600' : 'text-slate-400'"></i>
                                        <span>Rev {{ $singleRevNum }}</span>
                                        <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded-full tabular-nums"
                                              :class="activeTab === 'rev_{{ $singleRevNum }}' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-200/70 text-slate-500'">
                                            {{ $singleRevFiles->count() }}
                                        </span>
                                    </button>
                                @else
                                    <button type="button" 
                                        @click="revDropdownOpen = !revDropdownOpen"
                                        :class="activeTab.startsWith('rev_') ? 'bg-white text-slate-950 shadow-2xs border border-slate-200/90 font-semibold' : 'text-slate-500 hover:text-slate-800 hover:bg-white/40 font-medium'"
                                        class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0"
                                        aria-haspopup="true"
                                        :aria-expanded="revDropdownOpen ? 'true' : 'false'"
                                        title="Select revision cycle">
                                        <i class="fas fa-code-branch text-xs" :class="activeTab.startsWith('rev_') ? 'text-indigo-600' : 'text-slate-400'"></i>
                                        <span x-text="activeTab.startsWith('rev_') ? ('Rev ' + activeTab.replace('rev_', '')) : 'Revisions'"></span>
                                        <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded-full tabular-nums"
                                              :class="activeTab.startsWith('rev_') ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-200/70 text-slate-500'"
                                              x-text="activeTab.startsWith('rev_') ? (revisions[activeTab.replace('rev_', '')] ? revisions[activeTab.replace('rev_', '')].reduce((acc, g) => acc + g.files.length, 0) : '') : '{{ $revisionFolders->count() }}'">
                                        </span>
                                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" 
                                           :class="revDropdownOpen ? 'rotate-180 text-indigo-600' : (activeTab.startsWith('rev_') ? 'text-slate-600' : 'text-slate-400')" aria-hidden="true"></i>
                                    </button>
                                @endif
                            @endif

                            @if($hasRevisions && $activeFiles->isNotEmpty())
                                <button type="button" @click="activeTab = 'current'; if (activeFiles.length > 0 && activeFiles[0].files.length > 0 && (!activeFile || activeFile.group !== 'Current')) { selectFile(activeFiles[0].files[0]); }"
                                    :class="activeTab === 'current' ? 'bg-white text-slate-950 shadow-2xs border border-slate-200/90 font-semibold' : 'text-slate-500 hover:text-slate-800 hover:bg-white/40 font-medium'"
                                    class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer shrink-0">
                                    <i class="fas fa-file-signature text-xs" :class="activeTab === 'current' ? 'text-[#8B0000]' : 'text-slate-400'"></i>
                                    <span>Current</span>
                                    <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded-full tabular-nums"
                                          :class="activeTab === 'current' ? 'bg-slate-100 text-slate-700 border border-slate-200/80' : 'bg-slate-200/70 text-slate-500'">
                                        {{ $activeFiles->count() }}
                                    </span>
                                </button>
                            @endif

                            @if($isRevision || $hasDraftFiles)
                                <button type="button" @click="activeTab = 'draft'; if (draftFiles.length > 0 && draftFiles[0].files.length > 0) { selectFile(draftFiles[0].files[0]); }"
                                    :class="activeTab === 'draft' ? 'bg-orange-50 text-orange-900 shadow-2xs border border-orange-200 font-semibold' : 'text-orange-700/80 hover:text-orange-950 hover:bg-orange-50/50 font-medium'"
                                    class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-lg transition-all whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer shrink-0">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span>Drafting Rev {{ $nextRevisionNumber }}</span>
                                    <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded-full tabular-nums"
                                          :class="activeTab === 'draft' ? 'bg-orange-200 text-orange-900' : 'bg-orange-100 text-orange-800'">
                                        {{ $draftFiles->count() }}
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
                                 class="absolute right-3 top-full mt-1.5 w-64 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-1 z-50 overflow-hidden"
                                 role="menu"
                                 aria-label="Revision cycles">
                                <div class="px-3 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                                    <span class="flex items-center gap-1.5 text-indigo-950 font-semibold">
                                        <i class="fas fa-code-branch text-indigo-600 text-xs" aria-hidden="true"></i>
                                        <span>Select Revision Round</span>
                                    </span>
                                    <span class="text-slate-500 font-semibold tabular-nums bg-slate-200/70 px-1.5 py-0.5 rounded text-[10px]">{{ $revisionFolders->count() }} rounds</span>
                                </div>
                                <div class="max-h-64 overflow-y-auto custom-scrollbar divide-y divide-slate-100/80">
                                    @foreach($revisionFolders->sortKeys() as $revNum => $revFiles)
                                        @php
                                            $revDate = $revFiles->first()?->created_at?->format('M d, Y') ?? '';
                                            $isLatest = $loop->last;
                                        @endphp
                                        <button type="button" 
                                                @click="activeTab = 'rev_{{ $revNum }}'; revDropdownOpen = false; if (revisions['{{ $revNum }}'] && revisions['{{ $revNum }}'].length > 0 && revisions['{{ $revNum }}'][0].files.length > 0) { selectFile(revisions['{{ $revNum }}'][0].files[0]); }"
                                                :class="activeTab === 'rev_{{ $revNum }}' ? 'bg-indigo-50/70 text-indigo-950 font-semibold' : 'text-slate-700 hover:bg-slate-50/80 font-medium'"
                                                class="w-full flex items-center justify-between px-3.5 py-2 text-xs transition-colors text-left cursor-pointer group"
                                                role="menuitem">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-semibold shrink-0 border transition-colors"
                                                     :class="activeTab === 'rev_{{ $revNum }}' ? 'bg-indigo-600 text-white border-indigo-700' : 'bg-slate-100 text-slate-600 border-slate-200 group-hover:bg-slate-200/70'">
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
                                                      :class="activeTab === 'rev_{{ $revNum }}' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-500'">
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

                        <!-- Letters Tab List (Default closed) -->
                        <div x-show="activeTab === 'letters'" style="display:none;">
                            <template x-for="group in letters" :key="group.category">
                                <div class="mb-1.5 border border-slate-200/80 rounded-xl overflow-hidden bg-white transition-colors"
                                     x-data="{ expanded: false }">
                                    <button type="button" @click="expanded = !expanded"
                                        class="w-full flex items-center justify-between px-3.5 py-2 bg-white hover:bg-slate-50/80 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                        <span class="text-xs font-semibold text-slate-800 tracking-tight" x-text="group.category"></span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[11px] font-medium text-slate-400 tabular-nums"
                                                  x-text="group.files.length + ' file(s)'"></span>
                                            <i class="fas fa-chevron-down text-[11px] text-slate-400 transition-transform duration-200"
                                               :class="expanded ? 'rotate-180 text-slate-600' : ''" aria-hidden="true"></i>
                                        </div>
                                    </button>
                                    <div x-show="expanded" style="display: none;" x-transition>
                                        <div class="divide-y divide-slate-100 border-t border-slate-100">
                                            <template x-for="file in group.files" :key="file.id">
                                                <button type="button" @click="selectFile(file)"
                                                    :class="activeFile && activeFile.id === file.id 
                                                        ? 'bg-slate-100/90 text-slate-950 font-medium border-l-[3px] border-[#8B0000]' 
                                                        : 'text-slate-700 hover:bg-slate-50/80 font-normal border-l-[3px] border-transparent'"
                                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-left transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                                    <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 border"
                                                         :class="activeFile && activeFile.id === file.id 
                                                            ? 'bg-white text-slate-900 border-slate-300/80 shadow-2xs' 
                                                            : [file.bg, 'border-slate-200/70']">
                                                        <i :class="[file.icon, file.color]" class="text-xs" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-xs truncate" 
                                                           :class="activeFile && activeFile.id === file.id ? 'font-semibold text-slate-950' : 'font-medium text-slate-800'"
                                                           x-text="file.filename"></p>
                                                        <p class="text-[10px] tabular-nums text-slate-400" 
                                                           x-text="file.uploaded_at"></p>
                                                    </div>
                                                    <span x-show="activeFile && activeFile.id === file.id"
                                                          class="flex items-center gap-1 text-[10px] font-medium text-emerald-800 bg-emerald-50/90 border border-emerald-200/80 px-2 py-0.5 rounded-full shrink-0">
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

                        <!-- Original Documents Tab List (ONLY Application Form open by default!) -->
                        <div x-show="activeTab === 'original'" style="display:none;">
                            @foreach($requirements as $req)
                                @php
                                    $reqFiles = $originalFiles->where('category', $req->name);
                                    $shouldRender = $reqFiles->isNotEmpty() || $canReplace;
                                    $isAppForm = str_contains(strtolower($req->name), 'application form');
                                @endphp
                                @if($shouldRender)
                                    <div class="mb-1.5 border border-slate-200/80 rounded-xl overflow-hidden bg-white transition-colors"
                                         x-data="{ expanded: {{ $isAppForm ? 'true' : 'false' }} }">
                                        <!-- Header Accordion Trigger -->
                                        <button type="button" @click="expanded = !expanded"
                                            class="w-full flex items-center justify-between px-3.5 py-2 bg-white hover:bg-slate-50/80 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="text-xs font-semibold text-slate-800 tracking-tight truncate">{{ $req->name }}</span>
                                                @if(in_array($req->name, $missingDocs ?? []))
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[9px] font-semibold uppercase tracking-wider bg-amber-50 text-amber-900 border border-amber-200/80 shrink-0">
                                                        <i class="fas fa-exclamation-circle text-[8px]"></i> Reupload
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <span class="text-[11px] font-medium text-slate-400 tabular-nums">
                                                    {{ $reqFiles->count() }} file(s)
                                                </span>
                                                <i class="fas fa-chevron-down text-[11px] text-slate-400 transition-transform duration-200"
                                                   :class="expanded ? 'rotate-180 text-slate-600' : ''" aria-hidden="true"></i>
                                            </div>
                                        </button>

                                        <!-- Category Files Content -->
                                        <div x-show="expanded" style="display: none;" x-transition>
                                            <div class="divide-y divide-slate-100 border-t border-slate-100">
                                                @forelse($reqFiles as $file)
                                                    @php $fJson = $enrichFile($file, 'Original'); @endphp
                                                    <div class="flex items-center justify-between gap-2 px-3 py-2 text-left transition-all"
                                                         :class="activeFile && activeFile.id === {{ $file->id }} ? 'bg-slate-100/90 text-slate-950 font-medium border-l-[3px] border-[#8B0000]' : 'text-slate-700 hover:bg-slate-50/80 font-normal border-l-[3px] border-transparent'">
                                                        <button type="button" @click="selectFile({{ json_encode($fJson) }})"
                                                                class="flex items-center gap-2.5 min-w-0 flex-1 text-left cursor-pointer focus:outline-none">
                                                            <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 border"
                                                                 :class="activeFile && activeFile.id === {{ $file->id }} ? 'bg-white text-slate-900 border-slate-300/80 shadow-2xs' : '{{ $fJson['bg'] }} border-slate-200/70'">
                                                                <i class="{{ $fJson['icon'] }} {{ $fJson['color'] }} text-xs" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-xs truncate"
                                                                   :class="activeFile && activeFile.id === {{ $file->id }} ? 'font-semibold text-slate-950' : 'font-medium text-slate-800'">
                                                                    {{ $file->filename }}
                                                                </p>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-[10px] tabular-nums text-slate-400">
                                                                        {{ $file->created_at->format('M d, Y') }}
                                                                    </span>
                                                                    @if(isset($file->reviewerRemarks) && $file->reviewerRemarks->isNotEmpty())
                                                                        <span class="text-[9px] font-semibold uppercase tracking-wider px-1.5 py-0.2 rounded bg-amber-50 text-amber-900 border border-amber-200/80">
                                                                            Has Remarks
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </button>

                                                        <!-- Interactive Replace File Button (when allowed) -->
                                                        <div class="flex items-center gap-1.5 shrink-0">
                                                            <span x-show="activeFile && activeFile.id === {{ $file->id }}"
                                                                  class="flex items-center gap-1 text-[10px] font-medium text-emerald-800 bg-emerald-50/90 border border-emerald-200/80 px-2 py-0.5 rounded-full">
                                                                <i class="fas fa-eye text-[9px]" aria-hidden="true"></i>
                                                                <span>Viewing</span>
                                                            </span>

                                                            @if($canReplace)
                                                                <label class="cursor-pointer inline-flex items-center gap-1 px-2 py-0.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 text-[11px] font-medium transition-all shadow-2xs"
                                                                       title="Replace this document">
                                                                    <i class="fas fa-arrow-rotate-right text-[10px] text-slate-400"></i>
                                                                    <span>Replace</span>
                                                                    <input type="file" class="hidden" @change="replaceExisting({{ $file->id }}, $event)">
                                                                </label>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @empty
                                                    <!-- Missing File Empty Notice -->
                                                    <div class="p-3 bg-rose-50/30 text-center">
                                                        <p class="text-xs font-semibold text-rose-800 mb-1.5 flex items-center justify-center gap-1.5">
                                                            <i class="fas fa-circle-exclamation text-rose-500"></i>
                                                            <span>Missing Required Document</span>
                                                        </p>
                                                        @if($canReplace)
                                                            <label class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-xl border border-dashed border-rose-300 bg-white text-rose-700 hover:bg-rose-50 text-xs font-medium transition-colors cursor-pointer shadow-2xs">
                                                                <i class="fas fa-upload text-xs"></i>
                                                                <span>Upload Missing {{ $req->name }}</span>
                                                                <input type="file" class="hidden" @change="uploadMissing('{{ $req->name }}', $event)">
                                                            </label>
                                                        @endif
                                                    </div>
                                                @endforelse

                                                @if($reqFiles->isNotEmpty() && $canReplace && $req->is_multiple)
                                                    <div class="p-2 bg-slate-50/40 flex justify-end">
                                                        <label class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border border-dashed border-slate-300 bg-white hover:bg-slate-50 text-slate-600 text-xs font-medium cursor-pointer transition-all shadow-2xs">
                                                            <i class="fas fa-plus text-[10px] text-slate-400"></i>
                                                            <span>Add Another {{ $req->name }}</span>
                                                            <input type="file" class="hidden" @change="uploadMissing('{{ $req->name }}', $event)">
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Revision Tabs (One per revision number - ONLY Application Form open by default!) -->
                        @foreach($revisionFolders->sortKeys() as $revNum => $revFiles)
                            <div x-show="activeTab === 'rev_{{ $revNum }}'" style="display:none;">
                                <div class="px-3 py-1.5 bg-indigo-50/50 border border-indigo-100 rounded-xl mb-2 flex items-center justify-between">
                                    <p class="text-xs font-semibold text-indigo-950">Revision {{ $revNum }} Documents</p>
                                    <span class="text-[10px] font-mono text-slate-400">{{ $revFiles->first()?->created_at?->format('M d, Y') }}</span>
                                </div>
                                <template x-for="group in revisions['{{ $revNum }}']" :key="group.category">
                                    <div class="mb-1.5 border border-slate-200/80 rounded-xl overflow-hidden bg-white transition-colors"
                                         x-data="{ expanded: Boolean(group.category && group.category.toLowerCase().includes('application form')) }">
                                        <button type="button" @click="expanded = !expanded"
                                            class="w-full flex items-center justify-between px-3.5 py-2 bg-white hover:bg-slate-50/80 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                            <span class="text-xs font-semibold text-slate-800 tracking-tight" x-text="group.category"></span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[11px] font-medium text-slate-400 tabular-nums"
                                                      x-text="group.files.length + ' file(s)'"></span>
                                                <i class="fas fa-chevron-down text-[11px] text-slate-400 transition-transform duration-200"
                                                   :class="expanded ? 'rotate-180 text-slate-600' : ''" aria-hidden="true"></i>
                                            </div>
                                        </button>
                                        <div x-show="expanded" style="display: none;" x-transition>
                                            <div class="divide-y divide-slate-100 border-t border-slate-100">
                                                <template x-for="file in group.files" :key="file.id">
                                                    <button type="button" @click="selectFile(file)"
                                                        :class="activeFile && activeFile.id === file.id 
                                                            ? 'bg-slate-100/90 text-slate-950 font-medium border-l-[3px] border-[#8B0000]' 
                                                            : 'text-slate-700 hover:bg-slate-50/80 font-normal border-l-[3px] border-transparent'"
                                                        class="w-full flex items-center gap-2.5 px-3 py-2 text-left transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                                        <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 border"
                                                             :class="activeFile && activeFile.id === file.id 
                                                                ? 'bg-white text-slate-900 border-slate-300/80 shadow-2xs' 
                                                                : [file.bg, 'border-slate-200/70']">
                                                            <i :class="[file.icon, file.color]" class="text-xs" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="text-xs truncate" 
                                                               :class="activeFile && activeFile.id === file.id ? 'font-semibold text-slate-950' : 'font-medium text-slate-800'"
                                                               x-text="file.filename"></p>
                                                            <p class="text-[10px] tabular-nums text-slate-400" 
                                                               x-text="file.uploaded_at"></p>
                                                        </div>
                                                        <span x-show="activeFile && activeFile.id === file.id"
                                                              class="flex items-center gap-1 text-[10px] font-medium text-emerald-800 bg-emerald-50/90 border border-emerald-200/80 px-2 py-0.5 rounded-full shrink-0">
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

                        <!-- Current Documents Tab List (ONLY Application Form open by default!) -->
                        @if($hasRevisions && $activeFiles->isNotEmpty())
                            <div x-show="activeTab === 'current'" style="display:none;">
                                <div class="px-3 py-1.5 bg-slate-100/60 border border-slate-200/70 rounded-xl mb-2">
                                    <p class="text-xs font-semibold text-slate-700">Latest Consolidated Version of Each Document</p>
                                </div>
                                <template x-for="group in activeFiles" :key="group.category">
                                    <div class="mb-1.5 border border-slate-200/80 rounded-xl overflow-hidden bg-white transition-colors"
                                         x-data="{ expanded: Boolean(group.category && group.category.toLowerCase().includes('application form')) }">
                                        <button type="button" @click="expanded = !expanded"
                                            class="w-full flex items-center justify-between px-3.5 py-2 bg-white hover:bg-slate-50/80 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                            <span class="text-xs font-semibold text-slate-800 tracking-tight" x-text="group.category"></span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[11px] font-medium text-slate-400 tabular-nums"
                                                      x-text="group.files.length + ' file(s)'"></span>
                                                <i class="fas fa-chevron-down text-[11px] text-slate-400 transition-transform duration-200"
                                                   :class="expanded ? 'rotate-180 text-slate-600' : ''" aria-hidden="true"></i>
                                            </div>
                                        </button>
                                        <div x-show="expanded" style="display: none;" x-transition>
                                            <div class="divide-y divide-slate-100 border-t border-slate-100">
                                                <template x-for="file in group.files" :key="file.id">
                                                    <button type="button" @click="selectFile(file)"
                                                        :class="activeFile && activeFile.id === file.id 
                                                            ? 'bg-slate-100/90 text-slate-950 font-medium border-l-[3px] border-[#8B0000]' 
                                                            : 'text-slate-700 hover:bg-slate-50/80 font-normal border-l-[3px] border-transparent'"
                                                        class="w-full flex items-center gap-2.5 px-3 py-2 text-left transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                                        <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 border"
                                                             :class="activeFile && activeFile.id === file.id 
                                                                ? 'bg-white text-slate-900 border-slate-300/80 shadow-2xs' 
                                                                : [file.bg, 'border-slate-200/70']">
                                                            <i :class="[file.icon, file.color]" class="text-xs" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="text-xs truncate" 
                                                               :class="activeFile && activeFile.id === file.id ? 'font-semibold text-slate-950' : 'font-medium text-slate-800'"
                                                               x-text="file.filename"></p>
                                                            <p class="text-[10px] tabular-nums text-slate-400" 
                                                               x-text="file.uploaded_at"></p>
                                                        </div>
                                                        <span x-show="activeFile && activeFile.id === file.id"
                                                              class="flex items-center gap-1 text-[10px] font-medium text-emerald-800 bg-emerald-50/90 border border-emerald-200/80 px-2 py-0.5 rounded-full shrink-0">
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

                        <!-- Draft Workspace Tab List (ONLY Application Form open by default!) -->
                        @if($isRevision || $hasDraftFiles)
                            <div x-show="activeTab === 'draft'" style="display:none;">
                                <div class="px-3.5 py-2.5 bg-orange-50/60 border border-orange-200/70 rounded-xl mb-2 flex items-center justify-between gap-2">
                                    <div>
                                        <p class="text-xs font-semibold text-orange-950">Revision {{ $nextRevisionNumber }} Draft Workspace</p>
                                        <p class="text-[11px] text-orange-800 mt-0.5">Upload your revised documents below before submitting.</p>
                                    </div>
                                    @if($draftFiles->isNotEmpty())
                                        <button type="button" onclick="document.getElementById('revisionModal').classList.remove('hidden')"
                                                class="shrink-0 px-3 py-1.5 bg-brand-primary hover:bg-brand-secondary text-white text-xs font-semibold rounded-xl shadow-xs cursor-pointer transition-all active:scale-95">
                                            Submit Revisions
                                        </button>
                                    @endif
                                </div>

                                @foreach($requirements as $req)
                                    @php
                                        $dFiles = $draftFiles->where('category', $req->name);
                                        $isAppForm = str_contains(strtolower($req->name), 'application form');
                                    @endphp
                                    <div class="mb-1.5 border border-slate-200/80 rounded-xl overflow-hidden bg-white transition-colors"
                                         x-data="{ expanded: {{ $isAppForm ? 'true' : 'false' }} }">
                                        <button type="button" @click="expanded = !expanded"
                                            class="w-full flex items-center justify-between px-3.5 py-2 bg-white hover:bg-slate-50/80 transition-colors text-left focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer">
                                            <span class="text-xs font-semibold text-slate-800 tracking-tight">{{ $req->name }}</span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[11px] font-medium text-slate-400 tabular-nums">
                                                    {{ $dFiles->count() }} file(s)
                                                </span>
                                                <i class="fas fa-chevron-down text-[11px] text-slate-400 transition-transform duration-200"
                                                   :class="expanded ? 'rotate-180 text-slate-600' : ''" aria-hidden="true"></i>
                                            </div>
                                        </button>
                                        <div x-show="expanded" style="display: none;" x-transition>
                                            <div class="divide-y divide-slate-100 border-t border-slate-100">
                                                @foreach($dFiles as $file)
                                                    @php $dJson = $enrichFile($file, 'Draft'); @endphp
                                                    <div class="flex items-center justify-between gap-2 px-3 py-2 text-left"
                                                         :class="activeFile && activeFile.id === {{ $file->id }} ? 'bg-slate-100/90 text-slate-950 font-medium border-l-[3px] border-[#8B0000]' : 'text-slate-700 hover:bg-slate-50/80 font-normal border-l-[3px] border-transparent'">
                                                        <button type="button" @click="selectFile({{ json_encode($dJson) }})"
                                                                class="flex items-center gap-2.5 min-w-0 flex-1 text-left cursor-pointer focus:outline-none">
                                                            <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 border"
                                                                 :class="activeFile && activeFile.id === {{ $file->id }} ? 'bg-white text-slate-900 border-slate-300/80 shadow-2xs' : '{{ $dJson['bg'] }} border-slate-200/70'">
                                                                <i class="{{ $dJson['icon'] }} {{ $dJson['color'] }} text-xs" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-xs truncate"
                                                                   :class="activeFile && activeFile.id === {{ $file->id }} ? 'font-semibold text-slate-950' : 'font-medium text-slate-800'">
                                                                    {{ $file->filename }}
                                                                </p>
                                                                <span class="text-[10px] tabular-nums text-slate-400">
                                                                    {{ $file->created_at->format('M d, Y') }}
                                                                </span>
                                                            </div>
                                                        </button>

                                                        <div class="flex items-center gap-1.5 shrink-0">
                                                            <span x-show="activeFile && activeFile.id === {{ $file->id }}"
                                                                  class="flex items-center gap-1 text-[10px] font-medium text-emerald-800 bg-emerald-50/90 border border-emerald-200/80 px-2 py-0.5 rounded-full">
                                                                <i class="fas fa-eye text-[9px]" aria-hidden="true"></i>
                                                                <span>Viewing</span>
                                                            </span>
                                                            <button type="button" @click="deleteDraft({{ $file->id }}, '{{ route('delete.revision.document', $file->id) }}')"
                                                                    class="w-6 h-6 rounded-lg flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-800 transition-colors cursor-pointer border border-rose-200 shadow-2xs"
                                                                    title="Remove from draft">
                                                                <i class="fas fa-trash-alt text-[9px]"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <!-- Upload Dropzone for this requirement in Draft Workspace -->
                                                <div class="p-2.5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between gap-2">
                                                    <span class="text-[11px] text-slate-500 font-medium">Upload revised file:</span>
                                                    <label class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl border border-dashed border-orange-400/80 bg-white hover:bg-orange-50/60 text-orange-800 text-xs font-medium cursor-pointer transition-all shadow-2xs">
                                                        <i class="fas fa-cloud-arrow-up text-xs text-orange-600"></i>
                                                        <span>Upload {{ $req->name }}</span>
                                                        <input type="file" class="hidden" @change="uploadDraft('{{ $req->name }}', $event)">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>{{-- end scrollable list --}}
        <!-- Slide-over Drawer (Details & Activity Log)                -->
        <!-- ========================================================= -->
        <div x-show="drawerOpen" 
             style="display: none;" 
             class="relative z-[100]" 
             aria-labelledby="drawer-title" 
             role="dialog" 
             aria-modal="true">
            
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
                            
                            <!-- Drawer Top Header -->
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
                                </div>
                                <button type="button" @click="closeDrawer()" 
                                        class="w-8 h-8 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 transition-all flex items-center justify-center shrink-0 min-w-[36px] min-h-[36px] focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-2xs active:scale-95 cursor-pointer"
                                        title="Close Drawer (Esc)" aria-label="Close Drawer">
                                    <i class="fas fa-times text-xs" aria-hidden="true"></i>
                                </button>
                            </div>

                            <!-- Drawer Multi-Tab Switcher -->
                            <div class="px-5 py-2.5 bg-slate-50 border-b border-slate-200 shrink-0">
                                <div class="flex gap-1 bg-slate-200/70 p-1 rounded-xl border border-slate-300/80">
                                    <button type="button" @click="drawerTab = 'details'"
                                            :class="drawerTab === 'details' ? 'bg-white text-slate-950 shadow-xs border border-slate-300/80 font-bold' : 'text-slate-600 hover:text-slate-950 hover:bg-white/50 font-semibold'"
                                            class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-xs rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                        <i class="fas fa-info-circle text-xs" :class="drawerTab === 'details' ? 'text-[#8B0000]' : 'text-slate-500'"></i>
                                        <span>Details</span>
                                    </button>

                                    <button type="button" @click="drawerTab = 'activity'"
                                            :class="drawerTab === 'activity' ? 'bg-white text-slate-950 shadow-xs border border-slate-300/80 font-bold' : 'text-slate-600 hover:text-slate-950 hover:bg-white/50 font-semibold'"
                                            class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-xs rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-[#8B0000] cursor-pointer">
                                        <i class="fas fa-history text-xs" :class="drawerTab === 'activity' ? 'text-[#8B0000]' : 'text-slate-500'"></i>
                                        <span>Activity Log</span>
                                        <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full tabular-nums"
                                              :class="drawerTab === 'activity' ? 'bg-slate-950 text-white' : 'bg-slate-300/80 text-slate-700'">
                                            {{ $researchTitle->titleLogs->count() }}
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <!-- Drawer Body Content -->
                            <div class="flex-1 overflow-y-auto custom-scrollbar">
                                
                                <!-- Tab 1: Submission Details -->
                                <div x-show="drawerTab === 'details'" class="p-5 space-y-4 text-xs">
                                    <div class="space-y-3.5 bg-slate-50 p-4 rounded-xl border border-slate-200/90 shadow-2xs">
                                        <div class="flex justify-between items-center gap-3">
                                            <span class="text-slate-600 font-semibold">Protocol Code:</span>
                                            <span class="font-bold text-slate-900 font-mono text-right">{{ $researchTitle->reoc_code ?? 'Pending Assignment' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center gap-3 pt-2.5 border-t border-slate-200">
                                            <span class="text-slate-600 font-semibold">Research Category:</span>
                                            <span class="font-bold text-slate-900 text-right">{{ $researchTitle->Research_Category }}</span>
                                        </div>
                                        <div class="flex justify-between items-center gap-3 pt-2.5 border-t border-slate-200">
                                            <span class="text-slate-600 font-semibold">Project Type:</span>
                                            <span class="font-bold text-slate-900 text-right">{{ $researchTitle->project_type ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center gap-3 pt-2.5 border-t border-slate-200">
                                            <span class="text-slate-600 font-semibold">Review Type:</span>
                                            <span class="font-bold text-slate-900 text-right">{{ $researchTitle->Review_Type ?? 'Unassigned' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center gap-3 pt-2.5 border-t border-slate-200">
                                            <span class="text-slate-600 font-semibold">Submission Date:</span>
                                            <span class="font-mono text-slate-900 text-right">{{ $researchTitle->created_at->format('M d, Y • h:i A') }}</span>
                                        </div>
                                    </div>

                                    @if($researchTitle->primary_objectives)
                                        <div class="border border-slate-200 rounded-xl p-4 bg-white shadow-2xs">
                                            <h4 class="font-bold text-slate-900 uppercase tracking-wider text-[10px] mb-1.5">Primary Objectives</h4>
                                            <p class="text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $researchTitle->primary_objectives }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Tab 2: Activity Log -->
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
                                        class="px-5 py-2 text-xs font-bold text-slate-700 hover:text-slate-950 bg-white border border-slate-300 rounded-xl shadow-2xs hover:bg-slate-100 transition-all min-h-[38px] focus:outline-none focus:ring-2 focus:ring-[#8B0000] active:scale-95 cursor-pointer">
                                    Close
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Revision/Correction Modal -->
    @if($canSubmit)
        <div id="revisionModal" class="fixed inset-0 z-[110] hidden" aria-labelledby="revision-modal-heading" role="dialog"
            aria-modal="true" x-data @keydown.escape.window="document.getElementById('revisionModal').classList.add('hidden')">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
                onclick="document.getElementById('revisionModal').classList.add('hidden')"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                        <form action="{{ route('submit.revisions', $researchTitle->id) }}" method="POST">
                            @csrf

                            <!-- Header -->
                            <div class="px-7 py-5 bg-brand-primary relative overflow-hidden text-white">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 id="revision-modal-heading" class="text-xl font-bold tracking-tight flex items-center gap-2.5">
                                            <i class="fas {{ $submitIcon }} text-base"></i>
                                            <span>{{ $submitLabel }}</span>
                                        </h3>
                                        <p class="text-red-100 text-xs mt-1">
                                            {{ $isRevision ? 'Confirm submission of your revised documents for committee evaluation.' : 'Confirm submission of your document corrections for admin verification.' }}
                                        </p>
                                    </div>
                                    <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')"
                                        class="w-8 h-8 flex items-center justify-center text-white/80 hover:text-white hover:bg-white/20 rounded-xl transition-all cursor-pointer"
                                        aria-label="Close modal">
                                        <i class="fas fa-times text-base"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="px-7 py-6 space-y-4">
                                <div class="bg-blue-50/80 p-3.5 rounded-xl border border-blue-200 flex gap-3 items-start">
                                    <i class="fas fa-info-circle text-blue-600 text-base shrink-0 mt-0.5"></i>
                                    <p class="text-xs text-blue-900 leading-relaxed">
                                        @if($isRevision)
                                            Your revision workspace files will be compiled into <strong>Revision {{ $nextRevisionNumber }}</strong> and your protocol status will be updated to <strong>Revision Submitted</strong>.
                                        @else
                                            Your updated documents will be submitted to the Research Ethics Office for intake review.
                                        @endif
                                    </p>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="revision_message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Remarks / Summary of Changes <span class="text-slate-400 font-normal normal-case">(Optional)</span>
                                    </label>
                                    <textarea name="revision_message" id="revision_message" rows="3"
                                        class="w-full rounded-xl border border-slate-300 bg-white shadow-2xs focus:border-brand-primary focus:ring-1 focus:ring-brand-primary text-xs p-3 resize-none transition-all placeholder-slate-400"
                                        placeholder="{{ $isRevision ? 'Describe the key modifications made in this revision...' : 'Describe what was corrected...' }}"></textarea>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="bg-slate-50 px-7 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5 border-t border-slate-200">
                                <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')"
                                    class="inline-flex justify-center items-center rounded-xl bg-white px-4 py-2 text-xs font-bold text-slate-700 border border-slate-300 hover:bg-slate-100 transition-all cursor-pointer">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="inline-flex justify-center items-center gap-2 rounded-xl bg-brand-primary hover:bg-brand-secondary px-6 py-2 text-xs font-bold text-white shadow-md shadow-red-950/20 hover:shadow-red-950/30 transition-all cursor-pointer">
                                    <span>Confirm & Submit</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Upload Progress Modal -->
    <div id="upload-progress-modal" class="fixed inset-0 z-[120] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-xs transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 p-8 text-center">
                    <div class="relative w-16 h-16 mx-auto mb-5">
                        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-brand-primary rounded-full border-t-transparent animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-xl text-brand-primary animate-pulse"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Uploading Research Document</h3>
                    <p class="text-slate-500 text-xs mb-6">Please keep this window open while the document transfers to the server.</p>
                    <div class="w-full bg-slate-100 rounded-full h-3 mb-3 relative overflow-hidden shadow-inner">
                        <div id="upload-progress-bar" class="bg-brand-primary h-full w-0 transition-all duration-200"></div>
                    </div>
                    <div class="flex justify-between w-full text-xs font-bold">
                        <span id="upload-percentage" class="text-brand-primary">0%</span>
                        <span id="upload-size" class="text-slate-400 font-mono">0 KB / 0 KB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Mouse Drag-to-Scroll ("hold click then swipe") & Wheel Scroll for Tab Bar -->
    <script>
        (function initTabDragScroll() {
            const setupDragScroll = () => {
                const tabBar = document.getElementById('document-tab-bar');
                if (!tabBar || tabBar.dataset.dragScrollInitialized) return;
                tabBar.dataset.dragScrollInitialized = 'true';

                let isDown = false;
                let startX = 0;
                let scrollLeft = 0;
                let hasDragged = false;

                tabBar.addEventListener('mousedown', (e) => {
                    if (e.button !== 0) return;
                    isDown = true;
                    hasDragged = false;
                    tabBar.classList.add('cursor-grabbing');
                    tabBar.classList.remove('cursor-grab');
                    startX = e.pageX - tabBar.offsetLeft;
                    scrollLeft = tabBar.scrollLeft;
                });

                window.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    const x = e.pageX - tabBar.offsetLeft;
                    const walk = (x - startX);
                    if (Math.abs(walk) > 4) {
                        hasDragged = true;
                    }
                    tabBar.scrollLeft = scrollLeft - walk;
                });

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
</x-user_layout>