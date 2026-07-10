<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Services\InvoiceService;
use Filament\Actions\Action; 
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('delivery_date')
                    ->label('Tgl Kirim')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn ($record) => $record->delivery_time
                        ? \Carbon\Carbon::parse($record->delivery_time)->format('H:i')
                        : null)
                    ->color(function ($record) {
                        if ($record->status !== 'process') {
                            return null;
                        }

                        $today = now()->startOfDay();
                        $deliveryDate = \Carbon\Carbon::parse($record->delivery_date)->startOfDay();

                        if ($deliveryDate->lt($today)) {
                            return 'danger'; 
                        }

                        if ($deliveryDate->isToday()) {
                            return 'warning'; 
                        }

                        if ($deliveryDate->isTomorrow()) {
                            return 'info'; 
                        }

                        return null;
                    })
                    ->weight(function ($record) {
                        $today = now()->startOfDay();
                        $deliveryDate = \Carbon\Carbon::parse($record->delivery_date)->startOfDay();

                        return ($record->status === 'process' && $deliveryDate->lte($today->copy()->addDay()))
                            ? 'bold'
                            : 'normal';
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'process' => 'warning',
                        'done' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('markAsDone')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record): bool => $record->status === 'process')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai pesanan ini selesai?')
                    ->modalDescription(fn ($record) => "Order \"{$record->name}\" akan ditandai sebagai selesai dikirim.")
                    ->action(function ($record) {
                        $record->update(['status' => 'done']);

                        Notification::make()
                            ->title('Order ditandai selesai')
                            ->success()
                            ->send();
                    }),

                Action::make('generateInvoice')
                    ->label('Generate Invoice')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->visible(fn ($record): bool => $record->status === 'done' && ! $record->invoice)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(InvoiceService::class)->generateFromOrder($record);

                        Notification::make()
                            ->title('Invoice Berhasil Dibuat')
                            ->success()
                            ->send();
                    }),

                Action::make('viewInvoice')
                    ->label('Lihat Invoice')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->visible(fn ($record): bool => (bool) $record->invoice)
                    ->url(fn ($record) => $record->invoice
                        ? route('filament.admin.resources.invoices.edit', $record->invoice)
                        : null),

                Action::make('deleteInvoice')
                    ->label('Hapus Invoice')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record): bool => (bool) $record->invoice)
                    ->requiresConfirmation()
                    ->modalHeading('Hapus invoice terkait?')
                    ->modalDescription(fn ($record) => "Invoice \"{$record->invoice->invoice_number}\" akan dihapus.")
                    ->action(function ($record) {
                        $record->invoice->receipt?->delete();
                        $record->invoice->delete();

                        Notification::make()
                            ->title('Invoice Berhasil Dihapus')
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}