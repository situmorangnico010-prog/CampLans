<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Model Rental untuk merepresentasikan data transaksi penyewaan.
 * Berisi informasi mengenai jadwal sewa, detail biaya, status sewa, status pembayaran,
 * bukti pembayaran, serta method helper untuk perhitungan denda, expired, dan timeline status.
 */
class Rental extends Model {
    protected $fillable = [
        'kode_sewa',
        'customer_id',
        'start_date',
        'end_date',
        'total_amount',
        'note',
        'status',
        'transaction_status',
        'actual_return_date',
        'late_fee',
        'payment_status',
        'payment_method',
        'payment_proof',
        'proof_uploaded_at',
        'payment_note',
        'verified_by',
        'verified_at',
        'returned_at',
        'expired_at',
    ];

    protected $casts = [
        'start_date'         => 'date',
        'end_date'           => 'date',
        'actual_return_date' => 'date',
        'verified_at'        => 'datetime',
        'proof_uploaded_at'  => 'datetime',
        'returned_at'        => 'datetime',
        'expired_at'         => 'datetime',
    ];

    protected $appends = [
        'booking_id',
        'order_code',
        'total_price',
        'rent_start_date',
        'rent_end_date',
    ];

    // ─── Boot: Auto-generate kode_sewa ──────────────────────────────────────
    protected static function boot() {
        parent::boot();
        static::creating(function ($rental) {
            $latest = static::latest('id')->first();
            $num = $latest ? ((int) substr($latest->kode_sewa, 3)) + 1 : 1;
            $rental->kode_sewa = 'TRX' . str_pad($num, 3, '0', STR_PAD_LEFT);
        });
    }

    // ─── Relationships ────────────────────────────────────────────────────────
    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function details() {
        return $this->hasMany(RentalDetail::class);
    }

    public function verifiedBy() {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────
    public function scopeExpired($query) {
        return $query->where('transaction_status', 'waiting_payment')
                     ->where('expired_at', '<', now());
    }

    public function scopeForCustomer($query, $customerId) {
        return $query->where('customer_id', $customerId);
    }

    public function scopeAwaitingPayment($query) {
        return $query->whereIn('transaction_status', ['waiting_payment', 'waiting_verification', 'payment_rejected']);
    }

    public function scopeOngoing($query) {
        return $query->whereIn('transaction_status', [
            'payment_approved', 'processing', 'ready_for_pickup', 'on_rent', 'returned',
        ]);
    }

    public function scopeHistory($query) {
        return $query->whereIn('transaction_status', ['completed', 'cancelled', 'expired']);
    }

    // ─── Alias Attributes (kompatibel dengan spesifikasi bisnis) ─────────────
    public function getBookingIdAttribute(): int {
        return $this->id;
    }

    public function getOrderCodeAttribute(): string {
        return $this->kode_sewa;
    }

    public function getTotalPriceAttribute(): float {
        return (float) $this->total_amount;
    }

    public function getRentStartDateAttribute() {
        return $this->start_date;
    }

    public function getRentEndDateAttribute() {
        return $this->end_date;
    }

    // ─── Computed Attributes ─────────────────────────────────────────────────
    /**
     * Label status dalam Bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string {
        return match($this->transaction_status) {
            'pending'              => 'Menunggu',
            'waiting_payment'     => 'Menunggu Pembayaran',
            'waiting_verification'=> 'Menunggu Verifikasi',
            'payment_approved'    => 'Pembayaran Diterima',
            'payment_rejected'    => 'Pembayaran Ditolak',
            'processing'          => 'Sedang Diproses',
            'ready_for_pickup'    => 'Siap Diambil',
            'on_rent'             => 'Sedang Disewa',
            'returned'            => 'Dikembalikan',
            'completed'           => 'Selesai',
            'cancelled'           => 'Dibatalkan',
            'expired'             => 'Kedaluwarsa',
            default               => ucfirst($this->transaction_status),
        };
    }

    /**
     * Warna badge Tailwind untuk setiap status
     */
    public function getStatusBadgeColorAttribute(): string {
        return match($this->transaction_status) {
            'pending'              => 'bg-gray-100 text-gray-600',
            'waiting_payment'     => 'bg-yellow-100 text-yellow-700',
            'waiting_verification'=> 'bg-blue-100 text-blue-700',
            'payment_approved'    => 'bg-teal-100 text-teal-700',
            'payment_rejected'    => 'bg-red-100 text-red-700',
            'processing'          => 'bg-purple-100 text-purple-700',
            'ready_for_pickup'    => 'bg-indigo-100 text-indigo-700',
            'on_rent'             => 'bg-green-100 text-green-700',
            'returned'            => 'bg-cyan-100 text-cyan-700',
            'completed'           => 'bg-emerald-100 text-emerald-700',
            'cancelled'           => 'bg-gray-100 text-gray-500',
            'expired'             => 'bg-orange-100 text-orange-700',
            default               => 'bg-gray-100 text-gray-600',
        };
    }

    /**
     * Apakah masih dalam batas waktu pembayaran
     */
    public function getIsExpiredAttribute(): bool {
        return $this->expired_at && $this->expired_at->isPast();
    }

    /**
     * Sisa waktu pembayaran dalam menit
     */
    public function getTimeRemainingAttribute(): ?int {
        if (!$this->expired_at) return null;
        return max(0, now()->diffInMinutes($this->expired_at, false));
    }

    /**
     * Label metode pembayaran
     */
    public function getPaymentProofUrlAttribute(): ?string
    {
        if (empty($this->payment_proof) || !Storage::disk('public')->exists($this->payment_proof)) {
            return null;
        }

        return '/storage/' . ltrim($this->payment_proof, '/');
    }

    public function getPaymentMethodLabelAttribute(): string {
        return match($this->payment_method) {
            'transfer_bank' => 'Transfer Bank',
            'qris'          => 'QRIS Manual',
            'ewallet'       => 'E-Wallet',
            default         => 'Belum dipilih',
        };
    }

    public function getPaymentStatusLabelAttribute(): string {
        return match($this->payment_status) {
            'unpaid'                => 'Belum Dibayar',
            'pending_verification'  => 'Menunggu Verifikasi',
            'paid'                  => 'Lunas',
            'rejected'              => 'Ditolak',
            default                 => ucfirst($this->payment_status ?? '-'),
        };
    }

    public function getPaymentStatusBadgeColorAttribute(): string {
        return match($this->payment_status) {
            'unpaid'               => 'bg-yellow-100 text-yellow-700',
            'pending_verification' => 'bg-blue-100 text-blue-700',
            'paid'                 => 'bg-green-100 text-green-700',
            'rejected'             => 'bg-red-100 text-red-700',
            default                => 'bg-gray-100 text-gray-600',
        };
    }

    public function getRentalDurationDaysAttribute(): int {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        return max(1, Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)));
    }

    /**
     * Langkah timeline status rental untuk UI customer/admin.
     */
    public function getTimelineStepsAttribute(): array {
        $status = $this->transaction_status;

        $flow = [
            ['key' => 'booking',       'label' => 'Booking',              'statuses' => ['pending', 'waiting_payment', 'waiting_verification', 'payment_rejected', 'payment_approved', 'processing', 'ready_for_pickup', 'on_rent', 'returned', 'completed', 'cancelled', 'expired']],
            ['key' => 'payment',       'label' => 'Pembayaran',           'statuses' => ['waiting_payment', 'waiting_verification', 'payment_rejected', 'payment_approved', 'processing', 'ready_for_pickup', 'on_rent', 'returned', 'completed']],
            ['key' => 'verification',  'label' => 'Verifikasi Admin',     'statuses' => ['waiting_verification', 'payment_approved', 'processing', 'ready_for_pickup', 'on_rent', 'returned', 'completed']],
            ['key' => 'processing',    'label' => 'Persiapan Barang',     'statuses' => ['processing', 'ready_for_pickup', 'on_rent', 'returned', 'completed']],
            ['key' => 'pickup',        'label' => 'Siap Diambil/Dikirim', 'statuses' => ['ready_for_pickup', 'on_rent', 'returned', 'completed']],
            ['key' => 'on_rent',       'label' => 'Sedang Disewa',        'statuses' => ['on_rent', 'returned', 'completed']],
            ['key' => 'return',        'label' => 'Pengembalian',         'statuses' => ['returned', 'completed']],
            ['key' => 'completed',     'label' => 'Selesai',              'statuses' => ['completed']],
        ];

        if (in_array($status, ['cancelled', 'expired'])) {
            return [
                ['key' => 'booking', 'label' => 'Booking', 'active' => false, 'description' => 'Pesanan dibuat'],
                ['key' => 'end', 'label' => $status === 'expired' ? 'Kedaluwarsa' : 'Dibatalkan', 'active' => true, 'description' => $status === 'expired' ? 'Batas waktu pembayaran habis' : 'Pesanan dibatalkan'],
            ];
        }

        $activeIndex = 0;
        foreach ($flow as $i => $step) {
            if (in_array($status, $step['statuses'])) {
                $activeIndex = $i;
            }
        }

        return collect($flow)->map(function ($step, $index) use ($activeIndex, $status) {
            $descriptions = [
                'waiting_payment'      => 'Menunggu transfer & upload bukti',
                'waiting_verification' => 'Bukti sedang diperiksa admin',
                'payment_rejected'     => 'Bukti ditolak, silakan upload ulang',
                'payment_approved'     => 'Pembayaran dikonfirmasi',
                'processing'           => 'Barang sedang disiapkan',
                'ready_for_pickup'     => 'Barang siap diambil/dikirim',
                'on_rent'              => 'Barang sedang digunakan',
                'returned'             => 'Barang telah dikembalikan',
                'completed'            => 'Transaksi selesai',
            ];

            return [
                'key'         => $step['key'],
                'label'       => $step['label'],
                'active'      => $index === $activeIndex,
                'description' => $index === $activeIndex ? ($descriptions[$status] ?? null) : null,
            ];
        })->values()->all();
    }
}