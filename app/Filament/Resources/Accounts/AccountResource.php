<?php

namespace App\Filament\Resources\Accounts;

use UnitEnum;
use BackedEnum;
use App\Models\Account;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\TextInput\Mask;
use App\Filament\Resources\Accounts\Pages\ManageAccounts;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'Account';
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->numeric()
                    ->maxLength(9),
                TextInput::make('name')
                    ->label('Nama Akun')
                    ->required()
                    ->maxLength(60),
                Select::make('type')
                    ->label('Tipe Akun')
                    ->required()
                    ->options([
                        'asset' => 'Aset',
                        'liability' => 'Beban',
                        'equity' => 'Ekuitas',
                        'income' => 'Penghasilan',
                        'expense' => 'Pengeluaran'
                    ]),

                TextInput::make('opening_balance')
                    ->label('Saldo Awal')
                    ->prefix('Rp')
                    ->numeric()
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)

            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code')
                    ->label('Kode'),
                TextEntry::make('name')
                    ->label('Akun'),
                TextEntry::make('type')
                    ->label('Tipe'),
                TextEntry::make('opening_balance')
                    ->label('Saldo')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Akun')
                    ->searchable(),
                TextColumn::make('opening_balance')
                    ->label('Saldo')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAccounts::route('/'),
        ];
    }
}
