<?php

namespace App\Filament\Resources\Accounts;

use UnitEnum;
use BackedEnum;
use Carbon\Carbon;
use App\Models\Account;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
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
                    ->default(0)
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
                TextColumn::make('saldo')
                    ->label('Saldo Periode')
                    ->getStateUsing(function ($record, $livewire) {
                        $start = $livewire->tableFilters['periode']['data']['start_date'] ?? null;
                        $end = $livewire->tableFilters['periode']['data']['end_date'] ?? null;
                        return 'Rp ' . number_format($record->getBalanceByPeriod($start, $end), 0, ',', '.');
                    }),
            ])
            ->filters([
                Filter::make('periode')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Dari Tanggal')
                            ->default(Carbon::today()),
                        DatePicker::make('end_date')
                            ->label('Sampai Tanggal')
                            ->default(Carbon::today()),
                    ])
                    ->query(function ($query, array $data) {
                        $start = $data['start_date'] ?? null;
                        $end = $data['end_date'] ?? null;
                        return $query
                            ->when($start, fn($q) => $q->whereDate('created_at', '>=', $start))
                            ->when($end, fn($q) => $q->whereDate('created_at', '<=', $end));
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (isset($data['start_date'], $data['end_date'])) {
                            return 'Periode: ' . Carbon::parse($data['start_date'])->format('d/m/Y')
                                . ' — ' . Carbon::parse($data['end_date'])->format('d/m/Y');
                        }

                        return 'Periode: Hari ini';
                    }),
            ])
            ->header(
                fn($livewire) =>
                view('filament.account-period-header', [
                    'start' => $livewire->tableFilters['periode']['data']['start_date'] ?? null,
                    'end' => $livewire->tableFilters['periode']['data']['end_date'] ?? null,
                ])
            )
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
