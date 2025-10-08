<?php

namespace App\Filament\Resources\JournalEntries\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Tables\Grouping\Group;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Contracts\Database\Query\Builder;

class JournalEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('journal.invoice_number')
                    ->label('No Invoice')
                    ->collapsible(),
                Group::make('journal.date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->collapsible()
            ])
            ->defaultGroup('journal.invoice_number')
            ->columns([
                TextColumn::make('account.name')
                    ->sortable(),
                TextColumn::make('journal.description')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('debit')
                    ->label('Debit')
                    ->alignRight()
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state ?: 0, 0, ',', '.'))
                    ->summarize(
                        Sum::make()
                            ->prefix('Rp. ')
                            ->label('Total Debit')
                    ),

                TextColumn::make('credit')
                    ->label('Kredit')
                    ->alignRight()
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state ?: 0, 0, ',', '.'))
                    ->summarize(
                        Sum::make()
                            ->prefix('Rp. ')
                            ->label('Total Kredit')
                    ),


            ])
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
