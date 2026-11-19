@extends('layouts.app')
@section('title', 'Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-tags me-2 text-primary"></i>Kelola Kategori</h5>
        <small class="text-muted">Manajemen kategori produk</small>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Nama Kategori</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-center" width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td>{{ $categories->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $cat->nama }}</td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $cat->products_count }} produk</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus kategori {{ $cat->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="bi bi-tags fs-2 d-block mb-2"></i>Belum ada kategori.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-white">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

