@props(['name', 'label', 'accept', 'required' => false, 'multiple' => false])

<div data-file-item data-field-name="{{ $name }}" class="rounded-2xl p-3.5 sm:p-4.5 transition-all duration-200 group bg-slate-50/60 hover:bg-white hover:shadow-xs border border-slate-100 hover:border-slate-200">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <!-- Left: Document Details -->
        <div class="flex items-center gap-3 sm:gap-3.5 min-w-0 flex-1">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white flex items-center justify-center shrink-0 shadow-2xs group-hover:scale-102 transition-all duration-200">
                @if(str_contains(strtoupper($accept ?? ''), 'PDF'))
                    <i class="fas fa-file-pdf text-base sm:text-lg text-brand-primary" aria-hidden="true"></i>
                @elseif(str_contains(strtoupper($accept ?? ''), 'DOC'))
                    <i class="fas fa-file-word text-base sm:text-lg text-blue-600" aria-hidden="true"></i>
                @else
                    <i class="fas fa-file-alt text-base sm:text-lg text-slate-500" aria-hidden="true"></i>
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <label class="block text-sm sm:text-base font-bold text-slate-800 group-hover:text-brand-primary leading-snug cursor-pointer transition-colors">
                        {{ $label }}@if($required == 'true')<span class="text-brand-primary font-bold ml-0.5">*</span>@endif
                    </label>
                    @if($accept)
                        @php
                            $rawExt = str_replace('.', '', strtoupper($accept));
                            $extList = array_filter(array_map('trim', explode(',', $rawExt)));
                            $displayExt = count($extList) > 3 ? 'ALL FORMATS' : implode(', ', $extList);
                        @endphp
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-200/70 px-2 py-0.5 rounded-md uppercase tracking-wider shrink-0" title="{{ $rawExt }}">
                            {{ $displayExt }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Click Choose File to attach document</p>
            </div>
        </div>
        
        <!-- Right: Actions & File Status -->
        <div class="flex items-center gap-3 shrink-0 justify-between sm:justify-end border-t sm:border-t-0 pt-2.5 sm:pt-0 border-slate-100/80">
            <div class="flex items-center gap-2 min-w-0">
                <span class="file-name text-xs text-slate-500 font-medium italic max-w-[140px] sm:max-w-[200px] truncate">No file chosen</span>
                <button type="button" class="clear-btn hidden w-7 h-7 rounded-lg bg-slate-200/70 text-slate-600 hover:text-white hover:bg-brand-primary transition-all flex items-center justify-center shrink-0 cursor-pointer shadow-2xs hover:scale-105 active:scale-95" onclick="clearFile(this)" title="Remove file" aria-label="Remove file">
                    <i class="fas fa-times text-xs" aria-hidden="true"></i>
                </button>
            </div>
            
            <label class="cursor-pointer shrink-0">
                <span class="bg-red-50/90 text-brand-primary border border-red-100/80 hover:bg-brand-primary hover:text-white px-3.5 py-2.5 sm:py-2 rounded-xl font-bold text-xs shadow-2xs hover:shadow-xs transition-all inline-flex items-center gap-2 active:scale-95 cursor-pointer min-h-[42px] sm:min-h-0">
                    <i class="fas fa-arrow-up-from-bracket text-xs" aria-hidden="true"></i>
                    <span>Choose File</span>
                </span>
                <input type="file" 
                    name="{{ $name }}" 
                    class="hidden" 
                    accept="{{ $accept }}" 
                    @if($required == 'true') required @endif
                    @if($multiple == 'true') multiple @endif
                    onchange="updateFileName(this)">
            </label>
        </div>
    </div>
</div>
