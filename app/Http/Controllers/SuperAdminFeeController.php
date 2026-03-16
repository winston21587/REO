<?php

namespace App\Http\Controllers;

use App\Models\ResearchCategory;
use App\Models\Research_title;
use Illuminate\Http\Request;

class SuperAdminFeeController extends Controller
{
    public function manageFees()
    {
        $categories = ResearchCategory::orderBy('created_at', 'asc')->get();
        return view('super_admin.manage_fees', compact('categories'));
    }

    public function storeFee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fee' => 'required|numeric|min:0',
        ]);

        ResearchCategory::create([
            'name' => $request->name,
            'fee' => $request->fee,
            'active' => 1
        ]);

        return redirect()->back()->with('success', 'Research category added successfully.');
    }

    public function updateFee(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fee' => 'required|numeric|min:0',
        ]);

        $category = ResearchCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'fee' => $request->fee,
            'active' => $request->has('active') ? 1 : 0
        ]);

        return redirect()->back()->with('success', 'Research category updated successfully.');
    }

    public function destroyFee($id)
    {
        $category = ResearchCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Research category deleted successfully.');
    }

    public function revenueLogs(Request $request)
    {
        $query = Research_title::whereNotNull('category_fee_at_submission');

        // Optional Date Filters
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        if ($request->filled('exact_date')) {
            $query->whereDate('created_at', $request->exact_date);
        }

        // Calculate total revenue ONLY for submissions with a VERIFIED Official Receipt
        $revenueQuery = clone $query;
        $totalRevenue = $revenueQuery->where('is_or_verified', true)
                                     ->sum('category_fee_at_submission');

        $submissions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('super_admin.revenue_logs', compact('submissions', 'totalRevenue'));
    }
}
