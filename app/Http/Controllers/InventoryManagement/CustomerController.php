<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::query()
            ->leftJoinSub(
                DB::table('member_credit_history')
                    ->select('member_id')
                    ->selectRaw('SUM(remaining_amount) as credit_balance')
                    ->where('status', 'active')
                    ->groupBy('member_id'),
                'credits',
                'customers.member_id',
                '=',
                'credits.member_id'
            )
            ->select(
                'customers.*',
                DB::raw('COALESCE(credits.credit_balance, 0) as credit_balance')
            )
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('customers.name', 'like', "%{$search}%")
                        ->orWhere('customers.phone', 'like', "%{$search}%")
                        ->orWhere('customers.address', 'like', "%{$search}%");
                });
            })
            ->latest('customers.created_at')
            ->paginate(10)
            ->withQueryString();

        return view('inventory.customer.index', compact('customers'));
    }
    public function create(){
        return view('inventory.customer.create');
    }
    public function edit(int $id){
        $customer=Customer::findOrFail($id);
        return view('inventory.customer.edit',compact('customer'));
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:customers,phone',
            'address' => 'nullable|string|max:255',
        ]);
        try{
            Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'member_id' => null,
                'status' => 'active'
            ]);
            return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
        }catch (\Exception $exception){
            return redirect()->route('customers.index')->with('error', $exception->getMessage());
        }


    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }
    public function activate(int $id){
        Customer::findOrFail($id)->update(['status' => 'active']);
        return redirect()->route('customers.index')->with('success', 'Customer activated successfully.');
    }
    public function deactivate(int $id){
        Customer::findOrFail($id)->update(['status' => 'inactive']);
        return redirect()->route('customers.index')->with('success', 'Customer deactivated successfully.');
    }

}
