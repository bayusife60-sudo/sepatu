@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 800px;">

    {{-- Page Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Catat Pengeluaran</h2>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-custom" style="padding: 0.55rem 1.2rem;">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0" style="font-weight: 600; font-family: 'Jost', sans-serif; letter-spacing: 0.5px;">
                <i class="fas fa-wallet me-2" style="color: var(--primary);"></i>Form Data Pengeluaran
            </h6>
        </div>
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.expenses.store') }}" method="POST">
                @csrf
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="date" class="form-label text-muted small text-uppercase fw-bold mb-2">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" 
                               value="{{ old('date', date('Y-m-d')) }}" required 
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="expense_category_id" class="form-label text-muted small text-uppercase fw-bold mb-2">Kategori <span class="text-danger">*</span></label>
                        <select name="expense_category_id" id="expense_category_id" class="form-select @error('expense_category_id') is-invalid @enderror" required
                                style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('expense_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('expense_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="amount" class="form-label text-muted small text-uppercase fw-bold mb-2">Nominal (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #a0a0a0;">Rp</span>
                        <input type="number" class="form-control fw-bold text-danger @error('amount') is-invalid @enderror" id="amount" name="amount" 
                               value="{{ old('amount') }}" placeholder="150000" min="0" required 
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); border-left: false; font-size: 1.1rem;">
                    </div>
                    @error('amount')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label text-muted small text-uppercase fw-bold mb-2">Keterangan / Rincian Belanja <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                              rows="3" placeholder="Contoh: Beli perlengkapan pembersih sepatu 2 set..." required
                              style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0;">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-3">Metode Pembayaran <span class="text-danger">*</span></label>
                    <div class="d-flex flex-wrap gap-4">
                        @foreach(['Cash', 'Transfer Bank', 'e-Wallet'] as $method)
                        <div class="form-check custom-radio">
                            <input class="form-check-input" type="radio" name="payment_method" id="method_{{ Str::slug($method) }}" 
                                   value="{{ $method }}" {{ old('payment_method', 'Cash') == $method ? 'checked' : '' }} required>
                            <label class="form-check-label px-3 py-2 rounded-3 border border-secondary text-center" for="method_{{ Str::slug($method) }}" 
                                   style="cursor: pointer; min-width: 120px; transition: all 0.2s;">
                                {{ $method }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('payment_method')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <hr style="border-color: rgba(255,255,255,0.08); margin: 2rem 0;">
                
                <div class="text-end">
                    <button type="submit" class="btn btn-danger px-4 py-2">
                        <i class="fas fa-save me-2"></i>Simpan Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        background-color: #2a2a2a !important;
        border-color: rgba(239, 68, 68, 0.5) !important;
        color: #e0e0e0 !important;
        box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.1) !important;
    }
    .form-select option { background-color: #2a2a2a; }
    
    /* Custom Radio for Payment Methods */
    .custom-radio .form-check-input {
        display: none;
    }
    .custom-radio .form-check-label {
        background-color: #2a2a2a;
        color: #a0a0a0;
    }
    .custom-radio .form-check-input:checked + .form-check-label {
        background-color: rgba(239, 68, 68, 0.1);
        border-color: #ef4444 !important;
        color: #fff;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);
    }
    .custom-radio .form-check-label:hover {
        border-color: rgba(255,255,255,0.3) !important;
    }
</style>
@endsection
