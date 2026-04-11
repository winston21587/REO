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
            <form id="rcLetterForm" action="{{ route('admin.recommendation.generate') }}" method="POST" onsubmit="this.target = event.submitter && event.submitter.value === 'view' ? '_blank' : '_self'" class="p-8 space-y-8">
                @csrf
                <input type="hidden" name="id" value="{{ $submission->id }}">
                <input type="hidden" name="email" value="{{ $submission->researcher->user->email ?? '' }}">

                <!-- History of RC Letters -->
                @if(isset($hasLetter) && $hasLetter || (isset($archivedLetters) && $archivedLetters->isNotEmpty()))
                <div x-data="{ open: false }" class="border border-slate-200 rounded-xl bg-slate-50 overflow-hidden">
                    <button type="button" @click="open = !open" class="w-full px-5 py-3.5 flex items-center justify-between text-left hover:bg-slate-100/80 transition-colors focus:outline-none">
                        <div class="flex items-center gap-2.5">
                            <i class="fas fa-history text-slate-400"></i>
                            <span class="text-sm font-bold text-slate-700">History of RC Letters ({{ (isset($hasLetter) && $hasLetter ? 1 : 0) + (isset($archivedLetters) ? $archivedLetters->count() : 0) }})</span>
                        </div>
                        <i class="fas fa-chevron-down text-slate-400 text-[11px] transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-5 pb-5 pt-1 space-y-3">
                            {{-- Active letter shown first --}}
                            @if(isset($hasLetter) && $hasLetter)
                            <div class="flex items-center justify-between p-3 bg-emerald-50 border border-emerald-200 rounded-xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 border border-emerald-200">
                                        <i class="fas fa-file-circle-check"></i>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold text-emerald-800 line-clamp-1">Active Result of Review</p>
                                        <p class="text-[11px] font-medium text-emerald-500 mt-0.5">Current version</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.recommendation.view_saved', $submission->id) }}" target="_blank" class="text-emerald-500 hover:text-emerald-700 hover:bg-emerald-100 w-8 h-8 rounded-full flex items-center justify-center transition-all bg-white border border-emerald-200 flex-shrink-0">
                                    <i class="fas fa-external-link-alt text-[10px]"></i>
                                </a>
                            </div>
                            @endif

                            {{-- Archived letters --}}
                            @if(isset($archivedLetters))
                            @foreach($archivedLetters as $archived)
                            <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:border-slate-200 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                                        <i class="fas fa-file-signature"></i>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold text-slate-700 line-clamp-1">Archived Result of Review</p>
                                        <p class="text-[11px] font-medium text-slate-400 flex items-center gap-1.5 mt-0.5">
                                            <i class="far fa-clock text-[10px]"></i>
                                            {{ $archived->created_at->format('M d, Y \\a\\t h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.serve_file', $archived->id) }}" target="_blank" class="text-slate-400 hover:text-[#8B0000] hover:bg-red-50 w-8 h-8 rounded-full flex items-center justify-center transition-all bg-white border border-slate-100 flex-shrink-0">
                                    <i class="fas fa-external-link-alt text-[10px]"></i>
                                </a>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
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
                    <button type="button" id="openFinalizeBtn" class="flex-1 px-6 py-3 bg-[#8B0000] text-white rounded-xl text-sm font-bold hover:bg-[#6d0000] transition-colors shadow-lg shadow-red-900/20 flex justify-center items-center gap-2">
                        <i class="fas fa-save"></i> Save & Send
                    </button>
                </div>

                {{-- Hidden fields injected by the modal --}}
                <input type="hidden" name="action" id="formAction" value="view">
                <input type="hidden" name="deadline" id="formDeadline" value="">

            </form>
        </div>
    </div>

    <!-- Finalize Review Modal -->
    <div id="finalizeModal" class="fixed inset-0 z-50 hidden" aria-labelledby="finalize-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeFinalizeModal()"></div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200">
                    
                    <!-- Modal Body -->
                    <div class="px-8 py-8 bg-white text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 mb-5">
                            <i class="fas fa-question text-slate-400 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2" id="finalize-title">Finalize Review?</h3>
                        <p class="text-sm text-slate-500 mb-6">
                            Are you sure you want to send the Recommendation Letter for 
                            "<strong class="text-slate-700">{{ $submission->Study_Protocol_title }}</strong>"?
                            This will notify the researcher and set the status to Waiting for Revision.
                        </p>

                        <div class="text-left mb-6">
                            <label for="deadlineInput" class="block text-sm font-bold text-slate-700 mb-2">
                                Revision Deadline <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="deadlineInput" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-[#8B0000] focus:border-transparent shadow-sm"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            <p id="deadlineError" class="text-xs text-red-500 mt-1 hidden">Please select a revision deadline.</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-slate-50 px-8 py-5 flex justify-center gap-3 border-t border-slate-100">
                        <button type="button" onclick="confirmFinalizeSend()" class="px-8 py-2.5 bg-[#8B0000] text-white text-sm font-bold rounded-xl hover:bg-[#6d0000] transition-colors shadow-lg shadow-red-900/20">
                            Yes, Proceed
                        </button>
                        <button type="button" onclick="closeFinalizeModal()" class="px-8 py-2.5 bg-white text-slate-600 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('checkAllBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Check All' : 'Uncheck All';
        });

        // Finalize Modal Logic
        const form = document.getElementById('rcLetterForm');
        const modal = document.getElementById('finalizeModal');

        document.getElementById('openFinalizeBtn').addEventListener('click', function() {
            // Validate the form before showing the modal
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            modal.classList.remove('hidden');
        });

        function closeFinalizeModal() {
            modal.classList.add('hidden');
        }

        function confirmFinalizeSend() {
            const deadlineInput = document.getElementById('deadlineInput');
            const deadlineError = document.getElementById('deadlineError');

            if (!deadlineInput.value) {
                deadlineError.classList.remove('hidden');
                deadlineInput.classList.add('border-red-500');
                return;
            }

            deadlineError.classList.add('hidden');
            deadlineInput.classList.remove('border-red-500');

            // Inject values into the form
            document.getElementById('formAction').value = 'save';
            document.getElementById('formDeadline').value = deadlineInput.value;
            form.target = '_self';

            // Close modal and submit
            closeFinalizeModal();
            form.submit();
        }
    </script>
</x-admin_layout>
