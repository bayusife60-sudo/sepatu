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
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Cari Order</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #a0a0a0;">
                            <i class="fas fa-search" style="font-size: 0.85rem;"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                               placeholder="Kode order, nama pelanggan, merek..."
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-left: none;">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Filter Status</label>
                    <select name="status" class="form-select"
                            style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                        <option value="">Semua Status</option>
                        @foreach($allStatuses as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $st)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill" style="padding: 0.6rem;">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    @if(request('search') || request('status'))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-custom" style="padding: 0.6rem 0.8rem;" title="Reset">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Stats Row --}}
    @php
        $totalAll       = \App\Models\Order::count();
        $totalPending   = \App\Models\Order::where('status', 'pending')->count();
        $totalProcess   = \App\Models\Order::whereIn('status', ['pickup_scheduled','picked_up','in_queue','cleaning_in_progress','quality_check'])->count();
        $totalDone      = \App\Models\Order::where('status', 'completed')->count();
    @endphp
    <div class="row g-3 mb-4">
        @foreach([
            ['label' => 'Total Order', 'value' => $totalAll, 'color' => 'rgba(244,114,182,0.15)', 'border' => 'var(--primary)', 'icon' => 'fa-list'],
            ['label' => 'Pending', 'value' => $totalPending, 'color' => 'rgba(107,114,128,0.15)', 'border' => '#6b7280', 'icon' => 'fa-clock'],
            ['label' => 'Dalam Proses', 'value' => $totalProcess, 'color' => 'rgba(234,179,8,0.15)', 'border' => '#eab308', 'icon' => 'fa-spinner'],
            ['label' => 'Selesai', 'value' => $totalDone, 'color' => 'rgba(34,197,94,0.15)', 'border' => '#22c55e', 'icon' => 'fa-check-circle'],
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
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.08);">
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Kode Order</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Pelanggan</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Sepatu</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Treatment</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Tanggal</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase text-end" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        @php
                            $statusMap = [
                                'pending'              => ['class' => 'bg-secondary',         'label' => 'Pending'],
                                'pickup_scheduled'     => ['class' => 'bg-info text-dark',    'label' => 'Jadwal Pickup'],
                                'picked_up'            => ['class' => 'bg-info text-dark',    'label' => 'Sudah Pickup'],
                                'in_queue'             => ['class' => 'bg-warning text-dark', 'label' => 'Antrian'],
                                'cleaning_in_progress' => ['class' => 'bg-primary',           'label' => 'Proses Cuci'],
                                'quality_check'        => ['class' => 'bg-warning text-dark', 'label' => 'QC'],
                                'ready_for_delivery'   => ['class' => 'bg-success',           'label' => 'Siap Kirim'],
                                'delivery_in_progress' => ['class' => 'bg-info text-dark',    'label' => 'Dalam Pengiriman'],
                                'completed'            => ['class' => 'bg-success',           'label' => 'Selesai'],
                                'cancelled'            => ['class' => 'bg-danger',            'label' => 'Dibatalkan'],
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
                                <span class="d-block text-white" style="font-size: 0.9rem;">{{ $order->shoe_brand }}</span>
                                <span class="text-muted" style="font-size: 0.78rem;">{{ $order->shoe_type }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="text-muted small">{{ $order->treatment->name ?? '-' }}</span>
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
                            <td class="py-3 px-4 align-middle text-end">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-light rounded-circle me-1"
                                   title="Lihat Detail" style="width: 32px; height: 32px; padding: 0; line-height: 30px; text-align: center;">
                                    <i class="fas fa-eye" style="font-size: 0.8rem;"></i>
                                </a>
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
@endsection
