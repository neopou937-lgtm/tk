<?php

namespace App\Services\Admin;

use App\Models\Alumni;
use App\Models\Bill;
use App\Models\BopTransaction;
use App\Models\PpdbApplication;
use App\Models\SavingsTransaction;
use App\Models\Student;
use App\Models\Teacher;

class AdminDashboardService
{
    /**
     * @return array<string, int|float>
     */
    public function stats(): array
    {
        return [
            'students' => Student::where('status', 'aktif')->count(),
            'teachers' => Teacher::where('status', 'aktif')->count(),
            'pendingBills' => Bill::whereIn('status', ['pending', 'partial'])->count(),
            'paidPayments' => Bill::where('status', 'paid')->count(),
            'savings' => $this->savingsBalance(),
            'bopFunds' => $this->bopBalance(),
            'pendingPpdb' => PpdbApplication::where('status', 'pending')->count(),
            'alumni' => Alumni::count(),
        ];
    }

    public function savingsBalance(): float
    {
        $setor = (float) SavingsTransaction::where('type', 'setor')->sum('amount');
        $tarik = (float) SavingsTransaction::where('type', 'tarik')->sum('amount');

        return $setor - $tarik;
    }

    public function bopBalance(): float
    {
        $debit = (float) BopTransaction::where('type', 'debit')->sum('amount');
        $kredit = (float) BopTransaction::where('type', 'kredit')->sum('amount');

        return $debit - $kredit;
    }

    /**
     * @return array<string, float>
     */
    public function billSummary(): array
    {
        return [
            'amount' => (float) Bill::sum('amount'),
            'paid' => (float) Bill::sum('paid_amount'),
            'remaining' => (float) Bill::selectRaw('SUM(amount - paid_amount) as total')->value('total'),
        ];
    }
}
