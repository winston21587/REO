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
    <div class="login_container w-1/2 outline-1">
        <form action="POST" class="flex flex-col p-10 gap-1.5 justify-center items-center">
            @csrf
            <h1 class="text-2xl font-bold mb-4 text-white">Login</h1>
            <input type="email" name="email" placeholder="Email" class="input_field rounded-md mb-2 p-3 w-1/2" required>
            <input type="password" name="password" placeholder="Password" class="input_field rounded-md mb-4 p-3 w-1/2" required>
            <button type="submit" class="btn w-full">Login</button>
            <p class="mt-4 text-white">Don't have an account? <a href="{{ route('register') }}" class="text-yellow-300 underline">Register</a></p>
        </form>
    </div>
</x-layout_auth>