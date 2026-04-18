<table>
    <thead>
    <tr>
        <th colspan="6" style="font-weight: bold; text-align: center; font-size: 16px;">LAPORAN LABA RUGI CLEANSETZ SHOE CARE</th>
    </tr>
    <tr>
        <th colspan="6" style="text-align: center;">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th colspan="2" style="font-weight: bold; background-color: #f3f4f6;">Total Pemasukan</th>
        <th colspan="2" style="font-weight: bold; background-color: #f3f4f6;">Total Pengeluaran</th>
        <th colspan="2" style="font-weight: bold; background-color: #f3f4f6;">Laba Bersih</th>
    </tr>
    <tr>
        <td colspan="2" style="color: #22c55e; font-weight: bold;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        <td colspan="2" style="color: #ef4444; font-weight: bold;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
        <td colspan="2" style="color: #db2777; font-weight: bold;">Rp {{ number_format($netProfit, 0, ',', '.') }}</td>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th colspan="6" style="font-weight: bold; background-color: #f472b6; color: #ffffff;">RINCIAN PEMASUKAN (ORDER LUNAS)</th>
    </tr>
    <tr>
        <th style="font-weight: bold; background-color: #f9fafb;">Tanggal</th>
        <th style="font-weight: bold; background-color: #f9fafb;">Kode Order</th>
        <th style="font-weight: bold; background-color: #f9fafb;">Pelanggan</th>
        <th style="font-weight: bold; background-color: #f9fafb;" colspan="2">Detail Items</th>
        <th style="font-weight: bold; background-color: #f9fafb; text-align: right;">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
            <td>{{ $order->order_code }}</td>
            <td>{{ $order->customer_name }}</td>
            <td colspan="2">
                @foreach($order->items as $item)
                    • {{ $item->shoe_brand }} ({{ $item->treatment->name ?? '-' }}){{ !$loop->last ? "\n" : "" }}
                @endforeach
            </td>
            <td style="text-align: right;">{{ $order->total_price }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan="5" style="text-align: right; font-weight: bold;">TOTAL PEMASUKAN:</th>
        <th style="text-align: right; font-weight: bold;">{{ $totalRevenue }}</th>
    </tr>
    </tfoot>
</table>

<table>
    <thead>
    <tr><th></th></tr>
    <tr>
        <th colspan="6" style="font-weight: bold; background-color: #f472b6; color: #ffffff;">RINCIAN PENGELUARAN OPERASIONAL</th>
    </tr>
    <tr>
        <th style="font-weight: bold; background-color: #f9fafb;">Tanggal</th>
        <th style="font-weight: bold; background-color: #f9fafb;">Kategori</th>
        <th style="font-weight: bold; background-color: #f9fafb;" colspan="3">Deskripsi</th>
        <th style="font-weight: bold; background-color: #f9fafb; text-align: right;">Nominal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($expenses as $expense)
        <tr>
            <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
            <td>{{ $expense->category->name ?? 'Lainnya' }}</td>
            <td colspan="3">{{ $expense->description }} ({{ $expense->payment_method }})</td>
            <td style="text-align: right;">{{ $expense->amount }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan="5" style="text-align: right; font-weight: bold;">TOTAL PENGELUARAN:</th>
        <th style="text-align: right; font-weight: bold;">{{ $totalExpenses }}</th>
    </tr>
    </tfoot>
</table>
