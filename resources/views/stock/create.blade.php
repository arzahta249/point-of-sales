@extends('layouts.app')
@section('title', 'Sesuaikan Stok')

@section('content')
<div class="mb-4">
    <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-clipboard-plus me-2 text-primary"></i>Sesuaikan Stok</h5>
</div>

<div class="card" style="max-width:520px">
    <div class="card-body">
        <form method="POST" action="{{ route('stock.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Produk <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" id="selectProduk">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}"
                                data-stok="{{ $prod->stok }}"
                                {{ (old('product_id', request('product_id'))) == $prod->id ? 'selected' : '' }}>
                            {{ $prod->nama }} (Stok: {{ $prod->stok }})
                        </option>
                    @endforeach
                </select>
                @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div id="stok-info" class="form-text text-muted"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tipe Penyesuaian <span class="text-danger">*</span></label>
                <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" id="selectTipe">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="masuk" {{ old('tipe') === 'masuk' ? 'selected' : '' }}>
                        📦 Stok Masuk (Pembelian/Restock)
                    </option>
                    <option value="keluar" {{ old('tipe') === 'keluar' ? 'selected' : '' }}>
                        📤 Stok Keluar (Rusak/Hilang)
                    </option>
                    <option value="penyesuaian" {{ old('tipe') === 'penyesuaian' ? 'selected' : '' }}>
                        🔧 Penyesuaian (Set ke nilai tertentu)
                    </option>
                </select>
                @error('tipe') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold" id="labelQty">
                    Jumlah <span class="text-danger">*</span>
                </label>
                <input type="number" name="qty" value="{{ old('qty') }}"
                       class="form-control @error('qty') is-invalid @enderror"
                       placeholder="Masukkan jumlah" min="1">
                @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div id="qty-hint" class="form-text"></div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
                <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('selectProduk').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const stok = opt.dataset.stok;
    const info = document.getElementById('stok-info');
    if (stok !== undefined) {
        info.innerHTML = `Stok saat ini: <strong>${parseInt(stok).toLocaleString('id-ID')}</strong>`;
    } else {
        info.textContent = '';
    }
});

document.getElementById('selectTipe').addEventListener('change', function () {
    const tipe = this.value;
    const label = document.getElementById('labelQty');
    const hint  = document.getElementById('qty-hint');
    if (tipe === 'penyesuaian') {
        label.innerHTML = 'Stok Baru <span class="text-danger">*</span>';
        hint.textContent = 'Stok produk akan diset ke nilai ini.';
    } else {
        label.innerHTML = 'Jumlah <span class="text-danger">*</span>';
        hint.textContent = tipe === 'keluar' ? 'Stok akan dikurangi sebesar jumlah ini.' : 'Stok akan bertambah sebesar jumlah ini.';
    }
});

// Auto-select if product_id is in query string
window.addEventListener('load', () => {
    document.getElementById('selectProduk').dispatchEvent(new Event('change'));
});
</script>
@endpush

