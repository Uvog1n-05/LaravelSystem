<x-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Admin Header -->
        <div class="relative overflow-hidden bg-white shadow-sm">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-blue-500/10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div class="flex items-center space-x-5">
                        <div class="relative">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-600 to-blue-600 flex items-center justify-center text-white text-2xl font-bold uppercase">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-4 border-white"></div>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                            <p class="mt-1 text-gray-500">System overview and management</p>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 flex items-center space-x-3">
                        <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Add New Book
                        </a>
                      
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Users</p>
                            <div class="mt-2 flex items-baseline">
                                <h3 class="text-3xl font-bold text-gray-900">{{ $users->count() }}</h3>
                                @php
                                    $newUsers = $users->where('created_at', '>=', now()->subMonth())->count();
                                @endphp
                                @if($newUsers > 0)
                                    <span class="ml-2 text-sm text-green-600">
                                        <i class="fas fa-arrow-up"></i>
                                        +{{ $newUsers }} this month
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="rounded-full p-3 bg-blue-100">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Admin Users Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Admin Users</p>
                            <div class="mt-2 flex items-baseline">
                                <h3 class="text-3xl font-bold text-gray-900">{{ $users->where('role', 'admin')->count() }}</h3>
                            </div>
                        </div>
                        <div class="rounded-full p-3 bg-purple-100">
                            <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Active Users Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active Users</p>
                            <div class="mt-2 flex items-baseline">
                                <h3 class="text-3xl font-bold text-gray-900">{{ $users->where('last_login', '>=', now()->subDays(7))->count() }}</h3>
                                <span class="ml-2 text-sm text-gray-600">this week</span>
                            </div>
                        </div>
                        <div class="rounded-full p-3 bg-yellow-100">
                            <i class="fas fa-user-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Administrative Actions</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <a href="{{ route('admin.users') }}" class="flex items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users-cog text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Manage Users</p>
                            <p class="text-sm text-gray-500">View and manage user accounts</p>
                        </div>
                        <div class="ml-auto">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.books') }}" class="flex items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book-open text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Manage Books</p>
                            <p class="text-sm text-gray-500">Add, edit, and organize books</p>
                        </div>
                        <div class="ml-auto">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </a>
                    
                   
                    
                    <a href="{{ route('admin.settings') }}" class="flex items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-cog text-gray-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Settings</p>
                            <p class="text-sm text-gray-500">Configure system preferences</p>
                        </div>
                        <div class="ml-auto">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                </div>
                <div class="divide-y divide-gray-100 overflow-hidden">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table-header">User</th>
                                <th class="table-header">Action</th>
                                <th class="table-header">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Add your activity logs here -->
                            <tr>
                                <td class="table-cell">System</td>
                                <td class="table-cell">Dashboard updated</td>
                                <td class="table-cell">Just now</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout>