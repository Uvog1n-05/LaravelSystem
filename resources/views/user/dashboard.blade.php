<x-layout>
<div class="user-dashboard">
    <div class="user-header">
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
    </div>

    <div class="user-stats">
        <div class="stat-card">
            <h3>Your Books</h3>
            <p>{{ auth()->user()->books()->count() }}</p>
        </div>
        <div class="stat-card">
            <h3>Favorites</h3>
            <p>0</p>
        </div>
    </div>

    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-grid">
            <a href="{{ route('books.create') }}" class="action-card">
                <i class="fas fa-plus-circle"></i>
                <span>Add New Book</span>
            </a>
            <a href="{{ route('books.index') }}" class="action-card">
                <i class="fas fa-book"></i>
                <span>Browse Books</span>
            </a>
            <a href="{{ route('user.favorite') }}" class="action-card">
                <i class="fas fa-heart"></i>
                <span>My Favorites</span>
            </a>
            <a href="{{ route('user.profile') }}" class="action-card">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>
        </div>
    </div>
</div>
</x-layout>