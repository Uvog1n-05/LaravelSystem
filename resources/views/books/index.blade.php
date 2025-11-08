<x-layout>
    <div class="container-fluid py-8">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg mb-12 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h1 class="text-4xl font-bold mb-6">Find your next Book</h1>
                <p class="text-lg text-blue-100 mb-12">Explore our collection of amazing books</p>
                
                <!-- Search and Filters -->
                <form action="{{ route('books.index') }}" method="GET" class="max-w-4xl" x-data="{ showFilters: false }">
                    <div class="space-y-4">
                        <!-- Search Bar -->
                        <div class="flex gap-3">
                            <div class="flex-1 relative">
                                <input type="text" name="search" 
                                    placeholder="Search by title, author, or genre..." 
                                    class="w-full px-6 py-4 text-lg rounded-xl text-gray-700 placeholder-gray-400 bg-white/95 backdrop-blur-sm border-0 focus:ring-2 focus:ring-white shadow-sm"
                                    value="{{ request('search') }}">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                    <i class="fas fa-search text-gray-400 text-lg"></i>
                                </div>
                            </div>
                            <button type="button" 
                                @click="showFilters = !showFilters"
                                :class="{ 'bg-white/30': showFilters }"
                                class="px-4 py-4 bg-white/20 text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-200 shadow-sm flex items-center gap-2">
                                <i class="fas" :class="{ 'fa-filter': !showFilters, 'fa-times': showFilters }"></i>
                                <span class="hidden sm:inline">{{ request()->hasAny(['genre', 'availability', 'sort']) ? 'Filters Active' : 'Filters' }}</span>
                            </button>
                            <button type="submit" class="px-6 py-4 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-indigo-50 transition-all duration-200 shadow-sm text-lg flex items-center gap-2">
                                <i class="fas fa-search sm:mr-2"></i>
                                <span class="hidden sm:inline">Search</span>
                            </button>
                        </div>

                        <!-- Filters Panel -->
                        <div x-show="showFilters" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="bg-white/95 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-white/20">
                            
                            <!-- Filter Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Genre Filter -->
                                <div class="space-y-2">
                                    <label for="genre" class="block text-sm font-semibold text-indigo-900">
                                        <i class="fas fa-books mr-2 text-indigo-500"></i>Genre
                                    </label>
                                    <select name="genre" id="genre" 
                                            class="block w-full rounded-lg bg-gray-50 text-gray-700 border-gray-200 hover:border-indigo-400 focus:border-indigo-500 focus:ring focus:ring-indigo-500/20 transition-all duration-200">
                                        <option value="">All Genres</option>
                                        @foreach($genres as $genre)
                                            <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                                                {{ $genre->genre_name }} ({{ $genre->books_count }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Availability Filter -->
                                <div class="space-y-2">
                                    <label for="availability" class="block text-sm font-semibold text-indigo-900">
                                        <i class="fas fa-book-open mr-2 text-emerald-500"></i>Availability
                                    </label>
                                    <select name="availability" id="availability" 
                                            class="block w-full rounded-lg bg-gray-50 text-gray-700 border-gray-200 hover:border-emerald-400 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all duration-200">
                                        <option value="">All Books</option>
                                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available Now</option>
                                        <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Currently Borrowed</option>
                                    </select>
                                </div>

                                <!-- Sort Order -->
                                <div class="space-y-2">
                                    <label for="sort" class="block text-sm font-semibold text-indigo-900">
                                        <i class="fas fa-sort mr-2 text-violet-500"></i>Sort By
                                    </label>
                                    <select name="sort" id="sort" 
                                            class="block w-full rounded-lg bg-gray-50 text-gray-700 border-gray-200 hover:border-violet-400 focus:border-violet-500 focus:ring focus:ring-violet-500/20 transition-all duration-200">
                                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Newest First</option>
                                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                                        <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Author (A-Z)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Active Filters -->
                            @if(request()->hasAny(['genre', 'availability', 'sort']))
                                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-4">
                                    <span class="text-sm font-semibold text-indigo-900">Active filters:</span>
                                    <div class="flex flex-wrap gap-2">
                                        @if(request('genre'))
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                <i class="fas fa-books text-indigo-500 mr-1.5"></i>
                                                {{ $genres->find(request('genre'))->genre_name }}
                                                <a href="{{ request()->fullUrlWithQuery(['genre' => null]) }}" 
                                                   class="ml-1.5 text-indigo-400 hover:text-indigo-600 transition-colors">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request('availability'))
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <i class="fas fa-book-open text-emerald-500 mr-1.5"></i>
                                                {{ ucfirst(request('availability')) }} Only
                                                <a href="{{ request()->fullUrlWithQuery(['availability' => null]) }}" 
                                                   class="ml-1.5 text-emerald-400 hover:text-emerald-600 transition-colors">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request('sort') && request('sort') != 'created_at')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-violet-50 text-violet-700 border border-violet-100">
                                                <i class="fas fa-sort text-violet-500 mr-1.5"></i>
                                                Sorted by {{ ucfirst(request('sort')) }}
                                                <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}" 
                                                   class="ml-1.5 text-violet-400 hover:text-violet-600 transition-colors">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Featured Books Carousel -->
            @if($featuredBooks->count() > 0)
                <div class="mb-12 relative" x-data="carousel">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-indigo-900 flex items-center">
                                <div class="relative mr-3">
                                    <i class="fas fa-star-half-alt text-amber-400 text-2xl absolute transform -rotate-12"></i>
                                    <i class="fas fa-star text-amber-400 text-2xl"></i>
                                </div>
                                New Arrivals
                                <span class="ml-3 text-sm font-normal text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                                    {{ $featuredBooks->count() }} books
                                </span>
                            </h2>
                            <p class="mt-1 text-gray-500 text-sm">Discover our latest additions to the library</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button @click="swiper.slidePrev()" 
                                    class="swiper-button-prev p-3 rounded-full bg-white shadow-sm border border-gray-100 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all duration-200">
                                <i class="fas fa-chevron-left text-lg"></i>
                            </button>
                            <button @click="swiper.slideNext()" 
                                    class="swiper-button-next p-3 rounded-full bg-white shadow-sm border border-gray-100 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all duration-200">
                                <i class="fas fa-chevron-right text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @foreach ($featuredBooks->chunk(4) as $bookPair)
                                <div class="swiper-slide">
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 px-2">
                                        @foreach ($bookPair as $book)
                                        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group">
                                            <div class="aspect-[2/3] relative overflow-hidden">
                                                <img 
                                                    src="{{ $book->cover_image_url }}" 
                                                    alt="{{ $book->title }}" 
                                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                                    onerror="this.src='{{ asset('img/default-book-cover.jpg') }}'"
                                                >
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                <div class="absolute top-0 right-0 m-2">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-500/90 text-white shadow-sm backdrop-blur-sm">
                                                        {{ $book->genre->genre_name }}
                                                    </span>
                                                </div>
                                                <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                                    <h3 class="font-medium text-sm mb-1">{{ $book->title }}</h3>
                                                    <p class="text-xs text-white/80">by {{ $book->author }}</p>
                                                    @auth
                                                        @if($book->number_of_books > 0)
                                                            <form action="{{ route('borrow-requests.store', ['book' => $book->id]) }}" method="POST" class="mt-2">
                                                                @csrf
                                                                <button type="submit" 
                                                                    class="w-full text-xs bg-white/90 hover:bg-white text-indigo-600 px-3 py-1.5 rounded-lg font-medium transition-colors duration-200">
                                                                    <i class="fas fa-book-reader mr-1.5"></i>Request to Borrow
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="inline-block mt-2 w-full text-center text-xs bg-white/20 text-white px-3 py-1.5 rounded-lg">
                                                                Currently Unavailable
                                                            </span>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                            <div class="p-4">
                                                <h3 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                                    {{ $book->title }}
                                                </h3>
                                                <p class="text-xs text-gray-500 line-clamp-1">
                                                    by {{ $book->author }}
                                                </p>
                                                <div class="mt-3 flex items-center justify-between border-t border-gray-100 pt-3">
                                                    <span class="text-xs text-gray-500 flex items-center">
                                                        <i class="fas fa-book-open mr-1.5 text-indigo-400"></i>
                                                        {{ $book->number_of_books }} available
                                                    </span>
                                                    <a href="{{ route('books.show', $book) }}" 
                                                       class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                                                        Details →
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <!-- Add pagination -->
                            <div class="swiper-pagination mt-6"></div>
                        </div>
                    </div>
                </div>

                <!-- Alpine.js + Swiper.js Integration -->
                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('carousel', () => ({
                            swiper: null,
                            init() {
                                this.swiper = new Swiper('.swiper-container', {
                                    slidesPerView: 2,
                                    spaceBetween: 20,
                                    loop: true,
                                    speed: 800,
                                    
                                    // Enable better touch handling
                                    grabCursor: true,
                                    
                                    // Navigation
                                    navigation: {
                                        nextEl: '.swiper-button-next',
                                        prevEl: '.swiper-button-prev',
                                    },

                                    // Autoplay configuration
                                    autoplay: {
                                        delay: 5000,
                                        disableOnInteraction: false,
                                    },

                                    // Pagination
                                    pagination: {
                                        el: '.swiper-pagination',
                                        clickable: true,
                                    },

                                    // Responsive breakpoints
                                    breakpoints: {
                                        640: {
                                            slidesPerView: 2,
                                            spaceBetween: 20,
                                        },
                                        768: {
                                            slidesPerView: 3,
                                            spaceBetween: 30,
                                        },
                                        1024: {
                                            slidesPerView: 4,
                                            spaceBetween: 30,
                                        }
                                    }
                                });

                                // Add keyboard navigation
                                document.addEventListener('keydown', (e) => {
                                    if (e.key === 'ArrowLeft') this.swiper.slidePrev();
                                    if (e.key === 'ArrowRight') this.swiper.slideNext();
                                });
                            }
                        }));
                    });
                </script>
            @endif

            <!-- Books Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-5xl mx-auto">
                @foreach ($books as $book)
                    <div class="flex flex-col h-full bg-white rounded-md shadow-sm hover:shadow transition-shadow duration-300">
                        <div class="relative pt-[120%] rounded-t-md overflow-hidden">
                            <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" 
                                class="absolute inset-0 w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-2 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full truncate max-w-[70%]">
                                    {{ $book->genre->genre_name }}
                                </span>
                                @if(auth()->user()->favorites->contains($book))
                                    <form action="{{ route('user.favorite.remove', $book->id) }}" method="POST" class="inline shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors text-sm" title="Remove from favorites">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('user.favorite.add', $book) }}" method="POST" class="inline shrink-0">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors text-sm" title="Add to favorites">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <a href="{{ route('books.show', $book) }}" class="block group flex-1">
                                <h3 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2 mb-0.5">
                                    {{ $book->title }}
                                </h3>
                                <p class="text-[10px] text-gray-600">By {{ $book->author }}</p>
                            </a>
                            <div class="mt-1 flex items-center justify-between border-t pt-1">
                                <span class="text-[10px] text-gray-500">
                                    <i class="fas fa-book-open mr-1"></i> {{ $book->number_of_books }}
                                </span>
                                <div class="flex items-center space-x-2">
                                    @auth
                                        @if($book->number_of_books > 0)
                                            <form action="{{ route('borrow-requests.store', ['book' => $book->id]) }}" method="POST" class="inline-flex">
                                                @csrf
                                                <button type="submit" 
                                                    class="text-[9px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full hover:bg-blue-200 transition-colors">
                                                    Request
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                    <a href="{{ route('books.show', $book) }}" 
                                       class="text-[10px] font-medium text-gray-600 hover:text-blue-600">
                                        Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($books->hasPages())
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @endif
            <!-- Footer -->
           <footer class="mt-16 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 border-t border-gray-700 text-gray-300 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="footer-section">
                <h3 class="text-lg font-semibold text-white mb-4">About AllReads</h3>
                <p class="text-gray-400">Your digital library for discovering and managing books.</p>
            </div>

            <div class="footer-section">
                <h3 class="text-lg font-semibold text-white mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('books.index') }}" class="text-gray-400 hover:text-blue-400 transition-colors">Home</a>
                    </li>
                  <li>
    <a href="https://github.com/Uvog1n-05/LaravelSystem.git" target="_blank"  class="text-gray-400 hover:text-blue-400 transition-colors">
       GitHub
    </a>
</li>
                </ul>
            </div>

            <div class="footer-section">
                <h3 class="text-lg font-semibold text-white mb-4">Contact</h3>
                <div class="space-y-2 text-gray-400">
                    <p><i class="fas fa-envelope mr-2 text-blue-400"></i>info@allreads.com</p>
                    <p><i class="fas fa-phone mr-2 text-blue-400"></i>(123) 456-7890</p>
                </div>
            </div>
        </div>

        <div class="mt-10 pt-8 border-t border-gray-700 text-center text-gray-500">
            <p>&copy; {{ date('Y') }} <span class="text-blue-400 font-semibold">AllReads</span>. All rights reserved.</p>
        </div>
    </div>
</footer>

        </div>
    </div>
</x-layout>