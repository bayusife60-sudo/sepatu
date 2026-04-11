@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-7">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Kelola Order</h2>
            <p class="text-muted mt-1 mb-0" style="font-weight: 300;">Manajemen semua order pelanggan Cleansetz.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary" style="padding: 0.6rem 1.5rem;">
                <i class="fas fa-plus me-2"></i>Order Baru
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="alert alert-dismissible fade show mb-4" role="alert"
         style="background-color: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #86efac; border-radius: 8px;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filter & Search --}}
    <div class="card mb-4">
        <div class="card-body p-4">
            <form method="GET" id="filterForm" action="{{ route('admin.orders.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Cari Order</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #a0a0a0;">
                            <i class="fas fa-search" style="font-size: 0.85rem;"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                               placeholder="Kode, Nama, Merek..."
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-left: none;">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Pelanggan</label>
                    <select name="customer_id" class="form-select" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                        <option value="">Semua Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Filter Status</label>
                    <select name="status" class="form-select" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                        <option value="">Semua Status</option>
                        @foreach($allStatuses as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                            {{ $st }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Rentang Tanggal</label>
                    <div class="input-group">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; font-size: 0.85rem;">
                        <span class="input-group-text bg-transparent border-white-10 text-white-50 px-2 small">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; font-size: 0.85rem;">
                    </div>
                </div>

                <div class="col-12 text-end d-flex gap-2 justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    
                    <button type="button" onclick="exportReport()" class="btn btn-outline-info px-4">
                        <i class="fas fa-file-pdf me-2"></i>Cetak Laporan
                    </button>

                    @if(request()->anyFilled(['search', 'status', 'customer_id', 'start_date', 'end_date']))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-danger px-3">
                            <i class="fas fa-times me-2"></i>Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Stats Row --}}
    @php
        $totalAll        = \App\Models\Order::count();
        $totalAntrian    = \App\Models\Order::where('status', 'Antrian')->count();
        $totalProcess    = \App\Models\Order::whereIn('status', ['Diterima Toko','Dikerjakan'])->count();
        $totalBayar      = \App\Models\Order::where('payment_status', 'belum_lunas')->whereNotNull('payment_proof')->count();
    @endphp
    <div class="row g-3 mb-4">
        @foreach([
            ['label' => 'Total Order', 'value' => $totalAll, 'color' => 'rgba(244,114,182,0.15)', 'border' => 'var(--primary)', 'icon' => 'fa-list'],
            ['label' => 'Antrian Baru', 'value' => $totalAntrian, 'color' => 'rgba(107,114,128,0.15)', 'border' => '#6b7280', 'icon' => 'fa-clock'],
            ['label' => 'Perlu Konv. Bayar', 'value' => $totalBayar, 'color' => 'rgba(56,189,248,0.15)', 'border' => '#38bdf8', 'icon' => 'fa-money-bill-wave'],
            ['label' => 'Sedang Diproses', 'value' => $totalProcess, 'color' => 'rgba(234,179,8,0.15)', 'border' => '#eab308', 'icon' => 'fa-spinner'],
        ] as $stat)
        <div class="col-6 col-md-3">
            <div class="card h-100" style="background: {{ $stat['color'] }}; border-left: 3px solid {{ $stat['border'] }};">
                <div class="card-body py-3 px-3 d-flex align-items-center gap-3">
                    <i class="fas {{ $stat['icon'] }}" style="font-size: 1.4rem; color: {{ $stat['border'] }};"></i>
                    <div>
                        <div class="text-white fw-bold" style="font-size: 1.4rem; line-height: 1;">{{ $stat['value'] }}</div>
                        <div class="text-muted small">{{ $stat['label'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabel Order --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0" style="font-size: 1rem; font-weight: 600; font-family: 'Jost', sans-serif;">
                Daftar Order
                <span class="badge ms-2 rounded-pill" style="background-color: rgba(244,114,182,0.2); color: var(--primary); font-size: 0.75rem;">
                    {{ $orders->total() }}
                </span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.08);">
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Kode Order</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Pelanggan</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Daftar Item</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase text-center" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Bayar</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase text-end" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        @php
                            $statusMap = [
                                'Antrian'             => ['class' => 'bg-secondary',         'label' => 'Antrian'],
                                'Menunggu Konfirmasi' => ['class' => 'bg-info',              'label' => 'Cek Bayar'],
                                'Diterima Toko'       => ['class' => 'bg-primary',           'label' => 'Diterima'],
                                'Dikerjakan'          => ['class' => 'bg-warning text-dark', 'label' => 'Proses'],
                                'Siap Diambil'        => ['class' => 'bg-success',           'label' => 'Ready'],
                                'Siap Dikirim'        => ['class' => 'bg-success',           'label' => 'Ready Kirim'],
                                'Selesai'             => ['class' => 'bg-success',           'label' => 'Selesai'],
                                'Dibatalkan'          => ['class' => 'bg-danger',            'label' => 'Batal'],
                            ];
                            $badge = $statusMap[$order->status] ?? ['class' => 'bg-secondary', 'label' => $order->status];
                        @endphp
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.2s;">
                            <td class="py-3 px-4 align-middle">
                                <span class="fw-medium text-white" style="font-size: 0.9rem;">#{{ $order->order_code }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="d-block text-white" style="font-size: 0.9rem;">{{ $order->customer_name ?: ($order->customer->name ?? 'Guest') }}</span>
                                <span class="text-muted" style="font-size: 0.78rem;">{{ $order->customer->email ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                @foreach($order->items as $item)
                                    <div class="mb-1" style="font-size: 0.85rem;">
                                        <i class="fas fa-shoe-prints me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        <span class="text-white">{{ $item->shoe_brand }}</span>
                                        <span class="text-muted">({{ $item->treatment->name ?? '-' }})</span>
                                    </div>
                                @endforeach
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="badge {{ $badge['class'] }} rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 500;">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="text-white fw-medium" style="font-size: 0.9rem;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                <span class="d-block" style="font-size: 0.75rem;">
                                    @if($order->payment_status === 'lunas')
                                        <span class="text-success"><i class="fas fa-circle-check me-1" style="font-size: 0.65rem;"></i>Lunas</span>
                                    @else
                                        <span class="text-warning"><i class="fas fa-circle me-1" style="font-size: 0.65rem;"></i>Belum Lunas</span>
                                    @endif
                                </span>
                            </td>
                            <td class="py-3 px-4 align-middle text-muted" style="font-size: 0.82rem;">
                                {{ $order->created_at->format('d M Y') }}<br>
                                <span style="font-size: 0.75rem;">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                            <td class="py-3 px-4">
                                @if($order->payment_status == 'lunas')
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.65rem; border: 1px solid rgba(25,135,84,0.1);">Lunas</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.65rem; border: 1px solid rgba(220,53,69,0.1);">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 align-middle text-end">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    {{-- WhatsApp Button --}}
                                    @php
                                        $phone = $order->customer_phone ?: ($order->customer->phone ?? '');
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                        }
                                        $invoiceUrl = route('public.invoice', $order->order_code);
                                        $msg = "Halo " . ($order->customer_name ?: 'Pelanggan') . ", pesanan Laundry Sepatu Anda #" . $order->order_code . " saat ini berstatus: " . $order->status . ".\n\nLihat invoice lengkap di sini:\n" . $invoiceUrl;
                                        $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . urlencode($msg);
                                    @endphp
                                    <a href="{{ $waUrl }}" target="_blank" 
                                       class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center" 
                                       title="Kirim WhatsApp" style="width: 30px; height: 30px; padding: 0; background-color: #25d366; border: none;">
                                        <i class="fab fa-whatsapp" style="font-size: 0.8rem;"></i>
                                    </a>

                                    {{-- Status Trigger Modal --}}
                                    <button class="btn btn-sm btn-outline-custom rounded-pill d-flex align-items-center gap-2" 
                                            type="button" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updateStatusModal{{ $order->id }}"
                                            style="font-size: 0.72rem; padding: 0.4rem 0.8rem;">
                                        <i class="fas fa-sync-alt" style="font-size: 0.65rem;"></i> Status
                                    </button>

                                    {{-- Status Update Modal --}}
                                    <div class="modal fade" id="updateStatusModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content" style="background: #1a1a1a; border: 1px solid rgba(255,255,255,0.1); border-radius: 15px;">
                                                <div class="modal-header border-bottom border-white-5 p-4">
                                                    <h5 class="modal-title text-white fw-bold">Update Status Order</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <div class="modal-body p-4 text-start">
                                                        <div class="mb-3">
                                                            <div class="small text-muted mb-2 text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.65rem;">Kode Order</div>
                                                            <div class="text-white fs-5 fw-bold">#{{ $order->order_code }}</div>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label text-muted small mb-2">Pilih Status Baru:</label>
                                                            <select name="status" class="form-select form-select-lg" style="background: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                                                                @foreach($allStatuses as $st)
                                                                    <option value="{{ $st }}" {{ $order->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="p-3 rounded-3 mb-2" style="background: rgba(244,114,182,0.05); border: 1px solid rgba(244,114,182,0.1);">
                                                            <div class="small text-white-50">
                                                                <i class="fas fa-info-circle me-1 text-primary"></i> 
                                                                Status saat ini: <span class="text-primary fw-bold">{{ $order->status }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top border-white-5 p-4">
                                                        <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary px-4 py-2">Update Status</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn btn-sm btn-outline-light rounded-circle d-flex align-items-center justify-content-center"
                                       title="Lihat Detail" style="width: 30px; height: 30px; padding: 0; border-color: rgba(255,255,255,0.15);">
                                        <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                    </a>

                                    {{-- Print Invoice --}}
                                    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
                                       class="btn btn-sm btn-outline-info rounded-circle d-flex align-items-center justify-content-center"
                                       title="Cetak Invoice" style="width: 30px; height: 30px; padding: 0; border-color: rgba(13,202,240,0.3);">
                                        <i class="fas fa-print" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-5 text-center text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 d-block" style="opacity: 0.15;"></i>
                                <p class="mb-2">Belum ada order.</p>
                                <a href="{{ route('admin.orders.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>Buat Order Baru
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="card-footer py-3 px-4" style="background-color: transparent; border-top: 1px solid rgba(255,255,255,0.05);">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }} order
                </span>
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<style>
    .form-control::placeholder { color: #555; }
    .form-control:focus, .form-select:focus {
        background-color: #2a2a2a !important;
        border-color: rgba(244, 114, 182, 0.5) !important;
        color: #e0e0e0 !important;
        box-shadow: 0 0 0 0.2rem rgba(244, 114, 182, 0.1) !important;
    }
    .form-select option { background-color: #2a2a2a; }
    .input-group-text { border-right: none !important; }
    .input-group .form-control { border-left: none !important; }

    /* Pagination dark styling */
    .pagination .page-link {
        background-color: #2a2a2a;
        border-color: rgba(255,255,255,0.08);
        color: #a0a0a0;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }
    .pagination .page-link:hover {
        background-color: rgba(244,114,182,0.1);
        color: var(--primary);
    }
</style>

<script>
    function exportReport() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        const exportUrl = "{{ route('admin.orders.export') }}?" + params;
        
        window.open(exportUrl, '_blank');
    }
</script>
@endsection
