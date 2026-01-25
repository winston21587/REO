<x-admin_layout title="Website Content Manager">
    <div class="max-w-7xl mx-auto py-8 relative">
        
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2 animate-[fadeIn_0.5s]">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.cms.content.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <!-- Quick Navigation Sidebar -->
                <div class="hidden lg:block w-64 flex-shrink-0 sticky top-4">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-4 bg-slate-50 border-b border-slate-100 font-bold text-slate-700">
                            <i class="fas fa-list-ul mr-2 text-[#8B0000]"></i> Sections
                        </div>
                        <nav class="p-2 space-y-1">
                            <a href="#section-general" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-red-50 hover:text-[#8B0000] font-medium transition-colors">
                                <i class="fas fa-cog w-5 text-center mr-1"></i> General
                            </a>
                            <a href="#section-hero" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-red-50 hover:text-[#8B0000] font-medium transition-colors">
                                <i class="fas fa-images w-5 text-center mr-1"></i> Hero Slider
                            </a>
                            <a href="#section-mission" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-red-50 hover:text-[#8B0000] font-medium transition-colors">
                                <i class="fas fa-bullseye w-5 text-center mr-1"></i> Mission & Vision
                            </a>
                            <a href="#section-purpose" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-red-50 hover:text-[#8B0000] font-medium transition-colors">
                                <i class="fas fa-shield-alt w-5 text-center mr-1"></i> Purpose & Join
                            </a>
                            <a href="#section-auth" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-red-50 hover:text-[#8B0000] font-medium transition-colors">
                                <i class="fas fa-lock w-5 text-center mr-1"></i> Auth Pages
                            </a>
                            <a href="#section-legal" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-red-50 hover:text-[#8B0000] font-medium transition-colors">
                                <i class="fas fa-gavel w-5 text-center mr-1"></i> Legal Pages
                            </a>
                            <a href="#section-footer" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-red-50 hover:text-[#8B0000] font-medium transition-colors">
                                <i class="fas fa-shoe-prints w-5 text-center mr-1"></i> Footer
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="flex-1 space-y-8 w-full">
                    
                    <!-- General Settings (Logo) -->
                    <div id="section-general" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24">
                        <div class="flex items-center justify-between mb-6 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-cog"></i>
                                </div>
                                General Branding
                            </h3>
                        </div>
                        <div class="grid md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Website Logo</label>
                                <div class="flex items-start gap-6">
                                    <div class="flex-shrink-0">
                                        @if(isset($contents['website_logo']))
                                            <div class="w-24 h-24 rounded-lg border border-slate-200 p-2 flex items-center justify-center bg-slate-50">
                                                <img src="{{ asset($contents['website_logo']) }}" class="max-w-full max-h-full object-contain">
                                            </div>
                                        @else
                                            <div class="w-24 h-24 rounded-lg border border-slate-200 p-2 flex items-center justify-center bg-slate-50 text-slate-300">
                                                <i class="fas fa-image text-3xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="website_logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#8B0000]/10 file:text-[#8B0000] hover:file:bg-[#8B0000]/20 mb-2">
                                        <p class="text-xs text-slate-500">Recommended: PNG with transparent background. Used in Header, Footer, and Auth pages.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Section (Slider) -->
                    <div id="section-hero" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24">
                        <div class="flex items-center justify-between mb-6 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-images"></i>
                                </div>
                                Hero Slider
                            </h3>
                            <span class="text-xs font-semibold px-2 py-1 bg-slate-100 text-slate-500 rounded">3 Slides</span>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-8">
                             @for($i=1; $i<=3; $i++)
                                <div class="bg-white p-0 md:p-4 rounded-xl border border-slate-100/50 md:border-slate-200 md:bg-slate-50">
                                    <h4 class="font-bold text-[#8B0000] mb-4 text-sm uppercase tracking-wide flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-[#8B0000] text-white flex items-center justify-center text-xs">{{ $i }}</span>
                                        Slide {{ $i }}
                                    </h4>
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Title</label>
                                                <input type="text" name="hero_title_{{ $i }}" value="{{ $contents['hero_title_' . $i] ?? 'Empty' }}" 
                                                    class="w-full px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-[#8B0000] outline-none transition-shadow">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Description</label>
                                                <textarea name="hero_text_{{ $i }}" rows="3" 
                                                    class="w-full px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-[#8B0000] outline-none transition-shadow">{{ $contents['hero_text_' . $i] ?? 'Empty' }}</textarea>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Background Image</label>
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:bg-slate-50 transition-colors relative group">
                                                <div class="space-y-1 text-center">
                                                     @if(isset($contents['hero_image_' . $i]))
                                                        <div class="mb-2">
                                                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <i class="fas fa-check-circle mr-1"></i> Uploaded
                                                            </span>
                                                        </div>
                                                    @else
                                                        <i class="fas fa-image text-slate-400 text-3xl mb-2"></i>
                                                    @endif
                                                    <div class="flex text-sm text-slate-600 justify-center">
                                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-[#8B0000] hover:text-red-700 focus-within:outline-none">
                                                            <span>Upload a file</span>
                                                            <input type="file" name="hero_image_{{ $i }}" accept="image/*" class="sr-only">
                                                        </label>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-slate-500">PNG, JPG, GIF up to 10MB</p>
                                                </div>
                                                @if(isset($contents['hero_image_' . $i]))
                                                    <a href="{{ asset($contents['hero_image_' . $i]) }}" target="_blank" class="absolute top-2 right-2 bg-white/90 p-1.5 rounded-full text-slate-600 hover:text-[#8B0000] shadow-sm border border-slate-200" title="Preview">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Mission / Vision / Goals -->
                    <div id="section-mission" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24">
                        <div class="flex items-center justify-between mb-6 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                Mission, Vision & Goals
                            </h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach(['mission', 'vision', 'goals'] as $item)
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 capitalize">
                                        {{ $item }}
                                    </label>
                                    <textarea name="{{ $item }}_text" rows="6" 
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#8B0000] outline-none transition-shadow text-sm leading-relaxed">{{ $contents[$item . '_text'] ?? 'Empty' }}</textarea>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Purpose & Join Us -->
                    <div id="section-purpose" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24">
                        <div class="flex items-center justify-between mb-6 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-columns"></i>
                                </div>
                                Content Sections
                            </h3>
                        </div>

                         <div class="space-y-8">
                            <!-- Purpose -->
                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 relative">
                                <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                    <i class="fas fa-shield-alt text-[#8B0000]"></i> Our Purpose
                                </h4>
                                <div class="grid md:grid-cols-3 gap-6">
                                    <div class="md:col-span-2 space-y-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 uppercase">Headline</label>
                                            <input type="text" name="purpose_title" value="{{ $contents['purpose_title'] ?? 'Empty' }}" class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-[#8B0000] outline-none">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 uppercase">Content</label>
                                            <textarea name="purpose_text" rows="3" class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-[#8B0000] outline-none">{{ $contents['purpose_text'] ?? 'Empty' }}</textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Image</label>
                                        <div class="relative group">
                                            @if(isset($contents['purpose_image']))
                                                <img src="{{ asset($contents['purpose_image']) }}" class="w-full h-32 object-cover rounded-lg border border-slate-200 mb-2">
                                                <a href="{{ asset($contents['purpose_image']) }}" target="_blank" class="absolute top-2 right-2 bg-white/90 p-1.5 rounded-full text-slate-600 hover:text-[#8B0000] shadow-sm"><i class="fas fa-external-link-alt"></i></a>
                                            @endif
                                            <input type="file" name="purpose_image" accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:bg-[#8B0000]/10 file:text-[#8B0000] hover:file:bg-[#8B0000]/20">
                                        </div>
                                    </div>
                                </div>
                            </div>

                             <!-- Join Us -->
                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 relative">
                                <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                    <i class="fas fa-user-plus text-[#8B0000]"></i> Join Us
                                </h4>
                                <div class="grid md:grid-cols-3 gap-6">
                                    <div class="md:col-span-2 space-y-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 uppercase">Title</label>
                                            <input type="text" name="join_title" value="{{ $contents['join_title'] ?? 'Empty' }}" class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-[#8B0000] outline-none">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-500 uppercase">Content</label>
                                            <textarea name="join_text" rows="3" class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-[#8B0000] outline-none">{{ $contents['join_text'] ?? 'Empty' }}</textarea>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-500 uppercase mb-1 block">Image</label>
                                        <div class="relative group">
                                            @if(isset($contents['join_image']))
                                                <img src="{{ asset($contents['join_image']) }}" class="w-full h-32 object-cover rounded-lg border border-slate-200 mb-2">
                                                <a href="{{ asset($contents['join_image']) }}" target="_blank" class="absolute top-2 right-2 bg-white/90 p-1.5 rounded-full text-slate-600 hover:text-[#8B0000] shadow-sm"><i class="fas fa-external-link-alt"></i></a>
                                            @endif
                                            <input type="file" name="join_image" accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:bg-[#8B0000]/10 file:text-[#8B0000] hover:file:bg-[#8B0000]/20">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <!-- Auth Pages -->
                    <div id="section-auth" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24">
                        <div class="flex items-center justify-between mb-6 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-lock"></i>
                                </div>
                                Auth Page Images
                            </h3>
                        </div>
                        <div class="grid md:grid-cols-2 gap-8">
                             <!-- Login -->
                            <div class="border border-slate-100 rounded-xl p-4">
                                <h4 class="font-bold text-sm text-slate-700 mb-3">Login Background</h4>
                                @if(isset($contents['login_image']))
                                    <img src="{{ asset($contents['login_image']) }}" class="w-full h-40 object-cover rounded-lg mb-3 border border-slate-200">
                                @else
                                    <div class="w-full h-40 bg-slate-100 rounded-lg mb-3 flex items-center justify-center text-slate-300">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                @endif
                                <input type="file" name="login_image" accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                            </div>
                             <!-- Register -->
                             <div class="border border-slate-100 rounded-xl p-4">
                                <h4 class="font-bold text-sm text-slate-700 mb-3">Register Background</h4>
                                @if(isset($contents['register_image']))
                                    <img src="{{ asset($contents['register_image']) }}" class="w-full h-40 object-cover rounded-lg mb-3 border border-slate-200">
                                @else
                                    <div class="w-full h-40 bg-slate-100 rounded-lg mb-3 flex items-center justify-center text-slate-300">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                @endif
                                <input type="file" name="register_image" accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Legal Pages -->
                    <div id="section-legal" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24">
                         <div class="flex items-center justify-between mb-8 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-gavel"></i>
                                </div>
                                Legal Pages
                            </h3>
                        </div>
                        
                        <div class="space-y-10">
                            @foreach(['Privacy Policy' => 'privacy', 'Terms of Service' => 'terms', 'Accessibility' => 'accessibility'] as $label => $key)
                                <div class="relative">
                                     <div class="mb-4 flex items-center gap-3">
                                        <div class="h-6 w-1 bg-[#8B0000] rounded-full"></div>
                                        <h4 class="font-bold text-slate-800 text-lg">{{ $label }}</h4>
                                     </div>
                                     
                                     <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                        <div class="grid md:grid-cols-3 gap-8">
                                            <div class="md:col-span-2">
                                                 <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Page Content (HTML Allowed)</label>
                                                 <textarea name="{{ $key }}_content" rows="12" 
                                                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm font-mono focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-sm resize-y leading-relaxed"
                                                    placeholder="Enter content for {{ $label }}...">{{ $contents[$key . '_content'] ?? '' }}</textarea>
                                            </div>
                                            <div>
                                                 <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Header Image</label>
                                                 <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                                                      @if(isset($contents[$key . '_header_image']))
                                                        <div class="relative group mb-3 rounded-lg overflow-hidden">
                                                            <img src="{{ asset($contents[$key . '_header_image']) }}" class="w-full h-32 object-cover">
                                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                <a href="{{ asset($contents[$key . '_header_image']) }}" target="_blank" class="text-white text-xs bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm hover:bg-white hover:text-red-900 transition-colors">
                                                                    View Full
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="w-full h-32 bg-slate-100 rounded-lg mb-3 flex flex-col items-center justify-center text-slate-400 border border-dashed border-slate-300">
                                                            <i class="fas fa-image text-2xl mb-1"></i>
                                                            <span class="text-[10px]">No image</span>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="{{ $key }}_header_image" accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#8B0000]/10 file:text-[#8B0000] hover:file:bg-[#8B0000]/20 cursor-pointer">
                                                 </div>
                                            </div>
                                        </div>
                                     </div>
                                </div>
                            @endforeach
                        </div>
                    </div>



                    <!-- Footer -->
                    <div id="section-footer" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 scroll-mt-24 mb-20">
                        <div class="flex items-center justify-between mb-6 border-b pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-[#8B0000]">
                                    <i class="fas fa-shoe-prints"></i>
                                </div>
                                Footer Information
                            </h3>
                        </div>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div class="space-y-4">
                                 <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Short Description</label>
                                    <textarea name="footer_description" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-sm">{{ $contents['footer_description'] ?? 'Empty' }}</textarea>
                                 </div>
                                 <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Facebook URL</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fab fa-facebook"></i></span>
                                        <input type="text" name="footer_facebook" value="{{ $contents['footer_facebook'] ?? 'Empty' }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-sm">
                                    </div>
                                 </div>
                             </div>
                             <div class="space-y-4">
                                 <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Email Address</label>
                                     <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-envelope"></i></span>
                                        <input type="text" name="footer_email" value="{{ $contents['footer_email'] ?? 'reo@wmsu.edu.ph' }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-sm">
                                    </div>
                                 </div>
                                 <div class="grid grid-cols-2 gap-4">
                                     <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Phone</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-phone"></i></span>
                                            <input type="text" name="footer_phone" value="{{ $contents['footer_phone'] ?? 'Empty' }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-sm">
                                        </div>
                                     </div>
                                     <div>
                                         <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Address</label>
                                         <textarea name="footer_address" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#8B0000]/20 focus:border-[#8B0000] outline-none transition-all shadow-sm">{{ $contents['footer_address'] ?? 'Empty' }}</textarea>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    </div>

                </div>
            </div>

            <!-- Fixed Save Button -->
            <div class="fixed bottom-6 right-8 z-50 animate-[fadeInUp_0.5s]">
                <button type="submit" class="group bg-[#8B0000] text-white px-6 py-3 rounded-full font-bold hover:bg-red-800 transition-all shadow-xl shadow-red-900/20 transform hover:-translate-y-1 hover:scale-105 flex items-center gap-3 ring-2 ring-white border border-[#8B0000]">
                    <span class="bg-white/20 p-1.5 rounded-full group-hover:bg-white/30 transition-colors">
                        <i class="fas fa-save text-sm"></i>
                    </span>
                    <span class="text-sm tracking-wide">Save Changes</span>
                </button>
            </div>

        </form>
    </div>
</x-admin_layout>
