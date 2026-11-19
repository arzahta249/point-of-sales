<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StockLog;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $transactions = Transaction::with(['user', 'customer', 'payment'])
            ->orderByDesc('tanggal')
            ->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('nama')->get();
        $products  = Product::with('category')->where('stok', '>', 0)->orderBy('nama')->get();
        return view('transactions.create', compact('customers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_id'           => ['nullable', 'exists:customers,id'],
            'diskon'                => ['required', 'numeric', 'min:0'],
            'metode'                => ['required', 'in:tunai,transfer,qris'],
            'jumlah_bayar'          => ['required', 'numeric', 'min:0'],
            'items'                 => ['required', 'array', 'min:1'],
            'items.*.product_id'    => ['required', 'exists:products,id'],
            'items.*.qty'           => ['required', 'integer', 'min:1'],
        ], [
            'metode.required'           => 'Metode pembayaran wajib dipilih.',
            'metode.in'                 => 'Metode pembayaran tidak valid.',
            'jumlah_bayar.required'     => 'Jumlah bayar wajib diisi.',
            'items.required'            => 'Minimal 1 item produk harus dipilih.',
            'items.*.product_id.exists' => 'Produk tidak valid.',
            'items.*.qty.min'           => 'Qty minimal 1.',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stok < $item['qty']) {
                    throw new \Exception("Stok {$product->nama} tidak mencukupi. Stok tersedia: {$product->stok}");
                }

                $hargaSatuan = $product->harga_jual;
                $itemSubtotal = $hargaSatuan * $item['qty'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product'      => $product,
                    'qty'          => $item['qty'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal'     => $itemSubtotal,
                ];
            }

            $diskon = $request->diskon ?? 0;
            $total  = $subtotal - $diskon;
            $kembalian = $request->jumlah_bayar - $total;

            $noInvoice = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

            $transaction = Transaction::create([
                'user_id'     => auth()->id(),
                'customer_id' => $request->customer_id,
                'no_invoice'  => $noInvoice,
                'tanggal'     => now(),
                'subtotal'    => $subtotal,
                'diskon'      => $diskon,
                'total'       => $total,
                'status'      => 'lunas',
            ]);

            foreach ($itemsData as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product']->id,
                    'qty'            => $item['qty'],
                    'harga_satuan'   => $item['harga_satuan'],
                    'subtotal'       => $item['subtotal'],
                ]);

                // Kurangi stok
                $item['product']->decrement('stok', $item['qty']);

                // Catat stock log
                StockLog::create([
                    'product_id' => $item['product']->id,
                    'user_id'    => auth()->id(),
                    'tipe'       => 'keluar',
                    'qty'        => $item['qty'],
                    'tanggal'    => now(),
                ]);
            }

            Payment::create([
                'transaction_id' => $transaction->id,
                'metode'         => $request->metode,
                'jumlah_bayar'   => $request->jumlah_bayar,
                'kembalian'      => max(0, $kembalian),
            ]);

            session(['last_transaction_id' => $transaction->id]);
        });

        $transactionId = session('last_transaction_id');

        return redirect()->route('transactions.show', $transactionId)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load(['user', 'customer', 'items.product', 'payment']);
        return view('transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        // Kembalikan stok
        foreach ($transaction->items as $item) {
            $item->product->increment('stok', $item->qty);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan.');
    }
}

