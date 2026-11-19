@extends('layouts.app')
@section('title', 'Riwayat Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <h5 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>Riwayat Stok Lengkap</h5>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
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
                        <td>{{ $stockLogs->firstItem() + $loop->index }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $log->product->nama ?? '(produk dihapus)' }}</div>
                            @if($log->product)
                                <div class="text-muted small"><code>{{ $log->product->sku }}</code></div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{
                                $log->tipe === 'masuk' ? 'success' :
                                ($log->tipe === 'keluar' ? 'danger' : 'info')
                            }}">
                                {{ ucfirst($log->tipe) }}
                            </span>
                        </td>
                        <td class="text-center fw-bold">{{ $log->qty }}</td>
                        <td>{{ $log->user->nama ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-journal-x fs-2 d-block mb-2"></i>Belum ada riwayat stok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stockLogs->hasPages())
    <div class="card-footer bg-white">
        {{ $stockLogs->links() }}
    </div>
    @endif
</div>
@endsection

