<x-user_layout>
    <div class="max-w-7xl mx-auto animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Welcome Section -->
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading">
                    Welcome back, <span class="text-brand-primary">{{ explode(' ', Auth::user()->first_name)[0] }}</span>!
                </h1>
                <p class="text-slate-500 mt-2 text-lg">Manage your research protocols and track their status.</p>
            </div>
            <a href="{{ route('submit') }}" class="group flex items-center gap-3 bg-[#8B0000] text-white px-6 py-3.5 rounded-xl font-bold shadow-lg shadow-red-900/20 hover:bg-red-800 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                <i class="fas fa-plus-circle text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                <span>New Submission</span>
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Active Protocols -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fas fa-file-medical text-6xl text-brand-primary"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-wider">Active Protocols</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $titles->count() }}</h3>
                    <div class="mt-4 flex items-center gap-2 text-xs font-bold text-green-600 bg-green-50 w-fit px-2 py-1 rounded-lg">
                        <i class="fas fa-arrow-up"></i> Updated today
                    </div>
                </div>
            </div>

            <!-- Pending Reviews -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fas fa-clock text-6xl text-orange-500"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-wider">Pending Review</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">
                        {{ $titles->where('status', 'Pending')->count() }}
                    </h3>
                    <div class="mt-4 flex items-center gap-2 text-xs font-bold text-orange-600 bg-orange-50 w-fit px-2 py-1 rounded-lg">
                        <i class="fas fa-hourglass-half"></i> Awaiting action
                    </div>
                </div>
            </div>

            <!-- Needs Revision -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fas fa-exclamation-circle text-6xl text-red-500"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-wider">Needs Revision</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">
                        {{ $titles->where('status', 'Returned')->count() }}
                    </h3>
                    <div class="mt-4 flex items-center gap-2 text-xs font-bold text-red-600 bg-red-50 w-fit px-2 py-1 rounded-lg">
                        <i class="fas fa-exclamation-triangle"></i> Attention required
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Submissions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                    <i class="fas fa-folder-open text-brand-primary"></i> My Submissions
                </h2>
                <div class="flex gap-2">
                    <button class="p-2 text-slate-400 hover:text-brand-primary transition-colors"><i class="fas fa-search"></i></button>
                    <button class="p-2 text-slate-400 hover:text-brand-primary transition-colors"><i class="fas fa-filter"></i></button>
                </div>
            </div>

            @if($titles->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder-open text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">No Submissions Yet</h3>
                    <p class="text-slate-500 mt-2 mb-6 max-w-sm mx-auto">Start your research journey by submitting your first protocol for review.</p>
                    <a href="{{ route('submit') }}" class="inline-flex items-center gap-2 text-brand-primary font-bold hover:underline">
                        Create Submission <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($titles as $title)
                        <div class="p-6 hover:bg-slate-50 transition-colors group">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                            {{ $title->status === 'Approved' ? 'bg-green-100 text-green-700' : 
                                              ($title->status === 'Returned' ? 'bg-red-100 text-red-700' : 
                                              'bg-orange-100 text-orange-700') }}">
                                            {{ $title->status ?? 'Pending' }}
                                        </span>
                                        <span class="text-xs text-slate-400 font-medium">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $title->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-primary transition-colors line-clamp-1">
                                        {{ $title->Study_Protocol_title }}
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $title->Layman_term }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('manage.files', $title->id) }}" 
                                       class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-lg hover:border-brand-primary hover:text-brand-primary transition-all shadow-sm">
                                        Manage Files
                                    </a>
                                    <button onclick="openModal('{{ $title->id }}')" class="p-2 text-slate-400 hover:text-brand-primary transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for this item -->
                        <div id="modal-{{ $title->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 transition-opacity">
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-[scaleIn_0.2s_ease-out]">
                                <div class="bg-slate-900 p-6 flex justify-between items-center">
                                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                                        <i class="fas fa-info-circle text-brand-primary"></i> Submission Details
                                    </h3>
                                    <button onclick="closeModal('{{ $title->id }}')" class="text-slate-400 hover:text-white transition-colors">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                                <div class="p-8 space-y-6">
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Research Title</label>
                                        <p class="text-lg font-bold text-slate-900 mt-1">{{ $title->Study_Protocol_title }}</p>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Category</label>
                                            <p class="text-sm font-semibold text-slate-700 mt-1">{{ $title->Research_Category }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Submission Date</label>
                                            <p class="text-sm font-semibold text-slate-700 mt-1">{{ $title->created_at->format('F d, Y') }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Abstract / Layman Terms</label>
                                        <div class="mt-2 p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-600 leading-relaxed max-h-40 overflow-y-auto">
                                            {{ $title->Layman_term }}
                                        </div>
                                    </div>

                                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                                        <button onclick="closeModal('{{ $title->id }}')" class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50 transition-colors">Close</button>
                                        <a href="{{ route('manage.files', $title->id) }}" class="px-6 py-2.5 rounded-xl bg-brand-primary text-white font-bold shadow-lg hover:bg-red-800 transition-all">
                                            View All Files
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $titles->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById('modal-' + id).classList.add('hidden');
        }
    </script>
</x-user_layout>