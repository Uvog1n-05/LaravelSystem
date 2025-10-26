<x-layout>
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Borrowing History</h1>
            <p class="mt-2 text-gray-600">View all your past and current book borrowings</p>
        </div>

        @if($borrowings->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul role="list" class="divide-y divide-gray-200">
                    @foreach($borrowings as $borrowing)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-start gap-x-4">
                                    <div class="flex-shrink-0 w-20">
                                        <img src="{{ $borrowing->book->cover_image_url }}" 
                                             alt="{{ $borrowing->book->title }}"
                                             class="w-full h-auto rounded shadow"
                                             onerror="this.src='{{ asset('img/default-book-cover.jpg') }}'">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900">
                                                {{ $borrowing->book->title }}
                                            </h3>
                                            <div class="ml-2 flex-shrink-0">
                                                @if($borrowing->returned_date)
                                                    <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                        Returned
                                                    </span>
                                                @elseif($borrowing->isOverdue())
                                                    <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                        Overdue
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                                        Active
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-2 flex flex-col sm:flex-row sm:items-center text-xs text-gray-500">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                <span>Borrowed: {{ $borrowing->borrowed_date->format('M d, Y') }}</span>
                                            </div>
                                            <span class="hidden sm:inline mx-2">•</span>
                                            <div class="flex items-center mt-1 sm:mt-0">
                                                <i class="fas fa-clock mr-1"></i>
                                                <span>Due: {{ $borrowing->due_date->format('M d, Y') }}</span>
                                            </div>
                                            @if($borrowing->returned_date)
                                                <span class="hidden sm:inline mx-2">•</span>
                                                <div class="flex items-center mt-1 sm:mt-0">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    <span>Returned: {{ $borrowing->returned_date->format('M d, Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if(!$borrowing->returned_date)
                                            <div class="mt-3 flex gap-2">
                                                <form action="{{ route('books.return', $borrowing->book) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        Return Book
                                                    </button>
                                                </form>
                                                
                                                @if(!$borrowing->isOverdue())
                                                    <form action="{{ route('books.extend', $borrowing->book) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                            Extend Period
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-4">
                {{ $borrowings->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-book-open text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No Borrowing History</h3>
                <p class="mt-2 text-sm text-gray-500">You haven't borrowed any books yet.</p>
                <div class="mt-6">
                    <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Browse Books
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layout>