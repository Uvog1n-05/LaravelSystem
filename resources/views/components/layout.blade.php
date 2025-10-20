        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKS nav</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="site-wraper">
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <nav>
            <header class="header">TMC LIBRARY</header>
            @auth
                <a href="{{route('books.index')}}">Books</a> 
            @endauth
            @guest 
                <a href="{{route('show.login')}}" class="btn">Login</a>
                <a href="{{route('show.register')}}" class="btn">Register</a>
            @endguest

            <span class="border-r-2"> 
                Hi there, {{ auth()->user() ? auth()->user()->name : 'Guest' }}!
            </span>
            
            @auth
                <a href="{{route('books.create')}}">Add Books</a>
                <form action="{{ route('logout')}}" method="POST">
                    @csrf
                    <button class="btn">Logout</button>
                </form>
            @endauth
        </nav>
        
        <main class="site-content">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
  