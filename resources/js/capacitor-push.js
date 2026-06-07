/**
 * Capacitor Push Notifications Handler
 *
 * Flow:
 * 1. Minta permission ke user
 * 2. Ambil device token (APNs/FCM)
 * 3. Kirim token ke backend untuk disimpan
 * 4. Handle notifikasi yang masuk (foreground & tap)
 */

const isCapacitor = () => window.Capacitor?.isNativePlatform?.() === true;

async function initCapacitorPush() {
    if (!isCapacitor()) return;

    const { PushNotifications } = await import('@capacitor/push-notifications');

    // --- 1. Cek & Minta Permission ---
    const permission = await PushNotifications.checkPermissions();

    if (permission.receive === 'prompt') {
        const result = await PushNotifications.requestPermissions();
        if (result.receive !== 'granted') {
            console.warn('[Push] Permission ditolak user.');
            return;
        }
    } else if (permission.receive === 'denied') {
        console.warn('[Push] Permission push notification ditolak sebelumnya.');
        return;
    }

    // --- 2. Daftarkan device ke APNs/FCM ---
    await PushNotifications.register();

    // --- 3. Terima token, kirim ke backend ---
    PushNotifications.addListener('registration', async (token) => {
        console.log('[Push] Device token:', token.value);
        await sendTokenToBackend(token.value);
    });

    // Error registrasi
    PushNotifications.addListener('registrationError', (error) => {
        console.error('[Push] Registrasi gagal:', error.error);
    });

    // --- 4. Notifikasi masuk saat app di foreground ---
    PushNotifications.addListener('pushNotificationReceived', (notification) => {
        console.log('[Push] Notifikasi diterima (foreground):', notification);

        // Tampilkan sebagai toast / banner custom di foreground
        showInAppNotification(notification);
    });

    // --- 5. User tap pada notifikasi ---
    PushNotifications.addListener('pushNotificationActionPerformed', (action) => {
        console.log('[Push] Notifikasi di-tap:', action);

        const data = action.notification.data ?? {};
        handleNotificationTap(data);
    });
}

/**
 * Kirim device token ke backend Laravel
 */
async function sendTokenToBackend(token) {
    try {
        const platform = window.Capacitor?.getPlatform() ?? 'ios';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        const res = await fetch('/app/push/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            },
            body: JSON.stringify({ token, platform }),
        });

        if (!res.ok) {
            console.error('[Push] Gagal simpan token ke backend:', res.status);
        } else {
            console.log('[Push] Token berhasil disimpan ke backend.');
        }
    } catch (err) {
        console.error('[Push] Error kirim token:', err);
    }
}

/**
 * Tampilkan banner notifikasi custom saat app di foreground
 */
function showInAppNotification(notification) {
    const title = notification.title ?? 'Notifikasi';
    const body  = notification.body  ?? '';

    // Buat elemen banner
    const banner = document.createElement('div');
    banner.className = [
        'fixed top-4 left-1/2 -translate-x-1/2 z-[9999]',
        'bg-white shadow-lg rounded-xl px-4 py-3 flex items-start gap-3',
        'w-[90vw] max-w-sm border border-gray-100',
        'animate-in slide-in-from-top-4 duration-300',
    ].join(' ');

    banner.innerHTML = `
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 truncate">${escapeHtml(title)}</p>
            ${body ? `<p class="text-xs text-gray-500 mt-0.5 line-clamp-2">${escapeHtml(body)}</p>` : ''}
        </div>
        <button class="text-gray-400 hover:text-gray-600 shrink-0 mt-0.5" aria-label="Tutup">✕</button>
    `;

    // Klik banner → navigasi jika ada data url
    banner.addEventListener('click', (e) => {
        if (!e.target.closest('button')) {
            handleNotificationTap(notification.data ?? {});
        }
        banner.remove();
    });

    // Tombol tutup
    banner.querySelector('button').addEventListener('click', () => banner.remove());

    document.body.appendChild(banner);

    // Auto-hilang setelah 5 detik
    setTimeout(() => banner?.remove(), 5000);
}

/**
 * Navigasi berdasarkan data payload notifikasi
 * Payload yang didukung:
 *   - { url: '/booking/123' }      → navigasi ke URL relatif
 *   - { type: 'booking', id: '1' } → navigasi ke halaman booking
 *   - { type: 'chat', id: '1' }    → navigasi ke halaman chat
 */
function handleNotificationTap(data) {
    if (!data) return;

    if (data.url) {
        window.location.href = data.url;
        return;
    }

    const routes = {
        booking: (id) => `/booking/${id}`,
        chat:    (id) => `/chat/${id}`,
        promo:   (id) => `/promo/${id}`,
    };

    const builder = routes[data.type];
    if (builder && data.id) {
        window.location.href = builder(data.id);
    }
}

/**
 * Escape HTML untuk mencegah XSS di banner
 */
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

document.addEventListener('DOMContentLoaded', initCapacitorPush);
