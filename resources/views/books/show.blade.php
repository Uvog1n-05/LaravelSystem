<x-layout>
    <div class="book-card-container">
       
        <div class="book-card-genre">
            <h3>Genre Information</h3>
            <p><strong>Genre:</strong> {{ $books->genre->genre_name }}</p>
            <p><strong>Genre Info:</strong> {{ $books->genre->description }}</p>
        </div>

        <hr>

        <div class="book-card-info">
            <h2>Book Number: {{ $books->id }}</h2>
            <p><strong>About Book:</strong></p>
            <p>{{ $books->about }}</p>
        </div>
    </div>

    <form action="{{ route('books.destroy',$books->id )}}" method="POST">
        @csrf
       @method('DELETE')
        <button class="btn-danger" type="submit">Delete Book</button>
    </form>
</x-layout>
