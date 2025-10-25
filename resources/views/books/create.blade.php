<x-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-900">Add a New Book</h1>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Books
                    </a>
                </div>
                <p class="mt-2 text-sm text-gray-600">Fill in the details below to add a new book to the library.</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf

                    <!-- Cover Image Upload -->
                    <div>
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">Book Cover</label>
                        <div class="flex items-center space-x-4">
                            <div class="w-32 h-40 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300 hover:border-blue-500 transition-colors">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500">Click to upload</p>
                                </div>
                            </div>
                            <input type="file" 
                                id="cover_image" 
                                name="cover_image" 
                                accept="image/jpeg,image/png,image/jpg"
                                class="hidden"
                                onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])"
                            >
                            <img id="preview" class="w-32 h-40 object-cover rounded-lg hidden">
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Recommended size: 400x600 pixels</p>
                                <p class="text-xs text-gray-500 mt-1">Supported formats: JPEG, PNG</p>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Book Title</label>
                            <input type="text"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter book title"
                            >
                        </div>
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Author</label>
                            <input type="text"
                                id="author"
                                name="author"
                                value="{{ old('author') }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter author name"
                            >
                        </div>
                    </div>

                    <!-- About -->
                    <div>
                        <label for="about" class="block text-sm font-medium text-gray-700 mb-2">About the Book</label>
                        <textarea
                            id="about"
                            name="about"
                            rows="4"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter book description"
                        >{{ old('about') }}</textarea>
                    </div>

                    <!-- Additional Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="number_of_books" class="block text-sm font-medium text-gray-700 mb-2">Number of Books</label>
                            <div class="relative">
                                <input type="number"
                                    id="number_of_books"
                                    name="number_of_books"
                                    value="{{ old('number_of_books') }}"
                                    required
                                    min="1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter quantity"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="genre" class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                            <div class="relative">
                                <select id="genre"
                                    name="genre_id"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none"
                                >
                                    <option value="">Select a genre</option>
                                    @foreach ($genre as $genre)
                                        <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                            {{ $genre->genre_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="rounded-lg bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc space-y-1 pl-5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t">
                        <button type="button" onclick="window.history.back()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg text-sm font-medium hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Add Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show preview of uploaded image
        document.getElementById('cover_image').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const uploadBox = this.previousElementSibling;
            
            if (this.files && this.files[0]) {
                preview.classList.remove('hidden');
                uploadBox.classList.add('hidden');
                preview.src = URL.createObjectURL(this.files[0]);
            }
        });

        // Click on preview box triggers file input
        document.querySelector('.border-dashed').addEventListener('click', function() {
            document.getElementById('cover_image').click();
        });
    </script>
</x-layout>