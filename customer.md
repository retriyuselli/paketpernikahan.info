# Customer Section – Arsitektur & Status Halaman

Dokumentasi lengkap fitur customer dashboard: struktur, file, komponen, status halaman, dan konvensi kode.

> **Aturan:** File ini WAJIB diupdate setiap ada perubahan model, API, Filament resource, atau status integrasi iOS.

---

## Struktur File

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

**Posisi menu:** `ZStack(alignment: .bottom)` + `.padding(.bottom, -16)` agar masuk ke area home indicator.

**Padding konten:** Semua ScrollView menggunakan `.padding(.bottom, 80)` sebagai safety net karena `safeAreaInset` tidak propagate melalui NavigationStack.

---

## Tombol + (Quick Actions)

`confirmationDialog("Aksi Cepat")` dengan pilihan:
- **Chat dengan WO** → `quickDestination = .chat` → sheet `ChatListView`
- **Tambah Tugas** → `selectedTab = 1`
- **Bayar Tagihan** → `selectedTab = 3`
- **Cari Paket** → `quickDestination = .store` → sheet `StoreView`

`CustomerQuickDestination` enum: hanya `.store` dan `.chat` (`.wishlist` sudah dihapus).

**Touch area fix:** `plusButton` menggunakan `.contentShape(Circle())` di dalam label untuk membatasi area tap hanya pada lingkaran 48pt.

---

## Warna & Font Konvensi

```swift
private let pink = Color(red: 0.96, green: 0.32, blue: 0.50)
private let softPink = Color(red: 1.0, green: 0.90, blue: 0.93)
```

Font: `.font(.poppins(.subheadline, weight: .semibold))` — gunakan `.semibold` (huruf kecil b), bukan `.semiBold`.

Background halaman: `Color(uiColor: .systemGroupedBackground)`.
Background list row: `Color(uiColor: .secondarySystemGroupedBackground)`.

---

## Status Sheet di CustomerProfileView

| Menu | State | Sheet | Status |
|------|-------|-------|--------|
| Informasi Akun | `showAccountInfo` | `CustomerAccountInfoSheet` | ✅ |
| Informasi Wedding | `showWeddingInfo` | `CustomerWeddingInfoSheet` | ✅ |
| Anggota Keluarga | `showFamilyMembers` | `CustomerFamilyMembersSheet` | ✅ |
| Notifikasi (settings) | `showNotificationSettings` | `CustomerNotificationSettingsSheet` | ✅ |
| Keamanan | `showSecurity` | `CustomerSecuritySheet` | ✅ |
| Bahasa | `showLanguage` | `CustomerLanguageSheet` | ✅ |
| Tema | `showTheme` | `CustomerThemeSheet` | ✅ |
| Bantuan & Dukungan | `showSupport` | `CustomerSupportSheet` | ✅ |
| Tentang Aplikasi | `showAbout` | `CustomerAboutSheet` | ✅ |
| Notifikasi (bell) | `showNotifications` | `CustomerNotificationView` | ✅ |

Semua struct sheet ada di `CustomerProfileView.swift`.

---

## CustomerDashboardHomeView

Menerima dua binding:
- `@Binding var showQuickActions: Bool`
- `@Binding var selectedTab: Int`

Tombol "Lihat Detail" pada card Progress & Laporan Persiapan menggunakan `selectedTab = 1` (bukan `showQuickActions = true`).

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
| `customer_preparation_sections` | `CustomerPreparationSection` | Kategori checklist |
| `customer_preparation_tasks` | `CustomerPreparationTask` | Item checklist per section |

### VipGuest — Kolom & Nilai Valid

| Kolom | Tipe | Nilai Valid |
|-------|------|-------------|
| `kategori` | enum | `keluarga_besar` · `pejabat` · `tokoh_masyarakat` · `rekan_bisnis` · `teman` |
| `rsvp_status` | enum | `menunggu` · `hadir` · `tidak_hadir` |

### VipGuestDelegate — Akses Berbagi Daftar VIP

Customer dapat membuat token akses untuk dibagikan ke orang lain (panitia, keluarga, MC, dll.) tanpa perlu punya akun. Penerima token bisa melihat seluruh daftar VIP milik customer dan mengupdate status RSVP.

| Kolom | Keterangan |
|-------|------------|
| `user_id` | Pemilik daftar VIP (customer) |
| `name` | Label akses, misal "Panitia Keluarga" |
| `token` | String unik 48 karakter untuk akses |
| `expires_at` | Batas waktu akses (nullable = tidak ada batas) |
| `last_accessed_at` | Terakhir kali token digunakan |

### Model Data iOS (Swift)

```swift
enum CustomerQuickDestination: Identifiable { case store, chat }
enum CustomerTaskStatus: Hashable { case done, todo, pending }
struct CustomerPreparationTask, CustomerPreparationSection
struct CustomerPaymentDue, CustomerPaymentTransaction
struct CustomerNotification
struct CustomerScheduleItem, CustomerTransaction
struct CustomerBudgetCategory, CustomerBudgetVendor
struct CustomerPaymentMethodItem
enum PaymentMethodType { case bank, ewallet }
// Tambah:
struct VipGuest        // name, jabatan, instansi, phone, kategori, rsvpStatus, catatan
struct VipGuestDelegate // id, name, token, expiresAt, lastAccessedAt, isActive
```

---

## API — Base URL & Auth

```
Base URL : https://paketpernikahan.co.id/api/v1
           http://localhost:8888/api/v1  (local MAMP)
Header   : Authorization: Bearer <sanctum_token>
Content-Type: application/json
```

Token diperoleh dari endpoint login (`POST /api/v1/auth/login`) dan disimpan di `@AppStorage` / `Keychain`.

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
| GET | `/api/v1/customer/vip-guests` | List tamu VIP + summary RSVP |
| POST | `/api/v1/customer/vip-guests` | Tambah tamu VIP |
| PUT | `/api/v1/customer/vip-guests/{id}` | Edit tamu VIP |
| DELETE | `/api/v1/customer/vip-guests/{id}` | Hapus tamu VIP |
| GET | `/api/v1/customer/vip-guests/delegates` | List token akses delegasi |
| POST | `/api/v1/customer/vip-guests/delegates` | Buat token delegasi baru |
| DELETE | `/api/v1/customer/vip-guests/delegates/{id}` | Cabut token delegasi |
| GET | `/api/v1/vip-guests/shared/{token}` | Lihat VIP list via token (tanpa login) |
| PATCH | `/api/v1/vip-guests/shared/{token}/guests/{id}/rsvp` | Update RSVP via token (tanpa login) |
| GET | `/api/v1/customer/notifications` | List notifikasi |
| PATCH | `/api/v1/customer/notifications/{id}/read` | Tandai dibaca |
| GET | `/api/v1/customer/preparation/sections` | Sections + tasks |
| POST | `/api/v1/customer/preparation/sections` | Tambah section |
| DELETE | `/api/v1/customer/preparation/sections/{id}` | Hapus section + tasks |
| POST | `/api/v1/customer/preparation/tasks` | Tambah task |
| PUT | `/api/v1/customer/preparation/tasks/{id}` | Update task |
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

**Rate limit:** semua mutasi (POST/PUT/DELETE) = 30 req/menit · password = 5 req/menit.

---

## ERD per Halaman

### 1. Beranda (`CustomerDashboardHomeView`)

**API:** `GET /api/v1/customer/dashboard`

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

### 2. Persiapan (`CustomerPreparationView`)

**Contoh response sections:**
```json
{
  "data": [
    {
      "id": 1, "title": "Venue & Dekorasi", "icon": "building.2.fill",
      "done": 3, "total": 5,
      "tasks": [
        { "id": 1, "title": "Survey dan pilih venue", "status": "done", "due_date": "2026-05-23" }
      ]
    }
  ]
}
```

**Body update task:** `{ "status": "done" }`

**Body tambah task:** `{ "section_id": 1, "title": "Nama tugas", "status": "todo", "due_date": "2026-08-01" }`

---

### 3. Pembayaran (`CustomerPaymentView`)

**Contoh response upcoming:**
```json
{
  "data": [
    { "id": 5, "title": "DP / Uang Muka", "vendor": "Grand Ballroom", "due_date": "2026-07-15", "amount": "Rp 5.000.000", "icon": "creditcard.fill" }
  ]
}
```

**Body tambah payment method:**
```json
{ "name": "BCA", "logo_icon": "building.columns.fill", "account_number": "1234567890", "account_name": "Nama", "is_primary": true, "type": "bank" }
```

---

### 4. Notifikasi (`CustomerNotificationView`)

**Contoh response:**
```json
{
  "data": [
    { "id": 1, "group": "Booking", "title": "Booking Dikonfirmasi", "message": "...", "time": "2 jam yang lalu", "icon": "checkmark.circle.fill", "destination": "payment", "tint": "#34C759", "is_unread": true }
  ]
}
```

---

### 5. Profil (`CustomerProfileView`)

**Contoh response wedding-info:**
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

**Contoh response vip-guests:**
```json
{
  "data": [
    { "id": 1, "name": "Budi Santoso", "jabatan": "Walikota", "instansi": "Pemkot Palembang", "phone": "08123456789", "kategori": "pejabat", "kategori_label": "Pejabat", "rsvp_status": "menunggu", "rsvp_label": "Menunggu", "catatan": null }
  ],
  "summary": { "total": 10, "hadir": 6, "tidak_hadir": 1, "menunggu": 3 }
}
```

**Contoh response vip-guests/delegates:**
```json
{
  "data": [
    { "id": 1, "name": "Panitia Keluarga", "token": "aB3dEf...", "expires_at": null, "last_accessed_at": "2026-06-23T10:00:00+07:00", "is_active": true, "created_at": "2026-06-23T09:00:00+07:00" }
  ]
}
```

**Body buat delegate:** `{ "name": "Panitia Keluarga", "expires_at": "2026-08-01T00:00:00" }` *(expires_at opsional)*

**Contoh response vip-guests/shared/{token}:**
```json
{
  "data": {
    "delegate_name": "Panitia Keluarga",
    "guests": [ { "id": 1, "name": "Budi Santoso", "rsvp_status": "menunggu", ... } ],
    "summary": { "total": 10, "hadir": 6, "tidak_hadir": 1, "menunggu": 3 }
  }
}
```

**Body update RSVP via token:** `{ "rsvp_status": "hadir" }` | `"tidak_hadir"` | `"menunggu"`

---

## Filament Admin — Resource Customer

| Resource | Grup | Nav Sort | Fitur Khusus |
|----------|------|----------|--------------|
| `WeddingInfoResource` | Customer | 1 | RelationManager: WeddingEvents |
| `FamilyMemberResource` | Customer | 2 | — |
| `VipGuestResource` | Customer | 3 | Import XLSX + Download Template |
| `VipGuestDelegateResource` | Customer | 4 | List, buat, edit, cabut token delegasi |
| `CustomerPaymentMethodResource` | Customer | — | — |
| `CustomerNotificationResource` | Customer | — | — |
| `CustomerPreparationSectionResource` | Customer | — | RelationManager: Tasks |

### Import XLSX Tamu VIP

Tombol **"Import XLSX"** di halaman list Tamu VIP:
- Pilih customer (dropdown)
- Upload file `.xlsx` / `.xls` maks 2 MB
- Kolom yang dibaca: `nama`, `jabatan`, `instansi`, `telepon`, `kategori`, `rsvp`, `catatan`
- Nilai tidak dikenal → default: `kategori=teman`, `rsvp=menunggu`
- File dihapus otomatis setelah import

Tombol **"Unduh Template"** → download `template-tamu-vip.xlsx` dengan 2 baris contoh.

---

## Status Integrasi API

| Halaman / Fitur | Backend | iOS |
|---|---|---|
| Auth (login, register, Apple, Google) | ✅ | ✅ |
| Profil dasar (`/me`) | ✅ | ✅ |
| Wedding Info & Events | ✅ | ⏳ |
| Anggota Keluarga | ✅ | ⏳ |
| Tamu VIP | ✅ | ⏳ |
| Tamu VIP – Delegasi Akses | ✅ | ⏳ |
| Dashboard stats | ✅ | ⏳ |
| Persiapan (sections + tasks) | ✅ | ⏳ |
| Pembayaran (upcoming, schedule, all) | ✅ | ⏳ |
| Budget | ✅ | ⏳ |
| Metode Pembayaran | ✅ | ⏳ |
| Notifikasi | ✅ | ⏳ |
| Update profil & password | ✅ | ⏳ |

---

## Urutan Integrasi yang Disarankan

1. `GET /api/v1/customer/wedding-info` → Profil header (nama pengantin, hari H)
2. `GET /api/v1/customer/dashboard` → Beranda (stats persiapan + countdown)
3. `GET /api/v1/customer/preparation/sections` → Tab Persiapan
4. `GET /api/v1/customer/payments/upcoming` → Tab Pembayaran
5. `GET /api/v1/customer/notifications` → Bell icon notifikasi
6. CRUD wedding events, family members, vip guests, tasks → Sheet-sheet edit
