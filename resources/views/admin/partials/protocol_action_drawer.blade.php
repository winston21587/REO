<!-- Protocol Action Slide-over Drawer (Shared Alpine Component) -->
<div x-data="{
    open: false,
    p: {
        id: '',
        title: '',
        researcher_name: '',
        created_at: '',
        code: '',
        status: '',
        review_type: '',
        ai_suggested_type: '',
        view_url: '#',
        has_deficiency: false,
        deficiency_message: '',
        can_ai_predict: false,
        rec_letter_url: null,
        is_reviewed: false,
        is_or_verified: false,
        rec_form_url: '#',
        already_notified: false,
        can_assign: false,
        has_valid_review_type: false,
        has_reviewers_assigned: false,
        assigned_reviewers: [],
        update_status_url: '#'
    }
}"
@open-protocol-drawer.window="open = true; p = $event.detail;"
@keydown.escape.window="if (open) open = false"
x-show="open"
style="display: none;"
class="relative z-[100]"
aria-labelledby="slide-over-title"
role="dialog"
aria-modal="true">

    <!-- Background backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-in-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in-out duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
         @click="open = false"></div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-4 sm:pl-10"
                 x-show="open"
                 x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">
                
                <div class="pointer-events-auto w-screen max-w-[320px] sm:max-w-sm flex flex-col h-full bg-white shadow-2xl">
                    <!-- Drawer Header -->
                    <div class="px-4 sm:px-6 py-4 sm:py-6 border-b border-slate-100 flex justify-between items-start bg-slate-50 flex-none text-left">
                        <div class="pr-3 w-full">
                            <h3 class="font-heading font-bold text-base text-slate-900 leading-snug line-clamp-3" :title="p.title" x-text="p.title"></h3>
                            
                            <div class="mt-3 space-y-1.5">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <i class="fas fa-user-circle text-slate-400 w-4 text-center"></i>
                                    <span class="font-medium truncate" x-text="p.researcher_name"></span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="far fa-calendar-alt text-slate-400 w-4 text-center"></i>
                                    <span x-text="p.created_at"></span>
                                    <span class="mx-1 text-slate-300">•</span>
                                    <span class="text-slate-600 font-mono text-[11px] font-medium bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200/70" x-text="p.code"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="open = false" aria-label="Close protocol actions drawer" class="text-slate-400 hover:text-[#8B0000] hover:bg-slate-100 transition-colors w-9 h-9 flex-shrink-0 flex items-center justify-center rounded-xl cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000] mt-0.5">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <!-- Drawer Actions List (Grouped by Workflow Phase) -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        
                        <!-- Group 1: Review Progression -->
                        <div class="space-y-1">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-1">
                                Review Progression
                            </span>

                            <!-- View Details -->
                            <a :href="p.view_url"
                                class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-[#8B0000] rounded-xl transition-colors cursor-pointer min-h-[44px]">
                                <i class="fas fa-file-alt w-4 text-slate-400 text-center"></i> 
                                <span>View Details</span>
                            </a>

                            <!-- Receive Hardcopy (when deficient / awaiting hardcopy) -->
                            <template x-if="p.has_deficiency">
                                <button type="button"
                                    @click="open = false; confirmHardcopyReceived(p.id, p.title, p.deficiency_message)"
                                    class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-emerald-800 hover:bg-emerald-50/70 rounded-xl transition-colors text-left cursor-pointer min-h-[44px]">
                                    <i class="fas fa-file-import w-4 text-emerald-600 text-center"></i> 
                                    <span>Receive Hardcopy</span>
                                </button>
                            </template>

                            <!-- Update Status (when not deficient) -->
                            <template x-if="!p.has_deficiency">
                                <button type="button"
                                    @click="open = false; openStatusModal(p.id, p.title, p.status, p.review_type, p.ai_suggested_type)"
                                    class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-[#8B0000] rounded-xl transition-colors text-left cursor-pointer min-h-[44px]">
                                    <i class="fas fa-sync-alt w-4 text-slate-400 text-center"></i> 
                                    <span>Update Status</span>
                                </button>
                            </template>

                            <!-- Predict Review Type (AI) -->
                            <template x-if="p.can_ai_predict">
                                <button type="button"
                                    @click="open = false; openAiPredictModal(p.id, p.title, p.ai_suggested_type)"
                                    class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-[#8B0000] rounded-xl transition-colors text-left cursor-pointer min-h-[44px]">
                                    <i class="fas fa-magic w-4 text-amber-500 text-center"></i> 
                                    <span>Predict Review Type (AI)</span>
                                </button>
                            </template>
                        </div>

                        <!-- Group 2: Committee & Clearance -->
                        <div class="space-y-1 pt-3 border-t border-slate-100">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-1">
                                Committee & Clearance
                            </span>

                            <!-- Assign Reviewers (Only after Hardcopy Received) -->
                            <template x-if="p.can_assign">
                                <div>
                                    <template x-if="p.has_valid_review_type">
                                        <button type="button"
                                            @click="open = false; $dispatch('open-assign-modal', { id: p.id, title: p.title, assigned: p.assigned_reviewers, reviewType: p.review_type })"
                                            class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-[#8B0000] rounded-xl transition-colors text-left cursor-pointer min-h-[44px]">
                                            <i class="fas w-4 text-center" :class="p.has_reviewers_assigned ? 'fa-user-edit text-green-600' : 'fa-users-cog text-slate-400'"></i> 
                                            <span x-text="p.has_reviewers_assigned ? 'Change Reviewer(s)' : 'Assign Reviewer(s)'"></span>
                                        </button>
                                    </template>
                                    <template x-if="!p.has_valid_review_type">
                                        <div title="Review Classification must be set before assigning reviewers." class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-slate-400 bg-slate-50/70 rounded-xl cursor-not-allowed select-none text-left min-h-[44px]">
                                            <i class="fas fa-users-slash w-4 text-center text-slate-300"></i> 
                                            <span>Assign Reviewer(s)</span>
                                            <span class="ml-auto text-[11px] font-bold bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full border border-slate-300 leading-none whitespace-nowrap">
                                                Classification Required
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- View Saved Recommendation Letter -->
                            <template x-if="p.rec_letter_url">
                                <a :href="p.rec_letter_url" target="_blank"
                                    class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-[#8B0000] rounded-xl transition-colors cursor-pointer min-h-[44px]">
                                    <i class="fas fa-file-pdf w-4 text-red-500 text-center"></i> 
                                    <span>View Recommendation Letter</span>
                                </a>
                            </template>

                            <!-- Reviewed Actions: Generate Letter or Notify Researcher -->
                            <template x-if="p.is_reviewed">
                                <div>
                                    <template x-if="p.is_or_verified">
                                        <a :href="p.rec_form_url"
                                            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-[#8B0000] rounded-xl transition-colors cursor-pointer min-h-[44px]">
                                            <i class="fas fa-file-signature w-4 text-emerald-600 text-center"></i> 
                                            <span>Generate Recommendation Letter</span>
                                        </a>
                                    </template>
                                    <template x-if="!p.is_or_verified">
                                        <div>
                                            <div title="Official Receipt must be received and verified before generating a Recommendation Letter."
                                                 class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl select-none text-slate-300 min-h-[44px]">
                                                <i class="fas fa-lock w-4 text-slate-300 text-center"></i>
                                                <span class="text-sm font-medium flex-1 truncate">Generate Recommendation Letter</span>
                                                <span class="ml-auto text-[11px] font-bold bg-amber-50 text-amber-900 px-2 py-0.5 rounded-full border border-amber-200/90 leading-none whitespace-nowrap">
                                                    OR Required
                                                </span>
                                            </div>
                                            <template x-if="p.already_notified">
                                                <button type="button" disabled
                                                    title="A receipt reminder has already been sent. Awaiting researcher response."
                                                    class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-slate-400 bg-slate-50/70 rounded-xl cursor-not-allowed select-none text-left min-h-[44px]">
                                                    <i class="fas fa-clock w-4 text-slate-400 text-center"></i>
                                                    <span>Awaiting Receipt Submission</span>
                                                    <span class="ml-auto text-[11px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full border border-slate-200 leading-none whitespace-nowrap">
                                                        Notified
                                                    </span>
                                                </button>
                                            </template>
                                            <template x-if="!p.already_notified">
                                                <button type="button"
                                                    @click="open = false; notifyReceiptRequired(p.id, p.title)"
                                                    title="Send a notification reminding the researcher to submit their Official Receipt."
                                                    class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-amber-900 bg-amber-50/50 hover:bg-amber-100/70 rounded-xl transition-colors text-left border border-amber-200/50 cursor-pointer min-h-[44px]">
                                                    <i class="fas fa-bell w-4 text-amber-600 text-center"></i>
                                                    <span>Send Receipt Reminder</span>
                                                    <span class="ml-auto text-[11px] font-bold bg-amber-100/80 text-amber-900 px-2 py-0.5 rounded-full border border-amber-200 leading-none">
                                                        OR Required
                                                    </span>
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Group 3: Workflow Safeguards -->
                        <div class="space-y-1 pt-3 border-t border-slate-100">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-1">
                                Workflow Safeguards
                            </span>

                            <!-- Revert Phase Button -->
                            <form :action="p.update_status_url" method="POST" :id="'revertPhaseForm-' + p.id">
                                @csrf
                                <input type="hidden" name="classification" value="Revert Phase">
                                <button type="button"
                                    @click="open = false; confirmRevertPhase(p.id, p.title, p.status)"
                                    class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium text-slate-600 hover:text-rose-700 hover:bg-slate-100 rounded-xl transition-colors text-left border border-transparent cursor-pointer min-h-[44px]">
                                    <i class="fas fa-undo-alt w-4 text-slate-400 hover:text-rose-600 text-center"></i> 
                                    <span>Revert Stage (Step Back)</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
