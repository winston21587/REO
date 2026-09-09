<x-admin_layout :title="'Initial Intake Queue'">
    <style>
        /* Impeccable Polish: Institutional Browser Surfaces & Custom Scrollbars */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }


        /* Impeccable Polish: Accessible Prefers-Reduced-Motion Accommodation */
        @media (prefers-reduced-motion: reduce) {
            *, ::before, ::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto w-full min-h-[calc(100vh-5rem)] flex flex-col justify-between animate-[fadeInUp_0.5s_ease-out] selection:bg-[#8B0000] selection:text-white pt-3 sm:pt-4"
         x-data="{ 
             activeTab: '{{ request('incomplete_page') || request('incomplete_search') || request('incomplete_sort') ? 'incomplete' : 'recent' }}' 
         }">

        <!-- Accessible Page Landmark (Visually Hidden per user directive to eliminate top intro banner) -->
        <h1 class="sr-only">Initial Intake Queue</h1>

        <!-- Mobile/Tablet Adaptive Queue Segmented Switcher (Visible only below lg) -->
        <div class="lg:hidden mb-3.5 flex p-1 bg-slate-100/90 rounded-xl border border-slate-300" role="tablist" aria-label="Submission Queue Selection">
            <button type="button" 
                role="tab"
                id="tab-recent"
                :aria-selected="activeTab === 'recent'"
                aria-controls="recent-column"
                aria-label="Recent Submissions queue, {{ $pendingSubmissions->total() }} pending"
                @click="activeTab = 'recent'"
                :class="activeTab === 'recent' ? 'bg-white text-slate-950 shadow-xs font-extrabold border-slate-300' : 'text-slate-700 hover:text-slate-950 font-semibold border-transparent'"
                class="flex-1 py-2 px-2.5 min-h-[40px] rounded-lg text-xs transition-all border flex items-center justify-center gap-1.5 touch-manipulation active:scale-[0.98] focus:outline-none focus:ring-1 focus:ring-[#8B0000]">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0" aria-hidden="true"></span>
                <span class="truncate">Recent</span>
                <span class="tabular-nums px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-950 border border-amber-300 shrink-0">
                    {{ $pendingSubmissions->total() }}
                </span>
            </button>
            <button type="button" 
                role="tab"
                id="tab-incomplete"
                :aria-selected="activeTab === 'incomplete'"
                aria-controls="incomplete-column"
                aria-label="Incomplete Submissions queue, {{ $incompleteSubmissions->total() }} items"
                @click="activeTab = 'incomplete'"
                :class="activeTab === 'incomplete' ? 'bg-white text-slate-950 shadow-xs font-extrabold border-slate-300' : 'text-slate-700 hover:text-slate-950 font-semibold border-transparent'"
                class="flex-1 py-2 px-2.5 min-h-[40px] rounded-lg text-xs transition-all border flex items-center justify-center gap-1.5 touch-manipulation active:scale-[0.98] focus:outline-none focus:ring-1 focus:ring-[#8B0000]">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0" aria-hidden="true"></span>
                <span class="truncate">Incomplete</span>
                <span class="tabular-nums px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-950 border border-rose-300 shrink-0">
                    {{ $incompleteSubmissions->total() }}
                </span>
            </button>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch flex-1">

            <!-- Recent Submissions Column -->
            <div id="recent-column"
                role="tabpanel"
                aria-labelledby="tab-recent"
                :class="activeTab === 'recent' ? 'flex' : 'hidden lg:flex'"
                class="flex flex-col space-y-3 h-full">
                <div>
                    <!-- Column Header -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-heading leading-tight">Recent Submissions</h2>
                            <span class="tabular-nums px-3 py-0.5 text-xs sm:text-sm font-extrabold rounded-full bg-slate-900 text-white shadow-2xs">
                                {{ $pendingSubmissions->total() }}
                            </span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm font-medium text-slate-600 mt-1">New protocols and resubmissions ready for completeness screening.</p>

                    <!-- Controls Toolbar -->
                    <div class="flex gap-2 mt-2.5" x-data="{ expanded: false }">
                        <div class="relative flex-1">
                            <label for="recent_search_input" class="sr-only">Search recent submissions by protocol title</label>
                            <input type="text" name="recent_search" id="recent_search_input"
                                value="{{ request('recent_search') }}" placeholder="Search submissions..."
                                aria-label="Search recent submissions by protocol title"
                                class="w-full pl-8 pr-3 py-1.5 h-9 min-h-[36px] border border-slate-300 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent bg-white placeholder:text-slate-400 caret-[#8B0000] transition-colors shadow-2xs">
                            <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs" aria-hidden="true"></i>
                        </div>
                        <div class="relative">
                            <button type="button" @click="expanded = !expanded" @click.outside="expanded = false"
                                :aria-expanded="expanded ? 'true' : 'false'"
                                aria-haspopup="true"
                                aria-label="Sort options for recent submissions"
                                class="flex items-center gap-1.5 px-3 py-1.5 h-9 min-h-[36px] border border-slate-300 rounded-xl text-xs font-bold text-slate-800 bg-white hover:bg-slate-50 hover:text-slate-950 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#8B0000] transition-colors justify-between sm:w-[125px] shadow-2xs cursor-pointer">
                                <span class="flex items-center gap-1.5"><i class="fas fa-arrow-down-short-wide text-slate-500 text-xs" aria-hidden="true"></i> <span>Sort</span></span>
                                <i class="fas fa-chevron-down text-xs text-slate-500 transition-transform duration-200"
                                    :class="expanded ? 'rotate-180' : ''" aria-hidden="true"></i>
                            </button>

                            <!-- Advanced Dropdown -->
                            <div x-show="expanded" x-cloak x-transition.opacity.duration.200ms @click.stop
                                class="absolute right-0 mt-1.5 w-52 bg-white border border-slate-200 rounded-xl shadow-lg z-50 overflow-hidden">
                                <div class="p-2.5 border-b border-slate-100 bg-slate-50/70">
                                    <span class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-1.5">Sort Submissions</span>
                                    <div class="space-y-1.5">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" name="recent_sort" value="created_at"
                                                class="recent-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('recent_sort', 'created_at') == 'created_at' ? 'checked' : '' }}>
                                            <span class="text-xs font-semibold text-slate-800 group-hover:text-[#8B0000] transition-colors">Date (Newest First)</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" name="recent_sort" value="Title"
                                                class="recent-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('recent_sort') == 'Title' ? 'checked' : '' }}>
                                            <span class="text-xs font-semibold text-slate-800 group-hover:text-[#8B0000] transition-colors">Title (Alphabetical)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submissions List -->
                <div id="recent-submissions-wrapper" class="flex flex-col flex-1 min-h-[400px]">
                    @include('admin.partials.recent_submissions_list')
                </div>
            </div>

            <!-- Incomplete Submissions Column -->
            <div id="incomplete-column"
                role="tabpanel"
                aria-labelledby="tab-incomplete"
                :class="activeTab === 'incomplete' ? 'flex' : 'hidden lg:flex'"
                class="flex flex-col space-y-3 h-full">
                <div>
                    <!-- Column Header -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-heading leading-tight">Incomplete Submissions</h2>
                            <span class="tabular-nums px-3 py-0.5 text-xs sm:text-sm font-extrabold rounded-full bg-slate-200 text-slate-800 shadow-2xs">
                                {{ $incompleteSubmissions->total() }}
                            </span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm font-medium text-slate-600 mt-1">Protocols returned for missing requirements or awaiting revision.</p>

                    <!-- Controls Toolbar -->
                    <div class="flex gap-2 mt-2.5" x-data="{ expanded: false }">
                        <div class="relative flex-1">
                            <label for="incomplete_search_input" class="sr-only">Search incomplete submissions by protocol title</label>
                            <input type="text" name="incomplete_search" id="incomplete_search_input"
                                value="{{ request('incomplete_search') }}" placeholder="Search incomplete..."
                                aria-label="Search incomplete submissions by protocol title"
                                class="w-full pl-8 pr-3 py-1.5 h-9 min-h-[36px] border border-slate-300 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent bg-white placeholder:text-slate-400 caret-[#8B0000] transition-colors shadow-2xs">
                            <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs" aria-hidden="true"></i>
                        </div>
                        <div class="relative">
                            <button type="button" @click="expanded = !expanded" @click.outside="expanded = false"
                                :aria-expanded="expanded ? 'true' : 'false'"
                                aria-haspopup="true"
                                aria-label="Sort options for incomplete submissions"
                                class="flex items-center gap-1.5 px-3 py-1.5 h-9 min-h-[36px] border border-slate-300 rounded-xl text-xs font-bold text-slate-800 bg-white hover:bg-slate-50 hover:text-slate-950 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#8B0000] transition-colors justify-between sm:w-[125px] shadow-2xs cursor-pointer">
                                <span class="flex items-center gap-1.5"><i class="fas fa-arrow-down-short-wide text-slate-500 text-xs" aria-hidden="true"></i> <span>Sort</span></span>
                                <i class="fas fa-chevron-down text-xs text-slate-500 transition-transform duration-200"
                                    :class="expanded ? 'rotate-180' : ''" aria-hidden="true"></i>
                            </button>

                            <!-- Advanced Dropdown -->
                            <div x-show="expanded" x-cloak x-transition.opacity.duration.200ms @click.stop
                                class="absolute right-0 mt-1.5 w-52 bg-white border border-slate-200 rounded-xl shadow-lg z-[60] overflow-hidden">
                                <div class="p-2.5 border-b border-slate-100 bg-slate-50/70">
                                    <span class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-1.5">Sort Submissions</span>
                                    <div class="space-y-1.5">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" name="incomplete_sort" value="created_at"
                                                class="incomplete-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('incomplete_sort', 'created_at') == 'created_at' ? 'checked' : '' }}>
                                            <span class="text-xs font-semibold text-slate-800 group-hover:text-[#8B0000] transition-colors">Date (Newest First)</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" name="incomplete_sort" value="Title"
                                                class="incomplete-filter-input text-[#8B0000] focus:ring-[#8B0000]" {{ request('incomplete_sort') == 'Title' ? 'checked' : '' }}>
                                            <span class="text-xs font-semibold text-slate-800 group-hover:text-[#8B0000] transition-colors">Title (Alphabetical)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submissions List -->
                <div id="incomplete-submissions-wrapper" class="flex flex-col flex-1 min-h-[460px]">
                        @include('admin.partials.incomplete_submissions_list')
                </div>
            </div>

        </div>
    </div>

    <!-- Triage Modal (Wide Ergonomic Layout) -->
    <div id="triageModal"
        class="hidden fixed inset-0 z-[60] flex items-end sm:items-center justify-center bg-slate-900/60 backdrop-blur-sm p-0 sm:p-4 opacity-0 transition-opacity duration-300 will-change-[opacity]"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl w-full sm:max-w-3xl lg:max-w-5xl xl:max-w-5xl overflow-hidden transform scale-95 transition-transform duration-300 max-h-[94vh] sm:max-h-[90vh] flex flex-col will-change-[transform,opacity]"
            id="modalContent">
            <div class="bg-[#1a0505] p-4 sm:p-6 border-b border-white/10 relative overflow-hidden flex-shrink-0">
                <!-- Mobile Bottom Sheet Drag Handle Indicator -->
                <div class="sm:hidden w-12 h-1.5 bg-white/25 rounded-full mx-auto mb-3" aria-hidden="true"></div>

                <div class="flex items-start justify-between">
                    <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none" aria-hidden="true">
                        <i class="fas fa-gavel text-7xl text-white"></i>
                    </div>
                    <div class="relative z-10 pr-3 sm:pr-4">
                        <h2 class="text-white font-extrabold text-xl sm:text-2xl leading-tight" id="modal-title">Completeness & Intake Triage</h2>
                        <p class="text-slate-200 text-xs sm:text-sm mt-1 leading-relaxed line-clamp-2 sm:line-clamp-none font-medium">Verify document presence, official receipt validity, and researcher CV status before routing to committee review.</p>
                    </div>
                    <button type="button" onclick="closeTriage()" aria-label="Close triage dialog"
                        class="relative z-10 text-white/80 hover:text-white active:scale-95 w-11 h-11 min-w-[44px] min-h-[44px] flex items-center justify-center rounded-xl hover:bg-white/10 transition-all focus:outline-none focus:ring-2 focus:ring-white/40 shrink-0 touch-manipulation">
                        <i class="fas fa-times text-base sm:text-lg" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <form id="triageForm" method="POST" action="" class="flex flex-col flex-1 overflow-hidden" novalidate>
                @csrf
                <div class="p-4 sm:p-6 lg:p-7 overflow-y-auto flex-1 custom-scrollbar">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- Left Panel: Protocol Identity & Evidence Verification (Span 6) -->
                        <div class="lg:col-span-6 space-y-5">
                            <!-- Submission Identity Card -->
                            <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-4 shadow-xs">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                                    <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i class="fas fa-id-card text-slate-400" aria-hidden="true"></i> Submission Identity
                                    </span>
                                    <span class="text-[11px] font-bold text-slate-500 bg-white px-2 py-0.5 rounded-md border border-slate-200/60">Stage 1 Intake</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                                            <i class="far fa-user text-slate-400 mr-1.5" aria-hidden="true"></i> Principal Investigator
                                        </span>
                                        <p id="modalResearcherName"
                                            class="text-slate-900 font-bold bg-white p-2.5 rounded-xl border border-slate-200/90 text-xs truncate shadow-xs">
                                        </p>
                                    </div>
                                    <div>
                                        <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                                            <i class="far fa-envelope text-slate-400 mr-1.5" aria-hidden="true"></i> Contact Email
                                        </span>
                                        <p id="modalResearcherEmail"
                                            class="text-slate-900 font-bold bg-white p-2.5 rounded-xl border border-slate-200/90 text-xs truncate shadow-xs font-mono">
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                                        <i class="fas fa-book-open text-slate-400 mr-1.5" aria-hidden="true"></i> Protocol Title
                                    </span>
                                    <p id="modalTitle"
                                        class="text-slate-900 font-bold bg-white p-3 rounded-xl border border-slate-200/90 text-xs leading-relaxed shadow-xs break-words max-h-28 overflow-y-auto custom-scrollbar">
                                    </p>
                                </div>
                            </div>

                            <!-- OR Verification Section -->
                            <div id="orVerificationField" class="hidden transition-all duration-300">
                                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 space-y-3 shadow-xs">
                                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-receipt text-indigo-500" aria-hidden="true"></i> Official Receipt (OR) Verification
                                        </label>
                                    </div>
                                    <!-- OR Info Display -->
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100/80 flex items-center justify-center text-indigo-600 shrink-0">
                                                <i class="fas fa-file-invoice" aria-hidden="true"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Official Receipt File</p>
                                                <p id="modalOrNumber" class="text-sm font-bold font-mono text-slate-800 truncate">Not Provided</p>
                                            </div>
                                        </div>
                                        <a id="modalOrFileLink" href="#" target="_blank"
                                            aria-label="View uploaded receipt in new tab"
                                            class="hidden px-3.5 py-2 min-h-[44px] bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-xl text-xs font-bold hover:bg-indigo-100 transition-colors inline-flex items-center justify-center gap-1.5 shrink-0 touch-manipulation"
                                            title="View Receipt">
                                            <i class="fas fa-external-link-alt text-[11px]" aria-hidden="true"></i> View File
                                        </a>
                                    </div>

                                    <!-- Verified Badge (read-only, shown if already verified) -->
                                    <div id="orAlreadyVerified" class="hidden">
                                        <div class="flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl">
                                            <i class="fas fa-check-circle text-emerald-500" aria-hidden="true"></i>
                                            <span class="text-xs font-bold text-emerald-700">Receipt Verified (Logged in Revenue)</span>
                                        </div>
                                    </div>

                                    <div id="orVerifyCheckboxContainer" class="hidden">
                                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors group">
                                            <input type="checkbox" id="verifyOrCheckbox" name="verify_or" value="1"
                                                onchange="refreshCompleteOptionLock()"
                                                class="w-5 h-5 text-emerald-600 focus:ring-emerald-500 rounded border-slate-300">
                                            <div>
                                                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900 transition-colors">Verify this receipt</span>
                                                <p class="text-[11px] text-slate-500 mt-0.5">Confirm payment for ethics review fee and log to revenue records.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- No Receipt Warning -->
                                <div id="orNoReceipt"
                                    class="hidden mt-3 flex items-start gap-2.5 p-3.5 bg-amber-50 border border-amber-200 rounded-xl shadow-xs">
                                    <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5 shrink-0" aria-hidden="true"></i>
                                    <p class="text-xs text-amber-900 font-medium leading-relaxed">No Official Receipt was submitted with this application. Mark as incomplete or require receipt before acceptance.</p>
                                </div>
                            </div>

                            <!-- CV Verification Section -->
                            <div id="cvVerificationField" class="hidden transition-all duration-300">
                                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 space-y-3 shadow-xs">
                                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-user-graduate text-blue-500" aria-hidden="true"></i> Researcher Classification & CV Verification
                                        </label>
                                    </div>

                                    <!-- Already Valid Badge -->
                                    <div id="cvAlreadyValid" class="hidden">
                                        <div class="flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl">
                                            <i class="fas fa-check-circle text-emerald-500" aria-hidden="true"></i>
                                            <span class="text-xs font-bold text-emerald-700">Classification & CV Verified</span>
                                        </div>
                                    </div>

                                    <!-- Already Invalid Badge -->
                                    <div id="cvAlreadyInvalid" class="hidden">
                                        <div class="flex items-center gap-2 px-3 py-2 bg-rose-50 border border-rose-200 rounded-xl">
                                            <i class="fas fa-times-circle text-rose-500" aria-hidden="true"></i>
                                            <span class="text-xs font-bold text-rose-800">Classification Marked as Invalid</span>
                                        </div>
                                    </div>

                                    <!-- Pending Options -->
                                    <div id="cvPendingOptions" class="hidden space-y-2">
                                        <p class="text-[11px] text-slate-500 italic mb-1">Confirm that researcher status matches their uploaded CV in View Details.</p>
                                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 transition-colors group">
                                            <input type="radio" name="cv_action" id="cv_action_verify" value="verify" onchange="refreshCompleteOptionLock()" class="text-emerald-600 focus:ring-emerald-500">
                                            <div>
                                                <span class="text-sm font-bold text-slate-700">Verify CV</span>
                                                <p class="text-[11px] text-slate-500 mt-0.5">Classification matches uploaded curriculum vitae.</p>
                                            </div>
                                        </label>
                                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 hover:bg-rose-50 hover:border-rose-200 transition-colors group">
                                            <input type="radio" name="cv_action" id="cv_action_invalidate" value="invalidate" class="text-rose-600 focus:ring-rose-500" onchange="toggleCvRemarks(true); refreshCompleteOptionLock()">
                                            <div>
                                                <span class="text-sm font-bold text-slate-700">Mark as Invalid</span>
                                                <p class="text-[11px] text-slate-500 mt-0.5">Mismatch detected between classification and CV.</p>
                                            </div>
                                        </label>
                                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors group">
                                            <input type="radio" name="cv_action" id="cv_action_skip" value="" checked class="text-slate-500 focus:ring-slate-400" onchange="toggleCvRemarks(false); refreshCompleteOptionLock()">
                                            <div>
                                                <span class="text-sm font-bold text-slate-700">Skip for now</span>
                                                <p class="text-[11px] text-slate-500 mt-0.5">Verify later during full document examination.</p>
                                            </div>
                                        </label>

                                        <!-- CV Remarks (shown when invalidate is selected) -->
                                        <div id="cvRemarksField" class="hidden mt-2">
                                            <label for="cv_remarks" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Reason for Invalidation <span class="text-rose-500">*</span></label>
                                            <textarea name="cv_remarks" id="cv_remarks" rows="3" maxlength="1000"
                                                class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100 resize-none caret-[#8B0000]"
                                                placeholder="Specify reason (e.g., Selected 'Undergraduate Student' but attached CV indicates faculty status)..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Completeness Decision & Conditional Actions (Span 6) -->
                        <div class="lg:col-span-6 space-y-5">
                            <!-- Decision Card -->
                            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-3">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fas fa-balance-scale text-[#8B0000]" aria-hidden="true"></i> Completeness Determination
                                    </label>
                                    <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Required Decision</span>
                                </div>

                                <div class="space-y-3">
                                    <label id="label_complete"
                                        class="group flex items-start gap-3.5 p-3.5 sm:p-4 border border-slate-200 rounded-xl cursor-pointer transition-all relative overflow-hidden bg-white hover:border-slate-300"
                                        onclick="selectOption('Complete')">
                                        <div id="bar_complete"
                                            class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-600 opacity-0 transition-opacity">
                                        </div>
                                        <input type="radio" name="classification" value="Complete"
                                            class="mt-1 text-emerald-700 focus:ring-emerald-600"
                                            onchange="toggleAppointment(true); selectOption('Complete')">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span id="text_complete"
                                                    class="block font-bold text-slate-800 text-sm transition-colors">Complete Submission</span>
                                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800">Pass</span>
                                            </div>
                                            <span class="block text-xs text-slate-500 mt-1 leading-relaxed">All mandatory forms, protocol files, and required documents are verified.</span>
                                            <p id="completeLockHint" class="hidden text-[11px] font-semibold text-amber-800 bg-amber-50 px-2.5 py-1.5 rounded-lg border border-amber-200 mt-2 flex items-center gap-1.5">
                                                <i class="fas fa-lock text-amber-600 text-xs shrink-0" aria-hidden="true"></i>
                                                <span>Official Receipt and CV verification required before approving as Complete.</span>
                                            </p>
                                        </div>
                                    </label>

                                    <label id="label_incomplete"
                                        class="group flex items-start gap-3.5 p-3.5 sm:p-4 border border-slate-200 rounded-xl cursor-pointer transition-all relative overflow-hidden bg-white hover:border-slate-300"
                                        onclick="selectOption('Incomplete')">
                                        <div id="bar_incomplete"
                                            class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-600 opacity-0 transition-opacity">
                                        </div>
                                        <input type="radio" name="classification" value="Incomplete"
                                            class="mt-1 text-rose-700 focus:ring-rose-600" checked onchange="toggleAppointment(false); selectOption('Incomplete')">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span id="text_incomplete"
                                                    class="block font-bold text-slate-800 text-sm transition-colors">Incomplete Submission</span>
                                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-rose-100 text-rose-800">Action Required</span>
                                            </div>
                                            <span class="block text-xs text-slate-500 mt-1 leading-relaxed">Return protocol to researcher with specific document deficiencies and revision notes.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Appointment Field (Complete Decision) -->
                            <div id="appointmentField"
                                class="transition-all duration-300 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-2">
                                <label for="appointment_date_input"
                                    class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                                    <i class="far fa-calendar-alt text-emerald-600" aria-hidden="true"></i> Hardcopy Deadline & Intake Appointment
                                </label>
                                <p class="text-xs text-slate-500 leading-relaxed">Researcher will be given until this date to deliver physical hardcopies of the approved intake package to the REO office.</p>
                                <input type="date" name="appointment_date" id="appointment_date_input"
                                    min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                                    max="{{ date('Y-m-d', strtotime('+1 year')) }}"
                                    class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-xs bg-slate-50 font-medium text-slate-700 caret-[#8B0000]">
                            </div>

                            <!-- Incomplete Fields (Incomplete Decision) -->
                            <div id="incompleteFields" class="hidden opacity-0 transition-all duration-300 space-y-4">
                                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <label for="intake_remarks_input" class="block text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                                                <i class="far fa-comment-dots text-rose-500" aria-hidden="true"></i> Instructions & Remarks for Researcher
                                            </label>
                                            <span id="remarksCharCounter" class="text-xs text-slate-500 font-mono" aria-live="polite">0 / 3000</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mb-2">These notes will be delivered to the researcher and recorded in the protocol's revision history.</p>
                                        <textarea name="remarks" id="intake_remarks_input" rows="3" maxlength="3000"
                                            class="w-full p-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] shadow-xs placeholder-slate-400 caret-[#8B0000] resize-none"
                                            placeholder="Explain what requirements are missing, illegible, or need revision before submission can be accepted..."></textarea>
                                    </div>

                                    <div>
                                        <label for="reqInput" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <i class="fas fa-tasks text-rose-500" aria-hidden="true"></i> Document Deficiencies & Missing Requirements
                                        </label>
                                        <p class="text-[11px] text-slate-500 mb-2">Tag the specific documents that must be replaced, updated, or submitted upon revision.</p>

                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="relative flex-1">
                                                <input type="text" id="reqInput" list="requirementsOptions" maxlength="255"
                                                    class="w-full p-2.5 pr-8 min-h-[44px] border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all touch-manipulation caret-[#8B0000]"
                                                    placeholder="Select or type missing requirement...">
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none">
                                                    <i class="fas fa-search text-slate-400 text-xs" aria-hidden="true"></i>
                                                </div>
                                                <datalist id="requirementsOptions">
                                                    @foreach($requirements as $req)
                                                         <option value="{{ $req->name }}">
                                                    @endforeach
                                                </datalist>
                                            </div>
                                            <button type="button" onclick="addRequirement()"
                                                class="px-4 py-2.5 min-h-[44px] bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-bold text-xs rounded-xl transition-all border border-slate-200 flex items-center justify-center gap-1.5 shrink-0 touch-manipulation focus:outline-none focus:ring-2 focus:ring-[#8B0000]"
                                                aria-label="Add missing requirement to list">
                                                <i class="fas fa-plus text-xs" aria-hidden="true"></i>
                                                <span>Add</span>
                                            </button>
                                        </div>

                                        <div id="requirementsList" class="space-y-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="p-4 sm:p-6 pb-[max(1rem,env(safe-area-inset-bottom))] border-t border-slate-100 bg-slate-50/80 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 flex-shrink-0">
                    <p class="text-xs text-slate-500 hidden sm:flex items-center gap-1.5">
                        <i class="fas fa-info-circle text-slate-400" aria-hidden="true"></i>
                        <span>Review all documents thoroughly before submitting triage determination.</span>
                    </p>
                    <div class="flex flex-col-reverse sm:flex-row items-center gap-2.5 sm:gap-3 w-full sm:w-auto">
                        <button type="button" onclick="closeTriage()"
                            class="w-full sm:w-auto px-5 py-2.5 min-h-[44px] flex items-center justify-center text-slate-600 font-bold text-sm hover:bg-white hover:text-slate-800 active:scale-[0.98] rounded-xl transition-all border border-transparent hover:border-slate-200 text-center touch-manipulation">Cancel</button>
                        <button type="submit" id="triageSubmitBtn"
                            class="w-full sm:w-auto px-6 py-2.5 min-h-[44px] bg-gradient-to-r from-[#8B0000] to-[#a30000] hover:from-[#780000] hover:to-[#8B0000] text-white font-bold text-sm rounded-xl shadow-md shadow-red-950/20 active:scale-[0.98] transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 flex items-center justify-center gap-2 touch-manipulation">
                            <i id="triageSubmitBtnIcon" class="fas fa-undo-alt text-white/90" aria-hidden="true"></i>
                            <span id="triageSubmitBtnText">Return with Deficiencies</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- Hardened Requirement List Logic (Safe DOM, Zero innerHTML Injection) ---
        function addRequirement() {
            const input = document.getElementById('reqInput');
            const container = document.getElementById('requirementsList');
            if (!input || !container) return;
            let value = input.value.trim();

            if (!value) return;
            if (value.length > 255) {
                value = value.substring(0, 255);
            }

            // Prevent duplicate entries (case-insensitive)
            const existingValues = [...container.querySelectorAll('input[name="missing_requirements[]"]')]
                .map(el => el.value.toLowerCase());
            if (existingValues.includes(value.toLowerCase())) {
                // Find and flash the existing item
                [...container.querySelectorAll('div[id^="req_"]')].forEach(el => {
                    const span = el.querySelector('span');
                    if (span && span.textContent.toLowerCase() === value.toLowerCase()) {
                        el.classList.add('ring-2', 'ring-red-400', 'bg-red-50');
                        setTimeout(() => el.classList.remove('ring-2', 'ring-red-400', 'bg-red-50'), 1200);
                    }
                });
                // Show inline hint below the datalist input
                let hint = document.getElementById('reqDuplicateHint');
                if (!hint) {
                    hint = document.createElement('p');
                    hint.id = 'reqDuplicateHint';
                    hint.className = 'text-xs text-red-500 font-bold mt-1';
                    const relativeContainer = input.closest('.relative');
                    if (relativeContainer) {
                        relativeContainer.insertAdjacentElement('afterend', hint);
                    }
                }
                hint.textContent = `"${value}" is already in the list.`;
                setTimeout(() => { if (hint) hint.textContent = ''; }, 2200);
                input.value = '';
                input.focus();
                return;
            }

            // Clear any previous hint
            const hint = document.getElementById('reqDuplicateHint');
            if (hint) hint.textContent = '';

            // Generate unique ID
            const id = 'req_' + Date.now() + '_' + Math.random().toString(36).substring(2, 7);

            // Create container element safely without raw HTML string interpolation
            const item = document.createElement('div');
            item.id = id;
            item.className = 'flex items-center justify-between bg-rose-50/70 p-2.5 px-3 rounded-xl border border-rose-200/90 shadow-sm animate-[fadeIn_0.3s_ease-out] group hover:border-rose-300 transition-all duration-300';

            const leftWrapper = document.createElement('div');
            leftWrapper.className = 'flex items-center gap-2.5 overflow-hidden';

            const dot = document.createElement('div');
            dot.className = 'w-2 h-2 rounded-full bg-rose-500 flex-shrink-0';
            dot.setAttribute('aria-hidden', 'true');

            const span = document.createElement('span');
            span.className = 'text-xs font-bold text-rose-950 truncate';
            span.textContent = value;
            span.title = value;

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'missing_requirements[]';
            hiddenInput.value = value;

            leftWrapper.appendChild(dot);
            leftWrapper.appendChild(span);
            leftWrapper.appendChild(hiddenInput);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'text-rose-400 hover:text-rose-700 transition-colors p-1 opacity-70 hover:opacity-100 focus:opacity-100 focus:outline-none focus:ring-1 focus:ring-rose-400 rounded';
            removeBtn.setAttribute('aria-label', `Remove requirement: ${value}`);
            removeBtn.innerHTML = '<i class="fas fa-times text-xs" aria-hidden="true"></i>';
            removeBtn.addEventListener('click', () => removeRequirement(id));

            item.appendChild(leftWrapper);
            item.appendChild(removeBtn);
            container.appendChild(item);

            input.value = '';
            input.focus();
        }

        function removeRequirement(id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.opacity = '0';
                el.style.transform = 'translateX(10px)';
                setTimeout(() => el.remove(), 200);
            }
        }

        // Allow "Enter" key to add item manually with keydown (hardened against mobile IME/virtual keyboards)
        const reqInputElement = document.getElementById('reqInput');
        if (reqInputElement) {
            reqInputElement.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addRequirement();
                }
            });

            // Auto-add when a datalist option is selected
            reqInputElement.addEventListener('input', function () {
                const val = this.value.trim();
                const options = Array.from(document.querySelectorAll('#requirementsOptions option')).map(o => o.value);
                if (options.includes(val)) {
                    addRequirement();
                }
            });
        }

        // Live Remarks Character Counter with Visual Thresholds
        const intakeRemarksInput = document.getElementById('intake_remarks_input');
        if (intakeRemarksInput) {
            intakeRemarksInput.addEventListener('input', function () {
                const len = this.value.length;
                const charCounter = document.getElementById('remarksCharCounter');
                if (charCounter) {
                    charCounter.textContent = `${len} / 3000`;
                    if (len >= 2900) {
                        charCounter.className = 'text-xs text-rose-600 font-bold font-mono';
                    } else if (len >= 2500) {
                        charCounter.className = 'text-xs text-amber-600 font-medium font-mono';
                    } else {
                        charCounter.className = 'text-xs text-slate-500 font-mono';
                    }
                }
            });
        }

        // Reset requirement list state
        window._triageModalClean = function () {
            const container = document.getElementById('requirementsList');
            if (container) container.innerHTML = '';
            const hint = document.getElementById('reqDuplicateHint');
            if (hint) hint.textContent = '';
            const reqInput = document.getElementById('reqInput');
            if (reqInput) reqInput.value = '';
            const intakeRemarks = document.getElementById('intake_remarks_input');
            if (intakeRemarks) intakeRemarks.value = '';
            const charCounter = document.getElementById('remarksCharCounter');
            if (charCounter) {
                charCounter.textContent = '0 / 3000';
                charCounter.className = 'text-xs text-slate-500 font-mono';
            }
        };

        // --- Toggle & Modal Logic ---

        function toggleAppointment(isComplete) {
            const appointmentField = document.getElementById('appointmentField');
            const appointmentInput = appointmentField ? appointmentField.querySelector('input') : null;

            const incompleteFields = document.getElementById('incompleteFields');
            const remarksInput = incompleteFields ? incompleteFields.querySelector('textarea') : null;

            if (isComplete) {
                if (appointmentField) {
                    appointmentField.classList.remove('hidden');
                    setTimeout(() => appointmentField.classList.remove('opacity-0'), 10);
                }

                if (incompleteFields) {
                    incompleteFields.classList.add('opacity-0');
                    setTimeout(() => incompleteFields.classList.add('hidden'), 300);
                }
                if (remarksInput) {
                    remarksInput.value = '';
                }
                const container = document.getElementById('requirementsList');
                if (container) container.innerHTML = '';

            } else {
                if (appointmentField) {
                    appointmentField.classList.add('opacity-0');
                    setTimeout(() => appointmentField.classList.add('hidden'), 300);
                }
                if (appointmentInput) {
                    appointmentInput.value = '';
                }

                if (incompleteFields) {
                    incompleteFields.classList.remove('hidden');
                    setTimeout(() => incompleteFields.classList.remove('opacity-0'), 10);
                }
            }
        }

        function selectOption(type) {
            const labelComplete = document.getElementById('label_complete');
            const labelIncomplete = document.getElementById('label_incomplete');
            const barComplete = document.getElementById('bar_complete');
            const barIncomplete = document.getElementById('bar_incomplete');
            const textComplete = document.getElementById('text_complete');
            const textIncomplete = document.getElementById('text_incomplete');
            const submitBtn = document.getElementById('triageSubmitBtn');
            const submitBtnText = document.getElementById('triageSubmitBtnText');
            const submitBtnIcon = document.getElementById('triageSubmitBtnIcon');

            // Reset Complete label
            if (labelComplete) {
                labelComplete.classList.remove('bg-emerald-50/70', 'border-emerald-300', 'shadow-sm');
                labelComplete.classList.add('border-slate-200');
            }
            if (barComplete) {
                barComplete.classList.remove('opacity-100');
                barComplete.classList.add('opacity-0');
            }
            if (textComplete) {
                textComplete.classList.remove('text-emerald-950');
                textComplete.classList.add('text-slate-800');
            }

            // Reset Incomplete label
            if (labelIncomplete) {
                labelIncomplete.classList.remove('bg-rose-50/70', 'border-rose-300', 'shadow-sm');
                labelIncomplete.classList.add('border-slate-200');
            }
            if (barIncomplete) {
                barIncomplete.classList.remove('opacity-100');
                barIncomplete.classList.add('opacity-0');
            }
            if (textIncomplete) {
                textIncomplete.classList.remove('text-rose-950');
                textIncomplete.classList.add('text-slate-800');
            }

            if (type === 'Complete') {
                if (labelComplete) {
                    labelComplete.classList.remove('border-slate-200');
                    labelComplete.classList.add('bg-emerald-50/70', 'border-emerald-300', 'shadow-sm');
                }
                if (barComplete) {
                    barComplete.classList.remove('opacity-0');
                    barComplete.classList.add('opacity-100');
                }
                if (textComplete) {
                    textComplete.classList.remove('text-slate-800');
                    textComplete.classList.add('text-emerald-950');
                }

                if (submitBtn) {
                    submitBtn.classList.remove('from-[#8B0000]', 'to-[#a30000]', 'hover:from-[#780000]', 'hover:to-[#8B0000]', 'focus:ring-[#8B0000]', 'shadow-red-950/20', 'bg-[#8B0000]', 'hover:bg-[#6d0000]', 'shadow-red-900/20');
                    submitBtn.classList.add('from-emerald-700', 'to-emerald-800', 'hover:from-emerald-800', 'hover:to-emerald-900', 'focus:ring-emerald-700', 'shadow-emerald-950/20');
                }
                if (submitBtnIcon) {
                    submitBtnIcon.className = 'fas fa-calendar-check text-emerald-200';
                }
                if (submitBtnText) {
                    submitBtnText.textContent = 'Accept & Schedule Deadline';
                }
            } else {
                if (labelIncomplete) {
                    labelIncomplete.classList.remove('border-slate-200');
                    labelIncomplete.classList.add('bg-rose-50/70', 'border-rose-300', 'shadow-sm');
                }
                if (barIncomplete) {
                    barIncomplete.classList.remove('opacity-0');
                    barIncomplete.classList.add('opacity-100');
                }
                if (textIncomplete) {
                    textIncomplete.classList.remove('text-slate-800');
                    textIncomplete.classList.add('text-rose-950');
                }

                if (submitBtn) {
                    submitBtn.classList.remove('from-emerald-700', 'to-emerald-800', 'hover:from-emerald-800', 'hover:to-emerald-900', 'focus:ring-emerald-700', 'shadow-emerald-950/20', 'bg-emerald-800', 'hover:bg-emerald-700');
                    submitBtn.classList.add('from-[#8B0000]', 'to-[#a30000]', 'hover:from-[#780000]', 'hover:to-[#8B0000]', 'focus:ring-[#8B0000]', 'shadow-red-950/20');
                }
                if (submitBtnIcon) {
                    submitBtnIcon.className = 'fas fa-undo-alt text-white/90';
                }
                if (submitBtnText) {
                    submitBtnText.textContent = 'Return with Deficiencies';
                }
            }
        }

        function toggleCvRemarks(show) {
            const field = document.getElementById('cvRemarksField');
            const textarea = document.getElementById('cv_remarks');
            if (show) {
                if (field) field.classList.remove('hidden');
                if (textarea) textarea.required = true;
            } else {
                if (field) field.classList.add('hidden');
                if (textarea) textarea.required = false;
            }
        }

        // --- Keyboard Focus Trap for Modal Accessibility ---
        function trapModalFocus(e) {
            const modal = document.getElementById('triageModal');
            if (!modal || modal.classList.contains('hidden')) return;

            if (e.key === 'Tab') {
                const focusables = modal.querySelectorAll('button:not([disabled]), [href], input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])');
                const visible = Array.from(focusables).filter(el => el.offsetParent !== null && !el.closest('.hidden'));
                if (visible.length === 0) return;

                const first = visible[0];
                const last = visible[visible.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === first || !modal.contains(document.activeElement)) {
                        e.preventDefault();
                        last.focus();
                    }
                } else {
                    if (document.activeElement === last || !modal.contains(document.activeElement)) {
                        e.preventDefault();
                        first.focus();
                    }
                }
            }
        }

        window.openTriageModal = function(id, title, orNumber, orFilePath, isOrVerified, cvStatus, hasProjectType, researcherName, researcherEmail) {
            window._lastActiveElement = document.activeElement;
            const modal = document.getElementById('triageModal');
            const content = document.getElementById('modalContent');
            const titleEl = document.getElementById('modalTitle');
            const form = document.getElementById('triageForm');

            if (!modal || !content || !form) return;

            if (titleEl) {
                titleEl.textContent = title || 'Untitled Submission';
                titleEl.title = title || '';
            }
            const nameEl = document.getElementById('modalResearcherName');
            if (nameEl) {
                nameEl.textContent = researcherName || 'N/A';
                nameEl.title = researcherName || '';
            }
            const emailEl = document.getElementById('modalResearcherEmail');
            if (emailEl) {
                emailEl.textContent = researcherEmail || 'N/A';
                emailEl.title = researcherEmail || '';
            }
            form.action = `/admin/update-status/${encodeURIComponent(id)}`;

            // Set Date Defaults (minimum 2 days from now)
            const dateInput = document.querySelector('input[name="appointment_date"]');
            if (dateInput) {
                const targetDate = new Date();
                targetDate.setDate(targetDate.getDate() + 2);
                const minDate = targetDate.toISOString().split('T')[0];
                dateInput.value = minDate;
                dateInput.min = minDate;
            }

            // Clear requirements list and hints on open
            if (window._triageModalClean) window._triageModalClean();

            // Populate OR Verification Section
            const orField = document.getElementById('orVerificationField');
            const orNumEl = document.getElementById('modalOrNumber');
            const orFileLink = document.getElementById('modalOrFileLink');
            const orVerified = document.getElementById('orAlreadyVerified');
            const orCheckbox = document.getElementById('orVerifyCheckboxContainer');
            const orNoReceipt = document.getElementById('orNoReceipt');
            const verifyCheckbox = document.getElementById('verifyOrCheckbox');

            if (orField) orField.classList.remove('hidden');
            if (orVerified) orVerified.classList.add('hidden');
            if (orCheckbox) orCheckbox.classList.add('hidden');
            if (orNoReceipt) orNoReceipt.classList.add('hidden');
            if (orFileLink) orFileLink.classList.add('hidden');

            window._triageModalState = {
                id: id,
                isOrVerified: !!isOrVerified,
                hasOrFile: !!(orFilePath && orFilePath !== 'null' && orFilePath !== ''),
                cvStatus: cvStatus,
                hasProjectType: !!hasProjectType
            };

            if (orFilePath && orFilePath !== 'null' && orFilePath !== '') {
                const filename = orFilePath.split('/').pop();
                if (orNumEl) {
                    orNumEl.innerHTML = '';
                    const wrapperSpan = document.createElement('span');
                    wrapperSpan.className = 'text-emerald-600 flex items-center gap-1.5 max-w-[220px] sm:max-w-[320px] min-w-0';
                    wrapperSpan.title = filename;

                    const icon = document.createElement('i');
                    icon.className = 'fas fa-paperclip flex-shrink-0';
                    icon.setAttribute('aria-hidden', 'true');

                    const textSpan = document.createElement('span');
                    textSpan.className = 'truncate';
                    textSpan.textContent = filename;

                    wrapperSpan.appendChild(icon);
                    wrapperSpan.appendChild(textSpan);
                    orNumEl.appendChild(wrapperSpan);
                }

                if (orFileLink) {
                    orFileLink.href = orFilePath;
                    orFileLink.classList.remove('hidden');
                }

                if (isOrVerified) {
                    if (orVerified) orVerified.classList.remove('hidden');
                } else {
                    if (orCheckbox) orCheckbox.classList.remove('hidden');
                    if (verifyCheckbox) verifyCheckbox.checked = false;
                }
            } else {
                if (orNumEl) orNumEl.textContent = 'Not Provided';
                if (orNoReceipt) orNoReceipt.classList.remove('hidden');
            }

            // Populate CV Verification Section
            const cvField = document.getElementById('cvVerificationField');
            const cvAlreadyValid = document.getElementById('cvAlreadyValid');
            const cvAlreadyInvalid = document.getElementById('cvAlreadyInvalid');
            const cvPendingOptions = document.getElementById('cvPendingOptions');
            const cvRemarksField = document.getElementById('cvRemarksField');
            const cvRemarks = document.getElementById('cv_remarks');

            if (cvAlreadyValid) cvAlreadyValid.classList.add('hidden');
            if (cvAlreadyInvalid) cvAlreadyInvalid.classList.add('hidden');
            if (cvPendingOptions) cvPendingOptions.classList.add('hidden');
            if (cvRemarksField) cvRemarksField.classList.add('hidden');
            if (cvRemarks) { cvRemarks.value = ''; cvRemarks.required = false; }

            const skipRadio = document.getElementById('cv_action_skip');
            if (skipRadio) skipRadio.checked = true;

            if (hasProjectType && cvField) {
                cvField.classList.remove('hidden');
                if (cvStatus === 'Valid' && cvAlreadyValid) {
                    cvAlreadyValid.classList.remove('hidden');
                } else if (cvStatus === 'Invalid' && cvAlreadyInvalid) {
                    cvAlreadyInvalid.classList.remove('hidden');
                } else if (cvPendingOptions) {
                    cvPendingOptions.classList.remove('hidden');
                }
            } else if (cvField) {
                cvField.classList.add('hidden');
            }

            // Default to Incomplete
            const incompleteRadio = document.querySelector('input[value="Incomplete"]');
            if (incompleteRadio) incompleteRadio.checked = true;
            toggleAppointment(false);
            selectOption('Incomplete');

            // Show Modal and Trap Focus with synchronized frame rendering
            modal.classList.remove('hidden');
            document.addEventListener('keydown', trapModalFocus);

            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
                const closeBtn = modal.querySelector('button[onclick="closeTriage()"]');
                if (closeBtn) closeBtn.focus();
            });

            refreshCompleteOptionLock();
        };

        function refreshCompleteOptionLock() {
            const state = window._triageModalState || {};
            const completeRadio = document.querySelector('input[value="Complete"]');
            const completeLabel = completeRadio ? completeRadio.closest('label') : null;
            const verifyOrCheckbox = document.getElementById('verifyOrCheckbox');
            const cvVerifyRadio = document.getElementById('cv_action_verify');

            let orOk = state.isOrVerified || (verifyOrCheckbox && verifyOrCheckbox.checked);
            if (!state.hasOrFile && !state.isOrVerified) orOk = false;

            let cvOk = true;
            if (state.hasProjectType) {
                cvOk = (state.cvStatus === 'Valid') || (cvVerifyRadio && cvVerifyRadio.checked);
            }

            const locked = !(orOk && cvOk);
            const lockHint = document.getElementById('completeLockHint');

            if (locked) {
                if (completeRadio && completeRadio.checked) {
                    const incompleteRadio = document.querySelector('input[value="Incomplete"]');
                    if (incompleteRadio) {
                        incompleteRadio.checked = true;
                        toggleAppointment(false);
                        selectOption('Incomplete');
                    }
                }
                if (completeRadio) completeRadio.disabled = true;
                if (completeLabel) {
                    completeLabel.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
                if (lockHint) lockHint.classList.remove('hidden');
            } else {
                if (completeRadio) completeRadio.disabled = false;
                if (completeLabel) {
                    completeLabel.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
                if (lockHint) lockHint.classList.add('hidden');
            }
        }

        function closeTriage() {
            const modal = document.getElementById('triageModal');
            const content = document.getElementById('modalContent');
            if (!modal || !content) return;

            document.removeEventListener('keydown', trapModalFocus);

            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                if (window._lastActiveElement && typeof window._lastActiveElement.focus === 'function') {
                    window._lastActiveElement.focus();
                }
            }, 300);
        }

        // Global Escape Listener for Modal Dismissal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('triageModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeTriage();
                }
            }
        });

        // Global Undo Incomplete Action with Re-entrancy Guard & Network Timeout
        let isUndoProcessing = false;
        window.undoIncomplete = function(id, title) {
            if (isUndoProcessing) return;

            Swal.fire({
                title: 'Revert to Recent Submissions?',
                text: `Move "${title}" back to the Pending intake queue for re-screening?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Revert to Pending',
                cancelButtonText: 'Keep Incomplete',
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#64748b',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                    title: 'font-heading text-xl text-slate-800 font-bold pt-2',
                    htmlContainer: 'text-slate-600 text-sm mt-2',
                    confirmButton: 'bg-[#8B0000] text-white px-5 py-2.5 rounded-xl font-bold shadow-lg hover:bg-red-900 transition-all mx-1.5',
                    cancelButton: 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 px-5 py-2.5 rounded-xl font-bold transition-all mx-1.5'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    if (isUndoProcessing) return;
                    isUndoProcessing = true;

                    Swal.fire({
                        title: 'Reverting...',
                        text: 'Updating submission queue...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const undoController = new AbortController();
                    const undoTimeout = setTimeout(() => undoController.abort(), 12000);

                    try {
                        const response = await fetch(`/admin/update-status/${encodeURIComponent(id)}`, {
                            method: 'POST',
                            signal: undoController.signal,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                classification: 'Undo'
                            })
                        });

                        clearTimeout(undoTimeout);
                        const data = await response.json().catch(() => ({}));

                        if (!response.ok) {
                            throw new Error(data.message || 'Server rejected the revert request.');
                        }

                        await Swal.fire({
                            title: 'Reverted Successfully',
                            text: 'Submission has been returned to the Recent Submissions queue.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                                title: 'font-heading text-xl text-slate-800 font-bold'
                            }
                        });

                        window.location.reload();
                    } catch (error) {
                        clearTimeout(undoTimeout);
                        console.error('Error reverting submission:', error);
                        const isTimeout = error.name === 'AbortError';
                        Swal.fire({
                            title: isTimeout ? 'Request Timed Out' : 'Action Failed',
                            text: isTimeout 
                                ? 'The server took too long to revert the status. Please check your network and try again.'
                                : (error.message || 'Unable to revert submission status. Please try again.'),
                            icon: 'error',
                            confirmButtonColor: '#8B0000',
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                                title: 'font-heading text-xl text-slate-800 font-bold',
                                confirmButton: 'bg-[#8B0000] text-white px-5 py-2.5 rounded-xl font-bold'
                            }
                        });
                    } finally {
                        isUndoProcessing = false;
                    }
                }
            });
        };

        // AJAX Submission with Client-Side Validation Guards, Double-Submit Lock & Timeout
        let isTriageSubmitting = false;
        document.getElementById('triageForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            if (isTriageSubmitting) return;

            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            const classification = form.querySelector('input[name="classification"]:checked')?.value || 'Incomplete';

            // Guard 1: Incomplete Validation (require explanation or at least one missing requirement)
            if (classification === 'Incomplete') {
                const remarksInput = document.getElementById('intake_remarks_input');
                const remarksValue = remarksInput ? remarksInput.value.trim() : '';
                const reqCount = document.querySelectorAll('#requirementsList input[name="missing_requirements[]"]').length;

                if (!remarksValue && reqCount === 0) {
                    Swal.fire({
                        title: 'Explanation Required',
                        text: 'Please provide intake instructions or specify at least one missing requirement so the researcher knows how to resolve deficiencies.',
                        icon: 'info',
                        confirmButtonText: 'Understood',
                        confirmButtonColor: '#8B0000',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                            title: 'font-heading text-xl text-slate-800 font-bold pt-2',
                            htmlContainer: 'text-slate-600 text-sm mt-2',
                            confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold'
                        }
                    });
                    if (remarksInput) remarksInput.focus();
                    return;
                }
            }

            // Guard 2: CV Invalidation Reason
            const cvActionInvalidate = document.getElementById('cv_action_invalidate');
            if (cvActionInvalidate && cvActionInvalidate.checked) {
                const cvRemarks = document.getElementById('cv_remarks');
                if (cvRemarks && !cvRemarks.value.trim()) {
                    Swal.fire({
                        title: 'Invalidation Reason Required',
                        text: 'Please specify why the researcher classification was marked as invalid.',
                        icon: 'info',
                        confirmButtonText: 'Understood',
                        confirmButtonColor: '#8B0000',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                            title: 'font-heading text-xl text-slate-800 font-bold pt-2',
                            htmlContainer: 'text-slate-600 text-sm mt-2',
                            confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold'
                        }
                    });
                    cvRemarks.focus();
                    return;
                }
            }

            // Guard 3: Complete Validation (Valid appointment date)
            if (classification === 'Complete') {
                const dateInput = document.getElementById('appointment_date_input');
                if (!dateInput || !dateInput.value) {
                    Swal.fire({
                        title: 'Appointment Deadline Required',
                        text: 'Please select an appointment deadline for hardcopy delivery.',
                        icon: 'info',
                        confirmButtonText: 'Select Date',
                        confirmButtonColor: '#8B0000',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                            title: 'font-heading text-xl text-slate-800 font-bold pt-2',
                            htmlContainer: 'text-slate-600 text-sm mt-2',
                            confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold'
                        }
                    });
                    if (dateInput) dateInput.focus();
                    return;
                }

                const selectedDate = new Date(dateInput.value);
                const minAllowed = new Date();
                minAllowed.setDate(minAllowed.getDate() + 1);
                minAllowed.setHours(0, 0, 0, 0);

                if (selectedDate <= minAllowed) {
                    Swal.fire({
                        title: 'Invalid Deadline Date',
                        text: 'The intake appointment deadline must be at least 2 days in the future.',
                        icon: 'warning',
                        confirmButtonText: 'Adjust Date',
                        confirmButtonColor: '#8B0000',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                            title: 'font-heading text-xl text-slate-800 font-bold pt-2',
                            htmlContainer: 'text-slate-600 text-sm mt-2',
                            confirmButton: 'bg-[#8B0000] text-white px-6 py-2.5 rounded-xl font-bold'
                        }
                    });
                    dateInput.focus();
                    return;
                }
            }

            // Lock double-submit & show visual feedback
            isTriageSubmitting = true;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> Processing...';
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

            const triageController = new AbortController();
            const triageTimeout = setTimeout(() => triageController.abort(), 15000);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    signal: triageController.signal,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                clearTimeout(triageTimeout);
                const result = await response.json().catch(() => ({}));

                if (response.ok) {
                    closeTriage();

                    await Swal.fire({
                        title: 'Success!',
                        text: result.message || 'Protocol status has been updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Great!',
                        confirmButtonColor: '#8B0000',
                        scrollbarPadding: false,
                        backdrop: `rgba(15, 23, 42, 0.75)`,
                        buttonsStyling: false,
                        showClass: {
                            popup: 'animate-[fadeInUp_0.3s_ease-out]'
                        },
                        hideClass: {
                            popup: 'animate-[fadeOutDown_0.3s_ease-in]'
                        },
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                            title: 'font-heading text-2xl text-slate-800 font-bold pt-4',
                            htmlContainer: 'text-slate-600 text-sm mt-2',
                            confirmButton: 'bg-[#8B0000] text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-900 hover:shadow-xl hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2'
                        }
                    });

                    window.location.reload();
                } else {
                    // Granular Laravel 422 Error Display
                    let errorDetails = '';
                    if (result.errors && typeof result.errors === 'object') {
                        const errorMessages = Object.values(result.errors).flat();
                        if (errorMessages.length > 0) {
                            errorDetails = `<div class="mt-3 text-left bg-red-50 p-3 rounded-xl border border-red-100"><ul class="list-disc pl-4 space-y-1 text-xs text-red-700 font-medium">${errorMessages.map(msg => `<li>${msg}</li>`).join('')}</ul></div>`;
                        }
                    }

                    Swal.fire({
                        title: 'Action Failed',
                        html: `<p class="text-sm text-slate-700">${result.message || 'Unable to update status. Please review the highlighted issues.'}</p>${errorDetails}`,
                        icon: 'error',
                        confirmButtonText: 'Okay',
                        confirmButtonColor: '#334155',
                        scrollbarPadding: false,
                        backdrop: `rgba(15, 23, 42, 0.75)`,
                        buttonsStyling: false,
                        showClass: {
                            popup: 'animate-[shake_0.5s_ease-in-out]'
                        },
                        customClass: {
                            popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                            title: 'font-heading text-2xl text-slate-800 font-bold pt-4',
                            htmlContainer: 'text-slate-600 text-sm mt-2',
                            confirmButton: 'bg-slate-800 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-slate-700 hover:shadow-xl hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2'
                        }
                    });
                }
            } catch (error) {
                clearTimeout(triageTimeout);
                console.error(error);
                const isTimeout = error.name === 'AbortError';
                Swal.fire({
                    title: isTimeout ? 'Request Timed Out' : 'System Error',
                    text: isTimeout 
                        ? 'The server took too long to process the triage determination. Please check your network connection and try again.'
                        : 'Something went wrong while connecting to the server. Please check your network connection.',
                    icon: isTimeout ? 'error' : 'warning',
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#334155',
                    scrollbarPadding: false,
                    backdrop: `rgba(15, 23, 42, 0.75)`,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-slate-200 font-sans p-6',
                        title: 'font-heading text-2xl text-slate-800 font-bold pt-4',
                        htmlContainer: 'text-slate-600 text-sm mt-2',
                        confirmButton: 'bg-slate-800 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-slate-700 hover:shadow-xl hover:-translate-y-0.5 transition-all outline-none focus:ring-0 mx-2'
                    }
                });
            } finally {
                isTriageSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let debounceTimer;
            let currentAbortController = null;

            // Optimized targeted fetchSubmissions with selective aria-busy and queue isolation
            const fetchSubmissions = (params) => {
                if (currentAbortController) {
                    currentAbortController.abort();
                }
                currentAbortController = new AbortController();

                const target = params.get('target') || 'all';
                const recentContainer = document.getElementById('recent-submissions-wrapper');
                const incompleteContainer = document.getElementById('incomplete-submissions-wrapper');

                const affectsRecent = target === 'all' || target === 'recent';
                const affectsIncomplete = target === 'all' || target === 'incomplete';

                if (affectsRecent && recentContainer) {
                    recentContainer.setAttribute('aria-busy', 'true');
                    recentContainer.classList.add('opacity-60', 'pointer-events-none', 'transition-opacity', 'duration-200');
                }
                if (affectsIncomplete && incompleteContainer) {
                    incompleteContainer.setAttribute('aria-busy', 'true');
                    incompleteContainer.classList.add('opacity-60', 'pointer-events-none', 'transition-opacity', 'duration-200');
                }

                const resetLoadingState = () => {
                    if (affectsRecent && recentContainer) {
                        recentContainer.removeAttribute('aria-busy');
                        recentContainer.classList.remove('opacity-60', 'pointer-events-none');
                    }
                    if (affectsIncomplete && incompleteContainer) {
                        incompleteContainer.removeAttribute('aria-busy');
                        incompleteContainer.classList.remove('opacity-60', 'pointer-events-none');
                    }
                };

                const url = `{{ request()->url() }}?${params.toString()}`;

                fetch(url, {
                    signal: currentAbortController.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.recent !== undefined && recentContainer) {
                            recentContainer.innerHTML = data.recent;
                        }
                        if (data.incomplete !== undefined && incompleteContainer) {
                            incompleteContainer.innerHTML = data.incomplete;
                        }
                        window.history.pushState({}, '', url);
                    })
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            console.error('Error fetching submissions:', error);
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'Unable to update queue. Please check your network connection.',
                                    showConfirmButton: false,
                                    timer: 3500,
                                    timerProgressBar: true
                                });
                            }
                        }
                    })
                    .finally(() => {
                        resetLoadingState();
                    });
            };

            // Event Listeners for Search Inputs (Optimized 250ms debounce with value diff guard)
            const lastSearchValues = {
                recent_search_input: document.getElementById('recent_search_input')?.value.trim() || '',
                incomplete_search_input: document.getElementById('incomplete_search_input')?.value.trim() || ''
            };

            ['recent_search_input', 'incomplete_search_input'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;

                el.addEventListener('input', (e) => {
                    const currentVal = e.target.value.trim();
                    if (currentVal === lastSearchValues[id]) return;

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        lastSearchValues[id] = currentVal;
                        const params = new URLSearchParams(window.location.search);

                        if (id === 'recent_search_input') {
                            params.set('recent_search', currentVal);
                            params.set('target', 'recent');
                            params.delete('pending_page');
                        } else {
                            params.set('incomplete_search', currentVal);
                            params.set('target', 'incomplete');
                            params.delete('incomplete_page');
                        }

                        fetchSubmissions(params);
                    }, 250);
                });
            });

            // Event Listeners for Advanced Filter Inputs (Sort Radios)
            document.querySelectorAll('.recent-filter-input, .incomplete-filter-input').forEach(input => {
                input.addEventListener('change', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        const params = new URLSearchParams(window.location.search);

                        if (input.classList.contains('recent-filter-input')) {
                            const recentSortNode = document.querySelector('input[name="recent_sort"]:checked');
                            if (recentSortNode) params.set('recent_sort', recentSortNode.value);
                            params.set('target', 'recent');
                            params.delete('pending_page');
                        } else {
                            const incompleteSortNode = document.querySelector('input[name="incomplete_sort"]:checked');
                            if (incompleteSortNode) params.set('incomplete_sort', incompleteSortNode.value);
                            params.set('target', 'incomplete');
                            params.delete('incomplete_page');
                        }

                        fetchSubmissions(params);
                    }, 100);
                });
            });

            // Event Delegation for Pagination, Triage Triggers, and Undo Actions
            document.addEventListener('click', (e) => {
                // Triage Modal Trigger via Data Attributes
                const triageBtn = e.target.closest('.triage-trigger-btn');
                if (triageBtn) {
                    e.preventDefault();
                    const ds = triageBtn.dataset;
                    if (typeof window.openTriageModal === 'function') {
                        window.openTriageModal(
                            ds.id,
                            ds.title,
                            ds.orNumber || '',
                            ds.orPath || '',
                            ds.orVerified === 'true',
                            ds.cvStatus || '',
                            ds.hasProjectType === 'true',
                            ds.researcherName || '',
                            ds.researcherEmail || ''
                        );
                    }
                    return;
                }

                // Undo Incomplete Trigger via Data Attributes
                const undoBtn = e.target.closest('.undo-incomplete-btn');
                if (undoBtn) {
                    e.preventDefault();
                    const id = undoBtn.dataset.id;
                    const title = undoBtn.dataset.title;
                    if (typeof window.undoIncomplete === 'function') {
                        window.undoIncomplete(id, title);
                    }
                    return;
                }

                // Pagination Link Trigger with Target Support
                const link = e.target.closest('.pagination-link');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const params = new URLSearchParams(url.search);
                    const target = link.dataset.target || (link.closest('#recent-submissions-wrapper') ? 'recent' : 'incomplete');
                    params.set('target', target);
                    fetchSubmissions(params);
                }
            });
        });
    </script>
</x-admin_layout>