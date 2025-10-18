<x-layout>
    <h1>Add a New Book</h1>

    <form  action="{{ route('books.store') }}"method="POST">
        @csrf
   
        <label for="title">Book Title:</label>
        <input type="text"
         id="title"
          name="title"
          value="{{old('title')}}"
          required
          >
        
        <label for="author">Author:</label>
        <input
        type="text" 
        id="author" 
        name="author" 
        value="{{old('author')}}"
        required

        >
        
        <label for="about">About:</label>

        <input type="text"
         id="about"
         name="about" 
         value="{{old('about')}}"
         required
         >

        <label for="about">Number of Books:</label>

        <input type="number" 
        id="number_of_books" 
        name="number_of_books" 
        value="{{old('number_of_books')}}"
        required>

        <label for="genre">Genre:</label>
        <select id="genre" name="genre_id" required>

            @foreach ($genre as $genre)
                <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                    {{ $genre->genre_name }}
                
                </option>
            @endforeach
        </select>
      
       <button class="btn" type="submit">Add Book</button>
    </form>


     @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="px-4 py-2 bg-red-100">
                @foreach ($errors->all() as $error)
                    <li class="my-2">{{ $error }}</li>
                @endforeach
            </ul>
            @endif
        </div>
</x-layout>