@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 py-6">
        <div class="flex justify-between flex-1 sm:hidden w-full">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-400 bg-slate-50 border border-slate-200 cursor-default rounded-lg leading-5">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 leading-5 rounded-lg hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#8B0000]/10 transition ease-in-out duration-150">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-700 bg-white border border-slate-200 leading-5 rounded-lg hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#8B0000]/10 transition ease-in-out duration-150">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-slate-400 bg-slate-50 border border-slate-200 cursor-default rounded-lg leading-5">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>

        <div class="hidden sm:flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div class="text-sm text-slate-600 font-medium">
                @if ($paginator->firstItem())
                    {{ __('Showing') }} 
                    <span class="font-bold text-slate-900">{{ $paginator->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-bold text-slate-900">{{ $paginator->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-bold text-slate-900">{{ $paginator->total() }}</span>
                    {{ __('results') }}
                @else
                    {{ __('No results') }}
                @endif
            </div>

            <div class="flex items-center gap-1 sm:gap-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-slate-400 rounded-lg bg-slate-50 border border-slate-200 cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-slate-700 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#8B0000]/10 transition ease-in-out duration-150 hover:-translate-y-0.5">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-2 py-2 text-slate-400">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-bold text-white rounded-lg bg-[#8B0000] border border-[#8B0000] shadow-sm shadow-red-900/20">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-slate-700 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#8B0000]/10 transition ease-in-out duration-150 hover:-translate-y-0.5" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-slate-700 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#8B0000]/10 transition ease-in-out duration-150 hover:-translate-y-0.5">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span aria-disabled="true" class="relative inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-slate-400 rounded-lg bg-slate-50 border border-slate-200 cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
