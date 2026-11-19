@extends('layouts.app')
@section('title', 'Detail Transaksi')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-start">
    <div>
        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-info"></i>Detail Transaksi</h5>
    </div>
    <button onclick="window.print()" class="btn btn-outline-secondary">
        <i class="bi bi-printer me-1"></i>Cetak Invoice
    </button>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Info Transaksi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-muted" width="120">No. Invoice</td>
                        <td class="fw-bold">{{ $transaction->no_invoice }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal</td>
                        <td>{{ $transaction->tanggal->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kasir</td>
                        <td>{{ $transaction->user->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Pelanggan</td>
                        <td>{{ $transaction->customer->nama ?? 'Umum' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $transaction->status === 'lunas' ? 'success' : 'warning' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($transaction->payment)
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Info Pembayaran</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-muted">Metode</td>
                        <td><span class="badge bg-secondary">{{ strtoupper($transaction->payment->metode) }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dibayar</td>
                        <td>Rp {{ number_format($transaction->payment->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kembalian</td>
                        <td class="fw-semibold text-success">
                            Rp {{ number_format($transaction->payment->kembalian, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Item Transaksi</h6>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->product->nama ?? '(produk dihapus)' }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-semibold">
                        <tr>
                            <td colspan="4" class="text-end">Subtotal</td>
                            <td class="text-end">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($transaction->diskon > 0)
                        <tr>
                            <td colspan="4" class="text-end text-danger">Diskon</td>
                            <td class="text-end text-danger">- Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="table-success">
                            <td colspan="4" class="text-end fs-6">TOTAL</td>
                            <td class="text-end fs-6">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

