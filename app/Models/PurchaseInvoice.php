<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'date',
        'due_date',
        'total',
        'status', // draft, paid, overdue
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function journalEntries()
    {
        return $this->morphMany(JournalEntry::class, 'journalable');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            // ambil tanggal saat ini
            $date = now()->format('dmy'); // contoh 081025

            // hitung jumlah invoice hari ini
            $count = self::whereDate('created_at', now()->toDateString())->count() + 1;

            // format nomor urut jadi 3 digit
            $seq = str_pad($count, 3, '0', STR_PAD_LEFT);

            // gabungkan jadi nomor invoice
            $model->invoice_number = "{$seq}/Purchase/{$date}";
        });
    }
}
