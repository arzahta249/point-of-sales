<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Total pendapatan hari ini
        $pendapatanHariIni = Transaction::whereDate('tanggal', today())
            ->where('status', 'lunas')
            ->sum('total');

        // Total transaksi hari ini
        $transaksiHariIni = Transaction::whereDate('tanggal', today())->count();

        // Total transaksi bulan ini
        $pendapatanBulanIni = Transaction::whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->where('status', 'lunas')
            ->sum('total');

        // Produk stok menipis (stok <= stok_minimum)
        $stokMenipis = Product::with('category')
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->limit(5)
            ->get();

        // Transaksi terbaru
        $transaksiTerbaru = Transaction::with(['user', 'customer', 'payment'])
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        // Total produk
        $totalProduk = Product::count();

        return view('dashboard', compact(
            'pendapatanHariIni',
            'transaksiHariIni',
            'pendapatanBulanIni',
            'stokMenipis',
            'transaksiTerbaru',
            'totalProduk'
        ));
    }
}

