{{--
    User's Borrowing History Page
    This view shows users their borrowing activity and allows them to:
    - View currently borrowed books
    - See borrowing history and statistics
    - Track due dates and overdue items
    - Request extensions for borrowed books
    - Return books
    - Monitor borrowing limits and availability
--}}

<x-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            {{-- Header Section with Statistics --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Library Activity</h1>
                        <p class="mt-2 text-sm text-gray-600">Track your book borrowings and manage  deadline extensions</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-book-open mr-2"></i>
                            Browse More Books
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white overflow-hidden rounded-lg border border-gray-200">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-book text-blue-600 text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Currently Borrowed</dt>
                                        <dd class="text-2xl font-semibold text-gray-900">
                                            {{ $borrowings->where('returned_date', null)->count() }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden rounded-lg border border-gray-200">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-history text-indigo-600 text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Borrowed</dt>
                                        <dd class="text-2xl font-semibold text-gray-900">{{ $borrowings->count() }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden rounded-lg border border-gray-200">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-yellow-600 text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Due Soon</dt>
                                        <dd class="text-2xl font-semibold text-gray-900">
                                            {{ $borrowings->where('returned_date', null)->where('due_date', '>', now())->where('due_date', '<', now()->addDays(3))->count() }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden rounded-lg border border-gray-200">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-red-600 text-2xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Overdue</dt>
                                        <dd class="text-2xl font-semibold text-gray-900">
                                            {{ $borrowings->where('returned_date', null)->where('due_date', '<', now())->count() }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                        <div class="mt-2 text-sm text-gray-700">
                                            <p class="text-sm text-gray-600">{{ $borrowing->book->author }}</p>
                                        </div>
                                        <div class="mt-2 flex flex-col sm:flex-row sm:items-center text-xs text-gray-500 space-y-1 sm:space-y-0">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                <span>Borrowed: {{ $borrowing->borrowed_date->format('M d, Y') }}</span>
                                            </div>
                                            <span class="hidden sm:inline mx-2">•</span>
                                            <div class="flex items-center">
                                                <i class="fas fa-clock mr-1"></i>
                                                @if($borrowing->due_date)
                                                    <span>Due: {{ $borrowing->due_date->format('M d, Y') }}</span>
                                                    @if(!$borrowing->returned_date && !$borrowing->isOverdue())
                                                        <span class="ml-1 text-xs">({{ now()->diffInDays($borrowing->due_date) }} days left)</span>
                                                    @endif
                                                @else
                                                    <span>Due date not set</span>
                                                @endif
                                            </div>
                                            @if($borrowing->returned_date)
                                                <span class="hidden sm:inline mx-2">•</span>
                                                <div class="flex items-center">
                                                    <i class="fas fa-check-circle mr-1 text-green-500"></i>
                                                    <span>Returned: {{ $borrowing->returned_date->format('M d, Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-1 flex items-center text-xs text-gray-500">
                                            <i class="fas fa-redo mr-1"></i>
                                            <span>Extensions used: {{ $borrowing->extensions_count }} / {{ \App\Http\Controllers\BookBorrowingController::MAX_EXTENSIONS }}</span>
                                        </div>
                                        
                                        @if(!$borrowing->returned_date)
                                            <div class="mt-3 flex gap-2">
                                                @if($borrowing->return_requested)
                                                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">Return requested</span>
                                                @else
                                                    <form action="{{ route('books.return', $borrowing->book) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                            <i class="fas fa-undo-alt mr-1.5"></i>
                                                            Return Book
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!$borrowing->isOverdue() && $borrowing->extensions_count < \App\Http\Controllers\BookBorrowingController::MAX_EXTENSIONS)
                                                    <form action="{{ route('books.extend', $borrowing->book) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 hover:bg-green-200 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                            <i class="fas fa-calendar-plus mr-1.5"></i>
                                                            Extend Period (+7 days)
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