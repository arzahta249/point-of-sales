@extends('layouts.app')
@section('title', 'Transaksi Baru')

@push('styles')
<style>
    #cart-table tbody tr td { vertical-align: middle; }
    .product-row { background: #f8f9fa; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-cart-plus me-2 text-success"></i>Transaksi Penjualan Baru</h5>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Terdapat kesalahan:</strong>
    <ul class="mb-0 mt-1">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('transactions.store') }}" id="trxForm">
    @csrf
    <div class="row g-4">
        <!-- Kiri: Pilih Produk -->
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-cart me-2"></i>Item Belanja</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="btnAddItem">
                        <i class="bi bi-plus me-1"></i>Tambah Produk
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0" id="cart-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center" width="120">Qty</th>
                                    <th class="text-end" width="150">Harga Satuan</th>
                                    <th class="text-end" width="150">Subtotal</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-body">
                                <tr id="empty-row">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-cart-x fs-3 d-block mb-2"></i>
                                        Klik "Tambah Produk" untuk memulai transaksi
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan: Ringkasan -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person me-2"></i>Data Pelanggan</h6>
                </div>
                <div class="card-body">
                    <select name="customer_id" class="form-select">
                        <option value="">Pelanggan Umum</option>
                        @foreach($customers as $cust)
                            <option value="{{ $cust->id }}" {{ old('customer_id') == $cust->id ? 'selected' : '' }}>
                                {{ $cust->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-calculator me-2"></i>Ringkasan Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span id="display-subtotal" class="fw-semibold">Rp 0</span>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-muted small">Diskon (Rp)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="diskon" id="diskon" value="{{ old('diskon', 0) }}"
                                   class="form-control @error('diskon') is-invalid @enderror"
                                   min="0" step="100" oninput="hitung()">
                            @error('diskon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold fs-6">Total</span>
                        <span id="display-total" class="fw-bold fs-6 text-success">Rp 0</span>
                        <input type="hidden" name="_total" id="hidden-total" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode" class="form-select @error('metode') is-invalid @enderror">
                            <option value="">-- Pilih Metode --</option>
                            <option value="tunai" {{ old('metode') === 'tunai' ? 'selected' : '' }}>💵 Tunai</option>
                            <option value="transfer" {{ old('metode') === 'transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                            <option value="qris" {{ old('metode') === 'qris' ? 'selected' : '' }}>📱 QRIS</option>
                        </select>
                        @error('metode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Bayar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="jumlah_bayar" id="jumlah_bayar"
                                   value="{{ old('jumlah_bayar', 0) }}"
                                   class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                   min="0" step="100" oninput="hitungKembalian()">
                            @error('jumlah_bayar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="alert alert-info py-2 d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold">Kembalian</span>
                        <span id="display-kembalian" class="fw-bold">Rp 0</span>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg" id="btnSubmit" disabled>
                            <i class="bi bi-check-circle me-2"></i>Selesaikan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Pilih Produk -->
<div class="modal fade" id="modalProduk" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-search me-2"></i>Pilih Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 border-bottom">
                    <input type="text" class="form-control" id="cariProduk" placeholder="Cari nama produk atau SKU...">
                </div>
                <div class="table-responsive" style="max-height:400px;overflow-y:auto">
                    <table class="table table-hover mb-0" id="tabelProdukModal">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Nama Produk</th>
                                <th>SKU</th>
                                <th>Kategori</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-center">Stok</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $prod)
                            <tr data-nama="{{ strtolower($prod->nama) }}" data-sku="{{ strtolower($prod->sku) }}">
                                <td class="fw-semibold">{{ $prod->nama }}</td>
                                <td><code>{{ $prod->sku }}</code></td>
                                <td><span class="badge bg-secondary">{{ $prod->category->nama ?? '-' }}</span></td>
                                <td class="text-end">Rp {{ number_format($prod->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $prod->stok <= $prod->stok_minimum ? 'danger' : 'success' }}">
                                        {{ $prod->stok }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary"
                                            onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->nama) }}', {{ $prod->harga_jual }}, {{ $prod->stok }})">
                                        <i class="bi bi-plus"></i> Pilih
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = {}; // { product_id: { nama, harga, qty, stok } }
let subtotal = 0;

const modalProduk = new bootstrap.Modal(document.getElementById('modalProduk'));

document.getElementById('btnAddItem').addEventListener('click', () => modalProduk.show());

document.getElementById('cariProduk').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tabelProdukModal tbody tr').forEach(tr => {
        const nama = tr.dataset.nama || '';
        const sku  = tr.dataset.sku  || '';
        tr.style.display = (nama.includes(q) || sku.includes(q)) ? '' : 'none';
    });
});

function addToCart(id, nama, harga, stok) {
    if (cart[id]) {
        if (cart[id].qty >= stok) {
            alert('Stok tidak mencukupi!');
            return;
        }
        cart[id].qty++;
        document.getElementById('qty-' + id).value = cart[id].qty;
    } else {
        cart[id] = { nama, harga, qty: 1, stok };
        renderRow(id);
    }
    updateSubtotalRow(id);
    hitung();
    modalProduk.hide();
}

function renderRow(id) {
    const item = cart[id];
    const emptyRow = document.getElementById('empty-row');
    if (emptyRow) emptyRow.remove();

    const tr = document.createElement('tr');
    tr.id = 'row-' + id;
    tr.innerHTML = `
        <td>
            ${item.nama}
            <input type="hidden" name="items[${id}][product_id]" value="${id}">
        </td>
        <td class="text-center">
            <input type="number" id="qty-${id}" name="items[${id}][qty]"
                   value="${item.qty}" min="1" max="${item.stok}"
                   class="form-control form-control-sm text-center"
                   onchange="updateQty(${id}, this.value)" style="width:80px;margin:auto">
        </td>
        <td class="text-end">Rp ${item.harga.toLocaleString('id-ID')}</td>
        <td class="text-end fw-semibold" id="sub-${id}">Rp 0</td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${id})">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    document.getElementById('cart-body').appendChild(tr);
}

function updateSubtotalRow(id) {
    const item = cart[id];
    const sub = item.harga * item.qty;
    document.getElementById('sub-' + id).textContent = 'Rp ' + sub.toLocaleString('id-ID');
}

function updateQty(id, val) {
    val = parseInt(val);
    if (isNaN(val) || val < 1) val = 1;
    if (val > cart[id].stok) { val = cart[id].stok; }
    cart[id].qty = val;
    document.getElementById('qty-' + id).value = val;
    updateSubtotalRow(id);
    hitung();
}

function removeItem(id) {
    delete cart[id];
    const row = document.getElementById('row-' + id);
    if (row) row.remove();
    if (Object.keys(cart).length === 0) {
        const tbody = document.getElementById('cart-body');
        tbody.innerHTML = `<tr id="empty-row"><td colspan="5" class="text-center text-muted py-4">
            <i class="bi bi-cart-x fs-3 d-block mb-2"></i>Klik "Tambah Produk" untuk memulai transaksi</td></tr>`;
    }
    hitung();
}

function hitung() {
    subtotal = Object.values(cart).reduce((sum, i) => sum + i.harga * i.qty, 0);
    const diskon = parseFloat(document.getElementById('diskon').value) || 0;
    const total  = Math.max(0, subtotal - diskon);

    document.getElementById('display-subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('display-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('hidden-total').value = total;
    hitungKembalian();

    document.getElementById('btnSubmit').disabled = Object.keys(cart).length === 0;
}

function hitungKembalian() {
    const diskon = parseFloat(document.getElementById('diskon').value) || 0;
    const total  = Math.max(0, subtotal - diskon);
    const bayar  = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
    const kembalian = bayar - total;
    document.getElementById('display-kembalian').textContent =
        'Rp ' + Math.max(0, kembalian).toLocaleString('id-ID');
}
</script>
@endpush

