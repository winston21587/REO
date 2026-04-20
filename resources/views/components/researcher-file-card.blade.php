@props(['file', 'researchTitle'])

@php 
        $ext = strtolower($file->filetype);
    $isPdf = $ext === 'pdf';
    $isOffice = in_array($ext, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']);
    $displayName = $file->category ?? 'General Document';

    if ($isPdf) {
        $iconClass = 'fa-file-pdf text-[#8B0000]';
        $bgClass = 'bg-red-50';
    } elseif (in_array($ext, ['doc', 'docx'])) {
        $iconClass = 'fa-file-word text-blue-600';
        $bgClass = 'bg-blue-50';
    } else {
        $iconClass = 'fa-file text-slate-400';
        $bgClass = 'bg-slate-50';
    }
@endphp

<div
    class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 hover:border-slate-300 transition-all duration-300 flex flex-col">
    <!-- Header -->
    <div class="p-5 flex items-start gap-4 border-b border-slate-50 bg-white relative z-10">
        <div
            class="w-12 h-12 rounded-xl {{ $bgClass }} flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
            <i class="fas {{ $iconClass }} text-xl"></i>
        </div>
        <div class="min-w-0 flex-1">
            <h4 class="font-bold text-slate-800 text-sm leading-snug truncate mb-1" title="{{ $file->filename }}">
                {{ $displayName }}
            </h4>
        </div>
    </div>

    <!-- Preview Area -->
    <div
        class="relative bg-slate-50 flex-1 min-h-[200px] border-b border-slate-100 group-hover:bg-slate-100 transition-colors overflow-hidden">
        @if($isPdf)
            <iframe src="{{ asset($file->filepath) }}#toolbar=0&navpanes=0&scrollbar=0"
                class="w-full h-full border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
            <div
                class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                <a href="{{ asset($file->filepath) }}" target="_blank"
                    class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-[#8B0000] hover:text-white flex items-center gap-2">
                    <i class="fas fa-external-link-alt"></i> View Fullscreen
                </a>
            </div>
        @elseif($isOffice)
            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ urlencode(asset($file->filepath)) }}"
                width="100%" height="100%"
                class="border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
            <div
                class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                <a href="https://view.officeapps.live.com/op/view.aspx?src={{ urlencode(asset($file->filepath)) }}"
                    target="_blank"
                    class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-[#8B0000] hover:text-white flex items-center gap-2">
                    <i class="fas fa-external-link-alt"></i> View Fullscreen
                </a>
            </div>
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                    <i class="fas fa-eye-slash text-2xl text-slate-300"></i>
                </div>
                <span class="text-xs font-medium text-slate-500">Preview not available</span>
            </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="p-4 bg-white space-y-3">
        @php
            $canUpload = in_array($researchTitle->Status ?? 'Pending', ['Incomplete', 'Pending', 'Pending (Initial Intake)']);
        @endphp
        @if($canUpload)
            <form action="{{ route('update.file', $researchTitle->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="file_id" value="{{ $file->id }}">
                <label class="block cursor-pointer">
                    <div
                        class="w-full py-2.5 px-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-[#8B0000] hover:bg-red-50 text-slate-500 hover:text-[#8B0000] text-sm font-bold text-center transition-all duration-200 flex items-center justify-center gap-2 group/upload">
                        <i class="fas fa-cloud-upload-alt group-hover/upload:animate-bounce"></i>
                        <span>Upload New Version</span>
                    </div>
                    <input type="file" name="file" class="hidden" onchange="this.form.submit()"
                        accept=".{{ $file->filetype }}">
                </label>
            </form>
        @endif
        <a href="{{ asset($file->filepath) }}" download
            class="block w-full py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 rounded-xl text-sm font-bold text-center transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-download"></i> Download File
        </a>
    </div>
</div>