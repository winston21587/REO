@props(['file', 'researchTitle'])

@php 
    $ext = strtolower($file->filetype);
    $isPdf = $ext === 'pdf';
    $isOffice = in_array($ext, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']);
    $displayName = $file->category ?? 'General Document';

    $hasRemarks = isset($file->reviewerRemarks) && $file->reviewerRemarks->isNotEmpty();

    if ($hasRemarks) {
        $cardBorder = 'border-amber-300 shadow-md shadow-amber-100/50 ring-1 ring-amber-300/60';
    } else {
        $cardBorder = 'border-slate-200/90 hover:shadow-lg hover:shadow-slate-200/50 hover:border-slate-300';
    }

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

<div x-data="{
    isUploading: false,
    fileSrc: '{{ asset($file->filepath) }}',
    fileName: '{{ addslashes($displayName) }}',
    async uploadFile(event) {
        const file = event.target.files[0];
        if (!file) return;
        this.isUploading = true;
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('file_id', '{{ $file->id }}');
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        try {
            const response = await fetch('{{ route('update.file', $researchTitle->id) }}', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            
            if (!response.ok) {
                if (response.status === 422 && data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    throw new Error(firstError);
                }
                throw new Error(data.message || data.error || 'Failed to upload file. Check file size or type.');
            }
            
            if (data.success) {
                this.fileSrc = data.file.filepath;
                
                Swal.fire({ 
                    icon: 'success', 
                    title: 'File Updated', 
                    text: 'The document was successfully replaced.', 
                    timer: 2000, 
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                throw new Error('Server returned unexpected format.');
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Upload Error', text: error.message });
        } finally {
            this.isUploading = false;
            event.target.value = '';
        }
    }
 }"
    class="group bg-white rounded-2xl border {{ $cardBorder }} overflow-hidden transition-all duration-200 flex flex-col relative">

    <!-- Header with High-Contrast Hierarchy -->
    <div class="p-4 sm:p-5 flex items-start gap-3.5 border-b {{ $hasRemarks ? 'border-amber-200/70 bg-amber-50/40' : 'border-slate-100 bg-white' }} relative z-10">
        <div class="w-11 h-11 rounded-xl {{ $bgClass }} flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform duration-200">
            <i class="fas {{ $iconClass }} text-lg"></i>
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between gap-2 mb-1">
                <h3 class="font-black text-slate-900 text-sm sm:text-base leading-snug truncate" x-text="fileName" :title="fileName">
                    {{ $displayName }}
                </h3>
                @if($hasRemarks)
                    <span class="shrink-0 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-300 shadow-2xs">
                        Action Required
                    </span>
                @endif
            </div>
            <p class="text-[11px] text-slate-500 font-medium truncate" title="{{ $file->filename }}">{{ $file->filename }}</p>
        </div>
    </div>

    <!-- Reviewer Remarks Section (Decisive Callout) -->
    @if($hasRemarks)
        <div class="bg-amber-50/90 border-b border-amber-200/80 px-4 sm:px-5 py-3.5 z-10 relative">
            <div class="flex items-center gap-2 mb-2.5">
                <div class="w-5 h-5 rounded-full bg-amber-200/80 flex items-center justify-center text-amber-800 text-[10px]">
                    <i class="fas fa-exclamation"></i>
                </div>
                <span class="text-[10px] font-black text-amber-900 uppercase tracking-widest">Reviewer Feedback</span>
            </div>
            <div class="space-y-2">
                @foreach($file->reviewerRemarks as $remark)
                    <div class="bg-white p-3 sm:p-3.5 rounded-xl border border-amber-200 shadow-2xs relative">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <p class="text-[11px] font-black text-slate-800 flex items-center gap-1.5 uppercase tracking-wider">
                                <i class="fas fa-user-pen text-amber-600 text-xs"></i> 
                                <span>{{ $remark->reviewer->first_name ?? 'Reviewer' }} {{ $remark->reviewer->last_name ?? '' }}</span>
                            </p>
                            @if(isset($remark->created_at))
                                <span class="text-[10px] font-bold text-slate-400">{{ $remark->created_at->diffForHumans() }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-800 leading-relaxed font-medium whitespace-pre-wrap pl-3 border-l-2 border-amber-400">{{ $remark->remarks }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Document Visual / Preview Area (Touch discoverable & Desktop Hover) -->
    <div class="relative bg-gradient-to-b from-slate-50 to-slate-100/50 flex-1 min-h-[160px] border-b border-slate-100 group-hover:bg-slate-100/70 transition-colors overflow-hidden flex flex-col items-center justify-center p-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-white shadow-xs border border-slate-200/80 flex items-center justify-center mb-2.5 group-hover:scale-105 transition-transform duration-200">
            <i class="fas {{ $iconClass }} text-2xl"></i>
        </div>
        <p class="text-xs font-black text-slate-800 max-w-[220px] truncate mb-1" x-text="fileName"></p>
        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md {{ $ext === 'pdf' ? 'bg-red-50 text-brand-primary border border-red-100' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
            {{ strtoupper($ext ?: 'DOC') }} File
        </span>

        <div class="absolute inset-0 bg-slate-900/0 sm:group-hover:bg-slate-900/10 transition-colors flex items-center justify-center p-4">
            <a :href="fileSrc" target="_blank"
                class="opacity-90 sm:opacity-0 sm:group-hover:opacity-100 sm:translate-y-2 sm:group-hover:translate-y-0 transition-all duration-200 bg-white/95 backdrop-blur-sm text-slate-900 hover:text-brand-primary px-4 py-2 min-h-[38px] rounded-xl font-black text-xs shadow-md flex items-center gap-2"
                aria-label="View {{ $displayName }} in fullscreen">
                <i class="fas fa-external-link-alt text-[11px]"></i>
                <span>View Fullscreen</span>
            </a>
        </div>
    </div>

    <!-- Actions with 44px Touch Targets -->
    <div class="p-3.5 sm:p-4 bg-white space-y-2.5 relative overflow-hidden">
        <!-- Overlay when uploading -->
        <div x-show="isUploading"
            class="absolute inset-0 z-20 bg-white/90 backdrop-blur-sm flex items-center justify-center pointer-events-none transition-opacity duration-200"
            style="display: none;">
            <div class="flex flex-col items-center gap-2">
                <i class="fas fa-spinner fa-spin text-brand-primary text-xl"></i>
                <span class="text-[10px] font-black text-brand-primary uppercase tracking-wider">Uploading...</span>
            </div>
        </div>

        @php
            $canUpload = in_array($researchTitle->Status ?? 'Pending', ['Incomplete', 'Pending', 'Pending (Initial Intake)']);
        @endphp
        @if($canUpload)
            <label class="block cursor-pointer">
                <div class="w-full py-2.5 px-4 min-h-[44px] rounded-xl border-2 border-dashed border-slate-300 hover:border-brand-primary hover:bg-brand-primary hover:text-white text-slate-700 text-xs sm:text-sm font-black text-center transition-all duration-150 flex items-center justify-center gap-2 group/upload active:scale-[0.98]"
                    :class="isUploading ? 'opacity-50 pointer-events-none bg-slate-50 border-slate-200' : ''">
                    <i class="fas fa-cloud-upload-alt text-slate-400 group-hover/upload:text-white transition-colors"></i>
                    <span>Upload New Version</span>
                </div>
                <input type="file" class="hidden" @change="uploadFile($event)" accept=".{{ $file->filetype }}"
                    :disabled="isUploading" aria-label="Upload new version of {{ $displayName }}">
            </label>
        @endif
        <a :href="fileSrc" download
            class="block w-full py-2.5 px-4 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-800 hover:text-slate-900 rounded-xl text-xs sm:text-sm font-black text-center transition-colors flex items-center justify-center gap-2 active:scale-[0.98]"
            aria-label="Download {{ $file->filename }}">
            <i class="fas fa-download text-slate-500"></i>
            <span>Download File</span>
        </a>
    </div>
</div>