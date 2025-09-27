@if(Auth::check() && !Auth::user()->first_time)
    <div id="termsModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60">
        <div class="bg-white p-6 rounded-lg w-1/2">
            <h2 class="text-xl font-bold mb-4">Welcome to REO!</h2>
            <p class="mb-4">
                Please read and accept our instructions and Terms & Conditions to continue.
            </p>
            <div class="flex justify-end space-x-3">
                <form action="{{ route('accept.terms') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Accept</button>
                </form>
                <button onclick="document.getElementById('termsModal').style.display='none'"
                        class="bg-gray-400 text-white px-4 py-2 rounded">
                    Close
                </button>
            </div>
        </div>
    </div>
@endif

{{-- card carousel with modal component will be added here --}}
