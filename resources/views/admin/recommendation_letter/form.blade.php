<x-admin_layout>
    <div class="max-w-4xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Generate Recommendation Letter</h1>
                <p class="text-slate-500 mt-2 text-sm">Result of Review Form</p>
            </div>
            <a href="{{ route('admin.applications') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Applications
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <form action="{{ route('admin.recommendation.generate') }}" method="POST" onsubmit="this.target = event.submitter.value === 'view' ? '_blank' : '_self'" class="p-8 space-y-8">
                @csrf
                <input type="hidden" name="id" value="{{ $submission->id }}">
                <input type="hidden" name="email" value="{{ $submission->researcher->user->email ?? '' }}">

                <!-- Existing Letter Indicator -->
                @if(isset($hasLetter) && $hasLetter)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between gap-3 animate-pulse">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-green-800">Recommendation Letter Already Generated</h4>
                            <p class="text-xs text-green-600">A letter has already been created for this protocol.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.recommendation.view_saved', $submission->id) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-eye"></i> View Letter
                    </a>
                </div>
                @endif

                <!-- Section 1: Basic Info -->
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#8B0000]"></i> Basic Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Paper Entitled</label>
                            <div class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 font-medium shadow-sm">
                                {{ $submission->Study_Protocol_title }}
                            </div>
                            <input type="hidden" name="title" value="{{ $submission->Study_Protocol_title }}">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Review Type</label>
                            @php $defaultRT = request('review_type', $submission->Review_Type); @endphp
                            <select name="review_type" class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm bg-white cursor-pointer">
                                <option value="Exempt Review" {{ $defaultRT == 'Exempt Review' ? 'selected' : '' }}>Exempt Review</option>
                                <option value="Expedited Review" {{ $defaultRT == 'Expedited Review' ? 'selected' : '' }}>Expedited Review</option>
                                <option value="Full Board Review" {{ $defaultRT == 'Full Board Review' ? 'selected' : '' }}>Full Board Review</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Number of Sets to Submit <span class="text-red-500">*</span></label>
                            <input type="text" name="num_sets" class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm" placeholder="e.g. 3" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Envelope Type <span class="text-red-500">*</span></label>
                            <input type="text" name="envelope_type" class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm" placeholder="e.g. Brown Envelope" required>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Checkboxes -->
                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-tasks text-[#8B0000]"></i> Review Checklist
                        </h3>
                        <button type="button" id="checkAllBtn" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition-colors">
                            Check All
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Protocol/Proposal -->
                        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="font-bold text-[#8B0000] text-sm uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                                In the Protocol/Proposal
                            </h4>
                            <div class="space-y-2">
                                <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="ethics_review_1[]" value="1" class="peer h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                    </div>
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Anonymity/Confidentiality of the data</span>
                                </label>
                                <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors group">
                                    <input type="checkbox" name="ethics_review_1[]" value="2" class="peer h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Plan on processing personal data, access, disposal and terms of use</span>
                                </label>
                                <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors group">
                                    <input type="checkbox" name="ethics_review_1[]" value="3" class="peer h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Measures to protect privacy of participants</span>
                                </label>
                                <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors group">
                                    <input type="checkbox" name="ethics_review_1[]" value="4" class="peer h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Appropriate mechanisms/interventions to address vulnerability</span>
                                </label>
                                <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors group">
                                    <input type="checkbox" name="ethics_review_1[]" value="5" class="peer h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Measures to mitigate the risks</span>
                                </label>
                                <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors group">
                                    <input type="checkbox" name="ethics_review_1[]" value="6" class="peer h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Disclosure of conflict of Interest</span>
                                </label>
                            </div>
                        </div>

                        <!-- Informed Consent -->
                        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="font-bold text-[#8B0000] text-sm uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                                In the Informed Consent
                            </h4>
                            <div class="space-y-2">
                                @php
                                    $consentItems = [
                                        1 => 'Purpose of the study',
                                        2 => 'Expected duration of participation',
                                        3 => 'Adequate process for ensuring that consent is voluntary',
                                        4 => 'Procedures to be carried out',
                                        5 => 'Mechanisms in cases of discomforts and risks',
                                        6 => 'Benefits to the participants',
                                        7 => 'Compensations/reimbursement of expenses',
                                        8 => 'Withdrawal of participants from the study anytime without penalty',
                                        9 => 'Duties and responsibilities of participants',
                                        10 => 'Extent of confidentiality',
                                        11 => 'Ensuring language is understood (Translation)',
                                        12 => 'Contact person',
                                        13 => 'Include REOC contact details'
                                    ];
                                @endphp

                                @foreach($consentItems as $key => $label)
                                <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors group">
                                    <input type="checkbox" name="ethics_review_2[]" value="{{ $key }}" class="peer h-5 w-5 rounded border-slate-300 text-[#8B0000] focus:ring-[#8B0000]">
                                    <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- Section 3: Recommended Actions & Notes -->
                <div class="space-y-6">
                    <h4 class="font-bold text-slate-700 text-sm uppercase tracking-wider">Recommended Actions</h4>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer">
                            <input type="checkbox" name="Recommended_Actions[]" value="1" class="rounded text-[#8B0000] focus:ring-[#8B0000]">
                            <span class="text-sm text-slate-600">Pls. Incorporate required information</span>
                        </label>
                        <label class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer">
                            <input type="checkbox" name="Recommended_Actions[]" value="2" class="rounded text-[#8B0000] focus:ring-[#8B0000]">
                            <span class="text-sm text-slate-600">For Payment at the University Cashier</span>
                        </label>
                    </div>

                    <div>
                        <label for="extraNotes" class="block text-sm font-bold text-slate-700 mb-2">Extra Notes</label>
                        <textarea name="extraNotes" id="extraNotes" rows="5" class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm resize-none"></textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4">
                    <button type="submit" name="action" value="view" formnovalidate class="flex-1 px-6 py-3 bg-white border-2 border-[#8B0000] text-[#8B0000] rounded-xl text-sm font-bold hover:bg-red-50 transition-colors flex justify-center items-center gap-2">
                        <i class="fas fa-eye"></i> View PDF
                    </button>
                    <button type="submit" name="action" value="save" class="flex-1 px-6 py-3 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#6d0000] transition-colors shadow-lg shadow-red-900/20 flex justify-center items-center gap-2">
                        <i class="fas fa-save"></i> Save & Send
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.getElementById('checkAllBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Check All' : 'Uncheck All';
        });
    </script>
</x-admin_layout>
