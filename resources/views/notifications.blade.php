<x-user_layout>
    <div class="max-w-4xl mx-auto pt-2 pb-28 sm:py-12 px-4 sm:px-6 animate-[fadeInUp_0.5s_ease-out] relative">

        <!-- Atmospheric Maroon Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-72 bg-gradient-to-b from-red-100/40 via-red-50/20 to-transparent pointer-events-none -z-10 rounded-full blur-3xl" aria-hidden="true"></div>

        <!-- Page Header & Actions -->
        <div class="mb-6 sm:mb-8 border-b border-slate-200/80 pt-2 pb-5 sm:pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-brand-primary transition-colors duration-150 mb-2.5 group py-1 min-h-[36px] rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary">
                        <i class="fas fa-arrow-left text-[11px] group-hover:-translate-x-0.5 transition-transform" aria-hidden="true"></i>
                        <span>Back to Dashboard</span>
                    </a>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading tracking-tight">Notifications</h1>
                        @php $unreadCount = $notifications->where('is_read', false)->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="bg-brand-primary text-white text-xs font-black px-2.5 py-0.5 rounded-full shadow-2xs tracking-wide" aria-label="{{ $unreadCount }} unread notifications">
                                {{ $unreadCount }} Unread
                            </span>
                        @endif
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Official submission updates, reviewer notes, and REO ethics decisions.</p>
                </div>

                @if($unreadCount > 0)
                    <form action="{{ route('notifications.markAllRead') }}" method="POST" class="shrink-0 w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2.5 min-h-[44px] bg-white border border-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold hover:bg-slate-50 hover:border-slate-300 hover:text-brand-primary transition-all duration-150 ease-out shadow-xs active:scale-[0.98] inline-flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary"
                            aria-label="Mark all notifications as read">
                            <i class="fas fa-check-double text-xs text-brand-primary" aria-hidden="true"></i>
                            <span>Mark all as read</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Notifications Feed Card -->
        <main class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200/90 overflow-hidden" role="feed" aria-label="Notifications Feed">
            <div class="divide-y divide-slate-100">
                @forelse($notifications as $notify)
                    <article class="p-4 sm:p-5 hover:bg-slate-50/90 transition-all duration-150 ease-out group relative {{ $notify->is_read ? 'opacity-65 bg-white' : 'bg-brand-primary/[0.02]' }} flex items-start gap-3.5 sm:gap-4 active:scale-[0.995]"
                        aria-labelledby="notification-title-{{ $notify->id }}">
                        
                        <!-- Categorical Protocol Icon (WCAG AA Contrast Compliant) -->
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0 shadow-xs mt-0.5
                            {{ $notify->type == 'warning' ? 'bg-amber-50/90 text-amber-700 ring-1 ring-amber-200/80' : ($notify->type == 'success' ? 'bg-emerald-50/90 text-emerald-700 ring-1 ring-emerald-200/80' : 'bg-blue-50/90 text-blue-700 ring-1 ring-blue-200/80') }}"
                            aria-hidden="true">
                            @if($notify->type == 'warning')
                                <i class="fas fa-triangle-exclamation text-base sm:text-lg"></i>
                            @elseif($notify->type == 'success')
                                <i class="fas fa-check-circle text-base sm:text-lg"></i>
                            @else
                                <i class="fas fa-circle-info text-base sm:text-lg"></i>
                            @endif
                        </div>

                        <!-- Content Column (Clickable to open related item) -->
                        <a href="{{ route('notifications.show', $notify->id) }}" class="flex-1 min-w-0 no-underline focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary rounded-lg -m-1 p-1">
                            <!-- Responsive Header: Title + Timestamp Stack on Mobile, Flex on Desktop -->
                            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-1 sm:gap-4 mb-1.5">
                                <div class="flex items-center gap-2 min-w-0">
                                    @if(!$notify->is_read)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-brand-primary/10 text-brand-primary border border-brand-primary/20 shrink-0" aria-label="Unread notification">New</span>
                                    @endif
                                    <h2 id="notification-title-{{ $notify->id }}" class="text-sm sm:text-base font-black text-slate-900 group-hover:text-brand-primary transition-colors duration-150 leading-snug">
                                        {{ $notify->title }}
                                    </h2>
                                </div>
                                <div class="flex items-center gap-1.5 text-[11px] sm:text-xs text-slate-500 font-medium shrink-0">
                                    <i class="far fa-clock text-[10px] sm:text-[11px] text-slate-400" aria-hidden="true"></i>
                                    <span>{{ $notify->created_at->diffForHumans() }}</span>
                                    <span class="hidden sm:inline text-slate-300" aria-hidden="true">•</span>
                                    <span class="hidden sm:inline text-slate-400">{{ $notify->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>

                            <!-- Message Body -->
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed break-words line-clamp-3 sm:line-clamp-none font-normal">
                                {{ $notify->message }}
                            </p>
                        </a>

                        <!-- Individual Mark as Read Button (Adaptive Touch Affordance) -->
                        @if(!$notify->is_read)
                            <form action="{{ route('notifications.read', $notify->id) }}" method="POST" class="shrink-0 self-center">
                                @csrf
                                <button type="submit"
                                    class="w-9 h-9 min-w-[36px] min-h-[36px] rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-400 hover:bg-brand-primary hover:border-brand-primary hover:text-white transition-all duration-150 ease-out active:scale-90 cursor-pointer shadow-xs focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary"
                                    title="Mark as read"
                                    aria-label="Mark notification '{{ $notify->title }}' as read">
                                    <i class="fas fa-check text-xs" aria-hidden="true"></i>
                                </button>
                            </form>
                        @endif

                        <!-- Right Chevron Affordance (Desktop only) -->
                        <div class="hidden sm:flex self-center shrink-0 text-slate-300 group-hover:text-brand-primary group-hover:translate-x-0.5 transition-all duration-150" aria-hidden="true">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </div>
                    </article>
                @empty
                    <!-- Empty State -->
                    <div class="py-12 sm:py-16 px-6 text-center" role="status" aria-live="polite">
                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-3xl flex items-center justify-center mx-auto mb-3.5 border border-slate-100 shadow-inner" aria-hidden="true">
                            <i class="fas fa-bell-slash text-2xl"></i>
                        </div>
                        <h2 class="text-lg font-black text-slate-800 mb-1 font-heading">All caught up</h2>
                        <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">There are no pending protocol updates or action alerts requiring your attention.</p>
                    </div>
                @endforelse
            </div>
        </main>

    </div>
</x-user_layout>
