<!DOCTYPE html>
<html lang="id">

<head>
    <title>Latihan Matematika</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Animasi Bawaan */
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

        /* Background Polkadot Seru ala Game */
        body {
            background-color: #FFF3E0;
            background-image: radial-gradient(#FFCC80 2px, transparent 2px);
            background-size: 30px 30px;
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
            transition: opacity .4s, transform .3s;
            display: inline-block;
        }

        .prog-star.earned {
            opacity: 1;
            transform: scale(1.2) rotate(15deg);
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

        /* Input Bercahaya */
        .ans-input-kids:focus {
            border-color: #FF9F43 !important;
            box-shadow: 0 0 15px rgba(255, 159, 67, .4) !important;
            outline: none;
            transform: scale(1.02);
        }

        /* Efek Tombol 3D Game */
        .btn-3d {
            border-bottom-width: 6px;
            transition: all 0.1s;
        }

        .btn-3d:active {
            border-bottom-width: 0px;
            transform: translateY(6px);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">

    {{-- ══ HEADER ══ --}}
    <header class="bg-orange-400 px-5 py-3 flex items-center justify-between flex-shrink-0 shadow-md relative z-10">
        <div class="flex gap-1.5">
            <span class="hstar text-xl">⭐</span>
            <span class="hstar text-xl">⭐</span>
            <span class="hstar text-xl">⭐</span>
        </div>
        <span class="text-white font-bold text-lg tracking-wide drop-shadow-md">Kuis Matematika!</span>
        <a href="{{ route('dashboard') }}" onclick="playClick()"
            class="flex items-center gap-1.5 text-xs font-bold bg-yellow-300 text-yellow-900 px-4 py-2 rounded-full border-b-4 border-yellow-500 active:border-b-0 active:translate-y-1 transition-all">
            🏠 Keluar
        </a>
    </header>

    {{-- ══ KONTEN ══ --}}
    <main class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-sm">

            {{-- Konfeti container --}}
            <div id="confetti-zone" class="relative overflow-hidden h-0 transition-all duration-300"></div>

            {{-- Bintang progres --}}
            <div class="flex justify-center gap-2 mb-4">
                <span class="prog-star earned text-3xl drop-shadow-md" id="s1">⭐</span>
                <span class="prog-star earned text-3xl drop-shadow-md" id="s2">⭐</span>
                <span class="prog-star text-3xl drop-shadow-md" id="s3">⭐</span>
                <span class="prog-star text-3xl drop-shadow-md" id="s4">⭐</span>
                <span class="prog-star text-3xl drop-shadow-md" id="s5">⭐</span>
            </div>

            {{-- Maskot --}}
            <div class="flex justify-center mb-2">
                <div class="mascot-float drop-shadow-lg" id="mascot">
                    <svg width="100" height="100" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="45" cy="45" r="38" fill="#FFD580" />
                        <circle cx="32" cy="40" r="7" fill="#fff" />
                        <circle cx="58" cy="40" r="7" fill="#fff" />
                        <circle cx="34" cy="41" r="3.5" fill="#185FA5" />
                        <circle cx="60" cy="41" r="3.5" fill="#185FA5" />
                        <circle cx="35.5" cy="39.5" r="1.2" fill="#fff" />
                        <circle cx="61.5" cy="39.5" r="1.2" fill="#fff" />
                        <path d="M33 56 Q45 66 57 56" stroke="#854F0B" stroke-width="3" stroke-linecap="round"
                            fill="none" id="mascot-mouth" />
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
            <div class="flex justify-center mb-6 relative">
                <div class="bg-white rounded-2xl border-4 border-yellow-400 px-5 py-2 inline-block shadow-lg">
                    <div
                        class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-[10px] border-l-transparent border-r-[10px] border-r-transparent border-b-[12px] border-b-yellow-400">
                    </div>
                    <p class="text-sm font-bold text-yellow-900 text-center" id="mascot-text">
                        Halo! Ayo kita berhitung! 😊
                    </p>
                </div>
            </div>

            {{-- Kartu soal --}}
            <div
                class="bg-white rounded-3xl border-4 border-blue-400 p-6 shadow-[0_8px_0_0_rgba(96,165,250,1)] relative z-20">

                {{-- Dekorasi buah/emoji --}}
                <div class="flex justify-around mb-4">
                    <span class="deco-item text-3xl drop-shadow-sm">🍎</span>
                    <span class="deco-item text-3xl drop-shadow-sm">🍊</span>
                    <span class="deco-item text-3xl drop-shadow-sm">🍓</span>
                    <span class="deco-item text-3xl drop-shadow-sm">🍊</span>
                    <span class="deco-item text-3xl drop-shadow-sm">🍎</span>
                </div>

                <p class="text-sm font-bold text-center text-blue-500 uppercase tracking-wider mb-2">Berapakah hasilnya?
                </p>

                {{-- Angka soal --}}
                <div
                    class="text-center text-6xl font-black text-blue-700 tracking-widest mb-6 leading-none drop-shadow-sm">
                    {{ $angka1 }}
                    <span class="text-orange-500 animate-pulse inline-block">+</span>
                    {{ $angka2 }}
                </div>

                {{-- Form jawaban --}}
                <form action="{{ route('chat.unlock') }}" method="POST" class="space-y-4" onsubmit="playClick()">
                    @csrf
                    <input type="number" name="jawaban" id="jawaban-input" required autocomplete="off"
                        placeholder="Ketik jawabanmu..."
                        class="ans-input-kids w-full text-center text-3xl font-black px-4 py-4
                                 bg-orange-50 border-4 border-orange-300 rounded-2xl
                                 text-blue-800 transition-all placeholder:text-lg placeholder:font-medium placeholder:text-orange-300">

                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-green-500 border-green-600 btn-3d
                                   text-white font-black text-xl py-4 rounded-2xl
                                   hover:bg-green-400 transition-all cursor-pointer">
                        ✅ CEK JAWABAN!
                    </button>
                </form>

                {{-- Pesan sukses --}}
                @if(session('success'))
                <div class="mt-6 flex items-center justify-center gap-2 p-3 bg-green-100 border-2 border-green-400
                            text-green-800 rounded-2xl text-base font-bold result-animate-ok shadow-inner">
                    🎉 {{ session('success') }}
                </div>
                @endif

                {{-- Pesan error --}}
                @if(session('error'))
                <div class="mt-6 flex items-center justify-center gap-2 p-3 bg-red-100 border-2 border-red-400
                            text-red-800 rounded-2xl text-base font-bold result-animate-err shadow-inner">
                    😅 Ups, coba hitung lagi!
                </div>
                @else
                <p class="mt-4 text-xs font-bold text-center text-gray-400 uppercase tracking-widest">
                    Semangat terus! 💪
                </p>
                @endif
            </div>

        </div>
    </main>

    {{-- ══ SCRIPT AUDIO & ANIMASI ══ --}}
    <script>
        // --- SISTEM AUDIO (Web Audio API) ---
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

        function playTone(freq, type, duration, vol) {
            if(audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            osc.type = type;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);

            gainNode.gain.setValueAtTime(vol, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration);

            osc.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        }

        // Suara tombol diklik / ketik
        function playClick() { playTone(600, 'sine', 0.1, 0.1); }
        function playType() { playTone(800, 'triangle', 0.05, 0.05); }

        // Suara Benar (Tada!)
        function playSuccessSound() {
            playTone(440, 'sine', 0.1, 0.2); // A
            setTimeout(() => playTone(554, 'sine', 0.1, 0.2), 100); // C#
            setTimeout(() => playTone(659, 'sine', 0.3, 0.2), 200); // E
        }

        // Suara Salah (Boing)
        function playErrorSound() {
            playTone(300, 'sawtooth', 0.2, 0.15);
            setTimeout(() => playTone(250, 'sawtooth', 0.3, 0.15), 150);
        }

        // --- INTERAKTIVITAS INPUT ---
        const inputField = document.getElementById('jawaban-input');
        const mascotText = document.getElementById('mascot-text');
        const mascotMouth = document.getElementById('mascot-mouth');

        inputField.addEventListener('input', () => {
            playType();
            // Ubah ekspresi maskot saat anak mengetik
            if(inputField.value.length > 0) {
                mascotText.innerText = "Wah, sepertinya kamu sudah tahu! 🤩";
                mascotMouth.setAttribute('d', 'M33 50 Q45 70 57 50'); // Senyum lebih lebar
            } else {
                mascotText.innerText = "Ayo, hitung pelan-pelan ya... 🤔";
                mascotMouth.setAttribute('d', 'M33 56 Q45 66 57 56'); // Normal
            }
        });

        // --- CEK SESSION DARI LARAVEL ---
        window.onload = () => {
            // Aktifkan fokus ke input otomatis
            setTimeout(() => inputField.focus(), 500);

            @if(session('success'))
                // Logika saat jawaban benar
                playSuccessSound();
                mascotText.innerText = "YAY! Jawabanmu TEPAT SEKALI! 🥳";
                mascotMouth.setAttribute('d', 'M33 50 Q45 70 57 50');

                // Animasi Konfeti
                const zone = document.getElementById('confetti-zone');
                const COLORS = ['#FF9F43','#185FA5','#3B6D11','#D85A30','#7F77DD','#D4537E','#1D9E75', '#FDE047'];
                zone.style.height = '150px';
                for(let i=0; i<45; i++){
                    const el = document.createElement('div');
                    el.className = 'confetti-piece';
                    el.style.background = COLORS[i % COLORS.length];
                    el.style.left = Math.random()*100+'%';
                    el.style.top = '0';
                    el.style.animationDelay = (Math.random()*0.5)+'s';
                    el.style.animationDuration = (1+Math.random())+'s';
                    el.style.borderRadius = Math.random()>.5 ? '50%' : '3px';
                    zone.appendChild(el);
                    setTimeout(() => el.remove(), 2000);
                }
                setTimeout(() => { zone.style.height='0'; }, 2200);

                // Tambah Bintang (Simulasi)
                document.getElementById('s3').classList.add('earned');
            @endif

            @if(session('error'))
                // Logika saat jawaban salah
                playErrorSound();
                mascotText.innerText = "Jangan menyerah! Coba hitung lagi ya! 💪";
                mascotMouth.setAttribute('d', 'M38 60 Q45 50 52 60'); // Ekspresi sedih/oops
                document.getElementById('mascot').classList.add('result-animate-err');
            @endif
        };
    </script>

</body>

</html>