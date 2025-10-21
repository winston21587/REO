<x-user_layout>
    <x-first_time_popup/>

      <main class="flex-grow container mx-auto px-4 py-8">

        <form method="POST" action="{{ route('submit.title') }}" enctype="multipart/form-data"  class="max-w-4xl mx-auto">
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
                />
              </div>


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
                >
                  <option selected disabled >Select research category</option>
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

            <div class="flex md:justify-end justify-center pt-4">
              <button type="submit"
                class="bg-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-primary/90 focus:ring-2 focus:ring-offset-2 focus:ring-primary"
              >
                Submit Research
              </button>
            </div>
        
        
          </form>
       
      </main>

</x-user_layout>
