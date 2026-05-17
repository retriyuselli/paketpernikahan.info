@extends('layout.dashboard')

@section('title', 'Chat dengan ' . $session->guest_name . ' — ' . $vendor->name)
@section('page-title', 'Chat')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('chat.vendor.index') }}"
       class="text-sm text-gray-400 hover:text-accent transition flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Chat Masuk
    </a>
    <span class="text-gray-300">/</span>
    <div>
        <span class="text-sm font-semibold text-dark">{{ $session->guest_name }}</span>
        @if($session->vendorPackage)
            <p class="mt-0.5 text-xs text-gray-400">Paket: <span class="font-medium text-dark">{{ $session->vendorPackage->name }}</span></p>
        @endif
    </div>

    @if($session->status === 'closed')
        <span class="ml-auto inline-flex text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">Ditutup</span>
    @else
        <span class="ml-auto inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-50 text-green-700">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            Aktif
        </span>
    @endif
</div>

{{-- Chat box --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col" style="height: calc(100vh - 150px); min-height: 480px;">

    {{-- Messages --}}
    @php $lastRenderedPkgId = $session->vendor_package_id; @endphp
    <div id="vendor-chat-messages"
         class="flex-1 overflow-y-auto px-4 py-5 space-y-3"
         data-session-token="{{ $session->session_token }}"
         data-session-status="{{ $session->status }}"
         data-last-id="{{ $session->messages->max('id') ?? 0 }}">

        @if($session->vendorPackage)
        @php $fpkg = $session->vendorPackage; $fpkgPrice = max(((int)$fpkg->price) - ((int)$fpkg->discount), 0); @endphp
        <div class="mx-1 w-fit flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3">
            <img src="{{ $fpkg->image_url ?: url(config('app.logo_url')) }}" alt="{{ $fpkg->name }}"
                 class="h-14 w-14 rounded-xl object-cover bg-gray-100 shrink-0">
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-dark">{{ $fpkg->name }}</p>
                <p class="mt-0.5 text-sm font-bold text-slate-700">
                    Rp{{ number_format($fpkgPrice ?: (int)$fpkg->price, 0, ',', '.') }}
                </p>
            </div>
        </div>
        @endif

        @foreach($session->messages as $msg)
        @if($msg->vendor_package_id && $msg->vendor_package_id !== $lastRenderedPkgId && $msg->vendorPackage)
        {{-- Package-switch divider --}}
        <div class="my-1 flex items-center gap-2 px-1">
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-400">Menanyakan paket baru</span>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>
        @php $dpkg = $msg->vendorPackage; $dpkgPrice = max(((int)$dpkg->price) - ((int)$dpkg->discount), 0); @endphp
        <div class="mx-1 w-fit flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
            <img src="{{ $dpkg->image_url ?: url(config('app.logo_url')) }}" alt="{{ $dpkg->name }}"
                 class="h-14 w-14 rounded-xl object-cover bg-gray-100 shrink-0">
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-dark">{{ $dpkg->name }}</p>
                <p class="mt-0.5 text-sm font-bold text-slate-700">
                    Rp{{ number_format($dpkgPrice ?: (int)$dpkg->price, 0, ',', '.') }}
                </p>
            </div>
        </div>
        @php $lastRenderedPkgId = $msg->vendor_package_id; @endphp
        @endif
        @php $isMine = in_array($msg->sender, ['vendor', 'admin']); @endphp
        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}"
             data-msg-id="{{ $msg->id }}">
            <div class="max-w-[85%] break-words px-4 py-2.5 rounded-2xl text-sm leading-snug
                {{ $isMine ? 'bg-accent text-white rounded-br-sm' : 'bg-gray-100 text-dark rounded-bl-sm' }}">
                @if(!$isMine)
                    <span class="block text-[10px] mb-1 text-gray-400 font-medium">{{ $msg->sender === 'guest' ? ($session->guest_name ?? 'Tamu') : 'Admin' }}</span>
                @endif
                {{ $msg->message }}
                <span class="block text-[10px] mt-1 {{ $isMine ? 'text-white/60 text-right' : 'text-gray-400' }}">
                    {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                </span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Input --}}
    @if($session->status === 'open')
    <div id="vendor-chat-input-area" class="border-t border-gray-100 px-4 pt-2 pb-3">
        {{-- Emoji Picker --}}
        <div id="vendor-emoji-picker" class="hidden mb-2 bg-white rounded-2xl shadow-xl ring-1 ring-gray-200 p-3">
            <div class="grid grid-cols-8 gap-1">
                @php
                $emojis = ['😊','😂','❤️','🥰','😍','😘','🤗','😭',
                           '😅','🙏','💕','✨','🎉','💍','👰','🤵',
                           '💐','🌸','🌹','🥂','🍰','📸','💌','🎊',
                           '😔','😢','😮','🤔','👍','👏','🙌','💯',
                           '🔥','⭐','🌟','💫','🕊️','🌺','🌷','🌼'];
                @endphp
                @foreach($emojis as $emoji)
                    <button type="button" data-emoji="{{ $emoji }}"
                            class="vendor-emoji-btn flex h-9 w-9 items-center justify-center rounded-xl text-xl hover:bg-gray-100 transition">
                        {{ $emoji }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="flex items-end gap-2">
            <button type="button" id="vendor-emoji-toggle"
                    class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 0 1-5.656 0M9 10h.01M15 10h.01M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>
                </svg>
            </button>
            <textarea id="vendor-reply-input"
                      placeholder="Ketik balasan..."
                      rows="1"
                      class="flex-1 resize-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-accent transition"
                      style="max-height:120px;"></textarea>
            <button type="button" id="vendor-reply-btn"
                    class="w-10 h-10 rounded-xl flex items-center justify-center bg-accent text-white hover:opacity-90 transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>
    @else
    <div id="vendor-chat-input-area" class="border-t border-gray-100 px-4 py-3 text-center text-sm text-gray-400">
        Sesi ini telah ditutup oleh admin.
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    var msgBox  = document.getElementById('vendor-chat-messages');
    var input   = document.getElementById('vendor-reply-input');
    var btn     = document.getElementById('vendor-reply-btn');
    var token   = msgBox?.dataset?.sessionToken || '';
    var status  = msgBox?.dataset?.sessionStatus || '';
    var lastId  = parseInt(msgBox?.dataset?.lastId || '0', 10);
    var guestName = '{{ addslashes($session->guest_name ?? "Tamu") }}';
    var timeFormatter = null;
    try {
        timeFormatter = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', hour12: false });
    } catch (e) {}

    function scrollBottom() { if (msgBox) msgBox.scrollTop = msgBox.scrollHeight; }
    scrollBottom();

    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function fmtTime(str) {
        try {
            var t = new Date(str);
            return timeFormatter ? timeFormatter.format(t) : (t.getHours().toString().padStart(2,'0') + ':' + t.getMinutes().toString().padStart(2,'0'));
        } catch (e) { return ''; }
    }

    function appendMessage(msg) {
        if (msgBox && msgBox.querySelector('[data-msg-id="' + msg.id + '"]')) return;
        var isMine = msg.sender === 'vendor' || msg.sender === 'admin';
        var wrap = document.createElement('div');
        wrap.className = 'flex ' + (isMine ? 'justify-end' : 'justify-start');
        wrap.dataset.msgId = msg.id;
        var senderLabel = !isMine
            ? '<span class="block text-[10px] mb-1 text-gray-400 font-medium">' + esc(guestName) + '</span>'
            : '';
        wrap.innerHTML = '<div class="max-w-[85%] break-words px-4 py-2.5 rounded-2xl text-sm leading-snug '
            + (isMine ? 'bg-accent text-white rounded-br-sm' : 'bg-gray-100 text-dark rounded-bl-sm')
            + '">'
            + senderLabel
            + esc(msg.message)
            + '<span class="block text-[10px] mt-1 ' + (isMine ? 'text-white/60 text-right' : 'text-gray-400') + '">'
            + fmtTime(msg.created_at)
            + '</span></div>';
        msgBox.appendChild(wrap);
        lastId = Math.max(lastId, parseInt(msg.id, 10) || 0);
        scrollBottom();
    }

    var sessionClosed = status !== 'open';

    function markSessionClosed() {
        if (sessionClosed) return;
        sessionClosed = true;
        var inputArea = document.getElementById('vendor-chat-input-area');
        if (inputArea) {
            inputArea.innerHTML = '<p class="py-3 text-center text-sm text-gray-400">Sesi ini telah ditutup oleh admin.</p>';
        }
    }

    // Poll for new messages
    if (!sessionClosed) {
        setInterval(function () {
            fetch('/chat/' + token + '/messages?after=' + lastId, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    data.messages.forEach(function (msg) { appendMessage(msg); });
                    if (data.status === 'closed') markSessionClosed();
                })
                .catch(function () {});
        }, 3000);
    }

    // Send reply
    function sendReply() {
        if (!input || sessionClosed) return;
        var msg = input.value.trim();
        if (!msg) return;
        var savedMsg = msg;
        input.value = '';
        input.style.height = '';

        fetch('/dashboard/vendor-chat/' + token + '/reply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ message: msg }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.id) {
                appendMessage({ id: data.id, sender: 'vendor', message: msg, created_at: data.created_at });
            } else if (data.error === 'closed') {
                markSessionClosed();
            }
        })
        .catch(function () {
            input.value = savedMsg;
            input.dispatchEvent(new Event('input'));
        });
    }

    if (btn) btn.addEventListener('click', sendReply);
    if (input) {
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendReply(); }
        });
        input.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    // Emoji picker
    var emojiPicker = document.getElementById('vendor-emoji-picker');
    var emojiToggle = document.getElementById('vendor-emoji-toggle');
    if (emojiToggle && emojiPicker) {
        emojiToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            emojiPicker.classList.toggle('hidden');
            if (!emojiPicker.classList.contains('hidden') && input) input.focus();
        });
        emojiPicker.addEventListener('click', function (e) {
            var btn = e.target.closest('.vendor-emoji-btn');
            if (!btn || !input) return;
            var emoji = btn.dataset.emoji || '';
            var pos = input.selectionStart ?? input.value.length;
            input.value = input.value.slice(0, pos) + emoji + input.value.slice(pos);
            input.focus();
            input.selectionStart = input.selectionEnd = pos + emoji.length;
            emojiPicker.classList.add('hidden');
        });
        document.addEventListener('click', function (e) {
            if (!emojiPicker.classList.contains('hidden') && !emojiPicker.contains(e.target) && !emojiToggle.contains(e.target)) {
                emojiPicker.classList.add('hidden');
            }
        });
    }
})();
</script>
@endsection
