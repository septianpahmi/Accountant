<?php

namespace App\Filament\Resources\PurchaseInvoices\Tables;

use Filament\Tables\Table;
use App\Models\SalesInvoice;
use Filament\Actions\Action;
use App\Models\PurchaseInvoice;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\DB;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class PurchaseInvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Tanggal Expired')
                    ->date()
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('total')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'draft',   // biru
                        'success' => 'paid',    // hijau
                        'danger'  => 'overdue', // merah
                    ])
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('generateInvoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('danger')
                    ->url(fn($record) => route('purchaseInvoice', ['id' => $record->id]))
                    ->openUrlInNewTab(),
                EditAction::make(),
                Action::make('markAsPaid')
                    ->label('Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status !== 'paid')
                    ->action(function ($record) {
                        DB::transaction(function () use ($record) {
                            $record->update(['status' => 'paid']);
                            $journal = \App\Models\Journal::create([
                                'date' => $record->date,
                                'description' => $record->ket,
                            ]);
                            $debitAccountId = $record->account_id;

                            $journal->entries()->create([
                                'account_id' => $debitAccountId,
                                'type' => 'credit',
                                'price' => $record->price,
                                'qty' => $record->qty,
                                'total' => $record->total,
                                'journalable_id'   => $record->id,
                                'journalable_type' => PurchaseInvoice::class,
                            ]);

                            $debitAccount = \App\Models\Account::find($debitAccountId);
                            if ($debitAccount) {
                                $debitAccount->increment('opening_balance', $record->total);
                            }
                        });
                    })
                    ->after(function () {
                        Notification::make()
                            ->title('Faktur berhasil ditandai lunas dan saldo diperbarui')
                            ->success()
                            ->send();
                    }),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
