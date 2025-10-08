<?php

namespace App\Filament\Resources\Journals\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class JournalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Jurnal Umum')
                    ->schema([
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required(),
                        TextInput::make('description')
                            ->label('Keterangan')
                            ->required()
                            ->maxLength(255),

                        Repeater::make('entries')
                            ->relationship()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('account_id')
                                            ->label('Akun')
                                            ->relationship('account', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Select::make('type')
                                            ->label('Tipe')
                                            ->options([
                                                'debit' => 'Debit',
                                                'credit' => 'Kredit',
                                            ])
                                            ->required(),
                                    ]),
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('price')
                                            ->label('Harga')
                                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)
                                            ->prefix('Rp')
                                            ->required()
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
                                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)
                                            ->prefix('Rp')
                                            ->required(),
                                    ]),
                            ])
                            ->itemLabel('Journal Item')
                            ->minItems(2) // biasanya debit & kredit
                            ->required(),
                    ])->columnSpanFull(),
            ]);
    }
}
