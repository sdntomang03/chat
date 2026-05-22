<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknik menghitung perkalian dengan cepat</title>
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
    {{-- ══ Pesan ══ --}}
    <div id="chat-box">

        {{-- Tombol Load More (muncul di atas jika masih ada pesan) --}}
        @if($hasMore)
        <div id="load-more-wrapper" style="text-align:center; padding: 10px 0;">
            <button id="load-more-btn" style="background:#2a3942; color:#8696a0; border:none; border-radius:16px;
                   padding:6px 18px; font-size:12px; cursor:pointer;">
                ⟳ Memuat pesan sebelumnya...
            </button>
        </div>
        @endif

        <div class="date-chip">
            <span>{{ now()->translatedFormat('d F Y') }}</span>
        </div>

        {{-- Container pesan lama (prepend ke sini) --}}
        <div id="old-messages"></div>

        {{-- Pesan awal dari server --}}
        <div id="initial-messages">
            @foreach($messages as $msg)
            <div class="bubble-row {{ $msg->sender_id === Auth::id() ? 'me' : 'other' }}">
                <div class="bubble {{ $msg->sender_id === Auth::id() ? 'me' : 'other' }}">
                    @if($msg->file_path)
                    <img class="bubble-img" src="{{ $msg->file_path }}" alt="foto" onclick="openLightbox(this.src)">
                    @endif
                    @if($msg->content)
                    <span>{{ $msg->content }}</span>
                    @endif
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

        {{-- Pesan baru masuk (append ke sini) --}}
        <div id="new-messages"></div>
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
        const chatToken  = '{{ $token }}';
    const receiverId = {{ $receiver->id }};
    const authId     = {{ Auth::id() }};
    const baseUrl    = window.location.pathname;

    const chatBox       = document.getElementById('chat-box');
    const inputEl       = document.getElementById('message-input');
    const sendBtn       = document.getElementById('send-btn');
    const fileInput     = document.getElementById('file-input');
    const attachBtn     = document.getElementById('attach-btn');
    const emojiToggle   = document.getElementById('emoji-toggle-btn');
    const emojiPicker   = document.getElementById('emoji-picker');
    const previewBar    = document.getElementById('preview-bar');
    const oldMessages   = document.getElementById('old-messages');
    const newMessages   = document.getElementById('new-messages');
    const loadMoreBtn   = document.getElementById('load-more-btn');
    const loadMoreWrap  = document.getElementById('load-more-wrapper');

    let selectedFile = null;
    let currentPage  = 2;          // halaman 1 sudah di-render server
    let isLoading    = false;
    let noMorePages  = {{ $hasMore ? 'false' : 'true' }};

    /* ── Scroll ke bawah saat load ── */
    chatBox.scrollTop = chatBox.scrollHeight;

    /* ══ Load More via Scroll ke Atas ══ */
    chatBox.addEventListener('scroll', () => {
        // Trigger ketika user scroll ke atas mendekati 80px dari puncak
        if (chatBox.scrollTop < 80 && !isLoading && !noMorePages) {
            loadOlderMessages();
        }
    });

    // Tombol manual (fallback)
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            if (!isLoading && !noMorePages) loadOlderMessages();
        });
    }

    async function loadOlderMessages() {
        isLoading = true;
        if (loadMoreBtn) loadMoreBtn.textContent = '⏳ Memuat...';

        // Simpan posisi scroll sekarang supaya tidak melompat
        const prevScrollHeight = chatBox.scrollHeight;

        try {
            const res = await fetch(`${baseUrl}?page=${currentPage}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.messages.length > 0) {
                // Prepend pesan lama ke #old-messages (paling atas)
                const fragment = document.createDocumentFragment();
                data.messages.forEach(msg => {
                    fragment.appendChild(buildBubble(msg));
                });
                oldMessages.prepend(fragment);

                // Pertahankan posisi scroll (tidak loncat ke atas)
                chatBox.scrollTop = chatBox.scrollHeight - prevScrollHeight;
                currentPage++;
            }

            if (!data.has_more) {
                noMorePages = true;
                if (loadMoreWrap) loadMoreWrap.remove();
            } else {
                if (loadMoreBtn) loadMoreBtn.textContent = '⟳ Memuat pesan sebelumnya...';
            }
        } catch (e) {
            if (loadMoreBtn) loadMoreBtn.textContent = '⚠ Gagal, coba lagi';
        } finally {
            isLoading = false;
        }
    }

    /* ── Bangun elemen bubble dari data JSON ── */
    function buildBubble(msg) {
        const isSender = parseInt(msg.sender_id) === parseInt(authId);
        const side     = isSender ? 'me' : 'other';
        const checks   = isSender ? '<span class="checks">&#10003;&#10003;</span>' : '';
        let inner      = '';

        if (msg.file_path) {
            inner += `<img class="bubble-img" src="${msg.file_path}" alt="foto" onclick="openLightbox(this.src)">`;
        }
        if (msg.content && msg.content !== '📷 Mengirim Foto') {
            inner += `<span>${esc(msg.content)}</span>`;
        }

        const row = document.createElement('div');
        row.className = `bubble-row ${side}`;
        row.innerHTML = `
            <div class="bubble ${side}">
                ${inner}
                <div class="bubble-footer">
                    <span class="bubble-time">${msg.created_at}</span>
                    ${checks}
                </div>
            </div>`;
        return row;
    }

    /* ── Format waktu WIB ── */
    function nowStr() {
        return new Date().toLocaleTimeString('id-ID', {
            hour: '2-digit', minute: '2-digit',
            hour12: false, timeZone: 'Asia/Jakarta'
        });
    }

    function fmtBytes(b) {
        return b < 1048576 ? (b/1024).toFixed(1)+' KB' : (b/1048576).toFixed(1)+' MB';
    }

    function esc(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    /* ── Tambah bubble pesan baru (realtime/kirim) ── */
    function appendMessage({ content, imageUrl, isSender }) {
        const side   = isSender ? 'me' : 'other';
        const checks = isSender ? '<span class="checks">&#10003;&#10003;</span>' : '';
        let inner    = '';

        if (imageUrl) inner += `<img class="bubble-img" src="${imageUrl}" alt="foto" onclick="openLightbox(this.src)">`;
        if (content && content !== '📷 Mengirim Foto') inner += `<span>${esc(content)}</span>`;

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
        newMessages.appendChild(row);   // append ke #new-messages (paling bawah)
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    /* ═══ Kirim teks ═══ */
    function sendText() {
        const content = inputEl.value.trim();
        if (!content) return;

        appendMessage({ content, isSender: true });
        inputEl.value = '';
        closeEmoji();

        fetch(baseUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ content })
        }).catch(() => alert('Gagal mengirim pesan. Silakan refresh.'));
    }

    /* ═══ Kirim foto ═══ */
    attachBtn.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function () {
        const f = this.files[0];
        if (!f) return;
        if (f.size > 8 * 1024 * 1024) {
    alert('Ukuran foto maksimal 8MB.');
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
        const content = inputEl.value.trim();
        fd.append('content', content || '📷 Mengirim Foto');

        const localSrc = document.getElementById('preview-thumb').src;
        cancelImage();
        appendMessage({ content, imageUrl: localSrc, isSender: true });
        inputEl.value = '';
        closeEmoji();

        fetch(baseUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: fd
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

    emojiToggle.addEventListener('click', e => { e.stopPropagation(); emojiPicker.classList.toggle('show'); });
    function closeEmoji() { emojiPicker.classList.remove('show'); }
    document.addEventListener('click', e => {
        if (!emojiPicker.contains(e.target) && e.target !== emojiToggle) closeEmoji();
    });

    /* ═══ Tombol Kirim ═══ */
    sendBtn.addEventListener('click', () => { if (selectedFile) sendImage(); else sendText(); });
    inputEl.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (selectedFile) sendImage(); else sendText();
        }
    });

    /* ═══ WebSocket ═══ */
    window.Echo.private(`chat.${authId}`)
        .listen('.MessageSent', (e) => {
            if (parseInt(e.sender_id) === parseInt(receiverId)) {
                appendMessage({ content: e.content, imageUrl: e.file_path, isSender: false });
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