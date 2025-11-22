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
                  
            @forelse ($pendingSubmissions as $s)
                <div class="flex items-center justify-between py-4">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ $s['Study_Protocol_title'] }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Submitted at: {{ $s['created_at']->format('Y-m-d') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                <button  
                      data-id="{{ $s->id }}"
                    data-title="{{ e($s->Study_Protocol_title) }}"
                    data-category="{{ e($s->Research_Category) }}"
                    data-author="{{ e(($s->author->first_name ?? '') . ' ' . ($s->author->last_name ?? '')) }}"
                    data-email="{{ e($s->author->email ?? '') }}"
                    data-adviser="{{ e($s->Adviser ?? '') }}"
                    data-status="{{ e($s->Status ?? '') }}"
                    data-contact="{{ $s->author->contact ?? '' }}"
                    data-created="{{ $s->created_at ? $s->created_at->format('Y-m-d H:i') : '' }}"               
                              onclick="openDetailsModal(this)"
                    class="bg-primary text-white font-medium py-1.5 px-3 text:sm rounded-lg hover:bg-primary/90 transition-colors flex-grow sm:flex-grow-0">
                    View Details
                </button>
                        <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-200 rounded-md">
                            {{ $s['Status'] }}
                        </span>
                      <button
                        type="button"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium text-primary bg-primary/10 hover:bg-primary/20 border border-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition"
                        data-id="{{ $s->id }}"
                        onclick="openActionModal(this)"
                      >
                        Action
                      </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 py-4 text-center">No pending submissions found.</p>
            @endforelse

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

            @forelse ($incompleteSubmissions as $s)
                <div class="flex items-center justify-between py-4">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ $s['Study_Protocol_title'] }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Submitted at: {{ $s['created_at']->format('Y-m-d') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                <button  
                      data-id="{{ $s->id }}"
                    data-title="{{ e($s->Study_Protocol_title) }}"
                    data-category="{{ e($s->Research_Category) }}"
                    data-author="{{ e(($s->author->first_name ?? '') . ' ' . ($s->author->last_name ?? '')) }}"
                    data-email="{{ e($s->author->email ?? '') }}"
                    data-adviser="{{ e($s->Adviser ?? '') }}"
                    data-status="{{ e($s->Status ?? '') }}"
                    data-contact="{{ $s->author->contact ?? '' }}"
                    data-created="{{ $s->created_at ? $s->created_at->format('Y-m-d H:i') : '' }}"               
                              onclick="openDetailsModal(this)"
                    class="bg-primary text-white font-medium py-1.5 px-3 text:sm rounded-lg hover:bg-primary/90 transition-colors flex-grow sm:flex-grow-0">
                    View Details
                </button>
                        <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-200 rounded-md">
                            {{ $s['Status'] }}
                        </span>
                      <button
                        type="button"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium text-primary bg-primary/10 hover:bg-primary/20 border border-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition"
                        data-id="{{ $s->id }}"
                        onclick="openActionModal(this)"
                      >
                        Action
                      </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 py-4 text-center">No incomplete submissions found.</p>
            @endforelse
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

<!-- Details Modal -->
<div class="bg-background-light dark:bg-background-dark font-display detailsmodal hidden" id="detailsmodal">
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-2xl m-4 flex flex-col">

      <!-- Header -->
      <div class="flex items-center justify-between p-6 pb-3 border-b border-border-light dark:border-border-dark">
        <h2 class="text-xl font-bold text-content-light dark:text-content-dark">
          Submission Details
        </h2>
        <button onclick="closeModal('detailsmodal')"
          class="close-btn text-subtle-light dark:text-subtle-dark hover:bg-primary/10 dark:hover:bg-primary/20 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark">
          <svg class="h-6 w-6" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24"
            xmlns="http://www.w3.org/2000/svg">
            <line x1="18" x2="6" y1="6" y2="18"></line>
            <line x1="6" x2="18" y1="6" y2="18"></line>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div>
        <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
          <div class="space-y-6">
            <h3 class="text-lg font-semibold text-content-light dark:text-content-dark">
              Research Information
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Research Title -->
              <div
                class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-border-light/5 dark:border-border-dark/5">
                <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Research Title</p>
                <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-title"></p>
              </div>

              <!-- Category -->
              <div
                class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-border-light/5 dark:border-border-dark/5">
                <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Research Category</p>
                <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-category"></p>
              </div>

              <!-- Author -->
              <div
                class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-border-light/5 dark:border-border-dark/5">
                <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Author</p>
                <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-author"></p>
              </div>

              <!-- Author Email -->
              <div
                class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-border-light/5 dark:border-border-dark/5">
                <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Author Email</p>
                <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-email"></p>
              </div>

              <!-- Adviser -->
              <div
                class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-border-light/5 dark:border-border-dark/5">
                <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Adviser</p>
                <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-adviser"></p>
              </div>



              <!-- Status -->
              <div
                class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-border-light/5 dark:border-border-dark/5">
                <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Status</p>
                <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-status"></p>
              </div>

              <!-- Contact -->
              <div
                class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-border-light/5 dark:border-border-dark/5">
                <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">contact</p>
                <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-contact"></p>
              </div>

              <!-- Created Date -->
              <div
                class="px-4 py-3 bg-background-light/50 dark:bg-background-dark/60 rounded-lg border border-border-light/5 dark:border-border-dark/5">
                <p class="text-xs text-subtle-light dark:text-subtle-dark uppercase font-semibold">Submitted At</p>
                <p class="mt-1 text-sm font-medium text-content-light dark:text-content-dark" id="modal-created"></p>
              </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                          <!-- View Files -->
                          {{-- onclick value = window.location.href='{{ route('admin.view_files', $s->id) }}'\ --}}
          <button onclick=""
            class="bg-primary text-white font-semibold px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
            View Files
          </button>

              <!-- Close -->
              <button onclick="closeModal('detailsmodal')"
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

<!-- ACTION MODAL -->
<div id="actionModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
  <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-md p-6 space-y-6">
    
    <h2 class="text-xl font-bold text-content-light dark:text-content-dark">
      Take Action on Submission
    </h2>

    <p class="text-sm text-subtle-light dark:text-subtle-dark">
      Choose what you want to do with this submission.
    </p>

    <div class="space-y-4">
      <button 
        onclick="setSubmissionStatus('complete')" 
        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
        Mark as Complete (For Initial Review)
      </button>
{{-- <!-- Appointment Date Picker (hidden until button is clicked) -->
<div id="appointment-section" class="hidden mt-4">
  <label for="appointed_date" class="block text-sm font-medium text-content-light dark:text-content-dark">
    Appointed Date
  </label>
  <input
    type="date"
    id="appointed_date"
    name="appointed_date"
    class="mt-1 w-full border rounded-md px-3 py-2"
  > --}}
      <button 
        onclick="setSubmissionStatus('incomplete')" 
        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
        Mark as Incomplete
      </button>
    </div>

    <button onclick="closeModal('actionModal')" 
      class="w-full mt-4 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2 px-4 rounded-lg">
      Cancel
    </button>
  </div>
</div>

<!-- INCOMPLETE REASON MODAL -->
<div id="incompleteReasonModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
  <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-md p-6 space-y-4">
    <h2 class="text-lg font-bold text-content-light dark:text-content-dark">
      List Missing Requirements
    </h2>

    <textarea id="incompleteReason" rows="4"
      class="w-full border border-border-light dark:border-border-dark rounded-lg p-2 text-sm bg-background-light dark:bg-background-dark text-content-light dark:text-content-dark"
      placeholder="List the missing documents or details here..."></textarea>

    <div class="flex justify-end gap-3">
      <button onclick="closeModal('incompleteReasonModal')" 
        class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold py-2 px-4 rounded-lg">
        Cancel
      </button>

      <button onclick="submitIncompleteReason()" 
        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
        Submit
      </button>
    </div>
  </div>
</div>






</x-admin_layout>
<script>


  function showAppointmentInput() {
    document.getElementById('appointment-section').classList.remove('hidden');
}

function setInitialReview(id) {
    const date = document.getElementById('appointed_date').value;

    if (!date) {
        alert("Please select an appointment date.");
        return;
    }

    fetch(`/submissions/${id}/set-initial-review`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ appointment_date: date })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => console.error(error));
}

function openDetailsModal(button) {
  // get all data-* attributes from the button
  const submission = {
    Study_Protocol_title: button.dataset.title,
    Research_Category: button.dataset.category,
    author_name: button.dataset.author,
    email: button.dataset.email,
    Adviser: button.dataset.adviser,
    Status: button.dataset.status,
    contact: button.dataset.contact,
    created_at: button.dataset.created
  };

  // populate modal fields
  document.getElementById('modal-title').textContent = submission.Study_Protocol_title || '—';
  document.getElementById('modal-category').textContent = submission.Research_Category || '—';
  document.getElementById('modal-author').textContent = submission.author_name || '—';
  document.getElementById('modal-email').textContent = submission.email || '—';
  document.getElementById('modal-adviser').textContent = submission.Adviser || '—';
  document.getElementById('modal-status').textContent = submission.Status || '—';
  document.getElementById('modal-contact').textContent = submission.contact || '—';
  document.getElementById('modal-created').textContent = submission.created_at || '—';

  // show the modal
  document.getElementById('detailsmodal').classList.remove('hidden');
}

function closeModal(id) {
  document.getElementById(id).classList.add('hidden');
}



function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? null;
}

let selectedSubmissionId = null;

function openActionModal(button) {
  selectedSubmissionId = button.dataset.id;
  document.getElementById('actionModal').classList.remove('hidden');
}

function setSubmissionStatus(action) {
  // action: 'complete' or 'incomplete' (we map to DB statuses below)
  if (!selectedSubmissionId) {
    alert('No submission selected.');
    return;
  }

  if (action === 'complete') {
    // map UI action to DB status value
    updateStatus(selectedSubmissionId, 'For Initial Review', null);
    closeModal('actionModal');
  } else if (action === 'incomplete') {
    closeModal('actionModal');
    document.getElementById('incompleteReasonModal').classList.remove('hidden');
  }
}

function submitIncompleteReason() {
  const reason = document.getElementById('incompleteReason').value.trim();
  if (!reason) {
    alert('Please provide a reason for incompleteness.');
    return;
  }

  updateStatus(selectedSubmissionId, 'Incomplete', reason);
  closeModal('incompleteReasonModal');
}

/**
 * updateStatus: POST to server to update status + optional reason
 */
async function updateStatus(id, status, reason = null) {
  const token = getCsrfToken();
  if (!token) {
    console.error('CSRF token not found. Make sure <meta name="csrf-token" content="{{ csrf_token() }}"> is in your <head>.');
    alert('CSRF token missing — unable to send request. Check console for details.');
    return;
  }

  try {
    const res = await fetch(`/admin/update-status/${id}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token
      },
      body: JSON.stringify({ status, reason })
    });

    if (!res.ok) {
      // try to parse JSON error
      let errText = 'Request failed';
      try {
        const errJson = await res.json();
        errText = errJson.message || JSON.stringify(errJson);
      } catch (e) {
        errText = `${res.status} ${res.statusText}`;
      }
      throw new Error(errText);
    }

    const data = await res.json();
    // success - show message then refresh or update UI
    alert(data.message || 'Status updated successfully.');
    // Option A: reload the page to reflect changes
    window.location.reload();
    // Option B: update the DOM to reflect new status without reload (left for you)
  } catch (err) {
    console.error('updateStatus error:', err);
    alert('Error updating status: ' + (err.message || err));
  }
}

function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.add('hidden');
}
</script>

</script>

