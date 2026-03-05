<x-admin_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <div class="flex flex-col md:flex-row justify-between items-end pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Certifications</h1>
                <p class="text-slate-500 mt-2 text-sm">View approved protocols and manage clearance certificates.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                <form action="{{ route('admin.certifications') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search approved protocols..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8B0000] focus:border-transparent w-64 shadow-sm">
                    <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#8B0000]">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100">
            <div class="overflow-x-auto min-h-[400px] overflow-y-visible">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Protocol ID</th>
                            <th class="p-6">Research Title</th>
                            <th class="p-6">Researcher</th>
                            <th class="p-6">Approval Date</th>
                            <th class="p-6">Certificate</th>
                            <th class="p-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($datas as $data)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="p-6">
                                <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                    #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="p-6">
                                <p class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-[#8B0000] transition-colors" title="{{ $data->Study_Protocol_title }}">
                                    {{ $data->Study_Protocol_title }}
                                </p>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                        {{ substr($data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">
                                            {{ $data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'Unknown' }} 
                                            {{ $data->researcher->user->last_name ?? $data->user->last_name ?? '' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400">
                                            {{ $data->researcher->user->email ?? $data->user->email ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <i class="far fa-calendar-check text-green-500"></i>
                                    {{ $data->updated_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="p-6">
                                @php
                                    $certificate = $data->adminFiles->firstWhere('filetype', 'certificate');
                                    $approvalLetter = $data->adminFiles->firstWhere('filetype', 'Approval Letter');
                                @endphp
                                @if($certificate && $approvalLetter)
                                    <span class="text-xs text-green-700 font-bold bg-green-50 px-2 py-1 rounded border border-green-100">
                                        <i class="fas fa-check-circle mr-1"></i> Generated
                                    </span>
                                @else
                                    <span class="text-xs text-orange-500 font-bold bg-orange-50 px-2 py-1 rounded border border-orange-100">
                                        <i class="fas fa-clock mr-1"></i> Pending Generation
                                    </span>
                                @endif
                            </td>
                            <td class="p-6 text-right relative">
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    
                                    <div x-show="open" 
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden"
                                         style="display: none;">
                                        <div class="p-1">
                                            <a href="{{ route('admin.view_files', $data->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                <i class="fas fa-eye w-4"></i> View Files
                                            </a>
                                            @if($certificate && $approvalLetter)
                                                <button onclick="openViewCertificatesModal('{{ asset($certificate->filepath) }}', '{{ asset($approvalLetter->filepath) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-green-600 rounded-lg transition-colors text-left">
                                                    <i class="fas fa-certificate w-4"></i> View Certificates
                                                </button>
                                            @endif
                                            @php
                                                $resName = trim(($data->researcher->user->first_name ?? $data->user->first_name ?? '') . ' ' . ($data->researcher->user->last_name ?? $data->user->last_name ?? ''));
                                            @endphp
                                            <button onclick="openGenerateCertificateModal('{{ $data->id }}', '{{ addslashes($data->Study_Protocol_title) }}', '{{ addslashes($resName) }}')" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-emerald-600 rounded-lg transition-colors text-left">
                                                <i class="fas fa-stamp w-4"></i> Generate Documents
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">
                                <i class="fas fa-award text-4xl mb-4 text-slate-300"></i>
                                <p>No approved protocols found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-slate-100">
                {{ $datas->links() }}
            </div>
        </div>
    </div>
    
    @include('admin.partials.upload_certificate_modal')
    @include('admin.partials.view_certificates_modal')

    @if(session('success'))
    <div id="certToast" class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-medium animate-[fadeInUp_0.4s_ease-out]">
        <i class="fas fa-check-circle text-emerald-200"></i>
        {{ session('success') }}
        <button onclick="document.getElementById('certToast').remove()" class="ml-2 text-emerald-200 hover:text-white">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div id="certErrorToast" class="fixed bottom-6 right-6 z-[200] flex items-center gap-3 bg-red-600 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-medium animate-[fadeInUp_0.4s_ease-out]">
        <i class="fas fa-exclamation-circle text-red-200"></i>
        {{ session('error') }}
        <button onclick="document.getElementById('certErrorToast').remove()" class="ml-2 text-red-200 hover:text-white">&times;</button>
    </div>
    @endif

</x-admin_layout>
