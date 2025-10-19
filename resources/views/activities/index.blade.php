<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Tracker - Manage Activities</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <h2 class="text-xl font-semibold text-gray-900">Manage Activities</h2>
                <p class="text-sm text-gray-600">Update or review your activities</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse ($activities as $activity)
                        @php $latestUpdate = $activity->latestUpdate(today()); @endphp
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 line-clamp-2">{{ $activity->name }}</h3>
                                    <p class="text-xs text-gray-600 mt-1">
                                        Created by {{ $activity->created_by ? $activity->created_by->name : 'Unknown' }} on {{ \Carbon\Carbon::parse($activity->created_at)->format('Y-m-d h:i A') }}
                                    </p>
                                    @if($activity->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                                    @endif
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $latestUpdate && $latestUpdate->status === 'Done' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ $latestUpdate ? $latestUpdate->status : 'Pending' }}
                                </span>
                            </div>
                            @if ($latestUpdate)
                                <div class="text-sm bg-gray-50 p-3 rounded">
                                    <div class="flex items-center justify-between mb-1">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $latestUpdate->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $latestUpdate->user->email }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500">{{ $latestUpdate->created_at->format('h:i A') }}</p>
                                    </div>
                                    <p class="text-gray-700 mt-1">{{ $latestUpdate->remark ?? 'No remark' }}</p>
                                </div>
                            @endif
                            <div class="mt-3">
                                <a href="{{ route('activities.update', $activity->id) }}" class="text-blue-600 hover:underline text-sm">Update Activity</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No activities found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</body>
</html>