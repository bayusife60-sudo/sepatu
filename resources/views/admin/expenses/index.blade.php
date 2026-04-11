@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-7">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Data Pengeluaran</h2>
            <p class="text-muted mt-1 mb-0" style="font-weight: 300;">Manajemen data pengeluaran operasional toko.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end align-items-center gap-2">
            @if(auth()->user()->role == 'owner')
            <button type="button" class="btn btn-outline-info d-flex align-items-center" style="padding: 0.6rem 1.2rem; white-space: nowrap;" data-bs-toggle="modal" data-bs-target="#reportModal">
                <i class="fas fa-file-invoice-dollar me-2"></i>Laporan Laba Rugi
            </button>
            @endif
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary d-flex align-items-center" style="padding: 0.6rem 1.5rem; white-space: nowrap;">
                <i class="fas fa-plus me-2"></i>Catat Pengeluaran
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
    
    @if(session('error'))
    <div class="alert alert-dismissible fade show mb-4" role="alert"
         style="background-color: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; border-radius: 8px;">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Stats Cards --}}
    @php
        $totalExpenses = \App\Models\Expense::sum('amount');
        $thisMonth = \App\Models\Expense::whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('amount');
        $lastMonth = \App\Models\Expense::whereMonth('date', date('m', strtotime('-1 month')))->whereYear('date', date('Y', strtotime('-1 month')))->sum('amount');
        $trend = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100" style="background: rgba(239,68,68,0.1); border-left: 3px solid #ef4444;">
                <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
                    <div style="width: 45px; height: 45px; border-radius: 12px; background: rgba(239,68,68,0.2); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-wallet" style="font-size: 1.4rem; color: #ef4444;"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 0.5px;">Bulan Ini</div>
                        <div class="text-white fw-bold" style="font-size: 1.4rem; line-height: 1;">Rp {{ number_format($thisMonth, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100" style="background: rgba(59,130,246,0.1); border-left: 3px solid #3b82f6;">
                <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
                    <div style="width: 45px; height: 45px; border-radius: 12px; background: rgba(59,130,246,0.2); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chart-line" style="font-size: 1.4rem; color: #3b82f6;"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 0.5px;">Tren vs Bulan Lalu</div>
                        <div class="d-flex align-items-center gap-2">
                            @if($trend > 0)
                                <span class="text-danger fw-bold"><i class="fas fa-arrow-up me-1"></i>{{ number_format($trend, 1) }}%</span>
                            @elseif($trend < 0)
                                <span class="text-success fw-bold"><i class="fas fa-arrow-down me-1"></i>{{ number_format(abs($trend), 1) }}%</span>
                            @else
                                <span class="text-muted fw-bold"><i class="fas fa-minus me-1"></i>0%</span>
                            @endif
                            <span class="text-muted small">(Rp {{ number_format($lastMonth, 0, ',', '.') }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100" style="background: rgba(168,85,247,0.1); border-left: 3px solid #a855f7;">
                <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
                    <div style="width: 45px; height: 45px; border-radius: 12px; background: rgba(168,85,247,0.2); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-money-bill-wave" style="font-size: 1.4rem; color: #a855f7;"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 0.5px;">Total Keseluruhan</div>
                        <div class="text-white fw-bold" style="font-size: 1.4rem; line-height: 1;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="card mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.expenses.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Bulan & Tahun</label>
                    <input type="month" name="month" value="{{ request('month') }}" class="form-control"
                           style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Kategori</label>
                    <select name="category_id" class="form-select"
                            style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Cari Deskripsi</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Kata kunci..."
                           style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill" style="padding: 0.6rem;">
                        <i class="fas fa-filter"></i>
                    </button>
                    @if(request('search') || request('category_id') || request('month'))
                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-custom" style="padding: 0.6rem 0.8rem;" title="Reset Filter">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Pengeluaran --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0" style="font-size: 1rem; font-weight: 600; font-family: 'Jost', sans-serif;">
                Riwayat Pengeluaran
                <span class="badge ms-2 rounded-pill" style="background-color: rgba(244,114,182,0.2); color: var(--primary); font-size: 0.75rem;">
                    {{ $expenses->total() }}
                </span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.08);">
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Tanggal</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Kategori</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Deskripsi</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Metode Bayar</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Nominal</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase text-end" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.2s;">
                            <td class="py-3 px-4 align-middle">
                                <span class="d-block text-white" style="font-size: 0.9rem;">{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</span>
                                <span class="text-muted" style="font-size: 0.78rem;">Oleh: {{ $expense->user->name ?? 'Admin' }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="badge bg-secondary rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 500;">
                                    {{ $expense->category->name ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="text-white d-inline-block text-truncate" style="font-size: 0.9rem; max-width: 280px;" title="{{ $expense->description }}">
                                    {{ $expense->description }}
                                </span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="text-muted small text-uppercase">{{ $expense->payment_method }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="text-danger fw-bold" style="font-size: 1rem;">- Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle text-end">
                                <a href="{{ route('admin.expenses.edit', $expense) }}"
                                   class="btn btn-sm btn-outline-info rounded-circle me-1"
                                   title="Edit" style="width: 32px; height: 32px; padding: 0; line-height: 30px; text-align: center;">
                                    <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                </a>
                                <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Yakin ingin menghapus catatan pengeluaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"
                                            title="Hapus" style="width: 32px; height: 32px; padding: 0; line-height: 30px; text-align: center;">
                                        <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted">
                                <i class="fas fa-receipt fa-3x mb-3 d-block" style="opacity: 0.15;"></i>
                                <p class="mb-2">Belum ada catatan pengeluaran.</p>
                                <a href="{{ route('admin.expenses.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>Catat Pengeluaran
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($expenses->hasPages())
        <div class="card-footer py-3 px-4" style="background-color: transparent; border-top: 1px solid rgba(255,255,255,0.05);">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Menampilkan {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} dari {{ $expenses->total() }} data
                </span>
                {{ $expenses->links() }}
            </div>
        </div>
        @endif
    </div>

</div>



@if(auth()->user()->role == 'owner')
{{-- Modal Laporan Laba Rugi --}}
<div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="background-color: #1a1a1a; border-radius: 16px;">
            <div class="modal-header border-bottom border-white-5">
                <h5 class="modal-title text-white fw-bold">Cetak Laporan Keuangan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reportForm" action="{{ route('admin.reports.profitLoss') }}" method="GET" target="_blank">
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Pilih rentang tanggal untuk menghitung pendapatan (Order lunas) vs semua pengeluaran dalam periode tersebut.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold text-uppercase">Tanggal Mulai</label>
                            <input type="date" name="start_date" required class="form-control" 
                                   style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: white;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold text-uppercase">Tanggal Akhir</label>
                            <input type="date" name="end_date" required class="form-control" 
                                   style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: white;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-white-5 p-4">
                    <button type="button" class="btn btn-outline-secondary px-4 text-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-print me-2"></i>Generate Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .form-control::placeholder { color: #555; }
    .form-control:focus, .form-select:focus {
        background-color: #2a2a2a !important;
        border-color: rgba(244, 114, 182, 0.5) !important;
        color: #e0e0e0 !important;
        box-shadow: 0 0 0 0.2rem rgba(244, 114, 182, 0.1) !important;
    }
    .form-select option { background-color: #2a2a2a; }
    
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
