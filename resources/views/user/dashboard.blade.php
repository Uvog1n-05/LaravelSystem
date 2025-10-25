<x-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- User Header -->
        <div class="relative overflow-hidden bg-white shadow-sm">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-indigo-500/10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div class="flex items-center space-x-5">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold uppercase">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-4 border-white"></div>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                            <p class="mt-1 text-gray-500">Your personal reading dashboard</p>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 flex items-center space-x-3">
                        <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-book-open mr-2"></i>
                            Browse Books
                        </a>
                        <a href="{{ route('user.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-user-cog mr-2"></i>
                            Profile Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Favorites Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Favorite Books</p>
                            <div class="mt-2 flex items-baseline">
                                <h3 class="text-3xl font-bold text-gray-900">{{ auth()->user()->favorites()->count() }}</h3>
                                @php
                                    $lastMonthCount = auth()->user()->favorites()->where('book_user_favorites.created_at', '>=', now()->subMonth())->count();
                                @endphp
                                @if($lastMonthCount > 0)
                                    <span class="ml-2 text-sm text-green-600">
                                        <i class="fas fa-arrow-up"></i>
                                        +{{ $lastMonthCount }} this month
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="rounded-full p-3 bg-red-100">
                            <i class="fas fa-heart text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('user.favorite') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                            View favorites
                            <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Genre Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Explored Genres</p>
                            <div class="mt-2 flex items-baseline">
                                <h3 class="text-3xl font-bold text-gray-900">
                                    {{ auth()->user()->favorites()->distinct('genre_id')->count('genre_id') }}
                                </h3>
                                <span class="ml-2 text-sm text-gray-600">/ {{ \App\Models\Genre::count() }} total</span>
                            </div>
                        </div>
                        <div class="rounded-full p-3 bg-purple-100">
                            <i class="fas fa-tags text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500 rounded-full" 
                                 style="width: {{ (auth()->user()->favorites()->distinct('genre_id')->count('genre_id') / \App\Models\Genre::count()) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('books.create') }}" class="flex items-center px-6 py-4 hover:bg-gray-50 group transition-all duration-200">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center transform group-hover:scale-105 transition-transform">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors">Add New Book</p>
                                <p class="text-sm text-gray-500">Expand the library collection</p>
                            </div>
                            <div class="ml-auto opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all">
                                <i class="fas fa-chevron-right text-blue-500"></i>
                            </div>
                        </a>
                    @endif
                    <a href="{{ route('books.index') }}" class="flex items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book-open text-indigo-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Browse Library</p>
                            <p class="text-sm text-gray-500">Explore our collection of books</p>
                        </div>
                        <div class="ml-auto">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </a>
                    <a href="{{ route('user.favorite') }}" class="flex items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-heart text-red-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">My Favorites</p>
                            <p class="text-sm text-gray-500">View and manage your favorite books</p>
                        </div>
                        <div class="ml-auto">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </a>
                    <a href="{{ route('user.profile') }}" class="flex items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-cog text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Profile Settings</p>
                            <p class="text-sm text-gray-500">Update your account preferences</p>
                        </div>
                        <div class="ml-auto">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>