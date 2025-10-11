<x-admin_layout>

    <div class="mb-8 border-b border-stone-200 dark:border-gray-700 pb-2">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Reports</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Annual reports and analytics for research submissions.</p>
    </div>
    <section>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Submission Trends</h2>
        <div class="bg-white dark:bg-gray-900/50 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Total Submissions Over Time</p>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1">1,250</p>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Last Year</span>
                    <span class="text-green-500 font-medium">+15%</span>
                </div>
            </div>
            <div class="mt-8 h-48">
                <svg fill="none" height="100%" preserveAspectRatio="none" viewBox="0 0 472 150" width="100%"
                    xmlns="http://www.w3.org/2000/svg">
                    <path class="text-primary"
                        d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25"
                        stroke="currentColor" stroke-linecap="round" stroke-width="3"></path>
                    <path
                        d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25V150H0V109Z"
                        fill="url(#chart-gradient)"></path>
                    <defs>
                        <linearGradient gradientUnits="userSpaceOnUse" id="chart-gradient" x1="236" x2="236" y1="0"
                            y2="150">
                            <stop stop-color="#ec1313" stop-opacity="0.2"></stop>
                            <stop offset="1" stop-color="#ec1313" stop-opacity="0"></stop>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <div class="flex justify-around text-xs text-gray-500 dark:text-gray-400 mt-2">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span>
            </div>
        </div>
    </section>
    <section class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Completion Rates</h2>
        <div class="bg-white dark:bg-gray-900/50 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Submission Completion Rates</p>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1">85%</p>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Last Year</span>
                    <span class="text-green-500 font-medium">+5%</span>
                </div>
            </div>
            <div class="mt-8 h-48 grid grid-cols-3 gap-8 items-end">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-full h-full flex items-end">
                        <div class="w-full bg-primary/20 dark:bg-primary/30 rounded" style="height: 80%;"></div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-full h-full flex items-end">
                        <div class="w-full bg-primary/20 dark:bg-primary/30 rounded" style="height: 90%;"></div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">In Progress</p>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-full h-full flex items-end">
                        <div class="w-full bg-primary/20 dark:bg-primary/30 rounded" style="height: 60%;"></div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                </div>
            </div>
        </div>
    </section>
    <section class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">AI Detection Insights</h2>
        <div class="bg-white dark:bg-gray-900/50 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">AI Detection Results</p>
                    <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1">70%</p>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Last Year</span>
                    <span class="text-green-500 font-medium">+10%</span>
                </div>
            </div>
            <div class="mt-8 space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">AI Detected</span>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">60%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-primary h-2.5 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Human Written</span>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">30%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-primary h-2.5 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Under Review</span>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">10%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="bg-primary h-2.5 rounded-full" style="width: 10%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-admin_layout>