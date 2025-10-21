<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TMC Library - Your Digital Library Management System">
    <title>TMC Library - Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="homepage">
    <div class="content-wrapper">
        <div class="welcome-container">
            <div class="welcome-header">
                <i class="fas fa-book-reader welcome-icon"></i>
                <h1>Welcome to TMC Library</h1>
            </div>
            
            <div class="welcome-content">
                <div class="welcome-message">
                    <h2>Your Digital Library Management System</h2>
                    <div class="feature-list">
                        <ul>
                            <li><i class="fas fa-book"></i> Browse our extensive collection</li>
                            <li><i class="fas fa-search"></i> Easy search functionality</li>
                            <li><i class="fas fa-heart"></i> Save your favorite books</li>
                            <li><i class="fas fa-history"></i> Track your borrowing history</li>
                        </ul>
                    </div>
                    
                    <div class="welcome-buttons">
                        <a href="{{ route('show.login') }}" class="btn">
                            <i class="fas fa-sign-in-alt"></i> Sign In
                        </a>
                        <a href="{{ route('show.register') }}" class="btn">
                            <i class="fas fa-user-plus"></i> Register

                            
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>