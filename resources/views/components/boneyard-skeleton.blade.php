@props([
    'role' => 'guest',
    'type' => 'landing',
    'contentId' => 'page-content',
    'delay' => 450
])

@php
    $uniqueId = 'boneyard-' . $role . '-' . Str::random(6);
    $isOverlay = in_array($role, ['admin', 'reviewer', 'super_admin']);
@endphp

<div id="{{ $uniqueId }}"
     data-boneyard="{{ $type }}"
     data-boneyard-content="#{{ $contentId }}"
     data-boneyard-delay="{{ $delay }}"
     data-boneyard-attached="true"
     class="boneyard-container w-full {{ $isOverlay ? 'absolute inset-0 z-20 pointer-events-none bg-[#faf8f8] overflow-y-auto p-4 sm:p-5 lg:p-6 pt-16 md:pt-5 lg:pt-6' : 'relative pointer-events-none' }}"
     style="display: none; opacity: 0; transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1);"
     aria-hidden="true">
     
    {{-- Responsive Pre-rendered Bone Shells Matching Exact Role Layouts --}}
    @if($type === 'landing')
        <div class="w-full h-screen relative bg-slate-900 overflow-hidden flex flex-col justify-center px-6 sm:px-12 md:px-20">
            <div class="max-w-3xl space-y-4">
                <div class="h-7 w-36 rounded-full bg-slate-800 boneyard-bone mb-3"></div>
                <div class="h-12 sm:h-16 w-3/4 rounded-xl bg-slate-800 boneyard-bone"></div>
                <div class="h-10 sm:h-12 w-1/2 rounded-xl bg-slate-800 boneyard-bone"></div>
                <div class="h-5 w-4/5 rounded-md bg-slate-800/80 boneyard-bone mt-2"></div>
                <div class="h-5 w-3/5 rounded-md bg-slate-800/80 boneyard-bone"></div>
                <div class="flex items-center gap-4 pt-4">
                    <div class="h-12 w-36 rounded-xl bg-slate-800 boneyard-bone"></div>
                    <div class="h-12 w-36 rounded-xl bg-slate-800/60 boneyard-bone"></div>
                </div>
            </div>
            
            <div class="absolute bottom-10 left-6 right-6 sm:left-12 sm:right-12 md:left-20 md:right-20 grid grid-cols-1 sm:grid-cols-3 gap-4 hidden md:grid">
                <div class="h-28 rounded-2xl bg-slate-800/60 boneyard-bone p-4"></div>
                <div class="h-28 rounded-2xl bg-slate-800/60 boneyard-bone p-4"></div>
                <div class="h-28 rounded-2xl bg-slate-800/60 boneyard-bone p-4"></div>
            </div>
        </div>
    @elseif($type === 'researcher')
        <div class="max-w-5xl mx-auto py-6 sm:py-8 space-y-6">
            {{-- Welcome Header --}}
            <div class="flex items-center justify-between pb-6 border-b border-slate-200">
                <div class="space-y-2">
                    <div class="h-8 w-64 rounded-lg bg-slate-200 boneyard-bone"></div>
                    <div class="h-4 w-96 rounded-md bg-slate-200 boneyard-bone"></div>
                </div>
                <div class="h-11 w-40 rounded-xl bg-slate-200 boneyard-bone hidden sm:block"></div>
            </div>

            {{-- 3 Metric Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="h-28 rounded-2xl bg-white p-5 border border-slate-200 shadow-2xs space-y-3">
                    <div class="h-4 w-20 bg-slate-200 rounded boneyard-bone"></div>
                    <div class="h-8 w-12 bg-slate-200 rounded-lg boneyard-bone"></div>
                </div>
                <div class="h-28 rounded-2xl bg-white p-5 border border-slate-200 shadow-2xs space-y-3">
                    <div class="h-4 w-24 bg-slate-200 rounded boneyard-bone"></div>
                    <div class="h-8 w-12 bg-slate-200 rounded-lg boneyard-bone"></div>
                </div>
                <div class="h-28 rounded-2xl bg-white p-5 border border-slate-200 shadow-2xs space-y-3">
                    <div class="h-4 w-24 bg-slate-200 rounded boneyard-bone"></div>
                    <div class="h-8 w-12 bg-slate-200 rounded-lg boneyard-bone"></div>
                </div>
            </div>

            {{-- Submissions List Cards --}}
            <div class="space-y-4 pt-2">
                <div class="h-6 w-36 rounded bg-slate-200 boneyard-bone mb-4"></div>
                @for($i = 0; $i < 3; $i++)
                    <div class="rounded-2xl bg-white border border-slate-200/90 p-5 sm:p-6 shadow-2xs space-y-4">
                        <div class="flex justify-between items-center">
                            <div class="h-5 w-24 rounded-full bg-slate-200 boneyard-bone"></div>
                            <div class="h-4 w-28 rounded bg-slate-200 boneyard-bone"></div>
                        </div>
                        <div class="h-6 w-3/4 rounded-lg bg-slate-200 boneyard-bone"></div>
                        <div class="h-4 w-1/2 rounded bg-slate-200 boneyard-bone"></div>
                        <div class="flex justify-between items-center pt-2">
                            <div class="h-4 w-32 rounded bg-slate-200 boneyard-bone"></div>
                            <div class="h-9 w-24 rounded-xl bg-slate-200 boneyard-bone"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    @elseif($type === 'admin')
        <div class="w-full space-y-6">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-200">
                <div class="space-y-2">
                    <div class="h-8 w-60 rounded-lg bg-slate-200 boneyard-bone"></div>
                    <div class="h-4 w-80 rounded-md bg-slate-200 boneyard-bone"></div>
                </div>
                <div class="h-10 w-32 rounded-xl bg-slate-200 boneyard-bone"></div>
            </div>

            {{-- 5-Card Metric Ribbon --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                @for($i = 0; $i < 5; $i++)
                    <div class="h-24 rounded-xl bg-white border border-slate-200 p-4 shadow-2xs space-y-2">
                        <div class="h-3 w-16 bg-slate-200 rounded boneyard-bone"></div>
                        <div class="h-7 w-12 bg-slate-200 rounded-lg boneyard-bone"></div>
                    </div>
                @endfor
            </div>

            {{-- Table Filter Bar --}}
            <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-200">
                <div class="h-10 w-64 rounded-lg bg-slate-200 boneyard-bone"></div>
                <div class="flex items-center gap-2">
                    <div class="h-10 w-32 rounded-lg bg-slate-200 boneyard-bone"></div>
                    <div class="h-10 w-32 rounded-lg bg-slate-200 boneyard-bone"></div>
                </div>
            </div>

            {{-- Ledger Table Shell --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
                <div class="h-12 bg-slate-50 border-b border-slate-200 px-6 flex items-center justify-between">
                    <div class="h-4 w-1/6 bg-slate-200 rounded boneyard-bone"></div>
                    <div class="h-4 w-1/4 bg-slate-200 rounded boneyard-bone"></div>
                    <div class="h-4 w-1/6 bg-slate-200 rounded boneyard-bone"></div>
                    <div class="h-4 w-1/6 bg-slate-200 rounded boneyard-bone"></div>
                    <div class="h-4 w-1/12 bg-slate-200 rounded boneyard-bone"></div>
                </div>
                <div class="divide-y divide-slate-100 px-6">
                    @for($i = 0; $i < 6; $i++)
                        <div class="py-4 flex items-center justify-between">
                            <div class="h-4 w-1/6 bg-slate-200 rounded boneyard-bone"></div>
                            <div class="h-5 w-1/3 bg-slate-200 rounded boneyard-bone"></div>
                            <div class="h-6 w-20 rounded-full bg-slate-200 boneyard-bone"></div>
                            <div class="h-4 w-24 bg-slate-200 rounded boneyard-bone"></div>
                            <div class="h-8 w-20 rounded-lg bg-slate-200 boneyard-bone"></div>
                        </div>
                    @endfor
                </div>
                <div class="h-14 bg-slate-50/70 border-t border-slate-200 px-6 flex items-center justify-between">
                    <div class="h-4 w-36 bg-slate-200 rounded boneyard-bone"></div>
                    <div class="h-8 w-44 rounded-lg bg-slate-200 boneyard-bone"></div>
                </div>
            </div>
        </div>
    @elseif($type === 'reviewer')
        <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200">
                <div class="space-y-2">
                    <div class="h-7 w-48 rounded-lg bg-slate-200 boneyard-bone"></div>
                    <div class="h-4 w-96 rounded-md bg-slate-200 boneyard-bone"></div>
                </div>
                <div class="h-8 w-28 rounded-full bg-slate-200 boneyard-bone"></div>
            </div>

            {{-- Protocols Card Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @for($i = 0; $i < 8; $i++)
                    <div class="rounded-2xl bg-white border border-slate-200/90 p-5 sm:p-6 shadow-2xs flex flex-col justify-between space-y-4 h-60">
                        <div class="flex justify-between items-center">
                            <div class="h-5 w-20 rounded-full bg-slate-200 boneyard-bone"></div>
                            <div class="h-3 w-14 rounded bg-slate-200 boneyard-bone"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="h-5 w-full rounded bg-slate-200 boneyard-bone"></div>
                            <div class="h-5 w-3/4 rounded bg-slate-200 boneyard-bone"></div>
                            <div class="h-3 w-1/2 rounded bg-slate-200 boneyard-bone pt-1"></div>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                            <div class="h-4 w-20 rounded bg-slate-200 boneyard-bone"></div>
                            <div class="h-8 w-20 rounded-xl bg-slate-200 boneyard-bone"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    @endif
</div>

<script>
(function() {
    const skeletonId = "{{ $uniqueId }}";
    const contentSelector = "#{{ $contentId }}";
    const delay = {{ (int)$delay }};
    const minHoldTime = 400; // Minimum duration to display skeleton if it ever appears
    
    let isSlow = false;
    if (typeof navigator !== 'undefined' && 'connection' in navigator) {
        const conn = navigator.connection;
        if (conn) {
            // Only flag as slow for genuinely constrained connections
            if (conn.saveData || conn.effectiveType === 'slow-2g' || conn.effectiveType === '2g' || (conn.rtt && conn.rtt > 450)) {
                isSlow = true;
            }
        }
    }

    const skeletonEl = document.getElementById(skeletonId);
    const contentEl = document.querySelector(contentSelector);
    let isShown = false;
    let isFinished = false;
    let shownTime = 0;
    let timer = null;

    function revealSkeleton() {
        if (isFinished || isShown || !skeletonEl) return;
        isShown = true;
        shownTime = Date.now();
        skeletonEl.style.display = 'block';
        requestAnimationFrame(function() {
            skeletonEl.style.opacity = '1';
        });
    }

    function dismissSkeleton() {
        if (isFinished) return;
        isFinished = true;
        if (timer) {
            clearTimeout(timer);
            timer = null;
        }

        if (isShown && skeletonEl) {
            // If the skeleton was shown, ensure it displays for at least minHoldTime
            // so it never causes an abrupt or jarring flicker
            const elapsed = Date.now() - shownTime;
            const remaining = Math.max(0, minHoldTime - elapsed);

            setTimeout(function() {
                skeletonEl.style.transition = 'opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1)';
                skeletonEl.style.opacity = '0';
                setTimeout(function() {
                    skeletonEl.style.display = 'none';
                    if (contentEl) {
                        contentEl.style.removeProperty('display');
                        contentEl.style.removeProperty('opacity');
                    }
                }, 350);
            }, remaining);
        } else if (skeletonEl) {
            // Never shown on fast connections: cleanly keep hidden without touching content
            skeletonEl.style.display = 'none';
        }
    }

    // Fast connection handling:
    // If DOM is already interactive or DOMContentLoaded fires quickly, cancel timer and NEVER show skeleton!
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        dismissSkeleton();
    } else {
        document.addEventListener('DOMContentLoaded', dismissSkeleton, { once: true });
        window.addEventListener('load', dismissSkeleton, { once: true });

        // If explicitly slow network, reveal immediately
        if (isSlow) {
            revealSkeleton();
        } else {
            // Regular fast connection: wait 450ms. If page resolves in <450ms, timer is cancelled!
            timer = setTimeout(function() {
                if (!isFinished) {
                    revealSkeleton();
                }
            }, delay);
        }
    }

    // Safety fallback timeout
    setTimeout(dismissSkeleton, 6000);
})();
</script>
