<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Promo;
use Illuminate\Support\Str;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class VendorPackage extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'discount', 'is_active', 'max_guests'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($package) {
            if (empty($package->slug)) {
                $package->slug = static::generateUniqueSlug($package);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function generateUniqueSlug(self $package): string
    {
        $vendor = $package->vendor ?? \App\Models\Vendor::find($package->vendor_id);
        $vendorSlug = $vendor?->slug ?: 'vendor';
        $base = $vendorSlug . '-' . Str::slug($package->name ?: 'paket');
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    protected $fillable = [
        'vendor_id',
        'category_vendor_id',
        'name',
        'slug',
        'price',
        'discount',
        'dp_paket',
        'max_guests',
        'card_color',
        'card_text_color',
        'image_path',
        'item',
        'type',
        'capacity',
        'facilities',
        'sort_order',
        'is_active',
        'discount_expires_at',
    ];

    protected $casts = [
        'image_path'          => 'array',
        'facilities'          => 'array',
        'category_vendor_id'  => 'array',
        'is_active'           => 'boolean',
        'discount_expires_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function categoryVendor()
    {
        return $this->belongsTo(CategoryVendor::class);
    }

    public function galleries()
    {
        return $this->hasMany(PaketGallery::class, 'vendor_package_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'promo_vendor_package');
    }

    public function getImageUrlAttribute(): ?string
    {
        $paths = $this->image_path;
        $path = is_array($paths) ? ($paths[0] ?? null) : $paths;
        if (!$path) return null;
        if (is_string($path) && str_starts_with($path, 'http')) return $path;
        return \Illuminate\Support\Facades\Storage::url($path);
    }

    /**
     * Parse kolom `item` (HTML RichEditor) menjadi array teks dari setiap <li>.
     *
     * @return array<string>
     */
    public function getItemsAttribute(): array
    {
        if (empty($this->item)) {
            return [];
        }

        $dom = new \DOMDocument();
        @$dom->loadHTML(
            mb_convert_encoding($this->item, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_NOERROR | LIBXML_NOWARNING
        );

        // Try <li> first
        $items = [];
        foreach ($dom->getElementsByTagName('li') as $li) {
            $text = trim($li->textContent);
            if ($text !== '') {
                $items[] = $text;
            }
        }

        // Fallback: try <p> tags
        if (empty($items)) {
            foreach ($dom->getElementsByTagName('p') as $p) {
                $text = trim($p->textContent);
                if ($text !== '') {
                    $items[] = $text;
                }
            }
        }

        // Fallback: split by <br>
        if (empty($items)) {
            $html = preg_replace('/<br\s*\/?>/i', "\n", $this->item);
            $lines = array_values(array_filter(array_map('trim', explode("\n", strip_tags($html)))));
            if (!empty($lines)) {
                $items = $lines;
            }
        }

        // Last resort: whole text as one item
        if (empty($items)) {
            $plain = trim(strip_tags($this->item));
            if ($plain !== '') {
                $items[] = $plain;
            }
        }

        return $items;
    }

    /**
     * Parse HTML item field menjadi grup: [{header, items}]
     * Header dideteksi dari <strong> atau <b> di dalam <p>.
     */
    public function getItemsGroupedAttribute(): array
    {
        if (empty($this->item)) {
            return [];
        }

        $dom = new \DOMDocument();
        @$dom->loadHTML(
            mb_convert_encoding($this->item, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_NOERROR | LIBXML_NOWARNING
        );

        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body) {
            $items = $this->items;
            return empty($items) ? [] : [['header' => null, 'items' => $items]];
        }

        $groups        = [];
        $currentHeader = null;
        $currentItems  = [];

        foreach ($body->childNodes as $node) {
            if ($node->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }

            $tag = strtolower($node->tagName);

            if ($tag === 'p') {
                // Cek apakah <p> berisi <strong> atau <b> saja (= heading)
                $hasStrong = $node->getElementsByTagName('strong')->length > 0
                          || $node->getElementsByTagName('b')->length > 0;
                $text = trim($node->textContent);

                if ($hasStrong && $text !== '') {
                    // Simpan grup sebelumnya
                    if ($currentHeader !== null || !empty($currentItems)) {
                        $groups[] = ['header' => $currentHeader, 'items' => $currentItems];
                    }
                    $currentHeader = $text;
                    $currentItems  = [];
                }
                // <p> tanpa strong — abaikan (biasanya <br> spacer)

            } elseif ($tag === 'ol' || $tag === 'ul') {
                foreach ($node->getElementsByTagName('li') as $li) {
                    $t = trim($li->textContent);
                    if ($t !== '') {
                        $currentItems[] = $t;
                    }
                }
            }
        }

        // Simpan grup terakhir
        if ($currentHeader !== null || !empty($currentItems)) {
            $groups[] = ['header' => $currentHeader, 'items' => $currentItems];
        }

        // Fallback: jika tidak ada grup terstruktur, kembalikan items flat
        if (empty($groups)) {
            $items = $this->items;
            return empty($items) ? [] : [['header' => null, 'items' => $items]];
        }

        return $groups;
    }

    public function getDescriptionAttribute(): ?string
    {
        if (empty($this->item)) {
            return null;
        }
        $plain = trim(strip_tags(preg_replace('/<br\s*\/?>/i', ' ', $this->item)));
        return $plain !== '' ? $plain : null;
    }
}
