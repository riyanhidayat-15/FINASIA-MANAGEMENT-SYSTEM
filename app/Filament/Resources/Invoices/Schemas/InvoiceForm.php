<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Invoice')
                    ->columns(2)
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label('No. Invoice')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('order_id')
                            ->label('Order')
                            ->relationship('order', 'name')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('invoice_date')
                            ->label('Tanggal Invoice')
                            ->required(),

                        TextInput::make('po_number')
                            ->label('No. PO')
                            ->nullable(),

                        TextInput::make('periode')
                            ->label('Periode')
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'belum_masuk' => 'Belum Masuk',
                                'sudah_masuk' => 'Sudah Masuk',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('belum_masuk')
                            ->required(),
                    ]),

                Section::make('Rincian Pembayaran')
                    ->columns(2)
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('discount_percentage')
                            ->label('Diskon (%)')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),

                        TextInput::make('discount_amount')
                            ->label('Nominal Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        TextInput::make('total_amount')
                            ->label('Total')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('amount_in_words')
                            ->label('Terbilang')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}