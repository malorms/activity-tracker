<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Activity Tracker - Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">

  <div class="w-full max-w-md bg-white shadow-lg rounded-2xl overflow-hidden">
    <div class="p-6 space-y-2 border-b">
      <h1 class="text-3xl font-bold text-center">Activity Tracker</h1>
      <p class="text-center text-gray-600">Support Team Management System</p>
    </div>

    <div class="p-6">
      {{-- Flash Messages --}}
      @if (session('error'))
        <div class="text-sm text-red-600 bg-red-50 p-2 rounded mb-3">
          {{ session('error') }}
        </div>
      @endif

      @if (session('success'))
        <div class="text-sm text-green-600 bg-green-50 p-2 rounded mb-3">
          {{ session('success') }}
        </div>
      @endif

      <form method="POST" action="/login" id="loginForm" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div class="space-y-2">
          <label for="email" class="text-sm font-medium">Email Address</label>
          <input
            type="email"
            name="email"
            id="email"
            value="{{ old('email') }}"
            placeholder="john@example.com"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
          @error('email')
            <div class="text-sm text-red-600 bg-red-50 p-2 rounded">{{ $message }}</div>
          @enderror
        </div>

        {{-- Password --}}
        <div class="space-y-2">
          <label for="password" class="text-sm font-medium">Password</label>
          <input
            type="password"
            name="password"
            id="password"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
          @error('password')
            <div class="text-sm text-red-600 bg-red-50 p-2 rounded">{{ $message }}</div>
          @enderror
        </div>

        {{-- JS Error Display --}}
        <div id="error" class="hidden text-sm text-red-600 bg-red-50 p-2 rounded"></div>

        {{-- Submit --}}
        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition"
        >
          Login
        </button>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const form = document.getElementById("loginForm");
      const emailInput = document.getElementById("email");
      const passwordInput = document.getElementById("password");
      const errorDiv = document.getElementById("error");

      form.addEventListener("submit", (e) => {
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        if (!email || !password) {
          e.preventDefault();
          errorDiv.textContent = "Please fill in all fields";
          errorDiv.classList.remove("hidden");
          return;
        }

        if (!email.includes("@")) {
          e.preventDefault();
          errorDiv.textContent = "Please enter a valid email";
          errorDiv.classList.remove("hidden");
          return;
        }

        errorDiv.classList.add("hidden");
      });
    });
  </script>

</body>
</html>
