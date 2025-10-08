<?php

namespace App\Models;

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
}
