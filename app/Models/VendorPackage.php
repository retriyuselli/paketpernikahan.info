<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPackage extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();
    }

    protected $fillable = [
        'vendor_id', 
        'category_vendor_id',
        'name', 
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
}
