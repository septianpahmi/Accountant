<?php

namespace App\Filament\Resources\JournalEntries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JournalEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('journal_id')
                    ->required()
                    ->numeric(),
                TextInput::make('account_id')
                    ->required()
                    ->numeric(),
                Select::make('type')
                    ->options(['debit' => 'Debit', 'credit' => 'Credit'])
                    ->required(),
                TextInput::make('qty')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
                TextInput::make('journalable_type'),
                TextInput::make('journalable_id')
                    ->numeric(),
            ]);
    }
}
