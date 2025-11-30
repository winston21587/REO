<x-user_layout>
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="flex flex-col md:flex-row justify-between items-start mb-8 border-b border-slate-200 pb-6">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-sm mb-1">
                    <a href="{{ route('home') }}" class="hover:text-brand-primary transition-colors"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 font-heading">Notifications</h2>
                <p class="text-slate-500 text-sm">View all your activity and updates.</p>
            </div>
            
            @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 hover:text-[#8B0000] transition-colors shadow-sm">
                    <i class="fas fa-check-double mr-2"></i> Mark all as read
                </button>
            </form>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <ul class="divide-y divide-slate-100">
                @forelse($notifications as $notify)
                    <li class="p-6 hover:bg-slate-50 transition-colors group relative {{ $notify->is_read ? 'opacity-75' : '' }}">
                        <div class="absolute left-0 top-0 bottom-0 w-1 
                            {{ $notify->type == 'warning' ? 'bg-red-500' : ($notify->type == 'success' ? 'bg-green-500' : 'bg-blue-500') }}
                            opacity-100 group-hover:opacity-80 transition-opacity">
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center shadow-sm
                                {{ $notify->type == 'warning' ? 'bg-red-50 text-red-600' : ($notify->type == 'success' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600') }}">
                                @if($notify->type == 'warning')
                                    <i class="fas fa-exclamation-triangle text-xl"></i>
                                @elseif($notify->type == 'success')
                                    <i class="fas fa-check-circle text-xl"></i>
                                @else
                                    <i class="fas fa-info-circle text-xl"></i>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="text-base font-bold text-slate-800">{{ $notify->title }}</h4>
                                    <span class="text-xs text-slate-400 font-medium whitespace-nowrap ml-4">
                                        {{ $notify->created_at->format('M d, Y h:i A') }}
                                        ({{ $notify->created_at->diffForHumans() }})
                                    </span>
                                </div>

                                @if(Str::contains($notify->message, '- '))
                                    <div class="mt-2 bg-slate-50 rounded-xl p-4 border border-slate-100">
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Remarks / Details:</p>
                                        <ul class="space-y-1">
                                            @foreach(explode("\n", $notify->message) as $line)
                                                @if(Str::startsWith(trim($line), '-'))
                                                    <li class="flex items-start gap-2 text-sm text-slate-600">
                                                        <i class="fas fa-circle text-[4px] mt-2 text-slate-400"></i>
                                                        <span>{{ trim(str_replace('-', '', $line)) }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $notify->message }}</p>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="p-12 text-center text-slate-400">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bell-slash text-2xl text-slate-300"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 mb-1">No notifications yet</h3>
                        <p class="text-sm">We'll notify you when there are updates to your research.</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </main>
</x-user_layout>
