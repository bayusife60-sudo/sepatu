<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Order - CLEANSETZ SHOE CARE</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.4; font-size: 11px; margin: 0; padding: 0; }
        .container { padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f472b6; padding-bottom: 10px; }
        .logo { font-size: 20px; font-weight: bold; color: #f472b6; text-transform: uppercase; letter-spacing: 2px; }
        .report-title { font-size: 16px; font-weight: bold; margin-top: 5px; color: #666; }
        .date-range { font-size: 10px; color: #888; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f9fafb; text-align: left; padding: 8px; border: 1px solid #ddd; text-transform: uppercase; font-size: 9px; color: #555; }
        td { padding: 8px; border: 1px solid #ddd; vertical-align: top; }
        
        .footer { position: fixed; bottom: 30px; left: 30px; right: 30px; text-align: right; color: #aaa; font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        
        .status-badge { font-size: 9px; font-weight: bold; }
        .total-summary { margin-top: 20px; float: right; width: 250px; }
        .summary-row { border-bottom: 1px solid #eee; padding: 5px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CLEANSETZ SHOE CARE</div>
            <div class="report-title">LAPORAN REKAPITULASI PESANAN</div>
            @if($dateRange)
                <div class="date-range">Periode: {{ $dateRange }}</div>
            @endif
            <div class="date-range">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 80px;">Tgl Order</th>
                    <th style="width: 90px;">Kode Order</th>
                    <th>Pelanggan</th>
                    <th>Detail Item (Qty)</th>
                    <th style="width: 70px;">Metode</th>
                    <th style="width: 80px;">Status</th>
                    <th style="width: 90px;" class="text-right">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($orders as $index => $order)
                @php $grandTotal += $order->total_price; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="fw-bold">{{ $order->order_code }}</td>
                    <td>
                        {{ $order->customer_name }}<br>
                        <span style="font-size: 8px; color: #888;">{{ $order->customer_phone }}</span>
                    </td>
                    <td>
                        @foreach($order->items as $item)
                            • {{ $item->shoe_brand }} ({{ $item->treatment->name ?? '-' }})<br>
                        @endforeach
                    </td>
                    <td>{{ $order->service_method == 'pickup_delivery' ? 'P&D' : 'Store' }}</td>
                    <td>{{ $order->status }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-summary">
            <div class="summary-row" style="border-top: 2px solid #ddd;">
                <span class="fw-bold">TOTAL PENDAPATAN:</span>
                <span class="fw-bold text-right" style="float: right; color: #f472b6; font-size: 14px;">
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </span>
            </div>
            <div style="clear: both;"></div>
            <div class="summary-row">
                <span>Total Pesanan:</span>
                <span style="float: right;">{{ $orders->count() }} Order</span>
            </div>
        </div>

        <div class="footer">
            Dokumen ini dihasilkan secara otomatis oleh sistem CLEANSETZ SHOE CARE.
        </div>
    </div>
</body>
</html>
