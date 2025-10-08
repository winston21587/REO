<x-layout_auth>
    @if (!request('email'))
        <p class="p-4 bg-red-300 rounded mb-5 text-center" >Invalid access!</p>
    @endif
        <div class="max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-8">

            
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Verification</h2>
                <p class="text-gray-600">Enter the code sent to {{ request('email') }}</p>
            </div>
            
            <form action="{{ route('verify.submit') }}" method="POST" class="space-y-6">
            @csrf        
            <input type="hidden" name="email" value="{{ request('email') }}">        
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                    <input type="text" name="code" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition" placeholder="Enter your code here">
                </div>
                
                <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Verify</button>
            </form>
            
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