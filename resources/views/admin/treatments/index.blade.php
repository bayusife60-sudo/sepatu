@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-7">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Layanan Treatment</h2>
            <p class="text-muted mt-1 mb-0" style="font-weight: 300;">Manajemen jenis treatment dan layanannya.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.treatments.create') }}" class="btn btn-primary" style="padding: 0.6rem 1.5rem;">
                <i class="fas fa-plus me-2"></i>Tambah Treatment
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
            <form method="GET" action="{{ route('admin.treatments.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Cari Treatment</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #a0a0a0;">
                            <i class="fas fa-search" style="font-size: 0.85rem;"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                               placeholder="Nama treatment atau deksripsi..."
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-left: none;">
                    </div>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill" style="padding: 0.6rem;">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.treatments.index') }}" class="btn btn-outline-custom" style="padding: 0.6rem 0.8rem;" title="Reset">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Treatment --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0" style="font-size: 1rem; font-weight: 600; font-family: 'Jost', sans-serif;">
                Daftar Treatment
                <span class="badge ms-2 rounded-pill" style="background-color: rgba(244,114,182,0.2); color: var(--primary); font-size: 0.75rem;">
                    {{ $treatments->total() }}
                </span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.08);">
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px; width: 5%;">#</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px; width: 25%;">Nama Treatment</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px; width: 15%;">Harga</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px; width: 30%;">Deskripsi</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px; width: 10%;">Status</th>
                            <th class="py-3 px-4 fw-medium text-muted text-uppercase text-end" style="font-size: 0.75rem; letter-spacing: 1px; width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as $treatment)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.2s;">
                            <td class="py-3 px-4 align-middle text-muted">{{ $loop->iteration + $treatments->firstItem() - 1 }}</td>
                            <td class="py-3 px-4 align-middle">
                                <span class="fw-medium text-white" style="font-size: 0.95rem;">{{ $treatment->name }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="text-primary fw-medium" style="font-size: 0.9rem;">Rp {{ number_format($treatment->price, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                <span class="text-muted small text-truncate d-inline-block" style="max-width: 250px;">{{ $treatment->description ?: '-' }}</span>
                            </td>
                            <td class="py-3 px-4 align-middle">
                                @if($treatment->is_active)
                                    <span class="badge bg-success rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 500;">Aktif</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 500;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 align-middle text-end">
                                <a href="{{ route('admin.treatments.edit', $treatment) }}"
                                   class="btn btn-sm btn-outline-info rounded-circle me-1"
                                   title="Edit" style="width: 32px; height: 32px; padding: 0; line-height: 30px; text-align: center;">
                                    <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                </a>
                                <form action="{{ route('admin.treatments.destroy', $treatment) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Yakin ingin menghapus treatment ini?');">
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
                                <i class="fas fa-spray-can fa-3x mb-3 d-block" style="opacity: 0.15;"></i>
                                <p class="mb-2">Belum ada data treatment.</p>
                                <a href="{{ route('admin.treatments.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>Tambah Treatment
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($treatments->hasPages())
        <div class="card-footer py-3 px-4" style="background-color: transparent; border-top: 1px solid rgba(255,255,255,0.05);">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Menampilkan {{ $treatments->firstItem() }}–{{ $treatments->lastItem() }} dari {{ $treatments->total() }} treatment
                </span>
                {{ $treatments->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<style>
    .form-control::placeholder { color: #555; }
    .form-control:focus {
        background-color: #2a2a2a !important;
        border-color: rgba(244, 114, 182, 0.5) !important;
        color: #e0e0e0 !important;
        box-shadow: 0 0 0 0.2rem rgba(244, 114, 182, 0.1) !important;
    }
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
