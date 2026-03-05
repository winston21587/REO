<x-admin_layout>
<div class="max-w-5xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end pb-6 border-b border-slate-200 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.certifications') }}" class="text-slate-400 hover:text-[#8B0000] transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 font-heading tracking-tight">Generate Certification Documents</h1>
            </div>
            <p class="text-slate-500 text-sm ml-7">
                <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded mr-2">#{{ str_pad($submission->id, 5, '0', STR_PAD_LEFT) }}</span>
                {{ $submission->Study_Protocol_title }}
            </p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 ml-7 md:ml-0">
            <i class="fas fa-user-circle text-slate-400"></i>
            <span>{{ $researcherName ?: 'Unknown Researcher' }}</span>
        </div>
    </div>

    <form action="{{ route('admin.certificate.generate', $submission->id) }}" method="POST" id="generateForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ==========================================
                 SECTION 1: Certificate of Exemption
                 ========================================== --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden flex flex-col">
                {{-- Section header --}}
                <div class="bg-gradient-to-r from-[#8B0000] to-[#a52828] px-6 py-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-award text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-sm">Certificate of Exemption</h2>
                        <p class="text-red-200 text-xs">Ethics Clearance Certificate</p>
                    </div>
                </div>

                <div class="p-6 space-y-4 flex-1">

                    {{-- Names --}}
                    <div>
                        <label for="cert_names" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                            Names <span class="text-[#8B0000]">*</span>
                        </label>
                        <input type="text" id="cert_names" name="cert_names"
                            value="{{ old('cert_names', $researcherName) }}"
                            class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-shadow"
                            placeholder="Full name(s) of researcher(s)" required>
                        <p class="mt-1 text-xs text-slate-400">Add all researcher names if multiple.</p>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="cert_title" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                            Title <span class="text-[#8B0000]">*</span>
                        </label>
                        <textarea id="cert_title" name="cert_title" rows="3"
                            class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent resize-none transition-shadow"
                            placeholder="Full research protocol title" required>{{ old('cert_title', $submission->Study_Protocol_title) }}</textarea>
                    </div>

                    {{-- REO Code --}}
                    <div>
                        <label for="cert_reo_code" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                            REO Code <span class="font-normal text-slate-400 normal-case">(Optional)</span>
                        </label>
                        <input type="text" id="cert_reo_code" name="cert_reo_code"
                            value="{{ old('cert_reo_code', $submission->reoc_code ?? '') }}"
                            class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent transition-shadow"
                            placeholder="e.g. REOC-2026-001">
                    </div>

                    {{-- REO Summary --}}
                    <div>
                        <label for="cert_reo_summary" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                            REO Summary <span class="font-normal text-slate-400 normal-case">(Optional)</span>
                        </label>
                        <textarea id="cert_reo_summary" name="cert_reo_summary" rows="4"
                            class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent resize-none transition-shadow"
                            placeholder="Brief summary of the exemption scope…">{{ old('cert_reo_summary') }}</textarea>
                        <p class="mt-1 text-xs text-slate-400">This will appear as the exemption summary on the certificate.</p>
                    </div>

                </div>

                {{-- Section footer for Preview --}}
                <div class="bg-slate-50 px-6 py-4 flex justify-end border-t border-slate-100 mt-auto">
                    <button type="submit" name="action" value="preview_cert" formtarget="_blank" formnovalidate
                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-[#8B0000] bg-red-50 hover:bg-red-100 rounded-lg transition-colors border border-red-200 shadow-sm">
                        <i class="fas fa-eye text-red-400"></i> Preview Certificate
                    </button>
                </div>
            </div>

            {{-- ==========================================
                 SECTION 2: Cover Letter
                 ========================================== --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden flex flex-col">
                {{-- Section header --}}
                <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-6 py-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-envelope-open-text text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-sm">Cover Letter</h2>
                        <p class="text-slate-300 text-xs">Cover Letter of Approval</p>
                    </div>
                </div>

                <div class="p-6 space-y-4 flex-1">

                    {{-- REO Code --}}
                    <div>
                        <label for="cover_reo_code" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                            REO Code <span class="font-normal text-slate-400 normal-case">(Optional)</span>
                        </label>
                        <input type="text" id="cover_reo_code" name="cover_reo_code"
                            value="{{ old('cover_reo_code', $submission->reoc_code ?? '') }}"
                            class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent transition-shadow"
                            placeholder="e.g. REOC-2026-001">
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="cover_title" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                            Title <span class="text-[#8B0000]">*</span>
                        </label>
                        <textarea id="cover_title" name="cover_title" rows="3"
                            class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent resize-none transition-shadow"
                            placeholder="Research protocol title as it appears on the letter" required>{{ old('cover_title', $submission->Study_Protocol_title) }}</textarea>
                    </div>

                    {{-- Approved Period & Expiry --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="cover_approved_period" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                                Approved Period <span class="text-[#8B0000]">*</span>
                            </label>
                            <input type="date" id="cover_approved_period" name="cover_approved_period"
                                value="{{ old('cover_approved_period') }}"
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent transition-shadow"
                                required>
                        </div>
                        <div>
                            <label for="cover_expiry_date" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                                Expiry Date <span class="text-[#8B0000]">*</span>
                            </label>
                            <input type="date" id="cover_expiry_date" name="cover_expiry_date"
                                value="{{ old('cover_expiry_date') }}"
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent transition-shadow"
                                required>
                        </div>
                    </div>

                    {{-- Researcher --}}
                    <div>
                        <label for="cover_researcher" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                            Researcher <span class="text-[#8B0000]">*</span>
                        </label>
                        <input type="text" id="cover_researcher" name="cover_researcher"
                            value="{{ old('cover_researcher', $researcherName) }}"
                            class="block w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent transition-shadow"
                            placeholder="Lead researcher name" required>
                    </div>

                </div>

                {{-- Section footer for Preview --}}
                <div class="bg-slate-50 px-6 py-4 flex justify-end border-t border-slate-100 mt-auto">
                    <button type="submit" name="action" value="preview_cover" formtarget="_blank" formnovalidate
                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-100 rounded-lg transition-colors border border-slate-300 shadow-sm">
                        <i class="fas fa-eye text-slate-400"></i> Preview Cover Letter
                    </button>
                </div>
            </div>

        </div>

        {{-- ==========================================
             Pickup Notification + Actions
             ========================================== --}}
        <div class="mt-6 bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">

                {{-- Pickup date --}}
                <div class="flex-1 min-w-0">
                    <label for="pickup_date" class="block text-xs font-semibold text-amber-700 uppercase tracking-wider mb-1">
                        <i class="fas fa-calendar-alt mr-1"></i> Researcher Pickup Date <span class="text-[#8B0000]">*</span>
                    </label>
                    <input type="date" id="pickup_date" name="pickup_date"
                        value="{{ old('pickup_date') }}"
                        class="block w-full max-w-xs rounded-lg border border-amber-200 bg-amber-50 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-shadow"
                        required>
                    <p class="mt-1 text-xs text-slate-400">The researcher will be notified with this pickup date.</p>
                </div>

                {{-- Info note --}}
                <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-100 rounded-xl flex-1">
                    <i class="fas fa-info-circle text-blue-400 mt-0.5 flex-shrink-0"></i>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Both documents will be generated using the REO PDF templates and automatically saved. The researcher will receive an in-app notification.
                    </p>
                </div>

            </div>
        </div>

        {{-- Validation errors --}}
        @if($errors->any())
        <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-red-400 mt-0.5 flex-shrink-0"></i>
            <ul class="text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Action buttons --}}
        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.certifications') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition-colors">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" id="submitBtn"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#8B0000] text-white text-sm font-semibold shadow-md hover:bg-[#a52828] transition-colors">
                <i class="fas fa-stamp"></i>
                <span id="submitLabel">Generate & Notify Researcher</span>
            </button>
        </div>

    </form>
</div>

<script>
    // Auto-populate expiry to 1 year after approved period
    document.getElementById('cover_approved_period').addEventListener('change', function () {
        if (!document.getElementById('cover_expiry_date').value) {
            const approvedDate = new Date(this.value);
            approvedDate.setFullYear(approvedDate.getFullYear() + 1);
            document.getElementById('cover_expiry_date').value = approvedDate.toISOString().split('T')[0];
        }
    });

    // Sync REO code between both sections
    document.getElementById('cert_reo_code').addEventListener('input', function () {
        if (!document.getElementById('cover_reo_code').value) {
            document.getElementById('cover_reo_code').value = this.value;
        }
    });

    // Show loading state on submit ONLY for the main generate action
    document.getElementById('generateForm').addEventListener('submit', function (e) {
        // If a preview button was clicked (submitter has name="action"), do not show loading state
        if (e.submitter && e.submitter.name === 'action' && e.submitter.value.startsWith('preview_')) {
            // Slight delay then restore target to default in case browser keeps it
            setTimeout(() => {
                document.getElementById('generateForm').removeAttribute('target');
            }, 100);
            return; // Allow the preview to open in a new tab normally without blocking the form
        }

        const btn   = document.getElementById('submitBtn');
        const label = document.getElementById('submitLabel');
        
        // Append a hidden action=generate so the backend knows this is the real submission
        const hiddenAction = document.createElement('input');
        hiddenAction.type = 'hidden';
        hiddenAction.name = 'action';
        hiddenAction.value = 'generate';
        this.appendChild(hiddenAction);

        btn.disabled      = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        label.textContent = 'Generating…';
    });
</script>
</x-admin_layout>
