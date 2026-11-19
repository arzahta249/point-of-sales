<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->orderBy('nama')
            ->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('nama')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'sku'          => ['required', 'string', 'max:100', 'unique:products,sku'],
            'nama'         => ['required', 'string', 'max:255'],
            'harga_beli'   => ['required', 'numeric', 'min:0'],
            'harga_jual'   => ['required', 'numeric', 'min:0', 'gte:harga_beli'],
            'stok'         => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
        ], [
            'category_id.required'  => 'Kategori wajib dipilih.',
            'category_id.exists'    => 'Kategori tidak valid.',
            'sku.required'          => 'SKU wajib diisi.',
            'sku.unique'            => 'SKU sudah digunakan.',
            'nama.required'         => 'Nama produk wajib diisi.',
            'harga_beli.required'   => 'Harga beli wajib diisi.',
            'harga_beli.numeric'    => 'Harga beli harus berupa angka.',
            'harga_jual.required'   => 'Harga jual wajib diisi.',
            'harga_jual.gte'        => 'Harga jual harus lebih besar atau sama dengan harga beli.',
            'stok.required'         => 'Stok wajib diisi.',
            'stok.integer'          => 'Stok harus berupa bilangan bulat.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
        ]);

        Product::create($request->only(
            'category_id', 'sku', 'nama', 'harga_beli', 'harga_jual', 'stok', 'stok_minimum'
        ));

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product): View
    {
        $product->load('category', 'stockLogs.user');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('nama')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'sku'          => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'nama'         => ['required', 'string', 'max:255'],
            'harga_beli'   => ['required', 'numeric', 'min:0'],
            'harga_jual'   => ['required', 'numeric', 'min:0', 'gte:harga_beli'],
            'stok'         => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
        ], [
            'category_id.required'  => 'Kategori wajib dipilih.',
            'sku.required'          => 'SKU wajib diisi.',
            'sku.unique'            => 'SKU sudah digunakan.',
            'nama.required'         => 'Nama produk wajib diisi.',
            'harga_jual.gte'        => 'Harga jual harus lebih besar atau sama dengan harga beli.',
        ]);

        $product->update($request->only(
            'category_id', 'sku', 'nama', 'harga_beli', 'harga_jual', 'stok', 'stok_minimum'
        ));

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->transactionItems()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Produk tidak dapat dihapus karena sudah digunakan dalam transaksi.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}

