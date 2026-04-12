<x-reviewer_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col flex-wrap gap-4 pb-6 border-b border-slate-200">
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">{{ $pageTitle ?? 'Assigned Protocols' }}</h1>
            <p class="text-slate-500 mt-2 text-sm">{{ $pageDescription ?? 'Review the research protocols assigned to you by the administrative oversight committee.' }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($titles as $title)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md hover:border-[#8B0000]/30 transition-all flex flex-col relative group">
                <!-- Status Badge -->
                <div class="mb-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-700 border border-amber-100 shadow-sm">
                        <i class="fas fa-clock mr-1.5"></i> {{ $title->Status ?? 'Under Review' }}
                    </span>
                </div>

                <!-- Title -->
                <h3 class="font-extrabold text-slate-800 text-lg leading-tight mb-2 line-clamp-3 group-hover:text-[#8B0000] transition-colors" title="{{ $title->Study_Protocol_title }}">
                    {{ $title->Study_Protocol_title }}
                </h3>

                <!-- Meta Details -->
                <div class="mt-4 space-y-2 mb-6 text-xs">
                    <div class="flex items-center text-slate-500">
                        <i class="fas fa-tag w-5 text-center text-blue-400"></i>
                        <span class="font-medium truncate">{{ $title->Research_Category ?? 'No Category' }}</span>
                    </div>
                    <div class="flex items-center text-slate-500">
                        <i class="fas fa-file-signature w-5 text-center text-emerald-400"></i>
                        <span class="font-medium truncate">{{ $title->Review_Type ?? 'Unassigned' }}</span>
                    </div>
                    <div class="flex items-center text-slate-500">
                        <i class="fas fa-calendar-alt w-5 text-center text-purple-400"></i>
                        <span class="font-medium truncate">{{ $title->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <!-- Action Button (Pushed to bottom) -->
                <div class="mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('reviewer.view_files', $title->id) }}" class="flex items-center justify-center w-full py-2.5 bg-slate-50 text-slate-600 hover:bg-[#8B0000] hover:text-white rounded-xl text-xs font-bold transition-colors border border-slate-200 hover:border-[#8B0000]">
                        <i class="fas fa-folder-open mr-2"></i> View Protocol Files
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center text-slate-500 bg-white rounded-3xl border border-slate-200 border-dashed">
                <i class="fas fa-folder-open text-5xl text-slate-300 mb-4 block"></i>
                <span class="block font-bold text-lg text-slate-700">No assigned protocols found</span>
                <span class="block text-sm text-slate-400 mt-2">You currently have no tasks assigned by the administrators.</span>
            </div>
            @endforelse
        </div>

    </div>
</x-reviewer_layout>
