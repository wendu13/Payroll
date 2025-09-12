<?php

namespace App\Http\Controllers;

use App\Models\DeductionSetting;
use Illuminate\Http\Request;
use App\Models\SSSBracket;


class DeductionSettingController extends Controller
{
    public function index()
    {
        $lateAbsence = DeductionSetting::firstOrCreate(
            ['deduction_type' => 'late_absences'],           
            ['settings' => json_encode(['days' => 0])]       
        );
        
        $lateAbsence->settings = json_decode($lateAbsence->settings, true);
    
        // Load SSS brackets
        $sssBrackets = SSSBracket::orderBy('to')->get();
    
        // Pass both variables to the view
        return view('hr.deductions.index', compact('lateAbsence', 'sssBrackets'));
    }

    public function update(Request $request, DeductionSetting $deduction)
    {
        $request->validate([
            'days' => 'required|numeric|min:0'
        ]);
    
        $deduction->settings = json_encode([
            'days' => $request->days
        ]);
    
        $deduction->save();
    
        return redirect()->route('deductions.index')->with('success', 'Late & Absences updated.');
    }
    
    public function storeSSS(Request $request)
    {
        $request->validate([
            'from' => 'required|numeric',
            'to' => 'required|numeric|gte:from',
            'er' => 'required|numeric',
            'ee' => 'required|numeric',
            'others' => 'nullable|string'
        ]);
    
        $bracket = SSSBracket::create([
            'from' => $request->from,
            'to' => $request->to,
            'er' => $request->er,
            'ee' => $request->ee,
            'total' => $request->er + $request->ee,
            'others' => $request->others,
        ]);
    
        return redirect()->back()->with('success', 'SSS bracket added successfully.');
    } 
    
    public function updateSSS(Request $request, $id)
    {
        $request->validate([
            'from' => 'required|numeric|min:0',
            'to' => 'required|numeric|gte:from',
            'er' => 'required|numeric|min:0',
            'ee' => 'required|numeric|min:0',
            'others' => 'nullable|string',
        ]);
    
        $bracket = SSSBracket::findOrFail($id);
        $bracket->update([
            'from' => $request->from,
            'to' => $request->to,
            'er' => $request->er,
            'ee' => $request->ee,
            'total' => $request->er + $request->ee,
            'others' => $request->others,
        ]);
    
        return redirect()->back()->with('success', 'SSS bracket updated.');
    }
    
    
}
