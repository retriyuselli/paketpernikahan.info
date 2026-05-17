@extends('layout.dashboard')

@section('title', 'Chat ke Admin — ' . $vendor->name)
@section('page-title', 'Chat ke Admin')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <div>
        <h1 class="text-xl font-bold text-dark">Chat ke Admin</h1>
        <p class="text-sm text-gray-400 mt-0.5">Kirim pesan langsung ke tim admin Makna Wedding.</p>
    </div>
    @if($session->status === 'open')
        <span class="ml-auto inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-50 text-green-700">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            Aktif
        </span>
    @else
        <span class="ml-auto inline-flex text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">Ditutup</span>
    @endif
</div>

{{-- Chat box --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col" style="height: calc(100vh - 150px); min-height: 480px;">

    {{-- Messages --}}
    <div id="internal-chat-messages"
         class="flex-1 overflow-y-auto px-4 py-5 space-y-3"
         data-session-token="{{ $session->session_token }}"
         data-session-status="{{ $session->status }}"
         data-last-id="{{ $session->messages->max('id') ?? 0 }}">

        @if($session->messages->isEmpty())
        <div class="flex flex-col items-center justify-center h-full text-gray-400 py-10">
            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <p class="text-sm font-medium text-gray-500">Mulai percakapan</p>
            <p class="text-xs text-gray-400 mt-1">Kirim pesan pertama ke admin.</p>
        </div>
        @endif

        @foreach($session->messages as $msg)
        @php $isMine = $msg->sender === 'vendor'; @endphp
        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}"
             data-msg-id="{{ $msg->id }}">
            <div class="max-w-[85%] break-words px-4 py-2.5 rounded-2xl text-sm leading-snug
                {{ $isMine ? 'bg-accent text-white rounded-br-sm' : 'bg-gray-100 text-dark rounded-bl-sm' }}">
                @if(!$isMine)
                    <span class="block text-[10px] mb-1 font-medium text-gray-400">
                        {{ $msg->adminUser?->name ?? 'Admin' }}
                    </span>
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
    <div id="internal-chat-input-area" class="border-t border-gray-100 px-4 pt-2 pb-3">
        <div class="flex items-end gap-2">
            <textarea id="internal-reply-input"
                      placeholder="Ketik pesan ke admin..."
                      rows="1"
                      class="flex-1 resize-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-accent transition"
                      style="max-height:120px;"></textarea>
            <button type="button" id="internal-reply-btn"
                    class="w-10 h-10 rounded-xl flex items-center justify-center bg-accent text-white hover:opacity-90 transition shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>
    @else
    <div class="border-t border-gray-100 px-4 py-3 text-center text-sm text-gray-400">
        Sesi ini telah ditutup oleh admin.
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    var msgBox = document.getElementById('internal-chat-messages');
    var input  = document.getElementById('internal-reply-input');
    var btn    = document.getElementById('internal-reply-btn');
    var lastId = parseInt(msgBox?.dataset?.lastId || '0', 10);
    var status = msgBox?.dataset?.sessionStatus || '';
    var sessionClosed = status !== 'open';

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

        // Remove empty state if present
        var emptyState = msgBox?.querySelector('.flex.flex-col.items-center');
        if (emptyState) emptyState.remove();

        var isMine = msg.sender === 'vendor';
        var wrap = document.createElement('div');
        wrap.className = 'flex ' + (isMine ? 'justify-end' : 'justify-start');
        wrap.dataset.msgId = msg.id;
        var senderLabel = !isMine
            ? '<span class="block text-[10px] mb-1 font-medium text-gray-400">' + esc(msg.admin_name || 'Admin') + '</span>'
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

    function markClosed() {
        if (sessionClosed) return;
        sessionClosed = true;
        var inputArea = document.getElementById('internal-chat-input-area');
        if (inputArea) {
            inputArea.innerHTML = '<p class="py-3 text-center text-sm text-gray-400">Sesi ini telah ditutup oleh admin.</p>';
        }
    }

    if (!sessionClosed) {
        setInterval(function () {
            fetch('{{ route("chat.internal.vendor.poll") }}?after=' + lastId, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                data.messages.forEach(function (msg) { appendMessage(msg); });
                if (data.status === 'closed') markClosed();
            })
            .catch(function () {});
        }, 3000);
    }

    function sendReply() {
        if (!input || sessionClosed) return;
        var msg = input.value.trim();
        if (!msg) return;
        var saved = msg;
        input.value = '';
        input.style.height = '';

        fetch('{{ route("chat.internal.vendor.send") }}', {
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
            }
        })
        .catch(function () {
            input.value = saved;
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
})();
</script>
@endsection
