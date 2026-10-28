<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Common\Exceptions\CurrentFiscalYearNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class YearSetupController extends Controller
{
    public function index(Request $request){
        $years = Year::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('year_id', 'desc')
            ->paginate(10)
            ->withQueryString();
        return view('year.index',compact('years'));
    }

    public function create(){
        return view('year.create');
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|regex:/^[0-9]{4}$/',
        ]);

        $lastSequence = Year::query()->max('sequence');
        if(!$lastSequence){
            $lastSequence = 0;
        }

        Year::create([
            'name'=>$request->name,
            'status'=>'active',
            "sequence"=>++$lastSequence,
        ]);
        return redirect()->route('year.index')->with('success','Year created successfully');

    }
    public function activate(int $yearId){
        Year::findOrFail($yearId)->update(['status'=>'active']);
        return redirect()->route('year.index')->with('success','Year activated successfully');
    }
    public function deactivate(int $yearId){
        Year::findOrFail($yearId)->update(['status'=>'inactive']);
        return redirect()->route('year.index')->with('success','Year deactivated successfully');
    }
    public function edit(int $yearId){
        $year = Year::findOrFail($yearId);
        return view('year.edit',compact('year'));
    }
    public function update(Request $request,int $yearId){
        $year = Year::findOrFail($yearId);
        $request->validate([
            'name'=>'required|regex:/^[0-9]{4}$/',
            'sequence'=>'numeric|nullable',
        ]);
        $year->update([
            'name'=>$request->name,
            'sequence'=>$request->sequence,
        ]);
        return redirect()->route('year.index')->with('success','Year updated successfully');
    }

    public function markAsCurrent(int $yearId)
    {
        try {
            DB::transaction(function () use ($yearId) {
                $year = Year::findOrFail($yearId);
                $this->validateYear($year->name);
                Year::query()->update([
                    'is_current' => false,
                ]);

                $year->update([
                    'is_current' => true,
                ]);
            });
        } catch (Throwable $e) {
            return redirect()->route('year.index')->with('error',$e->getMessage());
        }

        return redirect()
            ->route('year.index')
            ->with('success', 'Year marked as current successfully.');
    }

    /**
     * @throws CurrentFiscalYearNotFoundException
     */
    private function validateYear(string $name): void
    {
        $fiscalYearName = getCurrentFiscalYear()->name;

        [$startYear, $endYear] = explode('/', $fiscalYearName);

        $allowedYears = [
            $startYear,
            substr($startYear, 0, 2) . $endYear,
        ];

        if (!in_array($name, $allowedYears, true)) {
            throw new InvalidArgumentException(
                "Year {$name} does not belong to the current fiscal year {$fiscalYearName}."
            );
        }
    }
}
