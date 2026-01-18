<x-admin_layout>
    <div class="max-w-7xl mx-auto py-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Top Navigation & Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6 p-6 bg-gradient-to-r from-white to-slate-50 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full blur-3xl opacity-50 -mr-16 -mt-16 pointer-events-none group-hover:opacity-70 transition-opacity duration-700"></div>
            
            <div class="flex items-center gap-5 relative z-10">
                <a href="javascript:history.back()" class="group flex items-center justify-center w-12 h-12 rounded-2xl bg-white border border-slate-200 text-slate-400 hover:border-[#8B0000] hover:text-[#8B0000] hover:shadow-md hover:-translate-x-1 transition-all duration-300">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-extrabold uppercase tracking-widest border border-slate-200 shadow-sm">
                            {{ $researchTitle->reoc_code ?? 'PENDING-ID' }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-extrabold uppercase tracking-widest border border-amber-100 flex items-center gap-1.5 shadow-sm">
                            <i class="fas fa-clock text-[10px]"></i> Pending Review
                        </span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 font-heading leading-tight tracking-tight">
                        {{ $researchTitle->Study_Protocol_title }}
                    </h1>
                </div>
            </div>
            
            <div class="flex gap-3 relative z-10">
                <!-- Buttons removed as per request -->
            </div>
        </div>

        @php
            // Server-side filename cleaning to ensure valid JSON and display
            $cleanFiles = $researchTitle->files->map(function($file) {
                $filename = $file->filename;
                // Remove timestamp
                $clean = preg_replace('/^\d+_/', '', $filename);
                // Replace underscores/hyphens with spaces
                $clean = preg_replace('/[_-]/', ' ', $clean);
                // Replace multiple spaces/newlines
                $clean = preg_replace('/\s+/', ' ', $clean);
                // Trim
                $clean = trim($clean);
                // Remove extension
                $clean = preg_replace('/\.[^.]+$/', '', $clean);
                // Title Case
                $clean = ucwords(strtolower($clean));
                
                $file->clean_filename = $clean ?: 'Unknown File';
                return $file;
            });
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-[calc(100vh-240px)]" 
             x-data="{ 
                activeFile: {{ $cleanFiles->isNotEmpty() ? json_encode($cleanFiles->first()) : 'null' }},
                files: {{ json_encode($cleanFiles) }},
                getFileUrl(file) {
                    return file ? '{{ route('admin.serve_file', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', file.id) : '';
                }
             }">
            
            <!-- Left Column: Document Viewer (8 cols) -->
            <div class="lg:col-span-8 flex flex-col gap-4 h-full">
                <!-- Glassmorphic Control Bar -->
                <div class="flex items-center justify-between bg-white/80 backdrop-blur-md p-2 rounded-2xl border border-slate-200 shadow-sm sticky top-0 z-20">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 px-2 py-1.5 hover:bg-slate-50 rounded-xl transition-all text-left min-w-[280px] group">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-red-50 to-white border border-red-100 flex items-center justify-center text-[#8B0000] shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fas fa-file-pdf text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Currently Viewing</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-slate-800 truncate max-w-[180px]" x-text="activeFile ? activeFile.clean_filename : 'Select a file'"></p>
                                    <i class="fas fa-chevron-down text-slate-300 text-[10px] transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                                </div>
                            </div>
                        </button>

                        <!-- Modern Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 translate-y-2"
                             x-transition:enter-end="transform opacity-100 translate-y-0"
                             class="absolute top-full left-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 overflow-hidden ring-1 ring-black/5"
                             style="display: none;">
                            <div class="p-2 bg-slate-50 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-500 px-2 uppercase tracking-wider">Available Documents</p>
                            </div>
                            <div class="p-2 max-h-[300px] overflow-y-auto custom-scrollbar">
                                <template x-for="file in files" :key="file.id">
                                    <button @click="activeFile = file; open = false" 
                                            class="w-full flex items-center gap-3 p-3 rounded-xl transition-all text-left group relative overflow-hidden"
                                            :class="activeFile && activeFile.id === file.id ? 'bg-red-50 text-[#8B0000] ring-1 ring-red-100' : 'hover:bg-slate-50 text-slate-600'">
                                        
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors"
                                             :class="activeFile && activeFile.id === file.id ? 'bg-white text-[#8B0000] shadow-sm' : 'bg-slate-100 text-slate-400 group-hover:bg-white group-hover:shadow-sm'">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0 z-10">
                                            <p class="text-sm font-bold truncate" x-text="file.clean_filename"></p>
                                        </div>
                                        
                                        <div x-show="activeFile && activeFile.id === file.id" class="absolute right-3 w-2 h-2 rounded-full bg-[#8B0000]"></div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-1 pr-1" x-show="activeFile">
                        <a :href="getFileUrl(activeFile)" download class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                        <a :href="getFileUrl(activeFile)" target="_blank" class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-all" title="Open in New Tab">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>

                <!-- PDF/DOCX Viewer Container -->
                <div class="flex-1 bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-800 relative group" x-ref="viewerContainer">
                    <!-- PDF Viewer -->
                    <template x-if="activeFile && (activeFile.filename.endsWith('.pdf') || activeFile.filetype === 'pdf')">
                        <iframe :src="getFileUrl(activeFile)" class="w-full h-full border-0" title="Document Viewer"></iframe>
                    </template>

                    <!-- DOCX Viewer -->
                    <div x-show="activeFile && (activeFile.filename.endsWith('.docx') || activeFile.filename.endsWith('.doc'))" class="w-full h-full bg-white overflow-y-auto custom-scrollbar p-8">
                        <div id="docx-container" class="w-full min-h-full bg-white shadow-sm"></div>
                    </div>
                    
                    <!-- Loading State -->
                    <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm z-50">
                        <div class="w-12 h-12 border-4 border-white/30 border-t-white rounded-full animate-spin"></div>
                    </div>

                    <!-- No File / Unsupported -->
                    <template x-if="!activeFile">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-file-pdf text-4xl text-slate-600"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No Document Selected</p>
                                <p class="text-slate-600 text-xs mt-2">Select a file from the dropdown to view.</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right Column: Sidebar (4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-6 h-full overflow-y-auto pr-2 custom-scrollbar pb-4">
                
                <!-- Principal Investigator Card -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-50 to-slate-100 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110 duration-500"></div>
                    
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-6 relative z-10">Principal Investigator</h3>
                    
                    <div class="flex items-center gap-5 relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#8B0000] to-red-900 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-red-900/20 ring-4 ring-red-50">
                            {{ substr($researchTitle->researcher->user->first_name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-lg leading-tight">{{ $researchTitle->researcher->user->first_name ?? 'Unknown' }} {{ $researchTitle->researcher->user->last_name ?? '' }}</p>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wide mt-1">{{ $researchTitle->researcher->college ?? 'External Researcher' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Submission Details -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-6">Submission Details</h3>
                    <div class="space-y-5">
                        <div class="flex justify-between items-center group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-tag text-xs"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-600">Category</span>
                            </div>
                            <span class="text-sm font-bold text-slate-900 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">{{ $researchTitle->Research_Category }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-calendar text-xs"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-600">Submitted</span>
                            </div>
                            <span class="text-sm font-bold text-slate-900">{{ $researchTitle->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="flex justify-between items-center group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-clipboard-check text-xs"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-600">Completeness</span>
                            </div>
                            <span class="text-xs font-bold text-green-700 bg-green-50 px-3 py-1 rounded-lg border border-green-100 flex items-center gap-1.5">
                                <i class="fas fa-check-circle"></i> 100%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- File Playlist Removed as per request -->
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('fileViewer', (files) => ({
                        activeFile: files.length > 0 ? files[0] : null,
                        files: files,
                        isLoading: false,
                        
                        init() {
                            this.$watch('activeFile', (file) => {
                                if (file && (file.filename.endsWith('.docx') || file.filename.endsWith('.doc'))) {
                                    this.renderDocx(file);
                                }
                            });
                            // Initial render if first file is docx
                            if (this.activeFile && (this.activeFile.filename.endsWith('.docx') || this.activeFile.filename.endsWith('.doc'))) {
                                this.renderDocx(this.activeFile);
                            }
                        },

                        getFileUrl(file) {
                            return file ? '{{ route('admin.serve_file', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', file.id) : '';
                        },

                        renderDocx(file) {
                            this.isLoading = true;
                            const url = this.getFileUrl(file);
                            
                            fetch(url)
                                .then(response => response.blob())
                                .then(blob => {
                                    const container = document.getElementById('docx-container');
                                    container.innerHTML = ''; // Clear previous
                                    
                                    docx.renderAsync(blob, container, container, {
                                        className: "docx-wrapper",
                                        inWrapper: true,
                                        ignoreWidth: false,
                                        ignoreHeight: false,
                                        ignoreFonts: false,
                                        breakPages: true,
                                        ignoreLastRenderedPageBreak: true,
                                        experimental: false,
                                        trimXmlDeclaration: true,
                                        useBase64URL: false,
                                        useMathMLPolyfill: false,
                                        debug: false,
                                    })
                                    .then(() => {
                                        this.isLoading = false;
                                        console.log("DOCX rendered successfully");
                                    })
                                    .catch(err => {
                                        console.error("Error rendering DOCX:", err);
                                        this.isLoading = false;
                                        container.innerHTML = '<div class="text-center p-4 text-red-500">Error rendering document. Please download to view.</div>';
                                    });
                                })
                                .catch(err => {
                                    console.error("Error fetching DOCX:", err);
                                    this.isLoading = false;
                                });
                        }
                    }));
                });
            </script>

            </div>
        </div>
    </div>
</x-admin_layout>