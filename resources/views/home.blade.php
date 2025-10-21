<x-user_layout>
    <x-first_time_popup/>
        <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <h2
            class="text-3xl font-bold text-background-dark dark:text-background-light mb-8"
          >
            My Submissions
          </h2>


<div class="grid gap-6 title_wrapper">
    @forelse($researchTitles as $title)
        <div class="title_box bg-background-light dark:bg-background-dark/50 rounded-lg shadow-md overflow-hidden border border-background-dark/10 dark:border-background-light/10">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4">
                    <p class="text-sm text-background-dark/60 dark:text-background-light/60">
                        Submitted: {{ $title->created_at->format('M d, Y') }}
                    </p>
                    <div
                        class="mt-2 sm:mt-0 inline-flex items-center gap-2 px-3 py-1 rounded-full
                               {{ $title->status === 'Approved' ? 'bg-green-100 text-green-700' : 
                                  ($title->status === 'On Revision' ? 'bg-yellow-100 text-yellow-700' : 'bg-primary/10 text-primary') }}
                               text-sm font-semibold">
                        <span class="material-symbols-outlined text-base">history</span>
                        {{ $title->status ?? 'Pending Review' }}
                    </div>
                </div>

                <h3 class="text-xl font-bold text-background-dark dark:text-background-light">
                    {{ $title->Study_Protocol_title }}
                </h3>

                <p class="text-sm text-background-dark/70 dark:text-background-light/70 mt-2">
                    Adviser: <strong>{{ $title->Adviser }}</strong>
                </p>
                <p class="text-sm text-background-dark/70 dark:text-background-light/70">
                    Category: <strong>{{ $title->Research_Category }}</strong>
                </p>

                <div class="mt-6 flex flex-wrap gap-4">
                    <button  onclick="openDetailModal(
                    '{{ $title->Study_Protocol_title }}','{{ $title->Research_Category }}',
                    '{{ $title->Adviser }}','{{ $title->Official_Receipt_Number }}','{{ $title->status ?? 'Pending Review' }}')"
                        class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-primary/90 transition-colors flex-grow sm:flex-grow-0">
                        View Details
                    </button>
                    <button onclick="openManageModal({{ $title->id }})" 
                        class="bg-primary/20 dark:bg-primary/30 text-primary font-bold py-2 px-6 rounded-lg hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors flex-grow sm:flex-grow-0">
                        Manage Files
                    </button>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center text-gray-500 dark:text-gray-400">No research titles submitted yet.</p>
    @endforelse
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
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-title">–</p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Research Category</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-category">–</p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Adviser</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-adviser">–</p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Review Type</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-status">–</p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Review Type</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark">
                      Pending
                    </p>
                  </div>

                  <div class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5">
                    <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Revision Type</p>
                    <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark">
                      Pending
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

              <ul id="file-list" class="space-y-3">

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
  function openHelpModal()
  { const helpModal = document.querySelector('.faq-modal');
   helpModal.classList.remove('hidden');
  
  }
   function closeHelpModal(){
     const helpModal = document.querySelector('.faq-modal'); helpModal.classList.add('hidden');
    
    }
function openDetailModal(title, category, adviser, receipt, status) {
    const modal = document.querySelector('.detail-modal');
    modal.classList.remove('hidden');

    // Fill modal content dynamically
    modal.querySelector('#modal-title').textContent = title;
    modal.querySelector('#modal-category').textContent = category;
    modal.querySelector('#modal-adviser').textContent = adviser;
    modal.querySelector('#modal-receipt').textContent = receipt;
    modal.querySelector('#modal-status').textContent = status;
}

function openManageModal(researchId) {
    const modal = document.querySelector('.manage-modal');
    modal.classList.remove('hidden');

    // Fetch files dynamically using AJAX
    fetch(`/research/${researchId}/files`)
        .then(response => response.json())
        .then(files => {
            const list = modal.querySelector('#file-list');
            list.innerHTML = ''; // clear previous

            if (files.length === 0) {
                list.innerHTML = `<p class="text-sm text-gray-500">No files uploaded yet.</p>`;
            } else {
                files.forEach(file => {
                    list.innerHTML += `
                        <li class="flex items-start justify-between bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-background-dark/5 dark:border-background-light/5 p-4">
                            <div class="flex-1 min-w-0">
                                <a href="/storage/${file.filepath}" target="_blank" class="block text-sm font-medium text-primary hover:underline truncate">${file.filename}</a>
                                <p class="text-xs text-subtle-light dark:text-subtle-dark mt-1">Uploaded: ${new Date(file.created_at).toLocaleDateString()}</p>
                            </div>
                            <div class="ml-4">
                                <a href="/${file.filepath}" download class="text-sm bg-primary text-white px-3 py-1 rounded-md hover:bg-primary/90">Download</a>
                            </div>
                        </li>`;
                });
            }
        });
}
function closeModal(modalid) { document.querySelector("." + modalid).classList.add("hidden"); }
</script>
