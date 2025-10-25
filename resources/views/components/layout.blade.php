        
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
    <script src="https://cdn.tailwindcss.com"></script>

    
</head>
<body>
    <div class="min-h-screen bg-gray-50">
        <!-- Top Navigation Bar -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-xl font-semibold text-gray-800">TMC LIBRARY</span>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button class="sidebar-toggle inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" onclick="toggleSidebar()">
                            <i class="fas fa-bars w-6 h-6"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        @if (session('success'))
            <div class="fixed top-4 right-4 z-50 bg-green-50 text-green-800 px-4 py-3 rounded-lg shadow-lg flex items-center border border-green-200" 
                x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 3000)">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Sidebar -->
        <div class="sidebar fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full sm:translate-x-0 transition-transform duration-300 ease-in-out bg-gray-800">
            <div class="flex flex-col h-full">
                <div class="px-4 py-6 border-b border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center">
                            <span class="text-white text-sm font-medium">
                                {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'G' }}
                            </span>
                        </div>
                        <div class="text-white">
                            Hi, {{ auth()->user() ? auth()->user()->name : 'Guest' }}!
                        </div>
                    </div>
                </div>
                <div class="flex-1 px-2 py-4 space-y-1">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                                <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i> Admin Dashboard
                            </a>
                            <a href="{{ route('admin.users') }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                                <i class="fas fa-users w-5 h-5 mr-3"></i> Manage Users
                            </a>
                            <a href="{{route('books.create')}}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                                <i class="fas fa-plus-circle w-5 h-5 mr-3"></i> Add Book
                            </a>
                            <div class="border-t border-gray-700 my-4"></div>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                                <i class="fas fa-home w-5 h-5 mr-3"></i> Dashboard
                            </a>
                        @endif
                        
                        <a href="{{route('books.index')}}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                            <i class="fas fa-book w-5 h-5 mr-3"></i> Books
                        </a>
                        <a href="{{ route('user.favorite') }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                            <i class="fas fa-heart w-5 h-5 mr-3"></i> Favorites
                        </a>
                        <a href="{{ route('user.profile') }}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                            <i class="fas fa-user w-5 h-5 mr-3"></i> Profile
                        </a>
                        <a href="#" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                            <i class="fas fa-history w-5 h-5 mr-3"></i> History
                        </a>
                    @endauth
                </div>
                
                <div class="px-2 py-4 border-t border-gray-700">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 w-full">
                                <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{route('show.login')}}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                            <i class="fas fa-sign-in-alt w-5 h-5 mr-3"></i> Login
                        </a>
                        <a href="{{route('show.register')}}" class="group flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700">
                            <i class="fas fa-user-plus w-5 h-5 mr-3"></i> Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="sm:ml-64">
            <main class="py-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('.sidebar-toggle i');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                toggleBtn.classList.remove('fa-bars');
                toggleBtn.classList.add('fa-times');
            } else {
                sidebar.classList.add('-translate-x-full');
                toggleBtn.classList.remove('fa-times');
                toggleBtn.classList.add('fa-bars');
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 640) {  // sm breakpoint
                const sidebar = document.querySelector('.sidebar');
                const toggleBtn = document.querySelector('.sidebar-toggle');
                
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target) && !sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth >= 640) {  // sm breakpoint
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>
  