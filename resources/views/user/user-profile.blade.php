@use Carbon\Carbon

{{--
    User Profile Page
    This view provides users with account management features:
    - View and update personal information
    - Change password
    - View currently borrowed books
    - Access quick actions for borrowed books
    - Track borrowing status and due dates
    
    Layout:
    - Left Column: Account settings forms
    - Right Column: Currently borrowed books
--}}

<x-layout>
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Profile Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
            <p class="mt-2 text-gray-600">Manage your account settings and view your borrowed books</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - User Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information Form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Personal Information</h2>
                    <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ auth()->user()->name }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ auth()->user()->email }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Change Password</h2>
                    <form action="{{ route('user.profile.password') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" id="current_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Borrowed Books -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Borrowed Books</h2>
                    @if($borrowedBooks->count() > 0)
                        <div class="space-y-4">
                            @foreach($borrowedBooks as $borrowing)
                                <div class="flex gap-4 p-3 bg-gray-50 rounded-md">
                                    <div class="flex-shrink-0 w-16">
                                        <img src="{{ $borrowing->book->cover_image_url }}" alt="{{ $borrowing->book->title }}"
                                            class="w-full h-auto rounded shadow"
                                            onerror="this.src='{{ asset('img/default-book-cover.jpg') }}'">
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $borrowing->book->title }}</h3>
                                        <p class="text-xs text-gray-500">By {{ $borrowing->book->author }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            @if($borrowing->due_date)
                                                Due: {{ $borrowing->due_date->format('M d, Y') }}
                                            @else
                                                Due date not set
                                            @endif
                                        </p>
                                        <div class="mt-2">
                                            @if($borrowing->return_requested)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">Return requested</span>
                                                <form action="{{ route('books.cancel-return', $borrowing->book) }}" method="POST" class="inline ml-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-xs text-red-600 hover:text-red-700">
                                                        Cancel Request
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('books.return', $borrowing->book) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-700">
                                                        Return Book
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $borrowedBooks->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">You haven't borrowed any books yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>