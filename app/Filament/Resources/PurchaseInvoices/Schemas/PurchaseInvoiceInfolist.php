<?php

namespace App\Filament\Resources\PurchaseInvoices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PurchaseInvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }
}
