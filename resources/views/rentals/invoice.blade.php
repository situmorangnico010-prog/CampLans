{{-- 
Halaman Invoice Penyewaan (Rentals Invoice)
Menampilkan rangkuman transaksi sewa secara formal (siap cetak / print friendly).
Berisi detail barang, biaya total sewa, durasi sewa, dan status verifikasi pembayaran.
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $rental->order_code }} - CampLens</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; color: #1e293b; background: #f8fafc; padding: 2rem; }
        .invoice { max-width: 800px; margin: 0 auto; background: white; border-radius: 16px; padding: 2.5rem; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px solid #0d9488; }
        .logo { font-size: 1.5rem; font-weight: 800; color: #0d9488; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-size: 1.75rem; font-weight: 700; color: #0f766e; }
        .invoice-title p { color: #64748b; font-size: 0.875rem; margin-top: 0.25rem; }
        .meta { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem; }
        .meta h3 { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; margin-bottom: 0.5rem; }
        .meta p { font-size: 0.9rem; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        th { background: #f0fdfa; color: #0f766e; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.75rem 1rem; text-align: left; }
        td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
        .text-right { text-align: right; }
        .totals { margin-left: auto; width: 280px; }
        .totals .row { display: flex; justify-content: space-between; padding: 0.5rem 0; font-size: 0.9rem; }
        .totals .grand { font-size: 1.25rem; font-weight: 700; color: #0d9488; border-top: 2px solid #e2e8f0; padding-top: 0.75rem; margin-top: 0.5rem; }
        .status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: #ccfbf1; color: #0f766e; }
        .footer { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; text-align: center; color: #94a3b8; font-size: 0.8rem; }
        .no-print { margin-bottom: 1.5rem; text-align: center; }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; text-decoration: none; cursor: pointer; border: none; }
        .btn-primary { background: #0d9488; color: white; }
        .btn-secondary { background: #f1f5f9; color: #475569; margin-left: 0.5rem; }
        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none; }
            .invoice { box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">Cetak Invoice</button>
        <a href="{{ route('rentals.detail', $rental) }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="invoice">
        <div class="header">
            <div>
                <div class="logo">CampLens</div>
                <p style="color:#64748b; font-size:0.85rem; margin-top:0.5rem;">Sewa Kamera & Alat Camping Profesional</p>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p>{{ $rental->order_code }}</p>
                <p>{{ $rental->created_at->format('d F Y') }}</p>
            </div>
        </div>

        <div class="meta">
            <div>
                <h3>Tagihan Kepada</h3>
                <p><strong>{{ $rental->customer->name }}</strong></p>
                <p>{{ $rental->customer->email }}</p>
                <p>{{ $rental->customer->kode_user ?? '' }}</p>
            </div>
            <div>
                <h3>Detail Sewa</h3>
                <p>Periode: {{ $rental->rent_start_date->format('d M Y') }} — {{ $rental->rent_end_date->format('d M Y') }}</p>
                <p>Durasi: {{ $rental->rental_duration_days }} hari</p>
                <p>Metode: {{ $rental->payment_method_label }}</p>
                <p style="margin-top:0.5rem;"><span class="status-badge">{{ $rental->status_label }}</span></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rental->details as $detail)
                <tr>
                    <td>
                        <strong>{{ $detail->item->name }}</strong><br>
                        <span style="color:#94a3b8; font-size:0.8rem;">{{ $detail->item->category->name ?? '-' }}</span>
                    </td>
                    <td>{{ $detail->quantity }}</td>
                    <td class="text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="row">
                <span>Subtotal Sewa</span>
                <span>Rp{{ number_format($rental->total_amount - ($rental->late_fee ?? 0), 0, ',', '.') }}</span>
            </div>
            @if($rental->late_fee > 0)
            <div class="row">
                <span>Denda Keterlambatan</span>
                <span>Rp{{ number_format($rental->late_fee, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="row grand">
                <span>Total</span>
                <span>Rp{{ number_format($rental->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="row" style="margin-top:0.75rem; color:#64748b;">
                <span>Status Pembayaran</span>
                <span>{{ $rental->payment_status_label }}</span>
            </div>
        </div>

        @if($rental->verified_at)
        <div style="margin-top:1.5rem; padding:1rem; background:#f0fdfa; border-radius:8px; font-size:0.85rem;">
            Pembayaran diverifikasi pada {{ $rental->verified_at->format('d M Y, H:i') }}
            @if($rental->verifiedBy) oleh {{ $rental->verifiedBy->name }} @endif
        </div>
        @endif

        <div class="footer">
            <p>Terima kasih telah menggunakan layanan CampLens.</p>
            <p>Invoice ini dicetak secara otomatis dan sah tanpa tanda tangan.</p>
        </div>
    </div>
</body>
</html>
