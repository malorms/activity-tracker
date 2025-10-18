<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Tracker - Register User</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="min-h-screen bg-gray-50">
    <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Activity Tracker</h1>
                <p class="text-sm text-gray-600">Support Team Management System</p>
            </div>
            <div class="flex items-center gap-4">
                @if(Auth::check())
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout.post') }}">
                        @csrf
                        <button 
                            type="submit" 
                            class="border border-red-600 text-red-600 hover:bg-red-50 font-medium px-4 py-2 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex gap-8" aria-label="Tabs">
                <a href="{{ route('dashboard') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm transition-colors 
                   {{ request()->routeIs('dashboard') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Daily Overview
                </a>
                <a href="{{ route('activities.create') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm transition-colors 
                   {{ request()->routeIs('activities.create') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Add Activity
                </a>
                <a href="{{ route('activities.index') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm transition-colors 
                   {{ request()->routeIs('activities.index') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Manage Activities
                </a>
                <a href="{{ route('reports') }}" 
                   class="py-4 px-1 border-b-2 font-medium text-sm transition-colors 
                   {{ request()->routeIs('reports') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Reports
                </a>
                @if (Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ route('register') }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm transition-colors 
                       {{ request()->routeIs('register') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Register User
                    </a>
                @endif
            </nav>
        </div>
    </div>

    <main class="max-w-6xl mx-auto py-10 space-y-6">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if ($errors->any() && !session('success'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Add User Form --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="flex flex-row items-center justify-between border-b border-gray-200 p-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Add New User</h2>
                    <p class="text-gray-500 text-sm">Create a new team member account</p>
                </div>
                <button id="toggleFormBtn"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                    Add User
                </button>
            </div>

            <div id="addUserForm" class="hidden p-6 border-t border-gray-200">
                <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="name" class="text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" placeholder="John Doe"
                                value="{{ old('name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            @error('name')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="john@example.com"
                                value="{{ old('email') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            @error('email')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>Team Member</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg">
                        Create User
                    </button>
                </form>
            </div>
        </div>

        {{-- Users List --}}
        <div class="bg-white rounded-xl shadow-sm">
            <div class="border-b border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900">Team Members</h2>
                <p class="text-gray-500 text-sm">All registered users in the system</p>
            </div>

            <div class="p-6 overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 text-gray-900 font-medium">{{ $user->name }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $user->email }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $user->role === 'admin' ? 'Administrator' : 'Team Member' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('toggleFormBtn').addEventListener('click', () => {
            const form = document.getElementById('addUserForm');
            form.classList.toggle('hidden');
            document.getElementById('toggleFormBtn').textContent =
                form.classList.contains('hidden') ? 'Add User' : 'Cancel';
        });
    </script>
</body>
</html>