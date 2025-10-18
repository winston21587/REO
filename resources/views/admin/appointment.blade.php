<x-admin_layout>
    <div class="flex flex-col w-full">

        <div class="flex justify-between items-center mb-8 border-b border-stone-200 dark:border-gray-700 pb-2">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Appointment Management</h1>

            <button onclick="openModal('add-appointment-modal')"
                class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] min-w-0 gap-2 pl-4 pr-6">
                <span class="material-symbols-outlined">add</span>
                <span class="truncate">Add Appointment</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <div
                    class="bg-white dark:bg-background-dark p-6 rounded-xl shadow-sm border border-primary/10 dark:border-primary/20">
                    <div class="flex flex-col gap-0.5">
                        <div class="flex items-center p-1 justify-between">
                            <button class="text-stone-800 dark:text-white">
                                <div class="flex size-10 items-center justify-center">
                                    <span class="material-symbols-outlined">chevron_left</span>
                                </div>
                            </button>
                            <p
                                class="text-stone-800 dark:text-white text-base font-bold leading-tight flex-1 text-center">
                                October 2024</p>
                            <button class="text-stone-800 dark:text-white">
                                <div class="flex size-10 items-center justify-center">
                                    <span class="material-symbols-outlined">chevron_right</span>
                                </div>
                            </button>
                        </div>
                        <div class="grid grid-cols-7">
                            <p
                                class="text-stone-500 dark:text-stone-400 text-[13px] font-bold leading-normal tracking-[0.015em] flex h-12 w-full items-center justify-center pb-0.5">
                                S</p>
                            <p
                                class="text-stone-500 dark:text-stone-400 text-[13px] font-bold leading-normal tracking-[0.015em] flex h-12 w-full items-center justify-center pb-0.5">
                                M</p>
                            <p
                                class="text-stone-500 dark:text-stone-400 text-[13px] font-bold leading-normal tracking-[0.015em] flex h-12 w-full items-center justify-center pb-0.5">
                                T</p>
                            <p
                                class="text-stone-500 dark:text-stone-400 text-[13px] font-bold leading-normal tracking-[0.015em] flex h-12 w-full items-center justify-center pb-0.5">
                                W</p>
                            <p
                                class="text-stone-500 dark:text-stone-400 text-[13px] font-bold leading-normal tracking-[0.015em] flex h-12 w-full items-center justify-center pb-0.5">
                                T</p>
                            <p
                                class="text-stone-500 dark:text-stone-400 text-[13px] font-bold leading-normal tracking-[0.015em] flex h-12 w-full items-center justify-center pb-0.5">
                                F</p>
                            <p
                                class="text-stone-500 dark:text-stone-400 text-[13px] font-bold leading-normal tracking-[0.015em] flex h-12 w-full items-center justify-center pb-0.5">
                                S</p>
                            <button
                                class="h-12 w-full text-stone-400 dark:text-stone-500 text-sm font-medium leading-normal col-start-3">
                                <div class="flex size-full items-center justify-center rounded-full">30
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal col-start-4">
                                <div class="flex size-full items-center justify-center rounded-full">1</div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">2</div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">3</div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">4</div>
                            </button>
                            <button class="h-12 w-full text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full bg-primary">
                                    5</div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">6</div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">7</div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">8</div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">9</div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">10
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">11
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">12
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">13
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">14
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">15
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">16
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">17
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">18
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">19
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">20
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">21
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">22
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">23
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">24
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">25
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">26
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">27
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">28
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">29
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">30
                                </div>
                            </button>
                            <button
                                class="h-12 w-full text-stone-800 dark:text-white text-sm font-medium leading-normal">
                                <div class="flex size-full items-center justify-center rounded-full">31
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="flex flex-col gap-4">
                    <h3 class="text-2xl font-bold text-stone-800 dark:text-white">Upcoming Appointments</h3>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-4 bg-white dark:bg-background-dark p-4 rounded-xl shadow-sm border border-primary/10 dark:border-primary/20 min-h-[72px] justify-between">
                            <div class="flex-1">
                                <ul class="text-stone-800 dark:text-white text-sm font-medium leading-normal space-y-1">
                                    <li class="line-clamp-1">AI Ethics Framework - 11:30 AM</li>

                                </ul>
                            </div>
                             <div class="shrink-0 flex items-center gap-4">
                                 <button class="text-primary text-sm font-medium leading-normal">Manage</button>

                             </div>
                         </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Appointment Modal (UI-only, HTML + Tailwind) -->
<div class="bg-background-light dark:bg-background-dark font-display add-appointment-modal modal hidden">
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-2xl m-4 flex flex-col">
      <div class="flex items-center justify-between p-6 pb-3 border-b border-border-light dark:border-border-dark">
        <h2 class="text-xl font-bold text-content-light dark:text-content-dark">Add Appointment</h2>
        <button onclick="closeModal('add-appointment-modal')"
          class="close-btn text-subtle-light dark:text-subtle-dark hover:bg-primary/10 dark:hover:bg-primary/20 rounded-full p-2 focus:outline-none">
          <svg class="h-6 w-6" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
            <line x1="18" x2="6" y1="6" y2="18"></line>
            <line x1="6" x2="18" y1="6" y2="18"></line>
          </svg>
        </button>
      </div>

      <div class="p-6 space-y-4 overflow-y-auto max-h-[75vh]">
        <form class="space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-subtle-light dark:text-subtle-dark">Research Title <span class="text-red-500">*</span></label>
              <select class="mt-1 block w-full rounded-md border-gray-200 bg-background-light/50 dark:bg-background-dark/60 text-content-light dark:text-content-dark p-2 sm:text-sm">
                <option>Choose research title...</option>
                <option>Impact of Social Media on Youth Mental Health</option>
                <option>AI Ethics Framework</option>
                <option>Sleep Patterns and Cognitive Performance</option>
              </select>
            </div>



            <div>
              <label class="block text-xs font-semibold text-subtle-light dark:text-subtle-dark">Date <span class="text-red-500">*</span></label>
              <input type="date" class="mt-1 block w-full rounded-md border-gray-200 bg-background-light/50 dark:bg-background-dark/60 p-2 sm:text-sm text-content-light dark:text-content-dark">
            </div>

            <div>
              <label class="block text-xs font-semibold text-subtle-light dark:text-subtle-dark">Time <span class="text-red-500">*</span></label>
              <input type="time" class="mt-1 block w-full rounded-md border-gray-200 bg-background-light/50 dark:bg-background-dark/60 p-2 sm:text-sm text-content-light dark:text-content-dark">
            </div>

            <div>
              <label class="block text-xs font-semibold text-subtle-light dark:text-subtle-dark">Duration</label>
              <input type="text" placeholder="e.g., 30 mins, 1 hour" class="mt-1 block w-full rounded-md border-gray-200 bg-background-light/50 dark:bg-background-dark/60 p-2 sm:text-sm text-content-light dark:text-content-dark">
            </div>

          <div>
            <label class="block text-xs font-semibold text-subtle-light dark:text-subtle-dark">Reason for Appointment <span class="text-red-500">*</span></label>
            <textarea rows="3" placeholder="Brief reason or agenda for the appointment" class="mt-1 block w-full rounded-md border-gray-200 bg-background-light/50 dark:bg-background-dark/60 p-2 sm:text-sm text-content-light dark:text-content-dark"></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
            <div class="flex items-center gap-2">
              <input id="reminder" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
              <label for="reminder" class="text-sm text-subtle-light dark:text-subtle-dark">Send reminder email</label>
            </div>

            <div>
              <label class="block text-xs font-semibold text-subtle-light dark:text-subtle-dark">Add necessary detail</label>
              <input type="text" placeholder="e.g., Bring printed copies / ID required" class="mt-1 block w-full rounded-md border-gray-200 bg-background-light/50 dark:bg-background-dark/60 p-2 sm:text-sm text-content-light dark:text-content-dark">
            </div>
          </div>

          <div class="text-sm text-subtle-light dark:text-subtle-dark">Fields marked with <span class="text-red-500">*</span> are required for scheduling (UI-only).</div>
        </form>
      </div>

      <div class="p-6 border-t border-border-light dark:border-border-dark flex justify-end gap-3">
        <button onclick="closeModal('add-appointment-modal')"
          class="bg-background-light/50 dark:bg-background-dark/60 text-primary font-semibold px-4 py-2 rounded-lg hover:bg-primary/10 transition-colors">
          Cancel
        </button>
        <button onclick="closeModal('add-appointment-modal')"
          class="bg-primary text-white font-semibold px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
          Add Appointment
        </button>
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