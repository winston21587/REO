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
                            <button onclick="openModal('manage-modal')" class="text-sm font-medium text-primary hover:underline">View Files</button>
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
                            <button onclick="openModal('manage-modal')" class="text-sm font-medium text-primary hover:underline">View Files</button>
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

        <!-- Manage Files Modal (UI-only, HTML + Tailwind) -->
    <div class="bg-background-light dark:bg-background-dark font-display manage-modal modal hidden">
      <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-2xl m-4 flex flex-col">
          <div class="flex items-center justify-between p-6 pb-3 border-b border-border-light dark:border-border-dark">
            <h2 class="text-xl font-bold text-content-light dark:text-content-dark">
              Manage Files
            </h2>
            <button onclick="closeModal('manage-modal')"
              class="close-btn text-subtle-light dark:text-subtle-dark hover:bg-primary/10 dark:hover:bg-primary/20 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark">
              <svg class="h-6 w-6" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                <line x1="18" x2="6" y1="6" y2="18"></line>
                <line x1="6" x2="18" y1="6" y2="18"></line>
              </svg>
            </button>
          </div>

          <div>
            <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
              <h3 class="text-lg font-semibold text-content-light dark:text-content-dark">Files (click to open)</h3>

              <ul class="space-y-3">
                <li class="flex items-start justify-between bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5 p-4">
                  <div class="flex-1 min-w-0">
                    <a href="#" class="block text-sm font-medium text-primary hover:underline truncate">sample_draft.docx</a>
                    <p class="text-xs text-subtle-light dark:text-subtle-dark mt-1">Uploaded: Oct 22, 2024 · 560 KB</p>

                    <!-- indicate no revisions -->
                    <div class="mt-3 text-xs italic text-subtle-light dark:text-subtle-dark">
                      No revisions yet — not revised or changed yet.
                    </div>
                  </div>

                  <div class="ml-4 flex-shrink-0 flex flex-col items-end gap-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200">Not revised</span>
                    <a href="#" class="text-sm bg-primary text-white px-3 py-1 rounded-md hover:bg-primary/90">Download</a>
                  </div>
                </li>
                <li class="flex items-start justify-between bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5 p-4">
                  <div class="flex-1 min-w-0">
                    <a href="#" class="block text-sm font-medium text-primary hover:underline truncate">manuscript_final_v3.pdf</a>
                    <p class="text-xs text-subtle-light dark:text-subtle-dark mt-1">Uploaded: Oct 20, 2024 · 1.2 MB</p>

                    <details class="mt-3 bg-background-light/20 dark:bg-background-dark/30 rounded-md p-3">
                      <summary class="text-xs font-semibold text-subtle-light dark:text-subtle-dark cursor-pointer">Revision history</summary>
                      <ul class="mt-2 space-y-2">
                        <li>
                          <a href="#" class="text-sm text-content-light dark:text-content-dark hover:underline">v3 — Oct 20, 2024 · Reviewer updates</a>
                        </li>
                        <li>
                          <a href="#" class="text-sm text-content-light dark:text-content-dark hover:underline">v2 — Oct 08, 2024 · Author revision</a>
                        </li>
                        <li>
                          <a href="#" class="text-sm text-content-light dark:text-content-dark hover:underline">v1 — Sep 30, 2024 · Initial upload</a>
                        </li>
                      </ul>
                    </details>
                  </div>

                  <div class="ml-4 flex-shrink-0 flex flex-col items-end gap-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary">Rev 3</span>
                    <a href="#" class="text-sm bg-primary text-white px-3 py-1 rounded-md hover:bg-primary/90">Download</a>
                  </div>
                </li>
                <li class="flex items-start justify-between bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5 p-4">
                  <div class="flex-1 min-w-0">
                    <a href="#" class="block text-sm font-medium text-primary hover:underline truncate">consent_form_signed.pdf</a>
                    <p class="text-xs text-subtle-light dark:text-subtle-dark mt-1">Uploaded: Sep 28, 2024 · 230 KB</p>

                    <details class="mt-3 bg-background-light/20 dark:bg-background-dark/30 rounded-md p-3">
                      <summary class="text-xs font-semibold text-subtle-light dark:text-subtle-dark cursor-pointer">Revision history</summary>
                      <ul class="mt-2 space-y-2">
                        <li><a href="#" class="text-sm text-content-light dark:text-content-dark hover:underline">v1 — Sep 28, 2024 · Signed copy</a></li>
                      </ul>
                    </details>
                  </div>

                  <div class="ml-4 flex-shrink-0 flex flex-col items-end gap-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary">Rev 1</span>
                    <a href="#" class="text-sm bg-primary text-white px-3 py-1 rounded-md hover:bg-primary/90">Download</a>
                  </div>
                </li>
                <li class="flex items-start justify-between bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5 p-4">
                  <div class="flex-1 min-w-0">
                    <a href="#" class="block text-sm font-medium text-primary hover:underline truncate">figure_set.zip</a>
                    <p class="text-xs text-subtle-light dark:text-subtle-dark mt-1">Uploaded: Oct 01, 2024 · 4.5 MB</p>

                    <details class="mt-3 bg-background-light/20 dark:bg-background-dark/30 rounded-md p-3">
                      <summary class="text-xs font-semibold text-subtle-light dark:text-subtle-dark cursor-pointer">Revision history</summary>
                      <ul class="mt-2 space-y-2">
                        <li><a href="#" class="text-sm text-content-light dark:text-content-dark hover:underline">v2 — Oct 01, 2024 · Updated figures</a></li>
                        <li><a href="#" class="text-sm text-content-light dark:text-content-dark hover:underline">v1 — Sep 25, 2024 · Initial upload</a></li>
                      </ul>
                    </details>
                  </div>

                  <div class="ml-4 flex-shrink-0 flex flex-col items-end gap-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary">Rev 2</span>
                    <a href="#" class="text-sm bg-primary text-white px-3 py-1 rounded-md hover:bg-primary/90">Download</a>
                  </div>
                </li>
                <!-- New sample file that has NOT been revised yet -->

              </ul>

              <div class="flex justify-end gap-3 pt-2">
                <button onclick="closeModal('manage-modal')"
                  class="bg-primary/10 text-primary font-semibold px-4 py-2 rounded-lg hover:bg-primary/20 transition-colors">
                  Close
                </button>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-admin_layout>

<script>
        function openModal(modalid) {
    document.querySelector("." + modalid).classList.remove("hidden");
  }
  function closeModal(modalid) {
    document.querySelector("." + modalid).classList.add("hidden");
  }
</script>