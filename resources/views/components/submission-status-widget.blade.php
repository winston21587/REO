@props(['showTrigger' => true])

<!-- Submission Status Widget -->
<div id="submission-status-widget" class="{{ $showTrigger ? 'mb-6' : '' }}" 
     x-data="submissionStatusWidget()" 
     x-init="loadStatus()"
     x-on:open-quota-modal.window="openModal()"
     x-cloak>

    @if($showTrigger)
    <!-- Status Trigger Block (Standalone) -->
    <div x-show="status" style="display: none;" @click="openModal()"
         role="button"
         tabindex="0"
         @keydown.enter="openModal()"
         @keydown.space.prevent="openModal()"
         aria-haspopup="dialog"
         aria-label="View submission quota and rate limit details"
         class="group relative overflow-hidden rounded-2xl p-4 border transition-all duration-200 cursor-pointer active:scale-[0.99] focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2"
         :class="status?.can_submit 
             ? 'bg-gradient-to-br from-emerald-50/50 via-white to-white border-emerald-200/90 hover:border-emerald-300 hover:shadow-md hover:shadow-emerald-950/5' 
             : 'bg-gradient-to-br from-red-50/70 via-white to-white border-red-200 hover:border-red-300 hover:shadow-md hover:shadow-red-950/5'">

        <div class="flex items-center gap-3.5">
            <!-- Icon -->
            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 transition-transform duration-200 group-hover:scale-105"
                 :class="status?.can_submit 
                     ? 'bg-emerald-50 border border-emerald-200 text-emerald-600 shadow-xs' 
                     : 'bg-red-50 border border-red-200 text-brand-primary shadow-xs'">
                <i class="fas text-lg" :class="status?.can_submit ? 'fa-circle-check' : 'fa-clock-rotate-left'" aria-hidden="true"></i>
            </div>

            <!-- Text Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-bold text-sm text-slate-900 tracking-tight leading-tight" 
                        x-text="status?.can_submit ? 'Ready to Submit' : 'Submission Quota Reached'"></h3>
                    
                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full shrink-0"
                          :class="status?.can_submit 
                              ? 'bg-emerald-100/70 text-emerald-800 border border-emerald-200/60' 
                              : 'bg-red-100 text-red-800 border border-red-200'">
                        <span class="w-1.5 h-1.5 rounded-full" :class="status?.can_submit ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'"></span>
                        <span x-text="(status?.status?.daily?.remaining ?? 10) + ' Left'"></span>
                    </span>
                </div>

                <p class="text-xs mt-1 flex items-center justify-between text-slate-500 group-hover:text-slate-700 transition-colors">
                    <span class="truncate" x-text="status?.can_submit ? 'Click to view quota & rate limits' : 'Temporarily paused • View details'"></span>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition-all ml-1.5 shrink-0" aria-hidden="true"></i>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Status Modal -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="closeModal()"
         style="display: none;" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         role="dialog" 
         aria-modal="true"
         aria-labelledby="quota-modal-title">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
            <!-- Modal Panel -->
            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[85vh] overflow-hidden flex flex-col text-left border border-slate-200 animate-[scaleIn_0.2s_ease-out]" 
                 @click.stop>

                <!-- Header -->
                <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-red-950/80 px-6 py-5 border-b border-slate-800 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-900/40 text-red-300 border border-red-700/40 flex items-center justify-center shrink-0 shadow-inner">
                            <i class="fas fa-shield-halved text-base" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 id="quota-modal-title" class="text-base font-extrabold text-white font-heading tracking-tight flex items-center gap-2">
                                <span>Submission Quota & Rate Limits</span>
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">WMSU Research Ethics Office Security Policy</p>
                        </div>
                    </div>
                    <button type="button" 
                            @click="closeModal()" 
                            aria-label="Close quota details dialog"
                            class="w-8 h-8 rounded-full bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition-colors active:scale-95 focus:outline-hidden focus-visible:ring-2 focus-visible:ring-white">
                        <i class="fas fa-times text-sm" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Scrollable Body -->
                <div class="p-6 space-y-5 overflow-y-auto custom-scrollbar flex-1">

                    <!-- Status Message Alert -->
                    <div :class="status?.can_submit ? 'bg-emerald-50/80 border border-emerald-200/90' : 'bg-red-50/80 border border-red-200/90'" 
                         class="p-4 rounded-2xl shadow-2xs flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                             :class="status?.can_submit ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-brand-primary'">
                            <i class="fas text-sm" :class="status?.can_submit ? 'fa-check' : 'fa-exclamation'" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-sm" :class="status?.can_submit ? 'text-emerald-950' : 'text-red-950'"
                                x-text="status?.can_submit ? 'Submission Allowance Active' : 'Submission Limit Reached'"></h4>
                            <p class="text-xs mt-0.5 leading-relaxed" :class="status?.can_submit ? 'text-emerald-800' : 'text-red-800'">
                                <span x-show="status?.can_submit">You have active submission quota and may proceed with uploading and queuing your research protocol.</span>
                                <span x-show="!status?.can_submit && status?.reasons?.[0]" x-text="status?.reasons?.[0] ?? ''"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Limits Grid (Hourly, Daily, File Storage) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        
                        <!-- Per Hour Card -->
                        <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2.5">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-clock text-slate-500 text-xs" aria-hidden="true"></i>
                                        <span class="text-xs font-bold text-slate-700">Hourly</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Limit</span>
                                </div>
                                <div class="flex items-baseline gap-1.5 mb-2">
                                    <span class="text-2xl font-black text-slate-900 font-heading" x-text="status?.status?.hourly?.remaining ?? 3"></span>
                                    <span class="text-xs text-slate-500 font-medium">of <span x-text="status?.status?.hourly?.limit ?? 3"></span> left</span>
                                </div>
                                <!-- Progress Track -->
                                <div class="w-full bg-slate-200/80 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-emerald-600 h-full rounded-full transition-all duration-300" 
                                         :style="`width: ${((status?.status?.hourly?.remaining ?? 3) / (status?.status?.hourly?.limit ?? 3)) * 100}%`"></div>
                                </div>
                            </div>
                            <div class="pt-3 mt-3 border-t border-slate-200/60 text-[11px] text-slate-500 flex items-center justify-between">
                                <span>Window reset:</span>
                                <span class="font-bold text-slate-700" x-text="formatTime(resetCountdowns.hourly)"></span>
                            </div>
                        </div>

                        <!-- Per Day Card -->
                        <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2.5">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-day text-slate-500 text-xs" aria-hidden="true"></i>
                                        <span class="text-xs font-bold text-slate-700">Daily</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Limit</span>
                                </div>
                                <div class="flex items-baseline gap-1.5 mb-2">
                                    <span class="text-2xl font-black text-slate-900 font-heading" x-text="status?.status?.daily?.remaining ?? 10"></span>
                                    <span class="text-xs text-slate-500 font-medium">of <span x-text="status?.status?.daily?.limit ?? 10"></span> left</span>
                                </div>
                                <!-- Progress Track -->
                                <div class="w-full bg-slate-200/80 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-emerald-600 h-full rounded-full transition-all duration-300"
                                         :style="`width: ${((status?.status?.daily?.remaining ?? 10) / (status?.status?.daily?.limit ?? 10)) * 100}%`"></div>
                                </div>
                            </div>
                            <div class="pt-3 mt-3 border-t border-slate-200/60 text-[11px] text-slate-500 flex items-center justify-between">
                                <span>Daily reset:</span>
                                <span class="font-bold text-slate-700" x-text="formatTime(resetCountdowns.daily)"></span>
                            </div>
                        </div>

                        <!-- Per Submission (Files & Size) -->
                        <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200/80 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2.5">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-hard-drive text-slate-500 text-xs" aria-hidden="true"></i>
                                        <span class="text-xs font-bold text-slate-700">Storage</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Max</span>
                                </div>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-500">Max files:</span>
                                        <span class="font-bold text-slate-800" x-text="(status?.status?.files?.max_per_submission ?? 20) + ' files'"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-500">Package limit:</span>
                                        <span class="font-bold text-slate-800" x-text="(status?.status?.files?.max_size_mb ?? 150) + ' MB'"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-3 mt-3 border-t border-slate-200/60 text-[11px] text-emerald-700 font-bold flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-xs" aria-hidden="true"></i>
                                <span>25 MB max per file</span>
                            </div>
                        </div>

                    </div>

                    <!-- Policy Protection Note -->
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/70 text-xs text-slate-600 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-slate-800">
                            <i class="fas fa-info-circle text-brand-primary" aria-hidden="true"></i>
                            <span>Why does REO apply submission quotas?</span>
                        </div>
                        <p class="leading-relaxed text-slate-500">
                            Rate limits prevent accidental duplicate submissions, ensure high availability for all university researchers during defense cycles, and protect secure protocol archives.
                        </p>
                    </div>

                </div>

                <!-- Footer -->
                <div class="bg-slate-50/80 border-t border-slate-200/80 px-6 py-4 flex justify-end shrink-0">
                    <button type="button" 
                            @click="closeModal()" 
                            class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors shadow-xs active:scale-95 focus:outline-hidden focus-visible:ring-2 focus-visible:ring-slate-900 cursor-pointer">
                        Close Details
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- Alpine.js Component Script -->
<script>
function submissionStatusWidget() {
    return {
        status: null,
        loading: true,
        errorMessage: null,
        showModal: false,
        resetCountdowns: {
            hourly: 0,
            daily: 0,
            cooldown: 0,
        },
        countdownInterval: null,

        openModal() {
            this.showModal = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.showModal = false;
            document.body.style.overflow = '';
        },

        formatTime(seconds) {
            if (seconds <= 0) return 'Ready now';
            if (seconds < 60) return `${seconds}s`;
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            if (mins >= 60) {
                const hrs = Math.floor(mins / 60);
                const remainMins = mins % 60;
                return `${hrs}h ${remainMins}m`;
            }
            return `${mins}m ${secs}s`;
        },

        startCountdown() {
            if (this.countdownInterval) clearInterval(this.countdownInterval);

            this.resetCountdowns.hourly = this.status?.status?.hourly?.resets_in_seconds ?? 0;
            this.resetCountdowns.daily = this.status?.status?.daily?.resets_in_seconds ?? 0;
            this.resetCountdowns.cooldown = this.status?.status?.cooldown_resets_in_seconds ?? 0;

            this.countdownInterval = setInterval(() => {
                if (this.resetCountdowns.hourly > 0) this.resetCountdowns.hourly--;
                if (this.resetCountdowns.daily > 0) this.resetCountdowns.daily--;
                if (this.resetCountdowns.cooldown > 0) this.resetCountdowns.cooldown--;

                if (this.resetCountdowns.hourly === 0 && 
                    this.resetCountdowns.daily === 0 && 
                    this.resetCountdowns.cooldown === 0) {
                    clearInterval(this.countdownInterval);
                }
            }, 1000);
        },

        async loadStatus() {
            this.loading = true;
            this.errorMessage = null;

            try {
                const response = await fetch('{{ route("api.submission_status") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                this.status = await response.json();
                this.startCountdown();

                // Dispatch event so other components on page stay in sync
                window.dispatchEvent(new CustomEvent('quota-status-synced', { detail: this.status }));

                // Auto-refresh every 15 seconds
                setTimeout(() => this.loadStatus(), 15000);

            } catch (error) {
                console.error('Failed to load submission status:', error);
                this.errorMessage = 'Could not load submission quota.';
                setTimeout(() => this.loadStatus(), 10000);
            } finally {
                this.loading = false;
            }
        },

        destroy() {
            if (this.countdownInterval) clearInterval(this.countdownInterval);
        }
    };
}
</script>
