@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-primary text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="small opacity-75">Pendapatan Hari Ini</div>
                    <div class="fs-5 fw-bold">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
                </div>
                <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="small opacity-75">Transaksi Hari Ini</div>
                    <div class="fs-5 fw-bold">{{ $transaksiHariIni }} transaksi</div>
                </div>
                <i class="bi bi-receipt fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-info text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="small opacity-75">Pendapatan Bulan Ini</div>
                    <div class="fs-5 fw-bold">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
                </div>
                <i class="bi bi-graph-up-arrow fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-warning text-dark">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="small opacity-75">Total Produk</div>
                    <div class="fs-5 fw-bold">{{ $totalProduk }} produk</div>
                </div>
                <i class="bi bi-box-seam fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Transaksi Terbaru -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Transaksi Terbaru</h6>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Invoice</th>
                                <th>Kasir</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiTerbaru as $trx)
                            <tr>
                                <td>
                                    <a href="{{ route('transactions.show', $trx) }}" class="text-decoration-none fw-semibold">
                                        {{ $trx->no_invoice }}
                                    </a>
                                    <div class="text-muted small">{{ $trx->tanggal->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>{{ $trx->user->nama ?? '-' }}</td>
                                <td>{{ $trx->customer->nama ?? 'Umum' }}</td>
                                <td class="fw-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($trx->payment)
                                        <span class="badge bg-secondary">{{ strtoupper($trx->payment->metode) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $trx->status === 'lunas' ? 'success' : 'warning' }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Menipis -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Stok Menipis</h6>
            </div>
            <div class="card-body p-0">
                @forelse($stokMenipis as $produk)
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <div>
                        <div class="fw-semibold small">{{ $produk->nama }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $produk->category->nama ?? '-' }}</div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-danger">{{ $produk->stok }} pcs</span>
                        <div class="text-muted" style="font-size:.7rem">min: {{ $produk->stok_minimum }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-check-circle text-success fs-3 d-block mb-2"></i>
                    Semua stok aman.
                </div>
                @endforelse
            </div>
            @if($stokMenipis->count() > 0)
            <div class="card-footer bg-white text-center">
                <a href="{{ route('stock.index') }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-clipboard-data me-1"></i>Kelola Stok
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

