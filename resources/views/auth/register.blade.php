<x-layout_auth>

    {{-- <div class="register_container">
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <h1 class="text-2xl font-bold mb-4 text-white">Register</h1>
            <input type="email" name="email" placeholder="Email" class="input_field rounded-md mb-2 p-3 w-1/2" required value="{{ old('email') }}">
            <input type="name" name="name" placeholder="Name" class="input_field rounded-md mb-2 p-3 w-1/2" required value="{{ old('name') }}">
            <input type="password" name="password" placeholder="Password" class="input_field rounded-md mb-4 p-3 w-1/2" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="input_field rounded-md mb-4 p-3 w-1/2" required>
            <button type="submit" class="btn w-full">Sign Up</button>
            <p class="mt-4 text-white">Already have an account? <a href="{{ route('login') }}" class="text-yellow-300 underline">Login</a></p>
        </form>
    </div> --}}

        <div class="max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-8">
            {{-- <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">REOC Portal</h1>
                <p class="text-gray-600">Research Ethics Oversight Committee</p>
            </div> --}}
            
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Create Account</h2>
                <p class="text-gray-600">Start your research ethics review process today</p>
            </div>
            
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Enter your full name">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Enter your email">
                </div>
                
                {{-- <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Enter your phone number">
                </div> --}}
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Create a password">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Comfirm password">
                </div>
                
                {{-- <div class="flex items-center">
                    <input type="checkbox" id="terms" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="terms" class="ml-2 text-sm text-gray-700">I agree to the <a href="#" class="text-red-600 hover:text-red-700">Terms and Conditions</a></label>
                </div> --}}
                
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

    </div>

</x-layout_auth>