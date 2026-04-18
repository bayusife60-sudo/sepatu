<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Order::with(['customer', 'items.treatment'])->latest();

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('customer_id')) {
            $query->where('customer_id', $this->request->customer_id);
        }

        if ($this->request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $this->request->start_date);
        }

        if ($this->request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $this->request->end_date);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Tgl Order',
            'Kode Order',
            'Pelanggan',
            'No. Telp',
            'Detail Item',
            'Metode',
            'Status',
            'Total Harga',
        ];
    }

    public function map($order): array
    {
        $itemsDetail = $order->items->map(function ($item) {
            return $item->shoe_brand . ' (' . ($item->treatment->name ?? '-') . ')';
        })->implode("\n");

        return [
            $order->created_at->format('d/m/Y'),
            $order->order_code,
            $order->customer_name ?: ($order->customer->name ?? 'Guest'),
            $order->customer_phone ?: ($order->customer->phone ?? '-'),
            $itemsDetail,
            $order->service_method == 'pickup_delivery' ? 'P&D' : 'Store',
            $order->status,
            $order->total_price,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
