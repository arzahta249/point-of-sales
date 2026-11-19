<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('nama')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:categories,nama'],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique'   => 'Nama kategori sudah ada.',
            'nama.max'      => 'Nama kategori maksimal 255 karakter.',
        ]);

        Category::create(['nama' => $request->nama]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:categories,nama,' . $category->id],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique'   => 'Nama kategori sudah ada.',
            'nama.max'      => 'Nama kategori maksimal 255 karakter.',
        ]);

        $category->update(['nama' => $request->nama]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}

