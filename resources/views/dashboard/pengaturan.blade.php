@extends('layout.dashboard')

@section('title', 'Pengaturan Akun — Makna Wedding')
@section('page-title', 'Pengaturan Akun')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-dark">Pengaturan Akun</h1>
        <p class="text-sm text-gray-400 mt-1">Kelola foto profil, nama, dan password kamu.</p>
    </div>

    @if(session('setting_success'))
    <div class="mb-4 text-xs font-semibold px-3 py-2.5 rounded-xl bg-green-50 text-green-700">
        ✓ {{ session('setting_success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Upload Avatar --}}
        <form method="POST" action="{{ route('dashboard.avatar.update') }}"
              enctype="multipart/form-data"
              class="bg-white rounded-2xl border border-gray-100 p-5 sm:col-span-2">
            @csrf
            <p class="text-xs font-bold mb-4 text-dark">Foto Profil</p>
            <div class="flex items-center gap-5">
                <div class="flex-shrink-0">
                    @if($user->avatar_url)
                    <img id="avatar-preview" src="{{ $user->avatarUrl() }}"
                         alt="Avatar" class="w-20 h-20 rounded-full object-cover border-4 border-gray-100 shadow">
                    @else
                    <div id="avatar-preview-initials"
                         class="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold text-white border-4 border-gray-100 shadow bg-accent">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="text-[10px] uppercase tracking-widest text-gray-400 block mb-2">Pilih Gambar</label>
                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                           id="avatar-input"
                           class="hidden">
                    <label for="avatar-input"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-dashed border-gray-300 text-xs text-gray-500 cursor-pointer hover:border-gray-400 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span id="avatar-filename">Pilih file foto...</span>
                    </label>
                    @error('avatar')
                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[10px] text-gray-400 mt-1.5">Format: JPG, PNG, WEBP &bull; Maks. 1 MB</p>

                </div>
            </div>
            <button type="submit"
                    class="mt-4 w-full sm:w-auto px-6 py-2.5 rounded-xl text-sm font-bold bg-accent text-white transition hover:opacity-90">
                Simpan Foto
            </button>
        </form>

        {{-- Edit Nama --}}
        <form method="POST" action="{{ route('dashboard.profile.update') }}"
              class="bg-white rounded-2xl border border-gray-100 p-5">
            @csrf
            <p class="text-xs font-bold mb-4 text-dark">Ubah Nama</p>

            <div class="mb-3">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 block mb-1">Nama</label>
                <input type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       maxlength="100" required
                       class="w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none transition
                              {{ $errors->has('name') ? 'border-red-300 focus:border-red-400' : 'border-gray-200 focus:border-gray-400' }}">
                @error('name')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 block mb-1">Email</label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full border border-gray-100 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
            </div>

            <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-bold bg-accent text-white transition hover:opacity-90">
                Simpan Nama
            </button>
        </form>

        {{-- Nomor WhatsApp --}}
        <form method="POST" action="{{ route('dashboard.whatsapp.update') }}"
              class="bg-white rounded-2xl border border-gray-100 p-5">
            @csrf
            <p class="text-xs font-bold mb-4 text-dark">Nomor WhatsApp</p>

            <div class="mb-4">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 block mb-1">WhatsApp</label>
                @php
                    $waDisplay = $user->whatsapp ? preg_replace('/^62/', '0', $user->whatsapp) : '';
                @endphp
                <input type="tel" inputmode="numeric" autocomplete="tel" name="whatsapp"
                       value="{{ old('whatsapp', $waDisplay) }}"
                       maxlength="30"
                       placeholder="Contoh: 08xxxxxxxxxx"
                       class="w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none transition
                              {{ $errors->has('whatsapp') ? 'border-red-300 focus:border-red-400' : 'border-gray-200 focus:border-gray-400' }}">
                @error('whatsapp')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[10px] text-gray-400 mt-1.5">Nomor ini akan dipakai untuk autofill saat booking.</p>
            </div>

            <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-bold bg-accent text-white transition hover:opacity-90">
                Simpan WhatsApp
            </button>
        </form>

        {{-- Ganti Password --}}
        <form method="POST" action="{{ route('dashboard.password.update') }}"
              class="bg-white rounded-2xl border border-gray-100 p-5">
            @csrf
            <p class="text-xs font-bold mb-4 text-dark">Ganti Password</p>

            <div class="mb-3">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 block mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required
                       class="w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none transition
                              {{ $errors->has('current_password') ? 'border-red-300 focus:border-red-400' : 'border-gray-200 focus:border-gray-400' }}">
                @error('current_password')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 block mb-1">Password Baru</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full border rounded-xl px-3.5 py-2.5 text-sm focus:outline-none transition
                              {{ $errors->has('password') ? 'border-red-300 focus:border-red-400' : 'border-gray-200 focus:border-gray-400' }}">
                @error('password')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="text-[10px] uppercase tracking-widest text-gray-400 block mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required minlength="8"
                       class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
            </div>

            <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-bold bg-dark text-white transition hover:opacity-90">
                Ganti Password
            </button>
        </form>

    </div>

    {{-- Hapus Akun --}}
    <div class="mt-4 bg-white rounded-2xl border border-red-100 p-5" x-data="{ confirm: false }">
        <p class="text-xs font-bold text-red-500 mb-1">Hapus Akun</p>
        <p class="text-xs text-gray-400 mb-4">Tindakan ini permanen dan tidak dapat dibatalkan. Semua data akunmu akan dihapus.</p>

        <div x-show="!confirm">
            <button type="button" @click="confirm = true"
                class="px-5 py-2.5 rounded-xl text-sm font-bold border border-red-300 text-red-500 hover:bg-red-50 transition">
                Hapus Akun Saya
            </button>
        </div>

        <div x-show="confirm" x-cloak class="border border-red-200 rounded-xl p-4 bg-red-50">
            <p class="text-sm font-semibold text-red-600 mb-1">Yakin ingin menghapus akun?</p>
            <p class="text-xs text-red-400 mb-4">Akun dan semua datamu akan dihapus permanen.</p>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('dashboard.account.delete') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold bg-red-500 text-white hover:bg-red-600 transition">
                        Ya, Hapus Akun
                    </button>
                </form>
                <button type="button" @click="confirm = false"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var img = document.getElementById('avatar-preview');
    var input = document.getElementById('avatar-input');
    if (input) {
        input.addEventListener('change', previewAvatar);
    }
    if (!img) return;
    img.addEventListener('error', function () {
        img.classList.add('hidden');
        var initials = document.getElementById('avatar-preview-initials');
        if (!initials) {
            initials = document.createElement('div');
            initials.id = 'avatar-preview-initials';
            initials.className = 'w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold text-white border-4 border-gray-100 shadow bg-accent';
            initials.textContent = @json(strtoupper(substr($user->name, 0, 1)));
            img.insertAdjacentElement('afterend', initials);
        } else {
            initials.classList.remove('hidden');
            initials.classList.add('flex');
        }
    });
});

function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;

    const label = document.getElementById('avatar-filename');
    if (label) label.textContent = file.name;

    const reader = new FileReader();
    reader.onload = function(e) {
        const existing = document.getElementById('avatar-preview');
        const initials = document.getElementById('avatar-preview-initials');
        if (existing) {
            existing.src = e.target.result;
            existing.classList.remove('hidden');
            if (initials) initials.classList.add('hidden');
        } else if (initials) {
            const img = document.createElement('img');
            img.id = 'avatar-preview';
            img.src = e.target.result;
            img.alt = 'Avatar';
            img.className = 'w-20 h-20 rounded-full object-cover border-4 border-gray-100 shadow';
            initials.replaceWith(img);
        }

    };
    reader.readAsDataURL(file);
}
</script>
@endsection
