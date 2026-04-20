<!-- AI Predict Modal -->
<div id="aiPredictModal" class="fixed inset-0 z-[60] hidden group" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data="{ open: false }" @open-ai-predict-modal.window="open = true; setTimeout(() => $el.classList.remove('opacity-0'), 10);" @close-ai-predict-modal.window="open = false; $el.classList.add('opacity-0'); setTimeout(() => $el.classList.add('hidden'), 300);" :class="open ? '' : 'opacity-0'">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity duration-300" aria-hidden="true" @click="$dispatch('close-ai-predict-modal')"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md scale-95 duration-300 ease-out"
                 :class="open ? 'scale-100 opacity-100' : 'scale-95 opacity-0'">
                
                <div class="bg-[#0f172a] px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-magic text-[#8B0000]"></i> AI Review Prediction
                    </h3>
                    <button type="button" @click="$dispatch('close-ai-predict-modal')" class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="mb-5">
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1 block">Protocol Title</span>
                        <p class="text-sm font-medium text-slate-900 leading-tight" id="ai-predict-modal-title">Loading...</p>
                    </div>

                    <div id="ai-predict-loader" class="flex flex-col items-center justify-center py-6">
                        <div class="relative w-16 h-16 mb-4">
                            <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-[#8B0000] rounded-full border-t-transparent animate-spin"></div>
                            <i class="fas fa-robot absolute inset-0 flex items-center justify-center text-lg text-[#8B0000] animate-pulse"></i>
                        </div>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-[#8B0000] leading-tight">Analyzing Title...</span>
                    </div>

                    <div id="ai-predict-result" class="hidden">
                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-6 text-center">
                            <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider mb-2">Suggested Review Type</p>
                            <h4 class="text-xl font-black text-indigo-900" id="ai-predict-suggested-label">Exempt Review</h4>
                        </div>
                        
                        <input type="hidden" id="ai-predict-protocol-id" value="">
                        
                        <div class="flex gap-3">
                            <button type="button" id="ai-predict-cancel-btn" @click="$dispatch('close-ai-predict-modal')" class="flex-1 py-2.5 px-4 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all text-center">
                                Cancel
                            </button>
                            <button type="button" onclick="saveAiPrediction()" id="ai-predict-save-btn" class="flex-1 flex py-2.5 px-4 bg-[#8B0000] text-white rounded-xl text-sm font-bold shadow-lg shadow-red-900/20 hover:bg-red-800 hover:-translate-y-0.5 transition-all justify-center items-center gap-2">
                                Save Prediction <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </div>

                    <div id="ai-predict-error" class="hidden text-center py-6">
                        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">Prediction Failed</h4>
                        <p class="text-xs text-slate-500" id="ai-predict-error-msg">The AI service is currently unavailable.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openAiPredictModal(id, title, existingSuggestion = null) {
        document.getElementById('ai-predict-modal-title').textContent = title;
        document.getElementById('ai-predict-protocol-id').value = id;
        
        const modal = document.getElementById('aiPredictModal');
        modal.classList.remove('hidden');
        window.dispatchEvent(new CustomEvent('open-ai-predict-modal'));

        const loader = document.getElementById('ai-predict-loader');
        const resultContainer = document.getElementById('ai-predict-result');
        const errorContainer = document.getElementById('ai-predict-error');
        const saveBtn = document.getElementById('ai-predict-save-btn');
        const cancelBtn = document.getElementById('ai-predict-cancel-btn');
        
        loader.classList.remove('hidden');
        resultContainer.classList.add('hidden');
        errorContainer.classList.add('hidden');

        // Check if there is an existing prediction cached in the database
        if (existingSuggestion && existingSuggestion !== 'null' && existingSuggestion !== '') {
            loader.classList.add('hidden');
            resultContainer.classList.remove('hidden');
            document.getElementById('ai-predict-suggested-label').innerText = existingSuggestion;
            
            // Hide save button since it's already saved
            saveBtn.classList.add('hidden');
            saveBtn.classList.remove('flex');
            cancelBtn.innerText = 'Close';
            
            return;
        }

        // Reset visibility for new fetches
        saveBtn.classList.remove('hidden');
        saveBtn.classList.add('flex');
        cancelBtn.innerText = 'Cancel';

        fetch('/admin/predict', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ text: title })
        })
        .then(res => res.json())
        .then(data => {
            loader.classList.add('hidden');
            if (data.success && (data.label || (data.prediction && data.prediction.prediction))) {
                resultContainer.classList.remove('hidden');
                let predLabel = data.label || data.prediction.prediction;
                if (!predLabel.includes('Review')) predLabel += ' Review';
                document.getElementById('ai-predict-suggested-label').innerText = predLabel;
            } else {
                errorContainer.classList.remove('hidden');
                if (data.message) document.getElementById('ai-predict-error-msg').innerText = data.message;
            }
        })
        .catch(err => {
            console.error('AI Predict Error:', err);
            loader.classList.add('hidden');
            errorContainer.classList.remove('hidden');
        });
    }

    function saveAiPrediction() {
        const id = document.getElementById('ai-predict-protocol-id').value;
        const suggestion = document.getElementById('ai-predict-suggested-label').innerText;
        const btn = document.getElementById('ai-predict-save-btn');
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        btn.disabled = true;

        fetch('/admin/predict/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ protocol_id: id, suggested_review_type: suggestion })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = 'Save Prediction <i class="fas fa-save"></i>';
            if (data.success) {
                // Find existing button indicator and update it if possible, or just close and show tiny toast
                window.dispatchEvent(new CustomEvent('close-ai-predict-modal'));
                
                // Show a quick success alert or toast (basic fallback)
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 z-[9999] bg-slate-900 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 animate-[fadeInUp_0.3s_ease-out] font-medium text-sm border border-slate-700';
                toast.innerHTML = `<i class="fas fa-check-circle text-green-400 text-lg"></i> AI Prediction Saved`;
                document.body.appendChild(toast);
                
                // Update badge in UI table
                const badge = document.getElementById('ai-badge-' + id);
                if (badge) {
                    badge.innerHTML = `<i class="fas fa-robot text-xs"></i> AI: ${suggestion}`;
                    badge.classList.remove('bg-indigo-50', 'text-indigo-600', 'border-indigo-200');
                    badge.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
                }

                setTimeout(() => toast.remove(), 3000);
            }
        });
    }
</script>
