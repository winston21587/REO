
<x-admin_layout>
    <div class="layout-content-container flex flex-col max-w-full h-full">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6 border-b border-stone-200 dark:border-gray-700 pb-2">
            <div class="flex flex-col gap-1">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Title Revisions</h1>
            </div>

        </div>


<div class="flex flex-col gap-4 justify-between h-full">

  <div class="flex flex-col gap-4">
    <!-- Filter -->
    <div class="flex flex-col md:flex-row gap-3">
      <div class="flex-1">
        <label class="flex flex-col min-w-40 h-12 w-full">
          <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
            <div class="text-[#994d4d] dark:text-gray-400 flex bg-[#f3e7e7] dark:bg-gray-800 items-center justify-center pl-4 rounded-l-lg border-r-0">
              <span class="material-symbols-outlined">search</span>
            </div>
            <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg text-[#1b0e0e] dark:text-white focus:outline-0 focus:ring-0 border-none bg-[#f3e7e7] dark:bg-gray-800 h-full placeholder:text-[#994d4d] dark:placeholder:text-gray-500 px-4 text-base font-normal leading-normal" placeholder="Search by Title, Submitter..." value="" />
          </div>
        </label>
      </div>
      <div class="flex gap-3 flex-wrap items-center">
        <label for="revision_status" class="relative flex items-center h-12 shrink-0 rounded-lg bg-[#f3e7e7] dark:bg-gray-800">
          <div class="flex items-center justify-center pl-3 pr-2 text-[#994d4d] dark:text-gray-400 rounded-l-lg">
            <span class="material-symbols-outlined">filter_list</span>
          </div>
          <select id="revision_status" name="revision_status" class="focus:outline-0 focus:ring-0 appearance-none bg-transparent pl-3 pr-10 text-sm font-medium text-[#1b0e0e] dark:text-white h-12 w-full sm:w-auto min-w-[220px] outline-none border-none">
            <option value="none" selected>Revision: All</option>
            <option value="waiting_for_revision">Waiting for Revision</option>
            <option value="panel_deliberation">Panel Deliberation</option>
            <option value="submission_of_revision">Submission of Revision</option>
            <option value="checking_of_revision">Checking of Revision</option>
          </select>
        </label>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-visible">
      <div class="flex overflow-visible rounded-lg border border-[#e7d0d0] dark:border-gray-700 bg-background-light dark:bg-background-dark">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-[#f3e7e7] dark:bg-gray-800">
              <th class="px-4 py-3 text-[#1b0e0e] dark:text-white text-sm font-medium leading-normal">Title</th>
              <th class="px-4 py-3 text-[#1b0e0e] dark:text-white text-sm font-medium leading-normal">Author</th>
              <th class="px-4 py-3 text-[#1b0e0e] dark:text-white text-sm font-medium leading-normal">Date</th>
              <th class="px-4 py-3 text-[#1b0e0e] dark:text-white text-sm font-medium leading-normal">Revision Status</th>
              <th class="px-4 py-3 text-center text-[#1b0e0e] dark:text-white text-sm font-medium leading-normal">Actions</th>
            </tr>
          </thead>
          <tbody class="overflow-visible divide-y divide-[#e7d0d0] dark:divide-gray-700">
            <!-- Sample Row 1 -->
            <tr class="dark:hover:bg-primary/10 text-start">
              <td class="h-[72px] px-4 py-2 text-[#1b0e0e] dark:text-white text-sm font-normal leading-normal">Study Protocol 001</td>
              <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm font-normal leading-normal">John Doe</td>
              <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm font-normal leading-normal">2025-10-01</td>
              <td class="h-[72px] px-4 py-2 text-sm font-normal leading-normal"><span class="text-yellow-700">Waiting for Revision</span></td>
              <td class="h-[72px] px-4 py-2 text-center">
                <div class="flex justify-center items-center gap-2">
                  <a href="{{ route('admin.file') }}" class="p-2 pb-0 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" aria-label="View details">
                    <span class="material-symbols-outlined text-[#1b0e0e] dark:text-white">visibility</span>
                  </a>
                  <button onclick="openEditModal('Study Protocol 001')" class="p-2 pb-0 rounded-full hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors" aria-label="Edit">
                    <span class="material-symbols-outlined text-primary">edit</span>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Sample Row 2 -->
            <tr class="dark:hover:bg-primary/10 text-start">
              <td class="h-[72px] px-4 py-2 text-[#1b0e0e] dark:text-white text-sm font-normal leading-normal">Study Protocol 002</td>
              <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm font-normal leading-normal">Jane Smith</td>
              <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm font-normal leading-normal">2025-10-05</td>
              <td class="h-[72px] px-4 py-2 text-sm font-normal leading-normal"><span class="text-green-700">Panel Deliberation</span></td>
              <td class="h-[72px] px-4 py-2 text-center">
                <div class="flex justify-center items-center gap-2">
                  <a href="{{ route('admin.file') }}" class="p-2 pb-0 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <span class="material-symbols-outlined text-[#1b0e0e] dark:text-white">visibility</span>
                  </a>
                  <button onclick="openEditModal('Study Protocol 002')" class="p-2 pb-0 rounded-full hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined text-primary">edit</span>
                  </button>
                </div>
              </td>
            </tr>

            <!-- Sample Row 3 -->
            <tr class="dark:hover:bg-primary/10 text-start">
              <td class="h-[72px] px-4 py-2 text-[#1b0e0e] dark:text-white text-sm font-normal leading-normal">Study Protocol 003</td>
              <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm font-normal leading-normal">Alice Johnson</td>
              <td class="h-[72px] px-4 py-2 text-[#994d4d] dark:text-gray-400 text-sm font-normal leading-normal">2025-10-10</td>
              <td class="h-[72px] px-4 py-2 text-sm font-normal leading-normal"><span class="text-red-700">Submission of Revision</span></td>
              <td class="h-[72px] px-4 py-2 text-center">
                <div class="flex justify-center items-center gap-2">
                  <a href="{{ route('admin.file') }}" class="p-2 pb-0 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                    <span class="material-symbols-outlined text-[#1b0e0e] dark:text-white">visibility</span>
                  </a>
                  <button onclick="openEditModal('Study Protocol 003')" class="p-2 pb-0 rounded-full hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined text-primary">edit</span>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Pagination -->
  <div class="flex justify-between items-center mt-4">
    <p class="text-[#994d4d] dark:text-gray-400 text-sm">Showing 1 to 3 of 3 results</p>
    <div class="flex items-center gap-2">
      <button class="p-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 disabled:opacity-50" disabled>
        <span class="material-symbols-outlined text-[#1b0e0e] dark:text-white">chevron_left</span>
      </button>
      <button class="px-4 py-2 rounded-lg text-sm font-medium bg-primary text-white">1</button>
      <button class="p-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20">
        <span class="material-symbols-outlined text-[#1b0e0e] dark:text-white">chevron_right</span>
      </button>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96">
    <h2 class="text-lg font-semibold mb-4">Edit Study Protocol</h2>
    <p id="modalTitle" class="mb-4 text-[#1b0e0e] dark:text-white"></p>

    <div class="mb-4">
      <label class="block text-sm font-medium mb-2 text-[#1b0e0e] dark:text-white">Revision Type</label>
      <select class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:text-white">
        <option value="waiting_for_revision">Waiting for Revision</option>
        <option value="panel_deliberation">Panel Deliberation</option>
        <option value="submission_of_revision">Submission of Revision</option>
        <option value="checking_of_revision">Checking of Revision</option>
      </select>
    </div>

    <button class="w-full p-2 bg-primary text-white rounded-lg hover:bg-primary/90 mb-2">Generate Recommendation Letter</button>
    <button onclick="closeEditModal()" class="w-full p-2 border rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">Cancel</button>
  </div>
</div>



    </div>
</x-admin_layout>

<script>
function openEditModal(title) {
  document.getElementById('modalTitle').textContent = title;
  document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
}
</script>