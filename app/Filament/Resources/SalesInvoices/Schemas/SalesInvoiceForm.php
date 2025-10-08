<?php

namespace App\Filament\Resources\SalesInvoices\Schemas;

use Filament\Support\RawJs;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class SalesInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Faktur Penjualan')
                    ->description('Gunakan untuk mencatat setiap transaksi penjualan agar laporan tetap akurat dan rapi.')
                    ->schema([
                        Select::make('customer_id')
                            ->required()
                            ->relationship(name: 'customer', titleAttribute: 'name')
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
