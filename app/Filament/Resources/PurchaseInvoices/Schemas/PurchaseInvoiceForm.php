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
                        Grid::make(2)
                            ->schema([
                                Select::make('account_id')
                                    ->label('Account')
                                    ->required()
                                    ->relationship(name: 'account', titleAttribute: 'name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('supplier_id')
                                    ->required()
                                    ->relationship(name: 'supplier', titleAttribute: 'name')
                                    ->searchable()
                                    ->preload(),

                            ]),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('price')
                                    ->label('Harga')
                                    ->required()
                                    ->prefix('Rp')
                                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)
                                    ->reactive()
                                    ->afterStateUpdated(
                                        fn($state, callable $set, $get) =>
                                        $set('total', (int)$state * (int)$get('qty'))
                                    ),
                                TextInput::make('qty')
                                    ->label('Qty')
                                    ->required()
                                    ->minLength(1)
                                    ->maxLength(3)
                                    ->minValue(0)
                                    ->maxValue(999)
                                    ->default(1)
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(
                                        fn($state, callable $set, $get) =>
                                        $set('total', (int)$get('price') * (int)$state)
                                    ),
                                TextInput::make('total')
                                    ->label('Total')
                                    ->required()
                                    ->prefix('Rp')
                                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)
                                    ->readOnly(),
                                DatePicker::make('date')
                                    ->label('Tanggal')
                                    ->required(),
                                DatePicker::make('due_date')
                                    ->label('Tanggal Expired'),
                                Select::make('status')
                                    ->label('Status')
                                    ->options(['draft' => 'Draft', 'paid' => 'Paid', 'overdue' => 'Overdue'])
                                    ->default('draft')
                                    ->required(),
                            ]),
                        TextInput::make('ket')
                            ->label('Keterangan')
                            ->required()
                            ->maxLength(255)

                    ])
                    ->columnSpanFull(),
            ]);
    }
}
