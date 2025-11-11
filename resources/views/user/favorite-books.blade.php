<x-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">My Favorites</h1>
                    <p class="text-sm text-gray-500 mt-1">Your collection of favorite books</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button id="viewToggle" class="p-2 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <div class="relative">
                        <input type="text" id="searchFavorites" placeholder="Search favorites..." 
                            class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Grid View -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($favorites as $book)
                    <div class="flex flex-col h-full bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="relative pt-[140%] rounded-t-lg overflow-hidden group">
                            <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" 
                                class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                            <form action="{{ route('user.favorite.remove', $book->id) }}" method="POST" 
                                  class="absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-sm hover:bg-red-50 group/btn" 
                                        title="Remove from favorites">
                                    <i class="fas fa-heart text-red-500 group-hover/btn:text-red-600"></i>
                                </button>
                            </form>
                        </div>
                        <div class="p-3 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full truncate max-w-[70%]">
                                    {{ $book->genre->genre_name }}
                                </span>
                            </div>
                            <a href="{{ route('books.show', $book) }}" class="block group flex-1">
                                <h3 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 mb-0.5">
                                    {{ $book->title }}
                                </h3>
                                <p class="text-[10px] text-gray-600">By {{ $book->author }}</p>
                            </a>
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <p class="text-[10px] text-gray-500">Added {{ $book->pivot->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="bg-gray-50 rounded-lg p-8">
                            <i class="fas fa-heart text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No favorites yet</h3>
                            <p class="text-gray-600 mb-4">Start adding books to your favorites collection!</p>
                            <a href="{{ route('books.index') }}" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Browse Books
                            </a>
                        </div>
                    </div>
                    
                @endforelse
            </div>

            <!-- Pagination -->
            @if($favorites->hasPages())
                <div class="mt-6">
                    {{ $favorites->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchFavorites').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let cards = document.querySelectorAll('.book-card');
            
            cards.forEach(card => {
                let title = card.querySelector('h3').textContent.toLowerCase();
                let author = card.querySelector('p').textContent.toLowerCase();
                
                if (title.includes(filter) || author.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</x-layout>