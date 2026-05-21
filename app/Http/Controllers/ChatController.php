<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ChatController extends Controller
{
    // ==========================================
    // 1. HALAMAN DAFTAR KONTAK & KAMUFLASE BERHITUNG
    // ==========================================

    // METHOD INI HANYA BOLEH ADA SATU DI DALAM CLASS INI
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

    // Method untuk memeriksa jawaban dari form kuis
    public function unlockContacts(Request $request)
    {
        $request->validate(['jawaban' => 'required|numeric']);

        // Ambil angka kuis dari memori session server
        $angka1 = $request->session()->get('puzzle_angka1');
        $angka2 = $request->session()->get('puzzle_angka2');

        if (! $angka1 || ! $angka2) {
            return redirect()->route('chat.contacts');
        }

        $jawabanAsli = $angka1 + $angka2;
        $jawabanRahasia = $jawabanAsli + 5; // Rumus bypass rahasia
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
        // Menghapus tanda izin masuk saat klik tombol kembali
        $request->session()->forget('kontak_terbuka');
        $request->session()->forget('puzzle_angka1');
        $request->session()->forget('puzzle_angka2');

        return redirect()->route('chat.contacts');
    }

    // ==========================================
    // 2. LOGIKA RUANG CHAT & BROADCAST
    // ==========================================

    public function index($token)
    {
        try {
            // Dekripsi token URL menjadi ID angka asli
            $receiverId = Crypt::decryptString($token);
            $receiver = User::findOrFail($receiverId);
        } catch (DecryptException $e) {
            abort(404, 'Tautan chat tidak valid.');
        }

        $authId = Auth::id();

        // Ambil riwayat percakapan
        $messages = Message::where(function ($query) use ($authId, $receiver) {
            $query->where('sender_id', $authId)->where('receiver_id', $receiver->id);
        })->orWhere(function ($query) use ($authId, $receiver) {
            $query->where('sender_id', $receiver->id)->where('receiver_id', $authId);
        })->orderBy('created_at', 'asc')->get();

        return view('chat', compact('receiver', 'messages', 'token'));
    }

    public function sendMessage(Request $request, $token)
    {
        $request->validate(['content' => 'required|string']);

        try {
            $receiverId = Crypt::decryptString($token);
            $receiver = User::findOrFail($receiverId);
        } catch (DecryptException $e) {
            return response()->json(['error' => 'Invalid token'], 403);
        }

        // Simpan pesan ke DB (otomatis terenkripsi At-Rest lewat $casts di Model)
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'content' => $request->content,
        ]);

        // Pancarkan event real-time ke Reverb via PrivateChannel
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }
}
