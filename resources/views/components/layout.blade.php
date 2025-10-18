    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=`, initial-scale=1.0">
        <title>BOOKS nav</title>

        @vite('resources/css/app.css')

    </head>
    <body>
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

            <nav >
                <header class="header">TMC LIBRARY</header>
                
                <a href="{{route('books.index')}}">Books</a>
                <a href="{{route('books.create')}}">Add Books</a>

                
                    <a href="{{route('show.login')}}" class="btn">Login</a>
                    <a href="{{route('show.register')}}" class="btn">Register</a>
                    <form action="{{ route('logout')}}" method="POST">
                        @csrf
                        <button class="btn">Logout</button>
                    </form>
            </nav>
        
        <main class="container">
            {{ $slot }}
        </main>
            
    </body>
    </html>