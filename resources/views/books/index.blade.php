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

        <!-- Featured Books Carousel -->
            @if($featuredBooks->count() > 0)
                <div class="mb-8 relative" x-data="carousel">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-2 text-lg"></i>
                                New Arrivals
                            </h2>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="swiper.slidePrev()" class="swiper-button-prev p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                                <i class="fas fa-chevron-left text-gray-600 text-sm"></i>
                            </button>
                            <button @click="swiper.slideNext()" class="swiper-button-next p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                                <i class="fas fa-chevron-right text-gray-600 text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="relative overflow-hidden">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @foreach ($featuredBooks->chunk(4) as $bookPair)
                                <div class="swiper-slide">
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                        @foreach ($bookPair as $book)
                                        <div class="bg-white rounded-lg hover:shadow-md transition-all duration-300 overflow-hidden group max-w-[160px]">
                                            <div class="aspect-[2/3] relative overflow-hidden">
                                                <img 
                                                    src="{{ $book->cover_image_url }}" 
                                                    alt="{{ $book->title }}" 
                                                    class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                                                    onerror="this.src='{{ asset('img/default-book-cover.jpg') }}'"
                                                >
                                                <div class="absolute top-0 right-0 m-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-500/80 text-white backdrop-blur-sm">
                                                        {{ $book->genre->genre_name }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <h3 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                                    {{ $book->title }}
                                                </h3>
                                                <p class="text-xs text-gray-500 line-clamp-1">
                                                    {{ $book->author }}
                                                </p>
                                                <div class="mt-2 flex items-center justify-between border-t pt-2">
                                                    <span class="text-[9px] text-gray-500">
                                                        <i class="fas fa-book-open mr-0.5"></i> {{ $book->number_of_books }}
                                                    </span>
                                                    @auth
                                                        @if($book->number_of_books > 0)
                                                            <form action="{{ route('books.borrow', $book) }}" method="POST" class="inline-flex">
                                                                @csrf
                                                                <button type="submit" 
                                                                    class="text-[9px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full hover:bg-blue-200 transition-colors">
                                                                    Borrow
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endauth
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
                                    slidesPerView: 1,
                                    spaceBetween: 12,
                                    loop: true,
                                    navigation: {
                                        nextEl: '.swiper-button-next',
                                        prevEl: '.swiper-button-prev',
                                    },
                                    autoplay: {
                                        delay: 5000,
                                        disableOnInteraction: false,
                                        pauseOnMouseEnter: true
                                    },
                                    pagination: {
                                        el: '.swiper-pagination',
                                        clickable: true
                                    },
                                    breakpoints: {
                                        640: {
                                            slidesPerView: 1,
                                            spaceBetween: 16
                                        }
                                    }
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
                                            <form action="{{ route('books.borrow', ['book' => $book->id]) }}" method="POST" class="inline-flex">
                                                @csrf
                                                <button type="submit" 
                                                    class="text-[9px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full hover:bg-blue-200 transition-colors">
                                                    Borrow
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
                        <a href="{{ route('books.create') }}" class="text-gray-400 hover:text-blue-400 transition-colors">Add Book</a>
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