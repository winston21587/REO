<div id="notifications-panel"
    role="dialog"
    aria-modal="false"
    aria-labelledby="notifications-panel-heading"
    class="hidden fixed right-3 sm:right-6 top-18 sm:top-24 w-[calc(100vw-24px)] max-w-sm sm:w-88 bg-white rounded-2xl shadow-2xl ring-1 ring-slate-200/90 z-[100] transform transition-all duration-200 ease-out origin-top-right scale-95 opacity-0 overflow-hidden"
    style="display: none;">

    <!-- Header -->
    <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-b border-slate-100 flex items-center justify-between bg-white">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-brand-primary text-white rounded-xl flex items-center justify-center shadow-xs shrink-0" aria-hidden="true">
                <i class="fas fa-bell text-xs"></i>
            </div>
            <h3 id="notifications-panel-heading" class="font-black text-slate-900 text-sm font-heading tracking-tight">Notifications</h3>
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="bg-brand-primary text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-2xs" aria-label="{{ $unreadCount }} new notifications">
                    {{ $unreadCount }} New
                </span>
            @endif
        </div>

        <div class="flex items-center gap-1.5 ml-auto">
            @if(isset($unreadCount) && $unreadCount > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="min-h-[36px] px-2.5 py-1 text-xs font-black text-brand-primary hover:text-brand-secondary transition-colors cursor-pointer rounded-lg hover:bg-red-50/50 active:scale-95 inline-flex items-center focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary"
                        aria-label="Clear all notifications">
                        Clear all
                    </button>
                </form>
            @endif

            <!-- Mobile Close Button (Touch-Friendly Dismissal) -->
            <button type="button" id="close-notifications-panel"
                class="sm:hidden w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 active:scale-95 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary"
                aria-label="Close notifications panel">
                <i class="fas fa-times text-xs" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <!-- Notification Items List -->
    <ul class="max-h-[min(380px,60vh)] overflow-y-auto divide-y divide-slate-100 [scrollbar-width:thin] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-slate-200/80">
        @if(isset($notifications) && $notifications->count() > 0)
            @foreach($notifications as $notify)
                <li class="relative {{ $notify->is_read ? 'opacity-65 bg-white' : 'bg-brand-primary/[0.02]' }} hover:bg-slate-50/90 transition-colors duration-150 ease-out group">
                    <div class="p-3.5 sm:p-4 flex items-start gap-3">
                        <!-- Categorical Protocol Icon (WCAG AA Compliant) -->
                        <div class="shrink-0 w-8 h-8 rounded-xl flex items-center justify-center text-xs font-semibold mt-0.5 shadow-2xs
                            {{ $notify->type == 'warning' ? 'bg-amber-50/90 text-amber-700 ring-1 ring-amber-200/80' : ($notify->type == 'success' ? 'bg-emerald-50/90 text-emerald-700 ring-1 ring-emerald-200/80' : 'bg-blue-50/90 text-blue-700 ring-1 ring-blue-200/80') }}"
                            aria-hidden="true">
                            @if($notify->type == 'warning')
                                <i class="fas fa-triangle-exclamation"></i>
                            @elseif($notify->type == 'success')
                                <i class="fas fa-check"></i>
                            @else
                                <i class="fas fa-circle-info"></i>
                            @endif
                        </div>

                        <!-- Content Link -->
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('notifications.show', $notify->id) }}"
                                class="block cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary rounded-lg -m-1 p-1">
                                <div class="flex items-start justify-between gap-1.5">
                                    <p class="text-xs sm:text-sm font-black text-slate-900 line-clamp-1 group-hover:text-brand-primary transition-colors">{{ $notify->title }}</p>
                                    @if(!$notify->is_read)
                                        <span class="w-2 h-2 rounded-full bg-brand-primary shrink-0 mt-1" title="Unread" aria-label="Unread"></span>
                                    @endif
                                </div>
                                <p class="text-[11px] sm:text-xs text-slate-600 line-clamp-2 mt-0.5 leading-snug font-normal">{{ $notify->message }}</p>
                                <p class="text-[10px] text-slate-500 mt-1.5 flex items-center gap-1 font-medium">
                                    <i class="far fa-clock text-[9px] text-slate-400" aria-hidden="true"></i>
                                    <span>{{ $notify->created_at->diffForHumans() }}</span>
                                </p>
                            </a>
                        </div>

                        <!-- Mark as Read Button (Comfortable 36x36px Touch Target) -->
                        @if(!$notify->is_read)
                            <form action="{{ route('notifications.read', $notify->id) }}" method="POST" class="shrink-0 self-center">
                                @csrf
                                <button type="submit"
                                    class="w-9 h-9 min-w-[36px] min-h-[36px] rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-400 hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all duration-150 ease-out active:scale-90 cursor-pointer shadow-2xs focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary"
                                    title="Mark as read"
                                    aria-label="Mark notification '{{ $notify->title }}' as read">
                                    <i class="fas fa-check text-[11px]" aria-hidden="true"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        @else
            <li class="py-10 px-6 text-center text-slate-400" role="status">
                <div class="w-12 h-12 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center mx-auto mb-2.5 border border-slate-100 shadow-inner" aria-hidden="true">
                    <i class="fas fa-bell-slash text-lg"></i>
                </div>
                <p class="text-xs font-black text-slate-700 font-heading">No notifications</p>
                <p class="text-[11px] text-slate-400 mt-0.5">You're all caught up!</p>
            </li>
        @endif
    </ul>

    <!-- Footer -->
    <div class="p-3 border-t border-slate-100 bg-slate-50/70">
        <a href="{{ route('notifications.index') }}"
            class="w-full py-2.5 px-4 rounded-xl bg-white border border-slate-200/90 text-slate-800 hover:bg-brand-primary hover:border-brand-primary hover:text-white text-xs font-black tracking-tight transition-all duration-150 ease-out inline-flex items-center justify-center gap-2 shadow-2xs active:scale-[0.98] group/btn focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary">
            <span>View All Activity</span>
            <i class="fas fa-arrow-right text-[10px] text-slate-400 group-hover/btn:text-white group-hover/btn:translate-x-0.5 transition-all" aria-hidden="true"></i>
        </a>
    </div>
</div>