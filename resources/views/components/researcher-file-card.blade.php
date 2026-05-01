@props(['file', 'researchTitle'])

@php 
    $ext = strtolower($file->filetype);
    $isPdf = $ext === 'pdf';
    $isOffice = in_array($ext, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']);
    $displayName = $file->category ?? 'General Document';

    $hasRemarks = isset($file->reviewerRemarks) && $file->reviewerRemarks->isNotEmpty();

    if ($hasRemarks) {
        $cardBorder = 'border-amber-400 shadow-md shadow-amber-100/50 ring-1 ring-amber-400/20';
    } else {
        $cardBorder = 'border-slate-200 hover:shadow-xl hover:shadow-slate-200/50 hover:border-slate-300';
    }

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
    class="group bg-white rounded-2xl border {{ $cardBorder }} overflow-hidden transition-all duration-300 flex flex-col relative">
    
    @if($hasRemarks)
        <!-- Floating Needs Attention Badge -->
        <div class="absolute -right-12 top-5 bg-amber-500 text-white text-[10px] font-extrabold uppercase tracking-widest py-1 px-12 rotate-45 shadow-sm shadow-amber-500/20 z-20 pointer-events-none">
            Attention
        </div>
    @endif

    <!-- Header -->
    <div class="p-5 flex items-start gap-4 border-b {{ $hasRemarks ? 'border-amber-100 bg-amber-50/20' : 'border-slate-50 bg-white' }} relative z-10">
        <div
            class="w-12 h-12 rounded-xl {{ $bgClass }} flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
            <i class="fas {{ $iconClass }} text-xl"></i>
        </div>
        <div class="min-w-0 flex-1 pr-4">
            <h4 class="font-bold text-slate-800 text-sm leading-snug truncate mb-1" x-text="fileName" :title="fileName">
                {{ $displayName }}
            </h4>
        </div>
    </div>

    <!-- Reviewer Remarks Section -->
    @if($hasRemarks)
        <div class="bg-amber-50 border-b border-amber-100 px-5 py-4 z-10 relative">
            <div class="absolute top-0 left-0 bottom-0 w-1 bg-amber-400"></div>
            <div class="flex items-center gap-2 mb-3">
                <i class="fas fa-exclamation-circle text-amber-600"></i>
                <span class="text-xs font-extrabold text-amber-900 uppercase tracking-widest">Reviewer Feedback</span>
            </div>
            <div class="space-y-2">
                @foreach($file->reviewerRemarks as $remark)
                    <div class="bg-white p-3 rounded-lg border border-amber-200 shadow-sm relative overflow-hidden">
                        <p class="text-[10px] font-bold text-slate-500 mb-1 flex items-center gap-1.5 uppercase tracking-wider">
                            <i class="fas fa-user-edit text-slate-400"></i> 
                            {{ $remark->reviewer->first_name ?? 'Reviewer' }} {{ $remark->reviewer->last_name ?? '' }}
                        </p>
                        <p class="text-xs text-amber-900 leading-relaxed font-medium whitespace-pre-wrap">{{ $remark->remarks }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Preview Area -->
    <div
        class="relative bg-slate-50 flex-1 min-h-[200px] border-b border-slate-100 group-hover:bg-slate-100 transition-colors overflow-hidden">
        @if($isPdf)
            <iframe loading="lazy" :src="fileSrc + '#toolbar=0&navpanes=0&scrollbar=0'"
                class="w-full h-full border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
            <div
                class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                <a :href="fileSrc" target="_blank"
                    class="transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 bg-white/90 backdrop-blur-sm text-slate-900 px-5 py-2.5 rounded-full font-bold text-sm shadow-lg hover:bg-[#8B0000] hover:text-white flex items-center gap-2">
                    <i class="fas fa-external-link-alt"></i> View Fullscreen
                </a>
            </div>
        @elseif($isOffice)
            <iframe loading="lazy" :src="'https://view.officeapps.live.com/op/view.aspx?src=' + encodeURIComponent(fileSrc)" width="100%"
                height="100%"
                class="border-0 pointer-events-none opacity-80 group-hover:opacity-100 scale-[1.02] group-hover:scale-100 transition-all duration-500"></iframe>
            <div
                class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/5 transition-colors flex items-center justify-center p-6">
                <a :href="'https://view.officeapps.live.com/op/view.aspx?src=' + encodeURIComponent(fileSrc)"
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
    <div class="p-4 bg-white space-y-3 relative overflow-hidden">
        <!-- Overlay when uploading -->
        <div x-show="isUploading"
            class="absolute inset-0 z-20 bg-white/90 backdrop-blur-sm flex items-center justify-center pointer-events-none transition-opacity duration-300"
            style="display: none;">
            <div class="flex flex-col items-center gap-2">
                <i class="fas fa-spinner fa-spin text-[#8B0000] text-2xl"></i>
                <span class="text-[10px] font-bold text-[#8B0000] uppercase tracking-wider">Uploading...</span>
            </div>
        </div>

        @php
            $canUpload = in_array($researchTitle->Status ?? 'Pending', ['Incomplete', 'Pending', 'Pending (Initial Intake)']);
        @endphp
        @if($canUpload)
            <label class="block cursor-pointer">
                <div class="w-full py-2.5 px-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-[#8B0000] hover:bg-red-50 text-slate-500 hover:text-[#8B0000] text-sm font-bold text-center transition-all duration-200 flex items-center justify-center gap-2 group/upload"
                    :class="isUploading ? 'opacity-50 pointer-events-none bg-slate-50 border-slate-200' : ''">
                    <i class="fas fa-cloud-upload-alt group-hover/upload:animate-bounce"></i>
                    <span>Upload New Version</span>
                </div>
                <input type="file" class="hidden" @change="uploadFile($event)" accept=".{{ $file->filetype }}"
                    :disabled="isUploading">
            </label>
        @endif
        <a :href="fileSrc" download
            class="block w-full py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 rounded-xl text-sm font-bold text-center transition-colors flex flex-row items-center justify-center gap-2">
            <i class="fas fa-download"></i> Download File
        </a>
    </div>
</div>