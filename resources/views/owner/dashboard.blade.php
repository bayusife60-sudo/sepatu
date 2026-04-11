@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">
    <!-- Page Header -->
    <div class="row mb-5 align-items-end">
        <div class="col-md-8">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">{{ Auth::user()->role == 'owner' ? 'Owner Area' : 'Admin Area' }}</span>
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

    <!-- Financial/Work Volume Chart -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(145deg, #1a1a1a 0%, #111111 100%);">
                <div class="card-header border-bottom border-white-5 bg-transparent py-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1" style="font-size: 1.25rem; font-weight: 600;">{{ Auth::user()->role == 'owner' ? 'Performa Keuangan' : 'Volume Pekerjaan' }}</h4>
                        <p class="text-muted small mb-0">{{ Auth::user()->role == 'owner' ? 'Perbandingan Pendapatan vs Pengeluaran' : 'Statistik Jumlah Sepatu Masuk' }}</p>
                    </div>
                    <div class="btn-group" role="group" style="background: rgba(255,255,255,0.05); padding: 4px; border-radius: 12px;">
                        <button type="button" class="btn btn-sm px-3 chart-toggle active" id="toggleDaily" onclick="updateChart('daily')">Harian</button>
                        <button type="button" class="btn btn-sm px-3 chart-toggle" id="toggleMonthly" onclick="updateChart('monthly')">Bulanan</button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="financialChart" style="min-height: 380px;"></div>
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
                                        'Antrian' => 'bg-secondary',
                                        'Menunggu Konfirmasi' => 'bg-info text-dark',
                                        'Diterima Toko' => 'bg-primary',
                                        'Dikerjakan' => 'bg-warning text-dark',
                                        'Siap Diambil' => 'bg-success',
                                        'Siap Dikirim' => 'bg-success',
                                        'Selesai' => 'bg-success',
                                        'Dibatalkan' => 'bg-danger'
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

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const chartData = @json($chartData);
    let chart;

    const options = {
        series: [
            @if(Auth::user()->role == 'owner')
            {
                name: 'Pendapatan',
                type: 'area',
                data: chartData.daily.revenue
            }, {
                name: 'Pengeluaran',
                type: 'area',
                data: chartData.daily.expenses
            },
            @endif
            {
                name: 'Sepatu Masuk',
                type: 'column',
                data: chartData.daily.shoes
            }
        ],
        chart: {
            height: 380,
            type: 'line', // Mixed chart
            stacked: false,
            toolbar: { show: false },
            zoom: { enabled: false },
            background: 'transparent',
            foreColor: '#a0a0a0'
        },
        colors: [
            @if(Auth::user()->role == 'owner')
            '#f472b6', '#3b82f6', 
            @endif
            '#fb923c'
        ],
        dataLabels: { enabled: false },
        stroke: { 
            curve: 'smooth', 
            width: [
                @if(Auth::user()->role == 'owner')
                3, 3, 0
                @else
                0
                @endif
            ] 
        },
        fill: {
            type: [
                @if(Auth::user()->role == 'owner')
                'gradient', 'gradient', 'solid'
                @else
                'solid'
                @endif
            ],
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100, 100, 100]
            }
        },
        xaxis: {
            categories: chartData.daily.labels,
            type: 'category',
            axisBorder: { show: false },
            axisTicks: { show: false },
            tooltip: { enabled: false }
        },
        yaxis: [
            @if(Auth::user()->role == 'owner')
            {
                // Left Axis (Revenue/Expenses)
                seriesName: 'Pendapatan',
                axisTicks: { show: false },
                axisBorder: { show: false },
                labels: {
                    style: { colors: '#f472b6' },
                    formatter: function (val) {
                        return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                },
                title: { text: "Rupiah", style: { color: '#f472b6' } }
            },
            {
                // Proxy for Expenses
                show: false,
                seriesName: 'Pengeluaran',
            },
            @endif
            {
                // Right/Main Axis (Shoes)
                opposite: {{ Auth::user()->role == 'owner' ? 'true' : 'false' }},
                seriesName: 'Sepatu Masuk',
                axisTicks: { show: false },
                axisBorder: { show: false },
                labels: {
                    style: { colors: '#fb923c' },
                    formatter: function (val) { return Math.floor(val) + " Pasang"; }
                },
                title: { text: "Jumlah Sepatu", style: { color: '#fb923c' } }
            }
        ],
        grid: {
            borderColor: 'rgba(255,255,255,0.05)',
            strokeDashArray: 4
        },
        tooltip: {
            theme: 'dark',
            shared: true,
            intersect: false,
            y: {
                formatter: function (val, { seriesIndex }) {
                    if (seriesIndex === ({{ Auth::user()->role == 'owner' ? '2' : '0' }})) return val + " Pasang";
                    return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            offsetY: -10,
            itemMargin: { horizontal: 15 }
        },
        plotOptions: {
            bar: {
                columnWidth: '35%',
                borderRadius: 4
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        chart = new ApexCharts(document.querySelector("#financialChart"), options);
        chart.render();
    });

    function updateChart(type) {
        document.querySelectorAll('.chart-toggle').forEach(el => el.classList.remove('active'));
        const labels = (type === 'daily') ? chartData.daily.labels : chartData.monthly.labels;
        const revenue = (type === 'daily') ? chartData.daily.revenue : chartData.monthly.revenue;
        const expenses = (type === 'daily') ? chartData.daily.expenses : chartData.monthly.expenses;
        const shoes = (type === 'daily') ? chartData.daily.shoes : chartData.monthly.shoes;

        if (type === 'daily') {
            document.getElementById('toggleDaily').classList.add('active');
        } else {
            document.getElementById('toggleMonthly').classList.add('active');
        }

        chart.updateOptions({
            xaxis: { categories: labels }
        });
        
        chart.updateSeries([
            @if(Auth::user()->role == 'owner')
            {
                name: 'Pendapatan',
                type: 'area',
                data: revenue
            }, {
                name: 'Pengeluaran',
                type: 'area',
                data: expenses
            },
            @endif
            {
                name: 'Sepatu Masuk',
                type: 'column',
                data: shoes
            }
        ]);
    }
</script>

<style>
    .chart-toggle {
        border: none;
        background: transparent;
        color: #888;
        border-radius: 8px !important;
        font-weight: 500;
        transition: all 0.2s;
    }
    .chart-toggle.active {
        background: var(--primary) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(244, 114, 182, 0.2);
    }
    .chart-toggle:hover:not(.active) {
        background: rgba(255,255,255,0.05);
        color: #fff;
    }
</style>
@endsection
