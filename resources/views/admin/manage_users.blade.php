<x-admin_layout>
    <div class="flex-1">
        <div class="flex justify-between items-center mb-8 border-b border-stone-200 dark:border-gray-700 pb-2">
            <div>
                <h2 class="text-3xl font-bold text-content-light dark:text-content-dark">User Management</h2>
                <p class="text-subtle-light dark:text-subtle-dark mt-1">Manage user accounts
                    .</p>
            </div>
            <button
                class="bg-primary text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined">Add</span>
                <span>Add User</span>
            </button>
        </div>
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined">search</span>
            </div>
            <input
                class="w-full pl-10 pr-4 py-3 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-lg text-content-light dark:text-content-dark placeholder-subtle-light dark:placeholder-subtle-dark focus:ring-primary focus:border-primary"
                placeholder="Search users by name or email..." type="text" />
        </div>
        <div
            class="bg-background-light dark:bg-background-dark rounded-lg border border-border-light dark:border-border-dark overflow-visible">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-border-light dark:border-border-dark">
                        <th class="p-4 font-medium text-content-light dark:text-content-dark">Name</th>
                        <th class="p-4 font-medium text-content-light dark:text-content-dark">Email</th>
                        <th class="p-4 font-medium text-content-light dark:text-content-dark">Status</th>
                        <th class="p-4 font-medium text-content-light dark:text-content-dark">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-border-light dark:border-border-dark last:border-b-0">
                        <td class="p-4 text-content-light dark:text-content-dark">Dr. David Lee</td>
                        <td class="p-4 text-subtle-light dark:text-subtle-dark">david.lee@research.org</td>
                        <td class="p-4"><span
                                class="px-3 py-1 text-sm rounded-full bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>
                        </td>
                        <td class="p-4">
                            <div class="relative group inline-block ">
                                <button
                                    class="p-2 pb-0 rounded-full hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                    aria-label="Edit">
                                    <span class="material-symbols-outlined text-primary">edit</span>
                                    <span
                                        class="overflow-y-visible absolute left-1/2 -translate-x-1/2 -bottom-8 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-gray-500 text-white text-xs rounded px-2 py-1 whitespace-nowrap  ">
                                        Edit
                                    </span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="border-b border-border-light dark:border-border-dark last:border-b-0">
                        <td class="p-4 text-content-light dark:text-content-dark">Dr. David Lee</td>
                        <td class="p-4 text-subtle-light dark:text-subtle-dark">david.lee@research.org</td>
                        <td class="p-4"><span
                                class="px-3 py-1 text-sm rounded-full bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>
                        </td>
                        <td class="p-4">
                            <div class="relative group inline-block ">
                                <button
                                    class="p-2 pb-0 rounded-full hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                    aria-label="Edit">
                                    <span class="material-symbols-outlined text-primary">edit</span>
                                    <span
                                        class="overflow-y-visible absolute left-1/2 -translate-x-1/2 -bottom-8 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-gray-500 text-white text-xs rounded px-2 py-1 whitespace-nowrap  ">
                                        Edit
                                    </span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-border-light dark:border-border-dark last:border-b-0">
                        <td class="p-4 text-content-light dark:text-content-dark">Dr. David Lee</td>
                        <td class="p-4 text-subtle-light dark:text-subtle-dark">david.lee@research.org</td>
                        <td class="p-4"><span
                                class="px-3 py-1 text-sm rounded-full bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300">Active</span>
                        </td>
                        <td class="p-4">
                            <div class="relative group inline-block ">
                                <button
                                    class="p-2 pb-0 rounded-full hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                    aria-label="Edit">
                                    <span class="material-symbols-outlined text-primary">edit</span>
                                    <span
                                        class="overflow-y-visible absolute left-1/2 -translate-x-1/2 -bottom-8 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-gray-500 text-white text-xs rounded px-2 py-1 whitespace-nowrap  ">
                                        Edit
                                    </span>
                                </button>
                            </div>
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
</x-admin_layout>