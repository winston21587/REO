<!-- Revision Action Slide-over Drawer (Shared Component) -->
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
        view_files_url: '#',
        logs: [],
        current_letter_url: null,
        previous_letters: []
    }
}"
@open-revision-drawer.window="open = true; p = $event.detail;"
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
         class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity cursor-pointer" 
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
                    <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex justify-between items-start bg-slate-50 flex-none text-left">
                        <div class="pr-3 w-full">
                            <h3 class="font-heading font-bold text-base text-slate-900 leading-snug line-clamp-3" :title="p.title" x-text="p.title"></h3>
                            
                            <div class="mt-3 space-y-1.5">
                                <div class="flex items-center gap-2 text-sm text-slate-700">
                                    <i class="fas fa-user-circle text-slate-400 w-4 text-center"></i>
                                    <span class="font-semibold truncate" x-text="p.researcher_name"></span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="far fa-calendar-alt text-slate-400 w-4 text-center"></i>
                                    <span x-text="p.created_at"></span>
                                    <span class="mx-1 text-slate-300">•</span>
                                    <span class="text-[#8B0000] font-mono text-[11px] font-bold bg-red-50/60 px-2 py-0.5 rounded border border-red-200/50" x-text="p.code"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="open = false" aria-label="Close drawer" class="text-slate-400 hover:text-[#8B0000] hover:bg-slate-100 transition-colors w-9 h-9 min-w-[36px] min-h-[36px] flex-shrink-0 flex items-center justify-center rounded-xl cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#8B0000] mt-0.5">
                            <i class="fas fa-times text-base"></i>
                        </button>
                    </div>

                    <!-- Drawer Actions List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-2">
                        
                        <div class="space-y-1.5">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-1">
                                Revision Actions
                            </span>

                            <!-- View Files -->
                            <a :href="p.view_files_url"
                                class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] rounded-xl transition-colors cursor-pointer min-h-[44px]">
                                <i class="fas fa-eye w-4 text-slate-400 text-center"></i> 
                                <span>View Files</span>
                            </a>

                            <!-- Update Status -->
                            <button type="button"
                                @click="open = false; openRevisionStatusModal(p.id, p.title, p.status, p.review_type)"
                                class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] rounded-xl transition-colors text-left cursor-pointer min-h-[44px]">
                                <i class="fas fa-sync-alt w-4 text-slate-400 text-center"></i> 
                                <span>Update Status</span>
                            </button>

                            <!-- View Logs -->
                            <button type="button"
                                @click="open = false; openRevisionLogsModal(p.logs)"
                                class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] rounded-xl transition-colors text-left cursor-pointer min-h-[44px]">
                                <i class="fas fa-history w-4 text-slate-400 text-center"></i> 
                                <span>View Logs</span>
                            </button>
                        </div>

                        <!-- Recommendation Letters Group -->
                        <template x-if="p.current_letter_url || (p.previous_letters && p.previous_letters.length > 0)">
                            <div class="space-y-1.5 pt-3 border-t border-slate-100">
                                <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-1">
                                    Official Documents
                                </span>

                                <template x-if="p.current_letter_url">
                                    <a :href="p.current_letter_url" target="_blank"
                                        class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] rounded-xl transition-colors cursor-pointer min-h-[44px]">
                                        <i class="fas fa-file-pdf w-4 text-red-500 text-center"></i> 
                                        <span>View Recommendation Letter</span>
                                    </a>
                                </template>

                                <template x-if="p.previous_letters && p.previous_letters.length > 0">
                                    <div class="ml-4 border-l-2 border-slate-100 pl-2 space-y-1 my-1">
                                        <template x-for="(letter, index) in p.previous_letters" :key="index">
                                            <a :href="letter.url" target="_blank"
                                                class="flex items-center gap-2.5 px-2.5 py-2 text-xs font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-800 rounded-lg transition-colors min-h-[38px]">
                                                <i class="fas fa-file-archive text-slate-400 text-xs"></i>
                                                <span class="truncate">Previous Letter</span>
                                                <span class="text-[11px] text-slate-400 font-mono whitespace-nowrap ml-auto" x-text="letter.date"></span>
                                            </a>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
