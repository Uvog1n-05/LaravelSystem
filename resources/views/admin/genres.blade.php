<x-layout>
    <div class="container-fluid py-8">
        <div class="page-header">
            <h1 class="page-title">Manage Genres</h1>
            <p class="page-description">View and manage book genres</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <h2 class="text-lg font-medium text-gray-900">All Genres</h2>
                <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors" onclick="document.getElementById('addGenreModal').classList.remove('hidden')">
                    <i class="fas fa-plus mr-2"></i> Add Genre
                </button>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Books</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($genres as $genre)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        <div class="font-medium">{{ $genre->name ?? $genre->genre_name }}</div>
                                        @if(!empty($genre->description))
                                            <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $genre->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $genre->books_count }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $genre->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        <div class="inline-flex items-center space-x-2 justify-end">
                        <button class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-700 rounded-md hover:bg-gray-100" 
                            onclick='editGenre({{ $genre->id }}, @json($genre->name), @json($genre->description))'>
                                                <i class="fas fa-edit mr-2"></i>Edit
                                            </button>
                                            @if($genre->books_count == 0)
                                                <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-md hover:bg-red-100" onclick="return confirm('Are you sure you want to delete this genre?')">
                                                        <i class="fas fa-trash mr-2"></i>Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-500">Has books</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                                        <i class="fas fa-tags text-4xl mb-4"></i>
                                        <p>No genres found</p>
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
                            <label for="name" class="block text-sm font-medium text-gray-700">Genre Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description (optional)</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Short description of the genre"></textarea>
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

        <!-- Edit Genre Modal -->
        <div id="editGenreModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900">Edit Genre</h3>
                    <form id="editGenreForm" action="" method="POST" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="edit_name" class="block text-sm font-medium text-gray-700">Genre Name</label>
                            <input type="text" name="name" id="edit_name" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label for="edit_description" class="block text-sm font-medium text-gray-700">Description (optional)</label>
                            <textarea name="description" id="edit_description" rows="3" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Short description of the genre"></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" class="btn-secondary" onclick="document.getElementById('editGenreModal').classList.add('hidden')">
                                Cancel
                            </button>
                            <button type="submit" class="btn-primary">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function editGenre(id, name, description) {
            // set form action to PATCH /admin/genres/{id}
            const form = document.getElementById('editGenreForm');
            form.action = '/admin/genres/' + id;

            document.getElementById('edit_name').value = name || '';
            document.getElementById('edit_description').value = description || '';

            document.getElementById('editGenreModal').classList.remove('hidden');
        }
    </script>
</x-layout>