<x-layout>
    <div class="container-fluid py-8">
        <div class="page-header">
            <h1 class="page-title">Manage Genres</h1>
            <p class="page-description">View and manage book genres</p>
        </div>

        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">All Genres</h2>
                <button class="btn-primary" onclick="document.getElementById('addGenreModal').classList.remove('hidden')">
                    <i class="fas fa-plus mr-2"></i> Add New Genre
                </button>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table-header">Name</th>
                                <th class="table-header">Books Count</th>
                                <th class="table-header">Created</th>
                                <th class="table-header">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($genres as $genre)
                                <tr>
                                    <td class="table-cell">{{ $genre->genre_name }}</td>
                                    <td class="table-cell">{{ $genre->books_count }}</td>
                                    <td class="table-cell">{{ $genre->created_at->format('M d, Y') }}</td>
                                    <td class="table-cell">
                                        <div class="flex items-center space-x-3">
                                            <button class="text-blue-600 hover:text-blue-800" onclick="editGenre({{ $genre->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($genre->books_count === 0)
                                                <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this genre?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="table-cell text-center py-8">
                                        <div class="text-gray-500">
                                            <i class="fas fa-tags text-4xl mb-4"></i>
                                            <p>No genres found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($genres->hasPages())
                    <div class="mt-6">
                        {{ $genres->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Add Genre Modal -->
        <div id="addGenreModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900">Add New Genre</h3>
                    <form action="{{ route('admin.genres.store') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="mb-4">
                            <label for="genre_name" class="form-label">Genre Name</label>
                            <input type="text" name="genre_name" id="genre_name" class="form-input" required>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" class="btn-secondary" onclick="document.getElementById('addGenreModal').classList.add('hidden')">
                                Cancel
                            </button>
                            <button type="submit" class="btn-primary">
                                Add Genre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>