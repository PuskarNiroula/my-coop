<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use Exception;
use Illuminate\Http\Request;

class FiscalYearSetupController extends Controller
{
    public function index(Request $request){
        $fiscalYears = FiscalYear::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('fiscal_year_id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('fiscal-year.index', compact('fiscalYears'));
    }
    public function create(){
        return view ('fiscal-year.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => [
                'required',
                'string',
                'regex:/^[0-9]{4}\/[0-9]{2}$/',
                'unique:fiscal_years,name',
            ],
        ]);

      try {
          $sequence = FiscalYear::query()->max('sequence') + 1;
          FiscalYear::create([
              'name' => $request->name,
              'sequence' => $sequence,
              'status' => 'active'
          ]);
          return redirect()->route('fiscal-year.index')->with('success', 'Fiscal Year created successfully.');
      }catch (Exception $exception){
          return redirect()->route('fiscal-year.index')->with('error', $exception->getMessage());
      }

    }

    public function activate(int $id){
        FiscalYear::findOrFail($id)->update(['status' => 'active']);
        return redirect()->route('fiscal-year.index')->with('success', 'Fiscal Year activated successfully.');
    }
    public function deactivate(int $id){
        FiscalYear::findOrFail($id)->update(['status' => 'inactive']);
        return redirect()->route('fiscal-year.index')->with('success', 'Fiscal Year Deactivated successfully.');
    }

    public function markCurrent(int $id){
        $currentFiscalYear = FiscalYear::where('is_current', true)->first();
        if($currentFiscalYear){
            return redirect()->route('fiscal-year.index')->with('error', 'Fiscal Year '.$currentFiscalYear->name.' is already marked as Current.');
        }
        FiscalYear::findOrFail($id)->update([
            'is_current' => true,
            'is_closed' => false
        ]);
        return redirect()->route('fiscal-year.index')->with('success', 'Fiscal Year Marked as Current successfully.');
    }
    public function close(int $id){
        FiscalYear::findOrFail($id)->update([
            'is_current' => false,
            'is_closed' => true
        ]);
        return redirect()->route('fiscal-year.index')->with('success', 'Fiscal Year Marked as Current successfully.');
    }
    public function edit(int $id){
        $fiscalYear = FiscalYear::findOrFail($id);
        return view('fiscal-year.edit', compact('fiscalYear'));
    }
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'regex:/^[0-9]{4}\/[0-9]{2}$/',
                'unique:fiscal_years,name,' . $id . ',fiscal_year_id',
            ],
            'sequence_number' => [
                'required',
                'integer',
                'unique:fiscal_years,sequence,' . $id . ',fiscal_year_id',
            ],
        ]);
        try {
            FiscalYear::findOrFail($id)->update([
                'name' => $request->name,
                'sequence' => $request->sequence_number,
            ]);

            return redirect()->route('fiscal-year.index')->with('success', 'Fiscal Year updated successfully.');
        }catch (Exception $exception){
            return redirect()->route('fiscal-year.index')->with('error', $exception->getMessage())->withInput();
        }
    }
}
