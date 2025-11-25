@if(session()->has('success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 4000)"
         class="fixed bottom-3 right-3 bg-green-500 text-white px-4 py-2 rounded-xl shadow-lg z-50 animate-bounce">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if(session()->has('error'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 4000)"
         class="fixed bottom-3 right-3 bg-red-500 text-white px-4 py-2 rounded-xl shadow-lg z-50 animate-bounce">
        <p>{{ session('error') }}</p>
    </div>
@endif
