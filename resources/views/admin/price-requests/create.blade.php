@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 800px;">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Buat Request Harga</h2>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.price-requests.index') }}" class="btn btn-outline-custom" style="padding: 0.55rem 1.2rem;">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0" style="font-weight: 600; font-family: 'Jost', sans-serif; letter-spacing: 0.5px;">
                <i class="fas fa-plus-circle me-2" style="color: var(--primary);"></i>Form Pengajuan Perubahan Harga
            </h6>
        </div>
        <div class="card-body p-4 p-md-5">
            <div class="alert alert-info mb-4" style="background-color: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.2); color: #93c5fd;">
                <i class="fas fa-info-circle me-2"></i> Pengajuan ini akan diteruskan ke Owner toko untuk diproses (Disetujui/Ditolak).
            </div>

            <form action="{{ route('admin.price-requests.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="treatment_id" class="form-label text-muted small text-uppercase fw-bold mb-2">Layanan Treatment <span class="text-danger">*</span></label>
                    <select name="treatment_id" id="treatment_id" class="form-select @error('treatment_id') is-invalid @enderror" required
                            style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($treatments as $treatment)
                        <option value="{{ $treatment->id }}" data-price="{{ $treatment->price }}" {{ old('treatment_id') == $treatment->id ? 'selected' : '' }}>
                            {{ $treatment->name }} (Saat ini: Rp{{ number_format($treatment->price, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                    @error('treatment_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="new_price" class="form-label text-muted small text-uppercase fw-bold mb-2">Usulan Harga Baru (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #a0a0a0;">Rp</span>
                        <input type="number" class="form-control fw-bold text-success @error('new_price') is-invalid @enderror" id="new_price" name="new_price" 
                               value="{{ old('new_price') }}" placeholder="Contoh: 75000" min="0" required 
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); border-left: false; font-size: 1.1rem;">
                    </div>
                    @error('new_price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="reason" class="form-label text-muted small text-uppercase fw-bold mb-2">Alasan Perubahan <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" 
                              rows="4" placeholder="Jelaskan secara detail mengapa harga perlu diubah..." required
                              style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr style="border-color: rgba(255,255,255,0.08); margin: 2rem 0;">
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Request
                    </button>
                </div>
            </form>
        </div>
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
</style>
@endsection
