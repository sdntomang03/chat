<!DOCTYPE html>
<html lang="id">

<head>
    <title>Latihan Matematika</title> @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ══ ANIMASI GAME ══ */
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

        /* Background Polkadot Seru */
        body {
            background-color: #FFF3E0;
            background-image: radial-gradient(#FFCC80 2px, transparent 2px);
            background-size: 30px 30px;
        }

        /* Utility Animasi */
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

        /* Efek Input & Tombol */
        .ans-input-kids:focus {
            border-color: #FF9F43 !important;
            box-shadow: 0 0 15px rgba(255, 159, 67, .4) !important;
            outline: none;
            transform: scale(1.02);
        }

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

<body class="min-h-screen flex flex-col items-center justify-center p-4">

    @if(session('kontak_terbuka'))

    {{-- ========================================== --}}
    {{-- 1. TAMPILAN RAHASIA: DAFTAR KONTAK GURU --}}
    {{-- ========================================== --}}
    <div
        class="w-full max-w-md mx-auto bg-white rounded-2xl shadow-2xl p-6 border-t-8 border-blue-500 animate-[popIn_0.3s_ease-out]">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                Ruang Diskusi Guru
            </h1>

        </div>

        <ul class="space-y-3">
            @foreach($users as $user)
            <li
                class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100 hover:bg-blue-50 transition shadow-sm group">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-blue-700">{{ $user->name }}</span>
                </div>
                <a href="{{ route('chat.index', \Illuminate\Support\Facades\Crypt::encryptString($user->id)) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 font-bold shadow-md transition-transform active:scale-95">
                    Chat
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    @else

    {{-- ========================================== --}}
    {{-- 2. TAMPILAN KAMUFLASE: GAME MATEMATIKA --}}
    {{-- ========================================== --}}
    <div class="w-full max-w-sm">

        {{-- Konfeti container --}}
        <div id="confetti-zone" class="relative overflow-hidden h-0 transition-all duration-300"></div>

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
                    <path d="M33 56 Q45 66 57 56" stroke="#854F0B" stroke-width="3" stroke-linecap="round" fill="none"
                        id="mascot-mouth" />
                    <ellipse cx="28" cy="52" rx="5" ry="3.5" fill="#F0997B" opacity=".7" />
                    <ellipse cx="62" cy="52" rx="5" ry="3.5" fill="#F0997B" opacity=".7" />
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

        {{-- Kartu Soal Utama --}}
        <div
            class="bg-white rounded-3xl border-4 border-blue-400 p-6 shadow-[0_8px_0_0_rgba(96,165,250,1)] relative z-20">

            {{-- Dekorasi Emoji --}}
            <div class="flex justify-around mb-4">
                <span class="deco-item text-3xl drop-shadow-sm">🍎</span>
                <span class="deco-item text-3xl drop-shadow-sm">🍊</span>
                <span class="deco-item text-3xl drop-shadow-sm">🍓</span>
                <span class="deco-item text-3xl drop-shadow-sm">🍊</span>
                <span class="deco-item text-3xl drop-shadow-sm">🍎</span>
            </div>

            <p class="text-sm font-bold text-center text-blue-500 uppercase tracking-wider mb-2">Berapakah hasilnya?</p>

            {{-- Angka soal --}}
            <div class="text-center text-6xl font-black text-blue-700 tracking-widest mb-6 leading-none drop-shadow-sm">
                {{ $angka1 ?? 0 }}
                <span class="text-orange-500 animate-pulse inline-block">+</span>
                {{ $angka2 ?? 0 }}
            </div>

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

            {{-- Pesan Feedback Simulasi --}}
            @if(session('success'))
            @elseif(session('error'))
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

    {{-- Script Audio Web API khusus untuk Game --}}
    <script>
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

            function playClick() { playTone(600, 'sine', 0.1, 0.1); }
            function playType() { playTone(800, 'triangle', 0.05, 0.05); }
            function playErrorSound() {
                playTone(300, 'sawtooth', 0.2, 0.15);
                setTimeout(() => playTone(250, 'sawtooth', 0.3, 0.15), 150);
            }

            const inputField = document.getElementById('jawaban-input');
            const mascotText = document.getElementById('mascot-text');
            const mascotMouth = document.getElementById('mascot-mouth');

            if (inputField) {
                inputField.addEventListener('input', () => {
                    playType();
                    if(inputField.value.length > 0) {
                        mascotText.innerText = "Wah, sepertinya kamu sudah tahu! 🤩";
                        mascotMouth.setAttribute('d', 'M33 50 Q45 70 57 50');
                    } else {
                        mascotText.innerText = "Ayo, hitung pelan-pelan ya... 🤔";
                        mascotMouth.setAttribute('d', 'M33 56 Q45 66 57 56');
                    }
                });

                window.onload = () => {
                    setTimeout(() => inputField.focus(), 500);

                    @if(session('error'))
                        playErrorSound();
                        mascotText.innerText = "Jangan menyerah! Coba hitung lagi ya! 💪";
                        mascotMouth.setAttribute('d', 'M38 60 Q45 50 52 60');
                        document.getElementById('mascot').classList.add('result-animate-err');
                    @endif
                };
            }
    </script>
    @endif

</body>

</html>