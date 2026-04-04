@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-7">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Request Harga</h2>
            <p class="text-muted mt-1 mb-0" style="font-weight: 300;">Manajemen usulan perubahan harga layanan treatment.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.price-requests.create') }}" class="btn btn-primary" style="padding: 0.6rem 1.5rem;">
                <i class="fas fa-plus me-2"></i>Buat Request Baru
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

    {{-- Filter & Search --}}
    <div class="card mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.price-requests.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Status Request</label>
                    <select name="status" class="form-select"
                            style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill" style="padding: 0.6rem;">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    @if(request('status'))
                    <a href="{{ route('admin.price-requests.index') }}" class="btn btn-outline-custom" style="padding: 0.6rem 0.8rem;" title="Reset Filter">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Request Harga --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0" style="font-size: 1rem; font-weight: 600; font-family: 'Jost', sans-serif;">
                Riwayat Request
                <span class="badge ms-2 rounded-pill" style="background-color: rgba(244,114,182,0.2); color: var(--primary); font-size: 0.75rem;">
                    {{ $priceRequests->total() }}
                </span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.08);">
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Tanggal</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Admin</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Layanan</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase text-center" style="font-size: 0.75rem; letter-spacing: 1px;">Perubahan Harga</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase text-end" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($priceRequests as $req)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.2s;">
                            <td class="py-3 px-4 align-middle">
                                <span class="d-block text-white" style="font-size: 0.9rem;">{{ $req->created_at->format('d M Y') }}</span>
                                <span class="text-muted" style="font-size: 0.78rem;">{{ $req->created_at->format('H:i') }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle text-white" style="font-size: 0.9rem;">
                                {{ $req->admin->name ?? '-' }}
                            </td>
                            <td class="py-3 px-4 align-middle text-white" style="font-size: 0.9rem;">
                                {{ $req->treatment->name ?? 'Terhapus' }}
                            </td>
                            <td class="py-3 px-4 align-middle text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="text-muted text-decoration-line-through" style="font-size: 0.85rem;">Rp{{ number_format($req->old_price, 0, ',', '.') }}</span>
                                    <i class="fas fa-arrow-right text-muted" style="font-size: 0.7rem;"></i>
                                    <span class="text-white fw-medium" style="font-size: 0.9rem;">Rp{{ number_format($req->new_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-1" style="font-size: 0.75rem;">
                                    @if($req->difference > 0)
                                        <span class="text-success"><i class="fas fa-caret-up me-1"></i>Naik Rp{{ number_format($req->difference, 0, ',', '.') }}</span>
                                    @elseif($req->difference < 0)
                                        <span class="text-danger"><i class="fas fa-caret-down me-1"></i>Turun Rp{{ number_format(abs($req->difference), 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">Tidak Berubah</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 500;">Pending Menunggu ACC</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 500;">Disetujui</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 500;">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 align-middle text-end">
                                <a href="{{ route('admin.price-requests.show', $req) }}"
                                   class="btn btn-sm btn-outline-info rounded-circle me-1"
                                   title="Lihat Detail" style="width: 32px; height: 32px; padding: 0; line-height: 30px; text-align: center;">
                                    <i class="fas fa-eye" style="font-size: 0.8rem;"></i>
                                </a>
                                @if($req->status === 'pending')
                                <form action="{{ route('admin.price-requests.destroy', $req) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Batalkan pengajuan request ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"
                                            title="Batalkan" style="width: 32px; height: 32px; padding: 0; line-height: 30px; text-align: center;">
                                        <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted">
                                <i class="fas fa-tags fa-3x mb-3 d-block" style="opacity: 0.15;"></i>
                                <p class="mb-2">Belum ada data request harga.</p>
                                <a href="{{ route('admin.price-requests.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>Buat Request
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($priceRequests->hasPages())
        <div class="card-footer py-3 px-4" style="background-color: transparent; border-top: 1px solid rgba(255,255,255,0.05);">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Menampilkan {{ $priceRequests->firstItem() }}–{{ $priceRequests->lastItem() }} dari {{ $priceRequests->total() }} data
                </span>
                {{ $priceRequests->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<style>
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
