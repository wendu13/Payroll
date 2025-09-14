<?php

namespace App\Http\Controllers;

use App\Models\DeductionSetting;
use Illuminate\Http\Request;
use App\Models\SSSBracket;


class DeductionSettingController extends Controller
{
    public function index()
    {
        // Late & Absences
        $lateAbsence = DeductionSetting::where('deduction_type', 'late_absences')->first();
        if (!$lateAbsence) {
            $lateAbsence = new DeductionSetting();
            $lateAbsence->deduction_type = 'late_absences';
            $lateAbsence->settings = json_encode(['days' => 0]);
            $lateAbsence->save();
        }
        $lateAbsence->settings = json_decode($lateAbsence->settings, true);
    
        // SSS
        $sssBrackets = SSSBracket::orderBy('to')->get();
    
        // PHIC
        $phicSetting = DeductionSetting::where('deduction_type', 'phic')->first();
        if (!$phicSetting) {
            $phicSetting = new DeductionSetting();
            $phicSetting->deduction_type = 'phic';
            $phicSetting->settings = json_encode(['rate' => 0, 'min_salary' => 0, 'max_salary' => 0]);
            $phicSetting->save();
        }
        $phicSetting->settings = json_decode($phicSetting->settings, true);
    
        // HDMF
        $hdmfSetting = DeductionSetting::where('deduction_type', 'hdmf')->first();
        if (!$hdmfSetting) {
            $hdmfSetting = new DeductionSetting();
            $hdmfSetting->deduction_type = 'hdmf';
            $hdmfSetting->settings = json_encode(['employee' => 200, 'employer' => 200]);
            $hdmfSetting->save();
        }
        $hdmfSetting->settings = json_decode($hdmfSetting->settings, true);

        // SSS Loan
        $sssLoanSetting = DeductionSetting::where('deduction_type', 'sss_loan')->first();
        if (!$sssLoanSetting) {
            $sssLoanSetting = new DeductionSetting();
            $sssLoanSetting->deduction_type = 'sss_loan';
            $sssLoanSetting->settings = json_encode([]);
            $sssLoanSetting->save();
        }
        $sssLoanSetting->settings = json_decode($sssLoanSetting->settings, true);

        // HDMF Loan
        $hdmfLoanSetting = DeductionSetting::where('deduction_type', 'hdmf_loan')->first();
        if (!$hdmfLoanSetting) {
            $hdmfLoanSetting = new DeductionSetting();
            $hdmfLoanSetting->deduction_type = 'hdmf_loan';
            $hdmfLoanSetting->settings = json_encode([]);
            $hdmfLoanSetting->save();
        }
        $hdmfLoanSetting->settings = json_decode($hdmfLoanSetting->settings, true);
    
        // Income Tax
        $taxSetting = DeductionSetting::where('deduction_type', 'income_tax')->first();
        if (!$taxSetting) {
            $taxSetting = new DeductionSetting();
            $taxSetting->deduction_type = 'income_tax';
            $taxSetting->settings = json_encode([
                'brackets' => [
                    ['from'=>0, 'to'=>250000, 'base'=>0, 'rate'=>0],
                    ['from'=>250001, 'to'=>400000, 'base'=>0, 'rate'=>15],
                    ['from'=>400001, 'to'=>800000, 'base'=>22500, 'rate'=>20],
                    ['from'=>800001, 'to'=>2000000, 'base'=>102500, 'rate'=>25],
                    ['from'=>2000001, 'to'=>8000000, 'base'=>402500, 'rate'=>30],
                    ['from'=>8000001, 'to'=>PHP_INT_MAX, 'base'=>2202500, 'rate'=>35],
                ]
            ]);
            $taxSetting->save();
        }
        $taxBrackets = \App\Models\TaxBracket::all();

        return view('hr.deductions.index', compact(
            'lateAbsence',
            'sssBrackets',
            'phicSetting',
            'hdmfSetting',
            'sssLoanSetting',
            'hdmfLoanSetting',
            'taxBrackets',
            'taxSetting'
        ));
    }

    public function update(Request $request, DeductionSetting $deduction)
    {
        if ($deduction->deduction_type === 'late_absences') {
            $request->validate([
                'days' => 'required|numeric|min:0'
            ]);
    
            $deduction->settings = json_encode([
                'days' => $request->days
            ]);
    
            $deduction->save();
            $message = 'Late & Absences updated.';
    
        } elseif ($deduction->deduction_type === 'phic') {
            $request->validate([
                'rate' => 'required|numeric|min:0|max:100',
                'min_salary' => 'required|numeric|min:0',
                'max_salary' => 'required|numeric|min:0|gte:min_salary'
            ]);
    
            $deduction->settings = json_encode([
                'rate' => $request->rate,
                'min_salary' => $request->min_salary,
                'max_salary' => $request->max_salary
            ]);
    
            $deduction->save();
            $message = 'PHIC Contribution updated.';
    
        } elseif ($deduction->deduction_type === 'hdmf') {
            $request->validate([
                'employee' => 'required|numeric|min:0',
                'employer' => 'required|numeric|min:0',
            ]);
    
            $deduction->settings = json_encode([
                'employee' => $request->employee,
                'employer' => $request->employer,
            ]);
    
            $deduction->save();
            $message = 'HDMF Contribution updated.';
    
        } elseif ($deduction->deduction_type === 'income_tax') {
            $brackets = $request->input('brackets', []);
    
            // Clear old tax brackets
            \App\Models\TaxBracket::truncate();
    
            foreach ($brackets as $b) {
                \App\Models\TaxBracket::create([
                    'from' => floatval($b['from']),
                    'to' => floatval($b['to']),
                    'percentage' => floatval($b['percentage']),
                    'fixed_amount' => floatval($b['fixed_amount']),
                ]);
            }
    
            // ⚠️ Huwag nang $deduction->save()
            $message = 'Tax successfully updated!';
        }
    
        return redirect()->route('deductions.index')->with('success', $message);
    }
    
    public function storeSSS(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|numeric|min:0',
            'to' => 'required|numeric|min:0|gte:from',
            'er' => 'required|numeric|min:0',
            'ee' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'others' => 'nullable|string|max:255'
        ]);
    
        SSSBracket::create($validated);
    
        return redirect()->route('deductions.index')
                         ->with('success', 'SSS bracket created successfully');
    }  
    
    public function updateSSS(Request $request)
    {
        $validated = $request->validate([
            'brackets' => 'required|array',
            'brackets.*.id' => 'nullable|exists:sss_brackets,id', // Optional kung may id
            'brackets.*.from' => 'required|numeric|min:0',
            'brackets.*.to' => 'required|numeric|min:0|gte:brackets.*.from',
            'brackets.*.er' => 'required|numeric|min:0',
            'brackets.*.ee' => 'required|numeric|min:0',
            'brackets.*.total' => 'required|numeric|min:0',
            'brackets.*.others' => 'nullable|string|max:255',
        ]);
    
        try {
            foreach ($validated['brackets'] as $bracketData) {
                if (!empty($bracketData['id'])) {
                    // Update existing record
                    $bracket = SSSBracket::findOrFail($bracketData['id']);
                    $bracket->update([
                        'from'   => $bracketData['from'],
                        'to'     => $bracketData['to'],
                        'er'     => $bracketData['er'],
                        'ee'     => $bracketData['ee'],
                        'total'  => $bracketData['total'],
                        'others' => $bracketData['others'] ?? null,
                    ]);
                } else {
                    // Create new record
                    SSSBracket::create([
                        'from'   => $bracketData['from'],
                        'to'     => $bracketData['to'],
                        'er'     => $bracketData['er'],
                        'ee'     => $bracketData['ee'],
                        'total'  => $bracketData['total'],
                        'others' => $bracketData['others'] ?? null,
                    ]);
                }
            }
    
            return redirect()
                ->route('deductions.index')
                ->with('success', 'SSS brackets updated successfully');
        } catch (\Exception $e) {
            \Log::error('Error updating SSS brackets: ' . $e->getMessage());
    
            return redirect()
                ->back()
                ->with('error', 'Failed to update SSS brackets')
                ->withInput();
        }
    }
    
    public function destroySSS(Request $request, $id)
    {
        try {
            $sssBracket = SSSBracket::findOrFail($id); // Replace with your actual model
            $sssBracket->delete();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'SSS bracket deleted successfully']);
            }

            return redirect()->back()->with('success', 'SSS bracket deleted successfully');
            
        } catch (\Exception $e) {
            \Log::error('Error deleting SSS bracket: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to delete SSS bracket'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to delete SSS bracket');
        }
    }
    
}
