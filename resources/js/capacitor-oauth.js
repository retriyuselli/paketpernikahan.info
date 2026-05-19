const isCapacitor = () => window.Capacitor?.isNativePlatform?.() === true;

async function initCapacitorOAuth() {
    if (!isCapacitor()) return;

    const { Browser } = await import('@capacitor/browser');
    const { App } = await import('@capacitor/app');

    // Intercept Google login links
    document.addEventListener('click', async (e) => {
        const link = e.target.closest('a[href*="/auth/google"]');
        if (!link) return;

        e.preventDefault();
        const separator = link.href.includes('?') ? '&' : '?';
        const url = link.href + separator + 'source=app';

        await Browser.open({ url, presentationStyle: 'popover' });
    });

    // Handle redirect back from OAuth via custom URL scheme
    App.addListener('appUrlOpen', async (data) => {
        if (data.url.startsWith('paketpernikahan://auth-success')) {
            await Browser.close();
            const url = new URL(data.url.replace('paketpernikahan://', 'https://dummy.com/'));
            const token = url.searchParams.get('token');
            window.location.href = token ? `/auth/app-token?token=${token}` : '/dashboard';
        }
    });

    // Reload when in-app browser closes (fallback)
    Browser.addListener('browserFinished', () => {
        window.location.reload();
    });
}

document.addEventListener('DOMContentLoaded', initCapacitorOAuth);
