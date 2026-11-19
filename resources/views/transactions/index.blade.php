@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Riwayat Transaksi</h5>
        <small class="text-muted">Daftar semua transaksi penjualan</small>
    </div>
    <a href="{{ route('transactions.create') }}" class="btn btn-success">
        <i class="bi bi-cart-plus me-1"></i> Transaksi Baru
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Pelanggan</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Metode</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="110">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td>
                            <a href="{{ route('transactions.show', $trx) }}" class="fw-semibold text-decoration-none">
                                {{ $trx->no_invoice }}
                            </a>
                        </td>
                        <td class="small">{{ $trx->tanggal->format('d/m/Y H:i') }}</td>
                        <td>{{ $trx->user->nama ?? '-' }}</td>
                        <td>{{ $trx->customer->nama ?? 'Umum' }}</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($trx->payment)
                                <span class="badge bg-secondary">{{ strtoupper($trx->payment->metode) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $trx->status === 'lunas' ? 'success' : 'warning' }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('transactions.show', $trx) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('transactions.destroy', $trx) }}" class="d-inline"
                                  onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-receipt fs-2 d-block mb-2"></i>Belum ada transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transactions->hasPages())
    <div class="card-footer bg-white">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection

