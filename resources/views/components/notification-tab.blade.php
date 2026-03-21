<div id="notifications-panel"
    class="hidden fixed right-6 top-24 w-80 bg-white rounded-xl shadow-2xl ring-1 ring-slate-200 z-[100] transform transition-all duration-300 origin-top-right scale-95 opacity-0"
    style="display: none;">

    <!-- Header -->
    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-[#8B0000] rounded-lg flex items-center justify-center text-white">
                <i class="fas fa-bell text-sm"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">Notifications</h3>
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="ml-auto bg-[#8B0000] text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                    {{ $unreadCount }}
                </span>
            @endif
        </div>

        @if(isset($unreadCount) && $unreadCount > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="text-xs font-semibold text-[#8B0000] hover:text-red-900 transition-colors">
                    Clear
                </button>
            </form>
        @endif
    </div>

    <!-- Notification Items -->
    <ul class="max-h-80 overflow-y-auto divide-y divide-slate-100">
        @if(isset($notifications) && $notifications->count() > 0)
            @foreach($notifications as $notify)
                <li class="relative {{ $notify->is_read ? 'opacity-70' : '' }} hover:bg-slate-50 transition-colors group">
                    <div class="p-4 flex gap-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-sm font-semibold
                                    {{ $notify->type == 'warning' ? 'bg-red-100 text-red-600' : ($notify->type == 'success' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600') }}">
                            @if($notify->type == 'warning')
                                !
                            @elseif($notify->type == 'success')
                                ✓
                            @else
                                i
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('notifications.show', $notify->id) }}" class="block cursor-pointer">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm font-semibold text-slate-800 line-clamp-1">{{ $notify->title }}</p>
                                    @if(!$notify->is_read)
                                        <span class="w-2 h-2 rounded-full bg-[#8B0000] flex-shrink-0 mt-1.5"></span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-2 mt-1">{{ $notify->message }}</p>
                                <p class="text-[11px] text-slate-400 mt-2">{{ $notify->created_at->diffForHumans() }}</p>
                            </a>
                        </div>

                        <!-- Mark as Read Checkbox -->
                        @if(!$notify->is_read)
                            <form action="{{ route('notifications.read', $notify->id) }}" method="POST" class="flex-shrink-0 ml-2">
                                @csrf
                                <button type="submit" class="w-6 h-6 rounded border-2 border-slate-300 flex items-center justify-center text-slate-400 hover:bg-[#8B0000] hover:border-[#8B0000] hover:text-white transition-colors" title="Mark as read">
                                    <i class="fas fa-check text-xs"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        @else
            <li class="p-8 text-center text-slate-400">
                <i class="fas fa-bell-slash text-2xl mb-2 block"></i>
                <p class="text-xs">No notifications</p>
            </li>
        @endif
    </ul>

    <!-- Footer -->
    <div class="px-5 py-3 border-t border-slate-200 text-center">
        <a href="{{ route('notifications.index') }}"
            class="text-xs font-semibold text-[#8B0000] hover:text-red-900 transition-colors">
            View All
        </a>
    </div>
</div>