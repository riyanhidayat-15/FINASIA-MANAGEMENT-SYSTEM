<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Items Order')
                    ->columnSpan(2)
                    ->schema([
                        Repeater::make('items')
                            ->label('')
                            ->relationship()
                            ->disabled(fn ($record) => $record?->invoice()->exists())
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Item')
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('quantity')
                                    ->label('Qty')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(),

                                Select::make('unit')
                                    ->label('Unit')
                                    ->options([
                                        'pcs'  => 'PCS',
                                        'kg'   => 'KG',
                                        'g'    => 'G',
                                        'box'  => 'BOX',
                                        'pack' => 'PACK',
                                    ])
                                    ->required(),

                                TextInput::make('unit_price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->live(),

                                Placeholder::make('subtotal')
                                    ->label('Subtotal')
                                    ->content(function ($get): string {
                                        $qty = (float) ($get('quantity') ?? 0);
                                        $price = (float) ($get('unit_price') ?? 0);

                                        return 'Rp ' . number_format($qty * $price, 0, ',', '.');
                                    }),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('+ Tambah Item')
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Informasi Order')
                            ->disabled(fn ($record) => $record?->invoice()->exists())
                            ->schema([
                                Select::make('customer_id')
                                    ->label('Customer')
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('name')
                                    ->label('Nama Order')
                                    ->required(),

                                DatePicker::make('delivery_date')
                                    ->label('Tanggal Kirim')
                                    ->required(),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'process' => 'Process',
                                        'done' => 'Done',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('process')
                                    ->required(),

                                Textarea::make('notes')
                                    ->label('Catatan'),
                            ]),

                        Section::make('Ringkasan')
                            ->schema([
                                Placeholder::make('total')
                                    ->label('Total Pembayaran')
                                    ->content(function ($get): string {
                                        $items = $get('items') ?? [];

                                        $total = collect($items)->sum(function ($item) {
                                            return (float) ($item['quantity'] ?? 0) * (float) ($item['unit_price'] ?? 0);
                                        });

                                        return 'Rp ' . number_format($total, 0, ',', '.');
                                    }),
                            ]),
                    ]),

            ]);
    }
}