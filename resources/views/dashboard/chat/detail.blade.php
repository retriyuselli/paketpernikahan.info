@extends('layout.dashboard')

@section('title', 'Chat dengan ' . $session->guest_name . ' — Makna Wedding')
@section('page-title', 'Chat')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('chat.admin') }}"
       class="text-sm text-gray-400 hover:text-accent transition flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Semua Chat
    </a>
    <span class="text-gray-300">/</span>
    <div>
        <span class="text-sm font-semibold text-dark">{{ $session->guest_name }}</span>
        <p class="mt-0.5 text-xs text-gray-400">
            Vendor:
            @if($session->vendor)
                <a href="{{ route('chat.admin', ['vendor_id' => $session->vendor->id]) }}" class="font-medium text-accent transition hover:underline">{{ $session->vendor->name }}</a>
            @else
                Belum terdeteksi
            @endif
        </p>
    </div>

    @if($session->status === 'closed')
        <span class="ml-auto inline-flex text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">Ditutup</span>
        <form method="POST" action="{{ route('chat.admin.open', $session->session_token) }}">
            @csrf
            <button type="submit"
                    onclick="return confirm('Buka kembali sesi chat ini?')"
                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-accent/10 text-accent hover:bg-accent hover:text-white transition">
                Buka Lagi
            </button>
        </form>
    @else
        <form method="POST" action="{{ route('chat.admin.close', $session->session_token) }}" class="ml-auto">
            @csrf
            <button type="submit"
                    onclick="return confirm('Tutup sesi chat ini?')"
                    class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                Tutup Sesi
            </button>
        </form>
    @endif
</div>

{{-- Chat box --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col" style="height: calc(100vh - 150px); min-height: 480px;">

    {{-- Messages --}}
    @php
        $lastMsgPkgId = $session->messages->whereNotNull('vendor_package_id')->last()?->vendor_package_id
            ?? $session->vendor_package_id;
    @endphp
    <div id="admin-chat-messages"
         class="flex-1 overflow-y-auto px-4 py-5 space-y-3"
         data-session-token="{{ $session->session_token }}"
         data-session-status="{{ $session->status }}"
         data-last-id="{{ $session->messages->max('id') ?? 0 }}"
         data-last-pkg-id="{{ $lastMsgPkgId ?? '' }}">
        @php $lastRenderedPkgId = $session->vendor_package_id; @endphp
        @if($session->vendorPackage)
        @php
            $fpkg = $session->vendorPackage;
            $fpkgPrice = max(((int)$fpkg->price) - ((int)$fpkg->discount), 0);
        @endphp
        <div class="mx-1 w-fit flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3">
            <a href="{{ route('store.package.show', $fpkg->id) }}" target="_blank" class="shrink-0">
                <img src="{{ $fpkg->image_url ?: url(config('app.logo_url')) }}" alt="{{ $fpkg->name }}"
                     class="h-14 w-14 rounded-xl object-cover bg-gray-100">
            </a>
            <div class="min-w-0 flex-1">
                <a href="{{ route('store.package.show', $fpkg->id) }}" target="_blank"
                   class="block truncate text-sm font-semibold text-dark hover:text-accent transition">
                    {{ $fpkg->name }}
                </a>
                <p class="mt-0.5 text-xs text-gray-400">{{ $session->vendor?->name }}</p>
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
        @php
            $dpkg = $msg->vendorPackage;
            $dpkgPrice = max(((int)$dpkg->price) - ((int)$dpkg->discount), 0);
        @endphp
        <div class="mx-1 w-fit flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
            <a href="{{ route('store.package.show', $dpkg->id) }}" target="_blank" class="shrink-0">
                <img src="{{ $dpkg->image_url ?: url(config('app.logo_url')) }}" alt="{{ $dpkg->name }}"
                     class="h-14 w-14 rounded-xl object-cover bg-gray-100">
            </a>
            <div class="min-w-0 flex-1">
                <a href="{{ route('store.package.show', $dpkg->id) }}" target="_blank"
                   class="block truncate text-sm font-semibold text-dark hover:text-accent transition">
                    {{ $dpkg->name }}
                </a>
                <p class="mt-0.5 text-sm font-bold text-slate-700">
                    Rp{{ number_format($dpkgPrice ?: (int)$dpkg->price, 0, ',', '.') }}
                </p>
            </div>
        </div>
        @php $lastRenderedPkgId = $msg->vendor_package_id; @endphp
        @endif
        <div class="flex group {{ $msg->sender === 'admin' ? 'justify-end' : 'justify-start' }}"
             data-msg-id="{{ $msg->id }}"
             data-pkg-id="{{ $msg->vendor_package_id ?? '' }}">
            <div class="relative max-w-[85%] break-words px-4 py-2.5 rounded-2xl text-sm leading-snug
                {{ $msg->sender === 'admin'
                    ? 'bg-accent text-white rounded-br-sm'
                    : 'bg-gray-100 text-dark rounded-bl-sm' }}">
                <form method="POST"
                      action="{{ route('chat.admin.message.delete', [$session->session_token, $msg]) }}"
                      class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Hapus pesan ini?')"
                            class="w-6 h-6 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-red-600 hover:border-red-200 transition flex items-center justify-center text-xs font-bold">
                        ×
                    </button>
                </form>
                @if($msg->sender === 'admin' && $msg->adminUser)
                    <span class="block text-[10px] mb-1 text-white/70">{{ $msg->adminUser->name }}</span>
                @endif
                {{ $msg->message }}
                <span class="block text-[10px] mt-1 {{ $msg->sender === 'admin' ? 'text-white/60 text-right' : 'text-gray-400' }}">
                    {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                </span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Input --}}
    @if($session->status === 'open')
    <div class="border-t border-gray-100 px-4 pt-2 pb-3">
        {{-- Emoji Picker --}}
        <div id="admin-emoji-picker" class="hidden mb-2 bg-white rounded-2xl shadow-xl ring-1 ring-gray-200 p-3">
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
                            class="admin-emoji-btn flex h-9 w-9 items-center justify-center rounded-xl text-xl hover:bg-gray-100 transition">
                        {{ $emoji }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="flex items-end gap-2">
            <button type="button" id="admin-emoji-toggle"
                    class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 0 1-5.656 0M9 10h.01M15 10h.01M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>
                </svg>
            </button>
            <textarea id="admin-reply-input"
                      placeholder="Ketik balasan..."
                      rows="1"
                      class="flex-1 resize-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-accent transition"
                      style="max-height:120px;"></textarea>
            <button type="button" id="admin-reply-btn"
                    class="w-10 h-10 rounded-xl flex items-center justify-center bg-accent text-white hover:opacity-90 transition flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>
    @else
    <div class="border-t border-gray-100 px-4 py-3 text-center text-sm text-gray-400">
        Sesi ini telah ditutup.
    </div>
    @endif
</div>

<script>
(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    var msgBox  = document.getElementById('admin-chat-messages');
    var input   = document.getElementById('admin-reply-input');
    var btn     = document.getElementById('admin-reply-btn');
    var token = msgBox?.dataset?.sessionToken || '';
    var status = msgBox?.dataset?.sessionStatus || '';
    var lastId = parseInt(msgBox?.dataset?.lastId || '0', 10);
    var lastSeenPkgId = parseInt(msgBox?.dataset?.lastPkgId || '0', 10) || null;
    var APP_TZ = 'Asia/Jakarta';
    var timeFormatter = null;
    try {
        timeFormatter = new Intl.DateTimeFormat('id-ID', { timeZone: APP_TZ, hour: '2-digit', minute: '2-digit', hour12: false });
    } catch (e) {
        timeFormatter = null;
    }

    function scrollBottom() {
        if (msgBox) msgBox.scrollTop = msgBox.scrollHeight;
    }
    scrollBottom();

    function appendMessage(msg) {
        var isAdmin = msg.sender === 'admin';
        var msgPkgId = msg.vendor_package_id ? parseInt(msg.vendor_package_id, 10) : null;

        // Inject package-switch divider when guest switches to a new package
        if (!isAdmin && msgPkgId && msgPkgId !== lastSeenPkgId && msg.package_name) {
            var dividerRow = document.createElement('div');
            dividerRow.className = 'my-1 flex items-center gap-2 px-1';
            dividerRow.innerHTML = '<div class="flex-1 border-t border-gray-200"></div>'
                + '<span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-400">Menanyakan paket baru</span>'
                + '<div class="flex-1 border-t border-gray-200"></div>';
            msgBox.appendChild(dividerRow);

            var imgHtml = msg.package_image_url
                ? '<img src="' + escHtml(msg.package_image_url) + '" class="h-14 w-14 rounded-xl object-cover bg-gray-100">'
                : '<div class="h-14 w-14 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 text-xs">No img</div>';
            var cardRow = document.createElement('div');
            cardRow.className = 'mx-1 flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm';
            cardRow.innerHTML = '<div class="shrink-0">' + imgHtml + '</div>'
                + '<div class="min-w-0 flex-1">'
                + '<p class="block truncate text-sm font-semibold text-dark">' + escHtml(msg.package_name) + '</p>'
                + (msg.package_price ? '<p class="mt-0.5 text-sm font-bold text-slate-700">' + escHtml(msg.package_price) + '</p>' : '')
                + '</div>';
            msgBox.appendChild(cardRow);
            lastSeenPkgId = msgPkgId;
        }

        var wrap = document.createElement('div');
        wrap.className = 'flex group ' + (isAdmin ? 'justify-end' : 'justify-start');
        wrap.dataset.msgId = msg.id;
        if (msgPkgId) wrap.dataset.pkgId = msgPkgId;
        var t = new Date(msg.created_at);
        var hhmm = timeFormatter ? timeFormatter.format(t) : (t.getHours().toString().padStart(2,'0') + ':' + t.getMinutes().toString().padStart(2,'0'));
        var nameHtml = (isAdmin && msg.admin_name) ? '<span class="block text-[10px] mb-1 text-white/70">' + escHtml(msg.admin_name) + '</span>' : '';
        var delUrl = '/dashboard/chat/' + token + '/messages/' + msg.id;
        var delHtml = '<button type="button" data-delete-msg="' + msg.id + '" data-delete-url="' + delUrl + '" class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition w-6 h-6 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-red-600 hover:border-red-200 flex items-center justify-center text-xs font-bold">×</button>';
        wrap.innerHTML = '<div class="relative max-w-[85%] break-words px-4 py-2.5 rounded-2xl text-sm leading-snug '
            + (isAdmin ? 'bg-accent text-white rounded-br-sm' : 'bg-gray-100 text-dark rounded-bl-sm')
            + '">'
            + delHtml
            + nameHtml
            + escHtml(msg.message)
            + '<span class="block text-[10px] mt-1 '
            + (isAdmin ? 'text-white/60 text-right' : 'text-gray-400') + '">' + hhmm + '</span>'
            + '</div>';
        msgBox.appendChild(wrap);
    }

    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function deleteMessage(btn) {
        if (!btn) return;
        var url = btn.getAttribute('data-delete-url') || '';
        var id = parseInt(btn.getAttribute('data-delete-msg') || '0', 10);
        if (!url || !id) return;
        if (!confirm('Hapus pesan ini?')) return;

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(function (r) { return r.json(); })
        .then(function () {
            var row = msgBox.querySelector('[data-msg-id="' + id + '"]');
            if (row) row.remove();
        })
        .catch(function () {});
    }

    // Poll for new messages
    if (status === 'open') {
        setInterval(function () {
            fetch('/chat/' + token + '/messages?after=' + lastId, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    data.messages.forEach(function (msg) {
                        appendMessage(msg);
                        lastId = Math.max(lastId, msg.id);
                    });
                    if (data.messages.length > 0) scrollBottom();
                });
        }, 3000);
    }

    // Send reply
    function sendReply() {
        if (!input) return;
        var msg = input.value.trim();
        if (!msg) return;
        input.value = '';
        input.style.height = '';

        fetch('/dashboard/chat/' + token + '/reply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({message: msg}),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            appendMessage({id: data.id, sender: 'admin', message: msg, created_at: data.created_at, admin_name: data.admin_name});
            lastId = Math.max(lastId, data.id);
            scrollBottom();
        });
    }

    if (btn) btn.addEventListener('click', sendReply);
    if (msgBox) {
        msgBox.addEventListener('click', function (e) {
            var delBtn = e.target.closest('[data-delete-msg][data-delete-url]');
            if (!delBtn) return;
            deleteMessage(delBtn);
        });
    }
    if (input) {
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendReply();
            }
        });
        input.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    // ── Emoji picker ─────────────────────────────────────────────────────
    var emojiToggle = document.getElementById('admin-emoji-toggle');
    var emojiPicker = document.getElementById('admin-emoji-picker');
    if (emojiToggle && emojiPicker) {
        emojiToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            emojiPicker.classList.toggle('hidden');
            if (!emojiPicker.classList.contains('hidden') && input) input.focus();
        });
        document.querySelectorAll('.admin-emoji-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!input) return;
                var start = input.selectionStart;
                var end = input.selectionEnd;
                var val = input.value;
                var emoji = btn.getAttribute('data-emoji');
                input.value = val.slice(0, start) + emoji + val.slice(end);
                input.selectionStart = input.selectionEnd = start + emoji.length;
                input.focus();
                input.dispatchEvent(new Event('input'));
            });
        });
        document.addEventListener('click', function (e) {
            if (!emojiPicker.contains(e.target) && e.target !== emojiToggle) {
                emojiPicker.classList.add('hidden');
            }
        });
    }
})();
</script>
@endsection
