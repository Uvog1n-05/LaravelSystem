<x-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">My Borrow Requests</h2>
                <p class="mt-1 text-sm text-gray-600">Track the status of your book borrowing requests</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-6 bg-gray-50 border-b border-gray-200">
                @php
                    $pendingCount = $requests->where('status', 'pending')->count();
                    $approvedCount = $requests->where('status', 'approved')->count();
                    $declinedCount = $requests->where('status', 'declined')->count();
                @endphp

                <!-- Pending Requests -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-yellow-100">
                            <i class="fas fa-clock text-yellow-600 text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-500">Pending</h4>
                            <p class="text-2xl font-semibold text-gray-900">{{ $pendingCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Approved Requests -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-green-100">
                            <i class="fas fa-check text-green-600 text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-500">Approved</h4>
                            <p class="text-2xl font-semibold text-gray-900">{{ $approvedCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Declined Requests -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-red-100">
                            <i class="fas fa-times text-red-600 text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-500">Declined</h4>
                            <p class="text-2xl font-semibold text-gray-900">{{ $declinedCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($requests->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">No Borrow Requests</h3>
                    <p class="mt-1 text-sm text-gray-500">You haven't requested to borrow any books yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Browse Books
                        </a>
                    </div>
                </div>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($requests as $request)
                        <div class="p-6">
                            <div class="sm:flex sm:items-center sm:justify-between">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <img class="h-20 w-14 object-cover rounded" 
                                             src="{{ $request->book->cover_image_url }}" 
                                             alt="{{ $request->book->title }}"
                                             onerror="this.src='{{ asset('img/default-book-cover.jpg') }}'">
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $request->book->title }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">by {{ $request->book->author }}</p>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <i class="fas fa-clock mr-1.5"></i>
                                            Requested {{ $request->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 sm:mt-0">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $request->status === 'declined' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($request->status) }}
                                        @if($request->isPending())
                                            <i class="fas fa-clock ml-1.5"></i>
                                        @elseif($request->isApproved())
                                            <i class="fas fa-check ml-1.5"></i>
                                        @else
                                            <i class="fas fa-times ml-1.5"></i>
                                        @endif
                                    </span>
                                    @if(!$request->isPending())
                                        <div class="mt-2 text-xs text-gray-500">
                                            Processed {{ $request->processed_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($request->isApproved())
                                <div class="mt-4 flex items-center text-sm text-green-600">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Your request has been approved! The book is ready for pickup at the library.
                                </div>
                            @endif

                            @if($request->admin_note)
                                <div class="mt-4 bg-gray-50 rounded-md p-4">
                                    <div class="text-sm text-gray-700">
                                        <i class="fas fa-comment-alt text-gray-400 mr-2"></i>
                                        {{ $request->admin_note }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout>