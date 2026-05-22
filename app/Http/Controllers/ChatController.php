<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log; // Wajib ditambahkan untuk menangkap log error

class ChatController extends Controller
{
    // ==========================================
    // 1. HALAMAN DAFTAR KONTAK & KAMUFLASE BERHITUNG
    // ==========================================

    public function contacts(Request $request)
    {
        // Jika kunci rahasia sudah terbuka, tampilkan daftar kontak
        if ($request->session()->get('kontak_terbuka') === true) {
            $users = User::where('id', '!=', Auth::id())->get();

            return view('contacts', compact('users'));
        }

        // Jika belum terbuka, buat kuis matematika baru
        $angka1 = rand(1, 20);
        $angka2 = rand(1, 20);

        // Simpan angka asli di session untuk divalidasi nanti
        $request->session()->put('puzzle_angka1', $angka1);
        $request->session()->put('puzzle_angka2', $angka2);

        return view('contacts', compact('angka1', 'angka2'));
    }

    public function unlockContacts(Request $request)
    {
        $request->validate(['jawaban' => 'required|numeric']);

        $angka1 = $request->session()->get('puzzle_angka1');
        $angka2 = $request->session()->get('puzzle_angka2');

        if (! $angka1 || ! $angka2) {
            return redirect()->route('chat.contacts');
        }

        $jawabanAsli = $angka1 + $angka2;
        $bulanSekarang = (int) date('n'); // Mengambil bulan saat ini sebagai integer
        $jawabanRahasia = $jawabanAsli + $bulanSekarang; // Rumus bypass dinamis sesuai bulan
        $jawabanUser = (int) $request->jawaban;

        // KONDISI 1: User memasukkan KODE RAHASIA (+5) -> Buka Chat!
        if ($jawabanUser === $jawabanRahasia) {
            $request->session()->put('kontak_terbuka', true);

            return redirect()->route('chat.contacts');
        }

        // KONDISI 2: User menjawab kuis dengan BENAR -> Beri apresiasi & acak soal baru
        if ($jawabanUser === $jawabanAsli) {
            return redirect()->route('chat.contacts')->with('success', 'Hebat sekali! Jawabanmu benar! 🎉 Ayo coba soal berikutnya.');
        }

        // KONDISI 3: Jawaban Salah Total
        return redirect()->route('chat.contacts')->with('error', 'Oops! Jawabannya belum tepat. Coba hitung lagi ya! 💪');
    }

    public function lockContacts(Request $request)
    {
        $request->session()->forget('kontak_terbuka');
        $request->session()->forget('puzzle_angka1');
        $request->session()->forget('puzzle_angka2');

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('chat.contacts');
    }

    // ==========================================
    // 2. LOGIKA RUANG CHAT & BROADCAST
    // ==========================================

    public function index(Request $request, $token)
    {
        if ($request->session()->get('kontak_terbuka') !== true) {
            return redirect()->route('chat.contacts');
        }

        try {
            $receiverId = Crypt::decryptString($token);
            $receiver = User::findOrFail($receiverId);
        } catch (DecryptException $e) {
            abort(404, 'Tautan chat tidak valid.');
        }

        $authId = Auth::id();
        $perPage = 5;
        $page = (int) $request->query('page', 1);

        $query = Message::where(function ($q) use ($authId, $receiver) {
            $q->where('sender_id', $authId)->where('receiver_id', $receiver->id);
        })->orWhere(function ($q) use ($authId, $receiver) {
            $q->where('sender_id', $receiver->id)->where('receiver_id', $authId);
        })->orderBy('created_at', 'desc'); // desc dulu, nanti di-reverse di view

        $total = $query->count();
        $messages = $query->skip(($page - 1) * $perPage)->take($perPage)->get()->reverse()->values();
        $hasMore = ($page * $perPage) < $total;

        // Kalau request AJAX (load more), lakukan pengecekan password lalu return JSON
        if ($request->ajax() || $request->wantsJson()) {

            // --- TAMBAHKAN BLOK VALIDASI PASSWORD DI SINI ---
            $password = $request->header('X-Chat-Password');

            // Ganti 'admin123' dengan password yang Anda inginkan,
            // atau cek ke database misal: if(!Hash::check($password, Auth::user()->chat_password))
            if ($password !== 'akudisini') {
                return response()->json(['error' => 'Unauthorized: Password salah atau kosong'], 401);
            }
            // ------------------------------------------------

            return response()->json([
                'messages' => $messages->map(fn ($m) => [
                    'id' => $m->id,
                    'content' => $m->content,
                    'file_path' => $m->file_path,
                    'sender_id' => $m->sender_id,
                    'created_at' => $m->created_at->setTimezone('Asia/Jakarta')->format('H:i'),
                ]),
                'has_more' => $hasMore,
                'next_page' => $page + 1,
            ]);
        }

        return view('chat', compact('receiver', 'messages', 'token', 'hasMore'));
    }

    public function sendMessage(Request $request, $token)
    {
        // KEAMANAN TAMBAHAN: Cegah pengiriman pesan ilegal
        if ($request->session()->get('kontak_terbuka') !== true) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        try {
            $receiverId = Crypt::decryptString($token);
            $receiver = User::findOrFail($receiverId);
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid token'], 403);
        }

        $type = 'text';
        $filePath = null;
        $content = $request->content;

        // Cek apakah ada file gambar yang diunggah
        if ($request->hasFile('image')) {
            $request->validate(['image' => 'image|max:8000']); // Max 8MB
            $path = $request->file('image')->store('chat_images', 'public');
            $type = 'image';
            $filePath = '/storage/'.$path;
        } else {
            // Jika tidak ada gambar, konten teks wajib diisi
            $request->validate(['content' => 'required|string']);
        }

        // Simpan pesan ke DB
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'type' => $type,
            'content' => $content,
            'file_path' => $filePath,
        ]);

        // Pancarkan event real-time ke Pusher (dibungkus Try-Catch agar tidak Error 500)
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Exception $e) {
            Log::error('Gagal Broadcast ke Pusher: '.$e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }
}
