<!DOCTYPE html>
<html lang="id">

<head>
    <title>Latihan Matematika</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes floatUp {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-10px)
            }
        }

        @keyframes floatUp2 {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-7px)
            }
        }

        @keyframes wiggle {

            0%,
            100% {
                transform: rotate(0deg)
            }

            25% {
                transform: rotate(-8deg)
            }

            75% {
                transform: rotate(8deg)
            }
        }

        @keyframes starPulse {

            0%,
            100% {
                transform: scale(1)
            }

            50% {
                transform: scale(1.3)
            }
        }

        @keyframes celebrate {
            0% {
                transform: scale(1)
            }

            30% {
                transform: scale(1.08)
            }

            60% {
                transform: scale(.96)
            }

            100% {
                transform: scale(1)
            }
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(-10px) rotate(0deg);
                opacity: 1
            }

            100% {
                transform: translateY(200px) rotate(360deg);
                opacity: 0
            }
        }

        @keyframes popIn {
            0% {
                transform: scale(0);
                opacity: 0
            }

            70% {
                transform: scale(1.12)
            }

            100% {
                transform: scale(1);
                opacity: 1
            }
        }

        body {
            background: #FFF3E0;
        }

        .hstar {
            animation: starPulse 1.4s ease-in-out infinite;
            display: inline-block;
        }

        .hstar:nth-child(2) {
            animation-delay: .2s
        }

        .hstar:nth-child(3) {
            animation-delay: .4s
        }

        .mascot-float {
            animation: floatUp 2.6s ease-in-out infinite;
            display: inline-block;
        }

        .deco-item:nth-child(1) {
            animation: floatUp 2.1s ease-in-out infinite;
            display: inline-block;
        }

        .deco-item:nth-child(2) {
            animation: floatUp2 2.8s ease-in-out infinite;
            display: inline-block;
            animation-delay: .3s;
        }

        .deco-item:nth-child(3) {
            animation: wiggle 2.4s ease-in-out infinite;
            display: inline-block;
        }

        .deco-item:nth-child(4) {
            animation: floatUp 2.5s ease-in-out infinite;
            display: inline-block;
            animation-delay: .5s;
        }

        .deco-item:nth-child(5) {
            animation: floatUp2 2.2s ease-in-out infinite;
            display: inline-block;
            animation-delay: .1s;
        }

        .prog-star {
            opacity: .25;
            transition: opacity .4s;
            display: inline-block;
        }

        .prog-star.earned {
            opacity: 1;
        }

        .confetti-piece {
            position: absolute;
            width: 9px;
            height: 9px;
            border-radius: 2px;
            animation: confettiFall 1.3s ease forwards;
            pointer-events: none;
        }

        .result-animate-ok {
            animation: celebrate .5s ease;
        }

        .result-animate-err {
            animation: wiggle .4s ease;
        }

        .ans-input-kids:focus {
            border-color: #FF9F43 !important;
            box-shadow: 0 0 0 4px rgba(255, 159, 67, .2) !important;
            outline: none;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">

    {{-- ══ HEADER ══ --}}
    <header class="bg-orange-400 px-5 py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex gap-1.5">
            <span class="hstar text-xl">⭐</span>
            <span class="hstar text-xl">⭐</span>
            <span class="hstar text-xl">⭐</span>
        </div>
        <span class="text-white font-medium text-sm">Latihan matematika</span>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-xs font-medium bg-yellow-300 text-yellow-900
                  px-3 py-1.5 rounded-full hover:bg-yellow-200 transition">
            🏠 Beranda
        </a>
    </header>

    {{-- ══ KONTEN ══ --}}
    <main class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            {{-- Konfeti container --}}
            <div id="confetti-zone" class="relative overflow-hidden h-0 transition-all duration-300"></div>

            {{-- Bintang progres --}}
            <div class="flex justify-center gap-2 mb-4">
                <span class="prog-star earned text-2xl" id="s1">⭐</span>
                <span class="prog-star earned text-2xl" id="s2">⭐</span>
                <span class="prog-star text-2xl" id="s3">⭐</span>
                <span class="prog-star text-2xl" id="s4">⭐</span>
                <span class="prog-star text-2xl" id="s5">⭐</span>
            </div>

            {{-- Maskot --}}
            <div class="flex justify-center mb-3">
                <div class="mascot-float">
                    <svg width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="45" cy="45" r="38" fill="#FFD580" />
                        <circle cx="32" cy="40" r="7" fill="#fff" />
                        <circle cx="58" cy="40" r="7" fill="#fff" />
                        <circle cx="34" cy="41" r="3.5" fill="#185FA5" />
                        <circle cx="60" cy="41" r="3.5" fill="#185FA5" />
                        <circle cx="35.5" cy="39.5" r="1.2" fill="#fff" />
                        <circle cx="61.5" cy="39.5" r="1.2" fill="#fff" />
                        <path d="M33 56 Q45 66 57 56" stroke="#854F0B" stroke-width="2.5" stroke-linecap="round"
                            fill="none" />
                        <ellipse cx="28" cy="52" rx="5" ry="3.5" fill="#F0997B" opacity=".7" />
                        <ellipse cx="62" cy="52" rx="5" ry="3.5" fill="#F0997B" opacity=".7" />
                        <path d="M20 30 Q16 20 22 18" stroke="#FF9F43" stroke-width="2.5" stroke-linecap="round"
                            fill="none" />
                        <path d="M70 30 Q74 20 68 18" stroke="#FF9F43" stroke-width="2.5" stroke-linecap="round"
                            fill="none" />
                        <circle cx="22" cy="17" r="3" fill="#FF9F43" />
                        <circle cx="68" cy="17" r="3" fill="#FF9F43" />
                    </svg>
                </div>
            </div>

            {{-- Balon ucapan maskot --}}
            <div class="flex justify-center mb-4">
                <div class="bg-white rounded-2xl border-2 border-yellow-300 px-4 py-2 inline-block">
                    <p class="text-sm font-medium text-yellow-900 text-center" id="mascot-text">
                        Halo! Ayo kita berhitung bersama! 😊
                    </p>
                </div>
            </div>

            {{-- Kartu soal --}}
            <div class="bg-white rounded-3xl border-2 border-yellow-300 p-6">

                {{-- Dekorasi buah/emoji --}}
                <div class="flex justify-around mb-4">
                    <span class="deco-item text-3xl">🍎</span>
                    <span class="deco-item text-3xl">🍊</span>
                    <span class="deco-item text-3xl">🍓</span>
                    <span class="deco-item text-3xl">🍊</span>
                    <span class="deco-item text-3xl">🍎</span>
                </div>

                <p class="text-xs text-center text-yellow-800 mb-2">Berapakah hasilnya?</p>

                {{-- Angka soal --}}
                <div class="text-center text-6xl font-bold text-blue-700 tracking-widest mb-6 leading-none">
                    {{ $angka1 }}
                    <span class="text-orange-400">+</span>
                    {{ $angka2 }}
                </div>

                {{-- Form jawaban --}}
                <form action="{{ route('chat.unlock') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="number" name="jawaban" required autocomplete="off" placeholder="Ketik jawabanmu..."
                        class="ans-input-kids w-full text-center text-2xl font-bold px-4 py-3
                                  bg-orange-50 border-2 border-yellow-300 rounded-2xl
                                  text-blue-700 transition-all">

                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-orange-400
                                   text-yellow-900 font-bold text-base py-3.5 rounded-2xl
                                   hover:bg-orange-500 hover:text-white transition-all active:scale-95">
                        ✅ Cek jawaban!
                    </button>
                </form>

                {{-- Pesan sukses --}}
                @if(session('success'))
                <div class="mt-4 flex items-center gap-2 p-3 bg-green-50 border-2 border-green-200
                            text-green-800 rounded-2xl text-sm font-medium result-animate-ok">
                    🎉 {{ session('success') }}
                </div>
                @endif

                {{-- Pesan error --}}
                @if(session('error'))
                <div class="mt-4 flex items-center gap-2 p-3 bg-red-50 border-2 border-red-200
                            text-red-800 rounded-2xl text-sm font-medium result-animate-err">
                    😅 Hmm, belum tepat nih. Coba lagi ya!
                </div>
                @else
                <p class="mt-4 text-xs text-center text-yellow-700">
                    Semangat terus, kamu pasti bisa! 💪
                </p>
                @endif
            </div>

        </div>
    </main>

    {{-- ══ FOOTER ══ --}}
    <footer class="bg-orange-400 px-5 py-2.5 flex items-center justify-center gap-2 flex-shrink-0">
        <span class="text-xs text-yellow-900 font-medium">🔒 Aman & terpercaya untuk anak-anak</span>
    </footer>

    {{-- ══ KONFETI JS (hanya muncul saat sukses) ══ --}}
    @if(session('success'))
    <script>
        (function(){
        const zone   = document.getElementById('confetti-zone');
        const COLORS = ['#FF9F43','#185FA5','#3B6D11','#D85A30','#7F77DD','#D4537E','#1D9E75'];
        zone.style.height = '120px';
        for(let i=0;i<32;i++){
            const el       = document.createElement('div');
            el.className   = 'confetti-piece';
            el.style.background       = COLORS[i % COLORS.length];
            el.style.left             = Math.random()*100+'%';
            el.style.top              = '0';
            el.style.animationDelay   = (Math.random()*.8)+'s';
            el.style.animationDuration= (.9+Math.random()*.7)+'s';
            el.style.borderRadius     = Math.random()>.5 ? '50%' : '3px';
            zone.appendChild(el);
            setTimeout(()=>el.remove(), 2000);
        }
        setTimeout(()=>{ zone.style.height='0'; }, 2200);

        // Nyalakan bintang progres (opsional — tambahkan logika skor ke session jika perlu)
        const stars = document.querySelectorAll('.prog-star');
        stars.forEach((s,i)=>{ s.classList.add('earned'); });
    })();
    </script>
    @endif

</body>

</html>