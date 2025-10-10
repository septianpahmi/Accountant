<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'opening_balance',
    ];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function SalesInvoice()
    {
        return $this->hasMany(SalesInvoice::class);
    }
    public function PurchaseInvoice()
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    public function getBalanceByPeriod($startDate = null, $endDate = null): float
    {
        // pastikan parsing tanggal
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        // buat dua query terpisah agar filter tidak saling mempengaruhi
        $debitQuery = $this->journalEntries()
            ->where('account_id', $this->id);

        $creditQuery = $this->journalEntries()
            ->where('account_id', $this->id);
        // dd($debitQuery);
        // apply period filter via relationship 'journal' (kolom journals.date)
        if ($start) {
            $debitQuery->whereHas('journal', fn($q) => $q->whereDate('created_at', '>=', $start->toDateString()));
            $creditQuery->whereHas('journal', fn($q) => $q->whereDate('created_at', '>=', $start->toDateString()));
        }

        if ($end) {
            $debitQuery->whereHas('journal', fn($q) => $q->whereDate('created_at', '<=', $end->toDateString()));
            $creditQuery->whereHas('journal', fn($q) => $q->whereDate('created_at', '<=', $end->toDateString()));
        }

        $debit = (float) $debitQuery->sum('total');
        $credit = (float) $creditQuery->sum('total');

        // saldo = saldo awal + (debit - kredit)
        $opening = (float) $this->opening_balance;

        return $opening + ($debit - $credit);
    }
}
