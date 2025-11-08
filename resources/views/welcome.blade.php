<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TMC Library - Your Digital Library Management System">
    <title>TMC Library - Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen relative">
    {{-- Background Image with Overlay --}}
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('img/tmc.jpg') }}" 
             alt="Library Background" 
             class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-black/50"></div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 flex min-h-screen flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 rounded-lg bg-white/90 backdrop-blur-sm p-8 shadow-lg border border-white/20">
            <div class="text-center">
                <i class="fas fa-book-reader text-6xl text-primary mb-6"></i>
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                    Welcome to TMC Library
                </h1>
                <h2 class="mt-4 text-xl text-gray-700">
                    Your Digital Library Management System
                </h2>
            </div>
            
            <div class="mt-8">
                <ul class="space-y-4">
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-book mr-3 text-primary"></i>
                        <span>Browse our extensive collection</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-search mr-3 text-primary"></i>
                        <span>Easy search functionality</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-heart mr-3 text-primary"></i>
                        <span>Save your favorite books</span>
                    </li>
                    <li class="flex items-center text-gray-700">
                        <i class="fas fa-history mr-3 text-primary"></i>
                        <span>Track your borrowing history</span>
                    </li>
                </ul>
                
                <div class="mt-8 flex flex-col space-y-4 sm:flex-row sm:space-x-4 sm:space-y-0">
                    <a href="{{ route('show.login') }}" 
                       class="flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In
                    </a>
                    <a href="{{ route('show.register') }}" 
                       class="flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-primary ring-1 ring-inset ring-primary hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        <i class="fas fa-user-plus mr-2"></i>
                        Register
                    </a>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/sidebar.js')
</body>
</html>