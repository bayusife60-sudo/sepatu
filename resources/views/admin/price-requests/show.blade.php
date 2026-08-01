@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 800px;">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">{{ Auth::user()->role == 'owner' ? 'Owner Area' : 'Admin Area' }}</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Detail Request Harga</h2>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.price-requests.index') }}" class="btn btn-outline-custom" style="padding: 0.55rem 1.2rem;">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="card mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0" style="font-weight: 600; font-family: 'Jost', sans-serif; letter-spacing: 0.5px;">
                <i class="fas fa-file-invoice me-2" style="color: var(--primary);"></i>Informasi Pengajuan
            </h6>
            @if($priceRequest->status === 'pending')
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i>Menunggu ACC</span>
            @elseif($priceRequest->status === 'approved')
                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i>Disetujui</span>
            @else
                <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="fas fa-times-circle me-1"></i>Ditolak</span>
            @endif
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <p class="text-muted small text-uppercase fw-bold mb-1">Diajukan Oleh</p>
                    <p class="text-white fw-medium mb-0">{{ $priceRequest->admin->name ?? 'Admin Terhapus' }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small text-uppercase fw-bold mb-1">Tanggal Pengajuan</p>
                    <p class="text-white fw-medium mb-0">{{ $priceRequest->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-muted small text-uppercase fw-bold mb-1">Layanan Treatment</p>
                <div class="p-3 rounded" style="background-color: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                    <h5 class="text-primary mb-0">{{ $priceRequest->treatment->name ?? 'Layanan Telah Dihapus' }}</h5>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <p class="text-muted small text-uppercase fw-bold mb-1">Harga Lama</p>
                    <p class="text-white" style="font-size: 1.2rem;">Rp {{ number_format($priceRequest->old_price, 0, ',', '.') }}</p>
                </div>
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <p class="text-muted small text-uppercase fw-bold mb-1">Usulan Baru</p>
                    <p class="text-success fw-bold" style="font-size: 1.2rem;">Rp {{ number_format($priceRequest->new_price, 0, ',', '.') }}</p>
                </div>
                <div class="col-sm-4">
                    <p class="text-muted small text-uppercase fw-bold mb-1">Perbedaan</p>
                    @if($priceRequest->difference > 0)
                        <p class="text-danger fw-bold" style="font-size: 1.2rem;"><i class="fas fa-caret-up me-1"></i>Rp {{ number_format($priceRequest->difference, 0, ',', '.') }}</p>
                    @else
                        <p class="text-success fw-bold" style="font-size: 1.2rem;"><i class="fas fa-caret-down me-1"></i>Rp {{ number_format(abs($priceRequest->difference), 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>

            <div class="mb-0">
                <p class="text-muted small text-uppercase fw-bold mb-1">Alasan Pengajuan</p>
                <div class="p-3 rounded" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.05);">
                    <p class="text-white mb-0" style="white-space: pre-line;">{{ $priceRequest->reason }}</p>
                </div>
            </div>
            
            @if($priceRequest->status === 'rejected' && $priceRequest->rejection_note)
            <div class="mt-4 p-3 rounded" style="background-color: rgba(239,68,68,0.1); border-left: 4px solid #ef4444;">
                <p class="text-danger small text-uppercase fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Catatan Penolakan Owner</p>
                <p class="text-white mb-0" style="white-space: pre-line;">{{ $priceRequest->rejection_note }}</p>
            </div>
            @endif
        </div>
        
        @if($priceRequest->status === 'pending')
        <div class="card-footer py-3 d-flex justify-content-between align-items-center" style="border-top: 1px solid rgba(255,255,255,0.05); background-color: transparent;">
            
            @if(auth()->user()->role === 'admin')
            <form action="{{ route('admin.price-requests.destroy', $priceRequest) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Anda yakin ingin membatalkan pengajuan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-times me-2"></i>Batalkan Request
                </button>
            </form>
            @else
            <div></div>
            @endif

            @if(auth()->user()->role === 'owner')
            <div>
                <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times me-2"></i>Tolak
                </button>
                <form action="{{ route('admin.price-requests.approve', $priceRequest) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Anda yakin menyetujui perubahan harga ini? Harga treatment akan langsung diperbarui.');">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i>Setujui Perubahan
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

@if(auth()->user()->role === 'owner' && $priceRequest->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #2a2a2a; color: #fff; border: 1px solid rgba(255,255,255,0.1);">
      <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
        <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.price-requests.reject', $priceRequest) }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label for="rejection_note" class="form-label text-muted small text-uppercase fw-bold">Catatan Untuk Admin</label>
                <textarea class="form-control bg-dark text-white border-secondary" id="rejection_note" name="rejection_note" rows="3" required placeholder="Tulis alasan penolakan..."></textarea>
            </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05);">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-danger">Tolak Request</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endsection
