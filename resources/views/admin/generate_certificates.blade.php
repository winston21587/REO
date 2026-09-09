<x-admin_layout>
<div class="max-w-6xl mx-auto space-y-8 animate-[fadeInUp_0.4s_ease-out] pb-16">

    {{-- Breadcrumbs & Top Command Strip --}}
    <div class="flex flex-col gap-4">
        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-400">
            <a href="{{ route('admin.certifications') }}" class="hover:text-[#8B0000] transition-colors">Certifications & Clearances</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-slate-600 font-semibold">Document Issuance Studio</span>
        </nav>

        {{-- Page Header & High-Density Protocol Ribbon --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start gap-4 min-w-0">
                <a href="{{ route('admin.certifications') }}" 
                   class="w-11 h-11 rounded-xl bg-slate-50 border border-slate-200/80 text-slate-600 hover:text-[#8B0000] hover:border-[#8B0000]/30 hover:bg-red-50/40 flex items-center justify-center shrink-0 transition-all cursor-pointer shadow-xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]"
                   title="Return to Certifications">
                    <i class="fas fa-arrow-left text-sm" aria-hidden="true"></i>
                </a>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading tracking-tight">
                            Document Issuance Studio
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Approved Protocol
                        </span>
                    </div>
                    <p class="text-slate-500 text-sm mt-1 leading-relaxed">
                        Configure, preview, and officially seal the ethics clearance certificate and institutional endorsement letter.
                    </p>
                </div>
            </div>

            {{-- Metadata Cockpit Badges --}}
            <div class="flex items-center gap-3 flex-wrap lg:justify-end border-t lg:border-t-0 pt-4 lg:pt-0 border-slate-100">
                {{-- Protocol ID --}}
                <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200/70 rounded-xl text-xs">
                    <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Protocol</span>
                    <span class="font-mono font-bold text-slate-800">#{{ str_pad($submission->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>

                {{-- Review Type --}}
                <div class="flex items-center gap-2 px-3 py-2 bg-emerald-50/70 border border-emerald-200/70 rounded-xl text-xs text-emerald-900 font-semibold">
                    <i class="fas fa-check-circle text-emerald-600 text-xs" aria-hidden="true"></i>
                    <span>{{ $submission->Review_Type ?: 'Exempt Review' }}</span>
                </div>

                {{-- Researcher Avatar & Name --}}
                <div class="flex items-center gap-2 px-3.5 py-2 bg-slate-50 border border-slate-200/70 rounded-xl text-xs text-slate-700">
                    <div class="w-6 h-6 rounded-full bg-[#8B0000] text-white flex items-center justify-center text-[10px] font-bold uppercase shrink-0">
                        {{ substr($researcherName ?: 'U', 0, 1) }}
                    </div>
                    <span class="font-semibold max-w-[160px] truncate" title="{{ $researcherName }}">
                        {{ $researcherName ?: 'Unknown Researcher' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Error Banner --}}
    @if(isset($errors) && $errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3.5 shadow-sm animate-shake">
        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
            <i class="fas fa-exclamation-circle text-base" aria-hidden="true"></i>
        </div>
        <div class="text-xs text-rose-900 space-y-1">
            <p class="font-bold text-sm">Please resolve the following before issuing documents:</p>
            <ul class="list-disc list-inside space-y-0.5 text-rose-800">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Issuance Form --}}
    <form action="{{ route('admin.certificate.generate', $submission->id) }}" method="POST" id="generateForm" class="space-y-8">
        @csrf

        {{-- =========================================================================
             SECTION 1: Master Protocol Metadata Hub (Synchronized into Both PDFs)
             ========================================================================= --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100/60 border-b border-slate-200/80 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center font-bold text-xs">
                        1
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-heading">
                            Master Protocol Metadata
                        </h2>
                        <p class="text-xs text-slate-500">
                            Synchronized across both the Certificate of Exemption and the Cover Letter of Approval
                        </p>
                    </div>
                </div>
                <span class="hidden sm:inline-flex items-center gap-1.5 text-xs text-slate-500 font-medium px-2.5 py-1 bg-white rounded-lg border border-slate-200">
                    <i class="fas fa-sync-alt text-slate-400 text-[10px]"></i> Dual-Document Sync
                </span>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    {{-- Researchers Field --}}
                    <div class="lg:col-span-6 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="shared_researchers" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Investigators / Researchers <span class="text-[#8B0000]">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400 font-medium">One name per line</span>
                        </div>
                        <textarea id="shared_researchers" name="shared_researchers" rows="3"
                            class="block w-full rounded-xl border border-slate-300/80 bg-slate-50/50 p-3.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent resize-y transition-all font-medium leading-relaxed"
                            placeholder="Lead Researcher Full Name&#10;Co-Researcher Name (Optional)&#10;Adviser Name (Optional)" required>{{ old('shared_researchers', $researcherName) }}</textarea>
                        <p class="text-[11px] text-slate-400 leading-snug">
                            Line 1 is formally designated as the Principal Investigator on the university endorsement.
                        </p>
                    </div>

                    {{-- Protocol Title Field --}}
                    <div class="lg:col-span-6 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="shared_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Official Protocol Title <span class="text-[#8B0000]">*</span>
                            </label>
                            <span id="titleCharCount" class="text-[11px] text-slate-400 font-mono">0 chars</span>
                        </div>
                        <textarea id="shared_title" name="shared_title" rows="3"
                            class="block w-full rounded-xl border border-slate-300/80 bg-slate-50/50 p-3.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent resize-y transition-all font-medium leading-relaxed"
                            placeholder="Full research protocol title as approved by the committee" required>{{ old('shared_title', $submission->Study_Protocol_title) }}</textarea>
                        <p class="text-[11px] text-slate-400 leading-snug">
                            Printed verbatim on both official instruments. Ensure exact title case and spelling.
                        </p>
                    </div>

                </div>

                {{-- REO Control Code Field --}}
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="max-w-md">
                        <label for="shared_reo_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-0.5">
                            REO Control Code <span class="font-normal text-slate-400 normal-case">(Optional Institutional Registry ID)</span>
                        </label>
                        <p class="text-xs text-slate-400">
                            Unique registry tracking number affixed to the upper header of the certificate.
                        </p>
                    </div>
                    <div class="w-full sm:w-72 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-barcode text-xs" aria-hidden="true"></i>
                        </div>
                        <input type="text" id="shared_reo_code" name="shared_reo_code"
                            value="{{ old('shared_reo_code', $submission->reoc_code ?? '') }}"
                            class="block w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-300/80 bg-slate-50/50 text-sm font-mono font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-all"
                            placeholder="e.g. REOC-2026-001">
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================================================================
             SECTION 2: Dual-Document Issuance Cockpit (Symmetrical Two-Column Grid)
             ========================================================================= --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center font-bold text-xs">
                        2
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-heading">
                            Document Instrument Specifications & Previews
                        </h2>
                        <p class="text-xs text-slate-500">
                            Review specific legal boilerplate and validity parameters before formal generation
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

                {{-- ==========================================
                     INSTRUMENT A: Certificate of Exemption
                     ========================================== --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col">
                    {{-- Header banner --}}
                    <div class="bg-gradient-to-r from-[#8B0000] via-[#9e0202] to-[#b01010] px-6 py-4 flex items-center justify-between text-white">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0 shadow-xs">
                                <i class="fas fa-award text-base text-white" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-sm leading-tight text-white font-heading">
                                    Certificate of Exemption
                                </h3>
                                <p class="text-red-100 text-xs">Ethics Clearance Certificate • REO-Form 01</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase bg-white/20 text-white border border-white/30 shrink-0">
                            Official Seal
                        </span>
                    </div>

                    {{-- Form body --}}
                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                        @php
                            $defaultSummary = "      This certifies that this protocol does not involve human participants or with no or minimal risk to human participants. Hence, this is an exemption for research ethics review. The study may proceed with implementation.\n\n       Compliance on the standard conditions attached here is required. Any change in the protocol invalidates the certificate and will require a new application for review. \n\n              Issued this " . now()->format('d') . "     day of " . now()->format('F Y') . ", at Research Ethics Office, Western Mindanao State University, Zamboanga City, Philippines.";
                        @endphp
                        
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="cert_reo_summary" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Exemption Clause & Statutory Summary <span class="font-normal text-slate-400 normal-case">(Editable)</span>
                                </label>
                                <button type="button" id="resetSummaryBtn" class="text-[11px] text-[#8B0000] hover:text-[#6d0000] font-semibold cursor-pointer">
                                    Reset to Standard
                                </button>
                            </div>
                            <textarea id="cert_reo_summary" name="cert_reo_summary" rows="9"
                                class="block w-full rounded-xl border border-slate-300/80 bg-slate-50/50 p-3.5 text-xs font-mono text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent resize-y transition-all leading-relaxed"
                                placeholder="Brief summary of the exemption scope…">{{ old('cert_reo_summary', $defaultSummary) }}</textarea>
                            <p class="text-[11px] text-slate-400 leading-snug">
                                This statutory declaration appears on the official sealed certificate above the signatory lines.
                            </p>
                        </div>

                        {{-- Panel Footer with Preview --}}
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-4">
                            <span class="text-xs text-slate-400 font-medium">Output: PDF Instrument</span>
                            <button type="submit" name="action" value="preview_cert" formtarget="_blank" formnovalidate
                                class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-xs font-bold text-[#8B0000] bg-red-50 hover:bg-red-100/80 border border-red-200 transition-all cursor-pointer shadow-xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                                <i class="fas fa-eye text-[#8B0000]" aria-hidden="true"></i>
                                <span>Preview Certificate (PDF)</span>
                                <i class="fas fa-external-link-alt text-[10px] text-red-400 ml-0.5" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ==========================================
                     INSTRUMENT B: Cover Letter of Approval
                     ========================================== --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col">
                    {{-- Header banner --}}
                    <div class="bg-gradient-to-r from-slate-800 via-slate-900 to-slate-950 px-6 py-4 flex items-center justify-between text-white">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0 shadow-xs">
                                <i class="fas fa-file-invoice text-base text-white" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-sm leading-tight text-white font-heading">
                                    Cover Letter of Approval
                                </h3>
                                <p class="text-slate-300 text-xs">Cover Letter of Approval • REO-Form 02</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase bg-white/20 text-white border border-white/30 shrink-0">
                            Formal Dispatch
                        </span>
                    </div>

                    {{-- Form body --}}
                    <div class="p-6 space-y-5 flex-1 flex flex-col justify-between">
                        <div class="space-y-5">
                            {{-- Version Field --}}
                            <div>
                                <label for="cover_version" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Document Version <span class="font-normal text-slate-400 normal-case">(Optional)</span>
                                </label>
                                <div class="relative w-full max-w-xs">
                                    <input type="text" id="cover_version" name="cover_version"
                                        value="{{ old('cover_version', '1.0') }}"
                                        class="block w-full rounded-xl border border-slate-300/80 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-700 focus:border-transparent transition-all"
                                        placeholder="e.g. 1.0">
                                </div>
                            </div>

                            {{-- Approved Period & Expiry Date Grid --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="cover_approved_period" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Approved Period <span class="text-[#8B0000]">*</span>
                                    </label>
                                    <input type="date" id="cover_approved_period" name="cover_approved_period"
                                        value="{{ old('cover_approved_period', now()->toDateString()) }}"
                                        class="block w-full rounded-xl border border-slate-300/80 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-700 focus:border-transparent transition-all"
                                        required>
                                    <p class="text-[11px] text-slate-400 mt-1">Start of validity window.</p>
                                </div>
                                <div>
                                    <label for="cover_expiry_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Expiry Date <span class="text-[#8B0000]">*</span>
                                    </label>
                                    <input type="date" id="cover_expiry_date" name="cover_expiry_date"
                                        value="{{ old('cover_expiry_date', now()->addYear()->toDateString()) }}"
                                        class="block w-full rounded-xl border border-slate-300/80 bg-slate-50/50 px-3.5 py-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-700 focus:border-transparent transition-all"
                                        required>
                                    <p class="text-[11px] text-slate-400 mt-1">Standard: 1 year from approval.</p>
                                </div>
                            </div>

                            {{-- Institutional Note --}}
                            <div class="p-3.5 bg-slate-50 border border-slate-200/70 rounded-xl flex items-start gap-3 text-xs text-slate-600">
                                <i class="fas fa-info-circle text-slate-400 mt-0.5 shrink-0" aria-hidden="true"></i>
                                <p class="leading-relaxed">
                                    The Cover Letter serves as the formal transmittal to the researcher and department dean, authorizing active data collection within the defined approval period.
                                </p>
                            </div>
                        </div>

                        {{-- Panel Footer with Preview --}}
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-4">
                            <span class="text-xs text-slate-400 font-medium">Output: PDF Instrument</span>
                            <button type="submit" name="action" value="preview_cover" formtarget="_blank" formnovalidate
                                class="inline-flex items-center gap-2 h-10 px-4 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 transition-all cursor-pointer shadow-xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-700">
                                <i class="fas fa-eye text-slate-500" aria-hidden="true"></i>
                                <span>Preview Cover Letter (PDF)</span>
                                <i class="fas fa-external-link-alt text-[10px] text-slate-400 ml-0.5" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- =========================================================================
             SECTION 3: Hardcopy Registry & Physical Pickup Schedule
             ========================================================================= --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 space-y-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#8B0000]/10 text-[#8B0000] flex items-center justify-center font-bold text-xs">
                    3
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-heading">
                        Hardcopy Archival & Researcher Pickup Schedule
                    </h2>
                    <p class="text-xs text-slate-500">
                        Schedule physical document release and record dispatch details
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pt-2 items-center">
                {{-- Pickup date input --}}
                <div class="md:col-span-5">
                    <label for="pickup_date" class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1.5">
                        <i class="fas fa-calendar-alt text-amber-700 mr-1" aria-hidden="true"></i>
                        Scheduled Pickup Date <span class="text-[#8B0000]">*</span>
                    </label>
                    <input type="date" id="pickup_date" name="pickup_date"
                        value="{{ old('pickup_date', now()->addDays(2)->toDateString()) }}"
                        class="block w-full rounded-xl border border-amber-300/90 bg-amber-50/40 px-3.5 py-2.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                        required>
                    <p class="text-[11px] text-amber-800 mt-1">
                        The researcher will be prompted to pick up their sealed hardcopies on this date.
                    </p>
                </div>

                {{-- Security & Dispatch Notice --}}
                <div class="md:col-span-7 p-4 bg-slate-50 border border-slate-200/80 rounded-xl flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fas fa-shield-check text-sm" aria-hidden="true"></i>
                    </div>
                    <div class="text-xs text-slate-600 leading-relaxed">
                        <span class="font-bold text-slate-800 block text-xs">Automated Registry Dispatch</span>
                        Generating will create sealed PDF records in the institutional vault, update protocol status to <strong class="text-emerald-800 font-semibold">Approved</strong>, log an immutable audit event in the activity ledger, and notify the researcher in their portal.
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================================================================
             Executive Action Bar
             ========================================================================= --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <i class="fas fa-file-contract text-slate-400" aria-hidden="true"></i>
                <span>All parameters verified against WMSU research ethics guidelines.</span>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="{{ route('admin.certifications') }}"
                   class="h-11 min-h-[44px] px-5 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 uppercase tracking-wider bg-white hover:bg-slate-100 transition-colors flex items-center justify-center cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#8B0000]">
                    Cancel
                </a>
                
                <button type="submit" id="submitBtn"
                    class="h-11 min-h-[44px] px-7 rounded-xl bg-gradient-to-r from-[#8B0000] to-[#a51d1d] hover:from-[#730000] hover:to-[#8B0000] text-white text-xs font-bold uppercase tracking-wider shadow-md hover:shadow-lg transition-all flex items-center gap-2.5 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#8B0000]">
                    <i class="fas fa-stamp text-sm" aria-hidden="true"></i>
                    <span id="submitLabel">Issue & Seal Clearance Documents</span>
                </button>
            </div>
        </div>

    </form>
</div>

{{-- Dynamic Interaction Script --}}
<script>
    // Character count for Study Protocol Title
    const titleInput = document.getElementById('shared_title');
    const charCountEl = document.getElementById('titleCharCount');
    if (titleInput && charCountEl) {
        function updateCharCount() {
            charCountEl.textContent = `${titleInput.value.length} chars`;
        }
        titleInput.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    // Auto-calculate expiry to 1 year after approved period
    const approvedPeriodInput = document.getElementById('cover_approved_period');
    if (approvedPeriodInput) {
        approvedPeriodInput.addEventListener('change', function () {
            const expiryInput = document.getElementById('cover_expiry_date');
            if (expiryInput && this.value) {
                const approvedDate = new Date(this.value);
                approvedDate.setFullYear(approvedDate.getFullYear() + 1);
                expiryInput.value = approvedDate.toISOString().split('T')[0];
            }
        });
    }

    // Reset summary button
    const defaultSummaryText = @json($defaultSummary);
    const resetSummaryBtn = document.getElementById('resetSummaryBtn');
    const certSummaryInput = document.getElementById('cert_reo_summary');
    if (resetSummaryBtn && certSummaryInput) {
        resetSummaryBtn.addEventListener('click', function () {
            certSummaryInput.value = defaultSummaryText;
        });
    }

    // Form submission management for previews vs final issuance
    document.getElementById('generateForm').addEventListener('submit', function (e) {
        // If a preview button was triggered, allow new tab without loading lock
        if (e.submitter && e.submitter.name === 'action' && e.submitter.value.startsWith('preview_')) {
            setTimeout(() => {
                document.getElementById('generateForm').removeAttribute('target');
            }, 150);
            return;
        }

        const btn   = document.getElementById('submitBtn');
        const label = document.getElementById('submitLabel');
        
        // Append hidden action=generate
        const hiddenAction = document.createElement('input');
        hiddenAction.type = 'hidden';
        hiddenAction.name = 'action';
        hiddenAction.value = 'generate';
        this.appendChild(hiddenAction);

        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        label.textContent = 'Generating & Sealing…';
    });
</script>
</x-admin_layout>
