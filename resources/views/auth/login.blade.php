<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | BPR Parinama Simfoni Indonesia</title>
    {!! NoCaptcha::renderJs() !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#0d1b2a] min-h-screen flex flex-col items-center justify-start relative px-4 py-8">

    <!-- Shadow / Gradient Background Layer -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#0d1b2a] via-[#1b263b] to-[#415a77] opacity-60 blur-2xl -z-10">
    </div>

    <!-- Header -->
    <header class="flex items-center justify-between w-full max-w-5xl mb-12 z-10">
        <div class="flex items-center space-x-4">
            <img src="{{ Vite::asset('resources/images/Logo.png') }}" alt="Logo BPR Parinama" class="w-20 h-20">
            <h1 class="text-white text-2xl md:text-3xl font-bold whitespace-nowrap">
                BPR Parinama Simfoni Indonesia
            </h1>
        </div>
        <div class="flex items-center space-x-3">
            <!-- Beranda link ke welcome.blade.php -->
            <a href="{{ url('/') }}"
                class="border border-yellow-400 text-yellow-400 px-4 py-2 rounded-lg font-medium hover:bg-yellow-400 hover:text-[#0d1b2a] transition">
                Beranda
            </a>
            <a href="{{ route('register') }}"
                class="bg-yellow-400 text-[#0d1b2a] px-4 py-2 rounded-lg font-semibold hover:bg-yellow-300 transition">
                Register
            </a>
        </div>
    </header>

    <!-- Login Card -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 z-10">
        <h2 class="text-3xl font-bold text-center mb-6 text-[#0d1b2a]">
            Login
        </h2>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:ring-yellow-400 focus:border-yellow-400">
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:ring-yellow-400 focus:border-yellow-400">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input id="remember_me" type="checkbox" name="remember"
                    class="h-4 w-4 text-yellow-400 border-gray-300 rounded">
                <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>

            <!-- reCAPTCHA -->
            <div class="mb-4">
                {!! NoCaptcha::display() !!}
                @if ($errors->has('g-recaptcha-response'))
                    <span class="text-red-500 text-sm">
                        {{ $errors->first('g-recaptcha-response') }}
                    </span>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-yellow-400 text-[#0d1b2a] font-semibold py-3 rounded-lg shadow hover:bg-yellow-300 transition">
                Login
            </button>

            @if (Route::has('password.request'))
                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Forgot your password?
                    </a>
                </div>
            @endif
        </form>
    </div>

</body>

</html>
