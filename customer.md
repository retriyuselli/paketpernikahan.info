# Customer Section – Arsitektur & Status Halaman

Dokumentasi lengkap fitur customer dashboard: struktur, file, komponen, status halaman, dan konvensi kode.

> **Aturan:** File ini WAJIB diupdate setiap ada perubahan model, API, Filament resource, atau status integrasi iOS.

---

## Struktur File iOS

Semua file customer ada di `PaketPernikahan/Views/Customer/`:

| File | Isi |
|------|-----|
| `CustomerDashboardView.swift` | `CustomerRootView` (ZStack + bottom menu), `CustomerBottomMenu` |
| `CustomerHomeView.swift` | `CustomerDashboardHomeView` – tab Beranda |
| `CustomerPreparationView.swift` | `CustomerPreparationView` – tab Persiapan |
| `CustomerPaymentView.swift` | `CustomerPaymentView` – tab Pembayaran |
| `CustomerPaymentDetailViews.swift` | Sheet detail pembayaran (jadwal, budget, transaksi, metode) |
| `CustomerProfileView.swift` | `CustomerProfileView` + semua sheet settings profil |
| `CustomerNotificationView.swift` | `CustomerNotificationView` – sheet notifikasi (dari bell icon) |
| `CustomerModels.swift` | Semua model/enum data customer |
| `CustomerComponents.swift` | Komponen shared: `CustomerDashboardCard`, dll |
| `SharedVipGuestView.swift` | `SharedVipGuestTokenView` + `SharedVipGuestListView` – akses tamu VIP via token |

---

## Navigasi – Tab Bar

`CustomerRootView` menggunakan ZStack + floating pill bottom menu dengan 5 slot:

| Slot | Tab ID | View |
|------|--------|------|
| Beranda | 0 | `CustomerDashboardHomeView(showQuickActions:, selectedTab:)` |
| Persiapan | 1 | `CustomerPreparationView(showQuickActions:)` |
| + (Quick Action) | — | `confirmationDialog` → sheet atau `selectedTab` |
| Pembayaran | 3 | `CustomerPaymentView()` |
| Profil | 4 | `CustomerProfileView()` |

**Padding konten:** Semua ScrollView menggunakan `.padding(.bottom, 80)` sebagai safety net.

---

## Warna & Font Konvensi

```swift
private let pink = Color(red: 0.96, green: 0.32, blue: 0.50)
```

Font: `.font(.poppins(.subheadline, weight: .semibold))` — gunakan `.semibold`, bukan `.semiBold`.

---

## Model Data – Backend (Laravel)

### Database Tables

| Tabel | Model | Keterangan |
|-------|-------|------------|
| `wedding_infos` | `WeddingInfo` | 1 per user, nama mempelai |
| `wedding_events` | `WeddingEvent` | Lamaran/Pengajian/Akad/Resepsi |
| `family_members` | `FamilyMember` | Anggota keluarga mempelai |
| `vip_guests` | `VipGuest` | Tamu VIP terpisah dari keluarga |
| `vip_guest_delegates` | `VipGuestDelegate` | Token akses berbagi daftar VIP ke orang lain |
| `customer_payment_methods` | `CustomerPaymentMethod` | Bank/e-wallet customer |
| `customer_notifications` | `CustomerNotification` | Notifikasi in-app |
| `customer_preparation_tasks` | `CustomerPreparationTask` | Task checklist — terikat `wedding_event_id` (per event) |
| `label_persiapans` | `LabelPersiapan` | Master label/kategori tugas per jenis acara |
| `preparation_task_templates` | `PreparationTaskTemplate` | Master katalog tugas bawaan per label |

### Persiapan per Acara — Arsitektur

Pola data persiapan mengikuti struktur: **User → WeddingEvent → Task**.

```
User
└── WeddingEvent (lamaran / pengajian / akad / resepsi)
      └── CustomerPreparationTask (wedding_event_id)
            label · title · status · due_date
```

#### Schema `customer_preparation_tasks` (terbaru)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `wedding_event_id` | FK | Task per acara — wajib diisi |
| `label` | string(100), nullable | Kategori tugas (contoh: "Venue", "Dokumen Nikah") |
| `user_id` | FK | Pemilik task |
| `title` | string(200) | Nama tugas |
| `status` | enum | `todo` · `pending` · `done` |
| `due_date` | date, nullable | Deadline tugas |
| `sort_order` | int | Urutan tampil |

> `section_id` masih ada di kolom database (nullable) namun tidak lagi digunakan. Semua task baru wajib terikat ke `wedding_event_id`.

#### Relasi Model

```php
// WeddingEvent → tasks (langsung)
WeddingEvent::preparationTasks()   // hasMany CustomerPreparationTask via wedding_event_id

// WeddingInfo → semua tasks milik user (via events)
WeddingInfo::preparationTasks()    // hasManyThrough: WeddingInfo → WeddingEvent → CustomerPreparationTask
```

#### Icon per Jenis Acara (iOS)

| `jenis_acara` | SF Symbol | Warna badge Filament |
|---------------|-----------|----------------------|
| `lamaran` | `gift.fill` | `.info` (biru) |
| `pengajian` | `book.fill` | `.warning` (kuning) |
| `akad` | `heart.fill` | `.success` (hijau) |
| `resepsi` | `sparkles` | `.danger` (merah) |

#### Tabel `label_persiapans` — Master Kategori

Kategori/label tugas dikelola terpisah agar bisa dipilih via dropdown (bukan input bebas).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `jenis_acara` | enum | `lamaran` · `pengajian` · `akad` · `resepsi` |
| `nama` | string(100) | Nama label (contoh: "Venue", "Dokumen Nikah") |
| `sort_order` | smallint | Urutan tampil di dropdown |

Total label bawaan: **98 label**

| Acara | Jumlah | Contoh Label |
|-------|--------|--------------|
| Lamaran | 15 | Persiapan Umum · Seserahan · Cincin Lamaran · Katering · Dekorasi · Transportasi · Keuangan |
| Pengajian | 13 | Pemimpin Acara · Perlengkapan Ibadah · Konsumsi · Sound System · Hadiah & Souvenir |
| Akad | 29 | KUA & Administrasi · Ijab Kabul · Mahar · Cincin Nikah · Pelaminan · Persiapan Spiritual · Koordinasi WO |
| Resepsi | 41 | Tenda & Fasilitas · Bunga & Rangkaian · Konsumsi VIP · Photo Booth · Live Music / Band · Honeymoon |

> Method `LabelPersiapan::optionsFor(string $jenisAcara)` → dipakai di `Select` Filament untuk dropdown yang otomatis difilter berdasarkan event yang dipilih.

#### Tabel `preparation_task_templates` — Master Katalog Tugas

Tugas bersifat master data — admin mendefinisikan satu kali. `label` disimpan sebagai string (dipilih dari `LabelPersiapan`).

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `jenis_acara` | enum | `lamaran` · `pengajian` · `akad` · `resepsi` |
| `label` | string(100) | Nama kategori — dipilih dari `label_persiapans` |
| `title` | string(200) | Nama tugas |
| `sort_order` | smallint | Urutan tampil per label |

Total template bawaan: **255 tugas**

| Acara | Jumlah |
|-------|--------|
| Lamaran | 36 |
| Pengajian | 29 |
| Akad Nikah | 72 |
| Resepsi | 118 |

#### Seeder — Urutan Wajib

```bash
# 1. Label/kategori dulu
php artisan db:seed --class=LabelPersiapanSeeder

# 2. Template tugas (pakai label dari step 1)
php artisan db:seed --class=PreparationTaskTemplateSeeder

# 3. Tasks per user (dari data template)
php artisan db:seed --class=PreparationEventTaskSeeder
```

Semua sudah terdaftar di `DatabaseSeeder` — cukup `php artisan migrate:fresh --seed` untuk setup lengkap dari awal. Masing-masing seeder aman dijalankan ulang secara individu (pakai `truncate` atau hapus per event).

---

### VipGuest — Kolom & Nilai Valid

| Kolom | Tipe | Nilai Valid / Keterangan |
|-------|------|--------------------------|
| `kategori` | enum | `keluarga_besar` · `pejabat` · `tokoh_masyarakat` · `rekan_bisnis` · `teman` |
| `rsvp_status` | enum | `menunggu` · `hadir` · `tidak_hadir` |
| `rsvp_updated_by_name` | string, nullable | Nama user yang terakhir mengubah RSVP (pemilik atau delegate) |
| `rsvp_updated_at` | timestamp, nullable | Waktu perubahan RSVP terakhir |

> **Migration:** `2026_06_24_000001_add_rsvp_updated_by_to_vip_guests_table.php` — tambah 2 kolom ke tabel `vip_guests`. Sudah dijalankan.

---

## Fitur Tamu VIP – Delegasi Akses (Lengkap)

### Konsep

Customer (pengantin) dapat membuat **token akses** untuk dibagikan ke orang lain (panitia, keluarga, MC, dll.). Penerima token **harus login/register** terlebih dahulu di aplikasi, lalu memasukkan token. Token hanya bisa diklaim oleh **satu user** — user pertama yang menggunakannya.

### Alur Lengkap

```
[PEMILIK]                              [DELEGASI/PENERIMA]
Profil → Daftar Tamu VIP               Profil → Akses Token Delegasi
  → tab "Delegasi Akses"                 (tersedia di DashboardView & CustomerProfileView)
  → Buat Delegasi Baru                 → Paste token
  → Isi nama label + expiry            → Tekan "Akses Daftar Tamu"
  → Backend generate token             → Backend: klaim otomatis jika belum diklaim
  → Share token via ShareLink          → Lihat daftar tamu VIP + update kehadiran
```

### Tabel `vip_guest_delegates`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `user_id` | FK → users | Pemilik daftar VIP (customer/pembuat token) |
| `name` | string | Label akses, misal "Panitia Keluarga" |
| `token` | string(48) | String unik untuk akses |
| `claimed_by_user_id` | FK → users, nullable | ⚠️ User yang mengklaim token. NULL = belum diklaim. |
| `expires_at` | timestamp, nullable | Batas waktu akses. NULL = tidak ada batas |
| `last_accessed_at` | timestamp, nullable | Terakhir kali token digunakan |
| `is_active` | boolean | Apakah token masih aktif (bisa di-toggle pemilik) |

### Migration `claimed_by_user_id`

```php
// database/migrations/xxxx_add_claimed_by_to_vip_guest_delegates_table.php
Schema::table('vip_guest_delegates', function (Blueprint $table) {
    $table->foreignId('claimed_by_user_id')
          ->nullable()
          ->after('user_id')
          ->constrained('users')
          ->nullOnDelete();
});
```

### Logika Token Claim — `GET /api/v1/vip-guests/shared/{token}`

Middleware: `auth:sanctum` (wajib login sebelum akses)

```
1. Cari VipGuestDelegate berdasarkan token
   → Tidak ditemukan → 404 "Token tidak valid atau telah kedaluwarsa."

2. Cek is_active == true
   → false → 404 "Token tidak valid atau telah kedaluwarsa."

3. Cek expires_at (jika tidak null)
   → expires_at < now() → 404 "Token tidak valid atau telah kedaluwarsa."

4. Cek claimed_by_user_id:
   a. NULL            → KLAIM: set claimed_by_user_id = auth()->id(), simpan ke DB
   b. == auth()->id() → izinkan akses (user yang sama boleh akses berulang kali)
   c. != auth()->id() → 403 "Token ini sudah digunakan oleh akun lain."

5. Update last_accessed_at = now()

6. Ambil data dari pemilik token:
   - Load VipGuest milik delegate->user_id (bukan milik auth()->id())
   - Load WeddingInfo milik delegate->user_id → ambil groom_name, bride_name
   - Hitung summary RSVP

7. Return response (lihat format di bawah)
```

### Logika Update RSVP — `PATCH /api/v1/vip-guests/shared/{token}/guests/{id}/rsvp`

Middleware: `auth:sanctum`

```
1. Cari VipGuestDelegate berdasarkan token
2. Cek claimed_by_user_id == auth()->id()
   → Bukan user yang mengklaim → 403
3. Update vip_guest->rsvp_status dengan nilai dari body
4. Return { "message": "Status kehadiran diperbarui." }
```

### Penyimpanan Token di iOS (per-user)

Token delegasi disimpan di `UserDefaults` dengan key **per-user** agar tidak hilang saat logout dan tidak bocor ke user lain:

```swift
// Key: "delegate_access_token_{userId}"
let storageKey = "delegate_access_token_\(session.user?.id ?? 0)"
UserDefaults.standard.set(token, forKey: storageKey)
```

> Token disimpan saat berhasil digunakan dan tetap ada setelah logout. Saat user yang sama login kembali, token langsung terbaca dan tidak perlu dimasukkan ulang selama masa berlaku belum habis.

### Fitur iOS – Tamu VIP (Owner)

| Fitur | Status |
|-------|--------|
| Tambah tamu VIP (form) | ✅ |
| Edit tamu VIP (swipe kiri) | ✅ |
| Hapus satu tamu VIP (swipe kanan + konfirmasi alert) | ✅ |
| Hapus semua tamu VIP (menu ellipsis + konfirmasi alert) | ✅ |
| Tampilan avatar berwarna sesuai RSVP (hijau/merah/pink) | ✅ |
| No HP hanya tampil di view pemilik, bukan delegasi | ✅ |
| Info "Ditandai oleh [nama]" per tamu | ✅ |
| Countdown hari berdasarkan acara terdekat dari `events[]` | ✅ |

---

## Implementasi Controller (Laravel)

### Method `sharedVipGuests`

```php
public function sharedVipGuests(Request $request, string $token)
{
    $delegate = VipGuestDelegate::where('token', $token)->first();

    if (!$delegate || !$delegate->is_active) {
        return response()->json(['message' => 'Token tidak valid atau telah kedaluwarsa.'], 404);
    }

    if ($delegate->expires_at && $delegate->expires_at->isPast()) {
        return response()->json(['message' => 'Token tidak valid atau telah kedaluwarsa.'], 404);
    }

    $authId = $request->user()->id;

    if (is_null($delegate->claimed_by_user_id)) {
        // Klaim otomatis
        $delegate->claimed_by_user_id = $authId;
        $delegate->save();
    } elseif ($delegate->claimed_by_user_id !== $authId) {
        return response()->json(['message' => 'Token ini sudah digunakan oleh akun lain.'], 403);
    }

    $delegate->update(['last_accessed_at' => now()]);

    // Data milik PEMILIK token (bukan user yang sedang login)
    $ownerId  = $delegate->user_id;
    $guests   = VipGuest::where('user_id', $ownerId)->get();
    $wedding  = WeddingInfo::where('user_id', $ownerId)->first();

    $summary = [
        'total'       => $guests->count(),
        'hadir'       => $guests->where('rsvp_status', 'hadir')->count(),
        'tidak_hadir' => $guests->where('rsvp_status', 'tidak_hadir')->count(),
        'menunggu'    => $guests->where('rsvp_status', 'menunggu')->count(),
    ];

    return response()->json([
        'data' => [
            'delegate_name' => $delegate->name,
            'groom_name'    => $wedding?->groom_name,   // ⚠️ wajib ada
            'bride_name'    => $wedding?->bride_name,   // ⚠️ wajib ada
            'guests'        => $guests,
            'summary'       => $summary,
        ]
    ]);
}
```

> **Penting:** `groom_name` dan `bride_name` diambil dari `WeddingInfo` milik **pemilik token** (`delegate->user_id`), bukan user yang sedang login. Kirim `null` jika belum diisi — iOS akan menyembunyikan baris nama pengantin secara otomatis.

### Method `updateSharedVipGuestRsvp`

```php
public function updateSharedVipGuestRsvp(Request $request, string $token, int $guestId)
{
    $delegate = VipGuestDelegate::where('token', $token)
        ->where('is_active', true)
        ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
        ->firstOrFail(); // → 404 jika tidak ditemukan atau sudah expired

    if ($delegate->claimed_by_user_id !== $request->user()->id) {
        return response()->json(['message' => 'Akses ditolak.'], 403);
    }

    $request->validate([
        'rsvp_status' => 'required|in:hadir,tidak_hadir,menunggu',
    ]);

    $guest = VipGuest::where('id', $guestId)
                     ->where('user_id', $delegate->user_id)
                     ->firstOrFail();

    $guest->update([
        'rsvp_status'          => $request->rsvp_status,
        'rsvp_updated_by_name' => $delegate->name,
        'rsvp_updated_at'      => now(),
    ]);

    return response()->json(['message' => 'Status kehadiran diperbarui.']);
}
```

### Method `destroyAllVipGuests` *(baru)*

```php
public function destroyAllVipGuests(Request $request): JsonResponse
{
    VipGuest::where('user_id', $request->user()->id)->delete();
    return response()->json(['message' => 'Semua tamu VIP berhasil dihapus.']);
}
```

> Route: `DELETE /api/v1/customer/vip-guests` (tanpa `{id}`, hanya dalam grup `auth:sanctum` dengan rate limit 30 req/menit).

---

## API — Base URL & Auth

```
Base URL : https://paketpernikahan.co.id/api/v1
           http://localhost:8888/api/v1  (local MAMP)
Header   : Authorization: Bearer <sanctum_token>
Content-Type: application/json
```

**Role default user baru (iOS):** `customer` — langsung bisa akses semua endpoint `/customer/`.

---

## Referensi Cepat — Semua Endpoint Customer

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/v1/me` | Data user dasar |
| GET | `/api/v1/customer/dashboard` | Stats booking, budget, persiapan, countdown |
| GET | `/api/v1/customer/wedding-info` | Nama pengantin + events |
| PUT | `/api/v1/customer/wedding-info` | Update nama pengantin |
| POST | `/api/v1/customer/wedding-events` | Tambah acara |
| PUT | `/api/v1/customer/wedding-events/{id}` | Edit acara |
| DELETE | `/api/v1/customer/wedding-events/{id}` | Hapus acara |
| GET | `/api/v1/customer/family-members` | List keluarga |
| POST | `/api/v1/customer/family-members` | Tambah anggota |
| PUT | `/api/v1/customer/family-members/{id}` | Edit anggota |
| DELETE | `/api/v1/customer/family-members/{id}` | Hapus anggota |
| POST | `/api/v1/customer/family-members/import` | **Import XLSX dari iOS** (multipart, file + opsional replace_all) |
| GET | `/api/v1/customer/vip-guests` | List tamu VIP + summary RSVP |
| POST | `/api/v1/customer/vip-guests` | Tambah tamu VIP |
| PUT | `/api/v1/customer/vip-guests/{id}` | Edit tamu VIP |
| DELETE | `/api/v1/customer/vip-guests` | **Hapus semua tamu VIP** (permanen, tidak bisa dikembalikan) |
| DELETE | `/api/v1/customer/vip-guests/{id}` | Hapus satu tamu VIP |
| GET | `/api/v1/customer/vip-guests/delegates` | List token akses delegasi |
| POST | `/api/v1/customer/vip-guests/delegates` | Buat token delegasi baru |
| DELETE | `/api/v1/customer/vip-guests/delegates/{id}` | Cabut token delegasi |
| GET | `/api/v1/vip-guests/shared/{token}` | Lihat VIP list via token (**butuh login** + klaim otomatis) |
| PATCH | `/api/v1/vip-guests/shared/{token}/guests/{id}/rsvp` | Update RSVP via token (**hanya user yang mengklaim**) |
| GET | `/api/v1/customer/notifications` | List notifikasi |
| PATCH | `/api/v1/customer/notifications/{id}/read` | Tandai dibaca |
| GET | `/api/v1/customer/preparation/sections` | Semua tasks dikelompokkan per event: `{ events: [...] }` |
| POST | `/api/v1/customer/preparation/tasks` | Tambah task (kirim `wedding_event_id` wajib, + `label` opsional) |
| PUT | `/api/v1/customer/preparation/tasks/{id}` | Update task (`label`, `title`, `status`, `due_date`) |
| DELETE | `/api/v1/customer/preparation/tasks/{id}` | Hapus task |
| GET | `/api/v1/customer/preparation/vendors` | Vendor booking list |
| GET | `/api/v1/customer/payments/upcoming` | Tagihan mendatang |
| GET | `/api/v1/customer/payments/schedule` | Jadwal lengkap |
| GET | `/api/v1/customer/payments/all` | Riwayat transaksi |
| GET | `/api/v1/customer/budget` | Budget per vendor |
| GET | `/api/v1/customer/payment-methods` | Metode pembayaran |
| POST | `/api/v1/customer/payment-methods` | Tambah metode |
| DELETE | `/api/v1/customer/payment-methods/{id}` | Hapus metode |
| PUT | `/api/v1/customer/profile` | Update nama & WA |
| PUT | `/api/v1/customer/password` | Ganti password |

**Route config (api.php) — shared endpoints harus di dalam `auth:sanctum`:**
```php
Route::middleware('auth:sanctum')->group(function () {
    // ... semua /customer/ endpoints ...

    // Shared VIP (butuh login, bukan publik)
    Route::prefix('vip-guests/shared')->group(function () {
        Route::get('/{token}', [CustomerController::class, 'sharedVipGuests']);
        Route::patch('/{token}/guests/{guestId}/rsvp', [CustomerController::class, 'updateSharedVipGuestRsvp']);
    });
});
```

**Rate limit:** semua mutasi (POST/PUT/DELETE) = 30 req/menit · password = 5 req/menit.

---

## Contoh Response JSON

### `GET /customer/preparation/sections`

Response dikelompokkan per event:

```json
{
  "data": {
    "events": [
      {
        "id": 3,
        "jenis_acara": "akad",
        "label": "Akad Nikah",
        "tgl_acara": "2026-07-27",
        "days_until": 33,
        "icon": "heart.fill",
        "done": 8,
        "total": 10,
        "tasks": [
          { "id": 1, "label": "Dokumen Nikah", "title": "Menentukan tanggal akad", "status": "done", "due_date": null },
          { "id": 2, "label": "Dokumen Nikah", "title": "Menyiapkan berkas administrasi", "status": "done", "due_date": null },
          { "id": 3, "label": "Penghulu & Wali", "title": "Menentukan penghulu", "status": "todo", "due_date": "2026-07-10" }
        ]
      },
      {
        "id": 4,
        "jenis_acara": "resepsi",
        "label": "Resepsi",
        "tgl_acara": "2026-07-27",
        "days_until": 33,
        "icon": "sparkles",
        "done": 15,
        "total": 20,
        "tasks": [ ... ]
      }
    ]
  }
}
```

> - `events` → tasks yang punya `wedding_event_id`. Urutan: tanggal acara ASC.
> - `days_until` → positif = mendatang, negatif = sudah lewat, null = tanggal belum diisi.
> - `icon` per `jenis_acara`: `lamaran` = `gift.fill` · `pengajian` = `book.fill` · `akad` = `heart.fill` · `resepsi` = `sparkles`
> - `label` per task → nama kategori (misal `"Venue"`, `"Dokumen Nikah"`). Gunakan untuk grouping tasks dalam satu event di iOS. `null` jika task dibuat manual tanpa kategori.

**Body tambah task:**
```json
{ "wedding_event_id": 3, "label": "Prosesi Akad", "title": "Latihan ijab kabul", "status": "todo", "due_date": "2026-07-20" }
```

**Body update task (PUT):**
```json
{ "status": "done" }
{ "label": "Busana & MUA", "title": "Fitting busana pengantin", "status": "pending", "due_date": "2026-07-15" }
```

> - `wedding_event_id` wajib di POST.
> - `label` opsional di POST — jika tidak dikirim, nilai `null`.
> - PUT hanya update field yang dikirim (`sometimes`) — tidak perlu kirim semua field.

---

### `GET /customer/vip-guests`
```json
{
  "data": [
    {
      "id": 1, "name": "Budi Santoso", "jabatan": "Walikota",
      "instansi": "Pemkot Palembang", "phone": "08123456789",
      "kategori": "pejabat", "kategori_label": "Pejabat",
      "rsvp_status": "hadir", "rsvp_label": "Hadir",
      "rsvp_updated_by_name": "Rama Dhona Utama",
      "rsvp_updated_at": "2026-06-24T10:30:00+07:00",
      "catatan": null
    }
  ],
  "summary": { "total": 10, "hadir": 6, "tidak_hadir": 1, "menunggu": 3 }
}
```

### `GET /customer/vip-guests/delegates`
```json
{
  "data": [
    {
      "id": 1,
      "name": "Panitia Keluarga",
      "token": "aB3dEf...",
      "claimed_by_user_id": 42,
      "claimed_by_name": "Siti Rahayu",
      "expires_at": null,
      "last_accessed_at": "2026-06-23T10:00:00+07:00",
      "is_active": true,
      "created_at": "2026-06-23T09:00:00+07:00"
    }
  ]
}
```

> `claimed_by_user_id` dan `claimed_by_name` = null jika belum diklaim siapapun.

**Body buat delegate:**
```json
{ "name": "Panitia Keluarga", "expires_at": "2026-08-01T00:00:00" }
```
*(`expires_at` opsional — jika tidak dikirim, token tidak punya batas waktu)*

### `GET /vip-guests/shared/{token}` *(butuh Authorization header)*
```json
{
  "data": {
    "delegate_name": "Panitia Keluarga",
    "groom_name": "Dimas",
    "bride_name": "Putri",
    "expires_at": "2026-08-01T00:00:00+07:00",
    "created_at": "2026-06-24T09:00:00+07:00",
    "guests": [
      {
        "id": 1, "name": "Budi Santoso", "jabatan": "Walikota",
        "instansi": "Pemkot", "phone": "08123456789",
        "kategori": "pejabat", "kategori_label": "Pejabat",
        "rsvp_status": "hadir", "rsvp_label": "Hadir",
        "rsvp_updated_by_name": "Panitia Keluarga",
        "rsvp_updated_at": "2026-06-24T10:30:00+07:00",
        "catatan": null
      }
    ],
    "summary": { "total": 10, "hadir": 6, "tidak_hadir": 1, "menunggu": 3 }
  }
}
```

> - `groom_name` / `bride_name` → dari `WeddingInfo` milik **pemilik token** (`delegate->user_id`)
> - Kirim `null` jika belum diisi. iOS tampilkan baris "♥ Pernikahan X & Y" hanya jika ada datanya.
> - `delegate_name` → ditampilkan sebagai judul halaman dan dalam kalimat "Selamat datang, Bapak/Ibu [nama]"
> - `expires_at` / `created_at` → ISO 8601, null jika tidak ada batas waktu. iOS menampilkan durasi token di header halaman (warna merah ≤1 hari, oranye ≤3 hari)
> - `rsvp_updated_by_name` → nama siapa yang terakhir mengubah RSVP (pemilik atau delegasi). iOS menampilkan "Ditandai oleh [nama]" di bawah status tamu.
> - `phone` → hanya ditampilkan di view pemilik (`CustomerVipGuestsSheet`), tidak di view delegasi (`SharedVipGuestListView`)

**Error responses:**
```json
// 404 — token tidak ditemukan / kadaluarsa / nonaktif
{ "message": "Token tidak valid atau telah kedaluwarsa." }

// 403 — token sudah diklaim akun lain
{ "message": "Token ini sudah digunakan oleh akun lain." }
```

**Body update RSVP via token:**
```json
{ "rsvp_status": "hadir" }
```
Nilai valid: `hadir` | `tidak_hadir` | `menunggu`

### `GET /customer/family-members`
```json
{
  "data": [
    { "id": 1, "name": "Budi Santoso",  "role": "Ayah Pengantin Pria",   "phone": "08123456789" },
    { "id": 2, "name": "Siti Rahayu",   "role": "Ibu Pengantin Wanita",  "phone": "08987654321" }
  ]
}
```

> Kolom: `id`, `name`, `role` (nullable), `phone` (nullable)

### `POST /customer/family-members/import` *(iOS upload XLSX)*

Request: `multipart/form-data`

| Field | Tipe | Keterangan |
|-------|------|------------|
| `file` | File (XLSX) | Max 2 MB. Kolom: `nama`, `peran`, `telepon` |
| `replace_all` | boolean (opsional) | `true` → hapus semua data lama sebelum import. Default `false` |

Response sukses:
```json
{ "message": "5 anggota keluarga berhasil diimpor, 0 baris dilewati." }
```

> Template XLSX dapat diunduh dari halaman admin Filament atau disediakan secara statis di dalam app iOS.

---

### `GET /customer/wedding-info`
```json
{
  "data": {
    "groom_name": "Dimas Ardiansyah", "bride_name": "Putri Maharani",
    "wedding_date": "2026-07-27", "venue": "Gedung Graha Sriwijaya, Palembang",
    "package_name": null,
    "events": [
      { "id": 1, "jenis_acara": "lamaran",  "label": "Lamaran",    "tgl_acara": "2026-07-07", "lokasi_acara": "Kediaman Keluarga, Palembang", "catatan": null },
      { "id": 2, "jenis_acara": "pengajian","label": "Pengajian",   "tgl_acara": "2026-07-20", "lokasi_acara": "Masjid Al-Mukarramah, Palembang", "catatan": null },
      { "id": 3, "jenis_acara": "akad",     "label": "Akad Nikah", "tgl_acara": "2026-07-27", "lokasi_acara": "Masjid Al-Mukarramah, Palembang", "catatan": null },
      { "id": 4, "jenis_acara": "resepsi",  "label": "Resepsi",    "tgl_acara": "2026-07-27", "lokasi_acara": "Gedung Graha Sriwijaya, Palembang", "catatan": null }
    ]
  }
}
```

`jenis_acara` valid: `lamaran` | `pengajian` | `akad` | `resepsi`

### `GET /customer/dashboard`
```json
{
  "data": {
    "booking_counts": { "total": 2, "confirmed": 1, "pending": 1, "completed": 0, "cancelled": 0, "contacted": 0 },
    "total_budget": 25000000,
    "used_budget": 5000000,
    "preparation": { "total": 22, "done": 10, "pending": 5, "todo": 7 },
    "days_to_wedding": 35
  }
}
```

---

## Filament Admin — Resource Customer

| Resource | Grup | Nav Sort | Fitur Khusus |
|----------|------|----------|--------------|
| `WeddingInfoResource` | Customer | 1 | Tab 1: **WeddingEventsRelationManager** · Tab 2: **PreparationTasksRelationManager** |
| `FamilyMemberResource` | Customer | 2 | Import XLSX + Download Template |
| `VipGuestResource` | Customer | 3 | Import XLSX + Download Template |
| `VipGuestDelegateResource` | Customer | 4 | List, buat, edit, cabut token delegasi |
| `PreparationTaskTemplateResource` | Customer | 5 | Master katalog 255 tugas — label dipilih dari `LabelPersiapan` |
| `LabelPersiapanResource` | Customer | 6 | Master 98 label/kategori per jenis acara — CRUD |
| `CustomerPaymentMethodResource` | Customer | — | — |
| `CustomerNotificationResource` | Customer | — | — |

#### PreparationTasksRelationManager (tab di WeddingInfo)

File: `app/Filament/Admin/Resources/WeddingInfos/RelationManagers/PreparationTasksRelationManager.php`

- **Badge tab** → `done/total` (misal `8/46`)
- **Kolom tabel** → Acara (badge warna) · Kategori · Tugas · Status · Deadline
- **Filter** → by Acara dan by Status
- **Form Tambah & Ubah (sama):**
  1. **Acara** — Select, filtered per user, `->live()`
  2. **Kategori** — Select dari `LabelPersiapan::optionsFor(jenis_acara)`, difilter otomatis saat acara dipilih
  3. **Nama Tugas** — TextInput bebas
  4. **Status** — Select: Belum Dikerjakan · Sedang Dikerjakan · Selesai
  5. **Deadline** — DatePicker, opsional
- **Relasi yang dipakai** → `WeddingInfo::preparationTasks()` (hasManyThrough via `WeddingEvent`)

#### PreparationTaskTemplateResource

File: `app/Filament/Admin/Resources/PreparationTaskTemplates/PreparationTaskTemplateResource.php`

- CRUD master template tugas (jenis acara + kategori + nama + urutan)
- Field **Kategori** → Select dari `LabelPersiapan`, difilter by jenis acara yang dipilih (`->live()`)
- Filter tabel by `jenis_acara` dan by `label`
- Isi awal dari `PreparationTaskTemplateSeeder` (81 template)

#### LabelPersiapanResource

File: `app/Filament/Admin/Resources/LabelPersiapans/LabelPersiapanResource.php`

- CRUD master label/kategori (jenis acara + nama + urutan)
- Filter tabel by `jenis_acara`
- Isi awal dari `LabelPersiapanSeeder` (98 label)

### Import XLSX Anggota Keluarga (Admin)

- Kolom yang dibaca: `nama`, `peran`, `telepon`
- Baris kosong (nama kosong) → dilewati
- File dihapus otomatis setelah import
- Tombol **"Unduh Template"** → download `template-anggota-keluarga.xlsx`

---

### Import XLSX Tamu VIP

- Kolom yang dibaca: `nama`, `jabatan`, `instansi`, `telepon`, `kategori`, `rsvp`, `catatan`
- Nilai tidak dikenal → default: `kategori=teman`, `rsvp=menunggu`
- File dihapus otomatis setelah import
- Tombol **"Unduh Template"** → download `template-tamu-vip.xlsx`

---

## Status Integrasi API

| Halaman / Fitur | Backend | iOS |
|---|---|---|
| Auth (login, register, Apple, Google) | ✅ | ✅ |
| Profil dasar (`/me`) | ✅ | ✅ |
| Wedding Info & Events | ✅ | ✅ |
| Anggota Keluarga (CRUD) | ✅ | ✅ |
| Anggota Keluarga – Import XLSX (iOS upload) | ✅ | ✅ |
| Anggota Keluarga – Hapus semua (`DELETE /family-members`) | ✅ | ✅ |
| Tamu VIP (CRUD + summary RSVP) | ✅ | ✅ |
| Tamu VIP – Hapus semua (`DELETE /vip-guests`) | ✅ | ✅ |
| Tamu VIP – Tracking siapa yang update RSVP (`rsvp_updated_by_name`) | ✅ | ✅ |
| Tamu VIP – Delegasi Akses (buat/hapus token) | ✅ | ✅ |
| Tamu VIP – Akses via Token (butuh login + klaim 1 user) | ✅ | ✅ |
| Tamu VIP – Shared: tampil nama pengantin pemilik token | ✅ | ✅ |
| Tamu VIP – Shared: tampil durasi/expiry token | ✅ | ✅ |
| Tamu VIP – Token disimpan per-user (tidak hilang saat logout) | — | ✅ |
| Dashboard stats (progress persiapan dari semua tasks) | ✅ | ✅ |
| Persiapan per Event (tampil, filter status, swipe delete/edit) | ✅ | ✅ |
| Persiapan – Tambah task per event (form + quick add) | ✅ | ✅ |
| Persiapan Umum (sections + tasks biasa) | ❌ dihapus | — |
| Pembayaran (upcoming, schedule, all) | ✅ | ⏳ |
| Budget | ✅ | ⏳ |
| Metode Pembayaran | ✅ | ⏳ |
| Notifikasi | ✅ | ⏳ |
| Update profil & password | ✅ | ⏳ |

---

---

## Catatan Penting iOS — Countdown Hari Bahagia

Teks "Menuju hari bahagia N hari lagi" di `CustomerProfileView` dihitung **di sisi iOS**, bukan dari backend:

```swift
// daysUntilNearestEvent — ambil tanggal terdekat dari events[] yang belum lewat
let upcoming = activeWeddingInfo.events.compactMap { event -> Date? in
    guard let raw = event.tglAcara, let date = formatter.date(from: raw) else { return nil }
    return Calendar.current.startOfDay(for: date)
}.filter { $0 >= today }
let nearest = upcoming.min()
```

- Format tanggal yang diharapkan dari backend: `"yyyy-MM-dd"` (ISO date string via `tgl_acara`)
- `countdownLabel` output: `"N hari lagi"` · `"Hari ini!"` · `"-"` (jika tidak ada acara mendatang)
- Warna label: **pink** untuk hari biasa · **hijau** untuk `"Hari ini!"`

---

## Urutan Integrasi yang Disarankan

1. `GET /api/v1/customer/wedding-info` → Profil header (nama pengantin, hari H, list events)
2. `GET /api/v1/customer/dashboard` → Beranda (stats persiapan + countdown)
3. `GET /api/v1/customer/preparation/sections` → Tab Persiapan — render `data.events[]` per acara
4. `GET /api/v1/customer/payments/upcoming` → Tab Pembayaran
5. `GET /api/v1/customer/notifications` → Bell icon notifikasi
6. CRUD tasks per event → `POST /preparation/tasks` dengan `wedding_event_id`
7. CRUD wedding events, family members, vip guests → Sheet-sheet edit

#### Catatan penting untuk iOS — Tab Persiapan

Response `GET /preparation/sections` hanya punya satu key:
- **`data.events`** — array event, render sebagai card group per acara

Setiap item di `events` sudah berisi `done`, `total`, `days_until`, `icon`, dan `tasks[]` — iOS tidak perlu join data tambahan.

---

## iOS (Xcode) — Panduan Integrasi Tab Persiapan (Terbaru)

> **Versi API aktif sejak:** 2026-06-25. `CustomerPreparationSection` (section umum) telah dihapus — iOS harus menggunakan struktur event-only.
> **Fix diterapkan 2026-06-25:** `CustomerPreparationSectionsData.general` (non-optional) dihapus dari `CustomerModels.swift` — penyebab error "Gagal membaca respons server" di dashboard. `CustomerPreparationGeneralSection` juga dihapus. `sectionId` di `addTask` body juga dihapus.

### Model Swift yang digunakan

```swift
// CustomerModels.swift

struct PreparationEvent: Codable, Identifiable {
    let id: Int
    let jenisAcara: String       // "lamaran" | "pengajian" | "akad" | "resepsi"
    let label: String            // "Lamaran" | "Pengajian" | "Akad Nikah" | "Resepsi"
    let tglAcara: String?        // "yyyy-MM-dd"
    let daysUntil: Int?          // positif = mendatang, negatif = sudah lewat
    let icon: String             // SF Symbol name
    let done: Int
    let total: Int
    let tasks: [PreparationTask]
}

struct PreparationTask: Codable, Identifiable {
    let id: Int
    let label: String?           // kategori (misal "Mahar", "KUA & Administrasi") — nil jika manual
    let title: String
    let status: String           // "todo" | "pending" | "done"
    let dueDate: String?         // "yyyy-MM-dd"
}

struct PreparationSectionsResponse: Codable {
    let data: PreparationSectionsData
}

struct PreparationSectionsData: Codable {
    let events: [PreparationEvent]
    // ⚠️ "general" SUDAH TIDAK ADA — hapus jika masih ada di model lama
}
```

### Fetch data

```swift
// GET /api/v1/customer/preparation/sections
func fetchPreparation() async {
    let response: PreparationSectionsResponse = try await apiClient.get("/customer/preparation/sections")
    self.events = response.data.events
}
```

### Render per event

```swift
ForEach(events) { event in
    EventPreparationCard(event: event)
}

struct EventPreparationCard: View {
    let event: PreparationEvent

    var body: some View {
        VStack(alignment: .leading) {
            // Header event
            HStack {
                Image(systemName: event.icon)
                Text(event.label)                          // "Akad Nikah"
                Spacer()
                Text("\(event.done)/\(event.total)")       // progress badge
            }

            // Group tasks by label
            let grouped = Dictionary(grouping: event.tasks, by: { $0.label ?? "Lainnya" })
            ForEach(grouped.keys.sorted(), id: \.self) { labelName in
                Section(header: Text(labelName)) {
                    ForEach(grouped[labelName]!) { task in
                        TaskRow(task: task)
                    }
                }
            }
        }
    }
}
```

### Tambah task baru

```swift
// POST /api/v1/customer/preparation/tasks
struct StoreTaskRequest: Encodable {
    let weddingEventId: Int       // wajib — ID dari event.id
    let label: String?            // opsional — nama kategori
    let title: String             // wajib
    let status: String            // "todo" (default)
    let dueDate: String?          // "yyyy-MM-dd" atau nil
}
```

> **Penting:** `weddingEventId` wajib diisi. Tidak ada lagi `sectionId`. Jika iOS masih mempunyai logika `section_id`, hapus seluruhnya.

### Update & hapus task

```swift
// PUT /api/v1/customer/preparation/tasks/{id}
// Kirim hanya field yang berubah (semua opsional)
struct UpdateTaskRequest: Encodable {
    var label: String?
    var title: String?
    var status: String?     // "todo" | "pending" | "done"
    var dueDate: String?
}

// DELETE /api/v1/customer/preparation/tasks/{id}
// Tidak perlu body
```

### Field `label` per task — cara grouping di iOS

`task.label` berisi nama kategori dari master `LabelPersiapan` (misal `"KUA & Administrasi"`, `"Mahar"`, `"Pelaminan"`). Gunakan untuk membuat section/grup di dalam satu card event:

```
[Akad Nikah]
  ▸ KUA & Administrasi
      ☑ Daftar ke KUA
      ☑ Siapkan berkas N1-N4
  ▸ Mahar
      ☐ Tentukan nominal mahar
  ▸ Pelaminan
      ☐ Booking dekorasi pelaminan
```

Task dengan `label = null` → masukkan ke grup `"Lainnya"` atau tampilkan langsung tanpa header grup.

---

## Troubleshooting Production

### Google Sign-In: "Verifikasi token Google gagal" di TestFlight / App Store

**Gejala:** Login dengan Google berhasil di simulator/device debug, tapi gagal di TestFlight atau build production dengan pesan `Verifikasi token Google gagal.`

**Penyebab:** Backend memvalidasi field `aud` dari Google ID token terhadap dua client ID:
```php
$allowedAudiences = array_filter([
    config('services.google.client_id'),     // web client ID
    config('services.google.ios_client_id'), // iOS client ID
]);
```
iOS SDK mengisi `aud` dengan **iOS Client ID** (`GIDClientID`). Jika production `.env` di server tidak punya `GOOGLE_IOS_CLIENT_ID`, validasi gagal.

**Fix:** Tambahkan ke `.env` production (Hostinger):
```env
GOOGLE_IOS_CLIENT_ID=1073847707519-nl2v15m0qh0ep66kkldm92tcuoug3faj.apps.googleusercontent.com
```
Lalu jalankan `php artisan config:clear && php artisan cache:clear`.

**Nilai yang benar (cocokkan dengan `GIDClientID` di `Info.plist`):**
| Key | Value |
|-----|-------|
| `GOOGLE_CLIENT_ID` | `1073847707519-au4ul9ha3b3rrlpf8j7o3ngc4i55fdh4.apps.googleusercontent.com` |
| `GOOGLE_IOS_CLIENT_ID` | `1073847707519-nl2v15m0qh0ep66kkldm92tcuoug3faj.apps.googleusercontent.com` |

---

## App Store / TestFlight — Catatan Release

| Versi | Build | Tanggal | Catatan |
|-------|-------|---------|---------|
| 2.0.2 | 6 | — | Versi sebelumnya (live di App Store) |
| 2.0.3 | 7 | 2026-06-25 | Fitur Anggota Keluarga: XLSX upload + swipe edit/hapus + hapus semua. Hapus key `NSLocalNetworkUsageDescription` & `NSAllowsLocalNetworking` dari `Info.plist`. Fix Google Sign-In production (`GOOGLE_IOS_CLIENT_ID`). |
