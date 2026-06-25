# Memory — Paket Pernikahan iOS Native

> Memory kanonik lengkap ada di:
> `/Users/ramadhonautama/.claude/projects/-Users-ramadhonautama/memory/paket-pernikahan-native-ios.md`

## Info Proyek

- **Backend Laravel 13**: direktori ini (`/Applications/MAMP/htdocs/xcode/paketpernikahan.info_swift`)
- **Produksi**: https://paketpernikahan.co.id (Hostinger)
- **DB lokal**: MySQL MAMP port 8889, database `paketpernikahancoid`
- **SwiftUI project**: `/Applications/MAMP/htdocs/xcode/PaketPernikahanApp` (XcodeGen)
- **Jalankan untuk device fisik**: `php artisan serve --host=0.0.0.0 --port=8000`
- **Bundle ID**: `id.co.paketpernikahan.app`

## API Base URL (APIClient.swift)

| Kondisi              | URL                                       |
| -------------------- | ----------------------------------------- |
| DEBUG + simulator    | `127.0.0.1:8000`                          |
| DEBUG + device fisik | IP LAN Mac — cek `ipconfig getifaddr en0` |
| RELEASE              | `paketpernikahan.co.id`                   |

> IP LAN Mac berubah setiap ganti jaringan. Saat ini `192.168.1.114`. Update di `Info.plist` key `API_BASE_URL_DEBUG_DEVICE` lalu build ulang.

---

## Tab Bar iOS (per 16 Juni 2026)

Custom floating pill — native UITabBar disembunyikan (`.toolbar(.hidden, for: .tabBar)` di tiap root tab), diganti capsule overlay `ultraThinMaterial`.

| Tab      | View         |
| -------- | ------------ |
| Home     | HomeView     |
| Store    | StoreView    |
| Wedding  | WeddingView  |
| Wishlist | WishlistView |
| Profile  | ProfileView  |

### TabBarVisibility — Reference Counter Pattern

```swift
@Observable final class TabBarVisibility {
    var hidden = false
    private var hideCount = 0
    func hide() { hideCount += 1; hidden = true }
    func show(delay ms: Int = 0) {
        hideCount = max(0, hideCount - 1)
        guard hideCount == 0 else { return }
        // ... set hidden = false after delay
    }
}
```

**Penting**: SwiftUI push navigation — `dest.onAppear` LEBIH DULU dari `source.onDisappear`. Reference counter mengatasi masalah ini: `hide()` increment, `show()` decrement + hanya tampilkan saat count = 0. Pakai `show(delay: 200)` di `onDisappear`.

---

## Komponen Utama SwiftUI

- **AppHeaderView** (Components.swift) — reusable: search bar + avatar + chat icon dengan unread badge (red dot dari `ChatUnreadMonitor`). Avatar pakai `AsyncImage` langsung (bukan `RemoteImage`).
- **AppHeaderView di HomeView** — TIDAK sticky, scroll bersama konten, `.padding(.bottom, -12)`.
- **AppHeaderView di WeddingView** — sticky di atas (di luar ScrollView).
- **BlogSectionView** — sub-tab All Articles/Real Wedding scroll bersama konten. Badge "EDITOR'S COLLECTION" tanpa background, text white size 9.
- **PackageDetailView** — bookingBar overlay (bukan safeAreaInset), `GalleryView` tidak lagi `private` (diperlukan RealWeddingDetailView).
- **RealWeddingDetailView** — cover image height 360pt, tappable → fullscreen `GalleryView`. Content via `BlogContentView`. Tab bar hide/show.
- **ErrorRetryView** — icon `wifi.exclamationmark` dengan `.font(.system(size: 44))` (BUKAN `.poppins` — SF Symbols tidak support custom font). Frame `maxHeight: .infinity` untuk centering.

---

## ChatUnreadMonitor (App.swift)

`@Observable` singleton, di-inject via `.environment`. Poll `GET /chats` setiap 15 detik. On unread baru: play sound (foreground) atau local notification (background). `UnreadTracker` enum di ChatViews.swift menggunakan `UserDefaults` untuk tracking `chatLastRead`.

---

## Backend API Endpoints Baru (per 16 Juni 2026)

### Join Vendor (`JoinVendorController.php`)

| Method | Path                             | Auth | Keterangan                       |
| ------ | -------------------------------- | ---- | -------------------------------- |
| GET    | `/api/v1/join-vendor/provinces`  | —    | Daftar provinsi (ProvinsiEnum)   |
| GET    | `/api/v1/join-vendor/cities`     | —    | Kota/kab per provinsi            |
| GET    | `/api/v1/join-vendor/categories` | —    | Kategori vendor (CategoryVendor) |
| GET    | `/api/v1/join-vendor/status`     | ✓    | Status pengajuan user            |
| POST   | `/api/v1/join-vendor`            | ✓    | Submit pengajuan vendor          |

---

## Aturan Pengembangan SwiftUI

- **Jangan gunakan WKWebView atau SFSafariViewController** untuk menampilkan konten di dalam app. Selalu buat halaman native SwiftUI. Jika konten ada di web (blade), baca lalu terjemahkan ke SwiftUI view.

---

## Fitur yang Sudah Selesai (per 16 Juni 2026)

- [x] Custom floating tab bar
- [x] Splash screen custom (`ss.jpg` → `SplashScreen.jpg`)
- [x] Chat dengan package card di header ChatRoomView
- [x] Unread badge realtime (red dot) di chat icon AppHeaderView
- [x] Suara saat chat masuk (foreground: `AudioServices`, background: local notification)
- [x] Real Wedding: cover image tappable (fullscreen), height 360, konten via BlogContentView
- [x] Tab bar hide/show di PackageDetailView dan RealWeddingDetailView (reference counter)
- [x] ErrorRetryView fix (SF Symbols font + centering)
- [x] Syarat & Ketentuan — `TermsView` native SwiftUI (10 section dari privacy-policy blade)
- [x] T&C tappable di WelcomeView, LoginView, RegisterView → sheet `TermsView`
- [x] RegisterView redesign: Google + Apple sign up, outlined fields, link "Sudah punya akun?"
- [x] JoinVendorSignupView (landing) + JoinVendorView (form)
- [x] "Bergabung sebagai Vendor" di ProfileView (hanya tampil jika bukan vendor/admin)
- [x] Keluar Akun: langsung logout → redirect ke tab Home (tanpa konfirmasi dialog)

---

## Native iOS Update — 2026-06-17

- Menambahkan privacy manifest app utama di `/Applications/MAMP/htdocs/xcode/PaketPernikahanApp/PaketPernikahan/PaketPernikahan/PrivacyInfo.xcprivacy`.
  - Deklarasi `NSPrivacyAccessedAPICategoryUserDefaults`.
  - Reason `CA92.1` untuk penggunaan `UserDefaults` pada unread chat.
  - `NSPrivacyTracking` false, collected data kosong.
- Memindahkan konfigurasi API dan Google Sign-In dari hard-coded Swift source ke `Info.plist`.
  - `API_BASE_URL`: `https://paketpernikahan.co.id/api/v1` untuk release.
  - `API_BASE_URL_DEBUG_DEVICE`: `http://192.168.1.77:8000/api/v1` untuk debug device fisik.
  - `API_BASE_URL_DEBUG_SIMULATOR`: `http://127.0.0.1:8000/api/v1` untuk debug simulator.
  - `GIDClientID`: Google client ID.
- Menambahkan `AppConfiguration` di `Core/APIClient.swift`.
  - Debug simulator membaca `API_BASE_URL_DEBUG_SIMULATOR`.
  - Debug device membaca `API_BASE_URL_DEBUG_DEVICE`.
  - Release membaca `API_BASE_URL`.
  - `App.swift` membaca Google client ID dari `Info.plist`.
- Catatan debugging: error `Gagal membaca respons server` sempat muncul karena debug build memakai production API. Sudah diperbaiki dengan pemilihan URL per environment.
- Validasi: Xcode diagnostics `Core/APIClient.swift` bersih dan full project build berhasil tanpa error.

## Native iOS Follow-up — 2026-06-17

- Permission local notification tidak lagi diminta otomatis saat `ChatUnreadMonitor.start()`.
  - Prompt dipindahkan ke konteks chat melalui `ChatNotificationPermission.requestIfNeeded()`.
  - `ChatListView` meminta permission saat daftar chat dibuka.
  - `ChatRoomView` meminta permission saat ruang chat dibuka langsung dari detail paket.
- Silent failure user-facing dikurangi:
  - Wishlist paket di `PackageDetailView` sekarang memakai `do/catch` dan alert gagal.
  - Like vendor di `VendorDetailView` sekarang memakai `do/catch` dan alert gagal.
  - Load kategori di `StoreView` sekarang menyimpan `categoryErrorMessage` dan menampilkan alert.
  - Polling chat di `ChatRoomView` tidak lagi memakai `try?`; error awal ditampilkan jika chat belum punya pesan.
- `PackageDetailView` dirapikan:
  - Indentasi `hasItems` diperbaiki.
  - `descriptionSection` sekarang dipakai untuk menampilkan deskripsi paket dari API.
- TODO Join Vendor diselesaikan:
  - Tombol "Buka Vendor Saya" sekarang membuka `VendorDashboardView`.
  - `VendorDashboardView` ditambahkan sebagai container tab untuk `VendorPackagesView`, `VendorBookingsInView`, `VendorPaymentsInView`, dan `VendorChatListView`.
- Validasi:
  - Diagnostics bersih untuk file yang disentuh.
  - Full project build berhasil tanpa error.

## UI Polish BlogDetailView — 2026-06-17 (sore)

- Keluhan: konten teks terlalu rapat ke tepi, dan tombol back terasa terlalu mepet kiri dibanding back button view lain.
- Perubahan di `Views/BlogDetailView.swift`:
  - Back button (custom circular): `.padding(.leading, 8)` → `.padding(.leading, 16)` agar sejajar dengan back button native (mis. RealWeddingDetailView yang pakai `navigationBarTitleDisplayMode(.inline)`).
  - Konten body: `.padding(20)` diganti `.padding(.horizontal, 24)` + `.padding(.top, 20)` (bottom 28pt tetap). Horizontal jadi 24pt agar napas teks lebih lega.
  - Cover image tinggi 300pt → 360pt — **dikembalikan ke 300pt** karena di device fisik konten tampak melebar / ter-shift ke kiri sehingga title dan badan teks kepotong. Percobaan menambah `.frame(maxWidth: .infinity, alignment: .leading)` di outer dan inner VStack juga tidak menyelesaikan masalah, dan ikut di-revert. Hipotesis: kombinasi `ignoresSafeArea(.top)` + `safeAreaInset` dari parent `appHomeChrome` (WeddingView) merusak layout pada iOS tertentu. Investigasi lanjutan diperlukan; saat ini BlogDetailView dikembalikan ke state sebelum image dibesarkan.
- Catatan konsistensi: VendorDetailView dan PackageDetailView masih pakai `padding(.leading, 8)` karena back button overlay di hero image gelap; BlogDetail di-shift karena background cover lebih beragam dan teks di bawahnya terasa berbeda alignmentnya.
- Validasi: `XcodeRefreshCodeIssuesInFile` bersih, tidak ada error.

## Troubleshooting — 2026-06-17 (sore)

- Keluhan: app di device fisik menampilkan ErrorRetryView "Tidak dapat terhubung ke server".
- Akar masalah: Laravel `php artisan serve` jalan tapi listen di `127.0.0.1:8000` saja, sehingga tidak bisa diakses dari HP di LAN.
- Konfigurasi Swift sudah benar:
  - IP LAN Mac sekarang `192.168.1.77` (sama dengan `API_BASE_URL_DEBUG_DEVICE` di `Info.plist`).
  - `AppConfiguration` di `Core/APIClient.swift` sudah memilih URL device fisik dengan benar.
- Tindakan: stop PHP lama, jalankan ulang dengan `php artisan serve --host=0.0.0.0 --port=8000` (listen di `*:8000`).
- Verifikasi: `curl http://192.168.1.77:8000/api/v1` dari Mac menghasilkan HTTP 404 (server hidup, route root memang tidak ada).
- Catatan: bila masih gagal dari HP, kemungkinan beda WiFi atau macOS Firewall blok PHP.
- Aturan baru: setiap perubahan / sesi troubleshooting wajib dicatat di file ini.

## Bug Fix Parser Konten Blog — 2026-06-17 (sore)

- Keluhan: konten blog tampil "double" — tiap poin muncul sebagai bullet list lalu langsung diulang sebagai paragraf di bawahnya.
- Penyebab: backend menyimpan `<li>` yang dibungkus `<p>` (umum di rich-text editor seperti TinyMCE/CKEditor). Fungsi `parseBlogBlocks` di `Views/BlogDetailView.swift` scan `<p>` dan `<li>` secara independen, sehingga konten yang sama ter-emit dua kali.
- Fix: sebelum scan `<p>` dan `<h1>..<h6>`, ambil dulu seluruh range `<ul>` / `<ol>`. Match `<p>`/`<h*>` yang lokasinya berada di dalam range list di-skip. `<li>` tetap di-scan seperti semula.
- Dampak: berlaku juga untuk `RealWeddingDetailView` yang reuse `BlogContentView`.

## Admin & Vendor Views Refactor ke Native iOS 26 — 2026-06-17 (sore)

- Tujuan: semua admin (dan sekaligus vendor) view yang menggunakan List jadi konsisten ala Settings app + Liquid Glass scroll edge.
- Perubahan di `Views/VendorAdminViews.swift`:
  - Tambah extension `private extension View { func nativeListChrome() -> some View { listStyle(.insetGrouped).scrollContentBackground(.hidden).background(Theme.screenBackground).liquidGlassScrollEdgeSoft(.vertical) } }`.
  - Replace semua `.listStyle(.plain)` (11×) dengan `.nativeListChrome()` di:
    - VendorPackagesView, VendorBookingsInView, VendorPaymentsInView, VendorChatListView
    - AdminBookingsView, AdminVendorsView, AdminVendorApplicationsView, AdminPaymentsView, AdminChatListView, AdminVendorChatListView, UlasanSayaView.
  - Refactor `AdminPanelView` dari `ScrollView { VStack { adminStatCard ×4 } }` jadi `List { Section "Vendor"/"Booking"/"Keuangan"/"Pengguna" ... }` dengan `.refreshable { await load() }` + `.nativeListChrome()`. Helper `adminStatCard` (custom card) diganti `statRow(icon:color:label:value:)` ringkas.
  - `emptyState(_:icon:)` diganti pakai `ContentUnavailableView` (iOS 17+ native).
- Validasi: build sukses, tidak ada error.

## Centralisasi Resolver URL Gambar Backend — 2026-06-17 (sore)

- Setelah fix `cover_image` di `admin/vendors`, gambar masih kosong di HP karena `cover_image_url` accessor `Vendor` return `/storage/galleries/...` (root-relative; Laravel default `Storage::url()` mengikuti config `filesystems.public.url` yang default-nya tanpa host). `APP_URL` di dev = `http://127.0.0.1:8000` — kalau di-prefix pun, host 127.0.0.1 tidak reachable dari device fisik.
- Solusi: pindahkan helper `resolveContentImageURL(_:)` dari `Views/BlogDetailView.swift` (private) ke `Views/Components.swift` sebagai fungsi global `resolveBackendImageURL(_:)`. `RemoteImage` sekarang otomatis melalui helper ini saat membangun URL untuk `AsyncImage`.
- Dampak: SEMUA pemanggilan `RemoteImage(url: ...)` di app sekarang otomatis:
  - URL absolut produksi → dipakai apa adanya.
  - URL absolut dengan host `127.0.0.1` / `localhost` / `0.0.0.0` → di-rewrite ke host backend aktif.
  - URL root-relative (`/storage/...`) → di-prefix host backend.
  - `data:` URI → apa adanya.
- BlogDetailView image case sekarang langsung `RemoteImage(url: url, contentMode: .fit)` tanpa resolver lokal.
- Validasi: build sukses.

## Backend Fix: admin/vendors cover_image Type Mismatch — 2026-06-17 (sore)

- Keluhan: Admin → All Vendor menampilkan "Gagal membaca respons server" (decoding error di iOS).
- Penyebab: `$v->cover_image` di `Vendor` model di-cast sebagai **array** (kolom JSON multi-image), tapi Swift `AdminVendorItem.coverImage` adalah `String?`. JSONDecoder fail karena dapat array padahal expect string.
- Fix di `app/Http/Controllers/Api/V1/VendorAdminController.php` method `adminVendors()`: ganti `'cover_image' => $v->cover_image` jadi `'cover_image' => $v->cover_image_url`. Accessor `getCoverImageUrlAttribute()` di `Vendor` model sudah ada — return string URL siap pakai (handles array, single string, dan asset URL).
- Cache di-clear (`php artisan optimize:clear`).
- TODO sanity check: kemungkinan endpoint admin lain juga punya mismatch tipe array vs string serupa (mis. `gallery_images`, `cover_image` di RealWedding). Cek bila ada keluhan endpoint admin lain.

## Backend Fix: User::isAdmin() Missing — 2026-06-17 (sore)

- Keluhan: di iOS, Admin → Pengajuan Vendor menampilkan ErrorRetryView dengan pesan dari API:
  `Call to undefined method App\Models\User::isAdmin()`
- Penyebab: `app/Http/Controllers/Api/V1/VendorAdminController.php` memanggil `$request->user()->isAdmin()` di 7 lokasi (line 122, 148, 174, 198, 224, 243, 275), tapi method tersebut tidak ada di `app/Models/User.php`. Konvensi codebase lain pakai `$user->hasRole(['super_admin', 'admin'])` langsung.
- Fix: tambah dua method shorthand di `User` model:
  - `isAdmin(): bool` → `hasRole(['super_admin', 'admin'])`
  - `isVendor(): bool` → `hasRole('vendor')`
- Cache di-clear (`php artisan optimize:clear`).
- Validasi: API harus mengembalikan data setelah login admin. Belum dites end-to-end via HP, tapi sintaks PHP valid dan logic konsisten dengan `canAccessPanel()` yang sudah ada.

## ProfileView Refactor ke Native iOS 26 — 2026-06-17 (sore)

- Tujuan: ProfileView (DashboardView) terasa "full native iOS terbaru".
- Restructure di `Views/AuthViews.swift`:
  - `ScrollView { VStack { profileHeader; cards; menuList } }` → `List { Section ... }` dengan `.listStyle(.insetGrouped)` dan `.scrollContentBackground(.hidden)`.
  - Hero row (gradient + avatar + nama + email + badge) jadi Section pertama dengan `listRowInsets(.init())`, `listRowBackground(.clear)`, `listRowSeparator(.hidden)`.
  - Stats (Total Booking, Wishlist) → `Section("Ringkasan")` dengan dua baris navigable.
  - Status Booking → `Section("Status Booking")` dengan empat baris (dot warna + label + count) yang link ke `BookingsView`.
  - Menu / Vendor / Admin → `Section("...")` masing-masing dengan native row + chevron otomatis dari `NavigationLink` di dalam `List`.
- Tambahan iOS 26:
  - `.refreshable { await loadStats() }` untuk pull-to-refresh.
  - `.liquidGlassScrollEdgeSoft(.vertical)` (fade lembut tepi scroll).
  - Tombol gear di hero pakai `liquidGlassCapsule(tint:)` (sebelumnya `.ultraThinMaterial` keras).
- Helper lama dibuang: `profileHeader`, `statsRow`, `statCard`, `bookingStatusSection`, `bookingStatusPill`, `menuList`, `sectionLabel`, `menuGroup`, `menuRow`, `menuRowLabel`. Diganti: `heroRow`, `statListRow`, `bookingStatusListRow`, `listMenuRow`.
- Validasi: build sukses.

## Inline Image di Konten Blog — 2026-06-17 (sore)

- RichEditor (TinyMCE/CKEditor) di backend mengizinkan upload gambar di dalam konten. Sebelumnya parser membuang `<img>` (ikut di-strip oleh `stripHTMLEntities`).
- Update di `Views/BlogDetailView.swift`:
  - Tambah `BlogBlock.image(url: String)`.
  - Tambah helper `resolveContentImageURL(_:)` — URL absolut / `data:` dipakai apa adanya; root-relative (`/storage/...`) di-prefix host backend dari `AppConfiguration.apiBaseURL`.
  - Scan `<img[^>]*?src="..."[^>]*>` di `parseBlogBlocks`, dipakai untuk semua nesting (skipInsideList false agar image di dalam `<li>` tetap tampil).
  - Render via `RemoteImage(contentMode: .fit)` dengan `frame(maxWidth: .infinity, minHeight: 180, maxHeight: 320)` dan corner radius 10.
- Dampak: berlaku juga di `RealWeddingDetailView` yang reuse `BlogContentView`.
- Validasi: build sukses.

## Fix Inline Image URL `127.0.0.1` — 2026-06-17 (sore)

- Keluhan: placeholder gambar muncul (parser sudah deteksi `<img>`) tapi gambar tidak ter-load di HP.
- Akar masalah: Filament RichEditor menyimpan URL gambar **absolut** dari mesin admin saat upload, jadi muncul `http://127.0.0.1:8000/storage/blogs/attachments/...`. Di HP, `127.0.0.1` = loopback HP itu sendiri → gambar 404.
- Contoh URL dari `curl /api/v1/blogs/<slug>`:
  `<img src="http://127.0.0.1:8000/storage/blogs/attachments/EozjYUJmuM9YHvMfvHzKYKJuaAwS9n70lHOd0gJV.jpg" ...>`
- Fix di `resolveContentImageURL(_:)`: parse host URL gambar; jika host = `127.0.0.1` / `localhost` / `0.0.0.0`, **rewrite** scheme/host/port-nya ke backend yang sedang aktif (`AppConfiguration.apiBaseURL`). Path & query dipertahankan.
- TODO backend: idealnya backend menyimpan URL relatif (`/storage/...`) atau pakai konfigurasi `APP_URL` yang konsisten dengan production, supaya konten tidak terikat ke host upload-time.
- Validasi: build sukses.

## Audit Parser Konten (lanjutan) — 2026-06-17 (sore)

Memeriksa edge case lain pada `parseBlogBlocks` & `stripHTMLEntities`:
- ✅ Fix tambahan di `stripHTMLEntities`:
  - `<br>` / `<br/>` sekarang dikonversi ke `\n` SEBELUM tag lain dibuang. Sebelumnya teks yang dipisah `<br>` di backend menempel jadi satu kata.
  - Tambah decode `&hellip;` → `…`.
  - Tambah collapse 3+ newline berturut-turut menjadi dua (mencegah jarak antar paragraf yang berlebihan).
- ⚠️ Belum di-handle (feature gap, bukan duplikasi):
  - `<ol>` ordered list masih dirender dengan bullet "•" alih-alih nomor.
  - Nested list rata kiri (tidak ada indent).
  - `<a>`, `<strong>`, `<em>`, `<img>`, `<table>`, `<blockquote>` belum punya treatment khusus (teks tetap muncul, formatting hilang).
- Validasi: build sukses, tidak ada error.

## Push Notification — Implementasi FCM — 2026-06-18

- Backend sudah punya `DeviceToken` model, `PushNotificationService` (kreait/laravel-firebase), `PushNotificationController` dari Capacitor lama.
- Firebase credentials: `/Applications/MAMP/htdocs/paketpernikahan.info/storage/app/paket-pernikahan-firebase-adminsdk-fbsvc-3322e8fb26.json` (sudah ada, project: `paket-pernikahan`).
- `GoogleService-Info.plist` dari Capacitor lama cocok bundle ID `id.co.paketpernikahan.app` — sudah dicopy ke `/Applications/MAMP/htdocs/xcode/PaketPernikahanApp/PaketPernikahan/`.
- Routes Laravel ditambahkan:
  - `POST /api/v1/device-tokens` → `PushNotificationController@register`
  - `DELETE /api/v1/device-tokens` → `PushNotificationController@unregister`
- iOS:
  - `App.swift`: tambah `AppDelegate` (configure Firebase, register APNs), `FCMTokenManager` (MessagingDelegate, kirim token ke backend saat login), update `NotificationDelegate` (banner+suara untuk remote push, hanya suara untuk local).
  - `APIClient.swift`: tambah `delete(_:body:)`, `registerDeviceToken(_:)`, `unregisterDeviceToken(_:)`.
  - `Info.plist`: tambah `UIBackgroundModes: remote-notification`.
  - `PaketPernikahan.entitlements`: tambah `aps-environment: development`.
- **Manual steps yang belum dilakukan (perlu dilakukan user di Xcode):**
  1. File → Add Package Dependency → `https://github.com/firebase/firebase-ios-sdk` → pilih `FirebaseMessaging`
  2. Tambah `GoogleService-Info.plist` ke Xcode project navigator (drag dari Finder)
  3. Signing & Capabilities → + → Push Notifications
  4. Pastikan APNs Key (.p8) sudah diupload di Firebase Console → Project Settings → Cloud Messaging → iOS app

## Pending / Belum Dikerjakan

- Bug tap overlap sub-tab All Articles/Real Wedding di BlogSectionView
- `api/v1/me/profile` endpoint belum ada di backend
- Google Sign In end-to-end di device (belum diuji)
- Push notification (remote/APNS)
- Signing team & TestFlight
- Inspiration & Event tab masih Coming Soon
- BlogDetailView belum hide tab bar saat dibuka

## Fix Admin Live Chat iOS Decode — 2026-06-17

- Keluhan: Admin → Live Chat di iOS menampilkan ErrorRetryView `Gagal membaca respons server`.
- Penyebab: endpoint API `GET /api/v1/admin/chats` mengirim field `package` secara parsial (`id`, `name`, `price`, `image_url`) untuk sesi tertentu, sementara model Swift `Package` membutuhkan field wajib seperti `slug` dan `discount`. Decode nested package gagal lalu menjatuhkan seluruh `[ChatSession]`.
- Fix di `PaketPernikahan/PaketPernikahan/Models.swift`:
  - `ChatSession` sekarang memakai custom `init(from:)`.
  - Nested `vendor`, `package`, dan `latestMessage` dibuat failable decode, sehingga data parsial admin tidak membuat list Live Chat gagal tampil.
  - `ChatMessage.package` juga dibuat failable decode untuk kasus payload pesan yang membawa package parsial.
- Validasi: diagnostics `Models.swift` dan `VendorAdminViews.swift` bersih; full project build sukses.

## Troubleshooting Device Tidak Terhubung — 2026-06-17 malam

- Keluhan: Home iOS menampilkan ErrorRetryView `Tidak dapat terhubung ke server. Periksa koneksi internet Anda.`
- Konfigurasi iOS benar:
  - `API_BASE_URL_DEBUG_DEVICE` di `Info.plist` = `http://192.168.1.77:8000/api/v1`.
  - IP LAN Mac saat dicek masih `192.168.1.77`.
- Akar masalah: tidak ada proses Laravel yang listen di TCP `:8000`.
- Tindakan: jalankan ulang backend dengan `php artisan serve --host=0.0.0.0 --port=8000`.
- Verifikasi:
  - `lsof -nP -iTCP:8000 -sTCP:LISTEN` menunjukkan `php84` listen di `*:8000`.
  - `curl http://192.168.1.77:8000/api/v1/home` dari Mac sukses mengembalikan JSON.
  - Log Laravel menerima request device ke `/api/v1/home` dan asset `/storage/...`, sehingga app sudah bisa terhubung lagi.

## UI Polish Home Category Chips — 2026-06-17 malam

- Referensi visual: chip horizontal ala iOS/Apple Music, capsule tinggi, teks bold besar, normal state putih + outline halus, selected state fill abu/tint lembut.
- Perubahan:
  - `Views/Components.swift` `ChipBar`:
    - Awalnya font chip 11pt → 15pt `Poppins-SemiBold`, lalu disesuaikan lagi agar lebih kecil dan normal: `Poppins-Regular` 13pt.
    - Tinggi chip fixed 36pt, horizontal padding 14pt.
    - Normal state: `Theme.cardBackground` + stroke secondary opacity 0.18.
    - Selected state: `Theme.accent.opacity(0.12)` + teks `Theme.accent`.
  - `Views/HomeView.swift` skeleton chip disesuaikan ke tinggi 36pt dan width compact.
  - Review lanjutan: container `categoryChips` di `HomeView` disesuaikan 44pt → 48pt agar muat chip 36pt + vertical padding tanpa terasa terpotong.
- Validasi: diagnostics `Components.swift` dan `HomeView.swift` bersih; full project build sukses.

## UI Polish StoreView — 2026-06-17 malam

- Review screenshot Store:
  - Store memakai native `.searchable`, jadi title `Cari Vendor` + search field tampil bertumpuk sesuai sistem, berbeda dari Home yang custom sebaris search/chat/menu.
  - Chip kategori memakai `ChipBar` reusable yang sama dengan Home.
- Perubahan di `Views/StoreView.swift`:
  - `categoryChips` frame 44pt → 48pt agar konsisten dengan chip compact terbaru.
  - Tambah `.padding(.bottom, 110)` pada feed package agar kartu/list bagian bawah tidak tertutup floating tab bar.
- Follow-up:
  - `GET /api/v1/categories` mengirim semua kategori, termasuk yang belum punya paket.
  - `PackageFeedModel.loadCategories()` sekarang mengambil `GET /api/v1/home`, membaca `packageSections`, lalu menampilkan chip hanya untuk kategori yang punya section paket berisi data.
  - Jika `packageSections` tidak tersedia (kompatibilitas API lama), fallback ke `home.categories`.
- Validasi: diagnostics `StoreView.swift` dan `Components.swift` bersih; full project build sukses.

## UI Polish Wedding Header — 2026-06-17 malam

- Keluhan: halaman Wedding masih menampilkan header search/chat/menu seperti Home, padahal di halaman ini tidak diperlukan.
- Perubahan di `Views/WeddingView.swift`:
  - Hapus state `searchText` yang hanya dipakai untuk `appHomeChrome`.
  - Hapus `.appHomeChrome(searchText:)` dari root Wedding.
  - Tambah `.toolbar(.hidden, for: .navigationBar)` agar navigation bar tetap tidak muncul dan tab Blog/Inspiration/Event menjadi header utama halaman.
- Follow-up screenshot: setelah header search hilang, area atas masih terasa seperti menyisakan reserved navigation bar.
  - Tambah `.navigationBarHidden(true)` dan `.toolbarBackground(.hidden, for: .navigationBar)` di root Wedding agar nav bar benar-benar tidak menyisakan visual/header chrome.
- Validasi: diagnostics `WeddingView.swift` dan `BlogSectionView.swift` bersih; full project build sukses.

## Bug Fix Wedding Pull-to-Refresh Error State — 2026-06-17 malam

- Keluhan: saat menarik halaman Wedding ke bawah, layar bisa berubah menjadi ErrorRetryView `Tidak dapat terhubung ke server`, walaupun backend sebenarnya masih hidup.
- Verifikasi backend:
  - `lsof` menunjukkan Laravel masih listen di `*:8000`.
  - `curl http://192.168.1.77:8000/api/v1/blogs` mengembalikan HTTP 200.
- Penyebab UI:
  - `reloadBlogs()` dan `reloadWeddings()` mengosongkan array (`blogs = []` / `weddings = []`) sebelum request refresh selesai.
  - Jika pull-to-refresh berbarengan dengan loading lain atau ada network hiccup sementara, data lama hilang dan empty state langsung berubah ke error.
- Fix di `Views/BlogSectionView.swift`:
  - `reloadBlogs()` fetch page 1 langsung dan hanya replace `blogs` setelah request sukses.
  - `reloadWeddings()` melakukan pola yang sama untuk `weddings`.
  - Jika reload gagal, data lama tetap tampil; error hanya tersimpan di state.
- Validasi: diagnostics `BlogSectionView.swift` dan `WeddingView.swift` bersih; full project build sukses.

## UI Simplify Profile Booking Status — 2026-06-17 malam

- Keluhan: section `Status Booking` di Profile/Dashboard redundant karena sudah ada menu `Booking Saya`.
- Perubahan di `Views/AuthViews.swift`:
  - Hapus Section `Status Booking` dari `DashboardView`.
  - Hapus helper `bookingStatusListRow(...)` karena tidak lagi dipakai.
- Dampak: Profile lebih ringkas; akses detail booking tetap lewat `Ringkasan → Total Booking` dan `Menu → Booking Saya`.
- Validasi: diagnostics `AuthViews.swift` bersih, tidak ada sisa referensi `Status Booking` / `bookingStatusListRow`, full project build sukses.

## UI Polish Home Logo/Chip/Package Spacing — 2026-06-17 malam

- Keluhan: area partner logo, ChipBar kategori, dan judul `Wedding Package` di Home masih terlalu berjauhan.
- Perubahan di `Views/HomeView.swift`:
  - Partner logo, ChipBar, dan `packageSectionsView` dibungkus dalam satu `VStack(alignment: .leading, spacing: 8)`.
  - Spacing global Home tetap 20pt untuk section lain, sehingga yang dirapatkan hanya cluster logo → chip → Wedding Package.
- Validasi: diagnostics `HomeView.swift` dan `PartnerLogoMarquee.swift` bersih; full project build sukses.

## UI Copy Home Wedding Package — 2026-06-17 malam

- Menambahkan deskripsi kecil di bawah judul `Wedding Package` pada `Views/HomeView.swift`:
  `Solusi pernikahan lengkap dalam satu paket untuk mewujudkan hari bahagia yang lebih mudah, nyaman, dan berkesan.`
- Styling: font `.caption`, warna secondary, fixed vertical size agar wrap natural.
- Validasi: diagnostics `HomeView.swift` dan `Components.swift` bersih; full project build sukses.

## Troubleshooting Device Tidak Terhubung — 2026-06-17 malam (lanjutan)

- Keluhan: Home iPhone menampilkan ErrorRetryView `Tidak dapat terhubung ke server. Periksa koneksi internet Anda.`
- Konfigurasi iOS benar:
  - `API_BASE_URL_DEBUG_DEVICE` di `Info.plist` = `http://192.168.1.77:8000/api/v1`.
  - IP LAN Mac aktif di `en0` = `192.168.1.77`.
- Akar masalah: tidak ada proses yang listen di TCP `:8000`, sehingga request ke `192.168.1.77:8000` gagal connect.
- Tindakan: jalankan backend dengan `php artisan serve --host=0.0.0.0 --port=8000`.
- Verifikasi:
  - `lsof -nP -iTCP:8000 -sTCP:LISTEN` menunjukkan `php84` listen di `*:8000`.
  - `curl http://192.168.1.77:8000/api/v1/home` dari Mac mengembalikan HTTP 200 dan JSON.
- Status: iPhone bisa coba tap `Coba Lagi`; bila masih gagal, cek iPhone berada di jaringan WiFi yang sama dan macOS Firewall tidak memblokir PHP.

## Mengatasi Masalah Jika Server Tidak Jalan

Gunakan langkah ini jika app iPhone menampilkan error `Tidak dapat terhubung ke server. Periksa koneksi internet Anda.` saat debug lokal.

1. Cek IP LAN Mac:
   ```bash
   ifconfig en0 | grep 'inet '
   ```
   IP aktif harus sama dengan `API_BASE_URL_DEBUG_DEVICE` di `PaketPernikahan/PaketPernikahan/Info.plist`, contoh:
   ```xml
   <key>API_BASE_URL_DEBUG_DEVICE</key>
   <string>http://192.168.1.77:8000/api/v1</string>
   ```
   Jika IP berubah, update `Info.plist` lalu build ulang app ke iPhone.

2. Jalankan Laravel dari folder backend dengan host LAN:
   ```bash
   cd /Applications/MAMP/htdocs/xcode/paketpernikahan.info_swift
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   Jangan hanya menjalankan `php artisan serve`, karena biasanya hanya listen ke `127.0.0.1` dan tidak bisa diakses iPhone.

3. Pastikan proses server listen di semua interface:
   ```bash
   lsof -nP -iTCP:8000 -sTCP:LISTEN
   ```
   Output yang benar harus menunjukkan `TCP *:8000 (LISTEN)`.

4. Tes endpoint dari Mac:
   ```bash
   curl -i http://192.168.1.77:8000/api/v1/home
   ```
   Jika benar, response harus `HTTP/1.1 200 OK`.

5. Di iPhone, pastikan WiFi sama dengan Mac, lalu tap `Coba Lagi`.
   Jika masih gagal, cek macOS Firewall apakah memblokir proses PHP (`php84`/`php`).

## UX Join Vendor Setelah Login — 2026-06-17 malam

- Keputusan flow: user harus login dulu sebelum menjadi vendor, jadi aksi utama `Bergabung sebagai Vendor` ditempatkan di Profile/Dashboard setelah user authenticated.
- Perubahan di `Views/AuthViews.swift`:
  - LoginView tidak lagi membuka `JoinVendorSignupView` dari link `Join Vendor`.
  - LoginView sekarang hanya menampilkan arahan kecil: `Ingin menjadi vendor? Masuk dulu, lalu buka Profile > Bergabung sebagai Vendor.`
  - Di Dashboard/Profile, Section `Vendor` dipindah ke atas Section `Menu` agar mudah ditemukan.
  - Row `Bergabung sebagai Vendor` sekarang langsung membuka `JoinVendorView()` karena user sudah login; view ini tetap menangani form, status pengajuan, atau tombol `Buka Vendor Saya`.
- Validasi: diagnostics `AuthViews.swift` bersih dan full project build sukses.

## Native iOS Polish JoinVendorView — 2026-06-18 dini hari

- Keluhan: halaman `Form Pengajuan Vendor` status pending masih terasa custom card/web-style dan tab bar masih muncul di detail.
- Perubahan di `Views/JoinVendorView.swift`:
  - `statusView(_:)` diubah dari `ScrollView + VStack + custom card + Grid` menjadi `List` dengan section native:
    - `Status`
    - `Detail Pengajuan`
    - `Catatan Admin` jika ada
    - action `Buka Vendor Saya` jika approved.
  - Detail row memakai `HStack` native-style, bukan `GridRow` dalam custom card.
  - Form pengajuan diberi chrome konsisten: `.listStyle(.insetGrouped)`, `.scrollContentBackground(.hidden)`, `Theme.screenBackground`, dan `.liquidGlassScrollEdgeSoft(.vertical)`.
  - Tombol submit dan approved action memakai `.buttonStyle(.borderedProminent)` + tint `Theme.accent`.
  - Navigation title diringkas menjadi `Pengajuan Vendor`.
  - `JoinVendorView` sekarang hide floating tab bar via `TabBarVisibility.hide()` saat appear dan `show(delay: 200)` saat disappear.
- Validasi: diagnostics `JoinVendorView.swift` bersih dan full project build sukses.

## Fix VendorDashboardView Double Tab Bar — 2026-06-18 dini hari

- Keluhan: halaman `Vendor Saya` menampilkan dua tab bar bertumpuk:
  - TabView vendor (`Paket`, `Booking`, `Pembayaran`, `Chat`)
  - Floating tab bar app utama (`Home`, `Store`, `Wedding`, `Wishlist`, `Profile`)
- Penyebab: `VendorDashboardView` punya `TabView` sendiri tetapi belum hide floating tab bar utama.
- Fix di `Views/VendorAdminViews.swift`:
  - Tambah `@Environment(TabBarVisibility.self) private var tabBarVisibility` ke `VendorDashboardView`.
  - `.onAppear { tabBarVisibility.hide() }`
  - `.onDisappear { tabBarVisibility.show(delay: 200) }`
- Status child view vendor sudah native enough karena list menggunakan `.nativeListChrome()` (`.insetGrouped`, hidden scroll background, `Theme.screenBackground`, Liquid Glass scroll edge).
- Validasi: diagnostics `VendorAdminViews.swift` bersih dan full project build sukses.

## Vendor Package Management Decision — 2026-06-18 dini hari

- Backend check:
  - API iOS saat ini hanya punya `GET /api/v1/vendor/packages`.
  - Create/update/delete paket sudah ada di web route session-based:
    - `POST /vendor/{vendor:slug}/packages`
    - `PUT /vendor/{vendor:slug}/packages/{package:id}`
    - `DELETE /vendor/{vendor:slug}/packages/{package:id}`
  - Belum ada API Bearer-token untuk create/update/delete paket dari iOS.
  - Form paket web cukup kompleks: harga, DP, diskon, kategori multi-select, upload image, max guests, items, type, capacity, facilities, color, sort order, active state.
- Keputusan produk:
  - iOS vendor dashboard dipakai untuk monitoring paket, booking, pembayaran, dan chat.
  - Create/update/delete paket tetap dilakukan lewat dashboard website desktop.
- Perubahan di `Views/VendorAdminViews.swift`:
  - Empty state `VendorPackagesView` kini menampilkan keterangan bahwa tambah/ubah paket dilakukan lewat dashboard vendor website.
  - Tambah tombol `Buka Dashboard Web` yang membuka URL root website `/vendor` via `openURL` eksternal.
  - URL dibangun dari `AppConfiguration.apiBaseURL`, sehingga debug memakai host lokal aktif dan release memakai production host.
- Validasi: diagnostics `VendorAdminViews.swift` bersih dan full project build sukses.

## UI Polish Vendor Packages — 2026-06-18 dini hari

- Keluhan: halaman `Paket Saya` vendor masih berupa list datar; item aktif dan nonaktif bercampur, dan tidak menampilkan image.
- Backend API update di `app/Http/Controllers/Api/V1/VendorAdminController.php`:
  - `GET /api/v1/vendor/packages` sekarang mengirim `image_url` dari `$p->image_url`.
- Swift update:
  - `Models.swift` `VendorPackageBasic` tambah `imageUrl: String?`.
  - `Views/VendorAdminViews.swift`:
    - List paket dipisah menjadi `Section("Aktif")` dan `Section("Nonaktif")`.
    - Row paket memakai thumbnail `RemoteImage(url: item.imageUrl)` ukuran 64×64.
    - Nama paket lineLimit 2, harga tetap ditampilkan, discount price tetap strikethrough.
- Validasi:
  - PHP syntax `VendorAdminController.php` valid.
  - Diagnostics `VendorAdminViews.swift` dan `Models.swift` bersih.
  - Full project build sukses.

## Vendor Packages Clickable Detail — 2026-06-18 dini hari

- Keluhan: row di `Paket Saya` harus bisa diklik dan mengarah langsung ke detail paketan.
- Backend update:
  - `GET /api/v1/vendor/packages` sekarang juga mengirim `slug`.
  - `Api\V1\PackageController@show` tetap 404 untuk paket nonaktif publik, tetapi mengizinkan owner vendor/admin membuka detail paket nonaktif melalui Bearer token Sanctum.
- Swift update:
  - `Models.swift` `VendorPackageBasic` tambah `slug: String`.
  - `Views/VendorAdminViews.swift` row paket aktif dan nonaktif dibungkus `NavigationLink { PackageDetailView(slug: item.slug) }`.
- Validasi:
  - PHP syntax `VendorAdminController.php` dan `PackageController.php` valid.
  - Diagnostics `VendorAdminViews.swift` dan `Models.swift` bersih.
  - Full project build sukses.

## Troubleshooting Server Lokal Tidak Bisa Diakses iPhone — 2026-06-18 pagi

- Keluhan: Home iPhone kembali menampilkan ErrorRetryView `Tidak dapat terhubung ke server. Periksa koneksi internet Anda.`
- Konfigurasi iOS benar:
  - `API_BASE_URL_DEBUG_DEVICE` = `http://192.168.1.77:8000/api/v1`.
  - IP Mac aktif = `192.168.1.77`.
- Akar masalah sesi ini:
  - Tidak ada server Laravel yang listen di `*:8000` saat dicek ulang.
  - Perintah `php artisan serve` gagal karena `php` tidak ada di PATH terminal Codex.
- Fix:
  - Jalankan Laravel memakai PHP MAMP eksplisit:
    ```bash
    /Applications/MAMP/bin/php/php8.4.1/bin/php artisan serve --host=0.0.0.0 --port=8000
    ```
- Verifikasi:
  - `lsof -nP -iTCP:8000 -sTCP:LISTEN` menunjukkan `php` listen di `TCP *:8000`.
  - `curl http://192.168.1.77:8000/api/v1/home` mengembalikan HTTP 200.
- Catatan: jika terminal biasa punya PATH berbeda, command `php artisan serve --host=0.0.0.0 --port=8000` tetap boleh dipakai. Jika `php` not found, gunakan path PHP MAMP di atas.

## UI Polish Home Background — 2026-06-18 pagi

- Keluhan: background halaman Home terlihat mendekati pink/krem.
- Penyebab: `HomeView` memakai `Theme.screenBackground`, yang di light mode memang `UIColor(red: 0.99, green: 0.96, blue: 0.94)`.
- Perubahan di `Views/HomeView.swift`:
  - Root background Home diganti dari `Theme.screenBackground` menjadi `Color(.systemBackground)`.
  - Perubahan dibatasi hanya ke Home; theme global tidak diubah agar halaman lain tetap memakai latar krem jika memang diinginkan.
- Follow-up di `Views/Components.swift`:
  - Header `appHomeChrome` juga diganti dari `Theme.screenBackground` menjadi `Color(.systemBackground)` agar area search/header ikut putih.
- Validasi: diagnostics `HomeView.swift` dan `Components.swift` bersih, full project build sukses.

## Home Promo See All — 2026-06-18 pagi

- Keluhan: section `Vendor Event dan Promo` di Home perlu tombol `Lihat` seperti section kategori, dan halaman tujuan jika belum ada.
- Perubahan:
  - `Views/HomeView.swift`:
    - Header section `Vendor Event dan Promo` sekarang memakai `HStack` dengan tombol `Lihat` di kanan.
    - Preview di Home dibatasi ke 4 paket pertama agar tombol `Lihat` membawa user ke daftar lengkap.
  - Tambah file `Views/PromoPackagesView.swift`:
    - Halaman native SwiftUI untuk semua paket promo/event.
    - Grid 2 kolom memakai `PackageGridCard`.
    - Tiap item `NavigationLink` ke `PackageDetailView(slug:)`.
    - Background putih `Color(.systemBackground)`, bottom padding 110 untuk floating tab bar.
- Validasi: diagnostics `HomeView.swift` dan `PromoPackagesView.swift` bersih, full project build sukses.

## Home City Packages Slider — 2026-06-18 pagi

- Keluhan: section `Paket Pernikahan per Kota` di Home masih berbentuk daftar/grid panjang, diminta menjadi slider dan memiliki tombol `Lihat`.
- Perubahan:
  - `Views/HomeView.swift`:
    - `Paket Pernikahan per Kota` diubah menjadi horizontal slider.
    - Header section sekarang punya tombol `Lihat` di kanan.
    - Tiap kartu kota bisa diklik dan membuka daftar paket untuk kota tersebut.
    - Kartu kota menampilkan nama kota, jumlah paket, dan preview thumbnail paket.
    - State lama `expandedCities` dihapus karena grid expand/collapse tidak lagi dipakai.
  - Tambah file `Views/CityPackagesView.swift`:
    - Halaman native SwiftUI untuk daftar paket per kota.
    - Tombol `Lihat` dari Home membuka semua kota.
    - Kartu kota tertentu membuka hanya daftar paket kota tersebut.
    - Tiap paket memakai `NavigationLink` ke `PackageDetailView(slug:)`.
- Validasi: diagnostics `HomeView.swift` dan `CityPackagesView.swift` bersih, full project build sukses.

### Revisi Tampilan City Slider — 2026-06-18 pagi

- Keluhan: card `Paket Pernikahan per Kota` yang baru terlalu berbentuk ringkasan kota dan tidak sama seperti ukuran grid paket sebelumnya.
- Perubahan:
  - `Views/HomeView.swift` city slider sekarang menampilkan `PackageGridCard` langsung, bukan card ringkasan kota.
  - Ukuran card dihitung mendekati grid 2 kolom Home, lalu dibungkus horizontal slider per kota.
  - Tiap card paket tetap bisa diklik menuju `PackageDetailView(slug:)`.
- Validasi: diagnostics `HomeView.swift` bersih, full project build sukses.

## Home Review Videos dan Real Wedding See All — 2026-06-18 pagi

- Keluhan: section `Review Videos` dan `Real Wedding` di Home perlu tombol `Lihat`, masing-masing menuju halaman daftar lengkap.
- Perubahan:
  - `Views/HomeView.swift`:
    - Header `Review Videos` sekarang punya tombol `Lihat`.
    - Header `Real Wedding` sekarang punya tombol `Lihat`.
    - Tombol `Review Videos` membuka halaman baru `ReviewVideosView(videos:)`.
    - Tombol `Real Wedding` membuka `RealWeddingListView()`.
  - Tambah file `Views/ReviewVideosView.swift`:
    - Grid 2 kolom untuk semua review video yang sudah tersedia dari API Home.
    - Tiap video bisa diklik dan membuka `videoUrl` lewat `SafariView`.
  - `Views/RealWeddingListView.swift`:
    - Tambah navigation title `Real Wedding`.
    - Background disamakan ke `Color(.systemBackground)`.
- Validasi: diagnostics `HomeView.swift`, `ReviewVideosView.swift`, dan `RealWeddingListView.swift` bersih, full project build sukses.

## Home City Packages Description — 2026-06-18 pagi

- Keluhan: section `Paket Pernikahan per Kota` perlu deskripsi singkat di bawah judul.
- Perubahan:
  - `Views/HomeView.swift`: header section kota sekarang menampilkan deskripsi `Temukan pilihan paket pernikahan dari vendor terbaik di kota Anda.` di bawah judul.
  - Tombol `Lihat` tetap berada di sisi kanan header.
- Validasi: diagnostics `HomeView.swift` bersih, full project build sukses.

## Home Review dan Real Wedding Descriptions — 2026-06-18 pagi

- Keluhan: section `Review Videos` dan `Real Wedding` perlu deskripsi singkat seperti section kota.
- Perubahan:
  - `Views/HomeView.swift`:
    - `Review Videos` menampilkan deskripsi `Lihat inspirasi momen pernikahan dari pasangan dan vendor pilihan.`
    - `Real Wedding` menampilkan deskripsi `Cerita dan dokumentasi pernikahan nyata sebagai referensi konsep hari bahagia Anda.`
    - Tombol `Lihat` tetap di sisi kanan masing-masing header.
- Validasi: diagnostics `HomeView.swift` bersih, full project build sukses.

## Home Vendor Event dan Promo Description — 2026-06-18 pagi

- Keluhan: section `Vendor Event dan Promo` juga perlu deskripsi singkat seperti section lain.
- Perubahan:
  - `Views/HomeView.swift`: header `Vendor Event dan Promo` sekarang menampilkan deskripsi `Temukan penawaran spesial dan event vendor untuk persiapan pernikahan Anda.`
  - Tombol `Lihat` tetap berada di sisi kanan header.
- Validasi: diagnostics `HomeView.swift` bersih, full project build sukses.

## Home Hamburger Menu Menyesuaikan Profile — 2026-06-18 pagi

- Keluhan: isi hamburger menu Home masih hardcoded `Beranda`, `Store`, `Wedding`, dll, sementara user ingin isinya menyesuaikan dengan menu yang ada di halaman Profile.
- Perubahan:
  - `Views/HomeMenuView.swift` dirombak menjadi menu profile-style.
  - Saat user login:
    - Menampilkan ringkasan akun kecil.
    - Menampilkan section `Ringkasan`, `Vendor`, `Menu`, serta section role `Vendor`/`Admin` sesuai role user.
    - Destination disamakan dengan `DashboardView`: `BookingsView`, `WishlistView`, `ChatListView`, `UlasanSayaView`, `EditProfileView`, `SettingsView`, `JoinVendorView`, `VendorPackagesView`, dan view admin/vendor terkait.
    - Memuat `me/dashboard` untuk total booking dan wishlist.
  - Saat user belum login:
    - Menampilkan ajakan login dan link ke `WelcomeView`.
- Validasi: diagnostics `HomeMenuView.swift` bersih, full project build sukses.

## Home Hamburger Menu Drawer Kiri — 2026-06-18 siang

- Keluhan: hamburger menu masih tampil dari bawah seperti sheet, user ingin tampil dari kiri.
- Perubahan:
  - `Views/Components.swift`: pemanggilan `HomeMenuView` diganti dari `.sheet` menjadi overlay side drawer dari sisi kiri.
  - Drawer memakai dim background yang bisa ditap untuk menutup.
  - `Views/HomeMenuView.swift`: tambah optional `onClose` callback agar tombol X bisa menutup drawer kiri, tetap fallback ke `dismiss()` jika dipakai sebagai sheet.
- Validasi: diagnostics `Components.swift` dan `HomeMenuView.swift` bersih, full project build sukses.

### Revisi Posisi Drawer — 2026-06-18 siang

- Keluhan: drawer kiri terlalu tinggi/menempel ke atas layar.
- Perubahan:
  - `Views/Components.swift`: drawer tidak lagi memakai `ignoresSafeArea(edges: .vertical)`.
  - Drawer diberi margin atas dan bawah 46pt, dengan tinggi dikurangi 92pt agar posisinya turun dan tidak menempel ke status bar.
- Validasi: diagnostics `Components.swift` bersih, full project build sukses.

### Revisi Lebar dan Font Drawer — 2026-06-18 siang

- Keluhan: drawer kiri terlalu melebar ke kanan dan teks menu terlalu besar.
- Perubahan:
  - `Views/Components.swift`: lebar drawer dikurangi dari 88%/maks 380pt menjadi 78%/maks 330pt.
  - `Views/HomeMenuView.swift`: font item menu/stat diperkecil dari `subheadline` ke `footnote`.
  - Ikon menu diperkecil dari 32x32 dengan font 15 menjadi 30x30 dengan font 13.
- Validasi: diagnostics `Components.swift` dan `HomeMenuView.swift` bersih, full project build sukses.

## Edit Profile Avatar Upload iOS — 2026-06-18 siang

- Keluhan: halaman Profile menampilkan avatar, tetapi `Edit Profil` belum menyediakan tempat untuk mengganti avatar.
- Backend:
  - `routes/api.php`: tambah `POST /api/v1/me/avatar` di grup `auth:sanctum`.
  - `App\Http\Controllers\Api\V1\AuthController`: tambah `updateAvatar(Request $request)`.
  - Validasi avatar: required image, jpeg/jpg/png/webp, max 2MB.
  - Avatar lama dihapus dari storage public jika bukan URL eksternal.
  - Avatar baru disimpan ke `storage/app/public/avatars` dan response memakai `UserResource`.
  - Cache route fisik `bootstrap/cache/routes-v7.php` perlu dihapus manual karena `route:clear` melapor sukses tetapi file tetap ada. Setelah dihapus, `route:list --path=api/v1/me` menampilkan `POST api/v1/me/avatar`.
- iOS:
  - `Core/APIClient.swift`: tambah helper `upload(_:fileData:fieldName:fileName:mimeType:)` untuk multipart/form-data dengan Bearer token.
  - `Views/AuthViews.swift` `EditProfileView`:
    - Import `PhotosUI` dan `UIKit`.
    - Tambah section `Foto Profil` di atas `Info Profil`.
    - Menampilkan avatar saat ini / preview foto terpilih / inisial fallback.
    - Tombol `Ganti Foto` memakai `PhotosPicker(selection:matching: .images)`.
    - Foto diperkecil ke max dimension 700 dan dikompresi JPEG dengan target sekitar 700KB agar tidak mudah kena validasi `max`.
    - Setelah pilih foto, upload otomatis ke `me/avatar`, refresh user session, dan avatar Profile ikut update.
- Revisi error 422:
  - `Core/APIClient.swift` `APIMessage` sekarang membaca field Laravel `errors`, sehingga pesan validasi spesifik tampil ke user, bukan hanya "Terjadi kesalahan (kode 422)".
  - Backend max upload dinaikkan dari 1MB ke 2MB untuk memberi ruang pada foto kamera iPhone.
- Validasi:
  - Swift diagnostics `AuthViews.swift` dan `APIClient.swift` bersih.
  - PHP syntax `AuthController.php` dan `routes/api.php` valid.
  - `route:list --path=api/v1/me` menampilkan `POST api/v1/me/avatar`.
  - Full Xcode project build sukses.

## Fix Avatar Upload Error Notification — 2026-06-18

- Keluhan: Edit Profil menampilkan "Terjadi kesalahan (kode 422)." generik saat upload foto gagal.
- Dua penyebab:
  1. `APIClient.upload()` memakai `try? decoder.decode(APIMessage.self, ...)` — jika decode gagal (misal server return HTML atau JSON struktur beda), langsung fallback ke teks generik.
  2. Tidak ada notifikasi yang jelas (hanya teks merah inline, tidak cukup menonjol).
- Fix di `Core/APIClient.swift`:
  - Tambah helper `private func parseErrorMessage(from:statusCode:)`.
  - Coba `decoder.decode(APIMessage.self, ...)` dulu.
  - Fallback ke `JSONSerialization` — ekstrak field `errors` (nilai pertama) atau `message` dari raw JSON.
  - Baru fallback ke "Terjadi kesalahan (kode X)." jika semua gagal.
  - Dipakai di `upload()` dan `request()`.
- Fix di `Views/AuthViews.swift` `EditProfileView`:
  - Tambah `@State private var avatarAlertMessage` dan `showAvatarAlert`.
  - Helper `showAlert(_:)` yang set kedua state tersebut.
  - `handleAvatarSelection()`: error baca/proses foto → alert langsung (tidak perlu hit server).
  - Pre-flight size check: jika compressed JPEG > 1.8 MB → alert "Ukuran foto melebihi batas 2 MB" tanpa upload.
  - `uploadAvatar()`: catch error → alert (bukan teks inline).
  - `.alert("Gagal Upload Foto", ...)` ditambahkan ke view.
- Validasi: diagnostics `APIClient.swift` dan `AuthViews.swift` bersih.

## UI Drawer Tinggi Diperlebar — 2026-06-18 siang

- Keluhan: drawer menu kiri terlalu pendek, item di bawah (Pengaturan, dll) terpotong.
- Perubahan di `Views/Components.swift`:
  - Padding atas: 46pt → 8pt.
  - Padding bawah: 46pt → 8pt.
  - Tinggi: `proxy.size.height - 92` → `proxy.size.height - 16`.
- Drawer sekarang hampir full-height, hanya gap kecil 8pt di atas dan bawah.
- Validasi: diagnostics `Components.swift` bersih.

## Hamburger Diganti Avatar di Header — 2026-06-18 siang

- Keluhan: ikon hamburger (`line.3.horizontal`) di header ingin diganti dengan avatar user.
- Perubahan di `Views/Components.swift` `AppHomeChrome`:
  - Tombol hamburger diganti tombol avatar.
  - Tambah computed property `avatarButton`:
    - Jika user punya foto profil → `RemoteImage` bulat 34pt + stroke tipis.
    - Jika login tapi belum foto → inisial nama dalam lingkaran `Theme.accent.opacity(0.15)`.
    - Jika belum login → SF Symbol `person.circle.fill` abu-abu 34pt.
  - Klik tetap memanggil `showMenu = true` (drawer kiri).
- Validasi: diagnostics `Components.swift` bersih.

## Push Notification FCM — Implementasi Penuh — 2026-06-18

- Backend sudah punya `DeviceToken` model + migration, `PushNotificationService` (kreait/laravel-firebase), `PushNotificationController` dari Capacitor lama.
- Firebase credentials ada di: `/Applications/MAMP/htdocs/paketpernikahan.info/storage/app/paket-pernikahan-firebase-adminsdk-fbsvc-3322e8fb26.json` (project: `paket-pernikahan`). Path dikonfigurasi di `.env` dan FILE SUDAH ADA.
- `GoogleService-Info.plist` dari Capacitor lama cocok Bundle ID `id.co.paketpernikahan.app` — dicopy ke `/Applications/MAMP/htdocs/xcode/PaketPernikahanApp/PaketPernikahan/`.
- Backend (`routes/api.php`): tambah import `PushNotificationController`, route:
  - `POST /api/v1/device-tokens` → `PushNotificationController@register`
  - `DELETE /api/v1/device-tokens` → `PushNotificationController@unregister`
- iOS `App.swift`:
  - Tambah `AppDelegate` (UIApplicationDelegate): `FirebaseApp.configure()`, set `Messaging.messaging().delegate`, `registerForRemoteNotifications()`, forward APNs token ke Firebase.
  - Tambah `FCMTokenManager` (MessagingDelegate, `@unchecked Sendable`): terima token dari Firebase, simpan di `UserDefaults` key `fcm_device_token`, kirim ke backend saat user login (`onLogin()`), hapus dari backend saat logout (`onLogout(oldToken:)`).
  - `NotificationDelegate` diperbarui: remote push → `.banner + .sound + .badge`; local notification → `.sound` saja.
  - `PaketPernikahanApp` tambah `@UIApplicationDelegateAdaptor(AppDelegate.self) var appDelegate`.
  - `onChange(session.isAuthenticated)`: saat login panggil `FCMTokenManager.shared.onLogin()`; saat logout panggil `onLogout(oldToken:)`.
- iOS `APIClient.swift`:
  - Tambah `delete<T, B>(_:body:)` convenience method.
  - Tambah `registerDeviceToken(_:)` → POST `device-tokens`.
  - Tambah `unregisterDeviceToken(_:)` → DELETE `device-tokens`.
- iOS `Info.plist`: tambah `UIBackgroundModes: remote-notification`.
- iOS `PaketPernikahan.entitlements`: tambah `aps-environment: development`.
- **Status penyelesaian manual steps (per 2026-06-18):**
  1. ✅ Firebase SDK (FirebaseMessaging, dll) sudah ditambahkan via SPM — error `No such module 'FirebaseCore'` sudah hilang.
  2. ✅ `GoogleService-Info.plist` sudah masuk Xcode project navigator (proses: ada duplikat "2", diselesaikan dengan hapus file lama via Terminal lalu rename di Xcode).
  3. ✅ `aps-environment: development` sudah ada di entitlements — "Push Notifications" capability tidak perlu ditambahkan manual karena entitlement sudah terpasang langsung.
  4. ✅ APNs Auth Key **sudah diupload sebelumnya** di Firebase Console (Key ID: `B27DFA2JQD`, Team ID: `LHH8L9GYY9`). Firebase Cloud Messaging API (V1): Enabled.
- Bug fix `App.swift`: `let token = storedToken ?? (await fetchCurrentToken())` → autoclosure tidak support async. Diperbaiki dengan `var token = storedToken; if token == nil { token = await fetchCurrentToken() }`.
- **Push notification setup SELESAI PENUH** dari sisi iOS dan Firebase. Tinggal build + run ke iPhone fisik, tap Allow saat dialog izin muncul.

## Pending / Belum Dikerjakan

- Bug tap overlap sub-tab All Articles/Real Wedding di BlogSectionView
- `api/v1/me/profile` endpoint belum ada di backend (save profil akan gagal)
- Google Sign In end-to-end di device (belum diuji)
- ~~Push notification~~ ✅ SELESAI — Firebase SDK SPM, GoogleService-Info.plist, entitlements, APNs key Firebase Console semua sudah terpasang
- Signing team & TestFlight
- Inspiration & Event tab masih Coming Soon
- BlogDetailView belum hide tab bar saat dibuka

## Session 2026-06-20

### Throttle Koneksi Gambar (Components.swift)

- Keluhan: banyak log `nw_connection_copy_protocol_metadata_internal on unconnected nm_connection` saat load pertama kali.
- Penyebab: semua `RemoteImage` langsung fire `URLSession.shared` bersamaan (20–30+ request serentak) saat `HomeView` selesai load data.
- Fix di `Views/Components.swift`:
  - Tambah `session: URLSession` di `RemoteImageCache` dengan `httpMaximumConnectionsPerHost = 4`.
  - `RemoteImage.loadImage()` sekarang memakai `RemoteImageCache.shared.session` bukan `URLSession.shared`.
- Dampak: log spam berkurang drastis; gambar tetap muncul, hanya queue jika > 4 koneksi serentak.

### Gallery Real Wedding (backend + iOS)

- **Problem**: halaman `RealWeddingDetailView` tidak menampilkan galeri foto.
- **Root cause ditemukan setelah 3 langkah investigasi:**
  1. iOS model `RealWeddingDetail` sudah benar (decode dari key `gallery`/`galleries`/`images`).
  2. Database `paket_nikah` tabel `real_weddings` sudah punya kolom `gallery` (JSON) dan data Kevin & Nabila sudah ada (4 foto).
  3. **Bug ada di backend**: `RealWeddingController::show()` tidak menyertakan `gallery_urls` di response JSON.
- **Fix backend** di `app/Http/Controllers/Api/V1/RealWeddingController.php`:
  - Tambah `'gallery' => $realWedding->gallery_urls` ke array_merge di method `show()`.
- **Fix iOS** di `Views/RealWeddingDetailView.swift`:
  - Fallback: jika `galleryImageUrls` kosong, coba ambil dari `wedding.galleries` (GalleryItem objects).
- **Status**: fix sudah diterapkan di local (`paket_nikah`). **Belum di-deploy ke production** `paketpernikahan.co.id`. Gallery tidak akan tampil di app sampai controller di-deploy.
- **File yang diubah**:
  - `app/Http/Controllers/Api/V1/RealWeddingController.php` (backend)
  - `PaketPernikahan/Views/RealWeddingDetailView.swift` (iOS)

### Struktur Backend yang Benar

- Backend lokal ada di: `/Applications/MAMP/htdocs/xcode/paketpernikahan.info_swift` (BUKAN `/Applications/MAMP/htdocs/paketpernikahan.info`)
- Database: `paket_nikah` (port 8889)
- App URL lokal: `http://127.0.0.1:8000`
- iOS Info.plist saat ini: semua URL → `https://paketpernikahan.co.id/api/v1` (production)

---

## Customer Section — Perbaikan & Fitur Baru (2026-06-25)

### Bug Fix: "Gagal membaca respons server" saat Dashboard Load

- **Penyebab**: `CustomerPreparationSectionsData` punya field `general: [CustomerPreparationGeneralSection]` non-optional, tapi backend sudah tidak mengembalikan key `general`.
- **Fix** di `CustomerModels.swift`: hapus field `general` dan seluruh struct `CustomerPreparationGeneralSection`.

### Bug Fix: "Gagal memuat dashboard" saat Pull-to-Refresh

- **Penyebab**: `fetchCustomerStats` dan `fetchPreparationSections` di `CustomerHomeView` selalu set `errorMessage` saat error, meski data lama sudah ada.
- **Fix**: tambah guard `if customerStats == nil` / `if preparationSections == nil` sebelum assign `errorMessage`.

### Bug Fix: `sectionId` pada addTask (CustomerPreparationView)

- **Penyebab**: body POST task masih kirim `sectionId` (sudah deprecated), bukan `weddingEventId`.
- **Fix** di dua tempat: `CustomerPreparationView.addTask` dan `CustomerPreparationDetailView.addTask` — ganti ke `weddingEventId: section.backendId`.

### Fitur: "Segera Hadir" Modal di Tab Pembayaran

- `CustomerPaymentView` menampilkan overlay gelap + modal card saat dibuka.
- Tombol "Oke, Mengerti" hanya tampil untuk role `super_admin`.
- State `showComingSoon = true` di-reset setiap `.onAppear`.

### Perubahan Layout CustomerHomeView

- Urutan card: header → weddingHeroCard → **preparationProgressCard** → **agendaCard** → preparationReportCard → budgetCard.
- `budgetCard` ditambah badge "Segera Hadir" (capsule pill).
- `summaryGrid` (LazyVGrid 2-kolom) dihapus.

### Fitur: CustomerFamilyMembersSheet — Update UX seperti Daftar Tamu VIP

- **File**: `CustomerProfileView.swift` — struct `CustomerFamilyMembersSheet` (baris ~2305)
- **Perubahan**:
  - Swipe kiri (leading) → buka `FamilyMemberFormSheet` untuk edit.
  - Swipe kanan (trailing) → konfirmasi alert hapus satu anggota (`memberToDelete`).
  - Toolbar leading: tombol Import XLSX (`fileImporter` dengan `allowedContentTypes: [.spreadsheet]`).
  - Toolbar trailing: tombol `+`, menu `ellipsis.circle` (Hapus Semua, muncul jika list tidak kosong), tombol `Selesai`.
  - Alert konfirmasi hapus semua (`showDeleteAllAlert`).
  - API upload: `POST customer/family-members/import` (multipart XLSX).
  - API hapus semua: `DELETE customer/family-members`.
  - `deleteMember` sekarang optimistic (hapus dulu dari array, insert balik jika error).
  - `addMember` sekarang `insert(at: 0)` bukan `append`.
  - Edit sheet tidak lagi menerima `onDelete` callback — delete eksklusif via swipe dari list.
  - `EmptyRequestBody` dihapus (tidak lagi digunakan).
  - Tambah `.scrollContentBackground(.hidden)` + `.background(Theme.screenBackground)`.
  - Row `familyMemberRow` dihapus chevron, nomor telepon ditampilkan dengan ikon `phone.fill`.
- **Build**: sukses tanpa error.

### Fix Route DELETE `/customer/family-members` Tidak Ditemukan

- **Penyebab**: route `DELETE /api/v1/customer/family-members` belum ada di `routes/api.php` — hanya ada `DELETE /family-members/{id}`.
- **Fix backend** di `app/Http/Controllers/Api/V1/CustomerController.php`: tambah method `destroyAllFamilyMembers` (hapus semua FamilyMember milik user saat ini).
- **Fix routes**: tambah `Route::delete('/family-members', [CustomerController::class, 'destroyAllFamilyMembers'])` **sebelum** route `DELETE /family-members/{id}` agar wildcard `{id}` tidak menangkap request tanpa ID.

### Fix Google Sign-In — "Verifikasi Token Google Gagal" di TestFlight

- **Penyebab**: production `.env` tidak punya `GOOGLE_IOS_CLIENT_ID`. Token iOS punya `aud` = iOS Client ID; backend Laravel (`AuthController::google`) validasi via `Google_Client::setClientId` + `verifyIdToken`, tapi tanpa `GOOGLE_IOS_CLIENT_ID` di `allowedAudiences`, verifikasi gagal.
- **Fix**: tambah `GOOGLE_IOS_CLIENT_ID=1073847707519-nl2v15m0qh0ep66kkldm92tcuoug3faj.apps.googleusercontent.com` di production `.env` Hostinger, lalu `php artisan config:clear && php artisan cache:clear`.
- **Status**: sudah berjalan di TestFlight per 2026-06-25.

### Fix Default Role User Baru — `pengunjung`, Bukan `customer`

- **Masalah**: semua user baru (register email, Google, Apple) langsung mendapat role `customer`, padahal seharusnya `pengunjung`.
- **Aturan bisnis**: role `customer` hanya diberikan manual oleh `super_admin` dari backend (saat booking disetujui atau akses customer dashboard diaktifkan).
- **Fix** di `app/Http/Controllers/Api/V1/AuthController.php` — method `assignDefaultRole`:
  ```php
  // Sebelum (SALAH):
  Role::findOrCreate('customer', 'web');
  $user->assignRole('customer');
  
  // Sesudah (BENAR):
  Role::findOrCreate('pengunjung', 'web');
  $user->assignRole('pengunjung');
  ```
- Method ini dipanggil di 3 flow: register email (line 36), Apple Sign-In (line 116), Google Sign-In (line 173).
- **Dampak**: user baru tidak otomatis dapat akses Customer Dashboard — harus di-assign manual oleh super_admin.
