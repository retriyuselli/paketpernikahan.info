<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_user_id',
        'name', 
        'slug', 
        'type', 
        'category', 
        'location', 
        'province', 
        'city', 
        'description',
        'phone', 
        'email', 
        'instagram', 
        'capacity', 
        'price_start', 
        'discount',
        'experience', 
        'venue_type', 
        'facilities', 
        'events_done',
        'likes', 
        'comments_count', 
        'rating', 
        'badge', 
        'promo',
        'cover_image', 
        'logo_vendor',
        'cover_video', 
        'is_active',
        'is_profile_complete',
    ];

    protected $casts = [
        'badge'           => 'array',
        'promo'           => 'array',
        'is_active'       => 'boolean',
        'is_profile_complete' => 'boolean',
        'rating'          => 'float',
        'discount'        => 'integer',
        'events_done'     => 'integer',
        'likes'           => 'integer',
        'comments_count'  => 'integer',
        'price_start'     => 'integer',
        'discount'        => 'integer',
        'cover_image'     => 'array',
    ];

    /** Use slug as route key: /vendor/{vendor:slug} */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function galleries()
    {
        return $this->hasMany(VendorGallery::class)->orderBy('sort_order');
    }

    public function videoGalleries()
    {
        return $this->hasMany(VendorGallery::class)->whereNotNull('video_url')->orderBy('sort_order');
    }

    public function packages()
    {
        return $this->hasMany(VendorPackage::class)->orderBy('sort_order');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function cheapestPackage()
    {
        return $this->hasOne(VendorPackage::class)
                    ->where('is_active', true)
                    ->orderBy('price_raw');
    }

    public function reviews()
    {
        return $this->hasMany(VendorReview::class);
    }

    /**
     * Get the users who have liked this vendor.
     */
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'vendor_user_likes')->withTimestamps();
    }

    public function approvedReviews()
    {
        return $this->hasMany(VendorReview::class)
                    ->where('is_approved', true)
                    ->latest('reviewed_at');
    }

    public function bookings()
    {
        return $this->hasMany(VendorBooking::class);
    }

    public function categoryVendor()
    {
        return $this->belongsTo(CategoryVendor::class, 'category', 'slug');
    }

    /** Harga setelah potongan */
    public function getFinalPriceAttribute(): int
    {
        $raw = (int) preg_replace('/[^\d]/', '', $this->price_start ?? '0');
        return max(0, $raw - ($this->discount ?? 0));
    }

    /** Mengembalikan URL gambar cover yang siap dipakai di Blade */
    public function getCoverImageUrlAttribute(): ?string
    {
        $value = $this->cover_image;
        if (!$value) return null;
        $path = is_array($value) ? ($value[0] ?? null) : $value;
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        return Storage::url($path);
    }

    public function computeProfileComplete(): bool
    {
        return $this->computeProfileProgress()['percent'] === 100;
    }

    public function computeProfileProgress(): array
    {
        $venueCategories = ['gedung', 'hotel', 'venue', 'rumah', 'wo'];
        $isVenueCategory = in_array($this->category, $venueCategories, true);

        $covers = array_values(array_filter((array) ($this->cover_image ?? [])));
        $hasCovers = count($covers) >= 5;
        $hasPackage = $this->packages()->exists();
        $hasGallery = $this->galleries()->exists();

        $required = [
            'name' => ['label' => 'Nama vendor', 'ok' => filled($this->name)],
            'category' => ['label' => 'Kategori', 'ok' => filled($this->category)],
            'location' => ['label' => 'Alamat lengkap', 'ok' => filled($this->location)],
            'province' => ['label' => 'Provinsi', 'ok' => filled($this->province)],
            'city' => ['label' => 'Kota / Kabupaten', 'ok' => filled($this->city)],
            'phone' => ['label' => 'No. WhatsApp', 'ok' => filled($this->phone)],
            'description' => ['label' => 'Deskripsi', 'ok' => filled($this->description)],
            'cover_image' => ['label' => 'Foto cover (min. 5)', 'ok' => $hasCovers],
            'packages' => ['label' => 'Paket (min. 1)', 'ok' => $hasPackage],
            'galleries' => ['label' => 'Galeri media (min. 1)', 'ok' => $hasGallery],
        ];

        if ($isVenueCategory) {
            $required['capacity'] = ['label' => 'Kapasitas', 'ok' => filled($this->capacity)];
            $required['venue_type'] = ['label' => 'Tipe venue', 'ok' => filled($this->venue_type)];
            $required['facilities'] = ['label' => 'Fasilitas', 'ok' => filled($this->facilities)];
        }

        $total = count($required);
        $done = collect($required)->filter(fn ($r) => (bool) $r['ok'])->count();
        $percent = $total > 0 ? (int) round(($done / $total) * 100) : 0;

        $missing = collect($required)
            ->filter(fn ($r) => !(bool) $r['ok'])
            ->map(fn ($r) => $r['label'])
            ->values()
            ->all();

        return [
            'percent' => $percent,
            'done' => $done,
            'total' => $total,
            'missing' => $missing,
        ];
    }

    public function computePriceStartFromPackages(): ?int
    {
        $pkg = $this->packages()
            ->orderBy('price_raw')
            ->first();
        if (!$pkg) return null;
        return max(0, (int) $pkg->price_raw - (int) ($pkg->discount ?? 0));
    }
}
