<x-reviewer_layout title="Reviewed Protocols">
    <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200/80">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 font-heading tracking-tight leading-tight">
                    Reviewed Protocols
                </h1>
                <p class="text-slate-500 mt-1.5 text-xs sm:text-sm max-w-2xl leading-relaxed">
                    Historical archive of research protocols you have formally evaluated and completed reviews for.
                </p>
            </div>
            <div class="flex items-center gap-2.5 self-start sm:self-auto">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200/90 shadow-2xs text-xs font-semibold text-slate-700">
                    <i class="fas fa-check-circle text-emerald-600" aria-hidden="true"></i>
                    <span>{{ $titles->count() }} {{ Str::plural('Protocol', $titles->count()) }}</span>
                </div>
            </div>
        </div>

        <!-- Protocols Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @forelse($titles as $title)
            <div class="group relative overflow-hidden rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex flex-col p-5 sm:p-6">
                <!-- Status Badge Header -->
                <div class="mb-3.5 flex items-center justify-between gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                        Evaluated
                    </span>
                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider truncate" title="System Status: {{ $title->Status }}">
                        {{ $title->Status ?? 'Completed' }}
                    </span>
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
                        <span class="font-medium truncate">{{ $title->Review_Type ?? 'Unassigned' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-500 text-[11px]">
                        <i class="fas fa-calendar-day text-slate-400 w-4 text-center shrink-0" aria-hidden="true"></i>
                        <span class="truncate">Submitted: {{ $title->created_at->format('M d, Y') }}</span>
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
                        <span>Review Submission</span>
                        <i class="fas fa-arrow-right text-[10px] ml-1 opacity-60 group-hover:translate-x-0.5 transition-transform" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 px-6 text-center bg-white rounded-3xl border border-dashed border-slate-200/90 shadow-2xs">
                <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-4 text-slate-400 shadow-2xs">
                    <i class="fas fa-folder-open text-3xl" aria-hidden="true"></i>
                </div>
                <h3 class="font-bold text-base text-slate-800 tracking-tight">No reviewed protocols found</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                    You have not yet completed and submitted evaluations for any protocols. Formally completed reviews will be archived here.
                </p>
                <div class="mt-6 flex items-center justify-center">
                    <a href="{{ route('reviewer.dashboard') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-primary hover:bg-brand-secondary text-white text-xs font-bold transition-all shadow-xs active:scale-98 min-h-[44px] cursor-pointer">
                        <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                        <span>View Assigned Protocols</span>
                    </a>
                </div>
            </div>
            @endforelse
        </div>

    </div>
</x-reviewer_layout>
