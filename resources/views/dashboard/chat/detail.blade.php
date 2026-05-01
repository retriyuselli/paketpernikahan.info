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
    <span class="text-sm font-semibold text-dark">{{ $session->guest_name }}</span>

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
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col" style="height: calc(100vh - 240px); min-height: 420px;">

    {{-- Messages --}}
    <div id="admin-chat-messages"
         class="flex-1 overflow-y-auto px-4 py-5 space-y-3"
         data-session-token="{{ $session->session_token }}"
         data-session-status="{{ $session->status }}"
         data-last-id="{{ $session->messages->max('id') ?? 0 }}">
        @foreach($session->messages as $msg)
        <div class="flex {{ $msg->sender === 'admin' ? 'justify-end' : 'justify-start' }}"
             data-msg-id="{{ $msg->id }}">
            <div class="max-w-[85%] break-words px-4 py-2.5 rounded-2xl text-sm leading-snug
                {{ $msg->sender === 'admin'
                    ? 'bg-accent text-white rounded-br-sm'
                    : 'bg-gray-100 text-dark rounded-bl-sm' }}">
                @if($msg->sender === 'admin' && $msg->adminUser)
                    <span class="block text-[10px] mb-1 text-white/70">{{ $msg->adminUser->name }}</span>
                @endif
                {{ $msg->message }}
                <span class="block text-[10px] mt-1 {{ $msg->sender === 'admin' ? 'text-white/60 text-right' : 'text-gray-400' }}">
                    {{ $msg->created_at->format('H:i') }}
                </span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Input --}}
    @if($session->status === 'open')
    <div class="border-t border-gray-100 px-4 py-3 flex items-end gap-2">
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

    function scrollBottom() {
        if (msgBox) msgBox.scrollTop = msgBox.scrollHeight;
    }
    scrollBottom();

    function appendMessage(msg) {
        var isAdmin = msg.sender === 'admin';
        var wrap = document.createElement('div');
        wrap.className = 'flex ' + (isAdmin ? 'justify-end' : 'justify-start');
        wrap.dataset.msgId = msg.id;
        var t = new Date(msg.created_at);
        var hhmm = t.getHours().toString().padStart(2,'0') + ':' + t.getMinutes().toString().padStart(2,'0');
        var nameHtml = (isAdmin && msg.admin_name) ? '<span class="block text-[10px] mb-1 text-white/70">' + escHtml(msg.admin_name) + '</span>' : '';
        wrap.innerHTML = '<div class="max-w-[85%] break-words px-4 py-2.5 rounded-2xl text-sm leading-snug '
            + (isAdmin ? 'bg-accent text-white rounded-br-sm' : 'bg-gray-100 text-dark rounded-bl-sm')
            + '">'
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
})();
</script>
@endsection
