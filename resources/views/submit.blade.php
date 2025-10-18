<x-user_layout>
    <x-first_time_popup/>

      <main class="flex-grow container mx-auto px-4 py-8">

        <div class="max-w-4xl mx-auto">
          <h1 class="text-3xl font-bold text-background-dark dark:text-background-light mb-8">
            Research Submission
          </h1>

          <div class="bg-background-light dark:bg-background-dark/50 rounded-lg p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Study Title -->
              <div>
                <label
                  for="study-title"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Study Protocol Title
                </label>
                <input
                  id="study-title"
                  type="text"
                  placeholder="Enter study protocol title"
                  class="w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                />
              </div>

              <!-- Researcher Contact -->
              <div>
                <label
                  for="contact-number"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Researcher Contact Number
                </label>
                <input
                  id="contact-number"
                  type="tel"
                  placeholder="Enter contact number"
                  class="w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                />
              </div>

              <!-- College -->
              <div>
                <label
                  for="college"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  College or Institution
                </label>
                <select
                  id="college"
                  class="form-select w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                >
                  <option>College Of Agriculture - Green</option>
                  <option>College Of Engineering - Blue</option>
                  <option>College Of Science - Yellow</option>
                </select>
              </div>

              <!-- Department -->
              <div>
                <label
                  for="department"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Department
                </label>
                <select
                  id="department"
                  class="form-select w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                >
                  <option>Select department</option>
                  <option>Department A</option>
                  <option>Department B</option>
                </select>
              </div>

              <div>
                <label
                  for="research-category"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Research Category
                </label>
                <select
                  id="research-category"
                  class="form-select w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                >
                  <option>Select research category</option>
                  <option>Category X</option>
                  <option>Category Y</option>
                </select>
              </div>

              <div>
                <label
                  for="fee"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Fee
                </label>
                <select
                  id="fee"
                  class="form-select w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                >
                  <option>WMSU Undergraduate Thesis - 300.00</option>
                  <option>Postgraduate Research - 500.00</option>
                </select>
              </div>

              <!-- Adviser -->
              <div>
                <label
                  for="adviser-name"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Name of Adviser
                </label>
                <input
                  id="adviser-name"
                  type="text"
                  placeholder="Enter adviser's name"
                  class="w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                />
              </div>

              {{-- <div>
                <label
                  for="drive-link"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Google Drive Folder Link <span class="text-primary">*</span>
                </label>
                <input
                  id="drive-link"
                  type="url"
                  required
                  placeholder="Paste Google Drive folder link"
                  class="w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                />
              </div>

              <div>
                <label
                  for="review-date"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Initial Review Tentative Date
                </label>
                <input
                  id="review-date"
                  type="date"
                  class="w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                />
              </div>

              <div>
                <label
                  for="follow-up-date"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Follow-up Date (5 working days)
                </label>
                <input
                  id="follow-up-date"
                  type="date"
                  disabled
                  class="w-full bg-background-light/50 dark:bg-background-dark/50 border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark/60 dark:text-background-light/60"
                />
              </div> --}}

              <div class="md:col-span-2">
                <label
                  for="or-number"
                  class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
                >
                  Official Receipt Number
                </label>
                <input
                  id="or-number"
                  type="text"
                  placeholder="Enter official receipt number"
                  class="w-full bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-3 px-4 text-background-dark dark:text-background-light focus:ring-primary focus:border-primary"
                />
              </div>
            </div>
            </div>

            <!-- File Upload -->
            <div class="md:col-span-2">
              <label
                for="research-files"
                class="block text-sm font-medium text-background-dark/80 dark:text-background-light/80 mb-1"
              >
                Upload Research Files <span class="text-primary">*</span>
              </label>
              <input
                id="research-files"
                type="file"
                name="research_files[]"
                multiple
                class="block w-full text-background-dark dark:text-background-light
                       file:mr-4 file:py-3 file:px-6 file:rounded-lg
                       file:border-0 file:text-sm file:font-semibold
                       file:bg-primary file:text-white
                       hover:file:bg-primary/90 transition
                       bg-background-light dark:bg-background-dark border border-background-dark/20 dark:border-background-light/20 rounded-lg py-2 px-3"
              />
            </div>

            <div class="flex justify-end pt-4">
              <button
                class="bg-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-primary/90 focus:ring-2 focus:ring-offset-2 focus:ring-primary"
              >
                Submit Research
              </button>
            </div>
        </div>
       
      </main>

</x-user_layout>
