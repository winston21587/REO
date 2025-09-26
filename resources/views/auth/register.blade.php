<x-layout_auth>
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-2 mb-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <div class="register_container">
        <form action="POST">
            @csrf
            <h1 class="text-2xl font-bold mb-4 text-white">Register</h1>
            <input type="email" name="email" placeholder="Email" class="input_field rounded-md mb-2 p-3 w-1/2" required>
            <input type="name" name="name" placeholder="Name" class="input_field rounded-md mb-2 p-3 w-1/2" required>
            <input type="password" name="password" placeholder="Password" class="input_field rounded-md mb-4 p-3 w-1/2" required>
            <input type="password" name="password_comfirmation" placeholder="Comfirm Password" class="input_field rounded-md mb-4 p-3 w-1/2" required>
            <button type="submit" class="btn w-full">Sign Up</button>
            <p class="mt-4 text-white">Already have an account? <a href="{{ route('login') }}" class="text-yellow-300 underline">Login</a></p>
        </form>
    </div>
</x-layout_auth>