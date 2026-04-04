@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="max-width: 860px;">

    {{-- Page Header --}}
    <div class="row mb-5 align-items-end">
        <div class="col-md-8">
            <span class="text-primary text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 500;">Admin Area</span>
            <h2 class="mb-0 mt-2" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Buat Order Baru</h2>
            <p class="text-muted mt-2 mb-0" style="font-weight: 300;">Isi detail order di bawah ini dengan lengkap.</p>
        </div>
        <div class="col-md-4 text-md-end mt-4 mt-md-0">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-custom" style="padding: 0.6rem 1.5rem;">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
         style="background-color: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.3); color: #fca5a5; border-radius: 8px;">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
        @csrf

        {{-- Informasi Pelanggan --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0" style="font-size: 1rem; font-family: 'Jost', sans-serif; font-weight: 600; letter-spacing: 0.5px;">
                    <i class="fas fa-user me-2" style="color: var(--primary); opacity: 0.8;"></i>Informasi Pelanggan
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Pilih Akun Pelanggan</label>
                        <select name="customer_id" id="customer_id" class="form-select" required
                                style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                        data-name="{{ $customer->name }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} &mdash; {{ $customer->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">
                            Nama Pelanggan
                            <span class="text-muted fw-normal" style="text-transform: none; letter-spacing: 0;">(otomatis terisi)</span>
                        </label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control"
                               placeholder="Nama pelanggan..."
                               value="{{ old('customer_name') }}"
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                        <small class="text-muted" style="font-size: 0.72rem;">Bisa diedit manual jika perlu.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Sepatu --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0" style="font-size: 1rem; font-family: 'Jost', sans-serif; font-weight: 600; letter-spacing: 0.5px;">
                    <i class="fas fa-shoe-prints me-2" style="color: var(--primary); opacity: 0.8;"></i>Detail Sepatu
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="shoe_brand" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Merek Sepatu</label>
                        <input type="text" name="shoe_brand" id="shoe_brand" class="form-control"
                               placeholder="Contoh: Nike, Adidas, Vans..."
                               value="{{ old('shoe_brand') }}" required
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                    </div>
                    <div class="col-md-6">
                        <label for="shoe_type" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Tipe Sepatu</label>
                        <input type="text" name="shoe_type" id="shoe_type" class="form-control"
                               placeholder="Contoh: Sneakers, Boots, Loafers..."
                               value="{{ old('shoe_type') }}" required
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Jenis Treatment --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0" style="font-size: 1rem; font-family: 'Jost', sans-serif; font-weight: 600; letter-spacing: 0.5px;">
                    <i class="fas fa-spray-can me-2" style="color: var(--primary); opacity: 0.8;"></i>Jenis Treatment
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-2">
                    <label class="form-label text-muted small text-uppercase fw-bold d-block mb-3" style="letter-spacing: 1px; font-size: 0.75rem;">Pilih Layanan</label>
                    <div class="row g-3" id="treatmentOptions">
                        @forelse($treatments as $treatment)
                        <div class="col-md-6">
                            <label class="d-block treatment-card" for="treatment_{{ $treatment->id }}"
                                   data-price="{{ $treatment->price }}"
                                   style="cursor: pointer; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 1rem; transition: all 0.3s ease;">
                                <input type="radio" name="treatment_id" id="treatment_{{ $treatment->id }}"
                                       value="{{ $treatment->id }}"
                                       class="d-none treatment-radio"
                                       {{ old('treatment_id') == $treatment->id ? 'checked' : '' }} required>
                                <div class="d-flex align-items-start">
                                    <div class="me-3 mt-1" style="width: 36px; height: 36px; background: rgba(244,114,182,0.1); border-radius: 50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <i class="fas fa-spray-can" style="color: var(--primary); font-size: 0.95rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="d-block text-white fw-medium" style="font-size: 0.95rem;">{{ $treatment->name }}</span>
                                            <span class="text-primary fw-bold ms-2" style="white-space: nowrap; font-size: 0.9rem;">
                                                Rp {{ number_format($treatment->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="d-block text-muted small mt-1" style="font-size: 0.78rem;">{{ Str::limit($treatment->description, 60) }}</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="p-4 text-center rounded" style="border: 1px dashed rgba(255,255,255,0.1);">
                                <i class="fas fa-spray-can fa-2x mb-2 text-muted" style="opacity: 0.3;"></i>
                                <p class="text-muted mb-0">Belum ada layanan treatment aktif. Tambahkan melalui menu <strong>Layanan Treatment</strong> terlebih dahulu.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Metode Layanan --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0" style="font-size: 1rem; font-family: 'Jost', sans-serif; font-weight: 600; letter-spacing: 0.5px;">
                    <i class="fas fa-truck me-2" style="color: var(--primary); opacity: 0.8;"></i>Metode Layanan
                </h5>
            </div>
            <div class="card-body p-4">

                <div class="row g-3 mb-4" id="serviceOptions">
                    {{-- Datang ke Toko --}}
                    <div class="col-md-4">
                        <label class="d-block service-card h-100" for="method_langsung"
                               style="cursor: pointer; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 1.2rem; transition: all 0.3s ease; text-align: center;">
                            <input type="radio" name="service_method" id="method_langsung" value="datang_langsung"
                                   class="d-none service-radio" {{ old('service_method', 'datang_langsung') == 'datang_langsung' ? 'checked' : '' }} required>
                            <div class="mb-2" style="width: 48px; height: 48px; background: rgba(244,114,182,0.1); border-radius: 50%; display:flex; align-items:center; justify-content:center; margin: 0 auto;">
                                <i class="fas fa-store-alt" style="font-size: 1.2rem; color: var(--primary);"></i>
                            </div>
                            <span class="d-block text-white fw-medium mt-2" style="font-size: 0.95rem;">Datang ke Toko</span>
                            <span class="text-success small fw-medium">Gratis</span>
                            <p class="text-muted mt-1 mb-0" style="font-size: 0.75rem;">Pelanggan mengantar & mengambil sendiri</p>
                        </label>
                    </div>

                    {{-- Pickup --}}
                    <div class="col-md-4">
                        <label class="d-block service-card h-100" for="method_pickup"
                               style="cursor: pointer; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 1.2rem; transition: all 0.3s ease; text-align: center;">
                            <input type="radio" name="service_method" id="method_pickup" value="pickup"
                                   class="d-none service-radio" {{ old('service_method') == 'pickup' ? 'checked' : '' }}>
                            <div class="mb-2" style="width: 48px; height: 48px; background: rgba(244,114,182,0.1); border-radius: 50%; display:flex; align-items:center; justify-content:center; margin: 0 auto;">
                                <i class="fas fa-box-open" style="font-size: 1.2rem; color: var(--primary);"></i>
                            </div>
                            <span class="d-block text-white fw-medium mt-2" style="font-size: 0.95rem;">Pickup</span>
                            <span class="text-warning small fw-medium">+ Rp 40.000</span>
                            <p class="text-muted mt-1 mb-0" style="font-size: 0.75rem;">Kami menjemput sepatu ke alamat pelanggan</p>
                        </label>
                    </div>

                    {{-- Delivery --}}
                    <div class="col-md-4">
                        <label class="d-block service-card h-100" for="method_delivery"
                               style="cursor: pointer; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 1.2rem; transition: all 0.3s ease; text-align: center;">
                            <input type="radio" name="service_method" id="method_delivery" value="delivery"
                                   class="d-none service-radio" {{ old('service_method') == 'delivery' ? 'checked' : '' }}>
                            <div class="mb-2" style="width: 48px; height: 48px; background: rgba(244,114,182,0.1); border-radius: 50%; display:flex; align-items:center; justify-content:center; margin: 0 auto;">
                                <i class="fas fa-truck" style="font-size: 1.2rem; color: var(--primary);"></i>
                            </div>
                            <span class="d-block text-white fw-medium mt-2" style="font-size: 0.95rem;">Delivery</span>
                            <span class="text-warning small fw-medium">+ Rp 40.000</span>
                            <p class="text-muted mt-1 mb-0" style="font-size: 0.75rem;">Kami mengantar sepatu ke alamat pelanggan</p>
                        </label>
                    </div>
                </div>

                {{-- Detail Pickup (conditional) --}}
                <div id="pickupSection" style="display: none;">
                    <div class="p-3 rounded" style="background-color: rgba(244,114,182,0.05); border: 1px dashed rgba(244,114,182,0.3);">
                        <p class="text-primary small mb-3 fw-medium"><i class="fas fa-map-marker-alt me-2"></i>Detail Jadwal Pickup</p>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="pickup_address" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Alamat Penjemputan <span class="text-danger">*</span></label>
                                <textarea name="pickup_address" id="pickup_address" rows="3" class="form-control"
                                          placeholder="Alamat lengkap untuk penjemputan sepatu..."
                                          style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem; resize: none;">{{ old('pickup_address') }}</textarea>
                            </div>
                            <div class="col-md-5">
                                <label for="pickup_date" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Tanggal & Waktu Pickup <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="pickup_date" id="pickup_date" class="form-control"
                                       value="{{ old('pickup_date') }}"
                                       style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detail Delivery (conditional) --}}
                <div id="deliverySection" style="display: none;">
                    <div class="p-3 rounded" style="background-color: rgba(59,130,246,0.05); border: 1px dashed rgba(59,130,246,0.3);">
                        <p class="text-info small mb-3 fw-medium"><i class="fas fa-map-marked-alt me-2"></i>Detail Jadwal Pengiriman</p>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="delivery_address" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Alamat Pengiriman <span class="text-danger">*</span></label>
                                <textarea name="delivery_address" id="delivery_address" rows="3" class="form-control"
                                          placeholder="Alamat lengkap untuk pengiriman sepatu..."
                                          style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem; resize: none;">{{ old('delivery_address') }}</textarea>
                            </div>
                            <div class="col-md-5">
                                <label for="delivery_date" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Tanggal & Waktu Pengiriman <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="delivery_date" id="delivery_date" class="form-control"
                                       value="{{ old('delivery_date') }}"
                                       style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Pembayaran & Estimasi --}}
        <div class="card mb-5">
            <div class="card-header">
                <h5 class="mb-0" style="font-size: 1rem; font-family: 'Jost', sans-serif; font-weight: 600; letter-spacing: 0.5px;">
                    <i class="fas fa-credit-card me-2" style="color: var(--primary); opacity: 0.8;"></i>Pembayaran & Estimasi
                </h5>
            </div>
            <div class="card-body p-4">

                {{-- Ringkasan Harga --}}
                <div class="p-3 rounded mb-4" id="priceSummaryBox"
                     style="background-color: rgba(244,114,182,0.05); border: 1px solid rgba(244,114,182,0.15);">
                    <p class="text-muted small text-uppercase fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.72rem;"><i class="fas fa-calculator me-1"></i>Ringkasan Harga</p>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Harga Treatment</span>
                        <span class="text-white small" id="summaryTreatmentPrice">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1" id="pickupFeeRow" style="display: none !important;">
                        <span class="text-muted small">Biaya Pickup</span>
                        <span class="text-white small">Rp 40.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1" id="deliveryFeeRow" style="display: none !important;">
                        <span class="text-muted small">Biaya Delivery</span>
                        <span class="text-white small">Rp 40.000</span>
                    </div>
                    <hr style="border-color: rgba(255,255,255,0.1); margin: 0.5rem 0;">
                    <div class="d-flex justify-content-between">
                        <span class="text-white fw-medium small">Total</span>
                        <span class="text-primary fw-bold" id="summaryTotal">Rp 0</span>
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Harga Treatment --}}
                    <div class="col-md-4">
                        <label for="price" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Harga Treatment (Rp)</label>
                        <input type="number" name="price" id="price" class="form-control"
                               placeholder="0"
                               value="{{ old('price', 0) }}" required min="0"
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                        <small class="text-muted" style="font-size: 0.72rem;">Otomatis terisi saat pilih treatment.</small>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="col-md-4">
                        <label for="payment_method" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Metode Pembayaran</label>
                        <input type="text" name="payment_method" id="payment_method" class="form-control"
                               placeholder="Transfer, Tunai, QRIS..."
                               value="{{ old('payment_method') }}"
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                    </div>

                    {{-- Status Pembayaran --}}
                    <div class="col-md-4">
                        <label for="payment_status" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Status Pembayaran</label>
                        <select name="payment_status" id="payment_status" class="form-select" required
                                style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                            <option value="belum_lunas" {{ old('payment_status', 'belum_lunas') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="lunas" {{ old('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>

                    {{-- Estimasi Selesai --}}
                    <div class="col-md-12">
                        <label for="estimated_completion" class="form-label text-muted small text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.75rem;">Estimasi Selesai</label>
                        <input type="datetime-local" name="estimated_completion" id="estimated_completion" class="form-control"
                               value="{{ old('estimated_completion') }}"
                               style="background-color: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); color: #e0e0e0; border-radius: 6px; padding: 0.7rem 1rem;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex justify-content-end gap-3 mb-4">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-custom" style="padding: 0.7rem 2rem;">
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="padding: 0.7rem 2.5rem;" id="submitBtn">
                <i class="fas fa-check me-2"></i>Simpan Order
            </button>
        </div>
    </form>
</div>

<style>
    .form-control::placeholder { color: #555; }
    .form-control:focus, .form-select:focus {
        background-color: #2a2a2a !important;
        border-color: rgba(244, 114, 182, 0.5) !important;
        color: #e0e0e0 !important;
        box-shadow: 0 0 0 0.2rem rgba(244, 114, 182, 0.1) !important;
    }
    .form-select option { background-color: #2a2a2a; }
    input[type="number"]::-webkit-inner-spin-button { opacity: 0.4; }

    .treatment-card:has(.treatment-radio:checked) {
        border-color: var(--primary) !important;
        background-color: rgba(244, 114, 182, 0.07);
        box-shadow: 0 0 0 1px var(--primary);
    }
    .treatment-card:hover {
        border-color: rgba(244, 114, 182, 0.4) !important;
    }

    .service-card:has(.service-radio:checked) {
        border-color: var(--primary) !important;
        background-color: rgba(244, 114, 182, 0.07);
        box-shadow: 0 0 0 1px var(--primary);
    }
    .service-card:hover {
        border-color: rgba(244, 114, 182, 0.4) !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Auto-fill nama pelanggan ──
    const customerSelect = document.getElementById('customer_id');
    const customerNameInput = document.getElementById('customer_name');

    customerSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const name = selectedOption.getAttribute('data-name') || '';
        if (!customerNameInput.value || customerNameInput.dataset.autoFilled === 'true') {
            customerNameInput.value = name;
            customerNameInput.dataset.autoFilled = 'true';
        }
    });

    customerNameInput.addEventListener('input', function () {
        customerNameInput.dataset.autoFilled = 'false';
    });

    if (customerSelect.value) {
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        if (!customerNameInput.value) {
            customerNameInput.value = selectedOption.getAttribute('data-name') || '';
            customerNameInput.dataset.autoFilled = 'true';
        }
    }

    // ── Harga & ringkasan ──
    const priceInput          = document.getElementById('price');
    const summaryTreatment    = document.getElementById('summaryTreatmentPrice');
    const summaryTotal        = document.getElementById('summaryTotal');
    const pickupFeeRow        = document.getElementById('pickupFeeRow');
    const deliveryFeeRow      = document.getElementById('deliveryFeeRow');

    function formatRupiah(num) {
        return 'Rp ' + parseInt(num || 0).toLocaleString('id-ID');
    }

    function updateSummary() {
        const price = parseInt(priceInput.value) || 0;
        const method = document.querySelector('input[name="service_method"]:checked')?.value;
        const pickupFee   = (method === 'pickup')   ? 40000 : 0;
        const deliveryFee = (method === 'delivery')  ? 40000 : 0;
        const total = price + pickupFee + deliveryFee;

        summaryTreatment.textContent = formatRupiah(price);
        summaryTotal.textContent     = formatRupiah(total);

        pickupFeeRow.style.display   = (pickupFee > 0)   ? 'flex' : 'none';
        deliveryFeeRow.style.display = (deliveryFee > 0) ? 'flex' : 'none';
    }

    // Auto-isi harga dari treatment
    document.querySelectorAll('.treatment-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            const card = this.closest('.treatment-card');
            const price = card.getAttribute('data-price') || 0;
            priceInput.value = price;
            updateSummary();
        });
    });

    priceInput.addEventListener('input', updateSummary);

    // ── Toggle Pickup / Delivery Section ──
    const pickupSection   = document.getElementById('pickupSection');
    const deliverySection = document.getElementById('deliverySection');
    const pickupAddress   = document.getElementById('pickup_address');
    const pickupDate      = document.getElementById('pickup_date');
    const deliveryAddress = document.getElementById('delivery_address');
    const deliveryDate    = document.getElementById('delivery_date');

    function toggleServiceSections() {
        const method = document.querySelector('input[name="service_method"]:checked')?.value;

        if (method === 'pickup') {
            pickupSection.style.display   = 'block';
            deliverySection.style.display = 'none';
            pickupAddress.setAttribute('required', 'required');
            pickupDate.setAttribute('required', 'required');
            deliveryAddress.removeAttribute('required');
            deliveryDate.removeAttribute('required');
        } else if (method === 'delivery') {
            pickupSection.style.display   = 'none';
            deliverySection.style.display = 'block';
            deliveryAddress.setAttribute('required', 'required');
            deliveryDate.setAttribute('required', 'required');
            pickupAddress.removeAttribute('required');
            pickupDate.removeAttribute('required');
        } else {
            pickupSection.style.display   = 'none';
            deliverySection.style.display = 'none';
            pickupAddress.removeAttribute('required');
            pickupDate.removeAttribute('required');
            deliveryAddress.removeAttribute('required');
            deliveryDate.removeAttribute('required');
        }

        updateSummary();
    }

    document.querySelectorAll('.service-radio').forEach(r => r.addEventListener('change', toggleServiceSections));
    toggleServiceSections();

    // Trigger harga dari old() value jika ada
    const checkedTreatment = document.querySelector('.treatment-radio:checked');
    if (checkedTreatment) {
        const card = checkedTreatment.closest('.treatment-card');
        if (card && !priceInput.value) {
            priceInput.value = card.getAttribute('data-price') || 0;
        }
    }
    updateSummary();

    // ── Submit loading state ──
    document.getElementById('orderForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });
});
</script>
@endsection
