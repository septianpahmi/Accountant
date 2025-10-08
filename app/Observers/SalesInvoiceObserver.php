<?php

namespace App\Observers;

use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\SalesInvoice;

class SalesInvoiceObserver
{
    /**
     * Handle the SalesInvoice "created" event.
     */
    public function created(SalesInvoice $salesInvoice): void
    {
        $journal = Journal::create([
            'date' => $salesInvoice->date,
            'description' => 'Penjualan #' . $salesInvoice->id,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => 1,
            'type'       => 'debit',
            'amount'     => $salesInvoice->total,
            'journalable_id'   => $salesInvoice->id,
            'journalable_type' => SalesInvoice::class,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => 1,
            'type'       => 'credit',
            'amount'     => $salesInvoice->total,
            'journalable_id'   => $salesInvoice->id,
            'journalable_type' => SalesInvoice::class,
        ]);
    }

    /**
     * Handle the SalesInvoice "updated" event.
     */
    public function updated(SalesInvoice $salesInvoice): void
    {
        //
    }

    /**
     * Handle the SalesInvoice "deleted" event.
     */
    public function deleted(SalesInvoice $salesInvoice): void
    {
        //
    }

    /**
     * Handle the SalesInvoice "restored" event.
     */
    public function restored(SalesInvoice $salesInvoice): void
    {
        //
    }

    /**
     * Handle the SalesInvoice "force deleted" event.
     */
    public function forceDeleted(SalesInvoice $salesInvoice): void
    {
        //
    }
}
