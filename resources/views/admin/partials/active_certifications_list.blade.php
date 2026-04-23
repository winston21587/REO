<div class="flex flex-col h-full min-h-[400px]">
    <div class="flex-grow">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                    <th class="p-6 w-[25%]">Research Title</th>
                    <th class="p-6">Researcher</th>
                    <th class="p-6 whitespace-nowrap">Approval Date</th>
                    <th class="p-6 whitespace-nowrap">Review Type</th>
                    <th class="p-6 whitespace-nowrap">Status</th>
                    <th class="p-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">

                @forelse($datas as $data)
                    <tr class="hover:bg-slate-50/80 transition-colors group relative" x-data="{ actionDrawerOpen: false }">
                        <td class="p-6">
                            <p class="font-bold text-slate-800 text-sm line-clamp-2 group-hover:text-[#8B0000] transition-colors"
                                title="{{ $data->Study_Protocol_title }}">
                                {{ $data->Study_Protocol_title }}
                            </p>

                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 uppercase flex-shrink-0">
                                    {{ substr($data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700 whitespace-nowrap">
                                        {{ $data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'Unknown' }}
                                        {{ $data->researcher->user->last_name ?? $data->user->last_name ?? '' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">
                                        {{ $data->researcher->user->email ?? $data->user->email ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <i class="far fa-calendar-check text-green-500"></i>
                                {{ $data->updated_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="p-6 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    'Exempt Review' => 'text-emerald-600',
                                    'Expedited Review' => 'text-blue-600',
                                    'Full Board Review' => 'text-amber-600'
                                ];
                            @endphp
                            @if($data->Review_Type && !in_array($data->Review_Type, ['Unassigned', 'N/A']))
                                <div class="flex items-center cursor-default">
                                    <span
                                        class="text-sm font-bold {{ $typeColors[$data->Review_Type] ?? 'text-slate-500' }} tracking-tight leading-tight whitespace-normal">{{ $data->Review_Type }}</span>
                                </div>
                            @elseif($data->Review_Type === 'N/A')
                                <span
                                    class="text-sm font-semibold text-slate-500 italic tracking-tight leading-tight">N/A</span>
                            @else
                                <span class="text-sm font-semibold text-slate-500 italic leading-tight">—</span>
                            @endif
                        </td>
                        <td class="p-6 whitespace-nowrap">
                            @php
                                $certificate = $data->adminFiles->firstWhere('filetype', 'certificate');
                                $approvalLetter = $data->adminFiles->firstWhere('filetype', 'Approval Letter');
                            @endphp
                            @if($certificate && $approvalLetter)
                                <button type="button"
                                    onclick="openViewCertificatesModal('{{ asset($approvalLetter->filepath) }}', '{{ asset($certificate->filepath) }}')"
                                    class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors bg-transparent border-none p-0 cursor-pointer text-left leading-tight"
                                    title="Click to view certificates">
                                    Certified
                                </button>
                            @else
                                <span class="text-sm font-bold text-amber-600 leading-tight">
                                    Pending Generation
                                </span>
                            @endif
                        </td>
                        <td class="p-6 text-right">
                            <button type="button" @click="actionDrawerOpen = true"
                                class="p-2 text-slate-400 hover:text-[#8B0000] hover:bg-red-50 rounded-lg transition-all ml-auto flex items-center justify-center">
                                <i class="fas fa-ellipsis-v text-sm"></i>
                            </button>

                            <!-- Action Slide-Over Drawer -->
                            <template x-teleport="body">
                                <div x-show="actionDrawerOpen" class="fixed inset-0 z-[110] overflow-hidden"
                                    aria-labelledby="slide-over-title" role="dialog" aria-modal="true"
                                    style="display: none;">
                                    <div class="absolute inset-0 overflow-hidden">
                                        <div x-show="actionDrawerOpen" x-transition.opacity
                                            class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                                            @click="actionDrawerOpen = false"></div>
                                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-sm w-full pl-10">
                                            <div x-show="actionDrawerOpen"
                                                x-transition:enter="transform transition ease-out duration-300"
                                                x-transition:enter-start="translate-x-full"
                                                x-transition:enter-end="translate-x-0"
                                                x-transition:leave="transform transition ease-in duration-300"
                                                x-transition:leave-start="translate-x-0"
                                                x-transition:leave-end="translate-x-full"
                                                class="pointer-events-auto w-screen max-w-sm">
                                                <div class="flex h-full flex-col overflow-y-scroll bg-slate-50 shadow-2xl">

                                                    <!-- Drawer Header -->
                                                    <div
                                                        class="px-6 py-6 border-b border-slate-100 flex justify-between items-start bg-slate-50 flex-none text-left">
                                                        <div class="pr-3 w-full">
                                                            <h3 class="font-heading font-extrabold text-lg text-slate-800 leading-tight line-clamp-3"
                                                                title="{{ $data->Study_Protocol_title }}">
                                                                {{ $data->Study_Protocol_title }}
                                                            </h3>

                                                            <div class="mt-3 space-y-1.5">
                                                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                                                    <i
                                                                        class="fas fa-user-circle text-slate-400 w-4 text-center"></i>
                                                                    <span
                                                                        class="font-medium truncate">{{ $data->researcher->user->first_name ?? $data->user->first_name ?? $data->Created_by ?? 'Unknown' }}
                                                                        {{ $data->researcher->user->last_name ?? $data->user->last_name ?? '' }}</span>
                                                                </div>
                                                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                                                    <i
                                                                        class="far fa-calendar-alt text-slate-400 w-4 text-center"></i>
                                                                    <span>{{ $data->updated_at->format('M d, Y') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button @click="actionDrawerOpen = false"
                                                            class="text-slate-400 hover:text-[#8B0000] hover:bg-red-50 transition-colors w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-full mt-0.5">
                                                            <i class="fas fa-times text-lg"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Drawer Actions List -->
                                                    <div class="flex-1 overflow-y-auto p-4 space-y-2">
                                                        <a href="{{ route('admin.view_files', $data->id) }}"
                                                            class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                            <i class="fas fa-eye w-4"></i> View Protocol Files
                                                        </a>

                                                        @if($certificate && $approvalLetter)
                                                            <button type="button"
                                                                onclick="actionDrawerOpen = false; setTimeout(() => openViewCertificatesModal('{{ asset($approvalLetter->filepath) }}', '{{ asset($certificate->filepath) }}'), 300)"
                                                                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-green-600 rounded-lg transition-colors text-left">
                                                                <i class="fas fa-certificate w-4"></i> View Issued Documents
                                                            </button>
                                                        @endif

                                                        <hr class="border-slate-100 my-1">

                                                        <a href="{{ route('admin.certificate.generate_page', $data->id) }}"
                                                            class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-[#8B0000] rounded-lg transition-colors">
                                                            <i class="fas fa-stamp w-4"></i> Document Generation
                                                        </a>

                                                        <hr class="border-slate-100 my-1">

                                                        <!-- Global Revert Phase Button -->
                                                        <form action="{{ route('admin.updateStatus', $data->id) }}" method="POST"
                                                            id="revertPhaseForm-{{ $data->id }}">
                                                            @csrf
                                                            <input type="hidden" name="classification" value="Revert Phase">
                                                            <button type="button"
                                                                onclick="confirmRevertPhase('{{ $data->id }}', {{ json_encode($data->Study_Protocol_title) }}, '{{ $data->Status }}')"
                                                                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-red-600 rounded-lg transition-colors text-left">
                                                                <i class="fas fa-undo-alt w-4 text-center"></i> Step Backward (Undo)
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </template>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400">
                            <i class="fas fa-award text-4xl mb-4 text-slate-300"></i>
                            <p>No approved protocols found matching the selected filters.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($datas->total() > 0)
        <div class="p-6 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500">
            <div>
                Showing <span class="font-bold text-slate-700">{{ $datas->firstItem() ?? 0 }}</span> - <span
                    class="font-bold text-slate-700">{{ $datas->lastItem() ?? 0 }}</span> of <span
                    class="font-bold text-slate-700">{{ $datas->total() }}</span>
            </div>
            <div class="flex gap-2 filter-pagination">
                <!-- Previous Page Link -->
                @if ($datas->onFirstPage())
                    <span class="opacity-50 cursor-not-allowed text-slate-400"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $datas->appends(request()->except('page'))->previousPageUrl() }}"
                        class="text-slate-600 hover:text-[#8B0000] transition-colors"><i class="fas fa-chevron-left"></i></a>
                @endif

                <!-- Next Page Link -->
                @if ($datas->hasMorePages())
                    <a href="{{ $datas->appends(request()->except('page'))->nextPageUrl() }}"
                        class="text-slate-600 hover:text-[#8B0000] transition-colors"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="opacity-50 cursor-not-allowed text-slate-400"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    @endif