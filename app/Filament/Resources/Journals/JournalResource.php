<?php

namespace App\Filament\Resources\Journals;

use BackedEnum;
use App\Models\Journal;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Journals\Pages\EditJournal;
use App\Filament\Resources\Journals\Pages\ViewJournal;
use App\Filament\Resources\Journals\Pages\ListJournals;
use App\Filament\Resources\Journals\Pages\CreateJournal;
use App\Filament\Resources\Journals\Schemas\JournalForm;
use App\Filament\Resources\Journals\Tables\JournalsTable;
use App\Filament\Resources\Journals\Schemas\JournalInfolist;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Journal';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('entries');
    }

    public static function form(Schema $schema): Schema
    {
        return JournalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JournalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JournalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJournals::route('/'),
            'create' => CreateJournal::route('/create'),
            'view' => ViewJournal::route('/{record}'),
            'edit' => EditJournal::route('/{record}/edit'),
        ];
    }
}
