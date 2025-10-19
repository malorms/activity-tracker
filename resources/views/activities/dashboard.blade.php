<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Tracker - Daily Overview</title>
    @vite('resources/css/app.css')
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any() && !session('success'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow rounded-lg p-6 text-center">
                    <p class="text-gray-600 text-sm font-medium">Total Activities</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $activities->count() }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-6 text-center">
                    <p class="text-gray-600 text-sm font-medium">Completed</p>
                    <p class="text-4xl font-bold text-green-600 mt-2">{{ $activities->filter(fn($activity) => $activity->latestUpdate(today())?->status === 'Done')->count() }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-6 text-center">
                    <p class="text-gray-600 text-sm font-medium">Pending</p>
                    <p class="text-4xl font-bold text-orange-600 mt-2">{{ $activities->filter(fn($activity) => !$activity->latestUpdate(today()) || $activity->latestUpdate(today())?->status === 'Pending')->count() }}</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-1">Today's Activities - {{ now()->format('l, F j, Y') }}</h2>
                <p class="text-gray-500 mb-6">All activities and their updates for today</p>

                <div class="space-y-6">
                    @forelse ($activities as $activity)
                        @php $latestUpdate = $activity->latestUpdate(today()); @endphp
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 line-clamp-2">{{ $activity->name }}</h3>
                                    <p class="text-xs text-gray-600 mt-1">
                                        Created by {{ $activity->created_by ? $activity->created_by->name : 'Unknown' }} on {{ $activity->created_at->format('Y-m-d h:i A') }}
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
                            <div class="mt-4 space-y-3 bg-gray-50 p-3 rounded">
                                @if ($latestUpdate)
                                    <div class="text-sm">
                                        <div class="flex items-center justify-between mb-1">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $latestUpdate->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $latestUpdate->user->email }}</p>
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $latestUpdate->created_at->format('h:i A') }}</p>
                                        </div>
                                        <p class="text-gray-700 mt-1">{{ $latestUpdate->remark ?? 'No remark provided' }}</p>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">No updates for today.</p>
                                @endif
                            </div>
                            
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">No activities found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</body>
</html>