<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | BPR XYZ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#0d1b2a] min-h-screen flex flex-col items-center justify-start relative px-4 py-8">

    <!-- Shadow / Gradient Background Layer -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#0d1b2a] via-[#1b263b] to-[#415a77] opacity-60 blur-2xl -z-10">
    </div>

    <!-- Header -->
    <header class="flex items-center justify-between w-full max-w-5xl mb-12 z-10">
        <div class="flex items-center space-x-4">
            <h1 class="text-white text-2xl md:text-3xl font-bold whitespace-nowrap">
                BPR XYZ
            </h1>
        </div>
    </header>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 z-10">
        <h2 class="text-3xl font-bold text-center mb-6 text-[#0d1b2a]">
            Lupa Password
        </h2>

        @if (session('status'))
            <x-alert type="info">
                <strong>Information:</strong> {{ session('status') }}
            </x-alert>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                    class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:ring-yellow-400 focus:border-yellow-400">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:ring-yellow-400 focus:border-yellow-400">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-medium">Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:ring-yellow-400 focus:border-yellow-400">
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-yellow-400 text-[#0d1b2a] font-semibold py-3 rounded-lg shadow hover:bg-yellow-300 transition mt-3">
                Reset Password
            </button>
        </form>
    </div>

</body>

</html>
