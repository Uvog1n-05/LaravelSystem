<x-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-black">Add a New Book</h1>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center text-sm text-gray-700 hover:text-black">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Books
                    </a>
                </div>
                <p class="mt-2 text-sm text-gray-800">Fill in the details below to add a new book to the library.</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf

                    <!-- Cover Image Upload -->
                    <div>
                        <label for="cover_image" class="block text-sm font-medium text-black mb-2">Book Cover</label>
                        <div class="flex items-center space-x-4">
                            <div class="w-32 h-40 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300 hover:border-blue-500 transition-colors">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-600 mb-2"></i>
                                    <p class="text-xs text-gray-700">Click to upload</p>
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
                                <p class="text-sm text-gray-800">Recommended size: 400x600 pixels</p>
                                <p class="text-xs text-gray-700 mt-1">Supported formats: JPEG, PNG</p>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-black mb-2">Book Title</label>
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
                            <label for="author" class="block text-sm font-medium text-black mb-2">Author</label>
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
                        <label for="about" class="block text-sm font-medium text-black mb-2">About the Book</label>
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
                            <label for="number_of_books" class="block text-sm font-medium text-black mb-2">Number of Books</label>
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
                            <label for="genre" class="block text-sm font-medium text-black mb-2">Genre</label>
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
                        <div class="rounded-lg bg-red-100 p-4 border-2 border-red-400 shadow-sm">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                                </div>
                                <div class="ml-3 w-full">
                                    <h3 class="text-base font-semibold text-red-800">Please fix the following errors:</h3>
                                    <div class="mt-3">
                                        <ul class="list-disc space-y-2 pl-5">
                                            @foreach ($errors->all() as $error)
                                                <li class="text-red-700 font-medium">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                        <button type="button" 
                            onclick="window.history.back()" 
                            class="px-6 py-3 border-2 border-gray-300 rounded-lg text-base font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 active:bg-gray-100 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-blue-800 to-indigo-800 text-white rounded-lg text-base font-bold shadow-xl hover:shadow-2xl hover:from-blue-900 hover:to-indigo-900 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 relative overflow-hidden group">
                            <span class="relative z-10 flex items-center justify-center">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Add Book
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-200"></div>
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