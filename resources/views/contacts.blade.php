<!DOCTYPE html>
<html lang="id">

<head>
    <title>Latihan Matematika</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ══ HEADER ══ --}}
    <header class="bg-white border-b border-gray-100 px-6 py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800 leading-none">EduChat</p>
                <p class="text-xs text-gray-400 leading-none mt-0.5">Portal Komunikasi Guru</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if(session('kontak_terbuka'))
            {{-- Nav saat di halaman kontak --}}
            <span class="text-xs font-medium px-3 py-1.5 rounded-md bg-blue-50 text-blue-600 border border-blue-100">
                Pesan
            </span>
            <a href="{{ route('dashboard') }}"
                class="text-xs text-gray-500 px-3 py-1.5 rounded-md border border-gray-100 bg-gray-50 hover:bg-gray-100 transition">
                Beranda
            </a>
            @else
            {{-- Nav saat di halaman puzzle --}}
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-1.5 text-xs text-gray-500 px-3 py-1.5 rounded-md border border-gray-100 bg-gray-50 hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            @endif

            <div class="w-7 h-7 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
    </header>

    {{-- ══ KONTEN UTAMA ══ --}}
    <main class="flex-1 flex items-center justify-center px-4 py-10">

        @if(session('kontak_terbuka'))
        {{-- ══ HALAMAN DAFTAR KONTAK ══ --}}
        <div class="w-full max-w-md">

            {{-- Stat cards --}}
            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="bg-white rounded-xl border border-gray-100 p-3">
                    <p class="text-xl font-medium text-gray-800">{{ $users->count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total kontak</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-3">
                    <p class="text-xl font-medium text-green-600">{{ $users->count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Sedang online</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-3">
                    <p class="text-xl font-medium text-blue-600">—</p>
                    <p class="text-xs text-gray-400 mt-0.5">Pesan hari ini</p>
                </div>
            </div>

            {{-- Label --}}
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-3">Daftar kontak</p>

            {{-- Kontak --}}
            <div class="flex flex-col gap-2">
                @php
                $avatarColors = [
                ['bg-blue-50 text-blue-600', 'border-blue-100'],
                ['bg-green-50 text-green-600', 'border-green-100'],
                ['bg-yellow-50 text-yellow-600','border-yellow-100'],
                ['bg-red-50 text-red-600', 'border-red-100'],
                ['bg-purple-50 text-purple-600','border-purple-100'],
                ['bg-pink-50 text-pink-600', 'border-pink-100'],
                ];
                @endphp

                @foreach($users as $user)
                @php
                $ci = $loop->index % count($avatarColors);
                $avColor = $avatarColors[$ci][0];
                $nameParts = explode(' ', $user->name);
                $initials = strtoupper(substr($nameParts[0], 0, 1))
                . (isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '');
                @endphp
                <div
                    class="flex items-center gap-3 bg-white border border-gray-100 rounded-xl px-4 py-3 hover:bg-gray-50 transition">

                    {{-- Avatar --}}
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium flex-shrink-0 {{ $avColor }}">
                        {{ $initials }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $user->name }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 flex-shrink-0"></span>
                            <p class="text-xs text-gray-400 truncate">{{ $user->role ?? 'Guru' }}</p>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <a href="{{ route('chat.index', \Illuminate\Support\Facades\Crypt::encryptString($user->id)) }}"
                        class="flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg
                              bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 transition whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Kirim pesan
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        @else
        {{-- ══ HALAMAN PUZZLE / VERIFIKASI ══ --}}
        <div class="w-full max-w-sm">
            <div class="text-center mb-6">
                <div
                    class="w-12 h-12 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-base font-medium text-gray-800">Ayo berhitung!</h1>
                <p class="text-sm text-gray-400 mt-1">Selesaikan soal berikut untuk melanjutkan</p>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-8">
                <p class="text-xs text-gray-400 text-center mb-5 flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Berapakah hasilnya?
                </p>

                <div class="text-center text-5xl font-medium text-gray-800 tracking-wide mb-7">
                    {{ $angka1 }} <span class="text-blue-500">+</span> {{ $angka2 }}
                </div>

                <form action="{{ route('chat.unlock') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="number" name="jawaban" required autocomplete="off" placeholder="Tulis jawabanmu..."
                        class="w-full text-center text-xl font-medium px-4 py-3 bg-gray-50 border border-gray-200
                                  rounded-xl focus:bg-white focus:outline-none focus:border-blue-300
                                  focus:ring-2 focus:ring-blue-50 transition text-gray-700">

                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white
                                   font-medium text-sm py-3 rounded-xl hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Cek jawaban
                    </button>
                </form>

                @if(session('success'))
                <div
                    class="mt-4 flex items-center gap-2 p-3 bg-green-50 border border-green-100 text-green-700 rounded-xl text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div
                    class="mt-4 flex items-center gap-2 p-3 bg-red-50 border border-red-100 text-red-600 rounded-xl text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Jawaban belum tepat. Ayo coba soal baru!
                </div>
                @else
                <p class="mt-4 text-xs text-gray-400 text-center">Semangat terus latihannya!</p>
                @endif
            </div>
        </div>
        @endif

    </main>

    {{-- ══ FOOTER ══ --}}
    <footer class="bg-white border-t border-gray-100 px-6 py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span class="text-xs text-gray-300">Enkripsi end-to-end aktif</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-gray-300">EduChat v1.0</span>
            <a href="#" class="text-xs text-gray-300 hover:text-gray-500 transition">Kebijakan privasi</a>
        </div>
    </footer>

</body>

</html>