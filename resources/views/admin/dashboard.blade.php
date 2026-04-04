@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">
    <!-- Page Header -->
    <div class="row mb-5 align-items-end">
        <div class="col-md-8">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.5rem;">Dashboard Operasional</h2>
            <p class="text-muted mt-2 mb-0" style="font-weight: 300;">Ringkasan aktivitas hari ini, {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="col-md-4 text-md-end mt-4 mt-md-0">
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary" style="padding: 0.6rem 1.5rem;"><i class="fas fa-plus me-2"></i>Order Baru</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-dismissible fade show mb-4" role="alert"
         style="background-color: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #86efac; border-radius: 8px;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Summary Widgets -->
    <div class="row mb-5 g-4">
        <!-- Active Orders -->
        <div class="col-md-4">
            <div class="card h-100" style="background: linear-gradient(145deg, var(--secondary-light) 0%, #1a1a1a 100%); border-top: 3px solid var(--primary);">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase text-muted mb-0" style="letter-spacing: 1px; font-size: 0.8rem;">Order Aktif</h6>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fas fa-spinner fa-lg"></i>
                        </div>
                    </div>
                    <h2 class="display-5 fw-bold mb-1" style="font-family: 'Playfair Display', serif;">{{ $activeOrders }}</h2>
                    <p class="text-muted small mb-0"><span class="text-success"><i class="fas fa-arrow-up me-1"></i>Dalam proses</span> pengerjaan</p>
                    
                    <i class="fas fa-spinner position-absolute text-white" style="font-size: 10rem; opacity: 0.02; bottom: -20px; right: -20px; transform: rotate(-15deg);"></i>
                </div>
            </div>
        </div>

        <!-- Today Pickups -->
        <div class="col-md-4">
            <div class="card h-100" style="background: linear-gradient(145deg, var(--secondary-light) 0%, #1a1a1a 100%);">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase text-muted mb-0" style="letter-spacing: 1px; font-size: 0.8rem;">Perlu Pickup Hari Ini</h6>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fas fa-truck-pickup fa-lg"></i>
                        </div>
                    </div>
                    <h2 class="display-5 fw-bold mb-1" style="font-family: 'Playfair Display', serif;">{{ $todayPickups }}</h2>
                    <p class="text-muted small mb-0">Menunggu jadwal penjemputan</p>
                    
                    <i class="fas fa-truck-pickup position-absolute text-white" style="font-size: 10rem; opacity: 0.02; bottom: -20px; right: -20px; transform: rotate(-15deg);"></i>
                </div>
            </div>
        </div>

        <!-- Today Deliveries -->
        <div class="col-md-4">
            <div class="card h-100" style="background: linear-gradient(145deg, var(--secondary-light) 0%, #1a1a1a 100%);">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase text-muted mb-0" style="letter-spacing: 1px; font-size: 0.8rem;">Perlu Delivery Hari Ini</h6>
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fas fa-box-open fa-lg"></i>
                        </div>
                    </div>
                    <h2 class="display-5 fw-bold mb-1" style="font-family: 'Playfair Display', serif;">{{ $todayDeliveries }}</h2>
                    <p class="text-muted small mb-0">Sepatu siap dikirim kembali</p>
                    
                    <i class="fas fa-box-open position-absolute text-white" style="font-size: 10rem; opacity: 0.02; bottom: -20px; right: -20px; transform: rotate(-15deg);"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center text-start py-4">
            <h4 class="mb-0" style="font-size: 1.25rem;">Order Terbaru</h4>
            <a href="#" class="btn btn-sm btn-outline-custom" style="padding: 0.4rem 1rem; font-size: 0.75rem;">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Kode Order</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Pelanggan</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Sepatu</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Status</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Tanggal</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase text-end" style="font-size: 0.8rem; letter-spacing: 1px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background-color 0.2s;">
                            <td class="py-3 px-4 align-middle fw-medium">#{{ $order->order_code }}</td>
                            <td class="py-3 px-4 align-middle">
                                <span class="d-block text-white">{{ $order->customer->name ?? 'Guest' }}</span>
                                <span class="text-muted small">{{ $order->customer->phone ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="d-block text-white">{{ $order->shoe_brand }}</span>
                                <span class="text-muted small">{{ $order->shoe_type }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                @php
                                    $statusBadge = [
                                        'pending' => 'bg-secondary',
                                        'pickup_scheduled' => 'bg-info text-dark',
                                        'picked_up' => 'bg-info text-dark',
                                        'in_queue' => 'bg-warning text-dark',
                                        'cleaning_in_progress' => 'bg-primary',
                                        'quality_check' => 'bg-warning text-dark',
                                        'ready_for_delivery' => 'bg-success',
                                        'delivery_in_progress' => 'bg-info text-dark',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger'
                                    ];
                                    $badgeClass = $statusBadge[$order->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }} py-2 px-3 fw-medium rounded-pill" style="font-weight: 500;">
                                    {{ str_replace('_', ' ', strtoupper($order->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 align-middle text-muted small">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3 px-4 align-middle text-end">
                                <button class="btn btn-sm btn-outline-light rounded-circle" title="View Detail" style="width: 32px; height: 32px; padding: 0;">
                                    <i class="fas fa-eye" style="font-size: 0.8rem;"></i>
                                </button>
                                <button class="btn btn-sm btn-primary rounded-circle ms-1" title="Update Status" style="width: 32px; height: 32px; padding: 0;">
                                    <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-box-open fa-3x mb-3" style="opacity: 0.2;"></i>
                                    <p class="mb-0">Belum ada order untuk saat ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
