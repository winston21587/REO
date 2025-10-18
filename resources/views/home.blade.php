<x-user_layout>
    <x-first_time_popup/>
        <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <h2
            class="text-3xl font-bold text-background-dark dark:text-background-light mb-8"
          >
            My Submissions
          </h2>


          <div class="grid gap-6 title_wrapper">
            <div class=" title_box bg-background-light dark:bg-background-dark/50 rounded-lg shadow-md overflow-hidden border border-background-dark/10 dark:border-background-light/10">
              <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4">
                  <p class="text-sm text-background-dark/60 dark:text-background-light/60">
                    APPOINTMENT: Oct 25, 2024
                  </p>
                  <div
                    class="mt-2 sm:mt-0 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-semibold"
                  >
                    <span class="material-symbols-outlined text-base"
                      >history</span
                    >
                    On Revision
                  </div>
                </div>
                <h3
                  class="text-xl font-bold text-background-dark dark:text-background-light"
                >
                  The Impact of Social Media on Youth Mental Health
                </h3>
                <div class="mt-6 flex flex-wrap gap-4">
                  <button onclick="openModal('detail-modal')"
                    class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-primary/90 transition-colors flex-grow sm:flex-grow-0"
                  >
                    View Details
                  </button>
                  <button onclick="openModal('manage-modal')"
                    class="bg-primary/20 dark:bg-primary/30 text-primary font-bold py-2 px-6 rounded-lg hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors flex-grow sm:flex-grow-0"
                  >
                    Manage Files
                  </button>
                </div>
              </div>
            </div>

            {{-- <div
              class="bg-background-light dark:bg-background-dark/50 rounded-lg shadow-md overflow-hidden border border-background-dark/10 dark:border-background-light/10"
            >
              <div class="p-6">
                <div
                  class="flex flex-col sm:flex-row justify-between sm:items-center mb-4"
                >
                  <p
                    class="text-sm text-background-dark/60 dark:text-background-light/60"
                  >
                    APPOINTMENT: Nov 12, 2024
                  </p>
                  <div
                    class="mt-2 sm:mt-0 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-semibold"
                  >
                    <span class="material-symbols-outlined text-base"
                      >rule</span
                    >
                    Selecting Review Type
                  </div>
                </div>
                <h3
                  class="text-xl font-bold text-background-dark dark:text-background-light"
                >
                  A Comparative Analysis of Renewable Energy Sources
                </h3>
                <div class="mt-6 flex flex-wrap gap-4">
                  <button
                    class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-primary/90 transition-colors flex-grow sm:flex-grow-0"
                  >
                    View Details
                  </button>
                  <button
                    class="bg-primary/20 dark:bg-primary/30 text-primary font-bold py-2 px-6 rounded-lg hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors flex-grow sm:flex-grow-0"
                  >
                    Manage Files
                  </button>
                </div>
              </div>
            </div>

            <div
              class="bg-background-light dark:bg-background-dark/50 rounded-lg shadow-md overflow-hidden border border-background-dark/10 dark:border-background-light/10"
            >
              <div class="p-6">
                <div
                  class="flex flex-col sm:flex-row justify-between sm:items-center mb-4"
                >
                  <p
                    class="text-sm text-background-dark/60 dark:text-background-light/60"
                  >
                    APPOINTMENT: Dec 01, 2024
                  </p>
                  <div
                    class="mt-2 sm:mt-0 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/10 text-green-500 text-sm font-semibold"
                  >
                    <span class="material-symbols-outlined text-base"
                      >check_circle</span
                    >
                    Complete
                  </div>
                </div>
                <h3
                  class="text-xl font-bold text-background-dark dark:text-background-light"
                >
                  The Role of Artificial Intelligence in Healthcare
                </h3>
                <div class="mt-6 flex flex-wrap gap-4">
                  <button
                    class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-primary/90 transition-colors flex-grow sm:flex-grow-0"
                  >
                    View Details
                  </button>
                  <button
                    class="bg-primary/20 dark:bg-primary/30 text-primary font-bold py-2 px-6 rounded-lg hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors flex-grow sm:flex-grow-0"
                  >
                    Manage Files
                  </button>
                </div>
              </div>
            </div> --}}
          </div>
        </main>
        <div class="fixed bottom-8 right-8">
          <button onclick="openHelpModal()"
            class="bg-primary text-white rounded-full h-16 w-16 flex items-center justify-center shadow-lg hover:bg-primary/90 transition-colors"
          >
            <span class="material-symbols-outlined text-3xl">help_outline</span>
          </button>
        </div>
        <x-f-a-q />
    <div class="bg-background-light dark:bg-background-dark font-display detail-modal modal hidden">
      <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-2xl m-4 flex flex-col">
          <div class="flex items-center justify-between p-6 pb-3 border-b border-border-light dark:border-border-dark">
            <h2 class="text-xl font-bold text-content-light dark:text-content-dark">
              Submission Details
            </h2>
            <button onclick="closeModal('detail-modal')"
              class=" close-btn text-subtle-light dark:text-subtle-dark hover:bg-primary/10 dark:hover:bg-primary/20 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark">
              <svg class="h-6 w-6" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                <line x1="18" x2="6" y1="6" y2="18"></line>
                <line x1="6" x2="18" y1="6" y2="18"></line>
              </svg>  
            </button>
          </div>

          <div>
            <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
              <div class="space-y-6">
                <h3 class="text-lg font-semibold text-content-light dark:text-content-dark">
                  Research Information
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Research Title</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark">
                      The Impact of Social Media on Youth Mental Health
                    </p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Research Category</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark">
                      Psychology
                    </p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Official Receipt No.</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark">
                      OR-2024-015
                    </p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Review Type</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark">
                      Full Board Review
                    </p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Revision Type</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark">
                      Major Revision
                    </p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Appointed Date</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark">
                      Oct 25, 2024
                    </p>
                  </div>

                  <div class="sm:col-span-2 px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Appointed Details</p>
                    <p class="mt-1 text-sm text-content-light dark:text-content-dark">
                      10:00 AM - 11:00 AM · Room 102 · Please bring two printed copies of the manuscript.
                    </p>
                  </div>
                </div>

                <div class="flex justify-end gap-3">
                  <button onclick="closeModal('detail-modal')"
                    class="bg-primary/10 text-primary font-semibold px-4 py-2 rounded-lg hover:bg-primary/20 transition-colors">
                    Close
                  </button>
                </div>
              </div>
            </div>
          </div>
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
                <button class="bg-primary text-white font-semibold px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                  Upload New Version
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-user_layout>

<script>
  function openHelpModal(){
    const helpModal = document.querySelector('.faq-modal');
    helpModal.classList.remove('hidden');
  }
  function closeHelpModal(){
    const helpModal = document.querySelector('.faq-modal');
    helpModal.classList.add('hidden');
  }

    function openModal(modalid) {
    document.querySelector("." + modalid).classList.remove("hidden");
  }
  function closeModal(modalid) {
    document.querySelector("." + modalid).classList.add("hidden");
  }
</script>