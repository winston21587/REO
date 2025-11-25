<div id="notifications-panel" 
    class="hidden fixed right-8 top-20 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 z-[100] transform transition-all duration-200 origin-top-right scale-95 opacity-0"
    style="display: none;">
    
    <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-white rounded-t-2xl relative z-10">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/reoc-nobg.png') }}" class="w-6 h-6">
            <h3 class="font-bold text-slate-800">Notifications</h3>
            
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="bg-[#8B0000] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                    {{ $unreadCount }} New
                </span>
            @endif
        </div>

        @if(isset($unreadCount) && $unreadCount > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-bold text-[#8B0000] hover:text-red-900 transition-colors">
                    Mark all read
                </button>
            </form>
        @endif
    </div>

    <ul class="max-h-[24rem] overflow-y-auto divide-y divide-slate-50">
        @if(isset($notifications) && $notifications->count() > 0)
            @foreach($notifications as $notify)
                <li class="p-4 hover:bg-slate-50 transition-colors cursor-pointer group relative {{ $notify->is_read ? 'opacity-75' : '' }}">
                    <div class="absolute left-0 top-0 bottom-0 w-1 
                        {{ $notify->type == 'warning' ? 'bg-red-500' : ($notify->type == 'success' ? 'bg-green-500' : 'bg-blue-500') }}
                        opacity-100 group-hover:opacity-80 transition-opacity">
                    </div>

                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center shadow-sm
                            {{ $notify->type == 'warning' ? 'bg-red-50 text-red-600' : ($notify->type == 'success' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600') }}">
                            @if($notify->type == 'warning')
                                <i class="fas fa-exclamation-triangle text-lg"></i>
                            @elseif($notify->type == 'success')
                                <i class="fas fa-check-circle text-lg"></i>
                            @else
                                <i class="fas fa-info-circle text-lg"></i>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-bold text-slate-800 mb-0.5">{{ $notify->title }}</p>
                                @if(!$notify->is_read)
                                    <span class="w-2 h-2 rounded-full bg-[#8B0000]"></span>
                                @endif
                            </div>

                            @if(Str::contains($notify->message, '- '))
                                <p class="text-xs text-slate-500 mb-1">Remarks:</p>
                                <ul class="list-disc list-inside text-[10px] text-red-600 font-medium bg-red-50 p-2 rounded-lg">
                                    @foreach(explode("\n", $notify->message) as $line)
                                        @if(Str::startsWith(trim($line), '-'))
                                            <li>{{ trim(str_replace('-', '', $line)) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-xs text-slate-500 line-clamp-2">{{ $notify->message }}</p>
                            @endif

                            <p class="text-[10px] text-slate-400 mt-1 font-medium">{{ $notify->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        @else
            <li class="p-8 text-center text-slate-400">
                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                <p class="text-xs">No notifications yet</p>
            </li>
        @endif
    </ul>

    <div class="p-3 border-t border-slate-100 bg-slate-50 rounded-b-2xl text-center">
        <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#8B0000] transition-colors uppercase tracking-wider">
            View All Activity
        </a>
    </div>
</div>