<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $year  = $request->query('year',   Carbon::now()->year);
        $month = $request->query('month',  Carbon::now()->month);

        // 1) KPI Cards
        $totalHouses    = DB::table('houses')->count();
        $totalResidents = DB::table('residents')->count();
        $monthIncome    = DB::table('payments')
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->sum('amount');
        $monthExpense   = DB::table('expenses')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');
        $dueThisWeek = DB::table('payments')
            ->where('status', 'unpaid')
            ->where('year',   $year)
            ->where('month',  $month)
            ->count();

        // 2) Annual aggregation
        $incomes = DB::table('payments')
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->whereYear('payment_date', $year)
            ->groupByRaw('MONTH(payment_date)')
            ->pluck('total', 'month')
            ->toArray();

        $expenses = DB::table('expenses')
            ->selectRaw('MONTH(`date`) as month, SUM(amount) as total')
            ->whereYear('date', $year)
            ->groupByRaw('MONTH(`date`)')
            ->pluck('total', 'month')
            ->toArray();

        $annual = collect(range(1, 12))->map(fn($m) => [
            'month'   => $m,
            'income'  => $incomes[$m] ?? 0,
            'expense' => $expenses[$m] ?? 0,
        ])->all();

        // 3) Cumulative balance series
        $running = 0;
        $balances = array_map(function ($row) use (&$running) {
            $running += $row['income'] - $row['expense'];
            return ['month' => $row['month'], 'balance' => $running];
        }, $annual);

        // 4) Recent activity
        $recentPayments = DB::table('payments')
            ->join('residents', 'payments.resident_id', '=', 'residents.id')
            ->select([
                'payments.payment_date',
                'payments.house_id',
                'residents.full_name as resident_name',
                'payments.amount',
            ])
            ->orderByDesc('payments.payment_date')
            ->limit(5)
            ->get();
        $recentExpenses = DB::table('expenses')
            ->select('date as expense_date', 'category', 'description', 'amount')
            ->orderByDesc('date')
            ->limit(5)
            ->get();

        // 5) Overdues & agendas
        $overdues = DB::table('payments')
            ->where('status', 'unpaid')
            ->where(function ($q) {
                $now = Carbon::now();
                $q->whereYear('payment_date', '<', $now->year)
                    ->orWhere(function ($q2) use ($now) {
                        $q2->whereYear('payment_date', $now->year)
                            ->whereMonth('payment_date', '<', $now->month);
                    });
            })
            ->join('residents', 'payments.resident_id', '=', 'residents.id')
            ->select([
                'payments.house_id',
                'residents.full_name as resident_name',
                DB::raw('payments.payment_date as due_date'),
                'payments.amount',
            ])
            ->get();
        $agendas = [];

        // 6) Report links
        $reports = [
            'monthly' => "monthly",
            'annual'  => "yearly",
        ];

        // 7) (opsional) kategori bulanan jika front-end kirim month
        $monthly = null;
        if ($request->has('month')) {
            $monthly = [
                'income_categories' => DB::table('payments')
                    ->select('category', DB::raw('SUM(amount) as total'))
                    ->whereYear('payment_date', $year)
                    ->whereMonth('payment_date', $month)
                    ->groupBy('category')
                    ->get(),
                'expense_categories' => DB::table('expenses')
                    ->select('category', DB::raw('SUM(amount) as total'))
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->groupBy('category')
                    ->get(),
            ];
        }

        // Full Response
        $response = [
            'user'     => ['name' => $user->name],
            'stats'    => [
                'totalHouses'   => $totalHouses,
                'totalResidents' => $totalResidents,
                'monthIncome'   => $monthIncome,
                'monthExpense'  => $monthExpense,
                'dueThisWeek'   => $dueThisWeek,
            ],
            'annual'   => $annual,
            'balances' => $balances,
            'recent'   => [
                'payments' => $recentPayments,
                'expenses' => $recentExpenses,
            ],
            'overdues' => $overdues,
            'agendas'  => $agendas,
            'reports'  => $reports,
        ];

        if ($monthly) {
            $response['monthly'] = $monthly;
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
