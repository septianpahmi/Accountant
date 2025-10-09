<?php

namespace App\Filament\Resources\Journals\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Infolists\Components\RepeatableEntry;

class JournalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Jurnal')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('invoice_number')
                                    ->label('Nomor Invoice')
                                    ->weight('medium')
                                    ->color('primary'),

                                TextEntry::make('date')
                                    ->label('Tanggal')
                                    ->date('d M Y')
                                    ->icon('heroicon-o-calendar'),

                                TextEntry::make('description')
                                    ->label('Keterangan')
                                    ->placeholder('-'),
                            ])
                    ])
                    ->icon('heroicon-o-clipboard-document')
                    ->columnSpanFull(),

                Section::make('Daftar Entri Jurnal (Default)')
                    ->visible(fn($record) => $record->entries->every(fn($entry) => $entry->journalable_id === null))
                    ->schema([
                        RepeatableEntry::make('entries')
                            ->label('Rincian Transaksi')
                            ->schema([
                                TextEntry::make('account.code')
                                    ->label('Kode Akun')
                                    ->badge(),
                                TextEntry::make('account.name')
                                    ->label('Akun')
                                    ->weight('medium'),
                                TextEntry::make('type')
                                    ->label('Tipe')
                                    ->badge()
                                    ->color(fn(string $state): string => $state === 'debit' ? 'success' : 'danger')
                                    ->formatStateUsing(fn($state) => ucfirst($state)),
                                TextEntry::make('debit')
                                    ->label('Debit')
                                    ->state(fn($record) => $record->type === 'debit' ? $record->total : null)
                                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : ''),
                                TextEntry::make('credit')
                                    ->label('Kredit')
                                    ->state(fn($record) => $record->type === 'credit' ? $record->total : null)
                                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : ''),


                            ])
                            ->columns(5)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),
                Section::make('Ringkasan Total')
                    ->visible(fn($record) => $record->entries->every(fn($entry) => $entry->journalable_id === null))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('total_debit')
                                    ->label('Total Debit')
                                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '')
                                    ->state(fn($record) => $record->entries?->where('type', 'debit')->sum('total') ?? 0)
                                    ->color('success')
                                    ->weight('medium'),

                                TextEntry::make('total_credit')
                                    ->label('Total Kredit')
                                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '')
                                    ->state(fn($record) => $record->entries?->where('type', 'credit')->sum('total') ?? 0)
                                    ->color('danger')
                                    ->weight('medium'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->state(function ($record) {
                                        $debit = $record->entries?->where('type', 'debit')->sum('total') ?? 0;
                                        $credit = $record->entries?->where('type', 'credit')->sum('total') ?? 0;

                                        return $debit === $credit ? 'Balance' : 'Not Balance';
                                    })
                                    ->badge()
                                    ->color(fn($state) => $state === 'Balance' ? 'success' : 'danger'),

                            ])
                    ])
                    ->icon('heroicon-o-calculator')
                    ->columnSpanFull(),
                //journalable_id only
                Section::make('Daftar Entri Jurnal (Journalable)')
                    ->visible(fn($record) => $record->entries->contains(fn($entry) => $entry->journalable_id !== null))
                    ->schema([
                        RepeatableEntry::make('entries')
                            ->label('Rincian Transaksi')
                            ->schema([
                                TextEntry::make('account.code')
                                    ->label('Kode Akun')
                                    ->badge(),
                                TextEntry::make('account.name')
                                    ->label('Akun'),
                                TextEntry::make('price')
                                    ->label('Harga')
                                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : ''),
                                TextEntry::make('qty')
                                    ->label('Qty'),
                                TextEntry::make('total')
                                    ->label('Total')
                                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : ''),

                            ])
                            ->columns(5)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),


            ]);
    }
}
