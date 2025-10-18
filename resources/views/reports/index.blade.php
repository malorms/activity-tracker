<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Tracker - Activity Report</title>
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

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Activity Report</h2>
            <p class="text-gray-500 mb-6">Query activity history by date range and status</p>

            <form method="GET" action="{{ route('reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start" class="block text-sm font-medium">Start Date</label>
                    <input type="date" id="start" name="start" value="{{ request('start', date('Y-m-d', strtotime('-2 days'))) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="end" class="block text-sm font-medium">End Date</label>
                    <input type="date" id="end" name="end" value="{{ request('end', date('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium">Status</label>
                    <select id="status" name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option {{ request('status') == 'All' ? 'selected' : '' }} value="All">All</option>
                        <option {{ request('status') == 'Done' ? 'selected' : '' }} value="Done">Done</option>
                        <option {{ request('status') == 'Pending' ? 'selected' : '' }} value="Pending">Pending</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                        Generate Report
                    </button>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        @if($updates)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-600 text-sm font-medium">Total Records</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $updates->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-600 text-sm font-medium">Completed</p>
                    <p class="text-4xl font-bold text-green-600 mt-2">
                        {{ $updates->where('status', 'Done')->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-600 text-sm font-medium">Pending</p>
                    <p class="text-4xl font-bold text-orange-600 mt-2">
                        {{ $updates->where('status', 'Pending')->count() }}
                    </p>
                </div>
            </div>

            {{-- Activity Records --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Activity Records</h3>
                <p class="text-gray-500 mb-6">
                    Showing {{ $updates->count() }} record{{ $updates->count() != 1 ? 's' : '' }}
                    from {{ request('start') ? \Carbon\Carbon::parse(request('start'))->format('M j, Y') : '...' }} to {{ request('end') ? \Carbon\Carbon::parse(request('end'))->format('M j, Y') : '...' }}
                </p>

                @forelse($updates as $update)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $update->activity->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ \Carbon\Carbon::parse($update->date)->format('D, M j, Y') }}
                                </p>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $update->status == 'Done' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $update->status }}
                            </span>
                        </div>
                        <div class="mt-3 bg-gray-50 p-3 rounded space-y-2">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $update->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $update->user->email }}</p>
                                </div>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($update->created_at)->format('h:i A') }}</p>
                            </div>
                            <p class="text-sm text-gray-700">{{ $update->remark ?? 'No remark' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        No activities found for the selected date range and status.
                    </div>
                @endforelse
            </div>
        @else
            <div class="text-center text-gray-500 mt-8">
                Generate a report to view activity history.
            </div>
        @endif
    </main>
</body>
</html>