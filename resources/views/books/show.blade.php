<x-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <!-- Book Cover and Quick Info -->
            <div class="md:w-1/3 p-6 bg-gray-50">
                <div class="aspect-w-3 aspect-h-4 rounded-lg overflow-hidden shadow-md mb-4">
                    <img src="{{ $books->cover_image_url }}" alt="{{ $books->title }}" class="object-cover">
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Book ID:</span>
                        <span class="text-sm font-medium text-gray-900">#{{ $books->id }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            {{ $books->genre->genre_name }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm {{ $books->number_of_books > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $books->number_of_books }} copies available
                        </span>
                    </div>
                </div>
            </div>

            <!-- Book Details -->
            <div class="md:w-2/3 p-6 md:border-l border-gray-200">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $books->title }}</h1>
                    <p class="text-lg text-gray-600">by {{ $books->author }}</p>
                </div>

                <div class="prose max-w-none mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">About this Book</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $books->about }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Genre Information</h3>
                    <p class="text-gray-600">{{ $books->genre->description ?? 'No genre description available.' }}</p>
                </div>

                <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-4">
                        @auth
                            @php
                                $pendingRequest = \App\Models\BorrowRequest::where('user_id', auth()->id())
                                    ->where('book_id', $books->id)
                                    ->where('status', 'pending')
                                    ->exists();
                            @endphp
                            
                            @if($pendingRequest)
                                <span class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-yellow-200 rounded-md font-medium text-sm text-yellow-800">
                                    <i class="fas fa-clock mr-2"></i>
                                    Request Pending
                                </span>
                            @elseif($books->number_of_books > 0)
                                <div x-data="{ open:false, extension: 0 }" class="inline-flex">
                                    <button type="button" @click="open = true" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-book-reader mr-2"></i>
                                        Request to Borrow
                                    </button>

                                    <template x-teleport="body">
                                        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                            <div @click.away="open = false" role="dialog" aria-modal="true" class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 text-gray-900">
                                                <h3 class="text-lg font-semibold text-gray-900">Request to Borrow</h3>
                                                <p class="text-sm font-medium text-gray-800 mt-1">{{ $books->title }}</p>
                                                <p class="text-sm text-gray-600 mt-2">If approved, due date will be: <strong x-text="new Date(Date.now() + ((14 + extension*7) * 24*60*60*1000)).toLocaleDateString('en-US',{ month: 'short', day: 'numeric', year: 'numeric' })">{{ now()->addDays(14)->format('M d, Y') }}</strong></p>
                                                <form action="{{ route('borrow-requests.store', ['book' => $books->id]) }}" method="POST" class="mt-4">
                                                    @csrf
                                                    <label class="block text-sm font-medium text-gray-900">Extension</label>
                                                    <select name="extension" x-model.number="extension" class="mt-2 block w-full rounded-md border-gray-200 shadow-sm text-gray-900 py-2 px-2">
                                                        <option value="0">Regular (14 days)</option>
                                                        <option value="1">1 extension (+7 days)</option>
                                                        <option value="2">2 extensions (+14 days)</option>
                                                    </select>

                                                    <label class="block text-sm font-medium text-gray-900 mt-4">Reason for borrowing (optional)</label>
                                                    <textarea name="reason" rows="4" class="mt-2 block w-full rounded-md border-gray-200 shadow-sm text-gray-900" placeholder="Tell us why you need this book"></textarea>
                                                    <div class="mt-4 flex justify-end gap-2">
                                                        <button type="button" @click="open = false" class="px-4 py-2 text-sm rounded bg-gray-100 text-gray-700">Cancel</button>
                                                        <button type="submit" class="px-4 py-2 text-sm rounded bg-blue-600 text-white">Submit Request</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            @else
                                <span class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-200 rounded-md font-medium text-sm text-gray-500">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    Not Available
                                </span>
                            @endif
                        @endauth
                    </div>

                    @if(auth()->user() && auth()->user()->isAdmin())
                        <form action="{{ route('books.destroy', $books->id )}}" method="POST" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Delete Book
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('books.index') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Books
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

   
</x-layout>
