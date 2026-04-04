@extends('layouts.customer')

@section('title', 'Dashboard Saya')

@section('content')

    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-5">
        <div>
            <span style="color: var(--primary); text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; font-weight: 500;">Pelanggan</span>
            <h2 class="mb-1 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #fff;">Halo, {{ auth()->user()->name ?? 'Pelanggan' }}! 👋</h2>
            <p class="text-muted mb-0" style="font-size: 0.9rem; font-weight: 300;">Pantau pesanan dan riwayat layanan Anda di sini.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(244,114,182,0.15); color: var(--primary); font-size: 0.78rem; letter-spacing: 0.5px;">
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
                <div class="stat-number">{{ $totalOrders }}</div>
                <div class="stat-label">Total Order</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(59,130,246,0.1);">
                        <i class="fas fa-spinner" style="color: #60a5fa;"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $activeOrders }}</div>
                <div class="stat-label">Sedang Diproses</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(34,197,94,0.1);">
                        <i class="fas fa-check-circle" style="color: #4ade80;"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $completedOrders }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(251,191,36,0.1);">
                        <i class="fas fa-clock" style="color: #fbbf24;"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $unpaidOrders }}</div>
                <div class="stat-label">Belum Lunas</div>
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
                <div class="card h-100 p-3 border-0 service-card-hover" style="background-color: var(--bg-card); border: 1px solid rgba(255,255,255,0.05) !important; transition: all 0.3s ease;">
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
                    <p class="text-muted mb-0">Belum ada data layanan tersedia.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    <style>
        .service-card-hover:hover {
            transform: translateY(-5px);
            border-color: rgba(244,114,182,0.4) !important;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
    </style>
    @endif

    {{-- Order History Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="font-size: 0.95rem; font-weight: 600; letter-spacing: 0.5px;">
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
                <h6 style="color: rgba(255,255,255,0.4); font-weight: 400;">Belum Ada Pesanan</h6>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Anda belum memiliki riwayat pesanan apapun.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
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
                                'pending' => ['label' => 'Menunggu', 'class' => 'bg-secondary'],
                                'pickup_scheduled' => ['label' => 'Pickup Dijadwalkan', 'class' => 'bg-info text-dark'],
                                'picked_up' => ['label' => 'Sudah Dijemput', 'class' => 'bg-info text-dark'],
                                'in_queue' => ['label' => 'Antrian', 'class' => 'bg-warning text-dark'],
                                'cleaning_in_progress' => ['label' => 'Sedang Dicuci', 'class' => 'bg-primary'],
                                'quality_check' => ['label' => 'Quality Check', 'class' => 'bg-primary'],
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
                                <span class="d-block" style="font-size: 0.88rem; color: #fff;">{{ $order->shoe_brand }}</span>
                                <span class="text-muted" style="font-size: 0.75rem;">{{ $order->shoe_type }}</span>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem;">{{ $order->treatment->name ?? '-' }}</span>
                            </td>
                            <td>
                                @if($order->service_method === 'datang_langsung')
                                    <span class="badge bg-secondary rounded-pill px-2" style="font-size: 0.7rem;"><i class="fas fa-store me-1"></i>Toko</span>
                                @elseif($order->service_method === 'pickup')
                                    <span class="badge bg-info text-dark rounded-pill px-2" style="font-size: 0.7rem;"><i class="fas fa-box-open me-1"></i>Pickup</span>
                                @elseif($order->service_method === 'delivery')
                                    <span class="badge bg-primary rounded-pill px-2" style="font-size: 0.7rem;"><i class="fas fa-truck me-1"></i>Delivery</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fw-medium" style="font-size: 0.85rem;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </td>
                            <td class="text-center">
                                @if($order->payment_status === 'lunas')
                                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.68rem;">Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size: 0.68rem;">Belum</span>
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
