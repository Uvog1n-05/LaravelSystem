<x-layout>
    <div class="container-fluid py-8">
        <div class="page-header">
            <h1 class="page-title">System Settings</h1>
            <p class="page-description">Configure system-wide settings</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- General Settings -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">General Settings</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">Site Name</label>
                                <input type="text" name="site_name" class="form-input" value="{{ config('app.name') }}">
                            </div>
                            <div>
                                <label class="form-label">Books Per Page</label>
                                <input type="number" name="books_per_page" class="form-input" value="12">
                            </div>
                            <div>
                                <label class="form-label">Allow User Registration</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="allow_registration" value="1" class="form-checkbox" checked>
                                        <span class="ml-2">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" name="allow_registration" value="0" class="form-checkbox">
                                        <span class="ml-2">No</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary">Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Maintenance Mode -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Maintenance Mode</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.maintenance') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">Maintenance Mode</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="maintenance_mode" value="0" class="form-checkbox" checked>
                                        <span class="ml-2">Off</span>
                                    </label>
                                    <label class="inline-flex items-center ml-6">
                                        <input type="radio" name="maintenance_mode" value="1" class="form-checkbox">
                                        <span class="ml-2">On</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Maintenance Message</label>
                                <textarea name="maintenance_message" rows="3" class="form-input">We are currently performing maintenance. Please check back soon.</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary">Update Maintenance Mode</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>