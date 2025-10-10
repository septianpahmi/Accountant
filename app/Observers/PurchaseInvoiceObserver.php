<?php

namespace App\Observers;

use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\PurchaseInvoice;

class PurchaseInvoiceObserver
{
    /**
     * Handle the PurchaseInvoice "created" event.
     */
    public function created(PurchaseInvoice $purchaseInvoice): void
    {
        if ($purchaseInvoice->status === 'paid') {
            $journal = Journal::create([
                'date' => $purchaseInvoice->date,
                'description' => $purchaseInvoice->ket,
            ]);

            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $purchaseInvoice->account_id,
                'type'       => 'credit',
                'qty'     => $purchaseInvoice->qty,
                'price'     => $purchaseInvoice->price,
                'total'      =>  $purchaseInvoice->total,
                'journalable_id'   => $purchaseInvoice->id,
                'journalable_type' => PurchaseInvoice::class,

            ]);
            $debitAccountId = $purchaseInvoice->account_id;
            $debitAccount = \App\Models\Account::find($debitAccountId);
            if ($debitAccount) {
                $debitAccount->increment('opening_balance', $purchaseInvoice->total);
            }
        }
    }

    /**
     * Handle the PurchaseInvoice "updated" event.
     */
    public function updated(PurchaseInvoice $purchaseInvoice): void
    {
        //
    }

    /**
     * Handle the PurchaseInvoice "deleted" event.
     */
    public function deleted(PurchaseInvoice $purchaseInvoice): void
    {
        //
    }

    /**
     * Handle the PurchaseInvoice "restored" event.
     */
    public function restored(PurchaseInvoice $purchaseInvoice): void
    {
        //
    }

    /**
     * Handle the PurchaseInvoice "force deleted" event.
     */
    public function forceDeleted(PurchaseInvoice $purchaseInvoice): void
    {
        //
    }
}
