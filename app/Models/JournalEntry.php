<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'journal_id',
        'account_id',
        'type',   // debit atau credit
        'amount',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Relasi polymorphic agar bisa nyambung ke faktur penjualan/pembelian
    public function journalable()
    {
        return $this->morphTo();
    }
}
