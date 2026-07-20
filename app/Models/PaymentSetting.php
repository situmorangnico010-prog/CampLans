<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Model PaymentSetting untuk mengelola konfigurasi/pengaturan pembayaran.
 * Menyimpan pengaturan berupa pasangan key-value seperti metode transfer, rekening bank,
 * batasan waktu pembayaran, serta gambar QRIS.
 */
class PaymentSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil satu setting berdasarkan key
     */
    public static function get(string $key, $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set/update nilai setting
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Ambil semua setting sebagai array asosiatif
     */
    public static function allAsArray(): array
    {
        return static::all()->pluck('value', 'key')->toArray();
    }

    /**
     * URL publik untuk file yang disimpan di disk public (qris, dll).
     * Menggunakan path relatif agar tetap benar di php artisan serve (port 8000).
     */
    public static function publicFileUrl(?string $path): ?string
    {
        if (empty($path) || !Storage::disk('public')->exists($path)) {
            return null;
        }

        return '/storage/' . ltrim($path, '/');
    }

    public static function qrisImageUrl(): ?string
    {
        return static::publicFileUrl(static::get('qris_image'));
    }
}
