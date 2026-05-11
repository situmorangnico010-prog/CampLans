<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\RentalDetail; // ✅ FIX: Import model yang hilang
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = auth()->user()->rentals()
            ->with('details.item')
            ->latest()
            ->get();

        return view('rentals.index', compact('rentals'));
    }

    public function pay(Request $request)
    {
        $request->validate(['rental_id' => 'required|exists:rentals,id']);
        
        $rental = Rental::where('id', $request->rental_id)
            ->where('customer_id', auth()->id())
            ->firstOrFail();

        $rental->update(['payment_status' => 'paid']);
        return back()->with('success', '💳 Pembayaran tercatat');
    }

    public function extend(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'new_end'   => 'required|date|after:today'
        ]);

        $rental = Rental::where('id', $request->rental_id)
            ->where('customer_id', auth()->id())
            ->where('status', 'active')
            ->firstOrFail();

        $newEnd = Carbon::parse($request->new_end);
        if ($newEnd->lte(Carbon::parse($rental->end_date))) {
            return back()->with('error', '⚠️ Tanggal baru harus setelah tanggal kembali');
        }

        // Cek konflik jadwal
        $itemIds = $rental->details->pluck('item_id');
        foreach ($itemIds as $itemId) {
            if ($this->checkConflict($itemId, $rental->end_date, $request->new_end, $rental->id)) {
                return back()->with('error', '⚠️ Barang sudah dipesan orang lain');
            }
        }

        $days  = Carbon::parse($rental->end_date)->diffInDays($newEnd);
        $extra = 0;
        foreach ($rental->details as $detail) {
            $extra += $days * $detail->item->daily_rate * $detail->quantity;
        }

        DB::beginTransaction();
        try {
            $rental->update([
                'end_date'     => $newEnd,
                'total_amount' => $rental->total_amount + $extra
            ]);
            DB::commit();
            return back()->with('success', "📅 +$days hari. Tambahan: Rp" . number_format($extra, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Gagal memperpanjang sewa');
        }
    }

    private function checkConflict($itemId, $currentEnd, $newEnd, $excludeRentalId)
    {
        return RentalDetail::where('item_id', $itemId)
            ->whereHas('rental', function ($q) use ($currentEnd, $newEnd, $excludeRentalId) {
                $q->where('id', '!=', $excludeRentalId)
                  ->whereNotIn('status', ['cancelled', 'completed'])
                  ->where('start_date', '<=', $newEnd)
                  ->where('end_date', '>=', $currentEnd);
            })->exists();
    }
}