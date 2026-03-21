<x-reviewer_layout>
    <div class="max-w-7xl mx-auto space-y-8 animate-[fadeInUp_0.5s_ease-out]">
        
        <!-- Header -->
        <div class="flex flex-col flex-wrap gap-4 pb-6 border-b border-slate-200">
            <h1 class="text-3xl font-extrabold text-slate-900 font-heading tracking-tight">Assigned Protocols</h1>
            <p class="text-slate-500 mt-2 text-sm">Review the research protocols assigned to you by the administrative oversight committee.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="p-6">Research Title</th>
                            <th class="p-6">Category</th>
                            <th class="p-6">Review Type</th>
                            <th class="p-6">Submission Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($titles as $title)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="p-6">
                                <p class="font-bold text-slate-800 text-base max-w-sm truncate" title="{{ $title->Study_Protocol_title }}">
                                    {{ $title->Study_Protocol_title }}
                                </p>
                            </td>
                            <td class="p-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $title->Research_Category ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="p-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                    {{ $title->Review_Type ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="p-6 text-sm text-slate-500">
                                {{ $title->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-slate-500">
                                <i class="fas fa-folder-open text-4xl text-slate-300 mb-4 block"></i>
                                <span class="block font-medium">No assigned protocols found</span>
                                <span class="block text-sm text-slate-400 mt-1">You currently have no tasks assigned by the administrators.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-reviewer_layout>
