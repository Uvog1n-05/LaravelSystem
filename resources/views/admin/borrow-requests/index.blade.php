{{--
    Admin Borrow Requests Management Page
    This view allows administrators to:
    - View all pending borrow requests
    - See request details (user, book, timestamp)
    - Approve or decline requests
    - Check book availability before approval
    - Monitor request status
--}}

<x-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Page Header --}}
                    <h2 class="text-2xl font-bold mb-6">Borrow Requests</h2>

                    @if($requests->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900">No Pending Requests</h3>
                            <p class="mt-1 text-sm text-gray-500">There are no borrow requests to process at this time.</p>
                        </div>
                    @else
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <form method="GET" action="{{ route('admin.borrow-requests') }}" class="flex items-center space-x-2">
                                    {{-- preserve sort & direction in filter form --}}
                                    <input type="hidden" name="sort" value="{{ $sort ?? request('sort') }}">
                                    <input type="hidden" name="direction" value="{{ $direction ?? request('direction') }}">
                                    <label for="status" class="text-sm font-medium text-gray-700">Status:</label>
                                    <select id="status" name="status" onchange="this.form.submit()" class="mt-1 block pl-3 pr-10 py-1 text-sm border-gray-200 rounded-md">
                                        <option value="" {{ empty($status) ? 'selected' : '' }}>All</option>
                                        <option value="pending" {{ (isset($status) && $status === 'pending') ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ (isset($status) && $status === 'approved') ? 'selected' : '' }}>Approved</option>
                                        <option value="declined" {{ (isset($status) && $status === 'declined') ? 'selected' : '' }}>Declined</option>
                                    </select>
                                </form>
                            </div>
                            <div>
                                @if(!empty($status))
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="text-sm text-blue-600 hover:underline">Reset</a>
                                @endif
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    @php
                                        $currentSort = $sort ?? request('sort', 'created_at');
                                        $currentDirection = $direction ?? request('direction', 'desc');

                                        $toggle = function($field) use ($currentSort, $currentDirection) {
                                            if ($currentSort === $field) {
                                                return $currentDirection === 'asc' ? 'desc' : 'asc';
                                            }
                                            return 'desc';
                                        };
                                    @endphp
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{-- Main header sorts by user name --}} 
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'user', 'direction' => $toggle('user')]) }}" class="inline-flex items-center gap-2">
                                                <span>Request Details</span>
                                                @if($currentSort === 'user')
                                                    <i class="fas fa-sort-amount-{{ $currentDirection === 'asc' ? 'up' : 'down' }} text-gray-400"></i>
                                                @endif
                                            </a>
                                            {{-- small date sort link --}}
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $toggle('created_at')]) }}" class="ml-2 text-xs text-gray-400">Date</a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'book', 'direction' => $toggle('book')]) }}" class="inline-flex items-center gap-2">
                                                <span>Book</span>
                                                @if($currentSort === 'book')
                                                    <i class="fas fa-sort-amount-{{ $currentDirection === 'asc' ? 'up' : 'down' }} text-gray-400"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => $toggle('status')]) }}" class="inline-flex items-center gap-2">
                                                <span>Status</span>
                                                @if($currentSort === 'status')
                                                    <i class="fas fa-sort-amount-{{ $currentDirection === 'asc' ? 'up' : 'down' }} text-gray-400"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($requests as $request)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $request->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Requested {{ $request->created_at->diffForHumans() }}
                                                        </div>
                                                        @if($request->reason)
                                                            <div class="mt-2 text-sm text-gray-600">
                                                                <strong>Reason:</strong> {{ \Illuminate\Support\Str::limit($request->reason, 120) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <img class="h-16 w-12 object-cover rounded" 
                                                         src="{{ $request->book->cover_image_url }}" 
                                                         alt="{{ $request->book->title }}"
                                                         onerror="this.src='{{ asset('img/default-book-cover.jpg') }}'">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $request->book->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            by {{ $request->book->author }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Available Copies: {{ $request->book->number_of_books }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $request->status === 'declined' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($request->isPending())
                                                    <div class="flex space-x-2">
                                                        <form action="{{ route('admin.borrow-requests.process', ['request' => $request, 'action' => 'approve']) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                                <i class="fas fa-check mr-1.5"></i>
                                                                Approve
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('admin.borrow-requests.process', ['request' => $request, 'action' => 'decline']) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                <i class="fas fa-times mr-1.5"></i>
                                                                Decline
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-500">
                                                        Processed {{ $request->processed_at->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>