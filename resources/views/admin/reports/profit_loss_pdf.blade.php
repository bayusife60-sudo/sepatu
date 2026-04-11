<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan - CLEANSETZ SHOE CARE</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.4; font-size: 11px; margin: 0; padding: 0; }
        .container { padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f472b6; padding-bottom: 10px; }
        .logo { font-size: 20px; font-weight: bold; color: #f472b6; text-transform: uppercase; letter-spacing: 2px; }
        .report-title { font-size: 16px; font-weight: bold; margin-top: 5px; color: #666; }
        .date-range { font-size: 10px; color: #888; margin-top: 5px; }
        
        .summary-card { padding: 15px; margin-bottom: 30px; border: 1px solid #eee; border-radius: 8px; background: #fff; }
        .summary-title { font-size: 10px; font-weight: bold; color: #888; text-transform: uppercase; margin-bottom: 5px; }
        .summary-value { font-size: 18px; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f9fafb; text-align: left; padding: 10px; border: 1px solid #eee; text-transform: uppercase; font-size: 9px; color: #666; }
        td { padding: 10px; border: 1px solid #eee; vertical-align: middle; }
        
        .img-thumb { width: 50px; height: 50px; border-radius: 4px; object-fit: cover; border: 1px solid #ddd; }
        
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 15px; border-left: 4px solid #f472b6; padding-left: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #22c55e; }
        .text-danger { color: #ef4444; }
        .fw-bold { font-weight: bold; }
        
        .footer { position: fixed; bottom: 30px; left: 30px; right: 30px; text-align: center; color: #aaa; font-size: 9px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CLEANSETZ SHOE CARE</div>
            <div class="report-title">LAPORAN LABA RUGI</div>
            <div class="date-range">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
        </div>

        <table style="border: none; margin-bottom: 40px;">
            <tr>
                <td style="border: none; width: 33%;">
                    <div class="summary-card">
                        <div class="summary-title">Total Pemasukan</div>
                        <div class="summary-value text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                </td>
                <td style="border: none; width: 33%;">
                    <div class="summary-card">
                        <div class="summary-title">Total Pengeluaran</div>
                        <div class="summary-value text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
                    </div>
                </td>
                <td style="border: none; width: 33%;">
                    <div class="summary-card" style="background: #fdf2f8; border-color: #fbcfe8;">
                        <div class="summary-title">Laba Bersih</div>
                        <div class="summary-value" style="color: #db2777;">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Section Pemasukan --}}
        <div class="section-title">RINCIAN PEMASUKAN (ORDER LUNAS)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">Foto</th>
                    <th style="width: 80px;">Tanggal</th>
                    <th style="width: 90px;">Kode Order</th>
                    <th>Pelanggan</th>
                    <th>Detail Items</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        @php $firstItem = $order->items->first(); @endphp
                        @if($firstItem && $firstItem->photo_before && file_exists(public_path($firstItem->photo_before)))
                            <img src="{{ public_path($firstItem->photo_before) }}" class="img-thumb">
                        @else
                            <div style="width: 50px; height: 50px; background: #f3f4f6; border-radius: 4px;"></div>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="fw-bold">{{ $order->order_code }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>
                        @foreach($order->items as $item)
                        • {{ $item->shoe_brand }}<br>
                        @endforeach
                    </td>
                    <td class="text-right fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada pemasukan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Section Pengeluaran --}}
        <div class="section-title">RINCIAN PENGELUARAN OPERASIONAL</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">Bukti</th>
                    <th style="width: 80px;">Tanggal</th>
                    <th style="width: 100px;">Kategori</th>
                    <th>Deskripsi</th>
                    <th style="width: 80px;">Metode</th>
                    <th class="text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td>
                        @if($expense->proof_of_payment && file_exists(public_path($expense->proof_of_payment)))
                            <img src="{{ public_path($expense->proof_of_payment) }}" class="img-thumb">
                        @else
                            <div style="width: 50px; height: 50px; background: #f3f4f6; border-radius: 4px;"></div>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                    <td><span style="font-size: 8px; background: #eee; padding: 2px 5px; border-radius: 4px;">{{ $expense->category->name ?? 'Lainnya' }}</span></td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ $expense->payment_method }}</td>
                    <td class="text-right fw-bold text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada pengeluaran pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dicetak secara otomatis oleh Sistem Keuangan CLEANSETZ SHOE CARE pada {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
