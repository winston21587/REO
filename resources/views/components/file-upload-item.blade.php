@props(['name', 'label', 'accept', 'required' => false, 'multiple' => false])

<div class="border border-slate-200 rounded-xl p-4 hover:border-[#8B0000] transition-colors group bg-slate-50 hover:bg-white">
    <label class="block text-sm font-bold text-slate-700 mb-2">
        {{ $label }}
        @if($required == 'true') <span class="text-red-500">*</span> @endif
    </label>
    
    <div class="flex items-center gap-4">
        <label class="cursor-pointer">
            <span class="bg-[#8B0000] text-white px-4 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-red-800 transition-colors inline-block">
                Choose File
            </span>
            <input type="file" 
                name="{{ $name }}" 
                class="hidden" 
                accept="{{ $accept }}" 
                @if($required == 'true') required @endif
                @if($multiple == 'true') multiple @endif
                onchange="updateFileName(this)">
        </label>
        
        <span class="file-name text-sm text-slate-400 italic truncate">No file chosen</span>
        
        <button type="button" class="clear-btn hidden text-slate-400 hover:text-red-500 transition-colors" onclick="clearFile(this)">
            <span class="material-icons text-sm">close</span>
        </button>
    </div>
</div>
