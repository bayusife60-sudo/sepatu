@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 900px;">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">
                Detail Order <span style="color: var(--primary);">#{{ $order->order_code }}</span>
            </h2>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-custom" style="padding: 0.55rem 1.2rem;">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-dismissible fade show mb-4" role="alert"
         style="background-color: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #86efac; border-radius: 8px;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        {{-- Kiri: Info Order --}}
        <div class="col-md-8">

            {{-- Info Pelanggan & Sepatu --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0" style="font-weight: 600; font-family: 'Jost', sans-serif; letter-spacing: 0.5px;">
                        <i class="fas fa-user me-2" style="color: var(--primary); opacity: 0.8;"></i>Informasi Pelanggan & Sepatu
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Nama Pelanggan</p>
                            <p class="text-white mb-0 fw-medium">{{ $order->customer_name ?: ($order->customer->name ?? 'Guest') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Email</p>
                            <p class="text-white mb-0">{{ $order->customer->email ?? '-' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Merek Sepatu</p>
                            <p class="text-white mb-0 fw-medium">{{ $order->shoe_brand }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Tipe Sepatu</p>
                            <p class="text-white mb-0">{{ $order->shoe_type }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Treatment & Layanan --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0" style="font-weight: 600; font-family: 'Jost', sans-serif; letter-spacing: 0.5px;">
                        <i class="fas fa-spray-can me-2" style="color: var(--primary); opacity: 0.8;"></i>Treatment & Metode Layanan
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Jenis Treatment</p>
                            <p class="text-white mb-0 fw-medium">{{ $order->treatment->name ?? '-' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Metode Layanan</p>
                            <p class="mb-0">
                                @if($order->service_method === 'pickup')
                                    <span class="text-info"><i class="fas fa-truck me-1"></i>Pickup & Delivery</span>
                                @else
                                    <span class="text-white"><i class="fas fa-store-alt me-1"></i>Datang Langsung</span>
                                @endif
                            </p>
                        </div>
                        @if($order->service_method === 'pickup')
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Alamat Pickup</p>
                            <p class="text-white mb-0" style="font-size: 0.9rem;">{{ $order->pickup_address ?? '-' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Jadwal Pickup</p>
                            <p class="text-white mb-0">{{ $order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('d M Y, H:i') : '-' }}</p>
                        </div>
                        @endif
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Estimasi Selesai</p>
                            <p class="text-white mb-0">{{ $order->estimated_completion ? \Carbon\Carbon::parse($order->estimated_completion)->format('d M Y, H:i') : '-' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Tanggal Order</p>
                            <p class="text-white mb-0">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rincian Harga --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0" style="font-weight: 600; font-family: 'Jost', sans-serif; letter-spacing: 0.5px;">
                        <i class="fas fa-receipt me-2" style="color: var(--primary); opacity: 0.8;"></i>Rincian Pembayaran
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Harga Treatment</span>
                        <span class="text-white">Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                    </div>
                    @if($order->pickup_fee > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Biaya Pickup</span>
                        <span class="text-white">Rp {{ number_format($order->pickup_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($order->delivery_fee > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Biaya Delivery</span>
                        <span class="text-white">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <hr style="border-color: rgba(255,255,255,0.08);">
                    <div class="d-flex justify-content-between">
                        <span class="text-white fw-medium">Total</span>
                        <span class="text-primary fw-bold" style="font-size: 1.1rem;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-3 d-flex gap-3">
                        <div>
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Metode Bayar</p>
                            <p class="text-white mb-0">{{ $order->payment_method ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-muted small text-uppercase mb-1" style="letter-spacing: 0.8px; font-size: 0.72rem;">Status Bayar</p>
                            @if($order->payment_status === 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kanan: Status & Update --}}
        <div class="col-md-4">

            {{-- Status Saat Ini --}}
            @php
                $statusMap = [
                    'pending'              => ['class' => 'bg-secondary',         'label' => 'Pending'],
                    'pickup_scheduled'     => ['class' => 'bg-info text-dark',    'label' => 'Jadwal Pickup'],
                    'picked_up'            => ['class' => 'bg-info text-dark',    'label' => 'Sudah Pickup'],
                    'in_queue'             => ['class' => 'bg-warning text-dark', 'label' => 'Antrian'],
                    'cleaning_in_progress' => ['class' => 'bg-primary',           'label' => 'Proses Cuci'],
                    'quality_check'        => ['class' => 'bg-warning text-dark', 'label' => 'Quality Check'],
                    'ready_for_delivery'   => ['class' => 'bg-success',           'label' => 'Siap Kirim'],
                    'delivery_in_progress' => ['class' => 'bg-info text-dark',    'label' => 'Dalam Pengiriman'],
                    'completed'            => ['class' => 'bg-success',           'label' => 'Selesai'],
                    'cancelled'            => ['class' => 'bg-danger',            'label' => 'Dibatalkan'],
                ];
                $badge = $statusMap[$order->status] ?? ['class' => 'bg-secondary', 'label' => $order->status];
            @endphp
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0" style="font-weight: 600; font-family: 'Jost', sans-serif; letter-spacing: 0.5px;">
                        <i class="fas fa-tag me-2" style="color: var(--primary); opacity: 0.8;"></i>Status Order
                    </h6>
                </div>
                <div class="card-body p-4 text-center">
                    <span class="badge {{ $badge['class'] }} rounded-pill px-4 py-2 mb-3" style="font-size: 0.9rem;">
                        {{ $badge['label'] }}
                    </span>

                    {{-- Update Status Form --}}
                    <hr style="border-color: rgba(255,255,255,0.08);">
                    <p class="text-muted small mb-3">Ubah status order:</p>
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select mb-3"
                                style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.15); color: #e0e0e0; border-radius: 6px;">
                            @foreach($allStatuses as $st)
                            <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $st)) }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary w-100" style="padding: 0.6rem;">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Progress Status --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0" style="font-weight: 600; font-family: 'Jost', sans-serif; letter-spacing: 0.5px;">
                        <i class="fas fa-route me-2" style="color: var(--primary); opacity: 0.8;"></i>Alur Proses
                    </h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $flow = [
                            'pending'              => 'Pending',
                            'pickup_scheduled'     => 'Jadwal Pickup',
                            'picked_up'            => 'Sudah Pickup',
                            'in_queue'             => 'Antrian',
                            'cleaning_in_progress' => 'Proses Cuci',
                            'quality_check'        => 'Quality Check',
                            'ready_for_delivery'   => 'Siap Kirim',
                            'delivery_in_progress' => 'Dalam Pengiriman',
                            'completed'            => 'Selesai',
                        ];
                        $currentIndex = array_search($order->status, array_keys($flow));
                    @endphp
                    <div class="d-flex flex-column gap-1">
                        @foreach($flow as $st => $label)
                        @php
                            $stIndex = array_search($st, array_keys($flow));
                            $isDone = ($currentIndex !== false && $stIndex <= $currentIndex && $order->status !== 'cancelled');
                            $isCurrent = $order->status === $st;
                        @endphp
                        <div class="d-flex align-items-center gap-2 py-1">
                            <div style="width: 20px; height: 20px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center;
                                background: {{ $isCurrent ? 'var(--primary)' : ($isDone ? 'rgba(34,197,94,0.3)' : 'rgba(255,255,255,0.05)') }};
                                border: 1px solid {{ $isCurrent ? 'var(--primary)' : ($isDone ? '#22c55e' : 'rgba(255,255,255,0.1)') }};">
                                @if($isCurrent)
                                    <i class="fas fa-circle" style="font-size: 0.5rem; color: #fff;"></i>
                                @elseif($isDone)
                                    <i class="fas fa-check" style="font-size: 0.55rem; color: #22c55e;"></i>
                                @endif
                            </div>
                            <span style="font-size: 0.82rem; color: {{ $isCurrent ? '#fff' : ($isDone ? '#a0a0a0' : '#555') }}; font-weight: {{ $isCurrent ? '600' : '400' }};">
                                {{ $label }}
                            </span>
                        </div>
                        @endforeach
                        @if($order->status === 'cancelled')
                        <div class="d-flex align-items-center gap-2 py-1">
                            <div style="width: 20px; height: 20px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center;
                                background: rgba(239,68,68,0.2); border: 1px solid #ef4444;">
                                <i class="fas fa-times" style="font-size: 0.55rem; color: #ef4444;"></i>
                            </div>
                            <span style="font-size: 0.82rem; color: #ef4444; font-weight: 600;">Dibatalkan</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<style>
    .form-select option { background-color: #2a2a2a; }
    .form-select:focus {
        background-color: #2a2a2a !important;
        border-color: rgba(244, 114, 182, 0.5) !important;
        color: #e0e0e0 !important;
        box-shadow: 0 0 0 0.2rem rgba(244, 114, 182, 0.1) !important;
    }
</style>
@endsection
