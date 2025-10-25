<x-layout>
    <div class="container-fluid py-8">
        <div class="page-header">
            <h1 class="page-title">Manage Books</h1>
            <p class="page-description">View and manage all books in the system</p>
        </div>

        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">All Books</h2>
                <a href="{{ route('books.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i> Add New Book
                </a>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table-header">Title</th>
                                <th class="table-header">Author</th>
                                <th class="table-header">Genre</th>
                                <th class="table-header">Added By</th>
                                <th class="table-header">Created</th>
                                <th class="table-header">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($books as $book)
                                <tr>
                                    <td class="table-cell">
                                        <div class="flex items-center">
                                            <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="h-10 w-8 object-cover rounded mr-3">
                                            {{ $book->title }}
                                        </div>
                                    </td>
                                    <td class="table-cell">{{ $book->author }}</td>
                                    <td class="table-cell">
                                        <span class="badge badge-blue">{{ $book->genre->genre_name }}</span>
                                    </td>
                                    <td class="table-cell">{{ $book->user->name }}</td>
                                    <td class="table-cell">{{ $book->created_at->format('M d, Y') }}</td>
                                    <td class="table-cell">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('books.show', $book) }}" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this book?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="table-cell text-center py-8">
                                        <div class="text-gray-500">
                                            <i class="fas fa-book text-4xl mb-4"></i>
                                            <p>No books found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($books->hasPages())
                    <div class="mt-6">
                        {{ $books->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>