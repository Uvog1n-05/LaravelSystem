        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AllReads Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @vite(['resources/css/app.css'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="pae-container" x-data="{ isSidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="isSidebarOpen" 
             class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 sm:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="isSidebarOpen = false"></div>

        <!-- Top Navigation Bar -->
        <nav class="bg-white shadow-sm fixed top-0 left-0 right-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Mobile menu button -->
                    <button @click="isSidebarOpen = true" 
                            class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="flex-1 flex items-center justify-center sm:justify-start">
                        <div class="flex-shrink-0">
                          
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Notifications -->
        <div class="fixed top-4 right-4 z-50 space-y-2">
            @if (session('success'))
                <div class="bg-green-50 text-green-800 px-4 py-3 rounded-lg shadow-lg flex items-center border border-green-200" 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-8"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-8"
                    x-init="setTimeout(() => show = false, 3000)">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-50 text-red-800 px-4 py-3 rounded-lg shadow-lg flex items-center border border-red-200" 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-8"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform translate-x-8"
                    x-init="setTimeout(() => show = false, 3000)">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 w-64 bg-red shadow-lg transition-transform duration-300 transform z-40 border-r border-gray-200 overflow-y-auto"
               :class="{'translate-x-0': isSidebarOpen, '-translate-x-full sm:translate-x-0': !isSidebarOpen}">
            <div class="flex flex-col min-h-screen">
                <!-- Logo -->
                <div class="sticky top-0 z-10 bg-white border-b border-gray-200">
                    <div class="p-4 flex justify-between items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <span class="text-3xl font-bold text-blue-600">TMC LIBRARY</span>
                        </a>
                        <!-- Close button for mobile -->
                        <button @click="isSidebarOpen = false" class="sm:hidden text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- User Profile -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                            <span class="text-white text-lg font-medium">
                                {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'G' }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium truncate">{{ auth()->user() ? auth()->user()->name : 'Guest' }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ auth()->user() ? auth()->user()->email : '' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 p-4 space-y-2">
                    @auth
                        <a href="{{ route('user.dashboard') }}" 
                           class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('user.dashboard') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                            <i class="fas fa-home w-5 h-5 mr-3"></i> Dashboard
                        </a>
                        
                        <a href="{{ route('books.index') }}" 
                           class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('books.index') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                            <i class="fas fa-book w-5 h-5 mr-3"></i> Books
                        </a>
                        
                        <a href="{{ route('user.favorite') }}" 
                           class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('user.favorite') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                            <i class="fas fa-heart w-5 h-5 mr-3"></i> Favorites
                        </a>
                        
                        <a href="{{ route('user.profile') }}" 
                           class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('user.profile') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                            <i class="fas fa-user w-5 h-5 mr-3"></i> Profile
                        </a>

                        <a href="{{ route('books.history') }}" 
                           class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('books.history') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                            <i class="fas fa-history w-5 h-5 mr-3"></i> Borrowing History
                        </a>

                        <a href="{{ route('borrow-requests.user') }}" 
                           class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('borrow-requests.user') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                            <i class="fas fa-clock w-5 h-5 mr-3"></i>  My Borrow Requests
                            @php
                                $pendingCount = \App\Models\BorrowRequest::where('user_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-500 rounded-full">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>

                        @if(auth()->user()->isAdmin())
                            <div class="border-t border-gray-200 my-4"></div>
                            
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                                <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i> Admin Dashboard
                            </a>

                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" 
                                    class="flex items-center w-full p-2 text-gray-700 rounded-lg transition-colors hover:bg-blue-50">
                                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                                    <span class="flex-1 text-left">Admin Controls</span>
                                    <i class="fas fa-chevron-down transition-transform" :class="{ 'transform rotate-180': open }"></i>
                                </button>

                                <div x-show="open" 
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="pl-6 mt-1 space-y-1">
                                    
                                    <a href="{{ route('admin.users') }}" 
                                       class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                                        <i class="fas fa-users w-5 h-5 mr-3"></i> Manage Users
                                    </a>

                                    <a href="{{ route('admin.borrow-requests') }}" 
                                       class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('admin.borrow-requests') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                                        <i class="fas fa-clock w-5 h-5 mr-3"></i> Borrow Requests
                                    </a>

                                    <a href="{{ route('admin.borrowings') }}" 
                                       class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('admin.borrowings') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                                        <i class="fas fa-history w-5 h-5 mr-3"></i> Book Borrowings
                                    </a>
                                    
                                    <a href="{{route('books.create')}}" 
                                       class="flex items-center p-2 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('books.create') ? 'bg-blue-100 text-blue-600' : 'hover:bg-blue-50' }}">
                                        <i class="fas fa-plus-circle w-5 h-5 mr-3"></i> Add Book
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <a href="{{route('show.login')}}" 
                           class="flex items-center p-2 text-gray-700 rounded-lg transition-colors hover:bg-blue-50">
                            <i class="fas fa-sign-in-alt w-5 h-5 mr-3"></i> Login
                        </a>
                        
                        <a href="{{route('show.register')}}" 
                           class="flex items-center p-2 text-gray-700 rounded-lg transition-colors hover:bg-blue-50">
                            <i class="fas fa-user-plus w-5 h-5 mr-3"></i> Register
                        </a>
                    @endauth
                </nav>
                
                @auth
                <div class="sticky bottom-0 p-4 mt-auto bg-white border-t border-gray-200">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt w-5 h-5 mr-3 flex-shrink-0"></i>
                            <span class="truncate">Logout</span>
                        </button>
                    </form>
                </div>
                @endauth
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="pt-16 sm:ml-64">
            <main class="min-h-[calc(-4rem)] py-6 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </div>
    
    @vite('resources/js/sidebar.js')
        
    </script>
</body>
</html>
  