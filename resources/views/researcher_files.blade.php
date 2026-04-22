<x-user_layout>
    <x-skeleton-loader />

    <main id="page-content" style="display: none;" class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Header Section -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-200 pb-8">
            <div class="space-y-2">
                <a href="{{ route('home') }}"
                    class="group inline-flex items-center gap-2 text-slate-500 hover:text-[#8B0000] transition-colors mb-2 text-sm font-medium">
                    <div
                        class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-red-50 transition-colors">
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
            <div id="revisionModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                    onclick="document.getElementById('revisionModal').classList.add('hidden')"></div>

                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div
                            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
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
                                            <h3
                                                class="text-2xl font-bold text-white tracking-tight flex items-center gap-3">
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
                                        <button type="button"
                                            onclick="document.getElementById('revisionModal').classList.add('hidden')"
                                            class="text-white/70 hover:text-white hover:bg-white/20 rounded-lg p-1 transition-all">
                                            <i class="fas fa-times text-xl"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Body -->
                                <div class="px-8 py-8 bg-white">
                                    <div class="space-y-6">
                                        <div
                                            class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex gap-4 items-start">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0 text-blue-600">
                                                <i class="fas fa-info-circle text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-sm">Review Process</h4>
                                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                                    @if($isRevision)
                                                        Your submission will be marked as <strong>Waiting for Approval</strong>
                                                        until reviewed.
                                                    @else
                                                        Submitting will notify the Research Ethics Office to process your
                                                        corrections.
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="revision_message"
                                                class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                                Add a Note <span
                                                    class="text-slate-400 font-normal normal-case">(Optional)</span>
                                            </label>
                                            <div class="relative group">
                                                <textarea name="revision_message" id="revision_message" rows="3"
                                                    class="w-full rounded-xl border-slate-200 bg-white shadow-sm focus:border-[#8B0000] focus:ring-[#8B0000] text-sm placeholder-slate-400 py-3 px-4 resize-none transition-all group-hover:border-slate-300"
                                                    placeholder="{{ $isRevision ? 'Briefly describe your changes...' : 'E.g., Added missing signature page...' }}"></textarea>
                                                <div
                                                    class="absolute bottom-3 right-3 text-slate-300 pointer-events-none group-focus-within:text-[#8B0000] transition-colors">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div
                                    class="bg-slate-50 px-8 py-5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100">
                                    <button type="button"
                                        onclick="document.getElementById('revisionModal').classList.add('hidden')"
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
            <div
                class="animate-[fadeInUp_0.3s_ease-out] bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl mb-8 flex items-center gap-4 shadow-sm">
                <div
                    class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-900">Success</h4>
                    <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @php
            $disapprovalFeedback = $researchTitle->feedbacks()->where('type', 'disapproval_remark')->first();
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

        {{-- Stage-Specific Admin Remarks Banner --}}
        @if($stageRemark)
            @php
                $isRevisionStage = $researchTitle->Status === 'Waiting for Revision';
                $isHardcopyStage = in_array($researchTitle->Status, ['Incomplete Hardcopy', 'Incomplete - Awaiting Hardcopy']);
                $missingDocs = is_array($stageRemark->missing_requirements) ? $stageRemark->missing_requirements : [];
                
                if ($isRevisionStage) {
                    $bannerColor = 'bg-indigo-50 border-indigo-200';
                    $iconBg = 'bg-indigo-100 text-indigo-600';
                    $iconClass = 'fa-file-alt';
                    $titleColor = 'text-indigo-900';
                    $badgeColor = 'bg-indigo-100 text-indigo-700';
                    $dividerColor = 'border-indigo-100';
                    $remarkLabel = 'Revision Remarks from Panel';
                    $remarkDesc = 'The following deliberation notes were provided by the review panel. Please address all issues in your revision:';
                    $missingBg = 'bg-white border-indigo-100';
                } elseif ($isHardcopyStage) {
                    $bannerColor = 'bg-amber-50 border-amber-200';
                    $iconBg = 'bg-amber-100 text-amber-600';
                    $iconClass = 'fa-file-invoice';
                    $titleColor = 'text-amber-900';
                    $badgeColor = 'bg-amber-100 text-amber-700';
                    $dividerColor = 'border-amber-100';
                    $remarkLabel = 'Hardcopy Submission Remarks';
                    $remarkDesc = 'The admin has flagged the following issues with your hardcopy submission:';
                    $missingBg = 'bg-white border-amber-100';
                } else {
                    $bannerColor = 'bg-amber-50 border-amber-200';
                    $iconBg = 'bg-amber-100 text-amber-600';
                    $iconClass = 'fa-exclamation-triangle';
                    $titleColor = 'text-amber-900';
                    $badgeColor = 'bg-amber-100 text-amber-700';
                    $dividerColor = 'border-amber-100';
                    $remarkLabel = 'Admin Remarks — Action Required';
                    $remarkDesc = 'The admin has reviewed your submission and provided the following remarks. Please review and comply:';
                    $missingBg = 'bg-white border-amber-100';
                }
            @endphp
            <div class="mb-8 p-6 {{ $bannerColor }} border rounded-2xl flex items-start gap-4 shadow-sm animate-[fadeInUp_0.5s_ease-out]">
                <div class="w-10 h-10 rounded-full {{ $iconBg }} flex items-center justify-center flex-shrink-0">
                    <i class="fas {{ $iconClass }} text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between flex-wrap gap-2 mb-2">
                        <h4 class="text-sm font-extrabold {{ $titleColor }} uppercase tracking-wider flex items-center gap-2">
                            <span>{{ $remarkLabel }}</span>
                        </h4>
                        <span class="text-[10px] font-bold {{ $badgeColor }} px-2.5 py-1 rounded-full">
                            <i class="far fa-clock mr-1"></i>{{ $stageRemark->created_at->timezone('Asia/Manila')->format('M d, Y \a\t h:i A') }}
                        </span>
                    </div>
                    <p class="text-xs {{ $titleColor }} opacity-75 mb-3">{{ $remarkDesc }}</p>

                    @if($stageRemark->message)
                        <div class="bg-white rounded-xl border {{ $dividerColor }} p-4 shadow-sm mb-3">
                            @if($isRevisionStage)
                                {{-- Parse the deliberation format for revision remarks --}}
                                @php
                                    $rawMsg = $stageRemark->message;
                                    $sections = [
                                        'Scientific Soundness' => ['key' => 'Scientific Soundness: ', 'next' => 'Ethical Issues: ', 'color' => 'text-indigo-600'],
                                        'Ethical Issues'       => ['key' => 'Ethical Issues: ', 'next' => 'ICF Issues: ', 'color' => 'text-amber-600'],
                                        'ICF Issues'           => ['key' => 'ICF Issues: ', 'next' => 'Summary of Issues & Resolutions: ', 'color' => 'text-emerald-600'],
                                        'Summary'              => ['key' => 'Summary of Issues & Resolutions: ', 'next' => '=== FINAL DECISION ===', 'color' => 'text-rose-600'],
                                    ];
                                    $parsed = [];
                                    foreach ($sections as $label => $sec) {
                                        $start = strpos($rawMsg, $sec['key']);
                                        $end   = strpos($rawMsg, $sec['next']);
                                        if ($start !== false && $end !== false) {
                                            $val = trim(substr($rawMsg, $start + strlen($sec['key']), $end - ($start + strlen($sec['key']))));
                                            if ($val && $val !== 'N/A') {
                                                $parsed[$label] = ['text' => $val, 'color' => $sec['color']];
                                            }
                                        }
                                    }
                                @endphp
                                @if(!empty($parsed))
                                    <div class="space-y-3">
                                        @foreach($parsed as $label => $item)
                                            <div>
                                                <p class="text-[10px] font-extrabold {{ $item['color'] }} uppercase tracking-widest mb-1">{{ $label }}</p>
                                                <p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed border-l-2 border-slate-200 pl-3">{{ $item['text'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-slate-600 whitespace-pre-wrap">{{ $stageRemark->message }}</p>
                                @endif
                            @else
                                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $stageRemark->message }}</p>
                            @endif
                        </div>
                    @endif

                    @if(!empty($missingDocs))
                        <div class="rounded-xl border {{ $missingBg }} overflow-hidden">
                            <div class="px-4 py-2 border-b {{ $dividerColor }} bg-white/50">
                                <p class="text-[10px] font-extrabold {{ $titleColor }} uppercase tracking-widest flex items-center gap-1.5">
                                    <i class="fas fa-list-check"></i> Missing Requirements / Actions Needed
                                </p>
                            </div>
                            <ul class="divide-y {{ $dividerColor }}">
                                @foreach($missingDocs as $doc)
                                    <li class="flex items-center gap-3 px-4 py-2.5">
                                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></div>
                                        <span class="text-sm text-slate-700 font-medium">{{ $doc }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @php
            $missingDocs = [];
            if (in_array($researchTitle->Status ?? '', ['Incomplete', 'Pending', 'Pending (Initial Intake)'])) {
                $latestDeficiency = \App\Models\SubmissionFeedback::where('research_title_id', $researchTitle->id)
                    ->where('type', 'admin_deficiency')
                    ->latest()
                    ->first();
                if ($latestDeficiency && !empty($latestDeficiency->missing_requirements)) {
                    $missingDocs = is_array($latestDeficiency->missing_requirements) 
                        ? $latestDeficiency->missing_requirements 
                        : (json_decode($latestDeficiency->missing_requirements, true) ?? []);
                }
            }

            $allFiles = $researchTitle->files->merge($researchTitle->adminFiles ?? collect());

            $letters = $allFiles->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter'])->sortByDesc('created_at');
            $archivedLetters = $allFiles->where('filetype', 'Archived Result of Review')->sortByDesc('created_at');
            $protocolDocs = $researchTitle->files->whereNotIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review']);

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
        @if($letters->isNotEmpty() || $archivedLetters->isNotEmpty())
            <div class="mb-10">
                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-envelope-open-text text-emerald-600"></i>
                    Recommendation Letters
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    {{-- Active Letters --}}
                    @foreach($letters as $file)
                        <div
                            class="bg-white rounded-2xl border border-emerald-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
                            <div class="p-5 flex items-start gap-4 border-b border-emerald-50 bg-emerald-50/30">
                                <div
                                    class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                    <i class="fas fa-certificate text-xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-slate-800 text-sm leading-snug truncate mb-1"
                                        title="{{ $file->filename }}">
                                        {{ $file->filename }}
                                    </h4>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">
                                        Official Document
                                    </span>
                                </div>
                            </div>

                            <!-- Preview Area -->
                            <div
                                class="relative bg-slate-50 flex-1 min-h-[150px] border-b border-slate-100 group-hover:bg-slate-100 transition-colors overflow-hidden">
                                <iframe src="{{ asset($file->filepath) }}#toolbar=0&navpanes=0&scrollbar=0"
                                    class="w-full h-full border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
                                <div
                                    class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                                    <a href="{{ asset($file->filepath) }}" target="_blank"
                                        class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-emerald-600 hover:text-white flex items-center gap-2">
                                        <i class="fas fa-external-link-alt"></i> View Fullscreen
                                    </a>
                                </div>
                            </div>

                            <!-- Read-Only Actions -->
                            <div class="p-4 bg-white">
                                <a href="{{ asset($file->filepath) }}" download
                                    class="block w-full py-2.5 px-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 hover:text-emerald-900 rounded-xl text-sm font-bold text-center transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            </div>
                        </div>
                    @endforeach

                    {{-- Archived (Previous) Letters --}}
                    @foreach($archivedLetters as $file)
                        <div
                            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col group hover:shadow-lg hover:border-slate-300 transition-all duration-300">
                            <div class="p-5 flex items-start gap-4 border-b border-slate-100 bg-slate-50/50">
                                <div
                                    class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0 text-slate-400">
                                    <i class="fas fa-history text-xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-slate-700 text-sm leading-snug truncate mb-1"
                                        title="{{ $file->filename }}">
                                        {{ $file->filename }}
                                    </h4>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500">
                                        Previous Version
                                    </span>
                                    <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                        <i class="far fa-clock"></i> {{ $file->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Preview Area -->
                            <div
                                class="relative bg-slate-50 flex-1 min-h-[150px] border-b border-slate-100 group-hover:bg-slate-100 transition-colors overflow-hidden">
                                <iframe src="{{ asset($file->filepath) }}#toolbar=0&navpanes=0&scrollbar=0"
                                    class="w-full h-full border-0 pointer-events-none opacity-60 group-hover:opacity-90 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
                                <div
                                    class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                                    <a href="{{ asset($file->filepath) }}" target="_blank"
                                        class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-slate-600 hover:text-white flex items-center gap-2">
                                        <i class="fas fa-external-link-alt"></i> View Fullscreen
                                    </a>
                                </div>
                            </div>

                            <!-- Read-Only Actions -->
                            <div class="p-4 bg-white">
                                <a href="{{ asset($file->filepath) }}" download
                                    class="block w-full py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-800 rounded-xl text-sm font-bold text-center transition-colors flex items-center justify-center gap-2">
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
                    <div x-show="!isDrafting"
                        class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-2xl flex items-center justify-between shadow-sm">
                        <div>
                            <h4 class="text-lg font-bold text-blue-900 mb-1">Revisions Required</h4>
                            <p class="text-blue-700 text-sm">Please create a new Revision Workspace to upload your corrected
                                documents.</p>
                        </div>
                        <button @click="startDrafting()" type="button"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <i class="fas fa-plus"></i> Create Revision {{ $nextRevisionNumber }}
                        </button>
                    </div>
                @endif

                <!-- Tabs Navigation -->
                <div class="flex gap-2 border-b border-slate-200 mb-8 overflow-x-auto custom-scrollbar">

                    {{-- Original Documents tab: always shown when files with revision_number=null exist or when Incomplete
                    --}}
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
                                <div x-data="{ expanded: $persist(false).as('{{ $pKey }}') }"
                                    class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                                    <!-- Header (Collapsible trigger) -->
                                    <button @click="expanded = !expanded"
                                        class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-100 text-left">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-full flex items-center justify-center {{ $categoryFiles->isNotEmpty() ? 'bg-indigo-100 text-indigo-600' : 'bg-red-50 text-red-500' }}">
                                                <i
                                                    class="fas {{ $categoryFiles->isNotEmpty() ? 'fa-folder-open' : 'fa-exclamation-triangle' }}"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-extrabold text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                                    {{ $req->name }}
                                                    @if(in_array($req->name, $missingDocs))
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 tracking-normal normal-case border border-amber-200" title="The Admin requested you to re-upload or fix this document">
                                                            <i class="fas fa-exclamation-circle text-[8px]"></i> Reupload Needed
                                                        </span>
                                                    @endif
                                                </h4>
                                                @if($categoryFiles->isNotEmpty())
                                                    <p class="text-[10px] font-bold text-slate-400">{{ $categoryFiles->count() }} doc(s)
                                                        submitted</p>
                                                @else
                                                    <p class="text-[10px] font-bold text-red-400">Missing Requirement</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div
                                            class="flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 shadow-sm group">
                                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 group-hover:text-slate-600"
                                                :class="expanded ? 'rotate-180' : ''"></i>
                                        </div>
                                    </button>

                                    <!-- Content Grid -->
                                    <div x-show="expanded" x-transition class="p-6 bg-slate-50/30" style="display: none;">
                                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                            @if($categoryFiles->isNotEmpty())
                                                @foreach($categoryFiles as $file)
                                                    <x-researcher-file-card :file="$file" :researchTitle="$researchTitle" />
                                                @endforeach

                                                @if(in_array($researchTitle->Status ?? 'Pending', ['Incomplete', 'Pending', 'Pending (Initial Intake)']) && $req->is_multiple)
                                                    <div x-data="{
                                                            isUploading: false,
                                                            async uploadMissingFile(event) {
                                                                const file = event.target.files[0];
                                                                if (!file) return;
                                                                this.isUploading = true;
                                                                
                                                                const formData = new FormData();
                                                                formData.append('file', file);
                                                                formData.append('category', '{{ $req->name }}');
                                                                formData.append('_token', '{{ csrf_token() }}');

                                                                // Show Progress Modal
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

                                                                const formatBytes = (bytes, decimals = 2) => {
                                                                    if (bytes === 0) return '0 Bytes';
                                                                    const k = 1024;
                                                                    const dm = decimals < 0 ? 0 : decimals;
                                                                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                                                                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                                                                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                                                                };

                                                                try {
                                                                    const xhr = new XMLHttpRequest();
                                                                    
                                                                    const uploadPromise = new Promise((resolve, reject) => {
                                                                        xhr.upload.addEventListener('progress', (e) => {
                                                                            if (e.lengthComputable) {
                                                                                const percentComplete = Math.round((e.loaded / e.total) * 100);
                                                                                if (progressBar) progressBar.style.width = percentComplete + '%';
                                                                                if (percentageText) percentageText.textContent = percentComplete + '%';
                                                                                if (sizeText) sizeText.textContent = `${formatBytes(e.loaded)} / ${formatBytes(e.total)}`;
                                                                            }
                                                                        });

                                                                        xhr.onload = () => {
                                                                            if (xhr.status >= 200 && xhr.status < 300) {
                                                                                resolve(JSON.parse(xhr.responseText));
                                                                            } else {
                                                                                let errorMsg = 'Upload failed';
                                                                                try {
                                                                                    const errorData = JSON.parse(xhr.responseText);
                                                                                    errorMsg = errorData.message || errorData.error || errorMsg;
                                                                                } catch(e) {}
                                                                                reject(new Error(errorMsg));
                                                                            }
                                                                        };

                                                                        xhr.onerror = () => reject(new Error('Network error'));
                                                                    });

                                                                    xhr.open('POST', '{{ route('add.missing.file', $researchTitle->id) }}');
                                                                    xhr.setRequestHeader('Accept', 'application/json');
                                                                    xhr.send(formData);

                                                                    const data = await uploadPromise;
                                                                    
                                                                    if (data.success) {
                                                                        const htmlResponse = await fetch(window.location.href);
                                                                        const htmlText = await htmlResponse.text();
                                                                        const parser = new DOMParser();
                                                                        const doc = parser.parseFromString(htmlText, 'text/html');
                                                                        
                                                                        const newContent = doc.getElementById('page-content');
                                                                        if (newContent) document.getElementById('page-content').innerHTML = newContent.innerHTML;
                                                                        
                                                                        if (progressModal) progressModal.classList.add('hidden');
                                                                        Swal.fire({ icon: 'success', title: 'Document Added', text: 'Document uploaded successfully!', timer: 1500, showConfirmButton: false, toast: true, position: 'top-end' });
                                                                    } else {
                                                                        throw new Error('Server returned unexpected format.');
                                                                    }
                                                                } catch (error) {
                                                                    if (progressModal) progressModal.classList.add('hidden');
                                                                    Swal.fire({ icon: 'error', title: 'Upload Error', text: error.message });
                                                                } finally {
                                                                    this.isUploading = false;
                                                                    if (event.target) event.target.value = '';
                                                                }
                                                            }
                                                        }"
                                                        class="group bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center hover:bg-slate-100 transition-colors shadow-sm relative overflow-hidden">
                                                        
                                                        <!-- Loading Overlay -->
                                                        <div x-show="isUploading" class="absolute inset-0 z-20 bg-white/90 backdrop-blur-sm flex items-center justify-center pointer-events-none transition-opacity duration-300" style="display: none;">
                                                            <div class="flex flex-col items-center gap-2">
                                                                <i class="fas fa-spinner fa-spin text-slate-500 text-2xl"></i>
                                                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Uploading...</span>
                                                            </div>
                                                        </div>

                                                        <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center mb-3">
                                                            <i class="fas fa-plus text-xl text-slate-400 group-hover:scale-110 transition-transform"></i>
                                                        </div>
                                                        <h4 class="font-bold text-slate-700 text-xs mb-1 flex items-center justify-center gap-1">
                                                            {{ $req->name }}
                                                            @if(in_array($req->name, $missingDocs))
                                                                <i class="fas fa-exclamation-circle text-amber-500" title="Reupload Needed"></i>
                                                            @endif
                                                        </h4>
                                                        <span class="text-xs text-slate-500 mb-4 block">Add Another Document</span>

                                                        <label class="block cursor-pointer w-full">
                                                            <div class="w-full py-2.5 px-4 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 text-xs font-bold transition-colors flex items-center justify-center gap-2 group/upload" :class="isUploading ? 'opacity-50 pointer-events-none' : ''">
                                                                <i class="fas fa-upload group-hover/upload:animate-bounce"></i>
                                                                <span>Upload Additional</span>
                                                            </div>
                                                            @php
                                                                $accepts = [];
                                                                if (str_contains(strtolower($req->file_type), 'pdf'))
                                                                    $accepts[] = '.pdf';
                                                                if (str_contains(strtolower($req->file_type), 'word'))
                                                                    array_push($accepts, '.doc', '.docx');
                                                                $acceptAttr = count($accepts) > 0 ? implode(',', $accepts) : '';
                                                            @endphp
                                                            <input type="file" class="hidden" @change="uploadMissingFile($event)" accept="{{ $acceptAttr }}" :disabled="isUploading">
                                                        </label>
                                                    </div>
                                                @endif
                                            @else
                                                    @if(in_array($researchTitle->Status ?? 'Pending', ['Incomplete', 'Pending', 'Pending (Initial Intake)']))
                                                        <div x-data="{
                                                            isUploading: false,
                                                            async uploadMissingFile(event) {
                                                                const file = event.target.files[0];
                                                                if (!file) return;
                                                                this.isUploading = true;
                                                                
                                                                const formData = new FormData();
                                                                formData.append('file', file);
                                                                formData.append('category', '{{ $req->name }}');
                                                                formData.append('_token', '{{ csrf_token() }}');

                                                                // Show Progress Modal
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

                                                                const formatBytes = (bytes, decimals = 2) => {
                                                                    if (bytes === 0) return '0 Bytes';
                                                                    const k = 1024;
                                                                    const dm = decimals < 0 ? 0 : decimals;
                                                                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                                                                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                                                                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                                                                };

                                                                try {
                                                                    const xhr = new XMLHttpRequest();
                                                                    
                                                                    const uploadPromise = new Promise((resolve, reject) => {
                                                                        xhr.upload.addEventListener('progress', (e) => {
                                                                            if (e.lengthComputable) {
                                                                                const percentComplete = Math.round((e.loaded / e.total) * 100);
                                                                                if (progressBar) progressBar.style.width = percentComplete + '%';
                                                                                if (percentageText) percentageText.textContent = percentComplete + '%';
                                                                                if (sizeText) sizeText.textContent = `${formatBytes(e.loaded)} / ${formatBytes(e.total)}`;
                                                                            }
                                                                        });

                                                                        xhr.onload = () => {
                                                                            if (xhr.status >= 200 && xhr.status < 300) {
                                                                                resolve(JSON.parse(xhr.responseText));
                                                                            } else {
                                                                                let errorMsg = 'Upload failed';
                                                                                try {
                                                                                    const errorData = JSON.parse(xhr.responseText);
                                                                                    errorMsg = errorData.message || errorData.error || errorMsg;
                                                                                } catch(e) {}
                                                                                reject(new Error(errorMsg));
                                                                            }
                                                                        };

                                                                        xhr.onerror = () => reject(new Error('Network error'));
                                                                    });

                                                                    xhr.open('POST', '{{ route('add.missing.file', $researchTitle->id) }}');
                                                                    xhr.setRequestHeader('Accept', 'application/json');
                                                                    xhr.send(formData);

                                                                    const data = await uploadPromise;
                                                                    
                                                                    if (data.success) {
                                                                        const htmlResponse = await fetch(window.location.href);
                                                                        const htmlText = await htmlResponse.text();
                                                                        const parser = new DOMParser();
                                                                        const doc = parser.parseFromString(htmlText, 'text/html');
                                                                        
                                                                        const newContent = doc.getElementById('page-content');
                                                                        if (newContent) document.getElementById('page-content').innerHTML = newContent.innerHTML;
                                                                        
                                                                        if (progressModal) progressModal.classList.add('hidden');
                                                                        Swal.fire({ icon: 'success', title: 'Document Added', text: 'Document uploaded successfully!', timer: 1500, showConfirmButton: false, toast: true, position: 'top-end' });
                                                                    } else {
                                                                        throw new Error('Server returned unexpected format.');
                                                                    }
                                                                } catch (error) {
                                                                    if (progressModal) progressModal.classList.add('hidden');
                                                                    Swal.fire({ icon: 'error', title: 'Upload Error', text: error.message });
                                                                } finally {
                                                                    this.isUploading = false;
                                                                    if (event.target) event.target.value = '';
                                                                }
                                                            }
                                                        }" class="group bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center hover:bg-slate-100 transition-colors shadow-sm relative overflow-hidden">
                                                            
                                                            <!-- Loading Overlay -->
                                                            <div x-show="isUploading" class="absolute inset-0 z-20 bg-white/90 backdrop-blur-sm flex items-center justify-center pointer-events-none transition-opacity duration-300" style="display: none;">
                                                                <div class="flex flex-col items-center gap-2">
                                                                    <i class="fas fa-spinner fa-spin text-[#8B0000] text-2xl"></i>
                                                                    <span class="text-[10px] font-bold text-[#8B0000] uppercase tracking-wider">Uploading...</span>
                                                                </div>
                                                            </div>

                                                            <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center mb-3">
                                                                <i class="fas fa-file-upload text-xl text-slate-400 group-hover:scale-110 transition-transform"></i>
                                                            </div>
                                                            <h4 class="font-bold text-slate-700 text-xs mb-1 flex items-center justify-center gap-1">
                                                                {{ $req->name }}
                                                                @if(in_array($req->name, $missingDocs))
                                                                    <i class="fas fa-exclamation-circle text-amber-500" title="Reupload Needed"></i>
                                                                @endif
                                                            </h4>
                                                            <span class="text-[10px] text-slate-500 mb-4 block">{{ $req->is_required ? 'Required Document' : 'Optional Document' }} (Not Submitted)</span>

                                                            <label class="block cursor-pointer w-full">
                                                                <div class="w-full py-2.5 px-4 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 text-xs font-bold transition-colors flex items-center justify-center gap-2 group/upload" :class="isUploading ? 'opacity-50 pointer-events-none' : ''">
                                                                    <i class="fas fa-upload group-hover/upload:animate-bounce"></i>
                                                                    <span>Upload File</span>
                                                                </div>
                                                                @php
                                                                    $accepts = [];
                                                                    if (str_contains(strtolower($req->file_type), 'pdf'))
                                                                        $accepts[] = '.pdf';
                                                                    if (str_contains(strtolower($req->file_type), 'word'))
                                                                        array_push($accepts, '.doc', '.docx');
                                                                    $acceptAttr = count($accepts) > 0 ? implode(',', $accepts) : '';
                                                                @endphp
                                                                <input type="file" class="hidden" @change="uploadMissingFile($event)" accept="{{ $acceptAttr }}" :disabled="isUploading">
                                                            </label>
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

                                $fileTypeLabel = match ($file->filetype) {
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
                                        $draftFilesForCategory = $draftFiles->where('category', $file->category)->values();
                                        // Pass the initial files to Alpine
                                        $initialFilesJson = $draftFilesForCategory->map(function ($df) {
                                            return [
                                                'id' => $df->id,
                                                'filename' => $df->filename,
                                                'filetype' => collect(explode('.', $df->filename))->last(),
                                                'category' => $df->category,
                                                'isPdf' => strtolower(collect(explode('.', $df->filename))->last()) === 'pdf',
                                                'deleteUrl' => route('delete.revision.document', $df->id),
                                                'created_at' => $df->created_at->timezone('Asia/Manila')->format('F d, Y \a\t h:i A')
                                            ];
                                        })->toJson();
                                    @endphp

                                    <div x-data="revisionDropzone({ 
                                        category: '{{ $file->category }}', 
                                        uploadUrl: '{{ route('upload.revision.document', $researchTitle->id) }}',
                                        csrfToken: '{{ csrf_token() }}',
                                        initialFiles: {{ $initialFilesJson }}
                                    })" 
                                    class="bg-white rounded-2xl border p-5 relative transition-all duration-300"
                                    :class="[isDragging ? 'border-orange-400 bg-orange-50 ring-4 ring-orange-100 scale-[1.02]' : (files.length > 0 ? 'border-emerald-300 ring-2 ring-emerald-50' : 'border-orange-200 border-dashed')]"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="handleDrop($event)">

                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors border"
                                                    :class="files.length > 0 ? 'bg-emerald-100 text-emerald-600 border-emerald-200 shadow-inner' : 'bg-orange-100 text-orange-500 border-orange-200 shadow-inner'">
                                                    <i class="fas" :class="files.length > 0 ? 'fa-check' : 'fa-file-upload'"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-sm text-slate-800 leading-snug">{{ $file->category }}</h4>
                                                    <template x-if="files.length > 0">
                                                        <p class="text-[10px] text-slate-400 mt-0.5" x-text="'Last updated on ' + getLatestDate()"></p>
                                                    </template>
                                                </div>
                                            </div>
                                            <span x-show="files.length > 0" x-text="files.length + ' file(s)'" class="text-[10px] font-bold px-2 py-1 rounded-md bg-slate-100 text-slate-500 shadow-sm border border-slate-200"></span>
                                        </div>

                                        <!-- Uploaded Files List -->
                                        <div class="space-y-2 mb-3" x-show="files.length > 0">
                                            <template x-for="(f, index) in files" :key="f.id || index">
                                                <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-200 flex items-center justify-between group shadow-sm transition-all animate-[fadeIn_0.2s_ease-out]">
                                                    <div class="flex items-center min-w-0 flex-1 gap-2">
                                                        <div class="w-7 h-7 bg-white rounded-md flex items-center justify-center shadow-sm border border-emerald-100 flex-shrink-0">
                                                            <i class="fas text-emerald-600 text-sm" :class="f.isPdf ? 'fa-file-pdf' : 'fa-file'"></i> 
                                                        </div>
                                                        <p class="text-[11px] font-bold text-emerald-800 truncate" x-text="f.filename"></p>
                                                    </div>
                                                    <button type="button" @click.prevent="removeFile(f, index)" class="text-emerald-600/50 hover:text-red-500 p-1.5 bg-white rounded-lg opacity-0 group-hover:opacity-100 transition-all border border-transparent hover:border-red-200 shadow-sm hover:shadow active:scale-95">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Loading State -->
                                        <div x-show="isUploading" class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-xl flex items-center gap-3 shadow-inner">
                                            <div class="w-8 h-8 bg-white border border-blue-100 shadow-sm rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-spinner fa-spin text-blue-500"></i>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-bold text-blue-800">Uploading File(s)...</p>
                                                <p class="text-[9px] text-blue-600 mt-0.5 font-medium">Please wait while transferring.</p>
                                            </div>
                                        </div>

                                        <!-- Drop/Browse action -->
                                        <label class="block cursor-pointer">
                                            <div class="w-full py-4 rounded-xl border-2 border-dashed border-orange-200 hover:border-orange-400 hover:bg-orange-50 text-orange-600 font-bold text-center transition-all flex flex-col items-center justify-center gap-2 group shadow-sm bg-white hover:shadow-md">
                                                <i class="fas fa-cloud-upload-alt text-2xl group-hover:-translate-y-1 transition-transform text-orange-400 group-hover:text-orange-600"></i>
                                                <span class="text-xs">Click to Browse or Drag Files</span>
                                                <span class="text-[9px] font-extrabold text-orange-500/80 bg-orange-100/50 px-2 rounded-full uppercase tracking-wider">Multiple Uploads Allowed</span>
                                            </div>
                                            <input type="file" class="hidden" multiple @change="handleFiles($event.target.files)" accept=".{{ strtolower($file->filetype) }}">
                                        </label>
                                    </div>
                                @endforeach
                @endif
            </div>
        @endif



    </main>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Async File Upload Alpine Component -->
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('revisionDropzone', (config) => ({
            category: config.category,
            uploadUrl: config.uploadUrl,
            csrfToken: config.csrfToken,
            files: config.initialFiles || [],
            isDragging: false,
            isUploading: false,
            
            getLatestDate() {
                if (this.files.length === 0) return '';
                const lastFile = this.files[this.files.length - 1];
                return lastFile.created_at || 'just now';
            },
            
            handleDrop(e) {
                this.isDragging = false;
                if (e.dataTransfer.files.length > 0) {
                    this.handleFiles(e.dataTransfer.files);
                }
            },
            
            async handleFiles(fileList) {
                this.isUploading = true;
                const promises = [];
                
                for (let i = 0; i < fileList.length; i++) {
                    promises.push(this.uploadFile(fileList[i]));
                }
                
                await Promise.all(promises);
                this.isUploading = false;
            },
            
            async uploadFile(file) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('category', this.category);
                formData.append('_token', this.csrfToken);
                
                // Show Progress Modal
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

                const formatBytes = (bytes, decimals = 2) => {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                };

                try {
                    const xhr = new XMLHttpRequest();
                    
                    const uploadPromise = new Promise((resolve, reject) => {
                        xhr.upload.addEventListener('progress', (e) => {
                            if (e.lengthComputable) {
                                const percentComplete = Math.round((e.loaded / e.total) * 100);
                                if (progressBar) progressBar.style.width = percentComplete + '%';
                                if (percentageText) percentageText.textContent = percentComplete + '%';
                                if (sizeText) sizeText.textContent = `${formatBytes(e.loaded)} / ${formatBytes(e.total)}`;
                            }
                        });

                        xhr.onload = () => {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                resolve(JSON.parse(xhr.responseText));
                            } else {
                                let errorMsg = 'Upload failed';
                                try {
                                    const errorData = JSON.parse(xhr.responseText);
                                    errorMsg = errorData.message || errorData.error || errorMsg;
                                } catch(e) {}
                                reject(new Error(errorMsg));
                            }
                        };

                        xhr.onerror = () => reject(new Error('Network error'));
                    });

                    xhr.open('POST', this.uploadUrl);
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.send(formData);

                    const data = await uploadPromise;
                    if (data.success) {
                        this.files.push(data.file);
                    } else {
                        alert('Upload failed: ' + (data.error || 'Unknown error'));
                    }
                } catch (error) {
                    console.error(error);
                    alert('Upload error: ' + error.message);
                } finally {
                    if (progressModal) progressModal.classList.add('hidden');
                }
            },
            
            async removeFile(fileObj, index) {
                const result = await Swal.fire({
                    title: 'Remove Document?',
                    text: 'Are you sure you want to delete this draft file?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', 
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-4 py-2 font-bold',
                        cancelButton: 'rounded-xl px-4 py-2 font-bold'
                    }
                });

                if (!result.isConfirmed) return;
                
                // Remove from UI optimistically
                this.files.splice(index, 1);
                
                try {
                    const response = await fetch(fileObj.deleteUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    });
                    
                    const data = await response.json();
                    if(!data.success) {
                        // re-add if failed
                        this.files.splice(index, 0, fileObj);
                        alert('Failed to remove: ' + data.error);
                    }
                } catch (e) {
                    this.files.splice(index, 0, fileObj);
                    alert('Error removing file');
                }
            }
        }));
    });
    </script>

    <!-- Upload Progress Modal -->
    <div id="upload-progress-modal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    <div class="px-8 py-10">
                        <div class="flex flex-col items-center text-center">
                            <!-- Animated Icon -->
                            <div class="relative w-24 h-24 mb-8">
                                <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                                <div class="absolute inset-0 border-4 border-[#8B0000] rounded-full border-t-transparent animate-spin"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-[#8B0000] animate-bounce"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Uploading Research Files</h3>
                            <p class="text-slate-500 text-sm mb-8 max-w-sm">Please do not close this window or refresh the page. We are securely transferring your documents to our servers.</p>
                            <!-- Progress Bar Container -->
                            <div class="w-full bg-slate-100 rounded-full h-4 mb-4 relative overflow-hidden shadow-inner">
                                <div id="upload-progress-bar" class="bg-gradient-to-r from-[#8B0000] to-red-600 h-full w-0 transition-all duration-300 ease-out shadow-lg relative">
                                    <div class="absolute inset-0 bg-white/20 animate-shimmer"></div>
                                </div>
                            </div>
                            <!-- Progress Stats -->
                            <div class="flex justify-between w-full text-sm font-bold">
                                <span id="upload-percentage" class="text-[#8B0000]">0%</span>
                                <span id="upload-size" class="text-slate-400">0 KB / 0 KB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite;
        }
    </style>
</x-user_layout>