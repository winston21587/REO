{{-- Floating Toast Notification System (Bottom-Right / Mobile Centered Pill) --}}
@php
    $hasErrors = isset($errors) && $errors->any();
    $hasErrorSession = session()->has('error');
    $hasSuccessSession = session()->has('success') || session()->has('status');
@endphp

@if($hasErrors || $hasErrorSession || $hasSuccessSession)
    <div id="toast-container" class="fixed z-[9999] bottom-4 sm:bottom-6 inset-x-4 sm:inset-x-auto sm:right-6 max-w-md w-full sm:w-auto pointer-events-none flex flex-col gap-3"
         aria-live="assertive">

        {{-- Validation Errors Toast --}}
        @if($hasErrors)
            <div x-data="{ show: true, timer: null }"
                 x-show="show"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 x-init="timer = setTimeout(() => show = false, 7000)"
                 @mouseenter="clearTimeout(timer)"
                 @mouseleave="timer = setTimeout(() => show = false, 2500)"
                 class="pointer-events-auto bg-white/98 backdrop-blur-xl border border-rose-200/90 border-l-4 border-l-rose-600 rounded-2xl shadow-2xl shadow-rose-950/15 p-4 flex items-start gap-3 w-full sm:min-w-[340px] sm:max-w-md"
                 role="alert">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shrink-0 border border-rose-100 mt-0.5" aria-hidden="true">
                    <i class="fas fa-circle-exclamation"></i>
                </div>
                <div class="flex-1 min-w-0 pr-1">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 tracking-tight">
                        {{ $errors->count() > 1 ? 'Please address ' . $errors->count() . ' items:' : 'Validation Error' }}
                    </h3>
                    @if($errors->count() > 1)
                        <ul class="mt-1 space-y-1 text-xs text-rose-700 font-medium list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li class="leading-snug">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-0.5 text-xs text-rose-700 font-medium leading-relaxed">
                            {{ $errors->first() }}
                        </p>
                    @endif
                </div>
                <button type="button"
                        @click="show = false"
                        class="text-slate-400 hover:text-slate-700 p-1.5 -mr-1 -mt-1 rounded-lg hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-rose-500/20 shrink-0"
                        aria-label="Dismiss error notification">
                    <i class="fas fa-times text-xs" aria-hidden="true"></i>
                </button>
            </div>
        @endif

        {{-- Single Error Session Toast --}}
        @if($hasErrorSession)
            <div x-data="{ show: true, timer: null }"
                 x-show="show"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 x-init="timer = setTimeout(() => show = false, 6000)"
                 @mouseenter="clearTimeout(timer)"
                 @mouseleave="timer = setTimeout(() => show = false, 2500)"
                 class="pointer-events-auto bg-white/98 backdrop-blur-xl border border-rose-200/90 border-l-4 border-l-rose-600 rounded-2xl shadow-2xl shadow-rose-950/15 p-4 flex items-start gap-3 w-full sm:min-w-[320px] sm:max-w-md"
                 role="alert">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shrink-0 border border-rose-100 mt-0.5" aria-hidden="true">
                    <i class="fas fa-circle-exclamation"></i>
                </div>
                <div class="flex-1 min-w-0 pr-1">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 tracking-tight">Attention Needed</h3>
                    <p class="mt-0.5 text-xs text-rose-700 font-medium leading-relaxed">
                        {{ session('error') }}
                    </p>
                </div>
                <button type="button"
                        @click="show = false"
                        class="text-slate-400 hover:text-slate-700 p-1.5 -mr-1 -mt-1 rounded-lg hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-rose-500/20 shrink-0"
                        aria-label="Dismiss error notification">
                    <i class="fas fa-times text-xs" aria-hidden="true"></i>
                </button>
            </div>
        @endif

        {{-- Success / Status Session Toast --}}
        @if($hasSuccessSession)
            <div x-data="{ show: true, timer: null }"
                 x-show="show"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 x-init="timer = setTimeout(() => show = false, 5000)"
                 @mouseenter="clearTimeout(timer)"
                 @mouseleave="timer = setTimeout(() => show = false, 2000)"
                 class="pointer-events-auto bg-white/98 backdrop-blur-xl border border-emerald-200/90 border-l-4 border-l-emerald-600 rounded-2xl shadow-2xl shadow-emerald-950/15 p-4 flex items-start gap-3 w-full sm:min-w-[320px] sm:max-w-md"
                 role="alert">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0 border border-emerald-100 mt-0.5" aria-hidden="true">
                    <i class="fas fa-circle-check"></i>
                </div>
                <div class="flex-1 min-w-0 pr-1">
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 tracking-tight">Success</h3>
                    <p class="mt-0.5 text-xs text-emerald-800 font-medium leading-relaxed">
                        {{ session('success') ?? session('status') }}
                    </p>
                </div>
                <button type="button"
                        @click="show = false"
                        class="text-slate-400 hover:text-slate-700 p-1.5 -mr-1 -mt-1 rounded-lg hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500/20 shrink-0"
                        aria-label="Dismiss notification">
                    <i class="fas fa-times text-xs" aria-hidden="true"></i>
                </button>
            </div>
        @endif

    </div>
@endif
