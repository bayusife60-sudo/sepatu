@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 800px;">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">{{ Auth::user()->role == 'owner' ? 'Owner Area' : 'Admin Area' }}</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Tambah Treatment</h2>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.treatments.index') }}" class="btn btn-outline-custom" style="padding: 0.55rem 1.2rem;">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0" style="font-weight: 600; font-family: 'Jost', sans-serif; letter-spacing: 0.5px;">
                <i class="fas fa-plus-circle me-2" style="color: var(--primary);"></i>Form Tambah Treatment
            </h6>
        </div>
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.treatments.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="form-label text-muted small text-uppercase fw-bold mb-2">Nama Treatment <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                           value="{{ old('name') }}" placeholder="Contoh: Fast Clean" required 
                           style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="price" class="form-label text-muted small text-uppercase fw-bold mb-2">Harga (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #a0a0a0;">Rp</span>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" 
                               value="{{ old('price') }}" placeholder="50000" min="0" required 
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-left: false;">
                    </div>
                    @error('price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label text-muted small text-uppercase fw-bold mb-2">Deskripsi Layanan</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                              rows="4" placeholder="Jelaskan apa saja yang termasuk dalam treatment ini..." 
                              style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch" style="padding-left: 2.5em;">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                               style="width: 40px; height: 20px; margin-left: -2.5em; cursor: pointer;">
                        <label class="form-check-label pt-1 ms-2" for="is_active" style="cursor: pointer; font-weight: 500;">
                            Status Aktif (Tampilkan di halaman)
                        </label>
                    </div>
                </div>

                <hr style="border-color: rgba(255,255,255,0.08); margin: 2rem 0;">
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-save me-2"></i>Simpan Treatment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        background-color: #2a2a2a !important;
        border-color: rgba(244, 114, 182, 0.5) !important;
        color: #e0e0e0 !important;
        box-shadow: 0 0 0 0.2rem rgba(244, 114, 182, 0.1) !important;
    }
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
</style>
@endsection
