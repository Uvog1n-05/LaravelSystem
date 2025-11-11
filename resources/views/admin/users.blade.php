<x-layout>
<div class="admin-users bg-white p-6 rounded-lg shadow-sm">
    <div class="admin-header flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">User Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage user roles and permissions</p>
        </div>
        <div class="search-box">
            <div class="relative">
                <input type="text" id="userSearch" placeholder="Search users..." 
                    class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 rounded-lg p-4 mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="users-table overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.users.update-role', $user) }}" method="POST" class="inline-flex">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="handleRoleChange(this)" 
                                class="text-sm border border-gray-200 rounded-lg py-1 pl-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
// Simple client-side user search
document.getElementById('userSearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        let name = row.querySelector('td:first-child').textContent.toLowerCase();
        let email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

        if (name.includes(filter) || email.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Role change confirmation: ensures admin confirms elevation to admin
function handleRoleChange(select) {
    try {
        const newRole = select.value;
        const previous = select.dataset.previous || 'user';

        if (newRole === 'admin') {
            const ok = confirm('Are you sure you want to make this user an admin? This will grant them elevated privileges.');
            if (!ok) {
                // revert to previous value
                select.value = previous;
                return;
            }
        }

        // submit the form for permitted changes
        select.form.submit();
    } catch (e) {
        console.error('Error handling role change', e);
    }
}

// Remember previous value on focus / mousedown so we can revert if cancelled
document.querySelectorAll('select[name="role"]').forEach(function(sel) {
    sel.addEventListener('focus', function() {
        this.dataset.previous = this.value;
    });
    sel.addEventListener('mousedown', function() {
        this.dataset.previous = this.value;
    });
});
</script>
</x-layout>