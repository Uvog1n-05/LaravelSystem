<x-layout> 

    <h1 class="header_text">Find your next Book</h1>


    <div class="content-wrapper">
        <div class="container mx-auto px-4 py-8">
            <!-- Search Bar -->
            <div class="search-container mb-8">
                <form action="{{ route('books.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" 
                        placeholder="Search by title, author, or genre..." 
                        class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                        value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        Search
                    </button>
                </form>
            </div>

           
            
            <!-- Books Grid -->
            <div class="books-grid">
                @foreach ($books as $book)
                    <x-card href="{{ route('books.show', $book->id) }}">
                        <div class="card-inner">
                            <h2 class="card-title">{{ $book->title }}</h2>
                            <p class="card-genre">{{ $book->genre->genre_name }}</p>
                            <div class="card-details">
                                <p class="card-author">By {{ $book->author }}</p>
                                <p class="card-stock">{{ $book->number_of_books }} copies available</p>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $books->links() }}
            </div>
        </div>
    </div>

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