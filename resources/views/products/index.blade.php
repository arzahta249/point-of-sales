@extends('layouts.app')
@section('title', 'Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Kelola Produk</h5>
        <small class="text-muted">Manajemen data produk</small>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $prod)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td><code>{{ $prod->sku }}</code></td>
                        <td>
                            <div class="fw-semibold">{{ $prod->nama }}</div>
                            <div class="text-muted small">Beli: Rp {{ number_format($prod->harga_beli, 0, ',', '.') }}</div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $prod->category->nama ?? '-' }}</span></td>
                        <td class="text-end fw-semibold text-success">
                            Rp {{ number_format($prod->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($prod->stok <= $prod->stok_minimum)
                                <span class="badge bg-danger">{{ $prod->stok }}</span>
                                <i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Stok menipis!"></i>
                            @else
                                <span class="badge bg-success">{{ $prod->stok }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('products.show', $prod) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $prod) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('products.destroy', $prod) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus produk {{ $prod->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-box fs-2 d-block mb-2"></i>Belum ada produk.
                        </td>
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
@endsection

