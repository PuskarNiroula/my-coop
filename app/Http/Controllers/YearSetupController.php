<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;

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

        Year::create([
            'name'=>$request->name,
            'status'=>'active'
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
}
