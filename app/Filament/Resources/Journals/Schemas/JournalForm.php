<?php

namespace App\Filament\Resources\Journals\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;

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
                            ->maxLength(255),

                        Repeater::make('entries')
                            ->relationship()
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
                                TextInput::make('amount')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->minItems(2) // biasanya debit & kredit
                            ->required(),
                    ])->columnSpanFull(),
            ]);
    }
}
