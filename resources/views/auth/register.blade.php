<x-layout_auth>

  <div class="signup-container mx-auto">
    <div class="bg-white dark:bg-background-dark/50 shadow-lg rounded-xl p-6 sm:p-10 w-full">
      <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2 text-center">Create an Account</h1>
      <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-6 text-center">Please fill out the details below.</p>
        <div class="flex items-center gap-2 pt-2">
          <input type="checkbox" id="isNotWmsu" name="external_user" value="on" class="rounded text-primary focus:ring-primary" />
          <label for="external_user" class="text-sm font-medium text-gray-700 dark:text-gray-300">Are you NOT from WMSU?</label>
        </div>
      
        {{-- student form --}}
      <form action="{{ route('register.internal') }}" method="POST"  class="space-y-4 sm:space-y-5" id="wmsuSection">
        @csrf
        <!-- Name fields in a grid for better use of width -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label for="FirstName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
            <input type="text" id="FirstName" name="FirstName" required value="{{ old('FirstName') }}"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
          </div>

          <div>
            <label for="MiddleName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Middle Name</label>
            <input type="text" id="MiddleName" name="MiddleName" placeholder="Optional" value="{{ old('MiddleName') }}"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
          </div>

          <div>
            <label for="LastName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
            <input type="text" id="LastName" name="LastName" required value="{{ old('LastName') }}"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
          </div>
        </div>
        <!-- WMSU Section (Default visible) -->
        <div  class="space-y-4 sm:space-y-5 mt-3">
          <!-- WMSU Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">WMSU Email</label>
            <input type="email" id="wmsuEmail" name="email" placeholder="hz20230402@wmsu.edu.ph" value="{{ old('email') }}"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
            <p id="emailError" class="text-red-600 text-sm mt-1 hidden">Email must end with @wmsu.edu.ph</p>
          </div>
            <div>
            <label for="Contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact</label>
            <input type="text" id="Contact" name="Contact" placeholder="09*********" pattern="[0-9]*" inputmode="numeric" maxlength="11" value="{{ old('Contact') }}"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base appearance-none" />
            </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="college" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">College</label>
              <select id="college" name="college" 
                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base">
                <option value="">Select College</option>
                <option value="CCS">College of Computer Studies</option>
                <option value="COE">College of Engineering</option>
                <option value="COED">College of Education</option>
                <option value="CAS">College of Arts and Sciences</option>
              </select>
            </div>

            <div>
              <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
              <select id="department" name="department"
                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base">
                <option value="">Select department</option>
                <option value="CCS">College of Computer Studies</option>
                <option value="COE">College of Engineering</option>
                <option value="COED">College of Education</option>
                <option value="CAS">College of Arts and Sciences</option>
              </select>
            </div>
          </div>

          <!-- Course Dropdown - Centered -->
          <div class="flex justify-center">
            <div class="w-full md:w-1/2">
              <label for="course" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course</label>
              <select id="course" name="course"
                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base">
                <option value="">Select your course</option>
                <option value="BS Computer Science">BS Computer Science</option>
                <option value="BS Information Technology">BS Information Technology</option>
                <option value="BS Education">BS Education</option>
                <option value="BS Engineering">BS Engineering</option>
              </select>
            </div>
          </div>

        </div>



            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
              <input type="password" id="password" name="password" required value="{{ old('password') }}"
                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
            </div>
            
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
              <input type="password" id="password_confirmation" name="password_confirmation" required value="{{ old('password_confirmation') }}"
                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
            </div>
          </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-primary/90 transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base mt-6">
          <span class="material-symbols-outlined text-lg">person_add</span>
          <span>Create Account</span>
        </button>
      </form>

      {{-- external user form --}}
      <form action="{{ route('register.external') }}" method="POST"  class="space-y-4 sm:space-y-5 hidden" id="nonWmsuSection">
        @csrf
        <!-- Name fields in a grid for better use of width -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label for="FirstName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
            <input type="text" id="FirstName" name="FirstName" required value="{{ old('FirstName') }}"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
          </div>

          <div>
            <label for="MiddleName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Middle Name</label>
            <input type="text" id="MiddleName" name="MiddleName" placeholder="Optional" value="{{ old('MiddleName') }}"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
          </div>

          <div>
            <label for="LastName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
            <input type="text" id="LastName" name="LastName" required value="{{ old('LastName') }}"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
          </div>
        </div>

        <!-- Non-WMSU Section (Hidden by default) -->
        <div  class=" space-y-4 sm:space-y-5 mt-3">
        
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
          </div>

          <div>
            <label for="institute" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Institute</label>
            <input type="text" id="institute" name="institute" 
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
          </div>
          <div>
            <label for="contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact</label>
            <input type="text" id="contact" name="contact" placeholder="09*********" pattern="[0-9]*" inputmode="numeric" maxlength="11"
              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base appearance-none" />
          </div>

        </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
              <input type="password" id="password" name="password" required value="{{ old('password') }}"
                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
            </div>
            
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
              <input type="password" id="password_confirmation" name="password_confirmation" required value="{{ old('password_confirmation') }}"
                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-background-dark text-gray-900 dark:text-gray-200 focus:ring-primary focus:border-primary px-3 py-2 text-sm sm:text-base" />
            </div>
          </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-primary/90 transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base mt-6">
          <span class="material-symbols-outlined text-lg">person_add</span>
          <span>Create Account</span>
        </button>
      </form>      
                  <div class="mt-6 text-center">
                <p class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-medium">Login</a></p>
            </div>
    </div>
  </div>
@if ($errors->any())
    <div class="mt-3 bg-red-100 border border-red-400 text-red-700 p-3 rounded">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    {{-- <div class="max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Create Account</h2>
                <p class="text-gray-600">Start your research ethics review process today</p>
            </div>
            
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name:</label>
                    <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Enter your full name" value="{{ old('name') }}" >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name:</label>
                    <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Enter your full name" value="{{ old('name') }}" >
                </div> 
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name:</label>
                    <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Enter your full name" value="{{ old('name') }}" >
                </div>                
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Enter your email" value="{{ old('email') }}" >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Create a password">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Comfirm password">
                </div>
                
                
                <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Create Account</button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-medium">Login</a></p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 p-4 text-center">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-red-800 font-medium">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif    
        @if (session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif    

    </div> --}}
<script>
  const isNotWmsu = document.getElementById('isNotWmsu');
  const wmsuSection = document.getElementById('wmsuSection');
  const nonWmsuSection = document.getElementById('nonWmsuSection');
  const wmsuEmail = document.getElementById('wmsuEmail');
  const emailError = document.getElementById('emailError');
  const form = document.getElementById('signupForm');

  // Toggle visibility of form sections (reversed logic)
  isNotWmsu.addEventListener('change', () => {
    if (isNotWmsu.checked) {
      nonWmsuSection.classList.remove('hidden');
      wmsuSection.classList.add('hidden');
    } else {
      wmsuSection.classList.remove('hidden');
      nonWmsuSection.classList.add('hidden');
    }
  });

  // Validate WMSU Email
  form.addEventListener('submit', (e) => {
    if (!isNotWmsu.checked) { // Only validate if user IS from WMSU
      const email = wmsuEmail.value.trim();
      if (!email.endsWith('@wmsu.edu.ph')) {
        e.preventDefault();
        emailError.classList.remove('hidden');
        wmsuEmail.classList.add('border-red-600');
      } else {
        emailError.classList.add('hidden');
        wmsuEmail.classList.remove('border-red-600');
      }
    }
  });
</script>
</x-layout_auth>