<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year  = $request->query('year', Carbon::now()->year);
        $month = $request->query('month');

        // 1) Agregasi tahunan: grup by bulan (1–12)
        $incomes = DB::table('payments')
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->whereYear('payment_date', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $expenses = DB::table('expenses')
            ->selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->whereYear('expense_date', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Siapkan default 0 untuk bulan yang tidak ada datanya
        $annual = collect(range(1, 12))->map(function ($m) use ($incomes, $expenses) {
            return [
                'month'   => $m,
                'income'  => $incomes[$m]  ?? 0,
                'expense' => $expenses[$m] ?? 0,
            ];
        });

        $response = ['annual' => $annual];

        // 2) Jika parameter month dikirim, tambahkan laporan bulanan per kategori
        if ($month) {
            $monthlyIncomeByCategory = DB::table('payments')
                ->select('category', DB::raw('SUM(amount) as total'))
                ->whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->groupBy('category')
                ->get();

            $monthlyExpenseByCategory = DB::table('expenses')
                ->select('category', DB::raw('SUM(amount) as total'))
                ->whereYear('expense_date', $year)
                ->whereMonth('expense_date', $month)
                ->groupBy('category')
                ->get();

            $response['monthly'] = [
                'income_categories'  => $monthlyIncomeByCategory,
                'expense_categories' => $monthlyExpenseByCategory,
            ];
        }

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
