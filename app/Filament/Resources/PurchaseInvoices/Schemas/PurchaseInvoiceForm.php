<?php

namespace App\Filament\Resources\PurchaseInvoices\Schemas;

use Filament\Support\RawJs;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class PurchaseInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Faktur Pembelian')
                    ->description('Gunakan untuk mencatat setiap transaksi pemebelian agar laporan tetap akurat dan rapi.')
                    ->schema([
                        Select::make('supplier_id')
                            ->required()
                            ->relationship(name: 'supplier', titleAttribute: 'name')
                            ->searchable()
                            ->preload(),
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Tanggal')
                                    ->required(),
                                DatePicker::make('due_date')
                                    ->label('Tanggal Expired'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('total')
                                    ->label('Total')
                                    ->required()
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters([',']),
                                Select::make('status')
                                    ->label('Status')
                                    ->options(['draft' => 'Draft', 'paid' => 'Paid', 'overdue' => 'Overdue'])
                                    ->default('draft')
                                    ->required(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
