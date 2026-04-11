<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_code }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; font-size: 13px; margin: 0; padding: 0; }
        .container { padding: 40px; }
        .header { margin-bottom: 30px; border-bottom: 2px solid #f472b6; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #f472b6; text-transform: uppercase; letter-spacing: 2px; }
        .invoice-title { text-align: right; font-size: 28px; font-weight: bold; color: #999; margin-top: -35px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f9fafb; text-align: left; padding: 12px; border-bottom: 1px solid #eee; text-transform: uppercase; font-size: 11px; color: #666; }
        td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        
        .shoe-img { width: 60px; height: 60px; border-radius: 6px; object-fit: cover; border: 1px solid #eee; }
        .info-section { margin-bottom: 40px; }
        .info-box { width: 45%; float: left; }
        .info-box-right { width: 45%; float: right; text-align: right; }
        .clearfix { clear: both; }
        
        .label { color: #888; font-size: 11px; text-transform: uppercase; display: block; margin-bottom: 3px; }
        .value { font-weight: bold; font-size: 14px; }
        
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-unpaid { background: #fee2e2; color: #991b1b; }
        
        .footer { position: fixed; bottom: 40px; left: 40px; right: 40px; text-align: center; color: #aaa; font-size: 11px; border-top: 1px solid #eee; padding-top: 20px; }
        .total-row td { border-bottom: none; }
        .total-box { background: #f9fafb; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: right; }
        .grand-total { font-size: 20px; color: #f472b6; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CLEANSETZ SHOE CARE</div>
            <div class="invoice-title">INVOICE</div>
        </div>

        <div class="info-section">
            <div class="info-box">
                <span class="label">DIBAYAR OLEH:</span>
                <span class="value">{{ $order->customer_name }}</span><br>
                {{ $order->customer_phone ?? $order->customer->phone }}<br>
                {{ $order->customer->email ?? '' }}
            </div>
            <div class="info-box-right">
                <span class="label">NOMOR INVOICE:</span>
                <span class="value">#{{ $order->order_code }}</span><br><br>
                <span class="label">TANGGAL:</span>
                <span class="value">{{ $order->created_at->format('d F Y') }}</span><br><br>
                <span class="label">STATUS PEMBAYARAN:</span>
                <span class="status-badge {{ $order->payment_status == 'lunas' ? 'status-paid' : 'status-unpaid' }}">
                    {{ $order->payment_status == 'lunas' ? 'LUNAS' : 'BELUM BAYAR' }}
                </span>
            </div>
            <div class="clearfix"></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">Foto</th>
                    <th>Deskripsi Item</th>
                    <th>Material & Warna</th>
                    <th>Treatment</th>
                    <th style="text-align: right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        @if($item->photo_before && file_exists(public_path($item->photo_before)))
                            <img src="{{ public_path($item->photo_before) }}" class="shoe-img">
                        @else
                            <div style="width: 60px; height: 60px; background: #eee; border-radius: 6px; text-align: center; line-height: 60px; color: #ccc; font-size: 10px;">
                                No Image
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: bold; font-size: 14px;">{{ $item->shoe_brand }}</div>
                        <div style="color: #888; font-size: 11px;">Item ID: #{{ $item->id }}</div>
                    </td>
                    <td>{{ $item->shoe_material }} / {{ $item->shoe_color }}</td>
                    <td>{{ $item->treatment->name ?? 'Service' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-box">
            <div style="margin-bottom: 5px;">
                <span style="color: #888;">Subtotal Treatment:</span>
                <span style="font-weight: bold; margin-left: 20px;">Rp {{ number_format($order->items->sum('price'), 0, ',', '.') }}</span>
            </div>
            <div style="margin-bottom: 5px;">
                <span style="color: #888;">Biaya Logistik ({{ $order->service_method == 'pickup_delivery' ? 'Pickup & Delivery' : 'Datang ke Toko' }}):</span>
                <span style="font-weight: bold; margin-left: 20px;">Rp {{ number_format($order->pickup_fee, 0, ',', '.') }}</span>
            </div>
            <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                <span style="font-size: 16px; font-weight: bold;">TOTAL TAGIHAN:</span>
                <span class="grand-total" style="margin-left: 20px;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <div style="margin-top: 50px;">
            <span class="label">METODE PEMBAYARAN:</span>
            <span class="value">{{ $order->payment_method ?? 'Transfer Bank' }}</span>
            @if($order->payment_status == 'belum_lunas')
                <p style="color: #666; font-style: italic; font-size: 11px; margin-top: 10px;">
                    * Silakan lakukan pembayaran sebelum pengambilan sepatu.
                </p>
            @endif
        </div>

        <div class="footer">
            Terima kasih telah mempercayakan perawatan sepatu Anda kepada CLEANSETZ.<br>
            Jl. Raya Kedoya No.123, Jakarta Barat | WhatsApp: 0812-3456-7890 | Instagram: @cleansetz
        </div>
    </div>
</body>
</html>
