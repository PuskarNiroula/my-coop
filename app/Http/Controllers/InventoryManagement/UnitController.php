<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('category')
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('symbol', 'LIKE', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->latest('unit_id')
            ->paginate(10)
            ->withQueryString();

        return view('inventory.unit.index', compact('units'));
    }
}
