@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 1000px;">

    {{-- Page Header --}}
    <div class="row mb-5 align-items-end">
        <div class="col-md-8">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Buat Order Baru</h2>
            <p class="text-white-50 mt-2 mb-0" style="font-weight: 300;">Manajemen input order multi-item dengan kalkulasi otomatis.</p>
        </div>
        <div class="col-md-4 text-md-end mt-4 mt-md-0">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-custom" style="padding: 0.6rem 1.5rem; border-radius: 12px;">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    @if($errors->any() || session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert"
         style="background: rgba(220, 38, 38, 0.1); color: #fca5a5; border-left: 4px solid #ef4444 !important; border-radius: 12px;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
            <div>
                <strong>Oops!</strong> {{ session('error') ?: 'Terdapat beberapa kesalahan input:' }}
                @if($errors->any())
                <ul class="mb-0 mt-1 small">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
                @endif
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
        @csrf

        <div class="row g-4">
            {{-- KIRI: Pelanggan & Items --}}
            <div class="col-lg-8">
                
                {{-- Data Pelanggan --}}
                <div class="card mb-4 border-0 shadow-sm" style="background: rgba(255,255,255,0.03); border-radius: 16px;">
                    <div class="card-header bg-transparent border-white-5 py-3 px-4">
                        <h5 class="mb-0 fs-6 fw-bold text-white d-flex align-items-center gap-2">
                            <i class="fas fa-user-circle text-primary"></i> Informasi Pelanggan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Pilih Akun</label>
                                <select name="customer_id" id="customer_id" class="form-select select2-dark" required>
                                    <option value="">-- Cari Pelanggan --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                                data-name="{{ $customer->name }}"
                                                data-phone="{{ $customer->phone }}"
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Nama Display</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control" 
                                       placeholder="Nama pada nota..." value="{{ old('customer_name') }}">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label text-muted small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Nomor WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-white-10 text-white-50"><i class="fab fa-whatsapp"></i></span>
                                    <input type="text" name="customer_phone" id="customer_phone" class="form-control" 
                                           placeholder="081234567..." value="{{ old('customer_phone') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar Sepatu (REPEATER) --}}
                <div class="card border-0 shadow-sm" style="background: rgba(255,255,255,0.03); border-radius: 16px;">
                    <div class="card-header bg-transparent border-white-5 py-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fs-6 fw-bold text-white d-flex align-items-center gap-2">
                            <i class="fas fa-shoe-prints text-primary"></i> Daftar Sepatu
                        </h5>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="addItemBtn">
                            <i class="fas fa-plus me-1"></i> Tambah Sepatu
                        </button>
                    </div>
                    <div class="card-body p-0" id="itemContainer">
                        {{-- Items will be injected here --}}
                        @php $oldItems = old('items', [[]]); @endphp
                        @foreach($oldItems as $index => $oldItem)
                        <div class="item-row p-4 border-bottom border-white-5 position-relative">
                            @if($index > 0)
                            <button type="button" class="btn-remove-item btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 mt-3 me-3" style="width: 24px; height: 24px; padding: 0;">&times;</button>
                            @endif
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-white-50 small">Merek Sepatu</label>
                                    <input type="text" name="items[{{ $index }}][shoe_brand]" class="form-control" placeholder="Contoh: Nike" value="{{ $oldItem['shoe_brand'] ?? '' }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white-50 small">Material</label>
                                    <input type="text" name="items[{{ $index }}][shoe_material]" class="form-control" placeholder="Contoh: Canvas/Suede" value="{{ $oldItem['shoe_material'] ?? '' }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white-50 small">Warna</label>
                                    <input type="text" name="items[{{ $index }}][shoe_color]" class="form-control" placeholder="Contoh: Putih" value="{{ $oldItem['shoe_color'] ?? '' }}" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label text-white-50 small">Jenis Treatment</label>
                                    <select name="items[{{ $index }}][treatment_id]" class="form-select treatment-select" required>
                                        <option value="">-- Pilih Treatment --</option>
                                        @foreach($treatments as $t)
                                            <option value="{{ $t->id }}" data-price="{{ $t->price }}" {{ (isset($oldItem['treatment_id']) && $oldItem['treatment_id'] == $t->id) ? 'selected' : '' }}>
                                                {{ $t->name }} (Rp {{ number_format($t->price, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white-50 small">Harga</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-white-10 text-white-50">Rp</span>
                                        <input type="number" name="items[{{ $index }}][price]" class="form-control item-price" value="{{ $oldItem['price'] ?? 0 }}" required min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- KANAN: Logistik & Pembayaran --}}
            <div class="col-lg-4">
                
                {{-- Metode & Jadwal --}}
                <div class="card mb-4 border-0 shadow-sm" style="background: rgba(255,255,255,0.03); border-radius: 16px;">
                    <div class="card-header bg-transparent border-white-5 py-3 px-4">
                        <h5 class="mb-0 fs-6 fw-bold text-white d-flex align-items-center gap-2">
                            <i class="fas fa-truck text-primary"></i> Logistik
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label text-white-50 small d-block mb-3">Metode Pelayanan</label>
                            <div class="d-flex flex-column gap-2">
                                <label class="service-option p-3 rounded-3 border border-white-10 cursor-pointer d-flex align-items-center gap-3">
                                    <input type="radio" name="service_method" value="datang_langsung" {{ old('service_method', 'datang_langsung') == 'datang_langsung' ? 'checked' : '' }} class="form-check-input mt-0">
                                    <div>
                                        <div class="text-white fw-bold small">Datang Langsung</div>
                                        <div class="text-white-50" style="font-size: 0.75rem;">Antar & ambil di toko</div>
                                    </div>
                                </label>
                                <label class="service-option p-3 rounded-3 border border-white-10 cursor-pointer d-flex align-items-center gap-3">
                                    <input type="radio" name="service_method" value="pickup_delivery" {{ old('service_method') == 'pickup_delivery' ? 'checked' : '' }} class="form-check-input mt-0">
                                    <div>
                                        <div class="text-white fw-bold small">Pickup & Delivery</div>
                                        <div class="text-white-50" style="font-size: 0.75rem;">Jemput & antar ke rumah (+40k)</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div id="logisticFields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label text-white-50 small">Alamat Lengkap</label>
                                <textarea name="pickup_address" class="form-control" rows="2" placeholder="Jl. Contoh No. 123...">{{ old('pickup_address') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50 small">Jadwal Penjemputan</label>
                                <input type="datetime-local" name="pickup_date" class="form-control" value="{{ old('pickup_date') }}">
                            </div>
                        </div>

                        <hr class="my-4 border-white-5">

                        <div class="mb-0">
                            <label class="form-label text-white-50 small">Estimasi Selesai</label>
                            <input type="datetime-local" name="estimated_completion" class="form-control" value="{{ old('estimated_completion') }}">
                        </div>
                    </div>
                </div>

                {{-- Total & Simpan --}}
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(244,114,182,0.1) 0%, rgba(244,114,182,0.02) 100%); border-radius: 16px; border: 1px solid rgba(244,114,182,0.2) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50 small">Subtotal Treatment</span>
                            <span class="text-white fw-bold" id="subtotalText">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-white-50 small">Biaya Logistik</span>
                            <span class="text-white fw-bold" id="logisticFeeText">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-white fw-bold fs-5">TOTAL</span>
                            <span class="text-primary fw-bold fs-4" id="totalPriceText">Rp 0</span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-50 small">Status Pembayaran</label>
                            <select name="payment_status" class="form-select bg-dark">
                                <option value="belum_lunas" {{ old('payment_status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="lunas" {{ old('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white-50 small">Metode Bayar</label>
                            <input type="text" name="payment_method" class="form-control" placeholder="Transfer/Tunai/QRIS" value="{{ old('payment_method') }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-lg" id="submitBtn">
                            SIMPAN ORDER
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>

{{-- TEMPLATE FOR REPEATER ITEM --}}
<template id="itemTemplate">
    <div class="item-row p-4 border-bottom border-white-5 position-relative" style="display: none;">
        <button type="button" class="btn-remove-item btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 mt-3 me-3" style="width: 24px; height: 24px; padding: 0;">&times;</button>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label text-white-50 small">Merek Sepatu</label>
                <input type="text" name="items[INDEX][shoe_brand]" class="form-control" placeholder="Contoh: Nike" required>
            </div>
            <div class="col-md-4">
                <label class="form-label text-white-50 small">Material</label>
                <input type="text" name="items[INDEX][shoe_material]" class="form-control" placeholder="Contoh: Canvas/Suede" required>
            </div>
            <div class="col-md-4">
                <label class="form-label text-white-50 small">Warna</label>
                <input type="text" name="items[INDEX][shoe_color]" class="form-control" placeholder="Contoh: Putih" required>
            </div>
            <div class="col-md-8">
                <label class="form-label text-white-50 small">Jenis Treatment</label>
                <select name="items[INDEX][treatment_id]" class="form-select treatment-select" required>
                    <option value="">-- Pilih Treatment --</option>
                    @foreach($treatments as $t)
                        <option value="{{ $t->id }}" data-price="{{ $t->price }}">{{ $t->name }} (Rp {{ number_format($t->price, 0, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-white-50 small">Harga</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-white-10 text-white-50">Rp</span>
                    <input type="number" name="items[INDEX][price]" class="form-control item-price" value="0" required min="0">
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .form-control, .form-select {
        background-color: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        border-radius: 10px;
        padding: 0.6rem 1rem;
    }
    .form-control:focus, .form-select:focus {
        background-color: rgba(255,255,255,0.08);
        border-color: var(--primary);
        color: #fff;
        box-shadow: none;
    }
    .form-control::placeholder { color: rgba(255,255,255,0.3); }
    
    .service-option { transition: all 0.2s; position: relative; }
    .service-option:hover { background: rgba(255,255,255,0.05); }
    .service-option:has(input:checked) {
        border-color: var(--primary) !important;
        background: rgba(244,114,182,0.05);
    }
    .cursor-pointer { cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($oldItems) }};
    const container = document.getElementById('itemContainer');
    const template = document.getElementById('itemTemplate');
    const addBtn = document.getElementById('addItemBtn');

    // Add Item
    addBtn.addEventListener('click', () => {
        const clone = template.content.cloneNode(true).querySelector('.item-row');
        clone.innerHTML = clone.innerHTML.replace(/INDEX/g, itemIndex);
        clone.style.display = 'block';
        container.appendChild(clone);
        itemIndex++;
        updateTotals();
    });

    // Remove Item
    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-remove-item')) {
            e.target.closest('.item-row').remove();
            updateTotals();
        }
    });

    // Update Price on Treatment Change
    container.addEventListener('change', (e) => {
        if (e.target.classList.contains('treatment-select')) {
            const price = e.target.options[e.target.selectedIndex].dataset.price || 0;
            const priceInput = e.target.closest('.item-row').querySelector('.item-price');
            priceInput.value = price;
            updateTotals();
        }
    });

    // Update Totals on Price Change
    container.addEventListener('input', (e) => {
        if (e.target.classList.contains('item-price')) {
            updateTotals();
        }
    });

    // Logistics Toggle
    const serviceRadios = document.querySelectorAll('input[name="service_method"]');
    const logisticFields = document.getElementById('logisticFields');

    function updateLogistics() {
        const selected = document.querySelector('input[name="service_method"]:checked').value;
        logisticFields.style.display = (selected === 'pickup_delivery') ? 'block' : 'none';
        updateTotals();
    }

    serviceRadios.forEach(r => r.addEventListener('change', updateLogistics));
    updateLogistics();

    // Calculate Final Totals
    function updateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-price').forEach(input => {
            subtotal += parseFloat(input.value || 0);
        });

        const method = document.querySelector('input[name="service_method"]:checked').value;
        const logisticFee = (method === 'pickup_delivery') ? 40000 : 0;
        const total = subtotal + logisticFee;

        document.getElementById('subtotalText').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('logisticFeeText').innerText = 'Rp ' + logisticFee.toLocaleString('id-ID');
        document.getElementById('totalPriceText').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Customer Auto-Fill
    const customerSelect = document.getElementById('customer_id');
    const customerName = document.getElementById('customer_name');
    const customerPhone = document.getElementById('customer_phone');

    customerSelect.addEventListener('change', () => {
        const option = customerSelect.options[customerSelect.selectedIndex];
        const name = option.dataset.name || '';
        const phone = option.dataset.phone || '';

        // Auto-fill name if empty or previously auto-filled
        if (!customerName.value || customerName.dataset.auto === 'true') {
            customerName.value = name;
            customerName.dataset.auto = 'true';
        }

        // Auto-fill phone if empty or previously auto-filled
        if (!customerPhone.value || customerPhone.dataset.auto === 'true') {
            customerPhone.value = phone;
            customerPhone.dataset.auto = 'true';
        }
    });

    customerName.addEventListener('input', () => customerName.dataset.auto = 'false');
    customerPhone.addEventListener('input', () => customerPhone.dataset.auto = 'false');

    // Initial calculation
    updateTotals();

    // Loading State
    document.getElementById('orderForm').addEventListener('submit', () => {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-grow spinner-grow-sm me-2"></span>MENYIMPAN...';
    });
});
</script>
@endsection
