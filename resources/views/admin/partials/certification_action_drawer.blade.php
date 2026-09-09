<!-- Certification Action Slide-Over Drawer -->
<div x-data="{ open: false, data: {} }" 
     @open-cert-drawer.window="data = $event.detail; open = true;"
     @keydown.escape.window="open = false"
     x-cloak
     class="relative z-[110]" 
     aria-labelledby="cert-action-drawer-title" 
     role="dialog" 
     aria-modal="true">

    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-in-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in-out duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" 
         @click="open = false"
         style="display: none;"></div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="open" 
                     x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400" 
                     x-transition:enter-start="translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="translate-x-full" 
                     class="pointer-events-auto w-screen max-w-sm flex flex-col h-full bg-white shadow-2xl"
                     style="display: none;">

                    <!-- Drawer Header -->
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-start bg-slate-50/80 flex-none text-left">
                        <div class="pr-3 w-full min-w-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold tracking-wide uppercase bg-slate-200 text-slate-700 font-mono mb-2" x-text="data.code || ('#' + String(data.id || '').padStart(5, '0'))"></span>
                            <h3 id="cert-action-drawer-title" class="font-heading font-extrabold text-base text-slate-900 leading-snug line-clamp-2" :title="data.title" x-text="data.title"></h3>

                            <div class="mt-3 space-y-1">
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <i class="fas fa-user-circle text-slate-400 w-4 text-center shrink-0"></i>
                                    <span class="font-medium truncate" x-text="data.researcher_name || 'Not Provided'"></span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="far fa-calendar-check text-emerald-600 w-4 text-center shrink-0"></i>
                                    <span class="tabular-nums" x-text="'Approved: ' + (data.approval_date || 'Not Provided')"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="open = false" class="w-9 h-9 min-w-[36px] min-h-[36px] flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer shrink-0" aria-label="Close action drawer">
                            <i class="fas fa-times text-base"></i>
                        </button>
                    </div>

                    <!-- Drawer Actions List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-2">
                        <!-- View Protocol Files -->
                        <a :href="data.view_files_url"
                           class="w-full min-h-[44px] flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] rounded-xl transition-all cursor-pointer group">
                            <i class="fas fa-eye w-5 text-center text-slate-400 group-hover:text-[#8B0000] transition-colors"></i>
                            <span>View Protocol Files</span>
                        </a>

                        <!-- View Issued Documents (only if certified) -->
                        <template x-if="data.has_certificates">
                            <button type="button"
                                    @click="open = false; openViewCertificatesModal(data.approval_letter_url, data.certificate_url, data.code, data.title, data.researcher_name)"
                                    class="w-full min-h-[44px] flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-emerald-700 rounded-xl transition-all text-left cursor-pointer group">
                                <i class="fas fa-certificate w-5 text-center text-emerald-600 group-hover:text-emerald-700 transition-colors"></i>
                                <span>View Issued Documents</span>
                            </button>
                        </template>

                        <div class="h-px bg-slate-100 my-1"></div>

                        <!-- Document Generation -->
                        <a :href="data.generate_page_url"
                           class="w-full min-h-[44px] flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] rounded-xl transition-all cursor-pointer group">
                            <i class="fas fa-stamp w-5 text-center text-slate-400 group-hover:text-[#8B0000] transition-colors"></i>
                            <span>Document Generation</span>
                        </a>

                        <div class="h-px bg-slate-100 my-1"></div>

                        <!-- Revert Phase (Undo) -->
                        <button type="button"
                                @click="open = false; confirmRevertPhase(data.id, data.title, data.status)"
                                class="w-full min-h-[44px] flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-rose-700 rounded-xl transition-all text-left cursor-pointer group">
                            <i class="fas fa-undo-alt w-5 text-center text-slate-400 group-hover:text-rose-600 transition-colors"></i>
                            <span>Step Backward (Undo)</span>
                        </button>
                    </div>

                    <!-- Drawer Footer -->
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        <button type="button" 
                                @click="open = false" 
                                class="w-full h-11 min-h-[44px] px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 uppercase tracking-wider bg-white hover:bg-slate-50 hover:border-slate-300 transition-colors cursor-pointer">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
