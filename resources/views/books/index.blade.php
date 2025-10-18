<x-layout>  
    <h1 class="text-2xl font-bold mb-4">Books List</h1>
   
    <ul class="space-y-4">
        @foreach ($books as $book)
            <li>
                <x-card href="{{ route('books.show', $book->id) }}">
                    <div class="mb-2">
                        <h2 class="text-xl font-semibold">{{ $book->title }}</h2>
                        <p class="text-sm text-gray-600">Genre: {{ $book->genre->genre_name }}</p>
                    </div>
                    <p class="text-gray-700">Author: {{ $book->author }}</p>
                    <p class="text-gray-700">Books Available: {{ $book->number_of_books }}</p>
                </x-card>
            </li> 
        @endforeach
    </ul>

    <div class="mt-6">
        {{ $books->links() }}
    </div>
</x-layout>
