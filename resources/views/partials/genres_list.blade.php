<div class="mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Genres</h3>
            <a href="{{ route('admin.genres') }}" class="text-sm text-blue-600 hover:underline">Manage genres</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
            @foreach($genres as $genre)
                <a href="{{ route('books.index', ['genre' => $genre->id]) }}" class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-blue-50 transition-colors border border-gray-100 shadow-sm">
                    <span class="text-sm text-gray-700 truncate">{{ $genre->name ?? $genre->genre_name }}</span>
                    <span class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full text-xs ml-3">
                        {{ $genre->books_count ?? 0 }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>