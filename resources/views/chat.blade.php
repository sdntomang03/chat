<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat dengan {{ $receiver->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #111b21;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', system-ui, sans-serif;
            overflow: hidden;
        }

        /* ══ Header ══ */
        .wa-header {
            background: #202c33;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .wa-back {
            color: #00a884;
            font-size: 22px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .wa-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #00a884;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 600;
            color: white;
            flex-shrink: 0;
        }

        .wa-info {
            flex: 1;
        }

        .wa-name {
            color: #e9edef;
            font-size: 16px;
            font-weight: 500;
            line-height: 1.2;
        }

        .wa-status {
            color: #8696a0;
            font-size: 12px;
        }

        .wa-icons {
            display: flex;
            gap: 20px;
            color: #aebac1;
            font-size: 20px;
        }

        /* ══ Chat Area ══ */
        #chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 3px;
            /* Wallpaper titik-titik WhatsApp */
            background-color: #0b141a;
            background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23182229' fill-opacity='0.6'%3E%3Cpath d='M0 0h4v4H0zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zM0 10h4v4H0zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zM0 20h4v4H0zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4zm10 0h4v4h-4z'/%3E%3C/g%3E%3C/svg%3E");
        }

        #chat-box::-webkit-scrollbar {
            width: 4px;
        }

        #chat-box::-webkit-scrollbar-thumb {
            background: #2a3942;
            border-radius: 4px;
        }

        .date-chip {
            text-align: center;
            margin: 8px 0;
        }

        .date-chip span {
            background: #182229;
            color: #8696a0;
            font-size: 11.5px;
            padding: 5px 12px;
            border-radius: 8px;
        }

        /* ══ Bubbles ══ */
        .bubble-row {
            display: flex;
            margin: 1px 0;
        }

        .bubble-row.me {
            justify-content: flex-end;
        }

        .bubble-row.other {
            justify-content: flex-start;
        }

        .bubble {
            max-width: 72%;
            padding: 7px 10px 0;
            border-radius: 8px;
            font-size: 14.5px;
            line-height: 1.45;
            position: relative;
            word-break: break-word;
        }

        .bubble.me {
            background: #005c4b;
            color: #e9edef;
            border-bottom-right-radius: 2px;
        }

        .bubble.other {
            background: #202c33;
            color: #e9edef;
            border-bottom-left-radius: 2px;
        }

        /* Ekor gelembung */
        .bubble.me::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: -7px;
            border: 7px solid transparent;
            border-left-color: #005c4b;
            border-bottom-color: #005c4b;
            border-right: 0;
            border-top: 0;
        }

        .bubble.other::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: -7px;
            border: 7px solid transparent;
            border-right-color: #202c33;
            border-bottom-color: #202c33;
            border-left: 0;
            border-top: 0;
        }

        /*
         * FIX WAKTU: bubble-footer memakai flexbox justify-content: flex-end
         * sehingga waktu selalu terlihat di pojok kanan bawah bubble.
         * Padding-bottom 5px memberi ruang agar tidak terpotong.
         */
        .bubble-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 4px;
            margin-top: 3px;
            padding-bottom: 5px;
        }

        .bubble-time {
            font-size: 11px;
            color: #8696a0;
            white-space: nowrap;
            line-height: 1;
        }

        .checks {
            font-size: 14px;
            color: #53bdeb;
            /* centang biru = sudah dibaca */
            line-height: 1;
        }

        /* Foto di dalam bubble */
        .bubble-img {
            display: block;
            width: 100%;
            max-width: 260px;
            border-radius: 6px;
            margin-bottom: 4px;
            cursor: zoom-in;
        }

        /* ══ Preview Gambar ══ */
        #preview-bar {
            display: none;
            background: #182229;
            padding: 8px 16px;
            align-items: center;
            gap: 12px;
            border-top: 1px solid #2a3942;
            flex-shrink: 0;
        }

        #preview-bar.show {
            display: flex;
        }

        #preview-thumb {
            width: 54px;
            height: 54px;
            border-radius: 6px;
            object-fit: cover;
            border: 2px solid #00a884;
        }

        #preview-info {
            flex: 1;
        }

        #preview-name {
            color: #e9edef;
            font-size: 13px;
        }

        #preview-size {
            color: #8696a0;
            font-size: 11px;
        }

        #preview-cancel {
            background: none;
            border: none;
            color: #8696a0;
            font-size: 22px;
            cursor: pointer;
            line-height: 1;
            padding: 4px;
        }

        #preview-cancel:hover {
            color: #e9edef;
        }

        /* ══ Emoji Picker ══ */
        #emoji-picker {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            background: #233138;
            border-top: 1px solid #2a3942;
            padding: 10px 12px;
            flex-wrap: wrap;
            gap: 2px;
            max-height: 220px;
            overflow-y: auto;
            z-index: 50;
        }

        #emoji-picker.show {
            display: flex;
        }

        #emoji-picker::-webkit-scrollbar {
            width: 3px;
        }

        #emoji-picker::-webkit-scrollbar-thumb {
            background: #2a3942;
        }

        .ep-category {
            width: 100%;
            font-size: 11px;
            color: #8696a0;
            padding: 6px 2px 3px;
            letter-spacing: .04em;
        }

        .emoji-btn {
            font-size: 22px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 5px;
            border-radius: 6px;
            line-height: 1;
            transition: background .1s;
        }

        .emoji-btn:hover {
            background: #2a3942;
        }

        /* ══ Input Bar ══ */
        .input-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .wa-input-bar {
            background: #202c33;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .wa-icon-btn {
            background: none;
            border: none;
            color: #8696a0;
            font-size: 24px;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            transition: color .15s;
        }

        .wa-icon-btn:hover {
            color: #d1d7db;
        }

        .wa-text-wrap {
            flex: 1;
            background: #2a3942;
            border-radius: 24px;
            display: flex;
            align-items: center;
            padding: 7px 14px;
            gap: 8px;
        }

        #message-input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: #d1d7db;
            font-size: 15px;
            font-family: inherit;
        }

        #message-input::placeholder {
            color: #8696a0;
        }

        .wa-send-btn {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #00a884;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 20px;
            color: white;
            transition: background .15s, transform .1s;
        }

        .wa-send-btn:hover {
            background: #02b891;
        }

        .wa-send-btn:active {
            transform: scale(.95);
        }

        /* ══ Lightbox ══ */
        #lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .9);
            z-index: 999;
            align-items: center;
            justify-content: center;
            cursor: zoom-out;
        }

        #lightbox.open {
            display: flex;
        }

        #lightbox img {
            max-width: 94vw;
            max-height: 94vh;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    {{-- ══ Header ══ --}}
    <div class="wa-header">
        <a class="wa-back" href="{{ route('chat.lock_session') }}">&#8592;</a>
        <div class="wa-avatar">
            {{ strtoupper(substr($receiver->name, 0, 2)) }}
        </div>
        <div class="wa-info">
            <div class="wa-name">{{ $receiver->name }}</div>
            <div class="wa-status" id="status-text">Online</div>
        </div>
        <div class="wa-icons">
            <span title="Video call">&#128247;</span>
            <span title="Telepon">&#128222;</span>
            <span title="Menu">&#8942;</span>
        </div>
    </div>

    {{-- ══ Pesan ══ --}}
    <div id="chat-box">
        <div class="date-chip">
            <span>{{ now()->translatedFormat('d F Y') }}</span>
        </div>

        @foreach($messages as $msg)
        <div class="bubble-row {{ $msg->sender_id === Auth::id() ? 'me' : 'other' }}">
            <div class="bubble {{ $msg->sender_id === Auth::id() ? 'me' : 'other' }}">

                {{-- Foto --}}
                @if($msg->type === 'image' && $msg->image_path)
                <img class="bubble-img" src="{{ asset('storage/' . $msg->image_path) }}" alt="foto"
                    onclick="openLightbox(this.src)">
                @endif

                {{-- Teks --}}
                @if($msg->content)
                <span>{{ $msg->content }}</span>
                @endif

                {{-- ✅ WAKTU — selalu tampil di baris footer, tidak tertutupi teks --}}
                <div class="bubble-footer">
                    <span class="bubble-time">
                        {{ $msg->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}
                    </span>
                    @if($msg->sender_id === Auth::id())
                    <span class="checks">&#10003;&#10003;</span>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>

    {{-- ══ Preview Gambar ══ --}}
    <div id="preview-bar">
        <img id="preview-thumb" src="" alt="preview">
        <div id="preview-info">
            <div id="preview-name"></div>
            <div id="preview-size"></div>
        </div>
        <button id="preview-cancel">&#10005;</button>
    </div>

    {{-- ══ Emoji + Input ══ --}}
    <div class="input-wrapper">
        <div id="emoji-picker"></div>

        <div class="wa-input-bar">
            <button class="wa-icon-btn" id="emoji-toggle-btn" title="Emoji">&#128522;</button>

            <div class="wa-text-wrap">
                <input type="text" id="message-input" placeholder="Pesan" autocomplete="off">
                <button class="wa-icon-btn" id="attach-btn" style="font-size:20px" title="Kirim foto">&#128247;</button>
                <input type="file" id="file-input" accept="image/jpeg,image/png,image/gif,image/webp"
                    style="display:none">
            </div>

            <button class="wa-send-btn" id="send-btn">&#10148;</button>
        </div>
    </div>

    {{-- ══ Lightbox ══ --}}
    <div id="lightbox" onclick="this.classList.remove('open')">
        <img id="lightbox-img" src="" alt="foto">
    </div>

    <script type="module">
        /* ═══ Variabel dari Controller ═══ */
        const chatToken  = '{{ $token }}';
        const receiverId = {{ $receiver->id }};
        const authId     = {{ Auth::id() }};

        const chatBox      = document.getElementById('chat-box');
        const inputEl      = document.getElementById('message-input');
        const sendBtn      = document.getElementById('send-btn');
        const fileInput    = document.getElementById('file-input');
        const attachBtn    = document.getElementById('attach-btn');
        const emojiToggle  = document.getElementById('emoji-toggle-btn');
        const emojiPicker  = document.getElementById('emoji-picker');
        const previewBar   = document.getElementById('preview-bar');

        let selectedFile = null;

        /* ── Scroll ke bawah saat load ── */
        chatBox.scrollTop = chatBox.scrollHeight;

        /* ── Format waktu WIB ── */
        function nowStr() {
            return new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            });
        }

        /* ── Format bytes ── */
        function fmtBytes(b) {
            return b < 1048576
                ? (b / 1024).toFixed(1) + ' KB'
                : (b / 1048576).toFixed(1) + ' MB';
        }

        /* ── Escape HTML ── */
        function esc(s) {
            return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        /* ── Tambah bubble ── */
        function appendMessage({ content, imageUrl, isSender }) {
            const side   = isSender ? 'me' : 'other';
            const checks = isSender ? '<span class="checks">&#10003;&#10003;</span>' : '';
            let inner = '';
            if (imageUrl) inner += `<img class="bubble-img" src="${imageUrl}" alt="foto" onclick="openLightbox(this.src)">`;
            if (content)  inner += `<span>${esc(content)}</span>`;

            const row = document.createElement('div');
            row.className = `bubble-row ${side}`;
            row.innerHTML = `
                <div class="bubble ${side}">
                    ${inner}
                    <div class="bubble-footer">
                        <span class="bubble-time">${nowStr()}</span>
                        ${checks}
                    </div>
                </div>`;
            chatBox.appendChild(row);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        /* ═══ Kirim teks ═══ */
        function sendText() {
            const content = inputEl.value.trim();
            if (!content) return;

            appendMessage({ content, isSender: true }); // Optimistic UI
            inputEl.value = '';
            closeEmoji();

            window.axios.post(`/cbt/${chatToken}`, { content })
                .catch(() => alert('Gagal mengirim pesan. Silakan refresh.'));
        }

        /* ═══ File / Foto ═══ */
        attachBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function () {
            const f = this.files[0];
            if (!f) return;
            if (f.size > 5 * 1024 * 1024) {
                alert('Ukuran foto maksimal 5MB.');
                this.value = '';
                return;
            }
            selectedFile = f;
            const rd = new FileReader();
            rd.onload = e => {
                document.getElementById('preview-thumb').src = e.target.result;
                document.getElementById('preview-name').textContent = f.name;
                document.getElementById('preview-size').textContent = fmtBytes(f.size);
                previewBar.classList.add('show');
            };
            rd.readAsDataURL(f);
        });

        document.getElementById('preview-cancel').addEventListener('click', cancelImage);
        function cancelImage() {
            selectedFile = null;
            fileInput.value = '';
            previewBar.classList.remove('show');
        }

        function sendImage() {
            const fd = new FormData();
            fd.append('image', selectedFile);
            const localSrc = document.getElementById('preview-thumb').src;
            cancelImage();
            appendMessage({ imageUrl: localSrc, isSender: true });

            window.axios.post(`/cbt/${chatToken}/image`, fd, {
                headers: { 'Content-Type': 'multipart/form-data' }
            }).catch(() => alert('Gagal mengirim foto.'));
        }

        /* ═══ Emoji Picker ═══ */
        const EMOJIS = {
            'Smiley': ['😀','😃','😄','😁','😆','😅','😂','🤣','😊','😇','🙂','🙃','😉','😌','😍','🥰','😘','😗','😙','😚','😋','😛','😝','😜','🤪','😎','🤩','🥳','😏','😒','😞','😔','😟','😕','🙁','☹️','😣','😖','😫','😩','🥺','😢','😭','😤','😠','😡','🤬','🤯','😳'],
            'Gestur': ['👍','👎','👌','✌️','🤞','🤟','🤘','🤙','👈','👉','👆','👇','☝️','👋','🤚','🖐️','✋','🖖','🤏','💪','🙌','👐','🤲','🙏','🤝','✍️'],
            'Hati':   ['❤️','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','❣️','💕','💞','💓','💗','💖','💘','💝','💟','♥️','💋','💌'],
            'Objek':  ['🎉','🎊','🎈','🎁','🔥','✨','⭐','🌟','💫','🌈','☀️','🌙','⚡','❄️','🌊','🍕','🍔','🍜','☕','🎵','🎶','📱','💻','📷','🚗','✈️','🏠','💰','🎮'],
        };

        (function buildEmojiPicker() {
            for (const [cat, emojis] of Object.entries(EMOJIS)) {
                const label = document.createElement('div');
                label.className = 'ep-category';
                label.textContent = cat;
                emojiPicker.appendChild(label);
                emojis.forEach(em => {
                    const btn = document.createElement('button');
                    btn.className = 'emoji-btn';
                    btn.textContent = em;
                    btn.type = 'button';
                    btn.addEventListener('click', () => {
                        const pos = inputEl.selectionStart ?? inputEl.value.length;
                        const val = inputEl.value;
                        inputEl.value = val.slice(0, pos) + em + val.slice(pos);
                        inputEl.focus();
                        const newPos = pos + [...em].length;
                        inputEl.setSelectionRange(newPos, newPos);
                    });
                    emojiPicker.appendChild(btn);
                });
            }
        })();

        emojiToggle.addEventListener('click', e => {
            e.stopPropagation();
            emojiPicker.classList.toggle('show');
        });
        function closeEmoji() { emojiPicker.classList.remove('show'); }
        document.addEventListener('click', e => {
            if (!emojiPicker.contains(e.target) && e.target !== emojiToggle) closeEmoji();
        });

        /* ═══ Tombol Kirim ═══ */
        sendBtn.addEventListener('click', () => {
            if (selectedFile) sendImage(); else sendText();
        });
        inputEl.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (selectedFile) sendImage(); else sendText();
            }
        });

        /* ═══ Terima pesan via WebSocket (Reverb) ═══ */
       window.Echo.private(`chat.${authId}`)
            .listen('.MessageSent', (e) => {


                // Gunakan == (dua sama dengan) atau parseInt agar String "4" dianggap sama dengan Angka 4
                if(parseInt(e.sender_id) == parseInt(receiverId)) {
                    appendMessage(e, false);
                }
            });
    </script>

    <script>
        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').classList.add('open');
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') document.getElementById('lightbox').classList.remove('open');
        });
    </script>

</body>

</html>
