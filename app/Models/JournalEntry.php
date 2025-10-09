<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'journal_id',
        'account_id',
        'type',   // debit atau credit
        'qty',
        'price',
        'total',
        'journalable_id',
        'journalable_type',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function journalable()
    {
        return $this->morphTo();
    }
}
