@props(['file', 'showRevisionTag' => false])

@php 
    $ext = strtolower($file->filetype);
    $isPdf = $ext === 'pdf';
    $isOffice = in_array($ext, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']);
    $displayName = $file->category ?? 'General Document';

    if ($isPdf) {
        $iconClass = 'fa-file-pdf text-brand-primary';
        $bgClass = 'bg-red-50';
    } elseif (in_array($ext, ['doc', 'docx'])) {
        $iconClass = 'fa-file-word text-blue-600';
        $bgClass = 'bg-blue-50';
    } else {
        $iconClass = 'fa-file text-slate-400';
        $bgClass = 'bg-slate-50';
    }
@endphp

<div class="group bg-white rounded-2xl border border-slate-200/90 overflow-hidden hover:shadow-lg hover:shadow-slate-200/40 hover:border-slate-300 transition-all duration-200 flex flex-col">
    <!-- Header -->
    <div class="p-4 sm:p-5 flex items-start gap-3.5 border-b border-slate-100 bg-white relative z-10">
        <div class="w-11 h-11 rounded-xl {{ $bgClass }} flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform duration-200">
            <i class="fas {{ $iconClass }} text-lg"></i>
        </div>
        <div class="min-w-0 flex-1">
            <h4 class="font-black text-slate-800 text-sm leading-snug truncate mb-0.5" title="{{ $file->filename }}">
                {{ $displayName }}
            </h4>
            <p class="text-[11px] text-slate-400 font-medium truncate" title="{{ $file->filename }}">{{ $file->filename }}</p>
        </div>
    </div>

    <!-- Document Visual / Preview Area -->
    <div class="relative bg-gradient-to-b from-slate-50 to-slate-100/50 flex-1 min-h-[160px] border-b border-slate-100 group-hover:bg-slate-100/70 transition-colors overflow-hidden flex flex-col items-center justify-center p-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-white shadow-xs border border-slate-200/80 flex items-center justify-center mb-2.5 group-hover:scale-105 transition-transform duration-200">
            <i class="fas {{ $iconClass }} text-2xl"></i>
        </div>
        <p class="text-xs font-bold text-slate-700 max-w-[220px] truncate mb-1">{{ $file->filename }}</p>
        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md {{ $ext === 'pdf' ? 'bg-red-50 text-brand-primary border border-red-100' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
            {{ strtoupper($ext ?: 'DOC') }} File
        </span>

        <div class="absolute inset-0 bg-slate-900/0 sm:group-hover:bg-slate-900/10 transition-colors flex items-center justify-center p-4">
            <a href="{{ asset($file->filepath) }}" target="_blank"
                class="opacity-90 sm:opacity-0 sm:group-hover:opacity-100 sm:translate-y-2 sm:group-hover:translate-y-0 transition-all duration-200 bg-white/95 backdrop-blur-sm text-slate-900 hover:text-brand-primary px-4 py-2 min-h-[38px] rounded-xl font-bold text-xs shadow-md flex items-center gap-2"
                aria-label="View {{ $displayName }} in fullscreen">
                <i class="fas fa-external-link-alt text-[11px]"></i>
                <span>View Fullscreen</span>
            </a>
        </div>
    </div>

    <!-- Actions -->
    <div class="p-3.5 sm:p-4 bg-slate-50/70 border-t border-slate-100 flex items-center {{ $showRevisionTag ? 'justify-between' : 'justify-end' }}">
        @if($showRevisionTag)
            @if($file->revision_number)
                <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-indigo-50 text-indigo-900 border border-indigo-200">
                    Revision {{ $file->revision_number }}
                </span>
            @else
                <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                    Original
                </span>
            @endif
        @endif
        <a href="{{ asset($file->filepath) }}" download
            class="w-11 h-11 min-w-[44px] min-h-[44px] bg-white border border-slate-200 hover:border-brand-primary hover:bg-slate-50 text-slate-600 hover:text-brand-primary rounded-xl flex items-center justify-center transition-all shadow-xs active:scale-95 cursor-pointer"
            aria-label="Download {{ $file->filename }}">
            <i class="fas fa-download text-sm"></i>
        </a>
    </div>
</div>
