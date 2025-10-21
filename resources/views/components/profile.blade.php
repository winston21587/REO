<script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          primary: "#ea2a2a",
          "background-light": "#f8f6f6",
          "background-dark": "#211111",
          "content-light": "#111827",
          "content-dark": "#f9fafb",
          "subtle-light": "#6b7280",
          "subtle-dark": "#9ca3af",
          "border-light": "#e5e7eb",
          "border-dark": "#374151",
        },
        fontFamily: {
          display: ["Inter", "sans-serif"],
        },
        borderRadius: {
          DEFAULT: "0.25rem",
          lg: "0.5rem",
          xl: "0.75rem",
          full: "9999px",
        },
      },
    },
  };
</script>
<style>
  .accordion-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
  }

  details[open] .accordion-content {
    max-height: 1000px;
  }

  details summary::-webkit-details-marker {
    display: none;
  }
</style>

<div class="bg-background-light dark:bg-background-dark font-display profile-modal hidden">
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-2xl m-4 flex flex-col">
      <div class="flex items-center justify-between p-6 pb-3 border-b border-border-light dark:border-border-dark">
        <h2 class="text-xl font-bold text-content-light dark:text-content-dark">
          Profile
        </h2>
        <button onclick="closeProfModal()"
          class=" close-btn text-subtle-light dark:text-subtle-dark hover:bg-primary/10 dark:hover:bg-primary/20 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark">
          <svg class="h-6 w-6" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
            <line x1="18" x2="6" y1="6" y2="18"></line>
            <line x1="6" x2="18" y1="6" y2="18"></line>
          </svg>
        </button>
      </div>

      <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-gray-700">Full name</label>
            <input readonly name="name" type="text" value="{{ old('name',  Auth::user()->id) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input readonly name="email" type="email" value="{{ old('email',  Auth::user()->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Department</label>
            <input readonly type="text" value="Department of Psychology" class="mt-1 block w-full rounded-md border-gray-200 bg-background-light/50 dark:bg-background-dark/60 shadow-sm sm:text-sm text-content-light dark:text-content-dark">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <input readonly type="text" value="Researcher" class="mt-1 block w-full rounded-md border-gray-200 bg-background-light/50 dark:bg-background-dark/60 shadow-sm sm:text-sm text-content-light dark:text-content-dark">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Contact number</label>
            <input readonly type="text" value="+63 912 345 6789" class="mt-1 block w-full rounded-md border-gray-200 bg-background-light/50 dark:bg-background-dark/60 shadow-sm sm:text-sm text-content-light dark:text-content-dark">
          </div>


        </div>
      </div>

      <div class="p-6 border-t border-border-light dark:border-border-dark flex justify-end gap-3">
        <button onclick="closeProfModal()"
          class="bg-primary/10 text-primary font-semibold px-4 py-2 rounded-lg hover:bg-primary/20 transition-colors">
          Close
        </button>
        <button class="bg-primary text-white font-semibold px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
          <a href="{{ route('settings') }}">Edit profile</a>
        </button>
      </div>
    </div>
  </div>
</div>


<script>
  function openProfModal(){
    const helpModal = document.querySelector('.profile-modal');
    helpModal.classList.remove('hidden');
  }
  function closeProfModal(){
    const helpModal = document.querySelector('.profile-modal');
    helpModal.classList.add('hidden');
  }
</script>