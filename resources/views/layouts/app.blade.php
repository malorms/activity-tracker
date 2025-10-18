<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Tracker</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex justify-between">
            <a href="/">Activity Tracker</a>
            @if (Auth::check())
                <div>
                    Welcome, {{ Auth::user()->name }}
                    <a href="/logout" class="ml-4">Logout</a>
                    @if (Auth::user()->role === 'admin')
                        <a href="/register" class="ml-4">Register User</a>
                    @endif
                    <a href="/activities/create" class="ml-4">Add New Activity</a>
                </div>
            @endif
        </div>
    </nav>
    <main class="container mx-auto p-6">
        @yield('content')
    </main>
</body>
</html>