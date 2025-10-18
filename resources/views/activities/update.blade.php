<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Tracker - Update Activity</title>
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

    <main class="max-w-3xl mx-auto py-10 space-y-6">
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
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-900">Update Activity</h2>
                <p class="text-sm text-gray-600">{{ $activity->name }}</p>
                @if($activity->description)
                    <p class="text-sm text-gray-600">{{ $activity->description }}</p>
                @endif
                <p class="text-sm text-gray-600">
                    Created by {{ $activity->created_by ? $activity->created_by->name : 'Unknown' }} on {{ $activity->created_at->format('Y-m-d h:i A') }}
                </p>
            </div>
            <div class="p-6">
                <form id="updateForm" method="POST" action="{{ route('activities.update.patch', $activity->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-2">
                        <label for="status" class="text-sm font-medium">Update Status *</label>
                        <div class="flex gap-4">
                            @foreach(['Done', 'Pending'] as $status)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="{{ $status }}"
                                        {{ old('status', $activity->latestUpdate(today())?->status ?? 'Pending') === $status ? 'checked' : '' }}
                                        class="w-4 h-4"
                                        required
                                    />
                                    <span class="text-sm">{{ $status }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('status')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="remark" class="text-sm font-medium">Remark *</label>
                        <textarea
                            id="remark"
                            name="remark"
                            placeholder="Add your update remarks here..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="4"
                            required
                        >{{ old('remark') }}</textarea>
                        @error('remark')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg space-y-2">
                        <p class="text-sm font-medium text-gray-700">Update will be recorded as:</p>
                        <div class="text-sm text-gray-600">
                            <p><span class="font-medium">Name:</span> {{ Auth::user()->name ?? 'Guest' }}</p>
                            <p><span class="font-medium">Email:</span> {{ Auth::user()->email ?? 'guest@example.com' }}</p>
                            <p><span class="font-medium">Time:</span> {{ now()->format('Y-m-d h:i A') }}</p>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition"
                    >
                        Submit Update
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>