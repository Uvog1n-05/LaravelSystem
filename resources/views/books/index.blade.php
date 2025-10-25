<x-layout>
    <div class="container-fluid py-8">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg mb-12 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h1 class="text-4xl font-bold mb-6">Find your next Book</h1>
                <p class="text-lg text-blue-100 mb-12">Explore our collection of amazing books</p>
                
                <!-- Search Bar -->
                <form action="{{ route('books.index') }}" method="GET" class="max-w-3xl">
                    <div class="flex gap-4">
                        <div class="flex-1 relative">
                            <input type="text" name="search" 
                                placeholder="Search by title, author, or genre..." 
                                class="w-full px-6 py-4 text-lg rounded-xl text-gray-900 bg-white/95 backdrop-blur-sm border-0 focus:ring-2 focus:ring-white shadow-sm"
                                value="{{ request('search') }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none">
                                <i class="fas fa-search text-gray-400 text-lg"></i>
                            </div>
                        </div>
                        <button type="submit" class="px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-colors shadow-sm text-lg">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- New Arrivals Section -->
            @if($featuredBooks->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">New Arrivals</h2>
                            <p class="text-gray-600 text-xs mt-0.5">Check out our latest additions</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="p-1.5 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors" id="prev-button">
                                <i class="fas fa-chevron-left text-gray-600 text-xs"></i>
                            </button>
                            <button class="p-1.5 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors" id="next-button">
                                <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="relative" id="featured-carousel">
                        <div class="swiper-container overflow-hidden max-w-4xl mx-auto">
                            <div class="swiper-wrapper">
                                @foreach ($featuredBooks as $book)
                                    <div class="swiper-slide px-1">
                                        <div class="bg-white rounded-md shadow-sm overflow-hidden group hover:shadow transition-shadow duration-300 h-full max-w-[160px] mx-auto">
                                            <div class="relative pt-[150%]">
                                                <img src="{{ $book->cover_image_url }}" 
                                                     alt="{{ $book->title }}" 
                                                     class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                                <div class="absolute top-1 right-1">
                                                    @if(auth()->user()->favorites->contains($book))
                                                        <form action="{{ route('user.favorite.remove', $book->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-1 bg-white/90 backdrop-blur-sm rounded-full shadow-sm hover:bg-red-50">
                                                                <i class="fas fa-heart text-red-500 text-xs"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('user.favorite.add', $book) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <button type="submit" class="p-1 bg-white/90 backdrop-blur-sm rounded-full shadow-sm hover:bg-red-50">
                                                                <i class="fas fa-heart text-gray-400 hover:text-red-500 text-xs"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="p-1.5">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-[9px] px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full truncate max-w-[70%]">
                                                        {{ $book->genre->genre_name }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('books.show', $book) }}" class="block group">
                                                    <h3 class="text-xs font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1 mb-0.5">
                                                        {{ $book->title }}
                                                    </h3>
                                                    <p class="text-[9px] text-gray-600 truncate">By {{ $book->author }}</p>
                                                </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper-pagination !-bottom-6"></div>
                    </div>
                </div>
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
                                <a href="{{ route('books.show', $book) }}" 
                                   class="text-xs font-medium text-blue-600 hover:text-blue-700">
                                    View Details →
                                </a>
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
        </div>
    </div>

            <!-- Initialize Swiper -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const featuredCarousel = document.getElementById('featured-carousel');
                    if (featuredCarousel) {
                        const swiper = new Swiper(featuredCarousel.querySelector('.swiper-container'), {
                            slidesPerView: 3,
                            spaceBetween: 8,
                            loop: true,
                            direction: 'horizontal',
                            slidesPerGroup: 3,
                            autoplay: {
                                delay: 5000,
                                disableOnInteraction: false,
                            },
                            breakpoints: {
                                640: {
                                    slidesPerView: 4,
                                    spaceBetween: 12,
                                },
                                1024: {
                                    slidesPerView: 6,
                                    spaceBetween: 16,
                                },
                            },
                            navigation: {
                                nextEl: '#next-button',
                                prevEl: '#prev-button',
                            },
                            speed: 400,
                        });

                        // Custom navigation buttons
                        document.getElementById('prev-button').addEventListener('click', () => {
                            swiper.slidePrev();
                        });
                        document.getElementById('next-button').addEventListener('click', () => {
                            swiper.slideNext();
                        });

                        featuredCarousel.addEventListener('mouseenter', () => {
                            swiper.autoplay.stop();
                        });
                        featuredCarousel.addEventListener('mouseleave', () => {
                            swiper.autoplay.start();
                        });
                    }
                });
            </script>

            <!-- Footer -->
            <footer class="footer">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>About AllReads</h3>
                        <p>Your digital library for discovering and managing books.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="{{ route('books.index') }}">Home</a></li>
                    <li><a href="{{ route('books.create') }}">Add Book</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: info@allreads.com</p>
                <p>Phone: (123) 456-7890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} AllReads. All rights reserved.</p>
        </div>

    </footer>

</x-layout>