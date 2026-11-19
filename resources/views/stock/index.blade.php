@extends('layouts.app')
@section('title', 'Manajemen Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard-data me-2 text-primary"></i>Manajemen Stok</h5>
        <small class="text-muted">Pantau dan sesuaikan stok produk</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock.logs') }}" class="btn btn-outline-secondary">
            <i class="bi bi-journal-text me-1"></i>Riwayat Stok
        </a>
        <a href="{{ route('stock.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Sesuaikan Stok
        </a>
    </div>
</div>

<!-- Stok Menipis Alert -->
@php $stokMenipis = $products->where('stok', '<=', 'stok_minimum'); @endphp

<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Semua Produk</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min. Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $prod)
                    <tr class="{{ $prod->stok <= $prod->stok_minimum ? 'table-warning' : '' }}">
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-semibold">{{ $prod->nama }}</div>
                            <div class="text-muted small"><code>{{ $prod->sku }}</code></div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $prod->category->nama ?? '-' }}</span></td>
                        <td class="text-center">
                            <span class="badge fs-6 bg-{{ $prod->stok <= $prod->stok_minimum ? 'danger' : 'success' }}">
                                {{ $prod->stok }}
                            </span>
                        </td>
                        <td class="text-center text-muted">{{ $prod->stok_minimum }}</td>
                        <td class="text-center">
                            @if($prod->stok === 0)
                                <span class="badge bg-dark">Habis</span>
                            @elseif($prod->stok <= $prod->stok_minimum)
                                <span class="badge bg-warning text-dark">Menipis</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('stock.create') }}?product_id={{ $prod->id }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-clipboard-plus"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Belum ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
    <div class="card-footer bg-white">
        {{ $products->links() }}
    </div>
    @endif
</div>

<!-- Riwayat Stok Terbaru -->
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between">
        <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Aktivitas Stok Terbaru</h6>
        <a href="{{ route('stock.logs') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Qty</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockLogs as $log)
                    <tr>
                        <td class="small">{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->product->nama ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $log->tipe === 'masuk' ? 'success' : ($log->tipe === 'keluar' ? 'danger' : 'info') }}">
                                {{ ucfirst($log->tipe) }}
                            </span>
                        </td>
                        <td class="text-center fw-semibold">{{ $log->qty }}</td>
                        <td class="small">{{ $log->user->nama ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada aktivitas stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

