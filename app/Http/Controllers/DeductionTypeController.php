<?php

namespace App\Http\Controllers;

use App\Models\DeductionType;
use Illuminate\Http\Request;

class DeductionTypeController extends Controller
{
    public function index()
    {
        $deductionTypes = DeductionType::all();
        return view('hr.deductions.index', compact('deductionTypes'));
    }

    public function create()
    {
        return view('hr.deductions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:manual,fixed,percent,bracket'
        ]);

        DeductionType::create($request->all());

        return redirect()->route('deductions.index')->with('success', 'Deduction added successfully');
    }
}
