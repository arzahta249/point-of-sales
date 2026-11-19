<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        $products  = Product::with('category')->orderBy('nama')->paginate(10);
        $stockLogs = StockLog::with(['product', 'user'])
            ->orderByDesc('tanggal')
            ->limit(20)
            ->get();
        return view('stock.index', compact('products', 'stockLogs'));
    }

    public function create(): View
    {
        $products = Product::with('category')->orderBy('nama')->get();
        return view('stock.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'tipe'       => ['required', 'in:masuk,keluar,penyesuaian'],
            'qty'        => ['required', 'integer', 'min:1'],
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists'   => 'Produk tidak valid.',
            'tipe.required'       => 'Tipe stok wajib dipilih.',
            'tipe.in'             => 'Tipe stok tidak valid.',
            'qty.required'        => 'Jumlah wajib diisi.',
            'qty.integer'         => 'Jumlah harus berupa bilangan bulat.',
            'qty.min'             => 'Jumlah minimal 1.',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->tipe === 'keluar' && $product->stok < $request->qty) {
            return back()->withErrors(['qty' => "Stok tidak mencukupi. Stok tersedia: {$product->stok}"])
                ->withInput();
        }

        // Update stok
        match ($request->tipe) {
            'masuk'        => $product->increment('stok', $request->qty),
            'keluar'       => $product->decrement('stok', $request->qty),
            'penyesuaian'  => $product->update(['stok' => $request->qty]),
        };

        StockLog::create([
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'tipe'       => $request->tipe,
            'qty'        => $request->qty,
            'tanggal'    => now(),
        ]);

        return redirect()->route('stock.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }

    public function logs(): View
    {
        $stockLogs = StockLog::with(['product', 'user'])
            ->orderByDesc('tanggal')
            ->paginate(15);
        return view('stock.logs', compact('stockLogs'));
    }
}

