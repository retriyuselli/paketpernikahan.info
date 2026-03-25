# Analisa Relasi Backend — `detail.blade.php`

> File: `resources/views/vendor/detail.blade.php`  
> Tanggal: 24 Maret 2026

---

## 🔴 Bug Aktif — Data Tidak Tampil Benar

### `$g->image_path` vs `$g->image_url`

**Lokasi:** Section "Hero Photos" (side photos) dan "Venue Review Videos"

**Masalah:**  
Di `app/Models/VendorGallery.php`, kolom `image_path` di-cast sebagai `array` (JSON). Jika dipakai langsung di Blade dengan `{{ $g->image_path }}`, akan di-render sebagai teks PHP array mentah, bukan URL gambar.

**Solusi:**  
Gunakan accessor `$g->image_url` yang sudah tersedia di model `VendorGallery`:

```php
// ❌ Salah
$g->image_path

// ✅ Benar
$g->image_url
```

---

## 🟡 Tombol / Aksi Tanpa Backend

| Elemen                    | Lokasi di Blade          | Masalah                                                | Solusi yang Diperlukan                          |
| ------------------------- | ------------------------ | ------------------------------------------------------ | ----------------------------------------------- |
| **Tombol ❤️ Like**        | Header kanan nama vendor | Tidak ada route `POST` untuk increment `vendors.likes` | Route `POST /vendor/{vendor}/like` + controller |
| **"Booking Sekarang"**    | Sidebar CTA              | Tombol statik `<button>`, belum ada sistem reservasi   | Form booking atau redirect ke halaman booking   |
| **"Bagikan"**             | Sidebar CTA              | Tidak ada aksi share — hanya tombol visual             | Web Share API (JS) atau link ke URL saat ini    |
| **"Simpan"**              | Sidebar CTA              | Belum ada sistem favorit/bookmark per user             | Tabel `user_favorites` + route toggle           |
| **"Lihat Semua"** (Video) | Section Review Videos    | `href="#"` — tidak mengarah ke mana pun                | Route ke halaman galeri/video penuh             |
| **"Lihat Semua Ulasan"**  | Section Ulasan           | Tombol statik tanpa paginasi atau route                | Route ke halaman ulasan dengan paginasi         |

---

## 🟡 Placeholder / Data Tidak Real

| Elemen                | Masalah                                                                                                              | Solusi yang Diperlukan                                                                                       |
| --------------------- | -------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------ |
| **Peta / Map**        | Menampilkan gambar `picsum.photos` palsu sebagai "peta". Tidak ada kolom `map_url` atau koordinat di tabel `vendors` | Tambah kolom `map_embed_url` (nullable) di migration vendors, tampilkan sebagai `<iframe>` Google Maps embed |
| **Instagram**         | Ditampilkan sebagai teks biasa (`{{ $vendor->instagram }}`), tidak ada `href`                                        | Wrap dalam `<a href="https://instagram.com/{{ $vendor->instagram }}">`                                       |
| **`reviewer_avatar`** | Fallback ke `picsum.photos` jika null — berfungsi sebagai placeholder tapi bukan data nyata                          | Oke untuk sementara; untuk production perlu upload avatar via storage                                        |

---

## ✅ Yang Sudah Terhubung ke Backend dengan Benar

| Elemen                                          | Sumber Data                                                                                                                   |
| ----------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------- |
| Foto hero & side photos                         | `$vendor->cover_image_url`, `$vendor->galleries`                                                                              |
| Rating, jumlah ulasan, jumlah foto              | `$vendor->rating`, `$vendor->approvedReviews->count()`, `$vendor->galleries->count()`                                         |
| Stats bar (like, komentar)                      | `$vendor->likes`, `$vendor->comments_count`                                                                                   |
| About cards (kapasitas, harga, pengalaman, dll) | `$vendor->capacity`, `$cheapPkg`, `$vendor->experience`, `$vendor->events_done`, `$vendor->venue_type`, `$vendor->facilities` |
| Harga Mulai + diskon                            | `$vendor->cheapestPackage` (via relasi `hasOne` → `VendorPackage`)                                                            |
| Paket & Harga cards                             | `$vendor->packages` (loop dengan `card_color`, `card_text_color`, `items`)                                                    |
| Review Videos                                   | `$vendor->galleries` (filter `video_url`)                                                                                     |
| Daftar ulasan                                   | `$vendor->approvedReviews`                                                                                                    |
| Kontak (telepon, email, instagram, lokasi)      | `$vendor->phone`, `$vendor->email`, `$vendor->instagram`, `$vendor->location`                                                 |
| WhatsApp button                                 | `$vendor->phone` (strip non-digit via `preg_replace`)                                                                         |
| Package modal WA link                           | `$vendor->phone` + `$vendor->name` via JS                                                                                     |

---

## Prioritas Perbaikan

1. **[BUG]** `$g->image_path` → `$g->image_url` — bug aktif yang menyebabkan gambar tidak tampil
2. **[FITUR]** Tambah kolom `map_embed_url` di tabel vendors + ganti placeholder peta dengan real iframe
3. **[FITUR]** Like button → route POST sederhana
4. **[QoL]** Instagram → jadikan hyperlink
5. **[FITUR]** Simpan/Favorit → tabel `user_favorites` (perlu auth)
6. **[FITUR]** Booking Sekarang → sistem reservasi (scope lebih besar)
