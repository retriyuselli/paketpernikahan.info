<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak - {{ $subjek }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #FAF3E7; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .wrapper { max-width: 580px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .header { background-color: #9CAF88; padding: 32px 40px; text-align: center; }
        .header-logo { font-size: 28px; font-weight: 900; color: #ffffff; letter-spacing: -1px; }
        .header-subtitle { color: rgba(255,255,255,0.85); font-size: 12px; margin-top: 6px; letter-spacing: 2px; text-transform: uppercase; }
        .body { padding: 36px 40px; }
        .label { font-size: 10px; font-weight: 700; color: #9CAF88; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 4px; }
        .value { font-size: 15px; color: #333333; margin-bottom: 20px; line-height: 1.5; }
        .divider { border: none; border-top: 1px solid #f0ebe1; margin: 4px 0 24px; }
        .message-box { background: #FAF3E7; border-radius: 10px; padding: 20px 24px; margin-top: 4px; }
        .message-text { font-size: 15px; color: #444444; line-height: 1.7; white-space: pre-wrap; }
        .reply-btn { display: inline-block; margin-top: 28px; padding: 12px 28px; background-color: #9CAF88; color: #ffffff; text-decoration: none; border-radius: 50px; font-size: 13px; font-weight: 700; letter-spacing: 0.5px; }
        .footer { background-color: #f7f3ec; padding: 20px 40px; text-align: center; }
        .footer p { font-size: 11px; color: #aaaaaa; margin: 0; line-height: 1.8; }
        .footer a { color: #9CAF88; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">

        <div class="header">
            <div class="header-logo">MW</div>
            <div class="header-subtitle">Makna Wedding · Pesan Masuk</div>
        </div>

        <div class="body">
            <p style="font-size:15px;color:#555;margin:0 0 28px;">Kamu mendapatkan pesan baru melalui form kontak di website Makna Wedding.</p>

            <div class="label">Nama</div>
            <div class="value">{{ $nama }}</div>
            <hr class="divider">

            <div class="label">Email Pengirim</div>
            <div class="value">
                <a href="mailto:{{ $email }}" style="color:#9CAF88;text-decoration:none;">{{ $email }}</a>
            </div>
            <hr class="divider">

            <div class="label">Subjek</div>
            <div class="value">{{ $subjek }}</div>
            <hr class="divider">

            <div class="label">Pesan</div>
            <div class="message-box">
                <p class="message-text">{{ $pesan }}</p>
            </div>

            <a href="mailto:{{ $email }}?subject=Re: {{ $subjek }}" class="reply-btn">
                Balas Pesan Ini
            </a>
        </div>

        <div class="footer">
            <p>
                Email ini dikirim otomatis dari <a href="{{ config('app.url') }}">{{ config('app.name') }}</a><br>
                &copy; {{ date('Y') }} Makna Wedding · Sumatera Selatan, Indonesia
            </p>
        </div>

    </div>
</body>
</html>
