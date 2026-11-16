<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));

        $suppliers = Supplier::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('inventory.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('inventory.supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers', 'name'),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
            ],
            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $validated['status'] = 'active';

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function edit(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('inventory.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, int $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers', 'name')->ignore($supplier),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
            ],
            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function activate(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'status' => 'active',
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier activated successfully.');
    }

    public function deactivate(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'status' => 'inactive',
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deactivated successfully.');
    }
}
