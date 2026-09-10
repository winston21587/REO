<div class="overflow-x-auto w-full">
    <table class="w-full text-left border-collapse min-w-[700px]">
        <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                <th class="py-4 px-6">Administrator / Role</th>
                <th class="py-4 px-6">Contact Email</th>
                <th class="py-4 px-6">Affiliation & Label</th>
                <th class="py-4 px-6">Account Status</th>
                <th class="py-4 px-6 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($users as $user)
            <tr class="hover:bg-slate-50/70 transition-colors">
                <!-- Name / Role -->
                <td class="py-4 px-6">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-[#8B0000] border border-slate-200 flex items-center justify-center font-bold text-sm shrink-0 font-heading">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-slate-900 text-sm truncate">
                                {{ $user->first_name }} {{ $user->middle_name ? $user->middle_name . ' ' : '' }}{{ $user->last_name }}
                            </p>
                            <p class="text-xs text-slate-500 truncate mt-0.5">
                                {{ $user->admin?->label ?? 'System Administrator' }}
                            </p>
                        </div>
                    </div>
                </td>

                <!-- Contact Email -->
                <td class="py-4 px-6">
                    <a href="mailto:{{ $user->email }}" class="inline-flex items-center gap-2 text-sm text-slate-700 hover:text-[#8B0000] transition-colors group">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-[#8B0000] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="truncate">{{ $user->email }}</span>
                    </a>
                </td>

                <!-- Affiliation & Label -->
                <td class="py-4 px-6">
                    <div class="flex flex-col gap-0.5">
                        @if(!$user->admin?->external_user)
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-700">Internal</span>
                        @else
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-700">External</span>
                        @endif
                        <span class="text-xs text-slate-500 truncate max-w-[200px]" title="{{ $user->admin?->label ?? 'General Secretariat' }}">
                            {{ $user->admin?->label ?? 'General Secretariat' }}
                        </span>
                    </div>
                </td>

                <!-- Status -->
                <td class="py-4 px-6">
                    @if($user->is_verified)
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Active</span>
                    @elseif($user->email_verified_at)
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600">Deactivated</span>
                    @else
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Pending</span>
                    @endif
                </td>

                <!-- Actions -->
                <td class="py-4 px-6 text-right">
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" 
                                @click.away="open = false" 
                                aria-label="Admin Actions Menu"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-[#8B0000]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 z-20 mt-1.5 w-48 origin-top-right rounded-xl bg-white shadow-lg border border-slate-200/80 py-1 focus:outline-none" 
                             style="display: none;">
                            
                            <!-- View Details -->
                            <button @click="open = false; openViewModal({{ $user->id }})" 
                                    class="w-full text-left px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] flex items-center gap-2.5 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Details
                            </button>

                            <!-- Edit Profile -->
                            <button @click="open = false; openEditModal({{ $user->id }})" 
                                    class="w-full text-left px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] flex items-center gap-2.5 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Profile
                            </button>
                            
                            <!-- Toggle Status -->
                            <button type="button" 
                                    @click="open = false; triggerConfirm(
                                        '{{ $user->is_verified ? 'Deactivate Account' : 'Activate Account' }}', 
                                        'Are you sure you want to {{ $user->is_verified ? 'deactivate' : 'activate' }} {{ $user->first_name }} {{ $user->last_name }}\'s admin account?', 
                                        '{{ $user->is_verified ? 'Deactivate' : 'Activate' }}', 
                                        '{{ route('super_admin.admins.toggle_status', $user->id) }}',
                                        false
                                    )" 
                                    class="w-full text-left px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#8B0000] flex items-center gap-2.5 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                {{ $user->is_verified ? 'Deactivate' : 'Activate' }}
                            </button>

                            <div class="my-1 border-t border-slate-100"></div>

                            <!-- Delete Admin -->
                            <button type="button" 
                                    @click="open = false; triggerConfirm(
                                        'Delete Admin Account', 
                                        'Are you sure you want to delete {{ $user->first_name }} {{ $user->last_name }}\'s account? This administrative action cannot be undone.', 
                                        'Delete Admin', 
                                        '{{ route('super_admin.admins.delete', $user->id) }}',
                                        true
                                    )" 
                                    class="w-full text-left px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 flex items-center gap-2.5 transition-colors">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Admin
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-12 px-6 text-center">
                    <div class="max-w-sm mx-auto space-y-2">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <p class="font-bold text-slate-800 text-sm">No administrators found</p>
                        <p class="text-xs text-slate-500">No admin accounts match your current query or filter criteria.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($users->hasPages())
<div class="p-4 border-t border-slate-200/80 bg-slate-50/50 filter-pagination">
    {{ $users->links() }}
</div>
@endif
