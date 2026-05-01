<style>
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 0%, #e0e0e0 50%, #f0f0f0 100%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    .skeleton-text {
        @apply skeleton rounded h-4 mb-3;
    }

    .skeleton-card {
        @apply bg-white p-6 rounded-2xl shadow-sm border border-slate-100;
    }
</style>

<div id="skeleton-loader" style="display: none;" class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <div class="skeleton h-10 w-64 rounded mb-3"></div>
        <div class="skeleton h-4 w-96 rounded"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @for ($i = 0; $i < 3; $i++)
            <div class="skeleton-card">
                <div class="flex justify-between items-start mb-4">
                    <div class="skeleton h-6 w-24 rounded"></div>
                    <div class="skeleton h-6 w-6 rounded"></div>
                </div>
                <div class="skeleton-text"></div>
                <div class="skeleton-text"></div>
                <div class="skeleton h-10 w-32 rounded"></div>
            </div>
        @endfor
    </div>

    <div class="skeleton-card">
        <div class="skeleton h-8 w-48 rounded mb-6"></div>
        @for ($i = 0; $i < 4; $i++)
            <div class="mb-6 pb-6 border-b border-slate-100 last:border-b-0">
                <div class="flex justify-between items-center mb-3">
                    <div class="skeleton h-5 w-64 rounded"></div>
                    <div class="skeleton h-5 w-24 rounded"></div>
                </div>
                <div class="skeleton-text w-1/2"></div>
            </div>
        @endfor
    </div>
</div>

<script>
    let skeletonShown = false;
    let pageLoaded = false;
    
    // Only show skeleton if page hasn't loaded after 300ms (prevents flash on fast loads)
    const skeletonTimer = setTimeout(function() {
        if (!pageLoaded) {
            const skeleton = document.getElementById('skeleton-loader');
            if (skeleton) {
                skeleton.style.display = 'block';
                skeletonShown = true;
            }
        }
    }, 300); // 300ms delay before showing skeleton

    window.addEventListener('load', function() {
        pageLoaded = true;
        clearTimeout(skeletonTimer);
        
        const skeleton = document.getElementById('skeleton-loader');
        const content = document.getElementById('page-content');
        
        if (skeleton && skeletonShown) {
            
            skeleton.style.transition = 'opacity 0.3s ease-out';
            skeleton.style.opacity = '0';
            setTimeout(() => {
                skeleton.style.display = 'none';
            }, 300);
        } else if (skeleton) {
            skeleton.style.display = 'none';
        }
        
        if (content) {
            content.style.display = 'block';
        }
    });

    setTimeout(function() {
        const skeleton = document.getElementById('skeleton-loader');
        if (skeleton) {
            skeleton.style.display = 'none';
        }
    }, 5000);
</script>
