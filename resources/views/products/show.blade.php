@extends('layouts.app')
@section('title', 'Detail Produk')

@section('content')
<div class="mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h5 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-info"></i>Detail Produk</h5>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Informasi Produk</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="ps-0 text-muted" width="140">SKU</th><td><code>{{ $product->sku }}</code></td></tr>
                    <tr><th class="ps-0 text-muted">Nama</th><td class="fw-semibold">{{ $product->nama }}</td></tr>
                    <tr><th class="ps-0 text-muted">Kategori</th><td><span class="badge bg-secondary">{{ $product->category->nama ?? '-' }}</span></td></tr>
                    <tr><th class="ps-0 text-muted">Harga Beli</th><td>Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td></tr>
                    <tr><th class="ps-0 text-muted">Harga Jual</th><td class="fw-semibold text-success">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td></tr>
                    <tr>
                        <th class="ps-0 text-muted">Stok</th>
                        <td>
                            @if($product->stok <= $product->stok_minimum)
                                <span class="badge bg-danger fs-6">{{ $product->stok }}</span>
                                <span class="text-danger small ms-1"><i class="bi bi-exclamation-triangle-fill"></i> Menipis!</span>
                            @else
                                <span class="badge bg-success fs-6">{{ $product->stok }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th class="ps-0 text-muted">Stok Minimum</th><td>{{ $product->stok_minimum }}</td></tr>
                </table>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="{{ route('stock.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-clipboard-plus me-1"></i>Tambah Stok
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2"></i>Riwayat Stok Terakhir</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th class="text-center">Qty</th>
                                <th>Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->stockLogs->take(10) as $log)
                            <tr>
                                <td class="small">{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $log->tipe === 'masuk' ? 'success' : ($log->tipe === 'keluar' ? 'danger' : 'info') }}">
                                        {{ ucfirst($log->tipe) }}
                                    </span>
                                </td>
                                <td class="text-center fw-semibold">{{ $log->qty }}</td>
                                <td class="small">{{ $log->user->nama ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Belum ada riwayat stok.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

