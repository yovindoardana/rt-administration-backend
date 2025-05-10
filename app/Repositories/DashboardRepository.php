<?php

namespace App\Repositories;

use App\Models\House;
use App\Models\Resident;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardRepository
{
    public function getTotalHouses(): int
    {
        return Cache::remember('dashboard.total_houses', 3600, fn() => House::count());
    }

    public function getTotalResidents(): int
    {
        return Cache::remember('dashboard.total_residents', 3600, fn() => Resident::count());
    }

    /**
     * Monthly income & expense.
     */
    public function getMonthlyIncome(int $year, int $month): float
    {
        return Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->sum('amount');
    }

    public function getMonthlyExpense(int $year, int $month): float
    {
        return Expense::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');
    }

    /**
     * Get the count of unpaid payments due this week.
     */
    public function getDueThisWeek(int $year, int $month): int
    {
        return Payment::where('status', 'unpaid')
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->count();
    }

    /**
     * Get annual income and expense data for the specified year.
     */
    public function getAnnualData(int $year): array
    {
        $incomes = Payment::selectRaw('MONTH(payment_date) AS month, SUM(amount) AS total')
            ->whereYear('payment_date', $year)
            ->groupByRaw('MONTH(payment_date)')
            ->pluck('total', 'month')
            ->toArray();

        $expenses = Expense::selectRaw('MONTH(`date`) AS month, SUM(amount) AS total')
            ->whereYear('date', $year)
            ->groupByRaw('MONTH(`date`)')
            ->pluck('total', 'month')
            ->toArray();

        return collect(range(1, 12))
            ->map(fn($m) => [
                'month'   => $m,
                'income'  => $incomes[$m]  ?? 0,
                'expense' => $expenses[$m] ?? 0,
            ])
            ->toArray();
    }

    /**
     * Recent payments.
     */
    public function getRecentPayments(int $limit): array
    {
        return Payment::with(['resident', 'house'])
            ->orderByDesc('payment_date')
            ->take($limit)
            ->get()
            ->map(fn($p) => [
                'payment_date'  => $p->payment_date->toDateString(),
                'house_id'      => $p->house_id,
                'resident_name' => $p->resident->full_name,
                'amount'        => $p->amount,
            ])
            ->toArray();
    }

    /**
     * Recent expenses.
     */
    public function getRecentExpenses(int $limit): array
    {
        return Expense::orderByDesc('date')
            ->take($limit)
            ->get(['date as expense_date', 'category', 'description', 'amount'])
            ->toArray();
    }

    /**
     * Get the list of overdues (tunggakan).
     */
    public function getOverdues(int $year, int $month): array
    {
        $now = Carbon::now();

        return Payment::with('resident')
            ->where('status', 'unpaid')
            ->where(function ($q) use ($now) {
                $q->whereYear('payment_date', '<', $now->year)
                    ->orWhere(
                        fn($q2) => $q2
                            ->whereYear('payment_date', $now->year)
                            ->whereMonth('payment_date', '<', $now->month)
                    );
            })
            ->get()
            ->map(fn($p) => [
                'house_id'      => $p->house_id,
                'resident_name' => $p->resident->full_name,
                'due_date'      => $p->payment_date->toDateString(),
                'amount'        => $p->amount,
            ])
            ->toArray();
    }

    /**
     * Agenda.
     */
    public function getAgendas(): array
    {
        return [];
    }

    /**
     * Kategori pemasukan & pengeluaran per bulan.
     */
    public function getIncomeCategories(int $year, int $month): array
    {
        return Payment::select('category', DB::raw('SUM(amount) as total'))
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->groupBy('category')
            ->get()
            ->map(fn($row) => [
                'category' => $row->category,
                'total'    => $row->total,
            ])
            ->toArray();
    }

    public function getExpenseCategories(int $year, int $month): array
    {
        return Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->groupBy('category')
            ->get()
            ->map(fn($row) => [
                'category' => $row->category,
                'total'    => $row->total,
            ])
            ->toArray();
    }
}
