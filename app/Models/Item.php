<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Model untuk merepresentasikan barang/alat rental camping dan kamera.
 */
class Item extends Model {
    /**
     * Field yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_barang',
        'category_id',
        'name',
        'daily_rate',
        'image',
        'description',
        'image_url',
        'stock'
    ];
    
    protected static function boot() {
        parent::boot();
        static::creating(function ($item) {
            $latest = static::latest('id')->first();
            $num = $latest ? ((int) substr($latest->kode_barang, 3)) + 1 : 1;
            $item->kode_barang = 'BRG' . str_pad($num, 3, '0', STR_PAD_LEFT);
        });
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function rentalDetails() {
        return $this->hasMany(RentalDetail::class);
    }

    /**
     * Hitung stok yang tersedia untuk periode sewa tertentu.
     * Mengambil total stok dikurangi jumlah unit yang sedang disewa
     * oleh transaksi aktif yang tumpang tindih dengan periode yang diminta.
     *
     * @param string $start  Tanggal mulai (Y-m-d)
     * @param string $end    Tanggal selesai (Y-m-d)
     * @param int|null $excludeRentalId ID rental yang dikecualikan (untuk perpanjangan)
     * @return int Jumlah stok yang tersedia
     */
    public function getAvailableStockForPeriod(string $start, string $end, ?int $excludeRentalId = null): int
    {
        // Karena stok fisik (kolom `stock`) dikurangi saat checkout dan dikembalikan saat selesai/batal,
        // maka total inventori sebenarnya = stock saat ini + unit yang sedang aktif disewa.
        $activeRentedQty = RentalDetail::where('item_id', $this->id)
            ->whereHas('rental', function ($q) {
                $q->whereNotIn('transaction_status', ['cancelled', 'completed', 'expired']);
            })
            ->sum('quantity');

        $totalInventory = $this->stock + (int) $activeRentedQty;

        // Hitung unit yang sudah dibooking untuk periode yang tumpang tindih
        $bookedQty = RentalDetail::where('item_id', $this->id)
            ->whereHas('rental', function ($q) use ($start, $end, $excludeRentalId) {
                // Hanya transaksi aktif (bukan cancelled/completed/expired)
                $q->whereNotIn('transaction_status', ['cancelled', 'completed', 'expired']);
                // Tumpang tindih dengan periode yang diminta
                $q->where('start_date', '<=', $end)
                  ->where('end_date', '>=', $start);
                // Kecualikan rental tertentu jika ada (untuk perpanjangan)
                if ($excludeRentalId) {
                    $q->where('id', '!=', $excludeRentalId);
                }
            })
            ->sum('quantity');

        return max(0, $totalInventory - (int) $bookedQty);
    }
}