<x-admin_layout>
    <div class="max-w-5xl mx-auto py-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 font-heading">Generate Result of Review</h1>
                <p class="text-slate-500 text-sm mt-1">Select the required revisions for: <span class="font-bold text-[#8B0000]">{{ $submission->Study_Protocol_title }}</span></p>
            </div>
            <a href="{{ route('admin.applications') }}" class="text-slate-500 hover:text-slate-700 font-bold text-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <form action="{{ route('admin.letter.preview') }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="submission_id" value="{{ $submission->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 border-b pb-2">1. Review Classification</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="review_type" value="Exempt" class="peer sr-only" required>
                            <div class="p-3 text-center border rounded-lg peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 hover:bg-slate-50">
                                Exempt Review
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="review_type" value="Expedited" class="peer sr-only">
                            <div class="p-3 text-center border rounded-lg peer-checked:bg-orange-50 peer-checked:border-orange-500 peer-checked:text-orange-700 hover:bg-slate-50">
                                Expedited Review
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="review_type" value="Full Board" class="peer sr-only">
                            <div class="p-3 text-center border rounded-lg peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 hover:bg-slate-50">
                                Full Board Review
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="font-bold text-slate-800">2. In the Protocol/Proposal</h3>
                        <button type="button" class="text-xs text-blue-600 font-bold hover:underline" onclick="toggleAll('protocol_issues[]')">Select All</button>
                    </div>
                    <div class="space-y-3">
                        @php
                            $protocolItems = [
                                "Anonymity/Confidentiality of the data",
                                "Plan on processing personal data (Data Privacy Act 2012)",
                                "Measures to protect privacy of participants",
                                "Mechanisms to address vulnerability issues",
                                "Measures to mitigate risks",
                                "Disclosure of Conflict of Interest"
                            ];
                        @endphp
                        @foreach($protocolItems as $item)
                        <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" name="protocol_issues[]" value="{{ $item }}" class="mt-1 rounded text-[#8B0000] focus:ring-[#8B0000]">
                            <span class="text-sm text-slate-700">{{ $item }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="font-bold text-slate-800">3. In the Informed Consent</h3>
                        <button type="button" class="text-xs text-blue-600 font-bold hover:underline" onclick="toggleAll('consent_issues[]')">Select All</button>
                    </div>
                    <div class="space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
                        @php
                            $consentItems = [
                                "Purpose of the study",
                                "Expected duration of participation",
                                "Adequate process for ensuring consent is voluntary",
                                "Procedures to be carried out",
                                "Mechanisms in cases of discomforts and risks",
                                "Benefits to the participants",
                                "Compensations/reimbursement of expenses",
                                "Withdrawal of participants without penalty",
                                "Duties and responsibilities of participants",
                                "Extent of confidentiality",
                                "Language understood by participants (Translation)",
                                "Contact person details",
                                "Include REOC contact details"
                            ];
                        @endphp
                        @foreach($consentItems as $item)
                        <label class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">
                            <input type="checkbox" name="consent_issues[]" value="{{ $item }}" class="mt-1 rounded text-[#8B0000] focus:ring-[#8B0000]">
                            <span class="text-sm text-slate-700">{{ $item }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="md:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 border-b pb-2">4. Recommended Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="recommended_actions[]" value="Pls. Incorporate required information" class="rounded text-[#8B0000] focus:ring-[#8B0000]">
                            <span class="text-sm font-bold text-slate-700">Please Incorporate required information</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="recommended_actions[]" value="For Payment at the University Cashier" class="rounded text-[#8B0000] focus:ring-[#8B0000]">
                            <span class="text-sm font-bold text-slate-700">For Payment at the University Cashier</span>
                        </label>
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Additional Remarks / Notes</label>
                        <textarea name="remarks" rows="3" class="w-full p-3 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000]"></textarea>
                    </div>
                </div>

            </div>

            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-lg z-50">
                <div class="max-w-5xl mx-auto flex justify-end items-center gap-4">
                    <span class="text-xs text-slate-500">This will open a printable preview in a new tab.</span>
                    <button type="submit" class="px-6 py-3 bg-[#8B0000] text-white font-bold rounded-xl hover:bg-[#6d0000] transition-transform active:scale-95 shadow-lg flex items-center gap-2">
                        <i class="fas fa-print"></i> Generate Printable Letter
                    </button>
                </div>
            </div>
            <div class="h-20"></div> </form>
    </div>

    <script>
        function toggleAll(name) {
            const checkboxes = document.querySelectorAll(`input[name="${name}"]`);
            const allChecked = Array.from(checkboxes).every(c => c.checked);
            checkboxes.forEach(c => c.checked = !allChecked);
        }
    </script>
</x-admin_layout>