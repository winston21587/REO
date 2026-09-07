<x-reviewer_layout :title="$pageTitle ?? 'Assigned Protocols'">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200/80">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 font-heading tracking-tight leading-tight">
                    {{ $pageTitle ?? 'Assigned Protocols' }}
                </h1>
                <p class="text-slate-500 mt-1.5 text-xs sm:text-sm max-w-2xl leading-relaxed">
                    {{ $pageDescription ?? 'Review the research protocols assigned to you by the administrative oversight committee.' }}
                </p>
            </div>
            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200/90 shadow-2xs text-xs font-semibold text-slate-700">
                    <i class="fas fa-clipboard-list text-brand-primary" aria-hidden="true"></i>
                    <span>{{ $titles->count() }} {{ Str::plural('Protocol', $titles->count()) }}</span>
                </div>
                <!-- Desktop Notification Trigger -->
                <button aria-label="Open notifications center"
                        class="notification-trigger hidden lg:flex w-9 h-9 rounded-xl bg-white border border-slate-200/90 items-center justify-center text-slate-600 hover:text-brand-primary hover:border-brand-primary/40 hover:shadow-xs transition-all relative focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-brand-primary group cursor-pointer shadow-2xs">
                    <i class="fas fa-bell text-sm group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-brand-primary rounded-full animate-pulse ring-2 ring-white hidden"></span>
                </button>
            </div>
        </div>

        <!-- Protocols Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @forelse($titles as $title)
            @php
                $statusClass = match($title->Status) {
                    'Approved' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
                    'Disapproved' => 'bg-rose-50 text-rose-800 border-rose-200/80',
                    'Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions' => 'bg-amber-50 text-amber-800 border-amber-200/80',
                    default => 'bg-red-50 text-brand-primary border-red-200/70',
                };
                $dotClass = match($title->Status) {
                    'Approved' => 'bg-emerald-500',
                    'Disapproved' => 'bg-rose-500',
                    'Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions' => 'bg-amber-500',
                    default => 'bg-brand-primary',
                };
            @endphp
            <div class="group relative overflow-hidden rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex flex-col p-5 sm:p-6">
                <!-- Status Badge Header -->
                <div class="mb-3.5 flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                        <span class="truncate">{{ $title->Status ?? 'Under Review' }}</span>
                    </span>
                    
                    @if(!empty($title->reoc_code))
                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest font-mono">
                        {{ $title->reoc_code }}
                    </span>
                    @endif
                </div>

                <!-- Protocol Title -->
                <h3 class="font-bold text-slate-900 text-sm sm:text-base leading-snug mb-3 line-clamp-3 group-hover:text-brand-primary transition-colors tracking-tight" 
                    title="{{ $title->Study_Protocol_title }}">
                    {{ $title->Study_Protocol_title }}
                </h3>

                <!-- Meta Details -->
                <div class="space-y-2 mb-5 text-xs">
                    <div class="flex items-center gap-2 text-slate-600">
                        <i class="fas fa-tag text-slate-400 w-4 text-center shrink-0" aria-hidden="true"></i>
                        <span class="font-medium truncate">{{ $title->Research_Category ?? 'No Category' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-600">
                        <i class="fas fa-file-signature text-slate-400 w-4 text-center shrink-0" aria-hidden="true"></i>
                        <span class="font-medium truncate">{{ $title->Review_Type ?? 'Pending Type' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-500 text-[11px]">
                        <i class="fas fa-calendar-day text-slate-400 w-4 text-center shrink-0" aria-hidden="true"></i>
                        <span class="truncate">Assigned: {{ $title->created_at->format('M d, Y') }}</span>
                    </div>

                    @if(auth()->user()->reviewer?->show_researcher_identity && $title->researcher?->user)
                    <div class="flex items-center gap-2 text-slate-600 pt-1 border-t border-slate-100">
                        <i class="fas fa-user-graduate text-slate-400 w-4 text-center shrink-0" aria-hidden="true"></i>
                        <span class="font-medium truncate" title="{{ $title->researcher->user->first_name }} {{ $title->researcher->user->last_name }} ({{ $title->researcher->user->email }})">
                            {{ $title->researcher->user->first_name }} {{ $title->researcher->user->last_name }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Action Button (Min 44px Touch Target) -->
                <div class="mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('reviewer.view_files', $title->id) }}" 
                       class="w-full min-h-[44px] py-2.5 px-4 bg-slate-50 hover:bg-slate-100/90 text-slate-700 hover:text-slate-900 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 border border-slate-200 hover:border-slate-300 flex items-center justify-center gap-2 shadow-2xs active:scale-[0.99] cursor-pointer">
                        <i class="fas fa-folder-open text-sm text-slate-500" aria-hidden="true"></i>
                        <span>View Protocol Files</span>
                        <i class="fas fa-arrow-right text-[10px] ml-1 opacity-60 group-hover:translate-x-0.5 transition-transform" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 px-6 text-center bg-white rounded-3xl border border-dashed border-slate-200/90 shadow-2xs">
                <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-4 text-slate-400 shadow-2xs">
                    <i class="fas fa-folder-open text-3xl" aria-hidden="true"></i>
                </div>
                <h3 class="font-bold text-base text-slate-800 tracking-tight">
                    {{ request()->routeIs('reviewer.reevaluation') ? 'No protocols awaiting re-evaluation' : 'No assigned protocols found' }}
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                    {{ request()->routeIs('reviewer.reevaluation') 
                        ? 'There are currently no revised submissions awaiting secondary review from researchers.' 
                        : 'You currently have no active protocol evaluations assigned by the ethics committee. Newly assigned protocols will automatically appear here.' }}
                </p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('reviewer.reviewed_titles') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-bold transition-all shadow-2xs active:scale-98 min-h-[44px] cursor-pointer">
                        <i class="fas fa-check-circle text-emerald-600" aria-hidden="true"></i>
                        <span>View Reviewed Protocols</span>
                    </a>
                    <a href="{{ url()->current() }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-800 border border-slate-200 text-xs font-bold transition-all shadow-2xs active:scale-98 min-h-[44px] cursor-pointer">
                        <i class="fas fa-rotate text-slate-400" aria-hidden="true"></i>
                        <span>Refresh List</span>
                    </a>
                </div>
            </div>
            @endforelse
        </div>

    </div>
</x-reviewer_layout>
