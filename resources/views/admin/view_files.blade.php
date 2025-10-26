<x-admin_layout>
  <main class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-6">
      Files for: {{ $researchTitle->Title ?? 'Untitled Submission' }}
    </h2>

    <div class="grid gap-6">
      @forelse($files as $file)
        <div class="p-4 border rounded-lg flex justify-between items-center bg-gray-50">
          <div>
            <p class="font-semibold">{{ $file->file_name }}</p>
            <p class="text-sm text-gray-500">Uploaded on: {{ $file->created_at->format('M d, Y') }}</p>
          </div>

          <a href="{{ asset('storage/' . $file->file_path) }}" 
             target="_blank"
             class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition">
            View PDF
          </a>
        </div>
      @empty
        <p class="text-gray-600">No files uploaded for this research yet.</p>
      @endforelse
    </div>
  </main>
</x-admin_layout>
