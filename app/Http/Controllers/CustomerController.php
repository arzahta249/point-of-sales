<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::withCount('transactions')
            ->orderBy('nama')
            ->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'  => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'digits_between:10,15'],
        ], [
            'nama.required'        => 'Nama pelanggan wajib diisi.',
            'no_hp.digits_between' => 'Nomor HP harus antara 10–15 digit.',
        ]);

        Customer::create($request->only('nama', 'no_hp'));

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $request->validate([
            'nama'  => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'digits_between:10,15'],
        ], [
            'nama.required'        => 'Nama pelanggan wajib diisi.',
            'no_hp.digits_between' => 'Nomor HP harus antara 10–15 digit.',
        ]);

        $customer->update($request->only('nama', 'no_hp'));

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}

