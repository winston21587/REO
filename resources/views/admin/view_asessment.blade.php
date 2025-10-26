<x-admin_layout>
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-6 text-primary">
            File
        </h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4">

            <div class="p-4 bg-white dark:bg-gray-800 shadow rounded-lg">
                <h3 class="font-semibold text-lg">STUDY PROTOCOL ASSESSMENT FORM</h3>
                <p class="text-sm text-gray-500">File</p>

                    <!-- Show inline PDF -->
                    <iframe src="{{ asset('src/4-FR.004 Study Protocol  Assessment Forms - Copy.docx') }}" class="w-full h-96 mt-4 border rounded-lg"></iframe>


                <!-- Replace file -->
                <form  method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <input type="hidden" name="file_id" value="">
                    <label class="block font-semibold mb-2">Replace this file:</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx" required
                        class="border border-gray-300 p-2 rounded w-full dark:bg-gray-700 dark:text-white">

                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 mt-3 rounded hover:bg-primary/80 transition">
                        Update File
                    </button>
                </form>
            </div>


        </div>

        <a href="{{ route('admin.Review') }}" class="inline-block mt-6 text-primary hover:underline">← Back to Review</a>
    </main>
</x-admin_layout>
