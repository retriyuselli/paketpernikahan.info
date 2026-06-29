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
| `wedding_infos` | `WeddingInfo` | 1 per user, nama mempelai + budaya/adat + songlist |
| `wedding_events` | `WeddingEvent` | Lamaran/Pengajian/Akad/Resepsi |
| `family_members` | `FamilyMember` | Anggota keluarga mempelai |
| `vip_guests` | `VipGuest` | Tamu VIP terpisah dari keluarga |
| `vip_guest_delegates` | `VipGuestDelegate` | Token akses berbagi daftar VIP ke orang lain |
| `customer_payment_methods` | `CustomerPaymentMethod` | Bank/e-wallet customer |
| `customer_notifications` | `CustomerNotification` | Notifikasi in-app |
| `customer_preparation_tasks` | `CustomerPreparationTask` | Task checklist — terikat `wedding_event_id` (per event) |
| `label_persiapans` | `LabelPersiapan` | Master label/kategori tugas per jenis acara |
| `preparation_task_templates` | `PreparationTaskTemplate` | Master katalog tugas bawaan per label |
| `wedding_budgets` | `WeddingBudget` | Total anggaran + currency pernikahan per user (1 baris per user) |
| `wedding_payment_schedules` | `WeddingPaymentSchedule` | Semua tagihan vendor (pending & lunas) |
| `wedding_payment_schedule_templates` | `WeddingPaymentScheduleTemplate` | Template tagihan otomatis per jenis acara |

### Schema `wedding_infos`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `user_id` | FK → users, unique | 1 user 1 info pernikahan |
| `groom_name` | string(100), nullable | Nama pengantin pria |
| `bride_name` | string(100), nullable | Nama pengantin wanita |
| `budaya` | string(100), nullable | Budaya/adat pernikahan, contoh: `Palembang`, `Jawa`, `Minang` |
| `songlist` | json, nullable | Array string judul lagu. Di iOS decode sebagai `[String]` |

### Schema `wedding_budgets`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `user_id` | FK → users, unique | 1 user 1 budget |
| `total_budget` | decimal(15,2) | Total anggaran yang ditetapkan user |
| `currency` | string(3) | ISO currency code. Default `IDR` |
| `notes` | text, nullable | |

### Schema `wedding_payment_schedule_templates`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `jenis_acara` | enum | `lamaran` · `pengajian` · `akad` · `resepsi` |
| `title` | string | Judul tagihan default |
| `vendor_name` | string | Nama vendor default |
| `category` | enum | `venue` · `catering` · `decoration` · `photo_video` · `entertainment` · `makeup` · `transport` · `wo` · `other` |
| `amount` | decimal(15,2) | Nominal estimasi default |
| `due_days_before_event` | smallint | Jatuh tempo H-berapa dari `tgl_acara` |
| `notes` | text, nullable | Catatan default |
| `sort_order` | smallint | Urutan schedule |
| `is_active` | boolean | Template aktif/nonaktif |

> Saat user membuat `WeddingEvent`, backend otomatis membaca template berdasarkan `jenis_acara` dan membuat `WeddingPaymentSchedule`. Proses ini idempotent: event yang sama tidak akan membuat tagihan template yang sama dua kali.

### Schema `wedding_payment_schedules`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `user_id` | FK → users | |
| `wedding_event_id` | FK → wedding_events, nullable | Event asal jika dibuat otomatis dari template |
| `source_template_id` | FK → wedding_payment_schedule_templates, nullable | Template asal jika dibuat otomatis |
| `title` | string | Contoh: "Pelunasan Venue", "DP Catering" |
| `vendor_name` | string | Nama vendor |
| `category` | enum | `venue` · `catering` · `decoration` · `photo_video` · `entertainment` · `makeup` · `transport` · `wo` · `other` |
| `amount` | decimal(15,2) | Nominal tagihan |
| `due_date` | date | Jatuh tempo |
| `status` | enum | `pending` · `paid` · `overdue` |
| `paid_at` | timestamp, nullable | |
| `customer_payment_method_id` | FK → customer_payment_methods, nullable, nullOnDelete | Metode pembayaran (relasi ke `CustomerPaymentMethod`) |
| `proof_url` | string, nullable | URL foto bukti transfer |
| `notes` | text, nullable | |

> **Implementasi:** Model dan migration payment sudah dibuat di Laravel. Kolom `payment_method` (string) telah diganti dengan FK `customer_payment_method_id` sejak migration `2026_06_29_000001`.

---

## Panduan Membuat Model Pembayaran (Laravel)

### Langkah 1 — Migration

```bash
php artisan make:migration create_wedding_budgets_table
php artisan make:migration create_wedding_payment_schedules_table
```

#### `create_wedding_budgets_table`

```php
Schema::create('wedding_budgets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
    $table->decimal('total_budget', 15, 2)->default(0);
    $table->string('currency', 3)->default('IDR');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

#### `create_wedding_payment_schedules_table`

```php
Schema::create('wedding_payment_schedules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->string('vendor_name');
    $table->enum('category', [
        'venue', 'catering', 'decoration', 'photo_video',
        'entertainment', 'makeup', 'transport', 'wo', 'other'
    ])->default('other');
    $table->decimal('amount', 15, 2);
    $table->date('due_date');
    $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
    $table->timestamp('paid_at')->nullable();
    $table->foreignId('customer_payment_method_id')
        ->nullable()->constrained('customer_payment_methods')->nullOnDelete();
    $table->string('proof_url')->nullable();
    $table->text('notes')->nullable();
    $table->unsignedSmallInteger('sort_order')->default(0);
    $table->timestamps();
});
```

```bash
php artisan migrate
```

---

### Langkah 2 — Model

#### `app/Models/WeddingBudget.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingBudget extends Model
{
    protected $fillable = ['user_id', 'total_budget', 'currency', 'notes'];

    protected $casts = [
        'total_budget' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

#### `app/Models/WeddingPaymentSchedule.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingPaymentSchedule extends Model
{
    protected $fillable = [
        'user_id', 'wedding_event_id', 'source_template_id',
        'title', 'vendor_name', 'category',
        'amount', 'due_date', 'status', 'paid_at',
        'customer_payment_method_id', 'proof_url', 'notes', 'sort_order',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'due_date' => 'date',
        'paid_at'  => 'datetime',
    ];

    protected static function booted(): void
    {
        static::retrieved(function (self $schedule) {
            if ($schedule->status === 'pending' && $schedule->due_date?->isPast()) {
                $schedule->updateQuietly(['status' => 'overdue']);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(CustomerPaymentMethod::class, 'customer_payment_method_id');
    }

    public function getCategoryLabelAttribute(): string { ... }
    public function getCategoryIconAttribute(): string { ... }
}
}
```

#### `app/Models/WeddingPaymentScheduleTemplate.php`

Template schedule disimpan di `wedding_payment_schedule_templates`. Field penting: `jenis_acara`, `title`, `vendor_name`, `category`, `amount`, `due_days_before_event`, `sort_order`, `is_active`.

#### Tambahkan relasi di `User.php`

```php
// app/Models/User.php
public function weddingBudget(): HasOne
{
    return $this->hasOne(WeddingBudget::class);
}

public function paymentSchedules(): HasMany
{
    return $this->hasMany(WeddingPaymentSchedule::class)->orderBy('due_date');
}
```

---

### Langkah 3 — Controller Methods

Implementasi aktif saat ini ada di `CustomerController.php`:

```php
// GET /customer/payment/summary
public function paymentSummary(Request $request): JsonResponse
{
    $user      = $request->user();
    $budget    = $user->weddingBudget;
    $schedules = $user->paymentSchedules;

    $totalBudget = $budget?->total_budget ?? 0;
    $paid        = $schedules->where('status', 'paid')->sum('amount');
    $remaining   = $schedules->whereIn('status', ['pending', 'overdue'])->sum('amount');
    $paidPct     = $totalBudget > 0 ? round(($paid / $totalBudget) * 100, 1) : 0;

    return response()->json([
        'data' => [
            'total_budget'      => $totalBudget,
            'currency'          => $budget?->currency ?? 'IDR',
            'total_paid'        => $paid,
            'total_remaining'   => $remaining,
            'paid_percentage'   => $paidPct,
        ]
    ]);
}

// GET /customer/payment/schedules?status=pending
public function paymentSchedules(Request $request): JsonResponse
{
    $query = $request->user()->paymentSchedules();

    if ($request->status) {
        $query->where('status', $request->status);
    }

    $items = $query->with('paymentMethod')->get()->map(fn ($s) => [
        'id'             => $s->id,
        'title'          => $s->title,
        'vendor_name'    => $s->vendor_name,
        'category'       => $s->category,
        'category_label' => $s->category_label,
        'category_icon'  => $s->category_icon,
        'amount'         => $s->amount,
        'due_date'       => $s->due_date?->format('Y-m-d'),
        'status'         => $s->status,
        'paid_at'        => $s->paid_at?->toISOString(),
        'payment_method' => $s->paymentMethod ? [
            'id'             => $s->paymentMethod->id,
            'name'           => $s->paymentMethod->name,
            'type'           => $s->paymentMethod->type,
            'logo_icon'      => $s->paymentMethod->logo_icon,
            'account_number' => $s->paymentMethod->account_number,
            'account_name'   => $s->paymentMethod->account_name,
        ] : null,
        'proof_url'      => $s->proof_url,
        'notes'          => $s->notes,
    ]);

    return response()->json(['data' => $items]);
}

// GET /customer/payment/transactions  (alias untuk status=paid)
public function paymentTransactions(Request $request): JsonResponse
{
    $items = $request->user()->paymentSchedules()
        ->where('status', 'paid')
        ->orderByDesc('paid_at')
        ->get()
        ->with('paymentMethod')
        ->map(fn ($s) => [
            'id'             => $s->id,
            'title'          => $s->title,
            'vendor_name'    => $s->vendor_name,
            'category_icon'  => $s->category_icon,
            'amount'         => $s->amount,
            'paid_at'        => $s->paid_at?->format('Y-m-d'),
            'payment_method' => $s->paymentMethod ? [
                'id'             => $s->paymentMethod->id,
                'name'           => $s->paymentMethod->name,
                'type'           => $s->paymentMethod->type,
                'logo_icon'      => $s->paymentMethod->logo_icon,
                'account_number' => $s->paymentMethod->account_number,
                'account_name'   => $s->paymentMethod->account_name,
            ] : null,
        ]);

    return response()->json(['data' => $items]);
}

// POST /customer/payment/schedules
public function storePaymentSchedule(Request $request): JsonResponse
{
    $data = $request->validate([
        'title'          => 'required|string|max:200',
        'vendor_name'    => 'required|string|max:200',
        'category'       => 'required|in:venue,catering,decoration,photo_video,entertainment,makeup,transport,wo,other',
        'amount'         => 'required|numeric|min:0',
        'due_date'       => 'required|date',
        'notes'          => 'nullable|string',
    ]);

    $schedule = $request->user()->paymentSchedules()->create($data);

    return response()->json(['data' => $schedule, 'message' => 'Tagihan berhasil ditambahkan.'], 201);
}

// PUT /customer/payment/schedules/{id}
public function updatePaymentSchedule(Request $request, int $id): JsonResponse
{
    $schedule = $request->user()->paymentSchedules()->findOrFail($id);

    $data = $request->validate([
        'title'          => 'sometimes|string|max:200',
        'vendor_name'    => 'sometimes|string|max:200',
        'category'       => 'sometimes|in:venue,catering,decoration,photo_video,entertainment,makeup,transport,wo,other',
        'amount'         => 'sometimes|numeric|min:0',
        'due_date'       => 'sometimes|date',
        'status'                     => 'sometimes|in:pending,paid,overdue',
        'customer_payment_method_id' => 'nullable|exists:customer_payment_methods,id',
        'notes'                      => 'nullable|string',
    ]);

    if (isset($data['status']) && $data['status'] === 'paid' && !$schedule->paid_at) {
        $data['paid_at'] = now();
    }

    $schedule->update($data);

    return response()->json(['data' => $schedule, 'message' => 'Tagihan berhasil diperbarui.']);
}

// DELETE /customer/payment/schedules/{id}
public function destroyPaymentSchedule(Request $request, int $id): JsonResponse
{
    $request->user()->paymentSchedules()->findOrFail($id)->delete();
    return response()->json(['message' => 'Tagihan berhasil dihapus.']);
}

// GET /customer/budget
public function getBudget(Request $request): JsonResponse
{
    $budget = $request->user()->weddingBudget;
    return response()->json([
        'data' => [
            'total_budget' => $budget?->total_budget ?? 0,
            'currency'     => $budget?->currency ?? 'IDR',
            'notes'        => $budget?->notes,
        ],
    ]);
}

// PUT /customer/budget
public function updateBudget(Request $request): JsonResponse
{
    $data = $request->validate([
        'total_budget' => 'required|numeric|min:0',
        'currency'     => 'sometimes|nullable|string|size:3',
        'notes'        => 'nullable|string',
    ]);

    if (! empty($data['currency'])) {
        $data['currency'] = strtoupper($data['currency']);
    }

    $budget = $request->user()->weddingBudget()->updateOrCreate(['user_id' => $request->user()->id], $data);
    return response()->json(['data' => $budget, 'message' => 'Budget berhasil disimpan.']);
}
```

---

### Langkah 4 — Routes (`api.php`)

Tambahkan di dalam grup `auth:sanctum`:

```php
Route::prefix('customer/payment')->group(function () {
    Route::get('/summary',        [CustomerController::class, 'paymentSummary']);
    Route::get('/schedules',      [CustomerController::class, 'paymentSchedules']);
    Route::get('/transactions',   [CustomerController::class, 'paymentTransactions']);
    Route::post('/schedules',     [CustomerController::class, 'storePaymentSchedule']);
    Route::put('/schedules/{id}', [CustomerController::class, 'updatePaymentSchedule']);
    Route::delete('/schedules/{id}', [CustomerController::class, 'destroyPaymentSchedule']);
});

Route::prefix('customer/budget')->group(function () {
    Route::get('/',  [CustomerController::class, 'budget']);
    Route::put('/',  [CustomerController::class, 'updateBudget']);
});
```

---

### Langkah 5 — Filament Resource Admin

```bash
php artisan make:filament-resource WeddingPaymentSchedule --generate
```

Sudah dibuat manual di `app/Filament/Admin/Resources/WeddingPaymentSchedules`. Letaknya di grup navigasi `Customer`, nav sort `7`. Kolom tabel: User · Judul · Vendor · Kategori · Nominal · Jatuh Tempo · Status. Field `proof_url` memakai `FileUpload` disk `public`, folder `wedding-payment-proofs`.

Template schedule juga sudah dibuat manual di `app/Filament/Admin/Resources/WeddingPaymentScheduleTemplates`. Letaknya di grup navigasi `Customer`, nav sort `6`. Admin bisa mengubah template per jenis acara tanpa mengubah kode.

---

### Auto Generate Payment Schedule dari Event

File utama:

| File | Fungsi |
|------|--------|
| `app/Observers/WeddingEventObserver.php` | Dipanggil saat `WeddingEvent` dibuat |
| `app/Services/WeddingPaymentScheduleTemplateService.php` | Generate schedule dari template aktif |
| `database/seeders/WeddingPaymentScheduleTemplateSeeder.php` | Isi template default per jenis acara |

Alur:

```
POST /api/v1/customer/wedding-events
  → WeddingEvent dibuat
  → WeddingEventObserver::created()
  → WeddingPaymentScheduleTemplateService::createSchedulesForEvent()
  → Ambil template aktif berdasarkan jenis_acara
  → Buat WeddingPaymentSchedule dengan:
      wedding_event_id = event.id
      source_template_id = template.id
      due_date = event.tgl_acara - due_days_before_event
```

Jika `tgl_acara` belum diisi, due date fallback ke `now() + due_days_before_event`.

Seeder:

```bash
php artisan db:seed --class=WeddingPaymentScheduleTemplateSeeder
php artisan db:seed --class=WeddingPaymentSeeder
```

`WeddingPaymentSeeder` juga akan backfill schedule dari template untuk event yang sudah ada.

---

### Contoh Response JSON

#### `GET /customer/payment/summary`
```json
{
  "data": {
    "total_budget":    150000000,
    "currency":        "IDR",
    "total_paid":      95000000,
    "total_remaining": 55000000,
    "paid_percentage": 63.3
  }
}
```

#### `GET /customer/payment/schedules?status=pending`
```json
{
  "data": [
    {
      "id": 1,
      "wedding_event_id": 4,
      "source_template_id": 10,
      "title": "Pelunasan Venue",
      "vendor_name": "Gedung Serbaguna",
      "category": "venue",
      "category_label": "Venue",
      "category_icon": "building.2",
      "amount": "25000000.00",
      "due_date": "2025-05-30",
      "status": "pending",
      "paid_at": null,
      "payment_method": null,
      "notes": null
    }
  ]
}
```

> `payment_method` → `null` jika belum diisi, atau object `CustomerPaymentMethod`:
> ```json
> "payment_method": {
>   "id": 1,
>   "name": "BCA",
>   "type": "bank_transfer",
>   "logo_icon": "building.columns",
>   "account_number": "1234567890",
>   "account_name": "Budi Santoso"
> }
> ```

#### `GET /customer/payment/transactions`
```json
{
  "data": [
    {
      "id": 5,
      "title": "Booking Venue",
      "vendor_name": "Gedung Serbaguna",
      "category_icon": "building.2",
      "amount": "20000000.00",
      "paid_at": "2025-04-20",
      "payment_method": {
        "id": 1,
        "name": "BCA",
        "type": "bank_transfer",
        "logo_icon": "building.columns",
        "account_number": "1234567890",
        "account_name": "Budi Santoso"
      }
    }
  ]
}
```

#### `GET /customer/budget`
```json
{
  "data": {
    "total_budget": 150000000,
    "currency": "IDR",
    "notes": "Budget awal pernikahan."
  }
}
```

**Body update budget (PUT):**
```json
{
  "total_budget": 150000000,
  "currency": "IDR",
  "notes": "Budget awal pernikahan."
}
```

### Xcode — Model Swift Budget & Payment Summary

```swift
struct CustomerBudgetResponse: Codable {
    let data: CustomerBudget
}

struct CustomerBudget: Codable {
    let totalBudget: Double
    let currency: String
    let notes: String?
}

struct UpdateCustomerBudgetRequest: Encodable {
    let totalBudget: Double
    let currency: String?
    let notes: String?
}

struct CustomerPaymentSummaryResponse: Codable {
    let data: CustomerPaymentSummary
}

struct CustomerPaymentSummary: Codable {
    let totalBudget: Double
    let currency: String
    let totalPaid: Double
    let totalRemaining: Double
    let paidPercentage: Double
}
```

Gunakan formatter currency di iOS berdasarkan `currency` dari API:

```swift
func formatMoney(_ amount: Double, currency: String) -> String {
    let formatter = NumberFormatter()
    formatter.numberStyle = .currency
    formatter.currencyCode = currency
    formatter.maximumFractionDigits = 0
    return formatter.string(from: NSNumber(value: amount)) ?? "\(currency) \(amount)"
}
```

### Xcode — Model Swift Payment Schedule & CustomerPaymentMethod

Tambahkan/update di `CustomerModels.swift`:

```swift
// Metode pembayaran customer (dari /customer/payment-methods)
struct CustomerPaymentMethod: Codable, Identifiable {
    let id: Int
    let name: String
    let type: String?           // "bank_transfer" | "e_wallet" | dll
    let logoIcon: String?       // SF Symbol name
    let accountNumber: String?
    let accountName: String?
    let isPrimary: Bool
}

struct CustomerPaymentMethodsResponse: Codable {
    let data: [CustomerPaymentMethod]
}

// Payment schedule (dari /customer/payment/schedules)
struct WeddingPaymentScheduleItem: Codable, Identifiable {
    let id: Int
    let weddingEventId: Int?
    let sourceTemplateId: Int?
    let title: String
    let vendorName: String
    let category: String
    let categoryLabel: String
    let categoryIcon: String
    let amount: Double
    let dueDate: String?        // "yyyy-MM-dd"
    let status: String          // "pending" | "paid" | "overdue"
    let paidAt: String?         // ISO 8601
    let paymentMethod: CustomerPaymentMethod?  // null jika belum diisi
    let proofUrl: String?
    let notes: String?
}

struct WeddingPaymentSchedulesResponse: Codable {
    let data: [WeddingPaymentScheduleItem]
}

// Untuk tandai lunas — kirim customer_payment_method_id (bukan string)
struct UpdatePaymentScheduleRequest: Encodable {
    var title: String?
    var vendorName: String?
    var category: String?
    var amount: Double?
    var dueDate: String?
    var status: String?                   // "pending" | "paid" | "overdue"
    var customerPaymentMethodId: Int?     // ID dari CustomerPaymentMethod
    var proofUrl: String?
    var notes: String?
}
```

> **Penting untuk iOS:** Saat tandai tagihan sebagai **lunas**, kirim `customer_payment_method_id` (Int) — bukan string `payment_method`. Ambil ID dari list `/customer/payment-methods` yang sudah dipilih user. `payment_method` di response sekarang berupa **object** (atau `null`), bukan string.

---

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
| `no` | unsigned smallint, nullable | Nomor urut tampil/export. Data iOS diurutkan berdasarkan `no`, lalu `id`. |
| `kategori` | enum | `vip` · `keluarga_besar` · `pejabat` · `tokoh_masyarakat` · `rekan_bisnis` · `teman` |
| `rsvp_status` | enum | `menunggu` · `hadir` · `tidak_hadir` |
| `rsvp_updated_by_name` | string, nullable | Nama user yang terakhir mengubah RSVP (pemilik atau delegate) |
| `rsvp_updated_at` | timestamp, nullable | Waktu perubahan RSVP terakhir |

> **Migration:** `2026_06_24_000001_add_rsvp_updated_by_to_vip_guests_table.php` tambah tracking RSVP; `2026_06_26_000001_add_no_to_vip_guests_table.php` tambah nomor urut; `2026_06_26_000002_add_vip_to_kategori_enum_vip_guests.php` tambah kategori `vip`.

### FamilyMember — Kolom & Nilai Valid

| Kolom | Tipe | Nilai Valid / Keterangan |
|-------|------|--------------------------|
| `no` | unsigned smallint, nullable | Nomor urut tampil/export. Data iOS diurutkan berdasarkan `no`, lalu `id`. |
| `name` | string | Nama anggota keluarga |
| `role` | string, nullable | Peran, contoh: Ayah Pengantin Pria |
| `phone` | string, nullable | Nomor telepon |
| `rsvp_status` | enum | `menunggu` · `hadir` · `tidak_hadir` |
| `rsvp_updated_by_name` | string, nullable | Nama user yang terakhir mengubah RSVP |
| `rsvp_updated_at` | timestamp, nullable | Waktu perubahan RSVP terakhir |

> **Migration:** `2026_06_26_000003_add_no_to_family_members_table.php` tambah nomor urut; `2026_06_26_000004_backfill_no_for_guest_lists.php` mengisi nomor data lama; `2026_06_27_000001_add_rsvp_columns_to_family_members_table.php` tambah field RSVP.

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
| Empty state tamu VIP: icon `person.2.slash` + judul + subtitle + tombol CTA | ✅ |
| Empty state keluarga: icon `person.3.slash` + judul + subtitle + tombol CTA | ✅ |
| Stats chip icon-based (tanpa Divider): `person.2.fill`, `checkmark.circle.fill`, `xmark.circle.fill`, `clock.fill` | ✅ |
| Stats chip: Total warna `.blue`, angka 0 = `.secondary` (redup) | ✅ |
| Stats chip: angka `.title2 .bold`, lebih besar & jelas | ✅ |
| Attendance button fixed width 72px (`fixedSize`) — posisi tidak bergeser saat status berubah | ✅ |
| Row alignment: `HStack(alignment: .center)` di `vipGuestRow` dan `familyMemberGuestRow` | ✅ |
| Tombol "Tambah Tamu VIP" di list disembunyikan saat empty (sudah ada di empty state) | ✅ |

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
| GET | `/api/v1/customer/wedding-info` | Nama pengantin, budget/currency, budaya, songlist, events |
| PUT | `/api/v1/customer/wedding-info` | Update nama pengantin, budget/currency, budaya, songlist |
| POST | `/api/v1/customer/wedding-events` | Tambah acara |
| PUT | `/api/v1/customer/wedding-events/{id}` | Edit acara |
| DELETE | `/api/v1/customer/wedding-events/{id}` | Hapus acara |
| GET | `/api/v1/customer/family-members` | List keluarga |
| POST | `/api/v1/customer/family-members` | Tambah anggota |
| PUT | `/api/v1/customer/family-members/{id}` | Edit anggota |
| DELETE | `/api/v1/customer/family-members` | **Hapus semua anggota keluarga** (permanen, tidak bisa dikembalikan) |
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
| GET | `/api/v1/customer/budget` | Budget pernikahan user (`total_budget`, `currency`, `notes`) |
| PUT | `/api/v1/customer/budget` | Simpan/update budget pernikahan user (`total_budget`, `currency`, `notes`) |
| GET | `/api/v1/customer/payment/summary` | Ringkasan budget: total, terbayar, sisa, persentase |
| GET | `/api/v1/customer/payment/schedules` | List jadwal pembayaran. Filter opsional: `?status=pending|paid|overdue` |
| GET | `/api/v1/customer/payment/transactions` | Riwayat transaksi lunas (`status=paid`) |
| POST | `/api/v1/customer/payment/schedules` | Tambah jadwal pembayaran |
| PUT | `/api/v1/customer/payment/schedules/{id}` | Edit jadwal pembayaran |
| DELETE | `/api/v1/customer/payment/schedules/{id}` | Hapus jadwal pembayaran |
| GET | `/api/v1/customer/payment/incoming` | List uang masuk. Filter opsional: `?status=pending|confirmed|rejected` |
| POST | `/api/v1/customer/payment/incoming` | Tambah catatan uang masuk |
| PUT | `/api/v1/customer/payment/incoming/{id}` | Edit catatan uang masuk |
| DELETE | `/api/v1/customer/payment/incoming/{id}` | Hapus catatan uang masuk |
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

## Status Keamanan API iOS

### Pembayaran & Budget — Aman untuk iOS

Endpoint payment terbaru sudah aman untuk dipakai iOS:

| Area | Status | Catatan |
|------|--------|---------|
| Auth | ✅ | Semua endpoint berada di grup `auth:sanctum` |
| Ownership data | ✅ | Query memakai relasi user login: `$request->user()->paymentSchedules()` dan `$request->user()->weddingBudget()` |
| Mutasi | ✅ | `POST/PUT/DELETE` berada di throttle `30 request/menit` |
| Validasi input | ✅ | Status, kategori, amount, due date, dan string length divalidasi |
| Akses silang user | ✅ | Update/delete schedule memakai `paymentSchedules()->findOrFail($id)`, sehingga ID milik user lain tidak bisa disentuh |
| Response iOS | ✅ | `currency`, `category_label`, `category_icon`, `due_date`, `paid_at`, `status`, `payment_method` (object/null), dan `notes` sudah diserialisasi stabil |

Endpoint aktif untuk iOS:

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/api/v1/customer/payment/summary` | Ringkasan pembayaran |
| GET | `/api/v1/customer/payment/schedules` | Jadwal pembayaran |
| GET | `/api/v1/customer/payment/transactions` | Riwayat pembayaran lunas |
| POST | `/api/v1/customer/payment/schedules` | Tambah tagihan |
| PUT | `/api/v1/customer/payment/schedules/{id}` | Update tagihan / tandai lunas |
| DELETE | `/api/v1/customer/payment/schedules/{id}` | Hapus tagihan |
| GET | `/api/v1/customer/budget` | Ambil budget (`total_budget`, `currency`, `notes`) |
| PUT | `/api/v1/customer/budget` | Simpan budget (`total_budget`, `currency`, `notes`) |

> Endpoint lama `/customer/payments/upcoming`, `/customer/payments/schedule`, dan `/customer/payments/all` tetap ada untuk kompatibilitas, tetapi iOS baru disarankan memakai endpoint `/customer/payment/*`.

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
      "id": 1, "no": 1, "name": "Budi Santoso", "jabatan": "Walikota",
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
        "id": 1, "no": 1, "name": "Budi Santoso", "jabatan": "Walikota",
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
    {
      "id": 1,
      "no": 1,
      "name": "Budi Santoso",
      "role": "Ayah Pengantin Pria",
      "phone": "08123456789",
      "rsvp_status": "hadir",
      "rsvp_label": "Hadir",
      "rsvp_updated_by_name": "Rama Dhona Utama",
      "rsvp_updated_at": "2026-06-27T10:30:00+07:00"
    },
    {
      "id": 2,
      "no": 2,
      "name": "Siti Rahayu",
      "role": "Ibu Pengantin Wanita",
      "phone": "08987654321",
      "rsvp_status": "menunggu",
      "rsvp_label": "Menunggu",
      "rsvp_updated_by_name": null,
      "rsvp_updated_at": null
    }
  ]
}
```

> Kolom: `id`, `no`, `name`, `role` (nullable), `phone` (nullable), `rsvp_status`, `rsvp_label`, `rsvp_updated_by_name`, `rsvp_updated_at`. Urutan response: `no` ASC, lalu `id` ASC.

**Body tambah anggota:**
```json
{ "no": 1, "name": "Budi Santoso", "role": "Ayah Pengantin Pria", "phone": "08123456789", "rsvp_status": "menunggu" }
```

**Body update anggota:**
```json
{ "rsvp_status": "hadir" }
{ "no": 2, "name": "Siti Rahayu", "role": "Ibu Pengantin Wanita", "phone": "08987654321" }
```

> Saat `rsvp_status` dikirim lewat update, backend otomatis mengisi `rsvp_updated_by_name` dengan nama user login dan `rsvp_updated_at` dengan waktu update.

### `POST /customer/family-members/import` *(iOS upload XLSX)*

Request: `multipart/form-data`

| Field | Tipe | Keterangan |
|-------|------|------------|
| `file` | File (XLSX) | Max 2 MB. Kolom: `no`, `nama`, `peran`, `telepon`, `rsvp` |
| `replace_all` | boolean (opsional) | `true` → hapus semua data lama sebelum import. Default `false` |

Response sukses:
```json
{
  "data": { "imported": 5, "skipped": 0 },
  "message": "Berhasil mengimpor 5 anggota keluarga."
}
```

> Template XLSX dapat diunduh dari halaman admin Filament atau disediakan secara statis di dalam app iOS.

---

### `GET /customer/wedding-info`
```json
{
  "data": {
    "groom_name": "Dimas Ardiansyah",
    "bride_name": "Putri Maharani",
    "wedding_date": "2026-07-27",
    "venue": "Gedung Graha Sriwijaya, Palembang",
    "package_name": null,
    "budget": 150000000,
    "currency": "IDR",
    "budaya": "Palembang",
    "songlist": ["Akad", "Teman Hidup", "Perfect"],
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

**Body update wedding info (PUT):**
```json
{
  "groom_name": "Dimas Ardiansyah",
  "bride_name": "Putri Maharani",
  "budget": 150000000,
  "currency": "IDR",
  "budaya": "Palembang",
  "songlist": ["Akad", "Teman Hidup", "Perfect"]
}
```

> Semua field memakai rule `sometimes`, jadi iOS boleh mengirim sebagian field saja. `songlist` harus array string; kirim `[]` untuk mengosongkan daftar lagu. `currency` wajib 3 karakter jika dikirim dan backend akan menyimpan uppercase.

### Xcode — Model Swift Wedding Info

Tambahkan/update model ini di `CustomerModels.swift`. Pastikan `JSONDecoder.keyDecodingStrategy = .convertFromSnakeCase` dipakai di API client.

```swift
struct CustomerWeddingInfoResponse: Codable {
    let data: CustomerWeddingInfo
}

struct CustomerWeddingInfo: Codable {
    let groomName: String?
    let brideName: String?
    let weddingDate: String?     // "yyyy-MM-dd"
    let venue: String?
    let packageName: String?
    let budget: Double
    let currency: String
    let budaya: String?
    let songlist: [String]
    let events: [CustomerWeddingEvent]
}

struct CustomerWeddingEvent: Codable, Identifiable {
    let id: Int
    let jenisAcara: String       // "lamaran" | "pengajian" | "akad" | "resepsi"
    let label: String
    let tglAcara: String?        // "yyyy-MM-dd"
    let lokasiAcara: String?
    let catatan: String?
}

struct UpdateCustomerWeddingInfoRequest: Encodable {
    let groomName: String?
    let brideName: String?
    let budget: Double?
    let currency: String?
    let budaya: String?
    let songlist: [String]?
}
```

Jika API client **tidak** memakai `.convertFromSnakeCase`, pakai `CodingKeys` eksplisit di masing-masing model:

```swift
struct CustomerWeddingInfo: Codable {
    let groomName: String?
    let brideName: String?
    let weddingDate: String?
    let venue: String?
    let packageName: String?
    let budget: Double
    let currency: String
    let budaya: String?
    let songlist: [String]
    let events: [CustomerWeddingEvent]

    enum CodingKeys: String, CodingKey {
        case groomName = "groom_name"
        case brideName = "bride_name"
        case weddingDate = "wedding_date"
        case venue
        case packageName = "package_name"
        case budget
        case currency
        case budaya
        case songlist
        case events
    }
}

struct CustomerWeddingEvent: Codable, Identifiable {
    let id: Int
    let jenisAcara: String
    let label: String
    let tglAcara: String?
    let lokasiAcara: String?
    let catatan: String?

    enum CodingKeys: String, CodingKey {
        case id
        case jenisAcara = "jenis_acara"
        case label
        case tglAcara = "tgl_acara"
        case lokasiAcara = "lokasi_acara"
        case catatan
    }
}
```

Untuk update partial, buat encoder dengan `.convertToSnakeCase` atau kirim dictionary JSON dengan key backend: `groom_name`, `bride_name`, `budget`, `currency`, `budaya`, `songlist`.

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
| `WeddingInfoResource` | Customer | 1 | Form nama pengantin, budaya/adat, songlist, section budget; Tab 1: **WeddingEventsRelationManager** · Tab 2: **PreparationTasksRelationManager** |
| `WeddingBudgetResource` | Customer | 2 | CRUD total budget, currency, catatan per customer |
| `FamilyMemberResource` | Customer | 2 | Import XLSX + Export XLSX + Download Template |
| `VipGuestResource` | Customer | 3 | Import XLSX + Export XLSX + Download Template |
| `VipGuestDelegateResource` | Customer | 4 | List, buat, edit, cabut token delegasi |
| `PreparationTaskTemplateResource` | Customer | 5 | Master katalog 255 tugas — label dipilih dari `LabelPersiapan` |
| `LabelPersiapanResource` | Customer | 6 | Master 98 label/kategori per jenis acara — CRUD |
| `CustomerPaymentMethodResource` | Customer | — | — |
| `CustomerNotificationResource` | Customer | — | — |
| `WeddingIncomingPaymentResource` | Customer | 8 | CRUD uang masuk + action Konfirmasi/Tolak |

#### WeddingInfoResource

File utama:

| File | Fungsi |
|------|--------|
| `app/Filament/Admin/Resources/WeddingInfos/Schemas/WeddingInfoForm.php` | Field customer, nama mempelai, budaya/adat, songlist, budget |
| `app/Filament/Admin/Resources/WeddingInfos/Tables/WeddingInfosTable.php` | Kolom customer, nama mempelai, budaya/adat, budget, songlist, acara |

Field form:
- `groom_name`, `bride_name`
- `budaya`
- `songlist` via `TagsInput`, disimpan sebagai JSON array
- Section `Budget` memakai relasi `WeddingInfo::budget()` ke `WeddingBudget`: `total_budget`, `currency`, `notes`

#### WeddingBudgetResource

File: `app/Filament/Admin/Resources/WeddingBudgets/WeddingBudgetResource.php`

- Grup navigasi: `Customer`
- Nav sort: `2`
- Field form: Customer, Total Budget, Currency (`IDR`, `USD`, `SGD`, `MYR`), Catatan
- Filter tabel: Customer dan Currency
- Policy: `app/Policies/WeddingBudgetPolicy.php` dengan permission Shield `ViewAny:WeddingBudget`, `Create:WeddingBudget`, `Update:WeddingBudget`, dst.

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

- Kolom yang dibaca: `no`, `nama`, `peran`, `telepon`, `rsvp`
- `no` kosong → backend otomatis mengisi nomor berikutnya
- `rsvp` tidak dikenal → default `menunggu`
- Baris kosong (nama kosong) → dilewati
- File dihapus otomatis setelah import
- Tombol **"Unduh Template"** → download `template-anggota-keluarga.xlsx`
- Tombol **"Export XLSX"** → pilih customer, download `anggota-keluarga.xlsx`
- Kolom export: `no`, `nama`, `peran`, `telepon`, `rsvp`, `rsvp_updated_by_name`, `rsvp_updated_at`

---

### Import XLSX Tamu VIP

- Kolom yang dibaca: `no`, `nama`, `jabatan`, `instansi`, `telepon`, `kategori`, `rsvp`, `catatan`
- `no` kosong → backend otomatis mengisi nomor berikutnya
- Nilai tidak dikenal → default: `kategori=teman`, `rsvp=menunggu`
- File dihapus otomatis setelah import
- Tombol **"Unduh Template"** → download `template-tamu-vip.xlsx`
- Tombol **"Export XLSX"** → pilih customer, download `tamu-vip.xlsx`
- Kolom export: `no`, `nama`, `jabatan`, `instansi`, `telepon`, `kategori`, `rsvp`, `catatan`

---

---

## Uang Masuk (Sumber Dana Pernikahan)

> **Status iOS:** Hardcode (fileprivate struct di `CustomerPaymentView.swift`). Backend model + API sudah dibuat; iOS tinggal pindahkan model ke `CustomerModels.swift` dan connect endpoint `/customer/payment/incoming`.

### Konsep

"Uang Masuk" adalah catatan dana yang dikumpulkan customer untuk membayar vendor pernikahan. Sumbernya bisa dari uang pribadi, orang tua, simpanan bersama, hadiah keluarga, bonus kerja, penjualan aset, pinjaman keluarga, dan sumber lain. Berbeda dengan `WeddingPaymentSchedule` yang mencatat *tagihan ke vendor*, tabel ini mencatat *pool dana yang tersedia* untuk membayar tagihan tersebut.

Alur:
```
Customer mengumpulkan dana dari pribadi / orang tua / simpanan / sumber lain
  → Customer / admin input sumber dana di app
  → Admin konfirmasi (status: pending → confirmed)
  → Tampil di iOS tab Pembayaran → kartu "Uang Masuk"
  → Dana dapat dipakai sebagai acuan pembayaran ke vendor
```

---

### Schema `wedding_incoming_payments`

> **Implementasi backend:** migration `2026_06_28_000002_create_wedding_incoming_payments_table.php`, model `App\Models\WeddingIncomingPayment`, relasi `User::incomingPayments()`, dan endpoint customer sudah aktif.

```bash
php artisan make:migration create_wedding_incoming_payments_table
```

```php
Schema::create('wedding_incoming_payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('bank_name');                          // Legacy key: nama sumber dana, contoh "Tabungan Pribadi"
    $table->decimal('amount', 15, 2);                     // Nominal dana masuk
    $table->date('transfer_date');                        // Legacy key: tanggal dana masuk
    $table->string('sender_name');                        // Legacy key: kontributor/pemberi dana
    $table->string('description')->nullable();            // Alokasi/keterangan, contoh "DP Venue & Dekorasi"
    $table->string('reference_number')->nullable();       // Referensi/catatan bukti dana
    $table->string('proof_url')->nullable();              // URL foto bukti dana masuk
    $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
    $table->timestamp('confirmed_at')->nullable();        // Waktu dikonfirmasi
    $table->string('confirmed_by')->nullable();           // Nama admin yang konfirmasi
    $table->string('rejection_reason')->nullable();       // Alasan ditolak jika rejected
    $table->text('notes')->nullable();                    // Catatan internal
    $table->timestamps();
});
```

```bash
php artisan migrate
```

---

### Seeder

File: `database/seeders/WeddingIncomingPaymentSeeder.php`

Seeder membuat contoh uang masuk untuk user demo dan semua user role `customer` / `pengunjung`:
- 7 pembayaran `confirmed`
- 4 pembayaran `pending`
- 1 pembayaran `rejected`
- reference number stabil dengan format `WIP-{user_id}-{sequence}` agar aman dijalankan ulang tanpa duplikasi
- contoh sumber dana: `Tabungan Pribadi`, `Orang Tua Mempelai Wanita`, `Simpanan Bersama`, `Hadiah Keluarga`, `Bonus Kerja`, `Penjualan Aset`

Seeder sudah dipanggil dari `DatabaseSeeder` setelah `WeddingPaymentSeeder`.

```bash
php artisan db:seed --class=WeddingIncomingPaymentSeeder
```

---

### Model `app/Models/WeddingIncomingPayment.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingIncomingPayment extends Model
{
    protected $fillable = [
        'user_id', 'bank_name', 'amount', 'transfer_date',
        'sender_name', 'description', 'reference_number',
        'proof_url', 'status', 'confirmed_at', 'confirmed_by',
        'rejection_reason', 'notes',
    ];

    protected $casts = [
        'amount'        => 'decimal:2',
        'transfer_date' => 'date',
        'confirmed_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'Dikonfirmasi',
            'rejected'  => 'Ditolak',
            default     => 'Menunggu',
        };
    }
}
```

#### Tambahkan relasi di `User.php`

```php
public function incomingPayments(): HasMany
{
    return $this->hasMany(WeddingIncomingPayment::class)->orderByDesc('transfer_date');
}
```

---

### Controller Methods (`CustomerController.php`)

```php
// GET /customer/payment/incoming
public function incomingPayments(Request $request): JsonResponse
{
    $status = $request->query('status'); // opsional: pending | confirmed | rejected

    $query = $request->user()->incomingPayments();
    if ($status) {
        $query->where('status', $status);
    }

    $items = $query->get()->map(fn ($p) => [
        'id'               => $p->id,
        'source_name'      => $p->bank_name,
        'bank_name'        => $p->bank_name,
        'amount'           => $p->amount,
        'received_date'    => $p->transfer_date?->format('Y-m-d'),
        'transfer_date'    => $p->transfer_date?->format('Y-m-d'),
        'contributor_name' => $p->sender_name,
        'sender_name'      => $p->sender_name,
        'description'      => $p->description,
        'reference_number' => $p->reference_number,
        'proof_url'        => $p->proof_url,
        'status'           => $p->status,
        'status_label'     => $p->status_label,
        'confirmed_at'     => $p->confirmed_at?->format('Y-m-d'),
        'confirmed_by'     => $p->confirmed_by,
        'rejection_reason' => $p->rejection_reason,
        'notes'            => $p->notes,
    ]);

    return response()->json(['data' => $items]);
}

// POST /customer/payment/incoming
public function storeIncomingPayment(Request $request): JsonResponse
{
    $data = $request->validate([
        'source_name'      => 'required_without:bank_name|string|max:100',
        'bank_name'        => 'required_without:source_name|string|max:100',
        'amount'           => 'required|numeric|min:1',
        'received_date'    => 'required_without:transfer_date|date',
        'transfer_date'    => 'required_without:received_date|date',
        'contributor_name' => 'required_without:sender_name|string|max:200',
        'sender_name'      => 'required_without:contributor_name|string|max:200',
        'description'      => 'nullable|string|max:300',
        'reference_number' => 'nullable|string|max:100',
        'proof_url'        => 'nullable|string|max:255',
        'notes'            => 'nullable|string',
    ]);

    $data = $this->normalizeIncomingPaymentData($data);
    $payment = $request->user()->incomingPayments()->create($data);

    return response()->json([
        'data'    => $this->serializeIncomingPayment($payment),
        'message' => 'Dana masuk berhasil dicatat.',
    ], 201);
}

// PUT /customer/payment/incoming/{id}
public function updateIncomingPayment(Request $request, int $id): JsonResponse
{
    $payment = $request->user()->incomingPayments()->findOrFail($id);

    $data = $request->validate([
        'source_name'      => 'sometimes|string|max:100',
        'bank_name'        => 'sometimes|string|max:100',
        'amount'           => 'sometimes|numeric|min:1',
        'received_date'    => 'sometimes|date',
        'transfer_date'    => 'sometimes|date',
        'contributor_name' => 'sometimes|string|max:200',
        'sender_name'      => 'sometimes|string|max:200',
        'description'      => 'nullable|string|max:300',
        'reference_number' => 'nullable|string|max:100',
        'proof_url'        => 'nullable|string|max:255',
        'notes'            => 'nullable|string',
    ]);

    $data = $this->normalizeIncomingPaymentData($data);
    $payment->update($data);

    return response()->json([
        'data' => $this->serializeIncomingPayment($payment),
        'message' => 'Data berhasil diperbarui.',
    ]);
}

// DELETE /customer/payment/incoming/{id}
public function destroyIncomingPayment(Request $request, int $id): JsonResponse
{
    $request->user()->incomingPayments()->findOrFail($id)->delete();
    return response()->json(['message' => 'Data berhasil dihapus.']);
}
```

> **Konfirmasi dilakukan admin** (bukan customer) — tambahkan method `confirmIncomingPayment` di AdminController atau lewat Filament resource.

---

### Routes (`api.php`)

```php
Route::prefix('customer/payment/incoming')->group(function () {
    Route::get('/',       [CustomerController::class, 'incomingPayments']);
    Route::post('/',      [CustomerController::class, 'storeIncomingPayment']);
    Route::put('/{id}',   [CustomerController::class, 'updateIncomingPayment']);
    Route::delete('/{id}',[CustomerController::class, 'destroyIncomingPayment']);
});
```

---

### Filament Resource Admin

> **Implementasi backend:** resource sudah dibuat manual di `app/Filament/Admin/Resources/WeddingIncomingPayments`.

- **Grup navigasi:** `Customer` · **Nav sort:** `8`
- **Kolom tabel:** User · Sumber Dana · Nominal · Tanggal Masuk · Kontributor · Alokasi · Status (badge)
- **Form admin:** field sumber dana + upload `proof_url` via `FileUpload` disk `public`, folder `wedding-incoming-payment-proofs`; field status (Select: pending/confirmed/rejected), `confirmed_by`, `confirmed_at`, `rejection_reason`
- **Action:** tombol "Konfirmasi" → set `status=confirmed`, `confirmed_at=now()`, `confirmed_by=nama admin`
- **Action:** tombol "Tolak" → isi alasan, set `status=rejected`, `rejection_reason=alasan`
- **Policy:** `app/Policies/WeddingIncomingPaymentPolicy.php` memakai permission Shield `ViewAny:WeddingIncomingPayment`, `Create:WeddingIncomingPayment`, `Update:WeddingIncomingPayment`, dst.

---

### Contoh Response JSON

#### `GET /customer/payment/incoming`
```json
{
  "data": [
    {
      "id": 1,
      "source_name": "Tabungan Pribadi",
      "bank_name": "Tabungan Pribadi",
      "amount": "50000000.00",
      "received_date": "2026-01-15",
      "transfer_date": "2026-01-15",
      "contributor_name": "Ahmad Ramadhan",
      "sender_name": "Ahmad Ramadhan",
      "description": "Modal awal budget pernikahan untuk DP venue dan dekorasi",
      "reference_number": "WIP-0001-001",
      "proof_url": null,
      "status": "confirmed",
      "status_label": "Dikonfirmasi",
      "confirmed_at": "2026-01-16",
      "confirmed_by": "Admin Paket Pernikahan",
      "rejection_reason": null,
      "notes": null
    },
    {
      "id": 3,
      "source_name": "Orang Tua Mempelai Wanita",
      "bank_name": "Orang Tua Mempelai Wanita",
      "amount": "15000000.00",
      "received_date": "2026-03-01",
      "transfer_date": "2026-03-01",
      "contributor_name": "Ayah dan Ibu Mempelai Wanita",
      "sender_name": "Ayah dan Ibu Mempelai Wanita",
      "description": "Tambahan dana keluarga untuk DP fotografer dan video",
      "reference_number": null,
      "proof_url": null,
      "status": "pending",
      "status_label": "Menunggu",
      "confirmed_at": null,
      "confirmed_by": null,
      "rejection_reason": null,
      "notes": null
    }
  ]
}
```

---

### iOS — Model Swift (perlu diupdate saat connect API)

Saat ini `IncomingPaymentItem` masih hardcode (`fileprivate struct`) di `CustomerPaymentView.swift`. Saat siap connect API, pindahkan ke `CustomerModels.swift` dan implementasikan `Decodable`:

```swift
// CustomerModels.swift

typealias IncomingPaymentsResponse = APIEnvelope<[IncomingPaymentItem]>

struct IncomingPaymentItem: Identifiable, Decodable {
    let id: Int
    let sourceName: String
    let amount: Double
    let receivedDate: String?      // "yyyy-MM-dd"
    let contributorName: String
    let description: String?
    let referenceNumber: String?
    let proofUrl: String?
    let status: String             // "pending" | "confirmed" | "rejected"
    let statusLabel: String?
    let confirmedAt: String?
    let confirmedBy: String?
    let rejectionReason: String?

    var sourceInitial: String { String(sourceName.prefix(1)).uppercased() }
    var statusColor: Color {
        switch status {
        case "confirmed": return .green
        case "rejected":  return .red
        default:          return .orange
        }
    }
    var formattedReceivedDate: String { formatShortDate(receivedDate) }

    enum CodingKeys: String, CodingKey {
        case id, sourceName, bankName, amount, receivedDate, transferDate
        case contributorName, senderName, description
        case referenceNumber, proofUrl, status, statusLabel
        case confirmedAt, confirmedBy, rejectionReason
    }

    init(from decoder: Decoder) throws {
        let c = try decoder.container(keyedBy: CodingKeys.self)
        id              = (try? c.decode(Int.self, forKey: .id)) ?? 0
        sourceName      = (try? c.decode(String.self, forKey: .sourceName))
            ?? (try? c.decode(String.self, forKey: .bankName))
            ?? ""
        amount          = flexDecimal(c, .amount)
        receivedDate    = (try? c.decodeIfPresent(String.self, forKey: .receivedDate))
            ?? (try? c.decodeIfPresent(String.self, forKey: .transferDate))
        contributorName = (try? c.decode(String.self, forKey: .contributorName))
            ?? (try? c.decode(String.self, forKey: .senderName))
            ?? ""
        description     = try? c.decodeIfPresent(String.self, forKey: .description)
        referenceNumber = try? c.decodeIfPresent(String.self, forKey: .referenceNumber)
        proofUrl        = try? c.decodeIfPresent(String.self, forKey: .proofUrl)
        status          = (try? c.decode(String.self, forKey: .status)) ?? "pending"
        statusLabel     = try? c.decodeIfPresent(String.self, forKey: .statusLabel)
        confirmedAt     = try? c.decodeIfPresent(String.self, forKey: .confirmedAt)
        confirmedBy     = try? c.decodeIfPresent(String.self, forKey: .confirmedBy)
        rejectionReason = try? c.decodeIfPresent(String.self, forKey: .rejectionReason)
    }
}

struct StoreIncomingPaymentRequest: Encodable {
    let sourceName: String
    let amount: Double
    let receivedDate: String      // "yyyy-MM-dd"
    let contributorName: String
    let description: String?
    let referenceNumber: String?
    let proofUrl: String?
    let notes: String?
}

struct UpdateIncomingPaymentRequest: Encodable {
    let sourceName: String?
    let amount: Double?
    let receivedDate: String?
    let contributorName: String?
    let description: String?
    let referenceNumber: String?
    let proofUrl: String?
    let notes: String?
}
```

#### Cara load data di `CustomerPaymentView` setelah connect API

```swift
// Ganti incomingPayments dari let hardcode → @State
@State private var incomingPayments: [IncomingPaymentItem] = []

// Tambahkan ke loadData()
async let incomingReq: APIEnvelope<[IncomingPaymentItem]> = APIClient.shared.get("customer/payment/incoming")
incomingPayments = (try await incomingReq).data
```

---

## Status Integrasi API

| Halaman / Fitur | Backend | iOS |
|---|---|---|
| Auth (login, register, Apple, Google) | ✅ | ✅ |
| Profil dasar (`/me`) | ✅ | ✅ |
| Wedding Info & Events | ✅ | ✅ |
| Wedding Info – Budget, Currency, Budaya, Songlist | ✅ | ✅ siap dipakai Xcode |
| Anggota Keluarga (CRUD) | ✅ | ✅ |
| Anggota Keluarga – Nomor urut `no` | ✅ | ✅ |
| Anggota Keluarga – RSVP + tracking update | ✅ | ✅ |
| Anggota Keluarga – Import XLSX (iOS upload) | ✅ | ✅ |
| Anggota Keluarga – Export XLSX (admin) | ✅ | — |
| Anggota Keluarga – Hapus semua (`DELETE /family-members`) | ✅ | ✅ |
| Tamu VIP (CRUD + summary RSVP) | ✅ | ✅ |
| Tamu VIP – Nomor urut `no` | ✅ | ✅ |
| Tamu VIP – Export XLSX (admin) | ✅ | — |
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
| Pembayaran legacy (`/payments/upcoming`, `/payments/schedule`, `/payments/all`) | ✅ | ✅ |
| Pembayaran baru (`/payment/summary`, `/payment/schedules`, `/payment/transactions`) | ✅ | ✅ |
| Uang Masuk (`/payment/incoming`) | ✅ | ⏳ hardcode, siap connect API |
| Budget (`GET/PUT /budget`, termasuk `currency`) | ✅ | ✅ |
| Metode Pembayaran | ✅ | ✅ |
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

1. `GET /api/v1/customer/wedding-info` → Profil header (nama pengantin, hari H, list events) + planning fields (`budget`, `currency`, `budaya`, `songlist`)
2. `GET /api/v1/customer/dashboard` → Beranda (stats persiapan + countdown)
3. `GET /api/v1/customer/preparation/sections` → Tab Persiapan — render `data.events[]` per acara
4. `GET /api/v1/customer/payment/summary` + `/payment/schedules` + `/payment/transactions` → Tab Pembayaran
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

## UI Changelog – Customer Section

### 2026-06-27 — Session Improvements

#### CustomerPreparationView.swift
- Category tabs: ganti `customerGlassIconBackground` → `.background(RoundedRectangle.fill(...))` agar tidak ada background abu-abu pada unselected
- Filter chips (main view + detail view): ganti ke `.background(Capsule().fill(...))` — selected = pink, unselected = `.clear`
- Section card: tambah `.clipShape(RoundedRectangle(cornerRadius: 20))` sebelum `customerGlassCard` agar konten ter-clip sesuai corner
- Bell button header: disamakan dengan `CustomerHomeView` (ZStack + `liquidGlassCapsule` + pink notification dot)
- Detail view summary card: hapus `Text("Progress Persiapan")`, `CircularProgressView` 76→60, padding top dikurangi
- Filter chip padding: `.horizontal: 16→12`, `.vertical: 9→8`
- Task row: `.fixedSize(horizontal: false, vertical: true)` → `.lineLimit(2)`
- `detailSummaryCard` top padding: `top: 14` → `top: 0`

#### CustomerHomeView.swift
- Semua "Lihat Detail" → "View"
- Hero card: ganti 1 tanggal wedding → ForEach event (max 4), fallback ke 1 tanggal jika events kosong
- Progress bar: gradient `[customerAccent.opacity(0.65), customerAccent]`, height 10→12
- `agendaLine`: tambah urgency color (merah ≤3 hari, oranye ≤14 hari, hijau lewat, biru default) + label "hari lagi"
- Tambah computed properties: `weddingDateWithDayName`, `daysUntil(_ dateString:)`, `shortDate(_ raw:)`

#### CustomerProfileView.swift – CustomerVipGuestsSheet
- `emptyGuestRow`: redesign full (icon `person.2.slash` + judul + subtitle + tombol CTA pink)
- `emptyFamilyMemberRow`: redesign full (icon `person.3.slash` + judul + subtitle + tombol CTA)
- Tombol "Tambah Tamu VIP" di list: wrap `if !guests.isEmpty {}` — tidak tampil saat empty
- `rsvpSummaryRow` + `familyAttendanceSummaryRow`: hapus `Divider`, `HStack(spacing: 4)`, tambah icon per chip
- `summaryChip` + `familySummaryChip`: tambah `icon: String` param, icon di atas angka, angka `.title2 .bold`, `activeColor` logic (0 = `.secondary`)
- Total chip color: `.primary` → `.blue` di kedua tabs
- `attendanceButton` + `familyAttendanceButton`: fixed width 72px + `.fixedSize(horizontal: true, vertical: true)`
- `vipGuestRow` + `familyMemberGuestRow`: `HStack(alignment: .top)` → `HStack(alignment: .center)`

---

## App Store / TestFlight — Catatan Release

| Versi | Build | Tanggal | Catatan |
|-------|-------|---------|---------|
| 2.0.2 | 6 | — | Versi sebelumnya (live di App Store) |
| 2.0.3 | 7 | 2026-06-25 | Fitur Anggota Keluarga: XLSX upload + swipe edit/hapus + hapus semua. Hapus key `NSLocalNetworkUsageDescription` & `NSAllowsLocalNetworking` dari `Info.plist`. Fix Google Sign-In production (`GOOGLE_IOS_CLIENT_ID`). |
