<!-- Submission Status Widget -->
<div id="submission-status-widget" class="mb-6" 
     x-data="submissionStatusWidget()" 
     x-init="loadStatus()"
     x-cloak>

    <!-- Status Trigger Block -->
    <div x-show="status" style="display: none;" @click="openModal()"
         class="bg-white rounded-xl border border-slate-200 p-4 cursor-pointer hover:shadow-md transition-all">
        <div class="flex items-center gap-4">
            <!-- Icon -->
            <div class="flex-shrink-0">
                <i class="material-icons text-2xl" :class="status?.can_submit ? 'text-green-600' : 'text-red-600'" 
                   x-text="status?.can_submit ? 'check_circle' : 'block'"></i>
            </div>
            <!-- Text Content -->
            <div class="flex-1">
                <h3 class="font-semibold text-slate-900" x-text="status?.can_submit ? 'Ready to Submit' : 'Cannot Submit Right Now'"></h3>
                <p class="text-xs text-slate-500 mt-1">Click to see details</p>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm" @click="closeModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <!-- Modal Panel -->
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-y-auto" @click.stop>

                    <!-- Header -->
                    <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <i class="material-icons" :class="status?.can_submit ? 'text-green-600' : 'text-red-600'"
                               x-text="status?.can_submit ? 'check_circle' : 'block'"></i>
                            <span x-text="status?.can_submit ? 'Quota Status' : 'Submission Blocked'"></span>
                        </h3>
                        <button @click="closeModal()" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                            <i class="material-icons text-slate-600">close</i>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6 space-y-6">

                        <!-- Status Message -->
                        <div :class="status?.can_submit ? 'bg-green-50 border-l-4 border-green-600' : 'bg-red-50 border-l-4 border-red-600'" class="p-4 rounded">
                            <p class="font-semibold" :class="status?.can_submit ? 'text-green-900' : 'text-red-900'">
                                <span x-show="status?.can_submit">You're all set! You can proceed with your submission.</span>
                                <span x-show="!status?.can_submit && status?.reasons?.[0]" x-text="status.reasons[0]"></span>
                            </p>
                        </div>

                        <!-- Limits Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <!-- Per Hour -->
                            <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <i class="material-icons text-blue-600">schedule</i>
                                    <h4 class="font-semibold text-slate-900">Per Hour</h4>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-baseline gap-2">
                                        <div class="text-3xl font-bold text-slate-900" x-text="status?.status?.hourly?.remaining ?? 0"></div>
                                        <span class="text-xs text-slate-600">of <span x-text="status?.status?.hourly?.limit ?? 3"></span></span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all" 
                                             :style="`width: ${((status?.status?.hourly?.current ?? 0) / (status?.status?.hourly?.limit ?? 3)) * 100}%`"></div>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs pt-2">
                                        <i class="material-icons text-sm" :class="status?.status?.hourly?.remaining > 0 ? 'text-green-600' : 'text-red-600'" x-text="status?.status?.hourly?.remaining > 0 ? 'check' : 'close'"></i>
                                        <span :class="status?.status?.hourly?.remaining > 0 ? 'text-green-700' : 'text-red-700'" x-text="status?.status?.hourly?.remaining > 0 ? 'Available' : 'Blocked'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Per Day -->
                            <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <i class="material-icons text-purple-600">calendar_today</i>
                                    <h4 class="font-semibold text-slate-900">Per Day</h4>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-baseline gap-2">
                                        <div class="text-3xl font-bold text-slate-900" x-text="status?.status?.daily?.remaining ?? 0"></div>
                                        <span class="text-xs text-slate-600">of <span x-text="status?.status?.daily?.limit ?? 10"></span></span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full transition-all"
                                             :style="`width: ${((status?.status?.daily?.current ?? 0) / (status?.status?.daily?.limit ?? 10)) * 100}%`"></div>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs pt-2">
                                        <i class="material-icons text-sm" :class="status?.status?.daily?.remaining > 0 ? 'text-green-600' : 'text-red-600'" x-text="status?.status?.daily?.remaining > 0 ? 'check' : 'close'"></i>
                                        <span :class="status?.status?.daily?.remaining > 0 ? 'text-green-700' : 'text-red-700'" x-text="status?.status?.daily?.remaining > 0 ? 'Available' : 'Blocked'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Per Submission -->
                            <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <i class="material-icons text-green-600">storage</i>
                                    <h4 class="font-semibold text-slate-900">Per Submission</h4>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-600">Files:</span>
                                        <span class="font-semibold text-slate-900" x-text="status?.status?.files?.max_per_submission ?? 20"></span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-600">Total size:</span>
                                        <span class="font-semibold text-slate-900" x-text="(status?.status?.files?.max_size_mb ?? 150) + ' MB'"></span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs pt-2 text-green-700 font-medium">
                                        <i class="material-icons text-sm">check</i>
                                        <span>No restrictions</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Reset Timers -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <h4 class="font-semibold text-blue-900 flex items-center gap-2 mb-3">
                                <i class="material-icons text-sm">info</i>
                                Reset Times
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-800">Hourly resets in:</span>
                                    <span class="font-semibold text-blue-900" x-text="formatTime(resetCountdowns.hourly)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-800">Daily resets in:</span>
                                    <span class="font-semibold text-blue-900" x-text="formatTime(resetCountdowns.daily)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- How Limits Work -->
                        <div class="bg-slate-100 rounded-lg p-4">
                            <h4 class="font-semibold text-slate-900 flex items-center gap-2 mb-3">
                                <i class="material-icons text-sm">help</i>
                                How limits work
                            </h4>
                            <ul class="text-sm text-slate-700 space-y-2">
                                <li class="flex gap-2"><i class="material-icons text-sm flex-shrink-0">schedule</i><span><strong>Hourly:</strong> Maximum 3 submissions per hour</span></li>
                                <li class="flex gap-2"><i class="material-icons text-sm flex-shrink-0">calendar_today</i><span><strong>Daily:</strong> Maximum 10 submissions per day</span></li>
                                <li class="flex gap-2"><i class="material-icons text-sm flex-shrink-0">description</i><span><strong>File Size:</strong> Each file up to 25 MB</span></li>
                                <li class="flex gap-2"><i class="material-icons text-sm flex-shrink-0">package_2</i><span><strong>Total:</strong> Total submission up to 150 MB</span></li>
                            </ul>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="sticky bottom-0 bg-slate-50 border-t border-slate-200 px-6 py-4 flex justify-end gap-3">
                        <button @click="closeModal()" class="px-4 py-2 bg-slate-900 text-white rounded-lg font-medium hover:bg-slate-800 transition-colors">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>


<!-- Alpine.js Component -->
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
            return `${mins}m ${secs}s`;
        },

        startCountdown() {
            // Clear any existing interval
            if (this.countdownInterval) clearInterval(this.countdownInterval);

            // Set initial countdown values
            this.resetCountdowns.hourly = this.status?.status?.hourly?.resets_in_seconds ?? 0;
            this.resetCountdowns.daily = this.status?.status?.daily?.resets_in_seconds ?? 0;
            this.resetCountdowns.cooldown = this.status?.status?.cooldown_resets_in_seconds ?? 0;

            // Tick down every second
            this.countdownInterval = setInterval(() => {
                if (this.resetCountdowns.hourly > 0) this.resetCountdowns.hourly--;
                if (this.resetCountdowns.daily > 0) this.resetCountdowns.daily--;
                if (this.resetCountdowns.cooldown > 0) this.resetCountdowns.cooldown--;

                // Stop ticking when all reach 0
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

                // Auto-refresh every 15 seconds for faster updates
                setTimeout(() => this.loadStatus(), 15000);

            } catch (error) {
                console.error('Failed to load submission status:', error);
                this.errorMessage = 'Could not load submission quota. Please refresh the page.';
                // Retry in 10 seconds on error
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

<!-- Add this CSS for animations (if not already present) -->
<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#submission-status-widget {
    animation: fadeIn 0.3s ease-out;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
