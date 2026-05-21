<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Latihan Berhitung SD</title>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tambahkan font ramah anak -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body class="bg-sky-100 text-gray-800 flex flex-col min-h-screen">

    <!-- HEADER PINTU MASUK RAHASIA (Auth Links) -->
    <header class="w-full p-6 flex justify-end">
        @if (Route::has('login'))
        <nav class="flex gap-4">
            @auth
            <a href="{{ url('/dashboard') }}"
                class="text-sm font-semibold text-gray-500 hover:text-blue-600 transition">
                Dashboard Guru
            </a>
            @else
            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-400 hover:text-blue-500 transition">
                Masuk
            </a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}"
                class="text-sm font-semibold text-gray-400 hover:text-blue-500 transition">
                Daftar
            </a>
            @endif
            @endauth
        </nav>
        @endif
    </header>

    <!-- MAIN APP LATIHAN BERHITUNG -->
    <main class="flex-1 flex items-center justify-center p-4">
        <div class="bg-white max-w-lg w-full rounded-3xl shadow-xl overflow-hidden border-b-8 border-sky-400">

            <!-- Judul -->
            <div class="bg-sky-400 p-6 text-center text-white">
                <h1 class="text-3xl font-black mb-2 flex items-center justify-center gap-2">
                    <span>🧮</span> Ayo Berhitung!
                </h1>
                <p class="font-medium text-sky-100">Pilih mode latihanmu hari ini</p>
            </div>

            <div class="p-8 text-center">

                <!-- Pilihan Operasi Matematika -->
                <div class="flex justify-center gap-3 mb-8">
                    <button onclick="setMode('add', this)"
                        class="mode-btn active bg-sky-500 text-white w-12 h-12 rounded-xl text-2xl font-bold shadow-md transform transition hover:scale-110">+</button>
                    <button onclick="setMode('sub', this)"
                        class="mode-btn bg-gray-100 text-gray-500 hover:bg-sky-100 hover:text-sky-600 w-12 h-12 rounded-xl text-2xl font-bold transition transform hover:scale-110">-</button>
                    <button onclick="setMode('mul', this)"
                        class="mode-btn bg-gray-100 text-gray-500 hover:bg-sky-100 hover:text-sky-600 w-12 h-12 rounded-xl text-2xl font-bold transition transform hover:scale-110">×</button>
                    <button onclick="setMode('div', this)"
                        class="mode-btn bg-gray-100 text-gray-500 hover:bg-sky-100 hover:text-sky-600 w-12 h-12 rounded-xl text-2xl font-bold transition transform hover:scale-110">÷</button>
                </div>

                <!-- Area Soal -->
                <div
                    class="flex items-center justify-center gap-4 text-5xl font-black text-gray-700 mb-8 tracking-wider">
                    <span id="num1" class="text-sky-500">0</span>
                    <span id="operator" class="text-orange-400">+</span>
                    <span id="num2" class="text-sky-500">0</span>
                    <span class="text-gray-400">=</span>
                </div>

                <!-- Form Jawaban -->
                <form id="math-form" class="space-y-4">
                    <input type="number" id="answer" autocomplete="off" required placeholder="?"
                        class="w-32 text-center text-4xl font-bold px-4 py-3 bg-gray-50 border-4 border-gray-200 rounded-2xl focus:bg-white focus:outline-none focus:border-orange-400 transition-all text-gray-700 mx-auto block">

                    <button type="submit"
                        class="w-full bg-orange-400 text-white font-black text-xl py-4 rounded-2xl hover:bg-orange-500 hover:shadow-lg transform hover:-translate-y-1 transition-all mt-6">
                        Cek Jawaban!
                    </button>
                </form>

                <!-- Feedback Area (Benar / Salah) -->
                <div id="feedback" class="mt-6 text-lg font-bold min-h-[30px] transition-all">
                    Semangat latihannya! ✨
                </div>

                <!-- Skor -->
                <div
                    class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center text-gray-500 font-bold">
                    <span class="flex items-center gap-1">🌟 Skor: <span id="score"
                            class="text-sky-500 text-xl">0</span></span>
                    <button onclick="resetScore()"
                        class="text-xs text-red-400 hover:text-red-600 underline">Reset</button>
                </div>

            </div>
        </div>
    </main>

    <!-- LOGIKA PERMAINAN (Vanilla JavaScript) -->
    <script>
        let currentMode = 'add';
            let currentAnswer = 0;
            let score = 0;

            const num1El = document.getElementById('num1');
            const num2El = document.getElementById('num2');
            const operatorEl = document.getElementById('operator');
            const answerInput = document.getElementById('answer');
            const feedbackEl = document.getElementById('feedback');
            const scoreEl = document.getElementById('score');

            // Set Mode (+, -, x, /)
            function setMode(mode, btnElement) {
                currentMode = mode;

                // Ubah styling tombol
                document.querySelectorAll('.mode-btn').forEach(btn => {
                    btn.classList.remove('bg-sky-500', 'text-white', 'shadow-md');
                    btn.classList.add('bg-gray-100', 'text-gray-500');
                });
                btnElement.classList.remove('bg-gray-100', 'text-gray-500');
                btnElement.classList.add('bg-sky-500', 'text-white', 'shadow-md');

                generateQuestion();
                answerInput.focus();
            }

            // Acak Angka
            function rand(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            // Buat Soal Baru
            function generateQuestion() {
                let n1, n2;

                if (currentMode === 'add') {
                    n1 = rand(1, 50); n2 = rand(1, 50);
                    currentAnswer = n1 + n2;
                    operatorEl.textContent = '+';
                }
                else if (currentMode === 'sub') {
                    n1 = rand(1, 50); n2 = rand(1, 50);
                    // Pastikan tidak ada hasil minus untuk anak SD
                    if(n1 < n2) { let temp = n1; n1 = n2; n2 = temp; }
                    currentAnswer = n1 - n2;
                    operatorEl.textContent = '-';
                }
                else if (currentMode === 'mul') {
                    n1 = rand(1, 10); n2 = rand(1, 10);
                    currentAnswer = n1 * n2;
                    operatorEl.textContent = '×';
                }
                else if (currentMode === 'div') {
                    // Pastikan pembagian habis dibagi (bulat)
                    let a = rand(1, 10); let b = rand(1, 10);
                    n1 = a * b; n2 = a;
                    currentAnswer = b;
                    operatorEl.textContent = '÷';
                }

                num1El.textContent = n1;
                num2El.textContent = n2;
                answerInput.value = '';
                feedbackEl.textContent = 'Berapa ya hasilnya? 🤔';
                feedbackEl.className = 'mt-6 text-lg font-bold min-h-[30px] text-gray-400';
            }

            // Cek Jawaban Saat Form Disubmit
            document.getElementById('math-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const userAnswer = parseInt(answerInput.value);

                if (userAnswer === currentAnswer) {
                    // BENAR
                    feedbackEl.textContent = 'Hebat! Jawabanmu Benar! 🎉';
                    feedbackEl.className = 'mt-6 text-lg font-bold min-h-[30px] text-green-500 animate-bounce';
                    score += 10;
                    scoreEl.textContent = score;

                    // Buat soal baru setelah 1.5 detik
                    setTimeout(() => { generateQuestion(); answerInput.focus(); }, 1500);
                } else {
                    // SALAH
                    feedbackEl.textContent = 'Oops, masih kurang tepat. Coba lagi! 💪';
                    feedbackEl.className = 'mt-6 text-lg font-bold min-h-[30px] text-red-500';
                    if(score > 0) score -= 5;
                    scoreEl.textContent = score;

                    // Kosongkan input agar mencoba lagi
                    answerInput.value = '';
                    answerInput.focus();
                }
            });

            function resetScore() {
                score = 0;
                scoreEl.textContent = score;
                generateQuestion();
            }

            // Jalankan soal pertama kali halaman dimuat
            generateQuestion();
    </script>
</body>

</html>
