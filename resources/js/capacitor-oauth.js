const isCapacitor = () => window.Capacitor?.isNativePlatform?.() === true;

async function initCapacitorOAuth() {
    if (!isCapacitor()) return;

    const { Browser } = await import('@capacitor/browser');
    const { App } = await import('@capacitor/app');

    // Show Apple Sign In button (iOS only)
    const appleBtn = document.getElementById('apple-signin-btn');
    if (appleBtn) appleBtn.style.display = '';

    // Intercept Google login links
    document.addEventListener('click', async (e) => {
        const link = e.target.closest('a[href*="/auth/google"]');
        if (!link) return;

        e.preventDefault();
        const separator = link.href.includes('?') ? '&' : '?';
        const url = link.href + separator + 'source=app';

        await Browser.open({ url, presentationStyle: 'popover' });
    });

    // Handle Apple Sign In button click
    if (appleBtn) {
        appleBtn.addEventListener('click', async () => {
            try {
                appleBtn.disabled = true;
                appleBtn.textContent = 'Memuat...';

                const { SignInWithApple } = await import('@capacitor-community/apple-sign-in');
                const result = await SignInWithApple.authorize({
                    clientId: 'id.co.paketpernikahan.app',
                    redirectURI: 'https://paketpernikahan.co.id/auth/apple',
                    scopes: 'email name',
                    state: '',
                    nonce: Math.random().toString(36).substring(2),
                });

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const response = await fetch('/auth/apple', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        identity_token: result.response.identityToken,
                        given_name: result.response.givenName ?? '',
                        family_name: result.response.familyName ?? '',
                    }),
                });

                const data = await response.json();

                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.error ?? 'Sign in dengan Apple gagal.');
                    appleBtn.disabled = false;
                    appleBtn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg> Lanjutkan dengan Apple`;
                }
            } catch (err) {
                // User cancelled or error
                appleBtn.disabled = false;
                appleBtn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg> Lanjutkan dengan Apple`;
            }
        });
    }

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
