<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'date',
        'due_date',
        'account_id',
        'qty',
        'price',
        'total',
        'ket',
        'status', // draft, paid, overdue
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function journalEntries()
    {
        return $this->morphMany(JournalEntry::class, 'journalable');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $date = now()->format('dmy');
            $count = self::whereDate('created_at', now()->toDateString())->count() + 1;
            $seq = str_pad($count, 3, '0', STR_PAD_LEFT);
            $model->invoice_number = "{$seq}/Sales/{$date}";
        });
    }
}
