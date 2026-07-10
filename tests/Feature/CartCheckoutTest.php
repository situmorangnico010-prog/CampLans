<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Rental;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $item;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create(['name' => 'Test Category']);
        
        $this->item = Item::create([
            'category_id' => $this->category->id,
            'name'        => 'Sony Camera Test',
            'daily_rate'  => 100000,
            'stock'       => 2,
        ]);

        $this->user = User::factory()->create(['role' => 'customer']);
    }

    /**
     * Skenario 1 & 2: Menyimpan item dengan rentang tanggal berbeda ke keranjang secara independen.
     */
    public function test_can_add_items_with_different_dates_to_cart(): void
    {
        $response1 = $this->actingAs($this->user)
            ->post('/cart/add', [
                'item_id' => $this->item->id,
                'start'   => '2026-08-01',
                'end'     => '2026-08-03',
                'notes'   => 'Catatan Kamera Kesatu'
            ]);

        $response1->assertRedirect();
        $response1->assertSessionHas('success');

        $response2 = $this->actingAs($this->user)
            ->post('/cart/add', [
                'item_id' => $this->item->id,
                'start'   => '2026-08-10',
                'end'     => '2026-08-13',
                'notes'   => 'Catatan Kamera Kedua'
            ]);

        $response2->assertRedirect();
        $response2->assertSessionHas('success');

        $cart = session('cart');
        $this->assertCount(2, $cart);

        // Pastikan tanggal dan catatan masing-masing item tidak berubah/saling memengaruhi
        $itemsList = array_values($cart);
        
        $this->assertEquals('2026-08-01', $itemsList[0]['start_date']);
        $this->assertEquals('2026-08-03', $itemsList[0]['end_date']);
        $this->assertEquals('Catatan Kamera Kesatu', $itemsList[0]['notes']);
        $this->assertEquals(2, $itemsList[0]['duration']);

        $this->assertEquals('2026-08-10', $itemsList[1]['start_date']);
        $this->assertEquals('2026-08-13', $itemsList[1]['end_date']);
        $this->assertEquals('Catatan Kamera Kedua', $itemsList[1]['notes']);
        $this->assertEquals(3, $itemsList[1]['duration']);
    }

    /**
     * Skenario 3 & 4: Checkout item secara terpisah dan menyisakan item lain di keranjang.
     */
    public function test_checkout_item_separately_creates_independent_transaction(): void
    {
        // 1. Tambahkan dua item ke keranjang
        $this->actingAs($this->user)->post('/cart/add', [
            'item_id' => $this->item->id,
            'start'   => '2026-08-01',
            'end'     => '2026-08-03',
            'notes'   => 'Kamera Note'
        ]);

        $this->actingAs($this->user)->post('/cart/add', [
            'item_id' => $this->item->id,
            'start'   => '2026-08-10',
            'end'     => '2026-08-13',
            'notes'   => 'Tenda Note'
        ]);

        $cart = session('cart');
        $cartIds = array_keys($cart);
        $firstCartId = $cartIds[0];
        $secondCartId = $cartIds[1];

        // 2. Lakukan checkout untuk item pertama saja
        $checkoutResponse = $this->actingAs($this->user)
            ->post(route('cart.checkout', $firstCartId), [
                'payment_method' => 'transfer_bank'
            ]);

        $checkoutResponse->assertRedirect();
        
        // 3. Verifikasi transaksi pertama sukses dibuat
        $rental1 = Rental::first();
        $this->assertNotNull($rental1);
        $this->assertEquals('2026-08-01', $rental1->start_date->format('Y-m-d'));
        $this->assertEquals('2026-08-03', $rental1->end_date->format('Y-m-d'));
        $this->assertEquals('Kamera Note', $rental1->note);
        $this->assertEquals(200000, (float) $rental1->total_amount); // 2 hari * 100rb * 1 unit
        
        // 4. Pastikan item kedua MASIH berada di keranjang
        $updatedCart = session('cart');
        $this->assertCount(1, $updatedCart);
        $this->assertTrue(isset($updatedCart[$secondCartId]));
        $this->assertFalse(isset($updatedCart[$firstCartId]));

        // 5. Lakukan checkout untuk item kedua
        $checkoutResponse2 = $this->actingAs($this->user)
            ->post(route('cart.checkout', $secondCartId), [
                'payment_method' => 'qris'
            ]);

        $checkoutResponse2->assertRedirect();

        // 6. Verifikasi transaksi kedua sukses dibuat terpisah
        $rental2 = Rental::orderBy('id', 'desc')->first();
        $this->assertNotEquals($rental1->id, $rental2->id);
        $this->assertEquals('2026-08-10', $rental2->start_date->format('Y-m-d'));
        $this->assertEquals('2026-08-13', $rental2->end_date->format('Y-m-d'));
        $this->assertEquals('Tenda Note', $rental2->note);
        $this->assertEquals(300000, (float) $rental2->total_amount); // 3 hari * 100rb * 1 unit
        
        // 7. Pastikan keranjang sekarang kosong
        $this->assertCount(0, session('cart', []));
    }

    /**
     * Skenario 5: Status pembayaran dan transaksi masing-masing rental tetap independen.
     */
    public function test_rental_status_remains_independent(): void
    {
        $rental1 = Rental::create([
            'customer_id'        => $this->user->id,
            'start_date'         => '2026-08-01',
            'end_date'           => '2026-08-03',
            'total_amount'       => 200000,
            'status'             => 'pending',
            'transaction_status' => 'waiting_payment',
            'payment_status'     => 'unpaid',
            'payment_method'     => 'transfer_bank',
        ]);

        $rental2 = Rental::create([
            'customer_id'        => $this->user->id,
            'start_date'         => '2026-08-10',
            'end_date'           => '2026-08-13',
            'total_amount'       => 300000,
            'status'             => 'pending',
            'transaction_status' => 'waiting_payment',
            'payment_status'     => 'unpaid',
            'payment_method'     => 'qris',
        ]);

        // Simulasikan rental 1 lunas (misal setelah diverifikasi)
        $rental1->update([
            'transaction_status' => 'payment_approved',
            'payment_status'     => 'paid'
        ]);

        $rental2->refresh();
        
        // Pastikan rental 2 tidak terpengaruh
        $this->assertEquals('waiting_payment', $rental2->transaction_status);
        $this->assertEquals('unpaid', $rental2->payment_status);
    }

    /**
     * Skenario 6: Validasi stok berdasarkan rentang tanggal masing-masing.
     */
    public function test_stock_validation_respects_overlapping_dates(): void
    {
        // Stok item Sony Camera Test adalah 2
        // Kurangi stok dengan membuat transaksi aktif pada tanggal 2026-08-01 sampai 2026-08-03 sebanyak 2 unit
        $activeRental = Rental::create([
            'customer_id'        => $this->user->id,
            'start_date'         => '2026-08-01',
            'end_date'           => '2026-08-03',
            'total_amount'       => 400000,
            'status'             => 'active',
            'transaction_status' => 'on_rent',
            'payment_status'     => 'paid',
        ]);
        
        \App\Models\RentalDetail::create([
            'rental_id' => $activeRental->id,
            'item_id'   => $this->item->id,
            'quantity'  => 2,
            'subtotal'  => 400000,
        ]);

        // 1. Coba pesan pada tanggal yang bertabrakan (1-3 Agustus), harus gagal karena stok terpakai habis (sisa 0)
        $responseFail = $this->actingAs($this->user)
            ->post('/cart/add', [
                'item_id' => $this->item->id,
                'start'   => '2026-08-02',
                'end'     => '2026-08-03',
            ]);

        $responseFail->assertSessionHas('error');

        // 2. Coba pesan pada tanggal yang tidak bertabrakan (10-12 Agustus), harus sukses karena stok tersedia utuh (sisa 2)
        $responseSuccess = $this->actingAs($this->user)
            ->post('/cart/add', [
                'item_id' => $this->item->id,
                'start'   => '2026-08-10',
                'end'     => '2026-08-12',
            ]);

        $responseSuccess->assertSessionHas('success');
    }
}
