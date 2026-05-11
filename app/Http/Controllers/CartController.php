<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Rental;
use App\Models\RentalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', ['items' => [], 'start' => null, 'end' => null]);

        if (empty($cart['items'])) {
            return redirect()->route('items.index');
        }

        $start = Carbon::parse($cart['start']);
        $end   = Carbon::parse($cart['end']);
        $days  = max(1, $start->diffInDays($end));

        $items = Item::whereIn('id', array_keys($cart['items']))
                    ->with('category')
                    ->get();

        $total = $items->sum(function ($item) use ($cart, $days) {
            return $days * $item->daily_rate * ($cart['items'][$item->id] ?? 1);
        });

        return view('cart.index', compact('cart', 'items', 'days', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'start'   => 'required|date|after_or_equal:today',
            'end'     => 'required|date|after:start'
        ]);

        $cart = session('cart', ['items' => [], 'start' => null, 'end' => null]);

        $cart['start'] = $request->start;
        $cart['end']   = $request->end;
        $cart['items'][$request->item_id] =
            ($cart['items'][$request->item_id] ?? 0) + 1;

        session(['cart' => $cart]);

        return back()->with('success', '✅ Ditambahkan ke keranjang');
    }

    public function remove($id)
    {
        $cart = session('cart', ['items' => [], 'start' => null, 'end' => null]);

        if (isset($cart['items'][$id])) {
            unset($cart['items'][$id]);
            session(['cart' => $cart]);
        }

        return back()->with('success', '❌ Item dihapus dari keranjang');
    }

    public function checkout()
    {
        $cart = session('cart');

        if (!$cart || empty($cart['items'])) {
            return redirect()->route('items.index');
        }

        $start = $cart['start'];
        $end   = $cart['end'];
        $days  = max(1, Carbon::parse($start)->diffInDays(Carbon::parse($end)));

        // ✅ cek ketersediaan
        foreach ($cart['items'] as $itemId => $qty) {
            if (!$this->checkAvailability($itemId, $start, $end)) {
                return back()->with('error', '⚠️ Barang tidak tersedia');
            }
        }

        DB::beginTransaction();

        try {
            $rental = Rental::create([
                'customer_id'  => auth()->id(),
                'start_date'   => $start,
                'end_date'     => $end,
                'total_amount' => 0,
                'status'       => 'pending'
            ]);

            $total = 0;

            foreach ($cart['items'] as $itemId => $qty) {
                $item = Item::findOrFail($itemId);

                $subtotal = $days * $item->daily_rate * $qty;
                $total += $subtotal;

                RentalDetail::create([
                    'rental_id' => $rental->id,
                    'item_id'   => $itemId,
                    'quantity'  => $qty,
                    'subtotal'  => $subtotal
                ]);
            }

            $rental->update(['total_amount' => $total]);

            DB::commit();
            session()->forget('cart');

            return redirect()->route('rentals.index')
                ->with('success', '✅ Booking berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', '❌ Gagal checkout');
        }
    }

    private function checkAvailability($itemId, $start, $end)
    {
        return !RentalDetail::where('item_id', $itemId)
            ->whereHas('rental', function ($q) use ($start, $end) {
                $q->whereNotIn('status', ['cancelled', 'completed'])
                  ->where('start_date', '<=', $end)
                  ->where('end_date', '>=', $start);
            })->exists();
    }
}
