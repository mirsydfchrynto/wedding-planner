<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konsol Administrator — Tatanikah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#2B2520] text-white flex min-h-screen items-center justify-center p-4">
    <div class="w-full max-w-sm space-y-6">
        <div class="text-center">
            <span class="text-xs font-semibold text-[#B58A2B]">Portal Internal</span>
            <h1 class="text-2xl font-bold tracking-tight text-white mt-1">Konsol Administrator</h1>
            <p class="text-xs text-[#E7DBC7]/70 mt-1">Masuk dengan kredensial staf sistem.</p>
        </div>

        @if($errors->any())
            <div class="rounded-xl border border-[#9E352B]/40 bg-[#9E352B]/10 p-3.5 text-xs text-[#F7C5B8]">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="rounded-2xl border border-white/10 bg-[#352D25] p-6 shadow-xl">
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="mb-1.5 block text-xs font-semibold text-[#E7DBC7]">Email Administrator</label>
                    <input id="email" type="email" name="email" value="{{ old('email', 'admin@tatanikah.id') }}" required placeholder="admin@tatanikah.id" class="w-full rounded-xl border border-white/15 bg-black/30 px-3.5 py-2 text-xs text-white placeholder-white/30 focus:border-[#B58A2B] focus:outline-none">
                </div>
                <div>
                    <label for="password" class="mb-1.5 block text-xs font-semibold text-[#E7DBC7]">Kata Sandi</label>
                    <input id="password" type="password" name="password" value="admin" required class="w-full rounded-xl border border-white/15 bg-black/30 px-3.5 py-2 text-xs text-white placeholder-white/30 focus:border-[#B58A2B] focus:outline-none">
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full rounded-xl bg-[#8C5835] py-2.5 text-xs font-bold text-white transition hover:bg-[#73472B] cursor-pointer shadow-sm">
                        Masuk ke Konsol
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-[11px] text-[#E7DBC7]/50">
            Akses publik login pengantin hanya melalui <a href="{{ route('login') }}" class="text-[#B58A2B] hover:underline">Portal Google Sign-In</a>.
        </p>
    </div>
</body>
</html>
