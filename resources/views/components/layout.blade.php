        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKS nav</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/css/user-dashboard.css'])

    
</head>
<body>
    <div class="site-wraper">
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-links">
                <span class="border-r-2" style="color: white; margin-bottom: 1rem;"> 
                    Hi there, {{ auth()->user() ? auth()->user()->name : 'Guest' }}!
                </span>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a>
                        <a href="{{ route('admin.users') }}"><i class="fas fa-users"></i> Manage Users</a>
                        <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); margin: 0.5rem 0;"></div>
                    @endif
                    <a href="{{route('books.index')}}"><i class="fas fa-book"></i> Books</a>
                    <a href="{{route('books.create')}}"><i class="fas fa-plus-circle"></i> Add Book</a>
                    <a href="{{ route('user.favorite') }}"><i class="fas fa-heart"></i> Favorites</a>
                    <a href="#"><i class="fas fa-history"></i> History</a>
                    <a href="{{ route('user.profile') }}"><i class="fas fa-user"></i> Profile</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="color: #e2e8f0; width: 100%; text-align: left; padding: 0.75rem 1rem; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                @endauth
                @guest 
                    <a href="{{route('show.login')}}" class="btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <a href="{{route('show.register')}}" class="btn"><i class="fas fa-user-plus"></i> Register</a>
                @endguest
            </div>
        </div>

        <nav class="main-nav">
            <header class=" header "></i>TMC LIBRARY</header>
        </nav>

            <script>
                function toggleSidebar() {
                    const sidebar = document.querySelector('.sidebar');
                    const toggleBtn = document.querySelector('.sidebar-toggle i');
                    
                    sidebar.classList.toggle('active');
                    
                    // Change icon based on sidebar state
                    if (sidebar.classList.contains('active')) {
                        toggleBtn.classList.remove('fa-bars');
                        toggleBtn.classList.add('fa-times');
                    } else {
                        toggleBtn.classList.remove('fa-times');
                        toggleBtn.classList.add('fa-bars');
                    }
                }

                // Close sidebar when clicking outside
                document.addEventListener('click', function(event) {
                    const sidebar = document.querySelector('.sidebar');
                    const toggleBtn = document.querySelector('.sidebar-toggle');
                    
                    if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target) && sidebar.classList.contains('active')) {
                        toggleSidebar();
                    }
                });
            </script>
        </nav>
        
        <main class="site-content">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
  