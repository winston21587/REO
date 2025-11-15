<x-user_layout>
    <x-first_time_popup/>

      <main class="flex-grow container mx-auto px-4 py-8">

<form method="POST" action="{{ route('submit.title') }}" enctype="multipart/form-data" class="max-w-4xl mx-auto">
  @csrf
  <h1 class="text-3xl font-bold text-background-dark dark:text-background-light mb-8">
    Research Submission
  </h1>

  <div class="bg-background-light dark:bg-background-dark/50 rounded-lg p-6 space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
      <!-- Study Title -->
      <div>
        <label
          for="Study_Protocol_title"
          class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
        >
          Study Protocol Title
        </label>
        <input
          id="Study_Protocol_title" name="Study_Protocol_title"
          type="text"
          placeholder="Enter study protocol title"
          class="w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
          required
        />
      </div>

      <!-- Research Category -->
      <div>
        <label
          for="Research_Category"
          class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
        >
          Research Category
        </label>
        <select
          id="Research_Category" name="Research_Category"
          class="form-select w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
          required
        >
          <option selected disabled>Select research category</option>
          <option>WMSU Undergraduate Thesis - 300.00</option>
          <option>WMSU Master's Thesis - 700.00</option>
          <option>WMSU Dissertation - 1,500.00</option>
          <option>WMSU Institutionally Funded Research - 2,000.00</option>
          <option>Externally Funded Research / Other Institution - 3,000.00</option>
        </select>
      </div>

      <!-- Adviser -->
      <div>
        <label
          for="Adviser"
          class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
        >
          Name of Adviser
        </label>
        <input
          id="Adviser" name="Adviser"
          type="text"
          placeholder="Enter adviser's name"
          class="w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
          required
        />
      </div>
    </div>
  </div>

<!-- File Upload Section -->
<div class="bg-background-light dark:bg-background-dark/50 rounded-lg p-6 space-y-6 mt-6">
  <h2 class="text-xl font-semibold text-background-dark dark:text-background-light mb-4">
    Required Research Documents
  </h2>

  <div class="space-y-4">

    <!-- 1. Application Form -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Application Form for Research Ethics Review - WMSU-REO-FR-001 (PDF)
      </label>
      <input type="file" name="files[application_form]" accept="application/pdf" required
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light
               file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0
               file:text-sm file:font-semibold file:bg-primary file:text-white
               hover:file:bg-primary/90 transition
               bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

    <!-- 2. Research Protocol -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Research Protocol / Proposal (with page and line numbers, PDF)
      </label>
      <input type="file" name="files[research_protocol]" accept="application/pdf" required
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

    <!-- 3. Technical Review Clearance -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Technical Review Clearance (with panel signatures, PDF)
      </label>
      <input type="file" name="files[technical_clearance]" accept="application/pdf" required
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

    <!-- 4. Data Collection Instruments -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Data Collection Instrument/s (with page and line numbers, PDF)
      </label>
      <input type="file" name="files[data_collection_instruments]" accept="application/pdf" required
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

    <!-- 5. Informed Consent/Assent -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Informed Consent / Assent (with page and line numbers, PDF)
      </label>
      <input type="file" name="files[informed_consent]" accept="application/pdf" required
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

    <!-- 6. Curriculum Vitae -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Curriculum Vitae of Researcher/s (PDF)
      </label>
      <input type="file" name="files[curriculum_vitae]" accept="application/pdf" required
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

    <!-- 7. Completed Study Protocol Assessment Form -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Completed Study Protocol Assessment Form - WMSU-REO-FR-004 (Word)
      </label>
      <input type="file" name="files[study_protocol_form]" accept=".doc,.docx" required
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

    <!-- 8. Completed Informed Consent Assessment Form -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Completed Informed Consent Assessment Form - WMSU-REO-FR-005 (Word)
      </label>
      <input type="file" name="files[informed_consent_form]" accept=".doc,.docx" required
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

    <!-- 9. Completed Exempt Review Assessment Form -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Completed Exempt Review Assessment Form - WMSU-REO-FR-006 (Word)
      </label>
      <input type="file" name="files[exempt_review_form]" accept=".doc,.docx" required
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

    <!-- 10. Supplementary Documents -->
    <div>
      <label class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1">
        Supplementary Documents (NCIP Clearance, MOA, MOU, etc., PDF, optional)
      </label>
      <input type="file" name="files[supplementary_docs][]" accept="application/pdf" multiple
        class=" ai-checkable-file block w-full text-background-dark dark:text-background-light file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90 transition bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3" />
    </div>

  </div>
</div>





  <div id="ai-results-container" class="mt-6 hidden">
    <h3 class="text-xl font-semibold text-background-dark dark:text-background-light mb-4">AI Compliance Check Results:</h3>
    <div id="ai-results" class="prose dark:prose-invert max-w-none rounded-lg border border-gray-300 bg-gray-50 p-4 dark:border-gray-700 dark:bg-background-dark">
        </div>
  </div>

  <div class="flex flex-col items-center gap-4 pt-6 md:flex-row md:justify-end">
      <button type="button" id="ai-check-btn"
              class="w-full rounded-lg bg-blue-600 px-6 py-3 font-bold text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 md:w-auto">
          Check Files with AI
      </button>
      <button type="submit"
              class="w-full rounded-lg bg-primary px-6 py-3 font-bold text-white hover:bg-primary/90 focus:ring-2 focus:ring-primary focus:ring-offset-2 md:w-auto">
          Submit Research
      </button>
  </div>

</form>

       
      </main>

      
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const aiCheckBtn = document.getElementById('ai-check-btn');
        const allFileInputs = document.querySelectorAll('.ai-checkable-file'); // Selects all inputs with the new class
        const resultsContainer = document.getElementById('ai-results-container');
        const resultsDiv = document.getElementById('ai-results');

        aiCheckBtn.addEventListener('click', async () => {
            const formData = new FormData();
            let fileCount = 0;

            // Loop through all designated file inputs
            allFileInputs.forEach(input => {
                if (input.files.length > 0) {
                    for (const file of input.files) {
                        formData.append('research_files[]', file); // Append each file to the same array
                        fileCount++;
                    }
                }
            });

            if (fileCount === 0) {
                alert('Please select at least one file to check.');
                return;
            }

            resultsContainer.classList.remove('hidden');
            resultsDiv.innerHTML = '<p>Checking files with AI... This may take a moment.</p>';
            aiCheckBtn.disabled = true;
            aiCheckBtn.textContent = 'Checking...';

            try {
                const response = await fetch("{{ route('submit.ai_check') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || `Server error: ${response.status}`);
                }
                
                // Style the table that 'marked' will create
                resultsDiv.innerHTML = `<style>
                    #ai-results table { width: 100%; border-collapse: collapse; }
                    #ai-results th, #ai-results td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    #ai-results th { background-color: #f2f2f2; }
                    .dark #ai-results th { background-color: #333; border-color: #555; }
                    .dark #ai-results td { border-color: #555; }
                </style>` + marked.parse(data.feedback);

            } catch (error) {
                resultsDiv.innerHTML = `<p style="color: red;"><strong>Error:</strong> ${error.message}</p>`;
                console.error('AI Check Error:', error);
            } finally {
                aiCheckBtn.disabled = false;
                aiCheckBtn.textContent = 'Check Files with AI';
            }
        });
    });
</script>

</x-user_layout>
