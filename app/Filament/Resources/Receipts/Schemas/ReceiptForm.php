<?php

namespace App\Filament\Resources\Receipts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReceiptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kwitansi')
                    ->columns(2)
                    ->schema([
                        TextInput::make('receipt_number')
                            ->label('No. Kwitansi')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('invoice_id')
                            ->label('Invoice')
                            ->relationship('invoice', 'invoice_number')
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('receipt_date')
                            ->label('Tanggal Kwitansi')
                            ->required(),

                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('amount_in_words')
                            ->label('Terbilang')
                            ->columnSpanFull(),

                        TextInput::make('payment_purpose')
                            ->label('Untuk Pembayaran')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}