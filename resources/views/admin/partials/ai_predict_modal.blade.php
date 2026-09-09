<!-- AI Predict Modal -->
<div id="aiPredictModal" class="fixed inset-0 z-[60] hidden group" aria-labelledby="ai-predict-modal-heading" role="dialog" aria-modal="true" x-data="{ open: false, openThinking: false }" @open-ai-predict-modal.window="open = true; openThinking = false; setTimeout(() => $el.classList.remove('opacity-0'), 10);" @close-ai-predict-modal.window="open = false; $el.classList.add('opacity-0'); setTimeout(() => $el.classList.add('hidden'), 300);" @keydown.escape.window="if (open) $dispatch('close-ai-predict-modal')" :class="open ? '' : 'opacity-0'">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity duration-300" aria-hidden="true" @click="$dispatch('close-ai-predict-modal')"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg scale-95 duration-300 ease-out border border-slate-200/80"
                 :class="open ? 'scale-100 opacity-100' : 'scale-95 opacity-0'">
                
                <!-- Modal Header -->
                <div class="bg-[#0f172a] px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#8B0000]/30 border border-[#8B0000]/60 flex items-center justify-center text-[#ff8080] shadow-xs">
                            <i class="fas fa-brain text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white flex items-center gap-2" id="ai-predict-modal-heading">
                                AI Review Prediction
                            </h3>
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-300 font-mono">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span id="ai-model-tag" class="truncate max-w-[220px]">nex-agi/nex-n2.5-pro:free</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" @click="$dispatch('close-ai-predict-modal')" aria-label="Close AI prediction modal" class="text-slate-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-slate-400 rounded-lg p-1.5 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Protocol Title Card -->
                    <div class="mb-5 bg-slate-50 border border-slate-200/80 rounded-xl p-3.5">
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1 block flex items-center gap-1">
                            <i class="fas fa-file-alt text-[10px] text-slate-400"></i> Protocol Title
                        </span>
                        <p class="text-xs sm:text-sm font-semibold text-slate-800 leading-snug break-words" id="ai-predict-modal-title">Loading...</p>
                    </div>

                    <!-- Loader State -->
                    <div id="ai-predict-loader" class="flex flex-col items-center justify-center py-8">
                        <div class="relative w-16 h-16 mb-4">
                            <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-[#8B0000] rounded-full border-t-transparent animate-spin"></div>
                            <i class="fas fa-microchip absolute inset-0 flex items-center justify-center text-lg text-[#8B0000] animate-pulse"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700 leading-tight">Analyzing with OpenRouter AI...</span>
                        <p class="text-[11px] text-slate-400 mt-1">Deep reasoning enabled · Categorizing ethical review level</p>
                    </div>

                    <!-- Prediction Result State -->
                    <div id="ai-predict-result" class="hidden space-y-4">
                        <!-- Suggested Review Type Banner -->
                        <div id="ai-predict-badge-container" class="rounded-xl p-4 text-center border transition-all bg-emerald-50/70 border-emerald-200 text-emerald-950">
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest mb-1.5 text-emerald-800" id="ai-predict-type-kicker">
                                <i class="fas fa-sparkles text-[10px]"></i> Suggested Review Type
                            </span>
                            <h4 class="text-xl font-black tracking-tight" id="ai-predict-suggested-label">Exempt Review</h4>
                        </div>

                        <!-- AI Rationale / Explanation -->
                        <div id="ai-predict-reason-container" class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 text-left">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1 flex items-center gap-1">
                                <i class="fas fa-info-circle text-[10px] text-slate-400"></i> AI Clinical Rationale
                            </span>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium" id="ai-predict-reason-text">
                                Title provides no indication of human-subjects involvement, identifiable data, or more than minimal risk.
                            </p>
                        </div>

                        <!-- Collapsible Deep Reasoning Process -->
                        <div id="ai-predict-reasoning-wrapper" class="hidden text-left">
                            <button type="button" @click="openThinking = !openThinking" class="w-full flex items-center justify-between text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200/80 border border-slate-200 px-3.5 py-2.5 rounded-xl transition-colors">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-lightbulb text-amber-500"></i>
                                    <span>Model Reasoning Process</span>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-200 text-slate-700">CoT</span>
                                </span>
                                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200 text-slate-500" :class="openThinking ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="openThinking" x-cloak class="mt-2 p-3 bg-slate-900 text-slate-200 rounded-xl text-[11px] font-mono leading-relaxed max-h-48 overflow-y-auto custom-scrollbar border border-slate-800 whitespace-pre-wrap select-text" id="ai-predict-reasoning-text"></div>
                        </div>

                        <input type="hidden" id="ai-predict-protocol-id" value="">

                        <!-- Modal Actions -->
                        <div class="flex gap-3 pt-2">
                            <button type="button" id="ai-predict-cancel-btn" @click="$dispatch('close-ai-predict-modal')" class="flex-1 py-2.5 px-4 bg-white border border-slate-300 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 transition-all text-center">
                                Cancel
                            </button>
                            <button type="button" onclick="saveAiPrediction()" id="ai-predict-save-btn" class="flex-1 flex py-2.5 px-4 bg-[#8B0000] text-white rounded-xl text-xs sm:text-sm font-bold shadow-sm hover:bg-[#6d0000] focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:ring-offset-2 transition-all justify-center items-center gap-2">
                                <span>Apply & Save</span>
                                <i class="fas fa-check text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="ai-predict-error" class="hidden text-center py-6">
                        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                        </div>
                        <h4 class="text-sm font-extrabold text-slate-900 mb-1">Prediction Unavailable</h4>
                        <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed mb-4" id="ai-predict-error-msg">
                            The AI prediction service encountered an issue. You can retry or set review classification manually.
                        </p>
                        <div class="flex justify-center gap-2">
                            <button type="button" onclick="retryAiPrediction()" class="py-2 px-4 bg-[#8B0000] text-white rounded-xl text-xs font-bold hover:bg-[#6d0000] transition-colors flex items-center gap-1.5">
                                <i class="fas fa-redo text-[10px]"></i> Try Again
                            </button>
                            <button type="button" @click="$dispatch('close-ai-predict-modal')" class="py-2 px-4 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPredictTitle = '';
    let currentPredictId = null;

    function applyReviewTypeTheme(label) {
        const container = document.getElementById('ai-predict-badge-container');
        const kicker = document.getElementById('ai-predict-type-kicker');
        if (!container || !kicker) return;

        // Reset classes
        container.className = 'rounded-xl p-4 text-center border transition-all ';
        kicker.className = 'inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest mb-1.5 ';

        const lower = (label || '').toLowerCase();
        if (lower.includes('exempt')) {
            container.className += 'bg-emerald-50/80 border-emerald-200 text-emerald-950';
            kicker.className += 'text-emerald-800';
        } else if (lower.includes('expedited')) {
            container.className += 'bg-amber-50/80 border-amber-200 text-amber-950';
            kicker.className += 'text-amber-800';
        } else if (lower.includes('full board') || lower.includes('board')) {
            container.className += 'bg-rose-50/80 border-rose-200 text-rose-950';
            kicker.className += 'text-rose-800';
        } else {
            container.className += 'bg-slate-50 border-slate-200 text-slate-900';
            kicker.className += 'text-slate-600';
        }
    }

    function openAiPredictModal(id, title, existingSuggestion = null) {
        currentPredictId = id;
        currentPredictTitle = title;

        document.getElementById('ai-predict-modal-title').textContent = title || 'Untitled Protocol';
        document.getElementById('ai-predict-protocol-id').value = id;
        
        const modal = document.getElementById('aiPredictModal');
        modal.classList.remove('hidden');
        window.dispatchEvent(new CustomEvent('open-ai-predict-modal'));

        const loader = document.getElementById('ai-predict-loader');
        const resultContainer = document.getElementById('ai-predict-result');
        const errorContainer = document.getElementById('ai-predict-error');
        const saveBtn = document.getElementById('ai-predict-save-btn');
        const cancelBtn = document.getElementById('ai-predict-cancel-btn');
        const reasoningWrapper = document.getElementById('ai-predict-reasoning-wrapper');
        const reasonContainer = document.getElementById('ai-predict-reason-container');
        
        loader.classList.remove('hidden');
        resultContainer.classList.add('hidden');
        errorContainer.classList.add('hidden');
        reasoningWrapper.classList.add('hidden');

        // Check if there is an existing prediction cached in the database
        if (existingSuggestion && existingSuggestion !== 'null' && existingSuggestion !== '') {
            loader.classList.add('hidden');
            resultContainer.classList.remove('hidden');
            document.getElementById('ai-predict-suggested-label').innerText = existingSuggestion;
            applyReviewTypeTheme(existingSuggestion);
            
            document.getElementById('ai-predict-reason-text').innerText = 'Cached recommendation recorded in the protocol archive.';
            reasonContainer.classList.remove('hidden');
            
            // Hide save button since it's already saved
            saveBtn.classList.add('hidden');
            saveBtn.classList.remove('flex');
            cancelBtn.innerText = 'Close';
            
            return;
        }

        // Reset visibility for new prediction
        saveBtn.classList.remove('hidden');
        saveBtn.classList.add('flex');
        cancelBtn.innerText = 'Cancel';
        
        executeAiPrediction(title);
    }

    function retryAiPrediction() {
        if (!currentPredictTitle) return;
        const loader = document.getElementById('ai-predict-loader');
        const errorContainer = document.getElementById('ai-predict-error');
        loader.classList.remove('hidden');
        errorContainer.classList.add('hidden');
        executeAiPrediction(currentPredictTitle);
    }

    function executeAiPrediction(title) {
        const loader = document.getElementById('ai-predict-loader');
        const resultContainer = document.getElementById('ai-predict-result');
        const errorContainer = document.getElementById('ai-predict-error');
        const reasoningWrapper = document.getElementById('ai-predict-reasoning-wrapper');
        const reasoningText = document.getElementById('ai-predict-reasoning-text');
        const reasonText = document.getElementById('ai-predict-reason-text');
        const modelTag = document.getElementById('ai-model-tag');

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
            if (data && data.success && data.label) {
                resultContainer.classList.remove('hidden');
                
                // Set suggested label
                const label = data.label;
                document.getElementById('ai-predict-suggested-label').innerText = label;
                applyReviewTypeTheme(label);

                // Set model tag if provided
                if (data.model) {
                    modelTag.innerText = data.model;
                }

                // Set reason
                if (data.reason && data.reason.trim() !== '') {
                    reasonText.innerText = data.reason;
                    document.getElementById('ai-predict-reason-container').classList.remove('hidden');
                } else {
                    document.getElementById('ai-predict-reason-container').classList.add('hidden');
                }

                // Set deep reasoning details if available
                if (data.reasoning && data.reasoning.trim() !== '') {
                    reasoningText.innerText = data.reasoning;
                    reasoningWrapper.classList.remove('hidden');
                } else {
                    reasoningWrapper.classList.add('hidden');
                }
            } else {
                errorContainer.classList.remove('hidden');
                if (data && data.message) {
                    document.getElementById('ai-predict-error-msg').innerText = data.message;
                } else {
                    document.getElementById('ai-predict-error-msg').innerText = 'The AI model could not process this title. Please check network connectivity or assign review type manually.';
                }
            }
        })
        .catch(err => {
            console.error('AI Predict Error:', err);
            loader.classList.add('hidden');
            errorContainer.classList.remove('hidden');
            document.getElementById('ai-predict-error-msg').innerText = 'Unable to reach the prediction service. Please verify your connection and try again.';
        });
    }

    function saveAiPrediction() {
        const id = document.getElementById('ai-predict-protocol-id').value;
        const suggestion = document.getElementById('ai-predict-suggested-label').innerText;
        const btn = document.getElementById('ai-predict-save-btn');
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Saving...</span>';
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
            btn.innerHTML = '<span>Apply & Save</span> <i class="fas fa-check text-xs"></i>';
            if (data.success) {
                window.dispatchEvent(new CustomEvent('close-ai-predict-modal'));
                
                // Toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 z-[9999] bg-slate-950 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 animate-[fadeInUp_0.3s_ease-out] font-semibold text-xs sm:text-sm border border-slate-700/80';
                toast.innerHTML = `<i class="fas fa-check-circle text-emerald-400 text-base"></i> <span>AI Prediction Saved: <strong>${suggestion}</strong></span>`;
                document.body.appendChild(toast);
                
                // Update badge in UI table if exists
                const badge = document.getElementById('ai-badge-' + id);
                if (badge) {
                    badge.innerHTML = `<i class="fas fa-robot text-xs"></i> AI: ${suggestion}`;
                    badge.classList.remove('bg-indigo-50', 'text-indigo-600', 'border-indigo-200');
                    badge.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                }

                // If on applications page with active protocols, refresh or emit event
                window.dispatchEvent(new CustomEvent('ai-prediction-saved', { detail: { id, suggestion } }));

                setTimeout(() => toast.remove(), 4000);
            }
        })
        .catch(err => {
            console.error('Error saving AI prediction:', err);
            btn.disabled = false;
            btn.innerHTML = '<span>Apply & Save</span> <i class="fas fa-check text-xs"></i>';
            alert('Failed to save AI prediction. Please try again.');
        });
    }
</script>
