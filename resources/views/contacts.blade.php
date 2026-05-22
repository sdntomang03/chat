<!DOCTYPE html>
<html lang="id">

<head>
    <title>Latihan Matematika</title> <!-- Ubah title agar tidak mencurigakan -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<!-- Ubah background menjadi lebih ceria -->

<body class="bg-blue-50 p-10 min-h-screen flex items-center justify-center">

    @if(session('kontak_terbuka'))
    <!-- TAMPILAN: DAFTAR KONTAK (RAHASIA) -->
    <div class="w-full max-w-md mx-auto bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
        <h1 class="text-xl font-bold mb-4 text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
            Ruang Diskusi Guru
        </h1>
        <ul class="space-y-3">
            @foreach($users as $user)
            <li
                class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100 hover:bg-gray-100 transition">
                <span class="font-medium text-gray-700">{{ $user->name }}</span>
                <!-- Generate Token URL Rahasia -->
                <a href="{{ route('chat.index', \Illuminate\Support\Facades\Crypt::encryptString($user->id)) }}"
                    class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm hover:bg-blue-700 font-medium shadow-sm">
                    Kirim Pesan
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @else

    <div class="w-full max-w-md mx-auto bg-white rounded-2xl shadow-xl p-8 text-center border-b-8 border-orange-400">
        <!-- Ikon Lucu / Ilustrasi -->
        <div class="bg-orange-100 w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4">
            <span class="text-4xl">🧮</span>
        </div>

        <h1 class="text-2xl font-black text-gray-800 mb-2">Ayo Berhitung!</h1>
        <p class="text-gray-500 mb-8 font-medium">Berapakah hasil penjumlahan di bawah ini?</p>

        <!-- Angka dibuat lebih besar dan playful -->
        <div class="text-6xl font-extrabold mb-8 text-blue-600 tracking-wider">
            {{ $angka1 }} <span class="text-orange-400">+</span> {{ $angka2 }}
        </div>

        <form action="{{ route('chat.unlock') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <input type="number" name="jawaban" required autocomplete="off" placeholder="Tulis jawabanmu..."
                    class="w-full text-center text-2xl font-bold px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition-all text-gray-700">
            </div>

            <!-- Tombol disamarkan sebagai tombol periksa -->
            <button type="submit"
                class="w-full bg-orange-400 text-white font-bold text-lg py-4 rounded-xl hover:bg-orange-500 hover:shadow-lg transform hover:-translate-y-1 transition-all">
                Cek Jawaban!
            </button>
        </form>
        @if(session('success'))
        <div class="mt-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-semibold animate-pulse">
            {{ session('success') }}
        </div>
        <!-- Opsional: Efek Suara -->
        <audio autoplay src="https://assets.mixkit.co/active_storage/sfx/2000/2000-preview.mp3"></audio>
        @endif
        <!-- Pesan error juga disamarkan menjadi feedback positif -->
        @if(session('error'))
        <div class="mt-6 p-3 bg-red-50 text-red-600 rounded-lg font-medium animate-bounce">
            Oops, jawabannya belum tepat. Ayo coba soal baru! 💪
        </div>
        @else
        <!-- Pesan default jika belum menjawab -->
        <div class="mt-6 text-sm text-gray-400">
            Semangat terus latihannya!
        </div>
        @endif
    </div>
    @endif

</body>

</html>