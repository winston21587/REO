<x-user_layout>
    <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 animate-[fadeInUp_0.5s_ease-out]">

        <div class="text-center mb-16">
            @if(isset($contents['resources_header_image']))
                <img src="{{ asset($contents['resources_header_image']) }}"
                    class="w-full h-48 object-cover rounded-2xl mb-8 shadow-sm">
            @endif
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading">
                {{ $contents['resources_title'] ?? 'Resource Library' }}
            </h1>
            <p class="text-slate-500 mt-4 text-base md:text-lg max-w-2xl mx-auto">
                {{ $contents['resources_intro'] ?? 'Download the official application and assessment forms required for your ethics review submission.' }}
            </p>
        </div>

        <div class="flex items-center gap-4 mb-8 md:mb-12">
            <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-slate-200 flex-1"></div>
            <span
                class="text-[10px] md:text-xs font-bold text-[#8B0000] uppercase tracking-widest bg-red-50 px-4 py-1.5 rounded-full border border-red-100 shadow-sm">Mandatory
                Forms</span>
            <div class="h-px bg-gradient-to-r from-slate-200 via-slate-200 to-transparent flex-1"></div>
        </div>

        <!-- MOBILE VIEW (Compact List) -->
        <div class="md:hidden space-y-4">
            @forelse($downloadables as $resource)
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4 active:scale-[0.98] transition-transform">
                <div class="w-10 h-10 rounded-lg {{ $resource->is_mandatory ? 'bg-red-50 border-red-100 text-[#8B0000]' : 'bg-slate-50 border-slate-100 text-slate-600' }} border flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">{{ $resource->is_mandatory ? 'description' : 'assignment' }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        @if($resource->code)
                        <span class="text-[10px] font-bold {{ $resource->is_mandatory ? 'bg-[#8B0000]' : 'bg-slate-600' }} text-white px-1.5 py-0.5 rounded">{{ $resource->code }}</span>
                        @endif
                        <h3 class="font-bold text-slate-900 text-sm truncate">{{ $resource->title }}</h3>
                    </div>
                    <p class="text-xs text-slate-500 truncate">{{ $resource->description }}</p>
                </div>
                <!-- File extension indicator added to mobile view -->
                <div class="hidden sm:flex items-center">
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100">{{ $resource->file_extension }}</span>
                </div>
                <a href="{{ asset($resource->file_path) }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 shrink-0 flex items-center justify-center bg-slate-100 text-slate-600 rounded-full hover:bg-[#8B0000] hover:text-white transition-colors title='Download'">
                    <span class="material-symbols-outlined text-lg">download</span>
                </a>
            </div>
            @empty
            <div class="text-center p-8 bg-white rounded-xl shadow-sm border border-slate-200 text-slate-500">
                <span class="material-symbols-outlined text-3xl mb-2 text-slate-300">inventory_2</span>
                <p>No forms available yet.</p>
            </div>
            @endforelse
        </div>

        <!-- DESKTOP VIEW (Original Grid) -->
        <div class="hidden md:block">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($downloadables as $resource)
                <div class="group bg-white p-8 rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-[#8B0000]/30 transition-all duration-300 relative overflow-hidden flex flex-col h-full">
                    @if($resource->code)
                    <div class="absolute top-0 right-0 {{ $resource->is_mandatory ? 'bg-[#8B0000]' : 'bg-slate-700' }} text-white text-[10px] font-bold px-4 py-1.5 rounded-bl-xl shadow-md z-10">{{ $resource->code }}</div>
                    @endif
                    <div class="absolute -top-10 -right-10 w-32 h-32 {{ $resource->is_mandatory ? 'bg-red-50' : 'bg-slate-50' }} rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <div class="flex items-start gap-6 relative z-10 flex-1">
                        <div class="w-16 h-16 shrink-0 rounded-2xl bg-gradient-to-br {{ $resource->is_mandatory ? 'from-red-50 to-white border-red-100 text-[#8B0000]' : 'from-slate-50 to-white border-slate-100 text-slate-700' }} border flex items-center justify-center shadow-sm group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                            <span class="material-symbols-outlined text-3xl">{{ $resource->is_mandatory ? 'description' : 'assignment' }}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-[#8B0000] transition-colors line-clamp-2" title="{{ $resource->title }}">{{ $resource->title }}</h3>
                            <p class="text-sm text-slate-500 mt-2 leading-relaxed line-clamp-3" title="{{ $resource->description }}">{{ $resource->description }}</p>
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wide">{{ $resource->file_extension }}</span>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $resource->file_size }}</span>
                        </div>
                        <!-- Style tweaks depending on mandatory status for visual hierarchy -->
                        <a href="{{ asset($resource->file_path) }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-sm font-bold {{ $resource->is_mandatory ? 'text-white bg-[#8B0000] hover:bg-red-800' : 'text-slate-700 bg-slate-100 hover:bg-[#8B0000] hover:text-white' }} px-4 py-2 rounded-lg hover:shadow-lg {{ $resource->is_mandatory ? 'hover:shadow-red-900/20' : '' }} transition-all transform hover:-translate-y-0.5">
                            <span>Download</span>
                            <span class="material-symbols-outlined text-lg">download</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center p-12 bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center justify-center">
                     <span class="material-symbols-outlined text-5xl mb-4 text-slate-300">inventory_2</span>
                     <h3 class="text-lg font-bold text-slate-800">Resource Library Empty</h3>
                     <p class="text-slate-500 mt-2 max-w-sm">There are currently no downloadable resources available for researchers at this time. Please check back later.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-user_layout>