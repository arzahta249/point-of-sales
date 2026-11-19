@extends('layouts.app')
@section('title', 'Pelanggan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Kelola Pelanggan</h5>
        <small class="text-muted">Data pelanggan terdaftar</small>
    </div>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Pelanggan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th class="text-center">Total Transaksi</th>
                        <th class="text-center" width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $cust)
                    <tr>
                        <td>{{ $customers->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $cust->nama }}</td>
                        <td>{{ $cust->no_hp ?? '<span class="text-muted">-</span>' }}</td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $cust->transactions_count }} transaksi</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('customers.edit', $cust) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('customers.destroy', $cust) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus pelanggan {{ $cust->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-person-x fs-2 d-block mb-2"></i>Belum ada pelanggan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($customers->hasPages())
    <div class="card-footer bg-white">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection

