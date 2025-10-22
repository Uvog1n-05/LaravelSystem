<x-layout>
<div class="admin-dashboard">
    <div class="admin-header">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="admin-stats">
        <div class="stat-card">
            <h3>Total Users</h3>
            <p>{{ $users->count() }}</p>
        </div>
        <div class="stat-card">
            <h3>Admins</h3>
            <p>{{ $users->where('role', 'admin')->count() }}</p>
        </div>
    </div>

    <div class="admin-actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="{{ route('admin.users') }}" class="btn">Manage Users</a>
        </div>
    </div>
</div>
</x-layout>