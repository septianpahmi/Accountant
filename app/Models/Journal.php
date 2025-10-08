<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'invoice_number',
        'date',
        'description',
    ];

    public function entries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $date = now()->format('dmy');

            $last = DB::table('journals')
                ->whereDate('created_at', now()->toDateString())
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $seq = $last
                ? ((int) explode('/', $last->invoice_number)[0] + 1)
                : 1;

            $seq = str_pad($seq, 3, '0', STR_PAD_LEFT);
            $model->invoice_number = "{$seq}/Journal/{$date}";
        });
    }
}
