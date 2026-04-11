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
        <div class="col-md-4 text-md-end mt-4 mt-md-0">
            <div class="d-flex justify-content-md-end gap-2">
                <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-outline-info px-4" style="border-radius: 12px;">
                    <i class="fas fa-print me-2"></i>Cetak Invoice
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-custom px-4" style="border-radius: 12px;">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
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

            {{-- Daftar Item Sepatu --}}
            <div class="card mb-4 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header border-bottom border-white-5 py-3" style="background: rgba(255,255,255,0.02);">
                    <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <i class="fas fa-shoe-prints text-primary"></i> Daftar Item Sepatu
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="small text-muted text-uppercase" style="background: rgba(255,255,255,0.01);">
                                <tr>
                                    <th class="ps-4 py-3">Item</th>
                                    <th class="py-3">Material & Warna</th>
                                    <th class="py-3">Treatment</th>
                                    <th class="py-3 text-end pe-4">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->photo_before)
                                                <a href="{{ asset($item->photo_before) }}" target="_blank">
                                                    <img src="{{ asset($item->photo_before) }}" 
                                                         class="rounded-circle shadow-sm border border-white-10" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border: 2px solid rgba(255,255,255,0.1);"
                                                         title="Lihat Foto Sebelum">
                                                </a>
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-white-10" 
                                                     style="width: 50px; height: 50px; opacity: 0.3;">
                                                    <i class="fas fa-image text-white-50"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-white">{{ $item->shoe_brand }}</div>
                                                <span class="x-small text-muted" style="font-size: 0.7rem;">Item #{{ $item->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="small text-white-50">{{ $item->shoe_material }}</div>
                                        <div class="small text-white-50">{{ $item->shoe_color }}</div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem;">{{ $item->treatment->name ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 text-end pe-4 fw-bold">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Treatment & Layanan --}}
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header border-bottom border-white-5 py-3" style="background: rgba(255,255,255,0.02);">
                    <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <i class="fas fa-truck text-primary"></i> Layanan & Logistik
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <p class="text-muted small text-uppercase fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.7rem;">Metode Layanan</p>
                            <div class="d-flex align-items-center gap-2">
                                @if($order->service_method === 'pickup_delivery')
                                    <div class="p-2 rounded bg-primary-subtle text-primary">
                                        <i class="fas fa-truck-loading fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white">Pickup & Delivery</div>
                                        <div class="small text-muted">Akan dijemput & diantar</div>
                                    </div>
                                @else
                                    <div class="p-2 rounded bg-secondary-subtle text-white">
                                        <i class="fas fa-store fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white">Datang Langsung</div>
                                        <div class="small text-muted">Customer antar sendiri</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="text-muted small text-uppercase fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.7rem;">Estimasi Selesai</p>
                            <div class="fw-bold text-white fs-5">{{ $order->estimated_completion ? \Carbon\Carbon::parse($order->estimated_completion)->format('d M Y') : 'Belum ditentukan' }}</div>
                            <div class="small text-muted">{{ $order->created_at->format('d M Y, H:i') }} (Dipesan)</div>
                        </div>

                        @if($order->service_method === 'pickup_delivery')
                        <div class="col-12 mt-2">
                            <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="text-muted small mb-1">Alamat Penjemputan</p>
                                        <p class="text-white small mb-0">{{ $order->pickup_address ?? 'Sesuai Koordinat Peta' }}</p>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <p class="text-muted small mb-1">Jadwal Penjemputan</p>
                                        <p class="text-white small mb-0">{{ $order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') : '-' }} ({{ $order->pickup_time ?? '-' }})</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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
                    'Antrian'             => ['class' => 'bg-secondary',         'label' => 'Antrian'],
                    'Menunggu Konfirmasi' => ['class' => 'bg-info',              'label' => 'Konfirmasi Bayar'],
                    'Diterima Toko'       => ['class' => 'bg-primary',           'label' => 'Diterima Toko'],
                    'Dikerjakan'          => ['class' => 'bg-warning text-dark', 'label' => 'Sedang Dikerjakan'],
                    'Siap Diambil'        => ['class' => 'bg-success',           'label' => 'Siap Diambil'],
                    'Siap Dikirim'        => ['class' => 'bg-success',           'label' => 'Siap Dikirim'],
                    'Selesai'             => ['class' => 'bg-success',           'label' => 'Selesai'],
                    'Dibatalkan'          => ['class' => 'bg-danger',            'label' => 'Dibatalkan'],
                ];
                $badge = $statusMap[$order->status] ?? ['class' => 'bg-secondary', 'label' => $order->status];
            @endphp
            
            {{-- Bukti Pembayaran --}}
            @if($order->payment_proof)
            <div class="card mb-4 border-0 shadow-sm" style="background: rgba(56, 189, 248, 0.05); border: 1px solid rgba(56, 189, 248, 0.2) !important;">
                <div class="card-header border-bottom border-info-subtle py-3" style="background: rgba(56, 189, 248, 0.1);">
                    <h6 class="mb-0 fw-bold d-flex align-items-center gap-2 text-info">
                        <i class="fas fa-file-invoice-dollar"></i> Bukti Pembayaran
                    </h6>
                </div>
                <div class="card-body p-3 text-center">
                    <a href="{{ asset($order->payment_proof) }}" target="_blank">
                        <img src="{{ asset($order->payment_proof) }}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 200px; border: 3px solid #fff;">
                    </a>
                    @if($order->payment_status !== 'lunas')
                        <form action="{{ route('admin.orders.confirmPayment', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info w-100 fw-bold text-white shadow-sm">
                                <i class="fas fa-check-double me-2"></i> Konfirmasi Lunas
                            </button>
                        </form>
                    @else
                        <div class="badge bg-success w-100 py-2 fs-6">
                            <i class="fas fa-check-circle me-1"></i> Pembayaran Terverifikasi
                        </div>
                    @endif
                </div>
            </div>
            @endif
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

            {{-- Info Pelanggan PREMIUM --}}
            <div class="card shadow-sm border-0 mb-4" style="background: linear-gradient(135deg, #1e1e1e 0%, #161616 100%); border: 1px solid rgba(255,255,255,0.05) !important; border-radius: 16px;">
                <div class="card-body p-4">
                    <p class="text-muted small text-uppercase mb-4 fw-bold" style="letter-spacing: 1.5px; font-size: 0.65rem; opacity: 0.6;">
                        <i class="fas fa-id-badge me-1"></i> Informasi Pelanggan
                    </p>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle shadow-lg d-flex align-items-center justify-content-center fw-bold text-white" 
                             style="width: 60px; height: 60px; background: linear-gradient(45deg, var(--primary) 0%, #ec4899 100%); font-size: 1.5rem;">
                            {{ strtoupper(substr($order->customer->name ?? ($order->customer_name ?? 'G'), 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-white fs-5" style="letter-spacing: -0.5px;">{{ $order->customer_name ?: ($order->customer->name ?? 'Guest') }}</div>
                            <div class="small text-muted mb-1"><i class="fas fa-envelope me-1 small"></i>{{ $order->customer->email ?? '-' }}</div>
                            @if($order->customer->phone ?? false)
                                <div class="small text-white-50"><i class="fas fa-phone me-1 small"></i>{{ $order->customer->phone }}</div>
                            @endif
                        </div>
                    </div>
                    
                    @if($order->customer->phone ?? false)
                    <div class="d-grid mt-3">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer->phone) }}" target="_blank" 
                           class="btn btn-success d-flex align-items-center justify-content-center gap-2" 
                           style="border-radius: 10px; background-color: #25d366; border: none; font-weight: 600; padding: 0.6rem;">
                            <i class="fab fa-whatsapp fa-lg"></i> Hubungi via WhatsApp
                        </a>
                    </div>
                    @endif
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
