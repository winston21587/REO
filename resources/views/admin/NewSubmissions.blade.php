<x-admin_layout>
    <header class="mb-5 flex gap-24 justify-evenly items-center border-b border-stone-200 dark:border-gray-700 pb-2">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Recent Submissions</h2>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Incomplete Submissions</h2>

    </header>
    <div class=" flex gap-2 justify-between h-full">

        <div
            class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-6 w-full h-full flex flex-col justify-between">

            <div>
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-2">
                    <div class="relative w-1/2">
                        <input
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark focus:outline-none focus:ring-2 focus:ring-primary/50 dark:text-white"
                            placeholder="Search submissions..." type="text" />
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300" for="sort">Sort by:</label>
                        <select
                            class="pr-8 rounded-lg border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark px-3 py-2 focus:outline-none  dark:text-white"
                            id="sort">
                            <option>Submission Date</option>
                            <option>Author Name</option>
                            <option>Status</option>
                        </select>
                    </div>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">

                    @foreach ($submissions as $s)
                        @if(strtolower($s['status']) === 'incomplete')
                            @continue
                        @endif
                    <div class="flex items-center justify-between py-4">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white"> {{$s['title']}} </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">by Dr. Eleanor Vance - 2023-10-26</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <button class="text-sm font-medium text-primary hover:underline">View Files</button>
                            <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-200 rounded-md">
                                {{$s['status']}} </span>
                            <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium text-primary
                             bg-primary/10 hover:bg-primary/20 dark:bg-primary/15 dark:hover:bg-primary/25 border border-transparent
                              hover:border-primary/20 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light
                               dark:focus:ring-offset-background-dark transition" type="button" aria-label="Take action on submission">
                                Action </button>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>


            <div class="mt-6 flex justify-center items-center space-x-2">
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50"
                    disabled="">Previous</button>
                <button class="px-3 py-1 rounded-md text-sm font-medium text-white bg-primary">1</button>
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">2</button>
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">3</button>
                <span class="text-gray-500 dark:text-gray-400">...</span>
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">10</button>
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">Next</button>
            </div>
        </div>

        <div
            class="bg-white dark:bg-background-dark/50 rounded-lg shadow-sm p-6 w-full h-full flex flex-col justify-between">

            <div>
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-2">
                    <div class="relative w-1/2">
                        <input
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark focus:outline-none focus:ring-2 focus:ring-primary/50 dark:text-white"
                            placeholder="Search submissions..." type="text" />
                        <span
                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300" for="sort">Sort by:</label>
                        <select
                            class="pr-8 rounded-lg border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-background-dark px-3 py-2 focus:outline-none  dark:text-white"
                            id="sort">
                            <option>Submission Date</option>
                            <option>Author Name</option>
                            <option>Status</option>
                        </select>
                    </div>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">

                    @foreach ($submissions as $s)
                        @if(strtolower($s['status']) === 'pending' )
                            @continue
                        @endif
                    <div class="flex items-center justify-between py-4">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white"> {{$s['title']}} </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">by Dr. Eleanor Vance - 2023-10-26</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <button class="text-sm font-medium text-primary hover:underline">View Files</button>
                            <span class="px-3 py-1 text-sm font-medium text-white bg-red-500 rounded-md">
                                {{$s['status']}} </span>
                            <button class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium text-primary
                             bg-primary/10 hover:bg-primary/20 dark:bg-primary/15 dark:hover:bg-primary/25 border border-transparent
                              hover:border-primary/20 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light
                               dark:focus:ring-offset-background-dark transition" type="button" aria-label="Take action on submission">
                                Action </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>


            <div class="mt-6 flex justify-center items-center space-x-2">
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50"
                    disabled="">Previous</button>
                <button class="px-3 py-1 rounded-md text-sm font-medium text-white bg-primary">1</button>
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">2</button>
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">3</button>
                <span class="text-gray-500 dark:text-gray-400">...</span>
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">10</button>
                <button
                    class="px-3 py-1 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">Next</button>
            </div>
        </div>




    </div>
</x-admin_layout>