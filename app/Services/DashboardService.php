<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    protected DashboardRepository $repo;

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Kumpulkan semua data untuk dashboard.
     */
    public function getDashboardData(int $year, int $month): array
    {
        $user = Auth::user();

        // 1) KPI Cards
        $stats = [
            'totalHouses'    => $this->repo->getTotalHouses(),
            'totalResidents' => $this->repo->getTotalResidents(),
            'monthIncome'    => $this->repo->getMonthlyIncome($year, $month),
            'monthExpense'   => $this->repo->getMonthlyExpense($year, $month),
            'dueThisWeek'    => $this->repo->getDueThisWeek($year, $month),
        ];

        // 2) Annual & balance series
        $annual   = $this->repo->getAnnualData($year);
        $balances = $this->computeBalances($annual);

        // 3) Recent activity
        $recentPayments = $this->repo->getRecentPayments(5);
        $recentExpenses = $this->repo->getRecentExpenses(5);

        // 4) Overdues & agendas
        $overdues = $this->repo->getOverdues($year, $month);
        $agendas  = $this->repo->getAgendas();

        // 5) Report links (ubah sesuai route Anda)
        $reports = [
            'monthly' => route('reports.monthly', ['year' => $year, 'month' => $month]),
            'annual'  => route('reports.annual',  ['year' => $year]),
        ];

        $response = [
            'user'     => ['name' => $user->name],
            'stats'    => $stats,
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

        // 6) Optional: kategori per bulan
        if ($requestMonth = $month) {
            $response['monthly'] = [
                'income_categories'  => $this->repo->getIncomeCategories($year, $month),
                'expense_categories' => $this->repo->getExpenseCategories($year, $month),
            ];
        }

        return $response;
    }

    /**
     * Hitung saldo kumulatif per bulan.
     */
    protected function computeBalances(array $annual): array
    {
        $running = 0;
        return array_map(function ($item) use (&$running) {
            $running += $item['income'] - $item['expense'];
            return [
                'month'   => $item['month'],
                'balance' => $running,
            ];
        }, $annual);
    }
}
