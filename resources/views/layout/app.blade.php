<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $defaultTitle = 'Makna Wedding - Wedding Organizer & Paket Pernikahan';
            $pageTitle = trim($__env->yieldContent('title', $defaultTitle)) ?: $defaultTitle;
            $defaultDescription = 'Makna Wedding membantu calon pengantin menemukan vendor, paket pernikahan, inspirasi, dan layanan wedding organizer di Indonesia.';
            $metaDescription = trim($__env->yieldContent('meta-description', $defaultDescription)) ?: $defaultDescription;
            $metaImage = trim($__env->yieldContent('meta-image', url(config('app.logo_url'))));
            $canonicalUrl = trim($__env->yieldContent('canonical-url', url()->current())) ?: url()->current();
            $metaType = trim($__env->yieldContent('meta-type', 'website')) ?: 'website';
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">

        <link rel="canonical" href="{{ $canonicalUrl }}">

        <title>{{ $pageTitle }}</title>
        <link rel="icon" href="{{ config('app.favicon_url') }}">
        <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
        <meta property="og:type" content="{{ $metaType }}">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:site_name" content="{{ config('app.name', 'Makna Wedding') }}">
        <meta property="og:image" content="{{ $metaImage }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $metaImage }}">
        @if(filled(config('services.ga4.measurement_id')))
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.measurement_id') }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', "{{ config('services.ga4.measurement_id') }}");
            </script>
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                * {
                    font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
                }

                :root {
                    --soft-pink: #F9D5E5;
                    --sage-green: #9CAF88;
                    --light-sage: #C8D5B9;
                    --cream: #FAF3E7;
                    --dark-gray: #444444;
                }

                body {
                    background-color: var(--cream);
                    color: var(--dark-gray);
                }

                .text-accent {
                    color: var(--sage-green);
                }

                .text-accent-pink {
                    color: var(--soft-pink);
                }

                .text-dark {
                    color: var(--dark-gray);
                }

                .bg-accent {
                    background-color: var(--sage-green);
                }

                .bg-accent-pink {
                    background-color: var(--soft-pink);
                }

                .bg-light-sage {
                    background-color: var(--light-sage);
                }

                .bg-cream {
                    background-color: var(--cream);
                }

                .border-accent {
                    border-color: var(--sage-green);
                }

                .from-accent {
                    --tw-gradient-from: var(--sage-green);
                }

                .to-accent {
                    --tw-gradient-to: var(--sage-green);
                }

                .to-accent-dark {
                    --tw-gradient-to: #7d9469;
                }

                .from-soft-pink {
                    --tw-gradient-from: var(--soft-pink);
                }

                .hover\:text-accent:hover {
                    color: var(--sage-green);
                }

                .focus\:ring-accent:focus {
                    --tw-ring-color: rgba(156, 175, 136, 0.4);
                    box-shadow: 0 0 0 3px rgba(156, 175, 136, 0.4);
                }

                .bg-accent-gradient {
                    background: linear-gradient(to right, var(--sage-green), var(--light-sage));
                }

                @keyframes marquee {
                    0% {
                        transform: translateX(0);
                    }
                    100% {
                        transform: translateX(-50%);
                    }
                }

                .animate-marquee {
                    animation: marquee 30s linear infinite;
                }

                .scrollbar-hide::-webkit-scrollbar {
                    display: none;
                }

                .scrollbar-hide {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
            </style>
        @endif

        @include('layout.theme-vars')

        @yield('extra-head')
    </head>
    
    <body class="@yield('body-class', 'bg-cream text-dark')">
        @yield('content')

        {{-- ── Error Modal (PostTooLarge / upload errors) ── --}}
        @if(session('error_modal'))
        <div id="error-modal"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-backdrop-45">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 flex flex-col items-center gap-4 text-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center bg-red-50">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-dark">File Terlalu Besar</h3>
                <p class="text-sm text-gray-500">{{ session('error_modal') }}</p>
                <button type="button" data-close-error-modal class="w-full py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90 bg-accent">
                    Mengerti
                </button>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[data-close-error-modal]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var modal = document.getElementById('error-modal');
                        if (modal) modal.remove();
                    });
                });
            });
        </script>
        @endif

        {{-- ── Live Chat Widget ── --}}
        <div id="lc-widget" class="fixed bottom-4 right-4 sm:bottom-5 sm:right-5 z-[9990] flex flex-col items-end gap-3">

            {{-- Chat Panel --}}
            <div id="lc-panel"
                 style="display:none;"
                 class="bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col w-[calc(100vw-24px)] sm:w-[420px] max-w-[calc(100vw-24px)] h-[70vh] sm:h-[520px]"
                 aria-label="Live Chat">

                {{-- Header --}}
                <div class="flex items-center gap-3 px-4 py-3 bg-dark">
                    <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-cream" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-cream font-semibold text-sm leading-tight">Makna Wedding</p>
                        <p class="text-cream/60 text-xs flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>
                            Online
                        </p>
                    </div>
                    <button type="button" id="lc-close" class="text-cream/60 hover:text-cream transition" aria-label="Tutup chat">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Intro (form nama) --}}
                <div id="lc-intro" class="px-4 py-5 flex flex-col gap-3">
                    <p class="text-sm text-gray-600 leading-snug">Halo! 👋 Sebelum mulai, boleh kami tahu nama kamu?</p>
                    <input type="text" id="lc-name-input"
                           placeholder="Nama kamu..."
                           maxlength="100"
                           class="border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-dark transition"/>
                    <button type="button" id="lc-start-btn"
                            class="bg-dark text-cream text-sm font-semibold py-2 rounded-xl hover:opacity-90 transition">
                        Mulai Chat
                    </button>
                    <p id="lc-intro-err" class="text-xs text-red-500 hidden">Nama tidak boleh kosong.</p>
                </div>

                {{-- Chat area (hidden until started) --}}
                <div id="lc-chat" style="display:none;" class="flex flex-1 min-h-0 flex-col">
                    <div id="lc-messages" class="flex-1 min-h-0 overflow-y-auto px-4 py-3 space-y-2 bg-gray-50"></div>
                    <div id="lc-closed-notice" style="display:none;"
                         class="px-4 py-2 text-center text-xs text-gray-400 bg-gray-50 border-t border-gray-100">
                        Sesi ini telah ditutup oleh admin.
                    </div>
                    <div id="lc-input-area" class="border-t border-gray-100 px-3 py-2.5 flex items-end gap-2 bg-white min-w-0">
                        <textarea id="lc-msg-input"
                                  placeholder="Ketik pesan..."
                                  rows="1"
                                  class="flex-1 min-w-0 resize-none bg-gray-100 rounded-xl px-3 py-2 text-sm outline-none focus:bg-gray-200 transition"
                                  style="max-height:90px;"></textarea>
                        <button type="button" id="lc-send-btn"
                                class="w-9 h-9 rounded-xl bg-dark text-cream flex items-center justify-center hover:opacity-90 transition flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

            {{-- Floating Button --}}
            <div id="lc-welcome"
                 style="display:none;"
                 class="mb-1 max-w-[260px] sm:max-w-xs bg-white border border-gray-200 shadow-lg rounded-2xl px-3 py-2 text-xs text-gray-700">
                <div class="flex items-start gap-2">
                    <div class="flex-1 leading-snug">
                        <p class="font-semibold text-dark">Selamat datang di Paket Pernikahan</p>
                        <p class="text-gray-500">Ada yang bisa kami bantu?</p>
                    </div>
                    <button type="button" id="lc-welcome-close" class="mt-0.5 text-gray-400 hover:text-gray-600 transition" aria-label="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <button type="button" id="lc-btn"
                    class="w-14 h-14 rounded-full shadow-xl flex items-center justify-center bg-dark text-cream transition hover:scale-105 active:scale-95 relative"
                    aria-label="Buka Live Chat">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span id="lc-notif-dot"
                      style="display:none;"
                      class="absolute top-0.5 right-0.5 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
        </div>

        <script>
        (function () {
            var CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

            var btn       = document.getElementById('lc-btn');
            var panel     = document.getElementById('lc-panel');
            var closeBtn  = document.getElementById('lc-close');
            var intro     = document.getElementById('lc-intro');
            var nameInput = document.getElementById('lc-name-input');
            var startBtn  = document.getElementById('lc-start-btn');
            var introErr  = document.getElementById('lc-intro-err');
            var chatArea  = document.getElementById('lc-chat');
            var messages  = document.getElementById('lc-messages');
            var msgInput  = document.getElementById('lc-msg-input');
            var sendBtn   = document.getElementById('lc-send-btn');
            var inputArea = document.getElementById('lc-input-area');
            var closedNotice = document.getElementById('lc-closed-notice');
            var notifDot  = document.getElementById('lc-notif-dot');
            var welcome   = document.getElementById('lc-welcome');
            var welcomeClose = document.getElementById('lc-welcome-close');

            var WELCOME_VERSION = '1';
            var token    = sessionStorage.getItem('lc_token') ?? null;
            var lastId   = parseInt(sessionStorage.getItem('lc_last_id') ?? '0', 10);
            var panelOpen = false;
            var pollTimer = null;
            var welcomeTimer = null;
            var APP_TZ = 'Asia/Jakarta';
            var timeFormatter = null;
            try {
                timeFormatter = new Intl.DateTimeFormat('id-ID', { timeZone: APP_TZ, hour: '2-digit', minute: '2-digit', hour12: false });
            } catch (e) {
                timeFormatter = null;
            }

            // ── helpers ──────────────────────────────────────────────
            function esc(s) {
                return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            }
            function fmtTime(iso) {
                var d = new Date(iso);
                if (timeFormatter) return timeFormatter.format(d);
                return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
            }
            function scrollBottom() {
                if (messages) messages.scrollTop = messages.scrollHeight;
            }
            function appendMsg(msg) {
                var isAdmin = msg.sender === 'admin';
                var wrap = document.createElement('div');
                wrap.className = 'flex ' + (isAdmin ? 'justify-start' : 'justify-end');
                wrap.dataset.msgId = msg.id;
                var nameHtml = (isAdmin && msg.admin_name) ? '<span class="block text-[10px] mb-1 text-gray-400">' + esc(msg.admin_name) + '</span>' : '';
                wrap.innerHTML = '<div class="max-w-[88%] break-words px-3 py-2 rounded-2xl text-sm leading-snug '
                    + (isAdmin ? 'bg-white text-dark shadow-sm rounded-tl-none' : 'bg-dark text-cream rounded-tr-none')
                    + '">'
                    + nameHtml
                    + esc(msg.message)
                    + '<span class="block text-[10px] mt-1 '
                    + (isAdmin ? 'text-gray-400' : 'text-cream/60 text-right') + '">' + fmtTime(msg.created_at) + '</span>'
                    + '</div>';
                messages.appendChild(wrap);
            }
            function setNewMsgDot(show) {
                if (notifDot) notifDot.style.display = show ? '' : 'none';
            }
            function getWelcomeTs() {
                try {
                    var v = localStorage.getItem('lc_welcome_v') ?? '';
                    if (v !== WELCOME_VERSION) {
                        return 0;
                    }
                    return parseInt(localStorage.getItem('lc_welcome_ts') ?? '0', 10) || 0;
                } catch (e) {
                    return 0;
                }
            }
            function setWelcomeTs() {
                try {
                    localStorage.setItem('lc_welcome_v', WELCOME_VERSION);
                    localStorage.setItem('lc_welcome_ts', String(Date.now()));
                } catch (e) {}
            }
            function hideWelcome(persist) {
                if (welcome) welcome.style.display = 'none';
                if (welcomeTimer) clearTimeout(welcomeTimer);
                welcomeTimer = null;
                if (persist) setWelcomeTs();
            }
            function maybeShowWelcome() {
                if (!welcome || panelOpen) return;
                var lastSeen = getWelcomeTs();
                if (lastSeen && (Date.now() - lastSeen) < 24 * 60 * 60 * 1000) return;
                welcome.style.display = '';
                welcomeTimer = null;
            }

            // ── restore session ───────────────────────────────────────
            function restoreSession() {
                if (!token) return;
                // fetch all messages from beginning
                fetch('/chat/' + token + '/messages?after=0', {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        showChat();
                        data.messages.forEach(function (m) { appendMsg(m); lastId = Math.max(lastId, m.id); });
                        sessionStorage.setItem('lc_last_id', lastId);
                        scrollBottom();
                        if (data.status === 'closed') showClosed();
                        else startPolling();
                    })
                    .catch(function () {
                        // token stale, reset
                        token = null;
                        sessionStorage.removeItem('lc_token');
                        sessionStorage.removeItem('lc_last_id');
                    });
            }

            function showChat() {
                intro.style.display = 'none';
                chatArea.style.display = 'flex';
                chatArea.style.flexDirection = 'column';
            }
            function showClosed() {
                if (closedNotice) closedNotice.style.display = '';
                if (inputArea) inputArea.style.display = 'none';
                stopPolling();
            }

            // ── polling ───────────────────────────────────────────────
            function startPolling() {
                stopPolling();
                pollTimer = setInterval(poll, 3000);
            }
            function stopPolling() {
                if (pollTimer) clearInterval(pollTimer);
                pollTimer = null;
            }
            function poll() {
                if (!token) return;
                fetch('/chat/' + token + '/messages?after=' + lastId, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        var hasNew = false;
                        data.messages.forEach(function (m) {
                            appendMsg(m);
                            lastId = Math.max(lastId, m.id);
                            if (m.sender === 'admin') hasNew = true;
                        });
                        sessionStorage.setItem('lc_last_id', lastId);
                        if (data.messages.length > 0) scrollBottom();
                        if (hasNew && !panelOpen) setNewMsgDot(true);
                        if (data.status === 'closed') showClosed();
                    });
            }

            // ── open / close panel ────────────────────────────────────
            function openPanel() {
                panelOpen = true;
                panel.style.display = 'flex';
                panel.style.flexDirection = 'column';
                setNewMsgDot(false);
                if (token) restoreSession();
                setTimeout(function () { if (nameInput && !token) nameInput.focus(); }, 100);
            }
            function closePanel() {
                panelOpen = false;
                panel.style.display = 'none';
            }

            btn.addEventListener('click', function () { panelOpen ? closePanel() : openPanel(); });
            closeBtn.addEventListener('click', closePanel);
            if (welcomeClose) welcomeClose.addEventListener('click', function () { hideWelcome(true); });

            // ── start session ─────────────────────────────────────────
            startBtn.addEventListener('click', function () {
                var name = nameInput.value.trim();
                if (!name) { introErr.classList.remove('hidden'); return; }
                introErr.classList.add('hidden');
                startBtn.disabled = true;
                startBtn.textContent = 'Memulai...';

                fetch('/chat/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({guest_name: name}),
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    token = data.token;
                    sessionStorage.setItem('lc_token', token);
                    sessionStorage.setItem('lc_last_id', '0');
                    lastId = 0;
                    showChat();
                    // Load welcome message
                    poll();
                    startPolling();
                })
                .catch(function () {
                    startBtn.disabled = false;
                    startBtn.textContent = 'Mulai Chat';
                });
            });
            nameInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') startBtn.click();
            });

            // ── send message ──────────────────────────────────────────
            function sendMessage() {
                if (!token || !msgInput) return;
                var msg = msgInput.value.trim();
                if (!msg) return;
                msgInput.value = '';
                msgInput.style.height = '';

                fetch('/chat/' + token + '/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({message: msg}),
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    appendMsg({id: data.id, sender: 'guest', message: msg, created_at: data.created_at});
                    lastId = Math.max(lastId, data.id);
                    sessionStorage.setItem('lc_last_id', lastId);
                    scrollBottom();
                });
            }

            if (sendBtn) sendBtn.addEventListener('click', sendMessage);
            if (msgInput) {
                msgInput.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
                });
                msgInput.addEventListener('input', function () {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 90) + 'px';
                });
            }

            // ── notification dot after 8s if no session ───────────────
            if (!token) {
                setTimeout(function () { if (!panelOpen) setNewMsgDot(true); }, 8000);
            }
            welcomeTimer = setTimeout(maybeShowWelcome, 1200);
        })();
        </script>

    </body>
</html>
