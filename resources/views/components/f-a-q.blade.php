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

<div class="bg-background-light dark:bg-background-dark font-display faq-modal hidden">
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-background-light dark:bg-background-dark rounded-xl shadow-2xl w-full max-w-2xl m-4 flex flex-col">
      <div class="flex items-center justify-between p-6 border-b border-border-light dark:border-border-dark">
        <h2 class="text-xl font-bold text-content-light dark:text-content-dark">
          Frequently Asked Questions
        </h2>
        <button onclick="closeHelpModal()"
          class=" close-btn text-subtle-light dark:text-subtle-dark hover:bg-primary/10 dark:hover:bg-primary/20 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark">
          <svg class="h-6 w-6" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
            <line x1="18" x2="6" y1="6" y2="18"></line>
            <line x1="6" x2="18" y1="6" y2="18"></line>
          </svg>
        </button>
      </div>
      <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">

        
        <details
          class="group rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark transition-colors duration-200">
          <summary class="flex cursor-pointer items-center justify-between p-4 list-none">
            <span class="font-medium text-content-light dark:text-content-dark">How do I submit a file?</span>
            <svg
              class="h-5 w-5 text-subtle-light dark:text-subtle-dark transition-transform duration-300 group-open:rotate-180"
              fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </summary>
          <div class="accordion-content px-4 pb-4">
            <p class="text-subtle-light dark:text-subtle-dark">
              To submit a file, navigate to the 'Submit' section of the
              dashboard. Follow the on-screen instructions to upload your file
              and provide any necessary details about your research. Ensure
              all required fields are completed before submitting.
            </p>
          </div>
        </details>
        <details
          class="group rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark transition-colors duration-200">
          <summary class="flex cursor-pointer items-center justify-between p-4 list-none">
            <span class="font-medium text-content-light dark:text-content-dark">What file types are accepted?</span>
            <svg
              class="h-5 w-5 text-subtle-light dark:text-subtle-dark transition-transform duration-300 group-open:rotate-180"
              fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </summary>
          <div class="accordion-content px-4 pb-4">
            <p class="text-subtle-light dark:text-subtle-dark">
              We accept a wide range of file types including .pdf, .docx,
              .txt, and various data formats like .csv and .xlsx. Please refer
              to the submission guidelines for a complete list of supported
              file types.
            </p>
          </div>
        </details>
        <details
          class="group rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark transition-colors duration-200">
          <summary class="flex cursor-pointer items-center justify-between p-4 list-none">
            <span class="font-medium text-content-light dark:text-content-dark">How long does the review process
              take?</span>
            <svg
              class="h-5 w-5 text-subtle-light dark:text-subtle-dark transition-transform duration-300 group-open:rotate-180"
              fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </summary>
          <div class="accordion-content px-4 pb-4">
            <p class="text-subtle-light dark:text-subtle-dark">
              The review process typically takes between 2 to 4 weeks,
              depending on the volume of submissions and the complexity of the
              research. You will be notified via email as your submission
              progresses through the stages.
            </p>
          </div>
        </details>
        <details
          class="group rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark transition-colors duration-200">
          <summary class="flex cursor-pointer items-center justify-between p-4 list-none">
            <span class="font-medium text-content-light dark:text-content-dark">Can I edit my submission after it's been
              submitted?</span>
            <svg
              class="h-5 w-5 text-subtle-light dark:text-subtle-dark transition-transform duration-300 group-open:rotate-180"
              fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </summary>
          <div class="accordion-content px-4 pb-4">
            <p class="text-subtle-light dark:text-subtle-dark">
              Once a file is submitted, it cannot be edited directly. If you
              need to make changes, please contact our support team with your
              submission ID, and they will assist you with the process.
            </p>
          </div>
        </details>
        <details
          class="group rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark transition-colors duration-200">
          <summary class="flex cursor-pointer items-center justify-between p-4 list-none">
            <span class="font-medium text-content-light dark:text-content-dark">Who can I contact for technical
              support?</span>
            <svg
              class="h-5 w-5 text-subtle-light dark:text-subtle-dark transition-transform duration-300 group-open:rotate-180"
              fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </summary>
          <div class="accordion-content px-4 pb-4">
            <p class="text-subtle-light dark:text-subtle-dark">
              For any technical issues or questions, please reach out to our
              dedicated support team at support@filesubmission.com. We aim to
              respond to all queries within 24 hours.
            </p>
          </div>
        </details>
      
      
      </div>
      <div class="p-6 border-t border-border-light dark:border-border-dark flex justify-end">
        <button onclick="closeHelpModal()"
          class=" close-btn bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark transition-colors duration-200">
          Close
        </button>
      </div>
    </div>
  </div>
</div>