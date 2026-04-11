@extends('layouts.customer')

@section('title', 'Dashboard Saya')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <style>
        #map { height: 350px; width: 100%; border-radius: 12px; margin-top: 10px; border: 1px solid rgba(255,255,255,0.1); box-shadow: inset 0 0 20px rgba(0,0,0,0.5); }
        .service-card-hover { cursor: pointer; }
        .service-card-hover:hover {
            transform: translateY(-5px);
            border-color: rgba(244,114,182,0.4) !important;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        /* Custom Leaflet Dark Theme Adjustments */
        .leaflet-container { background: #111 !important; }
        .leaflet-control-attribution { background: rgba(0,0,0,0.5) !important; color: #777 !important; border: none !important; }
        .leaflet-bar a { background-color: #222 !important; color: #fff !important; border-bottom: 1px solid #333 !important; }
        .leaflet-bar a:hover { background-color: #333 !important; }
    </style>
@endsection

@section('content')
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm p-4" role="alert" style="background: rgba(34, 197, 94, 0.15); color: #4ade80; border-left: 4px solid #4ade80 !important; border-radius: 12px;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fa-2x"></i> 
                <div>
                    <div class="fw-bold">{{ session('success') }}</div>
                    @if(session('new_order_id'))
                        <div class="small mt-1 text-white-50">Pesanan Anda baru akan diproses oleh admin setelah konfirmasi pembayaran diterima.</div>
                    @endif
                </div>
            </div>
            @if(session('new_order_id'))
                <button type="button" class="btn btn-success px-4" onclick="openPaymentModal({{ session('new_order_id') }}, {{ session('new_order_total') }})" style="background: #22c55e; border: none; font-weight: 600;">
                    <i class="fas fa-wallet me-2"></i> Bayar Sekarang
                </button>
            @endif
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="top: 1rem; right: 1rem;"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border-left: 4px solid #f87171 !important;">
        <i class="fas fa-exclamation-circle me-2"></i> Mohon periksa kembali form Anda.
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-5">
        <div>
            <span style="color: var(--primary); text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; font-weight: 500;">Pelanggan</span>
            <h2 class="mb-1 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #fff;">Halo, {{ auth()->user()->name ?? 'Pelanggan' }}! 👋</h2>
            <p class="text-white mb-0" style="font-size: 0.9rem; font-weight: 300; opacity: 0.85;">Pantau pesanan dan riwayat layanan Anda di sini.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#orderModal" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; font-weight: 600; box-shadow: 0 4px 15px rgba(244,114,182,0.3); transition: all 0.3s; border-radius: 8px;">
                <i class="fas fa-plus-circle"></i> Pesan Sekarang
            </button>
            <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(244,114,182,0.15); color: var(--primary); font-size: 0.78rem; letter-spacing: 0.5px; display: flex; align-items: center;">
                <i class="fas fa-circle me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Pelanggan Aktif
            </span>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-5">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(244,114,182,0.1);">
                        <i class="fas fa-box-open" style="color: var(--primary);"></i>
                    </div>
                </div>
                <div class="stat-number text-white">{{ $totalOrders }}</div>
                <div class="stat-label text-white-50">Total Order</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(59,130,246,0.1);">
                        <i class="fas fa-spinner" style="color: #60a5fa;"></i>
                    </div>
                </div>
                <div class="stat-number text-white">{{ $activeOrders }}</div>
                <div class="stat-label text-white-50">Sedang Diproses</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(34,197,94,0.1);">
                        <i class="fas fa-check-circle" style="color: #4ade80;"></i>
                    </div>
                </div>
                <div class="stat-number text-white">{{ $completedOrders }}</div>
                <div class="stat-label text-white-50">Selesai</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(251,191,36,0.1);">
                        <i class="fas fa-clock" style="color: #fbbf24;"></i>
                    </div>
                </div>
                <div class="stat-number text-white">{{ $unpaidOrders }}</div>
                <div class="stat-label text-white-50">Belum Lunas</div>
            </div>
        </div>
    </div>

    {{-- Active Orders (if any) --}}
    @if($activeOrdersList->count() > 0)
    <div class="mb-5">
        <h5 class="mb-3" style="font-family: 'Jost', sans-serif; font-weight: 600; font-size: 1rem; letter-spacing: 0.5px; color: #fff;">
            <i class="fas fa-route me-2" style="color: var(--primary);"></i>Tracking Pesanan Aktif
        </h5>
        <div class="row g-4">
            @foreach($activeOrdersList as $activeOrder)
            <div class="col-12">
                <div class="card p-4 border-0" style="background: linear-gradient(145deg, var(--bg-card), rgba(244,114,182,0.03)); border: 1px solid rgba(255,255,255,0.05) !important;">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom border-secondary">
                        <div>
                            <span class="badge bg-primary text-white mb-2" style="letter-spacing: 1px;">{{ $activeOrder->order_code }}</span>
                            <h5 class="mb-1 text-white" style="font-family: 'Playfair Display', serif;">{{ $activeOrder->shoe_brand }} - {{ $activeOrder->shoe_type }}</h5>
                            <p class="text-muted small mb-0">{{ $activeOrder->treatment->name ?? 'Layanan' }} | <span class="text-white">Rp {{ number_format($activeOrder->total_price, 0, ',', '.') }}</span></p>
                        </div>
                        <div class="mt-3 mt-md-0 text-md-end">
                            <span class="d-block text-muted small mb-1">Estimasi Selesai</span>
                            <span class="text-white fw-medium">
                                {{ $activeOrder->estimated_completion ? \Carbon\Carbon::parse($activeOrder->estimated_completion)->format('d M Y') : 'Menunggu Jadwal' }}
                            </span>
                        </div>
                    </div>

                    {{-- Custom Tracking Progress Bar --}}
                    @php
                        $status = $activeOrder->status;
                        $payment = $activeOrder->payment_status;
                        
                        // Define steps logic
                        $stepPaid = ($payment === 'lunas');
                        
                        // Step 2: Pickup/Dropoff
                        $method = $activeOrder->service_method;
                        $stepReceived = in_array($status, ['picked_up', 'in_queue', 'cleaning_in_progress', 'quality_check', 'ready_for_delivery', 'delivery_in_progress', 'completed']);
                        $receiveLabel = ($method === 'pickup') ? 'Di-pickup' : 'Diterima Toko';
                        $receiveIcon = ($method === 'pickup') ? 'fa-box-open' : 'fa-store';

                        // Step 3: Dikerjakan
                        $stepWorking = in_array($status, ['cleaning_in_progress', 'quality_check', 'ready_for_delivery', 'delivery_in_progress', 'completed']);

                        // Step 4: Delivery/Ambil
                        $stepDelivery = in_array($status, ['delivery_in_progress', 'completed']);
                        $deliveryLabel = ($method === 'delivery') ? 'Dalam Pengiriman' : 'Siap Diambil';
                        $deliveryIcon = ($method === 'delivery') ? 'fa-truck' : 'fa-shopping-bag';

                        // Step 5: Selesai
                        $stepDone = ($status === 'completed');

                        // Current active step calculation for progress bar width
                        $progressPercent = 0;
                        if($stepDone) $progressPercent = 100;
                        elseif($stepDelivery) $progressPercent = 75;
                        elseif($stepWorking) $progressPercent = 50;
                        elseif($stepReceived) $progressPercent = 25;
                        elseif($stepPaid) $progressPercent = 10;
                    @endphp

                    <div class="tracking-wrapper position-relative mt-2 mb-3 px-2 px-md-4">
                        <div class="progress position-absolute" style="top: 15px; left: 10%; width: 80%; height: 3px; background-color: rgba(255,255,255,0.1); z-index: 1;">
                            <div class="progress-bar" role="progressbar" style="background-color: var(--primary); width: {{ $progressPercent }}%; transition: width 1s ease;"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between position-relative" style="z-index: 2;">
                            {{-- Step 1: Dibayar --}}
                            <div class="text-center tracking-step {{ $stepPaid ? 'active' : '' }}">
                                <div class="icon-circle {{ $stepPaid ? 'bg-primary border-primary' : 'bg-dark border-secondary' }} mx-auto mb-2 d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid; transition: all 0.3s;">
                                    <i class="fas fa-wallet" style="font-size: 0.75rem;"></i>
                                </div>
                                <span class="d-block small {{ $stepPaid ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 0.75rem;">Dibayar</span>
                            </div>

                            {{-- Step 2: Diterima / Pickup --}}
                            <div class="text-center tracking-step {{ $stepReceived ? 'active' : '' }}">
                                <div class="icon-circle {{ $stepReceived ? 'bg-primary border-primary' : 'bg-dark border-secondary' }} mx-auto mb-2 d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid; transition: all 0.3s;">
                                    <i class="fas {{ $receiveIcon }}" style="font-size: 0.75rem;"></i>
                                </div>
                                <span class="d-block small {{ $stepReceived ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 0.75rem;">{{ $receiveLabel }}</span>
                            </div>

                            {{-- Step 3: Dikerjakan --}}
                            <div class="text-center tracking-step {{ $stepWorking ? 'active' : '' }}">
                                <div class="icon-circle {{ $stepWorking ? 'bg-primary border-primary' : 'bg-dark border-secondary' }} mx-auto mb-2 d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid; transition: all 0.3s;">
                                    <i class="fas fa-hands-wash" style="font-size: 0.75rem;"></i>
                                </div>
                                <span class="d-block small {{ $stepWorking ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 0.75rem;">Dikerjakan</span>
                            </div>

                            {{-- Step 4: Delivery / Siap Diambil --}}
                            <div class="text-center tracking-step {{ $stepDelivery ? 'active' : '' }}">
                                <div class="icon-circle {{ $stepDelivery ? 'bg-primary border-primary' : 'bg-dark border-secondary' }} mx-auto mb-2 d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid; transition: all 0.3s;">
                                    <i class="fas {{ $deliveryIcon }}" style="font-size: 0.75rem;"></i>
                                </div>
                                <span class="d-block small {{ $stepDelivery ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 0.75rem;">{{ $deliveryLabel }}</span>
                            </div>

                            {{-- Step 5: Selesai --}}
                            <div class="text-center tracking-step {{ $stepDone ? 'active' : '' }}">
                                <div class="icon-circle {{ $stepDone ? 'bg-primary border-primary' : 'bg-dark border-secondary' }} mx-auto mb-2 d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid; transition: all 0.3s;">
                                    <i class="fas fa-check" style="font-size: 0.75rem;"></i>
                                </div>
                                <span class="d-block small {{ $stepDone ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 0.75rem;">Selesai</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Menu Layanan (Jika tidak ada order aktif) --}}
    @if($activeOrdersList->count() == 0)
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="font-family: 'Jost', sans-serif; font-weight: 600; font-size: 1rem; letter-spacing: 0.5px; color: #fff;">
                <i class="fas fa-star me-2" style="color: var(--primary);"></i>Layanan Kami
            </h5>
        </div>
        
        <div class="row g-3">
            @forelse($services as $service)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 p-3 border-0 service-card-hover" onclick="selectTreatment({{ $service->id }}, {{ $service->price }})" style="background-color: var(--bg-card); border: 1px solid rgba(255,255,255,0.05) !important; transition: all 0.3s ease;">
                    <div class="d-flex align-items-start mb-3">
                        <div class="icon-box me-3 mt-1" style="width: 40px; height: 40px; border-radius: 10px; background: rgba(244,114,182,0.1); display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink:0;">
                            <i class="fas fa-spray-can" style="font-size: 1.1rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-white" style="font-family: 'Playfair Display', serif; font-size: 1.1rem;">{{ $service->name }}</h6>
                            <span class="badge bg-primary text-white" style="font-size: 0.75rem; font-weight: 500;">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.6;">{{ $service->description }}</p>
                    <div class="pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                        <span class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-clock me-1 text-primary"></i> Est. 3-5 Hari</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="p-4 text-center" style="background: var(--bg-card); border-radius: 12px; border: 1px dashed rgba(255,255,255,0.1);">
                    <p class="text-white mb-0">Belum ada data layanan tersedia.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    @endif

    {{-- Order History Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white" style="font-size: 0.95rem; font-weight: 600; letter-spacing: 0.5px;">
                <i class="fas fa-history me-2" style="color: var(--primary);"></i>Riwayat Semua Pesanan
            </h5>
            <span class="badge rounded-pill" style="background-color: rgba(244,114,182,0.15); color: var(--primary); font-size: 0.72rem;">
                {{ $orders->total() }} total
            </span>
        </div>
        <div class="card-body p-0">
            @if($orders->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-shoe-prints"></i></div>
                <h6 style="color: rgba(255,255,255,0.9); font-weight: 400;">Belum Ada Pesanan</h6>
                <p class="text-white-50 mb-0" style="font-size: 0.85rem;">Anda belum memiliki riwayat pesanan apapun.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="background-color: transparent;">
                    <thead>
                        <tr>
                            <th class="ps-4">Kode Order</th>
                            <th>Sepatu</th>
                            <th>Layanan</th>
                            <th>Metode</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        @php
                            $statusMap = [
                                'Antrian' => ['label' => 'Antrian', 'class' => 'bg-secondary'],
                                'Menunggu Konfirmasi' => ['label' => 'Menunggu Konfirmasi', 'class' => 'bg-info'],
                                'Diterima Toko' => ['label' => 'Diterima Toko', 'class' => 'bg-primary'],
                                'Dikerjakan' => ['label' => 'Dikerjakan', 'class' => 'bg-warning text-dark'],
                                'Siap Diambil' => ['label' => 'Siap Diambil', 'class' => 'bg-success'],
                                'ready_for_delivery' => ['label' => 'Siap Dikirim', 'class' => 'bg-success'],
                                'delivery_in_progress' => ['label' => 'Sedang Dikirim', 'class' => 'bg-success'],
                                'completed' => ['label' => 'Selesai', 'class' => 'bg-success'],
                                'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-danger'],
                            ];
                            $statusInfo = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-secondary'];
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="fw-medium" style="font-size: 0.88rem; color: var(--primary); font-family: monospace;">{{ $order->order_code }}</span>
                            </td>
                            <td>
                                @foreach($order->items as $item)
                                    <div class="mb-1">
                                        <span class="d-block" style="font-size: 0.85rem; color: #fff;">{{ $item->shoe_brand }}</span>
                                        <span class="text-muted" style="font-size: 0.7rem;">{{ $item->shoe_material }} - {{ $item->shoe_color }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @foreach($order->items as $item)
                                    <div class="mb-1">
                                        <span style="font-size: 0.82rem;">{{ $item->treatment->name ?? '-' }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @if($order->service_method === 'datang_langsung')
                                    <span class="badge bg-secondary rounded-pill px-2" style="font-size: 0.7rem;"><i class="fas fa-store me-1"></i>Toko</span>
                                @elseif($order->service_method === 'pickup_delivery')
                                    <span class="badge bg-primary rounded-pill px-2" style="font-size: 0.7rem;"><i class="fas fa-truck me-1"></i>Pickup Delivery</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fw-medium" style="font-size: 0.85rem;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </td>
                            <td class="text-center">
                                @if($order->payment_status === 'lunas')
                                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.68rem;">Lunas</span>
                                @elseif($order->payment_proof)
                                    <span class="badge bg-info rounded-pill px-2 py-1" style="font-size: 0.68rem;">Menunggu Verifikasi</span>
                                @else
                                    <button type="button" class="btn btn-warning btn-sm py-0 px-2 rounded-pill fw-bold" onclick="openPaymentModal({{ $order->id }}, {{ $order->total_price }})" style="font-size: 0.68rem;">
                                        Bayar
                                    </button>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $statusInfo['class'] }} rounded-pill px-2 py-1" style="font-size: 0.68rem;">{{ $statusInfo['label'] }}</span>
                            </td>
                            <td class="text-center text-muted" style="font-size: 0.78rem;">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
            <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }}</span>
                    {{ $orders->links() }}
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

    {{-- Profile Info --}}
    <div class="card mt-4 p-4">
        <div class="d-flex align-items-center gap-4">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 700; color: #fff; flex-shrink: 0;">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1 fw-bold" style="color: #fff;">{{ auth()->user()->name }}</h6>
                <p class="mb-0 text-muted" style="font-size: 0.85rem;"><i class="fas fa-envelope me-2"></i>{{ auth()->user()->email }}</p>
                @if(auth()->user()->phone)
                <p class="mb-0 text-muted mt-1" style="font-size: 0.85rem;"><i class="fas fa-phone me-2"></i>{{ auth()->user()->phone }}</p>
                @endif
                @if(auth()->user()->address)
                <p class="mb-0 text-muted mt-1" style="font-size: 0.85rem;"><i class="fas fa-map-marker-alt me-2"></i>{{ auth()->user()->address }}</p>
                @endif
            </div>
            <div class="text-end d-none d-md-block">
                <p class="text-muted mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Member Sejak</p>
                <p class="mb-0" style="font-size: 0.88rem; color: #fff;">{{ auth()->user()->created_at->format('M Y') }}</p>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Modal Pembayaran -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="background-color: #1a1a1a; color: #fff; border-radius: 16px;">
                <form id="paymentForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-bottom border-white-5" style="background: rgba(255,255,255,0.02); padding: 1.5rem;">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2" style="font-family: 'Playfair Display', serif;">
                            <i class="fas fa-university text-primary"></i> Konfirmasi Pembayaran
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-4 p-3 rounded-3" style="background: rgba(244,114,182,0.05); border: 1px dashed rgba(244,114,182,0.2);">
                            <p class="small text-white-50 mb-1 text-uppercase fw-bold" style="letter-spacing: 1px;">Total Tagihan</p>
                            <h3 class="fw-bold mb-0 text-primary" id="payment_total_display">Rp 0</h3>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-white-50 text-uppercase fw-bold" style="letter-spacing: 1px;">Transfer Ke Rekening</label>
                            <div class="p-3 rounded-3 bg-dark border border-secondary">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-white-50 small">BCA</span>
                                    <span class="fw-bold">1234567890</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white-50 small">A/N</span>
                                    <span class="fw-bold">Cleansetz Laundry</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-white-50 text-uppercase fw-bold" style="letter-spacing: 1px;">Upload Bukti Transfer</label>
                            <input type="file" name="payment_proof" class="form-control bg-dark border-secondary text-white" required accept="image/*">
                            <p class="x-small text-muted mt-2 mb-0" style="font-size: 0.75rem;">Format JPG, PNG, atau JPEG. Maks 2MB.</p>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-white-5 p-3">
                        <button type="button" class="btn btn-link text-white-50 text-decoration-none" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; border-radius: 8px;">
                            Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pemesanan -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="background-color: #1a1a1a; color: #fff; border-radius: 16px; overflow: hidden;">
                <form action="{{ route('customer.orders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-bottom border-white-5 link-secondary" style="background: rgba(255,255,255,0.02); padding: 1.5rem 2rem;">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="orderModalLabel" style="font-family: 'Playfair Display', serif;">
                            <i class="fas fa-magic text-primary"></i> Buat Pesanan Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 2rem;">
                        <div class="row g-4">
                            <!-- Multi-Item Container -->
                            <div class="col-md-12">
                                <div id="items_container">
                                    <!-- First Item (Default) -->
                                    <div class="item-block p-3 mb-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 text-white fw-bold"><i class="fas fa-shoe-prints me-2 text-primary"></i>Sepatu #1</h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0 remove-item d-none"><i class="fas fa-times"></i></button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Merek</label>
                                                <input type="text" name="items[0][brand]" class="form-control bg-dark border-secondary text-white" placeholder="Ex: ADIDAS" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Bahan</label>
                                                <select name="items[0][material]" class="form-select bg-dark border-secondary text-white material-select" required>
                                                    <option value="" selected disabled>Pilih Bahan</option>
                                                    <option value="Kulit">Kulit</option>
                                                    <option value="Suede">Suede</option>
                                                    <option value="Nubuck">Nubuck</option>
                                                    <option value="Canvas">Canvas</option>
                                                    <option value="Mesh">Mesh</option>
                                                    <option value="Knit">Knit</option>
                                                    <option value="Nylon">Nylon</option>
                                                    <option value="Rubber">Rubber (Karet)</option>
                                                    <option value="EVA">EVA</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Warna Utama</label>
                                                <input type="text" name="items[0][color]" class="form-control bg-dark border-secondary text-white color-input" placeholder="Ex: Putih" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Pilih Layanan Perawatan</label>
                                                <select name="items[0][treatment_id]" class="form-select bg-dark border-secondary text-white treatment-select-item" required>
                                                    <option value="" selected disabled>-- Pilih Layanan --</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-name="{{ $service->name }}">
                                                            {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Foto Sepatu (Opsional)</label>
                                                <input type="file" name="items[0][photo]" class="form-control bg-dark border-secondary text-white py-1" accept="image/*" style="font-size: 0.85rem;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add_item_btn" class="btn btn-outline-primary btn-sm w-100 py-2 border-dashed" style="border-style: dashed !important; border-width: 2px;">
                                    <i class="fas fa-plus-circle me-1"></i> Tambah Sepatu Lainnya
                                </button>
                            </div>

                            <!-- Metode Layanan -->
                            <div class="col-md-12">
                                <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Metode Pengiriman/Pengambilan</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="service_method" id="method_direct" value="datang_langsung" checked autocomplete="off">
                                        <label class="btn btn-outline-secondary w-100 py-3 text-start d-flex flex-column gap-1" for="method_direct">
                                            <i class="fas fa-store mb-1"></i>
                                            <span class="small fw-bold">Antar Sendiri</span>
                                            <span class="x-small text-muted" style="font-size: 0.65rem;">Bawa ke Toko</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="service_method" id="method_pickup_delivery" value="pickup_delivery" autocomplete="off" {{ old('service_method') == 'pickup_delivery' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-secondary w-100 py-3 text-start d-flex flex-column gap-1" for="method_pickup_delivery">
                                            <i class="fas fa-truck mb-1"></i>
                                            <span class="small fw-bold">Pickup Delivery</span>
                                            <span class="x-small text-muted" style="font-size: 0.65rem;">Layanan Antar Jemput</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Pickup Location (Map) -->
                            <div id="location_fields" class="col-md-12 d-none">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label small text-white-50 text-uppercase fw-bold m-1">Tandai Lokasi Anda di Map</label>
                                            <div id="map"></div>
                                            <input type="hidden" name="latitude" id="lat_input" value="{{ old('latitude') }}">
                                            <input type="hidden" name="longitude" id="lng_input" value="{{ old('longitude') }}">
                                            <div id="distance_info" class="mt-2 text-primary small d-none">
                                                <i class="fas fa-info-circle me-1"></i> Jarak: <span id="distance_val">0</span> KM | Biaya: <span id="fee_val">Rp 0</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-white-50 text-uppercase fw-bold m-1">Tgl Jemput</label>
                                            <input type="date" name="pickup_date" id="pickup_date" class="form-control bg-dark border-secondary text-white" value="{{ old('pickup_date', date('Y-m-d')) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-white-50 text-uppercase fw-bold m-1">Jam Jemput</label>
                                            <input type="time" name="pickup_time" class="form-control bg-dark border-secondary text-white" value="{{ old('pickup_time') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-white-5 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.02); padding: 1.5rem 2rem;">
                        <div>
                            <span class="text-white-50 small d-block">Estimasi Total</span>
                            <h4 id="display_total" class="text-white fw-bold mb-0">Rp 0</h4>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary px-4 border-0" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.1);">Batal</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; box-shadow: 0 4px 15px rgba(244,114,182,0.3);">Konfirmasi Pesanan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            console.error('GLOBAL ERROR:', message, 'at', source, 'line', lineno, ':', colno);
        };
        console.log('Script section start');
        // Global variables for map
        let map, marker;
        const STORE_LAT = parseFloat("{{ env('STORE_LATITUDE', -6.20000000) }}".replace(',', '.'));
        const STORE_LNG = parseFloat("{{ env('STORE_LONGITUDE', 106.71660000) }}".replace(',', '.'));

        console.log('Constants loaded:', {STORE_LAT, STORE_LNG});

        // Function to handle treatment selection from dashboard cards
        function selectTreatment(id, price) {
            console.log('selectTreatment called:', {id, price});
            // Reset existing items and add one with this treatment
            const container = document.getElementById('items_container');
            if (container) {
                container.innerHTML = '';
                itemCount = 0;
                addItem(); // Adds item #1
                
                const firstTreatmentSelect = container.querySelector('.treatment-select-item');
                if (firstTreatmentSelect) {
                    firstTreatmentSelect.value = id;
                    const event = new Event('change');
                    firstTreatmentSelect.dispatchEvent(event);
                }
            }
            
            // Open Modal
            const modalEl = document.getElementById('orderModal');
            if (modalEl) {
                const orderModal = new bootstrap.Modal(modalEl);
                orderModal.show();
            }
        }

        let itemCount = 0;

        function addItem() {
            const container = document.getElementById('items_container');
            const index = itemCount++;
            
            const itemHTML = `
                <div class="item-block p-3 mb-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-white fw-bold"><i class="fas fa-shoe-prints me-2 text-primary"></i>Sepatu #${index + 1}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0 remove-item ${index === 0 ? 'd-none' : ''}"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Merek</label>
                            <input type="text" name="items[${index}][brand]" class="form-control bg-dark border-secondary text-white" placeholder="Ex: ADIDAS" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Bahan</label>
                            <select name="items[${index}][material]" class="form-select bg-dark border-secondary text-white material-select" required>
                                <option value="" selected disabled>Pilih Bahan</option>
                                <option value="Kulit">Kulit</option>
                                <option value="Suede">Suede</option>
                                <option value="Nubuck">Nubuck</option>
                                <option value="Canvas">Canvas</option>
                                <option value="Mesh">Mesh</option>
                                <option value="Knit">Knit</option>
                                <option value="Nylon">Nylon</option>
                                <option value="Rubber">Rubber (Karet)</option>
                                <option value="EVA">EVA</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Warna Utama</label>
                            <input type="text" name="items[${index}][color]" class="form-control bg-dark border-secondary text-white color-input" placeholder="Ex: Putih" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Pilih Layanan Perawatan</label>
                            <select name="items[${index}][treatment_id]" class="form-select bg-dark border-secondary text-white treatment-select-item" required>
                                <option value="" selected disabled>-- Pilih Layanan --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-name="{{ $service->name }}">
                                        {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small text-white-50 text-uppercase fw-bold m-1" style="letter-spacing: 1px;">Foto Sepatu (Opsional)</label>
                            <input type="file" name="items[${index}][photo]" class="form-control bg-dark border-secondary text-white py-1" accept="image/*" style="font-size: 0.85rem;">
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', itemHTML);
            
            // Add listeners to new elements
            const newBlock = container.lastElementChild;
            const materialSelect = newBlock.querySelector('.material-select');
            const colorInput = newBlock.querySelector('.color-input');
            const treatmentSelect = newBlock.querySelector('.treatment-select-item');
            const removeBtn = newBlock.querySelector('.remove-item');

            [materialSelect, colorInput].forEach(el => {
                el.addEventListener('change', () => applyPremiumLogic(newBlock));
                el.addEventListener('input', () => applyPremiumLogic(newBlock));
            });

            treatmentSelect.addEventListener('change', calculateTotal);
        }

        // Add event delegation for removal
        document.getElementById('items_container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const block = e.target.closest('.item-block');
                block.remove();
                calculateTotal();
            }
        });

        function applyPremiumLogic(block) {
            const material = block.querySelector('.material-select').value;
            const color = block.querySelector('.color-input').value.toLowerCase();
            const treatmentSelect = block.querySelector('.treatment-select-item');
            
            const premiumMaterials = ['Kulit', 'Suede', 'Nubuck'];
            const isWhite = color.includes('putih') || color.includes('white');
            
            if (premiumMaterials.includes(material) || isWhite) {
                // Find "PREMIUM TREATMENT" option
                for (let i = 0; i < treatmentSelect.options.length; i++) {
                    const opt = treatmentSelect.options[i];
                    if (opt.getAttribute('data-name') === 'PREMIUM TREATMENT') {
                        treatmentSelect.value = opt.value;
                        break;
                    }
                }
            }
            calculateTotal();
        }

        function getDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function calculateTotal() {
            console.log('calculateTotal triggered');
            let treatmentTotal = 0;
            let serviceFee = 0;
            let distance = 0;

            const itemsContainer = document.getElementById('items_container');
            const displayTotal = document.getElementById('display_total');
            const locationFields = document.getElementById('location_fields');
            const latInput = document.getElementById('lat_input');
            const lngInput = document.getElementById('lng_input');
            const distanceInfo = document.getElementById('distance_info');
            const distanceVal = document.getElementById('distance_val');
            const feeVal = document.getElementById('fee_val');

            const allTreatments = document.querySelectorAll('.treatment-select-item');
            console.log('Found treatments:', allTreatments.length);
            allTreatments.forEach(sel => {
                const opt = sel.options[sel.selectedIndex];
                if (opt && opt.value !== "") {
                    treatmentTotal += parseInt(opt.getAttribute('data-price')) || 0;
                }
            });

            const methodRadio = document.querySelector('input[name="service_method"]:checked');
            const selectedMethod = methodRadio ? methodRadio.value : 'datang_langsung';
            const isPickup = selectedMethod === 'pickup_delivery';
            console.log('Service Method:', selectedMethod, 'isPickup:', isPickup);
            
            if (locationFields) locationFields.classList.toggle('d-none', !isPickup);

            if (isPickup) {
                console.log('Handling pickup logic, map exists?', !!map);
                if (!map) {
                    initMap();
                }
                
                if (latInput && latInput.value && lngInput && lngInput.value) {
                    distance = getDistance(STORE_LAT, STORE_LNG, parseFloat(latInput.value), parseFloat(lngInput.value));
                    if (distance >= 5) {
                        serviceFee = Math.round(distance * 10000);
                    }
                    if (distanceInfo) {
                        distanceInfo.classList.remove('d-none');
                        distanceVal.textContent = distance.toFixed(2);
                        feeVal.textContent = 'Rp ' + serviceFee.toLocaleString('id-ID');
                    }
                    console.log('Distance calculated:', distance, 'fee:', serviceFee);
                }
            } else {
                if (distanceInfo) distanceInfo.classList.add('d-none');
            }

            const total = treatmentTotal + serviceFee;
            console.log('Total Price:', total);
            if (displayTotal) displayTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function initMap() {
            console.log('initMap called');
            if (typeof L === 'undefined') {
                console.error('Leaflet (L) is not defined! Check if leaflet.js is loaded.');
                return;
            }
            if (map) {
                console.log('Map already initialized');
                return;
            }

            const latInput = document.getElementById('lat_input');
            const lngInput = document.getElementById('lng_input');
            const mapContainer = document.getElementById('map');
            
            if (!latInput || !lngInput || !mapContainer) {
                console.error('Missing map elements:', {latInput, lngInput, mapContainer});
                return;
            }

            console.log('Initializing map at:', STORE_LAT, STORE_LNG);

            // Check if lat/lng old values exist, if not use store loc or geo
            let startLat = latInput.value || STORE_LAT;
            let startLng = lngInput.value || STORE_LNG;

            try {
                map = L.map('map').setView([startLat, startLng], 13);
                console.log('L.map created');

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                console.log('Tile layer added');

                marker = L.marker([startLat, startLng], {draggable: true}).addTo(map);
                console.log('Marker added');

                marker.on('dragend', function(e) {
                    const pos = marker.getLatLng();
                    latInput.value = pos.lat;
                    lngInput.value = pos.lng;
                    console.log('Marker dragged to:', pos.lat, pos.lng);
                    calculateTotal();
                });

                map.on('click', function(e) {
                    console.log('Map clicked at:', e.latlng);
                    marker.setLatLng(e.latlng);
                    latInput.value = e.latlng.lat;
                    lngInput.value = e.latlng.lng;
                    calculateTotal();
                });

                // Get current location if no old value
                if (!latInput.value && navigator.geolocation) {
                    console.log('Requesting geolocation...');
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const curLat = position.coords.latitude;
                        const curLng = position.coords.longitude;
                        console.log('Geolocation success:', curLat, curLng);
                        if (marker && map) {
                            marker.setLatLng([curLat, curLng]);
                            map.setView([curLat, curLng], 13);
                            latInput.value = curLat;
                            lngInput.value = curLng;
                            calculateTotal();
                        }
                    }, function(err) {
                        console.warn('Geolocation error:', err);
                    });
                }
            } catch (e) {
                console.error('Error during Leaflet initialization:', e);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const methodRadios = document.querySelectorAll('input[name="service_method"]');

            // Initialization logic
            document.getElementById('add_item_btn').addEventListener('click', addItem);
            
            // Reset and add first item on load if empty
            if (document.getElementById('items_container').children.length === 0) {
                addItem();
            } else {
                // Attach listeners to existing (first) item if it exists from Blade
                itemCount = 1; 
                const firstBlock = document.querySelector('.item-block');
                firstBlock.querySelector('.material-select').addEventListener('change', () => applyPremiumLogic(firstBlock));
                firstBlock.querySelector('.color-input').addEventListener('input', () => applyPremiumLogic(firstBlock));
                firstBlock.querySelector('.treatment-select-item').addEventListener('change', calculateTotal);
            }



            methodRadios.forEach(radio => radio.addEventListener('change', calculateTotal));

            // Fix for map not loading in hidden modal
            document.getElementById('orderModal').addEventListener('shown.bs.modal', function() {
                if (map) {
                    setTimeout(() => {
                        map.invalidateSize();
                    }, 200);
                }
            });

            calculateTotal();
            
            @if($errors->any() || old('service_method'))
                const myModal = new bootstrap.Modal(document.getElementById('orderModal'));
                myModal.show();
            @endif
            calculateTotal();
        });

        function openPaymentModal(orderId, total) {
            const form = document.getElementById('paymentForm');
            const totalDisplay = document.getElementById('payment_total_display');
            
            form.action = `/customer/orders/${orderId}/payment`;
            totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
            
            const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            paymentModal.show();
        }
    </script>
@endsection
