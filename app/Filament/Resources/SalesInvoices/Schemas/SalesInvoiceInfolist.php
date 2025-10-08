<?php

namespace App\Filament\Resources\SalesInvoices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SalesInvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('invoice_number'),
                // TextEntry::make('customer_id')
                //     ->numeric(),
                // TextEntry::make('date')
                //     ->date(),
                // TextEntry::make('due_date')
                //     ->date()
                //     ->placeholder('-'),
                // TextEntry::make('total')
                //     ->numeric(),
                // TextEntry::make('status')
                //     ->badge(),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
            ]);
    }
}
