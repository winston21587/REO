<x-admin_layout>
    <div class="layout-content-container flex flex-col max-w-full h-full">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6 border-b border-stone-200 dark:border-gray-700 pb-2">
            <div class="flex flex-col gap-1">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">For Initial Review (UI-only)</h1>
            </div>
        </div>

        <div class="flex flex-col gap-4 justify-between h-full">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1">
                        <label class="flex flex-col min-w-40 h-12 w-full">
                            <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                                <div class="text-[#994d4d] dark:text-gray-400 flex bg-[#f3e7e7] dark:bg-gray-800 items-center justify-center pl-4 rounded-l-lg border-r-0">
                                    <span class="material-symbols-outlined">search</span>
                                </div>
                                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg text-[#1b0e0e] dark:text-white focus:outline-0 focus:ring-0 border-none bg-[#f3e7e7] dark:bg-gray-800 h-full placeholder:text-[#994d4d] dark:placeholder:text-gray-500 px-4 text-base font-normal leading-normal"
                                    placeholder="Search by Title, Submitter..." value="" />
                            </div>
                        </label>
                    </div>

                    <div class="flex gap-3 flex-wrap items-center">
                        <label for="review_type" class="relative flex items-center h-12 shrink-0 rounded-lg bg-[#f3e7e7] dark:bg-gray-800">
                            <div class="flex items-center justify-center pl-3 pr-2 text-[#994d4d] dark:text-gray-400 rounded-l-lg">
                                <span class="material-symbols-outlined">filter_list</span>
                            </div>
                            <select id="review_type" name="review_type" class="focus:outline-0 focus:ring-0 appearance-none bg-transparent pl-3 pr-10 text-sm font-medium text-[#1b0e0e] dark:text-white h-12 w-full sm:w-auto min-w-[180px] outline-none border-none">
                                <option value="none" selected>Review: All</option>
                                <option value="Full Review">Full Review</option>
                                <option value="Expedited">Expedited</option>
                                <option value="Exempt">Exempt</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="overflow-visible">
                    <div class="flex overflow-visible rounded-lg border border-[#e7d0d0] dark:border-gray-700 bg-background-light dark:bg-background-dark">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-[#f3e7e7] dark:bg-gray-800">
                                    <th class="px-4 py-3 text-[#1b0e0e] dark:text-white text-sm font-medium">Title</th>
                                    <th class="px-4 py-3 text-[#1b0e0e] dark:text-white text-sm font-medium">Author</th>
                                    <th class="px-4 py-3 text-[#1b0e0e] dark:text-white text-sm font-medium">Date</th>
                                    <th class="px-4 py-3 text-[#1b0e0e] dark:text-white text-sm font-medium">Review Type</th>
                                    <th class="px-4 py-3 text-center text-[#1b0e0e] dark:text-white text-sm font-medium">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="overflow-visible divide-y divide-[#e7d0d0] dark:divide-gray-700">
                                <!-- Example row 0 -->
                                <tr class="dark:hover:bg-primary/10 text-start">
                                    <td class="h-[72px] px-4 py-2 text-[#1b0e0e] dark:text-white text-sm">Impact of Social Media on Youth Mental Health</td>
                                    <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm">Dr. Maria Cruz</td>
                                    <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm">2025-10-20</td>
                                    <td class="h-[72px] px-4 py-2 text-sm">
                                        <div class="flex items-center gap-1"><span class="text-yellow-700">N/A</span></div>
                                    </td>
                                    <td class="h-[72px] px-4 py-2 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <div class="relative group inline-block">
                                                <a href="{{ route('admin.file') }}" class="p-2 pb-0 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" aria-label="View details">
                                                    <span class="material-symbols-outlined text-[#1b0e0e] dark:text-white">visibility</span>
                                                </a>
                                            </div>
                                            <div class="relative group inline-block">
                                                <button onclick="openModal('edit-modal-0')" class="p-2 pb-0 rounded-full hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors" aria-label="Edit">
                                                    <span class="material-symbols-outlined text-primary">edit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Example row 1 -->
                                <tr class="dark:hover:bg-primary/10 text-start">
                                    <td class="h-[72px] px-4 py-2 text-[#1b0e0e] dark:text-white text-sm">AI Ethics Framework for Clinical Trials</td>
                                    <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm">Prof. John Reyes</td>
                                    <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm">2025-10-15</td>
                                    <td class="h-[72px] px-4 py-2 text-sm">
                                        <div class="flex items-center gap-1"><span class="text-yellow-700">Full Review</span></div>
                                    </td>
                                    <td class="h-[72px] px-4 py-2 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <div class="relative group inline-block">
                                                <a href="{{ route('admin.file') }}" class="p-2 pb-0 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" aria-label="View details">
                                                    <span class="material-symbols-outlined text-[#1b0e0e] dark:text-white">visibility</span>
                                                </a>
                                            </div>
                                            <div class="relative group inline-block">
                                                <button onclick="openModal('edit-modal-1')" class="p-2 pb-0 rounded-full hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors" aria-label="Edit">
                                                    <span class="material-symbols-outlined text-primary">edit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Example row 2 -->
                                <tr class="dark:hover:bg-primary/10 text-start">
                                    <td class="h-[72px] px-4 py-2 text-[#1b0e0e] dark:text-white text-sm">Sleep Patterns and Cognitive Performance</td>
                                    <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm">Dr. Liza Santos</td>
                                    <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm">2025-10-10</td>
                                    <td class="h-[72px] px-4 py-2 text-sm">
                                        <div class="flex items-center gap-1"><span class="text-yellow-700">Expedited</span></div>
                                    </td>
                                    <td class="h-[72px] px-4 py-2 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <div class="relative group inline-block">
                                                <a href="{{ route('admin.file') }}" class="p-2 pb-0 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" aria-label="View details">
                                                    <span class="material-symbols-outlined text-[#1b0e0e] dark:text-white">visibility</span>
                                                </a>
                                            </div>
                                            <div class="relative group inline-block">
                                                <button onclick="openModal('edit-modal-2')" class="p-2 pb-0 rounded-full hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors" aria-label="Edit">
                                                    <span class="material-symbols-outlined text-primary">edit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-4">
                <p class="text-[#994d4d] dark:text-gray-400 text-sm">Showing 1 to 3 of 3 results (UI-only)</p>
                <div class="flex items-center gap-2">
                    <button class="p-2 rounded-lg disabled:opacity-50" disabled><span class="material-symbols-outlined">chevron_left</span></button>
                    <button class="px-4 py-2 rounded-lg text-sm font-medium bg-primary text-white">1</button>
                    <button class="p-2 rounded-lg"><span class="material-symbols-outlined">chevron_right</span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reusable Edit Modals for the 3 example rows (UI-only) -->
    <div class="bg-background-light dark:bg-background-dark font-display edit-modal-0 modal hidden">
      <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-md m-4 flex flex-col">
          <div class="flex items-center justify-between p-6 pb-3 border-b border-border-light dark:border-border-dark">
            <h2 class="text-xl font-bold text-content-light dark:text-content-dark">Set Review Type</h2>
            <button onclick="closeModal('edit-modal-0')" class="close-btn p-2 rounded-full">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
            </button>
          </div>
          <div class="p-6 space-y-4">
            <p class="text-sm text-subtle-light">Title</p>
            <p class="text-sm font-medium text-content-light truncate">Impact of Social Media on Youth Mental Health</p>
            <label class="block">
              <span class="text-sm font-medium text-subtle-light">Review Type</span>
              <select id="review_type_0" name="review_type" class="mt-2 block w-full rounded-md p-2">
                <option value="N/A">N/A</option>
                <option value="Full Review">Full Review</option>
                <option value="Expedited">Expedited</option>
                <option value="Exempt">Exempt</option>
              </select>
            </label>
            <div class="text-sm text-subtle-light">This is UI-only. Use your backend to persist changes.</div>
          </div>
          <div class="p-6 border-t flex justify-end gap-3">
            <button onclick="closeModal('edit-modal-0')" class="px-4 py-2 rounded-lg">Cancel</button>
            <button onclick="closeModal('edit-modal-0')" class="px-4 py-2 rounded-lg bg-primary text-white">Save</button>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-background-light dark:bg-background-dark font-display edit-modal-1 modal hidden">
      <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-md m-4 flex flex-col">
          <div class="flex items-center justify-between p-6 pb-3 border-b border-border-light dark:border-border-dark">
            <h2 class="text-xl font-bold text-content-light dark:text-content-dark">Set Review Type</h2>
            <button onclick="closeModal('edit-modal-1')" class="close-btn p-2 rounded-full">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
            </button>
          </div>
          <div class="p-6 space-y-4">
            <p class="text-sm text-subtle-light">Title</p>
            <p class="text-sm font-medium text-content-light truncate">AI Ethics Framework for Clinical Trials</p>
            <label class="block">
              <span class="text-sm font-medium text-subtle-light">Review Type</span>
              <select id="review_type_1" name="review_type" class="mt-2 block w-full rounded-md p-2">
                <option value="N/A">N/A</option>
                <option value="Full Review" selected>Full Review</option>
                <option value="Expedited">Expedited</option>
                <option value="Exempt">Exempt</option>
              </select>
            </label>
            <div class="text-sm text-subtle-light">This is UI-only. Use your backend to persist changes.</div>
          </div>
          <div class="p-6 border-t flex justify-end gap-3">
            <button onclick="closeModal('edit-modal-1')" class="px-4 py-2 rounded-lg">Cancel</button>
            <button onclick="closeModal('edit-modal-1')" class="px-4 py-2 rounded-lg bg-primary text-white">Save</button>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-background-light dark:bg-background-dark font-display edit-modal-2 modal hidden">
      <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-md m-4 flex flex-col">
          <div class="flex items-center justify-between p-6 pb-3 border-b border-border-light dark:border-border-dark">
            <h2 class="text-xl font-bold text-content-light dark:text-content-dark">Set Review Type</h2>
            <button onclick="closeModal('edit-modal-2')" class="close-btn p-2 rounded-full">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
            </button>
          </div>
          <div class="p-6 space-y-4">
            <p class="text-sm text-subtle-light">Title</p>
            <p class="text-sm font-medium text-content-light truncate">Sleep Patterns and Cognitive Performance</p>
            <label class="block">
              <span class="text-sm font-medium text-subtle-light">Review Type</span>
              <select id="review_type_2" name="review_type" class="mt-2 block w-full rounded-md p-2">
                <option value="N/A">N/A</option>
                <option value="Full Review">Full Review</option>
                <option value="Expedited" selected>Expedited</option>
                <option value="Exempt">Exempt</option>
              </select>
            </label>
            <div class="text-sm text-subtle-light">This is UI-only. Use your backend to persist changes.</div>
          </div>
          <div class="p-6 border-t flex justify-end gap-3">
            <button onclick="closeModal('edit-modal-2')" class="px-4 py-2 rounded-lg">Cancel</button>
            <button onclick="closeModal('edit-modal-2')" class="px-4 py-2 rounded-lg bg-primary text-white">Save</button>
          </div>
        </div>
      </div>
    </div>

 <script>
      // minimal modal helpers (UI-only)
      function openModal(name) {
        const modal = document.querySelector('.' + name);
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }
      function closeModal(name) {
        const modal = document.querySelector('.' + name);
        if (!modal) return;
        modal.classList.add('hidden');
        // restore if no other modal open
        if (!document.querySelector('.modal:not(.hidden)')) {
          document.body.style.overflow = '';
        }
      }

      // wire save buttons to update table (UI-only)
      document.addEventListener('DOMContentLoaded', () => {
        [0,1,2].forEach(idx => {
          const saveBtn = document.querySelector('.edit-modal-' + idx + ' button.bg-primary');
          const select = document.getElementById('review_type_' + idx);
          if (saveBtn && select) {
            saveBtn.addEventListener('click', () => {
              // find row and update the ReviewType span text
              const rows = Array.from(document.querySelectorAll('table tbody tr'));
              const row = rows[idx];
              if (row) {
                const span = row.querySelector('span.text-yellow-700');
                if (span) span.textContent = select.value;
              }
            });
          }
        });
      });
    </script>

    </x-admin_layout>