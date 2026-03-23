# Role & Konteks Website — Makna Wedding

## Identitas Project

- **Nama Brand**: Makna Wedding (MW)
- **Domain/Lokal**: paketpernikahan
- **Tagline**: Mewujudkan pernikahan impian dengan paket lengkap dan terjangkau

---

## Tujuan Website

Website marketplace paket pernikahan yang menghubungkan calon pengantin dengan vendor pernikahan terpercaya di Palembang, dengan rencana ekspansi ke seluruh Indonesia.

---

## Target Pengguna

1. **Calon Pengantin** — pasangan yang sedang mencari dan merencanakan pernikahan
2. **Vendor Pernikahan** — WO, fotografer, catering, venue, dll. yang ingin daftarkan layanan

---

## Cakupan Wilayah

- **Saat ini (awal)**: Kota Palembang, Sumatera Selatan
- **Rencana**: Seluruh Indonesia

---

## Tech Stack

| Layer         | Teknologi                           |
| ------------- | ----------------------------------- |
| Frontend      | Laravel Blade + Tailwind CSS + Vite |
| Backend/Admin | Filament                            |
| Database      | MySQL (via MAMP)                    |
| Payment       | Midtrans (rencana)                  |
| Komunikasi    | WhatsApp (administrasi order)       |

---

## Status Pengerjaan

### Sudah Ada

- ✅ Header (sticky + collapsible banner, scroll-hide effect)
- ✅ Hero Section (partials)
- ✅ Marquee vendor section
- ✅ Wedding Package section (data dummy)
- ✅ Venue section (data dummy)
- ✅ Venue Review Videos section

### Belum Dibuat

- ⬜ Model (sengaja ditunda, fokus UI dulu)
- ⬜ Footer
- ⬜ Integrasi Midtrans
- ⬜ Sistem WhatsApp notifikasi
- ⬜ Halaman detail paket
- ⬜ Halaman vendor/daftar vendor
- ⬜ Sistem autentikasi untuk vendor

---

## Prioritas Pengerjaan Saat Ini

1. Home page (sedang dikerjakan)
2. Header ✅
3. Hero Section ✅
4. Footer (berikutnya)
5. Model & backend (setelah UI selesai)

---

## Struktur Halaman Home

```
Home
├── Header (layout/header.blade.php)
├── Hero Section (front/sections/hero.blade.php)
├── Marquee Vendor
├── Wedding Package Section
├── Venue Section
├── Venue Review Videos
└── Footer (belum dibuat)
```

---

## Palet Warna (lihat color-palette.md)

- Sage Green `#9CAF88` — aksen utama
- Soft Pink `#F9D5E5` — badge, aksen sekunder
- Light Sage `#C8D5B9` — gradient, elemen sekunder
- Cream `#FAF3E7` — background
- Dark Gray `#444444` — teks utama

---

## Catatan Penting

- Model belum dibuat, semua data saat ini masih **dummy/statis**
- Fokus UI dulu → backend Filament menyusul
- Konvensi: gunakan CSS variables untuk warna brand, bukan Tailwind color classes
