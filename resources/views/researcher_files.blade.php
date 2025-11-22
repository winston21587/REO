<x-user_layout>
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold mb-6 text-primary">
            Manage Files for {{ $researchTitle->Study_Protocol_title }}
        </h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4">
        @foreach($researchTitle->files as $file)
            <div class="p-4 bg-white dark:bg-gray-800 shadow rounded-lg">
                <h3 class="font-semibold text-lg">{{ ucfirst($file->category ?? 'Uncategorized') }}</h3>
                <p class="text-sm text-gray-500">{{ $file->filename }}</p>

                @if($file->filetype === 'pdf')

                    {{-- Show PDF normally --}}
                    <iframe src="{{ asset($file->filepath) }}" 
                        class="w-full h-96 mt-4 border rounded-lg"></iframe>

                @else

                    {{-- Show DOC/DOCX using Google Docs Viewer --}}
                    <iframe 
                        src="https://docs.google.com/gview?url={{ urlencode(asset($file->filepath)) }}&embedded=true"
                        class="w-full h-96 mt-4 border rounded-lg bg-white"
                        frameborder="0">
                    </iframe>

                @endif

                <!-- Replace file -->
                <form action="{{ route('update.file', $researchTitle->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data" 
                      class="mt-4">
                    @csrf
                    <input type="hidden" name="file_id" value="{{ $file->id }}">
                    <label class="block font-semibold mb-2">Replace this file:</label>
                    <input type="file" 
                           name="file" 
                           accept=".pdf,.doc,.docx" 
                           required
                           class="border border-gray-300 p-2 rounded w-full dark:bg-gray-700 dark:text-white">

                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 mt-3 rounded hover:bg-primary/80 transition">
                        Update File
                    </button>
                </form>
            </div>
        @endforeach

        </div>

        <a href="{{ route('home') }}" class="inline-block mt-6 text-primary hover:underline">← Back to Titles</a>
    </main>
</x-user_layout>
